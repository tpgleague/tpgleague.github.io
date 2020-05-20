<?php

$script_time_start = microtime(TRUE);

//$pageTitle = 'Season Standings';
require_once '../includes/inc.initialization.php';
$tpl->append('external_css', 'standings');

//$tpl->caching = ;
//$tpl->cache_lifetime = 15;
if (!$tpl->is_cached('standings.tpl', LID)):

/*
$sql = 'SELECT league_title, season_title FROM leagues INNER JOIN seasons USING (lid) WHERE lid = ? AND seasons.active = 1 LIMIT 1';
$seasonInfo =& $db->getRow($sql, array(LID));
$tpl->assign('league_title', $seasonInfo['league_title']);
$tpl->assign('season_title', $seasonInfo['season_title']);

$tpl->assign('title', $seasonInfo['league_title'] . ' Season Standings');

$sql = 'SELECT divid, division_title FROM divisions WHERE lid = ? AND divisions.inactive = 0 ORDER BY divisions.sort_order DESC';
$divisions =& $db->getAssoc($sql, TRUE, array(LID));
$tpl->assign('standings_divisions', $divisions);

$sql = 'SELECT divid, cfid, conference_title FROM conferences INNER JOIN divisions USING (divid) WHERE lid = ? AND conferences.inactive = 0 AND divisions.inactive = 0 ORDER BY conferences.sort_order DESC';
$conferences =& $db->getAssoc($sql, NULL, array(LID), NULL, TRUE);
$tpl->assign('standings_conferences', $conferences);

$sql = 'SELECT cfid, grpid, group_title FROM groups INNER JOIN conferences USING (cfid) INNER JOIN divisions USING (divid) WHERE lid = ? AND groups.inactive = 0 AND conferences.inactive = 0 ORDER BY groups.sort_order';
$groups =& $db->getAssoc($sql, NULL, array(LID), NULL, TRUE);
$tpl->assign('standings_groups', $groups);

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
$teams =& $db->getAssoc($sql, NULL, array(LID), NULL, TRUE);
$tpl->assign('standings_teams', $teams);
*/

    include_once 'inc.func-standings.php';
    $standingsData = getStandings(LID);
    $tpl->assign('standings_league_title', $standingsData['league_title']);
    $tpl->assign('standings_season_title', $standingsData['season_title']);
    $tpl->assign('standings_groups', $standingsData['groups']);
    $tpl->assign('standings_divisions', $standingsData['divisions']);
    $tpl->assign('standings_conferences', $standingsData['conferences']);
    $tpl->assign('standings_teams', $standingsData['teams']);
    $tpl->assign('title', $standingsData['league_title'] . ' Season Standings');
endif;

displayTemplate('standings', LID, 60, FALSE);

$script_time_end = microtime(true);
$script_execution_time = $script_time_end - $script_time_start;
echo '<!-- Script execution time: ', $script_execution_time, ' seconds. -->';
