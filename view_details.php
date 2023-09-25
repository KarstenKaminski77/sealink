<?php
session_start();

$_SESSION['quote_no'] = $_GET['Id'];

if(isset($_GET['photos'])){
$photos = "?photos";
}

header('Location: view.php'. $photos .'');

?>

