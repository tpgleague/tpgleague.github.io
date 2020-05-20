<?php

function smarty_insert_friendly_date($params, &$smarty)
{
    $timestamp =& $params['timestamp'];
    $timeDiff = mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp)) - mktime();
    if ($timeDiff > 0 && $timeDiff < 86400) $format = '\T\o\m\o\r\r\o\w \a\t g:i a T';
    elseif ($timeDiff > -86400 && $timeDiff < 86400) $format = '\T\o\d\a\y \a\t g:i a T';
    elseif ($timeDiff > -172800 && $timeDiff < 86400) $format = '\Y\e\s\t\e\r\d\a\y \a\t g:i a T';
    else $format = 'D, M jS \a\t g:i a T';
    return date($format, $timestamp);
}
