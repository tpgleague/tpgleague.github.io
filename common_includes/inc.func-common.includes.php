<?php

function getPrivilegedMembers($tid)
{
    global $db;
    $sql = 'SELECT captain_uid FROM teams WHERE tid = ? LIMIT 1';
    $CaptainUID =& $db->getCol($sql, 0, array($tid));
    $sql = 'SELECT uid FROM rosters WHERE tid = ? AND leave_date_gmt = "0000-00-00 00:00:00" AND permission_reschedule = 1';
    $Schedulers =& $db->getCol($sql, 0, array($tid));
    return array_unique(array_merge($CaptainUID, $Schedulers));
}

function email($address, $subject='TPG Notification', $body)
{
        if (empty($address)) return FALSE;

        if (!defined('PHPMailerIncluded')) {
            $PHPMailerPath = dirname(__FILE__).'/PHPMailer';
            set_include_path(get_include_path() . PATH_SEPARATOR . $PHPMailerPath);
            define('PHPMailerIncluded', true);
            require_once 'class.phpmailer.php';
        }

        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Host = 'mail.tpgleague.org';
        $mail->SMTPAuth = true;
        $mail->Username = 'support@tpgleague.org';
        $mail->Password = 'blahblahblah';

        $mail->From = 'support@tpgleague.org';
        $mail->FromName = 'TPG League';
        $mail->AddAddress($address);

        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->Send();
}

function sendMessage($uid, $template, $notificationVars=NULL)
{
    if (empty($uid) || empty($template)) return FALSE;

    global $db, $tpl;

    if (!is_array($uid)) $uid = array($uid);

    $sql = 'SELECT IF(email="", pending_email, email) AS email, firstname FROM users WHERE uid = ? LIMIT 1';
    foreach ($uid as $userID) {
        $userInfo =& $db->getRow($sql, array($userID));
        $address = $userInfo['email'];
        $firstname = $userInfo['firstname'];
        $notificationVarsMerged = array_merge($notificationVars, array('firstname' => $firstname));
        
        $tpl->assign('notification', $notificationVarsMerged);
        //$message = trim($tpl->fetch('/home/tpgsite/domains/common_emails/'.$template.'.tpl'));
		$message = trim($tpl->fetch(COMMON_EMAILS_PATH . '\\' .$template.'.tpl'));
        $message = $message . "\r\n\r\n__\r\n".'http://www.tpgleague.org/'."\r\n";

        $subject = $tpl->get_template_vars('notification_subject');
        if (COOKIE_DOMAIN_NAME === '.tpgleague.org') {
            email($address, $subject, $message);
        } elseif (COOKIE_DOMAIN_NAME === '.tpg.pap.gotdns.com') {
            echo '<hr />';
            var_dump($address, $subject, $message);
            echo '<hr />';
        }

        unset($userInfo, $address, $firstname, $notificationVarsMerged, $message, $subject);
    }

    return TRUE;
}

function DateArrayToGMTString($formDate)
{
    $year = $formDate['Y']+0;
    $month = $formDate['M']+0;
    $day = $formDate['d']+0;
    $hour_12 = $formDate['g']+0;
    $meridian = $formDate['A'];
    $minute = $formDate['i']+0;
    $second = 0;
    if ($meridian == 'AM') {
        if ($hour_12 < 12) $hour = $hour_12;
        else $hour = 0;
    } else {
        if ($hour_12 == 12) $hour = 12;
        else $hour = $hour_12 + 12;
    }
    return gmstrftime('%Y-%m-%d %H:%M:%S', mktime($hour, $minute, $second, $month, $day, $year));
}

function DateArrayToString($formDate)
{
    $year = $formDate['Y']+0;
    $month = $formDate['M']+0;
    $day = $formDate['d']+0;
    $hour_12 = $formDate['g']+0;
    $meridian = $formDate['A'];
    $minute = $formDate['i']+0;
    $second = 0;
    if ($meridian == 'AM') {
        if ($hour_12 < 12) $hour = $hour_12;
        else $hour = 0;
    } else {
        if ($hour_12 == 12) $hour = 12;
        else $hour = $hour_12 + 12;
    }
    //return strftime('%Y-%m-%d %H:%M:%S', mktime($hour, $minute, $second, $month, $day, $year));
    return date('Y-m-d H:i:s', mktime($hour, $minute, $second, $month, $day, $year));

}