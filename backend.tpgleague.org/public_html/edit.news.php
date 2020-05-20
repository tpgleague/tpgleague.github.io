<?php


$extra_head[] = <<<EOF

<script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "textareas",
    theme_advanced_layout_manager : "SimpleLayout",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_path : true,
    theme_advanced_buttons1_add : "forecolor,backcolor"
});
</script>

EOF;

require_once '../includes/inc.initialization.php';

$newsid = $_GET['newsid'];
if (!checkNumber($newsid)) displayError('Invalid News ID');


$sql = 'SELECT title, body, IF(lid IS NULL, "0", lid) AS lid, news.create_by_aid, admins.admin_name, news.create_date_gmt, deleted, comments_locked FROM news INNER JOIN admins ON news.create_by_aid = admins.aid WHERE newsid = '.$db->quoteSmart($newsid);
$newsPost =& $db->getRow($sql);

define('LID', $newsPost['lid']);
$ACCESS = checkPermission('Edit League', 'League', LID);

$editNewsForm = new HTML_QuickForm('edit_news_form', 'post', $qfAction, NULL, $onsubmit, FALSE);
$editNewsForm->removeAttribute('name'); // XHTML compliance
$editNewsForm->applyFilter('__ALL__', 'trim');

$editNewsForm->setDefaults($newsPost);

$editNewsForm->addElement('text', 'title', 'Title', array('maxlength' => 255));
$editNewsForm->addRule('title', 'A title is required.', 'required');

$editNewsForm->addElement('static', 'admin_name', 'Posted by', '<div class="static">'. $teamData['admin_name'] .'</div>');

$editNewsForm->addElement('textarea', 'body', 'Body', array('rows' => 20, 'cols' => '100'));
$editNewsForm->addRule('body', 'News post may not exceed 10000 characters.', 'maxlength', 10000);
$editNewsForm->addRule('body', 'A body is required.', 'required');

$lidsel =& $editNewsForm->addElement('select', 'lid', 'League', NULL, array('class' => 'select_break'));
$lidsel->loadArray(array('main' => 'Main page only (no leagues. Good for sponsored news posts)', 'all'   => 'Main page + all leagues'));
$lidsel->loadQuery($db, 'SELECT league_title, lid FROM leagues WHERE deleted = 0 ORDER BY league_title ASC');

$editNewsForm->addElement('advcheckbox',
                 'deleted',   // name of advcheckbox
                 'Deleted',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editNewsForm->updateElementAttr(array('deleted'), array('id' => 'deleted'));

$editNewsForm->addElement('advcheckbox',
                 'comments_locked',   // name of advcheckbox
                 'Comments Locked?',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$editNewsForm->updateElementAttr(array('deleted'), array('id' => 'deleted'));

if (!$ACCESS) { 
    $editNewsForm->addElement('static', 'submit', '', '<div class="error"><p class="error">You are not authorized to use this control.</p></div>');
    $editNewsForm->freeze(array('newsid', 'title', 'deleted', 'lid'));
} else {
    $editNewsForm->addElement('submit', 'submit', 'Edit News');
}

if ($editNewsForm->validate()) {
    $lid = $editNewsForm->exportValue('lid');
    //if (!checkNumber($lid)) $editNewsForm->setElementError('lid', 'Invalid League');

    if ($lid == 'all') {
        $ACCESS = checkPermission('News', 'Sitewide', 0);
        $lid = NULL;
    } elseif ($lid == 'main') {
        $ACCESS = checkPermission('News', 'Sitewide', 0);
        $lid = 0;
    } else {
        $ACCESS = checkPermission('News', 'League', $lid);
    }
    if (!$ACCESS && (AID != $newsPost['create_by_aid'])) {
        $addNewsForm->setElementError('lid', 'You do not have access to post in this league.');
    } else {
        $valuesArray = array(
                            'newsid' => $newsid,
                            'title' => $editNewsForm->exportValue('title'),
                            'body' => $editNewsForm->exportValue('body'),
                            'deleted' => $editNewsForm->exportValue('deleted'),
                            'comments_locked' => $editNewsForm->exportValue('comments_locked'),
                            'lid' => $lid
                            );
        $updateRecord = new updateRecord('news', 'newsid');
        $updateRecord->addData($valuesArray);
        $updateRecord->UpdateData();

        //if (is_null($lid)) $frontend->clear_cache('index.tpl');
        //else $frontend->clear_cache('index.tpl', $lid);
        $frontend->clear_cache('index.tpl', $lid);

        $tpl->assign('success', TRUE);
    }
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editNewsForm->accept($renderer);
$tpl->assign('edit_news_form', $renderer->toArray());
displayTemplate('edit.news');