<?php
require_once('functions/functions.php');

select_db();

$quoteno = $_GET['Id'];

mysql_query("UPDATE tbl_qs SET Status = '0' WHERE QuoteNo = '$quoteno'") or die(mysql_error());

//mysql_query("DELETE FROM tbl_sent_invoices WHERE JobId = '$jobid'")or die(mysql_error());

header('Location: quote_calc.php?Id='. $quoteno .'');
?>
