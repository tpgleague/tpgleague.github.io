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

// ST4NK! Ticket Beta v0.1 - error_logout.php

// HEADER
   
require "./templates/front.header.tpl";

// BODY

print "<table align=center bgcolor=#FFFFFF border=0 cellpadding=5 cellspacing=0 width=800>
	<tr>
		<td align=left width=800>
			<p>You have either accessed an unauthorized page or you have not supplied required information in the form you submitted. This application uses javascript to ensure that forms are properly submitted.  Pleaes make sure you have java and javascript enabled in your browser. <p><p>Click <a class=two href=index.php>here</a> to return home.</p>
		</td>\n
	</tr>\n";
print "</table>\n";

// FOOTER

require "./templates/front.footer.tpl";

?>

