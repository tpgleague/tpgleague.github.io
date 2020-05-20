<?php

function smarty_modifier_custom_date($timestamp, $format='M d Y')
{
    return date($format, $timestamp);
}