<?php

$cssAppend[] = 'admin.permissions';
//$jsAppend[] = 'admin.permissions';
require_once '../includes/inc.initialization.php';



//if (!SUPERADMIN) displayError('You are not authorized to access this page.');








// HARD-CODED FOR NOW:
$permission_aid = '1';  // the AID of the admin we are trying to edit
$permission = 'Edit League';  // the permission type



$levelsDeep = array('Group', 'Conference', 'Division', 'League', 'Sitewide');

// find a set of permissions the admin is allowed to edit for the $aid to begin with.
// 1) remove the <img> and <input> tags for any values he isn't, 2) remove those values from the posted input fields if they exist
//$allowedEdit =& $db->getAll('SELECT ...'); <-- not quite so simple!

if (isset($_POST['permission_type']) && $_POST['permission_aid'] == $permission_aid) {
    foreach ($_POST as $key => $value) {
        $pieces = explode('_', $key);  // expecting format e.g.: "perm_League_3"
        if ($pieces[0] == 'perm' && in_array($pieces[1], $levelsDeep) && checkNumber($pieces[2]) && in_array($value, array('1', '0', 'NULL'))) {
            $permData[] = array($permission_aid, $permission, $pieces[1], $pieces[2], $value, $value);
        }
    }
    $sth = $db->prepare('INSERT INTO admins_permissions (aid, permission, permission_level, levelid, permission_flag) VALUES (?, ?, ?, ?, !) ON DUPLICATE KEY UPDATE permission_flag = !');
    $db->executeMultiple($sth, $permData);
    $db->freePrepared($sth);
}


$tpl->assign('form_permission_aid', $permission_aid);
$tpl->assign('form_permission_type', $permission);


// Find out what permissions the admin already has:
$permissions =& $db->getAssoc('SELECT CONCAT(permission_level, "_", levelid) as permission, IFNULL(permission_flag, "NULL") as permission_flag FROM admins_permissions WHERE aid = ' . $permission_aid . ' AND permission = "' . $permission . '"');

// How many levels we can possibly go with this permission type:
$permissionLevel =& $db->getOne('SELECT level FROM admins_permissions_meta WHERE permission = "' . $permission .'"');
$levelNumber = array_search($permissionLevel, $levelsDeep);  // $levelsDeep is used at the top of the script also.


function populatePermissionValues ($permission, $id)
{
    global $permissions, $presetValues;
    if (array_key_exists($permission.'_'.$id, $permissions)) $presetValues[$permission.'_'.$id] =& $permissions[$permission.'_'.$id];
    else $presetValues[$permission.'_'.$id] = 'NULL';
}


// Populate the preset form values for the Sitewide input:
populatePermissionValues('Sitewide', 0);


// make list of leagues / divisions / conferences / groups and populate the values:
if ($levelNumber <= array_search('League', $levelsDeep)) {
    $leagues =& $db->getAssoc('SELECT lid, league_title FROM leagues', TRUE);
    $tpl->assign('leagues', $leagues);
    foreach ($leagues as $lid => $array) {
        populatePermissionValues('League', $lid);
    }
}
if ($levelNumber <= array_search('Division', $levelsDeep)) {
    $divisions =& $db->getAssoc('SELECT lid, divid, division_title FROM divisions', NULL, NULL, NULL, TRUE);
    $tpl->assign('divisions', $divisions);
    foreach ($divisions as $key => $array) {
        foreach ($array as $key => $array2) {
            populatePermissionValues('Division', $array2['divid']);
        }
    }
}
if ($levelNumber <= array_search('Conference', $levelsDeep)) {
    $conferences =& $db->getAssoc('SELECT divid, cfid, conference_title FROM conferences', NULL, NULL, NULL, TRUE);
    $tpl->assign('conferences', $conferences);
    foreach ($conferences as $key => $array) {
        foreach ($array as $key => $array2) {
            populatePermissionValues('Conference', $array2['cfid']);
        }
    }
}
if ($levelNumber == array_search('Group', $levelsDeep)) {
    $groups =& $db->getAssoc('SELECT cfid, grpid, group_title FROM groups', NULL, NULL, NULL, TRUE);
    $tpl->assign('groups', $groups);
    foreach ($groups as $key => $array) {
        foreach ($array as $key => $array2) {
            populatePermissionValues('Group', $array2['grpid']);
        }
    }
}

$tpl->assign('preset_values', $presetValues);

displayTemplate('admin.permissions');

