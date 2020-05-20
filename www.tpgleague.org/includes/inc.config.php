<?php

define('COOKIE_DOMAIN_NAME', '.tpgleague.org');

define('MY_ERROR_REPORTING', 0);

define('MY_DISPLAY_ERRORS', 0);

$dsn = array(
             'phptype'  => 'mysql',
             'username' => 'tpgleague',
             'password' => 'Expl0d3y!',
             'hostspec' => 'tpgleague.db.2443758.hostedresource.com',
             'port'     => 3306,
             'database' => 'tpgleague'
);
/*
preg_match('@^(?:http://)?([^/]+)@i', $_SERVER['HTTP_REFERER'], $referer_matches);
$bad_referer_found = strpos('loltpgbad.com', $referer_matches[1]);
if ($bad_referer_found !== FALSE) { echo '<!-- ' . $_SERVER['HTTP_REFERER'] . ' -->'; }
*/
