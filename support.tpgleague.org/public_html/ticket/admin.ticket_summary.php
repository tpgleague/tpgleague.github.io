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

// ST4NK! Ticket Beta v0.1 - admin.ticket_summary.php

session_start();

// REQUIRE VALID USER FOR PAGE ACCESS

require "./includes/authentication.inc.php";
sessionAuthenticate();

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";

// REQUIRE THE SELECT FUCNTION SCRIPT

require "./includes/select.php";	

// OPEN DATABASE CONNECTION

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);	

// DETERMINE IF USER HAS RIGHTS TO ACCESS PAGE

if (isset($_SESSION["loginUsername"])) {
$admin_handle = $_SESSION["loginUsername"];
$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_handle = '$admin_handle'");
$row = @mysql_fetch_array($query);
$admin_access = $row["helper_admin_level"];
$ticket_helper2 = $row["helper_id"];
	
if ($admin_access > 0) {

// GET TICKET ID

$ticket_id = mysqlclean($_GET, "ticket_id", 7, $connection);

// RETRIEVE TICKET DETAILS FROM THE DATABASE

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);	

if (!mysql_select_db($databaseName, $connection))
	showerror();

$query = "SELECT * FROM st4nkticket_tickets WHERE ticket_id = '$ticket_id'";

if (!($result = @ mysql_query($query, $connection)))
	showerror();

// PUT SELECTED TICKET INFORMATION INTO AN ARRAY AND ASSIGN VARIABLES

$row = mysql_fetch_array($result);

$ticket_key = $row['ticket_key'];
		
// FORMAT DATE THE TICKET WAS SUBMITTED

$timeoffset = -5;
$timestamp = $row['ticket_date'];
$ticket_time = gmdate("D M d y g:i a", $timestamp + 3600 * $timeoffset);

// GET REST OF INFO

$ticket_email = $row['ticket_email'];
$ticket_catagory = $row['ticket_catagory'];
$ticket_subject = $row['ticket_subject'];
$ticket_desc = $row['ticket_desc'];
$ticket_status = $row['ticket_status'];
			
// GET TICKET HELPER HANDLE FROM HELPERS TABLE

$ticket_helper = $row['ticket_helper'];

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);	

if (!mysql_select_db($databaseName, $connection))
	showerror();

$query = "SELECT helper_handle FROM st4nkticket_helpers WHERE helper_id = '$ticket_helper'";
			
if (!($result = @ mysql_query($query, $connection)))
	showerror();
			
$row = mysql_fetch_array($result);

$helper_handle = $row['helper_handle'];
		
// HEADER

require "./templates/admin.header.tpl";

// BODY

// TICKET SUMMARY

print "

	<table align=center border=0 cellpadding=1 cellspacing=0 width=1000>\n";

print "<tr><td bgcolor=#CCCCCC colspan=4><p><i>You are logged in as {$_SESSION["loginUsername"]} </i><p></td></tr>";

print "<tr><td bgcolor=#CCCCCC colspan=4><p class=header>Summary for Ticket # $ticket_id </p></td></tr>\n";

print "<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Ticket #</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_id</p></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Subject</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_subject</p></td>
	</tr>\n";

print "<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Ticket Key</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_key</p></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Status</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_status</p></td>
	</tr>\n";

print "<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Created</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_time</p></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Catagory</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_catagory</p></td>
	</tr>\n";

print "<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Customer</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary><a class=mailto href=mailto:$ticket_email>$ticket_email</a></p></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Helper</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$helper_handle</p></td>
	</tr>\n";

print "<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary></td>
	</tr>\n";

print "</table>\n";

// TICKET NOTES - ORIGINAL TICKET SUMMARY

$ticket_desc = para($ticket_desc);

print "<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=1000>\n";

print "<tr><td colspan=2><p class=header>Original Ticket</p></td></tr>\n";

print "<tr>
		<td valign=top width=200>
			<p>$ticket_email
			<br>$ticket_time</p>
		</td>
		<td width=600>
			<p>$ticket_desc</p>
		</td>
	</tr>\n";

print "</table>\n";

// TICKET NOTES - ADDED NOTES

$result = mysql_query ("SELECT * FROM st4nkticket_notes WHERE ticket_id = '$ticket_id' ORDER BY note_date");

print "<table align=center border=0 cellpadding=5 cellspacing=2 width=1000>\n";

 print "<tr><td bgcolor=#CCCCCC colspan=2><p class=header>Updates</p></td></tr>\n"; 

while ($row = mysql_fetch_array($result))

{

	$row["note_note"] = stripslashes($row["note_note"]);
	$note_note = $row["note_note"];
	$note_note = para($note_note);

	$note_id = $row["note_id"];

	$ticket_email = $row["ticket_email"];

	$note_timestamp = $row["note_date"];
	$note_time = gmdate("D M d y g:i a", $note_timestamp + 3600*$timeoffset);

	$ticket_submitter = $row["ticket_submitter"];
	$ticket_helper = $row["ticket_helper"];
		
	$note_private = $row["note_private"];

	if ($note_private == 'p') {
		$td = "td bgcolor=#9999CC";
		$tag = "<b>(private)</b>";
	} elseif ($ticket_submitter == 'h') {
		$td = "td bgcolor=#FFFF66";
		$tag = "";
	} else {
	$td = "td";
	$tag ="";
	}	

	if ($ticket_submitter == 'h') {

		print "<tr><td bgcolor=#FFFF66 valign=top width=200>";

		$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_id = '$ticket_helper'");
		$row2 = @mysql_fetch_array($query);
		$helper_handle = $row2["helper_handle"];

		print "<p>$helper_handle ";

		if ($note_private == 'p') {

		print "<br>$note_time</p></td><$td width=600><p>$tag $note_note</p></td></tr>\n";

		} else {

		print "<a class=two href=admin.ticket_change_note.php?ticket_id=$ticket_id&note_id=$note_id&note_private=p>[make private]</a>";
		print "<br>$note_time</p></td><$td width=600><p>$tag $note_note</p></td></tr>\n";

		}
		
	} else { 

		print "<tr>
			<td bgcolor=#999999 valign=top width=200>
				<p>$ticket_email
				<br>$note_time</p>
			</td>
			<td bgcolor=#999999 width=600>
				<p>$note_note</p>
			</td>
		</tr>\n";
	}

}

print "</table>\n";

// NOTE INPUT BOX

print "<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=1000>\n";

print "<tr><td colspan=2><p class=header>Add Note</p></td></tr>\n";

print "<tr>
		<form action=admin.ticket_add_note.php method=post name=form>
			
		<td rowspan=2 width=400>
			<p><input type=checkbox name=note_private value=p>Make this note private</p>
			<p><textarea name=note_note rows=8 cols=50></textarea></p>
			<p><input type=hidden name=ticket_id value=$ticket_id></p>
			<p><input type=hidden name=ticket_helper value=$ticket_helper2></p>
			<p><input type=submit value=\"Update Ticket\"></p>
		</td>
			
		<td valign=top width=600>";
				
		if ($ticket_status == 'closed')

		print "<p><a href=admin.ticket_open.php?ticket_id=$ticket_id&ticket_key=$ticket_key>Open ticket</a></p>";

		else
				
		print "<p><a href=admin.ticket_close.php?ticket_id=$ticket_id&ticket_key=$ticket_key>Close ticket</a></p>";

// SHOW DELETE LINK ONLY FOR ADMINS

if ($admin_access > 1) {				

print "<p><a href=admin.ticket_delete.php?ticket_id=$ticket_id&ticket_key=$ticket_key onclick=\"return confirm('Are you sure you want to delete?')\">Delete ticket</a></p>
	</form>";

} else {

print "</form>";

}

if ($admin_access > 4 ) {

print "<form method=POST action=admin.ticket_transfer.php>";
print "<p>Transfer ticket to: ";
selectDistinct($connection, "st4nkticket_helpers", "helper_handle", "helper_handle","All");
print "<input type=hidden name=ticket_id value=$ticket_id>";
print " <input type=submit value=Transfer></form></p>";

print "<form method=POST action=admin.ticket_change.php>";
print "<p>Change catagory: ";
selectDistinct($connection, "st4nkticket_catagories", "catagory_name", "catagory_name","All");
print "<input type=hidden name=ticket_id value=$ticket_id>";
print " <input type=submit value=Change></form></p>";

print "</td></tr>\n";

} else {

print "<form method=POST action=admin.ticket_change.php>";
print "<p>Change catagory to: ";
selectDistinct($connection, "st4nkticket_catagories", "catagory_name", "catagory_name","All");
print "<input type=hidden name=ticket_id value=$ticket_id>";
print " <input type=submit value=Change></form></p>";

print "</td></tr>\n";

}

print "</table>\n";

// FOOTER

require "./templates/admin.footer.tpl";

} else {

header("Location: error_logout.php");

exit;

}

}

?>
