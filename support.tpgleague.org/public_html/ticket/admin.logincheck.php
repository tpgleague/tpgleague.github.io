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

// ST4NK! Ticket Beta v0.1 - admin.logincheck.php

session_start();

// REQUIRE AUTHENTICATION FOR THIS PAGE

require './includes/authentication.inc.php';
require './config.php';
require './includes/db.inc.php';

if (!$connection = @ mysql_connect($hostName, $username, $password))
	die("Cannot connect");

// COLLECT AND CLEAN FORM DATA

$loginUsername = mysqlclean($_POST, "loginUsername", 20, $connection);
$loginPassword = mysqlclean($_POST, "loginPassword", 10, $connection);

if (!mysql_selectdb($databaseName, $connection))
	showerror();

// AUTHENTICATE THE USER

if (authenticateUser($connection, $loginUsername, $loginPassword))

	{

	// REGISTER THE loginUsername
 
	$_SESSION["loginUsername"] = $loginUsername;

	// REGISTER THE IP ADDRESS THAT STARTED THIS SESSION
 
	$_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];

	header("Location: admin.php");
  
	exit;

	}

	else

	{
  
	header("Location: error_logout.php");

	exit;
	
	}

?>
