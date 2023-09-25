<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_inv = "sql15.jnb1.host-h.net";
$database_inv = "seavest_db333";
$username_inv = "kwdaco_333";
$password_inv = "SBbB38c8Qh8";
$inv = mysqli_connect($hostname_inv, $username_inv, $password_inv) or trigger_error(mysql_error(),E_USER_ERROR);

$con = mysqli_connect('sql15.jnb1.host-h.net','kwdaco_333','SBbB38c8Qh8','seavest_db333');

?>
