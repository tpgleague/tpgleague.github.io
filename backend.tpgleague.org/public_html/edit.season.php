<?php

$cssAppend[] = 'table';
require_once '../includes/inc.initialization.php';
require_once 'inc.func-updateStandings.php';

if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { define('LID', $_GET['lid']); }


$ACCESS = checkPermission('Edit League', 'League', LID);

if ($ACCESS) {
    if (checkNumber($_POST['activate_season'])) {
        $sql = 'SELECT sid FROM seasons WHERE lid = ? AND active = 1 LIMIT 1';
        $activeSID =& $db->getOne($sql, array(LID));

        /*
        $sql = 'SELECT preseason_close_date_gmt IS NULL OR season_close_date_gmt IS NULL FROM `seasons` WHERE lid = ? AND sid <> ?';
        $activeSeasonsSnapshots =& $db->getCol($sql, 0, array(LID, $_POST['activate_season']));
        if (in_array('1', $activeSeasonsSnapshots)) {
            $tpl->assign('season_error', 'Snapshots must be taken for previous seasons before a new season can be activated.');
        } else {
        */
            if (!empty($activeSID)) {
                $updateRecord = new updateRecord('seasons', 'sid', $activeSID);
                $updateRecord->addData(array('active' => 0));
                $updateRecord->UpdateData();
            }
            $updateRecord = new updateRecord('seasons', 'sid', $_POST['activate_season']);
            $updateRecord->addData(array('active' => 1));
            $updateRecord->UpdateData();
        //}
        echo '<p>Please be patient, recalculating standings...</p>';
        ob_flush();
        flush();
        require_once 'inc.func-updateStandings.php';
        $sql = 'SELECT tid FROM teams WHERE lid = ?';
        $teamList =& $db->getCol($sql, 0, array(LID));
        foreach ($teamList as $tid) {
            calculateTeamStandings($tid, $_POST['activate_season'], 1);
            //calculateTeamStandings($tid, $_POST['activate_season'], 0);
        }
        
        
    }
    elseif (checkNumber($_POST['toggle_preseason'])) {
        $toggleSID = $_POST['toggle_preseason'];
        $togglePreseason = $_POST['toggle_preseason_value'];
        if ($togglePreseason == 'Turn On') $toggleValue = 1;
        else $toggleValue = 0;
        $updateRecord = new updateRecord('seasons', 'sid', $toggleSID);
        $updateRecord->addData(array('display_preseason' => $toggleValue));
        $updateRecord->UpdateData();

        echo '<p>Please be patient, recalculating standings...</p>';
        ob_flush();
        flush();

        $sql = 'SELECT tid FROM teams WHERE lid = ? AND deleted = 0';
        $teamList =& $db->getCol($sql, 0, array(LID));
        foreach ($teamList as $tid) {
            calculateTeamStandings($tid, $toggleSID, $toggleValue);
            //calculateTeamStandings($tid, $_POST['activate_season'], 0);
        }

        /*
        $sql = 'UPDATE seasons SET display_preseason = ? WHERE sid = ?';
        $res =& $db->query($sql, array($toggleValue, $toggleSID));
        */
    } elseif (checkNumber($_POST['close_preseason'])) {
        $updateRecord = new updateRecord('seasons', 'sid', $_POST['close_preseason']);
        $updateRecord->addData(array('preseason_close_date_gmt' => mysqlNow()));
        $updateRecord->UpdateData();
    } elseif (checkNumber($_POST['close_season'])) {
        $sql = 'SELECT preseason_close_date_gmt IS NULL FROM seasons WHERE sid = ? LIMIT 1';
        $preseasonNotClosed =& $db->getOne($sql, array($_POST['close_season']));
        if ($preseasonNotClosed) {
            $tpl->assign('season_error', 'You must take a snapshot of preseason season before snapshotting regular season.');
        } else {
            $updateRecord = new updateRecord('seasons', 'sid', $_POST['close_season']);
            $updateRecord->addData(array('season_close_date_gmt' => mysqlNow()));
            $updateRecord->UpdateData();
        }
    }
}



$addSeasonForm = new HTML_QuickForm('add_season_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$addSeasonForm->removeAttribute('name'); // XHTML compliance
$addSeasonForm->applyFilter('__ALL__', 'trim');

$addSeasonForm->addElement('text', 'season_title', 'Season Title');
$addSeasonForm->addRule('season_title', 'Title is required.', 'required');

$addSeasonForm->addElement('text', 'season_number', 'Season Number');
$addSeasonForm->addRule('season_number', 'A number is required.', 'required');
$addSeasonForm->addRule('season_number', 'A number is required.', 'numeric');
$addSeasonForm->addRule('season_number', 'A number is required.', 'nonzero');

if (!$ACCESS) { 
    $addSeasonForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $addSeasonForm->freeze();
} else {
    $addSeasonForm->addElement('submit', 'submit', 'Add Season');
}


$addSeasonForm->setDefaults(array('lid', LID));
$addSeasonForm->setConstants(array('lid', LID));
$addSeasonForm->addElement('hidden', 'lid', LID);



if ($ACCESS && ($addSeasonForm->validate())) {
    $season_title = $addSeasonForm->exportValue('season_title');
    $checkExistingSeasonTitle =& $db->getOne('SELECT TRUE FROM seasons WHERE season_title = ? AND lid = ?', array($season_title, LID));
    $season_number = $addSeasonForm->exportValue('season_number');
    $checkExistingSeasonNumber =& $db->getOne('SELECT TRUE FROM seasons WHERE season_number = ? AND lid = ?', array($season_title, LID));
    if ($checkExistingSeasonTitle) {
        $addSeasonForm->setElementError('season_title', 'A season with this title already exists.');
    }
    if ($checkExistingSeasonNumber) {
        $addSeasonForm->setElementError('season_number', 'A season with this number already exists.');
    }    
    if (!$checkExistingSeasonTitle && !$checkExistingSeasonNumber) {
        $newSeasonArray = array_merge($addSeasonForm->exportValues(), array('create_date_gmt' => mysqlNow()));
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('seasons', $newSeasonArray);

        //$sql = 'INSERT INTO seasons (season_title, season_number, lid, create_date_gmt) VALUES (?, ?, ?, NOW())';
        //$db->query($sql, $addSeasonForm->exportValues());
        clearForm($addSeasonForm);
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$addSeasonForm->accept($renderer);
$tpl->assign('add_season_form', $renderer->toArray());


if ($_POST['sid'] && $ACCESS) {
    $sid = $_POST['sid'];
    
    $sql = 'SELECT TRUE FROM seasons WHERE sid = ? AND lid = ?';
    $sidValid =& $db->getOne($sql, array($sid, LID));
    if (!$sidValid) displayError('You do not have access to edit that season.');

    $seasonUpdateArray = array(
                                'roster_lock_hours' => $_POST['roster_lock_hours'],
                                'roster_lock_playoffs_sch_id' => 
                                    checkNumber($_POST['roster_lock_playoffs_sch_id']) ? $_POST['roster_lock_playoffs_sch_id'] : NULL
                              );
    $seasonEdit = new updateRecord('seasons', 'sid', $sid);
    $seasonEdit->addData($seasonUpdateArray);
    $seasonEdit->UpdateData();
}



$sql = 'SELECT * FROM seasons WHERE lid = ? ORDER BY season_number DESC';
$seasonsList =& $db->getAll($sql, array(LID));
$tpl->assign('seasons_list', $seasonsList);



$sql = 'SELECT schedules.sid, sch_id, stg_short_desc FROM schedules INNER JOIN seasons USING (sid) WHERE seasons.lid = ? AND schedules.deleted = 0 ORDER BY stg_match_date_gmt';
$scheduleData =& $db->getAssoc($sql, NULL, array(LID), NULL, TRUE);
$tpl->assign('schedule_data', $scheduleData);


displayTemplate('edit.season');