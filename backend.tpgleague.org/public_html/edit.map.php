<?php
    //require_once '../includes/inc.initialization.php';
    require_once '../includes/inc.initialization.support.php';

    if (!checkNumber($_GET['mapid'])) { displayError('Error: Map ID not specified.'); }
    else { define('MAPID', $_GET['mapid']); }
    
    if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
    else { define('LID', $_GET['lid']); }

    $ACCESS = checkPermission('Edit League', 'League', LID);
    
    $editMapForm = new HTML_QuickForm('edit_map_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
    if (!$ACCESS) { 
        $editMapForm->freeze();
        $editMapForm->setElementError('submit', 'You are not authorized to use this control.');
    }
    
    $editMapForm->removeAttribute('name'); // XHTML compliance
    $editMapForm->applyFilter('__ALL__', 'trim');
    
    $sql = 'SELECT * FROM maps WHERE mapid = '.$db->quoteSmart(MAPID);
    $mapDefaults =& $db->getRow($sql);
    $editMapForm->setDefaults($mapDefaults);

    $editMapForm->addElement('text', 'map_title', 'Map Title');
    $editMapForm->freeze(array('map_title'));
    
    $editMapForm->addElement('text', 'modify_date_gmt', 'Modified Date');
    $editMapForm->freeze(array('modify_date_gmt'));

    $editMapForm->addElement('text', 'filename', 'Full File Path');
    $editMapForm->addElement('text', 'config_path', 'Config Path');
    $editMapForm->addElement('text', 'overview_path', 'Overview Path');
    $editMapForm->addElement('text', 'illegal_locations_path', 'Exploits Folder');
       
    $editMapForm->addElement('advcheckbox',
                     'deleted',   // name of advcheckbox
                     'Deleted',  // label output before advcheckbox
                     NULL,           // label output after advcheckbox
                     array('class' => 'checkbox'),      // string or array of attributes
                     array(0,1)
                 );
    $editMapForm->updateElementAttr(array('deleted'), array('id' => 'deleted'));

    $editMapForm->addElement('submit', 'submit', 'Edit Map');
    
    $editMapForm->setConstants(array('mapid', MAPID));
    $editMapForm->addElement('hidden', 'mapid', MAPID);
    
    if ($ACCESS && $editMapForm->validate())
    {
            $updateRecord = new updateRecord('maps', 'mapid', MAPID);
            $updateRecord->addData($editMapForm->exportValues(array('filename', 'config_path', 'overview_path', 'illegal_locations_path', 'deleted')));
            $updateRecord->UpdateData();
            
            $tpl->assign('success', TRUE);
            redirect('/maps.manager.php?lid=' . LID);
    }

    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $editMapForm->accept($renderer);
    $tpl->assign('edit_map_form', $renderer->toArray());
   
    require_once 'inc.initialization.display.php';   
    displayTemplate('edit.map');

