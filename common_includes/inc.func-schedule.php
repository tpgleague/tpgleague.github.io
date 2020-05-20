<?php

//                                     // tid2=0 is bye, tid2=NULL is PQ. $court applies to $tid1 always.
function scheduleTeams($sch_id, $tid1, $tid2=NULL, $court='auto', $win_tid=NULL, $forfeit=0, $notify=1) {
    if ($tid2 === '') $tid2 = NULL;
    if (!is_null($tid2)) $tid2 = (int)$tid2;


    global $db;


    if (($tid1 === 0 && $tid2 === 0) || (is_null($tid1) && is_null($tid2))) return 'Select some actual teams maybe?';

    if ($tid1 === 0 && $tid2 !== 0) {
        $tid1 = $tid2;
        $tid2 = 0;
    }
    if (is_null($tid1) && !is_null($tid2)) {
        $tid1 = $tid2;
        $tid2 = NULL;
    }

    $sql = 'SELECT name FROM teams WHERE tid = ? UNION SELECT name FROM teams WHERE tid = ?';
    $teamNames =& $db->getCol($sql, 'name', array($tid1, $tid2));
    $teamName = $teamNames[0];
    $opponentName = $teamNames[1];
    $tid1name = $teamNames[0];
    $tid2name = $teamNames[1];

    if ($tid1 == $tid2) {
        return 'You cannot schedule a team against itself!';
    }

    if ($tid2 != 0) {
        $sql = 'SELECT TRUE FROM matches WHERE sch_id = ? AND (away_tid = ? OR home_tid = ?) AND deleted = 0 LIMIT 1';
        $alreadyScheduled =& $db->getRow($sql, array($sch_id, $tid1, $tid1));
        if ($alreadyScheduled) {
            return $teamName . ' is already scheduled for this matchdate.';
        }
        $alreadyScheduled =& $db->getRow($sql, array($sch_id, $tid2, $tid2));
        if ($alreadyScheduled) {
            return $opponentName . ' is already scheduled for this matchdate.';
        }
    }

    if ($tid2 != 0) {
        // check if team is in pending queue
        $sql = 'SELECT TRUE FROM matches_pending WHERE sch_id = ? AND tid = ? AND deleted = 0 AND `mid` IS NULL';
        $alreadyPending =& $db->getOne($sql, array($sch_id, $tid1));
        if ($alreadyPending) {
            return $teamName . ' is already in the pending queue for this matchdate.';
        }
        $alreadyPending =& $db->getOne($sql, array($sch_id, $tid2));
        if ($alreadyPending) {
            return $opponentName . ' is already in the pending queue for this matchdate.';
        }
    }

    // if $tid2 is NULL, check the matches_pending table for partners before adding $tid1.
    if (is_null($tid2)) {
        $sql = 'SELECT cfid FROM teams WHERE tid = ?';
        $cfid =& $db->getOne($sql, array($tid1));

        $sql = 'SELECT tid, mpnid FROM matches_pending WHERE sch_id = ? AND cfid = ? AND scheduled_date_gmt = "0000-00-00 00:00:00" AND deleted = 0 AND `mid` IS NULL LIMIT 1';
        $pendingRow =& $db->getRow($sql, array($sch_id, $cfid));
        $alreadyPending = $pendingRow['tid'];
        $mpnid = $pendingRow['mpnid'];

        if ($alreadyPending && $pendingRow['tid'] != $tid1) {
            $tid2 = $alreadyPending; // this sets the two teams to play each other.
            // remove team2 from pending queue down there
            $sql = 'SELECT name FROM teams WHERE tid = ? UNION SELECT name FROM teams WHERE tid = ?';
            $teamNames =& $db->getCol($sql, 'name', array($tid1, $tid2));
            $teamName = $teamNames[0];
            $opponentName = $teamNames[1];
            $tid1name = $teamNames[0];
            $tid2name = $teamNames[1];

        } else {
            // add $tid1 to pending;
            if ($pendingRow['tid'] != $tid1) {
                $newPendingArray = array(
                                         'tid' => $tid1,
                                         'sch_id' => $sch_id,
                                         'cfid' => $cfid,
                                         'scheduled_date_gmt' => '0000-00-00 00:00:00',
                                         'create_date_gmt' => mysqlNow()
                                        );
                $insertRecord = new InsertRecord();
                $insertRecord->insertData('matches_pending', $newPendingArray);
                unset($insertRecord);
                /*
                $sql = 'INSERT INTO matches_pending (tid, sch_id, cfid, create_date_gmt, scheduled_date_gmt) '
                     . 'VALUES (?, ?, ?, NOW(), "0000-00-00 00:00:00")';
                $res = $db->query($sql, array($tid1, $sch_id, $cfid));
                */
                return 'PENDING_QUEUE';
            } else {
                return 'Team is already in pending queue';
            }
        }
    }

    // $court applies to $tid1 always.
    if ($court != 'auto') {
        if ($court == 'home') {
            $home_tid = $tid1;
            $away_tid = $tid2;
        } else {
            $home_tid = $tid2;
            $away_tid = $tid1;
        }
    } elseif ($tid2 === 0) { // if $tid2 is 0, then we are awarding $tid1 a bye. so find how many home or away games $tid1 has had and give the opposite.
        $sql = <<<SQL
                    SELECT 
                      (SELECT count(1) FROM matches INNER JOIN schedules USING (sch_id) 
                       WHERE sid = ? AND stg_type = ? AND away_tid = ? AND matches.deleted = 0 AND schedules.deleted = 0) AS tid1aways,
                      (SELECT count(1) FROM matches INNER JOIN schedules USING (sch_id) 
                       WHERE sid = ? AND stg_type = ? AND home_tid = ? AND matches.deleted = 0 AND schedules.deleted = 0) AS tid1homes
SQL;
        $courts =& $db->getRow($sql, array(
                                                $sid, $stg_type, $tid1,
                                                $sid, $stg_type, $tid1
                                              ));
        $tid1aways = $courts['tid1aways'];
        $tid1homes = $courts['tid1homes'];

        if ($tid1homes < $tid1aways) {
            $home_tid = $tid1;
            $away_tid = $tid2;
        } elseif ($tid1homes > $tid1aways) {
            $home_tid = $tid2;
            $away_tid = $tid1;
        } else { // same amount of home games for each. randomize!
            if (mt_rand(0,1)) {
                $home_tid = $tid1;
                $away_tid = $tid2;
            } else {
                $home_tid = $tid2;
                $away_tid = $tid1;
            }
        }
    } else {     // find out which team has higher home %, then which had more home games total count
        $sql = 'SELECT stg_type, sid FROM schedules INNER JOIN seasons USING (sid) WHERE sch_id = ? LIMIT 1';
        $scheduleInfo =& $db->getRow($sql, array($sch_id));
        $stg_type = $scheduleInfo['stg_type'];
        $sid = $scheduleInfo['sid'];

        $sql = <<<SQL
                    SELECT 
      (SELECT count(1) FROM matches INNER JOIN schedules USING (sch_id) 
       WHERE sid = ? AND stg_type = ? AND home_tid = ? AND matches.deleted = 0 AND schedules.deleted = 0) AS tid1homes,
      (SELECT count(1) FROM matches INNER JOIN schedules USING (sch_id) 
       WHERE sid = ? AND stg_type = ? AND home_tid = ? AND matches.deleted = 0 AND schedules.deleted = 0) AS tid2homes,
      (SELECT count(1) FROM matches INNER JOIN schedules USING (sch_id) 
       WHERE sid = ? AND stg_type = ? AND away_tid = ? AND matches.deleted = 0 AND schedules.deleted = 0) AS tid1aways,
      (SELECT count(1) FROM matches INNER JOIN schedules USING (sch_id) 
       WHERE sid = ? AND stg_type = ? AND away_tid = ? AND matches.deleted = 0 AND schedules.deleted = 0) AS tid2aways
SQL;
        $courts =& $db->getRow($sql, array(
                                                $sid, $stg_type, $tid1,
                                                $sid, $stg_type, $tid2,
                                                $sid, $stg_type, $tid1,
                                                $sid, $stg_type, $tid2,
                                              ));
        $tid1homes = $courts['tid1homes'];
        $tid2homes = $courts['tid2homes'];
        $tid1aways = $courts['tid1aways'];
        $tid2aways = $courts['tid2aways'];

        $tid1total = $courts['tid1homes'] + $courts['tid1aways'];
        $tid2total = $courts['tid2homes'] + $courts['tid2aways'];

        if ($tid1total != 0 && $tid2total != 0) {
            $tid1HomePct = $tid1homes/$tid1total;
            $tid2HomePct = $tid2homes/$tid2total;
        } else { // one team hasn't even played any games, so we want to do it by total home games played.
            $tid1HomePct = 0;
            $tid2HomePct = 0;
        }

        if ($tid1HomePct < $tid2HomePct) {
            $home_tid = $tid1;
            $away_tid = $tid2;
            $home_name = $tid1name;
            $away_name = $tid2name;
        } elseif ($tid1HomePct > $tid2HomePct) {
            $home_tid = $tid2;
            $away_tid = $tid1;
            $home_name = $tid2name;
            $away_name = $tid1name;
        } elseif ($tid1homes < $tid2homes) {
            $home_tid = $tid1;
            $away_tid = $tid2;
            $home_name = $tid1name;
            $away_name = $tid2name;
        } elseif ($tid1homes > $tid2homes) {
            $home_tid = $tid2;
            $away_tid = $tid1;
            $home_name = $tid2name;
            $away_name = $tid1name;
        } else { // same amount of home games for each. randomize!
            if (mt_rand(0,1)) {
                $home_tid = $tid1;
                $away_tid = $tid2;
                $home_name = $tid1name;
                $away_name = $tid2name;
            } else {
                $home_tid = $tid2;
                $away_tid = $tid1;
                $home_name = $tid2name;
                $away_name = $tid1name;
            }
        }

    }

    // schedule the two teams at last!

    $report_date_gmt = '0000-00-00 00:00:00';
    if (!is_null($win_tid)) {
        // the admin has assigned a winner already
        $report_date_gmt = mysqlNow();
        $report_by_aid = checkNumber(AID) ? AID : NULL;

        /*
        if ($win_tid === 0) { // this team loses to bye? it's a forfeit loss
            if ($win_tid === $away_tid) $forfeit_home = 1;
            else $forfeit_away = 1;
        }
        */
        if (!$tid2 && $forfeit && $win_tid == $tid1) {
            if ($tid1 == $home_tid) $forfeit_away = 1;
            else $forfeit_home = 1;
        } elseif (!$tid2 && $forfeit) {
            if ($tid1 == $home_tid) $forfeit_home = 1;
            else $forfeit_away = 1;
        }
    }

    if (!isset($forfeit_home)) $forfeit_home = 0;
    if (!isset($forfeit_away)) $forfeit_away = 0;


    $sql = 'SELECT grpid, server_available FROM teams WHERE tid = ?';
    if ($away_tid) {
        $teamStatus =& $db->getRow($sql, array($away_tid));
        $awaySA = $teamStatus['server_available'];
        $awayGrpid = $teamStatus['grpid'];
        if (!$home_tid) {
            $homeSA = 0;
            $homeGrpid = $awayGrpID;
        }
    } else {
        $awaySA = 0;
        $awayGrpid = 0;
    }
    if ($home_tid) {
        $teamStatus =& $db->getRow($sql, array($home_tid));
        $homeSA = $teamStatus['server_available'];
        $homeGrpid = $teamStatus['grpid'];
        if (!$away_tid) {
            $awaySA = 0;
            $awayGrpid = $homeGrpID;
        }
    } elseif (!$homeGrpID) {
        $homeSA = 0;
        $homeGrpid = 0;
    }

    // find default start time
    $sql = 'SELECT stg_match_date_gmt FROM schedules where sch_id = '.$db->quoteSmart($sch_id);
    $start_date_gmt =& $db->getOne($sql);

    $newMatchArray = array(
                           'home_tid'        => $home_tid,
                           'away_tid'        => $away_tid,
                           'start_date_gmt'  => $start_date_gmt,
                           'sch_id'          => $sch_id,
                           'report_date_gmt' => $report_date_gmt,
                           'report_by_aid'   => $report_by_aid,
                           'win_tid'         => $win_tid,
                           'forfeit_home'    => $forfeit_home,
                           'forfeit_away'    => $forfeit_away,
                           'create_date_gmt' => mysqlNow(),
                           'away_sa'         => $awaySA,
                           'home_sa'         => $homeSA,
                           'away_grpid'      => empty($awayGrpid) ? 0:$awayGrpid,
                           'home_grpid'      => empty($homeGrpid) ? 0:$homeGrpid
                          );
    $insertRecord = new InsertRecord();
    $insertRecord->insertData('matches', $newMatchArray);
    $mid = $insertRecord->lastInsertId();
    unset($insertRecord);

    if ($notify == 1 && !empty($home_tid) && !empty($away_tid)) {
        $sql = 'SELECT leagues.league_title, leagues.lgname, schedules.stg_short_desc FROM leagues INNER JOIN seasons USING (lid) INNER JOIN schedules USING (sid) INNER JOIN matches USING (sch_id) WHERE `mid` = ? LIMIT 1';
        $matchInfo =& $db->getRow($sql, array($mid));

        $privMembers = getPrivilegedMembers($home_tid);
        $notificationVars = array(
                  'lgname' => $matchInfo['lgname'],
                  'url' => 'http://www.tpgleague.org/schedule.match.php?mid='.$mid.'&tid='.$home_tid,
                  'opponent_team' => $away_name,
                  'your_team' => $home_name,
                  'league_title' => $matchInfo['league_title'],
                  'week' => $matchInfo['stg_short_desc']
                 );
        foreach ($privMembers as $privUID) {
            sendMessage($privUID, 'notification.scheduled', $notificationVars);
        }

        $privMembers = getPrivilegedMembers($away_tid);
        $notificationVars = array(
                  'lgname' => $matchInfo['lgname'],
                  'url' => 'http://www.tpgleague.org/schedule.match.php?mid='.$mid.'&tid='.$away_tid,
                  'opponent_team' => $home_name,
                  'your_team' => $away_name,
                  'league_title' => $matchInfo['league_title'],
                  'week' => $matchInfo['stg_short_desc']
                 );
        foreach ($privMembers as $privUID) {
            sendMessage($privUID, 'notification.scheduled', $notificationVars);
        }

    }

    if ($alreadyPending) {
        /*
        $sql = 'UPDATE matches_pending SET mid = ?, scheduled_date_gmt = NOW() WHERE mpnid = ?';
        $db->query($sql, array($mid, $mpnid));
        */
        $updateRecord = new updateRecord('matches_pending', 'mpnid', $mpnid);
        $updateRecord->addData(array('mid' => $mid));
        $updateRecord->UpdateData();
        unset($updateRecord);
    }

    if (!is_null($win_tid)) { // teams got a win or a loss, so update their standings:
        require_once 'inc.func-updateStandings.php';
        $sql = 'SELECT sid, IF(stg_type = "Preseason", 1, 0) AS ps FROM schedules WHERE `sch_id` = ? LIMIT 1';
        $match =& $db->getRow($sql, array($sch_id));
        $ps = $match['ps'];
        $sid = $match['sid'];
        if ($tid1) calculateTeamStandings($tid1, $sid, $ps);
        if ($tid2) calculateTeamStandings($tid2, $sid, $ps);
    }

    return $mid;
}

