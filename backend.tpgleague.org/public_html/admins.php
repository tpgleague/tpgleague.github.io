<?php

require_once '../includes/inc.initialization.php';

$ACCESS = SUPERADMIN;
$addAdminForm = new HTML_QuickForm('addAdminForm', 'post', $qfAction, NULL, $onsubmit, TRUE);
$addAdminForm->removeAttribute('name');
$addAdminForm->applyFilter('__ALL__', 'trim');

$addAdminForm->addElement('text', 'username', 'Username');
$addAdminForm->addElement('static', 'note_username', "The person's existing username.");
$addAdminForm->addRule('username', 'Required', 'required');

$addAdminForm->addElement('text', 'admin_name', 'Admin Name');
$addAdminForm->addElement('static', 'note_admin_name', "The person's desired admin name.");
$addAdminForm->addRule('admin_name', 'Required', 'required');

$department =& $addAdminForm->addElement('select', 'department', 'Department');
$department->loadArray(getEnumOptions('admins', 'department'));
//$addAdminForm->addRule('department', 'Required', 'required');

$seniority =& $addAdminForm->addElement('select', 'seniority', 'Seniority');
$seniority->loadArray(getEnumOptions('admins', 'seniority'));
//$addAdminForm->addRule('seniority', 'Required', 'required');

$addAdminForm->addElement('text', 'admin_email', 'TPG E-mail');
$addAdminForm->addElement('static', 'note_admin_email', '[admin]@tpgleague.org');

$addAdminForm->addElement('text', 'gtalk', 'Google Talk');
$addAdminForm->addElement('static', 'note_gtalk', '[admin]@gmail.com');

$addAdminForm->addElement('text', 'irc_nick', 'IRC Nick');

$addAdminForm->addElement('advcheckbox',
                 'superadmin',   // name of advcheckbox
                 'Superadmin',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$addAdminForm->updateElementAttr(array('superadmin'), array('id' => 'superadmin'));


if (!$ACCESS) { 
    $addAdminForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $addAdminForm->freeze();
} else {
    $addAdminForm->addElement('submit', 'submit', 'Add Admin');
}


if ($ACCESS && $addAdminForm->validate()) {
    $newAdminUID =& $db->getOne('SELECT uid FROM users WHERE username = ? AND deleted = 0', $addAdminForm->exportValue('username'));
    $checkExistingAdmin =& $db->getOne('SELECT admin_name FROM admins INNER JOIN users USING (uid) WHERE username = ?', $addAdminForm->exportValue('username'));

    if (!checkNumber($newAdminUID)) {
        $addAdminForm->setElementError('username', 'This username does not exist.');
    } elseif (!is_null($checkExistingAdmin)) {
        $addAdminForm->setElementError('username', "This username is or already was a TPG admin ($checkExistingAdmin):");
    } else {
        $newAdminArray = array('uid'        => $newAdminUID,
                               'admin_name' => $addAdminForm->exportValue('admin_name'),
                               'department' => $addAdminForm->exportValue('department'),
                               'seniority'  => $addAdminForm->exportValue('seniority'),
                               'admin_email' => $addAdminForm->exportValue('admin_email'),
                               'gtalk'       => $addAdminForm->exportValue('gtalk'),
                               'irc_nick'   =>  $addAdminForm->exportValue('irc_nick'),
                               'superadmin' =>  $addAdminForm->exportValue('superadmin'),
                               'create_date_gmt' => mysqlNow()
                              );
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('admins', $newAdminArray);

        //$res = $db->autoExecute('admins', $newAdminArray, DB_AUTOQUERY_INSERT);
        //$db->query('INSERT INTO admins (uid, admin_name, department, seniority, admin_email, gtalk, irc_nick, superadmin, create_date_gmt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP)', $newAdminArray);
        clearForm($addAdminForm);
    }
}
if ($ACCESS) {
    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $addAdminForm->accept($renderer);
    $tpl->assign('add_admin_form', $renderer->toArray());
}


$sql = 'SELECT aid, admin_name, admin_email, uid, username, firstname, superadmin, inactive, lastname, department, seniority, irc_nick, gtalk, UNIX_TIMESTAMP(admins.create_date_gmt) as create_date_gmt FROM admins LEFT JOIN users USING (uid)';
$admins =& $db->getAll($sql);
$tpl->assign('admins_table', $admins);



$tpl->display('admins.tpl');
$tpl->display('footer.tpl');
