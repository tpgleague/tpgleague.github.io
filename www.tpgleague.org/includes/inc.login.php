<?php



/*
 *--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==
 *-- @Purpose:        Contains the logic and functions necessary to log user into the website.  ==
 *-- @Interval:       Runs for EVERY page call.                                                 ==
 *-- @Author:         S. Nagheenanajar                                                          ==
 *-- @Author:         M. Bolton                                                                 ==
 *-- @Create Date:    2006-07-10                                                                ==
 *--                                                                                            ==
 *-- To-Do:                                                                                     ==
 *--   Function                  Description                                                    ==
 *--   ------------------------- -------------------------------------------------------------- ==
 *--   logInfo()                 Logs IP address, hostname, geographic lat/long and user-agent. ==
 *--   cookie settings           Change expire time and domain they are set for.                == 
 *--                                                                                            == 
 *--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==
*/



function login($username, $password, $remember=TRUE, $url=NULL)
{
    global $db, $tpl;
    require_once 'inc.func-hash-password.php';
    $pwHash = hashPassword($password);
    $valuesArray = array(
                         $username,
                         $pwHash
                        );
    $sql = 'SELECT uid FROM users WHERE username = ? AND password = ? AND users.deleted = 0';
    $uid =& $db->getOne($sql, $valuesArray);
    define('UID', $uid);

    if (checkNumber(UID)) {
        if ($remember) {
            // change when site live. lifetime cookie: 2147483647; domain obviously: '.tpgleague.org'
            require_once "Text/Password.php";
            $loginKey =& Text_Password::create(32, 'unpronounceable');
            setcookie('username', $username, 2147483647, '/', COOKIE_DOMAIN_NAME);
            setcookie('loginkey', $loginKey, 2147483647, '/', COOKIE_DOMAIN_NAME);
            $sql = 'INSERT INTO users_cookies (uid, loginkey) VALUES (?, ?)';
            $valuesArray = array(UID, $loginKey);
            $res =& $db->query($sql, $valuesArray);
        }

        getUserSessInfo();
        logUserInfo();
        if (empty($url)) $url = $_SERVER['REQUEST_URI'];
        redirect($url);
    } else {
        $tpl->assign('invalid_login', TRUE);
        session_unset();
        return FALSE;
    }
}

function logUserInfo()
{
        global $db;
        $sql = 'SELECT brwsid FROM ip_browsers WHERE browser = ? LIMIT 1';
        $brwsid =& $db->getOne($sql, array(trim($_SERVER['HTTP_USER_AGENT'])));
        if (!$brwsid) {
            $sql = 'INSERT INTO ip_browsers (browser) VALUES (?)';
            $res =& $db->query($sql, array(trim($_SERVER['HTTP_USER_AGENT'])));
            $brwsid =& $db->getOne('SELECT LAST_INSERT_ID()');
        }
        $sql = 'INSERT INTO ip (uid, address, brwsid, timestamp_gmt) '
             . 'VALUES (?, INET_ATON(?), ?, NOW())';
        $valuesArray = array(UID,
                             $_SERVER['REMOTE_ADDR'],
                             $brwsid
                            );
        $res =& $db->query($sql,$valuesArray);
}

function getUserSessInfo()
{
    global $db;
    $sql = 'SELECT firstname, username, handle, abuse_lock, tz_name, email FROM users LEFT JOIN time_zones USING (tzid) WHERE uid = ' . $db->quoteSmart(UID);
    $userinfo =& $db->getRow($sql);

    // At this point, the user is about to be officially logged in. Recording of his personal information MUST continue:
    ignore_user_abort(TRUE);

    $_SESSION['uid'] = UID; // <-- officially logged in
    $_SESSION['firstname'] = $userinfo['firstname'];
    $_SESSION['handle'] = $userinfo['handle'];
    $_SESSION['username'] = $userinfo['username'];
    $_SESSION['abuse_lock'] = $userinfo['abuse_lock'];
    $_SESSION['timezone'] = $userinfo['tz_name'];
    $_SESSION['email'] = $userinfo['email'];
    $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];

    assigntpl();
}

function assigntpl()
{
    global $tpl;
    global $db;

    $tpl->assign('logged_in', TRUE);
    $tpl->assign('cp_firstname', $_SESSION['firstname']);
    $tpl->assign('username', $_SESSION['username']);
    $tpl->assign('handle', $_SESSION['handle']);

    cpTeams();
}

function cpTeams()
{
    global $tpl;
    global $db;

    $sql = 'SELECT TRUE FROM organizations WHERE owner_uid = ? AND inactive = 0 LIMIT 1';
    $playerManagesOrgs =& $db->getOne($sql, array(UID));

    $sql = 'SELECT TRUE FROM rosters WHERE uid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $playerOnTeams =& $db->getOne($sql, UID);

    $tpl->assign('player_manages_orgs', $playerManagesOrgs);
    $tpl->assign('player_on_teams', $playerOnTeams);
}

function logout()
{
    setcookie('username', FALSE, time()-259200, '/', COOKIE_DOMAIN_NAME);
    setcookie('loginkey', FALSE, time()-259200, '/', COOKIE_DOMAIN_NAME);
    session_unset();
    session_destroy();
    session_write_close();

    $uri = (substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI'])-7));
    $endofuri = substr($uri, -1, 1);
    if ($endofuri != '/') {
        $QueryStr = $_SERVER['QUERY_STRING'];
        parse_str($QueryStr, $QueryArray);
        unset($QueryArray['logout']);
        $Query = http_build_query($QueryArray);
    }

    $request = $_SERVER['REQUEST_URI'];
    $urlArray = explode('?', $request);
    $url = $urlArray[0];

    if (!empty($Query) || (strpos($url, '.') !== FALSE)) $url = '/';
    redirect($url);
}
/***************************  End login functions  ****************************/



/******** Logic to determine if user is logged in or trying to log in *********/
if (isset($_GET['logout'])) { 
    logout();
} else {
    if (isset($_POST['login_username'])) {  // check if user trying to log in via login form

        login($_POST['login_username'], $_POST['login_password'], TRUE);

    } elseif (isset($_SESSION['uid'])) {  // check session for active login

        $sql = 'SELECT deleted FROM users WHERE uid = ? LIMIT 1';
        $userIsDeleted =& $db->getOne($sql, array($_SESSION['uid']));
        if ($userIsDeleted) {
            session_unset();
            session_destroy();
        } else {
            if ($_SESSION['REMOTE_ADDR'] == $_SERVER['REMOTE_ADDR']) {
                define('UID', $_SESSION['uid']);
                loadTimeZone();
                assigntpl();
            } else {
                session_unset();
                session_destroy();
            }
        }

    } elseif (isset($_COOKIE['loginkey'])) {  // check any submitted cookies for login info

        $valuesArray = array($_COOKIE['username'], $_COOKIE['loginkey']);
        $sql = 'SELECT uid FROM users_cookies INNER JOIN users USING (uid) WHERE username = ? AND loginkey = ? AND users.deleted = 0';
        $uid =& $db->getOne($sql, $valuesArray);
        if (checkNumber($uid)) {
            define('UID', $uid);
            getUserSessInfo();
            logUserInfo();
        }

    }
}
if (!checkNumber(UID)) {
    $tpl->assign('logged_in', FALSE);
}
