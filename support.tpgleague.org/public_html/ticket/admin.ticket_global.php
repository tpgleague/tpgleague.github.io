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

// ST4NK! Ticket Beta v0.1 - admin.ticket_global.php

session_start();

// REQUIRE AUTHENTICATION AND ONLY LET AUTH'D PEOPLE HAVE ACCESS

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
	
if ($admin_access > 0) {

// IF AVAILABLE, GET PAGE OFFSET FROM PREVIOUS REQUEST

if (!isset($_GET['offset']))
	$offset = 0;
else
	$offset = $_GET['offset'];

// DETERMINE SORT ORDER FOR ROWS

$default_sort_column = "ticket_id";
$default_sort_order = "Desc";
$sort_column = isset($_SESSION["sort_column"]) ? $_SESSION["sort_column"] : $default_sort_column;
$sort_order = isset($_SESSION["sort_order"]) ? $_SESSION["sort_order"] : $default_sort_order;

// DETERMINE THE NUMBER OF ROWS TO RETURN ON EACH PAGE

$default_rows_page = 10;
$rows_page = isset($_SESSION["rows_page"]) ? $_SESSION["rows_page"] : $default_rows_page;

// HEADER

require "./templates/admin.header.tpl";

// BODY

print "<table align=center bgcolor=#FFFFFF border=0 width=1000>\n";

print "<tr><td colspan=9><p><i>You are logged in as {$_SESSION["loginUsername"]} </i></p></td></tr>\n";

// CREATE TICKET TABLE

print "<tr><td colspan=8>
			<form action=admin.ticket_global_sort.php method=post name=form>
			<input type=hidden name=sort_table value=global>
			<p>Sort by 
			<select name=sort_column>
				<option selected>$sort_column
				<option>ticket_catagory
				<option>ticket_date	
				<option>ticket_email
				<option>ticket_helper
				<option>ticket_id
				<option>ticket_last_update
				<option>ticket_status
			</select>
			in
			<select name=sort_order>
				<option selected>$sort_order
				<option>ASC
				<option>Desc
			</select>
			order with
			<select name=rows_page>
				<option selected>$rows_page
				<option>10
				<option>20
				<option>50
			</select>
			tickets per page
			<input type=submit value=Sort></form></p>
		</td>
	</tr>\n";

print "<tr><td colspan=9><form action=admin.ticket_summary.php method=get>
	<p>Or, find a ticket <input type text name=ticket_id size=10> <input type=submit value=Find>
	&nbsp &nbsp Short cut to <a class=two href=admin.php>view my tickets</a></p></form></td></tr>";

// HOW MANY TICKETS ARE IN THE DATAROWS?

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);
$num = mysql_query ("SELECT * FROM st4nkticket_tickets");
$rows = mysql_num_rows($num);

// CALCULATE NUMBER OF PAGES TO DISPLAY

if (($rows % $rows_page) == 0)
	$total_pages = $rows / $rows_page;
else
	$total_pages = $rows / $rows_page + 1; 

// TICKET SUMMARY TABLE

print "<tr><td colspan=9><p><b>Gobal Ticket View</b></p></td></tr>\n";

print "<tr>
		<td bgcolor=#999999 width=50><p class=ticketheader><b>ID</b></td>
		<td bgcolor=#999999 width=150><p class=ticketheader><b>Email</b></td>
		<td bgcolor=#999999 width=175><p class=ticketheader><b>Catagory</b></td>
		<td bgcolor=#999999 width=250><p class=ticketheader><b>Subject</b></td>
		<td bgcolor=#999999 width=75><p class=ticketheader><b></b></td>
		<td bgcolor=#999999 width=50><p class=ticketheader><b>Status</b></td>
		<td bgcolor=#999999 width=50><p class=ticketheader><b>Created</b></td>
		<td bgcolor=#999999 width=150><p class=ticketheader><b>Updated</b></td>
		<td bgcolor=#999999 width=50><p class=ticketheader><b>Helper</b></td>
	</tr>\n";

// PRINT SUMMARY TICKET ROWS

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);
$result = mysql_query ("SELECT * FROM st4nkticket_tickets ORDER BY $sort_column $sort_order LIMIT ".$offset.",".$rows_page."");

	while ($row = mysql_fetch_array($result))

	{
		$id = $row["ticket_id"];
		$status = $row["ticket_status"];
		$customer_email = $row["ticket_email"];
		$catagory = $row["ticket_catagory"];
		$subject = $row["ticket_subject"];	
		$helper_id = $row["ticket_helper"];
		$updated_by = $row["ticket_last_update_by"];
		
		$settings = mysql_query ("SELECT * FROM st4nkticket_settings WHERE settings_id=1");
		$settingsrow = @mysql_fetch_array($settings);
		$timeoffset = $settingsrow["settings_time_offset"];
		
		$timestamp = $row["ticket_date"];
		$date_submitted = gmdate("m/d/y", $timestamp + 3600 * $timeoffset);
		// $date_submitted = gmdate("D M d y g:i a", $timestamp + 3600 * $timeoffset);

		$timestamp_updated = $row["ticket_last_update"];
		$date_updated = gmdate("m/d/y", $timestamp_updated + 3600 * $timeoffset);

		// DETERMINE COLOR OF ROW BASED ON TICKET STATUS

		if ($status == 'active') {

			if ($updated_by == 'customer') {
				$td = "td bgcolor=#FF9999";
 			} elseif ($updated_by == 'Customer') {
				$td = "td bgcolor=#FF9999";
			} else {
				$td = "td bgcolor=#FF6666";
			}

		} elseif ($status == 'pending') {

			$td = "td bgcolor=#FFFF66";

		} else {

			if ($updated_by == 'customer') {
				$td = "td bgcolor=#CCCCFF";
 			} elseif ($updated_by == 'Customer') {
				$td = "td bgcolor=#CCCCFF";
			} else {
				$td = "td bgcolor=#9999CC";
			}
	
		}

		print "<tr>
				<td bgcolor=#999999><p class=ticketheader><a class=one href=admin.ticket_summary.php?ticket_id=$id>$id</a></p></td>
				<$td><p class=ticketheader>$customer_email</p></td>
				<$td><p class=ticketheader>$catagory</p></td>
				<$td><p class=ticketheader>$subject</p></td>
				<$td><p class=ticketheader></p></td>
				<$td><p class=ticketheader>$status</p></td>
				<$td><p class=ticketheader>$date_submitted</p></td>
				<$td><p class=ticketheader>$date_updated by $updated_by</p></td>";

				// GET TICKET HELPER HANDLE FROM HELPERS TABLE

				$result3 = mysql_query("SELECT helper_handle FROM st4nkticket_helpers WHERE helper_id = '$helper_id'");
	
				while ($row3 = mysql_fetch_array($result3))
	
				{
					$helper = $row3['helper_handle'];
					print "<$td><p class=ticketheader>$helper</p></td></tr>\n";
				}
	}

// FORMAT PAGE LINKS

if (($offset == 0) and ($rows <= $rows_page))

{
	print "<tr><td colspan=9><p></p></td></tr>";
	print "<tr><td colspan=9><p>Displaying $rows of $rows tickets</td></tr>";
}

else if (($offset == 0) and ($rows > $rows_page))

{
	$next = $offset + $rows_page;
	$begin = $offset +1;
	$end = floor($rows/$rows_page) * $rows_page;
	print "<tr><td colspan=9><p></p></td></tr>";
	print "<tr><td colspan=9><p> << First | < Prev | <a class=two href=admin.ticket_global.php?offset=".$next."> Next > </a> | <a class=two href=admin.ticket_global.php?offset=".$end."> Last >> </a>";
	print "<tr><td colspan=9><p>Displaying $begin - $next of $rows tickets</td></tr>";
}

else if (($offset > 0) and ($offset >= $rows - $rows_page))

{	
	$prev = $offset - $rows_page;
	$begin = $offset +1;
	print "<tr><td colspan=9><p></p></td></tr>";
	print "<tr><td colspan=9><p><a class=two href=admin.ticket_global.php?offset=0> << First </a> | <a class=two href=admin.ticket_global.php?offset=".$prev."> < Prev </a> | Next > </a> | End >> ";
	print "<tr><td colspan=9><p>Displaying $begin - $rows of $rows tickets</td></tr>";
}

else if (($offset > 0) and ($offset < $rows - $rows_page))

{
	$prev = $offset - $rows_page;
	$next = $offset + $rows_page;
	$begin = $offset +1;
	$end = floor($rows/$rows_page) * $rows_page;
	print "<tr><td colspan=9><p></p></td></tr>";
	print "<tr><td colspan=9><p><a class=two href=admin.ticket_global.php?offset=0> << First </a> | <a class=two href=admin.ticket_global.php?offset=".$prev."> < Prev </a> | <a class=two href=admin.ticket_global.php?offset=".$next."> Next > </a> | <a class=two href=admin.ticket_global.php?offset=".$end."> End >> </a>";
	print "<tr><td colspan=9><p>Displaying $begin - $next of $rows tickets</td></tr>";	
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

