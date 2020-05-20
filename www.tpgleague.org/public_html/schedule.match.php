<?php

$pageTitle = 'Schedule Match';
require_once '../includes/inc.initialization.php';


if (!loggedin()) displayError('You must be logged in to use this function.');
$tpl->append('external_js', 'scheduler.hide');
//$tpl->append('external_js', 'popup');
$tpl->append('external_css', 'schedule.match');

if ($_POST['comments_hide']) {
    $var_state = 'none';
} else {
    $var_state = 'block';
}

$extra_head = <<<JS
<script language="javascript" type="text/javascript">
<!--
var state = '$var_state';

//-->
</script>

<!--[if IE]>
<style type="text/css">
div.comments_box {
  border: thin solid #c60;
  padding: 0.5em 2em;
  background: white;
  margin: 1em;
}
div.comments_text {
  margin: 0;
  padding: 0;
  border: 0;
}
</style>
<![endif]-->

<script type="text/javascript">

function getposOffset(overlay, offsettype){
    var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
    var parentEl=overlay.offsetParent;
    while (parentEl!=null){
        totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
        parentEl=parentEl.offsetParent;
    }
    return totaloffset;
}

function overlay(curobj, subobj){
    if (document.getElementById){
        var subobj=document.getElementById(subobj)
        subobj.style.left=getposOffset(curobj, "left")+"px"
        subobj.style.top=getposOffset(curobj, "top")+"px"
        subobj.style.display="block"
        return false
    }
    else
    return true
}

function overlayclose(subobj){
    document.getElementById(subobj).style.display="none";
    document.getElementById('er_'+subobj).innerHTML = '';
}

function toggleDisplayStyleById(id){
if (document.getElementById)
   {
   var nodeObj = document.getElementById(id);
   var nodeObjStyle = document.getElementById(id).style.display;
   if (nodeObjStyle == 'block') {
        nodeObj.style.display = 'none';
   } else 
        nodeObj.style.display = 'block';
   }
}

</script>


JS;
$tpl->append('extra_head', $extra_head);



/*
<script type="text/javascript">
function displayForm()
{
    value = document.forms['schedule_match_form'].server_preference.value;

    document.getElementById('server_info_box').style.display = 'none';

    if (value == 'Home server') {
        document.getElementById('server_info_box').style.display = 'block';
    }
    return TRUE;

}
</script>
$tpl->assign('onload', 'displayForm();');
*/

define('TID', $_GET['tid']);
if (!checkNumber(TID) || TID == 0) { displayError('Team ID not specified.'); }

define('MID', $_GET['mid']);
if (!checkNumber(MID) || MID == 0) { displayError('Match ID not specified.'); }

$sql = 'SELECT away_tid, home_tid, lgname, report_date_gmt, UNIX_TIMESTAMP(stg_latest_match_date_gmt) AS max_schedule_unix_gmt, stg_latest_match_date_gmt, start_date_gmt, UNIX_TIMESTAMP(start_date_gmt) AS unix_start_date_gmt, confirmed_mpid, (SELECT name FROM teams WHERE tid=away_tid LIMIT 1) AS away_name, (SELECT name FROM teams WHERE tid=home_tid LIMIT 1) AS home_name FROM matches INNER JOIN schedules USING (sch_id) INNER JOIN teams ON (away_tid = teams.tid OR home_tid = teams.tid) INNER JOIN seasons ON (seasons.sid = schedules.sid) INNER JOIN leagues ON (leagues.lid = seasons.lid) WHERE mid = ? AND schedules.deleted = 0 AND matches.deleted = 0 LIMIT 1';
$matchData =& $db->getRow($sql, array(MID));
$tpl->assign('matchData', $matchData);
//$tpl->assign('home_name', $matchData['home_name']);
//$tpl->assign('away_name', $matchData['away_name']);

if (empty($matchData)) displayError('No match by that ID found.');
if ($matchData['away_tid'] != TID && $matchData['home_tid'] != TID) displayError('You are not authorized to access this function.');

$sql = 'SELECT lid, tid, teams.name, captain_uid, owner_uid FROM teams INNER JOIN organizations USING (orgid) WHERE tid = ? AND teams.deleted = 0 LIMIT 1';
$teamData =& $db->getRow($sql, array(TID));
if (empty($teamData)) displayError('Team not found.');

$sql = 'SELECT permission_reschedule FROM rosters WHERE leave_date_gmt = "0000-00-00 00:00:00" AND uid = ? AND tid = ? LIMIT 1';
$permissionScheduler =& $db->getOne($sql, array(UID, TID));

if ($teamData['captain_uid'] !== UID && $teamData['owner_uid'] !== UID && !$permissionScheduler) displayError('You are not authorized to access this function.');
define('LID', $teamData['lid']);

if ($matchData['report_date_gmt'] != '0000-00-00 00:00:00') displayError('This match has already been reported');
if ($matchData['home_tid'] == 0 || $matchData['away_tid'] == 0) displayError('This match is a bye week.');


$sql = <<<SQL
            SELECT TRUE FROM matches_proposed WHERE `mid` = ? AND proposed_tid <> ? AND status IN ("Declined", "Accepted", "Pending") LIMIT 1 
            UNION 
            SELECT TRUE FROM matches_proposed WHERE `mid` = ? AND proposed_tid = ? AND status IN ("Declined", "Accepted") LIMIT 1
SQL;
$opponentAttemptedSchedule =& $db->getOne($sql, array(MID, TID, MID, TID));
if (!$opponentAttemptedSchedule && $matchData['unix_start_date_gmt'] - 86400 < gmmktime()) {
    $tpl->assign('can_file_ff', TRUE);
}
//$tpl->assign('match_start_time', $matchData['unix_start_date_gmt']);



$sql = 'SELECT server_location, server_available FROM `matches` INNER JOIN `teams` ON (home_tid = teams.tid OR away_tid = teams.tid) WHERE `mid` = ?';
$serversInfo =& $db->getAll($sql, array(MID));

if ($matchData['home_tid'] == TID) {
    $opponentTID = $matchData['away_tid'];
    $opponentName = $matchData['away_name'];
    $teamName = $matchData['home_name'];
} else {
    $opponentTID = $matchData['home_tid'];
    $opponentName = $matchData['home_name'];
    $teamName = $matchData['away_name'];
}

$sql = 'SELECT server_location, server_available, server_ip, server_port, server_pw, hltv_ip, hltv_port, hltv_pw FROM teams WHERE tid = ?';
$homeServerInfo =& $db->getRow($sql, array($matchData['home_tid']));
$awayServerInfo =& $db->getRow($sql, array($matchData['away_tid']));

$tpl->assign('home_server_info', $homeServerInfo);
$tpl->assign('away_server_info', $awayServerInfo);

loadTimeZone(LID);

function sendSchedulerNotification($opponentTID, $opponentName, $teamName)
{
    global $db;

    // get a list of all UIDs on the opponent who are either schedulers or the captain.
    $sql = 'SELECT captain_uid FROM teams WHERE tid = ? LIMIT 1';
    $oppCaptainUID =& $db->getCol($sql, 0, array($opponentTID));
    $sql = 'SELECT uid FROM rosters WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" AND permission_reschedule = 1';
    $oppSchedulers =& $db->getCol($sql, 0, array($opponentTID));
    $oppNotify = array_unique(array_merge($oppCaptainUID, $oppSchedulers));

    // find some good info
    $sql = 'SELECT leagues.league_title, leagues.lgname, schedules.stg_short_desc FROM leagues INNER JOIN seasons USING (lid) INNER JOIN schedules USING (sid) INNER JOIN matches USING (sch_id) WHERE `mid` = ? LiMIT 1';
    $matchInfo =& $db->getRow($sql, array(MID));
    $notificationVars = array(
                              'lgname' => $matchInfo['lgname'].'/',
                              'url' => 'http://www.tpgleague.org/schedule.match.php?mid='.MID.'&tid='.$opponentTID,
                              'opponent_team' => $teamName,
                              'your_team' => $opponentName,
                              'league_title' => $matchInfo['league_title'],
                              'week' => $matchInfo['stg_short_desc']
                             );
    foreach ($oppNotify as $notifyUID) {
        sendMessage($notifyUID, 'notification.scheduler', $notificationVars);
    }
}


if ($_POST['submit'] == 'Accept this match time' || $_POST['submit'] == 'Decline this match time') {
    define('MPID', $_POST['mpid']);
    // only the reviewer_tid of this mpid may modify it
    $sql = 'SELECT reviewer_tid FROM matches_proposed WHERE mpid = ? LIMIT 1';
    $rvtid =& $db->getOne($sql, array(MPID));
    if ($rvtid !== TID) { displayError('There was an error with the form you submitted.'); }

    $sql = 'SELECT status, home_server_choice, proposed_date_gmt FROM matches_proposed WHERE mpid = ? AND reviewer_tid = ? LIMIT 1';
    $propRow =& $db->getRow($sql, array(MPID, TID));
    $currentStatus = $propRow['status'];
    $serverChoice = $propRow['home_server_choice'];
    if ((($serverChoice != 'Home server') && ($serverChoice != 'Away server')) && $_POST['server_preference']) {
        $serverChoice = $_POST['server_preference'];
    }
    $acceptedGMT = $propRow['proposed_date_gmt'];
    if ($currentStatus == 'Pending' && $_POST['submit'] == 'Accept this match time') {

        $valuesArray = array(
                             'status'               => 'Accepted',
                             'reviewer_uid'         => UID,
                             'home_server_choice'   => $serverChoice,
                             'review_date_gmt'      => mysqlNow()
                            );
        $proposalUpdate = new updateRecord('matches_proposed', 'mpid', MPID);
        $proposalUpdate->addData($valuesArray);
        $proposalUpdate->UpdateData();
        /*
        $sql = 'UPDATE matches_proposed SET status = "Accepted", reviewer_uid = ?, home_server_choice = ?, review_comments = ?, review_date_gmt = NOW() WHERE mpid = ? AND reviewer_tid = ?';
        $db->query($sql, array(UID, $serverChoice, $_POST['review_comments'], MPID, TID));
        */
        
        $valuesArray = array(
                             'start_date_gmt'   => $acceptedGMT,
                             'server'           => $serverChoice,
                             'confirmed_mpid'   => MPID
                            );
        $proposalUpdate = new updateRecord('matches', 'mid', MID);
        $proposalUpdate->addData($valuesArray);
        $proposalUpdate->UpdateData();

        sendSchedulerNotification($opponentTID, $opponentName, $teamName);
        redirect();
        /*
        $sql = 'UPDATE matches SET start_date_gmt = ?, server = ?, confirmed_mpid = ? WHERE mid = ?';
        $db->query($sql, array($acceptedGMT, $serverChoice, MPID, MID));
        */
    } elseif ($currentStatus == 'Pending' && $_POST['submit'] == 'Decline this match time') {

        $valuesArray = array(
                             'status'               => 'Declined',
                             'reviewer_uid'         => UID,
                             'home_server_choice'   => $serverChoice,
                             'review_date_gmt'      => mysqlNow()
                            );
        $proposalUpdate = new updateRecord('matches_proposed', 'mpid', MPID);
        $proposalUpdate->addData($valuesArray);
        $proposalUpdate->UpdateData();
        sendSchedulerNotification($opponentTID, $opponentName, $teamName);
        redirect();
        /*
        $sql = 'UPDATE matches_proposed SET status = "Declined", reviewer_uid = ?, review_comments = ?, review_date_gmt = NOW() WHERE mpid = ? AND reviewer_tid = ?';
        $db->query($sql, array(UID, $_POST['review_comments'], MPID, TID));
        */

    }
} elseif ($_POST['submit'] == 'Delete this proposal') {
    define('MPID', $_POST['mpid']);
    $sql = 'SELECT proposed_tid FROM matches_proposed WHERE mpid = ? AND status = "Pending" LIMIT 1';
    $prptid =& $db->getOne($sql, array(MPID));
    if ($prptid == TID) {
        $valuesArray = array(
                             'review_date_gmt' => mysqlNow(),
                             'status' => 'Deleted'
                            );
        $proposalUpdate = new updateRecord('matches_proposed', 'mpid', MPID);
        $proposalUpdate->addData($valuesArray);
        $proposalUpdate->UpdateData();
        sendSchedulerNotification($opponentTID, $opponentName, $teamName);
        redirect();
        /*
        $sql = 'UPDATE matches_proposed SET status = "Deleted" WHERE mpid = ?';
        $db->query($sql, array(MPID));
        */
    }
}

// find the current active season
//$sql = 'SELECT sid FROM seasons WHERE lid = ? AND active = 1 LIMIT 1';
//$sid =& $db->getOne($sql, array(LID));
//define('SID', $sid);


$scheduleMatchForm = new HTML_QuickForm('schedule_match_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$scheduleMatchForm->removeAttribute('name'); // XHTML compliance
$scheduleMatchForm->applyFilter('__ALL__', 'trim');

//$scheduleMatchForm->setDefaults($matchData);

//$scheduleMatchForm->addElement('static', 'mid', 'Match ID', MID);

$scheduleMatchForm->addElement('static', 'away_team', 'Away Team', '<div id="away_team" class="static"><a href="'. $matchData['lgname'] .'/team/'. $matchData['away_tid'] .'/">'. escape($matchData['away_name']) .'</a></div>');
$scheduleMatchForm->addElement('static', 'home_team', 'Home Team', '<div id="home_team" class="static"><a href="'. $matchData['lgname'] .'/team/'. $matchData['home_tid'] .'/">'. escape($matchData['home_name']) .'</a></div>');


$matchDate = dateToArray(mysqlGMTtoLocal($matchData['start_date_gmt']));
$latestMatchDate = dateToArray(mysqlGMTtoLocal($matchData['stg_latest_match_date_gmt']));

/*
$scheduleMatchForm->addElement('advcheckbox',
                 'comments_hide',   // name of advcheckbox
                 'Only make a comment',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox', 'style' => 'visibility: visible;', 'onclick' => 'showhide(\'start_hide\')'),      // string or array of attributes
                 array(0,1)
             );
$scheduleMatchForm->updateElementAttr(array('comments_hide'), array('id' => 'comments_hide'));
$scheduleMatchForm->addElement('static', 'note_comments_hide', 'Checkmark if you only wish to post a message to the other team.');
*/
$commentsPopupBox = <<<HTML
<div id="comments_hide" style="margin-bottom: 0.5em;"><a class="plus" onclick="toggleDisplayStyleById('comments_help');">What happened to the Comments checkbox?</a></div>
<div id="comments_help" style="display: none; margin: 1em; padding: 0.5em; width: auto; ">
    <a class="plus" onclick="toggleDisplayStyleById('comments_help');">(Close)</a><br />
    <p>If your match is already confirmed (accepted) for a date, then submitting the form with the same match time will not re-propose the match. It will instead simply post it as a comment automatically.</p>
</div>
HTML;
$scheduleMatchForm->addElement('static', 'comments_hide', '', $commentsPopupBox);

//$scheduleMatchForm->addElement('static', 'start_hide', '', '</div><div class="static" id="start_hide"><div>');
//$scheduleMatchForm->addElement('static', 'end_hide', '', '</div></div><div id="end_hide">');

if ($matchDate['hour'] > 12) {
    $hour_12 = $matchDate['hour']-12;
    $meridian = 'PM';
} elseif ($matchDate['hour'] == 0) {
    $hour_12 = 0;
    $meridian = 'AM';
} elseif ($matchDate['hour'] == 12) {
    $hour_12 = 0;
    $meridian = 'PM';
} else {
    $hour_12 = $matchDate['hour'];
    $meridian = 'AM';
}


$scheduleMatchForm->setDefaults(array('date' => array('Y' => $matchDate['year'], 'M' => $matchDate['month'], 'd' => $matchDate['day'], 'g' => $hour_12, 'A' => $meridian, 'i' => $matchDate['minute'])));
$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'giAdMY',
                     'optionIncrement' => array('i' => '5'),
                     'maxYear'         => $latestMatchDate['year'],
                     'minYear'         => date('Y'),
                     'addEmptyOption'  => array('i' => TRUE, 'g' => TRUE, 'd' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('i' => 'Min', 'g' => 'Hour', 'd' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
$scheduleMatchForm->addElement('date', 'date', 'Proposed Match Time', $dateOptions);
$scheduleMatchForm->addElement('static', 'note_date', 'Time and date ('.$matchDate['tz'].') (<a href="/edit.account.php?actedit=siteprefs">Change your local time zone</a>)');

if (!$_POST['comments_hide']) {
    $scheduleMatchForm->addRule('date', 'Please enter a valid date.', 'required');
    $scheduleMatchForm->registerRule('valid_date', 'function', 'checkValidDate');
    $scheduleMatchForm->addRule('date', 'Please enter a valid date.', 'valid_date');
}
if ($matchData['home_tid'] == TID) {
    $server_pref =& $scheduleMatchForm->addElement('select', 'server_preference', 'Server Choice');
    $server_pref->loadArray(
                       array(
                        'No preference' => 'No preference',
                        'Home server' => escape($matchData['home_name']) .'\'s server ('
                                       . ($homeServerInfo['server_location'] ? escape($homeServerInfo['server_location']) : 'Location unknown') . ', '
                                       . ($homeServerInfo['server_available'] ? 'available' : 'unavailable') .')',
                        'Away server' => escape($matchData['away_name']) .'\'s server ('
                                       . ($awayServerInfo['server_location'] ? escape($awayServerInfo['server_location']) : 'Location unknown') . ', '
                                       . ($awayServerInfo['server_available'] ? 'available' : 'unavailable') .')'
                            )
                           );
    $scheduleMatchForm->addRule('server_preference', 'Required', 'required');
}

$scheduleMatchForm->addElement('textarea', 'comments', 'Comments', array('rows' => 5, 'cols' => '50', 'onkeydown' => 'textCounter(this,"progressbar1",250)', 'onkeyup' => 'textCounter(this,"progressbar1",250)', 'onfocus' => 'textCounter(this,"progressbar1",250)'));
$scheduleMatchForm->addRule('comments', 'Comments may not exceed 250 characters.', 'maxlength', 250);
$scheduleMatchForm->addElement('static', 'note_comments', 'Maximum 250 characters.<div id="progressbar1" class="progress"></div><script type="text/javascript">textCounter(document.getElementById("comments"),"progressbar1",250)</script>');
$tpl->append('external_js', 'textarea.progressbar');


$scheduleMatchForm->addElement('submit', 'submit', 'Submit', array('class' => 'submit'));

// check if the other team has any pending proposals.
$sql = 'SELECT TRUE FROM matches_proposed WHERE `mid` = ? AND status = "Pending" LIMIT 1';
$pendingExists =& $db->getOne($sql, array(MID));
$tpl->assign('pending_proposals_exist', $pendingExists);


if ($scheduleMatchForm->validate()) {

    if ($pendingExists) {
        $tpl->assign('pending_exists_error', TRUE);
        //$scheduleMatchForm->setElementError('submit', 'You must accept or decline any existing proposals above before submitting new comments or proposals.');
    } else {
        if (!$_POST['comments_hide']) {
            $formDate = $scheduleMatchForm->exportValue('date');
            $year = $formDate['Y']+0;
            $month = $formDate['M']+0;
            $day = $formDate['d']+0;
            $hour_12 = $formDate['g']+0;
            $meridian = $formDate['A'];
            $minute = $formDate['i']+0;
            $second = 0;

            if ($meridian == 'AM') {
                if ($hour_12 < 12) $hour = $hour_12;
                else $hour = 0;
            } else {
                if ($hour_12 == 12) $hour = 12;
                else $hour = $hour_12 + 12;
            }

            $gmTime = gmstrftime('%Y-%m-%d %H:%M:%S', mktime($hour, $minute, $second, $month, $day, $year));
            $gmArray = dateToArray($gmTime);
            $convertedGMTunix = gmmktime($gmArray['hour'], $gmArray['minute'], $gmArray['second'], $gmArray['month'], $gmArray['day'], $gmArray['year']);
            if ($convertedGMTunix > $matchData['max_schedule_unix_gmt']) {
                $scheduleMatchForm->setElementError('date', 'Match date must be no later than '. smarty_modifier_easy_day($matchData['max_schedule_unix_gmt']) .', '. smarty_modifier_easy_time($matchData['max_schedule_unix_gmt']) .'.');
            } elseif ($matchData['confirmed_mpid'] && $convertedGMTunix == $matchData['unix_start_date_gmt']) {
                $_POST['comments_hide'] = TRUE;
                $changedCommented = TRUE;
                if ($scheduleMatchForm->exportValue('comments') == '') {
                    $scheduleMatchForm->setElementError('comments', 'The match is already confirmed for the time you selected. To make a comment, please fill out the box below.');
                    $commentsAreRequired = TRUE;
                }
            } elseif (!$changedCommented && $convertedGMTunix < gmmktime()) {
                $scheduleMatchForm->setElementError('date', 'Match date must be a date in the future.');
            } else {
                $timeValid = TRUE;
            }
        }

        $valuesArray = array(
                            'comments' => $scheduleMatchForm->exportValue('comments'),
                            'create_date_gmt' => mysqlNow(),
                            'proposed_tid' => TID,
                            'proposed_uid' => UID,
                            'reviewer_tid' => $opponentTID,
                            'mid' => MID
                           );
        if ($matchData['home_tid'] == TID && !$changedCommented) {
            $arrayPref = array('home_server_choice' => $scheduleMatchForm->exportValue('server_preference'));
            $valuesArray = array_merge($valuesArray, $arrayPref);
        }

        if ($_POST['comments_hide']) {
            $arrayStatus = array('status' => 'Message');
        } elseif ($timeValid) {
            $arrayStatus = array('status' => 'Pending', 'proposed_date_gmt' => $gmTime);

            // This clears out any previous existing PENDING proposals by setting them to either declined or deleted.
            // I believe this code is obsolete now, because you have to accept or decline any previous proposals before making new ones ($pendingExists)
            /*
            $sql = 'UPDATE matches_proposed SET status = IF(proposed_tid = ?, "Deleted", "Declined"), reviewer_uid = ?, review_date_gmt = NOW(), review_comments = "New proposal" WHERE mid = ? AND status = "Pending"';
            $db->query($sql, array(TID, UID, MID));
            */
        }
        $valuesArray = array_merge($valuesArray, $arrayStatus);

        if (!$commentsAreRequired && (($_POST['comments_hide']) || (!$_POST['comments_hide'] && $timeValid))) {
            $insertRecord = new InsertRecord();
            $insertRecord->insertData('matches_proposed', $valuesArray);
            //$mpid = $insertRecord->lastInsertId();
            //$res = $db->autoExecute('matches_proposed', $valuesArray, DB_AUTOQUERY_INSERT);
            sendSchedulerNotification($opponentTID, $opponentName, $teamName);
            redirect();
        }
    }

}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$scheduleMatchForm->accept($renderer);
$tpl->assign('schedule_match_form', $renderer->toArray());

$sql = 'SELECT mpid, UNIX_TIMESTAMP(matches_proposed.create_date_gmt) AS create_date_gmt, UNIX_TIMESTAMP(proposed_date_gmt) AS proposed_date_gmt, (SELECT name FROM teams WHERE tid=proposed_tid LIMIT 1) AS proposed_name, proposed_tid, (SELECT name FROM teams WHERE tid=reviewer_tid LIMIT 1) AS reviewer_name, reviewer_tid, comments, status, home_server_choice, home_tid, review_comments, away_tid FROM matches_proposed INNER JOIN matches USING (`mid`) WHERE mid = ? ORDER BY mpid DESC';
$proposalList =& $db->getAll($sql, array(MID));
$tpl->assign('proposal_list', $proposalList);


displayTemplate('schedule.match');