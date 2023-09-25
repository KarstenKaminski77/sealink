<?php
require_once('../functions/functions.php');

select_db();

//$rows = count($_POST['contact']);
//	
//	$contact = $_POST['contact'];
//	$sms = $_POST['meesage'];
//	
//	for($i=0;$i<$rows;$i++){
//
//   $to  = 'sms@messaging.clickatell.com'; 
//   $subject = 'Seavest';
//   $from = "control@seavest.co.za";
//   $cell = $contact[$i];
//
//$message = '
//user:seavest
//password:abc123
//api_id:3232946
//to:'. $cell .'
//reply: control@seavest.co.za
//concat: 3
//text: '. $sms;
//
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//
//$headers .= 'FROM: '. $from . "\r\n";
//
//mail($to, $subject, $message, $headers);
//
//	}

header('Location: compose.php?sent');
?>
