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

// ST4NK! Ticket Beta v0.1 - admin.ticket_add_note.php

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
	
if ($admin_access > 0) {

// TIMESTAMP THE NOTE

$note_date = date("U");

// GET THE HELPER NOTE INFO

$ticket_id = mysqlclean($_POST, "ticket_id", 7, $connection);
$ticket_helper = mysqlclean($_POST, "ticket_helper", 5, $connection);
$note_note = mysqlclean($_POST, "note_note", 1000, $connection);
$note_private = mysqlclean($_POST, "note_private",5, $connection);
$ticket_email = mysqlclean($_POST, "ticket_email", 30, $connection);	
$ticket_key = mysqlclean($_POST, "ticket_key", 32, $connection);

// IF WE MADE IT HERE, THE DATA IS VALID

if (!mysql_select_db($databaseName, $connection))
	showerror();

// INSERT ENTRY INTO THE NOTES TABLE

$query = "INSERT INTO st4nkticket_notes VALUES (
										NULL,
										'{$ticket_id}',
										'{$note_date}',
										'{$ticket_email}',
										'{$ticket_helper}',
										'h',
										'{$note_note}',
										'{$note_private}'
									)";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// UPDATE THE TICKET TABLE WITH THE NOTE DATE

$query = "UPDATE st4nkticket_tickets SET ticket_last_update = '{$note_date}', ticket_last_update_by = '{$admin_handle}' WHERE ticket_id = '{$ticket_id}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// START PHPMAILER ROUTINE

require("./includes/class.phpmailer.php");

// GET SITE SETTINGS AND CUSTOMER INFORMATION

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);

$query2 = mysql_query ("SELECT * FROM st4nkticket_tickets WHERE ticket_id = '$ticket_id'");
$row2 = @mysql_fetch_array($query2);

$ticket_email = $row2["ticket_email"];
$ticket_key = $row2["ticket_key"];

$query3 = mysql_query ("SELECT * FROM st4nkticket_settings WHERE settings_id = '1'");
$row3 = @mysql_fetch_array($query3);

$admin_email = $row3["settings_admin_email"];
$url = $row3["settings_url"];
$smtp = $row3["settings_smtp"];

// SEND EMAIL TO THE CUSTOMER

if ($note_private == "p") {

// skip the email

} else {

$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = $smtp; // SMTP server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = $smtpuser;  // SMTP username
$mail->Password = $smtppass; // SMTP password
$mail->From = $admin_email;
$mail->FromName = $admin_email;
$mail->AddAddress($ticket_email);

$mail->Subject = "There is an update to your support ticket $ticket_id";
$mail->Body = "

There is an update to your support ticket $ticket_id .  Click the following link to view the update: \n
$url/ticket_summary.php?ticket_email=$ticket_email&ticket_key=$ticket_key

";			

$mail->WordWrap = 80;

$return = $mail->Send();

}

// END OF PHPMAILER ROUTINE

// WE'RE DONE RETURN TO THE SUMMARY PAGE

header("Location: admin.ticket_summary.php?ticket_id=$ticket_id");

} else {

header("Location: error_logout.php");

exit;

}

}

?>
