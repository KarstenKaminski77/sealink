<?php
session_start();

$_SESSION['quote_no'] = $_GET['Id'];

header('Location: view.php');

?>

