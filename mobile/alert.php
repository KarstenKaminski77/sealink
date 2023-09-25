<?php
require_once('../functions/functions.php');

select_db();

$today = date('Y-m-d');

// Quotes
$query = mysql_query("SELECT * FROM tbl_history_alerts WHERE OnHold = '0' AND QuoteNo >= '1'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	
	$date = $row['Date'];
	$technicianid = $row['TechnicianId'];
	$quoteno = $row['QuoteNo'];
	
	if($today > $date){
		
//		$query2 = mysql_query("SELECT * FROM tbl_technicians WHERE Id = '$technicianid'")or die(mysql_error());
//		$row2 = mysql_fetch_array($query2);
//		
//		   $to  = 'sms@messaging.clickatell.com'; 
//           $subject = 'Seavest';
//           $from = "control@seavest.co.za";
//           $cell = $row2['Cell'];
//		   
//		   $message = '
//		   user:seavest
//		   password:abc123
//		   api_id:3232946
//		   to:'. $cell .'
//		   reply: control@seavest.co.za
//		   concat: 3
//		   text: History for '. $quoteno .' is overdue. http://www.seavest.co.za/inv/mobile/qs/history.php?Id='. $jobid ;
//		   
//		   $headers  = 'MIME-Version: 1.0' . "\r\n";
//		   $headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//		   $headers .= 'FROM: '. $from . "\r\n";
//		   
//		   mail($to, $subject, $message, $headers);
	}
}

// Job Cards
$query = mysql_query("SELECT * FROM tbl_history_alerts WHERE OnHold = '0' AND QuoteNo = '0'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	
	$date = $row['Date'];
	$technicianid = $row['TechnicianId'];
	$jobno = $row['JobNo'];
	$jobid = $row['JobId'];
	
	if($today > $date){
		
//		$query2 = mysql_query("SELECT * FROM tbl_technicians WHERE Id = '$technicianid'")or die(mysql_error());
//		$row2 = mysql_fetch_array($query2);
//		
//		   $to  = 'sms@messaging.clickatell.com'; 
//           $subject = 'Seavest';
//           $from = "control@seavest.co.za";
//           $cell = $row2['Cell'];
//		   
//		   $message = '
//		   user:seavest
//		   password:abc123
//		   api_id:3232946
//		   to:'. $cell .'
//		   reply: control@seavest.co.za
//		   concat: 3
//		   text: History for '. $jobno .' is overdue. http://www.seavest.co.za/inv/mobile/jc/history.php?Id='. $jobid ;
//		   
//		   $headers  = 'MIME-Version: 1.0' . "\r\n";
//		   $headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//		   $headers .= 'FROM: '. $from . "\r\n";
//		   
//		   mail($to, $subject, $message, $headers);
	}
}

		
	
?>