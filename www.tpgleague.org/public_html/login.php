<?php
exit();
require_once '../includes/inc.initialization.php';

/********** this page will probably not be needed since we have the login panel/control panel **********************/

$loginForm = new HTML_QuickForm('login', 'post', NULL, NULL, NULL, TRUE);
$loginForm->removeAttribute('name'); // XHTML compliance
$loginForm->applyFilter('__ALL__','trim');

$loginForm->addElement('text', 'username', 'Username:',array('size' => 20, 'maxlength' => 32));
$loginForm->addRule('username','Username is required','required');

$loginForm->registerRule('username_beginletter','regex','/^[a-zA-Z]/'); 
$loginForm->addRule('username','Username must begin with a letter.','username_beginletter');

$loginForm->registerRule('check_un','function','checkExistsInUsersTbl');
$loginForm->addRule('username','Username does not exist. If you have forgotten your username, then please use the <a href="/recover/">account/password recovery page</a>','check_un','username');

$loginForm->addElement('password','password','Password:'); 
$loginForm->addRule('password','Password is required.','required');

$loginForm->addElement('checkbox', 'remember', 'Remember me:');

$loginForm->addElement('submit', 'submit', 'Submit');

if ($loginForm->validate()) {
    global $db;
    $valuesArray = array(
                         $loginForm->exportValue('username'),
                         SHA1($loginForm->exportValue('password'))
                        );
    $sql = 'SELECT uid FROM users WHERE username = ? AND password = ?';
    $uid =& $db->getOne($sql,$valuesArray);
    if (ctype_digit($uid)) {
        echo "success! logged in as $uid";
        exit;
    } else {
        $loginForm->setElementError('password','Invalid password.');
        $loginRenderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
        $loginForm->accept($loginRenderer);
        $tpl->assign('login_form', $loginRenderer->toArray());
        $tpl->display('login.tpl');
    }
} else {
    $loginRenderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl);
    $loginForm->accept($loginRenderer);
    $tpl->assign('login_form', $loginRenderer->toArray());
    $tpl->display('login.tpl');
}