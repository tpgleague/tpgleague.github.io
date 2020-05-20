<?php

function smarty_modifier_easy_day($timestamp)
{
    $format = 'l, M jS';
    return date($format, $timestamp);
}