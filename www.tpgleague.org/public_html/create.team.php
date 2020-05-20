<?php



$pageTitle = 'Create Team';
require_once '../includes/inc.initialization.php';
require_once 'inc.cls-gid.php';
if (!loggedin()) displayError('You must be logged in to use this function.');

define('ORGID', $_GET['orgid']);
if (!checkNumber(ORGID)) { displayError('Organization ID not specified.'); }

define('LID', $_POST['lid']);
if (!checkNumber(LID)) { displayError('<p>League ID not specified. <a href="/org.cp.php?orgid='.ORGID.'">Go back and select a league</a>.</p>'); }
$sql = 'SELECT league_title, lgname FROM leagues WHERE lid = ? AND inactive = 0 AND deleted = 0';
$leagueinfo = $db->getRow($sql, array(LID));
$league_title = $leagueinfo['league_title'];
$lgname = $leagueinfo['lgname'];

$tpl->assign('league_title', escape($league_title));
if (!$league_title) { displayError('No such league with that ID exists.'); }

/// email verified???
$sql = 'SELECT email FROM users WHERE uid = ?';
$email =& $db->getOne($sql, array(UID));
if (empty($email)) displayError('You must <a href="/edit.account.php?actedit=enteremailkey">verify your e-mail address</a> before creating a team.');

$sql = 'SELECT owner_uid FROM organizations WHERE orgid = ?';
$orgData =& $db->getRow($sql, array(ORGID));
if ($orgData['owner_uid'] !== UID) displayError('You must be the owner of this organization to access this function.');


/*  you can create teams but not have to join them, duh...

// check if already on a team in this league!
$sql = 'SELECT name FROM rosters INNER JOIN teams USING (tid) WHERE leave_date_gmt = "0000-00-00 00:00:00" AND uid = ? AND lid = ? LIMIT 1';
$onTeamName =& $db->getOne($sql, array(UID, LID));
if ($onTeamName) { displayError('<p>You are already on team '. escape($onTeamName) .' in '. escape($league_title) .' league.</p>  <p>Access <a href="/my.teams.php">my teams</a>.</p>'); }
*/

$createTeamForm = new HTML_QuickForm('create_team_form', 'post', $qfAction, NULL, NULL, TRUE);
$createTeamForm->removeAttribute('name');
$createTeamForm->applyFilter('__ALL__', 'trim');

$createTeamForm->addElement('static', 'league_title', 'League', '<div class="static">'. escape($league_title) .'</div>');

$createTeamForm->addElement('hidden', 'lid', LID);

$createTeamForm->addElement('text', 'name', 'Team Name', array('maxlength' => 128));
$createTeamForm->addRule('name', 'Your team name is required.', 'required');
$createTeamForm->addRule('name', 'Team name may not exceed 128 characters.', 'maxlength', 128);
$createTeamForm->addRule('name', 'Team name must be at least 3 characters.', 'minlength', 3);


$createTeamForm->addElement('text', 'tag', 'Team Tag', array('maxlength' => 32));
$createTeamForm->addRule('tag', 'A team tag is required.', 'required');
$createTeamForm->addRule('tag', 'Team tag may not exceed 32 characters.', 'maxlength', 32);
$createTeamForm->addRule('tag', 'Team tag must be at least 1 character.', 'minlength', 1);

$createTeamForm->addElement('text', 'pw', 'Join Password', array('maxlength' => 60));
$createTeamForm->addRule('pw', 'A join password is required.', 'required');
$createTeamForm->addRule('pw', 'Join password may not exceed 60 characters.', 'maxlength', 60);


$createTeamForm->addElement('text', 'server_ip', 'Server IP/hostname', array('maxlength' => 255));
$createTeamForm->addRule('server_ip', 'Server IP/hostname cannot exceed 255 characters.', 'maxlength', 255);
$createTeamForm->addRule('server_ip', 'This does not appear to be a valid IP address or hostname.', 'regex', '/^[a-zA-Z0-9]+[a-zA-Z0-9\-.]+$/');

$createTeamForm->addElement('text', 'server_port', 'Server Port', array('maxlength' => 5));
$createTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'numeric');
$createTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'nonzero');
$createTeamForm->addRule('server_port', 'Server port is out of range (1-65535)', 'maxlength', 5);

$createTeamForm->addElement('text', 'server_pw', 'Server Password', array('maxlength' => 60));
$createTeamForm->addRule('server_pw', 'Server password may not exceed 60 characters.', 'maxlength', 60);
$createTeamForm->addRule('server_pw', 'Server password may only contain letters and numbers.', 'alphanumeric');

$createTeamForm->addElement('text', 'server_location', 'Server Physical Location', array('maxlength' => 64));
$createTeamForm->addElement('static', 'note_server_location', 'E.g., Chicago, Dallas, Atlanta, Seattle, etc.');

$createTeamForm->addElement('text', 'hltv_ip', 'HLTV IP/hostname', array('maxlength' => 255));
$createTeamForm->addRule('hltv_ip', 'Server IP/hostname cannot exceed 255 characters.', 'maxlength', 255);
$createTeamForm->addRule('hltv_ip', 'This does not appear to be a valid IP address or hostname.', 'regex', '/^[a-zA-Z0-9]+[a-zA-Z0-9\-.]+$/');

$createTeamForm->addElement('text', 'hltv_port', 'HLTV Port', array('maxlength' => 5));
$createTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'numeric');
$createTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'nonzero');
$createTeamForm->addRule('hltv_port', 'Server port is out of range (1-65535)', 'maxlength', 5);

$createTeamForm->addElement('text', 'hltv_pw', 'HLTV Password', array('maxlength' => 60));
$createTeamForm->addRule('hltv_pw', 'Server password may not exceed 60 characters.', 'maxlength', 60);
$createTeamForm->addRule('hltv_pw', 'Server password may only contain letters and numbers.', 'alphanumeric');

$createTeamForm->addElement('advcheckbox',
                 'server_available',   // name of advcheckbox
                 'Server Available',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$createTeamForm->updateElementAttr(array('server_available'), array('id' => 'server_available'));
$createTeamForm->addElement('static', 'note_server_available', 'Checkmark this box if this server is available for your next match.');


$createTeamForm->addElement('advcheckbox',
                 'hltv_public',   // name of advcheckbox
                 'HLTV Public',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$createTeamForm->updateElementAttr(array('hltv_public'), array('id' => 'hltv_public'));
$createTeamForm->addElement('static', 'note_hltv_public', 'Checkmark this box if you want the HLTV info publicized.');

$gidCls = new gidRoster($createTeamForm, LID);
if ($gidCls->gidRequired) {
    $gidCls->gidForm($_POST['add_roster']);
}
$createTeamForm->addElement('advcheckbox',
                 'add_roster',   // name of advcheckbox
                 'Join this roster',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$createTeamForm->updateElementAttr(array('add_roster'), array('id' => 'add_roster'));
$createTeamForm->addElement('static', 'note_add_roster', 'Checkmark this box if you also would like to join this team\'s roster.');

// find player's nickname
$sql = 'SELECT handle FROM users WHERE uid = '. UID;
$defaultHandle =& $db->getOne($sql);
$createTeamForm->setDefaults(array('handle' => $defaultHandle));

$createTeamForm->addElement('text', 'handle', 'Handle/Nickname', array('maxlength' => 60));
$createTeamForm->addRule('handle', 'Handle may not exceed 60 characters.', 'maxlength', 60);



$createTeamForm->addElement('submit', 'submit', 'Create Team', array('class' => 'submit'));









if ($createTeamForm->validate()) {

    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND name = ? AND deleted = 0 LIMIT 1';
    $teamNameExists =& $db->getOne($sql, array(LID, $createTeamForm->exportValue('name')));
    if ($teamNameExists) $createTeamForm->setElementError('name', 'A team by that name already exists in this league.');

    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND tag = ? AND deleted = 0 LIMIT 1';
    $teamTagExists =& $db->getOne($sql, array(LID, $createTeamForm->exportValue('tag')));
    if ($teamTagExists) $createTeamForm->setElementError('tag', 'A team using that tag already exists in this league.');

    if ($createTeamForm->exportValue('add_roster')) { // owner wants to join the team, too. check his shit.
        $sql = 'SELECT TRUE FROM rosters INNER JOIN teams USING (tid) WHERE leave_date_gmt = "0000-00-00 00:00:00" AND rosters.uid = ? AND teams.lid = ? LIMIT 1';
        $alreadyOnTeam =& $db->getOne($sql, array(UID, LID));
        if ($alreadyOnTeam) $createTeamForm->setElementError('add_roster', 'You are already on a team in this league.');

        $gidValue = $createTeamForm->exportValue('gid');
        $gidCheck = $gidCls->gidInUse($gidValue);
    }





    if (!$teamNameExists && !$teamTagExists && !$alreadyOnTeam && !$gidCheck) {

        $createTeamFormCaptainArray = array();
        if ($createTeamForm->exportValue('add_roster')) {
            $createTeamFormCaptainArray = array('captain_uid' => UID);
        }
        $mysqlNow = mysqlNow();
        $createTeamFormValuesArray = array(
                             'name' => $createTeamForm->exportValue('name'),
                             'tag' => $createTeamForm->exportValue('tag'),
                             'pw' => $createTeamForm->exportValue('pw'),
                             'orgid' => ORGID,
                             'lid' => LID,
                             'server_ip' => $createTeamForm->exportValue('server_ip'),
                             'server_port' => $createTeamForm->exportValue('server_port'),
                             'server_location' => $createTeamForm->exportValue('server_location'),
                             'server_pw' => $createTeamForm->exportValue('server_pw'),
                             'hltv_ip' => $createTeamForm->exportValue('hltv_ip'),
                             'hltv_port' => $createTeamForm->exportValue('hltv_port'),
                             'hltv_pw' => $createTeamForm->exportValue('hltv_pw'),
                             'server_available' => $createTeamForm->exportValue('server_available'),
                             'create_date_gmt' => $mysqlNow,
                             'modify_date_gmt' => $mysqlNow
                            );
        $createTeamFormInsertArray = array_merge($createTeamFormValuesArray, $createTeamFormCaptainArray);
        $db->autoCommit(FALSE);
        $res = $db->autoExecute('teams', $createTeamFormInsertArray, DB_AUTOQUERY_INSERT);
        $lastInsertTID =& $db->getOne('SELECT LAST_INSERT_ID()');

        if (!$lastInsertTID) {
            $db->rollback();
            $db->autoCommit(TRUE);
            displayError('The server has encountered an error processing your request. Please go back to the previous page and retry your request.');
        }

        if ($createTeamForm->exportValue('add_roster')) {
            $createTeamFormRosterArray = array(
                                                'gid' => $createTeamForm->exportValue('gid'),
                                                'handle' => $createTeamForm->exportValue('handle'),
                                                'uid' => UID,
                                                'tid' => $lastInsertTID
                                              );
            $res = $db->autoExecute('rosters', $createTeamFormRosterArray, DB_AUTOQUERY_INSERT);
            cpTeams();
        }

        $sql = 'INSERT INTO teams_divisions_log (divid, cfid, grpid, tid, lid, moved_by_aid, timestamp_gmt) VALUES (!, !, !, ?, ?, 0, ?)';
        $db->query($sql, array('NULL', 'NULL', 'NULL', $lastInsertTID, $createTeamForm->exportValue('lid'), $mysqlNow));

        $sql = 'INSERT INTO teams_names_changes (tid, timestamp_gmt, name, tag) VALUES (!, ?, ?, ?)';
        $db->query($sql, array($lastInsertTID, $mysqlNow, $createTeamForm->exportValue('name'), $createTeamForm->exportValue('tag')));

        $db->commit();
        $db->autoCommit(TRUE);

        $tpl->assign('create_team_success', array(
                                                  'lgname' => $lgname,
                                                  'tid' => $lastInsertTID,
                                                  'name' => $createTeamForm->exportValue('name'),
                                                  'pw' => $createTeamForm->exportValue('pw')
                                                  ));
        clearForm($createTeamForm);
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$createTeamForm->accept($renderer);
$tpl->assign('create_team_form', $renderer->toArray());

displayTemplate('create.team');