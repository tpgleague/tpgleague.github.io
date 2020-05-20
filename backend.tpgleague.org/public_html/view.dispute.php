<?php

$cssAppend[] = 'disputes';
require_once '../includes/inc.initialization.php';


if (!checkNumber(@$_GET['did'])) { displayError('Error: Dispute ID not specified.'); }
else { @define('DID', @$_GET['did']); }

$sql = <<<SQL
            SELECT leagues.lid, disputes.*, matches.*, UNIX_TIMESTAMP(disputes.create_date_gmt) AS unix_create_date_gmt
            FROM leagues
            INNER JOIN seasons USING (lid)
            INNER JOIN schedules USING (sid)
            INNER JOIN matches USING (sch_id)
            INNER JOIN disputes USING (`mid`)
            WHERE disputes.did = ?
SQL;
$dispute =& $db->getRow($sql, array(DID));
if (empty($dispute)) displayError('Error: Dispute not found.');
define('LID', $dispute['lid']);
define('SCH_ID', $dispute['sch_id']);
define('MID', $dispute['mid']);

$sql =& 'SELECT disputes_messages.* FROM disputes_messages INNER JOIN users USING (uid) WHERE disputes_messages.did = ?';
$disputesMessages($sql, array(DID));
$tpl->assign('disputes_messages', $disputesMessages);



$commentForm = new HTML_QuickForm('comment_form', 'post', $qfAction, NULL, $onsubmit, TRUE);
$commentForm->removeAttribute('name'); // XHTML compliance
$commentForm->applyFilter('__ALL__', 'trim');

$commentForm->addElement('textarea', 'comment', 'Comment', array('rows' => 5, 'cols' => '50'));
$commentForm->addElement('submit', 'submit', 'Submit');
$commentForm->addRule('comment', 'Please enter a comment.', 'required');
if ($commentForm->validate()) {
    $adminNotesValues = array(
                              'uid' => USER_ID,
                              'aid' => AID,
                              'create_date_gmt' => mysqlNow(),
                              'comment' => $commentForm->exportValue('comment')
                             );
    $res = $db->autoExecute('users_admin_notes', $adminNotesValues, DB_AUTOQUERY_INSERT);
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$commentForm->accept($renderer);
$tpl->assign('admin_notes_form', $renderer->toArray());


displayTemplate('view.dispute');