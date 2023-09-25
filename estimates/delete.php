<?php
require_once('../functions/functions.php');

$quoteid = $_GET['Id'];

mysqli_query($con, "UPDATE tbl_qs SET Status = '15' WHERE QuoteNo = '$quoteid'") or die(mysqli_error($con));

header('Location: create-new.php');

?>