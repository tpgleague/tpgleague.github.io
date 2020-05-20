<?php

$pageTitle = 'Manage Roster';
require_once '../includes/inc.initialization.php';

//$tpl->error_reporting = E_ALL ^ E_NOTICE;
$tpl->append('external_css', 'manage.roster');
require_once 'inc.cls-gid.php';
if (!loggedin()) displayError('You must be logged in to use this function.');

define('TID', $_GET['tid']);
if (!checkNumber(TID)) { displayError('Team ID not specified.'); }


$sql = 'SELECT lid, tid, teams.name, captain_uid, owner_uid, max_schedulers, max_reporters FROM teams INNER JOIN organizations USING (orgid) INNER JOIN leagues USING (lid) WHERE tid = ? AND teams.deleted = 0 LIMIT 1';
$teamData =& $db->getRow($sql, array(TID));
if (empty($teamData)) displayError('Team not found.');
$tpl->assign('team_data', $teamData);

if ($teamData['captain_uid'] !== UID && $teamData['owner_uid'] !== UID) displayError('You are not authorized to access this function.');
define('LID', $teamData['lid']);

if (isset($_GET['remove'])) {
    $rid = $_GET['rid'];
    $sql = 'SELECT uid FROM rosters WHERE rid = ? AND tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $uid =& $db->getOne($sql, array($rid, TID));
    if (!checkNumber($uid)) displayError('Player to remove not specified.');

    $sql = 'SELECT count(1) FROM rosters WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00"';
    $rosterCount =& $db->getOne($sql, array(TID));

    if ($_GET['remove'] === 'confirm') {
        if ($uid === $teamData['captain_uid'] && $rosterCount > 1) {
            $newCaptainUID = $_POST['new_captain'];
            $sql = 'SELECT uid FROM rosters WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" AND uid <> ?';
            $potentialCaptains =& $db->getCol($sql, 0, array(TID, $uid));
            if (!in_array($newCaptainUID, $potentialCaptains)) {
                $removeError = TRUE;
            }
        } elseif ($uid === $teamData['captain_uid'] && $rosterCount == 1) {
            $newCaptainUID = NULL;
        } else {
            $newCaptainUID = $teamData['captain_uid'];
        }
        if (!$removeError) {
            $rosterArray = array(
                                 'leave_date_gmt' => mysqlNow(),
                                 'removed_by_uid' => UID
                                );
            $updateRecord = new updateRecord('rosters', 'rid', $rid);
            $updateRecord->addData($rosterArray);
            $updateRecord->UpdateData();
            //$sql = 'UPDATE rosters SET leave_date_gmt = NOW(), removed_by_uid = ? WHERE tid = ? AND uid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
            //$res =& $db->query($sql, array($rbc, TID, UID));

            if ($newCaptainUID != $teamData['captain_uid']) {
                $updateRecord = new updateRecord('teams', 'tid', TID);
                $updateRecord->addData(array('captain_uid' => $newCaptainUID));
                $updateRecord->UpdateData();
            }
            redirect('/manage.roster.php?tid='.TID);
        }
    }
    $sql = 'SELECT uid, gid, rid, rosters.handle, users.firstname, users.hide_lastname, users.lastname FROM rosters INNER JOIN users USING (uid) WHERE tid = ? AND rosters.uid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $removePlayerInfo =& $db->getRow($sql, array(TID, $uid));
    if (empty($removePlayerInfo)) displayError('The player specified was not found on this roster.');
    $tpl->assign('remove_player_info', $removePlayerInfo);

    if ($uid === $teamData['captain_uid'] && $rosterCount > 1) {
        $sql = 'SELECT uid, gid, rid, rosters.handle, users.firstname, users.hide_lastname, users.lastname FROM rosters INNER JOIN users USING (uid) WHERE tid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" AND uid <> ?';
        $captainPlayerList =& $db->getAll($sql, array(TID, $uid));
        $tpl->assign('captain_player_list', $captainPlayerList);
    }

}




$sql = 'SELECT rid, uid, gid, rosters.handle, permission_reschedule, permission_report, users.firstname, users.hide_lastname, users.lastname FROM rosters INNER JOIN users USING (uid) WHERE tid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00"';
$teamRoster =& $db->getAssoc($sql, NULL, array(TID));
$tpl->assign('team_roster', $teamRoster);


$rosterForm = new HTML_QuickForm('roster_form', 'post', $qfAction, NULL, NULL, TRUE);
$rosterForm->removeAttribute('name');
$rosterForm->applyFilter('__ALL__', 'trim');

$gidCls = new gidRoster($rosterForm, LID);
$gidName = $gidCls->gidName;
$tpl->assign('gid_name', $gidName);

$sql = 'SELECT captain_uid FROM teams WHERE tid = ?';
$currentCaptain =& $db->getOne($sql, array(TID));

foreach ($teamRoster as $rid => $player) {

    $rosterForm->setDefaults(array(
                                   'permission_reschedule_'.$rid => $player['permission_reschedule'],
                                   'permission_report_'.$rid => $player['permission_report']
                                   ));
    $handle = escape($player['handle']);
    $lastname = $player['lastname'];
    $hide_lastname = $player['hide_lastname'];
    if ($hide_lastname) $lastname = substr($lastname, 0, 1).'.';
    if ($handle) $handle = '"'.$handle.'"';
    $pname = escape($player['firstname']).' '.$handle.' '.escape($lastname);
    $captainDropdown[$player['uid']] = $pname;

    $rosterForm->addElement('static', 'player_'.$rid, $pname);
    $rosterForm->addElement('static', 'gid_'.$rid, $player['gid']);

    if ($currentCaptain != $player['uid'] && $teamData['owner_uid'] != $player['uid']) {
        if ($teamData['max_schedulers'] !== 0) {
            $rosterForm->addElement('advcheckbox',
                         'permission_reschedule_'.$rid,   // name of advcheckbox
                         'Reschedule',  // label output before advcheckbox
                         NULL,           // label output after advcheckbox
                         array('class' => 'checkbox'),      // string or array of attributes
                         array(0,1)
                     );
            $rosterForm->updateElementAttr(array('permission_reschedule_'.$rid), array('id' => 'permission_reschedule_'.$rid));
        }
        if ($teamData['max_reporters'] !== 0) {
            $rosterForm->addElement('advcheckbox',
                         'permission_report_'.$rid,   // name of advcheckbox
                         'Report',  // label output before advcheckbox
                         NULL,           // label output after advcheckbox
                         array('class' => 'checkbox'),      // string or array of attributes
                         array(0,1)
                     );
            $rosterForm->updateElementAttr(array('permission_report_'.$rid), array('id' => 'permission_report_'.$rid));
        }
    }

}

$captain_uid =& $rosterForm->addElement('select', 'captain_uid', 'Captain');
$captain_uid->loadArray($captainDropdown);
if (isset($_POST['captain_uid'])) $captain_selected = $_POST['captain_uid'];
else $captain_selected = $currentCaptain;
$captain_uid->setSelected($captain_selected);





$rosterForm->addElement('submit', 'submit', 'Save Changes');

if ($rosterForm->validate()) {
    foreach ($teamRoster as $rid => $player) {

                unset($permission_reschedule, $permission_report);

                if ($currentCaptain != $player['uid'] && $teamData['owner_uid'] != $player['uid'] && $rosterForm->exportValue('captain_uid') != $player['uid']) {
                    $permission_reschedule = $rosterForm->exportValue('permission_reschedule_'.$rid);
                    $permission_report = $rosterForm->exportValue('permission_report_'.$rid);
                }
                if (empty($permission_reschedule)) $permission_reschedule = 0;
                if (empty($permission_report)) $permission_report = 0;

                $rescheduleCount = $rescheduleCount + $permission_reschedule;
                $reportCount = $reportCount + $permission_report;

                // update roster id
                $valuesArray[] = array(
                                    'rid' => $rid,
                                    'permission_report' => $permission_report,
                                    'permission_reschedule' => $permission_reschedule
                                    );

    }

    if ($rescheduleCount > $teamData['max_schedulers']) {
        $tpl->append('roster_form_error', 'You may not assign more than '.$teamData['max_schedulers'].' additional schedulers.');
        $rosterFormError = TRUE;
    }
    if ($reportCount > $teamData['max_reporters']) {
        $tpl->append('roster_form_error', 'You may not assign more than '.$teamData['max_reporters'].' additional match reporters.');
        $rosterFormError = TRUE;
    }

    $captainUID = $rosterForm->exportValue('captain_uid');
    $sql = 'SELECT TRUE FROM `rosters` WHERE uid = ? AND tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $captainOnRoster =& $db->getOne($sql, array($captainUID, TID));
    if (!$captainOnRoster) {
        $rosterFormError = TRUE;
        $tpl->append('roster_form_error', 'The captain must be active on the roster.');
    }

    if (!$rosterFormError && !empty($valuesArray)) {
        foreach ($valuesArray as $array) {
            $recordUpdate = new updateRecord('rosters', 'rid');
            $recordUpdate->addData($array);
            $recordUpdate->UpdateData();
        }
        $recordUpdate = new updateRecord('teams', 'tid');
        $recordUpdate->addData(array('tid' => TID, 'captain_uid' => $captainUID));
        $recordUpdate->UpdateData();
        redirect();
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$rosterForm->accept($renderer);
$tpl->assign('roster_form', $renderer->toArray());


displayTemplate('manage.roster');
