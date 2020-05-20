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

// ST4NK! Ticket Beta v0.1 - db.inc.php

require_once('./config.php');


// EDIT THE FOLLOWING LINES TO REFLECT YOUR SERVER AND DATABASE SETTINGS 

//$hostName = "localhost";
//$databaseName = "dbskytest";
//$username = "sbopple";
//$password = "curl";
	
// DON'T MESS WITH ANYTHING BELOW THIS LINE

// MYSQL ERROR FUNCTION

function showerror()
   
{
  
	die("Error " . mysql_errno() . " : " . mysql_error());

}

// FUNCTION TO UNTAINT DATA GOING TO DATABASE

function mysqlclean($array, $index, $maxlength, $connection)

{
  
	if (isset($array["{$index}"]))
    
	{
    
		$input = substr($array["{$index}"], 0, $maxlength);
		$input = mysql_real_escape_string($input, $connection);
		return ($input);
     
	}
    
return NULL;

}

// FUNCTION TO UNTAINT DATA GOING TO PHP SCRIPTS

function shellclean($array, $index, $maxlength)

{
    
	if (isset($array["{$index}"]))
			
	{

		$input = substr($array["{$index}"], 0, $maxlength);
		$input = EscapeShellArg($input);
		return ($input);

	}

return NULL;

}

// FUNCTION TO CONVERT NEWLINES TO PARAGRAPHS WHEN DISPLAYING DB TEXT

function para($pee, $br=1)
	
	{

	$pee = preg_replace("/(\r\n|\n|\r)/", "\n", $pee); // cross-platform newlines
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	$pee = preg_replace('/\n?(.+?)(\n\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end
	if ($br) $pee = preg_replace('|(?<!</p>)\s*\n|', "<br />\n", $pee); // optionally make line breaks
	return $pee;

	}



?>
