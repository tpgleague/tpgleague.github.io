<?php

require_once '../includes/inc.initialization.php';


if (!checkNumber(@$_GET['divid'])) { displayError('Error: Division ID not specified.'); }
else { @define('DIVID', @$_GET['divid']); }

$sql = 'SELECT * FROM divisions WHERE divid = ' . $db->quoteSmart(DIVID);
$divisionSettings =& $db->getRow($sql);
$lid = $divisionSettings['lid'];
define('LID', $lid);

$ACCESS = checkPermission('Edit League', 'League', LID);

$editDivisionForm = new HTML_QuickForm('edit_division_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editDivisionForm->removeAttribute('name'); // XHTML compliance
$editDivisionForm->applyFilter('__ALL__', 'trim');

$editDivisionForm->setDefaults($divisionSettings);
$editDivisionForm->setConstants(array('divid' => DIVID));

$editDivisionForm->addElement('hidden', 'divid', DIVID);
$editDivisionForm->addElement('text', 'division_title', 'Title');
//$editDivisionForm->addElement('text', 'create_date_gmt', 'Create Date');
//$editDivisionForm->addElement('text', 'inactive', 'Inactive');

$editDivisionForm->addElement('advcheckbox',
                 'inactive',   // name of advcheckbox
                 'Inactive',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editDivisionForm->updateElementAttr(array('inactive'), array('id' => 'inactive'));


$admins =& $editDivisionForm->addElement('select', 'admin', 'Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$selectedAdmins =& $db->getCol('SELECT aid FROM admins_assignments WHERE section = "divisions" AND pkid = '.DIVID);
if ($editDivisionForm->isSubmitted()) $admins->setSelected($editDivisionForm->exportValue('admin'));
else $admins->setSelected($selectedAdmins);
$editDivisionForm->addElement('static', 'note_admin', 'Optional');

if (!$ACCESS) { 
    $editDivisionForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $editDivisionForm->freeze();
} else {
    $editDivisionForm->addElement('submit', 'submit', 'Edit Division');
}


if ($ACCESS && ($editDivisionForm->validate()) && ($editDivisionForm->exportValue('divid') === DIVID)) {
    $division_title = $editDivisionForm->exportValue('division_title');
    $checkExistingDivision =& $db->getOne('SELECT TRUE FROM divisions WHERE division_title = ? AND lid = ? AND divid <> ? LIMIT 1', array($division_title, LID, DIVID));
    if ($checkExistingDivision) {
        $editDivisionForm->setElementError('division_title', 'A division with this title already exists.');
    } else {
        $DivisionEdit = new updateRecord('divisions', 'divid');
        $DivisionEdit->addData($editDivisionForm->exportValues(array('divid', 'division_title', 'inactive')));
        $DivisionEdit->UpdateData();

        foreach ($editDivisionForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('divisions', DIVID, $aid);
        }
        $db->query('DELETE FROM admins_assignments WHERE section = "divisions" AND pkid = '.DIVID);
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);

    
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editDivisionForm->accept($renderer);
$tpl->assign('edit_division_form', $renderer->toArray());












$ACCESS = checkPermission('Edit League', 'Division', DIVID);



$addConferenceForm = new HTML_QuickForm('add_conference_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$addConferenceForm->removeAttribute('name'); // XHTML compliance
$addConferenceForm->applyFilter('__ALL__', 'trim');

$addConferenceForm->addElement('text', 'conference_title', 'Title');
$addConferenceForm->addRule('conference_title', 'Title is required.', 'required');

$admins =& $addConferenceForm->addElement('select', 'admin', 'Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$addConferenceForm->addElement('static', 'note_admin', 'Optional');




if (!$ACCESS) { 
    $addConferenceForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $addConferenceForm->freeze();
} else {
    $addConferenceForm->addElement('submit', 'submit', 'Add Conference');
}


$addConferenceForm->setDefaults(array('divid', DIVID));
$addConferenceForm->setConstants(array('divid', DIVID));
$addConferenceForm->addElement('hidden', 'divid', DIVID);

if ($ACCESS && ($addConferenceForm->validate()) && ($addConferenceForm->exportValue('divid') === DIVID)) {
    $conference_title = $addConferenceForm->exportValue('conference_title');
    $checkExistingConference =& $db->getOne('SELECT TRUE FROM conferences WHERE conference_title = ? AND divid = ? LIMIT 1', array($conference_title, DIVID));
    if ($checkExistingConference) {
        $addConferenceForm->setElementError('conference_title', 'A conference with this title already exists.');
    } else {
        $newConferenceArray = array(
                                    'conference_title' => $addConferenceForm->exportValue('conference_title'),
                                    'lid'         => LID,
                                    'divid'        => DIVID,
                                    'create_date_gmt' => mysqlNow()
                                   );
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('conferences', $newConferenceArray);

        //$sql = 'INSERT INTO conferences (conference_title, lid, divid, create_date_gmt) VALUES (?, '.LID.', '.DIVID.', NOW())';
        //$db->query($sql, $addConferenceForm->exportValues(array('conference_title')));
        //$cfid =& $db->getOne('SELECT LAST_INSERT_ID()');

        $cfid = $insertRecord->lastInsertId();
        foreach ($addConferenceForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('conferences', $cfid, $aid);
        }
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);

        clearForm($addConferenceForm);
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$addConferenceForm->accept($renderer);
$tpl->assign('add_conference_form', $renderer->toArray());






$sql = 'SELECT cfid, conference_title, UNIX_TIMESTAMP(create_date_gmt) as create_date_gmt FROM conferences WHERE divid = ' . $db->quoteSmart(DIVID);
$conferencesList =& $db->getAll($sql);
$tpl->assign('conferences_list', $conferencesList);

displayTemplate('edit.division');
