<?php
// These prevent error messages from being displayed to the user. You should ALWAYS set these when access TPG information
error_reporting(0);
ini_set('display_errors', 0);

// Require to allow you to access the $_SESSION variable
session_start();

// Required to access TPG Database
require_once('../../includes/inc.db.php');

echo  'uid: ' . $_SESSION['uid'] . '<br/>';
echo  'firstname: '.$_SESSION['firstname'].'<br/>';
echo  'handle: '.$_SESSION['handle'].'<br/>';
echo  'username: '.$_SESSION['username'].'<br/>';
echo  'abuse_lock: '.$_SESSION['abuse_lock'].'<br/>';
echo  'timezone: '.$_SESSION['timezone'].'<br/>';
echo  'email: '.$_SESSION['email'].'<br/>';
echo  'REMOTE_ADDR: '.$_SESSION['REMOTE_ADDR'].'<br/>';

// This is the wrong way to do a query.  I guess I copied a bad example, but this will work for now.
global $db;
$sqlwrong = 'SELECT email FROM users WHERE uid = ' . $db->quoteSmart($_SESSION['uid']);
$userinfowrong = $db->getRow($sqlwrong);

// This is the right way to make a query.  Pass in the values as an array of values
$sqlcorrect = 'SELECT email FROM users WHERE uid = ?';
$userinfocorrect = $db->getRow($sqlcorrect, array($_SESSION['uid']));
echo 'Email: ' . $userinfocorrect['email'].'<br/>';




// Example of multiple values
$sql = 'SELECT lastname FROM users WHERE uid = ? AND username = ?';
$results = $db->getRow($sql, array($_SESSION['uid'], $_SESSION['username']));

// $db has several methods for returning what you need.  As you can see above getRow expects a single row to be returned by the SQL statment
// getOne expects a single value to be returned
// getAll expects multiple rows to be returned

// To span multiple lines with large SQL statements (makes them easier to read) use the <<< operator.  The terminating operator (SQL; in this case) must not be indented
// or this operator won't work. This example query returning all of the distinct Game IDs in use by a given user.
global $db2;
$gameIdsSql = <<<SQL
    SELECT gid
FROM rosters 
LEFT JOIN teams USING (tid) 
WHERE rosters.leave_date_gmt = '0000-00-00 00:00:00'
AND teams.lid = ? AND uid = ?
SQL;


echo 'Game ID: ' . $gameIdsSql['gid'];

?>





