<?php

$pageTitle = 'Team Management';
require_once '../includes/inc.initialization.php';
if (!loggedin()) displayError('You must be logged in to use this function.');

define('TID', $_GET['tid']);
if (!checkNumber(TID)) { displayError('Team ID not specified.'); }

$sql = 'SELECT owner_uid, captain_uid FROM teams INNER JOIN organizations USING (orgid) WHERE tid = ? AND teams.deleted = 0 LIMIT 1';
$teamData =& $db->getRow($sql, array(TID));
if (empty($teamData)) displayError('Team not found.');

if ($teamData['owner_uid'] !== UID && $teamData['captain_uid'] !== UID) displayError('You must be the captain or owner of this team to access this function.');


displayTemplate('team.cp');