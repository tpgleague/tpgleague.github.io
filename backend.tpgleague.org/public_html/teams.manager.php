<?php


$cssAppend[] = 'teams.manager';
require_once '../includes/inc.initialization.support.php';




if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { define('LID', $_GET['lid']); }

//$ACCESS = checkPermission('Edit League', 'League', LID);
$ACCESS = TRUE;
$tpl->assign('ACCESS', $ACCESS);

if ($ACCESS && !empty($_POST['order'])) {
    $groupsArray = array();
    if (strpos($_POST['order'], "\n") !== FALSE) {
        $groupsArray = explode("\n", $_POST['order']);
    }
    foreach ($groupsArray as $line) {
        if (!empty($line)) {
            $colonpos = strpos($line, ':');
            $group = substr($line, 6, $colonpos-6); // group_9-9-12: 87,88,118
            $teams = substr($line, $colonpos+2);
            $teams = trim($teams);
            $teamArray = array();
            if (!empty($teams)) {
                $teamArray = explode(',', $teams);
            }
            foreach ($teamArray as $team) {
                if (!empty($team)) $teamsArray[$team] = $group;
            }
        }
    }

    $sql = 'SELECT tid, IF(divid IS NULL, 0, divid) AS divid, IF(cfid IS NULL, 0, cfid) AS cfid, IF(grpid IS NULL, 0, grpid) AS grpid FROM teams WHERE lid = '. $db->quoteSmart(LID) . ' AND teams.deleted = 0';
    $currentTeamsArray = $db->getAssoc($sql);

    foreach ($teamsArray as $tid => $dashed) {
        $dashedArray = explode('-', $dashed);
        if (array_key_exists($tid, $currentTeamsArray)) {
            if (
                ($dashedArray[0] != $currentTeamsArray[$tid]['divid']) || 
                ($dashedArray[1] != $currentTeamsArray[$tid]['cfid']) || 
                ($dashedArray[2] != $currentTeamsArray[$tid]['grpid'])
               ) {
                $divid = $dashedArray[0] ? $dashedArray[0] : NULL;
                $cfid = $dashedArray[1] ? $dashedArray[1] : NULL;
                $grpid = $dashedArray[2] ? $dashedArray[2] : NULL;
                $valuesArray1[] = array(
                                       'divid' => $divid,
                                       'cfid' => $cfid,
                                       'grpid' => $grpid,
                                       'tid' => $tid
                                      );
                $valuesArray2[] = array(
                                       'divid' => $divid,
                                       'cfid' => $cfid,
                                       'grpid' => $grpid,
                                       'tid' => $tid,
                                       'lid' => LID,
                                       'moved_by_aid' => AID,
                                       'timestamp_gmt' => gmdate('c', mktime())
                                      );
                $updatedTeamsArray[] = $tid;
            }
        }
    }

    if (!empty($valuesArray1)) {
        $sth = $db->prepare('UPDATE teams SET divid = ?, cfid = ?, grpid = ? WHERE tid = ?');
        $db->executeMultiple($sth, $valuesArray1);
        $db->freePrepared($sth);

        $sth = $db->prepare('INSERT INTO teams_divisions_log (divid, cfid, grpid, tid, lid, moved_by_aid, timestamp_gmt) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $db->executeMultiple($sth, $valuesArray2);
        $db->freePrepared($sth);

        if ($errordetected) {
            $err = print_r($_POST, TRUE);
            $err .= "\r\n\r\n";
            $err .= print_r($currentTeamsArray, TRUE);
            $err .= "\r\n\r\n";
            $err .= print_r($valuesArray1, TRUE);
            $err .= "\r\n\r\n";
            $err .= print_r($valuesArray2, TRUE);
            $err .= "\r\n\r\n";
            $err .= $wtf;
            $err = mb_convert_encoding($err, 'HTML-ENTITIES', 'UTF-8');

            $subject = 'teams manager';
            $headers = 'From: error@tpgleague.org';

            mail('brianpap22@yahoo.com', $subject, $err, $headers);
        }

        require_once 'inc.func-updateStandings.php';
        $sql = 'SELECT sid, IF(preseason_close_date_gmt IS NULL, 1, 0) AS ps FROM `seasons` WHERE lid = ? AND active = 1 LIMIT 1';
        $seasonRow =& $db->getRow($sql, array(LID));
        $ps = $seasonRow['ps'];
        $sid = $seasonRow['sid'];
        foreach ($updatedTeamsArray as $teamID) {
            calculateTeamStandings($teamID, $sid, $ps);
        }
    }

}


$sql = 'SELECT format FROM leagues WHERE lid = '. $db->quoteSmart(LID);
$leagueFormat =& $db->getOne($sql);
$tpl->assign('league_format', $leagueFormat);

$standingsData = getTeamsStatus(LID);
$tpl->assign('standings_groups', $standingsData['groups']);
$tpl->assign('standings_divisions', $standingsData['divisions']);
$tpl->assign('standings_conferences', $standingsData['conferences']);
$tpl->assign('standings_teams', $standingsData['teams']);

$sql = 'SELECT divid, division_title, cfid, conference_title, grpid, group_title FROM divisions INNER JOIN conferences USING (divid) INNER JOIN groups USING (cfid) WHERE divisions.lid = '. $db->quoteSmart(LID);
$listing =& $db->getAll($sql);
$tpl->assign('listing', $listing);
$tpl->assign('teams', $standingsData['teams']);


$extra_head[] = <<<EOT
	<script language="JavaScript" type="text/javascript" src="/js/prototype.js"></script>
	<script language="JavaScript" type="text/javascript" src="/js/scriptaculous.js"></script>
    <script language="JavaScript" type="text/javascript">
    // <![CDATA[

EOT;

$sql = 'SELECT divid, IF(cfid IS NULL, 0, cfid) AS cfid, IF(grpid IS NULL, 0, grpid) AS grpid FROM divisions LEFT JOIN conferences USING(divid) LEFT JOIN groups USING(cfid) WHERE divisions.lid = '. $db->quoteSmart(LID);
$specialListing =& $db->getAll($sql);

$groupsArray = array();
foreach ($specialListing as $key => $group) {
    $groupsArray[] = "'group_" . $group['divid'].'-'.$group['cfid'].'-'.$group['grpid'] . "'";
    if (!in_array("'group_" . $group['divid'].'-'.$group['cfid']."-0'",$groupsArray)) {
        $groupsArray[] = "'group_" . $group['divid'].'-'.$group['cfid']."-0'";
    }
    if (!in_array("'group_" . $group['divid']."-0-0'",$groupsArray)) {
        $groupsArray[] = "'group_" . $group['divid']."-0-0'";
    }
    if (!in_array("'group_0-0-0'",$groupsArray)) {
        $groupsArray[] = "'group_0-0-0'";
    }
}
$extra_head[] = "sections = [". implode(',', $groupsArray) ."];";


$cdata = '<script type="text/javascript">
	// <![CDATA[
';
foreach ($groupsArray as $groupName) {
    $cdata .= "	Sortable.create($groupName,{tag:'div',dropOnEmpty: true, containment: sections,only:'lineitem'});\n";
}
$cdata .= "	Sortable.create('page',{tag:'div',only:'section',handle:'handle'});
	// ]]>
</script>";
$tpl->assign('cdata', $cdata);

$extra_head[] = <<<EOT

function createLineItemSortables() {
    for(var i = 0; i < sections.length; i++) {
        Sortable.create(sections[i],{tag:'div',dropOnEmpty: true, containment: sections,only:'lineitem'});
    }
}

function destroyLineItemSortables() {
    for(var i = 0; i < sections.length; i++) {
        Sortable.destroy(sections[i]);
    }
}

function createGroupSortable() {
    Sortable.create('page',{tag:'div',only:'section',handle:'handle'});
}
EOT;
/*
function toggleapprove(apprID)
{
	var curVal;
	curVal = document.getElementById('input_appr_' + apprID).value;
	if (curVal == 1)
	{
		document.getElementById('input_appr_' + apprID).value = 0;
		document.getElementById('div_appr_'+apprID).innerHTML = '[<span style="color:white;">_</span>]';
		return;
	}
	if (curVal == 0)
	{
		document.getElementById('input_appr_' + apprID).value = 1;
		document.getElementById('div_span_appr_'+apprID).innerHTML = '[A]';
		return;
	}
}
*/

$extra_head[] = <<<EOT

/*
Debug Functions for checking the group and item order
*/
function getGroupOrder() {
    var sections = document.getElementsByClassName('section');
    var alerttext = '';
    sections.each(function(section) {
        var sectionID = section.id;
        var order = Sortable.serialize(sectionID);
        alerttext += sectionID + ': ' + Sortable.sequence(section) + '\\n';
    });
    document.getElementById('order').value = alerttext;
    return false;
}
// ]]>
</script>







<style type="text/css">

</style>

EOT;


require_once '../includes/inc.initialization.display.php';


function getTeamsStatus($lid)
{
    global $db, $tpl;

    if ($_GET['sort'] == 'created') $orderBy = 'teams.create_date_gmt';
    else $orderBy = 'teams.name';

    $divisions =& $db->getAssoc('SELECT divid, division_title, divisions.inactive FROM divisions WHERE lid = ' . $db->quoteSmart($lid), TRUE);
    $conferences =& $db->getAssoc('SELECT divid, cfid, conference_title, conferences.inactive FROM conferences INNER JOIN divisions USING (divid) WHERE divisions.lid = ' . $db->quoteSmart($lid), NULL, NULL, NULL, TRUE);
    $groups =& $db->getAssoc('SELECT cfid, grpid, group_title, groups.inactive FROM groups INNER JOIN conferences USING (cfid) INNER JOIN divisions USING (divid) WHERE divisions.lid = ' . $db->quoteSmart($lid), NULL, NULL, NULL, TRUE);

/* Unoptimized: Averaged 2.048 seconds.
$sql = <<<SQL

SELECT  
        IF(teams.grpid IS NULL, 0, teams.grpid) AS grpid, 
        UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt, 
        IF(teams.cfid IS NULL, 0, teams.cfid) AS cfid, 
        IF(teams.divid IS NULL, 0, teams.divid) AS divid, 
        tid, teams.name, tag, teams.inactive, teams.approved, teams.roster_lock,
        (SELECT count(1) FROM rosters WHERE rosters.tid = teams.tid AND leave_date_gmt = "0000-00-00 00:00:00") AS roster_count, 
        (
            SELECT sch_id 
            FROM matches_pending 
            INNER JOIN schedules USING (sch_id)
            WHERE matches_pending.tid = teams.tid AND `mid` IS NULL 
            AND matches_pending.deleted = 0 AND schedules.deleted = 0
            LIMIT 1
        ) AS pq_sch_id,
        (
            SELECT `mid` 
            FROM matches 
            INNER JOIN schedules USING (sch_id)
            WHERE (matches.away_tid = teams.tid OR matches.home_tid = teams.tid) 
            AND report_date_gmt = "0000-00-00 00:00:00" AND matches.deleted = 0 AND schedules.deleted = 0
            ORDER BY schedules.stg_match_date_gmt ASC
            LIMIT 1
        ) AS match_id
FROM 
        teams 
LEFT JOIN 
        groups USING (grpid) 
WHERE 
        teams.lid = ? AND teams.deleted = 0 
ORDER BY 
        $orderBy ASC
SQL;
*/

// Optimized: Averages 0.156 seconds.
$sql = <<<SQL
SELECT
        IF(teams.grpid IS NULL, 0, teams.grpid) AS grpid,
        UNIX_TIMESTAMP(teams.create_date_gmt) AS unix_create_date_gmt,
        IF(teams.cfid IS NULL, 0, teams.cfid) AS cfid,
        IF(teams.divid IS NULL, 0, teams.divid) AS divid,
        teams.tid, teams.name, tag, teams.inactive, teams.approved, teams.roster_lock,

        COUNT(DISTINCT rosters.rid) AS roster_count,
        COALESCE(MIN(IF(sd1.deleted = 1, NULL, mp1.`sch_id`)), MIN(IF(sd2.deleted = 1, NULL, mp2.`sch_id`))) AS `pq_sch_id`,
        COALESCE(MIN(IF(s1.deleted = 1, NULL, m1.`mid`)), MIN(IF(s2.deleted = 1, NULL, m2.`mid`))) AS `match_id`
FROM teams

LEFT JOIN matches m1 ON (teams.tid = m1.away_tid AND m1.report_date_gmt = "0000-00-00 00:00:00" AND m1.deleted = 0)
LEFT JOIN schedules s1 ON (m1.sch_id = s1.sch_id AND s1.deleted = 0)
LEFT JOIN matches m2 ON (teams.tid = m2.home_tid AND m2.report_date_gmt = "0000-00-00 00:00:00" AND m2.deleted = 0)
LEFT JOIN schedules s2 ON (m2.sch_id = s2.sch_id AND s2.deleted = 0)

LEFT JOIN matches_pending mp1 ON (teams.tid = mp1.tid AND mp1.`mid` IS NULL AND mp1.deleted = 0)
LEFT JOIN matches_pending mp2 ON (teams.tid = mp2.tid AND mp2.`mid` IS NULL AND mp2.deleted = 0)
LEFT JOIN schedules sd1 ON (mp1.sch_id = sd1.sch_id AND sd1.deleted = 0)
LEFT JOIN schedules sd2 ON (mp2.sch_id = sd2.sch_id AND sd2.deleted = 0)

LEFT JOIN rosters ON (rosters.tid = teams.tid AND rosters.leave_date_gmt = "0000-00-00 00:00:00")

WHERE teams.lid = ? AND teams.deleted = 0
GROUP BY teams.tid
ORDER BY $orderBy ASC
SQL;

    $teams =& $db->getAssoc($sql, NULL, array($lid), NULL, TRUE);

    $listings = array('divisions'   => $divisions,
                       'conferences' => $conferences,
                       'groups'      => $groups,
                       'teams'       => $teams
                      );
    return $listings;
}


displayTemplate('teams.manager');