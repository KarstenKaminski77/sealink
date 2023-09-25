<?php 
require_once('Connections/seavest.php');

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT tbl_employees.AreaId, tbl_employees.Id, tbl_employees.Name, tbl_employees.Rate FROM (tbl_employees LEFT JOIN tbl_register ON tbl_register.EmployeeId=tbl_employees.Id) WHERE tbl_employees.AreaId = '$areaid'";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$numrows = $totalRows_Recordset1;

$week = date('W');

$query2 = mysql_query("SELECT * FROM tbl_register WHERE WeekNo = '$week'")or die(mysql_error());
$numrows2 = mysql_num_rows($query2);

if($numrows2 == 0){

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
if(isset($_POST['Mon'])){
for($i=1;$i<=$numrows;$i++){

$areaid = $row['AreaId'];
$user = $id[$i];

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
$areaid = $_SESSION['kt_AreaId'];

select_db();

mysql_query("INSERT INTO tbl_register (AreaId,EmployeeId,DateMon,RateMon,DateTue,RateTue,DateWed,RateWed,DateThu,RateThu,DateFri,RateFri,DateSat,RateSat,DateSun,RateSun) VALUES ('$areaid','$user','$monday_date','$monday','$tuesday_date','$tuesday','$wednesday_date','$wednesday','$thursday_date','$thursday','$friday_date','$friday','$saturday_date','$saturday','$sunday_date','$sunday')")or die(mysql_error());

if($monday != 0.00){
$day1 = "1";
} else {
$day1 = "0.00";
}
if($tuesday != 0.00){
$day2 = "1";
} else {
$day2 = "0.00";
}
if($wednesday != 0.00){
$day3 = "1";
} else {
$day3 = "0.00";
}
if($thursday != 0.00){
$day4 = "1";
} else {
$day4 = "0.00";
}
if($friday != 0.00){
$day5 = "1";
} else {
$day5 = "0.00";
}
if($saturday != 0.00){
$day6 = "1";
} else {
$day6 = "0.00";
}
if($sunday != 0.00){
$day7 = "1";
} else {
$day7 = "0.00";
}
$days_worked = $day1 + $day2 + $day3 + $day4 + $day5 + $day6 + $day7;

$total = $monday + $tuesday + $wednesday + $thursday + $friday + $saturday + $sunday;
$subtotal = $monday + $tuesday + $wednesday + $thursday + $friday + $saturday + $sunday;
$week = date('W');

mysql_query("UPDATE tbl_register SET Total = '$total', WeekNo = '$week', DaysWorked = '$days_worked', SubTotal = '$subtotal' WHERE EmployeeId = '$user'")or die(mysql_error());
} // close for loop
header('Location: register_edit.php?week='.$week.'');
} // close if isset $_POST Mon
} // close if numrows
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
    <td align="center" valign="top" bordercolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
    </table>
    <p>
<?php
$i = 0;
echo '<form name="form1" method="post" action="register.php?submit">';
echo '<table border="1" cellpadding="3" cellspacing="3" bordercolor="#FFFFFF" class="combo" align="center">
<tr><td></td><td colspan="7">'. date('M Y') .'</td></tr><tr><td></td>';
$day = date('d');
$month = date('m');
$year = date('Y');
$ts = mktime(0,0,0,$month,($day - date('w')+1),$year); 
for ($i=0; $i<7; $i++, $ts+=86400){  
echo '<td bordercolor="000066" bgcolor="#000066" style="color:#FFFFFF; font-weight:bold;">'. date("D d", $ts) .'</td>';
echo $mynewdate;
}
echo '</tr>';
$cnt = 0; 
do {
$cnt++;

echo '<tr><td bordercolor="000066" bgcolor="#000066" style="color:#FFFFFF; font-weight:bold;">'. $row_Recordset1['Name'] .'</td>';
$day = date('d');
$month = date('m');
$year = date('Y');
$ts = mktime(0,0,0,$month,($day - date('w')+1),$year); 
for ($i=0; $i<7; $i++, $ts+=86400){  
$newdate = date('j n Y', $ts);
$present = date('D', $ts);

echo '<td bordercolor="000066">
      <input type="radio" name="'.$present.'[' .$cnt .']" value="'.$row_Recordset1['Rate'].'" checked="checked" />Yes</label>
	  <label><input type="radio" name="'.$present.'[' .$cnt .']" value="0.00" />No</label>
	  <input type="hidden" value="'.$row_Recordset1['Id'].'" name="Id['. $cnt .']">
	  <input type="hidden" value="'.$newdate.'" name="date_'. $present .'['. $cnt .']">
      </td>';
}
'</tr>';
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
if(isset($_GET['submit'])){
echo '<tr><td align="right" colspan="8" bordercolor="000066" bgcolor="#000066" style="color:#FFFFFF; font-weight:bold;"><input type="submit" class="tarea2" value="Enter Overtime"></td></tr></table></form>';
} else {
echo '<tr><td align="right" colspan="8" bordercolor="000066" bgcolor="#000066" style="color:#FFFFFF; font-weight:bold;"><input type="submit" class="tarea2" value="Save"></td></tr></table></form>';
}

?>
	</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
