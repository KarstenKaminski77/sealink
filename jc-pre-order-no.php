<?php
$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');
?><?php
require_once('functions/functions.php');

select_db();

if(isset($_POST['orderno'])){
	
	$jobid = $_GET['Approve'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_query($con));
	$row = mysqli_fetch_array($query);
	
	$quoteno = $row['QuoteNo'];
	$comments = 'Quote No '. $quoteno .' Approved';
	$date = date('Y-m-d H:i:s');
	$orderno = $_POST['orderno'];
	
	mysql_query("INSERT INTO tbl_actual_history (JobId,TechnicianId,Date,Comments) VALUES ('$jobid','62','$date','$comments')")or die(mysql_error());
	
	// Send to Qued
	mysqli_query($con, "UPDATE tbl_jc SET Status = '1', RefNo = '$orderno' WHERE JobId = '$jobid'")or die(mysqli_error($con));	
	
	header('Location: jc-awaiting-order-no.php');
}
$jobid = $_GET['Id'];

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$inv_date = date('Y-m-d', strtotime($row['InvoiceDate']));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="styles/layout.css" rel="stylesheet" type="text/css" />

</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="200" align="center" valign="middle"><table border="0" align="center" cellpadding="2" cellspacing="3">
        <tr>
          <td align="left" class="btn-blue-generic">Order&nbsp;No</td>
          <td><input name="orderno" type="text" class="tarea-100per" id="orderno" size="40" style="padding-left:0px; padding-right:0px" /></td>
        </tr>
        <tr>
          <td colspan="2" align="right"><input name="Submit" type="submit" class="btn-blue-generic" value="Submit" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
