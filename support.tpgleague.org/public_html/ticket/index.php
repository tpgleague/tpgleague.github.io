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

// ST4NK! Ticket Beta v0.1 - index.php

// IF THE INSTALL SCRIPTS HAVE NOT BEEN DELETED GO TO THE INSTALL PAGE
  
if(file_exists('install/install.php')) {

header("Location: install/install.php");
exit;

}

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";

// REQUIRE THE SELECT FUCNTION SCRIPT

require "./includes/select.php";	

// OPEN DATABASE CONNECTION

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);	

// START WEBPAGE

// HEADER

require "./templates/front.header.tpl";

// BODY

print "
	<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=800>\n
	<form action=ticket_submit.php method=post name=form onSubmit=\"return check(form)\">\n
	<tr>
		<td colspan=3><p class=header>New Support Ticket</p></td>
	</tr>\n
		<td width=100><p>Category</p></td>
		<td width=400>";

		selectDistinctCatagory($connection, "st4nkticket_catagories", "catagory_name", "catagory_name","All");
		
print "
	<tr>
		<td width=100><p>Email</p></td>
		<td width=400><input name=ticket_email size=40 type=text></td>
		<td rowspan=9 valign=top width=300>
			<p></p>
		</td>
	</tr>\n
	
	<tr>
		<td width=100><p>Subject</p></td>
		<td width=400><input name=ticket_subject size=40 type=text></td>
	</tr>\n
	<tr>
		<td valign=top width=100><p>Ticket<br>Description</p></td>
		<td width=400><textarea name=ticket_desc rows=8 cols=50></textarea></td>
	</tr>\n
	<tr>
		<td width=100></td>
		<td width=400><input type=submit value=\"Submit ticket\"></td>
	</tr>\n
	<form></table>\n
	";

// END BODY

// FOOTER
  
require "./templates/front.footer.tpl";

?>
