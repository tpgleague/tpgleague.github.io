<?php

$pageTitle = 'Edit Account';
require_once '../includes/inc.initialization.php';
if (!loggedin()) displayError('You must be logged in to use this function.');


$sql = 'SELECT email_validation_key, email FROM users WHERE uid = ' . $db->quoteSmart(UID);
$userEditRow =& $db->getRow($sql);
$emailValidationKey = $userEditRow['email_validation_key'];
$current_active_email = $userEditRow['email'];
$tpl->assign('current_active_email', $current_active_email);
if (!empty($emailValidationKey)) $tpl->assign('email_not_validated', TRUE);

$qfAction = $_SERVER['SCRIPT_NAME'] . '?actedit=' . $_GET['actedit'];

switch ($_GET['actedit']) {
    case 'details':
        $editDetailsForm = new HTML_QuickForm('edit_details_form', 'post', $qfAction);
        $editDetailsForm->removeAttribute('name');
        $editDetailsForm->applyFilter('__ALL__', 'trim');

        $formArray = array('firstname', 'lastname', 'hide_lastname', 'handle', 'city', 'state', 'ccode', 'steam_profile_url', 'user_avatar_url', 'user_comments');
        $sql = 'SELECT ' . sqlSelect($formArray) . ' FROM users WHERE uid = ' . $db->quoteSmart(UID);
        $formDefaults =& $db->getRow($sql);
        $editDetailsForm->setDefaults($formDefaults);

        $editDetailsForm->addElement('text', 'firstname', 'First Name', array('maxlength' => 60));
        $editDetailsForm->addRule('firstname', 'Your first name is required to register.', 'required');
        $editDetailsForm->addRule('firstname', 'First name may not exceed 60 characters.', 'maxlength', 60);
        $editDetailsForm->addRule('firstname', 'First name must be at least 2 characters.', 'minlength', 2);

        $editDetailsForm->addElement('text', 'lastname', 'Last Name', array('maxlength' => 60));
        $editDetailsForm->addRule('lastname', 'Your last name is required to register.', 'required');
        $editDetailsForm->addRule('lastname', 'Last name may not exceed 60 characters.', 'maxlength', 60);
        $editDetailsForm->addRule('lastname', 'Last name must be at least 2 characters.', 'minlength', 2);

        $editDetailsForm->addElement('advcheckbox',
                         'hide_lastname',   // name of advcheckbox
                         'Hide Last Name',  // label output before advcheckbox
                         NULL,           // label output after advcheckbox
                         array('class' => 'checkbox'),      // string or array of attributes
                         array(0,1)
                     );
        $editDetailsForm->updateElementAttr(array('hide_lastname'), array('id' => 'hide_lastname'));
        $editDetailsForm->addElement('static', 'note_hide_lastname', 'Hide last name from public viewing');


        $editDetailsForm->addElement('text', 'handle', 'Handle/Nickname', array('maxlength' => 30));
        $editDetailsForm->addRule('handle', 'Handle may not exceed 30 characters.', 'maxlength', 30);
        
        //http://steamcommunity.com/
        //http://steamcommunity.com/id/notscott
        //http://steamcommunity.com/profiles/76561197961300680
        $editDetailsForm->addElement('text', 'steam_profile_url', 'Steam Profile', array('maxlength' => 255));
        $editDetailsForm->addRule('steam_profile_url', 'Steam profile link may not exceed 255 characters.', 'maxlength', 255);
        $editDetailsForm->registerRule('url_starts_with','regex','/http:\/\/steamcommunity.com\/.*/');
        $editDetailsForm->addRule('steam_profile_url','Steam profile links must start with http://steamcommunity.com/','url_starts_with');
        
        //100px × 56px
        $editDetailsForm->addElement('text', 'user_avatar_url', 'Avatar URL (100x56px)', array('maxlength' => 255));
        $editDetailsForm->addRule('user_avatar_url', 'Avatar URL may not exceed 255 characters.', 'maxlength', 255);

        $editDetailsForm->addElement('text', 'city', 'City', array('maxlength' => 60));
        $editDetailsForm->addRule('city', 'City may not exceed 60 characters.', 'maxlength', 60);


        $statesArray =& $db->getCol('SELECT state FROM states ORDER BY state ASC');
        $editDetailsForm->addElement('autocomplete', 'state', 'State/Province', $statesArray, array('maxlength' => 60));
        $editDetailsForm->addRule('state', 'State may not exceed 60 characters.', 'maxlength', 60);

        $ccode =& $editDetailsForm->addElement('select', 'ccode', 'Country');
        $ccode->loadArray(array(''   => 'Select country',
                                'us' => 'United States of America',
                                'ca' => 'Canada',
                                'gb' => 'United Kingdom'
                          ));
        $ccode->loadQuery($db, 'SELECT country, ccode FROM countries WHERE ccode NOT IN ("us", "ca", "gb") ORDER BY country ASC');


        $editDetailsForm->addElement('textarea', 'user_comments', 'Comments', array('rows' => 5, 'cols' => '50', 'onkeydown' => 'textCounter(this,"progressbar1",4000)', 'onkeyup' => 'textCounter(this,"progressbar1",4000)', 'onfocus' => 'textCounter(this,"progressbar1",4000)'));
        $editDetailsForm->addRule('user_comments', 'Comments may not exceed 4000 characters.', 'maxlength', 4000);
        $editDetailsForm->addElement('static', 'note_user_comments', 'Maximum 4000 characters.<div id="progressbar1" class="progress"></div><script type="text/javascript">textCounter(document.getElementById("user_comments"),"progressbar1",4000)</script>');
        // <br /><div id="progressbar1" class="progress"></div><script type="text/javascript">textCounter(document.getElementById("user_comments"),"progressbar1",4000)</script>
        $tpl->append('external_js', 'textarea.progressbar');

/* (old character length box)
        $editDetailsForm->addElement('textarea', 'user_comments', 'Comments', array('rows' => 5, 'cols' => '40', 'onkeydown' => 'textCounter(this.form.user_comments, this.form.comments_remlen, 255);', 'onkeyup' => 'textCounter(this.form.user_comments, this.form.comments_remlen, 255);'));
        $editDetailsForm->addElement('static', 'comments_progressbar', '<div id="progressbar1" class="progress"></div><script>textCounter(document.getElementById("user_comments"),"progressbar1",20)</script>');
        $editDetailsForm->addElement('text', 'comments_remlen', '', array('readonly', 'size' => 3, 'maxlength' => 3, 'value' => 255, 'name' => 'comments_remlen'));
        $tpl->append('external_js', 'textarea');
        $editDetailsForm->addElement('static', 'note_comments_remlen', '(Characters left)');
*/


        $editDetailsForm->addElement('submit', 'submit', 'Save Changes', array('class' => 'submit'));

        if ($editDetailsForm->validate()) {
            $userUpdate = new updateRecord('users', 'uid', UID);
            $userUpdate->addData($editDetailsForm->exportValues());
            $userUpdate->UpdateData();
            getUserSessInfo(UID);
            redirect('/edit.account.php?success');
        } else {
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
            $editDetailsForm->accept($renderer);
            $tpl->assign('edit_details_form', $renderer->toArray());
            displayTemplate('edit.details');
        }
        break;


    case 'password':
        $editPasswordForm = new HTML_QuickForm('edit_details_form', 'post', $qfAction);
        $editPasswordForm->removeAttribute('name');
        $editPasswordForm->applyFilter('__ALL__', 'trim');

        $editPasswordForm->addElement('password', 'passwordold', 'Current Password');

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
        $editPasswordForm->addRule(array('password', 'password2'), 'New passwords do not match.', 'compare');

        $editPasswordForm->addElement('submit', 'submit', 'Save Changes', array('class' => 'submit'));

        if ($editPasswordForm->validate()) {

            $sql = 'SELECT TRUE FROM users WHERE uid = ? AND `password` = ? AND users.deleted = 0';
            $correctCurrentPW =& $db->getOne($sql, array(UID, hashPassword($editPasswordForm->exportValue('passwordold'))));
            if (!$correctCurrentPW) {
                $changePwFailure = TRUE;
                $editPasswordForm->setElementError('passwordold', 'Your current password is incorrect!');
            } else {
                if ($editPasswordForm->exportValue('passwordold') === $editPasswordForm->exportValue('password')) {
                    $changePwFailure = TRUE;
                    $editPasswordForm->setElementError('password', 'Your new password must not match your old password!');
                }
            }

            if (!$changePwFailure) {
                $userUpdate = new updateRecord('users', 'uid', UID);
                $userUpdate->addData(array('password' => hashPassword($editPasswordForm->exportValue('password'))));
                $userUpdate->UpdateData();
                redirect('/edit.account.php?success');
                break;
            }
        }

            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
            $editPasswordForm->accept($renderer);
            $tpl->assign('edit_password_form', $renderer->toArray());
            displayTemplate('change.password');

        break;


    case 'changeemail':
        $changeEmailForm = new HTML_QuickForm('change_email_form', 'post', $qfAction);
        $changeEmailForm->removeAttribute('name');
        $changeEmailForm->applyFilter('__ALL__', 'trim');

        $changeEmailForm->addElement('text', 'email', 'New e-mail address');

        function checkEmailNotExists($element,$elementValue,$elementDbField)
        {
            global $db;
            $sql = "SELECT TRUE FROM users WHERE email = ? AND uid <> ? LIMIT 1";
            return is_null($db->getOne($sql, array($elementValue, UID)));
        }
        $changeEmailForm->addElement('password', 'password', 'Current Password');

        $changeEmailForm->addRule('email', 'Please enter an e-mail address.', 'required');
        $changeEmailForm->addRule('email', 'The e-mail address you entered appears to be invalid.', 'email', NULL, 'server');
        $changeEmailForm->registerRule('check_email', 'function', 'checkEmailNotExists');
        $changeEmailForm->addRule('email', 'E-mail address already in use.', 'check_email', 'email');

        $changeEmailForm->registerRule('check_email2', 'function', 'checkPendingEmailNotExistsInUsersTbl');
        $changeEmailForm->addRule('email', 'E-mail address already in use.', 'check_email2', 'email');

        $changeEmailForm->registerRule('email_bademail', 'function', 'checkBadEmail');
        $changeEmailForm->addRule('email', 'You have entered an invalid e-mail address.', 'email_bademail');

        $changeEmailForm->addElement('submit', 'submit', 'Change Address', array('class' => 'submit'));

        if ($changeEmailForm->validate()) {

            $sql = 'SELECT TRUE FROM users WHERE uid = ? AND `password` = ? AND users.deleted = 0';
            $correctCurrentPW =& $db->getOne($sql, array(UID, hashPassword($changeEmailForm->exportValue('password'))));
            if (!$correctCurrentPW) {
                $changeEmailForm->setElementError('password', 'Your current password is incorrect!');
                $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
                $changeEmailForm->accept($renderer);
                $tpl->assign('change_email_form', $renderer->toArray());
                displayTemplate('change.email');
                break;
            }


            $newEmail = $changeEmailForm->exportValue('email');
            $sql = 'SELECT email, firstname FROM users WHERE uid = ?';
            $changeRow =& $db->getRow($sql, array(UID));
            $currentEmail = $changeRow['email'];
            $firstname = $changeRow['firstname'];

            if ($newEmail == $currentEmail) {
                $userUpdate = new updateRecord('users', 'uid', UID);
                $userUpdate->addData(array('pending_email' => '', 'email_validation_key' => ''));
                $userUpdate->UpdateData();

                $tpl->assign('reverted_email', TRUE);
            } else {

                $emailValidationKey = createValidationKey();

                $userUpdate = new updateRecord('users', 'uid', UID);
                $userUpdate->addData(array('pending_email' => $newEmail, 'email_validation_key' => $emailValidationKey));
                $userUpdate->UpdateData();
                
    //            $sql = 'UPDATE users SET email = ?, email_validation_key = ? WHERE uid = ' . $db->quoteSmart(UID);
    //            $res =& $db->query($sql, array($changeEmailForm->exportValue('email'), $emailValidationKey));
                $sql = 'SELECT username FROM users WHERE uid = ' . $db->quoteSmart(UID);
                $username =& $db->getOne($sql);

                $tpl->assign('changed_username', $username);
                $tpl->assign('changed_email_validation_key', $emailValidationKey);
                $tpl->assign('firstname', $firstname);

                $to      = $newEmail;
                $subject = 'TPG account e-mail change';
                $tpl->clear_cache('emails/change.email.tpl');
                $message = $tpl->fetch('emails/change.email.tpl');
                email($to, $subject, $message);

                $tpl->assign('success', TRUE);
            }
        } else {
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
            $changeEmailForm->accept($renderer);
            $tpl->assign('change_email_form', $renderer->toArray());
        }
        displayTemplate('change.email');
        break;


    case 'enteremailkey':
        if (empty($emailValidationKey)) displayError('Your e-mail address is already validated.');

        $enterKeyForm = new HTML_QuickForm('enter_key_form', 'post', $qfAction);
        $enterKeyForm->removeAttribute('name');
        $enterKeyForm->applyFilter('__ALL__', 'trim');

        $enterKeyForm->addElement('text', 'key', 'Validation Key', array('size' => 12, 'maxlength' => 12));
        $enterKeyForm->addElement('submit', 'submit', 'Save Changes');

        if ($enterKeyForm->validate()) {
            $validateRow =& $db->getRow('SELECT email_validation_key, pending_email FROM users WHERE uid = ' . $db->quoteSmart(UID));
            $key = $validateRow['email_validation_key'];
            $pending_email = $validateRow['pending_email'];


            $formKey =& $enterKeyForm->exportValue('key');
            if (!empty($formKey) && $key === strtolower($formKey)) {
                $sql = 'UPDATE users SET email_validation_key = "", email = ?, pending_email = "", recover_timestamp_gmt = NULL WHERE uid = ?';
                $res =& $db->query($sql, array($pending_email, UID));
                redirect('/edit.account.php?success');
                break;
            } else {
                $enterKeyForm->setElementError('key', 'Invalid key.');
            }
        }
        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
        $enterKeyForm->accept($renderer);
        $tpl->assign('enter_key_form', $renderer->toArray());
        displayTemplate('enter.email.key');
        break;

    case 'resendemail':
        if (empty($emailValidationKey)) {
            $tpl->assign('email_already_validated', TRUE);
            displayTemplate('resend.email');
            break;
        }

        if (isset($_GET['send'])) {
            $sql = 'SELECT username, pending_email, firstname FROM users WHERE uid = ' . $db->quoteSmart(UID);
            $account =& $db->getRow($sql);
            $username = $account['username'];
            $email = $account['pending_email'];
            $firstname = $account['firstname'];

            $tpl->assign('resend_username', $username);
            $tpl->assign('resend_email_validation_key', $emailValidationKey);
            $tpl->assign('firstname', $firstname);

            $to      = $email;
            $subject = 'TPG e-mail validation key';
            $tpl->clear_cache('emails/resend.key.tpl');
            $message = $tpl->fetch('emails/resend.key.tpl');
            if (!empty($email)) email($to, $subject, $message);
            displayTemplate('resent.email');
        }
        else {
            displayTemplate('resend.email');
        }
        break;

    case 'siteprefs':
        $editSitePrefsForm = new HTML_QuickForm('edit_siteprefs_form', 'post', $qfAction);
        $editSitePrefsForm->removeAttribute('name');
        $editSitePrefsForm->applyFilter('__ALL__', 'trim');

        $sql = 'SELECT tzid FROM users WHERE uid = ' . UID;
        $editSitePrefsForm->setDefaults($db->getRow($sql));

        $tzid =& $editSitePrefsForm->addElement('select', 'tzid', 'Time Zone');
        $timezonesArray =& $db->getAssoc('SELECT tzid, CONCAT_WS(" ", CONCAT("(GMT",tz_offset,")"), tz_region) FROM time_zones');
        $tzid->loadArray(array('0' => 'Always use league time zone'));
        $tzid->loadArray($timezonesArray);

        $editSitePrefsForm->addElement('submit', 'submit', 'Save Changes', array('class' => 'submit'));

        if ($editSitePrefsForm->validate()) {
            $userUpdate = new updateRecord('users', 'uid', UID);
            $userUpdate->addData($editSitePrefsForm->exportValues());
            $userUpdate->UpdateData();

            $sql = 'SELECT tz_name FROM time_zones WHERE tzid = ?';
            $timezone =& $db->getOne($sql, array($editSitePrefsForm->exportValue('tzid')));

            $_SESSION['timezone'] = $timezone;
            loadTimeZone();
            redirect('/edit.account.php?success');
        } else {
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
            $editSitePrefsForm->accept($renderer);
            $tpl->assign('edit_siteprefs_form', $renderer->toArray());
            displayTemplate('site.preferences');
        }
        break;


    case 'notifyprefs':
        displayTemplate('notify.prefs');
        break;

    case 'uploadphoto':
        displayTemplate('upload.userpic');
        break;

    default:
        if (isset($_GET['success'])) $tpl->assign('edit_form_success', TRUE);
        displayTemplate('edit.account');
}

