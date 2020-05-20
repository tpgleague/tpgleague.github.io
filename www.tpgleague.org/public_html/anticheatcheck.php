<?php
    ini_set('display_errors', 0);
    require_once '../includes/inc.db.php';
    
    // Get the user ID    
    $usr = $_GET['usr'];
    $pwd = $_GET['pwd'];
    
    require_once 'inc.func-hash-password.php';
    $pwHash = hashPassword($pwd);
    $valuesArray = array(
                         $usr,
                         $pwHash
                        );
    $sql = 'SELECT uid FROM users WHERE username = ? AND password = ? AND users.deleted = 0';
    $uid =& $db->getOne($sql, $valuesArray);

$matchesSql = <<<SQL
    SELECT matches.mid, start_date_gmt, league_title, lgname, opponent.name, opponent.tag, tz_offset, games.short_name
    FROM users
    INNER JOIN rosters USING (uid)
    INNER JOIN teams USING (tid)
    INNER JOIN leagues ON (teams.lid = leagues.lid)
    INNER JOIN games on (leagues.gameid = games.gameid)
    INNER JOIN seasons on seasons.lid = leagues.lid
    INNER JOIN schedules USING (sid)
    INNER JOIN matches ON schedules.sch_id = matches.sch_id and (teams.tid = matches.away_tid or teams.tid = matches.home_tid)
    INNER JOIN teams AS opponent ON ((opponent.tid = matches.away_tid AND matches.away_tid <> teams.tid) OR (opponent.tid = matches.home_tid AND matches.home_tid <> teams.tid))
    LEFT OUTER JOIN time_zones ON users.tzid = time_zones.tzid
    WHERE rosters.uid = ? AND rosters.leave_date_gmt = "0000-00-00 00:00:00"
        AND seasons.active = 1 AND (seasons.display_preseason = 1 OR schedules.stg_type <> "Preseason")
        AND schedules.deleted = 0 AND (matches.deleted = 0 OR matches.deleted IS NULL) AND matches.report_date_gmt  = "0000-00-00 00:00:00"
SQL;
$matchesInfo =& $db->getAll($matchesSql, array($uid));

    if ($uid)
    {
        echo '{ ';
        echo '"userId": ' . $uid . ', ';
        echo '"ipAddress": "' . $_SERVER['REMOTE_ADDR'] . '", ' ;
        echo '"pendingMatches": [ ';
        $matchesJSON = "";
        foreach ($matchesInfo as $key => $match) {
            $timezone = '-05';
            if ($match['tz_offset'])
            {
                $timezone = rtrim($match['tz_offset'], 2);
            }
            //$timezone += date("I");
            //$matchTime = date("m-d-y h:i", $match['start_date_gmt'] + $timezone*3600);
            $matchesJSON .= '{ "matchId": ' . $match['mid'] .
             ', "matchTime": "' . $match['start_date_gmt'] .
              '", "timeZoneOffset": "' . $timezone .
             '", "opponentTag": "' . $match['tag'] .
             '", "gameShortName": "' . $match['short_name'] .
              '" }, ';
        }
        
        if ($matchesJSON){
            echo rtrim($matchesJSON, 2);
        }
        
        echo ' ]';
        echo ' }';
        logUserInfo($uid);
    }
    else
    {
        echo '0';   
    }
    
    ini_set('display_errors', 1);
    
    function logUserInfo($uid)
    {
        global $db;
        $sql = 'SELECT brwsid FROM ip_browsers WHERE browser = ? LIMIT 1';
        $brwsid =& $db->getOne($sql, array(trim($_SERVER['HTTP_USER_AGENT'])));
        if (!$brwsid) {
            $sql = 'INSERT INTO ip_browsers (browser) VALUES (?)';
            $res =& $db->query($sql, array(trim($_SERVER['HTTP_USER_AGENT'])));
            $brwsid =& $db->getOne('SELECT LAST_INSERT_ID()');
        }
        $sql = 'INSERT INTO ip (uid, address, brwsid, timestamp_gmt) '
             . 'VALUES (?, INET_ATON(?), ?, NOW())';
        $valuesArray = array($uid,
                             $_SERVER['REMOTE_ADDR'],
                             $brwsid
                            );
        $res =& $db->query($sql,$valuesArray);
    }
?>
