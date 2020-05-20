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
 *--   logout()                  Clears all session data and any cookies when a user logs out.  ==
 *--   cookie settings           Change expire time and domain they are set for.                == 
 *--   prevent session hijacking Bind sessions to the IP address they were created in?          == 
 *--                                                                                            == 
 *--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==
*/


function login($username, $password, $remember=FALSE, $url=NULL)
{
    global $db, $tpl;
    require_once 'inc.func-hash-password.php';
    $pwHash = hashPassword($password);
    $valuesArray = array(
                         $username,
                         $pwHash
                        );
    $sql = 'SELECT uid, admins.inactive, aid FROM admins INNER JOIN users USING (uid) WHERE username = ? AND password = ? AND users.deleted = 0';
    $row =& $db->getRow($sql, $valuesArray);
    if ($row['inactive']) exit('Your TPG administrator account has been deactivated.');
    define('AID', $row['aid']);
    define('UID', $row['uid']);

    if (checkNumber(AID) && checkNumber(UID)) {
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
        redirect($url);
    } else {
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
    $sql = 'SELECT firstname, username, handle, tz_name FROM users LEFT JOIN time_zones USING (tzid) WHERE uid = ' . $db->quoteSmart($uid);
    $userinfo =& $db->getRow($sql);

    // At this point, the user is about to be officially logged in. Recording of his personal information MUST continue:
    ignore_user_abort(TRUE);

    $_SESSION['aid'] = AID; // <-- officially logged in
    $_SESSION['uid'] = UID;
    $_SESSION['firstname'] = $userinfo['firstname'];
    $_SESSION['handle'] = $userinfo['handle'];
    $_SESSION['username'] = $userinfo['username'];
    $_SESSION['timezone'] = $userinfo['tz_name'];
    $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
}

function logout()
{
    setcookie('username', FALSE, time()-259200, '/', COOKIE_DOMAIN_NAME);
    setcookie('loginkey', FALSE, time()-259200, '/', COOKIE_DOMAIN_NAME);
    session_unset();
    session_destroy();
    session_write_close();

    exit('You have been logged out.<br /><a href="http://www.tpgleague.org/">www.tpgleague.org</a>');
}
if (isset($_GET['logout'])) logout();
/***************************  End login functions  ****************************/

/***** Script logic to determine if user is logged in or trying to log in ****/
if (isset($_POST['login_username'])) {  // check if user trying to log in via login form

    login($_POST['login_username'], $_POST['login_password'], $_POST['login_remember']);

} elseif (!empty($_SESSION['aid'])) {  // check session for active login

    if ($_SESSION['REMOTE_ADDR'] == $_SERVER['REMOTE_ADDR']) {
        define('AID', $_SESSION['aid']);
        define('UID', $_SESSION['uid']);
    } else {
        session_unset();
        session_destroy();
        session_write_close();
    }

} elseif (isset($_COOKIE['loginkey'])) {  // check any submitted cookies for login info

    $valuesArray = array($_COOKIE['username'], $_COOKIE['loginkey']);
    $sql = 'SELECT uid, admins.inactive, aid FROM users_cookies INNER JOIN users USING (uid) INNER JOIN admins USING (uid) WHERE username = ? AND loginkey = ? AND users.deleted = 0';
    $row =& $db->getRow($sql, $valuesArray);
    if ($row['inactive']) exit('Your TPG administrator account has been inactivated.');
    if (checkNumber($row['aid']) && checkNumber($row['uid'])) {
        define('AID', $row['aid']);
        define('UID', $row['uid']);
        getUserSessInfo();
        logUserInfo();
    }

}


if (!checkNumber(AID)) {
    if (!empty($_POST['login_username']) || !empty($_POST['login_password'])) $tpl->assign('invalid_login', TRUE);
    $tpl->display('login.panel.tpl');
    exit();
}
