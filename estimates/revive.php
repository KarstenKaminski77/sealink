<?php
require_once('../functions/functions.php');

$quoteno = $_GET['Id'];
$username = $_COOKIE['name'];
$userid = $_COOKIE['userid'];

mysqli_query($con, "UPDATE tbl_qs SET Status = '0', UsersName = '$username', UserId = '$userid' WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));

header('Location: quote-calc.php?Id='. $quoteno .'');
?>
