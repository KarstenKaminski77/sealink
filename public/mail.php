<?php
if
$to  = 'Seavest Africa <control@seavest.co.za>'; 
$subject = 'Seavest Africa Comment '. $_POST['ref'];

$message = '
<body style="font-family:arial; font-size:12px; line-height:18px; color:#333366"> 
<img src="http://www.seavest.co.za/inv/fpdf16/mail_logo.jpg">
<br><br>
<div style="font-family:arial; font-size:12px; line-height:18px; color:#333366">
<p>
'. nl2br($mail) .'
</p>
</div></body>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'FROM: Seavest Africa <nicky@seavest.co.za>' . "\r\n";

mail($to, $subject, $message, $headers);

header('Location: index.php?comment&ref='. $_POST['ref']);
?>