<?php
require_once('functions/functions.php');

$quoteid = $_POST['quoteno'];

select_db();

mysql_query("UPDATE tbl_qs SET Status = '15' WHERE QuoteNo = '$quoteid'") or die(mysql_error());

header('Location: quote.php');

?>