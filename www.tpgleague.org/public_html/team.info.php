<?php



$pageTitle = 'Team Information';
require_once '../includes/inc.initialization.php';
$tpl->append('external_css', 'team');
if (checkNumber($_GET['tid'])) { define('TID', $_GET['tid']); }

// get team info
$sql = <<<SQL
            SELECT 
            teams.captain_uid, teams.name AS team_name, tag, organizations.name AS organization_name, organizations.website, organizations.irc,
            organizations.ccode, teams.lid, league_title, division_title, conference_title, group_title, gid_name, team_avatar_url
            FROM teams 
            INNER JOIN organizations USING (orgid) 
            LEFT JOIN leagues USING (lid) 
            LEFT JOIN divisions USING (divid) 
            LEFT JOIN conferences USING (cfid) 
            LEFT JOIN groups USING (grpid) 
            WHERE tid = ? AND teams.deleted = 0 
            LIMIT 1
SQL;
$teamInfo =& $db->getRow($sql, array(TID));
$tpl->assign('team_info', $teamInfo);
define('LID', $teamInfo['lid']);
//if (empty($teamInfo)) displayError('No team with that ID found.'); // better to get the message from the template.

if (!empty($teamInfo)):

$sql = <<<SQL

SELECT r.uid, r.gid, r.handle,
       r.anticheatuserid, u.firstname, u.lastname, u.hide_lastname, u.ccode, c.country, join_date_gmt
,
COALESCE(
      (
      SELECT sl_u.suspid

        FROM suspensions_uids
          AS su

        JOIN suspensions_list
          AS sl_u
          ON sl_u.suspid = su.suspid
         AND sl_u.suspension_date_ends_gmt > NOW()
         AND sl_u.deleted = 0
         AND (sl_u.lid = ? OR sl_u.lid IS NULL)

       WHERE su.uid = r.uid
       LIMIT 1
      )
,
      (
      SELECT sl_g.suspid

        FROM suspensions_gids
          AS sg

        JOIN suspensions_list
          AS sl_g
          ON sl_g.suspid = sg.suspid
         AND sl_g.suspension_date_ends_gmt > NOW()
         AND sl_g.deleted = 0
         AND (sl_g.lid = ? OR sl_g.lid IS NULL)

       WHERE sg.gid = r.gid
       LIMIT 1
       )
) AS suspended

        FROM rosters
          AS r

        JOIN teams
          AS t
          ON r.tid = t.tid

        JOIN users
          AS u
          ON u.uid = r.uid

   LEFT JOIN countries
          AS c
          ON c.ccode = u.ccode

WHERE r.tid = ? AND leave_date_gmt = "0000-00-00 00:00:00"

SQL;

$rosterInfo =& $db->getAssoc($sql, NULL, array(LID,LID,TID));
$tpl->assign('roster_info', $rosterInfo);



// get team season record
$sql = <<<SQL
            SELECT wins, losses, ties 
            FROM teams_standings_summary 
            LEFT JOIN seasons USING (sid) 
            WHERE tid = ? AND lid = ? AND seasons.active = 1 AND display_preseason = preseason
SQL;
$seasonRecord =& $db->getRow($sql, array(TID, LID));
$tpl->assign('season_record', $seasonRecord);




//get team season schedule
$sql = <<<SQL
            SELECT cast(`mid` as unsigned) as mid, map_title, maps.filename, schedules.sch_id, win_tid, stg_short_desc, home_tid,
            (SELECT name FROM teams WHERE tid = home_tid LIMIT 1) AS home_name,
            away_tid,
            (SELECT name FROM teams WHERE tid = away_tid LIMIT 1) AS away_name,
            IF(((forfeit_home = 1 AND home_tid = ?) OR forfeit_away = 1 AND away_tid = ?), TRUE, FALSE) AS forfeit_loss,
            IF(((forfeit_home = 1 AND home_tid <> ?) OR forfeit_away = 1 AND away_tid <> ?), TRUE, FALSE) AS forfeit_win,
            (SELECT teams.divid FROM teams WHERE teams.tid = IF(away_tid = ?, away_tid, home_tid) LIMIT 1) = 
                                                   (
                                                    SELECT teams_divisions_log.divid 
                                                    FROM teams_divisions_log 
                                                    WHERE teams_divisions_log.tid = IF(away_tid = ?, away_tid, home_tid) 
                                                    AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                                                    ORDER BY teams_divisions_log.timestamp_gmt DESC 
                                                    LIMIT 1
                                                   ) AS divisional_match
            FROM matches
            RIGHT JOIN schedules ON (schedules.sch_id = matches.sch_id AND (matches.away_tid = ? OR matches.home_tid = ?))
            LEFT JOIN maps USING (mapid)
            INNER JOIN seasons USING (sid)
            WHERE seasons.lid = ? AND seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason")
            AND schedules.deleted = 0 AND (matches.deleted = 0 OR matches.deleted IS NULL)
            ORDER BY schedules.stg_match_date_gmt ASC
SQL;
$scheduleInfo =& $db->getAll($sql, array(TID, TID, TID, TID, TID, TID, TID, TID, LID));
$tpl->assign('schedule_info', $scheduleInfo);

// get pending matches for this team for this season
$sql = <<<SQL
            SELECT sch_id
            FROM leagues
            INNER JOIN seasons USING (lid)
            INNER JOIN schedules USING (sid)
            INNER JOIN matches_pending USING (sch_id)
            WHERE matches_pending.tid = ? AND seasons.lid = ? AND seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason")
            AND schedules.deleted = 0 AND matches_pending.deleted = 0 AND matches_pending.`mid` IS NULL
SQL;
$pendingInfo =& $db->getCol($sql, 0, array(TID, LID));
$tpl->assign('pending_info', $pendingInfo);

// get match totals for each match
$sql = <<<SQL
				SELECT `mid`, sum(away_score) AS away_score, sum(home_score) AS home_score,
                    case when away_tid = ? AND sum(away_score) > sum(home_score) then 1
                         when home_tid = ? AND sum(home_score) > sum(away_score) then 1
                         else 0 end win
				FROM matches 
				INNER JOIN schedules USING (sch_id) 
				INNER JOIN seasons USING (sid) 
				LEFT JOIN `matches_scores` USING (`mid`) 
				WHERE (away_tid = ? OR home_tid = ?) 
				AND matches.report_date_gmt <> "0000-00-00 00:00:00" 
				AND (matches.deleted = 0 AND matches.forfeit_away = 0 AND matches.forfeit_home = 0) 
				AND seasons.active = 1 
				GROUP BY `mid`
SQL;
$matchScores =& $db->getAssoc($sql, NULL, array(TID, TID, TID, TID));
$tpl->assign('match_scores', $matchScores);

// $get season title
$sql = 'SELECT seasons.season_title FROM seasons WHERE lid = ? AND active = 1 LIMIT 1';
$season_title =& $db->getOne($sql, array(LID));
$tpl->assign('season_title', $season_title);

endif;

displayTemplate('team.info', TID, 60, TRUE);
