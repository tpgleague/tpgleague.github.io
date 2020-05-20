<?php

$pageTitle = 'Member Search';
require_once '../includes/inc.initialization.php';
$tpl->append('external_css', 'member.search');

$mainSQL = <<<SQL
        SELECT DISTINCT 
            users.uid AS users_uid, 
            users.firstname AS users_firstname,
            users.lastname AS users_lastname,
            users.hide_lastname AS hide_lastname,
            leagues.lgname AS leagues_lgname, 
            teams.tid AS teams_tid,
            teams.name AS teams_name,
            teams.tag AS teams_tag,
            rosters.handle AS rosters_handle,
            rosters.gid AS rosters_gid,
            UNIX_TIMESTAMP(rosters.join_date_gmt) AS rosters_join_date_gmt,
            UNIX_TIMESTAMP(rosters.leave_date_gmt) AS rosters_leave_date_gmt
        FROM users 
            LEFT JOIN rosters USING (uid) 
            LEFT JOIN teams USING (tid) 
            LEFT JOIN leagues USING (lid)
        WHERE leagues.inactive = 0 AND leagues.deleted = 0
            AND teams.approved = 1
            AND users.deleted = 0
            AND (
SQL;

$orderBySQL = <<<SQL
        ) ORDER BY users_uid ASC, lid ASC, rid ASC
SQL;


$searchTransArray = array(
                        'users_firstname' => 'users.firstname',
                        'users_lastname' => 'users.lastname',
                        'rosters_handle' => 'rosters.handle',
                        'rosters_gid' => 'rosters.gid',
                    );

//TODO: do not do a "like" search on game IDs
if ($_GET['search'])
{
    foreach ($_GET as $key => $value)
    {
        if ($sqlSch = $searchTransArray[$key])
        {
            if (substr($sqlSch, 0, 5) == 'users') $searchUserArray[$sqlSch] = str_replace('*', '%', $db->quoteSmart('%'.$_GET['search'].'%'));
            else $searchRosterArray[$sqlSch] = str_replace('*', '%', $db->quoteSmart('%'.trim($_GET['search']).'%'));
        }
    }

    if ($searchUserArray)
    {
        $i = 1;
        foreach ($searchUserArray as $key => $value)
        {
            if ($i === 1) $glue = '';
            else $glue = ' OR ';

		    $whereUser .= $glue . $key . ' LIKE ' . $value;
            ++$i;
        }
    }

    if ($searchRosterArray)
    {
        $i = 1;
        foreach ($searchRosterArray as $key => $value)
        {
            if ($i === 1) $glue = '';
            else $glue = ' OR ';
            $whereRoster .= $glue . $key . ' LIKE ' . $value;
            ++$i;
        }
    }

    if (empty($whereUser) && empty($whereRoster))
    {
        $tpl->assign('search_error', 'Please select some search criteria above.');
    }
    else
    {
        if ($whereUser)
        {
            $whereSQL = $whereUser . ' OR ';
        }
        
        if ($whereRoster)
        {
            $whereSQL .= $whereRoster . ' OR ';
        }
        
        $sql = $mainSQL . substr($whereSQL, 0, -3) . $orderBySQL;
        $searchResults =& $db->getAssoc($sql, NULL, NULL, NULL, TRUE);

        $tpl->assign('search_results', $searchResults);
    }
}

displayTemplate('member.search');
