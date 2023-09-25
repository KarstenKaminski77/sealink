<?php
ini_set ("display_errors", "1");
error_reporting(E_ALL);

ob_start();

echo 'Hello world';

setcookie('test', '1', 3600 + time());

ob_end_flush();

ob_get_contents();

?>
