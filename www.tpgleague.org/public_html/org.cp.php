<?php

$pageTitle = 'Edit Organization';
require_once '../includes/inc.initialization.php';
if (!loggedin()) displayError('You must be logged in to use this function.');

define('ORGID', $_GET['orgid']);
if (!checkNumber(ORGID)) { displayError('Organization ID not specified.'); }

$sql = 'SELECT owner_uid, name, website, ccode FROM organizations WHERE orgid = ?';
$orgData =& $db->getRow($sql, array(ORGID));

if ($orgData['owner_uid'] !== UID) displayError('You must be the owner of this organization to access this function.');


$editOrgForm = new HTML_QuickForm('edit_org_form', 'post', $qfAction, NULL, NULL, TRUE);
$editOrgForm->removeAttribute('name');
$editOrgForm->applyFilter('__ALL__', 'trim');
$editOrgForm->setDefaults($orgData);

$editOrgForm->addElement('text', 'name', 'Organization Name', array('maxlength' => 128));
$editOrgForm->addRule('name', 'Your organization name is required.', 'required');
$editOrgForm->addRule('name', 'Organization name may not exceed 128 characters.', 'maxlength', 128);
$editOrgForm->addRule('name', 'Organization name must be at least 3 characters.', 'minlength', 3);
$editOrgForm->registerRule('check_org', 'function', 'checkOrgExistsByUser');
$editOrgForm->addRule('name', 'You already are the owner of an organization by this name.', 'check_org', 'name');
function checkOrgExistsByUser($element,$elementValue)
{
    global $db;
    $sql = "SELECT TRUE FROM organizations WHERE owner_uid = ". UID ." AND orgid <> ". ORGID ." AND name = ?";
    return is_null($db->getOne($sql,$elementValue));
}

$editOrgForm->addElement('text', 'website', 'Website', array('maxlength' => 255));
$editOrgForm->applyFilter('website','clean_http');
function clean_http($s) { 
    return preg_replace('/^(http:\/\/\s*)+/i','',$s); 
}


$ccode =& $editOrgForm->addElement('select', 'ccode', 'Country');
$ccode->loadArray(array(''   => 'Select country',
                        'us' => 'United States of America',
                        'ca' => 'Canada',
                        'gb' => 'United Kingdom'
                  ));
$ccode->loadQuery($db, 'SELECT country, ccode FROM countries WHERE ccode NOT IN ("us", "ca", "gb") ORDER BY country ASC');

$editOrgForm->addElement('submit', 'submit', 'Save Details', array('class' => 'submit'));

if ($editOrgForm->validate()) {
    $editOrgFormValuesArray = array(
                         'name' => $editOrgForm->exportValue('name'),
                         'website' => $editOrgForm->exportValue('website'),
                         'ccode' => $editOrgForm->exportValue('ccode'),
                         'modify_date_gmt' => gmdate('c', mktime())
                        );
    $res = $db->autoExecute('organizations', $editOrgFormValuesArray, DB_AUTOQUERY_UPDATE, 'orgid = ' . ORGID);
    $tpl->assign('edit_org_success', TRUE);
}
//else {
    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $editOrgForm->accept($renderer);
    $tpl->assign('edit_org_form', $renderer->toArray());
//}



$createTeamForm = new HTML_QuickForm('org_create_team_form', 'post', '/create.team.php?orgid='. ORGID, NULL, NULL, TRUE);
$createTeamForm->removeAttribute('name');
$createTeamForm->applyFilter('__ALL__', 'trim');


$lidlist =& $createTeamForm->addElement('select', 'lid', 'League');
$lidlist->loadArray(array(''   => 'Select league to join'));
$lidlist->loadQuery($db, 'SELECT league_title, lid FROM leagues WHERE inactive = 0 ORDER BY league_title ASC');
$createTeamForm->addRule('lid', 'You must choose a league to join.', 'required');
$createTeamForm->addRule('lid', 'You must choose a league to join.', 'nonzero');
$createTeamForm->addRule('lid', 'You must choose a league to join.', 'numeric');


$createTeamForm->addElement('submit', 'submit', 'Submit', array('class' => 'submit'));

/*
if ($createTeamForm->validate()) {

    $db->setErrorHandling(PEAR_ERROR_CALLBACK, 'pearSpecialCharacters');
    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND name = ? LIMIT 1';
    $teamNameExists =& $db->getOne($sql, $createTeamForm->exportValues(array('lid', 'name')));
    if ($teamNameExists) $createTeamForm->setElementError('name', 'A team by that name already exists in this league.');
    if (PEAR::isError($teamNameExists)) {
        $teamNameExists == TRUE;
        $createTeamForm->setElementError('name', 'You have entered invalid characters.');
    }

    $sql = 'SELECT TRUE FROM teams WHERE lid = ? AND tag = ? LIMIT 1';
    $teamTagExists =& $db->getOne($sql, $createTeamForm->exportValues(array('lid', 'tag')));
    if ($teamTagExists) $createTeamForm->setElementError('tag', 'A team using that tag already exists in this league.');
    if (PEAR::isError($teamTagExists)) {
        $teamTagExists == TRUE;
        $createTeamForm->setElementError('tag', 'You have entered invalid characters.');
    }

    $db->setErrorHandling(PEAR_ERROR_CALLBACK, 'pearHandleError');
    if (!$teamNameExists && !$teamTagExists) {
        $createTeamFormValuesArray = array(
                             'name' => $createTeamForm->exportValue('name'),
                             'tag' => $createTeamForm->exportValue('tag'),
                             'orgid' => ORGID,
                             'lid' => $createTeamForm->exportValue('lid'),
                             'create_date_gmt' => gmdate('c', mktime()),
                             'modify_date_gmt' => gmdate('c', mktime())
                            );
        $res = $db->autoExecute('teams', $createTeamFormValuesArray, DB_AUTOQUERY_INSERT);
        $lastInsertTID =& $db->getOne('SELECT LAST_INSERT_ID()');

        $sql = 'INSERT INTO teams_divisions_log (divid, cfid, grpid, tid, lid, moved_by_aid, timestamp_gmt) VALUES (!, !, !, ?, ?, !, ?)';
        $db->query($sql, array('NULL', 'NULL', 'NULL', $lastInsertTID, $createTeamForm->exportValue('lid'), 'NULL', gmdate('c', mktime())));
        $tpl->assign('create_team_success', TRUE);
        clearForm($createTeamForm);
    }
}
*/

$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$createTeamForm->accept($renderer);
$tpl->assign('create_team_form', $renderer->toArray());




$sql = 'SELECT tid, name, tag, approved, teams.inactive, division_title, conference_title, group_title, teams.lid as lid, league_title, lgname FROM teams INNER JOIN leagues ON (teams.lid = leagues.lid) LEFT JOIN divisions ON (teams.divid = divisions.divid) LEFT JOIN conferences ON (teams.cfid = conferences.cfid) LEFT JOIN groups ON (teams.grpid = groups.grpid) WHERE orgid = ? AND teams.deleted = 0 ORDER BY league_title ASC';
$teamList =& $db->getAll($sql, array(ORGID));
$tpl->assign('team_list', $teamList);

displayTemplate('org.cp');

