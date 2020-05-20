<?php

function getLeaguesList()
{
    global $db;

    $sql = 'SELECT lid, lgname, league_title FROM leagues WHERE inactive = 0 AND deleted = 0 ORDER BY sort_order DESC, league_title ASC';
    $leagues =& $db->getAll($sql);
    return $leagues;
}
