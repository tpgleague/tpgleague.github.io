<?php
/*******************************************************************************
*  Title: Help Desk Software HESK
*  Version: 2.5.2 from 13th October 2013
*  Author: Klemen Stirn
*  Website: http://www.hesk.com
********************************************************************************
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2005-2013 Klemen Stirn. All Rights Reserved.
*  HESK is a registered trademark of Klemen Stirn.

*  The HESK may be used and modified free of charge by anyone
*  AS LONG AS COPYRIGHT NOTICES AND ALL THE COMMENTS REMAIN INTACT.
*  By using this code you agree to indemnify Klemen Stirn from any
*  liability that might arise from it's use.

*  Selling the code for this program, in part or full, without prior
*  written consent is expressly forbidden.

*  Using this code, in part or full, to create derivate work,
*  new scripts or products is expressly forbidden. Obtain permission
*  before redistributing this software over the Internet or in
*  any other medium. In all cases copyright and header must remain intact.
*  This Copyright is in full effect in any country that has International
*  Trade Agreements with the United States of America or
*  with the European Union.

*  Removing any of the copyright notices without purchasing a license
*  is expressly forbidden. To remove HESK copyright notice you must purchase
*  a license for this script. For more information on how to obtain
*  a license please visit the page below:
*  https://www.hesk.com/buy.php
*******************************************************************************/

/* Check if this is a valid include */
if (!defined('IN_SCRIPT')) {die('Invalid attempt');}


function hesk_dbSetNames()
{
	global $hesk_settings, $hesk_db_link;

    if ($hesk_settings['db_vrsn'])
    {
		mysqli_set_charset($hesk_db_link, 'utf8');
    }
    else
    {
    	hesk_dbQuery("SET NAMES 'utf8'");
    }

} // END hesk_dbSetNames()


function hesk_dbFormatEmail($email, $field = 'email')
{
	global $hesk_settings;

	$email = hesk_dbLike($email);

	if ($hesk_settings['multi_eml'])
	{
		return " (`".hesk_dbEscape($field)."` LIKE '".hesk_dbEscape($email)."' OR `".hesk_dbEscape($field)."` LIKE '%,".hesk_dbEscape($email)."' OR `".hesk_dbEscape($field)."` LIKE '".hesk_dbEscape($email).",%' OR `".hesk_dbEscape($field)."` LIKE '%,".hesk_dbEscape($email).",%') ";
	}
	else
	{
		return " `".hesk_dbEscape($field)."` LIKE '".hesk_dbEscape($email)."' ";
	}

} // END hesk_dbFormatEmail()


function hesk_dbTime()
{
	$res = hesk_dbQuery("SELECT NOW()");
	return strtotime(hesk_dbResult($res,0,0));
} // END hesk_dbTime()


function hesk_dbEscape($in)
{
	global $hesk_db_link;

    $in = mysqli_real_escape_string($hesk_db_link, stripslashes($in));
    $in = str_replace('`','&#96;',$in);

    return $in;
} // END hesk_dbEscape()


function hesk_dbLike($in)
{
	return str_replace( array('_', '%'), array('\\\\_', '\\\\%'), $in);
} // END hesk_dbLike()


function hesk_dbConnect()
{
	global $hesk_settings;
	global $hesk_db_link;
    global $hesklang;

    // Is mysqli supported?
    if ( ! function_exists('mysqli_connect') )
    {
    	die($hesklang['emp']);
    }

	// Do we need a special port? Check and connect to the database
	if ( strpos($hesk_settings['db_host'], ':') )
	{
		list($hesk_settings['db_host'], $hesk_settings['db_port']) = explode(':', $hesk_settings['db_host']);
		$hesk_db_link = @mysqli_connect($hesk_settings['db_host'], $hesk_settings['db_user'], $hesk_settings['db_pass'], $hesk_settings['db_name'], intval($hesk_settings['db_port']) );
	}
	else
	{
		$hesk_db_link = @mysqli_connect($hesk_settings['db_host'], $hesk_settings['db_user'], $hesk_settings['db_pass'], $hesk_settings['db_name']);
	}

	// Errors?
    if ( ! $hesk_db_link)
    {
    	if ($hesk_settings['debug_mode'])
        {
			hesk_error("$hesklang[cant_connect_db]</p><p>$hesklang[mysql_said]:<br />(".mysqli_connect_errno().") ".mysqli_connect_error()."</p>");
        }
        else
        {
			hesk_error("$hesklang[cant_connect_db]</p><p>$hesklang[contact_webmsater] <a href=\"mailto:$hesk_settings[webmaster_mail]\">$hesk_settings[webmaster_mail]</a></p>");
        }
    }

    // Check MySQL/PHP version and set encoding to utf8
    hesk_dbSetNames();

    return $hesk_db_link;

} // END hesk_dbConnect()


function hesk_dbClose()
{
	global $hesk_db_link;

    return @mysqli_close($hesk_db_link);

} // END hesk_dbClose()


function hesk_dbQuery($query)
{
    global $hesk_last_query;
    global $hesk_db_link;
    global $hesklang, $hesk_settings;

    if ( ! $hesk_db_link && ! hesk_dbConnect())
    {
        return false;
    }

    $hesk_last_query = $query;

    #echo "<p>EXPLAIN $query</p>\n";

    if ($res = @mysqli_query($hesk_db_link, $query))
    {
    	return $res;
    }
    elseif ($hesk_settings['debug_mode'])
    {
	    hesk_error("$hesklang[cant_sql]: $query</p><p>$hesklang[mysql_said]:<br />".mysqli_error($hesk_db_link)."</p>");
    }
    else
    {
	    hesk_error("$hesklang[cant_sql]</p><p>$hesklang[contact_webmsater] <a href=\"mailto:$hesk_settings[webmaster_mail]\">$hesk_settings[webmaster_mail]</a></p>");
    }

} // END hesk_dbQuery()


function hesk_dbFetchAssoc($res)
{

    return @mysqli_fetch_assoc($res);

} // END hesk_FetchAssoc()


function hesk_dbFetchRow($res)
{

    return @mysqli_fetch_row($res);

} // END hesk_FetchRow()


function hesk_dbResult($res, $row = 0, $column = 0)
{
	$i=0;
	$res->data_seek(0);

	while ($tmp = @mysqli_fetch_array($res, MYSQLI_NUM))
    {
		if ($i==$row)
        {
        	return $tmp[$column];
        }
		$i++;
	}

	return '';

} // END hesk_dbResult()


function hesk_dbInsertID()
{
	global $hesk_db_link;

    if ($lastid = @mysqli_insert_id($hesk_db_link))
    {
        return $lastid;
    }

} // END hesk_dbInsertID()


function hesk_dbFreeResult($res)
{

    return @mysqli_free_result($res);

} // END hesk_dbFreeResult()


function hesk_dbNumRows($res)
{

    return @mysqli_num_rows($res);

} // END hesk_dbNumRows()


function hesk_dbAffectedRows()
{
	global $hesk_db_link;

    return @mysqli_affected_rows($hesk_db_link);

} // END hesk_dbAffectedRows()
