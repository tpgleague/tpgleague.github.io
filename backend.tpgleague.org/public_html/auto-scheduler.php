<?php

// to-do:
// 


require_once '../includes/inc.initialization.support.php';
require_once 'inc.func-schedule.php';

if (!checkNumber(@$_GET['sch_id'])) { displayError('Error: Schedule ID not specified.'); }
else { @define('SCH_ID', @$_GET['sch_id']); }

if (!checkNumber(@$_GET['cfid'])) { displayError('Error: Conference ID not specified.'); }
else { @define('CFID', @$_GET['cfid']); }


// check access here



require_once '../includes/inc.initialization.display.php';



class AutoScheduler {
	var $teamList = array();
	var $sch_id;
	var $lid;
	var $cfid;
	var $sid;
	var $stg_number;
	var $stg_type;
    var $stg_preseason;

    var $divisionTitle;
    var $conferenceTitle;
    var $seasonTitle;
    var $bbCode;

    function __construct($cfid, $sch_id)
    {
        global $db;
        $sql = 'SELECT * FROM schedules INNER JOIN seasons USING (sid) INNER JOIN leagues ON (seasons.lid = leagues.lid) WHERE sch_id = '. $db->quoteSmart($sch_id) .' LIMIT 1';
        $scheduleData =& $db->getRow($sql);
        $this->sch_id = $sch_id;
        $this->cfid = $cfid;
        $this->lid = $scheduleData['lid'];
        $this->sid = $scheduleData['sid'];
        $this->stg_number = $scheduleData['stg_number'];
        $this->stg_type = $scheduleData['stg_type'];
        $stageMatchDateGMT = $scheduleData['stg_match_date_gmt'];
        $minRosterSize = $scheduleData['format'];
        $stgDesc = $scheduleData['stg_short_desc'];
        $this->seasonTitle = $scheduleData['season_title'];

        if (!in_array($scheduleData['stg_type'], array('Preseason', 'Regular'))) exit('Auto-scheduler cannot be used with this stage type.');
        $this->stg_preseason = $scheduleData['stg_type'] == 'Preseason' ? 1 : 0;

        // find last week's sch_id, if exists:
        $sql = 'SELECT sch_id, UNIX_TIMESTAMP(stg_match_date_gmt) AS unix_stg_match_date_gmt '
             . 'FROM schedules WHERE sid = ? AND schedules.deleted = 0 AND stg_number <> ? AND stg_type = ? AND stg_match_date_gmt < ? '
             . 'ORDER BY stg_match_date_gmt DESC LIMIT 1';
        $previousScheduleData =& $db->getRow($sql, array($this->sid, $this->stg_number, $this->stg_type, $stageMatchDateGMT));
        $previousSchID = $previousScheduleData['sch_id'];
        $previousUnixStageMatchDateGMT = $previousScheduleData['unix_stg_match_date_gmt'];

        if ($previousSchID && $previousUnixStageMatchDateGMT < gmmktime()) {
            // Last week has already taken place (not true when making entire season schedule at once)...
            // Force the admin to take care of teams in pending queue for this conference:
            $sql = 'SELECT TRUE FROM `matches_pending` WHERE sch_id = ? AND cfid = ? AND deleted = 0 AND `mid` IS NULL LIMIT 1';
            $previousPending =& $db->getOne($sql, array($previousSchID, $this->cfid));
            if ($previousPending) exit('There are teams still in pending queue from last week in this conference. Please take care of that before running the auto-scheduler for this week.');
        }

        $sql = 'SELECT division_title, conference_title, league_title FROM leagues INNER JOIN divisions USING (lid) INNER JOIN conferences USING (divid) WHERE cfid = ?';
        $titles =& $db->getRow($sql, array($this->cfid));
        $this->conferenceTitle = $titles['conference_title'];
        $this->divisionTitle = $titles['division_title'];
        $this->leagueTitle = $titles['league_title'];
        
        echo "Preparing to schedule $this->leagueTitle - $this->divisionTitle division - $this->conferenceTitle conference - $this->seasonTitle $stgDesc.\r\n---\r\n";
        ob_flush();
	    flush();

        // this would be a good time to unschedule teams against byes and remove them from pending queue.
        // find teams already pending or with bye week and echo this.
        $sql = 'SELECT name FROM teams INNER JOIN matches_pending USING (tid) WHERE sch_id = ? AND matches_pending.cfid = ? AND mid IS NULL AND matches_pending.deleted = 0';
        $teamsPending =& $db->getCol($sql, 0, array($this->sch_id, $this->cfid));
        foreach ($teamsPending as $teamName) {
            echo escape($teamName) . " has been removed from pending queue.\r\n";
            ob_flush();
	        flush();
        }
        $sql = 'UPDATE matches_pending SET deleted = 1 WHERE sch_id = ? AND cfid = ? AND mid IS NULL';
        $res =& $db->query($sql, array($this->sch_id, $this->cfid));

        $sql = 'SELECT name FROM teams INNER JOIN matches ON (away_tid = teams.tid OR home_tid = teams.tid) WHERE sch_id = ? AND teams.cfid = ? AND (away_tid = 0 OR home_tid = 0) AND matches.deleted = 0';
        $teamsWithByes =& $db->getCol($sql, 0, array($this->sch_id, $this->cfid));
        foreach ($teamsWithByes as $teamName) {
            echo escape($teamName) . " has been unscheduled from their bye week.\r\n";
            ob_flush();
	        flush();
        }
        $sql = 'UPDATE matches INNER JOIN teams ON ((away_tid = teams.tid AND home_tid = 0) OR (home_tid = teams.tid AND away_tid = 0)) SET matches.deleted = 1 WHERE sch_id = ? AND teams.cfid = ?';
        $res =& $db->query($sql, array($this->sch_id, $this->cfid));

        $sql = <<<SQL
                SELECT SQL_NO_CACHE tid, server_available, name, 

                  (
                   SELECT count(1) 
                   FROM `matches_pending` 
                   INNER JOIN schedules USING (sch_id) 
                   WHERE sid = "$this->sid" AND `matches_pending`.tid = teams.tid AND stg_type = "$this->stg_type" 
                        AND matches_pending.deleted = 0 AND schedules.deleted = 0 AND `mid` IS NULL
                  ) AS times_pending,

                  (
                   SELECT count(1) 
                   FROM matches 
                   INNER JOIN schedules USING (sch_id) 
                   WHERE schedules.sid = $this->sid
                   AND schedules.stg_type = "$this->stg_type" AND schedules.deleted = 0
                   AND ((matches.home_tid = teams.tid AND matches.away_tid = 0) OR (matches.away_tid = teams.tid AND matches.home_tid = 0))
                   AND win_tid <> 0
                  ) AS byes,

                  (
                   SELECT count(1) 
                   FROM rosters 
                   WHERE rosters.tid = teams.tid AND leave_date_gmt = "0000-00-00 00:00:00"
                  ) AS roster_size,

                  (
                   SELECT forfeit_losses
                   FROM teams_standings_summary
                   WHERE teams_standings_summary.tid = teams.tid
                     AND teams_standings_summary.sid = $this->sid
                     AND teams_standings_summary.preseason = $this->stg_preseason
                   LIMIT 1
                  ) AS forfeit_losses,

                  (
                   SELECT forfeit_wins
                   FROM teams_standings_summary
                   WHERE teams_standings_summary.tid = teams.tid
                     AND teams_standings_summary.sid = $this->sid
                     AND teams_standings_summary.preseason = $this->stg_preseason
                   LIMIT 1
                  ) AS forfeit_wins

                FROM teams
                INNER JOIN groups ON (teams.grpid = groups.grpid)
                INNER JOIN conferences ON (groups.cfid = conferences.cfid)
                INNER JOIN divisions ON (conferences.divid = divisions.divid)
                WHERE teams.cfid = $this->cfid
                AND teams.approved = 1 AND teams.inactive = 0 AND teams.deleted = 0
                AND groups.inactive = 0 AND conferences.inactive = 0 AND divisions.inactive = 0
                AND NOT EXISTS (
                                SELECT TRUE
                                FROM matches
                                INNER JOIN schedules USING (sch_id)
                                WHERE matches.sch_id = $this->sch_id
                                AND (matches.home_tid = teams.tid OR matches.away_tid = teams.tid)
                                AND matches.deleted = 0
                                AND schedules.deleted = 0
                               )
                ORDER BY times_pending DESC, byes DESC, forfeit_losses ASC, forfeit_wins ASC, server_available ASC, RAND()
SQL;
        $teamsArray = $db->getAll($sql);
        foreach ($teamsArray as $team) {
            if ($team['roster_size'] < $minRosterSize) echo 'Skipping ' . escape($team['name']) . ' due to insufficient roster size ('. $team['roster_size'] .').'."\r\n";
            else $this->teamList[] = $team['tid'];
            ob_flush();
	        flush();
        }
        ob_flush();
	    flush();
        return;
    }

    function doNextTeam()
    {
        global $db;
        $currentTID = array_shift($this->teamList);  // pull out the first team from the list
        $teamListComma = implode(',', $this->teamList);

        // find a list of potential opponents, sorted by:
		// ----------------------------------------------
		// The number of times they've played before. The fewer times the better.
		// Same group first (1 for same group, 0 for not the same).
		// Sort by similar records (similar losses, THEN wins, so teams joining the league late don't start off with a ridiculously easy schedule).
		// Teams in the opposite server situation should try to be scheduled together (find matches, sort by opposite order?)
		// Randomize what (if any) teams are still tied from all the above criteria.

/*
        $sql = <<<SQL
                SELECT SQL_NO_CACHE tid AS TeamID,

                  (
                   SELECT count(1)
                   FROM matches
                   INNER JOIN schedules USING (sch_id)
                   WHERE schedules.sid = $this->sid
                   AND schedules.stg_type = "$this->stg_type"
                   AND ((matches.home_tid = TeamID AND matches.away_tid = $currentTID) OR (matches.home_tid = $currentTID AND matches.away_tid = TeamID))
                   AND schedules.deleted = 0
                   AND matches.deleted = 0
                  ) AS TimesPlayed,

                IF(grpid = (SELECT grpid FROM teams WHERE tid = $currentTID), 1, 0) AS SameGroup,

                ABS(
                    (
                      SELECT count(1)
                      FROM matches 
                      INNER JOIN schedules USING (sch_id) 
                      WHERE schedules.sid = $this->sid AND schedules.stg_type = "$this->stg_type" 
                      AND (away_tid = $currentTID OR home_tid = $currentTID)
                      AND (win_tid <> $currentTID OR ((forfeit_home = 1 AND home_tid = $currentTID) OR (forfeit_away = 1 AND away_tid = $currentTID))) 
                      AND schedules.deleted = 0 AND matches.deleted = 0
                      AND matches.report_date_gmt <> "0000-00-00 00:00:00"
                      AND (SELECT teams.divid FROM teams WHERE teams.tid = IF(away_tid = $currentTID, away_tid, home_tid) LIMIT 1) = (
                                            SELECT teams_divisions_log.divid 
                                            FROM teams_divisions_log 
                                            WHERE teams_divisions_log.tid = $currentTID 
                                            AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                                            LIMIT 1
                                        )
                    )
                -
                    (
                      SELECT count(1)
                      FROM matches 
                      INNER JOIN schedules USING (sch_id) 
                      WHERE schedules.sid = $this->sid AND schedules.stg_type = "$this->stg_type" 
                      AND (away_tid = TeamID OR home_tid = TeamID)
                      AND (win_tid <> TeamID OR ((forfeit_home = 1 AND home_tid = TeamID) OR (forfeit_away = 1 AND away_tid = TeamID))) 
                      AND schedules.deleted = 0 AND matches.deleted = 0
                      AND matches.report_date_gmt <> "0000-00-00 00:00:00"
                      AND (SELECT teams.divid FROM teams WHERE teams.tid = IF(away_tid = TeamID, away_tid, home_tid) LIMIT 1) = (
                                            SELECT teams_divisions_log.divid 
                                            FROM teams_divisions_log 
                                            WHERE teams_divisions_log.tid = TeamID 
                                            AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                                            LIMIT 1
                                        )
                    )
                ) AS LossDifference,

                ABS(
                    (
                      SELECT count(1)
                      FROM matches 
                      INNER JOIN schedules USING (sch_id) 
                      WHERE schedules.sid = $this->sid AND schedules.stg_type = "$this->stg_type" 
                      AND (away_tid = $currentTID OR home_tid = $currentTID)
                      AND win_tid = $currentTID
                      AND schedules.deleted = 0 AND matches.deleted = 0
                      AND matches.report_date_gmt <> "0000-00-00 00:00:00"
                      AND (SELECT teams.divid FROM teams WHERE teams.tid = IF(away_tid = $currentTID, away_tid, home_tid) LIMIT 1) = (
                                            SELECT teams_divisions_log.divid 
                                            FROM teams_divisions_log 
                                            WHERE teams_divisions_log.tid = $currentTID 
                                            AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                                            LIMIT 1
                                        )
                    )
                -
                    (
                      SELECT count(1)
                      FROM matches 
                      INNER JOIN schedules USING (sch_id) 
                      WHERE schedules.sid = $this->sid AND schedules.stg_type = "$this->stg_type" 
                      AND (away_tid = TeamID OR home_tid = TeamID)
                      AND win_tid = TeamID
                      AND schedules.deleted = 0 AND matches.deleted = 0
                      AND matches.report_date_gmt <> "0000-00-00 00:00:00"
                      AND (SELECT teams.divid FROM teams WHERE teams.tid = IF(away_tid = TeamID, away_tid, home_tid) LIMIT 1) = (
                                            SELECT teams_divisions_log.divid 
                                            FROM teams_divisions_log 
                                            WHERE teams_divisions_log.tid = TeamID 
                                            AND teams_divisions_log.timestamp_gmt <= matches.create_date_gmt 
                                            ORDER BY teams_divisions_log.timestamp_gmt DESC 
                                            LIMIT 1
                                        )
                    )
                ) AS WinDifference,

                (SELECT server_available FROM teams WHERE tid = $currentTID) = (SELECT server_available FROM teams WHERE tid = TeamID) AS SameServerSit

                FROM teams
                HAVING TeamID IN ($teamListComma)
                ORDER BY TimesPlayed ASC, SameGroup DESC, WinDifference ASC, LossDifference ASC, SameServerSit ASC, RAND()
SQL;
*/

        $sql = <<<SQL
                SELECT SQL_NO_CACHE tid AS TeamID, name,

                  (
                   SELECT count(1)
                   FROM matches
                   INNER JOIN schedules USING (sch_id)
                   WHERE schedules.sid = $this->sid
                   AND schedules.stg_type = "$this->stg_type"
                   AND ((matches.home_tid = TeamID AND matches.away_tid = $currentTID) OR (matches.home_tid = $currentTID AND matches.away_tid = TeamID))
                   AND schedules.deleted = 0
                   AND matches.deleted = 0
                  ) AS TimesPlayed,

                IF(grpid = (SELECT grpid FROM teams WHERE tid = $currentTID), 1, 0) AS SameGroup,

                ABS(
                    (
                      SELECT losses 
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = $currentTID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                -
                    (
                      SELECT losses 
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = TeamID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                ) AS LossDifference,

                ABS(
                    (
                      SELECT wins 
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = $currentTID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                -
                    (
                      SELECT wins 
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = TeamID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                ) AS WinDifference,

                ABS(
                    (
                      SELECT forfeit_wins 
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = $currentTID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                -
                    (
                      SELECT forfeit_wins 
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = TeamID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                ) AS ForfeitWinsDifference,

                ABS(
                    (
                      SELECT forfeit_losses
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = $currentTID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                -
                    (
                      SELECT forfeit_losses
                      FROM teams_standings_summary 
                      WHERE teams_standings_summary.tid = TeamID 
                        AND teams_standings_summary.sid = $this->sid 
                        AND teams_standings_summary.preseason = $this->stg_preseason
                      LIMIT 1
                    )
                ) AS ForfeitLossDifference,

                (SELECT server_available FROM teams WHERE tid = $currentTID) = (SELECT server_available FROM teams WHERE tid = TeamID) AS SameServerSit

                FROM teams
                HAVING TeamID IN ($teamListComma)
                ORDER BY TimesPlayed ASC, SameGroup DESC, WinDifference ASC, LossDifference ASC, ForfeitLossDifference ASC, ForfeitWinsDifference ASC, SameServerSit ASC, RAND()
SQL;
        /*
        $fullOpponentsList =& $db->getAll($sql);
        $currentTeamName =& $db->getOne('SELECT name FROM teams WHERE tid = '.$currentTID);
        echo '<br /><table class="clean"><caption>'.escape($currentTeamName).'</caption>';
        foreach ($fullOpponentsList as $opp) {
            echo ' <tr> ';
            foreach ($opp as $header => $op) {
                echo ' <th>'.escape($header).'</th> ';
            }
            echo ' </tr> ';
            break;
        }
        foreach ($fullOpponentsList as $opp) {
            echo '<tr>';
            foreach ($opp as $field)  {
                echo '<td>'.escape($field).'</td>';
            }
            echo '</tr>';
        }
        echo '</table><br />'."\r\n\r\n";
        */

        $opponentTID =& $db->getOne($sql);
        unset($this->teamList[array_search($opponentTID, $this->teamList)]);

        $sql = 'SELECT DISTINCT name, tag, IF(server_available = 1, "Avail.", "NA") as server, group_title as title FROM teams INNER JOIN groups USING (grpid) WHERE tid = ? OR tid = ? LIMIT 2';
        $teamInfo =& $db->getAll($sql, array($currentTID, $opponentTID));

        $team1name = $teamInfo[0]['name'];
        $team1tag = $teamInfo[0]['tag'];
        $team1title = $teamInfo[0]['title'];
        $team1server = $teamInfo[0]['server'];

        $team2name = $teamInfo[1]['name'];
        $team2tag = $teamInfo[1]['tag'];
        $team2title = $teamInfo[1]['title'];
        $team2server = $teamInfo[1]['server'];

        echo "Scheduling ".escape($team1tag).' '.escape($team1name)." (Server $team1server) against ".escape($team2tag).' '.escape($team2name)." (Server $team2server)...";
        ob_flush();
        flush();
        $schedStatus = scheduleTeams($this->sch_id, $currentTID, $opponentTID, 'auto');
        if ($schedStatus === TRUE || checkNumber($schedStatus)) {
            echo "OK.\r\n";
            $this->bbCode .= '<br />'.escape($team1name).' [b]v[/b] '.escape($team2name)."\r\n";
        } else {
            echo $schedStatus."\r\n";
        }
        ob_flush();
        flush();
    } // end for
}

ob_start();
echo '<a href="/edit.matches.php?sch_id='.SCH_ID.'">Return to the scheduling manager</a>.';
echo '<pre>';
ob_flush();
flush();

$scheduler = new AutoScheduler(CFID, SCH_ID);

echo "---\r\n". 'Begin auto-schedule calculations...'."\r\n";
ob_flush();
flush();

while (count($scheduler->teamList) > 1) {
	$scheduler->doNextTeam();
}

if (count($scheduler->teamList) === 1) {
	$sql = 'SELECT name, tag FROM teams WHERE tid = ? LIMIT 1';
    $leftoverTID = array_shift($scheduler->teamList);
    $teamInfo =& $db->getRow($sql, array($leftoverTID));
    $name = $teamInfo['name'];
    $tag = $teamInfo['tag'];
    echo escape($name) . " was left over for pending queue.\r\n";
    scheduleTeams(SCH_ID, $leftoverTID, NULL, 'auto');
    $scheduler->bbCode .= '<br />'.escape($name).' pending opponent'."\r\n";
}


echo '</pre> Scheduling complete. ';
echo '<div><h3>Forum code for prediction threads... copy\'n\'paste GO GO GO</h3>';
echo '[b][u]'.$scheduler->divisionTitle."[/u][/b]\r\n";
echo $scheduler->bbCode;
echo '</div></body></html>';
