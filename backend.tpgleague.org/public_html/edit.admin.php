<?php

$cssAppend[] = 'table';
require_once '../includes/inc.initialization.php';



$admin_id = $_GET['aid'];
if (!checkNumber($admin_id)) displayError('Invalid Admin ID.');
else define('ADMIN_ID', $admin_id);

$ACCESS = SUPERADMIN;

if (!$ACCESS) displayError('You are not authorized to view this page.');

$editAdminForm = new HTML_QuickForm('editAdminForm', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editAdminForm->removeAttribute('name');
$editAdminForm->applyFilter('__ALL__', 'trim');

$sql = 'SELECT admins.*, username FROM admins INNER JOIN users USING (uid) WHERE aid = '.$db->quoteSmart(ADMIN_ID);
$adminDefaults =& $db->getRow($sql);
$editAdminForm->setDefaults($adminDefaults);


$editAdminForm->addElement('text', 'uid', 'User ID');
$editAdminForm->addElement('text', 'username', 'Username');
$editAdminForm->freeze(array('uid', 'username'));


$editAdminForm->addElement('text', 'admin_name', 'Admin Name');
$editAdminForm->addElement('static', 'note_admin_name', "The person's desired admin name.");
$editAdminForm->addRule('admin_name', 'Required', 'required');

$department =& $editAdminForm->addElement('select', 'department', 'Department');
$department->loadArray(getEnumOptions('admins', 'department'));
//$addAdminForm->addRule('department', 'Required', 'required');

$seniority =& $editAdminForm->addElement('select', 'seniority', 'Seniority');
$seniority->loadArray(getEnumOptions('admins', 'seniority'));
//$addAdminForm->addRule('seniority', 'Required', 'required');

$editAdminForm->addElement('text', 'admin_email', 'TPG E-mail');
$editAdminForm->addElement('static', 'note_admin_email', '[admin]@tpgleague.org');

$editAdminForm->addElement('text', 'gtalk', 'Google Talk');
$editAdminForm->addElement('static', 'note_gtalk', '[admin]@gmail.com');

$editAdminForm->addElement('text', 'irc_nick', 'IRC Nick');

$editAdminForm->addElement('advcheckbox',
                 'superadmin',   // name of advcheckbox
                 'Superadmin',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editAdminForm->updateElementAttr(array('superadmin'), array('id' => 'superadmin'));

$editAdminForm->addElement('advcheckbox',
                 'inactive',   // name of advcheckbox
                 'Inactive',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editAdminForm->updateElementAttr(array('inactive'), array('id' => 'inactive'));


if (!$ACCESS) { 
    $editAdminForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $editAdminForm->freeze();
} else {
    $editAdminForm->addElement('submit', 'submit', 'Edit Admin');
}


if ($ACCESS && $editAdminForm->validate()) {

        if (!$editAdminForm->exportValue('inactive')) {
            $sql = 'SELECT users.deleted FROM admins INNER JOIN users USING (uid) WHERE aid = ? LIMIT 1';
            $adminUserDeleted =& $db->getOne($sql, array(ADMIN_ID));
        } else { // admin is inactive
            $db->query('DELETE FROM admins_assignments WHERE aid = '.ADMIN_ID);
        }

        if ($adminUserDeleted) { $editAdminForm->setElementError('inactive', 'Cannot re-activate a deleted user'); }
        else {
            $updateRecord = new updateRecord('admins', 'aid', ADMIN_ID);
            $updateRecord->addData($editAdminForm->exportValues(array('admin_name', 'department', 'seniority', 'admin_email', 'gtalk', 'irc_nick', 'superadmin', 'inactive')));
            $updateRecord->UpdateData();
        }
}

$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editAdminForm->accept($renderer);
$tpl->assign('edit_admin_form', $renderer->toArray());




$sql = 'SELECT `field`, from_value, to_value, tablename, tablePk, tablePkId, UNIX_TIMESTAMP(timestamp_gmt) AS unix_timestamp_gmt, `type` FROM admins_action_log WHERE aid = ? ORDER BY timestamp_gmt DESC';
$adminActionLog =& $db->getAll($sql, array(ADMIN_ID));
$tpl->assign('admin_action_log', $adminActionLog);


$sql = 'SELECT page, query, UNIX_TIMESTAMP(timestamp_gmt) AS unix_timestamp_gmt FROM admins_page_views WHERE aid = ? ORDER BY timestamp_gmt DESC';
$adminPageViews =& $db->getAll($sql, array(ADMIN_ID));
$tpl->assign('admin_page_views', $adminPageViews);






$tpl->display('edit.admin.tpl');
$tpl->display('footer.tpl');
