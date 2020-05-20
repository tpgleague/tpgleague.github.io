<?php


$cssAppend[] = 'news';
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

<script language="javascript" type="text/javascript">
function addElement(divParent) {
  var ni = document.getElementById(divParent);
  var numi = document.getElementById('currentValue');
  var num = (document.getElementById('currentValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = '<a class="plus" onclick="removeElement(\'myDiv\', '+divIdName+')"> [Remove] </a><input type="text" name="poll_choices[]" size="40" maxlength="255" /><br />';
  ni.appendChild(newdiv);
}

function removeElement(divParent, divNum) {
    var element = document.getElementById(divParent);
    element.removeChild(divNum);
}
</script>

EOF;


require_once '../includes/inc.initialization.php';



$sql = 'SELECT league_title, lid FROM leagues ORDER BY league_title ASC';
$leaguesDropdown =& $db->getAll($sql);
$tpl->assign('leagues_dropdown', $leaguesDropdown);








$addNewsForm = new HTML_QuickForm('add_news_form', 'post', NULL, NULL, $onsubmit, FALSE);
$addNewsForm->removeAttribute('name'); // XHTML compliance
$addNewsForm->applyFilter('__ALL__', 'trim');

$addNewsForm->addElement('text', 'title', 'Title', array('maxlength' => 255));
$addNewsForm->addRule('title', 'A title is required.', 'required');

$addNewsForm->addElement('textarea', 'body', 'Body', array('rows' => 20, 'cols' => '100'));
$addNewsForm->addRule('body', 'News post may not exceed 10000 characters.', 'maxlength', 10000);
$addNewsForm->addRule('body', 'A body is required.', 'required');

$lidsel =& $addNewsForm->addElement('select', 'lid', 'League', NULL, array('class' => 'select_break'));
$lidsel->loadArray(array('main' => 'Main page only (no leagues. Good for sponsored news posts)', 'all'   => 'Main page + all leagues'));
$lidsel->loadQuery($db, 'SELECT league_title, lid FROM leagues WHERE deleted = 0 ORDER BY league_title ASC');





$addNewsForm->addElement('text', 'poll_title', 'Poll Question', array('size' => 80, 'maxlength' => 255));

$addNewsForm->addElement('advcheckbox',
                 'poll_hidden',   // name of advcheckbox
                 'Hide results until voting closed',  // label output before advcheckbox
                 NULL,           // label output after advcheckbox
                 array('class' => 'checkbox'),      // string or array of attributes
                 array(0,1)
             );
$addNewsForm->updateElementAttr(array('poll_hidden'), array('id' => 'poll_hidden'));
$addNewsForm->addElement('static', 'note_poll_hidden', ' (Normally, results are displayed to whoever has voted.)');

//$date = dateToArray(strftime('%Y-%m-%d %H:%M:%S', strtotime('+1 week')));
$date = dateToArray(date('Y-m-d H:i:s', strtotime('+1 week')));

if ($date['hour'] > 12) {
    $hour_12 = $date['hour']-12;
    $meridian = 'PM';
} elseif ($date['hour'] == 0) {
    $hour_12 = 0;
    $meridian = 'AM';
} elseif ($date['hour'] == 12) {
    $hour_12 = 0;
    $meridian = 'PM';
} else {
    $hour_12 = $date['hour'];
    $meridian = 'AM';
}
//$date['minute'] = floor($date['minute']/5)*5;
$date['minute'] = 0;

$addNewsForm->setDefaults(array('poll_close_date' => array('Y' => $date['year'], 'M' => $date['month'], 'd' => $date['day'], 'g' => $hour_12, 'A' => $meridian, 'i' => $date['minute'])));
$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'giAdMY',
                     'optionIncrement' => array('i' => '5'),
                     'maxYear'         => date('Y', strtotime('+1 years')),
                     'minYear'         => date('Y'),
                     'addEmptyOption'  => array('i' => TRUE, 'g' => TRUE, 'd' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('i' => 'Min', 'g' => 'Hour', 'd' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
$addNewsForm->addElement('date', 'poll_close_date', 'Poll Closing Date', $dateOptions);
$addNewsForm->addElement('static', 'note_poll_close_date', ' (Time and date ('.(empty($date['tz']) ? date('T'):$date['tz']).'))');


$pollTitle = $addNewsForm->exportValue('poll_title');
if (isset($_POST['poll_choices'])) {
    $pollChoices = array();
    foreach ($_POST['poll_choices'] as $choice) {
        $choice = trim($choice);
        if (!empty($choice) && !in_array(array($choice), $pollChoices)) {
            $pollChoices[] = array($choice); // in this format for use by $db->executeMultiple
        }
    }
}
if (!empty($pollTitle) || !empty($pollChoices)) {
    $pollSubmitted = TRUE;
    $addNewsForm->addRule('poll_title', 'A Poll Question is required.', 'required');
    $addNewsForm->addRule('poll_close_date', 'Please enter a valid date.', 'required');
    $addNewsForm->addRule('poll_close_date', 'Please enter a valid date.', 'valid_date');
}

$addNewsForm->addElement('submit', 'submit', 'Post News');

$validEligibilityChoices = array('registered','active_team','captains','owners','privileged','ip');

if ($addNewsForm->validate() && (!$pollSubmitted) ||
        ($pollSubmitted
         && count($pollChoices) >= 2
         && !empty($_POST['poll_eligibility'])
         && in_array($_POST['poll_eligibility'], $validEligibilityChoices)
        )
    ) {
    $lid = $addNewsForm->exportValue('lid');
    //if (!checkNumber($lid)) $addNewsForm->setElementError('lid', 'Invalid League');

    if ($lid == 'all') {
        $ACCESS = checkPermission('News', 'Sitewide', 0);
        $lid = NULL;
    } elseif ($lid == 'main') {
        $ACCESS = checkPermission('News', 'Sitewide', 0);
        $lid = 0;
    } else {
        $ACCESS = checkPermission('News', 'League', $lid);
    }
    if (!$ACCESS) {
        $addNewsForm->setElementError('lid', 'You do not have access to post in this league.');
    } else {
        $valuesArray = array(
                            'title' => $addNewsForm->exportValue('title'),
                            'body' => $addNewsForm->exportValue('body'),
                            'create_by_aid' => AID,
                            'lid' => $lid,
                            'create_date_gmt' => mysqlNow()
                            );
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('news', $valuesArray);
        $newsid = $insertRecord->lastInsertId();


        if ($pollSubmitted
         && count($pollChoices) >= 2
         && !empty($_POST['poll_eligibility'])
         && in_array($_POST['poll_eligibility'], $validEligibilityChoices)
        ) {

            // poll_close_date
            $formDate = $addNewsForm->exportValue('poll_close_date');
            $year = $formDate['Y']+0;
            $month = $formDate['M']+0;
            $day = $formDate['d']+0;
            $hour_12 = $formDate['g']+0;
            $meridian = $formDate['A'];
            $minute = $formDate['i']+0;
            $second = 0;
            if ($meridian == 'AM') {
                if ($hour_12 < 12) $hour = $hour_12;
                else $hour = 0;
            } else {
                if ($hour_12 == 12) $hour = 12;
                else $hour = $hour_12 + 12;
            }
            $poll_close_date = gmstrftime('%Y-%m-%d %H:%M:%S', mktime($hour, $minute, $second, $month, $day, $year));


            $valuesArray = array(
                                'newsid' => $newsid,
                                'title' => $pollTitle,
                                'create_date_gmt' => mysqlNow(),
                                'expire_date_gmt' => $poll_close_date,
                                'eligibility' => $_POST['poll_eligibility'],
                                'lid' => $lid,
                                'results_hidden' => $_POST['poll_hidden'] ? 'Closed':'Voted'
                                );
            $insertRecord = new InsertRecord();
            $insertRecord->insertData('news_polls', $valuesArray);
            $nplid = $insertRecord->lastInsertId();
            $sth = $db->prepare('INSERT INTO news_polls_choices (nplid, name) VALUES ('.$db->quoteSmart($nplid).', ?)');
            $res =& $db->executeMultiple($sth, $pollChoices);
        }
        $tpl->assign('success', TRUE);

        if ($lid == 'NULL') $frontend->clear_cache('index.tpl');
        else $frontend->clear_cache('index.tpl', $lid);

        clearForm($addNewsForm);
    }
} elseif ($pollSubmitted) {
    $tpl->assign('poll_error', 'Please ensure that the poll form is filled out completely. If you don\'t want to post a poll then please clear the poll form. A valid poll has at least two unique choices.');
}

$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$addNewsForm->accept($renderer);
$tpl->assign('add_news_form', $renderer->toArray());
displayTemplate('news');

