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

// ST4NK! Ticket Beta v0.1 - admin.settings.php

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
	
if ($admin_access > 4) {

// RETRIEVE SETTINGS DETAILS FOR EDITING

if (!mysql_select_db($databaseName, $connection))
	showerror();

$query = "SELECT * FROM st4nkticket_settings WHERE settings_id = 1";

if (!($result = @ mysql_query($query, $connection)))
	showerror();

// PUT SELECTED id INFORMATION INTO AN ARRAY

$row = mysql_fetch_array($result);

// HEADER

require "./templates/admin.header.tpl";

if ($admin_access > 4) {

// BODY

print "<table align=center bgcolor=#FFFFFF border=0 width=1000>\n
		<tr><td colspan=2><p><i>You are logged in as {$_SESSION["loginUsername"]} </i></p></td></tr>\n
		<tr>
			<td width=250><form method=post action=admin.settings_update.php></td>
			<td width=750><input type=hidden name=settings_id value=1></td>
		</tr>\n
		<tr>
			<td><p>Administrative email address</p></td>
			<td><input type=text name=settings_admin_email value=$row[settings_admin_email] size=50></td>
		</tr>\n
		<tr>
			<td><p>Root website url</p></td>
			<td><input type=text name=settings_url value=$row[settings_url] size=50></td>
		</tr>\n
		<tr>
			<td><p>SMTP server</p></td>
			<td><input type=text name=settings_smtp value=$row[settings_smtp] size=50></td>
		</tr>\n
		<tr>
			<td><p>Time offset (hours)</p></td>
			<td><input type=text name=settings_time_offset value=$row[settings_time_offset] size=10></td>
		</tr>\n
		<tr>
			<td><p>Alert time for pending tickets (hours)</p></td>
			<td><input type=text name=settings_time_alert value=$row[settings_time_alert] size=10></td>
		</tr>\n
		<tr>
			<td><input type=submit value=update></form></td>
			<td></td>
		</tr>\n
	";

} else {

header("Location: error_logout.php");

exit;

}

// FOOTER

require "./templates/admin.footer.tpl";

}

}

?>
