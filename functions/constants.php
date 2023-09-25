<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);
define('ENVIRONMENT', 'PRD');

if (ENVIRONMENT == 'PRD') {

	define('DOMAIN', 'http://www.seavest.co.za/inv');
} else if (ENVIRONMENT == 'STG') {

	define('DOMAIN', 'http://reimaginedstudios.co.za/inv');
} else {

	define('DOMAIN', 'http://127.0.0.1:8080/inv');
}
