<?php

superAdminPermission();

function superAdminPermission()
{
    global $db;
    $sql = 'SELECT superadmin, users.deleted, admins.inactive FROM admins INNER JOIN users USING (uid) WHERE aid = ? LIMIT 1';
    $adminInfoRow =& $db->getRow($sql, array(AID));
    $superadmin = $adminInfoRow['superadmin'];
    if ($adminInfoRow['inactive'] || $adminInfoRow['deleted']) exit('Your TPG administrator account has been inactivated.');
    define('SUPERADMIN', $superadmin);
}

function upPermissionLevel($permission_level)
{
    $permission_list = array('Group', 'Conference', 'Division', 'League', 'Sitewide');
    $nextKey = array_search($permission_level, $permission_list) + 1;
    if ($nextKey == count($permission_list)) return NULL;
    else return $permission_list[$nextKey];
}

function checkPermission($permission, $permission_level, $levelid=0, $child_access=FALSE)
{
    if (SUPERADMIN) return TRUE;
    
    global $db;

    if ($permission == 'Department')
    {
        $sql = 'SELECT TRUE FROM admins WHERE aid = ? AND department = ? LIMIT 1';
        $deptAccess =& $db->getOne($sql, array(AID, $permission_level));
        return $deptAccess;
    }

    if ($child_access && ($permission_level != 'Sitewide'))
    {
$sql = <<<SQL
            SELECT TRUE
            FROM leagues
            INNER JOIN divisions USING (lid)
            INNER JOIN conferences USING (divid)
            INNER JOIN groups USING (cfid)
            INNER JOIN admins_assignments ON (admins_assignments.pkid = groups.grpid)
            WHERE section = "groups" AND leagues.lid = 5 AND aid = 12
SQL;

    }

    // for use in step 3:
    $original_permission_level = $permission_level;
    $original_levelid = $levelid;

    // 1) SuperAdmin (only add)
    if (SUPERADMIN) return TRUE;

    // 2) Overrides (add or take away)
    $GroupTable = array('table' => 'groups', 'pk' => 'grpid');
    $ConferenceTable = array('table' => 'conferences', 'pk' => 'cfid');
    $DivisionTable = array('table' => 'divisions', 'pk' => 'divid');
    $LeagueTable = array('table' => 'leagues', 'pk' => 'lid');
    $SitewideTable = NULL;

    do {
        $sqlArray = array(AID, $permission, $permission_level, $levelid);
        $sql = 'SELECT permission_flag FROM admins_permissions '
             . 'WHERE aid = ? AND permission = ? AND permission_level = ? AND levelid = ? LIMIT 1';
        $permissionValue =& $db->getOne($sql, $sqlArray);
        if (is_null($permissionValue)) $permissionValue = '';

        $old_permission_level = $permission_level;
        $permission_level = upPermissionLevel($permission_level);

        if (!is_null($permission_level) && ($permission_level != 'Sitewide')) {
            //find the next pk and pkid
            $table = ${$old_permission_level.'Table'}['table'];
            $oldpk = ${$old_permission_level.'Table'}['pk'];
            $pk = ${$permission_level.'Table'}['pk'];

            $sql = 'SELECT '. $pk .' FROM '. $table .' WHERE '. $oldpk .' = '. $levelid .' LIMIT 1';
            $levelid =& $db->getOne($sql);
        } else {
            $levelid = 0;
        }
    }
    while ($permissionValue === '' && !is_null($permission_level));

    if ($permissionValue !== '') return $permissionValue;


    // 3) League/Division/Group Assignments (only add)
    $permission_level = $original_permission_level;
    $levelid = $original_levelid;

    do {

        $next_permission_level = upPermissionLevel($permission_level);

        if (!is_null($permission_level) && ($levelid != 0)  && ($permission_level != 'Sitewide')) {
            //find the next pk and pkid
            $table = ${$permission_level.'Table'}['table'];
            $pk = ${$permission_level.'Table'}['pk'];

            $sql = 'SELECT TRUE FROM admins_assignments WHERE section = ? AND pkid = ? AND aid = ? LIMIT 1';
            $adminAccess =& $db->getOne($sql, array($table, $levelid, AID));

            if (!$adminAccess) {
                // if permission_level is Group, find cfid for the next procedure, etc.:
                $nextpk = ${$next_permission_level.'Table'}['pk'];

                if ($table == 'leagues') $nextpk = '"0"';
                $sql = 'SELECT '. $nextpk .' AS nextpk FROM '. $table .' WHERE '. $pk .' = '. $levelid .' LIMIT 1';
                $levelid =& $db->getOne($sql);
            }

        }
        $permission_level = $next_permission_level;
    }
    while (!$adminAccess && ($levelid != 0) && ($permission_level != 'Sitewide'));

    return ($adminAccess == TRUE);
}
