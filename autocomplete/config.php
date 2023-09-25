<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
define('DB_HOST', '"sql15.jnb1.host-h.net');
define('DB_NAME', 'seavest_db333');
define('DB_USERNAME','kwdaco_333');
define('DB_PASSWORD','SBbB38c8Qh8');

$con = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if( mysqli_connect_error()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
