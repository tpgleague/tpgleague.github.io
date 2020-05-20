<?php

require_once '../includes/inc.initialization.php';

if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { define('LID', $_GET['lid']); }

$ACCESS = checkPermission('Edit League', 'League', LID);

$addMapForm = new HTML_QuickForm('add_map_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
if (!$ACCESS) { 
    $addMapForm->freeze();
    $addMapForm->setElementError('submit', 'You are not authorized to use this control.');
}
$addMapForm->removeAttribute('name'); // XHTML compliance
$addMapForm->applyFilter('__ALL__', 'trim');

$addMapForm->addElement('text', 'map_title', 'Map Title');
$addMapForm->addRule('map_title', 'Title is required.', 'required');

$addMapForm->addElement('text', 'filename', 'File Path');
$addMapForm->addElement('text', 'config_path', 'Config Path');
$addMapForm->addElement('text', 'overview_path', 'Overview Path');
$addMapForm->addElement('text', 'illegal_locations_path', 'Exploits Folder');


$addMapForm->addElement('submit', 'submit', 'Add Map');

$addMapForm->setDefaults(array('lid', LID));
$addMapForm->setConstants(array('lid', LID));
$addMapForm->addElement('hidden', 'lid', LID);


$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$addMapForm->accept($renderer);
$tpl->assign('add_map_form', $renderer->toArray());



if ($ACCESS && ($addMapForm->validate()) && ($addMapForm->exportValue('lid') === LID)) {
    $map_title = $addMapForm->exportValue('map_title');
    $map_title = $addMapForm->exportValue('filename');
    $checkExistingMapTitle =& $db->getOne('SELECT TRUE FROM maps WHERE map_title = ? AND lid = ?', array($map_title, LID));
    if ($checkExistingMapTitle) {
        $addMapForm->setElementError('map_title', 'A map with this title already exists.');
    }
    if (!$checkExistingMapTitle) {
        $newMapArray = array_merge($addMapForm->exportValues(), array('modify_date_gmt' => mysqlNow()));
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('maps', $newMapArray);
        //$sql = 'INSERT INTO maps (map_title, lid, modify_date_gmt) VALUES (?, ?, NOW())';
        //$db->query($sql, $addMapForm->exportValues());
        clearForm($addMapForm);
    }
}




$sql = 'SELECT * FROM maps WHERE lid = ' . $db->quoteSmart(LID);
$mapsList =& $db->getAll($sql);
$tpl->assign('maps_list', $mapsList);

displayTemplate('maps.manager');