<?php
$to  = $_POST['email']; 
$from = "Seavest Africa <control@seavest.co.za>";
$subject = 'Seavest Africa';
$message = $_POST['message'];


$message = "
<body style=\"font-family:tahoma; font-size:12px; margin: 20px; line-height:18px; color:#333366\">". $message ."<br><br><img src=\"http://www.seavest.co.za/inv/images/nicky.jpg\"></body>";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'FROM: '. $from . "\r\n";

mail($to, $subject, $message, $headers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body onload="window.close();">
</body>
</html>
