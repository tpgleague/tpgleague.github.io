<?php

$pageTitle = 'Create Organization';
require_once '../includes/inc.initialization.php';

if (!loggedin()) displayError('You must be logged in to use this function.');


/// email verified???
$sql = 'SELECT email FROM users WHERE uid = ?';
$email =& $db->getOne($sql, array(UID));
if (empty($email)) displayError('You must <a href="/edit.account.php?actedit=enteremailkey">verify your e-mail address</a> before creating an organization.');


$createOrgForm = new HTML_QuickForm('create_org_form', 'post');
$createOrgForm->removeAttribute('name');
$createOrgForm->applyFilter('__ALL__', 'trim');

$createOrgForm->addElement('text', 'name', 'Organization Name', array('maxlength' => 128));
$createOrgForm->addRule('name', 'Your organization name is required.', 'required');
$createOrgForm->addRule('name', 'Organization name may not exceed 128 characters.', 'maxlength', 128);
$createOrgForm->addRule('name', 'Organization name must be at least 3 characters.', 'minlength', 3);
$createOrgForm->registerRule('check_org', 'function', 'checkOrgExistsByUser');
$createOrgForm->addRule('name', 'You already are the owner of an organization by this name.', 'check_org', 'name');
function checkOrgExistsByUser($element,$elementValue)
{
    global $db;
    $sql = "SELECT TRUE FROM organizations WHERE owner_uid = ". UID ." AND name = ?";
    return is_null($db->getOne($sql,$elementValue));
}


$createOrgForm->addElement('text', 'website', 'Website', array('maxlength' => 255));
$createOrgForm->applyFilter('website','clean_http');
function clean_http($s) { 
    return preg_replace('/^(http:\/\/\s*)+/i','',$s); 
}


$ccode =& $createOrgForm->addElement('select', 'ccode', 'Country');
$ccode->loadArray(array(''   => 'Select country',
                        'us' => 'United States of America',
                        'ca' => 'Canada',
                        'gb' => 'United Kingdom'
                  ));
$ccode->loadQuery($db, 'SELECT country, ccode FROM countries WHERE ccode NOT IN ("us", "ca", "gb") ORDER BY country ASC');

$createOrgForm->addElement('submit', 'submit', 'Create Organization', array('class' => 'submit'));

if ($createOrgForm->validate()) {
    $valuesArray = array(
                         'owner_uid' => UID,
                         'name' => $createOrgForm->exportValue('name'),
                         'website' => $createOrgForm->exportValue('website'),
                         'ccode' => $createOrgForm->exportValue('ccode'),
                         'create_date_gmt' => gmdate('c', mktime()),
                         'modify_date_gmt' => gmdate('c', mktime())
                        );
    $insertRecord = new InsertRecord();
    $insertRecord->insertData('organizations', $valuesArray);
    $orgid = $insertRecord->lastInsertId();
    if (!$orgid) { displayError('An error has occured creating your organization.'); }
    else {
        cpTeams();
        redirect("/org.cp.php?orgid=$orgid");
    }
} else {
    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $createOrgForm->accept($renderer);
    $tpl->assign('create_org_form', $renderer->toArray());
}



displayTemplate('create.org');