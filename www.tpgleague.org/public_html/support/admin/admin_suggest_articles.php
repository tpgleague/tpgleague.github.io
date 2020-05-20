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
define('HESK_PATH','../');

/* Get all the required files and functions */
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
require(HESK_PATH . 'inc/admin_functions.inc.php');
hesk_load_database_functions();

hesk_session_start();
hesk_dbConnect();
hesk_isLoggedIn();

/* Print XML header */
header('Content-Type: text/html; charset='.$hesklang['ENCODING']);

/* Get the search query composed of the subject and message */
$query = hesk_REQUEST('q') or die('');

/* Get relevant articles from the database, include private ones */
$res = hesk_dbQuery("SELECT `id`, `subject`, `content` FROM `".hesk_dbEscape($hesk_settings['db_pfix'])."kb_articles` WHERE `type` IN ('0','1') AND MATCH(`subject`,`content`,`keywords`) AGAINST ('".hesk_dbEscape($query)."') LIMIT ".intval($hesk_settings['kb_search_limit']));
$num = hesk_dbNumRows($res);

/* Solve some spacing issues */
if ( hesk_isREQUEST('p') )
{
	echo '&nbsp;<br />';
}

/* Return found articles */
?>
<div class="notice">
<span style="font-size:12px;font-weight:bold"><?php echo $hesklang['sc']; ?>:</span><br />&nbsp;<br />
    <?php
	if (!$num)
	{
		echo '<i>'.$hesklang['nsfo'].'</i>';
	}
    else
    {
		while ($article = hesk_dbFetchAssoc($res))
		{
			$txt = strip_tags($article['content']);
			if (strlen($txt) > $hesk_settings['kb_substrart'])
			{
				$txt = substr($txt, 0, $hesk_settings['kb_substrart']).'...';
			}

			echo '
			<a href="knowledgebase_private.php?article='.$article['id'].'&amp;suggest=1" target="_blank">'.$article['subject'].'</a>
		    <br />'.$txt.'<br /><br />';
		}
    }
    ?>
</div>
<?php
exit();
?>
