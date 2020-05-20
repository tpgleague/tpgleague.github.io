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

// ST4NK! Ticket Beta v0.1 - admin.ticket_delete.php

session_start();

// REQUIRE VALID USER FOR PAGE ACCESS

require "./includes/authentication.inc.php";
sessionAuthenticate();

// REQUIRE DB ACCESS FOR THIS PAGE

require './config.php';
require './includes/db.inc.php';

// OPEN THE CONNECTION TO THE DATABASE

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

// GET TICKET INFO

$ticket_id = mysqlclean($_GET, "ticket_id", 7, $connection);
$ticket_key = mysqlclean($_GET, "ticket_key", 32, $connection);

// IF WE MADE IT HERE, THEN THE DATA IS VALID

if (!mysql_select_db($databaseName, $connection))
	showerror();

// DELETE THE TICKET FROM THE TICKET DATABASE

$query = "DELETE FROM st4nkticket_tickets WHERE ticket_id = '{$ticket_id}' AND ticket_key = '{$ticket_key}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();
	
// DELETE ANY SUPPORTING NOTES FROM THE DATABASE

$query = "DELETE FROM st4nkticket_notes WHERE ticket_id = '{$ticket_id}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// WE'RE DONE, RETURN TO THE ADMIN PAGE

header("Location: admin.php");

} else {

header("Location: error_logout.php");

exit;

}

}

?>
