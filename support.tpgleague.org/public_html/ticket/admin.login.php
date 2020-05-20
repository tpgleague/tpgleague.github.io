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

// ST4NK! Ticket Beta v0.1 - admin.login.php

// HEADER
   
require "./templates/front.header.tpl";

// BODY

print "
	<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=800>\n

	<tr>\n
		<td align=left width=200><form method=POST action=admin.logincheck.php><p>TPG Helper Login</p></td>\n
		<td align=left width=600><p></p></td>\n
	</tr>\n

	<tr>\n 
		<td align=left width=200><p>Username</p></td>\n
		<td align=left width=600><input maxsize=20 type=text size=20 name=loginUsername></td>\n
	</tr>\n

	<tr>\n
		<td align=left width=200><p>Password</p></td>\n
		<td align=left width=600><input maxsize=10 type=password size=20 name=loginPassword></td>\n
	</tr>\n

	<tr>\n
		<td align=left width=200></td>\n
		<td align=left width=600><input type=submit value=Login></form></td>\n
	</tr>\n

	<tr>\n
		<td align=left width=200><p></p></td>\n
		<td align=left width=600><p></p></td>\n
	</tr>\n

	</table>\n
	";

// FOOTER

require "./templates/front.footer.tpl";

?>
