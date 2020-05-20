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

// ST4NK! Ticket Beta v0.1 - admin.manage.php

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
$admin_id = $row["helper_id"];

	
if ($admin_access > 0) {

// HEADER

require "./templates/admin.header.tpl";

// BODY

print "
	<table align=center bgcolor=#FFFFFF border=0 width=1000>\n
	<tr><td><p><i>You are logged in as {$_SESSION["loginUsername"]} </i></p></td></tr>\n
	<tr><td><p><b>Options</b></td></tr>\n
	<tr><td><p></p></td></tr>\n";

if ($admin_access > 4) {

print "
	<tr><td align=left><p><a class=two href=admin.helper_mod.php?helper_id=$admin_id>Manage my account</a></p></td></tr>\n
	<tr><td align=left><p><a class=two href=admin.helper.php>Manage helper accounts</a></p></td></tr>\n
	<tr><td align=left><p><a class=two href=admin.catagory.php>Manage ticket catagories</a></p></td></tr>\n
	<tr><td align=left><p><a class=two href=admin.settings.php>Manage system settings</a></p></td></tr>\n
	<tr><td align=left><p><a class=two href=admin.ticket_global.php>View ticket information</a></p></td></tr>\n
	<tr><td align=left><p><a class=two href=admin.ticket_stats.php>View ticket statistics</a></p></td></tr>\n";

} else {

print "
	<tr><td align=left><p><a class=two href=admin.helper_mod.php?helper_id=$admin_id>Manage my account</a></p></td></tr>\n
	<tr><td align=left><p><a class=two href=admin.ticket_global.php>View ticket information</a></p></td></tr>\n
	<tr><td align=left><p><a class=two href=admin.ticket_stats.php>View ticket statistics</a></p></td></tr>\n";

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

