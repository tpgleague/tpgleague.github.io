<?php

function smarty_modifier_easy_date($timestamp)
{
    $format = 'M jS, Y';
    return date($format, $timestamp);
}