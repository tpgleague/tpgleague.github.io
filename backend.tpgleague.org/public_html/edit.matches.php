<?php



$extra_head[] = <<<EOT

        <script src="/js/report.match.sides.js" type="text/javascript"></script>

        <script type="text/javascript">

        /***********************************************
        * Overlapping Content link Dynamic Drive (www.dynamicdrive.com)
        * This notice must stay intact for legal use.
        * Visit http://www.dynamicdrive.com/ for full source code
        ***********************************************/

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

        </script>

        <style type="text/css">
a {text-decoration: none; }
a:link { color: blue; }
a:visited { color: blue; }

div.popup {
    border: 3px solid black;
    background-color: lightyellow;
    width: 220px;
    height: 160px;
    padding: 2px;
    position:absolute;
    display:none;
    text-align: left;
}

div.popup_close {
    text-align: left;
}

div.date {
 position:absolute;
 display:none
}

.subbtn {
    width: 100px;
    margin-top: 2px;
}

.plus, .qm {
    color: blue;
    cursor: pointer;
}
.qm {
    font-size: 0.8em;
    vertical-align: super;
}

.close {
    color: blue;
    cursor: pointer;
}
.pop_er {
    color: red;
}

.popup input {
    width: 100px;
}
div.wtf {
    float: left;
}

.auto_scheduler {
    font-size: 0.8em;
    font-weight: normal;
    text-decoration: none;
}

.auto_scheduler a:hover {
    text-decoration: underline;
}

        
        </style>


EOT;


require_once '../includes/inc.initialization.support.php';
require_once 'inc.func-schedule.php';
require_once 'inc.func-updateStandings.php';

if (!checkNumber($_GET['sch_id'])) { displayError('Error: Schedule ID not specified.'); }
else { define('SCH_ID', $_GET['sch_id']); }

$sql = 'SELECT schedules.*, IF(stg_type = "Preseason", 1, 0) AS ps FROM schedules WHERE sch_id = ? LIMIT 1';
$scheduleData =& $db->getRow($sql, array(SCH_ID));
$tpl->assign('schedule_data', $scheduleData);
$lid = $scheduleData['lid'];
$sid = $scheduleData['sid'];
$stg_match_date_gmt = $scheduleData['stg_match_date_gmt'];
define('SID', $sid);
define('LID', $lid);

if (isset($_POST['remove_pending'])) {
    //$sql = 'UPDATE matches_pending SET deleted = 1 WHERE mpnid = ?';
    //$db->query($sql, array($_GET['mpnid']));
    $updateRecord = new updateRecord('matches_pending', 'mpnid', $_POST['mpnid']);
    $updateRecord->addData(array('deleted' => 1));
    $updateRecord->UpdateData();
    redirect('/edit.matches.php?sch_id='.SCH_ID);
}
/*
elseif (isset($_GET['award_bye_win'])) {
    if (isset($_GET['mpnid'])) {
        //$sql = 'UPDATE matches_pending SET deleted = 1 WHERE mpnid = ?';
        //$db->query($sql, array($_GET['mpnid']));
        $updateRecord = new updateRecord('matches_pending', 'mpnid', $_GET['mpnid']);
        $updateRecord->addData(array('deleted' => 1));
        $updateRecord->UpdateData();
    }
    $scheduleResult = scheduleTeams(SCH_ID, $_GET['award_bye_win'], NULL, $court='auto', $_GET['award_bye_win']);
    if ($scheduleResult === TRUE) redirect('/edit.matches.php?sch_id='.SCH_ID);
    else $tpl->append('schedule_error', $scheduleResult);
}
*/

$sql = 'SELECT lsid, side FROM leagues_sides WHERE lid = ?';
$arraySides =& $db->getAssoc($sql, NULL, array(LID), TRUE);
$tpl->assign('league_sides', $arraySides);

// check the $_POST and schedule the team.
if ($_POST['submit'] == 'Schedule') {
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $win_tid = NULL;
    $notify = ($_POST['notify'])?1:0;
    if ($_POST['team1_ff'] || $_POST['team2_ff']) $notify = 0;

    if (in_array($team1, array('bye_win', 'bye_win_ff', 'bye_loss', 'bye_loss_ff'))) $tid1 = 0;
    elseif ($team1 === 'pending') $tid1 = NULL;
    else $tid1 = $team1;

    if (in_array($team2, array('bye_win', 'bye_win_ff', 'bye_loss', 'bye_loss_ff'))) $tid2 = 0;
    elseif ($team2 === 'pending') $tid2 = NULL;
    else $tid2 = $team2;


    // check if each team is actually in a division, conference and a group:
    $sql = 'SELECT divid, cfid, grpid FROM teams WHERE tid = ?';
    if (!empty($tid1)) {
        $tid1Location =& $db->getRow($sql, array($tid1));
        if (!$tid1Location['divid'] || !$tid1Location['cfid'] || !$tid1Location['grpid']) {
            $tpl->append('schedule_error', 'Team 1 does not appear to be assigned to a specific group, which is required for scheduling.');
            $scheduleFailure = TRUE;
        }
    }

    if (!empty($tid2)) {
        $tid2Location =& $db->getRow($sql, array($tid2));
        if (!$tid2Location['divid'] || !$tid2Location['cfid'] || !$tid2Location['grpid']) {
            $tpl->append('schedule_error', 'Team 2 does not appear to be assigned to a specific group, which is required for scheduling.');
            $scheduleFailure = TRUE;
        }
    }


    // check if teams are in the same division:
    if (!empty($tid1) && !empty($tid2)) {
        if ($tid1Location['divid'] != $tid2Location['divid']) {
            $tpl->append('schedule_error', 'Unable to schedule cross-divisional match');
            $scheduleFailure = TRUE;
        }
    }


    if      ($team1 === 'bye_win')     { $win_tid = $tid2; $forfeit = 0; }
    elseif  ($team1 === 'bye_win_ff')  { $win_tid = $tid2; $forfeit = 1; }
    elseif  ($team1 === 'bye_loss')    { $win_tid = 0; $forfeit = 0; }
    elseif  ($team1 === 'bye_loss_ff') { $win_tid = 0; $forfeit = 1; }

    if      ($team2 === 'bye_win')     { $win_tid = $tid1; $forfeit = 0; }
    elseif  ($team2 === 'bye_win_ff')  { $win_tid = $tid1; $forfeit = 1; }
    elseif  ($team2 === 'bye_loss')    { $win_tid = 0; $forfeit = 0; }
    elseif  ($team2 === 'bye_loss_ff') { $win_tid = 0; $forfeit = 1; }


    // if scores were submitted, validate that part of the form:
    if ($_POST['h1a_score'] || $_POST['h1h_score'] || $_POST['h2a_score'] || $_POST['h2h_score']) {
        // match is being reported!!
        $reportMatch = TRUE;
        $notify = 0;

        if (
            !checkNumber($_POST['h1a_score'])
         || !checkNumber($_POST['h1h_score'])
         || !checkNumber($_POST['h2a_score'])
         || !checkNumber($_POST['h2h_score'])

         || !checkNumber($_POST['side_selector_h1a'])
         || !checkNumber($_POST['side_selector_h1h'])
         || !checkNumber($_POST['side_selector_h2a'])
         || !checkNumber($_POST['side_selector_h2h'])
        ) {
            $tpl->append('schedule_error', 'You must fill out the scores (and sides) to be reported <b>completely</b> (if you intent to report the match), or leave the scores completely blank');
            $scheduleFailure = TRUE;
         }

        if ($_POST['team1_ff'] || $_POST['team2_ff']) {
            $tpl->append('schedule_error', 'The match must not be marked as forfeit before you can enter scores.');
            $scheduleFailure = TRUE;
        }
    }

    // check if teams are already scheduled for a match. if so, delete old match, schedule old opponent against bye loss (ff).
    $sql = 'SELECT `mid`, IF(away_tid = ?, home_tid, away_tid) AS opponent_tid FROM matches WHERE sch_id = ? AND matches.deleted = 0 AND (away_tid = ? OR home_tid = ?) LIMIT 1';
    if (!empty($tid1)) {
        $tid1PreviousMatch =& $db->getRow($sql, array($tid1, SCH_ID, $tid1, $tid1));
        if ($tid1PreviousMatch && !$scheduleFailure) {
            if (!$_POST['override_makeup_match']) {
                $tpl->append('schedule_error', 'Team 1 is already scheduled for a <a href="/edit.match.php?mid='.$tid1PreviousMatch['mid'].'">match</a>.');
                $scheduleFailure = TRUE;
            } else {
                $updateRecord = new updateRecord('matches', 'mid', $tid1PreviousMatch['mid']);
                $updateRecord->addData(array('deleted' => 1));
                $updateRecord->updateData();
                if ($tid1PreviousMatch['opponent_tid']) scheduleTeams(SCH_ID, $tid1PreviousMatch['opponent_tid'], 0, 'auto', 0, 1, 0);
            }
        }
    }
    if (!empty($tid2)) {
        $tid2PreviousMatch =& $db->getRow($sql, array($tid2, SCH_ID, $tid2, $tid2));
        if ($tid2PreviousMatch && !$scheduleFailure) {
            if (!$_POST['override_makeup_match']) {
                $tpl->append('schedule_error', 'Team 2 is already scheduled for a <a href="/edit.match.php?mid='.$tid2PreviousMatch['mid'].'">match</a>.');
                $scheduleFailure = TRUE;
            } else {
                $updateRecord = new updateRecord('matches', 'mid', $tid2PreviousMatch['mid']);
                $updateRecord->addData(array('deleted' => 1));
                $updateRecord->updateData();
                if ($tid2PreviousMatch['opponent_tid']) scheduleTeams(SCH_ID, $tid2PreviousMatch['opponent_tid'], 0, 'auto', 0, 1, 0);
            }
        }
    }

    if (!$scheduleFailure) {

        // check if teams are already in pending queue.  remove them if so.
        $sql = 'SELECT mpnid FROM matches_pending WHERE sch_id = ? AND tid = ? AND deleted = 0 AND `mid` IS NULL LIMIT 1';
        if ($tid1) {
            $tidInPending =& $db->getOne($sql, array(SCH_ID, $tid1));
            if ($tidInPending) {
                $updateRecord = new updateRecord('matches_pending', 'mpnid', $tidInPending);
                $updateRecord->addData(array('deleted' => 1));
                $updateRecord->updateData();
            }
            unset($tidInPending);
        }
        if ($tid2) {
            $tidInPending =& $db->getOne($sql, array(SCH_ID, $tid2));
            if ($tidInPending) {
                $updateRecord = new updateRecord('matches_pending', 'mpnid', $tidInPending);
                $updateRecord->addData(array('deleted' => 1));
                $updateRecord->updateData();
            }
        }

        $scheduleResult = scheduleTeams(SCH_ID, $tid1, $tid2, $_POST['court'], $win_tid, $forfeit, $notify);
        if ($scheduleResult !== TRUE && $scheduleResult !== 'PENDING_QUEUE' && !checkNumber($scheduleResult)) {
            $tpl->append('schedule_error', $scheduleResult);
        } else {
            if ($scheduleResult !== 'PENDING_QUEUE') {
                $addAdminNote = trim($_POST['match_admin_note']);
                if (!empty($addAdminNote)) {
                    $notesValues = array(
                                          'mid' => $scheduleResult,
                                          'aid' => AID,
                                          'create_date_gmt' => mysqlNow(),
                                          'comment' => $addAdminNote
                                         );
                    $res = $db->autoExecute('matches_admin_notes', $notesValues, DB_AUTOQUERY_INSERT);
                }

                if ((!empty($tid1) && !empty($tid2)) && ($_POST['team1_ff'] || $_POST['team2_ff'])) {
                    $forfeit_home = 0;
                    $forfeit_away = 0;
                    $sql = 'SELECT home_tid FROM matches WHERE `mid` = ?';
                    $home_tid =& $db->getOne($sql, array($scheduleResult));
                    if ($home_tid == $tid1) {
                        if ($_POST['team1_ff']) $forfeit_home = 1;
                        if ($_POST['team2_ff']) $forfeit_away = 1;
                    } else {
                        if ($_POST['team2_ff']) $forfeit_home = 1;
                        if ($_POST['team1_ff']) $forfeit_away = 1;
                    }
                    if ($forfeit_home && $forfeit_away) $win_tid = NULL;
                    elseif ($forfeit_home && !$forfeit_away) {
                        if ($home_tid == $tid1) $win_tid = $tid2;
                        else $win_tid = $tid1;
                    }
                    elseif (!$forfeit_home && $forfeit_away) {
                        if ($home_tid == $tid1) $win_tid = $tid1;
                        else $win_tid = $tid2;
                    }
                    $reportArray = array(
                                        'forfeit_away' => $forfeit_away,
                                        'forfeit_home' => $forfeit_home,
                                        'report_by_aid' => AID,
                                        'win_tid' => $win_tid,
                                        'report_date_gmt' => mysqlNow()
                                        );
                    $updateRecord = new updateRecord('matches', 'mid', $scheduleResult);
                    $updateRecord->addData($reportArray);
                    $updateRecord->updateData();
                }

                if ($reportMatch && !$forfeit && (!empty($tid1) && !empty($tid2))) {

                    $h1t1_side = $_POST['side_selector_h1a'];
                    $h1t2_side = $_POST['side_selector_h1h'];
                    $h2t1_side = $_POST['side_selector_h2a'];
                    $h2t2_side = $_POST['side_selector_h2h'];

                    $h1t1_score = $_POST['h1a_score'];
                    $h1t2_score = $_POST['h1h_score'];
                    $h2t1_score = $_POST['h2a_score'];
                    $h2t2_score = $_POST['h2h_score'];

                    $t1_score = $h1t1_score + $h2t1_score;
                    $t2_score = $h1t2_score + $h2t2_score;

                    $tie = 0;
                    if ($t1_score > $t2_score) {
                        $win_tid = $tid1;
                    } elseif ($t1_score < $t2_score) {
                        $win_tid = $tid2;
                    } else {
                        $win_tid = NULL;
                        $tie = 1;
                    }

                    $sql = 'SELECT home_tid FROM matches WHERE `mid` = ?';
                    $home_tid =& $db->getOne($sql, array($scheduleResult));
                    if ($home_tid == $tid1) {
                        $h1a_score = $h1t1_score;
                        $h2a_score = $h2t1_score;
                        $h1h_score = $h1t2_score;
                        $h2h_score = $h2t2_score;

                        $h1a_side = $h1t1_side;
                        $h2a_side = $h2t1_side;
                        $h1h_side = $h1t2_side;
                        $h2h_side = $h2t2_side;

                    } else {
                        $h1a_score = $h1t2_score;
                        $h2a_score = $h2t2_score;
                        $h1h_score = $h1t1_score;
                        $h2h_score = $h2t1_score;

                        $h1a_side = $h1t2_side;
                        $h2a_side = $h2t2_side;
                        $h1h_side = $h1t1_side;
                        $h2h_side = $h2t1_side;
                    }

                    //$sql = 'UPDATE matches SET report_by_aid = ?, report_date_gmt = NOW(), win_tid = ?, tie = ? WHERE `mid` = ?';
                    //$res =& $db->query($sql, array(AID, $win_tid, $tie, $scheduleResult));
                    $reportArray = array(
                                          'report_by_aid' => AID,
                                          'report_date_gmt' => mysqlNow(),
                                          'win_tid' => $win_tid,
                                          'tie' => $tie
                                         );
                    $updateRecord = new updateRecord('matches', 'mid', $scheduleResult);
                    $updateRecord->addData($reportArray);
                    $updateRecord->updateData();

                    $sql = 'INSERT INTO matches_scores (mid, away_score, home_score, away_lsid, home_lsid) '
                         . 'VALUES (?, ?, ?, ?, ?) ';
                    $res =& $db->query($sql, array($scheduleResult, $h1a_score, $h1h_score, $h1a_side, $h1h_side));
                    $res =& $db->query($sql, array($scheduleResult, $h2a_score, $h2h_score, $h2a_side, $h2h_side));

                }
            }
            if ($tid1) calculateTeamStandings($tid1, SID, $scheduleData['ps']);
            if ($tid2) calculateTeamStandings($tid2, SID, $scheduleData['ps']);
            redirect();
        }
    }
}

require_once 'inc.initialization.display.php';


$editScheduleForm = new HTML_QuickForm('edit_schedule_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editScheduleForm->removeAttribute('name'); // XHTML compliance
$editScheduleForm->applyFilter('__ALL__', 'trim');
$editScheduleForm->registerRule('valid_date', 'function', 'checkValidDate');
$editScheduleForm->setDefaults($scheduleData);

//$editScheduleForm->addElement('static', 'sch_id', 'Schedule ID', SCH_ID);
$editScheduleForm->addElement('text', 'sch_id', 'Schedule ID');
$editScheduleForm->freeze('sch_id');

$editScheduleForm->addElement('text', 'stg_number', 'Stage Number');
$editScheduleForm->addRule('stg_number', 'Stage Number is required.', 'required');
$editScheduleForm->addRule('stg_number', 'Stage Number must be a number.', 'numeric');
$editScheduleForm->addRule('stg_number', 'Stage Number must not be zero.', 'nonzero');

$matchMap =& $editScheduleForm->addElement('select', 'mapid', 'Map');
$matchMap->loadArray(array('' => '[TBA]'));
$matchMap->loadArray($db->getAssoc('SELECT mapid, map_title FROM `maps` WHERE lid = '. $db->quoteSmart(LID)));
//$editScheduleForm->addRule('mapid', 'Map is required.', 'required');

$matchTypes =& $editScheduleForm->addElement('select', 'stg_type', 'Stage Type');
$matchTypes->loadArray(getEnumOptions('schedules', 'stg_type'));
$editScheduleForm->addRule('stg_type', 'Stage Type is required.', 'required');


$editScheduleForm->addElement('text', 'stg_short_desc', 'Stage Short Desc.');
$editScheduleForm->addRule('stg_short_desc', 'Short Description is required.', 'required');

$editScheduleForm->addElement('advcheckbox',
                 'deleted',   // name of advcheckbox
                 'Deleted',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editScheduleForm->updateElementAttr(array('deleted'), array('id' => 'deleted'));
$editScheduleForm->addElement('static', 'note_deleted', 'Deletes the schedule date and effectively deletes all associated matches.');


/*
$localDateTime = mysqlGMTtoLocal($scheduleData['stg_match_date_gmt']);

$localParts = explode(' ', $localDateTime);
$localDate = $localParts[0];
$localTime = $localParts[1];

$localDateParts = explode('-', $localDate);
$localDateYear = $localDateParts[0];
$localDateMonth = $localDateParts[1]+0;
$localDateDay = $localDateParts[2]+0;
$localDate = $localDateYear .'-'. $localDateMonth .'-'.  $localDateDay;

$localTimeParts = explode(':', $localTime);
$localTimeHour = $localTimeParts[0];
$localTimeMinute = $localTimeParts[1];

$editScheduleForm->setDefaults(array('time' => $localTimeHour .':'. $localTimeMinute));
$editScheduleForm->setDefaults(array('date' => array('Y' => $localDateYear, 'M' => $localDateMonth, 'd' => $localDateDay)));


$editScheduleForm->addElement('text', 'time', 'Time', array('maxlength' => '5', 'size' => '7'));
$editScheduleForm->addRule('time', 'A time is required.', 'required');
$editScheduleForm->addElement('static', 'note_time', '24HH time in default league time zone ('.date('T').'). 9pm is 21:00.');

$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'dMY',
                     'maxYear'         => date('Y')-1,
                     'minYear'         => date('Y')+1,
                     'addEmptyOption'  => array('d' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('d' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
$editScheduleForm->addElement('date', 'date', 'Date', $dateOptions);
$editScheduleForm->addRule('date', 'Please enter a valid date.', 'required');
$editScheduleForm->addRule('date', 'Please enter a valid date.', 'valid_date');
*/

////////////////     stg_match_date_gmt    ///////////////
$matchDate = dateToArray(mysqlGMTtoLocal($scheduleData['stg_match_date_gmt']));

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

$editScheduleForm->setDefaults(array('stg_match_date_gmt' => array('Y' => $matchDate['year'], 'M' => $matchDate['month'], 'd' => $matchDate['day'], 'g' => $hour_12, 'A' => $meridian, 'i' => $matchDate['minute'])));
$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'giAdMY',
                     'optionIncrement' => array('i' => '5'),
                     'maxYear'         => date('Y', strtotime('+1 years')),
                     'minYear'         => date('Y'),
                     'addEmptyOption'  => array('i' => TRUE, 'g' => TRUE, 'd' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('i' => 'Min', 'g' => 'Hour', 'd' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
$editScheduleForm->addElement('date', 'stg_match_date_gmt', 'Default Schedule Date', $dateOptions);
$editScheduleForm->addElement('static', 'note_stg_match_date_gmt', 'Time and date ('.date('T').')');
$editScheduleForm->addRule('stg_match_date_gmt', 'Please enter a valid date.', 'required');
$editScheduleForm->addRule('stg_match_date_gmt', 'Please enter a valid date.', 'valid_date');
///////////////////////////////////////////////////////




////////////////     stg_latest_match_date_gmt    ///////////////
$matchDate = dateToArray(mysqlGMTtoLocal($scheduleData['stg_latest_match_date_gmt']));

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

$editScheduleForm->setDefaults(array('stg_latest_match_date_gmt' => array('Y' => $matchDate['year'], 'M' => $matchDate['month'], 'd' => $matchDate['day'], 'g' => $hour_12, 'A' => $meridian, 'i' => $matchDate['minute'])));
$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'giAdMY',
                     'optionIncrement' => array('i' => '5'),
                     'maxYear'         => date('Y', strtotime('+1 years')),
                     'minYear'         => date('Y'),
                     'addEmptyOption'  => array('i' => TRUE, 'g' => TRUE, 'd' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('i' => 'Min', 'g' => 'Hour', 'd' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
$editScheduleForm->addElement('date', 'stg_latest_match_date_gmt', 'Latest Schedule Date', $dateOptions);
$editScheduleForm->addElement('static', 'note_stg_latest_match_date_gmt', 'Time and date ('.date('T').')');
$editScheduleForm->addRule('stg_latest_match_date_gmt', 'Please enter a valid date.', 'required');
$editScheduleForm->addRule('stg_latest_match_date_gmt', 'Please enter a valid date.', 'valid_date');
///////////////////////////////////////////////////////







$editScheduleForm->addElement('submit', 'submit', 'Edit Schedule');

if ($editScheduleForm->validate()) {

/*
    $date = $editScheduleForm->exportValue('date');
    $time = $editScheduleForm->exportValue('time');
    $time = explode(':', $time);
    $match_date_gmt = gmstrftime("%Y-%m-%d %H:%M:%S", mktime($time[0], $time[1], 0, $date['M'], $date['d'], $date['Y']));
*/

    // stg_match_date_gmt
    $formDate = $editScheduleForm->exportValue('stg_match_date_gmt');
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
    $match_date_gmt_timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    $match_date_gmt = gmstrftime('%Y-%m-%d %H:%M:%S', $match_date_gmt_timestamp);
    ////////

    // stg_latest_match_date_gmt
    $formDate = $editScheduleForm->exportValue('stg_latest_match_date_gmt');
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
    $latest_match_date_gmt_timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    $latest_match_date_gmt = gmstrftime('%Y-%m-%d %H:%M:%S', $latest_match_date_gmt_timestamp);
    ////////

    if ($latest_match_date_gmt_timestamp < $match_date_gmt_timestamp) {
        $editScheduleForm->setElementError('stg_latest_match_date_gmt', 'Latest Match Date must be equal to or later than the Match Date.');
        $editScheduleFormError = TRUE;
    }

    $mapid = $editScheduleForm->exportValue('mapid');
    if (empty($mapid)) $mapid = NULL;

    if (!$editScheduleFormError) {
        $valuesArray = array(
                            'mapid' => $mapid,
                            'stg_short_desc' => $editScheduleForm->exportValue('stg_short_desc'),
                            'stg_number' => $editScheduleForm->exportValue('stg_number'),
                            'stg_type' => $editScheduleForm->exportValue('stg_type'),
                            'deleted' => $editScheduleForm->exportValue('deleted'),
                            'stg_match_date_gmt' => $match_date_gmt,
                            'stg_latest_match_date_gmt' => $latest_match_date_gmt
                            );
        $updateRecord = new updateRecord('schedules', 'sch_id', SCH_ID);
        $updateRecord->addData($valuesArray);
        $updateRecord->updateData();

    /*
        if ($editScheduleForm->exportValue('deleted') == 1) {
            $sql = 'UPDATE matches SET deleted = 1 WHERE sch_id = '. $db->quoteSmart(SCH_ID);
            $db->query($sql);
        }
    */

        // if the stg_type was changed or the stage was deleted or undeleted, then all teams' standings must be recalculated.
        // only need to grab teams that are already scheduled for this match date.
        if ($scheduleData['stg_type'] != $editScheduleForm->exportValue('stg_type')) {
            $sql = 'SELECT tid FROM teams INNER JOIN matches ON (matches.away_tid = teams.tid OR matches.home_tid = teams.tid) WHERE matches.sch_id = ? AND matches.deleted = 0';
            $tidList =& $db->getCol($sql, 0, array(SCH_ID));
            require_once 'inc.func-updateStandings.php';
            foreach ($tidList as $teamID) {
                calculateTeamStandings($teamID, $scheduleData['sid'], 1);
                calculateTeamStandings($teamID, $scheduleData['sid'], 0);
            }
        } elseif ($scheduleData['deleted'] != $editScheduleForm->exportValue('deleted')) {
            $sql = 'SELECT IF(stg_type = "Preseason", 1, 0) AS ps FROM schedules WHERE sch_id = ?';
            $ps =& $db->getOne($sql, array(SCH_ID));
            $sql = 'SELECT tid FROM teams INNER JOIN matches ON (matches.away_tid = teams.tid OR matches.home_tid = teams.tid) WHERE matches.sch_id = ? AND matches.deleted = 0';
            $tidList =& $db->getCol($sql, 0, array(SCH_ID));
            require_once 'inc.func-updateStandings.php';
            foreach ($tidList as $teamID) {
                calculateTeamStandings($teamID, $scheduleData['sid'], $ps);
            }
        }
    }

}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editScheduleForm->accept($renderer);
$tpl->assign('edit_schedule_form', $renderer->toArray());









$scheduleNotesForm = new HTML_QuickForm('schedule_notes_form', 'post', $qfAction, NULL, NULL, TRUE);
$scheduleNotesForm->removeAttribute('name');
$scheduleNotesForm->applyFilter('__ALL__', 'trim');
$scheduleNotesForm->addElement('textarea', 'comment', 'Comment', array('rows' => 5, 'cols' => '50'));
$scheduleNotesForm->addElement('submit', 'submit', 'Add note');
$scheduleNotesForm->addRule('comment', 'Please enter a comment.', 'required');
if ($scheduleNotesForm->validate()) {
    $scheduleNotesValues = array(
                              'sch_id' => SCH_ID,
                              'aid' => AID,
                              'create_date_gmt' => mysqlNow(),
                              'comment' => $scheduleNotesForm->exportValue('comment')
                             );
    $res = $db->autoExecute('schedules_admin_notes', $scheduleNotesValues, DB_AUTOQUERY_INSERT);
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$scheduleNotesForm->accept($renderer);
$tpl->assign('schedule_notes_form', $renderer->toArray());

$sql = 'SELECT admin_name, aid, UNIX_TIMESTAMP(schedules_admin_notes.create_date_gmt) AS unix_create_date_gmt, `comment` FROM schedules_admin_notes INNER JOIN admins USING (aid) WHERE sch_id = ? ORDER BY sanid DESC';
$scheduleNotes =& $db->getAll($sql, array(SCH_ID));
$tpl->assign('schedule_notes', $scheduleNotes);



// get matches already scheduled for this week:
$sql = <<<SQL
    SELECT DISTINCT `mid`, matches.deleted, home_tid, 
        (SELECT UNIX_TIMESTAMP(proposed_date_gmt) FROM matches_proposed WHERE matches_proposed.`mid` = matches.`mid` AND status = "Accepted" ORDER BY mpid DESC LIMIT 1) AS start_date_gmt, 
        (SELECT TRUE FROM matches_proposed WHERE matches_proposed.`mid` = matches.`mid` AND proposed_tid = matches.away_tid LIMIT 1) AS away_messages,
        (SELECT TRUE FROM matches_proposed WHERE matches_proposed.`mid` = matches.`mid` AND proposed_tid = matches.home_tid LIMIT 1) AS home_messages,
        (SELECT TRUE FROM matches_proposed WHERE matches_proposed.`mid` = matches.`mid` LIMIT 1) AS proposals_exist, 
        (SELECT name FROM teams WHERE tid = home_tid LIMIT 1) AS home_name, away_tid, 
        (SELECT name FROM teams WHERE tid = away_tid LIMIT 1) AS away_name 
    FROM matches WHERE sch_id = ?
SQL;
$scheduled =& $db->getAll($sql, array(SCH_ID));
foreach ($scheduled as $matchArray) {
    if (!$matchArray['deleted']) {
        if (!empty($matchArray['home_tid'])) $scheduledTeamsList[$matchArray['home_tid']] = TRUE;
        if (!empty($matchArray['away_tid'])) $scheduledTeamsList[$matchArray['away_tid']] = TRUE;
    }
}
$tpl->assign('scheduled', $scheduled);
$tpl->assign('scheduled_teams_list', $scheduledTeamsList);


// get divisions, conferences, groups for this league.
$listingsData = getScheduleListings(LID, SCH_ID, $stg_match_date_gmt);
$tpl->assign('listings_groups', $listingsData['groups']);
$tpl->assign('listings_divisions', $listingsData['divisions']);
$tpl->assign('listings_conferences', $listingsData['conferences']);
$tpl->assign('listings_teams', $listingsData['teams']);
$tpl->assign('listings_pending', $listingsData['pending']);

$sql = <<<SQL
            SELECT tid, name, inactive, divid 
            FROM teams 
            WHERE lid = ? AND deleted = 0 AND approved = 1 
            AND teams.create_date_gmt <= ?
            AND divid IS NOT NULL AND cfid IS NOT NULL AND grpid IS NOT NULL 
            ORDER BY name ASC
SQL;
$teamList =& $db->getAll($sql, array(LID, $scheduleData['stg_match_date_gmt']));
$tpl->assign('team_list', $teamList);


displayTemplate('edit.matches');


function getScheduleListings($lid, $sch_id, $stg_match_date_gmt)
{
    global $db, $tpl;

    $divisions =& $db->getAssoc('SELECT divid, division_title, divisions.inactive FROM divisions WHERE lid = ' . $db->quoteSmart($lid) . ' AND divisions.inactive = 0', TRUE);
    $conferences =& $db->getAssoc('SELECT divid, cfid, conference_title, conferences.inactive FROM conferences INNER JOIN divisions USING (divid) WHERE divisions.lid = ' . $db->quoteSmart($lid) . ' AND conferences.inactive = 0', NULL, NULL, NULL, TRUE);
    $groups =& $db->getAssoc('SELECT conferences.cfid, groups.grpid, group_title, groups.inactive FROM groups INNER JOIN conferences USING (cfid) INNER JOIN divisions USING (divid) WHERE divisions.lid = ' . $db->quoteSmart($lid) . ' AND groups.inactive = 0', NULL, NULL, NULL, TRUE);
$sql = <<<SQL
        SELECT IF(teams.grpid IS NULL, 0, teams.grpid) AS grpid, IF(teams.cfid IS NULL, 0, teams.cfid) AS cfid, IF(teams.divid IS NULL, 0, divid) AS divid, tid, teams.name, teams.inactive, `mid`, IF(tid=away_tid, 
    
        IF(home_tid = 0, "[Bye]", (SELECT teams.name FROM teams WHERE tid=home_tid)),
        IF(away_tid = 0, "[Bye]", (SELECT teams.name FROM teams WHERE tid=away_tid))) AS opponent_name,
        IF(tid=away_tid, 
            IF(home_tid = 0, 0, (SELECT teams.inactive FROM teams WHERE tid=home_tid)),
            IF(away_tid = 0, 0, (SELECT teams.inactive FROM teams WHERE tid=away_tid))
          ) AS opponent_inactive,
        IF(tid=away_tid, home_tid, away_tid) AS opponent_tid 
        FROM teams 
        LEFT JOIN matches ON ((tid=away_tid OR tid=home_tid) AND matches.deleted = 0 AND sch_id = ?) 
        LEFT JOIN groups USING (grpid) 
        WHERE teams.lid = ? AND teams.deleted = 0 AND teams.approved = 1 AND teams.create_date_gmt <= ? 
          AND teams.divid IS NOT NULL AND teams.cfid IS NOT NULL AND teams.grpid IS NOT NULL
        ORDER BY teams.name ASC
SQL;
    $teams =& $db->getAssoc($sql, NULL, array($sch_id, $lid, $stg_match_date_gmt), NULL, TRUE);

$sql = <<<SQL
        SELECT tid, matches_pending.cfid, teams.name, mpnid, matches_pending.create_date_gmt 
        FROM matches_pending 
        INNER JOIN teams USING (tid) 
        WHERE sch_id = ? AND matches_pending.deleted = 0 AND matches_pending.`mid` IS NULL

SQL;
    $pending =& $db->getAssoc($sql, NULL, array($sch_id), TRUE);

    $listings = array('divisions'   => $divisions,
                       'conferences' => $conferences,
                       'groups'      => $groups,
                       'teams'       => $teams,
                       'pending'    => $pending
                      );
    return $listings;
}
