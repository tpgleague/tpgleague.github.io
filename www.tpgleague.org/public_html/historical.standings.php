<?php

require_once '../includes/inc.initialization.php';
$tpl->append('external_css', 'standings');

if (!checkNumber($_GET['sid'])) { displayError('Error: Sseason ID not specified.'); }
else { define('SID', $_GET['sid']); }

$preseason = $_GET['preseason'] ? 1:0;

$sql = 'SELECT lid FROM seasons WHERE sid = ?';
$lid =& $db->getOne($sql, array(SID));

//$sql = 'SELECT * FROM teams_standings_summary INNER JOIN teams_standings_names_summary USING (tid, sid, preseason) WHERE sid = ? AND preseason = ?';
$sql = <<<SQL
            SELECT SQL_CACHE teams_standings_summary.grpid, teams_standings_summary.tid, teams_standings_names_summary.name, teams_standings_names_summary.tag, wins, losses, ties, forfeit_wins, forfeit_losses, (wins <> 0 OR losses <> 0 OR ties <> 0) AS matches_played, points_for, points_against, CAST(points_for - points_against AS SIGNED) AS points_difference
            FROM seasons
            INNER JOIN teams_standings_summary ON (
                                                    teams_standings_summary.sid = seasons.sid
                                                  )
            INNER JOIN teams_standings_names_summary ON (
                                                            teams_standings_names_summary.sid = seasons.sid AND
                                                            teams_standings_names_summary.preseason = teams_standings_summary.preseason AND
                                                            teams_standings_names_summary.tid = teams_standings_summary.tid
                                                        )
            WHERE lid = ? AND seasons.sid = ? AND teams_standings_summary.preseason = ? AND teams_standings_summary.grpid IS NOT NULL
            ORDER BY matches_played DESC, wins DESC, losses ASC, ties DESC, forfeit_losses ASC, forfeit_wins ASC, points_difference DESC, points_for DESC
SQL;
$historicalStandings =& $db->getAssoc($sql, NULL, array($lid, SID, $preseason), NULL, TRUE);


$sql = <<<SQL
    SELECT league_title, season_title 
    FROM leagues 
    INNER JOIN seasons USING (lid) 
    WHERE lid = ? AND seasons.sid = ?
    LIMIT 1
SQL;
$seasonInfo =& $db->getRow($sql, array($lid, SID));
$league_title = $seasonInfo['league_title'];
$season_title = $seasonInfo['season_title'];


$sql = <<<SQL
    SELECT divid, division_title 
    FROM divisions 
    WHERE lid = ? AND divisions.inactive = 0 
    ORDER BY divisions.sort_order DESC
SQL;
$divisions =& $db->getAssoc($sql, TRUE, array($lid));


$sql = <<<SQL
    SELECT divid, cfid, conference_title 
    FROM conferences 
    INNER JOIN divisions USING (divid) 
    WHERE divisions.lid = ? AND conferences.inactive = 0 AND divisions.inactive = 0 
    ORDER BY conferences.sort_order DESC
SQL;
$conferences =& $db->getAssoc($sql, NULL, array($lid), NULL, TRUE);


$sql = <<<SQL
    SELECT cfid, grpid, group_title 
    FROM groups 
    INNER JOIN conferences USING (cfid) 
    INNER JOIN divisions USING (divid) 
    WHERE divisions.lid = ? AND groups.inactive = 0 AND conferences.inactive = 0 
    ORDER BY groups.sort_order
SQL;
$groups =& $db->getAssoc($sql, NULL, array($lid), NULL, TRUE);



$tpl->assign('standings_league_title', $league_title);
$tpl->assign('standings_season_title', $season_title);
$tpl->assign('standings_groups', $groups);
$tpl->assign('standings_divisions', $divisions);
$tpl->assign('standings_conferences', $conferences);
$tpl->assign('standings_teams', $historicalStandings);
$tpl->assign('title', $league_title . ' Historical Standings');

displayTemplate('standings');
