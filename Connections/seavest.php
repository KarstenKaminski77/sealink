<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

require_once($_SERVER['DOCUMENT_ROOT'] . '/inv/functions/constants.php');

if (ENVIRONMENT == 'PRD') {

  $hostname_seavest = "sql15.jnb1.host-h.net";
  $database_seavest = "seavest_db333";
  $username_seavest = "kwdaco_333";
  $password_seavest = "SBbB38c8Qh8";
  $seavest = mysqli_connect($hostname_seavest, $username_seavest, $password_seavest) or trigger_error(mysql_error(), E_USER_ERROR);

  $con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');
} else if (ENVIRONMENT == 'STG') {

  $hostname_seavest = "127.0.0.1";
  $database_seavest = "seavest";
  $username_seavest = "laraveluser";
  $password_seavest = "#@$$F@CE123";
  $seavest = mysqli_connect($hostname_seavest, $username_seavest, $password_seavest);

  $con = mysqli_connect('127.0.0.1', 'laraveluser', '#@$$F@CE123', 'seavest');
} else {

  $hostname_seavest = "127.0.0.1";
  $database_seavest = "seavest";
  $username_seavest = "root";
  $password_seavest = "root";
  $seavest = mysqli_connect($hostname_seavest, $username_seavest, $password_seavest);

  $con = mysqli_connect('127.0.0.1', 'root', 'root', 'seavest');
}
