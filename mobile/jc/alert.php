<?php
require_once('../../functions/functions.php');
require_once('../../Connections/inv.php');

$today = date('Y-m-d');

$query = mysqli_query($con, "SELECT * FROM tbl_history_alerts WHERE OnHold = '0'")or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	$date = $row['Date'];
	$technicianid = $row['TechnicianId'];
	$jobno = $row['JobNo'];
	$jobid = $row['JobId'];
	
	if($today > $date){
		
//		$query2 = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '$technicianid'")or die(mysqli_error($con));
//		$row2 = mysqli_fetch_array($query2);
//		
//		   $to  = 'sms@messaging.clickatell.com'; 
//           $subject = 'Seavest';
//           $from = "control@seavest.co.za";
//           $cell = $row2['Cell'];
//
//$message = '
//user:seavest
//password:abc123
//api_id:3232946
//to:'. $cell .'
//reply: control@seavest.co.za
//concat: 3
//text: History for '. $jobno .' is overdue. http://www.seavest.co.za/inv/mobile/jc/history.php?Id='. $jobid ;
//
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//
//$headers .= 'FROM: '. $from . "\r\n";
//
//mail($to, $subject, $message, $headers);

	}}

		
	
?>