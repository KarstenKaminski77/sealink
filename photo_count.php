<?php
$quoteno = $_GET['Id'];
$expire = 60 * 60 * 24 + time();
setcookie("quoteno",$quoteno,$expire);
$number = $_POST['no_new'];

header('Location: photos.php?no_new='. $number .'');
?>