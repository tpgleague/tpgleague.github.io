<?php

require_once '../includes/inc.initialization.support.php';

if (!checkNumber(@$_GET['suspid'])) { displayError('Error: Suspension ID not specified.'); }
else { @define('SUSPID', @$_GET['suspid']); }

// SQL Constants
$suspensionSql = <<<SQL
    SELECT
        suspensions_list.suspid,
        suspensions_list.deleted,
        suspensions_list.create_date_gmt,
        suspension_added_by_aid,
        suspension_last_edited_by_aid,
        (SELECT admin_name FROM admins WHERE admins.aid = suspensions_list.suspension_added_by_aid LIMIT 1) AS added_admin,
        (SELECT admin_name FROM admins WHERE admins.aid = suspensions_list.suspension_last_edited_by_aid LIMIT 1) AS edited_admin,
        firstname,
        lastname,
        handle,
        reason,
        rule_violation,
        suspension_type as type,
        suspension_date_starts_gmt AS start,
        suspension_date_ends_gmt AS end, 
        tid,
        team_name as team,
        mid,
        suspensions_list.lid,
        leagues.lgname,
        GROUP_CONCAT( DISTINCT sg.gid SEPARATOR ', ' ) AS gids,
        GROUP_CONCAT( DISTINCT su.uid SEPARATOR ', ' ) AS uid,
        stank_ticket_number,
        last_updated_date
    FROM suspensions_list
        LEFT OUTER JOIN leagues ON leagues.lid = suspensions_list.lid 
        LEFT OUTER JOIN suspensions_uids su ON suspensions_list.suspid = su.suspid
        LEFT OUTER JOIN suspensions_gids sg ON suspensions_list.suspid = sg.suspid
    WHERE suspensions_list.suspid = ?
    GROUP BY suspensions_list.suspid
SQL;

$deleteGidsSql = 'DELETE FROM suspensions_gids WHERE suspid = ?';
$deleteUidsSql = 'DELETE FROM suspensions_uids WHERE suspid = ?';

$updateSuspensionSql = <<<SQL
    UPDATE suspensions_list
    SET
        handle = ?,
        tid = ?,
        team_name = ?,
        rule_violation = ?,
        reason = ?,
        suspension_date_starts_gmt = ?,
        suspension_date_ends_gmt = ?,
        suspension_last_edited_by_aid = ?,
        suspension_type = ?,
        deleted = ?,
        firstname = ?,
        lastname = ?,
        lid = ?,
        mid = ?,
        stank_ticket_number = ?,
        last_updated_date = NOW()
    WHERE suspid = ?
SQL;

$editSuspensionForm = new HTML_QuickForm('edit_user_form', 'post', $qfAction);
$editSuspensionForm->removeAttribute('name');
$editSuspensionForm->applyFilter('__ALL__', 'trim');

$formDefaults =& $db->getRow($suspensionSql, array(SUSPID));
$editSuspensionForm->setDefaults($formDefaults);

$editSuspensionForm->addElement('text', 'suspid', 'Suspension ID');
$editSuspensionForm->freeze(array('suspid'));
$editSuspensionForm->addElement('text', 'uid', 'User ID(s) (comma separated)');
$editSuspensionForm->addElement('text', 'handle', 'Handle');
$editSuspensionForm->addElement('text', 'firstname', 'First Name');
$editSuspensionForm->addElement('text', 'lastname', 'Last Name');
$editSuspensionForm->addElement('text', 'reason', 'Reason');
$editSuspensionForm->addRule('reason', 'Required', 'required');
$editSuspensionForm->addElement('text', 'rule_violation', 'Rule Violation Number');
$suspensionType =& $editSuspensionForm->addElement('select', 'type', 'Type');
$suspensionType->loadArray(getEnumOptions('suspensions_list', 'suspension_type'));
$editSuspensionForm->addElement('text', 'start', 'Start Date (format: YYYY-MM-DD HH:MM:SS)');
$editSuspensionForm->addElement('text', 'end', 'End Date (format: YYYY-MM-DD HH:MM:SS) Max: 1/18/38');
$editSuspensionForm->addElement('text', 'tid', 'Team ID (if on team)');
$editSuspensionForm->addElement('text', 'team', 'Team Name (if on team)');
$editSuspensionForm->addElement('text', 'mid', 'Match ID (if occurred in match)');
$editSuspensionForm->addElement('text', 'lid', 'League ID (leave empty for all leagues)');
$editSuspensionForm->addElement('text', 'gids', 'Game ID(s) (comma separated)');
$editSuspensionForm->addElement('text', 'stank_ticket_number', 'Ticket Number');
$editSuspensionForm->addElement('static', 'added_admin', 'Added By');
$editSuspensionForm->addElement('static', 'create_date_gmt', 'Create Date (GMT)');
$editSuspensionForm->addElement('static', 'edited_admin', 'Last Edited By');
$editSuspensionForm->addElement('static', 'last_updated_date', 'Last Updated Date (GMT)');
$editSuspensionForm->addElement('advcheckbox',
                 'deleted',   // name of advcheckbox
                 'Deleted',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editSuspensionForm->updateElementAttr(array('deleted'), array('id' => 'deleted'));
$editSuspensionForm->addElement('static', 'note_deleted', 'Check this box if (and only if) the suspension was made in error.  If you wish end a suspension early then change the end date');

$editSuspensionForm->addElement('submit', 'submit', 'Save Changes');


if ($editSuspensionForm->validate()) {   
    if (strlen($editSuspensionForm->exportValue('team')) > 0)
    {
        $team_name_value = $editSuspensionForm->exportValue('team');
    }
    else
    {
        $team_name_value = "";
    }
    
    if (strlen($editSuspensionForm->exportValue('tid')) > 0)
    {
        $tid_value = $editSuspensionForm->exportValue('tid');
    }
    else
    {
        $tid_value = NULL;
    }

    if (strlen($editSuspensionForm->exportValue('mid')) > 0)
    {
        $mid_value = $editSuspensionForm->exportValue('mid');
    }
    else
    {
        $mid_value = NULL;
    }
    
    if (strlen($editSuspensionForm->exportValue('lid')) > 0)
    {
        $lid_value = $editSuspensionForm->exportValue('lid');
    }
    else
    {
        $lid_value = NULL;
    }
    
    if (strlen($editSuspensionForm->exportValue('gids')) > 0)
    {
        $game_ids = explode(',', $editSuspensionForm->exportValue('gids'));
    }
    else
    {
        $game_ids = NULL;
    }
    
    $user_ids =  explode(',', $editSuspensionForm->exportValue('uid'));

    // Get the suspension ID to be used across all updates/inserts
    $susp_id = $editSuspensionForm->exportValue('suspid');
    
    // Start the transaction
    $db->autoCommit(false);
    
    // Update the main table
    $res =& $db->query($updateSuspensionSql, array($editSuspensionForm->exportValue('handle'),
                                    $tid_value,
                                    $team_name_value,
                                    $editSuspensionForm->exportValue('rule_violation'),
                                    $editSuspensionForm->exportValue('reason'),
                                    $editSuspensionForm->exportValue('start'),
                                    $editSuspensionForm->exportValue('end'),
                                    AID,
                                    $editSuspensionForm->exportValue('type'),
                                    $editSuspensionForm->exportValue('deleted'),
                                    $editSuspensionForm->exportValue('firstname'),
                                    $editSuspensionForm->exportValue('lastname'),
                                    $lid_value,
                                    $mid_value,
                                    $editSuspensionForm->exportValue('stank_ticket_number'),
                                    $susp_id));
                                    
    $res =& $db->query($deleteUidsSql, array($susp_id));
    $res =& $db->query($deleteGidsSql, array($susp_id));
    
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
    
    // Commit or Rollback    
    if (DB::isError($res)) 
    {
        $db->rollback();
    }
    else
    {
        $db->commit();
        redirect('/suspensions.php');
    }
    
    $res->free();
}


$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editSuspensionForm->accept($renderer);
$tpl->assign('edit_suspension_form', $renderer->toArray());

require_once 'inc.initialization.display.php';  
displayTemplate('edit.suspension');