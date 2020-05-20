<?php

function clearForm(&$form)
{
    $blankDefaults = array();
    foreach ($form->getSubmitValues() as $key => $value) {
        if ($key != 'submit')
          $blankDefaults[$key] = '';
    }
    $form->setDefaults($blankDefaults);
}

/**
 * Returns TRUE if value does NOT exist in field $elementDbField of users table.
*/
function checkNotExistsInUsersTbl($element,$elementValue,$elementDbField)
{
    global $db;
    $sql = "SELECT TRUE FROM users WHERE $elementDbField = ?";
    return is_null($db->getOne($sql,$elementValue));
}

/**
 * Returns TRUE if value DOES exist in field $elementDbField of users table.
*/
function checkExistsInUsersTbl($element,$elementValue,$elementDbField)
{
    global $db;
    $sql = "SELECT TRUE FROM users WHERE $elementDbField = ?";
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
 * Check if the username and password fields from $registerForm or $editPasswordForm are equal.
*/
function comparePwUsername($element,$value,$arg)
{
    if (@is_object($GLOBALS['registerForm'])) {
        return (strtolower($value) != strtolower($GLOBALS['registerForm']->getElementValue($arg)));
    }
    elseif (@is_object($GLOBALS['editPasswordForm'])) {
        return (strtolower($value) != strtolower($_SESSION['username']));
    }
}

/**
 * Validate a Gregorian date.
*/
function checkValidDate($element,$value)
{
    if (@checkdate ( $value['M'], $value['d'], $value['Y'] )) return TRUE;
}

/**
 * Check if user is 13 years old today.
*/
function checkValidDob($element,$value)
{
    if (time() - mktime( 0,0,0, $value['M'], $value['d'], $value['Y'] ) > 410240038) return TRUE;
}
