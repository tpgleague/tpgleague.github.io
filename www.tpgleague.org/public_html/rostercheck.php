<?php

    ini_set('display_errors', 0);
    require_once '../includes/inc.db.php';
    
    // Vertical tab character to signify the server mod that the output is starting
    echo "\x0B";
    
    // Get the match ID    
    $mid = $_GET['mid'];
    //echo 'mid = ' . $mid . "\n";
    
    // Get the list of Steam IDs and put them into an array assuming they are delimited by a comma
    $steamIds = explode(",", $_GET['ids']);
    
    // Remove HLTV
    foreach ($steamIds as $key => $value) {
        if ($steamIds[$key] == 'HLTV')
        {
            unset($steamIds[$key]);
        }
    }
    
$sql = <<<SQL
    SELECT
        m.mid,
        sch.lid,
        m.start_date_gmt,
        r.gid,
        r.tid
    FROM matches m
    INNER JOIN schedules sch on m.sch_id = sch.sch_id
    INNER JOIN rosters r on r.tid = m.home_tid or r.tid = m.away_tid
    WHERE m.deleted = 0 AND m.report_date_gmt = '0000-00-00 00:00:00'
        AND r.leave_date_gmt = '0000-00-00 00:00:00'
        AND m.mid =         
SQL;

$suspendedSql = <<<SQL
    SELECT distinct sg.gid as susp_gid
    FROM suspensions_gids AS sg
    JOIN suspensions_list AS sl_g ON sl_g.suspid = sg.suspid
     AND sl_g.suspension_date_ends_gmt > NOW()
     AND (sl_g.lid = ? OR sl_g.lid IS NULL)
SQL;

    $sql .= $mid;
    $res = $db->query($sql);

    if ($res->numRows() > 0)
    {
        $rosteredGids = array();
        $suspendedGids = array();
        $lid = 0;
        
        // Get the list of rostered players
        while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC))
        {
            // Capture the league ID for use in the next query
            if (!$lid)
            {
                $lid = $row['lid'];
            }
            
            $rosteredGids[] = $row['gid'];
        }
        
        // Get the list of all active suspensions for this leauge
        $resSuspensions = $db->query($suspendedSql, array($lid));
        if ($resSuspensions->numRows() > 0)
        {
            while ($suspensionRow = $resSuspensions->fetchRow(DB_FETCHMODE_ASSOC))
            {
                $suspendedGids[] = $suspensionRow['susp_gid'];
            }
        }
        
        // Get steam IDs in server not in rostered list
        $rosteredDiff = array_diff($steamIds, $rosteredGids);
        
        // Get suspended gids in server
        $suspendedDiff = array_intersect($suspendedGids, $steamIds);
        
        foreach($suspendedDiff as $suspended)
        {
            echo $suspended . " is suspended.\n";
        }
        
        foreach($rosteredDiff as $unrostered)
        {
            echo $unrostered . " is not rostered.\n";
        }

        if (count($rosteredDiff) == 0 && count($suspendedDiff) == 0)
        {
            echo "All players are rostered and not suspended.";
        }
        
        //TODO: Place Steam IDs in the database.  Delete if mid already exists then insert
    }
    else
    {
        echo "Match is not found or already reported.";
    }
    
     ini_set('display_errors', 1);
    
?>
