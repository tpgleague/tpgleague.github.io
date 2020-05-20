<?php

require_once '../includes/inc.initialization.support.php';

if (!empty($_POST)) {
    foreach ($_POST as $tid => $value) {
        if (checkNumber($tid)) {
            $approveTeam = new updateRecord('teams', 'tid', $tid);
            $approveTeam->addData(array('approved' => 1));
            $approveTeam->UpdateData();
        }
    }
}

require_once '../includes/inc.initialization.display.php';

$sql = <<<SQL
            SELECT teams.tid, teams.name, teams.tag, leagues.lgname, leagues.lid, captain_uid, owner_uid, unix_timestamp(teams.create_date_gmt) AS unix_create_date_gmt
            FROM teams 
            INNER JOIN leagues USING (lid) 
            INNER JOIN organizations USING (orgid)
            WHERE teams.approved = 0 AND teams.deleted = 0
SQL;
$teamsPendingApproval =& $db->getAll($sql);
$tpl->assign('teams_pending_approval', $teamsPendingApproval);

foreach ($teamsPendingApproval as $teamArray) {
    $sql = 'SELECT uid, username, firstname, lastname, handle, email FROM users WHERE uid = ? LIMIT 1';
    $teamContactArray[$teamArray['tid']]['owner'] =& $db->getRow($sql, array($teamArray['owner_uid']));
    $teamContactArray[$teamArray['tid']]['captain'] =& $db->getRow($sql, array($teamArray['captain_uid']));
}
$tpl->assign('team_contact', $teamContactArray);



displayTemplate('pending.approval');