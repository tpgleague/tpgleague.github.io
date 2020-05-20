<?php

$pageTitle = 'Join Team';
require_once '../includes/inc.initialization.php';
require_once 'inc.cls-gid.php';

if (!loggedin()) displayError('You must be logged in to use this function.');

/// email verified???
$sql = 'SELECT email FROM users WHERE uid = ?';
$email =& $db->getOne($sql, array(UID));
if (empty($email)) displayError('You must <a href="/edit.account.php?actedit=enteremailkey">verify your e-mail address</a> before joining a team.');

$leagueAlreadySet = 0;
if (isset($_POST['select_lid']))
{
    define('SELECT_LID', $_POST['select_lid']);
}
else if (LID > 0)
{
    define('SELECT_LID', LID);
    $leagueAlreadySet = 1;
}

if (isset($_POST['select_lid']) || $leagueAlreadySet = 1) {
    $sql = 'SELECT league_title, lgname FROM leagues WHERE lid = ? AND inactive = 0 AND deleted = 0';
    $leagueinfo = $db->getRow($sql, array(SELECT_LID));
    $league_title = $leagueinfo['league_title'];
    $lgname = $leagueinfo['lgname'];
}


// for *BOTH* forms, must check if player is already on a team in the league
$sql = 'SELECT TRUE FROM rosters INNER JOIN teams USING (tid) WHERE leave_date_gmt = "0000-00-00 00:00:00" AND rosters.uid = ? AND teams.lid = ? LIMIT 1';
$alreadyOnTeam =& $db->getOne($sql, array(UID, SELECT_LID));


if ($league_title && !$alreadyOnTeam)
{
    // Get all game IDs used
    $gameIdsSql = <<<SQL
        SELECT DISTINCT gid
        FROM rosters 
            LEFT JOIN teams USING (tid) 
            LEFT JOIN leagues USING (lid)
        WHERE leagues.inactive = 0 AND leagues.deleted = 0
            AND teams.approved = 1 AND  uid = ?
SQL;
    $gameIdsData =& $db->getAll($gameIdsSql, array(UID));
    
    if ($gameIdsData && count($gameIdsData) > 0)
    {
    
    $extra_head[] = <<<JS
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
 <script>
$(function() {
var availableTags = [
JS;

    foreach($gameIdsData as $value)
    {
       $extra_head[] .= '"' . $value['gid'] . '",'; 
    }
    
    if (substr($extra_head, -1) == ',')
    {
        $extra_head[] = substr($extra_head, 0, -1);
    }

$extra_head[] .= '];
$( "#gid" ).autocomplete({
source: availableTags
});
});
</script>';
    $tpl->assign('extra_head', $extra_head);
    }
    
    $joinTeamForm = new HTML_QuickForm('join_team_form', 'post', $qfAction, NULL, NULL, TRUE);
    $joinTeamForm->removeAttribute('name');
    $joinTeamForm->applyFilter('__ALL__', 'trim');
   
    // find player's nickname
    $sql = 'SELECT handle FROM users WHERE uid = '. UID;
    $defaultHandle =& $db->getOne($sql);
    $joinTeamForm->setDefaults(array('handle' => $defaultHandle));

    $gidCls = new gidRoster($joinTeamForm, SELECT_LID);
    if ($gidCls->gidRequired) {
        $gidCls->gidForm();
    }

    if (LID <> SELECT_LID)
    {
        $joinTeamForm->addElement('text', 'note_lid', 'League');
        $joinTeamForm->setDefaults(array('note_lid' => $league_title));
        $joinTeamForm->freeze('note_lid');
    }
    
    $joinTeamForm->addElement('text', 'pw', 'Join Password', array('maxlength' => 30));
    $joinTeamForm->addRule('pw', 'A join password is required.', 'required');
    $joinTeamForm->addRule('pw', 'Join password may not exceed 30 characters.', 'maxlength', 30);

    $sql = 'SELECT name, tid FROM teams WHERE lid = '. $db->quoteSmart(SELECT_LID) .' AND deleted = 0 AND approved = 1 ORDER BY name ASC';
    $teamlist =& $joinTeamForm->addElement('select', 'teamname', 'Team Name');
    $teamlist->loadArray(array(''   => 'Select team to join'));
    $teamlist->loadQuery($db, $sql);

    $joinTeamForm->addElement('text', 'teamid', 'Team ID');
    $joinTeamForm->addElement('static', 'note_teamid', '(Team ID is optional.)');
    
    if (isset($_GET['tid']))
    {
        $joinTeamForm->setDefaults(array('teamid' => $_GET['tid']));
        $joinTeamForm->setDefaults(array('teamname' => $_GET['tid']));
    }

    $joinTeamForm->addElement('hidden', 'select_lid');
    $joinTeamForm->setConstants(array('select_lid' => SELECT_LID));

    $joinTeamForm->addElement('text', 'handle', 'Handle/Nickname', array('maxlength' => 30));
    $joinTeamForm->addRule('handle', 'Handle may not exceed 30 characters.', 'maxlength', 30);

    $joinTeamForm->addElement('submit', 'submit', 'Join Team', array('class' => 'submit'));

    
    if ($alreadyOnTeam) {
        $tpl->assign('join_team_form', array('error' => array('select_lid' => 'You are already on a team in this league.')));
        $joinTeamForm->setElementError('select_lid', 'You are already on a team in this league.');
    }

    if ($joinTeamForm->validate() && !$alreadyOnTeam) {

        // check if GID already in use in this league.
        $gidValue = $joinTeamForm->exportValue('gid');
        $gidCheck = $gidCls->gidInUse($gidValue);

        // find team id trying to join
        $teamid = $joinTeamForm->exportValue('teamid');
        $teamnameid = $joinTeamForm->exportValue('teamname');

        if (checkNumber($teamnameid) && ($teamnameid > 0))
        {
            $tid = $teamnameid;
        }
        elseif (checkNumber($teamid) && ($teamid > 0))
        {
            $tid = $teamid;
        }
        else
        {
            $tid = FALSE;
            $joinTeamForm->setElementError('teamname', 'Please select a team.');
        }

        // check if team exists
        if (checkNumber($tid)) {
            $sql = 'SELECT TRUE FROM teams WHERE deleted = 0 AND tid = ? AND lid = ?';
            $teamExists =& $db->getOne($sql, array($tid, SELECT_LID));
        }
        if ($tid && $teamExists) {
            // check if password is correct for team id
            $pw = $joinTeamForm->exportValue('pw');
            $sql = 'SELECT name FROM teams WHERE tid = ? AND pw = ?';
            $correctName =& $db->getOne($sql, array($tid, $pw));
            if (!$correctName) {
                // tell them password is wrong
                $joinTeamForm->setElementError('pw', 'Join password is incorrect.');
            }

            // check if roster is locked for this team
/*
$sql = <<<SQL
SELECT

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

FROM `matches` INNER JOIN schedules USING (sch_id) INNER JOIN seasons USING (sid) WHERE (away_tid = ? OR home_tid = ?) AND matches.deleted = 0 AND schedules.deleted = 0 AND stg_type IN ("Regular", "Playoffs")

HAVING locked = TRUE
LIMIT 1
SQL;
            $rosterLocked =& $db->getOne($sql, array($tid, $tid));
*/
            $rosterLocked = checkTeamRosterLock($tid) == 'locked' ? TRUE : FALSE;
            $tpl->assign('roster_locked', $rosterLocked);

        } elseif ($teamid) {
            // tell them team doesn't exist
            $joinTeamForm->setElementError('teamid', 'No such team ID exists.');
        }

        if (!$gidCheck && $teamExists && $tid && $correctName && !$rosterLocked) {
            // add to roster
            $valuesArray = array(
                                'uid' => UID,
                                'tid' => $tid,
                                'join_date_gmt' => gmdate('c', mktime()),
                                'leave_date_gmt' => '0000-00-00 00:00:00',
                                'handle' => $joinTeamForm->exportValue('handle'),
                                'gid' => $gidValue
                                );
            $res = $db->autoExecute('rosters', $valuesArray, DB_AUTOQUERY_INSERT);
            // if teams.captain_uid is NULL, add make the player a captain
            $db->query('UPDATE teams SET captain_uid = ? WHERE tid = ? AND captain_uid IS NULL', array(UID, $tid));
            cpTeams();
            $tpl->assign('success_team_info', array('name' => $correctName, 'lgname' => $lgname, 'tid' => $tid));
        } else {
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
            $joinTeamForm->accept($renderer);
            $tpl->assign('join_team_form', $renderer->toArray());
        }
    } else {
        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
        $joinTeamForm->accept($renderer);
        $tpl->assign('join_team_form', $renderer->toArray());
    }

}
else {

    $selectLeagueForm = new HTML_QuickForm('select_league_form', 'post', $qfAction, NULL, NULL, TRUE);
    $selectLeagueForm->removeAttribute('name');
    $selectLeagueForm->applyFilter('__ALL__', 'trim');

    $lidlist =& $selectLeagueForm->addElement('select', 'select_lid', 'League');
    $lidlist->loadArray(array(''   => 'Select league to join'));
    $lidlist->loadQuery($db, 'SELECT league_title, lid FROM leagues WHERE inactive = 0 ORDER BY league_title ASC');
    $selectLeagueForm->addRule('select_lid', 'You must choose a league to join.', 'required');
    $selectLeagueForm->addRule('select_lid', 'You must choose a league to join.', 'nonzero');
    $selectLeagueForm->addRule('select_lid', 'You must choose a league to join.', 'numeric');
    
    $selectLeagueForm->addElement('submit', 'submit', 'Select League', array('class' => 'submit'));

    if ($alreadyOnTeam) {
        $tpl->assign('select_league_form', array('error' => array('select_lid' => 'You are already on a team in ' . $league_title . '.  Click "My Teams" in your control panel to leave your current team or choose a different league to join below.')));
        $selectLeagueForm->setElementError('select_lid', 'You are already on a team in ' . $league_title . '.  Click "My Teams" in your control panel to leave your current team or choose a different league to join below.');
    }

    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $selectLeagueForm->accept($renderer);
    $tpl->assign('select_league_form', $renderer->toArray());
}


displayTemplate('join.team');


echo '<!-- ';
print_r($leagueinfo);
echo ' -->';
