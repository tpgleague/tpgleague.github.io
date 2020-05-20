<?php

require_once '../includes/inc.initialization.php';
$tpl->append('external_css', 'table');


$addLeagueForm = new HTML_QuickForm('add_league_form', 'post', NULL, NULL, $onsubmit, FALSE);
$addLeagueForm->removeAttribute('name'); // XHTML compliance
$addLeagueForm->applyFilter('__ALL__', 'trim');
$addLeagueForm->applyFilter('lgname', 'strtolower');

$addLeagueForm->setDefaults(array(
                                    'max_schedulers' => '2',
                                    'max_reporters' => '2'));

$addLeagueForm->addElement('text', 'league_title', 'Title');
$addLeagueForm->addRule('league_title', 'Title is required.', 'required');

$addLeagueForm->addElement('text', 'lgname', 'Short Name', array('maxlength' => 8, 'onkeyup' => 'javascript: document.getElementById("lgname_span").innerHTML=document.getElementById("lgname").value.toLowerCase()'));
$lgname = (isset($_POST['lgname'])) ? $_POST['lgname'] : $leagueSettings['lgname'];
if (empty($lgname)) $lgname = 'name';
$addLeagueForm->addElement('static', 'note_lgname', 'http://www.tpgleague.org/<span id="lgname_span" style="font-style: italic;">'.$lgname.'</span>/');
$addLeagueForm->addRule('lgname', 'Short Name is required.', 'required');
$addLeagueForm->addRule('lgname', 'Short Name must be between 2 and 8 characters.', 'rangelength', array(2,8));
$addLeagueForm->addRule('lgname', 'Short Name must be between 2 and 8 characters.', 'rangelength', array(2,8));
$addLeagueForm->registerRule('lgname_regex', 'regex', '/^[a-zA-Z][a-zA-Z0-9]{1,7}$/'); 
$addLeagueForm->addRule('lgname', 'Short name must begin with a letter and contain only letters and numbers.', 'lgname_regex');

$addLeagueForm->addElement('text', 'max_schedulers', 'Max Schedulers');
$addLeagueForm->addElement('text', 'max_reporters', 'Max Reporters');

$addLeagueForm->addElement('text', 'side_one', 'Side One (e.g. Allies)');
$addLeagueForm->addElement('text', 'side_two', 'Side Two (e.g. Axis)');

$addLeagueForm->addElement('advcheckbox',
                 'inactive',   // name of advcheckbox
                 'Inactive',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$addLeagueForm->updateElementAttr(array('inactive'), array('id' => 'inactive'));
$addLeagueForm->setDefaults(array('inactive' => 1));

$gidtype =& $addLeagueForm->addElement('select', 'gid_type', 'Game ID Type');
$gidtype->loadArray(getEnumOptions('leagues', 'gid_type'));
$addLeagueForm->addRule('gid_type', 'Required', 'required');

$addLeagueForm->addElement('text', 'gid_name', 'Game ID Name');

$admins =& $addLeagueForm->addElement('select', 'admin', 'Head Admin(s)');
$admins->setMultiple(TRUE);
$admins->setSize(6);
$admins->loadQuery($db, 'SELECT admin_name, aid FROM admins WHERE inactive = 0 ORDER BY admin_name ASC');
$addLeagueForm->addElement('static', 'note_admin', 'Optional');
//$addLeagueForm->addRule('admin', 'Please select at least one admin.', 'required');

$ACCESS = SUPERADMIN;
if (!$ACCESS) { 
    $addLeagueForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $addLeagueForm->freeze();
} else {
    $addLeagueForm->addElement('submit', 'submit', 'Add League');
}

if ($addLeagueForm->validate() && $ACCESS) {
    $league_title = $addLeagueForm->exportValue('league_title');
    $checkExistingLeague =& $db->getOne('SELECT TRUE FROM leagues WHERE league_title = ? LIMIT 1', array($league_title));

    $lgname = $addLeagueForm->exportValue('lgname');
    $checkExistingLGname =& $db->getOne('SELECT TRUE FROM leagues WHERE lgname = ? LIMIT 1', array($lgname));

    if ($checkExistingLeague) {
        $addLeagueForm->setElementError('league_title', 'A league with this title already exists.');
    }
    if ($checkExistingLGname) {
        $addLeagueForm->setElementError('league_title', 'A league with this short name already exists.');
    }

    if (!$checkExistingLeague && !$checkExistingLGname) {
        $newLeagueArray = array_merge($addLeagueForm->exportValues(array('league_title', 'lgname', 'gid_type', 'gid_name', 'inactive', 'max_schedulers', 'max_reporters')), array('create_date_gmt' => mysqlNow()));
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('leagues', $newLeagueArray);

        $lid = $insertRecord->lastInsertId();
        foreach ($addLeagueForm->exportValue('admin') as $aid) {
           $adminsArray[] = array('leagues', $lid, $aid);
        }
        $sth = $db->autoPrepare('admins_assignments', array('section', 'pkid', 'aid'), DB_AUTOQUERY_INSERT);
        $res =& $db->executeMultiple($sth, $adminsArray);

        // Add sides (only allow two for the moment)
        $newSidesArray = $addLeagueForm->exportValues(array('side_one'));
        $sideOneArray = array("side" => $newSidesArray['side_one'], "lid" => $lid);
        $insertSidesRecord = new InsertRecord();
        $insertSidesRecord->insertData('leagues_sides', $sideOneArray);
        
        $newSidesArray = $addLeagueForm->exportValues(array('side_two'));
        $sideTwoArray = array("side" => $newSidesArray['side_two'], "lid" => $lid);
        $insertSidesRecord = new InsertRecord();
        $insertSidesRecord->insertData('leagues_sides', $sideTwoArray);
        
        clearForm($addLeagueForm);
    }
}
if ($ACCESS) {
    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $addLeagueForm->accept($renderer);
    $tpl->assign('add_league_form', $renderer->toArray());
}

$sql = <<<SQL
    SELECT leagues.lid,
        leagues.league_title,
        leagues.description,
        leagues.inactive,
        leagues.deleted,
        leagues.roster_lock,
        leagues.lgname,
        leagues.linked_lid,
        leagues.show_rules,
        games.game_name,
        UNIX_TIMESTAMP(leagues.last_rule_update_gmt) as last_rule_update_gmt,
        UNIX_TIMESTAMP(leagues.create_date_gmt) as create_date_gmt,
        -- leagues.last_rule_update_gmt,
        -- leagues.create_date_gmt,
        seasons.sid,
        seasons.season_number,
        seasons.season_title
    FROM leagues
    LEFT OUTER JOIN games on games.gameid = leagues.gameid
    LEFT OUTER JOIN seasons on seasons.lid = leagues.lid AND seasons.active = 1
SQL;
$leaguesList =& $db->getAll($sql);
$tpl->assign('leagues_list', $leaguesList);

displayTemplate('leagues');