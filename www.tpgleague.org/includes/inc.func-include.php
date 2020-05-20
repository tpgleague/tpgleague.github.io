<?php

function escape($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}


function dateToArray($datetime)
{
    //$localDateTime = mysqlGMTtoLocal($datetime);

    $localParts = explode(' ', $datetime);
    $localDate = $localParts[0];
    $localTime = $localParts[1];
    $localTimeZone = $localParts[2];

    $localDateParts = explode('-', $localDate);
    $localDateYear = $localDateParts[0]+0;
    $localDateMonth = $localDateParts[1]+0;
    $localDateDay = $localDateParts[2]+0;
    $localDate = $localDateYear .'-'. $localDateMonth .'-'.  $localDateDay;

    $localTimeParts = explode(':', $localTime);
    $localTimeHour = $localTimeParts[0]+0;
    $localTimeMinute = $localTimeParts[1]+0;
    $localTimeSecond = $localTimeParts[2]+0;

    return array('year' => $localDateYear, 'month' => $localDateMonth, 'day' => $localDateDay, 'hour' => $localTimeHour, 'minute' => $localTimeMinute, 'second' => $localTimeSecond, 'tz' => $localTimeZone);
}


function mysqlGMTtoLocal($datetime)
{
    // $datetime = 2006-11-19 02:00:00;
    /*
    php > echo gmmktime(2, 0, 0, 11, 06, 2006);
    1162778400
    php > echo strftime("%b %d %Y %H:%M:%S", 1162778400);                              
    Nov 05 2006 19:00:00
    php > 
    strptime() would have worked too :/
    */
    
    $parts = explode(' ', $datetime);

    $date = explode('-', $parts[0]);
    $year = $date[0];
    $month = $date[1];
    $day = $date[2];

    $time = explode(':', $parts[1]);
    $hour = $time[0];
    $minute = $time[1];
    $second = $time[2];

    $Timestamp = gmmktime($hour, $minute, $second, $month, $day, $year);

    // strftime goes off of LOCALE setting, not value of get_date_default_timezone()
    //return strftime('%Y-%m-%d %H:%M:%S %Z', $Timestamp);
    return date('Y-m-d H:i:s T', $Timestamp);
}



function mysqlNow()
{
    return gmdate('Y-m-d H:i:s', mktime());
}

function safeDefaults(&$form, $defaultArray, $defaultNames) {
    foreach ($defaultNames as $key) {
        $safeDefaults[$key] = $defaultArray[$key];
    }
    $form->setDefaults($safeDefaults);
}

function safeExport(&$form, $constants=NULL) {
    $safeArray = $form->exportValues();
    foreach ($constants as $key => $value) {
        $safeArray[$key] = $value;
    }
    if (array_key_exists('submit', $safeArray)) unset($safeArray['submit']);
    return $safeArray;
}



function loadTimeZone($lid=0)
{
    if (empty($_SESSION['timezone'])) {
        if (empty($lid)) {
            date_default_timezone_set('US/Eastern');
        } else {
            global $db;
            $sql = 'SELECT tz_name FROM leagues INNER JOIN time_zones USING (tzid) WHERE lid = ? LIMIT 1';
            $tzName =& $db->getOne($sql, array($lid));
            if (!empty($tzName)) {
                date_default_timezone_set($tzName);
            } else {
                date_default_timezone_set('US/Eastern');
            }
        }
    } else {
        date_default_timezone_set($_SESSION['timezone']);
    }
}

function redirect($url=NULL)
{
    if (empty($url)) $url = $_SERVER['REQUEST_URI'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_SERVER'])) $host = $_SERVER['HTTP_X_FORWARDED_SERVER'];
    else $host = $_SERVER['HTTP_HOST'];
    session_write_close();
    $redirectURL = 'http://' . $host . $url;
    header('Location: ' . $redirectURL);
    exit();
}


function createValidationKey()
{
    return substr(MD5(uniqid(mt_rand(), TRUE)), 0, 12);
}

function checkNumber($number)
{
    return ctype_digit((string)$number);
}

function displayError($errorMessage)
{
    global $tpl;
    $tpl->assign('error', $errorMessage);
    displayTemplate('error');
    exit;
}

function loggedin()
{
    return checkNumber($_SESSION['uid']);
}

function escapeHTML($html)
{
    return htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
}

function unescapeHTML($html)
{
    return htmlspecialchars_decode($html, ENT_QUOTES);
}

function unescapeHTMLarray($htmlArray)
{
    foreach ($htmlArray as $key => $value) {
        $newArray[$key] = unescapeHTML($value);
    }
    return $newArray;
}

function sqlSelect($sqlSelectArray)
{
    return implode(', ', $sqlSelectArray);
}



function getEnumOptions($table, $column)
{
    global $db;
    $enum =& $db->getRow("SHOW COLUMNS FROM $table WHERE Field LIKE '$column'");
    preg_match_all("/'(.*?)'/", $enum['type'], $matches);
    foreach ($matches[1] as $value) {
        $enumArray[$value] = $value;
    }
    return $enumArray;
}










function utf8RawUrlDecode ($source) {
    $decodedStr = '';
    $pos = 0;
    $len = strlen ($source);
    while ($pos < $len) {
        $charAt = substr ($source, $pos, 1);
        if ($charAt == '%') {
            $pos++;
            $charAt = substr ($source, $pos, 1);
            if ($charAt == 'u') {
                // we got a unicode character
                $pos++;
                $unicodeHexVal = substr ($source, $pos, 4);
                $unicode = hexdec ($unicodeHexVal);
                $entity = '&#'. $unicode . ';';
                $decodedStr .= utf8_encode ($entity);
                $pos += 4;
            }
            else {
                // we have an escaped ascii character
                $hexVal = substr ($source, $pos, 2);
                $decodedStr .= chr (hexdec ($hexVal));
                $pos += 2;
            }
        } else {
            $decodedStr .= $charAt;
            $pos++;
        }
    }
    return $decodedStr;
}


