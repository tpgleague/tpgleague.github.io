<?php

$cssAppend[] = 'disputes';
require_once '../includes/inc.initialization.php';

if (!checkNumber(@$_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { @define('LID', @$_GET['lid']); }

$ACCESS = checkPermission('Edit League', 'League', LID);

$sql = 'SELECT leagues.* FROM leagues WHERE lid = ?';
$leagueInfo =& $db->getRow($sql, array(LID));
if (empty($leagueInfo)) displayError('Error: League not found.');
$tpl->assign('league_info', $leagueInfo);

$sql = <<<SQL
            SELECT disputes.*, disputes_players.uid, username, users.handle, firstname, lastname, hide_lastname,
            (
             SELECT handle 
             FROM rosters 
             WHERE rosters.uid = disputes_players.uid 
             AND rosters.tid = disputes.disputed_tid 
             AND rosters.join_date_gmt <= matches.report_date_gmt
             AND (rosters.leave_date_gmt >= matches.report_date_gmt OR rosters.leave_date_gmt = '0000-00-00 00:00:00')
             ORDER BY rosters.join_date_gmt DESC
             LIMIT 1
            ) AS roster_handle,
            (SELECT teams.name FROM teams WHERE teams.tid = away_tid LIMIT 1) AS away_name,
            (SELECT teams.name FROM teams WHERE teams.tid = home_tid LIMIT 1) AS home_name
            FROM leagues
            INNER JOIN seasons USING (lid)
            INNER JOIN schedules USING (sid)
            INNER JOIN matches USING (sch_id)
            INNER JOIN disputes USING (`mid`)
            LEFT JOIN disputes_players USING (did)
            LEFT JOIN users USING (uid)
            WHERE leagues.lid = ?
SQL;
$leagueDisputes =& $db->getAll($sql, array(LID));
$tpl->assign('disputes', $leagueDisputes);

displayTemplate('disputes');

