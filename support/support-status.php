<?php
session_start();

require_once('../Connections/inv.php');

require_once('../includes/tng/tNG.inc.php');

require_once('../functions/functions.php');

select_db();
$userid = $_GET['User'];
$id = $_GET['Id'];
$date = date('Y-m-d H:i:s');

mysql_query("UPDATE tbl_support SET Status = 'Acknowledged', ResolvedDate = '$date' WHERE Id = '$id'")or die(mysql_error());

	$query = mysql_query("SELECT * FROM tbl_users WHERE Id = '$userid'")or die(mysql_error());
	$row = mysql_fetch_array($query);
	
$to  = $row['Email']; 
$subject = 'Sealink Support Update';
	
$message = '
<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
<div style="padding-left:55px; padding-top:50px">
<span class="header"><img src="http://www.seavest.co.za/inv/images/logo.jpg" /></span>
</div>
<div style="padding-left:55px; padding-top:50px">
<table border="0" cellpadding="2" width="700" cellspacing="3" style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
  <tr>
    <td>Hi '. $row['Name'] .'<br><br>The status your support ticket with the reference number '. $_GET['Id'] .' has been updated to <b><i>Acknowledged</i></b></td>
  </tr>
</table>
</div>
</body>
';

$mail = 'karsten@kwd.co.za';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'FROM: '. $mail . "\r\n";

mail($to, $subject, $message, $headers);


header('Location: current.php');
?>