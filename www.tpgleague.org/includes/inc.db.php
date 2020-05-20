<?php

require_once 'inc.include-path.php';

require_once 'inc.config.php';

// A mess of useless functions:
require_once 'inc.func-include.php';

// Load this goodness:
require_once 'PEAR.php';

// PEAR DB connector:
require_once 'DB.php';

// Parameters for connecting to the database:
// $dsn moved to inc.config.php
$dsnOptions = array(
                    'debug'       => 2,
                    'autofree'    => FALSE,  // This gives PHP warnings if set to TRUE because using getAll, getOne, getAssoc, getCol, getRow will autofree the resultset anyway. I will handle freeing prepared statements manually.
                    'persistent'  => FALSE,
                    'portability' => DB_PORTABILITY_ALL
                   );
$db =& DB::connect($dsn,$dsnOptions);
if (PEAR::isError($db)) {
    // Print header, footer.
    header("HTTP/1.1 500 Internal Server Error");
    displayError('Website unavailable due to system maintenance. Please check back shortly.');
}
$db->setFetchMode(DB_FETCHMODE_ASSOC);
$db->query('SET NAMES "UTF8", time_zone = "UTC", sql_safe_updates = 0');
define('MYSQL_CONNECTION_ID', $db->getOne('SELECT CONNECTION_ID()'));
define('MYSQL_CONNECTED_TIME', microtime(TRUE));
unset($dsn);
