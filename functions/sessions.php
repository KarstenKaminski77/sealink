<?php
session_start();

$_SESSION['areaid'] = $_GET['Area'];
setcookie("areaid", $_GET['Area'], 60 * 60 * 24 * 365 + time(), '/', '.seavest.co.za');
$_SESSION["kt_AreaId"] = $_GET['Area'];

header('Location: '. $_SERVER['HTTP_REFERER']);

?>