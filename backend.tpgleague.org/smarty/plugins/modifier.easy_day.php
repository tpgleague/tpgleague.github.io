<?php

function smarty_modifier_easy_day($timestamp)
{
    if (date('Y', $timestamp) == date('Y')) $format = 'l, M jS';
    else $format = 'M jS, Y';
    return date($format, $timestamp);
}