<?php



function getStandings($lid) {

global $db;

$sql = <<<SQL
    SELECT league_title, season_title 
    FROM leagues 
    INNER JOIN seasons USING (lid) 
    WHERE lid = ? AND seasons.active = 1 
    LIMIT 1
SQL;
$seasonInfo =& $db->getRow($sql, array($lid));
$league_title = $seasonInfo['league_title'];
$season_title = $seasonInfo['season_title'];


$sql = <<<SQL
    SELECT divid, division_title 
    FROM divisions 
    WHERE lid = ? AND divisions.inactive = 0 
    ORDER BY divisions.sort_order DESC
SQL;
$divisions =& $db->getAssoc($sql, TRUE, array($lid));


$sql = <<<SQL
    SELECT divisions.divid, conferences.cfid, conference_title 
    FROM conferences 
    INNER JOIN divisions ON (conferences.divid = divisions.divid)
    WHERE divisions.lid = ? AND conferences.inactive = 0 AND divisions.inactive = 0 
    ORDER BY conferences.sort_order DESC
SQL;
$conferences =& $db->getAssoc($sql, NULL, array($lid), NULL, TRUE);


$sql = <<<SQL
    SELECT conferences.cfid, groups.grpid, group_title 
    FROM groups 
    INNER JOIN conferences ON (groups.cfid = conferences.cfid)
    INNER JOIN divisions ON (conferences.divid = divisions.divid)
    WHERE divisions.lid = ? AND groups.inactive = 0 AND conferences.inactive = 0 
    ORDER BY groups.sort_order
SQL;
$groups =& $db->getAssoc($sql, NULL, array($lid), NULL, TRUE);

/*
$sql = <<<SQL
    SELECT grpid, tid, name, tag,

    (
        SELECT count(1)
        FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid)
        WHERE seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND schedules.deleted = 0
        AND (away_tid = teams.tid OR home_tid = teams.tid)
        AND win_tid = teams.tid
        AND matches.deleted = 0
        AND matches.report_date_gmt <> "0000-00-00 00:00:00"
        AND teams.divid = (
                            SELECT teams_divisions_log.divid 
                            FROM teams_divisions_log 
                            WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                            LIMIT 1
                           )
    ) AS wins,

    (
        SELECT count(1)
        FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid)
        WHERE seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND schedules.deleted = 0
        AND (away_tid = teams.tid OR home_tid = teams.tid)
        AND (win_tid <> teams.tid OR (
                                       (forfeit_home = 1 AND home_tid = teams.tid) OR (forfeit_away = 1 AND away_tid = teams.tid)
                                     )
            )
        AND matches.deleted = 0
        AND matches.report_date_gmt <> "0000-00-00 00:00:00"
        AND teams.divid = (
                            SELECT teams_divisions_log.divid 
                            FROM teams_divisions_log 
                            WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                            LIMIT 1
                           )
    ) AS losses,

    (
        SELECT count(1)
        FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid)
        WHERE seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND schedules.deleted = 0
        AND (away_tid = teams.tid OR home_tid = teams.tid)
        AND matches.tie = 1 AND matches.deleted = 0
        AND matches.report_date_gmt <> "0000-00-00 00:00:00"
        AND teams.divid = (
                            SELECT teams_divisions_log.divid 
                            FROM teams_divisions_log 
                            WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                            LIMIT 1
                           )
    ) AS ties,

    (
        SELECT count(1)
        FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid)
        WHERE seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND schedules.deleted = 0
        AND (away_tid = teams.tid OR home_tid = teams.tid)
        AND IF(teams.tid = matches.away_tid, matches.forfeit_home, matches.forfeit_away) = 1 AND matches.deleted = 0
        AND matches.report_date_gmt <> "0000-00-00 00:00:00"
        AND teams.divid = (
                            SELECT teams_divisions_log.divid 
                            FROM teams_divisions_log 
                            WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                            LIMIT 1
                           )
    ) AS forfeit_wins,

    (
        SELECT count(1)
        FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid)
        WHERE seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND schedules.deleted = 0
        AND (away_tid = teams.tid OR home_tid = teams.tid)
        AND IF(teams.tid = matches.home_tid, matches.forfeit_home, matches.forfeit_away) = 1 AND matches.deleted = 0
        AND matches.report_date_gmt <> "0000-00-00 00:00:00"
        AND teams.divid = (
                            SELECT teams_divisions_log.divid 
                            FROM teams_divisions_log 
                            WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                            LIMIT 1
                           )
    ) AS forfeit_losses,

    (
        SELECT count(1)
        FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid)
        WHERE seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND schedules.deleted = 0
        AND (away_tid = teams.tid OR home_tid = teams.tid)
        AND IF(teams.tid = matches.home_tid, matches.forfeit_home, matches.forfeit_away) = 1 AND matches.deleted = 0
        AND matches.report_date_gmt <> "0000-00-00 00:00:00"
        AND teams.divid = (
                            SELECT teams_divisions_log.divid 
                            FROM teams_divisions_log 
                            WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                            LIMIT 1
                           )
    ) AS forfeit_losses,

    (
        SELECT count(1)
        FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid)
        WHERE seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND schedules.deleted = 0
        AND (away_tid = teams.tid OR home_tid = teams.tid)
        AND matches.deleted = 0
        AND matches.report_date_gmt <> "0000-00-00 00:00:00"
        AND teams.divid = (
                            SELECT teams_divisions_log.divid 
                            FROM teams_divisions_log 
                            WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                            LIMIT 1
                           )
    ) <> 0 AS matches_played,

    SUM(IF(matches.away_tid = teams.tid, matches_scores.away_score, matches_scores.home_score)) AS points_for,
    SUM(IF(matches.home_tid = teams.tid, matches_scores.away_score, matches_scores.home_score)) AS points_against,
    SUM(IF(matches.away_tid = teams.tid, matches_scores.away_score, matches_scores.home_score))
    - SUM(IF(matches.home_tid = teams.tid, matches_scores.away_score, matches_scores.home_score)) AS points_difference

    FROM seasons
    RIGHT JOIN schedules USING (sid)
    RIGHT JOIN matches USING (sch_id)
    RIGHT JOIN matches_scores USING (`mid`)
    RIGHT JOIN teams ON (
                            matches.away_tid = teams.tid OR matches.home_tid = teams.tid
                            AND (
                                    teams.divid = (
                                                    SELECT teams_divisions_log.divid 
                                                    FROM teams_divisions_log 
                                                    WHERE teams_divisions_log.tid = teams.tid AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                                                    ORDER BY teams_divisions_log.timestamp_gmt DESC 
                                                    LIMIT 1
                                                  )
                                )
                        )
    RIGHT JOIN groups USING (grpid)
    WHERE teams.lid = ? AND (seasons.active IS NULL OR
    (seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason") AND matches.deleted = 0 AND schedules.deleted = 0))
    AND teams.approved = 1 AND teams.inactive = 0 AND teams.deleted = 0 AND teams.grpid IS NOT NULL
    GROUP BY teams.tid, wins, losses, ties
    ORDER BY matches_played DESC, wins DESC, losses ASC, ties DESC, forfeit_losses ASC, forfeit_wins ASC, points_difference DESC, points_for DESC
SQL;
*/
$sql = <<<SQL
SELECT SQL_CACHE teams_standings_summary.grpid, teams_standings_summary.tid, teams_standings_names_summary.name, teams_standings_names_summary.tag, wins, losses, ties, forfeit_wins, forfeit_losses, (wins <> 0 OR losses <> 0 OR ties <> 0) AS matches_played, points_for, points_against, CAST(points_for - points_against AS SIGNED) AS points_difference
FROM seasons
INNER JOIN teams_standings_summary ON (
                                        teams_standings_summary.sid = seasons.sid AND teams_standings_summary.preseason = seasons.display_preseason
                                      )
INNER JOIN teams_standings_names_summary ON (
                                                teams_standings_names_summary.sid = seasons.sid AND 
                                                teams_standings_names_summary.preseason = seasons.display_preseason AND 
                                                teams_standings_names_summary.tid = teams_standings_summary.tid
                                            )
WHERE lid = ? AND seasons.active = 1 AND teams_standings_summary.grpid IS NOT NULL
ORDER BY matches_played DESC, wins DESC, losses ASC, ties DESC, forfeit_losses ASC, forfeit_wins ASC, points_difference DESC, points_for DESC
SQL;

$teams =& $db->getAssoc($sql, NULL, array($lid), NULL, TRUE);


$standings = array('league_title' => $league_title,
                   'season_title' => $season_title,
                   'divisions'    => $divisions,
                   'conferences'  => $conferences,
                   'groups'       => $groups,
                   'teams'        => $teams
                  );
return $standings;



}