<?php

$cssAppend[] = 'table';
require_once '../includes/inc.initialization.php';

$ACCESS = SUPERADMIN;
if (!$ACCESS) displayError('You are not authorized to view this page.');

// default option values to be used in form date field
$dateOptions = array(
                     'language'        => 'en',
                     'format'          => 'giAdMY',
                     'optionIncrement' => array('i' => '5'),
                     'maxYear'         => date('Y'),
                     'minYear'         => 2006,
                     'addEmptyOption'  => array('i' => TRUE, 'g' => TRUE, 'd' => TRUE, 'M' => TRUE, 'Y' => TRUE),
                     'emptyOptionValue'=> '',
                     'emptyOptionText' => array('i' => 'Min', 'g' => 'Hour', 'd' => 'Day', 'M' => 'Month', 'Y' => 'Year')
                    );
//

// SQL we will use
$sql = <<<SQL
            SELECT
                        `admins_action_log`.`aid`,
                        `admin_name`,
                        `admins_action_log`.`tablename`,
                        `admins_action_log`.`tablePkId`,
                        `admins_action_log`.`field`,
                        `admins_action_log`.`from_value`,
                        `admins_action_log`.`to_value`,
                        `admins_action_log`.`type`,
                        UNIX_TIMESTAMP(`admins_action_log`.`timestamp_gmt`) AS `unix_timestamp_gmt`,


                        CASE
                             `admins_action_log`.`tablename`
                        WHEN 'rosters' THEN (SELECT `rosters`.`tid` FROM `rosters` WHERE `rosters`.`rid` = `admins_action_log`.`tablePkId`)
                        WHEN 'teams'   THEN `admins_action_log`.`tablePkId`
                        ELSE NULL
                        END
                        AS `linked_table_record`,

                        CONVERT(
                          CASE
                               `admins_action_log`.`tablename`
                          WHEN 'rosters' THEN (SELECT `rosters`.`handle` FROM `rosters` WHERE `rosters`.`rid` = `admins_action_log`.`tablePkId`)
                          WHEN 'teams'   THEN (SELECT `teams`.`name` FROM `teams` WHERE `teams`.`tid` = `admins_action_log`.`tablePkId`)
                          ELSE NULL
                          END
                        USING utf8)
                        AS `linked_descriptor`

            FROM
                        `admins_action_log`
            INNER JOIN
                        `admins`
            USING
                        (`aid`)
            WHERE
                        `timestamp_gmt`
            BETWEEN
                        ?
            AND
                        ?
            ORDER BY
                        `timestamp_gmt` DESC
SQL;
//



$selectDateForm = new HTML_QuickForm('select_date_form', 'get', $qfAction, NULL, $onsubmit, TRUE);
$selectDateForm->removeAttribute('name'); // XHTML compliance
$selectDateForm->applyFilter('__ALL__', 'trim');
$selectDateForm->registerRule('valid_date', 'function', 'checkValidDate');

$selectDateForm->addElement('date', 'start_date', 'Start Date', $dateOptions);
$selectDateForm->addElement('static', 'note_start_date', 'Time and date ('.date('T').')');
$selectDateForm->addRule('start_date', 'Please enter a valid date.', 'required');
$selectDateForm->addRule('start_date', 'Please enter a valid date.', 'valid_date');

$selectDateForm->addElement('date', 'end_date', 'End Date', $dateOptions);
$selectDateForm->addElement('static', 'note_end_date', 'Time and date ('.date('T').')');
$selectDateForm->addRule('end_date', 'Please enter a valid date.', 'required');
$selectDateForm->addRule('end_date', 'Please enter a valid date.', 'valid_date');

$selectDateForm->addElement('submit', 'submit', 'View Admin Log');

// If form submitted, grab the values, use them as the defaults.
// If form not submiited, use last 30 days as default values.

if ($_GET['start_date'] && $_GET['end_date']) {
    $startDate = DateArrayToString($_GET['start_date']);
    $endDate = DateArrayToString($_GET['end_date']);
} else {
    // Form not submitted; use last 30 days:
    $endDateTimestamp = strtotime('+1 Day', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
    $startDateTimestamp = strtotime('-7 Day', $endDateTimestamp);

    $startDate = date('Y-m-d H:i:s', $startDateTimestamp);
    $endDate = date('Y-m-d H:i:s', $endDateTimestamp);
}

$Date = dateToArray($startDate);
if ($Date['hour'] > 12) {
    $hour_12 = $Date['hour']-12;
    $meridian = 'PM';
} elseif ($Date['hour'] == 0) {
    $hour_12 = 0;
    $meridian = 'AM';
} elseif ($Date['hour'] == 12) {
    $hour_12 = 0;
    $meridian = 'PM';
} else {
    $hour_12 = $Date['hour'];
    $meridian = 'AM';
}
$selectDateForm->setDefaults(array('start_date' => array('Y' => $Date['year'], 'M' => $Date['month'], 'd' => $Date['day'], 'g' => $hour_12, 'A' => $meridian, 'i' => $Date['minute'])));

$Date = dateToArray($endDate);
if ($Date['hour'] > 12) {
    $hour_12 = $Date['hour']-12;
    $meridian = 'PM';
} elseif ($Date['hour'] == 0) {
    $hour_12 = 0;
    $meridian = 'AM';
} elseif ($Date['hour'] == 12) {
    $hour_12 = 0;
    $meridian = 'PM';
} else {
    $hour_12 = $Date['hour'];
    $meridian = 'AM';
}
$selectDateForm->setDefaults(array('end_date' => array('Y' => $Date['year'], 'M' => $Date['month'], 'd' => $Date['day'], 'g' => $hour_12, 'A' => $meridian, 'i' => $Date['minute'])));


if ($selectDateForm->isSubmitted() && $selectDateForm->validate()) {
    $timestamp_start_date_gmt = DateArrayToGMTString($selectDateForm->exportValue('start_date'));
    $timestamp_end_date_gmt = DateArrayToGMTString($selectDateForm->exportValue('end_date'));
    if ($timestamp_start_date_gmt >= $timestamp_end_date_gmt) {
        $selectDateForm->setElementError('start_date', 'Start Date must be BEFORE End Date.');
    } else {
        $adminActionLog =& $db->getAll($sql, array($timestamp_start_date_gmt, $timestamp_end_date_gmt));
        $tpl->assign('admin_action_log', $adminActionLog);
        $tpl->assign('admin_log_form_submitted', TRUE);
    }
} else {
    $timestamp_start_date_gmt = $startDate;
    $timestamp_end_date_gmt = $endDate;
}
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$selectDateForm->accept($renderer);
$tpl->assign('select_date_form', $renderer->toArray());

displayTemplate('admins.action.log');
