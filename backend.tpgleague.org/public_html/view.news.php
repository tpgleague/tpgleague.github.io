<?php


require_once '../includes/inc.initialization.php';

$lid = $_GET['lid'];

if ($lid == 'all') { $sqllid = 'IS NULL'; }
elseif ($lid == 'main') { $sqllid = '= 0'; }
else {
    if (!checkNumber($lid)) displayError('ERROR: Invalid League ID');
    $sqllid = '= '.$db->quoteSmart($lid);
}

$sql = <<<SQL
            SELECT 
                    newsid, news.title, admins.admin_name, UNIX_TIMESTAMP(news.create_date_gmt) AS create_date_gmt, 
                    news.deleted, news_polls.title AS poll_title, news_polls.nplid, comments_locked
            FROM news 
            INNER JOIN admins ON news.create_by_aid = admins.aid 
            LEFT JOIN news_polls USING (newsid)
            WHERE news.lid $sqllid
            ORDER BY newsid DESC
SQL;
$newsPosts =& $db->getAll($sql);

if ($lid == 'all') {
    $league_title = 'Global (main page + all leagues)';
} elseif ($lid == 'main') {
    $league_title = 'Main page only (no leagues)';
} else {
    $sql = 'SELECT league_title FROM leagues WHERE lid = '.$db->quoteSmart($lid);
    $league_title =& $db->getOne($sql);
}
$tpl->assign('league_title', $league_title);


$tpl->assign('news_posts', $newsPosts);

displayTemplate('view.news');