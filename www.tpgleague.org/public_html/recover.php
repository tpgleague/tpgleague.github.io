<?php


$pageTitle = 'Recover Password';
require_once '../includes/inc.initialization.php';

if (loggedin()) displayError('You are already logged in!');





if (!empty($_POST['recover_input'])):
$recover_input = trim($_POST['recover_input']);
$findme  = '@';
$pos = strpos($recover_input, $findme);

if ($pos === false) {
    // username
    $sql = 'SELECT email, username, uid, firstname FROM users WHERE username = ? LIMIT 1';
    $data =& $db->getRow($sql, array($recover_input));
} else {
    // email address
    $sql = 'SELECT email, username, uid, firstname FROM users WHERE email = ? LIMIT 1';
    $data =& $db->getRow($sql, array($recover_input));
}
$username = $data['username'];
$uid = $data['uid'];
$email = $data['email'];
$firstname = $data['firstname'];

if ($email) {
    // make the key, send the e-mail
    require_once('Text/Password.php');
    $new_key = Text_Password::create(12, 'unpronounceable', 'alphanumeric');
    $sql = 'UPDATE users SET recover_key = ?, recover_timestamp_gmt = NOW() WHERE uid = ?';
    $db->query($sql, array($new_key, $uid));
    $implode = array($uid, $username, $email, $new_key);
    $imploded = implode(',', $implode);
    $encrypted = ENCRYPT_DECRYPT($imploded);
    $encoded_key = base64_encode($encrypted);
    $urlencoded_key = urlencode($encoded_key);
    $tpl->assign('recover_firstname', $firstname);
    $tpl->assign('recover_key', $urlencoded_key);

    $to      = $email;
    $subject = 'TPG password recover';
    $tpl->clear_cache('emails/recover.tpl');
    $message = $tpl->fetch('emails/recover.tpl');


    email($to, $subject, $message);
    $tpl->assign('recover_key_sent', TRUE);
} else {
    $tpl->assign('invalid_recover_input', TRUE);
}


elseif (!empty($_GET['recover_key'])):

$recover_key = $_GET['recover_key'];

$encoded_key = base64_decode($recover_key);
$imploded = ENCRYPT_DECRYPT($encoded_key);
$keyParts = explode(',', $imploded);

$uid = $keyParts[0];
$username = $keyParts[1];
$email = $keyParts[2];
$key = $keyParts[3];

// check if recover key is valid. if so, display change pw form
if (strlen($key) == 12) {
    $sql = 'SELECT TRUE FROM users WHERE uid = ? AND username = ? AND email = ? AND recover_key = ? AND ((NOW() < DATE_ADD(recover_timestamp_gmt, INTERVAL 1 DAY)) = 1 ) LIMIT 1';
    $recoverKeyValid =& $db->getOne($sql, $keyParts);
} else {
    $recoverKeyValid = FALSE;
}


if ($recoverKeyValid) {
    $editPasswordForm = new HTML_QuickForm('edit_details_form', 'post', $_SERVER['REQUEST_URI']);
    $editPasswordForm->removeAttribute('name');
    $editPasswordForm->applyFilter('__ALL__', 'trim');

    $editPasswordForm->addElement('password', 'password', 'New Password');
    $editPasswordForm->addElement('static', 'note_password', 'Minimum 6 characters.');
    $editPasswordForm->addElement('password', 'password2', 'New Password (again)');
    $editPasswordForm->addRule('password', 'Password is required.', 'required');
    $editPasswordForm->addRule('password2', 'Password is required.', 'required');
    $editPasswordForm->addRule('password', 'Password must be at least 6 characters.', 'minlength', 6);
    $editPasswordForm->registerRule('password_nospaces', 'regex', '/[\S]*/'); 
    $editPasswordForm->addRule('password', 'Your password may not contain spaces.', 'password_nospaces');
    $editPasswordForm->registerRule('compare_pw_un', 'function', 'comparePwUsername');
    $editPasswordForm->addRule('password', 'Your password may not contain your username!', 'compare_pw_un', 'username');
    $editPasswordForm->addRule(array('password', 'password2'), 'Passwords do not match.', 'compare');

    $editPasswordForm->addElement('submit', 'submit', 'Save Changes', array('class' => 'submit'));

    if ($editPasswordForm->validate()) {
        $sql = 'UPDATE users SET recover_key = "", recover_timestamp_gmt = NULL WHERE uid = ?';
        $db->query($sql, array($uid));

        $userUpdate = new updateRecord('users', 'uid', $uid);
        $userUpdate->setUID($uid);
        $userUpdate->addData('password', hashPassword($editPasswordForm->exportValue('password')));
        $userUpdate->UpdateData();
        login($username, $editPasswordForm->exportValue('password'), $remember=TRUE, $url='/edit.account.php?success');
    } else {
        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
        $editPasswordForm->accept($renderer);
        $tpl->assign('edit_password_form', $renderer->toArray());
    }
}

endif;

displayTemplate('recover');



/* 
Description : A function with a very simple but powerful xor method to encrypt 
              and/or decrypt a string with an unknown key. Implicitly the key is 
              defined by the string itself in a character by character way. 
              There are 4 items to compose the unknown key for the character 
              in the algorithm 
              1.- The ascii code of every character of the string itself 
              2.- The position in the string of the character to encrypt 
              3.- The length of the string that include the character 
              4.- Any special formula added by the programmer to the algorithm 
                  to calculate the key to use 
*/ 
FUNCTION ENCRYPT_DECRYPT($Str_Message) { 
//Function : encrypt/decrypt a string message v.1.0  without a known key 
//Author   : Aitor Solozabal Merino (spain) 
//Email    : aitor-3@euskalnet.net 
//Date     : 01-04-2005 
    $Len_Str_Message=STRLEN($Str_Message); 
    $Str_Encrypted_Message=""; 
    FOR ($Position = 0;$Position<$Len_Str_Message;$Position++){ 
        // long code of the function to explain the algoritm 
        //this function can be tailored by the programmer modifyng the formula 
        //to calculate the key to use for every character in the string. 
        $Key_To_Use = (($Len_Str_Message+$Position)+1); // (+5 or *3 or ^2) 
        //after that we need a module division because can´t be greater than 255 
        $Key_To_Use = (255+$Key_To_Use) % 255; 
        $Byte_To_Be_Encrypted = SUBSTR($Str_Message, $Position, 1); 
        $Ascii_Num_Byte_To_Encrypt = ORD($Byte_To_Be_Encrypted); 
        $Xored_Byte = $Ascii_Num_Byte_To_Encrypt ^ $Key_To_Use;  //xor operation 
        $Encrypted_Byte = CHR($Xored_Byte); 
        $Str_Encrypted_Message .= $Encrypted_Byte; 
        
        //short code of  the function once explained 
        //$str_encrypted_message .= chr((ord(substr($str_message, $position, 1))) ^ ((255+(($len_str_message+$position)+1)) % 255)); 
    } 
    RETURN $Str_Encrypted_Message; 
} //end function