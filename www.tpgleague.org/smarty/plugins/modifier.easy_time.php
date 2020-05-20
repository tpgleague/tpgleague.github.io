<?php

function smarty_modifier_easy_time($timestamp)
{
    $format = 'g:i a T';
    return date($format, $timestamp);
}