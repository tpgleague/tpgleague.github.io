<?php

require_once '../includes/inc.initialization.php';

if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { define('LID', $_GET['lid']); }


$sql = 'SELECT * FROM leagues WHERE lid = ' . $db->quoteSmart(LID);
$leagueSettings =& $db->getRow($sql);

//$ACCESS = checkPermission('Edit League', 'League', LID);
$ACCESS = TRUE;

$validRosterStatusArray = array('auto', 'unlocked', 'locked');
if ($ACCESS && $_POST['roster_lock']) {
    if (!in_array($_POST['roster_lock'], $validRosterStatusArray)) displayError('Unexpected roster lock value.');
        $rosterLockEdit = new updateRecord('leagues', 'lid', LID);
        $rosterLockEdit->addData(array('roster_lock' => $_POST['roster_lock']));
        $rosterLockEdit->UpdateData();
}

$sql = 'SELECT roster_lock FROM leagues WHERE lid = ?';
$roster_lock =& $db->getOne($sql, array(LID));
$tpl->assign('roster_lock', $roster_lock);

displayTemplate('edit.roster.lock');