<?php 
require_once('Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

select_db();

$userid = $_SESSION['kt_login_id'];

$query = mysql_query("SELECT * FROM tbl_users WHERE Id = '$userid'")or die(mysql_error());
$row = mysql_fetch_array($query);

$name = $row['Name'];
$date = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];

mysql_query("INSERT INTO tbl_login (Name, Date, Location) VALUES ('$name','$date','$ip')")or die(mysql_error());

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$userid = $_SESSION['kt_login_id'];
	
$query = mysqli_query($con, "SELECT tbl_management_report_details.Date, tbl_management_report_details.Id AS Row, tbl_management_reports.Id, tbl_management_reports.CoordinatorId, tbl_management_reports.Frequency, tbl_management_reports.Active, tbl_management_report_details.Old
FROM (tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id) WHERE tbl_management_reports.CoordinatorId = '$userid' AND tbl_management_report_details.Old = '0' GROUP BY tbl_management_report_details.ReportId")or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	setcookie('userid', $_SESSION['kt_login_id'], time()+3600, '/', '.seavest.co.za');
	
	$first_monday = date('Y-m-d', strtotime('First Monday of '.date('F o')));
	$first_day_week = date('Y-m-d', strtotime('Last Monday', time()));
	$today = date('Y-m-d H:i:s');
	if($row['Active'] == '1'){
		
		// Daily
		if($row['Frequency'] == 1){
			
			$report_date = date('Y-m-d 16:00:00', strtotime($row['Date'] . ' + 1 day')); 
			
			if($today >= $report_date){ 
				
				if(date('D', strtotime($row['Date'])) == 'Thu' && date('D') == 'Mon'){
					
					$friday = strtotime('last Friday');
					$date = date('Y-m-d', $friday);
					
					header('Location: management-reports/compulsory/send-reports.php?Date='.$date );
					exit();
				
				} else {
					
					header('Location: management-reports/compulsory/send-reports.php');
				    exit();
				}
			}
		}
		
		
		// Weekly
		if($row['Frequency'] == 2){
			
			if($first_day_week > $row['Date']){
				
				header('Location: http://www.seavest.co.za/inv/management-reports/compulsory/send-reports.php');
				exit();
			}
		}
		
		// Monthly
		if($row['Frequency'] == 3){
			
			if($first_monday > $row['Date']){
				
				header('Location: http://www.seavest.co.za/inv/management-reports/compulsory/send-reports.php');
				exit();
			}
		}
	}
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FFFFFF;
}
a:hover {
	text-decoration: none;
	color: #CCCCCC;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
-->
</style>
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td align="center"><p>&nbsp;</p>
          <p><img src="images/welcome.jpg" width="328" height="99"></p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
