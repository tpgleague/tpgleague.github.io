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

// ST4NK! Ticket Beta v0.1 - admin.ticket_take.php

session_start();

// REQUIRE VALID USER FOR PAGE ACCESS

require "./includes/authentication.inc.php";
sessionAuthenticate();

// REQUIRE DB ACCESS TO THIS PAGE

require "./config.php";
require "./includes/db.inc.php";	

// OPEN THE DATABASE CONNECTION

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);

if (!($connection = @ mysql_connect($hostName, $username, $password)))
	die("Could not connect to database");

// DETERMINE IF USER HAS RIGHTS TO ACCESS PAGE

if (isset($_SESSION["loginUsername"])) {
$admin_handle = $_SESSION["loginUsername"];
$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_handle = '$admin_handle'");
$row = @mysql_fetch_array($query);
$admin_access = $row["helper_admin_level"];
	
if ($admin_access > 0) {

$ticket_id = mysqlclean($_POST, "ticket_id", 7, $connection);
$helper_id = mysqlclean($_POST, "helper_id", 5, $connection);
		
if (!mysql_select_db($databaseName, $connection))
	showerror();

// TIMESTAMP WHEN THE TICKET IS TAKEN

$taken_date = date("U");

// UPDATE THE DATABASE

$query = "UPDATE st4nkticket_tickets	SET
									ticket_last_update = '{$taken_date}',
									ticket_helper = '{$helper_id}',
									ticket_status = 'active'
								WHERE
									ticket_id = '{$ticket_id}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// WE'RE DONE, NOW GO WERK ON THE TICKET

header ("Location: admin.ticket_summary.php?ticket_id=$ticket_id");
//header("Location: admin.php");

} else {

header("Location: error_logout.php");

exit;

}

}

?>
