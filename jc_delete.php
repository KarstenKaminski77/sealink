<?php
require_once('functions/functions.php');

select_db();

$jobid = $_GET['Id'];

mysql_query("DELETE FROM tbl_jc WHERE JobId = '$jobid'") or die(mysql_error());

header('Location: jc_select.php');
?>