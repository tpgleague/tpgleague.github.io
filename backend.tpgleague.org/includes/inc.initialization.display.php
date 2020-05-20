<?php

if (isset($cssAppend)) {
    foreach ($cssAppend as $css) {
        $tpl->append('external_css', $css);
    }
}
if (isset($jsAppend)) {
    foreach ($jsAppend as $js) {
        $tpl->append('external_js', $js);
    }
}

if (isset($extra_head)) {
    foreach ($extra_head as $eh) {
        $tpl->append('extra_head', $eh);
    }
}

$sql = <<<SQL
            SELECT count(*)
            FROM teams
            WHERE teams.approved = 0 AND teams.deleted = 0
SQL;
$teamsPendingApprovalCount =& $db->getOne($sql);
$tpl->assign('teams_pending_approval_count', $teamsPendingApprovalCount);


displayHeader();
