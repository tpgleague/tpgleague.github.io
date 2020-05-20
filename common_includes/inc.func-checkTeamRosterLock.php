<?php

function checkTeamRosterLock ($tid) {
    if (!checkNumber($tid) || $tid == 0) return 'locked';
    global $db;

$sql = <<<SQL
            SELECT
                    teams.deleted AS team_deleted,
                    teams.roster_lock AS team_roster_lock,
                    teams.lid, teams.divid,
                    leagues.roster_lock AS league_roster_lock,
                    seasons.sid,
                    seasons.active AS season_active
            FROM
                    teams
            INNER JOIN
                    leagues ON (teams.lid = leagues.lid)
            LEFT JOIN
                    seasons ON (seasons.lid = leagues.lid)
            WHERE
                    tid = $tid
            ORDER BY
                    seasons.active DESC
            LIMIT 1
SQL;
    $teamStatusArray =& $db->getRow($sql);
    if ($teamStatusArray['team_deleted']) return 'locked';
    if ($teamStatusArray['team_roster_lock'] != 'auto') return $teamStatusArray['team_roster_lock'];
    if ($teamStatusArray['league_roster_lock'] != 'auto') {
        if (!$teamStatusArray['divid'] || $teamStatusArray['league_roster_lock'] == 'unlocked') return 'unlocked';
        else return 'locked';
    }

    // Go on to check the schedules. A maximum of one season in a league can be marked as active.

    // With no active season, how could they be locked?
    if (!$teamStatusArray['season_active']) return 'unlocked';
    $sid = $teamStatusArray['sid'];


    // Check for permanent season roster lock. Only applies if the team is in a division.
    if ($teamStatusArray['divid']) {
        $sql = 'SELECT UNIX_TIMESTAMP(stg_match_date_gmt) AS season_lock_date_unix FROM seasons INNER JOIN schedules ON (seasons.sid = '
             . $sid .' AND seasons.roster_lock_playoffs_sch_id = schedules.sch_id) LIMIT 1';
        $permanentSeasonLockDate =& $db->getOne($sql);
        if ($permanentSeasonLockDate && (gmmktime() >= $permanentSeasonLockDate)) return 'locked';
    }

SQL;

    // This query averages 0.070 seconds on my dev box with no load.
    // For caching purposes, prefill the datetime in the query (rounded down to nearest 5-minute interval):
    $now = gmdate('Y-m-d H:i:s', mktime(date('H'), floor(date('i')/5)*5, 0));
    $inDivision = $teamStatusArray['divid'] ? 1:0;
$sql = <<<SQL
SELECT SQL_SMALL_RESULT
CASE
    WHEN report_date_gmt IS NULL AND $inDivision = FALSE THEN  # Team not scheduled for matches, and not in a division, so don't lock rosters.
         0
    WHEN report_date_gmt IS NULL THEN  # Team not scheduled for matches this week.
         '$now' >= stg_match_date_gmt - INTERVAL roster_lock_hours HOUR
         AND
         '$now' < stg_match_date_gmt
    WHEN report_date_gmt = '0000-00-00 00:00:00' THEN  # Team scheduled, not reported yet.
         IF(start_date_gmt < stg_match_date_gmt, start_date_gmt, stg_match_date_gmt) - INTERVAL roster_lock_hours HOUR <= '$now'
    ELSE  # Team scheduled and the match has been reported as played.
         stg_match_date_gmt > '$now'
END AS locked,

CASE
    WHEN report_date_gmt IS NULL AND $inDivision = FALSE THEN  # Team not scheduled for matches, and not in a division, so don't lock rosters.
        NULL
    ELSE
        UNIX_TIMESTAMP(IF(start_date_gmt < stg_match_date_gmt, start_date_gmt, stg_match_date_gmt) - INTERVAL roster_lock_hours HOUR)
    END AS lock_date_gmt  # Used only if rosters are currently unlocked.

FROM seasons
INNER JOIN schedules USING (sid)
LEFT JOIN matches ON (
                       matches.sch_id = schedules.sch_id AND matches.deleted = 0
                       AND
                       (matches.away_tid = $tid OR matches.home_tid = $tid)
                     )
WHERE   schedules.sid = $sid
AND     schedules.deleted = 0
AND     stg_type IN ('Regular', 'Playoffs')
HAVING  locked = 1 OR (locked = 0 AND lock_date_gmt > UNIX_TIMESTAMP('$now'))
ORDER BY locked DESC, lock_date_gmt IS NULL ASC, lock_date_gmt ASC
LIMIT 1
SQL;

    $rosterStatus =& $db->getRow($sql);
    if (is_null($rosterStatus['locked'])) return 'unlocked'; // No upcoming matches found.
    if (!$rosterStatus['locked']) return $rosterStatus['lock_date_gmt']; // Upcoming matches found, soon to be locked.
    return 'locked'; // Matches now, currently locked.
}