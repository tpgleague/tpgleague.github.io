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

// ST4NK! Ticket Beta v0.1 - admin.logout.php

session_start();

$message="";

// AN AUTHENTICATED USER HAS LOGGED OUT.

if (isset($_SESSION["loginUsername"]))

	$message .= "Thanks {$_SESSION["loginUsername"]} for using the support ticket system";

	// DESTROY THE CURRENT SESSION

	session_destroy();

	// HEADER
   
	require "./templates/front.header.tpl";

	// BODY

	print "
		<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=800>\n
		<tr>
			<td>
				<p align=left>$message</p>
				<p align=left><a class=two href=index.php>Go back home</a></p>
			</td>
		</tr>\n
		</table>\n
		";

	// FOOTER

	require "./templates/front.footer.tpl";

?>

