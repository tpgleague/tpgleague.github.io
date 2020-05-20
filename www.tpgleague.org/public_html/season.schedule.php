<?php

if ($_GET['sch_id']) {
    define('SCH_ID', $_GET['sch_id']);
    $pageTitle = 'Schedule Matches';
} else {
    $pageTitle = 'Season Schedule';
}

require_once '../includes/inc.initialization.php';
$tpl->assign('external_css', 'season.schedule');

if (!defined('LID')) define('LID', $_GET['lid']);
if (!checkNumber(LID)) { displayError('League ID not specified.'); }


if (defined('SCH_ID')) {
    if (!checkNumber(SCH_ID)) displayError('Invalid schedule ID.');
    $sql = <<<SQL
                SELECT DISTINCT cast(`mid` as unsigned) as mid, win_tid, home_tid, away_tid, forfeit_home, forfeit_away, report_date_gmt, UNIX_TIMESTAMP(start_date_gmt) AS unix_start_date_gmt,
                (SELECT name FROM teams WHERE tid = home_tid LIMIT 1) AS home_name,
                (SELECT name FROM teams WHERE tid = away_tid LIMIT 1) AS away_name,
                (SELECT SUM(home_score) FROM matches_scores WHERE `matches_scores`.`mid` = `matches`.`mid`) AS home_score,
                (SELECT SUM(away_score) FROM matches_scores WHERE `matches_scores`.`mid` = `matches`.`mid`) AS away_score
                FROM matches
                WHERE sch_id = ? AND matches.deleted = 0 AND (win_tid IS NULL OR win_tid <> 0)
SQL;
    $scheduled =& $db->getAll($sql, array(SCH_ID));
    $tpl->assign('week_schedule', $scheduled);

    $sql = <<<SQL
        SELECT teams.tid, teams.name FROM `matches_pending` INNER JOIN teams USING (tid)
        WHERE sch_id = ? AND matches_pending.deleted = 0 AND matches_pending.`mid` IS NULL
SQL;
    $scheduledPending =& $db->getAll($sql, array(SCH_ID));
    $tpl->assign('week_schedule_pending', $scheduledPending);
}
else {
$sql = <<<SQL
            SELECT
                schedules.sch_id,
                seasons.display_preseason,
                stg_type,
                UNIX_TIMESTAMP(stg_match_date_gmt) AS unix_stg_match_date_gmt,
                map_title,
                maps.filename,
                stg_short_desc,
                (SELECT COUNT(1) FROM matches WHERE sch_id = schedules.sch_id AND deleted = 0) > 0 AS matches_scheduled
            FROM leagues
            INNER JOIN seasons USING (lid)
            LEFT JOIN schedules USING (sid)
            LEFT JOIN maps USING (mapid)
            WHERE leagues.lid = ? AND seasons.active = 1 AND schedules.deleted = 0
            ORDER BY stg_match_date_gmt ASC
SQL;
    $seasonSchedule =& $db->getAll($sql, array(LID));
    $tpl->assign('season_schedule', $seasonSchedule);
}

displayTemplate('season.schedule', NULL, 0, TRUE);