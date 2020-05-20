<?php

$pageTitle = 'My Teams';
require_once '../includes/inc.initialization.php';
require_once 'inc.cls-gid.php';
if (!loggedin()) displayError('You must be logged in to use this function.');

$sql = 'SELECT abuse_lock FROM users WHERE uid = ?';
$userRow = $db->getRow( $sql, array( UID ) );
define( 'ABUSE_LOCK', (boolean) $userRow['abuse_lock'] );




if (isset($_GET['leave'])) {
        $tid = $_GET['tid'];
        $sql = 'SELECT teams.name AS name, captain_uid, rosters.rid AS rid FROM rosters INNER JOIN teams USING (tid) WHERE tid = ? AND uid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
        $rosterInfo =& $db->getRow($sql, array($tid, UID));
        $teamName = $rosterInfo['name'];
        $rid = $rosterInfo['rid'];
        $captain_uid = $rosterInfo['captain_uid'];

        if ($teamName) {

            $sql = 'SELECT count(1) FROM rosters WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00"';
            $rosterCount =& $db->getOne($sql, array($tid));

            // check if they are the captain
            if ($captain_uid == UID && $rosterCount > 1) {

                // make them choose a new captain

                
                $sql = 'SELECT uid, gid, rosters.handle, users.firstname, users.hide_lastname, users.lastname FROM rosters INNER JOIN users USING (uid) WHERE tid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" AND uid <> ?';
                $potentialCaptains =& $db->getAssoc($sql, NULL, array($tid, UID));
                //$captainPlayerList =& $db->getAll($sql, array($tid, UID));
                $tpl->assign('captain_player_list', $potentialCaptains);

            }

            if ($_GET['confirm'] == 'true') {
                // remove them from team by grabbing the rid and updating it
                /*
                $sql = 'UPDATE rosters SET leave_date_gmt = NOW() WHERE tid = ? AND uid = ? AND leave_date_gmt = "0000-00-00 00:00:00"';
                $db->query($sql, array($tid, UID));
                */

                if (UID == $captain_uid && $rosterCount > 1) {
                    $newCaptainUID = $_POST['new_captain'];
                    //$sql = 'SELECT uid FROM rosters WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" AND uid <> ?';
                    //$potentialCaptains =& $db->getCol($sql, 0, array($tid, UID));
                    if (!array_key_exists($newCaptainUID, $potentialCaptains)) {
                        $removeError = TRUE;
                    } else {
                        $captain_uid = $newCaptainUID;
                    }
                } elseif (UID == $captain_uid) {
                    $captain_uid = NULL;
                }

                if (!$removeError) {
                    $valuesArray = array(
                                        'leave_date_gmt' => mysqlNow(),
                                        'removed_by_uid' => UID
                                        );
                    $recordUpdate = new updateRecord('rosters', 'rid', $rid);
                    $recordUpdate->addData($valuesArray);
                    $recordUpdate->UpdateData();

                    $captainArray = array('captain_uid' => $captain_uid);
                    $recordUpdate = new updateRecord('teams', 'tid', $tid);
                    $recordUpdate->addData($captainArray);
                    $recordUpdate->UpdateData();

                    cpTeams();
                    $tpl->assign('message', 'You have been removed from '.escape($teamName).'.');
                } else {
                    displayError('We encountered an error processing your request.');
                }
            } else {
                $skipForm = TRUE;
                $tpl->assign('confirm_leave_team', array('tid' => $tid, 'name' => $teamName));
            }
        } else {
            // they aren't on the team
            $tpl->assign('message', 'You are not on the team specified.');
        }
        unset($teamName, $rid);
}



if (!$skipForm):


/*
// find if any rosters are locked for this player
$sql = <<<SQL
SELECT rid,

IF(report_date_gmt = "0000-00-00 00:00:00",

IF(
DATE_SUB(
  IF(start_date_gmt < stg_match_date_gmt, start_date_gmt, stg_match_date_gmt),
  INTERVAL roster_lock_hours hour) < NOW(),
TRUE,
FALSE
),

IF(stg_match_date_gmt > NOW(), TRUE, FALSE)

)

AS locked

FROM `matches` INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid) INNER JOIN rosters ON (matches.away_tid = rosters.tid OR matches.home_tid = rosters.tid) WHERE rosters.uid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00" AND matches.deleted = 0 AND schedules.deleted = 0 AND stg_type IN ("Regular", "Playoffs")
HAVING locked = 1
ORDER BY tid ASC, locked ASC
SQL;
$rosterLocks =& $db->getAssoc($sql, FALSE, array(UID));
*/

// $sql = 'SELECT lid FROM suspensions WHERE suspensions.uid = ? AND suspensions.suspension_date_ends_gmt > UTC_TIMESTAMP() AND suspensions.deleted = 0';
$sql = <<<SQL
SELECT lid 
FROM suspensions_list AS suspensions
JOIN suspensions_uids AS su ON suspensions.suspid = su.suspid
WHERE su.uid = ? AND suspensions.suspension_date_ends_gmt > UTC_TIMESTAMP() AND suspensions.deleted = 0
SQL;
$suspendedLeagues =& $db->getCol($sql, 0, array(UID));


// get teams' info
$sql = 'SELECT rid, tid, name, tag, handle, gid, league_title, teams.lid, lgname FROM rosters INNER JOIN teams USING (tid) INNER JOIN leagues ON (teams.lid = leagues.lid) WHERE rosters.uid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00"';
$teams =& $db->getAssoc($sql, FALSE, array(UID));

foreach ($teams as $rid => $teamArray) {
    $rosterLocks[$rid] = checkTeamRosterLock($teamArray['tid']);
}

$myTeamsForm = new HTML_QuickForm('my_teams_form', 'post', '/my.teams.php', NULL, NULL, TRUE);
$myTeamsForm->removeAttribute('name');
$myTeamsForm->applyFilter('__ALL__', 'trim');

foreach ($teams as $rid => $teamInfo) {
    $tpl->append('rosterids', array('rid' => $rid, 'name' => $teamInfo['name'], 'lgname' => $teamInfo['lgname'], 'tid' => $teamInfo['tid']));

    $myTeamsForm->addElement('static', 'league_'.$rid, 'League', '<div class="static" id="league_'.$rid.'">'.$teamInfo['league_title'].'</div>');
    $myTeamsForm->addElement('text', 'handle_'.$rid, 'Handle', array('maxlength' => 30));
    //$myTeamsForm->setDefaults('handle_'.$rid, $teamInfo['handle']);
    $myTeamsForm->addRule('handle_'.$rid, 'Handle may not exceed 30 characters.', 'maxlength', 30);
    if ( ABUSE_LOCK )
    {
        $myTeamsForm->updateElementAttr( array( 'handle_'.$rid ), array( 'disabled' => 'disabled' ) );
        //$myTeamsForm->addElement('static', 'note_handle_'.$rid, 'Disabled due to account lock.');
    }
//|| $teamInfo['lid'] == '1' || $teamInfo['lid'] == '3'
    $locked = ($rosterLocks[$rid] == 'locked' || in_array($teamInfo['lid'], $suspendedLeagues) || in_array(NULL, $suspendedLeagues));

    if ($locked) {
        $myTeamsForm->freeze('handle_'.$rid);
        $status = '<div class="static" style="color: red;" id="status_'.$rid.'">Locked</div>';
    } else {
        $status = '<div class="static" style="color: green;" id="status_'.$rid.'">Unlocked</div>';
    }
    $myTeamsForm->addElement('static', 'status_'.$rid, 'Status', $status);

/////////////////////////// WTF
/////////////////////////// WTF

    $gidCls_{$rid} = new gidRoster($myTeamsForm, $teamInfo['lid'], $rid);
    if ($gidCls_{$rid}->gidRequired) {
        $gidCls_{$rid}->gidForm();
        if ($locked) $myTeamsForm->freeze('gid_'.$rid);
    }

    $myTeamsForm->addElement('static', 'leave_team_'.$rid, '&nbsp;', '<div class="static" id="leave_team_'.$rid.'"><a href="/my.teams.php?leave&amp;tid='.$teamInfo['tid'].'">Leave this team</a></div>');

    $myTeamsForm->setDefaults(array(
                                    'handle_'.$rid => $teamInfo['handle'],
                                    'gid_'.$rid => $teamInfo['gid']

                                    )
                              );
}

$myTeamsForm->addElement('submit', 'submit', 'Save Changes', array('class' => 'submit'));


if ($myTeamsForm->validate()) {

    foreach ($teams as $rid => $teamInfo) {
            unset($handle, $gid, $gidCheck, $changingHandle, $changingGID, $valuesArray);

            // if current GID differs from submitted value, check if GID already in use in this league.
            $gid = $myTeamsForm->exportValue('gid_'.$rid);
            $handle = $myTeamsForm->exportValue('handle_'.$rid);

            $changingHandle = (($handle !== $teamInfo['handle']) ? TRUE : FALSE);

            $changingGID = (($gid !== $teamInfo['gid']) ? TRUE : FALSE);
            if ($changingGID) {
                $gidCheck = $gidCls_{$rid}->gidInUse($gid);
            } else {
                $gidCheck = FALSE;
            }

            if (!$gidCheck && ($changingHandle || $changingGID)) {
                // update roster id
                $valuesArray = array(
                                    'rid' => $rid,
                                    'handle' => ( ABUSE_LOCK ? $teamInfo['handle'] : $handle ),
                                    'gid' => $gid
                                    );
                $recordUpdate = new updateRecord('rosters', 'rid');
                $recordUpdate->addData($valuesArray);
                $recordUpdate->UpdateData();

                cpTeams();
                $tpl->assign('message', 'Your changes were successful.');
            }
    }

}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$myTeamsForm->accept($renderer);
$tpl->assign('my_teams_form', $renderer->toArray());

endif;

unset($_GET['tid']); // a dirty hack against my own dirty-hack code (displayTemplate()).
displayTemplate('my.teams');
