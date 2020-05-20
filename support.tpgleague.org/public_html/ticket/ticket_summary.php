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

// ST4NK! Ticket Beta v0.1 - ticket_summary.php

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";

// OPEN THE CONNECTION TO THE DATABASE

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);

// GET THE TICKET INFO

$ticket_email = mysqlclean($_GET, "ticket_email", 50, $connection);
$ticket_key = mysqlclean($_GET, "ticket_key", 32, $connection);

// RETRIEVE TICKET DETAILS FROM THE DATABASE

if (!mysql_select_db($databaseName, $connection))
	showerror();

$query = "SELECT * FROM st4nkticket_tickets WHERE ticket_email = '$ticket_email' AND ticket_key= '$ticket_key'";

if (!($result = @ mysql_query($query, $connection)))
	showerror();

// PUT SELECTED TICKET INFORMATION INTO AN ARRAY

$row = mysql_fetch_array($result);

// FORMAT DATE THE TICKET WAS SUBMITTED

$timeoffset = -5;
$timestamp = $row['ticket_date'];
$ticket_time = gmdate("D M d y g:i a", $timestamp + 3600 * $timeoffset);

// GET REST OF TICKET INFO FROM ARRAY

$ticket_id = $row['ticket_id'];
$ticket_email = $row['ticket_email'];
$ticket_catagory = $row['ticket_catagory'];
$ticket_subject = $row['ticket_subject'];
$ticket_desc = $row['ticket_desc'];
$ticket_status = $row['ticket_status'];

$ticket_subject = stripslashes($ticket_subject);
$ticket_desc = stripslashes($ticket_desc);

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

// RETURN WEBPAGE TO CUSTOMER

// HEADER

require "./templates/front.header.tpl";

// BODY

// TICKET SUMMARY

print "
	<table align=center bgcolor=#FFFFFF border=0 cellpadding=2 cellspacing=0 width=800>\n

	<tr><td colspan=4><p class=header>Summary for Ticket # $ticket_id </p></td></tr>\n 

	<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Ticket #</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_id</p></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Subject</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_subject</p></td>
	</tr>\n 

	<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Ticket Key</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_key</p></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Status</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_status</p></td>
	</tr>\n 

	<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Created</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_time</p></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Catagory</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$ticket_catagory</p></td>
	</tr>\n 

	<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Customer</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>
			<a class=mailto href=mailto:$ticket_email>$ticket_email</a></p>
		</td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary><b>Helper</b></p></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary>$helper_handle</p></td>
	</tr>\n 

	<tr>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary></td>
		<td bgcolor=#B2B2B2 width=100><p class=ticketsummary></td>
		<td bgcolor=#B2B2B2 width=300><p class=ticketsummary></td>
	</tr>\n 

	</table>\n
	";

// TICKET NOTES - ORIGINAL TICKET SUMMARY

$ticket_desc = para($ticket_desc);

print "
	<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=800>\n 

	<tr><td colspan=2><p class=header>Original Ticket</p></td></tr>\n

	<tr>
		<td valign=top width=200>
		<p>$ticket_email
		<br>$ticket_time</p>
		</td>
		<td valign=top width=600>
		<p>$ticket_desc</p>
		</td>
	</tr>\n 

	</table>\n
	";

// TICKET NOTES - ADDED NOTES

$result = mysql_query ("SELECT * FROM st4nkticket_notes WHERE ticket_id = '$ticket_id' ORDER BY note_date");

print "
	<table align=center bgcolor=#CCCCCC border=0 cellpadding=5 cellspacing=1 width=800>\n  
	<tr><td colspan=2><p class=header>Notes</p></td></tr>\n
	";

while ($row = mysql_fetch_array($result))

	{
		$row["note_note"] = stripslashes($row["note_note"]);
		$note_note = $row["note_note"];
		$note_note = para($note_note);
		$ticket_email = $row["ticket_email"];
		$note_timestamp = $row["note_date"];
		$note_time = gmdate("D M d y g:i a", $note_timestamp + 3600*$timeoffset);
		$ticket_submitter = $row["ticket_submitter"];
		$ticket_helper = $row["ticket_helper"];
		$note_private = $row["note_private"];

	if ($note_private == 'p') {

	// skip row

	} else {

		if ($ticket_submitter == 'h') {

			print "<tr><td bgcolor=#FFFF66 valign=top width=200>";

			$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_id = '$ticket_helper'");
			$row2 = @mysql_fetch_array($query);
			$helper_handle = $row2["helper_handle"];

			print "<p>$helper_handle<br>$note_time</p></td><td bgcolor=#FFFF66 valign=top width=600><p>$note_note</p></td></tr>\n";
		
		} else { 

			print "<tr>
				<td bgcolor=#9999CC valign=top width=200>
				<p>$ticket_email
				<br>$note_time</p>
				</td>
				<td bgcolor=#9999CC valign=top width=600>
				<p>$note_note</p>
				</td>
			</tr>\n";

	}

	}

	}

print "</table>\n";

// NOTE INPUT BOX

if (!mysql_select_db($databaseName, $connection))
	showerror();

$query = "SELECT * FROM st4nkticket_tickets WHERE ticket_id = '$ticket_id'";

if (!($result = @ mysql_query($query, $connection)))
	showerror();

$row = mysql_fetch_array($result);

$ticket_email = $row["ticket_email"];

print "
	<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=800>\n 
	<tr><td colspan=2><p class=header>Add Note</p></td></tr>\n 
	<tr>
	<form action=ticket_add_note.php method=post name=form>
			
		<td rowspan=2 width=500>
		<p><textarea name=note_note rows=8 cols=50></textarea></p>
		<p><input type=hidden name=ticket_id value=$ticket_id></p>
		<p><input type=hidden name=ticket_helper value=$ticket_helper></p>
		<p><input type=hidden name=ticket_email value=$ticket_email></p>
		<p><input type=hidden name=ticket_key value=$ticket_key></p>
		<p><input type=submit value=\"Update ticket\"></p>
		</td>
		<td valign=top width=300>
	";

print "";
	
		if ($ticket_status == 'closed')

			print "<p><a href=ticket_open.php?ticket_id=$ticket_id&ticket_key=$ticket_key>Open ticket</a></p>";

		else
				
			print "<p><a href=ticket_close.php?ticket_id=$ticket_id&ticket_key=$ticket_key>Close ticket</a></p>";

print "
	</td></form></tr>\n
	</table>\n 
	";

// END BODY

// FOOTER

require "./templates/front.footer.tpl";

?>
