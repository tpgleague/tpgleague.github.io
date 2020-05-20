<?php

function smarty_modifier_simple_date($timestamp)
{
    $format = 'M d Y';
    return date($format, $timestamp);
}