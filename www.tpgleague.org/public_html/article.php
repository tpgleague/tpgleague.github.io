<?php
  
// make sure Quickform clears out the captcha code after every submit
if ($_POST['captcha_code']) {
    $post_captcha_code = $_POST['captcha_code'];
    unset($_POST['captcha_code']);
}
  
$pageTitle = 'News Article';

require_once('../includes/inc.initialization.php');

// Check that a numeric "article id" was provided
if (!checkNumber($_GET['newsid'])) { displayError('The article id provided is not valid.'); }
else { define('NEWSID', $_GET['newsid']); }


$sql = <<<SQL
    SELECT
        news.newsid,
        news.title,
        news.body,
        news.deleted,
        news.comments_locked,
        UNIX_TIMESTAMP(news.create_date_gmt) AS timestamp,
        admin_name AS author,
        nplid -- Poll Id
    FROM news 
    LEFT JOIN admins ON news.create_by_aid = admins.aid 
    LEFT JOIN news_polls USING (newsid) 
    WHERE news.newsid = ?
SQL;

$article =& $db->getRow($sql, array(NEWSID));
$tpl->assign('news_data', $article);

if ($article['nplid'])
{
    include_once 'inc.func-poll.php';
    $poll = new poll($article['nplid']);
    $poll->pollGraph();
}

// Update the title of the page
$tpl->assign('title', escape($article['title']));

// Is the user currently an admin?
$adminSql = <<<SQL
    SELECT aid
    FROM admins
    WHERE inactive = 0 and department in ('Operations', 'General Manager', 'League Admin')
        AND admins.uid = ?
SQL;

$adminAid =& $db->getOne($adminSql, array(UID));
$tpl->assign('admin_aid', $adminAid);

// Delete a comment is requested by an admin
if (checkNumber($_GET['delete']))
{
    if ($adminAid)
    {
                $deleteSql = <<<SQL
                    UPDATE comments
                    SET deleted = 1, deleted_by_aid = ?
                    WHERE cmt_id = ?
SQL;

                $deleteValuesArray = array(UID, $_GET['delete']);
                $res =& $db->query($deleteSql, $deleteValuesArray);
    }
    else
    {
        displayError('You are not authorized to delete comments.');
    }
}

if (loggedin())
{
    // Set information about the user since they are logged in
    $tpl->assign('loggedin', 1);
    $abuseLockSql = 'SELECT abuse_lock FROM users WHERE uid = ' . $db->quoteSmart(UID);
    $abuselock =& $db->getOne($abuseLockSql);
    $tpl->assign('abuse_lock', $abuselock);
    $tpl->assign('mainhandle', $_SESSION['handle']);
    
    if (!$abuselock)
    {
        // Comment Area
        $commentForm = new HTML_QuickForm('comment_form', 'post', NULL, NULL, $onsubmit, FALSE);
        $commentForm->removeAttribute('name'); // XHTML compliance
        $commentForm->applyFilter('__ALL__', 'trim');

        $commentForm->addElement('textarea', 'comments', 'Comments', array('rows' => 5, 'cols' => '100', 'onkeydown' => 'textCounter(this,"progressbar1",4000)', 'onkeyup' => 'textCounter(this,"progressbar1",4000)', 'onfocus' => 'textCounter(this,"progressbar1",4000)'));
        $commentForm->addRule('comments', 'Comments may not exceed 4000 characters.', 'maxlength', 4000);
        $commentForm->addElement('static', 'note_comments', 'Maximum 4000 characters.<div id="progressbar1" class="progress"></div><script type="text/javascript">textCounter(document.getElementById("comments"),"progressbar1",4000)</script>');
        $tpl->append('external_js', 'textarea.progressbar');

        $captchaImage = '<div class="static"><img src="/imagebuilder.php?rand='. rand(111111,999999) .'" alt="CAPTCHA" id="captcha" /></div>';
        $commentForm->addElement('static', 'captcha', 'Verification Image', $captchaImage);
        $commentForm->addElement('text', 'captcha_code', 'Enter Verification Code', array('value' => '', 'size' => 8, 'maxlength' => 5));
        $commentForm->addElement('static', 'note_captcha_code', 'Please enter the code shown above.');

        $commentForm->addElement('submit', 'submit', 'Submit', array('class' => 'submit'));

        if ($commentForm->validate()) {
            $string = strtoupper($_SESSION['string']);
            $userstring = strtoupper($post_captcha_code); 
            unset($_SESSION['string']);
            if (($string !== $userstring) || (strlen($string) < 5))
            {
                $commentForm->setElementError('captcha_code', 'Incorrect validation code.');
                $formFailure = TRUE;
            }
            else
            {
                $userComments = $commentForm->exportValue('comments');
                
                // Check for dupes
                $dupeCheckSql = <<<SQL
                    SELECT comments.cmt_id
                    FROM news_comments 
                    INNER JOIN comments on comments.cmt_id = news_comments.cmt_id
                    WHERE news_comments.newsid = ? AND posted_by_uid = ? AND post_date_gmt > DATE_SUB(CURDATE(), INTERVAL 120 SECOND) AND comments.comment_text = ?
SQL;
                $dupes =& $db->getOne($dupeCheckSql, array(NEWSID, UID, $userComments));
                if (!$dupes)
                {
                    // Add the comment
                    $addCommentSql = <<<SQL
                        INSERT INTO comments
                        (
                            comment_text,
                            deleted,
                            posted_by_uid
                        )
                        VALUES
                        (
                            ?,
                            0,
                            ?
                        )
SQL;

                    $valuesArray = array($userComments, UID);
                    $res =& $db->query($addCommentSql, $valuesArray);
                    $cmt_id = $db->getOne('SELECT last_insert_id() FROM comments');

                    $addNewsCommentsSql = <<<SQL
                        INSERT INTO news_comments
                        (
                            newsid,
                            cmt_id
                        )
                        values
                        (
                            ?,
                            ?
                        )
SQL;
                    $newsCmtvaluesArray = array(NEWSID, $cmt_id);
                    $res =& $db->query($addNewsCommentsSql, $newsCmtvaluesArray);
                }
                else
                {
                    displayError('You already posted this comment within the last 2 minutes.');
                }
            }
            
            // Remove text for the form
            clearform($commentForm);
            redirect();
        }

        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
        $commentForm->accept($renderer);
        $tpl->assign('comment_form', $renderer->toArray());
    }
}

// Retrieve the comments
$commentsSql = <<<SQL
    SELECT
        comments.cmt_id,
        comments.comment_text,
        comments.deleted,
        UNIX_TIMESTAMP(comments.post_date_gmt) as post_date_gmt,
        comments.posted_by_uid,
        comments.deleted_by_aid,
        users.handle,
        case when admins.inactive = 0 then 1 else 0 end as tpg_admin
    FROM news_comments 
    INNER JOIN comments on comments.cmt_id = news_comments.cmt_id
    LEFT OUTER JOIN users ON users.uid = posted_by_uid
    LEFT OUTER JOIN admins ON comments.posted_by_uid = admins.uid 
    WHERE news_comments.newsid = ?
SQL;

$comments =& $db->getAll($commentsSql, array(NEWSID));
$tpl->assign('cmts', $comments);

displayTemplate('article', NULL, NULL, TRUE);

