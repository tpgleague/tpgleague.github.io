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

// ST4NK! Ticket Beta v0.1 - ticket_open.php

// REQUIRE DB ACCESS FOR THIS PAGE

require "./config.php";
require "./includes/db.inc.php";	

// OPEN THE DATABASE CONNECTION

$connection = mysql_connect($hostName, $username, $password);
mysql_select_db($databaseName, $connection);

if (!($connection = @ mysql_connect($hostName, $username, $password)))
	die("Could not connect to database");

$ticket_id = mysqlclean($_GET, "ticket_id", 7, $connection);
$ticket_key = mysqlclean($_GET, "ticket_key", 32, $connection);
		
if (!mysql_select_db($databaseName, $connection))
	showerror();

// GET THE EMAIL ASSOCIATED WITH THIS TICKET

$query = mysql_query ("SELECT * FROM st4nkticket_tickets WHERE ticket_id = '$ticket_id'");
$row = @mysql_fetch_array($query);
$ticket_email = $row["ticket_email"];

// UPDATE DATABASE TABLE

$query = "UPDATE st4nkticket_tickets
				SET
					ticket_status = 'active'
				WHERE
					ticket_id = '{$ticket_id}'
				AND
					ticket_key = '{$ticket_key}'";

if (!(@ mysql_query ($query, $connection)))
	showerror();

// RETURN TO TICKET SUMMARY PAGE


header("Location: ticket_summary.php?ticket_email=$ticket_email&ticket_key=$ticket_key");

?>
