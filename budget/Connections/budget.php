<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_budget = "sql1.jnb1.host-h.net";
$database_budget = "budget";
$username_budget = "budget";
$password_budget = "budget001";
$budget = mysql_pconnect($hostname_budget, $username_budget, $password_budget) or trigger_error(mysql_error(),E_USER_ERROR); 
?>