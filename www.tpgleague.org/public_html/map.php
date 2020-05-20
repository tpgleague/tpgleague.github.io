<?php

require_once '../includes/inc.initialization.php';

// Get base information about the map
$sql = <<<SQL
    SELECT *
    FROM maps
    WHERE map_title = ? AND lid = ?
SQL;
$map =& $db->getRow($sql, array($_GET['mapname'], LID));

// Check that we found the map requested in the current league
if ($map) { define('MAPNAME', $_GET['mapname']); }
else { displayError('The map name provided is not valid.'); }

// Get earliest season on new website
$earliestSeasonSql = <<<SQL
    SELECT MIN(season_number)
    FROM seasons
    WHERE lid = ?
SQL;
$earliestSeason =& $db->getOne($earliestSeasonSql, array(LID));
$tpl->assign('earliest_season', $earliestSeason);

// Get aggregate times played stats
$timesPlayedSql = <<<SQL
    SELECT stg_type, count(*) as used
    FROM schedules
    WHERE lid = ? AND mapid = ? AND schedules.deleted = 0
    GROUP BY stg_type
SQL;
$timesPlayed =& $db->getAll($timesPlayedSql, array(LID, $map['mapid']));
$tpl->assign('times_played', $timesPlayed);

//TODO: Make dynamic, so it works for any "sides"
//TODO: BuLLeT FoDDeR: I should link to the match that the max score occured in
$scoringSql = <<<SQL
SELECT avg(case when away_ls.side = 'Allies' then away_score when home_ls.side = 'Allies' then home_score else null end) as avg_allies_score,
       avg(case when away_ls.side = 'Axis' then away_score when home_ls.side = 'Axis' then home_score else null end) as avg_axis_score,
       max(case when away_ls.side = 'Allies' then away_score when home_ls.side = 'Allies' then home_score else null end) as max_allies_score,
       max(case when away_ls.side = 'Axis' then away_score when home_ls.side = 'Axis' then home_score else null end) as max_axis_score
FROM schedules s
    inner join matches m on m.sch_id = s.sch_id
    inner join matches_scores ms on ms.mid = m.mid
    left outer join leagues_sides away_ls on away_ls.lsid = ms.away_lsid
    left outer join leagues_sides home_ls on home_ls.lsid = ms.home_lsid
WHERE s.lid = ? AND s.mapid = ?
SQL;

$scoringStats =& $db->getRow($scoringSql, array(LID, $map['mapid']));
$tpl->assign('scoring_stats', $scoringStats);

// Create exploits list
if ($map['illegal_locations_path'])
{
    // Get the physical server path
    //TODO: Allow leading slash to be missing
    $exploitsFolder = realpath( __DIR__ . '/../../files.tpgleague.org' . $map['illegal_locations_path']);
    
    // If the folder provided is a valid folder
    if(is_dir($exploitsFolder))
    {
        // Loop through and get all of the image names in that folder
        $dir = opendir($exploitsFolder);
        
        $exploits = array();
        while (false !== ($filename = readdir($dir)))
        {
            // Do not pull in the files named . and ..
            //TODO: force only image extensions
            if ($filename != '.' && $filename != '..')
            {
                $exploits[] = $filename;
            }
        }
        
        closedir($dir);

        $tpl->assign('exploits', $exploits);
    }
}

$tpl->assign('map', $map);

$tpl->assign('title', MAPNAME);
displayTemplate('map', NULL, NULL, TRUE);