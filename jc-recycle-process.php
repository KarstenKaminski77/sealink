<?php
require_once('functions/functions.php');

select_db();

$jobid = $_GET['Id'];
$today = date('Y-m-d');

mysql_query("UPDATE tbl_jc SET Status = '13', Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());

header('Location: jc_select.php');
?>