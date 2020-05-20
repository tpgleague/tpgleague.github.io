<?php

function getTeamNameTagHistoric($tid, $unix_timestamp)
{
    global $db;

    $sql = <<<SQL
                SELECT SQL_SMALL_RESULT `name`, `tag`
                FROM `teams_names_changes`
                WHERE `tid` = ? AND `timestamp_gmt` <= FROM_UNIXTIME(?)
                ORDER BY timestamp_gmt DESC
                LIMIT 1
SQL;
    return $db->getRow($sql, array($tid, $unix_timestamp));
}

function getTeamHistoricData($tid, $field, $timestamp)
{
    global $db;
$sql = <<<SQL
(SELECT SQL_NO_CACHE CONVERT(to_value USING utf8) AS !
FROM admins_action_log
WHERE tablename = 'teams' AND tablePk = 'tid' AND tablePkId = ? AND field = ? AND timestamp_gmt <= FROM_UNIXTIME(?)
ORDER BY timestamp_gmt DESC LIMIT 1)

UNION

(SELECT SQL_NO_CACHE CONVERT(from_value USING utf8) AS !
FROM admins_action_log
WHERE tablename = 'teams' AND tablePk = 'tid' AND tablePkId = ? AND field = ? AND timestamp_gmt > FROM_UNIXTIME(?)
ORDER BY timestamp_gmt ASC LIMIT 1)

UNION

(SELECT SQL_NO_CACHE CONVERT(! USING utf8) AS !
FROM teams
WHERE tid = ?
LIMIT 1)

LIMIT 1
SQL;
    return $db->getOne($sql, array($field, $tid, $field, $timestamp, $field, $tid, $field, $timestamp, $field, $field, $tid));

}

function calculateTeamStandings($tid=0, $sid=0, $ps=0)
{
    if (empty($tid) || empty($sid)) return FALSE;
    global $db;

    if ($ps) { 
        $stg_types = "'Preseason'";
        $sql = 'SELECT UNIX_TIMESTAMP(IFNULL(preseason_close_date_gmt, NOW())) FROM seasons WHERE sid = ? LIMIT 1';
        $closeDateTimestamp =& $db->getOne($sql, array($sid));
    } else {
        $stg_types = "'Regular', 'Playoffs'";
        $sql = 'SELECT UNIX_TIMESTAMP(IFNULL(season_close_date_gmt, NOW())) FROM seasons WHERE sid = ? LIMIT 1';
        $closeDateTimestamp =& $db->getOne($sql, array($sid));
    }
    $sql = 'SELECT SQL_NO_CACHE inactive, approved, deleted, name, tag, UNIX_TIMESTAMP(create_date_gmt) AS unix_create_date_gmt, UNIX_TIMESTAMP(modify_date_gmt) AS unix_modify_date_gmt FROM teams WHERE tid = ? LIMIT 1';
    $teamInfo =& $db->getRow($sql, array($tid));
    $teamCreateDateTimestamp = $teamInfo['unix_create_date_gmt'];
    $teamModifyDateTimestamp = $teamInfo['unix_modify_date_gmt'];

    // team joined the league after the season ended, so they don't belong in the standings.
    if ($teamCreateDateTimestamp > $closeDateTimestamp) { return FALSE; }

    // Find out what division ID and group ID the team was in when we need this (at the closing date).
    // We need it according to the information in the seasons table.
    $sql = <<<SQL
                SELECT SQL_NO_CACHE grpid, divid
                FROM teams_divisions_log 
                WHERE tid = ? AND timestamp_gmt <= FROM_UNIXTIME(?)
                ORDER BY timestamp_gmt DESC
                LIMIT 1
SQL;
    $placement =& $db->getRow($sql, array($tid, $closeDateTimestamp));
    $divid = $placement['divid'];
    $grpid = $placement['grpid'];
    if (empty($grpid)) $grpid = NULL;


    // We need the following variables. 
    // If closing date is a date in the future, we can just take the current values from the Teams table.
    if ($closeDateTimestamp >= gmmktime()) {
        $teamName = $teamInfo['name'];
        $teamTag = $teamInfo['tag'];
        $teamInactive = $teamInfo['inactive'];
        $teamApproved = $teamInfo['approved'];
        $teamDeleted = $teamInfo['deleted'];
    } else {
        $teamHistoric = getTeamNameTagHistoric($tid, $closeDateTimestamp);
        $teamName = $teamHistoric['name'];
        $teamTag = $teamHistoric['tag'];
        $teamInactive = getTeamHistoricData($tid, 'inactive', $closeDateTimestamp);
        $teamApproved = getTeamHistoricData($tid, 'approved', $closeDateTimestamp);
        $teamDeleted = getTeamHistoricData($tid, 'deleted', $closeDateTimestamp);
    }

    if (empty($divid) || $teamDeleted) {
        $sql = 'DELETE FROM teams_standings_summary WHERE tid = ? AND sid = ? AND preseason = ? LIMIT 1';
        $res =& $db->query($sql, array($tid, $sid, $ps));
        $sql = 'DELETE FROM teams_standings_names_summary WHERE tid = ? AND sid = ? AND preseason = ? LIMIT 1';
        $res =& $db->query($sql, array($tid, $sid, $ps));
        return FALSE;
    } elseif ($teamInactive || !$teamApproved) {
        $standingsHide = 1;
        $sql = 'DELETE FROM teams_standings_names_summary WHERE tid = ? AND sid = ? AND preseason = ? LIMIT 1';
        $res =& $db->query($sql, array($tid, $sid, $ps));
    } else {
        $standingsHide = 0;
    }

if (!empty($divid)) {
$sql = <<<SQL
SELECT SQL_NO_CACHE

(
SELECT count(1)
FROM matches INNER JOIN schedules USING (sch_id)
WHERE schedules.sid = $sid 
AND schedules.stg_type IN ($stg_types)
AND schedules.deleted = 0
AND matches.deleted = 0 
AND (away_tid = teams.tid OR home_tid = teams.tid)
AND win_tid = teams.tid
AND matches.report_date_gmt <> '0000-00-00 00:00:00'
AND $divid = (
		    SELECT teams_divisions_log.divid 
		    FROM teams_divisions_log 
		    WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
		    ORDER BY teams_divisions_log.timestamp_gmt DESC 
		    LIMIT 1
		   )
) AS wins,

(
SELECT count(1)
FROM matches INNER JOIN schedules USING (sch_id)
WHERE schedules.sid = $sid 
AND schedules.stg_type IN ($stg_types)
AND schedules.deleted = 0
AND matches.deleted = 0 
AND (away_tid = teams.tid OR home_tid = teams.tid)
AND (win_tid <> teams.tid OR (
			       (forfeit_home = 1 AND home_tid = teams.tid) OR (forfeit_away = 1 AND away_tid = teams.tid)
			     )
    )
AND matches.report_date_gmt <> '0000-00-00 00:00:00'
AND $divid = (
		    SELECT teams_divisions_log.divid 
		    FROM teams_divisions_log 
		    WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
		    ORDER BY teams_divisions_log.timestamp_gmt DESC 
		    LIMIT 1
		   )
) AS losses,

(
SELECT count(1)
FROM matches INNER JOIN schedules USING (sch_id)
WHERE schedules.sid = $sid 
AND schedules.stg_type IN ($stg_types)
AND schedules.deleted = 0
AND matches.deleted = 0 
AND (away_tid = teams.tid OR home_tid = teams.tid)
AND matches.tie = 1
AND matches.report_date_gmt <> '0000-00-00 00:00:00'
AND $divid = (
		    SELECT teams_divisions_log.divid 
		    FROM teams_divisions_log 
		    WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
		    ORDER BY teams_divisions_log.timestamp_gmt DESC 
		    LIMIT 1
		   )
) AS ties,

(
SELECT count(1)
FROM matches INNER JOIN schedules USING (sch_id)
WHERE schedules.sid = $sid 
AND schedules.stg_type IN ($stg_types)
AND schedules.deleted = 0
AND matches.deleted = 0 
AND (away_tid = teams.tid OR home_tid = teams.tid)
AND IF(teams.tid = matches.away_tid, matches.forfeit_home, matches.forfeit_away) = 1
AND matches.report_date_gmt <> '0000-00-00 00:00:00'
AND $divid = (
		    SELECT teams_divisions_log.divid 
		    FROM teams_divisions_log 
		    WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
		    ORDER BY teams_divisions_log.timestamp_gmt DESC 
		    LIMIT 1
		   )
) AS forfeit_wins,

(
SELECT count(1)
FROM matches INNER JOIN schedules USING (sch_id)
WHERE schedules.sid = $sid 
AND schedules.stg_type IN ($stg_types)
AND schedules.deleted = 0
AND matches.deleted = 0 
AND (away_tid = teams.tid OR home_tid = teams.tid)
AND IF(teams.tid = matches.home_tid, matches.forfeit_home, matches.forfeit_away) = 1
AND matches.report_date_gmt <> '0000-00-00 00:00:00'
AND $divid = (
		    SELECT teams_divisions_log.divid 
		    FROM teams_divisions_log 
		    WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
		    ORDER BY teams_divisions_log.timestamp_gmt DESC 
		    LIMIT 1
		   )
) AS forfeit_losses,

SUM(IF(matches.away_tid = teams.tid, matches_scores.away_score, matches_scores.home_score)) AS points_for,
SUM(IF(matches.home_tid = teams.tid, matches_scores.away_score, matches_scores.home_score)) AS points_against

FROM schedules
RIGHT JOIN matches USING (sch_id)
RIGHT JOIN teams ON (
		    (matches.away_tid = teams.tid OR matches.home_tid = teams.tid)
		    AND (
			    $divid = (
					    SELECT teams_divisions_log.divid 
					    FROM teams_divisions_log 
					    WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
					    ORDER BY teams_divisions_log.timestamp_gmt DESC 
					    LIMIT 1
					  )
			)
		)
LEFT JOIN matches_scores USING (`mid`)
WHERE teams.tid = $tid
AND (schedules.sid = $sid OR schedules.sid IS NULL)
AND (schedules.stg_type IN ($stg_types) OR schedules.stg_type IS NULL)
AND (schedules.deleted = 0 OR schedules.deleted IS NULL)
AND (matches.deleted = 0 OR matches.deleted IS NULL)
GROUP BY wins, losses, ties, forfeit_wins, forfeit_losses


SQL;

    $teamStandings =& $db->getRow($sql);

$sql = <<<SQL
INSERT INTO teams_standings_summary 
    (grpid, wins, losses, ties, forfeit_wins, forfeit_losses, points_for, points_against, tid, sid, preseason)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
    grpid = ?,
    wins = ?,
    losses = ?,
    ties = ?,
    forfeit_wins = ?,
    forfeit_losses = ?,
    points_for = ?,
    points_against = ?
SQL;
    $res =& $db->query($sql, array(
                                    $grpid,
                                    (is_null($teamStandings['wins']) ? 0 : $teamStandings['wins']),
                                    (is_null($teamStandings['losses']) ? 0 : $teamStandings['losses']),
                                    (is_null($teamStandings['ties']) ? 0 : $teamStandings['ties']),
                                    (is_null($teamStandings['forfeit_wins']) ? 0 : $teamStandings['forfeit_wins']),
                                    (is_null($teamStandings['forfeit_losses']) ? 0 : $teamStandings['forfeit_losses']),
                                    (is_null($teamStandings['points_for']) ? 0 : $teamStandings['points_for']),
                                    (is_null($teamStandings['points_against']) ? 0 : $teamStandings['points_against']),
                                    $tid,
                                    $sid,
                                    $ps,
                                    $grpid,
                                    (is_null($teamStandings['wins']) ? 0 : $teamStandings['wins']),
                                    (is_null($teamStandings['losses']) ? 0 : $teamStandings['losses']),
                                    (is_null($teamStandings['ties']) ? 0 : $teamStandings['ties']),
                                    (is_null($teamStandings['forfeit_wins']) ? 0 : $teamStandings['forfeit_wins']),
                                    (is_null($teamStandings['forfeit_losses']) ? 0 : $teamStandings['forfeit_losses']),
                                    (is_null($teamStandings['points_for']) ? 0 : $teamStandings['points_for']),
                                    (is_null($teamStandings['points_against']) ? 0 : $teamStandings['points_against'])
                                  ));

if (!$standingsHide) {

$sql = <<<SQL
INSERT INTO teams_standings_names_summary
    (name, tag, tid, sid, preseason)
    VALUES (?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE
    name = ?,
    tag = ?
SQL;
    $res =& $db->query($sql, array(
                                    $teamName,
                                    $teamTag,
                                    $tid,
                                    $sid,
                                    $ps,
                                    $teamName,
                                    $teamTag
                                  ));
}
    return TRUE;

} else {
    /*
    $teamStandings = array(
                           'wins'               => 0,
                           'losses'             => 0,
                           'ties'               => 0,
                           'forfeit_losses'     => 0,
                           'forfeit_wins'       => 0,
                           'points_for'         => 0,
                           'points_against'     => 0
                          );
    */
    $sql = 'DELETE FROM teams_standings_summary WHERE tid = ? AND sid = ? AND preseason = ? LIMIT 1';
    $res =& $db->query($sql, array($tid, $sid, $ps));
    $sql = 'DELETE FROM teams_standings_names_summary WHERE tid = ? AND sid = ? AND preseason = ? LIMIT 1';
    $res =& $db->query($sql, array($tid, $sid, $ps));
    return FALSE;
}

}
