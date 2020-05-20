<?php

$pageTitle = 'Suspensions';
require_once '../includes/inc.initialization.php';

if (defined('LID')) $_GET['lid'] = LID;
if (isset($_GET['lid']) && checkNumber($_GET['lid'])) {
    define('LID', $_GET['lid']);
} else {
    define('LID', 0);
}

$sql = <<<SQL

SELECT 
        handle, tid, team_name, 
        IF(suspensions.lid IS NULL, "", leagues.gid_name) AS gid_name, 
        GROUP_CONCAT( DISTINCT sg.gid SEPARATOR ', ' ) AS `gid`, rule_violation, reason, 
        UNIX_TIMESTAMP(suspension_date_ends_gmt) AS end_date, 
        UNIX_TIMESTAMP(suspension_date_starts_gmt) AS start_date 

FROM suspensions_list AS suspensions 
LEFT JOIN suspensions_uids su ON suspensions.suspid = su.suspid
LEFT JOIN suspensions_gids sg ON suspensions.suspid = sg.suspid
LEFT JOIN leagues ON leagues.lid = suspensions.lid 
WHERE (suspensions.lid = ? OR suspensions.lid IS NULL) AND suspensions.deleted = 0
AND suspensions.suspension_date_ends_gmt > NOW()
GROUP BY suspensions.suspid
ORDER BY suspensions.create_date_gmt DESC

SQL;

$activeSuspensions =& $db->getAll($sql, array(LID));
$tpl->assign('activeSuspensions', $activeSuspensions);

$sql = <<<SQL

SELECT 
        handle, tid, team_name, 
        IF(suspensions.lid IS NULL, "", leagues.gid_name) AS gid_name, 
        GROUP_CONCAT( DISTINCT sg.gid SEPARATOR ', ' ) AS `gid`, rule_violation, reason, 
        UNIX_TIMESTAMP(suspension_date_ends_gmt) AS end_date, 
        UNIX_TIMESTAMP(suspension_date_starts_gmt) AS start_date 

FROM suspensions_list AS suspensions 
LEFT JOIN suspensions_uids su ON suspensions.suspid = su.suspid
LEFT JOIN suspensions_gids sg ON suspensions.suspid = sg.suspid
LEFT JOIN leagues ON leagues.lid = suspensions.lid 
WHERE (suspensions.lid = ? OR suspensions.lid IS NULL) AND suspensions.deleted = 0 
AND suspensions.suspension_date_ends_gmt < NOW()
GROUP BY suspensions.suspid
ORDER BY suspensions.suspension_date_ends_gmt DESC

SQL;

$inactiveSuspensions =& $db->getAll($sql, array(LID));
$tpl->assign('inactiveSuspensions', $inactiveSuspensions);

displayTemplate('suspensions');
