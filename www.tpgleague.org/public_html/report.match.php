<?php

$pageTitle = 'Report Match';
require_once '../includes/inc.initialization.php';
require_once 'inc.func-schedule.php';
if (!loggedin()) displayError('You must be logged in to use this function.');

$tpl->append('external_css', 'report.match');



define('TID', $_GET['tid']);
if (!checkNumber(TID) || TID == 0) { displayError('Team ID not specified.'); }

define('MID', $_GET['mid']);
if (!checkNumber(MID) || MID == 0) { displayError('Match ID not specified.'); }

$sql = 'SELECT away_tid, home_tid, sch_id, stg_type, IF(stg_type = "Preseason", 1, 0) AS ps, sid, report_date_gmt, UNIX_TIMESTAMP(stg_match_date_gmt) AS unix_stg_match_date_gmt, UNIX_TIMESTAMP(DATE_ADD(stg_match_date_gmt, INTERVAL 125 HOUR)) AS max_schedule_unix_gmt, start_date_gmt, unix_timestamp(start_date_gmt) AS unix_start_date_gmt, (SELECT name FROM teams WHERE tid=away_tid LIMIT 1) AS away_name, (SELECT name FROM teams WHERE tid=home_tid LIMIT 1) AS home_name FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN teams ON (away_tid = teams.tid OR home_tid = teams.tid) WHERE `mid` = ? AND matches.deleted = 0 LIMIT 1';
$matchData =& $db->getRow($sql, array(MID));

if (empty($matchData)) displayError('No match by that ID found.');

if ($matchData['away_tid'] != TID && $matchData['home_tid'] != TID) displayError('You are not authorized to access this function.');

$sql = 'SELECT lid, tid, teams.name, captain_uid, owner_uid FROM teams INNER JOIN organizations USING (orgid) WHERE tid = ? AND teams.deleted = 0 LIMIT 1';
$teamData =& $db->getRow($sql, array(TID));

$sql = 'SELECT permission_report FROM rosters WHERE leave_date_gmt = "0000-00-00 00:00:00" AND uid = ? AND tid = ? LIMIT 1';
$permissionReporter =& $db->getOne($sql, array(UID, TID));

if (empty($teamData)) displayError('Team not found.');
if ($teamData['captain_uid'] !== UID && $teamData['owner_uid'] !== UID && !$permissionReporter) displayError('You are not authorized to access this function.');
define('LID', $teamData['lid']);

if ($matchData['report_date_gmt'] != '0000-00-00 00:00:00') displayError('This match has already been reported.');
if ($matchData['home_tid'] == 0 || $matchData['away_tid'] == 0) displayError('This match is a bye week.');

$homeName = $matchData['home_name'];
$awayName = $matchData['away_name'];

$tpl->assign('home_name', $homeName);
$tpl->assign('away_name', $awayName);

$tpl->assign('home_tid', $matchData['home_tid']);
$tpl->assign('away_tid', $matchData['away_tid']);

$sql = 'SELECT lsid, side FROM leagues_sides WHERE lid = '. $db->quoteSmart(LID);
$sides =& $db->getAll($sql);
$tpl->assign('sides', $sides);

$arraySides = array($sides[0]['lsid'], $sides[1]['lsid']);

$sql = <<<SQL
            SELECT TRUE FROM matches_proposed WHERE `mid` = ? AND proposed_tid <> ? AND status IN ("Declined", "Accepted", "Pending") LIMIT 1 
            UNION 
            SELECT TRUE FROM matches_proposed WHERE `mid` = ? AND proposed_tid = ? AND status IN ("Declined", "Accepted") LIMIT 1
SQL;
$opponentAttemptedSchedule =& $db->getOne($sql, array(MID, TID, MID, TID));

$tpl->assign('match_start_time', $matchData['unix_start_date_gmt']);

if ($matchData['home_tid'] == TID) {
    $homeCanFF = TRUE;
    if (
        (!$opponentAttemptedSchedule && $matchData['unix_start_date_gmt'] - 86400 < gmmktime()) 
        || $matchData['unix_start_date_gmt'] <= gmmktime()) {
        $awayCanFF = TRUE;
    }
} else {
    $awayCanFF = TRUE;
    if ((!$opponentAttemptedSchedule && $matchData['unix_start_date_gmt'] - 86400 < gmmktime())
        || $matchData['unix_start_date_gmt'] <= gmmktime()) {
        $homeCanFF = TRUE;
    }
}
$tpl->assign('home_can_ff', $homeCanFF);
$tpl->assign('away_can_ff', $awayCanFF);



if (isset($_POST['forfeit'])) {
    $forfeit_tid = $_POST['forfeit'];

    if ($matchData['home_tid'] == $forfeit_tid) {
        if (!$homeCanFF) displayError('This match cannot be reported as a forfeit loss for your opponent at this time.');
        $home_ff = 1;
        $away_ff = 0;
        $forfeiter = $homeName;
        $winner_name = $awayName;
        $win_tid = $matchData['away_tid'];
    } else {
        if (!$awayCanFF) displayError('This match cannot be reported as a forfeit loss for your opponent at this time.');
        $home_ff = 0;
        $away_ff = 1;
        $forfeiter = $awayName;
        $winner_name = $homeName;
        $win_tid = $matchData['home_tid'];
    }

    // find some good info
    $sql = 'SELECT leagues.league_title, leagues.lgname, schedules.stg_short_desc FROM leagues INNER JOIN seasons USING (lid) INNER JOIN schedules USING (sid) INNER JOIN matches USING (sch_id) WHERE `mid` = ? LiMIT 1';
    $matchInfo =& $db->getRow($sql, array(MID));
    $notificationVars = array(
                              'lgname' => $matchInfo['lgname'].'/',
                              'opponent_team' => $winner_name,
                              'your_team' => $forfeiter,
                              'league_title' => $matchInfo['league_title'],
                              'week' => $matchInfo['stg_short_desc']
                             );
    sendMessage(getPrivilegedMembers($forfeit_tid), 'forfeit.loss', $notificationVars);


    if ($matchData['stg_type'] != 'Playoffs' && $matchData['unix_stg_match_date_gmt'] > gmmktime()) { // default match date not reached. delete this match and pending queue the winning team.
        $sql = 'UPDATE matches SET deleted = 1, report_date_gmt = NOW(), report_by_uid = ?, report_by_tid = ? WHERE `mid` = ?';
        $db->query($sql, array(UID, TID, MID));
        $newMatchID = scheduleTeams($matchData['sch_id'], $win_tid, NULL); // win_tid to pending queue
        scheduleTeams($matchData['sch_id'], $forfeit_tid, 0, 'auto', 0, 1); // forfeit tid gets bye loss.
        if (TID == $forfeit_tid) { // the team who filed forfeited
            $ff_message = 'The match has been reported as a win for '.escape($winner_name).'.';
        } else {
            if ($newMatchID == 'PENDING_QUEUE') {
                $ff_message = 'Since the match has been reported before this week\'s matches have been played, your team will be placed in the pending queue. You may be placed against another opponent in your division at any time.';
            } else {
                $ff_message = 'Since the match has been reported before this week\'s matches have been played, your team has been rescheduled against a waiting opponent. Please visit the <a href="/schedule.match.php?mid='.$newMatchID.'&amp;tid='.TID.'">TPG Scheduler</a> and begin the scheduling process immediately.';
            }
        }
        $tpl->assign('ff_message', $ff_message);
    } else {
        $sql = 'UPDATE matches SET report_by_uid = ?, report_by_tid = ?, report_date_gmt = NOW(), win_tid = ?, forfeit_home = ?, forfeit_away = ?  WHERE mid = ?';
        $res =& $db->query($sql, array(UID, TID, $win_tid, $home_ff, $away_ff, MID));
        $tpl->assign('winner_name', $winner_name);
    }

    $sql = 'SELECT inactive FROM teams WHERE tid = ? LIMIT 1';
    $TeamInactive =& $db->getOne($sql, array($forfeit_tid));

    if (!$TeamInactive) {
        $sql = 'UPDATE teams SET inactive = 1 WHERE tid = ? LIMIT 1';
        $db->query($sql, array($forfeit_tid));
        $sql = 'INSERT INTO admins_action_log (aid, field, from_value, to_value, tablename, tablePk, tablePkId, timestamp_gmt, type) '
             . 'VALUES (NULL, "inactive", "0", "1", "teams", "tid", ?, NOW(), "update")';
        $db->query($sql, array($forfeit_tid));
    }

    require_once 'inc.func-updateStandings.php';
    calculateTeamStandings($matchData['away_tid'], $matchData['sid'], $matchData['ps']);
    calculateTeamStandings($matchData['home_tid'], $matchData['sid'], $matchData['ps']);

} elseif ($_POST['submit'] == 'Report Score') {

    if ($matchData['unix_start_date_gmt'] > gmmktime()) displayError('The scores may not be reported for your match at this time');

    $h1a_side = $_POST['side_selector_h1a'];
    $h1h_side = $_POST['side_selector_h1h'];
    $h2a_side = $_POST['side_selector_h2a'];
    $h2h_side = $_POST['side_selector_h2h'];

    $h1a_score = $_POST['h1a_score'];
    $h1h_score = $_POST['h1h_score'];
    $h2a_score = $_POST['h2a_score'];
    $h2h_score = $_POST['h2h_score'];

    if (!checkNumber($h1a_score) || !checkNumber($h1h_score) || !checkNumber($h2a_score) || !checkNumber($h2h_score)) {
        $tpl->append('error_message', 'Please completely fill out the match scores.');
        $error = TRUE;
    }

    if (!in_array($h1a_side, $arraySides) || !in_array($h1h_side, $arraySides) || !in_array($h2a_side, $arraySides) || !in_array($h2h_side, $arraySides)) {
        $tpl->append('error_message', 'Please completely fill out the match sides.');
        $error = TRUE;
    }

    if (!$error) {

        $away_score = $h1a_score + $h2a_score;
        $home_score = $h1h_score + $h2h_score;

        $tie = 0;
        if ($away_score > $home_score) {
            $win_tid = $matchData['away_tid'];
            $winner_name = $matchData['away_name'];
        } elseif ($away_score < $home_score) {
            $win_tid = $matchData['home_tid'];
            $winner_name = $matchData['home_name'];
        } else {
            $win_tid = 'NULL';
            $tie = 1;
            $tpl->assign('tie', TRUE);
        }

        $sql = 'UPDATE matches SET report_by_uid = ?, report_by_tid = ?, report_date_gmt = NOW(), win_tid = !, tie = ?, match_comments = ? WHERE mid = ?';
        $res =& $db->query($sql, array(UID, TID, $win_tid, $tie, trim($_POST['comments']), MID));

        $sql = 'INSERT INTO matches_scores (away_score, home_score, mid, away_lsid, home_lsid) '
             . 'VALUES (?, ?, ?, ?, ?)';
        $res =& $db->query($sql, array($h1a_score, $h1h_score, MID, $h1a_side, $h1h_side));
        $res =& $db->query($sql, array($h2a_score, $h2h_score, MID, $h2a_side, $h2h_side));

        require_once 'inc.func-updateStandings.php';
        calculateTeamStandings($matchData['away_tid'], $matchData['sid'], $matchData['ps']);
        calculateTeamStandings($matchData['home_tid'], $matchData['sid'], $matchData['ps']);

        $tpl->assign('winner_name', $winner_name);
    }
}


//$onload = 'changeSides()';
//$tpl->assign('onload', $onload);

$extra_head[] = <<<JS
<script language="javascript" type="text/javascript">
<!--
function changeSides(box) {
    sel = eval("document.forms['report_match_form'].side_selector_" + box + ".selectedIndex");
    if (sel == 0) { return true; }
    opp = (sel == 1) ? 2 : 1;
    if (box == 'h1a') {
        document.forms['report_match_form'].side_selector_h1h.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2a.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2h.selectedIndex = sel;
    } else if (box == 'h1h') {
        document.forms['report_match_form'].side_selector_h1a.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2a.selectedIndex = sel;
        document.forms['report_match_form'].side_selector_h2h.selectedIndex = opp;
    } else if (box == 'h2a') {
        document.forms['report_match_form'].side_selector_h1a.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h1h.selectedIndex = sel;
        document.forms['report_match_form'].side_selector_h2h.selectedIndex = opp;
    } else {
        document.forms['report_match_form'].side_selector_h1a.selectedIndex = sel;
        document.forms['report_match_form'].side_selector_h1h.selectedIndex = opp;
        document.forms['report_match_form'].side_selector_h2a.selectedIndex = opp;
    }
}


-->
</script>
JS;
$tpl->assign('extra_head', $extra_head);

displayTemplate('report.match');
