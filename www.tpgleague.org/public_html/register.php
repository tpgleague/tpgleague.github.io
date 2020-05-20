<?php

// make sure Quickform clears out the captcha code after every submit
if ($_POST['captcha_code']) {
    $post_captcha_code = $_POST['captcha_code'];
    unset($_POST['captcha_code']);
}

$pageTitle = 'Registration';
require_once('../includes/inc.initialization.php');



if (loggedin()) displayError('You are already registered!');

// create the form object
$registerForm = new HTML_QuickForm('register_form', 'post', NULL, NULL, $onsubmit, FALSE);
$registerForm->removeAttribute('name'); // XHTML compliance
// $registerForm->setRequiredNote('All <span style="font-weight:bold;">bold</span> fields are required.');
// $registerForm->setJsWarnings('In order to register, please correct the following:','');
$registerForm->applyFilter('__ALL__', 'trim');


// populate the form with the elements and their rules!
$registerForm->addElement('text', 'username', 'Username', array('maxlength' => 32));
$registerForm->addElement('static', 'note_username', 'Minimum 3 characters.');
$registerForm->addRule('username', 'Username is required', 'required');
$registerForm->addRule('username', 'Username must be at least 3 characters.', 'minlength', 3);
$registerForm->addRule('username', 'Username can be at most 32 characters.', 'maxlength', 32);
$registerForm->registerRule('username_beginletter', 'regex', '/^[a-zA-Z]/'); 
$registerForm->addRule('username', 'Username must begin with a letter.', 'username_beginletter');
$registerForm->registerRule('username_badusername', 'function', 'checkBadUsername');
$registerForm->addRule('username', 'You have entered an invalid username.', 'username_badusername');
$registerForm->addRule('username', 'Username may only contain letters and numbers and have no spaces.', 'alphanumeric');
$registerForm->registerRule('check_un', 'function', 'checkNotExistsInUsersTbl');
$registerForm->addRule('username', 'Username already taken. Please choose a different one. If this username belongs to you, then please use the <a href="/recover/">account/password recovery page</a>.', 'check_un', 'username');


$registerForm->addElement('password', 'password', 'Password');
$registerForm->addElement('static', 'note_password', 'Minimum 6 characters.');
$registerForm->addElement('password', 'password2', 'Password (again)');
$registerForm->addRule('password', 'Password is required.', 'required');
$registerForm->addRule('password2', 'Password is required.', 'required');
$registerForm->addRule('password', 'Password must be at least 6 characters.', 'minlength', 6);
$registerForm->registerRule('password_nospaces', 'regex', '/[\S]*/'); 
$registerForm->addRule('password', 'Your password may not contain spaces.', 'password_nospaces');
$registerForm->registerRule('compare_pw_un', 'function', 'comparePwUsername');
$registerForm->addRule('password', 'Your password may not contain your username!', 'compare_pw_un', 'username'); 
$registerForm->addRule(array('password', 'password2'), 'Passwords do not match.', 'compare');

$registerForm->addElement('text', 'email', 'E-mail');
$registerForm->addRule('email', 'Your e-mail address is required to register.', 'required');
$registerForm->addRule('email', 'The e-mail address you entered appears to be invalid.', 'email', NULL, 'server');

$registerForm->registerRule('check_email', 'function', 'checkNotExistsInUsersTbl');
$registerForm->addRule('email', 'E-mail address already taken. If this address belongs to you, then please use the <a href="/recover/">account/password recovery page</a>.', 'check_email', 'email');

$registerForm->registerRule('check_email2', 'function', 'checkPendingEmailNotExistsInUsersTbl');
$registerForm->addRule('email', 'E-mail address already taken. If this address belongs to you, then please use the <a href="/recover/">account/password recovery page</a>.', 'check_email2', 'email');

$registerForm->registerRule('email_bademail', 'function', 'checkBadEmail');
$registerForm->addRule('email', 'You have entered an invalid e-mail address.', 'email_bademail');


$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'dMY',
                     'maxYear'         => 1900,
                     'minYear'         => date('Y'),
                     'addEmptyOption'  => array('d' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('d' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
$registerForm->addElement('date', 'dob', 'Date of Birth', $dateOptions);
$registerForm->addRule('dob', 'Please enter a valid date of birth.', 'required');
$registerForm->registerRule('valid_date', 'function', 'checkValidDate');
$registerForm->addRule('dob', 'Please enter a valid date of birth.', 'valid_date');
$registerForm->registerRule('valid_dob', 'function', 'checkValidDob');
$registerForm->addRule('dob', "We're sorry, but in order to comply with the COPPA Act of 2001, all participants must be 13 years of age or older.", 'valid_dob');


$registerForm->addElement('text', 'firstname', 'First Name', array('maxlength' => 60));
$registerForm->addRule('firstname', 'Your first name is required to register.', 'required');
$registerForm->addRule('firstname', 'First name may not exceed 60 characters.', 'maxlength', 60);
$registerForm->addRule('firstname', 'First name must be at least 2 characters.', 'minlength', 2);

$registerForm->addElement('text', 'lastname', 'Last Name', array('maxlength' => 60));
$registerForm->addRule('lastname', 'Your last name is required to register.', 'required');
$registerForm->addRule('lastname', 'Last name may not exceed 60 characters.', 'maxlength', 60);
$registerForm->addRule('lastname', 'Last name must be at least 2 characters.', 'minlength', 2);

$registerForm->addElement('advcheckbox',
                 'hide_lastname',   // name of advcheckbox
                 'Hide Last Name',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$registerForm->updateElementAttr(array('hide_lastname'), array('id' => 'hide_lastname'));
$registerForm->addElement('static', 'note_hide_lastname', 'Hide last name from public viewing');



$registerForm->addElement('text', 'handle', 'Handle/Nickname', array('maxlength' => 60));
$registerForm->addRule('handle', 'Handle may not exceed 60 characters.', 'maxlength', 60);

$registerForm->addElement('text', 'city', 'City', array('maxlength' => 60));
$registerForm->addRule('city', 'City may not exceed 60 characters.', 'maxlength', 60);

//$registerForm->addElement('text', 'state', 'State/Province');
$statesArray =& $db->getCol('SELECT state FROM states ORDER BY state ASC');
$registerForm->addElement('autocomplete', 'state', 'State/Province', $statesArray, array('maxlength' => 60));
$registerForm->addRule('state', 'State may not exceed 60 characters.', 'maxlength', 60);


$ccode =& $registerForm->addElement('select', 'ccode', 'Country', NULL, array('class' => 'select_break'));
$ccode->loadArray(array(''   => 'Select country',
                        'us' => 'United States of America',
                        'ca' => 'Canada',
                        'gb' => 'United Kingdom'
                  ));
$ccode->loadQuery($db, 'SELECT country, ccode FROM countries WHERE ccode NOT IN ("us", "ca", "gb") ORDER BY country ASC');

$registerForm->addElement('textarea', 'user_comments', 'Comments', array('rows' => 5, 'cols' => '50', 'onkeydown' => 'textCounter(this,"progressbar1",4000)', 'onkeyup' => 'textCounter(this,"progressbar1",4000)', 'onfocus' => 'textCounter(this,"progressbar1",4000)'));
$registerForm->addRule('user_comments', 'Comments may not exceed 4000 characters.', 'maxlength', 4000);
$registerForm->addElement('static', 'note_user_comments', 'Maximum 4000 characters.<div id="progressbar1" class="progress"></div><script type="text/javascript">textCounter(document.getElementById("user_comments"),"progressbar1",4000)</script>');
$tpl->append('external_js', 'textarea.progressbar');


$tzid =& $registerForm->addElement('select', 'tzid', 'Time Zone');
$timezonesArray =& $db->getAssoc('SELECT tzid, CONCAT_WS(" ", CONCAT("(GMT",tz_offset,")"), tz_region) FROM time_zones');
$tzid->loadArray(array('0' => 'Always use league time zone'));
$tzid->loadArray($timezonesArray);
$registerForm->addElement('static', 'note_tzid', 'Times on the website will be displayed in this time zone.');

/*
$registerForm->addElement('advcheckbox',
                 'remember',   // name of advcheckbox
                 'Remember login info',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$registerForm->updateElementAttr(array('remember'), array('id' => 'remember'));
*/

$captchaImage = '<div class="static"><img src="/imagebuilder.php?rand='. rand(111111,999999) .'" alt="CAPTCHA" id="captcha" /></div>';
$registerForm->addElement('static', 'captcha', 'Verification Image', $captchaImage);
$registerForm->addElement('text', 'captcha_code', 'Enter Verification Code', array('value' => '', 'size' => 8, 'maxlength' => 5));
$registerForm->addElement('static', 'note_captcha_code', 'Please enter the code shown above.');


$registerForm->addElement('submit', 'submit', 'Register', array('class' => 'submit'));

// Finished defining form. Check if it's valid now:
if ($registerForm->validate()) {

    $string = strtoupper($_SESSION['string']);
    $userstring = strtoupper($post_captcha_code); 
    unset($_SESSION['string']);
    if (($string !== $userstring) || (strlen($string) < 5)) {
        $registerForm->setElementError('captcha_code', 'Incorrect validation code.');
        $formFailure = TRUE;
    } else {

        $emailValidationKey = createValidationKey();
        $sql = 'INSERT INTO users '
             . '(username, password, firstname, lastname, hide_lastname, email_validation_key,  dob, '
             . 'handle, city, state, ccode, user_comments, tzid, create_date_gmt, pending_email) '
             . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP, ?)';
        $dob = $registerForm->exportValue('dob');
        $username = $registerForm->exportValue('username');
        $pwHash = hashPassword($registerForm->exportValue('password'));
        $valuesArray = array(
                             $username,
                             $pwHash,
                             $registerForm->exportValue('firstname'),
                             $registerForm->exportValue('lastname'),
                             $registerForm->exportValue('hide_lastname'),
                             //$registerForm->exportValue('email'),
                             $emailValidationKey,
                             $dob['Y'] . '-' . $dob['M'] . '-' . $dob['d'],
                             $registerForm->exportValue('handle'),
                             $registerForm->exportValue('city'),
                             $registerForm->exportValue('state'),
                             $registerForm->exportValue('ccode'),
                             $registerForm->exportValue('user_comments'),
                             $registerForm->exportValue('tzid'),
                             $registerForm->exportValue('email')
                            );
        $res =& $db->query($sql,$valuesArray);

        $tpl->assign('registered_firstname', $registerForm->exportValue('firstname'));
        $tpl->assign('registered_username', $username);
        $tpl->assign('registered_email_validation_key', $emailValidationKey);

        $to      = $registerForm->exportValue('email');
        $subject = 'TPG Registration';
        $tpl->clear_cache('emails/welcome.tpl');
        $message = $tpl->fetch('emails/welcome.tpl');
        email($to, $subject, $message);
        login($username, $registerForm->exportValue('password'), TRUE, '/new-user/');
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$registerForm->accept($renderer);
$tpl->assign('register_form', $renderer->toArray());




displayTemplate('register');
