<?php

$pageTitle = 'Edit Team';
require_once '../includes/inc.initialization.php';

if (!loggedin()) displayError('You must be logged in to use this function.');

define('TID', $_GET['tid']);
if (!checkNumber(TID)) { displayError('Team ID not specified.'); }

$sql = 'SELECT lid, tid, teams.name, tag, pw, team_avatar_url, captain_uid, owner_uid, team_comments, server_ip, server_location, server_available, hltv_public, server_port, server_pw, hltv_ip, hltv_port, hltv_pw FROM teams INNER JOIN organizations USING (orgid) WHERE tid = ? AND teams.deleted = 0 LIMIT 1';
$teamData =& $db->getRow($sql, array(TID));
if (empty($teamData)) displayError('Team not found.');

$sql = 'SELECT permission_reschedule, permission_report FROM rosters WHERE leave_date_gmt = "0000-00-00 00:00:00" AND uid = ? AND tid = ? LIMIT 1';
$permissionFlags =& $db->getRow($sql, array(UID, TID));

if ($teamData['captain_uid'] !== UID && $teamData['owner_uid'] !== UID && !$permissionFlags['permission_reschedule'] && !$permissionFlags['permission_report']) displayError('You are not authorized to access this function.');

$editTeamForm = new HTML_QuickForm('edit_team_form', 'post', $qfAction);
$editTeamForm->removeAttribute('name');
$editTeamForm->applyFilter('__ALL__', 'trim');
safeDefaults($editTeamForm, $teamData, array('pw', 'team_avatar_url', 'captain_uid', 'owner_uid', 'server_location', 'team_comments', 'server_ip', 'server_port', 'server_pw', 'server_available', 'hltv_ip', 'hltv_pw', 'hltv_port', 'hltv_public'));
//$editTeamForm->setDefaults($teamData);

$editTeamForm->addElement('static', 'tid', 'Team ID', '<div class="static">'. $teamData['tid'] .'</div>');
$editTeamForm->addElement('static', 'name', 'Team Name', '<div class="static">'. escape($teamData['name']) .'</div>');
$editTeamForm->addElement('static', 'tag', 'Team Tag', '<div class="static">'. escape($teamData['tag']) .'</div>');

$editTeamForm->addElement('text', 'pw', 'Join Password', array('maxlength' => 30));
$editTeamForm->addRule('pw', 'A join password is required.', 'required');
$editTeamForm->addRule('pw', 'Join password may not exceed 30 characters.', 'maxlength', 30);

//100px × 56px
$editTeamForm->addElement('text', 'team_avatar_url', 'Avatar URL (100x56px)', array('maxlength' => 255));
$editTeamForm->addRule('team_avatar_url', 'Avatar URL may not exceed 255 characters.', 'maxlength', 255);

if ($teamData['captain_uid'] !== UID && $teamData['owner_uid'] !== UID) $editTeamForm->freeze('pw');

/*
$sql = 'SELECT uid, handle FROM `rosters` WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00"';
$rosterArray =& $db->getAssoc($sql, FALSE, array(TID));
$captain_uid =& $editTeamForm->addElement('select', 'captain_uid', 'Captain');
$captain_uid->loadArray($rosterArray);
if (isset($_POST['captain_uid'])) $captain_selected = $_POST['captain_uid'];
else $captain_selected = $teamData['captain_uid'];
$captain_uid->setSelected($captain_selected);
*/

$editTeamForm->registerRule('valid_server_info', 'regex', '/^[-a-zA-Z0-9.]*/'); 

$editTeamForm->addElement('text', 'server_ip', 'Server IP/hostname', array('maxlength' => 255));
$editTeamForm->addRule('server_ip', 'Server IP/hostname cannot exceed 255 characters.', 'maxlength', 255);
$editTeamForm->addRule('server_ip', 'This does not appear to be a valid IP address or hostname.', 'regex', '/^[a-zA-Z0-9]+[a-zA-Z0-9\-.]+$/');
//$editTeamForm->addRule('server_ip', 'You have entered invalid characters.', 'valid_server_info');

$editTeamForm->addElement('text', 'server_port', 'Server Port', array('maxlength' => 5));
$editTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'numeric');
$editTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'nonzero');
$editTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'maxlength', 5);

$editTeamForm->addElement('text', 'server_pw', 'Server Password', array('maxlength' => 60));
$editTeamForm->addRule('server_pw', 'Server password may not exceed 60 characters.', 'maxlength', 60);
$editTeamForm->addRule('server_pw', 'Server password may only contain letters and numbers.', 'alphanumeric');

$editTeamForm->addElement('text', 'server_location', 'Server Physical Location', array('maxlength' => 64));
$editTeamForm->addElement('static', 'note_server_location', 'E.g., Chicago, Dallas, Atlanta, Seattle, etc.');


$editTeamForm->addElement('text', 'hltv_ip', 'HLTV IP/hostname', array('maxlength' => 255));
$editTeamForm->addRule('hltv_ip', 'Server IP/hostname cannot exceed 255 characters.', 'maxlength', 255);
$editTeamForm->addRule('hltv_ip', 'This does not appear to be a valid IP address or hostname.', 'regex', '/^[a-zA-Z0-9]+[a-zA-Z0-9\-.]+$/');

$editTeamForm->addElement('text', 'hltv_port', 'HLTV Port', array('maxlength' => 5));
$editTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'numeric');
$editTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'nonzero');
$editTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'maxlength', 5);

$editTeamForm->addElement('text', 'hltv_pw', 'HLTV Password', array('maxlength' => 60));
$editTeamForm->addRule('hltv_pw', 'Server password may not exceed 60 characters.', 'maxlength', 60);
$editTeamForm->addRule('hltv_pw', 'Server password may only contain letters and numbers.', 'alphanumeric');

$editTeamForm->addElement('advcheckbox',
                 'server_available',   // name of advcheckbox
                 'Server Available',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('server_available'), array('id' => 'server_available'));
$editTeamForm->addElement('static', 'note_server_available', 'Checkmark this box if this server is available for your next match.');


$editTeamForm->addElement('advcheckbox',
                 'hltv_public',   // name of advcheckbox
                 'HLTV Public',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('hltv_public'), array('id' => 'hltv_public'));
$editTeamForm->addElement('static', 'note_hltv_public', 'Checkmark this box if you want the HLTV info publicized.');


$editTeamForm->addElement('submit', 'submit', 'Save Changes', array('class' => 'submit'));

if ($editTeamForm->validate()) {
    $lid = $teamData['lid'];

/*
    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND name = ? AND tid <> ? LIMIT 1';
    $teamNameExists =& $db->getOne($sql, array($lid, $editTeamForm->exportValue('name'), TID));
    if ($teamNameExists) $editTeamForm->setElementError('name', 'A team by that name already exists in this league.');

    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND tag = ? AND tid <> ? LIMIT 1';
    $teamTagExists =& $db->getOne($sql, array($lid, $editTeamForm->exportValue('tag'), TID));
    if ($teamTagExists) $editTeamForm->setElementError('tag', 'A team using that tag already exists in this league.');
*/
/*
    $captainUID = $editTeamForm->exportValue('captain_uid');
    $sql = 'SELECT TRUE FROM `rosters` WHERE uid = ? AND tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $captainOnRoster =& $db->getOne($sql, array($captainUID, TID));
    if (!$captainOnRoster) $editTeamForm->setElementError('captain_uid', 'The captain must be active on the roster.');
*/

    if (!$teamNameExists && !$teamTagExists) {
        $valuesArray = array(
                             'tid' => TID,
                             'name' => $teamData['name'],
                             'tag' => $teamData['tag'],
                             //'captain_uid' => $captainUID,
                             'pw' => $editTeamForm->exportValue('pw'),
                             'team_avatar_url' => $editTeamForm->exportValue('team_avatar_url'),
                             'server_ip' => $editTeamForm->exportValue('server_ip'),
                             'server_port' => $editTeamForm->exportValue('server_port'),
                             'server_pw' => $editTeamForm->exportValue('server_pw'),
                             'server_location' => $editTeamForm->exportValue('server_location'),
                             'hltv_ip' => $editTeamForm->exportValue('hltv_ip'),
                             'hltv_port' => $editTeamForm->exportValue('hltv_port'),
                             'hltv_pw' => $editTeamForm->exportValue('hltv_pw'),
                             'server_available' => $editTeamForm->exportValue('server_available'),
                             'hltv_public' => $editTeamForm->exportValue('hltv_public'),
                             'modify_date_gmt' => gmdate('c', mktime())
                            );
        $updateRecord = new updateRecord('teams', 'tid');
        $updateRecord->addData($valuesArray);
        $updateRecord->UpdateData();
    }
}
//else {
    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $editTeamForm->accept($renderer);
    $tpl->assign('edit_team_form', $renderer->toArray());
//}

displayTemplate('edit.team');