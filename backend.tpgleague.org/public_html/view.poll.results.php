<?php

require_once '../includes/inc.initialization.php';

$nplid = $_GET['nplid'];
if (!checkNumber($nplid)) displayError('Error: Invalid or missing Poll ID.');

$sql = 'SELECT news_polls.*, UNIX_TIMESTAMP(expire_date_gmt) AS expire_date_gmt_unix FROM news_polls WHERE nplid = ?';
$pollInfo =& $db->getRow($sql, array($nplid));
$tpl->assign('poll_info', $pollInfo);

$sql = 'SELECT COUNT(*) AS total FROM news_polls_votes WHERE nplid = ?';
$pollVotes =& $db->getOne($sql, array($nplid));
$tpl->assign('poll_votes', $pollVotes);

$sql = <<<SQL
            SELECT name, COUNT(news_polls_votes.nplchid) AS votes
            FROM news_polls_choices 
            LEFT JOIN news_polls_votes USING (nplchid) 
            WHERE news_polls_choices.nplid = ?
            GROUP BY name
            ORDER BY news_polls_choices.nplchid ASC
SQL;
$pollResults =& $db->getAll($sql, array($nplid));
$tpl->assign('poll_results', $pollResults);

displayTemplate('view.poll.results');