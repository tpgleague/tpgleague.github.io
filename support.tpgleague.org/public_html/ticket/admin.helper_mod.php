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

// ST4NK! Ticket Beta v0.1 - admin.helper_mod.php

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

// GET ID FOR HELPER

$helper_id = mysqlclean($_GET, "helper_id", 5, $connection);

// RETRIEVE HELPER DETAILS FOR EDITING

if (!mysql_select_db($databaseName, $connection))
	showerror();

$query = "SELECT * FROM st4nkticket_helpers WHERE helper_id = {$helper_id}";

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
			<td width=250><form method=post action=admin.helper_update.php></td>
			<td width=750><input type=hidden name=helper_id value=$row[helper_id]></td>
		</tr>\n
		<tr>
			<td><p>Helper handle</p></td>
			<td><input type=text name=helper_handle value=$row[helper_handle] size=20></td>
		</tr>\n
		<tr>
			<td><p>Helper active?</p></td>
			<td><select name=helper_active>
				<option selected value=$row[helper_active]>$row[helper_active]
				<option value=yes>yes
				<option value=no>no
			</td>
		</tr>\n
		<tr>
			<td><p>Helper admin level</p></td>
			<td><select name=helper_admin_level>
				<option selected value=$row[helper_admin_level]>$row[helper_admin_level]
				<option value=0>0
				<option value=1>1
				<option value=5>5
			</td>
		</tr>\n
		<tr>
			<td><p>Email</p></td>
			<td><input type=text name=helper_email value=$row[helper_email] size=40></td>
		</tr>\n
		<tr>
			<td><input type=submit value=update></form></td>
			<td></td>
		</tr>\n
		<tr>
			<td><form method=post action=admin.helper_password.php onSubmit=\"return checkPw(this)\"></td>
			<td><input type=hidden name=helper_id value=$row[helper_id]></td>
		</tr>\n
		<tr>
			<td><p>Change password (10 characters max)</p></td>
			<td><p><input type=password name=password size=10></p></td>
		</tr>\n
		<tr>
			<td><p>Confirm password</p></td>
			<td><p><input type=password name=password2 size=10></p></td>
		</tr>\n
		<tr>
			<td><input type=submit value=update></form></td>
			<td></td>
		</tr>\n
	";

} elseif (($admin_access > 0) && ($admin_access < 5)) {

// BODY

print "<table align=center bgcolor=#FFFFFF border=0 width=1000>\n
		<tr><td colspan=2><p><i>You are logged in as {$_SESSION["loginUsername"]} </i></p></td></tr>\n
		<tr>
			<td width=250><form method=post action=admin.helper_update.php></td>
			<td width=750><input type=hidden name=helper_id value=$row[helper_id]></td>
		</tr>\n
		<tr>
			<td><p>Email</p></td>
			<td><input type=text name=helper_email value=$row[helper_email] size=40></td>
		</tr>\n
		<tr>
			<td><input type=submit value=update></form></td>
			<td></td>
		</tr>\n
		<tr>
			<td><form method=post action=admin.helper_password.php onSubmit=\"return checkPw(this)\"></td>
			<td><input type=hidden name=helper_id value=$row[helper_id]></td>
		</tr>\n
		<tr>
			<td><p>Change password (10 characters max)</p></td>
			<td><p><input type=password name=password size=10></p></td>
		</tr>\n
		<tr>
			<td><p>Confirm password</p></td>
			<td><p><input type=password name=password2 size=10></p></td>
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
