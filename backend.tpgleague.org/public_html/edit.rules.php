<?php

$cssAppend[] = 'edit.rules';
if (isset($_GET['rlid'])) $tinyMCEmode = 'textareas';
else $tinyMCEmode = 'specific_textareas';
$extra_head[] = <<<JS

        <script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
        <script language="javascript" type="text/javascript">
        tinyMCE.init({
            mode : "$tinyMCEmode",
            width : "600",
            theme_advanced_layout_manager : "SimpleLayout",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_path : true,
            theme_advanced_buttons1_add : "forecolor,backcolor"
        });
        </script>


        <script type="text/javascript">

        function getposOffset(overlay, offsettype){
            var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
            var parentEl=overlay.offsetParent;
            while (parentEl!=null){
                totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
                parentEl=parentEl.offsetParent;
            }
            return totaloffset;
        }

        function overlay(curobj, subobj){
            if (document.getElementById){
                var subobj=document.getElementById(subobj)
                subobj.style.left=getposOffset(curobj, "left")+"px"
                subobj.style.top=getposOffset(curobj, "top")+"px"
                subobj.style.display="block"
                return false
            }
            else
            return true
        }

        function overlayclose(subobj){
            document.getElementById(subobj).style.display="none";
            document.getElementById('er_'+subobj).innerHTML = '';
        }

        </script>

        <script language="JavaScript" type="text/javascript">
        function checkform(form)
        {
            //alert(form.parent_rlid.value);
          if (form.title.value == "") {
            alert("Please enter a title.");
            form.title.focus();
            return false ;
          }
        /*
          if (form.section.value == "") {
            alert("Please enter a section name.");
            form.section.focus();
            return false ;
          }
        */
          if (form.parent_rlid.value != "0") {

            var radio_choice = false;

            for (counter = 0; counter < form.placement.length; counter++)
            {
            // If a radio button has been selected it will return true
            // (If not it will return false)
            if (form.placement[counter].checked)
                radio_choice = true; 
            }

            if (!radio_choice)
            {
                // If there were no selections made display an alert box 
                alert("Please specify the placement location.")
                return false;
            }

          }
          return true ;
        }

        function check_move_form(form)
        {

          if (form.move_rlid.value == form.move_destination.value) {
            alert("Please select a NEW location to move to.");
            form.move_destination.focus();
            return false ;
          }

            var radio_choice = false;

            for (counter = 0; counter < form.placement.length; counter++)
            {
            // If a radio button has been selected it will return true
            // (If not it will return false)
            if (form.placement[counter].checked)
                radio_choice = true; 
            }

            if (!radio_choice)
            {
                // If there were no selections made display an alert box 
                alert("Please specify the placement location.")
                return false;
            }


          return true ;
        }

        </script>

JS;
require_once '../includes/inc.initialization.support.php';

if (!checkNumber($_GET['lid'])) { displayError('Error: League ID not specified.'); }
else { define('LID', $_GET['lid']); }

$sql = 'SELECT league_title FROM leagues WHERE lid = ? LIMIT 1';
$leagueTitle =& $db->getOne($sql, array(LID));
$tpl->assign('league_title', $leagueTitle);

$ACCESS = checkPermission('Department', 'Operations');
//$ACCESS = TRUE;
if (!$ACCESS) $tpl->assign('access', FALSE);
else $tpl->assign('access', TRUE);


if ($ACCESS) {

    if (isset($_POST['parent_rlid'])) {
        if (!empty($_POST['title'])
            // && !empty($_POST['section'])
            && (
              ($_POST['parent_rlid'] != '0' && !empty($_POST['placement'])) ||
               $_POST['parent_rlid'] == '0'
            )
           ) {
            $bodyStripped = trim(strip_tags($_POST['body']));
            if (empty($bodyStripped)) $_POST['body'] = $bodyStripped;
            $modifiedDate = getTodayModify();
            $newRuleArray = array(
                            'title' => $_POST['title'],
                            //'section' => $_POST['section'],
                            'body' => $_POST['body'],
                            'inactive' => $_POST['inactive']?1:0,
                            'lid'        => LID,
                            'modify_date_gmt' => $modifiedDate
                           );
            $insertRecord = new InsertRecord();
            $insertRecord->insertData('rules', $newRuleArray);
            $newRuleID = $insertRecord->lastInsertId();

            if (!empty($_POST['body']) && !$_POST['inactive'] && !checkBranchInactive($newRuleID)) {
                $sql = 'UPDATE leagues SET last_rule_update_gmt = ? WHERE lid = ? LIMIT 1';
                $db->query($sql, array($modifiedDate, LID));
            }

            $sql = 'CALL rules_insert_node(?, ?, ?, ?)';
            $db->query($sql, array(LID, $newRuleID, $_POST['parent_rlid'], $_POST['placement']));
            populateRuleSections(LID);
            redirect('/edit.rules.php?lid='.LID);
        } else {
            displayError('There was an error in the form you submitted.');
        }
    } elseif (isset($_POST['move_rlid'])) {
        if (!empty($_POST['placement']) && 
            !empty($_POST['move_rlid']) && 
            !empty($_POST['move_destination']) &&
            ($_POST['move_destination'] != $_POST['move_rlid'])
           ) {
            $sql = <<<SQL
                SELECT TRUE
                FROM rules_nodes AS node, rules_nodes AS parent
                WHERE node.lft BETWEEN parent.lft AND parent.rgt
                AND node.rlid = ? AND parent.rlid = ?
                ORDER BY parent.lft
                LIMIT 1
SQL;
            $moveError =& $db->getOne($sql, array($_POST['move_destination'], $_POST['move_rlid']));
            if (!empty($moveError)) {
                $tpl->assign('error', 'Cannot move rule to a sub-branch of itself.');
            } else {
                $sql = 'CALL rules_move_branch(?, ?, ?, ?)';
                $db->query($sql, array(LID, $_POST['move_rlid'], $_POST['move_destination'], $_POST['placement']));
                populateRuleSections(LID);
                redirect('/edit.rules.php?lid='.LID);
            }
        } else {
            displayError('There was an error in the form you submitted.');
            //print_r($_POST);
        }
    } elseif (isset($_GET['rlid'])) {
        $sql = 'SELECT title, modify_date_gmt, section, inactive, body FROM rules WHERE rlid = ? AND lid = ?';
        $ruleEdit =& $db->getRow($sql, array($_GET['rlid'], LID));
        if (empty($ruleEdit)) displayError('There was an error in the form you submitted.');
        $tpl->assign('rule_edit', $ruleEdit);

        if (isset($_POST['submit'])) {
            $inactivated = $_POST['inactive'] ? 1 : 0;
            if (!empty($_POST['title'])
                // && !empty($_POST['section'])
               ) {
                $_POST['body'] = str_replace('&nbsp;', ' ', $_POST['body']);
                $bodyStripped = trim(strip_tags($_POST['body']));
                if (empty($bodyStripped)) $_POST['body'] = $bodyStripped;
                if (
                    !$inactivated
                    && $_POST['major_edit']
                    && !empty($bodyStripped)
                   ) {
                    $majorEdit = TRUE;
                    $modifiedDate = getTodayModify();
                }
                else {
                    $modifiedDate = $ruleEdit['modify_date_gmt'];
                }

                $ruleArray = array(
                                'rlid' => $_GET['rlid'],
                                'title' => $_POST['title'],
                                //'section' => $_POST['section'],
                                'body' => $_POST['body'],
                                'inactive' => $inactivated,
                                'modify_date_gmt' => $modifiedDate
                               );
                $updateRecord = new updateRecord('rules', 'rlid');
                $updateRecord->addData($ruleArray);
                $updateRecord->updateData();

                if ($majorEdit && !checkBranchInactive($_GET['rlid'])) {
                    $sql = 'UPDATE leagues SET last_rule_update_gmt = ? WHERE lid = ? LIMIT 1';
                    $db->query($sql, array($modifiedDate, LID));
                }

                if ($inactivated != $ruleEdit['inactive']) {
                    populateRuleSections(LID);
                }

                redirect('/edit.rules.php?lid='.LID);
            } else {
                $tpl->assign('error', 'Please make sure the title field is not empty.');
            }
        }
    }

}


$sql = <<<SQL
        SELECT node.rlid, (COUNT(parent.rlid) - 1) AS depth, rules.section, rules.title, node.lft, node.rgt, rules.inactive
        FROM (rules_nodes AS node,
        rules_nodes AS parent) INNER JOIN rules ON (rules.rlid = node.rlid)
        WHERE node.lid = ? AND parent.lid = ? AND node.lft BETWEEN parent.lft AND parent.rgt
        GROUP BY node.rlid
        ORDER BY node.lft
SQL;
$rules =& $db->getAll($sql, array(LID, LID));
$tpl->assign('rules', $rules);


require_once 'inc.initialization.display.php';
displayTemplate('edit.rules');













function getTodayModify()
{
    //return date('Y-m-d'); // this returns time of edit in the league's local time zone
    return mysqlNow(); // this returns GMT time
    //return gmdate('Y-m-d H:00:00', gmmktime(gmdate('H', gmmktime()), 0, 0)); // "rounds" down GMT time to the hour
}

function checkBranchInactive($rlid)
{
    global $db;

$sql = <<<SQL
    SELECT TRUE
    FROM rules_nodes AS node,
    rules_nodes AS parent INNER JOIN rules ON (parent.rlid = rules.rlid)
    WHERE node.lft BETWEEN parent.lft AND parent.rgt
    AND node.rlid = ?
    AND parent.rlid <> ?
    AND rules.inactive = 1
    ORDER BY parent.lft
    LIMIT 1
SQL;
    return $db->getOne($sql, array($rlid, $rlid));
}

function populateRuleSections ($lid)
{
    global $db;

    $sql = 'SELECT rlid, section FROM rules WHERE lid = ?';
    $currentSections =& $db->getAssoc($sql, TRUE, array($lid));

$sql = <<<SQL
SELECT node.rlid, (COUNT(parent.rlid) - 1) AS depth, node.lft, node.rgt, rules.inactive
FROM (rules_nodes AS node, rules_nodes AS parent)
INNER JOIN rules ON (rules.rlid = node.rlid)
WHERE node.lid = ? AND parent.lid = ? AND node.lft BETWEEN parent.lft AND parent.rgt
GROUP BY node.rlid
ORDER BY node.lft
SQL;
    $rules =& $db->getAll($sql, array($lid, $lid));

    $lastDepth = 0;
    $lastRule = '0';
    foreach ($rules as $rule) {

        if ($inactive_until <= $rule['lft']) {
            $inactive_until = 0;
            $inactive = 0;
        }
        if ($rule['inactive'] && !$inactive_until) {
            $inactive_until = $rule['rgt']+1;
            $inactive = 1;
        }
        

        if (!$inactive) {
            if ($lastDepth < $rule['depth']) { // we just dropped down a level.
                if ($lastRule == '0') {
                    $lastRule = 1;
                } else {
                    $lastRule = $lastRule.'.1';
                }
            } elseif ($lastDepth > $rule['depth']) { // we finished our current level and went up a level.
                $lastPos = strrpos($lastRule, '.');
                if ($lastPos === FALSE) {
                    $lastRule = $lastRule + 1;
                } else {
                    $sectionArray = explode('.', $lastRule);
                    if ($rule['depth'] == 0) {
                        $lastRule = $sectionArray[$rule['depth']]+1;
                    } else {
                        $lastRule = '';
                        $i = 0;
                        while ($i <= $rule['depth']) {
                            if ($rule['depth'] == $i) {
                                $lastRule = (string)$lastRule . '.' . (string)($sectionArray[$i]+1);
                            } else {
                                $lastRule = (string)$lastRule . '.' . (string)$sectionArray[$i];
                            }
                            ++$i;
                        }
                        if ($lastRule{strlen($lastRule)-1} == '.') {
                            $lastRule = substr($lastRule, 0, -1);
                        }
                        if ($lastRule{0} == '.') {
                            $lastRule = substr($lastRule, 1);
                        }

                    }
                }
            } else { // we are still doing the same level.
                $lastPos = strrpos($lastRule, '.');
                if ($lastPos === FALSE) {
                    $lastRule = $lastRule + 1;
                } else {
                    $sectionArray = explode('.', $lastRule);
                    $lastRule = substr($lastRule, 0, $lastPos) . '.' . (string)($sectionArray[$rule['depth']]+1);
                }
            }
            $lastDepth = $rule['depth'];
            settype($lastRule, 'string');

            if ($currentSections[$rule['rlid']] != $lastRule) {
                $updateData[] = array($lastRule, $rule['rlid']);
            }
        } else {
            if ($currentSections[$rule['rlid']] != '') {
                $updateData[] = array('', $rule['rlid']);
            }
        }
    }

    $sth = $db->prepare('UPDATE rules SET section = ? WHERE rlid = ?');
    $db->executeMultiple($sth, $updateData);
    $db->freePrepared($sth);

}

