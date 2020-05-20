<?php

//if ($_SERVER['REMOTE_ADDR'] != '67.190.93.216') { exit('Website down for system maintenance, 1230-130am EDT.'); }

header("Cache-Control: no-cache");
header("Pragma: nocache");

/*
if (gmmktime() < 1172062800) {
    header('HTTP/1.1 503 Service Unavailable');
    header('Retry-After: Wed, 21 Feb 2007 13:00:00 GMT');
    exit('Website offline for system maintenance (12am-8am EST).');
}
*/

/*
 *--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==
 *-- @Purpose:        All initialization procedures and their associated functions.             ==
 *-- @Interval:       Runs for EVERY page call.                                                 ==
 *-- @Author:         S. Nagheenanajar                                                          ==
 *-- @Author:         M. Bolton                                                                 ==
 *-- @Create Date:    2006-07-10                                                                ==
 *--                                                                                            ==
 *-- To-Do:                                                                                     ==
 *--   Function                  Description                                                    ==
 *--   ------------------------- -------------------------------------------------------------- ==
 *--                                                                                            == 
 *--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==
*/

ini_set('display_startup_errors', 1);
ini_set('register_globals', 0);
/********  DEBUG STUFF - COMMENT THIS STUFF OUT ONCE SITE IS LIVE!  ***********/
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
ini_set('display_errors', 1);

/*
ob_start();
include_once 'debugConsole.php';
$_debugConsoleConfig['active'] = TRUE;

$dbgQueryStr = $_SERVER['QUERY_STRING'];
parse_str($dbgQueryStr, $dbgQueryArray);
$dbgQueryArray = @array_merge($dbgQueryArray, array('debug' => NULL));
$dbgQuery = http_build_query($dbgQueryArray);

switch (@$_GET['debug']) {
    case 'full':
        include_once('debug.php');
        $Dbg = &new Debug(DBG_MODE_FULL); // DBG_MODE_OFF DBG_MODE_USERPERF DBG_MODE_QUERY DBG_MODE_QUERYTEMP DBG_MODE_FULL DBG_MODE_AUTO
        $Dbg->DebugDisplay();
        exit;
    case 'highlight':
        include_once 'PHP_Highlight.php';
        $phpHighlight = new PHP_Highlight;
        $phpHighlight->loadFile($_SERVER['SCRIPT_FILENAME']);
        $phpHighlight->toList(FALSE);
        exit;
    case 'console':
        $_debugConsoleConfig['active'] = TRUE;
        dc_watch(NULL);
}
*/
/*****************************  END DEBUG STUFF!  *****************************/

header('Content-Type: text/html; charset=UTF-8');

ini_set('include_path',
        implode(PATH_SEPARATOR,
                array(realpath(dirname(__FILE__)),
                      realpath('../../common_includes'),
                      realpath('../../common_includes/pear'),
                      realpath('../../common_includes/Smarty'),
                      ini_get('include_path')
                     )
               )
       );

define( 'COMMON_EMAILS_PATH', realpath( __DIR__ . '/../../common_emails' ) );



// get config file
require_once 'inc.config.php';


// Get Smarty ^_^
require_once 'Smarty.class.php';
$tpl = new Smarty();

$tpl->security = FALSE;
$tpl->use_sub_dirs = TRUE;
$tpl->plugins_dir[] = '../smarty/plugins';
$tpl->template_dir = '../smarty/templates';
$tpl->config_dir = '../smarty/configs';
$tpl->compile_dir = '../smarty/templates_c';
$tpl->cache_dir = '../smarty/cache';

$tpl->caching = FALSE;
$tpl->cache_lifetime = 0; // set default caching to always regenerate
$tpl->compile_check = TRUE;
$tpl->force_compile = FALSE;
$tpl->error_reporting = E_ALL ^ E_WARNING ^ E_NOTICE;
//$tpl->debugging = (@$_GET['debug'] == 'smarty'); // remove when site live!
//$tpl->assign('debug_query', @$dbgQuery); // for the debugging links box

// The currently executing page
define('CURRENT_PAGE', basename($_SERVER['SCRIPT_NAME'], '.php'));
$tpl->assign('current_page', CURRENT_PAGE);
date_default_timezone_set('America/New_York');
include_once '../smarty/plugins/modifier.converted_timezone.php';
$tpl->register_modifier('converted_timezone', 'smarty_modifier_converted_timezone');


$frontend = new Smarty();
$frontend->security = FALSE;
$frontend->use_sub_dirs = TRUE;
//OLD SITE'S PATH: $frontend->template_dir = '/home/tpgsite/domains/tpgleague.org/smarty/templates';
//OLD SITE'S PATH: $frontend->config_dir = '/home/tpgsite/domains/tpgleague.org/smarty/configs';
//OLD SITE'S PATH: $frontend->compile_dir = '/home/tpgsite/domains/tpgleague.org/smarty/templates_c';
//OLD SITE'S PATH: $frontend->cache_dir = '/home/tpgsite/domains/tpgleague.org/smarty/cache';
$frontend->template_dir = '../../www.tpgleague.org/smarty/templates';
$frontend->config_dir = '../../www.tpgleague.org/smarty/configs';
$frontend->compile_dir = '../../www.tpgleague.org/smarty/templates_c';
$frontend->cache_dir = '../../www.tpgleague.org/smarty/cache';




// Load this goodness:
require_once 'PEAR.php';

// PEAR DB connector:
require_once 'DB.php';
// Parameters for connecting to the database:
// $dsn moved to inc.config.php
$dsnOptions = array(
                    'debug'       => 2,
                    'autofree'    => FALSE,  // This gives PHP warnings if set to TRUE because using getAll, getOne, getAssoc, getCol, getRow will autofree the resultset anyway. I will handle freeing prepared statements manually.
                    'persistent'  => FALSE,
                    'portability' => DB_PORTABILITY_ALL
                   );
$db =& DB::connect($dsn,$dsnOptions);
if (PEAR::isError($db)) {
    // Print header, footer.
    header("HTTP/1.1 500 Internal Server Error");
    echo 'ERROR: The database seems to be down.';
    exit;
}
$db->setFetchMode(DB_FETCHMODE_ASSOC);
$db->query('SET NAMES "UTF8", time_zone = "UTC", sql_safe_updates = 0');
unset($dsn);

// This must be changed once the site goes live.
$db->setErrorHandling(PEAR_ERROR_CALLBACK, 'pearHandleError');
function pearHandleError($error)
{
    //header("HTTP/1.1 500 Internal Server Error");
    
    $err = "\r\n<br><font color=\"red\"><br>\r\n";
    $err .= 'Standard Message: ' . $error->getMessage() . "<br>\r\n";
    $err .= 'Standard Code: ' . $error->getCode() . "<br>\r\n";
    $err .= 'DBMS/User Message: ' . $error->getUserInfo() . "<br>\r\n";
    $err .= 'DBMS/Debug Message: ' . $error->getDebugInfo() . "<br>\r\n";
    $err .= $error->message . "<br>\r\n" . $error->userinfo . "<br>\r\n";
    //OLD SITE'S PATH: file_put_contents('/home/tpgsite/domains/backend.tpgleague.org/error.log.html', $err, FILE_APPEND);
	file_put_contents('../logs/error.log.html' , $err, FILE_APPEND);
    displayError('<div><p style="color:red; font-weight: bold; font-size: 125%;">MySQL Error:</p><p>'.$err.'</p></div>');
    exit;
}
function ajaxShortError($error)
{
    global $date;
    echo $date .'|error|An unknown error has occurred. Please check your input and report this back to Brian.';
    exit;
}

// Start the session
ini_set('session.use_only_cookies', TRUE);
session_start();


function __autoload($className)
{
    switch ($className) {
        case 'HTML_QuickForm':
            require_once 'inc.quickform.php';
            // also load the form stylesheet into the HTML <head>
            global $tpl;
            $tpl->append('external_css', 'form');
            break;
        case 'updateRecord':
            require_once 'inc.cls-updateRecord.php';
    }
}





$onsubmit = 'onsubmit="this.submit.disabled = true; return true"';
require_once 'inc.quickform.php';
require_once 'inc.func-include.php';
require_once 'inc.func-common.includes.php';
require_once 'inc.parameter-defines.php';
require_once 'inc.login.php';
require_once 'inc.admin-permissions.php';
require_once 'inc.func-schedule.php';
require_once 'inc.cls-updateRecord.php';
require_once 'inc.func-checkTeamRosterLock.php';


// Populate $qfAction for use in QuickForm class constructor (action URL).
$qfAction = $_SERVER['REQUEST_URI'];

// Now we can use any $_GET as a constant and be assured that is a number like we usually expect.
/*
foreach ($_GET as $constant => $value) {
    if (checkNumber($value)) define(strtoupper($constant), $value, FALSE);
    // lot's of things need to help us find the LID, though:
    // divid, grpid, cfid, tid
}
*/

if (checkNumber(AID) && !(CURRENT_PAGE == 'edit.schedule' && !empty($_GET['wt']))) {
    $sql = 'SELECT page, query FROM admins_page_views WHERE aid = ? AND timestamp_gmt > DATE_SUB(NOW(), INTERVAL 5 MINUTE) ORDER BY timestamp_gmt DESC LIMIT 1';
    $pageViewed =& $db->getRow($sql, array(AID));
    if ($pageViewed['page'] != CURRENT_PAGE || $pageViewed['query'] != $_SERVER['QUERY_STRING']) {
        $sql = 'INSERT INTO admins_page_views (aid, page, query, timestamp_gmt) VALUES (?, ?, ?, NOW())';
        $res =& $db->query($sql, array(AID, CURRENT_PAGE, $_SERVER['QUERY_STRING']));
    }
    unset($pageViewed, $sql, $res);
}


if ($_SERVER['REQUEST_URI'] == '/') $request_uri = '/index.php';
else $request_uri = $_SERVER['REQUEST_URI'];


$sql = 'REPLACE INTO admins_breadcrumbs (aid, page) VALUES (?, ?)';
$db->query($sql, array(AID, $request_uri));

$sql = 'SELECT id, page FROM admins_breadcrumbs WHERE aid = ? ORDER BY id DESC LIMIT 5';
$pageHistory =& $db->getAssoc($sql, FALSE, array(AID));

end($pageHistory);
$earliestPage = key($pageHistory);
$pageHistory = array_reverse($pageHistory);

$sql = 'DELETE FROM admins_breadcrumbs WHERE aid = ? AND id < ?';
$db->query($sql, array(AID, $earliestPage));

$tpl->assign('page_history', $pageHistory);
