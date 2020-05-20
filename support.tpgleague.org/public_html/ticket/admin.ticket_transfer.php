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

// ST4NK! Ticket Beta v0.1 - admin.ticket_transfer.php

session_start();

// REQUIRE VALID USER FOR PAGE ACCESS

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
	
if ($admin_access > 4) {

$ticket_id = mysqlclean($_POST, "ticket_id", 10, $connection);
$helper_handle = mysqlclean($_POST, "helper_handle", 20, $connection);

$query = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_handle = '$helper_handle'");
$row = @mysql_fetch_array($query);
$ticket_helper = $row["helper_id"];

if (!mysql_select_db($databaseName, $connection))
	showerror();

// UPDATE THE HELPER ENTRY

$query = "UPDATE st4nkticket_tickets SET ticket_helper = '{$ticket_helper}' WHERE ticket_id = '{$ticket_id}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// START PHPMAILER ROUTINE

require("./includes/class.phpmailer.php");

// GET HELPER AND SITE SETTINGS INFORMATION

$query2 = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_handle = '$helper_handle'");

if (!($result = @ mysql_query($query, $connection)))
	showerror();

$row2 = @mysql_fetch_array($query2);
$helper_email = $row2["helper_email"];

$query3 = mysql_query ("SELECT * FROM st4nkticket_settings WHERE settings_id = '1'");

if (!($result = @ mysql_query($query, $connection)))
	showerror();

$row3 = @mysql_fetch_array($query3);
$admin_email = $row3["settings_admin_email"];
$smtp = $row3["settings_smtp"];

// SEND EMAIL TO THE HELPER

$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = $smtp; // SMTP server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = $smtpuser;  // SMTP username
$mail->Password = $smtppass; // SMTP password
$mail->From = $admin_email;
$mail->FromName = $admin_email;
$mail->AddAddress($helper_email);

$mail->Subject = "You have been assigned to ticket $ticket_id";
$mail->Body = "You have been assigned ticket $ticket_id by an admin";			

$mail->WordWrap = 80;

$return = $mail->Send();

// END OF PHPMAILER ROUTINE

// WE'RE DONE, RETURN TO ADMIN MANAGE PAGE

header("Location: admin.ticket_summary.php?ticket_id=$ticket_id");

} else {

header("Location: error_logout.php");

exit;

}

}

?>
