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

// ST4NK! Ticket Beta v0.1 - admin.ticket_view.php

session_start();

// REQUIRE VALID USER FOR PAGE ACCESS

require "./includes/authentication.inc.php";
sessionAuthenticate();

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";	

// OPEN THE CONNECTION TO THE DATABASE

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);

// DETERMINE IF USER HAS RIGHTS TO ACCESS PAGE

if (isset($_SESSION["loginUsername"])) {
$admin_handle = $_SESSION["loginUsername"];
$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_handle = '$admin_handle'");
$row = @mysql_fetch_array($query);
$admin_access = $row["helper_admin_level"];
	
if ($admin_access > 0) {

// GET INFO FOR HELPER AND ASSIGN VARIABLES

$helper = $_SESSION["loginUsername"];
$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_handle = '$helper'");
$row = @mysql_fetch_array($query);
$helper_id  = $row["helper_id"];

// GET TICKET ID

$ticket_id = mysqlclean($_GET, "ticket_id", 7, $connection);

// RETRIEVE TICKET DETAILS FROM THE DATABASE

if (!mysql_select_db($databaseName, $connection))
	showerror();

$query = "SELECT * FROM st4nkticket_tickets WHERE ticket_id = '$ticket_id'";

if (!($result = @ mysql_query($query, $connection)))
	showerror();

// PUT SELECTED TICKET INFORMATION INTO AN ARRAY

$row = mysql_fetch_array($result);

// FORMAT DATE THE TICKET WAS SUBMITTED

$timeoffset = -5;
$timestamp = $row['ticket_date'];
$ticket_time = gmdate("D M d y g:i a", $timestamp + 3600 * $timeoffset);

// ESTABLISH VARIABLES FOR REMAINING ARRAY ELEMENTS

$ticket_key = $row['ticket_key'];
$ticket_email = $row['ticket_email'];
$ticket_catagory = $row['ticket_catagory'];
$ticket_subject = $row['ticket_subject'];
$ticket_desc = $row['ticket_desc'];
$ticket_helper = $row['ticket_helper'];
$ticket_status = $row['ticket_status'];

$ticket_subject = stripslashes($ticket_subject);
$ticket_desc = stripslashes($ticket_desc);
		
// HEADER

require "./templates/admin.header.tpl";

// BODY

// TICKET SUMMARY

$ticket_desc = para($ticket_desc);

print "
		<table align=center border=0 cellpadding=1 cellspacing=0 width=800>\n
		<tr><td colspan=4><p><i>You are logged in as {$_SESSION["loginUsername"]} </i></p></td></tr>\n
		<tr><td colspan=4><p class=header>Summary for Ticket # $ticket_id</p></td></tr>\n
		<form method=post action=admin.ticket_take.php>
		<tr>
			<td><p><b>Ticket #</b></p></td>
			<td><p>$ticket_id</p></td>
		</tr>\n
		<tr>
			<td><p><b>Ticket Key</b></p></td>
			<td><p>$ticket_key</p></td>
		</tr>\n
		<tr>
			<td><p><b>Date Submitted</b></p></td>
			<td><p>$ticket_time</p></td>
		</tr>\n
		<tr>
			<td><p><b>Customer</b></p></td>
			<td><p>$ticket_email</p></td>
		<tr>
			<td><p><b>Catagory</b></p></td>
			<td><p>$ticket_catagory</p></td>
		</tr>\n
		<tr>
			<td><p><b>Subject</b></p></td>
			<td><p>$ticket_subject</p></td>
		</tr>\n
		<tr>
			<td valign=top><p><b>Description</b></p></td>
			<td><p>$ticket_desc</p></td>
		</tr>\n
		<tr>
			<td colspan=2>
				<p><input type=hidden name=ticket_id value=$ticket_id </p>
				<p><input type=hidden name=helper_id value=$helper_id </p>
			</td>
		</tr>\n
		<tr>
			<td colspan=2><p><input type=submit value=\"Take this ticket\">
			 or click your browser's back button to go back to the main admin page</p></td>
		</tr>\n
		</table>\n
	";

// FOOTER

require "./templates/front.footer.tpl";

} else {

header("Location: error_logout.php");

exit;

}

}

?>
