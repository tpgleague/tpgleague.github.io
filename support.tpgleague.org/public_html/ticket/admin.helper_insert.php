<?php

/********************************************************************************************
*                                                                                            
* ST4NK!Ticket content management system, copyright 2005 and beyond by Digital Bluesky, LLC. 
* Released under the BSD License,  see the LICENSE.txt file for more infomation.             
*                                                                                            
* Although greatly modified at this point, the origianl concepts for the basis of this       
* code were taken from "Web Database Applications with PHP and MySQL" published by O'Reilly  
* and Associates and authored by Hugh E. Williams and David Lane.                            
*                                                                                            
*/

// ST4NK! Ticket Beta v0.1 - admin.helper_insert.php

session_start();

// REQUIRE VALID USER FOR PAGE ACCESS

require "./includes/authentication.inc.php";
sessionAuthenticate();

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";

// OPEN THE DATABASE CONNECTION

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);

// DETERMINE IF USER HAS RIGHTS TO ACCESS PAGE

if (isset($_SESSION["loginUsername"])) {
$admin_handle = $_SESSION["loginUsername"];
$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_handle = '$admin_handle'");
$row = @mysql_fetch_array($query);
$admin_access = $row["helper_admin_level"];
	
if ($admin_access > 4) {

if (!($connection = @ mysql_connect($hostName, $username, $password)))
	showerror();

$admin_level = mysqlclean($_POST, "admin_level", 5, $connection);
$active = mysqlclean($_POST, "active", 5, $connection);
$handle = mysqlclean($_POST, "handle", 20, $connection);
$password = mysqlclean($_POST, "password", 10, $connection);
$email = mysqlclean($_POST, "email", 50, $connection);

if (!mysql_select_db($databaseName, $connection))
	showerror();

// ENCRYPT PASSWORD BEFORE WRITING IT TO THE DATABASE

$encryptedpassword = md5(trim($password));

// INSERT THE NEW HELPER ENTRY
  
$query = "INSERT INTO st4nkticket_helpers VALUES
										(NULL,
										'{$admin_level}',
										'{$active}',
										'{$handle}',
										'{$encryptedpassword}',
										'{$email}')";
header("Location: admin.helper.php");

if (!(@mysql_query ($query, $connection)))
	showerror();

} else {

header("Location: error_logout.php");

exit;

}

}

?>
