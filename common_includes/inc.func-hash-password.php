<?php

function hashPassword($password)
{
    //$strHashMethod = 'MHASH_SHA512';
    //$hashId = bin2hex(mhash(constant($strHashMethod), $argStrString));
    //return mhash($hashId, $password . str_repeat('NOdictAttack!', 13), 'myleetskeetKEY');

    //return mhash(MHASH_SHA512, $password . str_repeat('NOdictAttack!', 13), 'myleetskeetKEY');

    return mhash(constant('MHASH_SHA512'), $password . str_repeat('NOdictAttack!', 13), 'myleetskeetKEY');
}