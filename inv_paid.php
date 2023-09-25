<?php
require_once('functions/functions.php');

$jobid = $_GET['Id'];

select_db();

mysql_query("UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'") or die(mysql_error());

header('Location: invoice_outstanding.php');

?>
