<?php

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
 *--   Template caching          Templates should be cached for optimal performance once live.  ==
 *--                                                                                            == 
 *--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==
*/

/*
if (($_SERVER['REMOTE_ADDR'] !== '24.239.122.53') && ($_SERVER['REMOTE_ADDR'] !== '174.51.65.194')) {
    echo 'Website offline for maintenance.';
    exit;
}
*/

error_reporting(0);
ini_set('display_errors', 0);

/********  DEBUG STUFF - COMMENT THIS STUFF OUT ONCE SITE IS LIVE!  ***********/
//error_reporting(E_ALL);
/*
error_reporting(0);
ini_set('display_errors', 0);
if (($_SERVER['REMOTE_ADDR'] === '24.239.122.53') || ($_SERVER['REMOTE_ADDR'] === '174.51.65.194')) {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);
    ob_start();
}
*/

//include_once 'debugConsole.php';
//$_debugConsoleConfig['active'] = TRUE;
//dc_watch(NULL);

/*
$dbgQueryStr = $_SERVER['QUERY_STRING'];
parse_str($dbgQueryStr, $dbgQueryArray);
$dbgQueryArray = @array_merge($dbgQueryArray, array('debug' => NULL, 'compile' => NULL));
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

define('CURRENT_PAGE', basename($_SERVER['SCRIPT_NAME'], '.php'));


require_once 'inc.includes.php';






ini_set('register_globals', 0);

define( 'COMMON_EMAILS_PATH', realpath( __DIR__ . '/../../common_emails' ) );


// Get Smarty ^_^

$tpl = new Smarty();

$tpl->security = FALSE;
$tpl->use_sub_dirs = TRUE;
$tpl->plugins_dir[] = '../smarty/plugins';
$tpl->template_dir = '../smarty/templates';
$tpl->config_dir = '../smarty/configs';
//$tpl->compile_id = CURRENT_PAGE;
$tpl->compile_dir = '../smarty/templates_c';
$tpl->cache_dir = '../smarty/cache';

$tpl->compile_check = TRUE;
//$tpl->clear_compiled_tpl();
$tpl->clear_all_cache();
$tpl->caching = 0;
$tpl->cache_lifetime = 0; // set default caching to always regenerate

$tpl->error_reporting = 0;
/*
if ($_SERVER['REMOTE_ADDR'] == '67.190.59.243') {
    $tpl->force_compile = TRUE;
    $tpl->debugging = TRUE;
}
*/



//$tpl->assign('smarty_time', '%l:%M %p %Z');
//$tpl->assign('smarty_date', '%b %d');

//$tpl->assign('dbgToggle', (@$_GET['debug'] == 'smarty')); // remove when site live!
//$tpl->assign('debug_query', @$dbgQuery); // for the debugging links box

// The currently executing page
$tpl->assign('current_page', CURRENT_PAGE);





// This must be changed once the site goes live.
$db->setErrorHandling(PEAR_ERROR_CALLBACK, 'pearHandleError');
function pearHandleError($error)
{
    header("HTTP/1.1 500 Internal Server Error");

    $err = gmdate(DATE_COOKIE)." <br> ";
    $err .= 'UID: '. UID ." <br> ";
    $err .= 'IP: '. $_SERVER['REMOTE_ADDR'] . " <br> ";

    $err .= 'Standard Message: ' . $error->getMessage() ." <br> ";
    $err .= 'Standard Code: ' . $error->getCode() ." <br> ";
    $err .= 'DBMS/User Message: ' . $error->getUserInfo() ." <br> ";
    $err .= 'DBMS/Debug Message: ' . $error->getDebugInfo() ." <br> ";
    $err .= $error->message . ' <br> ' . $error->userinfo ." <br> ";

    $err .= $_SERVER['REQUEST_URI'] . ' <br> ';
    $POST = print_r($_POST, TRUE);
    $POST = mb_convert_encoding($POST, 'HTML-ENTITIES', 'UTF-8');

    $subject = 'DB error';
    $headers = 'From: error@tpgleague.org';

    if (MY_DISPLAY_ERRORS === 1) {
        echo '<pre>'.escape($err)."\r\n\r\n".$POST.'</pre>';
    } else {
        $err = '<p> '. str_replace(array("\r", "\n"), ' <br> ', $err . ' <br> <pre>' . $POST .'</pre>') . ' </p> '."\r\n"
             . '<br> Connected for ' . microtime(TRUE) - MYSQL_CONNECTION_TIME . ' seconds.'."\r\n"
             . '<br> Connection ID: ' . MYSQL_CONNECTION_ID . "\r\n";
        mail('bullet@tpgleague.org', $subject, $err, $headers);
        file_put_contents('../logs/error.log.html', $err, FILE_APPEND);
        mail('bullet@tpgleague.org', $subject, $error->getDebugInfo(), $headers);
        displayError('An unknown error has occurred.');
    }

    exit;
}


function pearSpecialCharacters($error)
{
    $findError = strpos($error->getDebugInfo(), 'Illegal mix of collations');
    if ($findError !== FALSE) return FALSE;


    header("HTTP/1.1 500 Internal Server Error");

    $err = 'UID: '. UID ." \r\nIP: ". $_SERVER['REMOTE_ADDR'] . "\r\n\r\n";

    $err .= 'Standard Message: ' . $error->getMessage();
    $err .= 'Standard Code: ' . $error->getCode();
    $err .= 'DBMS/User Message: ' . $error->getUserInfo();
    $err .= 'DBMS/Debug Message: ' . $error->getDebugInfo();
    $err .= $error->message . "<br>\r\n" . $error->userinfo;

    $err .= "\r\n\r\n".$_SERVER['REQUEST_URI'];
    $POST = print_r($_POST, TRUE);
    $POST = mb_convert_encoding($POST, 'HTML-ENTITIES', 'UTF-8');
    $err .= "\r\n". ($POST);

    $subject = 'DB error';
    $headers = 'From: error@tpgleague.org';

    mail('bullet@tpgleague.org', $subject, $err, $headers);
    mail('billkamm@gmail.com', $subject, $error->getDebugInfo(), $headers);


    displayError('An unknown error has occurred.');

    //displayError('Website unavailable due to system maintenance. Please check back shortly.');
    exit;
}

// Start the session
ini_set('session.use_only_cookies', TRUE);
session_start();


function __autoload($className)
{
   if ($className == 'HTML_QuickForm') {
        global $tpl, $onsubmit;
        $onsubmit = 'onsubmit="this.submit.disabled = true; return true"';
        require_once 'inc.quickform.php';
        $tpl->append('external_css', 'form');
   }
}

require_once 'inc.func-common.includes.php';
require_once 'inc.login.php';
//require_once 'inc.defines.php';
require_once 'inc.cls-updateRecord.php';
require_once 'inc.func-checkTeamRosterLock.php';
require_once 'inc.func-hash-password.php';


$tpl->assign('title', $pageTitle);

// Populate $qfAction for use in QuickForm class constructor (action URL).
$qfAction = $_SERVER['REQUEST_URI'];


/*
if (strpos($_SERVER['REQUEST_URI'], '?') === FALSE) {
    $logoutURL = $_SERVER['REQUEST_URI'] . '?logout';
} else {
    // URI has query string already
    $logoutURL = $_SERVER['REQUEST_URI'] . '&amp;logout';
}
*/
$logoutPos = strripos($_SERVER['REQUEST_URI'], '/');
$logoutStr = substr($_SERVER['REQUEST_URI'], 0, $logoutPos+1);
$logoutURL = $logoutStr . '?logout';
$tpl->assign('logout_URL', $logoutURL);


// if lgname (league ID key) is set, find the LID from there.
function defineLGname($lid=0)
{
    global $db, $tpl;

    if (isset($_GET['lgname'])) {
        $lgName = $_GET['lgname'];
        unset($_GET['lgname']);
    }

    if (!defined('LID') && checkNumber($lid) && $lid != 0) {
        define('LID', $lid);
    }

    if (defined('LID') && !isset($lgName)) {
        $sql = 'SELECT lgname, UNIX_TIMESTAMP(ADDDATE(last_rule_update_gmt, 1)) AS unix_last_rule_update, map_pack_download_url, config_pack_download_url FROM leagues WHERE lid = ? LIMIT 1';
        $leagueRow =& $db->getRow($sql, array(LID));
        $lgName = $leagueRow['lgname'];
        define('LEAGUE_RULE_LAST_UPDATE', $leagueRow['unix_last_rule_update']);
        $mapPackUrl = $leagueRow['map_pack_download_url'];
        $configPackUrl = $leagueRow['config_pack_download_url'];
    }
    elseif (isset($lgName) && !empty($lgName)) {
        $sql = 'SELECT lid, UNIX_TIMESTAMP(last_rule_update_gmt) AS unix_last_rule_update FROM leagues WHERE lgname = ? LIMIT 1';
        $leagueRow =& $db->getRow($sql, array($lgName));
        $lid = $leagueRow['lid'];
        define('LEAGUE_RULE_LAST_UPDATE', $leagueRow['unix_last_rule_update']);
        $mapPackUrl = $leagueRow['map_pack_download_url'];
        $configPackUrl = $leagueRow['config_pack_download_url'];
    }

    if (!empty($lid) && !defined('LID')) {
        define('LID', $lid);
    }
    if (!empty($lgName)) { $tpl->assign('lgname', '/'.$lgName); }
    if (!empty($mapPackUrl)) { $tpl->assign('map_pack_url', $mapPackUrl); }
    if (!empty($configPackUrl)) { $tpl->assign('config_pack_url', $configPackUrl); }
    
}
defineLGname();


function displayTemplate($template, $cacheID=NULL, $ttl=0, $standings=FALSE)
{
    if (defined('TEMPLATE_DISPLAYED')) { die('ERROR: The system has encountered a fatal exception.'); }
    define('TEMPLATE_DISPLAYED', TRUE);

    global $tpl, $db;

    $pageTitle = $tpl->get_template_vars('title');
    if (!empty($pageTitle)) $tpl->assign('title', $pageTitle);

    if (defined('LID') && (LID != 0)) {
        $lid = LID;
    }
    elseif (isset($_GET['tid'])) {
        $tid = $_GET['tid'];
        $sql = 'SELECT lid FROM teams WHERE tid = ? LIMIT 1';
        $lid =& $db->getOne($sql, array($tid));
    }

    defineLGname($lid);
    if (is_null($ttl)) $ttl = 0;

    if (!$lid) $lid = 0;
    loadTimeZone($lid);

    if ($lid) {

$sql = <<<SQL
SELECT season_title, UNIX_TIMESTAMP(last_rule_update_gmt) AS unix_last_rule_update_gmt, show_rules
FROM leagues AS l
LEFT JOIN seasons AS s ON ( l.lid = s.lid AND s.active = 1 )
WHERE l.lid = ?
SQL;

//        $sql = 'SELECT season_title, UNIX_TIMESTAMP(last_rule_update_gmt) AS unix_last_rule_update_gmt, show_rules FROM leagues LEFT JOIN seasons USING (lid) WHERE lid = ? AND (seasons.active = 1 OR seasons.active IS NULL) LIMIT 1';
        $leagueInfo =& $db->getRow($sql, array($lid));
        $unix_last_rule_update_gmt = $leagueInfo['unix_last_rule_update_gmt'];
        $tpl->assign('active_season_title', $leagueInfo['activeSeasonTitle']);

	if ($leagueInfo['show_rules'] == 1) {
            $tpl->assign('show_rules', TRUE);

            if (!empty($_SESSION['uid'])) {
                if (!isset($_SESSION['userViewRules'][$lid])) {
                    $sql = 'SELECT UNIX_TIMESTAMP(last_view_gmt) AS unix_last_view_gmt FROM rules_user_views WHERE lid = ? AND uid = ? LIMIT 1';
                    $lastViewResult =& $db->getOne($sql, array($lid, UID));
                    if (empty($lastViewResult)) $_SESSION['userViewRules'][$lid] = 0;
                    else $_SESSION['userViewRules'][$lid] = $lastViewResult;
                }
                if (!empty($leagueInfo['unix_last_rule_update_gmt']) 
                    && (
                        empty($_SESSION['userViewRules'][$lid]) || $unix_last_rule_update_gmt >= $_SESSION['userViewRules'][$lid]
                       )
                   ) {
                    $tpl->assign('new_rules', TRUE);
                } else {
                    $tpl->assign('new_rules', FALSE);
                }

	}

            $sql = 'SELECT tid, owner_uid, teams.name AS team_name, teams.inactive AS team_inactive, permission_reschedule, permission_report, captain_uid FROM teams INNER JOIN rosters USING (tid) INNER JOIN organizations USING (orgid) WHERE rosters.uid = ? AND teams.lid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" AND teams.deleted = 0 LIMIT 1';
            $permissionData =& $db->getRow($sql, array(UID, $lid));

            if ($permissionData['tid']) {
                $tpl->assign('mp_team_roster_lock_status', checkTeamRosterLock($permissionData['tid']));
                if ($permissionData['captain_uid'] === UID) $tpl->assign('mp_captain', TRUE);
                if ($permissionData['owner_uid'] === UID) $tpl->assign('mp_owner', TRUE);
                $tpl->assign('mp_data', $permissionData);
                
                //$tpl->clear_cache('team.mini-panel.tpl');
                $team_mini_panel = $tpl->fetch('team.mini-panel.tpl');
                $tpl->assign('team_mini_panel', $team_mini_panel);
            }
        }
    }

    //$tpl->cache = 0;
    $tpl->cache_lifetime = 0;
    if (!empty($_SESSION['uid'])) {
        //$tpl->clear_cache('control.panel.tpl');
        $login_cp = $tpl->fetch('control.panel.tpl');
    }
    else {
        //$tpl->clear_cache('login.panel.tpl');
        $login_cp = $tpl->fetch('login.panel.tpl');
    }
    $tpl->assign('login_cp', $login_cp);

    //$tpl->caching = 2;
    $tpl->cache_lifetime = $ttl;
    $mainContent = $tpl->fetch($template . '.tpl', $cacheID);
    $tpl->cache_lifetime = 0;

    if (!$tpl->is_cached('inc.league.selector.tpl')) {
        require_once 'inc.league.selector.php';
        $leaguesList = getLeaguesList();
        $tpl->assign('leagues_list', $leaguesList);
    }
    $tpl->assign('LEAGUE_SELECTOR_LID', $lid);
    $leagueSelector = $tpl->fetch('inc.league.selector.tpl', $lid);
    $tpl->assign('league_selector', $leagueSelector);

    if ($lid) {
        include_once 'inc.func-leagueadmins.php';
        $tpl->assign('league_head_admins', getLeagueHeadAdmins($lid));
        $tpl->assign('league_admins', getLeagueAdmins($lid));
        $tpl->assign('league_admins_panel', $tpl->fetch('inc.league_admins-panel.tpl'));
    }

    if ($standings) {
        $tpl->assign('display_standings', TRUE);
        if (!$tpl->is_cached('inc.standings.tpl', $lid)) {
            include_once 'inc.func-standings.php';
            $standingsData = getStandings($lid);
            $tpl->assign('standings_league_title', $standingsData['league_title']);
            $tpl->assign('standings_groups', $standingsData['groups']);
            $tpl->assign('standings_divisions', $standingsData['divisions']);
            $tpl->assign('standings_conferences', $standingsData['conferences']);
            $tpl->assign('standings_teams', $standingsData['teams']);
        }
        $tpl->cache_lifetime = 60;
        $standings = $tpl->fetch('inc.standings.tpl', $lid);
        $tpl->cache_lifetime = 0;
        $tpl->append('external_css', 'inc.standings');
        $tpl->assign('standings', $standings);
    }

    //$tpl->caching = 0;
    $tpl->cache_lifetime = 0;

    $tpl->assign('main_content', $mainContent);

    $tpl->display('main.tpl');

//$time_end = microtime(true);
//$time = $time_end - $GLOBALS['time_start'];
//echo "Did nothing in $time seconds\n";
//echo '<!-- ' . microtime(TRUE)-$GLOBALS['time_start'] . ' -->';
}
