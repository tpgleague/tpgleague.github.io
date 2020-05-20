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

// ST4NK! Ticket Beta v0.1 - ticket_search.php

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";

// OPEN THE CONNECTION TO THE DATABASE

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);
   
// START WEBPAGE

// HEADER

require "./templates/front.header.tpl";

// BODY

print	"
	<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=800>\n
	<form action=ticket_summary.php method=get>\n
	<tr>
		<td colspan=2><p class=header>Search Support Tickets</p></td>
	</tr>\n 
	<tr>
		<td width=100><p>Email</p></td>
		<td width=400><input name=ticket_email size=30 type=text></td>
	</tr>\n
	<tr>
		<td width=100><p>Access Key</p></td>
		<td width=400><input name=ticket_key size=32 type=text></td>
	</tr>\n 
	<tr>
		<td width=100></td>
		<td width=400><input type=submit value=\"Search for ticket\"></td>
	</tr>\n 
	</form></table>\n
	";

// END BODY

// FOOTER
  
require "./templates/front.footer.tpl";

?>
