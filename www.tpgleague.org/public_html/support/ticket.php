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

define('IN_SCRIPT',1);
define('HESK_PATH','./');
define('HESK_NO_ROBOTS',1);

/* Get all the required files and functions */
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
hesk_load_database_functions();

hesk_session_start();

$hesk_error_buffer = array();
$do_remember = '';
$display = 'none';

/* Was this accessed by the form or link? */
$is_form = isset($_GET['f']) ? 1 : 0;

/* Get the tracking ID */
$trackingID = hesk_cleanID();

/* Email required to view ticket? */
$my_email = hesk_getCustomerEmail(1);

/* A message from ticket reminder? */
if ( ! empty($_GET['remind']) )
{
    $display = 'block';
	print_form();
}

/* Any errors? Show the form */
if ($is_form)
{
	if ( empty($trackingID) )
    {
    	$hesk_error_buffer[] = $hesklang['eytid'];
    }

    if ($hesk_settings['email_view_ticket'] && empty($my_email) )
    {
    	$hesk_error_buffer[] = $hesklang['enter_valid_email'];
    }

    $tmp = count($hesk_error_buffer);
    if ($tmp == 1)
    {
    	$hesk_error_buffer = implode('',$hesk_error_buffer);
		hesk_process_messages($hesk_error_buffer,'NOREDIRECT');
        print_form();
    }
    elseif ($tmp == 2)
    {
    	$hesk_error_buffer = $hesklang['pcer'].'<br /><br /><ul><li>'.$hesk_error_buffer[0].'</li><li>'.$hesk_error_buffer[1].'</li></ul>';
		hesk_process_messages($hesk_error_buffer,'NOREDIRECT');
        print_form();
    }
}
elseif ( empty($trackingID) || ( $hesk_settings['email_view_ticket'] && empty($my_email) ) )
{
	print_form();
}

/* Connect to database */
hesk_dbConnect();

/* Limit brute force attempts */
hesk_limitBfAttempts();

/* Get ticket info */
$res = hesk_dbQuery( "SELECT `t1`.* , `t2`.name AS `repliername` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."tickets` AS `t1` LEFT JOIN `".hesk_dbEscape($hesk_settings['db_pfix'])."users` AS `t2` ON `t1`.`replierid` = `t2`.`id` WHERE `trackid`='".hesk_dbEscape($trackingID)."' LIMIT 1");

/* Ticket found? */
if (hesk_dbNumRows($res) != 1)
{
	/* Ticket not found, perhaps it was merged with another ticket? */
	$res = hesk_dbQuery("SELECT * FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."tickets` WHERE `merged` LIKE '%#".hesk_dbEscape($trackingID)."#%' LIMIT 1");

	if (hesk_dbNumRows($res) == 1)
	{
    	/* OK, found in a merged ticket. Get info */
     	$ticket = hesk_dbFetchAssoc($res);

		/* If we require e-mail to view tickets check if it matches the one from merged ticket */
		if ( hesk_verifyEmailMatch($ticket['trackid'], $my_email, $ticket['email'], 0) )
        {
        	hesk_process_messages( sprintf($hesklang['tme'], $trackingID, $ticket['trackid']) ,'NOREDIRECT','NOTICE');
            $trackingID = $ticket['trackid'];
        }
        else
        {
        	hesk_process_messages( sprintf($hesklang['tme1'], $trackingID, $ticket['trackid']) . '<br /><br />' . sprintf($hesklang['tme2'], $ticket['trackid']) ,'NOREDIRECT','NOTICE');
            $trackingID = $ticket['trackid'];
            print_form();
        }
	}
    else
    {
    	/* Nothing found, error out */
	    hesk_process_messages($hesklang['ticket_not_found'],'NOREDIRECT');
	    print_form();
    }
}
else
{
	/* We have a match, get ticket info */
	$ticket = hesk_dbFetchAssoc($res);

	/* If we require e-mail to view tickets check if it matches the one in database */
	hesk_verifyEmailMatch($trackingID, $my_email, $ticket['email']);
}

/* Ticket exists, clean brute force attempts */
hesk_cleanBfAttempts();

/* Remember email address? */
if ($is_form)
{
	if ( ! empty($_GET['r']) )
	{
		setcookie('hesk_myemail', $my_email, strtotime('+1 year'));
		$do_remember = ' checked="checked" ';
	}
	elseif ( isset($_COOKIE['hesk_myemail']) )
	{
		setcookie('hesk_myemail', '');
	}
}

/* Set last replier name */
if ($ticket['lastreplier'])
{
	if (empty($ticket['repliername']))
	{
		$ticket['repliername'] = $hesklang['staff'];
	}
}
else
{
	$ticket['repliername'] = $ticket['name'];
}

/* Get category name and ID */
$result = hesk_dbQuery("SELECT * FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."categories` WHERE `id`='".intval($ticket['category'])."' LIMIT 1");

/* If this category has been deleted use the default category with ID 1 */
if (hesk_dbNumRows($result) != 1)
{
	$result = hesk_dbQuery("SELECT * FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."categories` WHERE `id`='1' LIMIT 1");
}

$category = hesk_dbFetchAssoc($result);

/* Get replies */
$result  = hesk_dbQuery("SELECT * FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."replies` WHERE `replyto`='".intval($ticket['id'])."' ORDER BY `id` ".($hesk_settings['new_top'] ? 'DESC' : 'ASC') );
$replies = hesk_dbNumRows($result);
$unread_replies = array();

// Demo mode
if ( defined('HESK_DEMO') )
{
	$ticket['email'] = 'hidden@demo.com';
}

/* Print header */
require_once(HESK_PATH . 'inc/header.inc.php');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="3"><img src="img/headerleftsm.jpg" width="3" height="25" alt="" /></td>
<td class="headersm"><?php hesk_showTopBar($hesklang['cid'].': '.$trackingID); ?></td>
<td width="3"><img src="img/headerrightsm.jpg" width="3" height="25" alt="" /></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td><span class="smaller"><a href="<?php echo $hesk_settings['site_url']; ?>" class="smaller"><?php echo $hesk_settings['site_title']; ?></a> &gt;
<a href="<?php echo $hesk_settings['hesk_url']; ?>" class="smaller"><?php echo $hesk_settings['hesk_title']; ?></a>
&gt; <?php echo $hesklang['your_ticket']; ?></span></td>
</tr>
</table>

</td>
</tr>
<tr>
<td>

<?php
/* This will handle error, success and notice messages */
hesk_handle_messages();

/*
* If the ticket has been reopened by customer:
* - show the "Add a reply" form on top
* - and ask them why the form has been reopened
*/
if (isset($_SESSION['force_form_top']))
{
    hesk_printCustomerReplyForm(1);
    echo ' <p>&nbsp;</p> ';

    unset($_SESSION['force_form_top']);
}
?>

<h3 style="text-align:center"><?php echo $ticket['subject']; ?></h3>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="7" height="7"><img src="img/roundcornerslt.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornerstop"></td>
	<td><img src="img/roundcornersrt.jpg" width="7" height="7" alt="" /></td>
</tr>
<tr>
	<td class="roundcornersleft">&nbsp;</td>
	<td>
    <!-- START TICKET HEAD -->

		<table border="0" cellspacing="1" cellpadding="1">
		<?php

        if ($hesk_settings['sequential'])
        {
			echo '<tr>
			<td>'.$hesklang['trackID'].': </td>
			<td>'.$trackingID.' ('.$hesklang['seqid'].': '.$ticket['id'].')</td>
			</tr>';
        }
        else
        {
			echo '<tr>
			<td>'.$hesklang['trackID'].': </td>
			<td>'.$trackingID.'</td>
			</tr>';
        }

		echo '
		<tr>
		<td>'.$hesklang['ticket_status'].': </td>
		<td>';
		$random=rand(10000,99999);

		switch ($ticket['status'])
		{
			case 0:
				echo '<font class="open">'.$hesklang['open'].'</font> [<a href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=3&amp;Refresh='.$random.'&amp;token='.hesk_token_echo(0).'">'.$hesklang['close_action'].'</a>]';
				break;
			case 1:
				echo '<font class="replied">'.$hesklang['wait_staff_reply'].'</font> [<a href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=3&amp;Refresh='.$random.'&amp;token='.hesk_token_echo(0).'">'.$hesklang['close_action'].'</a>]';
				break;
			case 2:
				echo '<font class="waitingreply">'.$hesklang['wait_cust_reply'].'</font> [<a href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=3&amp;Refresh='.$random.'&amp;token='.hesk_token_echo(0).'">'.$hesklang['close_action'].'</a>]';
				break;
			case 4:
				echo '<font class="inprogress">'.$hesklang['in_progress'].'</font> [<a href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=3&amp;Refresh='.$random.'&amp;token='.hesk_token_echo(0).'">'.$hesklang['close_action'].'</a>]';
				break;
			case 5:
				echo '<font class="onhold">'.$hesklang['on_hold'].'</font> [<a href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=3&amp;Refresh='.$random.'&amp;token='.hesk_token_echo(0).'">'.$hesklang['close_action'].'</a>]';
				break;
			default:
				echo '<font class="resolved">'.$hesklang['closed'].'</font>';
				if ($ticket['locked'] != 1 && $hesk_settings['custopen'])
				{
					echo ' [<a href="change_status.php?track='.$trackingID.$hesk_settings['e_query'].'&amp;s=2&amp;Refresh='.$random.'&amp;token='.hesk_token_echo(0).'">'.$hesklang['open_action'].'</a>]';
				}
		}

		echo '</td>
		</tr>
		<tr>
		<td>'.$hesklang['created_on'].': </td>
		<td>'.hesk_date($ticket['dt']).'</td>
		</tr>
		<tr>
		<td>'.$hesklang['last_update'].': </td>
		<td>'.hesk_date($ticket['lastchange']).'</td>
		</tr>
		<tr>
		<td>'.$hesklang['last_replier'].': </td>
		<td>'.$ticket['repliername'].'</td>
		</tr>
		<tr>
		<td>'.$hesklang['category'].': </td>
		<td>'.$category['name'].'</td>
		</tr>
		<tr>
		<td>'.$hesklang['replies'].': </td>
		<td>'.$replies.'</td>
		</tr>
        ';

		if ($hesk_settings['cust_urgency'])
		{
			echo '
			<tr>
			<td>'.$hesklang['priority'].': </td>
			<td>';
			if ($ticket['priority']==0) {echo '<font class="critical">'.$hesklang['critical'].'</font>';}
            elseif ($ticket['priority']==1) {echo '<font class="important">'.$hesklang['high'].'</font>';}
			elseif ($ticket['priority']==2) {echo '<font class="medium">'.$hesklang['medium'].'</font>';}
			else {echo $hesklang['low'];}
			echo '
			</td>
			</tr>
			';
		}

		?>
		</table>

    <!-- END TICKET HEAD -->
	</td>
	<td class="roundcornersright">&nbsp;</td>
</tr>
<tr>
	<td><img src="img/roundcornerslb.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornersbottom"></td>
	<td width="7" height="7"><img src="img/roundcornersrb.jpg" width="7" height="7" alt="" /></td>
</tr>
</table>

<?php
// Print "Submit a reply" form?
if ($ticket['locked'] != 1 && $ticket['status'] != 3 && $hesk_settings['reply_top'] == 1)
{
	hesk_printCustomerReplyForm();
}
?>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="7" height="7"><img src="img/roundcornerslt.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornerstop"></td>
	<td><img src="img/roundcornersrt.jpg" width="7" height="7" alt="" /></td>
</tr>
<tr>
	<td class="roundcornersleft">&nbsp;</td>
	<td>
    <!-- START TICKET REPLIES -->

		<table border="0" cellspacing="1" cellpadding="1" width="100%">

        <?php
		if ($hesk_settings['new_top'])
        {
        	$i = hesk_printCustomerTicketReplies() ? 0 : 1;
        }
        else
        {
        	$i = 1;
        }

        /* Make sure original message is in correct color if newest are on top */
        $color = $i ? 'class="ticketalt"' : 'class="ticketrow"';
		?>

		<tr>
		<td <?php echo $color; ?>>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
			<td valign="top">

			    <table border="0" cellspacing="1">
			    <tr>
			    <td class="tickettd"><?php echo $hesklang['date']; ?>:</td>
			    <td class="tickettd"><?php echo hesk_date($ticket['dt']); ?></td>
			    </tr>
			    <tr>
			    <td class="tickettd"><?php echo $hesklang['name']; ?>:</td>
			    <td class="tickettd"><?php echo $ticket['name']; ?></td>
			    </tr>
			    <tr>
			    <td class="tickettd"><?php echo $hesklang['email']; ?>:</td>
			    <td class="tickettd"><?php echo str_replace(array('@','.'),array(' (at) ',' (dot) '),$ticket['email']); ?></td>
			    </tr>
			    </table>

			</td>
			<td style="text-align:right; vertical-align:top;">
				<?php echo hesk_getCustomerButtons($i); ?>
            </td>
			</tr>
			</table>

		<?php
		/* custom fields before message */
		$print_table = 0;
		$myclass = ' class="tickettd"';

		foreach ($hesk_settings['custom_fields'] as $k=>$v)
		{
			if ($v['use'] && $v['place']==0)
		    {
		    	if ($print_table == 0)
		        {
		        	echo '<table border="0" cellspacing="1" cellpadding="2">';
		        	$print_table = 1;
		        }

		        echo '
				<tr>
				<td valign="top" '.$myclass.'>'.$v['name'].':</td>
				<td valign="top" '.$myclass.'>'.$ticket[$k].'</td>
				</tr>
		        ';
		    }
		}
		if ($print_table)
		{
			echo '</table>';
		}
		?>

		<p><b><?php echo $hesklang['message']; ?>:</b></p>
		<p><?php echo $ticket['message']; ?><br />&nbsp;</p>

		<?php
		/* custom fields after message */
		$print_table = 0;
		$myclass = 'class="tickettd"';

		foreach ($hesk_settings['custom_fields'] as $k=>$v)
		{
			if ($v['use'] && $v['place'])
		    {
		    	if ($print_table == 0)
		        {
		        	echo '<table border="0" cellspacing="1" cellpadding="2">';
		        	$print_table = 1;
		        }

		        echo '
				<tr>
				<td valign="top" '.$myclass.'>'.$v['name'].':</td>
				<td valign="top" '.$myclass.'>'.$ticket[$k].'</td>
				</tr>
		        ';
		    }
		}
		if ($print_table)
		{
			echo '</table>';
		}

		/* Print attachments */
		hesk_listAttachments($ticket['attachments'], $i);
		?>

		</td>
		</tr>

        <?php
		if ( ! $hesk_settings['new_top'])
        {
        	hesk_printCustomerTicketReplies();
        }
		?>

	</table>

    <!-- END TICKET REPLIES -->
	</td>
	<td class="roundcornersright">&nbsp;</td>
</tr>
<tr>
	<td><img src="img/roundcornerslb.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornersbottom"></td>
	<td width="7" height="7"><img src="img/roundcornersrb.jpg" width="7" height="7" alt="" /></td>
</tr>
</table>

<?php
/* Print "Submit a reply" form? */
if ($ticket['locked'] != 1 && $ticket['status'] != 3 && ! $hesk_settings['reply_top'])
{
	hesk_printCustomerReplyForm();
}

/* If needed update unread replies as read for staff to know */
if ( count($unread_replies) )
{
	hesk_dbQuery("UPDATE `".hesk_dbEscape($hesk_settings['db_pfix'])."replies` SET `read` = '1' WHERE `id` IN ('".implode("','", $unread_replies)."')");
}

/* Clear unneeded session variables */
hesk_cleanSessionVars('ticket_message');

require_once(HESK_PATH . 'inc/footer.inc.php');

/*** START FUNCTIONS ***/

function print_form()
{
	global $hesk_settings, $hesklang;
    global $hesk_error_buffer, $my_email, $trackingID, $do_remember, $display;

	/* Print header */
	$hesk_settings['tmp_title'] = $hesk_settings['hesk_title'] . ' - ' . $hesklang['view_ticket'];
	require_once(HESK_PATH . 'inc/header.inc.php');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="3"><img src="img/headerleftsm.jpg" width="3" height="25" alt="" /></td>
<td class="headersm"><?php hesk_showTopBar($hesklang['view_ticket']); ?></td>
<td width="3"><img src="img/headerrightsm.jpg" width="3" height="25" alt="" /></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td><span class="smaller"><a href="<?php echo $hesk_settings['site_url']; ?>" class="smaller"><?php echo $hesk_settings['site_title']; ?></a> &gt;
<a href="<?php echo $hesk_settings['hesk_url']; ?>" class="smaller"><?php echo $hesk_settings['hesk_title']; ?></a>
&gt; <?php echo $hesklang['view_ticket']; ?></span></td>
</tr>
</table>

</td>
</tr>
<tr>
<td>

&nbsp;<br />

<?php
/* This will handle error, success and notice messages */
hesk_handle_messages();
?>

<div align="center">
<table border="0" cellspacing="0" cellpadding="0" width="50%">
<tr>
	<td width="7" height="7"><img src="img/roundcornerslt.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornerstop"></td>
	<td><img src="img/roundcornersrt.jpg" width="7" height="7" alt="" /></td>
</tr>
<tr>
	<td class="roundcornersleft">&nbsp;</td>
	<td>

        <form action="ticket.php" method="get" name="form2">

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
                <td width="1"><img src="img/existingticket.png" alt="" width="60" height="60" /></td>
                <td>
                <p><b><?php echo $hesklang['view_existing']; ?></a></b></p>
                </td>
        </tr>
        <tr>
                <td width="1">&nbsp;</td>
                <td>&nbsp;</td>
        </tr>
        <tr>
                <td width="1">&nbsp;</td>
                <td>
                <?php echo $hesklang['ticket_trackID']; ?>: <br /><input type="text" name="track" maxlength="20" size="35" value="<?php echo $trackingID; ?>" /><br />&nbsp;
                </td>
        </tr>
	<?php
    $tmp = '';
	if ($hesk_settings['email_view_ticket'])
	{
    	$tmp = 'document.form1.email.value=document.form2.e.value;';
		?>
        <tr>
                <td width="1">&nbsp;</td>
                <td>
                <?php echo $hesklang['email']; ?>: <br /><input type="text" name="e" size="35" value="<?php echo $my_email; ?>" /><br />&nbsp;<br />
                <label><input type="checkbox" name="r" value="Y" <?php echo $do_remember; ?> /> <?php echo $hesklang['rem_email']; ?></label><br />&nbsp;
                </td>
        </tr>
		<?php
	}
	?>
        <tr>
                <td width="1">&nbsp;</td>
                <td><input type="submit" value="<?php echo $hesklang['view_ticket']; ?>" class="orangebutton" onmouseover="hesk_btn(this,'orangebuttonover');" onmouseout="hesk_btn(this,'orangebutton');" /><input type="hidden" name="Refresh" value="<?php echo rand(10000,99999); ?>"><input type="hidden" name="f" value="1"></td>
        </tr>
        <tr>
                <td width="1">&nbsp;</td>
                <td>&nbsp;<br />&nbsp;<br /><a href="Javascript:void(0)" onclick="javascript:hesk_toggleLayerDisplay('forgot');<?php echo $tmp; ?>"><?php echo $hesklang['forgot_tid'];?></a>
                </td>
        </tr>
        </table>

        </form>

        &nbsp;

		<div id="forgot" class="notice" style="display: <?php echo $display; ?>;">
			<form action="index.php" method="post" name="form1">
			<p><b><?php echo $hesklang['forgot_tid'];?></b><br />&nbsp;<br /><?php echo $hesklang['tid_mail']; ?><br />
			<input type="text" name="email" size="35" value="<?php echo $my_email; ?>" /><input type="hidden" name="a" value="forgot_tid" /><br />&nbsp;<br />
			<input type="submit" value="<?php echo $hesklang['tid_send']; ?>" class="orangebutton" /></p>
			</form>
		</div>

	</td>
	<td class="roundcornersright">&nbsp;</td>
</tr>
<tr>
	<td><img src="img/roundcornerslb.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornersbottom"></td>
	<td width="7" height="7"><img src="img/roundcornersrb.jpg" width="7" height="7" alt="" /></td>
</tr>
</table>
</div>

<p>&nbsp;</p>
<?php
require_once(HESK_PATH . 'inc/footer.inc.php');
exit();
} // End print_form()


function hesk_printCustomerReplyForm($reopen=0)
{
	global $hesklang, $hesk_settings, $trackingID, $my_email;

	// Already printed?
	if (defined('REPLY_FORM'))
	{
		return '';
	}

	?>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="7" height="7"><img src="img/roundcornerslt.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornerstop"></td>
	<td><img src="img/roundcornersrt.jpg" width="7" height="7" alt="" /></td>
</tr>
<tr>
	<td class="roundcornersleft">&nbsp;</td>
	<td>

	<h3 style="text-align:center"><?php echo $hesklang['add_reply']; ?></h3>

	<form method="post" action="reply_ticket.php" enctype="multipart/form-data">
	<p align="center"><?php echo $hesklang['message']; ?>: <span class="important">*</span><br />
	<textarea name="message" rows="12" cols="60"><?php if (isset($_SESSION['ticket_message'])) {echo stripslashes(hesk_input($_SESSION['ticket_message']));} ?></textarea></p>

	<?php
	/* attachments */
	if ($hesk_settings['attachments']['use'])
    {
	?>

	<p align="center">
	<?php
	echo $hesklang['attachments'].' (<a href="file_limits.php" target="_blank" onclick="Javascript:hesk_window(\'file_limits.php\',250,500);return false;">' . $hesklang['ful'] . '</a>):<br />';
	for ($i=1;$i<=$hesk_settings['attachments']['max_number'];$i++)
    {
	    echo '<input type="file" name="attachment['.$i.']" size="50" /><br />';
	}
	?>
    &nbsp;
	</p>

	<?php
	}
	?>

	<p align="center">
    <input type="hidden" name="token" value="<?php hesk_token_echo(); ?>" />
    <input type="hidden" name="orig_track" value="<?php echo $trackingID; ?>" />
    <?php
    if ($hesk_settings['email_view_ticket'])
    {
	    echo '<input type="hidden" name="e" value="' . $my_email . '" />';
    }
    if ($reopen)
    {
	    echo '<input type="hidden" name="reopen" value="1" />';
    }
    ?>
	<input type="submit" value="<?php echo $hesklang['submit_reply']; ?>" class="orangebutton" onmouseover="hesk_btn(this,'orangebuttonover');" onmouseout="hesk_btn(this,'orangebutton');" /></p>

	</form>

	</td>
	<td class="roundcornersright">&nbsp;</td>
</tr>
<tr>
	<td><img src="img/roundcornerslb.jpg" width="7" height="7" alt="" /></td>
	<td class="roundcornersbottom"></td>
	<td width="7" height="7"><img src="img/roundcornersrb.jpg" width="7" height="7" alt="" /></td>
</tr>
</table>
	<?php

    // Make sure the form is only printed once per page
    define('REPLY_FORM', true);

} // End hesk_printCustomerReplyForm()


function hesk_printCustomerTicketReplies()
{
	global $hesklang, $hesk_settings, $result, $reply, $trackingID, $unread_replies;

	$i = $hesk_settings['new_top'] ? 0 : 1;

	while ($reply = hesk_dbFetchAssoc($result))
	{
		if ($i) {$color = 'class="ticketrow"'; $i=0;}
		else {$color = 'class="ticketalt"'; $i=1;}

		/* Store unread reply IDs for later */
		if ($reply['staffid'] && ! $reply['read'])
		{
			$unread_replies[] = $reply['id'];
		}

		$reply['dt'] = hesk_date($reply['dt']);
		?>
		<tr>
			<td <?php echo $color; ?>>

				<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td valign="top">
							<table border="0" cellspacing="1">
								<tr>
									<td><?php echo $hesklang['date']; ?>:</td>
									<td><?php echo $reply['dt']; ?></td>
								</tr>
								<tr>
									<td><?php echo $hesklang['name']; ?>:</td>
									<td><?php echo $reply['name']; ?></td>
								</tr>
							</table>
						</td>
						<td style="text-align:right; vertical-align:top;">
							<?php echo hesk_getCustomerButtons($i); ?>
						</td>
					</tr>
				</table>

			<p><b><?php echo $hesklang['message']; ?>:</b></p>
			<p><?php echo $reply['message']; ?></p>

			<?php

            /* Attachments */
			hesk_listAttachments($reply['attachments'],$i);

            /* Staff rating */
			if ($hesk_settings['rating'] && $reply['staffid'])
			{
				if ($reply['rating']==1)
				{
					echo '<p class="rate">'.$hesklang['rnh'].'</p>';
				}
				elseif ($reply['rating']==5)
				{
					echo '<p class="rate">'.$hesklang['rh'].'</p>';
				}
				else
				{
					echo '
					<div id="rating'.$reply['id'].'" class="rate">
					'.$hesklang['r'].'
					<a href="Javascript:void(0)" onclick="Javascript:hesk_rate(\'rate.php?rating=5&amp;id='.$reply['id'].'&amp;track='.$trackingID.'\',\'rating'.$reply['id'].'\')">'.strtolower($hesklang['yes']).'</a> /
					<a href="Javascript:void(0)" onclick="Javascript:hesk_rate(\'rate.php?rating=1&amp;id='.$reply['id'].'&amp;track='.$trackingID.'\',\'rating'.$reply['id'].'\')">'.strtolower($hesklang['no']).'</a>
					</div>
					';
				}
			}
			?>
	        </td>
        </tr>
        <?php
	}

    return $i;

} // End hesk_printCustomerTicketReplies()


function hesk_listAttachments($attachments='', $white=1)
{
	global $hesk_settings, $hesklang, $trackingID;

	/* Attachments disabled or not available */
	if ( ! $hesk_settings['attachments']['use'] || ! strlen($attachments) )
    {
    	return false;
    }

    /* Style and mousover/mousout */
    $tmp = $white ? 'White' : 'Blue';
    $style = 'class="option'.$tmp.'OFF" onmouseover="this.className=\'option'.$tmp.'ON\'" onmouseout="this.className=\'option'.$tmp.'OFF\'"';

	/* List attachments */
	echo '<p><b>'.$hesklang['attachments'].':</b><br />';
	$att=explode(',',substr($attachments, 0, -1));
	foreach ($att as $myatt)
	{
		list($att_id, $att_name) = explode('#', $myatt);

		echo '
		<a href="download_attachment.php?att_id='.$att_id.'&amp;track='.$trackingID.$hesk_settings['e_query'].'"><img src="img/clip.png" width="16" height="16" alt="'.$hesklang['dnl'].' '.$att_name.'" title="'.$hesklang['dnl'].' '.$att_name.'" '.$style.' /></a>
		<a href="download_attachment.php?att_id='.$att_id.'&amp;track='.$trackingID.$hesk_settings['e_query'].'">'.$att_name.'</a><br />
        ';
	}
	echo '</p>';

    return true;
} // End hesk_listAttachments()


function hesk_getCustomerButtons($white=1)
{
	global $hesk_settings, $hesklang, $trackingID;

	$options = '';

    /* Style and mousover/mousout */
    $tmp = $white ? 'White' : 'Blue';
    $style = 'class="option'.$tmp.'OFF" onmouseover="this.className=\'option'.$tmp.'ON\'" onmouseout="this.className=\'option'.$tmp.'OFF\'"';

	/* Print ticket button */
    $options .= '<a href="print.php?track='.$trackingID.$hesk_settings['e_query'].'"><img src="img/print.png" width="16" height="16" alt="'.$hesklang['printer_friendly'].'" title="'.$hesklang['printer_friendly'].'" '.$style.' /></a> ';

    /* Return generated HTML */
    return $options;

} // END hesk_getCustomerButtons()
?>
