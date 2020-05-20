<?php

$pageTitle = 'Map Downloads';
require_once '../includes/inc.initialization.php';

if (!defined('LID'))
{
    define('LID', $_GET['lid']);
}

// find the current active season
$sql = 'SELECT sid, season_number, season_title FROM seasons WHERE lid = ? AND active = 1 LIMIT 1';
$seasonData =& $db->getRow($sql, array(LID));
define('SID', $seasonData['sid']);
$tpl->assign('season_number', $seasonData['season_number']);
$tpl->assign('season_title', $seasonData['season_title']);

$sql = 'SELECT league_title FROM leagues WHERE lid = ' . $db->quoteSmart(LID);
$leagueData = $db->getOne($sql);
$tpl->assign('league_title', $leagueData['league_title']);

$sql = <<<SQL
    SELECT DISTINCT maps.map_title, maps.filename
    FROM schedules
        LEFT JOIN maps USING (mapid)
    WHERE schedules.deleted = 0 AND sid = ? and maps.filename <> ''
SQL;
$mapsList =& $db->getAll($sql, array(SID));
$tpl->assign('maps_list', $mapsList);

displayTemplate('maps', NULL, NULL, TRUE);