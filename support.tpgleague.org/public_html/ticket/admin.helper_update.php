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

// ST4NK! Ticket Beta v0.1 - admin.helper_update.php

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

$helper_id = mysqlclean($_POST, "helper_id", 5, $connection);
$helper_admin_level = mysqlclean($_POST, "helper_admin_level", 5, $connection);
$helper_active = mysqlclean($_POST, "helper_active", 5, $connection);
$helper_handle = mysqlclean($_POST, "helper_handle", 20, $connection);
$helper_email = mysqlclean($_POST, "helper_email", 50, $connection);

if (!mysql_select_db($databaseName, $connection))
	showerror();

// UPDATE THE HELPER ENTRY

$query = "UPDATE st4nkticket_helpers SET 
								helper_admin_level = '{$helper_admin_level}',
								helper_active = '{$helper_active}',
								helper_handle = '{$helper_handle}',
								helper_email = '{$helper_email}'
							WHERE
								helper_id = '{$helper_id}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// WE'RE DONE, RETURN TO ADMIN MANAGE PAGE

header("Location: admin.manage.php");

} elseif (($admin_access > 0) && ($admin_access < 5)) {

$helper_id = mysqlclean($_POST, "helper_id", 5, $connection);
$helper_email = mysqlclean($_POST, "helper_email", 50, $connection);

if (!mysql_select_db($databaseName, $connection))
	showerror();

// UPDATE THE HELPER ENTRY

$query = "UPDATE st4nkticket_helpers SET 
								helper_email = '{$helper_email}'
							WHERE
								helper_id = '{$helper_id}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// WE'RE DONE, RETURN TO ADMIN MANAGE PAGE

header("Location: admin.manage.php");

} else {

header("Location: error_logout.php");

exit;

}

}

?>
