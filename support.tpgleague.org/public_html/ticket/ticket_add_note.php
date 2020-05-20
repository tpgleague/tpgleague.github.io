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

// ST4NK! Ticket Beta v0.1 - ticket_add_note.php

//session_start();

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";	

// OPEN THE DATABASE CONNECTION

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);

// TIMESTAMP THE INCOMING NOTE

$note_date = date("U");

// GET THE NOTE INFO

$ticket_email = mysqlclean($_POST, "ticket_email", 50, $connection);
$ticket_key = mysqlclean($_POST, "ticket_key", 32, $connection);
$ticket_id = mysqlclean($_POST, "ticket_id", 7, $connection);
$ticket_helper = mysqlclean($_POST, "ticket_helper", 5, $connection);
$note_note = mysqlclean($_POST, "note_note", 4096, $connection);

if (!mysql_select_db($databaseName, $connection))
	showerror();

// INSERT ENTRY INTO THE NOTES TABLE

$query = "INSERT INTO st4nkticket_notes VALUES (
						NULL,
						'{$ticket_id}',
						'{$note_date}',
						'{$ticket_email}',
						'{$ticket_helper}',
						'c',
						'{$note_note}',
						''
						)";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// UPDATE THE TICKET TABLE WITH THE NOTE DATE

$query = "UPDATE st4nkticket_tickets SET ticket_last_update = '{$note_date}', ticket_last_update_by = 'customer' WHERE ticket_id = '{$ticket_id}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// START PHPMAILER ROUTINE

require("./includes/class.phpmailer.php");

// GET SITE SETTINGS INFORMATION

$query2 = mysql_query ("SELECT * FROM st4nkticket_tickets WHERE ticket_id = '$ticket_id'");

if (!($result = @ mysql_query($query, $connection)))
	showerror();

$row2 = @mysql_fetch_array($query2);
$ticket_helper = $row2["ticket_helper"];
$ticket_subject = $row2["ticket_subject"];

$query3 = mysql_query ("SELECT * FROM st4nkticket_settings WHERE settings_id = '1'");

if (!($result = @ mysql_query($query, $connection)))
	showerror();

$row3 = @mysql_fetch_array($query3);
$admin_email = $row3["settings_admin_email"];
$smtp = $row3["settings_smtp"];

// SEND EMAIL TO THE HELPER, IF ONE IS ASSIGNED

if ($ticket_helper > 0)

{

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);
	
$query4 = mysql_query ("SELECT * FROM st4nkticket_helpers WHERE helper_id = '$ticket_helper'");
$row4 = @mysql_fetch_array($query4);
$helper_email = $row4["helper_email"];

$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = $smtp; // SMTP server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = $smtpuser;  // SMTP username
$mail->Password = $smtppass; // SMTP password
$mail->From = $admin_email;
$mail->FromName = $admin_email;
$mail->AddAddress($helper_email);

$mail->Subject = "There is an update to ticket $ticket_id";
$mail->Body = "

There is an update to ticket $ticket_id \n
From: $ticket_email \n
Subject: $ticket_subject

";			

$mail->WordWrap = 80;

$return = $mail->Send();
	
}

// END OF PHPMAILER ROUTINE

// DO THE REDIRECT

header("Location: ticket_summary.php?ticket_email=$ticket_email&ticket_key=$ticket_key");

?>
