<?php


require_once '../includes/inc.initialization.php';

if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { define('LID', $_GET['lid']); }

$qsArray['joined_roster_in_last_month'] = <<<SQL
    SELECT users.uid,
           users.email,
           teams.approved,
           teams.inactive,
           (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count,
           teams.tid,
           divisions.division_title,
           conferences.conference_title,
           groups.group_title,
           teams.name,
           teams.tag,
           UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt,
           users.username,
           users.firstname,
           users.lastname,
           rosters.handle,
           organizations.irc
    FROM teams
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN users ON (rosters.uid = users.uid)
    LEFT JOIN divisions USING (divid)
    LEFT JOIN conferences USING (cfid)
    LEFT JOIN groups USING (grpid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND rosters.join_date_gmt > DATE_SUB(now(), INTERVAL 1 MONTH) AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
    ORDER BY rosters.join_date_gmt desc
SQL;

$qsArray['left_roster_in_last_month'] = <<<SQL
    SELECT users.uid, users.email, teams.approved, teams.inactive, (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count, teams.tid, divisions.division_title, conferences.conference_title, groups.group_title, teams.name, teams.tag, UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, users.username, users.firstname, users.lastname, rosters.handle, organizations.irc
    FROM teams
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN users ON (rosters.uid = users.uid)
    LEFT JOIN divisions USING (divid)
    LEFT JOIN conferences USING (cfid)
    LEFT JOIN groups USING (grpid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND rosters.leave_date_gmt > DATE_SUB(now(), INTERVAL 1 MONTH) 
    ORDER BY rosters.leave_date_gmt desc
SQL;

$qsArray['captains_inactive'] = <<<SQL
    SELECT users.uid, users.email, teams.approved, teams.inactive, (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count, teams.tid, divisions.division_title, conferences.conference_title, groups.group_title, teams.name, teams.tag, UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, users.username, users.firstname, users.lastname, rosters.handle, organizations.irc
    FROM teams
    LEFT JOIN users ON (teams.captain_uid = users.uid)
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN divisions USING (divid)
    LEFT JOIN conferences USING (cfid)
    LEFT JOIN groups USING (grpid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND teams.inactive = 1 AND teams.captain_uid = rosters.uid AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
    ORDER BY tid
SQL;

$qsArray['captains_division_unassigned'] = <<<SQL
    SELECT users.uid, users.email, teams.approved, teams.inactive, (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count, teams.tid, divisions.division_title, conferences.conference_title, groups.group_title, teams.name, teams.tag, UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, users.username, users.firstname, users.lastname, rosters.handle, organizations.irc
    FROM teams
    LEFT JOIN users ON (teams.captain_uid = users.uid)
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN divisions USING (divid)
    LEFT JOIN conferences USING (cfid)
    LEFT JOIN groups USING (grpid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND teams.divid IS NULL AND teams.captain_uid = rosters.uid AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
SQL;

$qsArray['captains_active'] = <<<SQL
    SELECT users.uid, users.email, teams.approved, teams.inactive, (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count, teams.tid, divisions.division_title, conferences.conference_title, groups.group_title, teams.name, teams.tag, UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, users.username, users.firstname, users.lastname, rosters.handle, organizations.irc
    FROM teams
    INNER JOIN divisions USING (divid)
    INNER JOIN conferences USING (cfid)
    INNER JOIN groups USING (grpid)
    LEFT JOIN users ON (teams.captain_uid = users.uid)
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND teams.approved = 1 AND teams.inactive = 0 AND teams.captain_uid = rosters.uid AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
SQL;

$qsArray['captains_active_no_forfeits'] = <<<SQL
    SELECT users.uid, users.email, teams.approved, teams.inactive, (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count, teams.tid, divisions.division_title, conferences.conference_title, groups.group_title, teams.name, teams.tag, UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, users.username, users.firstname, users.lastname, rosters.handle, organizations.irc
    FROM teams
    INNER JOIN divisions USING (divid)
    INNER JOIN conferences USING (cfid)
    INNER JOIN groups USING (grpid)
    LEFT JOIN users ON (teams.captain_uid = users.uid)
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND teams.approved = 1 AND teams.inactive = 0 AND teams.captain_uid = rosters.uid AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
    AND (
            SELECT (forfeit_losses = 0) AND (wins + losses > 0)
            FROM teams_standings_summary INNER JOIN seasons USING (sid)
            WHERE teams_standings_summary.tid = teams.tid AND seasons.active = 1 AND teams_standings_summary.preseason = 0
        ) = 1
SQL;

$qsArray['teams_no_captains'] = <<<SQL
    SELECT users.uid, users.email, teams.approved, teams.inactive, (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count, teams.tid, divisions.division_title, conferences.conference_title, groups.group_title, teams.name, teams.tag, UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, users.username, users.firstname, users.lastname, rosters.handle, organizations.irc
    FROM teams
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN users ON (rosters.uid = users.uid)
    LEFT JOIN divisions USING (divid)
    LEFT JOIN conferences USING (cfid)
    LEFT JOIN groups USING (grpid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND teams.captain_uid IS NULL AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
SQL;

$qsArray['players_in_unassigned_group'] = <<<SQL
    SELECT users.uid, users.email, teams.approved, teams.inactive, (SELECT COUNT(*) FROM rosters WHERE rosters.tid = teams.tid AND rosters.leave_date_gmt = '0000-00-00 00:00:00') AS roster_count, teams.tid, divisions.division_title, conferences.conference_title, groups.group_title, teams.name, teams.tag, UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, users.username, users.firstname, users.lastname, rosters.handle, organizations.irc
    FROM teams
    LEFT JOIN rosters ON (teams.tid = rosters.tid)
    LEFT JOIN users ON (rosters.uid = users.uid)
    LEFT JOIN divisions USING (divid)
    LEFT JOIN conferences USING (cfid)
    LEFT JOIN groups USING (grpid)
    LEFT JOIN organizations USING (orgid)
    WHERE teams.lid = ? AND teams.deleted = 0 AND rosters.leave_date_gmt = '0000-00-00 00:00:00'
      AND teams.grpid IS NULL
SQL;

$tpl->assign('qselector', $qsArray);

$qsSQL = $qsArray[$_GET['query']];

/*
switch ($_GET['query']) {
    case 'captains_inactive':

    break;

    case 'teams_no_captains':

    break;

    case 'captains_division_unassigned':

    break;

    case 'captains_active':
        
    break;
}
*/

if (!empty($qsSQL)) {
    $qsResults =& $db->getAll($qsSQL, array(LID));
    $tpl->assign('qs_results', $qsResults);
}

displayTemplate('query.selector');
