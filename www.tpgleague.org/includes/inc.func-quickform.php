<?php



function clearForm(&$form)
{
    $blankDefaults = array();
    foreach ($form->getSubmitValues() as $key => $value) {
        if ($key != 'submit')
          $blankDefaults[$key] = '';
    }
    $form->setConstants($blankDefaults);
}

function checkBadEmail($element,$value)
{
    //if (stripos($value, 'tpgleague') !== FALSE) return FALSE;
    return TRUE;
}



/**
 * Returns TRUE if value does NOT exist in field $elementDbField of users table.
*/
function checkNotExistsInUsersTbl($element,$elementValue,$elementDbField)
{
    global $db;
    $sql = "SELECT TRUE FROM users WHERE $elementDbField = ? LIMIT 1";
    return is_null($db->getOne($sql,$elementValue));
}



function checkPendingEmailNotExistsInUsersTbl($element,$elementValue,$elementDbField)
{
    global $db;
    $sql = "SELECT TRUE FROM users WHERE pending_email = ? AND (recover_timestamp_gmt >= DATE_SUB(NOW(), INTERVAL 1 DAY)) LIMIT 1";
    return is_null($db->getOne($sql,$elementValue));
}


/**
 * Returns TRUE if value DOES exist in field $elementDbField of users table.
*/
function checkExistsInUsersTbl($element,$elementValue,$elementDbField)
{
    global $db;
    $sql = "SELECT TRUE FROM users WHERE $elementDbField = ? LIMIT 1";
    return !is_null($db->getOne($sql,$elementValue));
}

/**
 * Check for invalid username.
*/
function checkBadUsername($element,$value)
{
    $badUsernames = array('tpg', 'test', 'admin', 'superadmin');
    foreach ($badUsernames as $badValues) {
        if (stripos($value, $badValues) === 0) return FALSE;
    }
    return TRUE;
}

/**
 * Check if the username is not in the password from $registerForm or $editPasswordForm.
*/
function comparePwUsername($element,$value,$arg)
{
    if (@is_object($GLOBALS['registerForm'])) {
        $username =& $GLOBALS['registerForm']->getElementValue($arg);
    }
    elseif (@is_object($GLOBALS['editPasswordForm'])) {
        $username =& $_SESSION['username'];
    }
    return (stripos($value, $username) === FALSE);
}

/**
 * Validate a Gregorian date.
*/
function checkValidDate($element,$value)
{
    return @checkdate($value['M'], $value['d'], $value['Y']);
}

/**
 * Check if user is 13 years old today.
*/
function checkValidDob($element,$value)
{
    return (time() - mktime( 0,0,0, $value['M'], $value['d'], $value['Y'] ) > 410240038);
}
