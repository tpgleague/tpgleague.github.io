<?php

require_once '../includes/inc.initialization.php';

if (!checkNumber(@$_GET['grpid'])) { displayError('Error: Group ID not specified.'); }
else { @define('GRPID', @$_GET['grpid']); }

$sql = 'SELECT * FROM groups WHERE grpid = ' . $db->quoteSmart(GRPID);
$groupSettings =& $db->getRow($sql);
define('CFID', $groupSettings['cfid']);


$ACCESS = checkPermission('Edit League', 'Conference', CFID);
$editGroupForm = new HTML_QuickForm('edit_group_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editGroupForm->removeAttribute('name'); // XHTML compliance
$editGroupForm->applyFilter('__ALL__', 'trim');

$editGroupForm->setDefaults($groupSettings);
$editGroupForm->setConstants(array('grpid' => GRPID));

$editGroupForm->addElement('hidden', 'grpid', GRPID);
$editGroupForm->addElement('text', 'group_title', 'Title');
//$editGroupForm->addElement('text', 'create_date_gmt', 'Create Date');
//$editGroupForm->addElement('text', 'inactive', 'Inactive');
$editGroupForm->addElement('advcheckbox',
                 'inactive',   // name of advcheckbox
                 'Inactive',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editGroupForm->updateElementAttr(array('inactive'), array('id' => 'inactive'));


$admins =& $editGroupForm->addElement('select', 'admin', 'Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$selectedAdmins =& $db->getCol('SELECT aid FROM admins_assignments WHERE section = "groups" AND pkid = '.GRPID);
if ($editGroupForm->isSubmitted()) $admins->setSelected($editGroupForm->exportValue('admin'));
else $admins->setSelected($selectedAdmins);
$editGroupForm->addElement('static', 'note_admin', 'Optional');

if (!$ACCESS) { 
    $editGroupForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $editGroupForm->freeze();
} else {
    $editGroupForm->addElement('submit', 'submit', 'Edit Group');
}


if ($ACCESS && ($editGroupForm->validate()) && ($editGroupForm->exportValue('grpid') === GRPID)) {
    $group_title = $editGroupForm->exportValue('group_title');
    $checkExistingGroup =& $db->getOne('SELECT TRUE FROM groups WHERE group_title = ? AND cfid = ? AND grpid <> ? LIMIT 1', array($group_title, CFID, GRPID));
    if ($checkExistingGroup) {
        $editGroupForm->setElementError('group_title', 'A group with this title already exists.');
    } else {
        $GroupEdit = new updateRecord('groups', 'grpid');
        $GroupEdit->addData($editGroupForm->exportValues(array('grpid', 'group_title', 'inactive')));
        $GroupEdit->UpdateData();

        foreach ($editGroupForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('groups', GRPID, $aid);
        }
        $db->query('DELETE FROM admins_assignments WHERE section = "groups" AND pkid = '.GRPID);
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);


    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editGroupForm->accept($renderer);
$tpl->assign('edit_group_form', $renderer->toArray());



displayTemplate('edit.group');
