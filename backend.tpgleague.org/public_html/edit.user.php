<?php



$extra_head[] = <<<HEAD

<link rel="stylesheet" type="text/css" href="/styles/tabcontent.css" />
<link rel="stylesheet" type="text/css" href="/styles/edit.user.css" />

<script type="text/javascript" src="/js/tabcontent.js">
</script>

<style type="text/css">
  v\:* {ldelim}
    behavior:url(#default#VML);
 {rdelim} 
</style>

HEAD;


require_once '../includes/inc.initialization.support.php';
require_once 'inc.cls-gid.php';
//include_once 'GoogleMapAPI.class.php';

if (!checkNumber($_GET['uid'])) { displayError('Error: User ID not specified.'); }
else { define('USER_ID', @$_GET['uid']); }

$tpl->assign('user_id', USER_ID);


$editUserForm = new HTML_QuickForm('edit_user_form', 'post', $qfAction, NULL, NULL, TRUE);
$editUserForm->removeAttribute('name');
$editUserForm->applyFilter('__ALL__', 'trim');

$formArray = array('username', 'firstname', 'email', 'lastname', 'handle', 'city', 'state', 'ccode', 'user_comments');
$sql = 'SELECT username, firstname, email, IF(email_validation_key = "", "Yes", "No") AS verified, pending_email, lastname, handle, city, state, ccode, user_comments, abuse_lock FROM users WHERE uid = ?';
$formDefaults =& $db->getRow($sql, array(USER_ID));
$editUserForm->setDefaults($formDefaults);

//$editUserForm->freeze();

$editUserForm->addElement('hidden', 'uid', USER_ID);
$editUserForm->addElement('text', 'username', 'Username');
$editUserForm->freeze('username');

$editUserForm->addElement('text', 'firstname', 'First Name', array('maxlength' => 60));
$editUserForm->addRule('firstname', 'First name is required.', 'required');
$editUserForm->addRule('firstname', 'First name may not exceed 60 characters.', 'maxlength', 60);

$editUserForm->addElement('text', 'lastname', 'Last Name', array('maxlength' => 60));
$editUserForm->addRule('lastname', 'Last name is required.', 'required');
$editUserForm->addRule('lastname', 'Last name may not exceed 60 characters.', 'maxlength', 60);

$editUserForm->addElement('text', 'email', 'E-mail (Verified)');
$editUserForm->freeze('email');
/*$editUserForm->addRule('email', 'E-mail address is required.', 'required');
$editUserForm->addRule('email', 'The e-mail address entered appears to be invalid.', 'email', NULL, 'server');
$editUserForm->registerRule('check_email', 'function', 'checkNotExistsInUsersTbl');
$editUserForm->addRule('email', 'E-mail address already taken.', 'check_email', 'email');
$editUserForm->registerRule('email_bademail', 'function', 'checkBadEmail');
$editUserForm->addRule('email', 'You have entered an invalid e-mail address.', 'email_bademail');*/

//$editUserForm->addElement('text', 'verified', 'E-mail Verified');
//$editUserForm->freeze('verified');

$editUserForm->addElement('text', 'pending_email', 'E-mail Pending Verification');
$editUserForm->freeze('pending_email');

$editUserForm->addElement('text', 'handle', 'Handle/Nickname', array('maxlength' => 60));
$editUserForm->addRule('handle', 'Handle may not exceed 60 characters.', 'maxlength', 60);

$editUserForm->addElement('text', 'city', 'City', array('maxlength' => 60));
$editUserForm->addRule('city', 'City may not exceed 60 characters.', 'maxlength', 60);
//$editUserForm->freeze('city');


$statesArray =& $db->getCol('SELECT state FROM states ORDER BY state ASC');
$editUserForm->addElement('autocomplete', 'state', 'State/Province', $statesArray, array('maxlength' => 60));
$editUserForm->addRule('state', 'State may not exceed 60 characters.', 'maxlength', 60);
//$editUserForm->freeze('state');

$ccode =& $editUserForm->addElement('select', 'ccode', 'Country');
$ccode->loadArray(array(''   => 'Select country',
                        'us' => 'United States of America',
                        'ca' => 'Canada',
                        'gb' => 'United Kingdom'
                  ));
$ccode->loadQuery($db, 'SELECT country, ccode FROM countries WHERE ccode NOT IN ("us", "ca", "gb") ORDER BY country ASC');
//$editUserForm->freeze('ccode');

$abuselock =& $editUserForm->addElement('select', 'abuse_lock', 'Abuse Lock');
$abuselock->loadArray(array(0   => 'No', 1 => 'Yes'));

$editUserForm->addElement('textarea', 'user_comments', 'Comments', array('rows' => 5, 'cols' => '50', 'onkeydown' => 'textCounter(this,"progressbar1",4000)', 'onkeyup' => 'textCounter(this,"progressbar1",4000)', 'onfocus' => 'textCounter(this,"progressbar1",4000)'));
$editUserForm->addRule('user_comments', 'Comments may not exceed 4000 characters.', 'maxlength', 4000);
$editUserForm->addElement('static', 'note_user_comments', 'Maximum 4000 characters.<div id="progressbar1" class="progress"></div><script type="text/javascript">textCounter(document.getElementById("user_comments"),"progressbar1",4000)</script>');
$tpl->append('external_js', 'textarea.progressbar');
//$editUserForm->freeze('user_comments');

//$editUserForm->addElement('submit', 'submit', 'Save Changes', array('style' => 'display: none;'));
//$editUserForm->freeze();


$editUserForm->addElement('submit', 'submit', 'Save Changes');


if ($editUserForm->validate()) {
    $safeArray = safeExport($editUserForm, array('username' => $formDefaults['username'], 'uid' => USER_ID));
    $userUpdate = new updateRecord('users', 'uid');
    $userUpdate->addData($safeArray);
    $userUpdate->UpdateData();
}


$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$editUserForm->accept($renderer);
$tpl->assign('edit_user_form', $renderer->toArray());




/*
$suspensionForm = new HTML_QuickForm('edit_user_form', 'post', $qfAction, NULL, NULL, TRUE);
$suspensionForm->removeAttribute('name');
$suspensionForm->applyFilter('__ALL__', 'trim');
$suspensionForm->addElement('text', 'handle', 'Handle');
$suspensionForm->addElement('text', 'tid', 'Team ID');
$suspensionForm->addElement('text', 'gid', 'Game ID');
$suspensionForm->addElement('text', 'dispute_reference_did', 'Dispute Number');
$suspensionForm->addElement('text', 'reason', 'Reason');
$suspensionForm->addElement('text', 'rule_violation', 'Rule Violation #');

$seasonEnd =& $suspensionForm->addElement('hierselect', 'suspension_completion_season_number', 'Suspension Ending Season');
$sql = 'SELECT lid, league_title FROM leagues';
$selectLeague[''] = '&nbsp;';
$selectLeague += $db->getAssoc($sql);

$sql = 'SELECT lid, sid, season_title FROM seasons';
$selectSeason[''][''] = '&nbsp;';
$seasons =& $db->getAll($sql);
foreach ($seasons as $value) {
    $selectSeason[$value['lid']][$value['sid']] = $value['season_title'];
}

$sql = 'SELECT lid, sid, sch_id, stg_short_desc FROM schedules';
$selectWeek[''][''][''] = '&nbsp;';
$seasons =& $db->getAll($sql);
foreach ($seasons as $value) {
    $selectWeek[$value['lid']][$value['sid']][$value['sch_id']] = $value['stg_short_desc'];
}
$seasonEnd->setOptions(array($selectLeague, $selectSeason, $selectWeek));

$suspensionForm->addElement('submit', 'submit', 'Add note');

$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$suspensionForm->accept($renderer);
$tpl->assign('suspension_form', $renderer->toArray());

$sql = 'SELECT * FROM suspensions WHERE suspensions.uid = ? ORDER BY create_date_gmt DESC';
$suspensions =& $db->getAll($sql, array(USER_ID));
$tpl->assign('suspensions', $suspensions);
*/

if (isset($_POST['remove_roster']) && !empty($_POST['rid'])) {
    $rid = $_POST['rid'];
    if (!checkNumber($rid)) displayError('Player to remove not specified.');
    $sql = 'SELECT uid, tid FROM rosters WHERE rid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $ridArray =& $db->getRow($sql, array($rid));
    $onTeamRosterUID = $ridArray['uid'];
    $tidRem = $ridArray['tid'];
    if (!$onTeamRosterUID) displayError('Player to remove not active on this roster.');

    $rosterUpdateArray = array(
                               'rid' => $rid,
                               'leave_date_gmt' => mysqlNow(),
                               'removed_by_aid' => AID
                              );
    $recordUpdate = new updateRecord('rosters', 'rid');
    $recordUpdate->addData($rosterUpdateArray);
    $recordUpdate->UpdateData();

    $sql = 'SELECT captain_uid FROM teams WHERE tid = ? LIMIT 1';
    $teamCaptUID =& $db->getOne($sql, array($tidRem));
    if ($onTeamRosterUID == $teamCaptUID) {
        $teamDataUpdateArray = array(
                                     'tid' => $tidRem,
                                     'captain_uid' => NULL
                                    );
        $recordUpdate = new updateRecord('teams', 'tid');
        $recordUpdate->addData($teamDataUpdateArray);
        $recordUpdate->UpdateData();
        $teamData['captain_uid'] = NULL;
    }
    unset($recordUpdate);
    redirect('/edit.user.php?uid=' . USER_ID);
}

// get list of leagues
$sql = 'SELECT lid, league_title FROM leagues ORDER BY league_title';
$lids =& $db->getAssoc($sql);

$sql = 'SELECT lid, tid, name FROM teams WHERE deleted = 0 ORDER BY name';
$tidsArray =& $db->getAll($sql);

foreach ($tidsArray as $tidArray) {
    $tids[$tidArray['lid']][$tidArray['tid']] = $tidArray['name'];
}

$joinTeamForm = new HTML_QuickForm('join_team_form', 'post', $qfAction, NULL, NULL, TRUE);
$joinTeamForm->removeAttribute('name');
$joinTeamForm->applyFilter('__ALL__', 'trim');

$teamSel =& $joinTeamForm->addElement('hierselect', 'team_selector', 'Add to league');
$teamSel->setOptions(array($lids, $tids));

$joinTeamForm->addElement('text', 'handle', 'Handle/Nickname', array('maxlength' => 30));
$joinTeamForm->addRule('handle', 'Handle may not exceed 30 characters.', 'maxlength', 30);

if ($joinTeamForm->isSubmitted()) {
    $teamSelArray = $joinTeamForm->exportValue('team_selector');
    $lid = $teamSelArray[0];
    $tid = $teamSelArray[1];
    $gidCls = new gidRoster($joinTeamForm, $lid);
    $gidCls->gidForm();
} else {
    $joinTeamForm->addElement('text', 'gid', 'Game ID', array('size' => 18));
}

$joinTeamForm->addElement('submit', 'submit', 'Add');

if ($joinTeamForm->validate() && !$alreadyOnTeam) {
    // check if team exists
    if (checkNumber($tid)) {
        $sql = 'SELECT TRUE FROM teams WHERE deleted = 0 AND tid = ?';
        $teamExists =& $db->getOne($sql, array($tid));
    }

    $gidValue = $joinTeamForm->exportValue('gid');
    $gidCheck = $gidCls->gidInUse($gidValue);

    $sql = 'SELECT TRUE FROM rosters INNER JOIN teams USING (tid) WHERE uid = ? AND lid = ? AND leave_date_gmt = "0000-00-00 00:00:00" LIMIT 1';
    $alreadyOnTeam =& $db->getOne($sql, array(USER_ID, $lid));

    if ($teamExists && !$gidCheck && !$alreadyOnTeam) {
        $valuesArray = array(
                            'uid' => USER_ID,
                            'tid' => $tid,
                            'join_date_gmt' => gmdate('c', mktime()),
                            'leave_date_gmt' => '0000-00-00 00:00:00',
                            'handle' => $joinTeamForm->exportValue('handle'),
                            'added_by_aid' => AID,
                            'gid' => $gidValue
                            );
        $insertRecord = new InsertRecord();
        $insertRecord->insertData('rosters', $valuesArray);
    } else {
        if (!$teamExists) $joinTeamForm->setElementError('team_selector', 'Team not found.');
        if ($gidCheck) $joinTeamForm->setElementError('gid', 'This '. $gidCls->gidName .' is already in use in this league.');
        if ($alreadyOnTeam) $joinTeamForm->setElementError('team_selector', 'This player is already on a team in this league.');
    }

}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$joinTeamForm->accept($renderer);
$tpl->assign('join_team_form', $renderer->toArray());



// find teams this player is on:
$sql = <<<SQL
            SELECT teams.tid, rosters.rid, leagues.league_title, leagues.lid, teams.name, rosters.handle, rosters.gid, rosters.anticheatuserid, UNIX_TIMESTAMP(rosters.join_date_gmt) AS unix_join_date_gmt FROM leagues INNER JOIN teams USING (lid) INNER JOIN rosters USING (tid) WHERE rosters.uid = ? AND leave_date_gmt = "0000-00-00 00:00:00"
SQL;
$activeTeams =& $db->getAll($sql, array(USER_ID));
$tpl->assign('active_teams', $activeTeams);



$sql = <<<SQL
            SELECT  
                    rosters.gid, 
                    rosters.anticheatuserid,
                    rosters.handle,
                    UNIX_TIMESTAMP(rosters.join_date_gmt) AS unix_join_date_gmt, 
                    UNIX_TIMESTAMP(rosters.leave_date_gmt) AS unix_leave_date_gmt, 
                    rosters.removed_by_aid,
                    teams.name AS team_name,
                    teams.tid,
                    leagues.lid, 
                    leagues.lgname,
                    a1.admin_name AS added_by_admin,
                    a2.admin_name AS removed_by_admin
            FROM rosters
            INNER JOIN teams USING (tid)
            INNER JOIN leagues USING (lid)
            LEFT JOIN admins AS a1 ON (rosters.added_by_aid = a1.aid)
            LEFT JOIN admins AS a2 ON (rosters.removed_by_aid = a2.aid)
            WHERE rosters.uid = ?
SQL;
$rosterLog =& $db->getAll($sql, array(USER_ID));
$tpl->assign('roster_log', $rosterLog);








$adminNotesForm = new HTML_QuickForm('admin_notes_form', 'post', $qfAction, NULL, NULL, TRUE);
$adminNotesForm->removeAttribute('name');
$adminNotesForm->applyFilter('__ALL__', 'trim');
$adminNotesForm->addElement('textarea', 'comment', 'Comment', array('rows' => 5, 'cols' => '50'));
$adminNotesForm->addElement('submit', 'submit', 'Add note');
$adminNotesForm->addRule('comment', 'Please enter a comment.', 'required');
if ($adminNotesForm->validate()) {
    $adminNotesValues = array(
                              'uid' => USER_ID,
                              'aid' => AID,
                              'create_date_gmt' => mysqlNow(),
                              'comment' => $adminNotesForm->exportValue('comment')
                             );
    $res = $db->autoExecute('users_admin_notes', $adminNotesValues, DB_AUTOQUERY_INSERT);
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$adminNotesForm->accept($renderer);
$tpl->assign('admin_notes_form', $renderer->toArray());

$sql = 'SELECT admin_name, aid, UNIX_TIMESTAMP(users_admin_notes.create_date_gmt) AS unix_create_date_gmt, `comment` FROM users_admin_notes INNER JOIN admins USING (aid) WHERE users_admin_notes.uid = ? ORDER BY uanid DESC';
$adminNotes =& $db->getAll($sql, array(USER_ID));
$tpl->assign('admin_notes', $adminNotes);


$sql = 'SELECT INET_NTOA(address) AS address, hostname, browser, UNIX_TIMESTAMP(timestamp_gmt) AS unix_timestamp_gmt FROM ip LEFT JOIN ip_browsers USING (brwsid) LEFT JOIN ip_location USING (address) WHERE uid = ? ORDER BY timestamp_gmt DESC';
$loginHistory =& $db->getAll($sql, array(USER_ID));
$tpl->assign('login_history', $loginHistory);







$sql = 'SELECT `field`, from_value, to_value, tablename, tablePk, tablePkId, UNIX_TIMESTAMP(timestamp_gmt) AS unix_timestamp_gmt, `type` FROM users_action_log WHERE uid = ? ORDER BY timestamp_gmt DESC';
$userActionLog =& $db->getAll($sql, array(USER_ID));
$tpl->assign('user_action_log', $userActionLog);




/*
//Brian's:
//$developmentservergoogleapikey = 'ABQIAAAAMUbbihM0flbzhn2mCS_NsRRRaXwZuACmCcGK1DKQx5agkviErxTjjpMVVxTVv8wKCINTvn7DjNDR4g';
//$productionservergoogleapikey = 'ABQIAAAAMUbbihM0flbzhn2mCS_NsRQc3byW4s50P36EUhEKmctf5VuEvhS28O6VRlVa0koegDPeuy8ebpl9PA';

$developmentservergoogleapikey = 'AIzaSyBUYpQt53dEmfZ5kdc2N8y4GwecRLkiT0k';
//SERVER: $productionservergoogleapikey = 'AIzaSyBlLBPkmzka4HqOsrRQNgsfavGxgEtEciY';
$productionservergoogleapikey = 'AIzaSyBUYpQt53dEmfZ5kdc2N8y4GwecRLkiT0k';

$map = new GoogleMapAPI();
$map->enableOnLoad();
$map->setInfoWindowTrigger('mouseover');
$map->disableDirections();
$map->disableZoomEncompass();
$map->setZoomLevel(3);
if (PHYSICAL_SITE == 'DEVELOPMENT') $map->setAPIKey($developmentservergoogleapikey);
else $map->setAPIKey($productionservergoogleapikey);

$sql = <<<SQL
            SELECT latitude, longitude, INET_NTOA(address) AS address, hostname, geo_city AS city, regions.name AS region, country, COUNT(1) AS hits,
                   (
                    SELECT UNIX_TIMESTAMP(timestamp_gmt) FROM ip WHERE (ip.uid = ? AND ip_location.address = ip.address) ORDER BY timestamp_gmt DESC LIMIT 1
                   ) AS unix_last_login_gmt
            FROM `ip` 
            INNER JOIN `ip_location` USING (address) 
            LEFT JOIN regions ON (geo_cc = regions.cc AND geo_region = region_code) 
            LEFT JOIN countries ON (geo_cc = countries.ccode) 
            WHERE ip.uid = ?
            AND (latitude IS NOT NULL AND longitude IS NOT NULL) 
            GROUP BY latitude, longitude, address, hostname, city, region, country, unix_last_login_gmt
SQL;
$geoLocations =& $db->getAll($sql, array(USER_ID, USER_ID));

if (empty($geoLocations)) $tpl->assign('geo_locations_empty', TRUE);
else {
    foreach ($geoLocations as $location) {
    $locationHTML = '<b>' . $location['hostname'] . '</b><br />'
                  . $location['address'] . '<br />'
                  . $location['city'] . ', '
                  . $location['region'] . ', '
                  . ($location['country'] == 'United States of America' ? 'USA' : $location['country']) . '<br />'
                  . 'Logins from this location: ' . $location['hits'] . '<br />'
                  . 'Last login from this location: ' . smarty_modifier_converted_timezone($location['unix_last_login_gmt']);
        $map->addMarkerByCoords($location['longitude'], $location['latitude'], $title = $location['hostname'] . " ({$location['hits']})", $locationHTML);
    }
}

$extra_head[] = $map->getOnLoad();
$extra_head[] = $map->getHeaderJS();
$extra_head[] = $map->getMapJS();
$tpl->assign('google_map_sidebar',$map->getSidebar());
$tpl->assign('google_map',$map->getMap());*/

$tpl->assign('extra_body_attr', 'onload="onLoad()"');
require_once 'inc.initialization.display.php';





displayTemplate('edit.user');