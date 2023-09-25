<?php
session_start();
session_destroy();
header('Location: http://www.seavest.co.za/inv/index.php');
?>