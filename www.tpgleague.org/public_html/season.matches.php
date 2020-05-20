<?php


$pageTitle = 'Season Matches';
require_once '../includes/inc.initialization.php';
if (!loggedin()) displayError('You must be logged in to use this function.');
$tpl->append('external_css', 'season.matches');


define('TID', $_GET['tid']);
if (!checkNumber(TID)) { displayError('Team ID not specified.'); }

$sql = 'SELECT lid, tid, teams.name, captain_uid, owner_uid FROM teams INNER JOIN organizations USING (orgid) WHERE tid = ? AND teams.deleted = 0 LIMIT 1';
$teamData =& $db->getRow($sql, array(TID));

$sql = 'SELECT permission_reschedule, permission_report FROM rosters WHERE leave_date_gmt = "0000-00-00 00:00:00" AND uid = ? AND tid = ? LIMIT 1';
$permissions =& $db->getRow($sql, array(UID, TID));

if (empty($teamData)) displayError('Team not found.');
if ($teamData['captain_uid'] !== UID && $teamData['owner_uid'] !== UID && !$permissions['permission_reschedule'] && !$permissions['permission_report']) displayError('You are not authorized to access this function.');
define('LID', $teamData['lid']);

// find the current active season
$sql = 'SELECT sid FROM seasons WHERE lid = ? AND active = 1 LIMIT 1';
$sid =& $db->getOne($sql, array(LID));
define('SID', $sid);

$sql = <<<SQL
SELECT `mid`, map_title, matches.report_date_gmt, UNIX_TIMESTAMP(start_date_gmt) AS start_date_gmt, away_tid, home_tid,
IF(tid=away_tid, (SELECT name FROM teams WHERE tid = home_tid LIMIT 1), (SELECT name FROM teams WHERE tid = away_tid LIMIT 1)) as opponent_name
FROM schedules LEFT JOIN maps USING (mapid)
INNER JOIN matches USING (sch_id)
INNER JOIN teams ON (away_tid = tid OR home_tid = tid)
WHERE tid = ? AND schedules.deleted = 0 AND matches.deleted = 0 AND sid = ? ORDER BY schedules.stg_match_date_gmt ASC
SQL;
$season_opponents =& $db->getAll($sql, array(TID, SID));
$tpl->assign('season_opponents', $season_opponents);

displayTemplate('season.matches');