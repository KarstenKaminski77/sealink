<?php
require_once('functions/functions.php');

select_db();

$jobid = $_GET['Id'];

// Update status and send to processing in order to regenerate invoice.
mysqli_query($con, "UPDATE tbl_jc SET Status = '7' WHERE JobId = '$jobid'") or die(mysqli_error($con));

//mysql_query("DELETE FROM tbl_sent_invoices WHERE JobId = '$jobid'")or die(mysql_error());

header('Location: invoices/inv-calc.php?Id=' . $jobid . '');
