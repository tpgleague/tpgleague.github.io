<?php

$pageTitle = 'News';

require_once('../includes/inc.initialization.php');

// The current league ID the player is in
if (isset($_GET['lid']) && checkNumber($_GET['lid'])) {
    define('LID', $_GET['lid']);
} else {
    define('LID', 0);
}
$tpl->assign('league_id', LID);

$newsArticlesPerPage = 20;
$tpl->assign('news_articles_per_page', $newsArticlesPerPage);
if (isset($_GET['page']) && checkNumber($_GET['page']) && $_GET['page'] != 0) {
    define('PAGE', $_GET['page']);
    $offset = (PAGE - 1) * $newsArticlesPerPage;
} else {
    define('PAGE', 1);
    $offset = 0;
}


if (isset($_POST['poll_number']) && checkNumber($_POST['poll_choice'])) {
    include_once 'inc.func-poll.php';
    $poll = new poll($_POST['poll_number']);
    if (!$poll->pollVoted()) $poll->pollVote($_POST['poll_choice']);
    unset($poll);
}



$cache_id = LID .'|'. PAGE;
if (!$tpl->is_cached('index.tpl', $cache_id)):

$lid = LID;
if ($lid) {
    $sql = 'SELECT create_date_gmt FROM leagues WHERE lid = '.$lid;
    $leagueCreateDateGmt =& $db->getOne($sql);
$sql = <<<SQL
            SELECT SQL_CALC_FOUND_ROWS (select count(1) from news_comments inner join comments on comments.cmt_id = news_comments.cmt_id where newsid = news.newsid and comments.deleted = 0) as number_of_comments, newsid, news.title, news.body, UNIX_TIMESTAMP(news.create_date_gmt) AS timestamp, admin_name AS author, nplid
            FROM news 
            LEFT JOIN admins ON news.create_by_aid = admins.aid 
            LEFT JOIN news_polls USING (newsid) 
            LEFT JOIN leagues ON (leagues.lid = news.lid)
            WHERE (news.lid = $lid OR news.lid IS NULL) AND news.deleted = 0 AND news.create_date_gmt >= '$leagueCreateDateGmt'
            ORDER BY news.create_date_gmt DESC
            LIMIT $newsArticlesPerPage OFFSET $offset
SQL;
} else {
$sql = <<<SQL
            SELECT SQL_CALC_FOUND_ROWS (select count(1) from news_comments inner join comments on comments.cmt_id = news_comments.cmt_id where newsid = news.newsid and comments.deleted = 0) as number_of_comments, newsid, news.title, body, UNIX_TIMESTAMP(news.create_date_gmt) AS timestamp, admin_name AS author, nplid
            FROM news 
            LEFT JOIN admins ON news.create_by_aid = admins.aid 
            LEFT JOIN news_polls USING (newsid) 
            WHERE (news.lid = $lid OR news.lid IS NULL) AND news.deleted = 0
            ORDER BY news.create_date_gmt DESC
            LIMIT $newsArticlesPerPage OFFSET $offset
SQL;
}
$newsArray =& $db->getAll($sql);
$newsArticlesTotal =& $db->getOne('SELECT FOUND_ROWS()');
$tpl->assign('news_articles_total', $newsArticlesTotal);
$newsArticlesMaxPages = ceil($newsArticlesTotal / $newsArticlesPerPage);
$tpl->assign('news_articles_max_pages', $newsArticlesMaxPages);
$tpl->assign('news_data', $newsArray);

foreach ($newsArray as $newsData) {
    if ($newsData['nplid']) {
        include_once 'inc.func-poll.php';
        $poll = new poll($newsData['nplid']);
        if ($poll->pollVoted()) $poll->pollGraph();
        else $poll->pollOptions();
    }
}

endif;

displayTemplate('index', $cache_id, 0, TRUE);

