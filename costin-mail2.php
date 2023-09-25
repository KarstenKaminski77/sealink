<?php require_once('Connections/seavest.php'); ?>
<?php
$colname_Recordset1 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);


?><?php
require_once('functions/functions.php');

$to = $_POST['email'];
$subject = 'Seavest Africa';
$ref = $row_Recordset1['JobNo'];
$work = $row_Recordset1['JobDescription'];
$from = "Seavest Africa <nicky@seavest.co.za>";

select_db();

$jobid = $_GET['Id'];

mysql_query("UPDATE tbl_sites SET Email = '$to' WHERE Id = '$siteid'")or die(mysql_error());

$message = '
<body style="font-family:tahoma; font-size:12px; line-height:18px; color:#333366"> 
<div style="font-family:arial; font-size:12px; line-height:18px; color:#333366">
Ref. No. '. $ref .': '. $work .' has successfully been completed. 
</div>
<br><br>
<img src="http://www.seavest.co.za/inv/images/mail_sig.jpg">
</body>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'FROM: '.$from . "\r\n";

mail($to, $subject, $message, $headers);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body onLoad="window.close();">
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
