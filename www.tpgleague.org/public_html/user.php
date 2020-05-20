<?php
require_once '../includes/inc.initialization.php';

// Check that a numeric "user id" was provided
if (!checkNumber($_GET['userid'])) { displayError('The user id provided is not valid.'); }
else { define('USERID', $_GET['userid']); }
  
// Get the user information
$sql = <<<SQL
    SELECT
        users.firstname,
        users.lastname,
        users.handle,
        users.user_comments,
        users.ccode,
        users.city,
        users.state,
        users.deleted,
        users.hide_lastname,
        users.abuse_lock,
        users.steam_profile_url,
        users.user_avatar_url,
        UNIX_TIMESTAMP(create_date_gmt) as join_date,
        c.country
    FROM users
    LEFT JOIN countries AS c ON c.ccode = users.ccode
    WHERE uid = ?
SQL;
$userData =& $db->getRow($sql, array(USERID));
$tpl->assign('user_data', $userData);

// Get all active roster information
$rostersSql = <<<SQL
        SELECT DISTINCT 
            leagues.lgname AS leagues_lgname,
            leagues.league_title,
            teams.tid AS teams_tid,
            teams.name AS teams_name,
            teams.tag AS teams_tag,
            rosters.handle AS rosters_handle,
            rosters.gid AS rosters_gid,
            UNIX_TIMESTAMP(rosters.join_date_gmt) AS rosters_join_date_gmt,
            UNIX_TIMESTAMP(rosters.leave_date_gmt) AS rosters_leave_date_gmt
        FROM rosters 
            LEFT JOIN teams USING (tid) 
            LEFT JOIN leagues USING (lid)
        WHERE leagues.inactive = 0 AND leagues.deleted = 0
            AND teams.approved = 1
            AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
            AND rosters.uid = ?
SQL;
$rostersData =& $db->getAll($rostersSql, array(USERID));
$tpl->assign('roster_data', $rostersData);

// Get all game IDs used
$gameIdsSql = <<<SQL
    SELECT DISTINCT gid
    FROM rosters 
        LEFT JOIN teams USING (tid) 
        LEFT JOIN leagues USING (lid)
    WHERE leagues.inactive = 0 AND leagues.deleted = 0
        AND teams.approved = 1 AND  uid = ?
SQL;
$gameIdsData =& $db->getAll($gameIdsSql, array(USERID));
$tpl->assign('game_ids_data', $gameIdsData);
    
// Update the title of the page
$tpl->assign('title', escape($userData['handle']));

// Display the page
displayTemplate('user', NULL, 0, TRUE);
