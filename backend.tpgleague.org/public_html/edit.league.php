<?php

require_once '../includes/inc.initialization.php';

if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { define('LID', $_GET['lid']); }

$ACCESS = SUPERADMIN;


$sql = 'SELECT * FROM leagues WHERE lid = ?';
$leagueSettings =& $db->getRow($sql, array(LID));
$tpl->assign('roster_lock', $leagueSettings['roster_lock']);


$editLeagueForm = new HTML_QuickForm('edit_league_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$editLeagueForm->removeAttribute('name'); // XHTML compliance
$editLeagueForm->applyFilter('__ALL__', 'trim');

$editLeagueForm->setDefaults($leagueSettings);
$editLeagueForm->setConstants(array('lid' => LID));

$editLeagueForm->addElement('hidden', 'lid', LID);
$editLeagueForm->addElement('text', 'league_title', 'Title');


$editLeagueForm->addElement('text', 'lgname', 'Short Name', array('maxlength' => 8, 'onkeyup' => 'javascript: document.getElementById("lgname_span").innerHTML=document.getElementById("lgname").value.toLowerCase()'));
$lgname = (isset($_POST['lgname'])) ? $_POST['lgname'] : $leagueSettings['lgname'];
if (empty($lgname)) $lgname = 'name';
$editLeagueForm->addElement('static', 'note_lgname', 'http://www.tpgleague.org/<span id="lgname_span" style="font-style: italic;">'.$lgname.'</span>/');
$editLeagueForm->addRule('lgname', 'Short Name is required.', 'required');
$editLeagueForm->addRule('lgname', 'Short Name must be between 2 and 8 characters.', 'rangelength', array(2,8));
$editLeagueForm->addRule('lgname', 'Short Name must be between 2 and 8 characters.', 'rangelength', array(2,8));
$editLeagueForm->registerRule('lgname_regex', 'regex', '/^[a-zA-Z][a-zA-Z0-9]{1,7}$/'); 
$editLeagueForm->addRule('lgname', 'Short name must begin with a letter and contain only letters and numbers.', 'lgname_regex');


$editLeagueForm->addElement('text', '', 'League Type');

$leaguetype =& $editLeagueForm->addElement('select', 'league_type', 'League Type');
$leaguetype->loadArray(getEnumOptions('leagues', 'league_type'));
$editLeagueForm->addRule('league_type', 'Required', 'required');

$editLeagueForm->addElement('text', 'description', 'Description');
$editLeagueForm->addElement('text', 'format', 'Match Format');
$editLeagueForm->addElement('static', 'note_format', 'E.g., "6" for 6v6, "1" for 1v1, "0" for anything else.');

$editLeagueForm->addElement('text', 'sort_order', 'Sort Order');

$gidtype =& $editLeagueForm->addElement('select', 'gid_type', 'Game ID Type');
$gidtype->loadArray(getEnumOptions('leagues', 'gid_type'));
$editLeagueForm->addRule('gid_type', 'Required', 'required');

$editLeagueForm->addElement('text', 'max_schedulers', 'Max Schedulers');
$editLeagueForm->addElement('text', 'max_reporters', 'Max Reporters');

$editLeagueForm->addElement('text', 'gid_name', 'Game ID Name');
$editLeagueForm->addElement('text', 'map_pack_download_url', 'Map Pack URL');
$editLeagueForm->addElement('text', 'config_pack_download_url', 'Config Pack URL');

$editLeagueForm->addElement('text', 'linked_lid', 'Linked To (League ID)');
$editLeagueForm->addElement('static', 'note_linked_lid', 'At the moment the only thing that will be linked is the rules.');

//$editLeagueForm->addElement('text', 'tzid', 'Default Time Zone');
//$editLeagueForm->addElement('text', 'default_start_time', 'Default Start Time');
//$editLeagueForm->addElement('text', 'default_match_days', 'Default Match Day(s)');
//$editLeagueForm->addElement('text', 'disputes_per_season', 'Disputes Per Season');
//$editLeagueForm->addElement('text', 'scoring_description', 'Scoring Description');
//$editLeagueForm->addElement('text', 'create_date_gmt', 'Create Date');
//$editLeagueForm->addElement('text', 'inactive', 'Inactive');
$editLeagueForm->addElement('advcheckbox',
                 'inactive',   // name of advcheckbox
                 'Inactive',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editLeagueForm->updateElementAttr(array('inactive'), array('id' => 'inactive'));


$editLeagueForm->addElement('advcheckbox',
                 'show_rules',   // name of advcheckbox
                 'Display Rules',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editLeagueForm->updateElementAttr(array('show_rules'), array('id' => 'show_rules'));



$admins =& $editLeagueForm->addElement('select', 'admin', 'Head Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$selectedAdmins =& $db->getCol('SELECT aid FROM admins_assignments WHERE section = "leagues" AND pkid = '.LID);
if ($editLeagueForm->isSubmitted()) $admins->setSelected($editLeagueForm->exportValue('admin'));
else $admins->setSelected($selectedAdmins);
$editLeagueForm->addElement('static', 'note_admin', 'Optional');
//$editLeagueForm->addRule('admin', 'Please select at least one admin.', 'required');


if (!$ACCESS) { 
    $editLeagueForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $editLeagueForm->freeze();
} else {
    $editLeagueForm->addElement('submit', 'submit', 'Edit League');
}


if ($ACCESS && ($editLeagueForm->validate()) && ($editLeagueForm->exportValue('lid') === LID)) {
    $league_title = $editLeagueForm->exportValue('league_title');
    $checkExistingLeague =& $db->getOne('SELECT TRUE FROM leagues WHERE league_title = ? AND lid <> ? LIMIT 1', array($league_title, LID));

    $lgname = $editLeagueForm->exportValue('lgname');
    $checkExistingLGname =& $db->getOne('SELECT TRUE FROM leagues WHERE lgname = ? AND lid <> ? LIMIT 1', array($lgname, LID));

    if ($checkExistingLeague) {
        $editLeagueForm->setElementError('league_title', 'A league with this title already exists.');
    }
    if ($checkExistingLGname) {
        $editLeagueForm->setElementError('league_title', 'A league with this short name already exists.');
    }

    if (!$checkExistingLeague && !$checkExistingLGname) {
        $LeagueEdit = new updateRecord('leagues', 'lid', LID);
        $LeagueEdit->addData($editLeagueForm->exportValues(array('lid', 'league_title', 'league_type', 'description', 'format', 'sort_order', 'gid_type', 'gid_name', 'inactive', 'lgname', 'show_rules', 'max_schedulers', 'max_reporters', 'map_pack_download_url', 'config_pack_download_url', 'linked_lid')));
        $LeagueEdit->updateData();

        foreach ($editLeagueForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('leagues', LID, $aid);
        }
        $db->query('DELETE FROM admins_assignments WHERE section = "leagues" AND pkid = '.LID);
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);

    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editLeagueForm->accept($renderer);
$tpl->assign('edit_league_form', $renderer->toArray());










$ACCESS = checkPermission('Edit League', 'League', LID);
$addDivisionForm = new HTML_QuickForm('add_division_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$addDivisionForm->removeAttribute('name'); // XHTML compliance
$addDivisionForm->applyFilter('__ALL__', 'trim');

$addDivisionForm->addElement('text', 'division_title', 'Title');
$addDivisionForm->addRule('division_title', 'Title is required.', 'required');


$admins =& $addDivisionForm->addElement('select', 'admin', 'Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$addDivisionForm->addElement('static', 'note_admin', 'Optional');

if (!$ACCESS) { 
    $addDivisionForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $addDivisionForm->freeze();
} else {
    $addDivisionForm->addElement('submit', 'submit', 'Add Division');
}


$addDivisionForm->setDefaults(array('lid' => LID));
$addDivisionForm->setConstants(array('lid' => LID));
$addDivisionForm->addElement('hidden', 'lid', LID);



if ($ACCESS && ($addDivisionForm->validate()) && ($addDivisionForm->exportValue('lid') === LID)) {
    $division_title = $addDivisionForm->exportValue('division_title');
    $checkExistingDivision =& $db->getOne('SELECT TRUE FROM divisions WHERE division_title = ? AND lid = ? LIMIT 1', array($division_title, LID));
    if ($checkExistingDivision) {
        $addDivisionForm->setElementError('division_title', 'A division with this title already exists.');
    } else {
        $newDivisionArray = array(
                                  'division_title' => $addDivisionForm->exportValue('division_title'),
                                  'lid'         => LID,
                                  'create_date_gmt' => mysqlNow()
                                 );
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('divisions', $newDivisionArray);

        //$sql = 'INSERT INTO divisions (division_title, lid, create_date_gmt) VALUES (?, ?, NOW())';
        //$db->query($sql, $addDivisionForm->exportValues(array('division_title', 'lid')));
        //$divid =& $db->getOne('SELECT LAST_INSERT_ID()');

        $divid = $insertRecord->lastInsertId();
        foreach ($addDivisionForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('divisions', $divid, $aid);
        }
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);


        clearForm($addDivisionForm);
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$addDivisionForm->accept($renderer);
$tpl->assign('add_division_form', $renderer->toArray());










$sql = 'SELECT divid, division_title, UNIX_TIMESTAMP(create_date_gmt) as create_date_gmt FROM divisions WHERE lid = ' . $db->quoteSmart(LID);
$divisionsList =& $db->getAll($sql);
$tpl->assign('divisions_list', $divisionsList);

displayTemplate('edit.league');
