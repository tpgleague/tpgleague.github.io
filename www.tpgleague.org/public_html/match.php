<?php

//TODO: Bye week wins:  http://www.tpgleague.org/dod6/match/6316/

require_once '../includes/inc.initialization.php';
require_once 'inc.func-schedule.php';
$tpl->append('external_css', 'match');

// Check that a numeric "match number" was provided
if (!checkNumber($_GET['mid'])) { displayError('The match number provided is not valid.'); }
else { define('MID', $_GET['mid']); }

// Get the league and schedule information
$sql = <<<SQL
    SELECT seasons.lid, sch_id, league_title
    FROM seasons
    INNER JOIN schedules USING (sid)
    INNER JOIN matches USING (sch_id)
    INNER JOIN leagues ON schedules.lid = leagues.lid
    WHERE `mid` = ? AND  matches.deleted = 0 LIMIT 1
SQL;
$match =& $db->getRow($sql, array(MID));
define('LID', $match['lid']);
define('SCH_ID', $match['sch_id']);

// If the match number corresponds to a non-existent or deleted match let the user know
if(!checkNumber($match['lid'])) { displayError('No match found under ' . $_GET['mid'] . ". The match number is either invalid or the match has been deleted." ); }

// Get the information about the match from the database
$sql = <<<SQL
    SELECT mid,
           matches.deleted,
           unix_timestamp(start_date_gmt) as start_date_gmt,
           win_tid,
           forfeit_away,
           matches.home_tid,
           matches.away_tid,
           forfeit_home,
           admins.admin_name,
           unix_timestamp(report_date_gmt) as report_date_gmt,
           report_by_uid,
           users.username,
           users.firstname,
           users.handle as main_handle,
           rosters.handle,
           users.lastname,
           confirmed_mpid,
           match_comments,
           IF(report_by_tid = away_tid,
              (SELECT teams.name FROM teams WHERE teams.tid = away_tid LIMIT 1),
              (SELECT teams.name FROM teams WHERE teams.tid = home_tid LIMIT 1)) AS reporting_team,
           (SELECT name FROM teams WHERE tid=away_tid LIMIT 1) AS away_name,
           (SELECT name FROM teams WHERE tid=home_tid LIMIT 1) AS home_name,
           map_title,
           maps.filename,
           stg_type,
           stg_short_desc
    FROM matches
        LEFT JOIN users ON (report_by_uid = users.uid)
        LEFT JOIN rosters ON (report_by_uid = rosters.uid and report_by_tid = rosters.tid and (rosters.leave_date_gmt = '0000-00-00 00:00:00' or (rosters.leave_date_gmt > report_date_gmt and rosters.join_date_gmt < start_date_gmt)))
        LEFT JOIN admins ON (report_by_aid = admins.aid)
        INNER JOIN schedules on schedules.sch_id = matches.sch_id
        INNER JOIN maps on schedules.mapid = maps.mapid
        INNER JOIN teams

    WHERE mid = ? LIMIT 1
SQL;

$matchData =& $db->getRow($sql, array(MID));

// Get the match date and report date
$reportDate = 'Unreported';
if ($matchData['report_date_gmt']) $reportDate = date('m-d-Y h:i T', $matchData['report_date_gmt']);
$matchDate = date('m-d-Y h:i T', $matchData['start_date_gmt']);


// Assigned general information about the match
$tpl->assign('match_number', (int)$matchData['mid']);
$tpl->assign('report_date', $reportDate);
$tpl->assign('match_date', $matchDate);
$tpl->assign('map_title', $matchData['map_title']);
$tpl->assign('map_path', $matchData['filename']);
$tpl->assign('stg_type', $matchData['stg_type']);
$tpl->assign('stg_short_desc', $matchData['stg_short_desc']);
           
if ($matchData['username'])
{
    // Attempt to get the roster handle. Fall back on main handle. If neither exist use generic "User#____"
    if ($matchData['handle'])
    {
        $tpl->assign('reporting_user', escape($matchData['handle']));
    }
    else if ($matchData['main_handle'])
    {
        $tpl->assign('reporting_user', escape($matchData['main_handle']));
    }
    else
    {
        $tpl->assign('reporting_user', 'User#' . $matchData['report_by_uid']);
    }
    
    $tpl->assign('reporting_uid', escape($matchData['report_by_uid']));
    $tpl->assign('reporting_team', escape($matchData['reporting_team']));
}
$tpl->assign('reporting_admin_name', escape($matchData['admin_name']));

// ... Determine if there was a forfeit and if so populate the name
if ($matchData['forfeit_away'])
{
    $tpl->assign('forfeit_name', $matchData['away_name']);
}
else if ($matchData['forfeit_home'])
{
    $tpl->assign('forfeit_name', $matchData['home_name']);
}
else
{
    $tpl->assign('forfeit_name', '');
}

$tpl->assign('match_comments', $matchData['match_comments']);


// Get and display the scores for each half
$sql = 'SELECT ms.msid, ms.away_score, ms.home_score, COALESCE(away_ls.side, \'Away Side\') away_side, COALESCE(home_ls.side, \'Home Side\') home_side FROM matches_scores ms LEFT JOIN leagues_sides away_ls on away_ls.lsid = ms.away_lsid LEFT JOIN leagues_sides home_ls on home_ls.lsid = ms.home_lsid WHERE ms.mid = ?';
$scores =& $db->getAll($sql, array(MID));

$populate = array();
$i = 1;
foreach ($scores as $half) {
    $populate = array_merge($populate, array(
                        'side_selector_h'.$i.'a' => $half['away_side'],
                        'side_selector_h'.$i.'h' => $half['home_side'],
                        'h'.$i.'a_score' => $half['away_score'],
                        'h'.$i.'h_score' => $half['home_score']
                        ));
    ++$i;
}

$tpl->assign('away_team_name', escape($matchData['away_name']));
$tpl->assign('home_team_name', escape($matchData['home_name']));
$tpl->assign('away_tid', $matchData['away_tid']);
$tpl->assign('home_tid', $matchData['home_tid']);
$tpl->assign('side_selector_h1a', $populate['side_selector_h1a']);
$tpl->assign('side_selector_h1h', $populate['side_selector_h1h']);
$tpl->assign('side_selector_h2a', $populate['side_selector_h2a']);
$tpl->assign('side_selector_h2h', $populate['side_selector_h2h']);
$tpl->assign('h1a_score', $populate['h1a_score']);
$tpl->assign('h1h_score', $populate['h1h_score']);
$tpl->assign('h2a_score', $populate['h2a_score']);
$tpl->assign('h2h_score', $populate['h2h_score']);
$tpl->assign('away_score', $populate['h1a_score'] + $populate['h2a_score']);
$tpl->assign('home_score', $populate['h1h_score'] + $populate['h2h_score']);

// Update the title of the page
$tpl->assign('title', $match['league_title'] . ' Match Number ' . (int)$matchData['mid']);

// Display the page
displayTemplate('match', NULL, 0, TRUE);