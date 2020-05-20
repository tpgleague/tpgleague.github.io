<?php

require_once '../includes/inc.initialization.php';

$ACCESS = checkPermission('Department', 'Operations');

if (!$ACCESS) { displayerror('You do not have access to this feature.'); }

$addSuspensionForm = new HTML_QuickForm('add_user_form', 'post', $qfAction);
$addSuspensionForm->removeAttribute('name');
$addSuspensionForm->applyFilter('__ALL__', 'trim');

if (checkNumber($_GET['uid']))
{
    $userSql = 'SELECT firstname, lastname, handle FROM users WHERE uid = ?';
    $userInfo =& $db->getRow($userSql, array($_GET['uid']));
    
    $addSuspensionForm->setDefaults(array(
                                        'uid' => $_GET['uid'],
                                        'firstname' => $userInfo['firstname'],
                                        'lastname' => $userInfo['lastname'],
                                        'handle' => $userInfo['handle']
                                    ));
                                    
    //TODO: lookup all roster info
    
    if (checkNumber($_GET['tid']))
    {
        $teamSql = 'SELECT handle, gid, name, lid FROM rosters INNER JOIN teams ON rosters.tid = teams.tid  WHERE uid = ? and rosters.tid = ?';
        $teamInfo =& $db->getRow($teamSql, array($_GET['uid'], $_GET['tid']));
        
        $addSuspensionForm->setDefaults(array(
                                            'tid' => $_GET['tid'],
                                            'gids' => $teamInfo['gid'],
                                            'lid' => $teamInfo['lid'],
                                            'team' => $teamInfo['name'],
                                            'handle' => $teamInfo['handle']
                                        ));
            
        if (checkNumber($_GET['mid']))
        {
            $addSuspensionForm->setDefaults(array('mid' => $_GET['mid']));
        }
    }
}

//TODO: pre-fill start date with today's date

$addSuspensionForm->addElement('text', 'uid', 'User ID');
$addSuspensionForm->addRule('uid', 'Required', 'required');

$addSuspensionForm->addElement('text', 'handle', 'Handle');

$addSuspensionForm->addElement('text', 'firstname', 'First Name');
$addSuspensionForm->addElement('text', 'lastname', 'Last Name');

$addSuspensionForm->addElement('text', 'reason', 'Reason');
$addSuspensionForm->addRule('reason', 'Required', 'required');

$addSuspensionForm->addElement('text', 'rule_violation', 'Rule Violation Number');

$suspensionType =& $addSuspensionForm->addElement('select', 'type', 'Type');
$suspensionType->loadArray(getEnumOptions('suspensions_list', 'suspension_type'));

$addSuspensionForm->addElement('text', 'start', 'Start Date (format: YYYY-MM-DD HH:MM:SS)');
$addSuspensionForm->addElement('text', 'end', 'End Date (format: YYYY-MM-DD HH:MM:SS) Max: 1/18/38');

$addSuspensionForm->addElement('text', 'tid', 'Team ID (if on team)');
$addSuspensionForm->addElement('text', 'team', 'Team Name (if on team)');
$addSuspensionForm->addElement('text', 'mid', 'Match ID (if occurred in match)');
$addSuspensionForm->addElement('text', 'lid', 'League ID (leave empty for all leagues)');
$addSuspensionForm->addElement('text', 'gids', 'Game IDs (comma separated)');
$addSuspensionForm->addElement('text', 'stank_ticket_number', 'Ticket Number');


$addSuspensionForm->addElement('submit', 'submit', 'Save Changes');


if ($addSuspensionForm->validate()) {   
	if (strlen($addSuspensionForm->exportValue('team')) > 0)
	{
		$team_name_value = $addSuspensionForm->exportValue('team');
	}
	else
	{
		$team_name_value = "";
	}
	
	if (strlen($addSuspensionForm->exportValue('tid')) > 0)
	{
		$tid_value = $addSuspensionForm->exportValue('tid');
	}
	else
	{
		$tid_value = NULL;
	}

	if (strlen($addSuspensionForm->exportValue('mid')) > 0)
	{
		$mid_value = $addSuspensionForm->exportValue('mid');
	}
	else
	{
		$mid_value = NULL;
	}
	
	if (strlen($addSuspensionForm->exportValue('lid')) > 0)
	{
		$lid_value = $addSuspensionForm->exportValue('lid');
	}
	else
	{
		$lid_value = NULL;
	}
	
	if (strlen($addSuspensionForm->exportValue('gids')) > 0)
	{
		$game_ids = explode(',', $addSuspensionForm->exportValue('gids'));
	}
	else
	{
		$game_ids = NULL;
	}
	
	$user_ids =  explode(',', $addSuspensionForm->exportValue('uid'));

	
	$sql = 'INSERT INTO suspensions_list (handle, tid, team_name, rule_violation, reason, suspension_added_by_aid, suspension_last_edited_by_aid, suspension_date_ends_gmt, suspension_type, create_date_gmt, firstname, lastname, suspension_date_starts_gmt, mid, lid, stank_ticket_number) '
		 . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)';
	$res =& $db->query($sql, array($addSuspensionForm->exportValue('handle'),
									$tid_value,
									$team_name_value,
									$addSuspensionForm->exportValue('rule_violation'),
									$addSuspensionForm->exportValue('reason'),
									AID,
									AID,
									$addSuspensionForm->exportValue('end'),
									$addSuspensionForm->exportValue('type'),
									$addSuspensionForm->exportValue('firstname'),
									$addSuspensionForm->exportValue('lastname'),
									$addSuspensionForm->exportValue('start'),
									$mid_value,
									$lid_value,
                                    $addSuspensionForm->exportValue('stank_ticket_number')));
									
	$susp_id = $db->getOne('SELECT last_insert_id()');
		
	foreach ($user_ids as &$value) {
		$value = trim($value);
		
		$sql = 'INSERT INTO suspensions_uids (suspid, uid) '
			 . 'VALUES (?, ?)';
		$res =& $db->query($sql, array($susp_id ,$value ));
	}
		
	if ($game_ids != NULL)
	{
		foreach ($game_ids as &$value) {
			$value = trim($value);
			
			$sql = 'INSERT INTO suspensions_gids (suspid, gid) '
				 . 'VALUES (?, ?)';
			$res =& $db->query($sql, array($susp_id ,$value));
		}
	}

	$tpl->assign('success', TRUE);
}

$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$addSuspensionForm->accept($renderer);
$tpl->assign('add_suspension_form', $renderer->toArray());

$existingSuspensionsSql = <<<SQL
    SELECT 
            suspensions.suspid,
            handle,
            suspensions.lid,
            lgname,
            tid,
            team_name,
            suspensions.deleted,
            IF(suspensions.lid IS NULL, "", leagues.gid_name) AS gid_name,
            GROUP_CONCAT( DISTINCT sg.gid SEPARATOR ', ' ) AS `gid`,
            rule_violation,
            reason,
            stank_ticket_number,
            UNIX_TIMESTAMP(suspension_date_ends_gmt) AS end_date, 
            UNIX_TIMESTAMP(suspension_date_starts_gmt) AS start_date 
    FROM suspensions_list AS suspensions 
    LEFT JOIN suspensions_uids su ON suspensions.suspid = su.suspid
    LEFT JOIN suspensions_gids sg ON suspensions.suspid = sg.suspid
    LEFT JOIN leagues ON leagues.lid = suspensions.lid 
    GROUP BY suspensions.suspid
    ORDER BY suspensions.create_date_gmt DESC
SQL;

$existingSuspensions =& $db->getAll($existingSuspensionsSql);
$tpl->assign('existing_suspensions', $existingSuspensions);

displayTemplate('suspensions');