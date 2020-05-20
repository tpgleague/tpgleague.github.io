<?php

function smarty_modifier_converted_timezone($timestamp)
{
    $format = 'Y-M-d g:i a T';
    return date($format, $timestamp);
}