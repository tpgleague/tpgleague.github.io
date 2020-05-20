<?php

require_once '../includes/inc.initialization.php';

if (!checkNumber(@$_GET['cfid'])) { displayError('Error: Conference ID not specified.'); }
else { @define('CFID', @$_GET['cfid']); }

$sql = 'SELECT * FROM conferences WHERE cfid = ' . $db->quoteSmart(CFID);
$conferenceSettings =& $db->getRow($sql);
$lid = $conferenceSettings['lid'];
define('LID', $lid);
define('DIVID', $conferenceSettings['divid']);

$ACCESS = checkPermission('Edit League', 'Division', DIVID);
$editConferenceForm = new HTML_QuickForm('edit_conference_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editConferenceForm->removeAttribute('name'); // XHTML compliance
$editConferenceForm->applyFilter('__ALL__', 'trim');

$editConferenceForm->setDefaults($conferenceSettings);
$editConferenceForm->setConstants(array('cfid' => CFID));

$editConferenceForm->addElement('hidden', 'cfid', CFID);
$editConferenceForm->addElement('text', 'conference_title', 'Title');
//$editConferenceForm->addElement('text', 'create_date_gmt', 'Create Date');
//$editConferenceForm->addElement('text', 'inactive', 'Inactive');
$editConferenceForm->addElement('advcheckbox',
                 'inactive',   // name of advcheckbox
                 'Inactive',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editConferenceForm->updateElementAttr(array('inactive'), array('id' => 'inactive'));


$admins =& $editConferenceForm->addElement('select', 'admin', 'Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$selectedAdmins =& $db->getCol('SELECT aid FROM admins_assignments WHERE section = "conferences" AND pkid = '.CFID);
if ($editConferenceForm->isSubmitted()) $admins->setSelected($editConferenceForm->exportValue('admin'));
else $admins->setSelected($selectedAdmins);
$editConferenceForm->addElement('static', 'note_admin', 'Optional');


if (!$ACCESS) { 
    $editConferenceForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $editConferenceForm->freeze();
} else {
    $editConferenceForm->addElement('submit', 'submit', 'Edit Conference');
}


if ($ACCESS && ($editConferenceForm->validate()) && ($editConferenceForm->exportValue('cfid') === CFID)) {
    $conference_title = $editConferenceForm->exportValue('conference_title');
    $checkExistingConference =& $db->getOne('SELECT TRUE FROM conferences WHERE conference_title = ? AND divid = ? AND cfid <> ? LIMIT 1', array($conference_title, DIVID, CFID));
    if ($checkExistingConference) {
        $editConferenceForm->setElementError('conference_title', 'A conference with this title already exists.');
    } else {
        $ConferenceEdit = new updateRecord('conferences', 'cfid');
        $ConferenceEdit->addData($editConferenceForm->exportValues(array('cfid', 'conference_title', 'inactive')));
        $ConferenceEdit->UpdateData();

        foreach ($editConferenceForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('conferences', CFID, $aid);
        }
        $db->query('DELETE FROM admins_assignments WHERE section = "conferences" AND pkid = '.CFID);
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);


    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editConferenceForm->accept($renderer);
$tpl->assign('edit_conference_form', $renderer->toArray());









$ACCESS = checkPermission('Edit League', 'Conference', CFID);
$addGroupForm = new HTML_QuickForm('add_group_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$addGroupForm->removeAttribute('name'); // XHTML compliance
$addGroupForm->applyFilter('__ALL__', 'trim');

$addGroupForm->addElement('text', 'group_title', 'Title');
$addGroupForm->addRule('group_title', 'Title is required.', 'required');

$admins =& $addGroupForm->addElement('select', 'admin', 'Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$addGroupForm->addElement('static', 'note_admin', 'Optional');

if (!$ACCESS) { 
    $addGroupForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $addGroupForm->freeze();
} else {
    $addGroupForm->addElement('submit', 'submit', 'Add Group');
}


$addGroupForm->setDefaults(array('cfid', CFID));
$addGroupForm->setConstants(array('cfid', CFID));
$addGroupForm->addElement('hidden', 'cfid', CFID);

if ($ACCESS && ($addGroupForm->validate()) && ($addGroupForm->exportValue('cfid') === CFID)) {
    $group_title = $addGroupForm->exportValue('group_title');
    $checkExistingGroup =& $db->getOne('SELECT TRUE FROM groups WHERE group_title = ? AND cfid = ? LIMIT 1', array($group_title, CFID));
    if ($checkExistingGroup) {
        $addGroupForm->setElementError('group_title', 'A group with this title already exists.');
    } else {
        $newGroupArray = array(
                            'group_title' => $addGroupForm->exportValue('group_title'),
                            'lid'         => LID,
                            'cfid'        => CFID,
                            'create_date_gmt' => mysqlNow()
                           );
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('groups', $newGroupArray);

        //$sql = 'INSERT INTO groups (group_title, lid, cfid, create_date_gmt) VALUES (?, '.LID.', '.CFID.', NOW())';
        //$db->query($sql, $addGroupForm->exportValues(array('group_title')));

        //$grpid =& $db->getOne('SELECT LAST_INSERT_ID()');
        $grpid = $insertRecord->lastInsertId();
        foreach ($addGroupForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('groups', $grpid, $aid);
        }
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);


        clearForm($addGroupForm);
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$addGroupForm->accept($renderer);
$tpl->assign('add_group_form', $renderer->toArray());




$sql = 'SELECT grpid, group_title, UNIX_TIMESTAMP(create_date_gmt) as create_date_gmt FROM groups WHERE cfid = ' . $db->quoteSmart(CFID);
$groupsList =& $db->getAll($sql);
$tpl->assign('groups_list', $groupsList);

displayTemplate('edit.conference');
