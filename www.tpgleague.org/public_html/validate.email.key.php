<?php

$formKey = $_GET['key'];
$username = $_GET['username'];


$pageTitle = 'Validate E-mail Key';
require_once '../includes/inc.initialization.php';

/*
if (loggedin()) {
    $sql = 'SELECT email_validation_key FROM users WHERE uid = ' . $db->quoteSmart(UID);
    $emailValidationKey =& $db->getOne($sql);
    if (empty($emailValidationKey)) displayError('Your e-mail address is already validated.');
}
*/




if (strlen($formKey) != 12) displayError('Invalid e-mail validation key.');


$row =& $db->getRow('SELECT email_validation_key, pending_email FROM users WHERE username = ' . $db->quoteSmart($username) . ' LIMIT 1');
$key = $row['email_validation_key'];
$pending_email = $row['pending_email'];

if (!empty($key) && (strtolower($key) === strtolower($formKey))) {
    $res =& $db->query('UPDATE users SET email_validation_key = "", email = ?, pending_email = "", recover_timestamp_gmt = NULL WHERE username = ?', array($pending_email, $username));
    $tpl->assign('validated_message', TRUE);
} else {
    $tpl->assign('validated_message', FALSE);
}


displayTemplate('validate.email.key');