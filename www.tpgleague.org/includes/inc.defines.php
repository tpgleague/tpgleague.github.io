<?php

// The current league ID the player is in
if (isset($_GET['lid']) && ctype_digit((string)$_GET['lid'])) {
    define('LID', $_GET['lid']);
} else {
    define('LID', 0);
}
$tpl->assign('league_id', LID);
