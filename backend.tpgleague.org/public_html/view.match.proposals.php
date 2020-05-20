<?php


require_once '../includes/inc.initialization.php';

if (!checkNumber(@$_GET['mid'])) { displayError('Error: Match ID not specified.'); }
else { @define('MID', @$_GET['mid']); }

$sql = 'SELECT lid, sch_id FROM matches INNER JOIN schedules USING (sch_id) WHERE mid = '. $db->quoteSmart(MID) .' LIMIT 1';
$match =& $db->getRow($sql);
define('LID', $match['lid']);
define('SCH_ID', $match['sch_id']);



$sql = <<<SQL

SELECT mpid, UNIX_TIMESTAMP(matches_proposed.create_date_gmt) AS create_date_gmt, UNIX_TIMESTAMP(proposed_date_gmt) AS proposed_date_gmt, UNIX_TIMESTAMP(review_date_gmt) AS review_date_gmt,
    (SELECT CONCAT("[", username, "]", " ", firstname, ' "', handle, '" ', lastname) FROM users WHERE users.uid = matches_proposed.proposed_uid LIMIT 1) AS proposed_player,
    (SELECT name FROM teams WHERE teams.tid = matches_proposed.proposed_tid LIMIT 1) AS proposed_team, proposed_tid, 
    (SELECT CONCAT("[", username, "]", " ", firstname, ' "', handle, '" ', lastname) FROM users WHERE users.uid = matches_proposed.reviewer_uid LIMIT 1) AS reviewer_player,
    (SELECT name FROM teams WHERE teams.tid = matches_proposed.reviewer_tid LIMIT 1) AS reviewer_team, reviewer_tid, 
comments, review_comments, status, home_server_choice, home_tid, away_tid, proposed_uid, reviewer_uid 
FROM matches_proposed 
INNER JOIN matches USING (`mid`) 
WHERE mid = ? 
ORDER BY mpid DESC

SQL;
$proposalList =& $db->getAll($sql, array(MID));
$tpl->assign('proposal_list', $proposalList);

displayTemplate('view.match.proposals');