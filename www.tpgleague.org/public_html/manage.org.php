<?php

$pageTitle = 'Manage Organizations';
require_once '../includes/inc.initialization.php';
if (!loggedin()) displayError('You must be logged in to use this function.');

$sql = 'SELECT orgid, name FROM organizations WHERE owner_uid = ? AND inactive = 0';
$organizations = $db->getAll($sql, array(UID));

$tpl->assign('organizations', $organizations);

displayTemplate('manage.org');