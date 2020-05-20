<?php


$cssAppend[] = 'table';
$cssAppend[] = 'manage.roster';
$extra_head[] = <<<JS
<script type="text/javascript">

/***********************************************
* Disable Text Selection script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

function disableSelection(target){
if (typeof target.onselectstart!="undefined") //IE route
	target.onselectstart=function(){return false}
else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
	target.style.MozUserSelect="none"
else //All other route (ie: Opera)
	target.onmousedown=function(){return false}
target.style.cursor = "default"
}
</script>
JS;
require_once '../includes/inc.initialization.support.php';
require_once 'inc.cls-gid.php';

if (!checkNumber($_GET['tid'])) { displayError('Error: Team ID not specified.'); }
else { define('TID', $_GET['tid']); }

$sql = <<<SQL
            SELECT tid, teams.name, teams.lid, organizations.irc, teams.tag, teams.pw, max_schedulers, max_reporters, captain_uid, server_ip, server_port, server_pw, server_location, server_available, hltv_ip, hltv_port, hltv_pw, hltv_public, teams.approved, teams.inactive, teams.deleted, teams.roster_lock,
            UNIX_TIMESTAMP(teams.create_date_gmt) AS create_date_gmt, team_avatar_url
            FROM teams 
            INNER JOIN organizations USING (orgid) 
            INNER JOIN leagues USING (lid) 
            WHERE tid = ? 
            LIMIT 1
SQL;
$teamData =& $db->getRow($sql, array(TID));
$teamData['create_date_gmt'] = smarty_modifier_converted_timezone($teamData['create_date_gmt']);
$lid = $teamData['lid'];
define('LID', $lid);

//$ACCESS = checkPermission('Edit League', 'League', LID);
$ACCESS = TRUE;

// to disable autocomplete form, use random number in form/element id:
$tpl->assign('delete_form_random_number', mt_rand(1, 10000000));

$postDeleteTeamVerify = FALSE;
if ($_POST['submit_delete'] === 'Delete Team') {
    // find the input name, even though it has a random number appended to it:
    $postKeys = array_keys($_POST);
    foreach ($postKeys as $value) {
        if (substr($value, 0, 19) === 'delete_team_verify_') {
            $postDeleteTeamVerify = TRUE;
            $postDeleteTeamVerifyValue = $_POST[$value];
            break;
        }
    }
}

if ($postDeleteTeamVerify === TRUE && $postDeleteTeamVerifyValue === 'Yes, delete this team!' && !$teamData['deleted']) {
    /*
    DELETE team tables (to update):
    teams
    matches
    matches_pending
    rosters!
    */

    // Check if in pending, remove from pending.
    $sql = 'SELECT mpnid FROM matches_pending WHERE tid = ? AND deleted = 0 AND `mid` IS NULL';
    $pendingMatches =& $db->getCol($sql, 0, array(TID));
    foreach ($pendingMatches as $mpnid) {
        $updateRecord = new updateRecord('matches_pending', 'mpnid', $mpnid);
        $updateRecord->addData(array('deleted' => 1));
        $updateRecord->updateData();
    }

    // Find a list of all matches not deleted that this team is playing in. return their opponents.
    $sql = 'SELECT `mid`, sch_id, away_tid, home_tid FROM matches WHERE (away_tid = ? OR home_tid = ?) AND matches.report_date_gmt = "0000-00-00 00:00:00" AND matches.deleted = 0';
    $unreportedMatches =& $db->getAll($sql, array(TID, TID));
    foreach ($unreportedMatches as $match) {
        $opponentTID = ($match['away_tid'] == TID) ? $match['home_tid'] : $match['away_tid'];
        $updateRecord = new updateRecord('matches', 'mid', $match['mid']);
        $updateRecord->addData(array('deleted' => 1));
        $updateRecord->updateData();
        // put their opponent in pending queue.
        if (!empty($opponentTID)) scheduleTeams($match['sch_id'], $opponentTID);
    }

    // remove all players from this roster.
    $sql = 'SELECT rid FROM rosters WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00"';
    $activeRoster =& $db->getCol($sql, 0, array(TID));
    foreach ($activeRoster as $rid) {
        $updateRecord = new updateRecord('rosters', 'rid', $rid);
        $updateRecord->addData(array('leave_date_gmt' => mysqlNow(), 'removed_by_aid' => AID));
        $updateRecord->updateData();
    }

    // finally, mark this team deleted...
    $updateRecord = new updateRecord('teams', 'tid', TID);
    $updateRecord->addData(array('deleted' => 1, 'modify_date_gmt' => gmdate('c', mktime()), 'captain_uid' => NULL));
    $updateRecord->updateData();

    require_once 'inc.func-updateStandings.php';
    $sql = 'SELECT sid, IF(preseason_close_date_gmt IS NULL, 1, 0) AS ps FROM `seasons` WHERE lid = ? AND active = 1 LIMIT 1';
    $seasonRow =& $db->getRow($sql, array(LID));
    $ps = $seasonRow['ps'];
    $sid = $seasonRow['sid'];
    calculateTeamStandings(TID, $sid, $ps);

    $teamData['deleted'] = 1;
}

if (!$teamData['deleted']) {
    $tpl->assign('delete_team_form', TRUE);
}


if (isset($_GET['remove']) && !empty($_GET['rid'])) {
    $rid = $_GET['rid'];
    if (!checkNumber($rid)) displayError('Player to remove not specified.');
    $sql = 'SELECT uid FROM rosters WHERE rid = ? AND tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $onTeamRosterUID =& $db->getOne($sql, array($rid , TID));
    if (!$onTeamRosterUID) displayError('Player to remove not active on this roster.');

    $rosterUpdateArray = array(
                               'rid' => $rid,
                               'leave_date_gmt' => mysqlNow(),
                               'removed_by_aid' => AID
                              );
    $recordUpdate = new updateRecord('rosters', 'rid');
    $recordUpdate->addData($rosterUpdateArray);
    $recordUpdate->UpdateData();

    if ($onTeamRosterUID == $teamData['captain_uid']) {
        $teamDataUpdateArray = array(
                                     'tid' => TID,
                                     'captain_uid' => NULL
                                    );
        $recordUpdate = new updateRecord('teams', 'tid');
        $recordUpdate->addData($teamDataUpdateArray);
        $recordUpdate->UpdateData();
        $teamData['captain_uid'] = NULL;
    }
    unset($recordUpdate);
    redirect('/edit.team.php?tid=' . TID);
}

require_once 'inc.initialization.display.php';








$editTeamForm = new HTML_QuickForm('edit_team_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editTeamForm->removeAttribute('name');
$editTeamForm->applyFilter('__ALL__', 'trim');
//safeDefaults($editTeamForm, $teamData, array('tid', 'pw', 'captain_uid', 'name', 'tag', 'owner_uid', 'team_comments', 'server_ip', 'server_port', 'server_pw', 'server_available', 'hltv_ip', 'hltv_pw', 'hltv_port', 'hltv_public', 'approved', 'inactive' ,'deleted','create_date_gmt','irc'));
$editTeamForm->setDefaults($teamData);
if ($teamData['deleted']) $editTeamForm->freeze();

$editTeamForm->addElement('text', 'tid', 'Team ID');
$editTeamForm->freeze('tid');

$editTeamForm->addElement('text', 'name', 'Team Name', array('maxlength' => 128));
$editTeamForm->addRule('name', 'Your team name is required.', 'required');
$editTeamForm->addRule('name', 'Team name may not exceed 128 characters.', 'maxlength', 128);
$editTeamForm->addRule('name', 'Team name must be at least 3 characters.', 'minlength', 3);

$editTeamForm->addElement('text', 'tag', 'Team Tag', array('maxlength' => 32));
$editTeamForm->addRule('tag', 'A team tag is required.', 'required');
$editTeamForm->addRule('tag', 'Team tag may not exceed 32 characters.', 'maxlength', 32);
$editTeamForm->addRule('tag', 'Team tag must be at least 1 character.', 'minlength', 1);

$editTeamForm->addElement('text', 'pw', 'Join Password', array('maxlength' => 60));
$editTeamForm->addRule('pw', 'A join password is required.', 'required');
$editTeamForm->addRule('pw', 'Join password may not exceed 60 characters.', 'maxlength', 60);

$editTeamForm->addElement('text', 'team_avatar_url', 'Avatar URL');
$editTeamForm->addRule('tag', 'Avatar URL may not exceed 255 characters.', 'maxlength', 255);

$sql = 'SELECT uid, username, firstname, lastname, rosters.handle FROM `rosters` INNER JOIN users USING(uid) WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00"';
$rosterArray =& $db->getAssoc($sql, FALSE, array(TID));
foreach ($rosterArray as $uid => $playerInfo) {
    $newRosterArray[$uid] = "[{$playerInfo['username']}] {$playerInfo['firstname']} \"{$playerInfo['handle']}\" {$playerInfo['lastname']}";
}

$captain_uid =& $editTeamForm->addElement('select', 'captain_uid', 'Captain');
$captain_uid->loadArray(array('0' => '[No captain]'));
$captain_uid->loadArray($newRosterArray);
if (isset($_POST['captain_uid'])) $captain_selected = $_POST['captain_uid'];
else $captain_selected = $teamData['captain_uid'];
$captain_uid->setSelected($captain_selected);


$editTeamForm->addElement('text', 'server_ip', 'Server IP/hostname', array('maxlength' => 255));
$editTeamForm->addRule('server_ip', 'Server IP/hostname cannot exceed 255 characters.', 'maxlength', 255);
$editTeamForm->addRule('server_ip', 'This does not appear to be a valid IP address or hostname.', 'regex', '/^[a-zA-Z0-9]+[a-zA-Z0-9\-.]+$/');

$editTeamForm->addElement('text', 'server_port', 'Server Port', array('maxlength' => 5));
$editTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'numeric');
$editTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'nonzero');
$editTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'maxlength', 5);

$editTeamForm->addElement('text', 'server_pw', 'Server Password', array('maxlength' => 60));
$editTeamForm->addRule('server_pw', 'Server password may not exceed 60 characters.', 'maxlength', 60);
$editTeamForm->addRule('server_pw', 'Server password may only contain letters and numbers.', 'alphanumeric');

$editTeamForm->addElement('text', 'server_location', 'Server Physical Location', array('maxlength' => 64));
$editTeamForm->addElement('static', 'note_server_location', 'E.g., Chicago, Dallas, Atlanta, Seattle, etc.');

$editTeamForm->addElement('text', 'hltv_ip', 'HLTV IP/hostname', array('maxlength' => 255));
$editTeamForm->addRule('hltv_ip', 'Server IP/hostname cannot exceed 255 characters.', 'maxlength', 255);
$editTeamForm->addRule('hltv_ip', 'This does not appear to be a valid IP address or hostname.', 'regex', '/^[a-zA-Z0-9]+[a-zA-Z0-9\-.]+$/');

$editTeamForm->addElement('text', 'hltv_port', 'HLTV Port', array('maxlength' => 5));
$editTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'numeric');
$editTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'nonzero');
$editTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'maxlength', 5);

$editTeamForm->addElement('text', 'hltv_pw', 'HLTV Password', array('maxlength' => 60));
$editTeamForm->addRule('hltv_pw', 'Server password may not exceed 60 characters.', 'maxlength', 60);
$editTeamForm->addRule('hltv_pw', 'Server password may only contain letters and numbers.', 'alphanumeric');

$editTeamForm->addElement('advcheckbox',
                 'server_available',   // name of advcheckbox
                 'Server Available',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('server_available'), array('id' => 'server_available'));
$editTeamForm->addElement('static', 'note_server_available', 'Checkmark this box if this server is available for your next match.');


$editTeamForm->addElement('advcheckbox',
                 'hltv_public',   // name of advcheckbox
                 'HLTV Public',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('hltv_public'), array('id' => 'hltv_public'));
$editTeamForm->addElement('static', 'note_hltv_public', 'Checkmark this box if you want the HLTV info publicized.');




$editTeamForm->addElement('advcheckbox',
                 'approved',   // name of advcheckbox
                 'Approved',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('approved'), array('id' => 'approved'));




$editTeamForm->addElement('advcheckbox',
                 'inactive',   // name of advcheckbox
                 'Inactive',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('inactive'), array('id' => 'inactive'));

$editTeamForm->addElement('advcheckbox',
                 'deleted',   // name of advcheckbox
                 'Deleted',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('deleted'), array('id' => 'deleted'));
$editTeamForm->freeze('deleted');


$editTeamForm->addElement('text', 'create_date_gmt', 'Create Date');
$editTeamForm->freeze('create_date_gmt');

/*
$editTeamForm->addElement('advcheckbox',
                 'roster_lock',   // name of advcheckbox
                 'Lock Roster',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editTeamForm->updateElementAttr(array('roster_lock'), array('id' => 'roster_lock'));
$editTeamForm->freeze('roster_lock');
*/

$teamRosterLock =& $editTeamForm->addElement('select', 'roster_lock', 'Roster Status');
$teamRosterLock->loadArray(getEnumOptions('teams', 'roster_lock'));
$editTeamForm->addRule('roster_lock', 'Required', 'required');

$editTeamForm->addElement('static', 'note_roster_lock', '"Locked" or "Unlocked" <b>always override whatever</b> the league setting is. "Auto" uses the league setting.');

$editTeamForm->addElement('submit', 'submit', 'Save Changes', array('class' => 'submit'));

if ($editTeamForm->validate()) {
    $lid = $teamData['lid'];

    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND name = ? AND tid <> ? AND deleted = 0 LIMIT 1';
    $teamNameExists =& $db->getOne($sql, array($lid, $editTeamForm->exportValue('name'), TID));
    if ($teamNameExists) $editTeamForm->setElementError('name', 'A team by that name already exists in this league.');

    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND tag = ? AND tid <> ? AND deleted = 0 LIMIT 1';
    $teamTagExists =& $db->getOne($sql, array($lid, $editTeamForm->exportValue('tag'), TID));
    if ($teamTagExists) $editTeamForm->setElementError('tag', 'A team using that tag already exists in this league.');

    $sql = 'SELECT count(1) FROM `rosters` WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00"';
    $rosterEmpty =& $db->getOne($sql, array(TID));

    $captainUID = $editTeamForm->exportValue('captain_uid');
    $sql = 'SELECT TRUE FROM `rosters` WHERE uid = ? AND tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $captainOnRoster =& $db->getOne($sql, array($captainUID, TID));
    if ($captainUID == 0) $captainUID = NULL;
    if (!$captainOnRoster && !empty($captainUID)) {
        $editTeamForm->setElementError('captain_uid', 'The captain must be active on the roster.');
        $captainError = TRUE;
    }
    if (empty($captainUID)) $captainUID = NULL;
    

    if (!$teamNameExists && !$teamTagExists && !$captainError) {
        $modifyDate = mysqlNow();
        $valuesArray = array(
                             'tid' => TID,
                             'pw' => $editTeamForm->exportValue('pw'),
                             'team_avatar_url' => $editTeamForm->exportValue('team_avatar_url'),
                             'captain_uid' => $captainUID,
                             'name' => $editTeamForm->exportValue('name'),
                             'tag' => $editTeamForm->exportValue('tag'),
                             'server_ip' => $editTeamForm->exportValue('server_ip'),
                             'server_port' => $editTeamForm->exportValue('server_port'),
                             'server_pw' => $editTeamForm->exportValue('server_pw'),
                             'server_location' => $editTeamForm->exportValue('server_location'),
                             'hltv_ip' => $editTeamForm->exportValue('hltv_ip'),
                             'hltv_port' => $editTeamForm->exportValue('hltv_port'),
                             'hltv_pw' => $editTeamForm->exportValue('hltv_pw'),
                             'server_available' => $editTeamForm->exportValue('server_available'),
                             'modify_date_gmt' => $modifyDate,
                             'approved' => $editTeamForm->exportValue('approved'),
                             'inactive' => $editTeamForm->exportValue('inactive'),
                             'roster_lock' => $editTeamForm->exportValue('roster_lock')
                            );
        $db->autoCommit(FALSE);
        $updateRecord = new updateRecord('teams', 'tid');
        $updateRecord->addData($valuesArray);
        $updateRecord->UpdateData();

        if (($editTeamForm->exportValue('name')     != $teamData['name'])     ||
            ($editTeamForm->exportValue('tag')     != $teamData['tag'])
           ) {
            $sql = 'INSERT INTO teams_names_changes (tid, timestamp_gmt, name, tag) VALUES (!, ?, ?, ?)';
            $db->query($sql, array(TID, $modifyDate, $editTeamForm->exportValue('name'), $editTeamForm->exportValue('tag')));
        }

        if (($editTeamForm->exportValue('approved') != $teamData['approved']) || 
            ($editTeamForm->exportValue('inactive') != $teamData['inactive']) ||
            ($editTeamForm->exportValue('name')     != $teamData['name'])     ||
            ($editTeamForm->exportValue('tag')     != $teamData['tag'])
           ) {
            require_once 'inc.func-updateStandings.php';
            $sql = 'SELECT sid, IF(preseason_close_date_gmt IS NULL, 1, 0) AS ps FROM `seasons` WHERE lid = ? AND active = 1 LIMIT 1';
            $seasonRow =& $db->getRow($sql, array(LID));
            $ps = $seasonRow['ps'];
            $sid = $seasonRow['sid'];
            calculateTeamStandings(TID, $sid, $ps);
        }
        $db->commit();
        $db->autoCommit(TRUE);
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editTeamForm->accept($renderer);
$tpl->assign('edit_team_form', $renderer->toArray());

$tpl->assign('roster_lock_status', checkTeamRosterLock(TID));


$adminNotesForm = new HTML_QuickForm('admin_notes_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$adminNotesForm->removeAttribute('name');
$adminNotesForm->applyFilter('__ALL__', 'trim');
$adminNotesForm->addElement('textarea', 'comment', 'Comment', array('rows' => 5, 'cols' => '50'));
$adminNotesForm->addElement('submit', 'submit', 'Add note');
$adminNotesForm->addRule('comment', 'Please enter a comment.', 'required');
if ($adminNotesForm->validate()) {
    $adminNotesValues = array(
                              'tid' => TID,
                              'aid' => AID,
                              'create_date_gmt' => mysqlNow(),
                              'comment' => $adminNotesForm->exportValue('comment')
                             );
    $res = $db->autoExecute('teams_admin_notes', $adminNotesValues, DB_AUTOQUERY_INSERT);
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$adminNotesForm->accept($renderer);
$tpl->assign('admin_notes_form', $renderer->toArray());

$sql = 'SELECT admin_name, aid, UNIX_TIMESTAMP(teams_admin_notes.create_date_gmt) AS unix_create_date_gmt, `comment` FROM teams_admin_notes INNER JOIN admins USING (aid) WHERE tid = ? ORDER BY tanid DESC';
$adminNotes =& $db->getAll($sql, array(TID));
$tpl->assign('admin_notes', $adminNotes);




$sql = 'SELECT rid, username, email, uid, gid, rosters.handle, permission_reschedule, permission_report, users.firstname, users.hide_lastname, users.lastname FROM rosters INNER JOIN users USING (uid) WHERE tid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00"';
$teamRoster =& $db->getAssoc($sql, NULL, array(TID));
$tpl->assign('team_roster', $teamRoster);


$rosterForm = new HTML_QuickForm('roster_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$rosterForm->removeAttribute('name');
$rosterForm->applyFilter('__ALL__', 'trim');
if ($teamData['deleted']) $rosterForm->freeze();


foreach ($teamRoster as $rid => $player) {

    $gidCls_{$rid} = new gidRoster($rosterForm, LID, $rid);
    $gidName = $gidCls_{$rid}->gidName;
    $tpl->assign('gid_name', $gidName);

    $rosterForm->setDefaults(array(
                                   'permission_reschedule_'.$rid => $player['permission_reschedule'],
                                   'permission_report_'.$rid => $player['permission_report']
                                   ));
    $pname = escape($player['firstname']).' '.escape($player['lastname']);

    $rosterForm->addElement('static', 'player_'.$rid, $pname);
    $rosterForm->addElement('text', 'handle_'.$rid, 'Handle');
    $gidCls_{$rid}->gidForm();

    $rosterForm->setDefaults(array(
                                   'gid_'.$rid => $player['gid'],
                                   'handle_'.$rid => $player['handle']
                            ));
    
    //if ($currentCaptain != $player['uid'] && $teamData['owner_uid'] != $player['uid']) {
        //if ($teamData['max_schedulers'] !== 0) {
            $rosterForm->addElement('advcheckbox',
                         'permission_reschedule_'.$rid,   // name of advcheckbox
                         'Reschedule',  // label output before advcheckbox
                         NULL,           // label output after advcheckbox
                         array('class' => 'checkbox'),      // string or array of attributes
                         array(0,1)
                     );
            $rosterForm->updateElementAttr(array('permission_reschedule_'.$rid), array('id' => 'permission_reschedule_'.$rid));
        //}
        //if ($teamData['max_reporters'] !== 0) {
            $rosterForm->addElement('advcheckbox',
                         'permission_report_'.$rid,   // name of advcheckbox
                         'Report',  // label output before advcheckbox
                         NULL,           // label output after advcheckbox
                         array('class' => 'checkbox'),      // string or array of attributes
                         array(0,1)
                     );
            $rosterForm->updateElementAttr(array('permission_report_'.$rid), array('id' => 'permission_report_'.$rid));
        //}
    //}

}

$rosterForm->addElement('submit', 'submit', 'Save Changes');

if ($rosterForm->validate()) {
    foreach ($teamRoster as $rid => $player) {

                unset($changingGID, $gidCheck, $va, $va2, $gid, $handle, $permission_reschedule, $permission_report);

                //if ($currentCaptain != $player['uid'] && $teamData['owner_uid'] != $player['uid'] && $rosterForm->exportValue('captain_uid') != $player['uid']) {
                    $permission_reschedule = $rosterForm->exportValue('permission_reschedule_'.$rid);
                    $permission_report = $rosterForm->exportValue('permission_report_'.$rid);
                //}
                if (empty($permission_reschedule)) $permission_reschedule = 0;
                if (empty($permission_report)) $permission_report = 0;

                // if current GID differs from submitted value, check if GID already in use in this league.
                $gid = $rosterForm->exportValue('gid_'.$rid);
                $handle = $rosterForm->exportValue('handle_'.$rid);

                $changingGID = (($gid !== $player['gid']) ? TRUE : FALSE);
                if ($changingGID) {
                    $gidCheck = $gidCls_{$rid}->gidInUse($gid);
                } else {
                    $gidCheck = FALSE;
                }

                //$rescheduleCount = $rescheduleCount + $permission_reschedule;
                //$reportCount = $reportCount + $permission_report;

                // update roster id
                $va = array(
                            'rid' => $rid,
                            'permission_report' => $permission_report,
                            'permission_reschedule' => $permission_reschedule,
                            'handle' => $handle
                           );
                if ($changingGID && !$gidCheck) {
                   $va2 = array('gid' => $gid);
                   $va = array_merge($va, $va2);
                }
                $valuesArray[] = $va;

    }

/*
    if ($rescheduleCount > $teamData['max_schedulers']) {
        $tpl->append('roster_form_error', 'You may not assign more than '.$teamData['max_schedulers'].' additional schedulers.');
        $rosterFormError = TRUE;
    }
    if ($reportCount > $teamData['max_reporters']) {
        $tpl->append('roster_form_error', 'You may not assign more than '.$teamData['max_reporters'].' additional match reporters.');
        $rosterFormError = TRUE;
    }
*/

/*
    $captainUID = $rosterForm->exportValue('captain_uid');
    $sql = 'SELECT TRUE FROM `rosters` WHERE uid = ? AND tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $captainOnRoster =& $db->getOne($sql, array($captainUID, TID));
    if (!$captainOnRoster) {
        $rosterFormError = TRUE;
        $tpl->append('roster_form_error', 'The captain must be active on the roster.');
    }
*/

    if (!$rosterFormError && !empty($valuesArray)) {
        foreach ($valuesArray as $array) {
            $recordUpdate = new updateRecord('rosters', 'rid');
            $recordUpdate->addData($array);
            $recordUpdate->UpdateData();
        }
/*
        $recordUpdate = new updateRecord('teams', 'tid');
        $recordUpdate->addData(array('tid' => TID, 'captain_uid' => $captainUID));
        $recordUpdate->UpdateData();
*/

    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$rosterForm->accept($renderer);
$tpl->assign('roster_form', $renderer->toArray());















$sql = <<<SQL
            SELECT CONVERT(`field` USING utf8) AS `field`, from_value, to_value, admin_name, UNIX_TIMESTAMP(timestamp_gmt) AS unix_timestamp_gmt
            FROM admins_action_log
            LEFT JOIN admins USING (aid)
            WHERE tablename = 'teams' AND tablePk = 'tid' AND tablePkId = ?

            UNION

            SELECT 'moved to' AS `field`,
            CONVERT(CONCAT_WS('-', 
                    lgname,
                    IFNULL(division_title, 'Unassigned'),
                    IFNULL(conference_title, 'Unassigned'),
                    IFNULL(group_title, 'Unassigned')
                  ) USING utf8) AS from_value,
            '' AS to_value,
            admin_name, UNIX_TIMESTAMP(teams_divisions_log.timestamp_gmt) AS unix_timestamp_gmt
            FROM `teams_divisions_log`
            LEFT JOIN admins ON (moved_by_aid = admins.aid)
            LEFT JOIN leagues USING (lid)
            LEFT JOIN divisions USING (divid)
            LEFT JOIN conferences USING (cfid)
            LEFT JOIN groups USING (grpid)
            WHERE teams_divisions_log.tid = ?
            ORDER BY unix_timestamp_gmt DESC
SQL;
$teamLog =& $db->getAll($sql, array(TID, TID));
$tpl->assign('team_log', $teamLog);


$sql = <<<SQL
            SELECT uid, username, users.firstname, users.lastname, gid, rosters.handle,
            UNIX_TIMESTAMP(join_date_gmt) AS unix_join_date_gmt, UNIX_TIMESTAMP(leave_date_gmt) AS unix_leave_date_gmt, rosters.*
            FROM rosters
            INNER JOIN users USING (uid)
            WHERE tid = ?
SQL;
$rosterLog =& $db->getAll($sql, array(TID));
$tpl->assign('roster_log', $rosterLog);


$tpl->assign('team_data', $teamData);
displayTemplate('edit.team');