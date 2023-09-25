<?php require_once('Connections/seavest.php'); ?><?php 
require_once('Connections/seavest.php');

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

$startTime = mktime(0, 0, 0, date('n'), (date('j')), date('Y')) - ((date('N')-1)*3600*24);
$endTime = mktime(); 
$olddate = date('n m Y', $startTime) .'</td>';

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT tbl_employees.AreaId, tbl_employees.Id, tbl_employees.Name, tbl_employees.Rate FROM (tbl_employees LEFT JOIN tbl_register ON tbl_register.EmployeeId=tbl_employees.Id) ";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$numrows = $totalRows_Recordset1;

$colname_Recordset2 = "-1";
if (isset($_GET['week'])) {
  $colname_Recordset2 = (get_magic_quotes_gpc()) ? $_GET['week'] : addslashes($_GET['week']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_register WHERE WeekNo = %s", $colname_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);


$mon = $_POST["Mon"];
$tue = $_POST["Tue"];
$wed = $_POST["Wed"];
$thu = $_POST["Thu"];
$fri = $_POST["Fri"];
$sat = $_POST["Sat"];
$sun = $_POST["Sun"];
$id = $_POST['Id'];

$mon_date = $_POST["date_Mon"];
$tue_date = $_POST["date_Tue"];
$wed_date = $_POST["date_Wed"];
$thu_date = $_POST["date_Thu"];
$fri_date = $_POST["date_Fri"];
$sat_date = $_POST["date_Sat"];
$sun_date = $_POST["date_Sun"];
$id = $_POST['Id'];

$date = $_POST['date'];

for($i=1;$i<=$numrows;$i++){

$user = $id[$i];

$query = mysql_query("SELECT * FROM tbl_employees WHERE Id = '$user'")or die(mysql_error());
$row = mysql_fetch_array($query);

$areaid = $row['AreaId'];

$areaid = $row['AreaId'];

$monday = $mon[$i];
$tuesday = $tue[$i];
$wednesday = $wed[$i];
$thursday = $thu[$i];
$friday = $fri[$i];
$saturday = $sat[$i];
$sunday = $sun[$i];

$monday_date = $mon_date[$i];
$tuesday_date = $tue_date[$i];
$wednesday_date = $wed_date[$i];
$thursday_date = $thu_date[$i];
$friday_date = $fri_date[$i];
$saturday_date = $sat_date[$i];
$sunday_date = $sun_date[$i];

select_db();
$week = $_GET['week'];

mysql_query("UPDATE tbl_register SET RateMon = '$monday_date', RateTue = '$tuesday_date', RateWed = '$weddnesday_date', RateThu = '$thursday_date', RateFri = '$friday_date', RateSat = '$saturday_date', RateSun = '$sunday_date' WHERE Id = '$user' AND WeekNo = '$week'")or die(mysql_error());

$total = $monday + $tuesday + $wednesday + $thursday + $friday + $saturday + $sunday;

mysql_query("UPDATE tbl_register SET Total = '$total' WHERE Id = '$user' AND WeekNo = '$week'")or die(mysql_error());
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
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
    <td align="left" valign="top" bordercolor="#FFFFFF"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="823" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
    </table>
    <p>
<?php
if(isset($_GET['duplicate'])){
?>
<p align="center" class="form_validation_field_error_error_message">You have already made an entry for this week!</p>
<?php
}
$i = 0;
echo '<form name="form1" method="post" action="register.php?submit">';
echo '<table border="1" cellpadding="3" cellspacing="3" bordercolor="#FFFFFF" class="combo" align="left">
<tr><td></td><td colspan="7">'. date('M Y') .'</td></tr><tr><td></td>';
for($i=0;$i<=6;$i++){
$startTime = mktime(0, 0, 0, date('n'), (date('j')+$i), date('Y')) - ((date('N')-1)*3600*24);
$endTime = mktime(); 
$mynewdate = '<td bordercolor="000066" bgcolor="#000066" style="color:#FFFFFF; font-weight:bold;">'. date('D d', $startTime) .'</td>';
echo $mynewdate;
}
echo '</tr>';
do {
echo '<tr><td bordercolor="000066" bgcolor="#000066" style="color:#FFFFFF; font-weight:bold;">'. $row_Recordset1['Name'] .'</td>';
$i=0;
for($i=0;$i<=6;$i++){
$startTime = mktime(0, 0, 0, date('n'), (date('j')+$i), date('Y')) - ((date('N')-1)*3600*24);
$endTime = mktime(); 
$newdate = date('j n Y', $startTime);
$present = date('D', $startTime);

echo '<td bordercolor="000066">
      <input type="radio" name="'.$present.'['.$row_Recordset1['Id'].']" value="'.$row_Recordset1['Rate'].'" checked="checked" />Yes</label>
	  <label><input type="radio" name="'.$present.'['.$row_Recordset1['Id'].']" value="0.00" />No</label>
	  <input type="hidden" value="'.$row_Recordset1['Id'].'" name="Id['.$row_Recordset1['Id'] .']">
	  <input type="hidden" value="'.$newdate.'" name="date_'. $present . '['.$row_Recordset1['Id'].']">
      </td>';
}
'</tr>';
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
if(!isset($_GET['duplicate'])){
echo '<tr><td align="right" colspan="8" bordercolor="000066" bgcolor="#000066" style="color:#FFFFFF; font-weight:bold;"><input type="submit" value="Submit" class="tarea2"></td></tr></table></form>';
}
?>
	</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
