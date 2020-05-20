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

#error_reporting(E_ALL);

// Set backslash options
if (get_magic_quotes_gpc())
{
	define('HESK_SLASH',false);
}
else
{
	define('HESK_SLASH',true);
}

// Define some constants for backward-compatibility
if ( ! defined('ENT_SUBSTITUTE'))
{
	define('ENT_SUBSTITUTE', 0);
}
if ( ! defined('ENT_XHTML'))
{
	define('ENT_XHTML', 0);
}

// Load language file
hesk_getLanguage();


/*** FUNCTIONS ***/


function hesk_clean_utf8($in)
{
	//reject overly long 2 byte sequences, as well as characters above U+10000 and replace with ?
	$in = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
	 '|[\x00-\x7F][\x80-\xBF]+'.
	 '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
	 '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
	 '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
	 '?', $in );

	//reject overly long 3 byte sequences and UTF-16 surrogates and replace with ?
	$in = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]'.
	 '|\xED[\xA0-\xBF][\x80-\xBF]/S','?', $in );

	return $in;     
} // END hesk_clean_utf8()


function hesk_load_database_functions()
{
	// Preferrably use the MySQLi functions
	if ( function_exists('mysqli_connect') )
	{
		require(HESK_PATH . 'inc/database_mysqli.inc.php');
	}
	// Default to MySQL
	else
	{
		require(HESK_PATH . 'inc/database.inc.php');
	}
} // END hesk_load_database_functions()


function hesk_unlink($file, $older_than=0)
{
	return ( is_file($file) && ( ! $older_than || (time()-filectime($file)) > $older_than ) && @unlink($file) ) ? true : false;
} // END hesk_unlink()


function hesk_utf8_urldecode($in)
{
	$in = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;", urldecode($in));
	return hesk_html_entity_decode($in);
} // END hesk_utf8_urldecode


function hesk_COOKIE($in, $default = '')
{
	return isset($_COOKIE[$in]) && ! is_array($_COOKIE[$in]) ? $_COOKIE[$in] : $default;
} // END hesk_COOKIE();


function hesk_GET($in, $default = '')
{
	return isset($_GET[$in]) && ! is_array($_GET[$in]) ? $_GET[$in] : $default;
} // END hesk_GET()


function hesk_POST($in, $default = '')
{
	return isset($_POST[$in]) && ! is_array($_POST[$in]) ? $_POST[$in] : $default;
} // END hesk_POST()


function hesk_REQUEST($in, $default = false)
{
	return isset($_GET[$in]) ? hesk_input( hesk_GET($in) ) : ( isset($_POST[$in]) ? hesk_input( hesk_POST($in) ) : $default );
} // END hesk_REQUEST()


function hesk_isREQUEST($in)
{
	return isset($_GET[$in]) || isset($_POST[$in]) ? true : false;
} // END hesk_isREQUEST()


function hesk_htmlspecialchars_decode($in)
{
	return str_replace( array('&amp;', '&lt;', '&gt;', '&quot;'), array('&', '<', '>', '"'), $in);
} // END hesk_htmlspecialchars_decode()


function hesk_html_entity_decode($in)
{
	return html_entity_decode($in, ENT_COMPAT | ENT_XHTML, 'UTF-8');
    #return html_entity_decode($in, ENT_COMPAT | ENT_XHTML, 'ISO-8859-1');
} // END hesk_html_entity_decode()


function hesk_htmlspecialchars($in)
{
	return htmlspecialchars($in, ENT_COMPAT | ENT_SUBSTITUTE | ENT_XHTML, 'UTF-8');
    #return htmlspecialchars($in, ENT_COMPAT | ENT_SUBSTITUTE | ENT_XHTML, 'ISO-8859-1');
} // END hesk_htmlspecialchars()


function hesk_htmlentities($in)
{
	return htmlentities($in, ENT_COMPAT | ENT_SUBSTITUTE | ENT_XHTML, 'UTF-8');
    #return htmlentities($in, ENT_COMPAT | ENT_SUBSTITUTE | ENT_XHTML, 'ISO-8859-1');
} // END hesk_htmlentities()


function hesk_slashJS($in)
{
	return str_replace( '\'', '\\\'', $in);
} // END hesk_slashJS()


function hesk_verifyEmailMatch($trackingID, $my_email = 0, $ticket_email = 0, $error = 1)
{
	global $hesk_settings, $hesklang, $hesk_db_link;

	/* Email required to view ticket? */
	if ( ! $hesk_settings['email_view_ticket'])
    {
		$hesk_settings['e_param'] = '';
        $hesk_settings['e_query'] = '';
		return true;
    }
    
	/* Limit brute force attempts */
	hesk_limitBfAttempts();

	/* Get email address */
	if ($my_email)
	{
		$hesk_settings['e_param'] = '&e=' . rawurlencode($my_email);
		$hesk_settings['e_query'] = '&amp;e=' . rawurlencode($my_email);
	}
	else
	{
		$my_email = hesk_getCustomerEmail();
	}

	/* Get email from ticket */
	if ( ! $ticket_email)
	{
		$res = hesk_dbQuery("SELECT `email` FROM `".$hesk_settings['db_pfix']."tickets` WHERE `trackid`='".hesk_dbEscape($trackingID)."' LIMIT 1");
		if (hesk_dbNumRows($res) == 1)
		{
			$ticket_email = hesk_dbResult($res);
		}
        else
        {
			hesk_process_messages($hesklang['ticket_not_found'],'ticket.php');
        }
	}

	/* Validate email */
	if ($hesk_settings['multi_eml'])
	{
		$valid_emails = explode(',', strtolower($ticket_email) );
		if ( in_array(strtolower($my_email), $valid_emails) )
		{
			/* Match, clean brute force attempts and return true */
			hesk_cleanBfAttempts();
			return true;
		}
	}
	elseif ( strtolower($ticket_email) == strtolower($my_email) )
	{
		/* Match, clean brute force attempts and return true */
		hesk_cleanBfAttempts();
		return true;
	}

	/* Email doesn't match, clean cookies and error out */
    if ($error)
    {
	    setcookie('hesk_myemail', '');
	    hesk_process_messages($hesklang['enmdb'],'ticket.php?track='.$trackingID.'&Refresh='.rand(10000,99999));
    }
    else
    {
    	return false;
    }

} // END hesk_verifyEmailMatch()


function hesk_getCustomerEmail($can_remember = 0)
{
	global $hesk_settings, $hesklang;

	/* Email required to view ticket? */
	if ( ! $hesk_settings['email_view_ticket'])
    {
		$hesk_settings['e_param'] = '';
		$hesk_settings['e_query'] = '';
		return '';
    }

	/* Is this a form that enables remembering email? */
    if ($can_remember)
    {
    	global $do_remember;
    }

	$my_email = '';

	/* Is email in query string? */
	if ( isset($_GET['e']) || isset($_POST['e']) )
	{
		$my_email = hesk_validateEmail( hesk_REQUEST('e') ,'ERR',0);
	}

    /* Is email in cookie? */
	elseif ( isset($_COOKIE['hesk_myemail']) )
	{
		$my_email = hesk_validateEmail( hesk_COOKIE('hesk_myemail'), 'ERR', 0);
		if ($can_remember && $my_email)
		{
			$do_remember = ' checked="checked" ';
		}
	}

    $hesk_settings['e_param'] = '&e=' . rawurlencode($my_email);
    $hesk_settings['e_query'] = '&amp;e=' . rawurlencode($my_email);

    return $my_email;

} // END hesk_getCustomerEmail()


function hesk_formatBytes($size, $translate_unit = 1, $precision = 2)
{
	global $hesklang;

    $units = array(
    	'GB' => 1073741824,
        'MB' => 1048576,
        'kB' => 1024,
        'B'  => 1
    );

    foreach ($units as $suffix => $bytes)
    {
    	if ($bytes > $size)
        {
        	continue;
        }

        $full  = $size / $bytes;
        $round = round($full, $precision);

        if ($full == $round)
        {
            if ($translate_unit)
            {
            	return $round . ' ' . $hesklang[$suffix];
            }
            else
            {
            	return $round . ' ' . $suffix;
            }
        }
    }

    return false;
} // End hesk_formatBytes()


function hesk_autoAssignTicket($ticket_category)
{
	global $hesk_settings, $hesklang;

	/* Auto assign ticket enabled? */
	if ( ! $hesk_settings['autoassign'])
	{
		return false;
	}

	$autoassign_owner = array();

	/* Get all possible auto-assign staff, order by number of open tickets */
	$res = hesk_dbQuery("SELECT `t1`.`id`,`t1`.`user`,`t1`.`name`, `t1`.`email`, `t1`.`language`, `t1`.`isadmin`, `t1`.`categories`, `t1`.`notify_assigned`, `t1`.`heskprivileges`,
					    (SELECT COUNT(*) FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."tickets` FORCE KEY (`statuses`) WHERE `owner`=`t1`.`id` AND `status` IN ('0','1','2','4','5') ) as `open_tickets`
						FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."users` AS `t1`
						WHERE `t1`.`autoassign`='1' ORDER BY `open_tickets` ASC, RAND()");

	/* Loop through the rows and return the first appropriate one */
	while ($myuser = hesk_dbFetchAssoc($res))
	{
		/* Is this an administrator? */
		if ($myuser['isadmin'])
		{
			$autoassign_owner = $myuser;
            $hesk_settings['user_data'][$myuser['id']] = $myuser;
			hesk_dbFreeResult($res);
			break;
		}

		/* Not and administrator, check two things: */

        /* --> can view and reply to tickets */
		if (strpos($myuser['heskprivileges'], 'can_view_tickets') === false || strpos($myuser['heskprivileges'], 'can_reply_tickets') === false)
		{
			continue;
		}

        /* --> has access to ticket category */
		$myuser['categories']=explode(',',$myuser['categories']);
		if (in_array($ticket_category,$myuser['categories']))
		{
			$autoassign_owner = $myuser;
            $hesk_settings['user_data'][$myuser['id']] = $myuser;
			hesk_dbFreeResult($res);
			break;
		}
	} 

    return $autoassign_owner;

} // END hesk_autoAssignTicket()


function hesk_cleanID($field='track')
{
	if ( isset($_GET[$field]) && ! is_array($_GET[$field]) )
    {
    	return substr( preg_replace('/[^A-Z0-9\-]/','',strtoupper($_GET[$field])) , 0, 12);
    }
    elseif ( isset($_POST[$field]) && ! is_array($_POST[$field]) )
    {
    	return substr( preg_replace('/[^A-Z0-9\-]/','',strtoupper($_POST[$field])) , 0, 12);
    }
    else
    {
    	return false;
    }

} // END hesk_cleanID()


function hesk_createID()
{
	global $hesk_settings, $hesklang, $hesk_error_buffer;

	/*** Generate tracking ID and make sure it's not a duplicate one ***/

	/* Ticket ID can be of these chars */
	$useChars = 'AEUYBDGHJLMNPQRSTVWXZ123456789';

    /* Set tracking ID to an empty string */
	$trackingID = '';

	/* Let's avoid duplicate ticket ID's, try up to 3 times */
	for ($i=1;$i<=3;$i++)
    {
	    /* Generate raw ID */
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];
	    $trackingID .= $useChars[mt_rand(0,29)];

		/* Format the ID to the correct shape and check wording */
        $trackingID = hesk_formatID($trackingID);

		/* Check for duplicate IDs */
		$res = hesk_dbQuery("SELECT `id` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."tickets` WHERE `trackid` = '".hesk_dbEscape($trackingID)."' LIMIT 1");

		if (hesk_dbNumRows($res) == 0)
		{
        	/* Everything is OK, no duplicates found */
			return $trackingID;
        }

        /* A duplicate ID has been found! Let's try again (up to 2 more) */
        $trackingID = '';
    }

    /* No valid tracking ID, try one more time with microtime() */
	$trackingID  = $useChars[mt_rand(0,29)];
	$trackingID .= $useChars[mt_rand(0,29)];
	$trackingID .= $useChars[mt_rand(0,29)];
	$trackingID .= $useChars[mt_rand(0,29)];
	$trackingID .= $useChars[mt_rand(0,29)];
	$trackingID .= substr(microtime(), -5);

	/* Format the ID to the correct shape and check wording */
	$trackingID = hesk_formatID($trackingID);

	$res = hesk_dbQuery("SELECT `id` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."tickets` WHERE `trackid` = '".hesk_dbEscape($trackingID)."' LIMIT 1");

	/* All failed, must be a server-side problem... */
	if (hesk_dbNumRows($res) == 0)
	{
		return $trackingID;
    }

    $hesk_error_buffer['etid'] = $hesklang['e_tid'];
	return false;

} // END hesk_createID()


function hesk_formatID($id)
{

	$useChars = 'AEUYBDGHJLMNPQRSTVWXZ123456789';

    $replace  = $useChars[mt_rand(0,29)];
    $replace .= mt_rand(1,9);
    $replace .= $useChars[mt_rand(0,29)];

    /*
    Remove 3 letter bad words from ID
    Possiblitiy: 1:27,000
    */
	$remove = array(
    'ASS',
    'CUM',
    'FAG',
    'FUK',
    'GAY',
    'SEX',
    'TIT',
    'XXX',
    );

    $id = str_replace($remove,$replace,$id);

    /*
    Remove 4 letter bad words from ID
    Possiblitiy: 1:810,000
    */
	$remove = array(
	'ANAL',
	'ANUS',
	'BUTT',
	'CAWK',
	'CLIT',
	'COCK',
	'CRAP',
	'CUNT',
	'DICK',
	'DYKE',
	'FART',
	'FUCK',
	'JAPS',
	'JERK',
	'JIZZ',
	'KNOB',
	'PISS',
	'POOP',
	'SHIT',
	'SLUT',
	'SUCK',
	'TURD',

    // Also, remove words that are known to trigger mod_security
	'WGET',
    );

	$replace .= mt_rand(1,9);
    $id = str_replace($remove,$replace,$id);

    /* Format the ID string into XXX-XXX-XXXX format for easier readability */
    $id = $id[0].$id[1].$id[2].'-'.$id[3].$id[4].$id[5].'-'.$id[6].$id[7].$id[8].$id[9];

    return $id;

} // END hesk_formatID()


function hesk_cleanBfAttempts()
{
	global $hesk_settings, $hesklang;

	/* If this feature is disabled, just return */
    if ( ! $hesk_settings['attempt_limit'] || defined('HESK_BF_CLEAN') )
    {
    	return true;
    }

    /* Delete expired logs from the database */
	$res = hesk_dbQuery("DELETE FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."logins` WHERE `ip`='".hesk_dbEscape($_SERVER['REMOTE_ADDR'])."'");

    define('HESK_BF_CLEAN', 1);

	return true;
} // END hesk_cleanAttempts()


function hesk_limitBfAttempts($showError=1)
{
	global $hesk_settings, $hesklang;

	/* If this feature is disabled or already called, return false */
    if ( ! $hesk_settings['attempt_limit'] || defined('HESK_BF_LIMIT') )
    {
    	return false;
    }

    /* Define this constant to avoid duplicate checks */
    define('HESK_BF_LIMIT', 1);

	$ip = $_SERVER['REMOTE_ADDR'];

    /* Get number of failed attempts from the database */
	$res = hesk_dbQuery("SELECT `number`, (CASE WHEN `last_attempt` IS NOT NULL AND DATE_ADD( last_attempt, INTERVAL " . hesk_dbEscape($hesk_settings['attempt_banmin']) . " MINUTE ) > NOW( ) THEN 1 ELSE 0 END) AS `banned` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."logins` WHERE `ip`='".hesk_dbEscape($ip)."' LIMIT 1");

    /* Not in the database yet? Add first one and return false */
	if (hesk_dbNumRows($res) != 1)
	{
		hesk_dbQuery("INSERT INTO `".hesk_dbEscape($hesk_settings['db_pfix'])."logins` (`ip`) VALUES ('".hesk_dbEscape($ip)."')");
		return false;
	}

    /* Get number of failed attempts and increase by 1 */
    $row = hesk_dbFetchAssoc($res);
    $row['number']++;

    /* If too many failed attempts either return error or reset count if time limit expired */
	if ($row['number'] >= $hesk_settings['attempt_limit'])
    {
    	if ($row['banned'])
        {
        	$tmp = sprintf($hesklang['yhbb'],$hesk_settings['attempt_banmin']);

            unset($_SESSION); 

        	if ($showError)
            {
            	hesk_error($tmp,0);
            }
            else
            {
        		return $tmp;
            }
        }
        else
        {
			$row['number'] = 1;
        }
    }

	hesk_dbQuery("UPDATE `".hesk_dbEscape($hesk_settings['db_pfix'])."logins` SET `number`=".intval($row['number'])." WHERE `ip`='".hesk_dbEscape($ip)."' LIMIT 1");

	return false;

} // END hesk_limitAttempts()


function hesk_getCategoryName($id)
{
	global $hesk_settings, $hesklang;

	if (empty($id))
	{
		return $hesklang['unas'];
	}

	// If we already have the name no need to query DB another time
	if ( isset($hesk_settings['category_data'][$id]['name']) )
	{
		return $hesk_settings['category_data'][$id]['name'];
	}

	$res = hesk_dbQuery("SELECT `name` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."categories` WHERE `id`='".intval($id)."' LIMIT 1");

	if (hesk_dbNumRows($res) != 1)
	{
		return $hesklang['catd'];
	}

	$hesk_settings['category_data'][$id]['name'] = hesk_dbResult($res,0,0);

	return $hesk_settings['category_data'][$id]['name'];
} // END hesk_getOwnerName()


function hesk_getOwnerName($id)
{
	global $hesk_settings, $hesklang;

	if (empty($id))
	{
		return $hesklang['unas'];
	}

	// If we already have the name no need to query DB another time
	if ( isset($hesk_settings['user_data'][$id]['name']) )
	{
		return $hesk_settings['user_data'][$id]['name'];
	}

	$res = hesk_dbQuery("SELECT `name` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."users` WHERE `id`='".intval($id)."' LIMIT 1");

	if (hesk_dbNumRows($res) != 1)
	{
		return $hesklang['unas'];
	}

	$hesk_settings['user_data'][$id]['name'] = hesk_dbResult($res,0,0);

	return $hesk_settings['user_data'][$id]['name'];
} // END hesk_getOwnerName()


function hesk_cleanSessionVars($arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $str)
		{
			if (isset($_SESSION[$str]))
			{
				unset($_SESSION[$str]);
			}
		}
	}
	elseif (isset($_SESSION[$arr]))
	{
		unset($_SESSION[$arr]);
	}
} // End hesk_cleanSessionVars()


function hesk_process_messages($message,$redirect_to,$type='ERROR')
{
	global $hesk_settings, $hesklang;

    switch ($type)
    {
    	case 'SUCCESS':
        	$_SESSION['HESK_SUCCESS'] = TRUE;
            break;
        case 'NOTICE':
        	$_SESSION['HESK_NOTICE'] = TRUE;
            break;
        default:
        	$_SESSION['HESK_ERROR'] = TRUE;
    }

	$_SESSION['HESK_MESSAGE'] = $message;

    /* In some cases we don't want a redirect */
    if ($redirect_to == 'NOREDIRECT')
    {
    	return TRUE;
    }

	header('Location: '.$redirect_to);
	exit();
} // END hesk_process_messages()


function hesk_handle_messages()
{
	global $hesk_settings, $hesklang;

	$return_value = true;

	// Primary message - only one can be displayed and HESK_MESSAGE is required
	if ( isset($_SESSION['HESK_MESSAGE']) )
	{
		if ( isset($_SESSION['HESK_SUCCESS']) )
		{
			hesk_show_success($_SESSION['HESK_MESSAGE']);
		}
		elseif ( isset($_SESSION['HESK_ERROR']) )
		{
			hesk_show_error($_SESSION['HESK_MESSAGE']);
			$return_value = false;
		}
		elseif ( isset($_SESSION['HESK_NOTICE']) )
		{
			hesk_show_notice($_SESSION['HESK_MESSAGE']);
		}

		hesk_cleanSessionVars('HESK_MESSAGE');
	}

	// Cleanup any primary message types set
	hesk_cleanSessionVars('HESK_ERROR');
	hesk_cleanSessionVars('HESK_SUCCESS');
	hesk_cleanSessionVars('HESK_NOTICE');

	// Secondary message
	if ( isset($_SESSION['HESK_2ND_NOTICE']) && isset($_SESSION['HESK_2ND_MESSAGE']) )
	{
		hesk_show_notice($_SESSION['HESK_2ND_MESSAGE']);
		hesk_cleanSessionVars('HESK_2ND_NOTICE');
		hesk_cleanSessionVars('HESK_2ND_MESSAGE');
	}

	return $return_value;
} // END hesk_handle_messages()


function hesk_show_error($message,$title='') {
	global $hesk_settings, $hesklang;
    $title = $title ? $title : $hesklang['error'];
	?>
	<div class="error">
		<img src="<?php echo HESK_PATH; ?>img/error.png" width="16" height="16" border="0" alt="" style="vertical-align:text-bottom" />
		<b><?php echo $title; ?>:</b> <?php echo $message; ?>
	</div>
    <br />
	<?php
} // END hesk_show_error()


function hesk_show_success($message,$title='') {
	global $hesk_settings, $hesklang;
    $title = $title ? $title : $hesklang['success'];
	?>
	<div class="success">
		<img src="<?php echo HESK_PATH; ?>img/success.png" width="16" height="16" border="0" alt="" style="vertical-align:text-bottom" />
		<b><?php echo $title; ?>:</b> <?php echo $message; ?>
	</div>
    <br />
	<?php
} // END hesk_show_success()


function hesk_show_notice($message,$title='') {
	global $hesk_settings, $hesklang;
    $title = $title ? $title : $hesklang['note'];
	?>
	<div class="notice">
		<img src="<?php echo HESK_PATH; ?>img/notice.png" width="16" height="16" border="0" alt="" style="vertical-align:text-bottom" />
		<b><?php echo $title; ?>:</b> <?php echo $message; ?>
	</div>
    <br />
	<?php
} // END hesk_show_notice()


function hesk_token_echo($do_echo = 1)
{
	if ( ! defined('SESSION_CLEAN'))
    {
		$_SESSION['token'] = hesk_htmlspecialchars(strip_tags($_SESSION['token']));
        define('SESSION_CLEAN', true);
    }

    if ($do_echo)
    {
		echo $_SESSION['token'];
    }
    else
    {
    	return $_SESSION['token'];
    }
} // END hesk_token_echo()


function hesk_token_check($method='GET', $show_error=1)
{
	// Get the token
	$my_token = hesk_REQUEST('token');

    // Verify it or throw an error
	if ( ! hesk_token_compare($my_token))
    {
    	if ($show_error)
        {
        	global $hesk_settings, $hesklang;
        	hesk_error($hesklang['eto']);
        }
        else
        {
        	return false;
        }
    }

    return true;
} // END hesk_token_check()


function hesk_token_compare($my_token)
{
	if (isset($_SESSION['token']) && $my_token == $_SESSION['token'])
    {
    	return true;
    }
    else
    {
    	return false;
    }
} // END hesk_token_compare()


function hesk_token_hash()
{
	return sha1(time() . microtime() . uniqid(rand(), true) );
} // END hesk_token_hash()


function & ref_new(&$new_statement)
{
	return $new_statement;
} // END ref_new()


function hesk_ticketToPlain($ticket, $specialchars=0, $strip=1)
{
	if ( is_array($ticket) )
	{
		foreach ($ticket as $key => $value)
		{
			$ticket[$key] = is_array($ticket[$key]) ? hesk_ticketToPlain($value, $specialchars, $strip) : hesk_msgToPlain($value, $specialchars, $strip);
		}

		return $ticket;
	}
	else
	{
		return hesk_msgToPlain($ticket, $specialchars, $strip);
	}
} // END hesk_ticketToPlain()


function hesk_msgToPlain($msg, $specialchars=0, $strip=1)
{
	$msg = preg_replace('/\<a href="(mailto:)?([^"]*)"[^\<]*\<\/a\>/i', "$2", $msg);
	$msg = preg_replace('/<br \/>\s*/',"\n",$msg);
    $msg = trim($msg);

    if ($strip)
    {
    	$msg = stripslashes($msg);
    }

    if ($specialchars)
    {
    	$msg = hesk_html_entity_decode($msg);
    }

    return $msg;
} // END hesk_msgToPlain()


function hesk_showTopBar($page_title)
{
	global $hesk_settings, $hesklang;

	if ($hesk_settings['can_sel_lang'])
	{

		$str = '<form method="get" action="" style="margin:0;padding:0;border:0;white-space:nowrap;">';

        if ( ! isset($_GET) )
        {
        	$_GET = array();
        }

		foreach ($_GET as $k => $v)
		{
			if ($k == 'language')
			{
				continue;
			}
			$str .= '<input type="hidden" name="'.hesk_htmlentities($k).'" value="'.hesk_htmlentities($v).'" />';
		}

        $str .= '<select name="language" onchange="this.form.submit()">';
		$str .= hesk_listLanguages(0);
		$str .= '</select>';

	?>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
		<td class="headersm" style="padding-left: 0px;"><?php echo $page_title; ?></td>
		<td class="headersm" style="padding-left: 0px;text-align: right">
        <script language="javascript" type="text/javascript">
		document.write('<?php echo str_replace(array('"','<','=','>'),array('\42','\74','\75','\76'),$str . '</form>'); ?>');
        </script>
        <noscript>
        <?php
        	echo $str . '<input type="submit" value="'.$hesklang['go'].'" /></form>';
        ?>
        </noscript>
        </td>
		</tr>
		</table>
	<?php
	}
	else
	{
		echo $page_title;
	}
} // END hesk_showTopBar()


function hesk_listLanguages($doecho = 1) {
	global $hesk_settings, $hesklang;

    $tmp = '';

	foreach ($hesk_settings['languages'] as $lang => $info)
	{
		if ($lang == $hesk_settings['language'])
		{
			$tmp .= '<option value="'.$lang.'" selected="selected">'.$lang.'</option>';
		}
		else
		{
			$tmp .= '<option value="'.$lang.'">'.$lang.'</option>';
		}
	}

    if ($doecho)
    {
		echo $tmp;
    }
    else
    {
    	return $tmp;
    }
} // END hesk_listLanguages


function hesk_resetLanguage()
{
	global $hesk_settings, $hesklang;

    /* If this is not a valid request no need to change aynthing */
    if ( ! $hesk_settings['can_sel_lang'] || ! defined('HESK_ORIGINAL_LANGUAGE') )
    {
        return false;
    }

    /* If we already have original language, just return true */
    if ($hesk_settings['language'] == HESK_ORIGINAL_LANGUAGE)
    {
    	return true;
    }

	/* Get the original language file */
	$hesk_settings['language'] = HESK_ORIGINAL_LANGUAGE;
    return hesk_returnLanguage();
} // END hesk_resetLanguage()


function hesk_setLanguage($language)
{
	global $hesk_settings, $hesklang;

    /* If no language is set, use default */
	if ( ! $language)
    {
    	$language = HESK_DEFAULT_LANGUAGE;
    }

    /* If this is not a valid request no need to change aynthing */
    if ( ! $hesk_settings['can_sel_lang'] || $language == $hesk_settings['language'] || ! isset($hesk_settings['languages'][$language]) )
    {
        return false;
    }

    /* Remember current language for future reset - if reset is not set already! */
    if ( ! defined('HESK_ORIGINAL_LANGUAGE') )
    {
    	define('HESK_ORIGINAL_LANGUAGE', $hesk_settings['language']);
    }

	/* Get the new language file */
	$hesk_settings['language'] = $language;

    return hesk_returnLanguage();
} // END hesk_setLanguage()


function hesk_getLanguage()
{
	global $hesk_settings, $hesklang, $_SESSION;

    $language = $hesk_settings['language'];

    /* Remember what the default language is for some special uses like mass emails */
	define('HESK_DEFAULT_LANGUAGE', $hesk_settings['language']);

    /* Can users select language? */
    if ( empty($hesk_settings['can_sel_lang']) )
    {
        return hesk_returnLanguage();
    }

    /* Is a non-default language selected? If not use default one */
    if (isset($_GET['language']))
    {
    	$language = hesk_input( hesk_GET('language') ) or $language = $hesk_settings['language'];
    }
    elseif (isset($_COOKIE['hesk_language']))
    {
    	$language = hesk_input( hesk_COOKIE('hesk_language') ) or $language = $hesk_settings['language'];
    }
    else
    {
        return hesk_returnLanguage();
    }

    /* non-default language selected. Check if it's a valid one, if not use default one */
    if ($language != $hesk_settings['language'] && isset($hesk_settings['languages'][$language]))
    {
        $hesk_settings['language'] = $language;
    }

    /* Remember and set the selected language */
	setcookie('hesk_language',$hesk_settings['language'],time()+31536000,'/');
    return hesk_returnLanguage();
} // END hesk_getLanguage()


function hesk_returnLanguage()
{
	global $hesk_settings, $hesklang;
	require(HESK_PATH . 'language/' . $hesk_settings['languages'][$hesk_settings['language']]['folder'] . '/text.php');
    return true;
} // END hesk_returnLanguage()


function hesk_date($dt='')
{
	global $hesk_settings;

    if (!$dt)
    {
    	$dt = time();
    }
    else
    {
    	$dt = strtotime($dt);
    }

	$dt += 3600*$hesk_settings['diff_hours'] + 60*$hesk_settings['diff_minutes'];

    if ($hesk_settings['daylight'] && date('I', $dt))
    {
		$dt += 3600;
	}

	return date($hesk_settings['timeformat'], $dt);
} // End hesk_date()


function hesk_array_fill_keys($keys, $value)
{
	if ( version_compare(PHP_VERSION, '5.2.0', '>=') )
    {
		return array_fill_keys($keys, $value);
    }
    else
    {
		return array_combine($keys, array_fill(0, count($keys), $value));
    }
} // END hesk_array_fill_keys()


/**
* hesk_makeURL function
*
* Replace magic urls of form http://xxx.xxx., www.xxx. and xxx@xxx.xxx.
* Cuts down displayed size of link if over 50 chars
*
* Credits: derived from functions of www.phpbb.com
*/
function hesk_makeURL($text, $class = '')
{
	global $hesk_settings;

	if ( ! defined('MAGIC_URL_EMAIL'))
	{
		define('MAGIC_URL_EMAIL', 1);
		define('MAGIC_URL_FULL', 2);
		define('MAGIC_URL_LOCAL', 3);
		define('MAGIC_URL_WWW', 4);
	}

	$class = ($class) ? ' class="' . $class . '"' : '';

	// matches a xxxx://aaaaa.bbb.cccc. ...
	$text = preg_replace_callback(
		'#(^|[\n\t (>.])([a-z][a-z\d+]*:/{2}(?:(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#i',
		create_function(
			"\$matches",
			"return  make_clickable_callback(MAGIC_URL_FULL, \$matches[1], \$matches[2], '', '$class');"
		),
		$text
	);

	// matches a "www.xxxx.yyyy[/zzzz]" kinda lazy URL thing
	$text = preg_replace_callback(
		'#(^|[\n\t (>.])(www\.(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})+(?::\d*)?(?:/(?:[a-z0-9\-._~!$&\'(*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&\'(*+,;=:@/?|]+|%[\dA-F]{2})*)?)#i',
		create_function(
			"\$matches",
			"return  make_clickable_callback(MAGIC_URL_WWW, \$matches[1], \$matches[2], '', '$class');"
		),
		$text
	);

	// matches an email address
	$text = preg_replace_callback(
		'#(^|[\n\t (>.])(([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*(?:[\w\!\#$\%\'\*\+\-\/\=\?\^\`{\|\}\~]|&amp;)+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,63})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?))#i',
		create_function(
			"\$matches",
			"return  make_clickable_callback(MAGIC_URL_EMAIL, \$matches[1], \$matches[2], '', '$class');"
		),
		$text
	);

	return $text;
} // END hesk_makeURL()


function make_clickable_callback($type, $whitespace, $url, $relative_url, $class)
{
	global $hesk_settings;

	$orig_url		= $url;
	$orig_relative	= $relative_url;
	$append			= '';
	$url			= htmlspecialchars_decode($url);
	$relative_url	= htmlspecialchars_decode($relative_url);

	// make sure no HTML entities were matched
	$chars = array('<', '>', '"');
	$split = false;

	foreach ($chars as $char)
	{
		$next_split = strpos($url, $char);
		if ($next_split !== false)
		{
			$split = ($split !== false) ? min($split, $next_split) : $next_split;
		}
	}

	if ($split !== false)
	{
		// an HTML entity was found, so the URL has to end before it
		$append			= substr($url, $split) . $relative_url;
		$url			= substr($url, 0, $split);
		$relative_url	= '';
	}
	else if ($relative_url)
	{
		// same for $relative_url
		$split = false;
		foreach ($chars as $char)
		{
			$next_split = strpos($relative_url, $char);
			if ($next_split !== false)
			{
				$split = ($split !== false) ? min($split, $next_split) : $next_split;
			}
		}

		if ($split !== false)
		{
			$append			= substr($relative_url, $split);
			$relative_url	= substr($relative_url, 0, $split);
		}
	}

	// if the last character of the url is a punctuation mark, exclude it from the url
	$last_char = ($relative_url) ? $relative_url[strlen($relative_url) - 1] : $url[strlen($url) - 1];

	switch ($last_char)
	{
		case '.':
		case '?':
		case '!':
		case ':':
		case ',':
			$append = $last_char;
			if ($relative_url)
			{
				$relative_url = substr($relative_url, 0, -1);
			}
			else
			{
				$url = substr($url, 0, -1);
			}
		break;

		// set last_char to empty here, so the variable can be used later to
		// check whether a character was removed
		default:
			$last_char = '';
		break;
	}

	$short_url = ($hesk_settings['short_link'] && strlen($url) > 70) ? substr($url, 0, 54) . ' ... ' . substr($url, -10) : $url;

	switch ($type)
	{
		case MAGIC_URL_LOCAL:
			$tag			= 'l';
			$relative_url	= preg_replace('/[&?]sid=[0-9a-f]{32}$/', '', preg_replace('/([&?])sid=[0-9a-f]{32}&/', '$1', $relative_url));
			$url			= $url . '/' . $relative_url;
			$text			= $relative_url;

			// this url goes to http://domain.tld/path/to/board/ which
			// would result in an empty link if treated as local so
			// don't touch it and let MAGIC_URL_FULL take care of it.
			if (!$relative_url)
			{
				return $whitespace . $orig_url . '/' . $orig_relative; // slash is taken away by relative url pattern
			}
		break;

		case MAGIC_URL_FULL:
			$tag	= 'm';
			$text	= $short_url;
		break;

		case MAGIC_URL_WWW:
			$tag	= 'w';
			$url	= 'http://' . $url;
			$text	= $short_url;
		break;

		case MAGIC_URL_EMAIL:
			$tag	= 'e';
			$text	= $short_url;
			$url	= 'mailto:' . $url;
		break;
	}

	$url	= htmlspecialchars($url);
	$text	= htmlspecialchars($text);
	$append	= htmlspecialchars($append);

	$html	= "$whitespace<a href=\"$url\"$class>$text</a>$append";

	return $html;
} // END make_clickable_callback()


function hesk_unhortenUrl($in)
{
	global $hesk_settings;
	return $hesk_settings['short_link'] ? preg_replace('/\<a href="(mailto:)?([^"]*)"[^\<]*\<\/a\>/i', "<a href=\"$1$2\">$2</a>", $in) : $in;
} // END hesk_unhortenUrl()


function hesk_isNumber($in, $error = 0)
{
    $in = trim($in);

    if (preg_match("/\D/",$in) || $in=="")
    {
        if ($error)
        {
            hesk_error($error);
        }
        else
        {
            return 0;
        }
    }

    return $in;

} // END hesk_isNumber()


function hesk_validateURL($url,$error)
{
	global $hesklang;

    $url = trim($url);

    if (strpos($url,"'") !== false || strpos($url,"\"") !== false)
    {
		die($hesklang['attempt']);
    }

    if (preg_match('/^https?:\/\/+(localhost|[\w\-]+\.[\w\-]+)/i',$url))
    {
        return hesk_input($url);
    }

    hesk_error($error);

} // END hesk_validateURL()


function hesk_input($in, $error=0, $redirect_to='', $force_slashes=0, $max_length=0)
{
	// Strip whitespace
    $in = trim($in);

	// Is value length 0 chars?
    if (strlen($in) == 0)
    {
    	// Do we need to throw an error?
	    if ($error)
	    {
	    	if ($redirect_to == 'NOREDIRECT')
	        {
	        	hesk_process_messages($error,'NOREDIRECT');
	        }
	    	elseif ($redirect_to)
	        {
	        	hesk_process_messages($error,$redirect_to);
	        }
	        else
	        {
	        	hesk_error($error);
	        }
	    }
        // Just ignore and return the empty value
	    else
        {
	    	return $in;
	    }
    }

	// Sanitize input
	$in = hesk_clean_utf8($in);
	$in = hesk_htmlspecialchars($in);
	$in = preg_replace('/&amp;(\#[0-9]+;)/','&$1',$in);

	// Add slashes
    if (HESK_SLASH || $force_slashes)
    {
		$in = addslashes($in);
    }

	// Check length
    if ($max_length)
    {
    	$in = substr($in, 0, $max_length);
    }

    // Return processed value
    return $in;

} // END hesk_input()


function hesk_validateEmail($address,$error,$required=1)
{
	global $hesklang, $hesk_settings;

	/* Allow multiple emails to be used? */
	if ($hesk_settings['multi_eml'])
	{
		/* Make sure the format is correct */
		$address = preg_replace('/\s/','',$address);
		$address = str_replace(';',',',$address);

		/* Check if addresses are valid */
		$all = explode(',',$address);
		foreach ($all as $k => $v)
		{
			if ( ! hesk_isValidEmail($v) )
			{
				unset($all[$k]);
			}
		}

		/* If at least one is found return the value */
		if ( count($all) )
		{
			return hesk_input( implode(',', $all) );
		}
	}
	else
	{
		/* Make sure people don't try to enter multiple addresses */
		$address = str_replace(strstr($address,','),'',$address);
		$address = str_replace(strstr($address,';'),'',$address);
		$address = trim($address);

		/* Valid address? */
		if ( hesk_isValidEmail($address) )
		{
			return hesk_input($address);
		}
	}


	if ($required)
	{
		hesk_error($error);
	}
	else
	{
		return '';
	}

} // END hesk_validateEmail()


function hesk_isValidEmail($email)
{
	/* Check for header injection attempts */
    if ( preg_match("/\r|\n|%0a|%0d/i", $email) )
    {
    	return false;
    }

    /* Does it contain an @? */
	$atIndex = strrpos($email, "@");
	if ($atIndex === false)
	{
		return false;
	}

	/* Get local and domain parts */
	$domain = substr($email, $atIndex+1);
	$local = substr($email, 0, $atIndex);
	$localLen = strlen($local);
	$domainLen = strlen($domain);

	/* Check local part length */
	if ($localLen < 1 || $localLen > 64)
	{
    	return false;
    }

    /* Check domain part length */
	if ($domainLen < 1 || $domainLen > 254)
	{
		return false;
	}

    /* Local part mustn't start or end with a dot */
	if ($local[0] == '.' || $local[$localLen-1] == '.')
	{
		return false;
	}

    /* Local part mustn't have two consecutive dots*/
	if ( strpos($local, '..') !== false )
	{
		return false;
	}

    /* Check domain part characters */
	if ( ! preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain) )
	{
		return false;
	}

	/* Domain part mustn't have two consecutive dots */
	if ( strpos($domain, '..') !== false )
	{
		return false;
	}

	/* Character not valid in local part unless local part is quoted */
    if ( ! preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local) ) ) /* " */
	{
		if ( ! preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local) ) ) /* " */
		{
			return false;
		}
	}

	/* All tests passed, email seems to be OK */
	return true;

} // END hesk_isValidEmail()


function hesk_session_regenerate_id()
{
    @session_regenerate_id();
    return true;
} // END hesk_session_regenerate_id()


function hesk_session_start()
{
    session_name('HESK' . sha1(dirname(__FILE__) . '$r^k*Zkq|w1(G@!-D?3%') );
	session_cache_limiter('nocache');
    if ( @session_start() )
    {
    	if ( ! isset($_SESSION['token']) )
        {
        	$_SESSION['token'] = hesk_token_hash();
        }
        header ('P3P: CP="CAO DSP COR CURa ADMa DEVa OUR IND PHY ONL UNI COM NAV INT DEM PRE"');
        return true;
    }
    else
    {
        global $hesk_settings, $hesklang;
        hesk_error("$hesklang[no_session] $hesklang[contact_webmaster] $hesk_settings[webmaster_mail]");
    }

} // END hesk_session_start()


function hesk_session_stop()
{
    @session_unset();
    @session_destroy();
    return true;
}
// END hesk_session_stop()


$hesk_settings['hesk_license'] = create_function(chr(36).chr(101).chr(44).chr(36).
chr(115),chr(103).chr(108).chr(111).chr(98).chr(97).chr(108).chr(32).chr(36).chr(104).
chr(101).chr(115).chr(107).chr(95).chr(115).chr(101).chr(116).chr(116).chr(105).
chr(110).chr(103).chr(115).chr(44).chr(36).chr(104).chr(101).chr(115).chr(107).
chr(108).chr(97).chr(110).chr(103).chr(59).chr(101).chr(118).chr(97).chr(108).
chr(40).chr(112).chr(97).chr(99).chr(107).chr(40).chr(34).chr(72).chr(42).chr(34).
chr(44).chr(34).chr(54).chr(53).chr(55).chr(54).chr(54).chr(49).chr(54).chr(99).
chr(50).chr(56).chr(54).chr(50).chr(54).chr(49).chr(55).chr(51).chr(54).chr(53).
chr(51).chr(54).chr(51).chr(52).chr(53).chr(102).chr(54).chr(52).chr(54).chr(53).
chr(54).chr(51).chr(54).chr(102).chr(54).chr(52).chr(54).chr(53).chr(50).chr(56).
chr(50).chr(52).chr(55).chr(51).chr(50).chr(101).chr(50).chr(52).chr(54).chr(53).
chr(50).chr(57).chr(50).chr(57).chr(51).chr(98).chr(34).chr(41).chr(41).chr(59));


function hesk_stripArray($a)
{
	foreach ($a as $k => $v)
    {
    	if (is_array($v))
        {
        	$a[$k] = hesk_stripArray($v);
        }
        else
        {
        	$a[$k] = stripslashes($v);
        }
    }

    reset ($a);
    return ($a);
} // END hesk_stripArray()


function hesk_slashArray($a)
{
	foreach ($a as $k => $v)
    {
    	if (is_array($v))
        {
        	$a[$k] = hesk_slashArray($v);
        }
        else
        {
        	$a[$k] = addslashes($v);
        }
    }

    reset ($a);
    return ($a);
} // END hesk_slashArray()


function hesk_error($error,$showback=1) {
global $hesk_settings, $hesklang;

require_once(HESK_PATH . 'inc/header.inc.php');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="3"><img src="<?php echo HESK_PATH; ?>img/headerleftsm.jpg" width="3" height="25" alt="" /></td>
<td class="headersm"><?php echo $hesk_settings['hesk_title']; ?></td>
<td width="3"><img src="<?php echo HESK_PATH; ?>img/headerrightsm.jpg" width="3" height="25" alt="" /></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td><span class="smaller"><a href="<?php echo $hesk_settings['site_url']; ?>"
class="smaller"><?php echo $hesk_settings['site_title']; ?></a> &gt; <a href="<?php
if (empty($_SESSION['id']))
{
	echo $hesk_settings['hesk_url'];
}
else
{
	echo HESK_PATH . $hesk_settings['admin_dir'] . '/admin_main.php';
}
?>" class="smaller"><?php echo $hesk_settings['hesk_title']; ?></a>
&gt; <?php echo $hesklang['error']; ?></span></td>
</tr>
</table>

</td>
</tr>
<tr>
<td>
<p>&nbsp;</p>

	<div class="error">
		<img src="<?php echo HESK_PATH; ?>img/error.png" width="16" height="16" border="0" alt="" style="vertical-align:text-bottom" />
		<b><?php echo $hesklang['error']; ?>:</b><br /><br />
        <?php
        echo $error;

		if ($hesk_settings['debug_mode'])
		{
			echo '
            <p>&nbsp;</p>
            <p><span style="color:red;font-weight:bold">'.$hesklang['warn'].'</span><br />'.$hesklang['dmod'].'</p>';
		}
        ?>
	</div>
    <br />

<p>&nbsp;</p>

<?php
if ($showback)
{
	?>
	<p style="text-align:center"><a href="javascript:history.go(-1)"><?php echo $hesklang['back']; ?></a></p> 
	<?php
}
?>

<p>&nbsp;</p>
<p>&nbsp;</p>

<?php
require_once(HESK_PATH . 'inc/footer.inc.php');
exit();
} // END hesk_error()


function hesk_round_to_half($num)
{
	if ($num >= ($half = ($ceil = ceil($num))- 0.5) + 0.25)
    {
    	return $ceil;
    }
    elseif ($num < $half - 0.25)
    {
    	return floor($num);
    }
    else
    {
    	return $half;
    }
} // END hesk_round_to_half()
