<?php

$quoteno = $_GET['Id'];

setcookie('costing-'. $_GET['Id'], $_GET['Id'], 60 * 60 * 24 * 365 + time());

header('Location: '. $_SERVER['HTTP_REFERER']);

?>