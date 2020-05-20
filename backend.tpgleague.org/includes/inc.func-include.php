<?php

// http://keithdevens.com/weblog/archive/2004/Jul/28/Times.PHP.MySQL
function getGmt($time){return $time-date('Z',$time);}
function getGmtDiff($lt, $ut){return ($lt-$ut) / 3600;}
function getMysqlDate($timestamp){return date("Y-m-d", $timestamp);}
function getMysqlDatetime($timestamp){return date("Y-m-d H:i:s", $timestamp);}
function getTimestamp($mysql_datetime){return strtotime($mysql_datetime);}


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


function mysqlNow()
{
    return gmdate('Y-m-d H:i:s', mktime());
}

function safeExport(&$form, $constants=NULL) {
    $safeArray = $form->exportValues();
    foreach ($constants as $key => $value) {
        $safeArray[$key] = $value;
    }
    if (array_key_exists('submit', $safeArray)) unset($safeArray['submit']);
    return $safeArray;
}

function safeDefaults(&$form, $defaultArray, $defaultNames) {
    foreach ($defaultNames as $key) {
        $safeDefaults[$key] = $defaultArray[$key];
    }
    $form->setDefaults($safeDefaults);
}




function escape($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
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

    $gmtTimestamp = gmmktime($hour, $minute, $second, $month, $day, $year);
    // strftime goes off of LOCALE setting, not value of get_date_default_timezone()
    //return strftime('%Y-%m-%d %H:%M:%S %Z', $Timestamp);
    $mysqlGMTtime = date('Y-m-d H:i:s T', $gmtTimestamp);
    return $mysqlGMTtime;
}


function displayHeader()
{
    global $tpl;
    $tpl->display('main.tpl');
    ob_flush();
    flush();
}

function displayError($errorMessage)
{
    global $tpl;
    $tpl->assign('error', $errorMessage);
    displayTemplate('error');
    exit;
}

function displayTemplate($template, $tidy=FALSE)
{
    global $tpl;

    $template = $template.'.tpl';
    $html = $tpl->fetch($template);
    if ($tidy) {
        $config = array('indent' => TRUE,
                        'indent-spaces' => 4,
                        'vertical-space' => TRUE,
                        'doctype' => 'omit',
                        'show-body-only' => TRUE,
                        'markup' => TRUE,
                        'force-output' => TRUE,
                        'output-xhtml' => TRUE,
                        'wrap' => FALSE,
                        'escape-cdata' => TRUE
                       );
        $tidy = tidy_parse_string($html, $config);
        tidy_clean_repair($tidy);
        $html = tidy_get_output($tidy);
    }
    echo $html;
    $tpl->display('footer.tpl');
}

function loadTimeZone()
{
    date_default_timezone_set('US/Eastern');
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

function checkNumber($number)
{
    return ctype_digit((string)$number);
}

function sqlSelect($sqlSelectArray)
{
    return implode(', ', $sqlSelectArray);
}



function getListings($lid)
{
    global $db, $tpl;

    $divisions =& $db->getAssoc('SELECT divid, division_title FROM divisions WHERE lid = ' . $db->quoteSmart($lid), TRUE);
    $conferences =& $db->getAssoc('SELECT divid, cfid, conference_title FROM conferences INNER JOIN divisions USING (divid) WHERE lid = ' . $db->quoteSmart($lid), NULL, NULL, NULL, TRUE);
    $groups =& $db->getAssoc('SELECT cfid, grpid, group_title FROM groups INNER JOIN conferences USING (cfid) INNER JOIN divisions USING (divid) WHERE lid = ' . $db->quoteSmart($lid), NULL, NULL, NULL, TRUE);
    $teams =& $db->getAssoc('SELECT IF(grpid IS NULL, 0, grpid) AS grpid, IF(cfid IS NULL, 0, cfid) AS cfid, IF(divid IS NULL, 0, divid) AS divid, tid, name, tag FROM teams LEFT JOIN groups USING (grpid) WHERE lid = ' . $db->quoteSmart($lid), NULL, NULL, NULL, TRUE);

    $listings = array('divisions'   => $divisions,
                       'conferences' => $conferences,
                       'groups'      => $groups,
                       'teams'       => $teams
                      );
    return $listings;
}
