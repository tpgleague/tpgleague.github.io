<?php


$cssAppend[] = 'member.search';
require_once '../includes/inc.initialization.php';

$searchTransArray = array(
                        'users_username' => 'users.username',
                        'users_handle' => 'users.handle',
                        'users_email' => 'users.email',
                        'users_firstname' => 'users.firstname',
                        'users_lastname' => 'users.lastname',
                        'rosters_handle' => 'rosters.handle',
                        'rosters_gid' => 'rosters.gid',
                        'ip_address' => 'ip.address',
                        'ip_hostname' => 'ip_location.hostname'
                    );



if ($_GET['search']) {

    foreach ($_GET as $key => $value) {
        if ($sqlSch = $searchTransArray[$key]) {
            if (substr($sqlSch, 0, 5) == 'users') $searchUserArray[$sqlSch] = str_replace('*', '%', $db->quoteSmart('%'.$_GET['search'].'%'));
            elseif (substr($sqlSch, 0, 2) == 'ip') $searchIPArray[$sqlSch] = str_replace('*', '%', $db->quoteSmart('%'.$_GET['search'].'%'));
            else $searchRosterArray[$sqlSch] = str_replace('*', '%', $db->quoteSmart('%'.trim($_GET['search']).'%'));
        }
    }

    if ($searchUserArray) {
        $i = 1;
        foreach ($searchUserArray as $key => $value) {
            if ($i === 1) $glue = '';
            else $glue = ' OR ';
            if ($key == 'users.email') $whereUser .= $glue . ' (users.email LIKE ' . $value . ' OR users.pending_email LIKE ' . $value . ') ';
            else $whereUser .= $glue . $key . ' LIKE ' . $value;
            ++$i;
        }
    }

    if ($searchRosterArray) {
        $i = 1;
        foreach ($searchRosterArray as $key => $value) {
            if ($i === 1) $glue = '';
            else $glue = ' OR ';
            $whereRoster .= $glue . $key . ' LIKE ' . $value;
            ++$i;
        }
    }
    if ($searchIPArray) {
        $innerJoinIP = 'INNER JOIN ip ON (users.uid = ip.uid) LEFT JOIN ip_location USING (address)';
        $i = 1;
        foreach ($searchIPArray as $key => $value) {
            if ($i === 1) $glue = '';
            else $glue = ' OR ';

            if ($key == 'ip.address') $whereIP .= $glue . 'INET_NTOA(' . $key . ') LIKE ' . $value;
            else $whereIP .= $glue . $key . ' LIKE ' . $value;

            ++$i;
        }
    }

    if (empty($whereUser) && empty($whereRoster) && empty($whereIP)) {
        $tpl->assign('search_error', 'Please select some search criteria above.');
    } else {

$mainSQL = <<<SQL
    SELECT DISTINCT users.uid AS users_uid, users.username AS users_username, users.handle AS users_handle, 
    users.email AS users_email, users.pending_email AS users_pending_email,
    users.firstname AS users_firstname, users.lastname AS users_lastname, leagues.lgname AS leagues_lgname, 
    teams.tid AS teams_tid, teams.name AS teams_name, teams.tag AS teams_tag, rosters.handle AS rosters_handle, rosters.gid AS rosters_gid,
    UNIX_TIMESTAMP(rosters.join_date_gmt) AS rosters_join_date_gmt, UNIX_TIMESTAMP(rosters.leave_date_gmt) AS rosters_leave_date_gmt
    FROM users 
    LEFT JOIN rosters USING (uid) 
    LEFT JOIN teams USING (tid) 
    LEFT JOIN leagues USING (lid) $innerJoinIP
    WHERE 
SQL;
$orderBySQL = <<<SQL
    ORDER BY users_uid ASC, rid ASC
SQL;
        if ($whereUser) { $whereSQL = $whereUser . ' OR '; }
        if ($whereRoster) { $whereSQL .= $whereRoster . ' OR '; }
        if ($whereIP) { $whereSQL .= $whereIP . ' OR '; }
        $sql = $mainSQL . substr($whereSQL, 0, -3) . $orderBySQL;
        $searchResults =& $db->getAssoc($sql, NULL, NULL, NULL, TRUE);

        $tpl->assign('search_results', $searchResults);
    }
}


displayTemplate('member.search');

/*
echo "<!-- \r\n";
print_r($sql);
echo "\r\n -->";
*/