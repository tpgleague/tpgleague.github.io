<?php

function smarty_modifier_iso_datetime($timestamp)
{
    $format = 'Y-m-d h:i:s A T';
    return date($format, $timestamp);
}