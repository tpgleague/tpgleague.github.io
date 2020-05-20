<?php


$pageTitle = 'Team List';
require_once '../includes/inc.initialization.php';

$tpl->append('external_js', 'jquery');
$tpl->append('external_js', 'jquery.dataTables');
$tpl->append('extra_head', '<script>$(document).ready( function () {
    $(\'#tblTeamList\').dataTable({ "bFilter": false, "bPaginate": false, "bInfo": false });
} );</script>');

$sql = 'SELECT tid, teams.name, tag, group_title, division_title FROM teams LEFT JOIN divisions USING (divid) LEFT JOIN groups USING (grpid) WHERE teams.lid = ? AND teams.approved = 1 AND teams.inactive = 0 AND teams.deleted = 0 ORDER BY name ASC';
$teamlist =& $db->getAll($sql, array(LID));

$tpl->assign('team_list', $teamlist);


displayTemplate('team.list', LID, 60, TRUE);