<?php

function getLeagueAdmins ($lid)
{

    global $db;

$sql = <<<SQL

            SELECT DISTINCT divisions.division_title, admin_name
            FROM divisions
            INNER JOIN conferences USING (divid)
            INNER JOIN groups USING (cfid)

            LEFT JOIN admins_assignments
            ON (
                    (section = 'divisions'   AND pkid = divisions.divid)
                 OR (section = 'conferences' AND pkid = conferences.cfid)
                 OR (section = 'groups'      AND pkid = groups.grpid)
               )

            LEFT JOIN admins USING (aid)

            WHERE divisions.lid = ?
            AND divisions.inactive = 0
            AND conferences.inactive = 0
            AND groups.inactive = 0
            AND admin_name IS NOT NULL

            ORDER BY divisions.sort_order DESC, admin_name ASC

SQL;

    return $db->getAssoc($sql, FALSE, array($lid), NULL, TRUE);

}

function getLeagueHeadAdmins ($lid)
{

    global $db;

$sql = <<<SQL
            SELECT DISTINCT admin_name
            FROM leagues
            LEFT JOIN admins_assignments ON (section = 'leagues' AND pkid = leagues.lid)
            LEFT JOIN admins USING (aid)
            WHERE leagues.lid = ?
            AND admin_name IS NOT NULL
            ORDER BY admin_name ASC
SQL;

    return $db->getCol($sql, 0, array($lid));

}