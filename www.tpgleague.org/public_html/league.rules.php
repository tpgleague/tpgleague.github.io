<?php



$pageTitle = 'League Rules';
require_once '../includes/inc.initialization.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$tpl->append('external_css', 'league.rules');

//define('LID', LID);
//
error_reporting(1);
ini_set('display_errors', 1);

if (isset($_GET['lid']) && ctype_digit((string)$_GET['lid'])) {
    define('LID', $_GET['lid']);
} else {
    define('LID', 0);
}

$sql = 'SELECT show_rules FROM leagues WHERE lid = ?';
$show_rules =& $db->getOne($sql, array(LID));

$sql = 'SELECT linked_lid FROM leagues WHERE lid = ?';
$linked_lid =& $db->getOne($sql, array(LID));


$lidToShowRulesFor = LID;

if ($linked_lid)
{
    $lidToShowRulesFor = $linked_lid;
}

if (checkNumber(UID) && $show_rules) { 
    /*
    $currentTzName = date_default_timezone_get();
    date_default_timezone_set(LEAGUE_DEFAULT_TZ_NAME);
    $userViewRules = mktime(0, 0, 0); // just returns the current day in the league default timezone.
    date_default_timezone_set($currentTzName);
    */

    if (!empty($_SESSION['userViewRules'][$lidToShowRulesFor])) {
        $sql = 'SELECT UNIX_TIMESTAMP(last_view_gmt) FROM rules_user_views WHERE lid = ? AND uid = ? LIMIT 1';
        $userLastViewRules =& $db->getOne($sql, array($lidToShowRulesFor, UID));
    } else {
        $userLastViewRules = $_SESSION['userViewRules'][$lidToShowRulesFor];
    }
    $_SESSION['userViewRules'][$lidToShowRulesFor] = gmmktime(); //gmmktime(gmdate('H', gmmktime()), 0, 0); // "rounds" down GMT time to the hour

    if ($userLastViewRules != $_SESSION['userViewRules'][$lidToShowRulesFor]) {
$sql = <<<SQL
            INSERT INTO rules_user_views (lid, uid, last_view_gmt)
            VALUES (?, ?, FROM_UNIXTIME(?))
            ON DUPLICATE KEY UPDATE last_view_gmt = FROM_UNIXTIME(?)
SQL;
        $db->query($sql, array($lidToShowRulesFor, UID, $_SESSION['userViewRules'][$lidToShowRulesFor], $_SESSION['userViewRules'][$lidToShowRulesFor]));
    }
    if ($userLastViewRules) $userLastViewRules -= 86400; // force any rules updated within the last 24 hours to still show up.

    $tpl->assign('rules_user_last_view', $userLastViewRules);
}

if ($show_rules)
{
$sql = <<<SQL
        SELECT UNIX_TIMESTAMP(rules.modify_date_gmt) AS unix_modify_date_gmt, (COUNT(parent.rlid) - 1) AS depth, rules.section, rules.title, rules.body
        FROM (rules_nodes AS node,
        rules_nodes AS parent) INNER JOIN rules ON (rules.rlid = node.rlid)
        WHERE node.lid = ? AND parent.lid = ? AND node.lft BETWEEN parent.lft AND parent.rgt AND rules.section <> ''
        GROUP BY node.rlid
        ORDER BY node.lft
SQL;
    $rules =& $db->getAll($sql, array($lidToShowRulesFor, $lidToShowRulesFor));
    $tpl->assign('rules', $rules);
}

displayTemplate('league.rules', LID, 0, TRUE);
