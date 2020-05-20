<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once '../includes/inc.db.php';

$sql = 'SELECT url FROM affiliate_links WHERE afid = ? LIMIT 1';
$url =& $db->getOne($sql, array($_GET['afid']));

if (empty($url)) {
    if (!empty($_SERVER['HTTP_REFERER'])) $url = $_SERVER['HTTP_REFERER'];
    else $url = 'http://www.tpgleague.org/';
} else {
    $sql = 'INSERT INTO affiliate_clickthroughs (afid, address) VALUES (?, INET_ATON(?))';
    $db->query($sql, array($_GET['afid'], $_SERVER['REMOTE_ADDR']));
    $url = 'http://'.$url;
}

header('Location: '.$url);