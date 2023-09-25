<?php
require_once('functions/functions.php');

select_db();

$jobid = $_GET['Id'];
$today = date('Y-m-d');
$query = "UPDATE tbl_jc SET Status = '14', Days = '$today' WHERE JobId = '$jobid'";

mysqli_query($con, $query) or die(mysqli_error($con));

header('Location: outbox.php');
