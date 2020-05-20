<?php

$extra_head[] = <<<EOT
    <script src="/js/report.match.sides.js" type="text/javascript"></script>
EOT;

require_once '../includes/inc.initialization.support.php';
require_once 'inc.func-schedule.php';

if (!checkNumber($_GET['mid'])) { displayError('Error: Match ID not specified.'); }
else { define('MID', $_GET['mid']); }

$sql = 'SELECT seasons.lid, sch_id, sid, IF(stg_type = "Preseason", 1, 0) AS ps FROM seasons INNER JOIN schedules USING (sid) INNER JOIN matches USING (sch_id) WHERE `mid` = ? LIMIT 1';
$match =& $db->getRow($sql, array(MID));
define('LID', $match['lid']);
define('SCH_ID', $match['sch_id']);


/*
if ($_GET['delete'] == 'confirm') {
    $sql = 'UPDATE matches SET deleted = 1 WHERE mid = ?';
    $db->query($sql, array(MID));
    redirect('/edit.matches.php?sch_id='.SCH_ID);
    echo '<script type="text/javascript">'."\r\n";
    echo '<!--'."\r\n";
    echo 'window.location = "/edit.matches.php?sch_id='.SCH_ID.'"'."\r\n";
    echo '//-->'."\r\n";
    echo '</script>'."\r\n";
    echo '<a href="/edit.matches.php?sch_id='.SCH_ID.'">Return to match scheduling page.</a>'."\r\n";
    exit;
}
*/


if ($_POST['unreport_submit'] == 'Unreport match') {
    $unreportArray = array(
                           'report_date_gmt' => '0000-00-00 00:00:00',
                           'report_by_uid' => NULL,
                           'report_by_aid' => NULL,
                           'win_tid' => NULL,
                           'forfeit_home' => 0,
                           'forfeit_away' => 0,
                           'report_by_tid' => NULL
                          );
    $updateRecord = new updateRecord('matches', 'mid', MID);
    $updateRecord->addData($unreportArray);
    $updateRecord->updateData();
    $sql = 'DELETE FROM matches_scores WHERE `mid` = ?';
    $db->query($sql, array(MID));
}


$sql = 'SELECT mid, matches.deleted, start_date_gmt, win_tid, forfeit_away, matches.home_tid, matches.away_tid, forfeit_home, admins.admin_name, unix_timestamp(report_date_gmt) as report_date_gmt, report_by_uid, users.username, users.firstname, users.handle, users.lastname, confirmed_mpid, match_comments, IF(report_by_tid = away_tid, (SELECT teams.name FROM teams WHERE teams.tid = away_tid LIMIT 1), (SELECT teams.name FROM teams WHERE teams.tid = home_tid LIMIT 1)) AS reporting_team, (SELECT name FROM teams WHERE tid=away_tid LIMIT 1) AS away_name, (SELECT name FROM teams WHERE tid=home_tid LIMIT 1) AS home_name FROM matches LEFT JOIN users ON (report_by_uid = users.uid) LEFT JOIN admins ON (report_by_aid = admins.aid) INNER JOIN teams WHERE mid = ? LIMIT 1';
$matchData =& $db->getRow($sql, array(MID));
$tpl->assign('match_data', $matchData);

if ($matchData['report_date_gmt'] != '0000-00-00 00:00:00') {
    $unreportForm = '<form ' . $onsubmit . ' action="/edit.match.php?mid='.MID.'" method="post"> <input type="submit" name="unreport_submit" value="Unreport match" /></form>';
}
$tpl->assign('unreport_form', $unreportForm);

$editMatchForm = new HTML_QuickForm('edit_match_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editMatchForm->removeAttribute('name'); // XHTML compliance
$editMatchForm->applyFilter('__ALL__', 'trim');

$editMatchForm->setDefaults($matchData);

$editMatchForm->addElement('static', 'mid', 'Match ID', $matchData['mid']);


/*
$editMatchForm->addElement('advcheckbox',
                 'confirmed_mpid',   // name of advcheckbox
                 'Confirmed ID',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editMatchForm->updateElementAttr(array('confirmed_mpid'), array('id' => 'confirmed_mpid'));
$editMatchForm->addElement('static', 'note_confirmed_mpid', 'Don\'t edit this :/');
*/

$editMatchForm->addElement('advcheckbox',
                 'deleted',   // name of advcheckbox
                 'Deleted',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editMatchForm->updateElementAttr(array('deleted'), array('id' => 'deleted'));
$editMatchForm->addElement('static', 'note_deleted', 'Effectively deletes the match.');

$editMatchForm->addElement('advcheckbox',
                 'forfeit_away',   // name of advcheckbox
                 escape($matchData['away_name']) . ' forfeits',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editMatchForm->updateElementAttr(array('forfeit_away'), array('id' => 'forfeit_away'));
$editMatchForm->addElement('static', 'note_forfeit_away', 'Matches actually played but overturned shouldn\'t be counted as forfeits for either team.');

$editMatchForm->addElement('advcheckbox',
                 'forfeit_home',   // name of advcheckbox
                 escape($matchData['home_name']) . ' forfeits',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editMatchForm->updateElementAttr(array('forfeit_home'), array('id' => 'forfeit_home'));
$editMatchForm->addElement('static', 'note_forfeit_home', 'Matches actually played but overturned shouldn\'t be counted as forfeits for either team.');

//$editMatchForm->addElement('static', 'important_note', '<span style="font-weight: bold; color: red;">IMPORTANT</span>', 'Checkmarking ANY of the 3 boxes above will ERASE all scoring reported for this match.');

$editMatchForm->addElement('textarea', 'match_comments', 'Player comments', array('maxlength' => 250, 'rows' => 3, 'cols' => '50'));
$editMatchForm->addRule('match_comments', 'Max 250 characters.', 'maxlength', 250);

if ($matchData['username']) {
    $user = '[<a href="/edit.user.php?uid='.$matchData['report_by_uid'].'">'.escape($matchData['username']).'</a>] '. escape($matchData['firstname']) . ' "'. escape($matchData['handle']) .'" '.escape($matchData['lastname']). ' of team: '. escape($matchData['reporting_team']);
}

//$editMatchForm->addElement('static', 'report_date_gmt', 'Report Date/Time', mysqlGMTtoLocal($matchData['report_date_gmt']).' '.date('T') . $unreportButton);
if ($matchData['report_date_gmt']) $datetime = smarty_modifier_converted_timezone($matchData['report_date_gmt']);
else $datetime = '';
$editMatchForm->addElement('static', 'report_date', 'Report Date/Time', $datetime);
$editMatchForm->addElement('static', 'reporting_user', 'Reporting User', $user);
$editMatchForm->addElement('static', 'reporting_admin_name', 'Reporting Admin', escape($matchData['admin_name']));


/*
$localDateTime = mysqlGMTtoLocal($matchData['start_date_gmt']);

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


$editMatchForm->setDefaults(array('time' => $localTimeHour .':'. $localTimeMinute));
$editMatchForm->setDefaults(array('date' => array('Y' => $localDateYear, 'M' => $localDateMonth, 'd' => $localDateDay)));


$editMatchForm->addElement('text', 'time', 'Time', array('maxlength' => '5', 'size' => '7'));
$editMatchForm->addRule('time', 'A time is required.', 'required');
$editMatchForm->addElement('static', 'note_time', '24HH time in default league time zone ('.date('T').'). 9pm is 21:00.');

$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'dMY',
                     'maxYear'         => date('Y')-1,
                     'minYear'         => date('Y')+1,
                     'addEmptyOption'  => array('d' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('d' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
$editMatchForm->addElement('date', 'date', 'Date', $dateOptions);
$editMatchForm->addRule('date', 'Please enter a valid date.', 'required');
$editMatchForm->registerRule('valid_date', 'function', 'checkValidDate');
$editMatchForm->addRule('date', 'Please enter a valid date.', 'valid_date');
*/

////////////////     start_date_gmt    ///////////////
$matchDate = dateToArray(mysqlGMTtoLocal($matchData['start_date_gmt']));

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

$editMatchForm->setDefaults(array('start_date_gmt' => array('Y' => $matchDate['year'], 'M' => $matchDate['month'], 'd' => $matchDate['day'], 'g' => $hour_12, 'A' => $meridian, 'i' => $matchDate['minute'])));
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
$editMatchForm->addElement('date', 'start_date_gmt', 'Match Date', $dateOptions);
$editMatchForm->addElement('static', 'note_start_date_gmt', 'Time and date ('.date('T').')');
$editMatchForm->addRule('start_date_gmt', 'Please enter a valid date.', 'required');
$editMatchForm->addRule('start_date_gmt', 'Please enter a valid date.', 'valid_date');
///////////////////////////////////////////////////////


$editMatchForm->addElement('submit', 'submit', 'Edit Match');



if ($editMatchForm->validate()) {

/*
    $date = $editMatchForm->exportValue('date');
    $time = $editMatchForm->exportValue('time');
    $time = explode(':', $time);
    $start_date_gmt = gmstrftime("%Y-%m-%d %H:%M:%S", mktime($time[0], $time[1], 0, $date['M'], $date['d'], $date['Y']));
*/

    if ($matchData['deleted'] && !$editMatchForm->exportValue('deleted')) {
        // admin is trying to UNDELETE a match... need to check if either team is already scheduled for a match for this stg
        $sql = 'SELECT `mid` FROM matches WHERE sch_id = ? AND (home_tid = ? OR away_tid = ?) AND deleted = 0 LIMIT 1';
        if ($matchData['home_tid']) {
            $cannotUndeleteHome =& $db->getOne($sql, array(SCH_ID, $matchData['home_tid'], $matchData['home_tid']));
        }
        if ($matchData['away_tid']) {
            $cannotUndeleteAway =& $db->getOne($sql, array(SCH_ID, $matchData['away_tid'], $matchData['away_tid']));
        }
        if ($cannotUndeleteHome || $cannotUndeleteAway) {
            $editMatchForm->setElementError('deleted', 'Cannot undelete match as one of these teams is already scheduled for a match this week. <br /> Match ID: <a href="/edit.match.php?mid='.$cannotUndeleteHome.'">'.$cannotUndeleteHome.'</a> <a href="/edit.match.php?mid='.$cannotUndeleteAway.'">'.$cannotUndeleteAway.'</a>');
        }
    }

    // start_date_gmt
    $formDate = $editMatchForm->exportValue('start_date_gmt');
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
    $start_date_gmt = gmstrftime('%Y-%m-%d %H:%M:%S', mktime($hour, $minute, $second, $month, $day, $year));
    ////////


    $valuesArray = array(
                        //'confirmed' => $editMatchForm->exportValue('confirmed'),
                        'deleted' => $editMatchForm->exportValue('deleted'),
                        'match_comments' => $editMatchForm->exportValue('match_comments'),
                        'forfeit_away' => $editMatchForm->exportValue('forfeit_away'),
                        'forfeit_home' => $editMatchForm->exportValue('forfeit_home'),
                        'start_date_gmt' => $start_date_gmt
                        );
    $mergeArray = array();
    if ($valuesArray['forfeit_away'] || $valuesArray['forfeit_home']) {

        $win_tid = $matchData['win_tid'];
        if ($valuesArray['forfeit_away'] && $valuesArray['forfeit_home']) {
            $win_tid = NULL;
        } elseif ($valuesArray['forfeit_away']) {
            $win_tid = $matchData['home_tid'];
        } elseif ($valuesArray['forfeit_home']) {
            $win_tid = $matchData['away_tid'];
        }

        $mergeArray = array ('report_date_gmt' => mysqlNow(), 'report_by_aid' => AID, 'win_tid' => $win_tid);
    }
    $valuesArray = array_merge($valuesArray, $mergeArray);

    if (!$cannotUndeleteHome && !$cannotUndeleteAway) {
        $updateRecord = new updateRecord('matches', 'mid', MID);
        $updateRecord->addData($valuesArray);
        $updateRecord->updateData();

        require_once 'inc.func-updateStandings.php';
        calculateTeamStandings($matchData['away_tid'], $match['sid'], $match['ps']);
        calculateTeamStandings($matchData['home_tid'], $match['sid'], $match['ps']);

        $tpl->assign('success', TRUE);
        redirect();
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editMatchForm->accept($renderer);
$tpl->assign('edit_match_form', $renderer->toArray());




$sql = 'SELECT msid, away_score, home_score, away_lsid, home_lsid FROM matches_scores WHERE mid = ?';
$scores =& $db->getAll($sql, array(MID));

$sql = 'SELECT lsid, side FROM leagues_sides WHERE lid = ?';
$arraySides =& $db->getAssoc($sql, NULL, array(LID), TRUE);
$arraySides[''] = '';
ksort($arraySides);

$populate = array();
$i = 1;
foreach ($scores as $half) {
    $populate = array_merge($populate, array(
                        'side_selector_h'.$i.'a' => $half['away_lsid'],
                        'side_selector_h'.$i.'h' => $half['home_lsid'],
                        'h'.$i.'a_score' => $half['away_score'],
                        'h'.$i.'h_score' => $half['home_score']
                        ));
    ++$i;
}
$editMatchScoresForm = new HTML_QuickForm('report_match_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editMatchScoresForm->removeAttribute('name'); // XHTML compliance
$editMatchScoresForm->applyFilter('__ALL__', 'trim');


$editMatchScoresForm->setDefaults($matchData);

$editMatchScoresForm->addElement('static', 'away_team_name', 'Away Team', '<a href="/edit.team.php?tid='.$matchData['away_tid'].'">'.escape($matchData['away_name']).'</a>');
$editMatchScoresForm->addElement('static', 'home_team_name', 'Home Team', '<a href="/edit.team.php?tid='.$matchData['home_tid'].'">'.escape($matchData['home_name']).'</a>');


$editMatchScoresForm->setDefaults($populate);



$sides_h1a =& $editMatchScoresForm->addElement('select', 'side_selector_h1a', 'Side');
$sides_h1a->loadArray($arraySides);
$editMatchScoresForm->addRule('side_selector_h1a', 'Required', 'required');
$editMatchScoresForm->addRule('side_selector_h1a', 'Required', 'nonzero');
$editMatchScoresForm->addRule('side_selector_h1a', 'Required', 'numeric');
$editMatchScoresForm->updateElementAttr('side_selector_h1a', 'onchange="changeSides(\'h1a\');"');


$sides_h1h =& $editMatchScoresForm->addElement('select', 'side_selector_h1h', 'Side');
$sides_h1h->loadArray($arraySides);
$editMatchScoresForm->addRule('side_selector_h1h', 'Required', 'required');
$editMatchScoresForm->addRule('side_selector_h1h', 'Required', 'nonzero');
$editMatchScoresForm->addRule('side_selector_h1h', 'Required', 'numeric');
$editMatchScoresForm->updateElementAttr('side_selector_h1h', 'onchange="changeSides(\'h1h\');"');


$sides_h2a =& $editMatchScoresForm->addElement('select', 'side_selector_h2a', 'Side');
$sides_h2a->loadArray($arraySides);
$editMatchScoresForm->addRule('side_selector_h2a', 'Required', 'required');
$editMatchScoresForm->addRule('side_selector_h2a', 'Required', 'nonzero');
$editMatchScoresForm->addRule('side_selector_h2a', 'Required', 'numeric');
$editMatchScoresForm->updateElementAttr('side_selector_h2a', 'onchange="changeSides(\'h2a\');"');


$sides_h2h =& $editMatchScoresForm->addElement('select', 'side_selector_h2h', 'Side');
$sides_h2h->loadArray($arraySides);
$editMatchScoresForm->addRule('side_selector_h2h', 'Required', 'required');
$editMatchScoresForm->addRule('side_selector_h2h', 'Required', 'nonzero');
$editMatchScoresForm->addRule('side_selector_h2h', 'Required', 'numeric');
$editMatchScoresForm->updateElementAttr('side_selector_h2h', 'onchange="changeSides(\'h2h\');"');


$editMatchScoresForm->addElement('text', 'h1a_score', 'Score');
$editMatchScoresForm->addRule('h1a_score', 'Required', 'required');
$editMatchScoresForm->addRule('h1a_score', 'Must be a number', 'numeric');

$editMatchScoresForm->addElement('text', 'h1h_score', 'Score');
$editMatchScoresForm->addRule('h1h_score', 'Required', 'required');
$editMatchScoresForm->addRule('h1h_score', 'Must be a number', 'numeric');

$editMatchScoresForm->addElement('text', 'h2a_score', 'Score');
$editMatchScoresForm->addRule('h2a_score', 'Required', 'required');
$editMatchScoresForm->addRule('h2a_score', 'Must be a number', 'numeric');

$editMatchScoresForm->addElement('text', 'h2h_score', 'Score');
$editMatchScoresForm->addRule('h2h_score', 'Required', 'required');
$editMatchScoresForm->addRule('h2h_score', 'Must be a number', 'numeric');

$editMatchScoresForm->addElement('submit', 'submit', 'Edit Scores');

if ($editMatchScoresForm->validate()) {

        $sql = 'SELECT TRUE FROM matches WHERE mid = '. $db->quoteSmart(MID) .' AND (forfeit_home = 1 OR forfeit_away = 1 OR deleted = 1)';
        $noEdit =& $db->getOne($sql);

        if ($noEdit) {
            $editMatchScoresForm->setElementError('submit', 'The match must not be marked as forfeit or deleted before you can enter scores.');
        } else {
            // either update or add the match scores
            $half_one_msid = $scores[0]['msid'];
            $h1a_side = $editMatchScoresForm->exportValue('side_selector_h1a');
            $h1h_side = $editMatchScoresForm->exportValue('side_selector_h1h');
            $h2a_side = $editMatchScoresForm->exportValue('side_selector_h2a');
            $h2h_side = $editMatchScoresForm->exportValue('side_selector_h2h');

            $half_two_msid = $scores[1]['msid'];
            $h1a_score = $editMatchScoresForm->exportValue('h1a_score');
            $h1h_score = $editMatchScoresForm->exportValue('h1h_score');
            $h2a_score = $editMatchScoresForm->exportValue('h2a_score');
            $h2h_score = $editMatchScoresForm->exportValue('h2h_score');

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
                $win_tid = NULL;
                $tie = 1;
                $tpl->assign('tie', TRUE);
            }
            $sql = 'UPDATE matches SET report_by_aid = ?, report_date_gmt = NOW(), win_tid = ?, tie = ? WHERE `mid` = ?';
            $res =& $db->query($sql, array(AID, $win_tid, $tie, MID));

            $sql = 'INSERT INTO matches_scores (msid, mid, away_score, home_score, away_lsid, home_lsid) '
                 . 'VALUES (?, ?, ?, ?, ?, ?) '
                 . 'ON DUPLICATE KEY UPDATE away_score = ?, home_score = ?, away_lsid = ?, home_lsid = ?';
            $res =& $db->query($sql, array($half_one_msid, MID, $h1a_score, $h1h_score, $h1a_side, $h1h_side, $h1a_score, $h1h_score, $h1a_side, $h1h_side));
            $res =& $db->query($sql, array($half_two_msid, MID, $h2a_score, $h2h_score, $h2a_side, $h2h_side, $h2a_score, $h2h_score, $h2a_side, $h2h_side));

            require_once 'inc.func-updateStandings.php';
            calculateTeamStandings($matchData['away_tid'], $match['sid'], $match['ps']);
            calculateTeamStandings($matchData['home_tid'], $match['sid'], $match['ps']);

            $tpl->assign('success', TRUE);
            redirect();
        }
} elseif ($editMatchScoresForm->isSubmitted()) {
    $tpl->assign('match_scores_failure', TRUE);
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editMatchScoresForm->accept($renderer);
$tpl->assign('report_match_form', $renderer->toArray());










$notesForm = new HTML_QuickForm('admin_notes_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$notesForm->removeAttribute('name');
$notesForm->applyFilter('__ALL__', 'trim');
$notesForm->addElement('textarea', 'comment', 'Comment', array('rows' => 5, 'cols' => '50'));
$notesForm->addElement('submit', 'submit', 'Add note');
$notesForm->addRule('comment', 'Please enter a comment.', 'required');
if ($notesForm->validate()) {
    $notesValues = array(
                              'mid' => MID,
                              'aid' => AID,
                              'create_date_gmt' => mysqlNow(),
                              'comment' => $notesForm->exportValue('comment')
                             );
    $res = $db->autoExecute('matches_admin_notes', $notesValues, DB_AUTOQUERY_INSERT);
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$notesForm->accept($renderer);
$tpl->assign('admin_notes_form', $renderer->toArray());

$sql = 'SELECT admin_name, aid, UNIX_TIMESTAMP(matches_admin_notes.create_date_gmt) AS unix_create_date_gmt, `comment` FROM matches_admin_notes INNER JOIN admins USING (aid) WHERE mid = ? ORDER BY matches_admin_notes.create_date_gmt DESC';
$adminNotes =& $db->getAll($sql, array(MID));
$tpl->assign('admin_notes', $adminNotes);












require_once 'inc.initialization.display.php';

displayTemplate('edit.match');