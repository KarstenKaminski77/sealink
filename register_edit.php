<?php 
require_once('Connections/seavest.php');

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

$KTColParam1_Recordset2 = "0";
if (isset($_GET["week"])) {
  $KTColParam1_Recordset2 = (get_magic_quotes_gpc()) ? $_GET["week"] : addslashes($_GET["week"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT tbl_employees.Id, tbl_employees.AreaId, tbl_employees.Name, tbl_employees.Rate, tbl_register.DateMon, tbl_register.RateMon, tbl_register.RateTue, tbl_register.DateTue, tbl_register.RateWed, tbl_register.DateWed, tbl_register.RateThu, tbl_register.DateThu, tbl_register.RateFri, tbl_register.DateFri, tbl_register.RateSat, tbl_register.DateSat, tbl_register.RateSun, tbl_register.DateSun, tbl_register.Total, tbl_register.WeekNo FROM (tbl_employees LEFT JOIN tbl_register ON tbl_register.EmployeeId=tbl_employees.Id) WHERE tbl_register.WeekNo=%s ", $KTColParam1_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$numrows = $totalRows_Recordset2;

$mon = $_POST["Mon"];
$tue = $_POST["Tue"];
$wed = $_POST["Wed"];
$thu = $_POST["Thu"];
$fri = $_POST["Fri"];
$sat = $_POST["Sat"];
$sun = $_POST["Sun"];
$rate = $_POST['rate'];
$overtime = $_POST["overtime"];
$id = $_POST['Id'];

for($i=1;$i<=100;$i++){

$user = $id[$i];

$week = $_GET['week'];
$monday = $mon[$i];
$tuesday = $tue[$i];
$wednesday = $wed[$i];
$thursday = $thu[$i];
$friday = $fri[$i];
$saturday = $sat[$i];
$sunday = $sun[$i];
$daily_rate = $rate[$i];
$over = $overtime[$i];

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

$overtime_due = ($daily_rate / 9) * $over;

select_db();

mysql_query("UPDATE tbl_register SET RateMon = '$monday', RateTue = '$tuesday', RateWed = '$wednesday', RateThu = '$thursday', RateFri = '$friday', RateSat = '$saturday', RateSun = '$sunday', Overtime = '$overtime_due', HoursOver = '$over', DaysWorked = '$days_worked' WHERE EmployeeId = '$user'")or die(mysql_error());

$subtotal = $monday + $tuesday + $wednesday + $thursday + $friday + $saturday + $sunday;
$total = $subtotal + $overtime_due;
$week = $_GET['week'];

mysql_query("UPDATE tbl_register SET SubTotal = '$subtotal', Total = '$total', WeekNo = '$week' WHERE EmployeeId = '$user'")or die(mysql_error());
}
$areaid = $_SESSION['kt_AreaId'];

$KTColParam1_Recordset1 = "0";
if (isset($_GET["week"])) {
  $KTColParam1_Recordset1 = (get_magic_quotes_gpc()) ? $_GET["week"] : addslashes($_GET["week"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT tbl_employees.Id, tbl_employees.AreaId, tbl_employees.Name, tbl_employees.Rate, tbl_register.DateMon, tbl_register.DateMon, tbl_register.RateMon, tbl_register.RateTue, tbl_register.DateTue, tbl_register.RateWed, tbl_register.DateWed, tbl_register.RateThu, tbl_register.DateThu, tbl_register.RateFri, tbl_register.DateFri, tbl_register.RateSat, tbl_register.DateSat, tbl_register.RateSun, tbl_register.DateSun, tbl_register.Overtime, tbl_register.HoursOver, tbl_register.Total, tbl_register.SubTotal, tbl_register.WeekNo FROM (tbl_employees LEFT JOIN tbl_register ON tbl_register.EmployeeId=tbl_employees.Id) WHERE tbl_register.WeekNo=%s AND tbl_register.AreaId = '$areaid'", $KTColParam1_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$KTColParam1_Recordset3 = "0";
if (isset($_GET["week"])) {
  $KTColParam1_Recordset3 = (get_magic_quotes_gpc()) ? $_GET["week"] : addslashes($_GET["week"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_employees.Id, tbl_employees.AreaId, tbl_employees.Name, tbl_employees.Rate, tbl_register.DateMon, tbl_register.DateMon, tbl_register.RateMon, tbl_register.RateTue, tbl_register.DateTue, tbl_register.RateWed, tbl_register.DateWed, tbl_register.RateThu, tbl_register.DateThu, tbl_register.RateFri, tbl_register.DateFri, tbl_register.RateSat, tbl_register.DateSat, tbl_register.RateSun, tbl_register.DateSun, tbl_register.Overtime, tbl_register.HoursOver, tbl_register.Total,tbl_register.DaysWorked, tbl_register.SubTotal, tbl_register.WeekNo FROM (tbl_employees LEFT JOIN tbl_register ON tbl_register.EmployeeId=tbl_employees.Id) WHERE tbl_register.WeekNo=%s AND tbl_register.AreaId = '$areaid' ", $KTColParam1_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

function reg_date($date){
$split = explode(" ",$date);
$day = $split[0];
$month = $split[1];
echo date('D d', mktime(0,0,0,$month,$day));
}
$date = $row_Recordset1['DateMon'];
function year($date){
$split = explode(" ",$date);
$day = $split[0];
$month = $split[1];
$year = $split[2];
echo date('F Y', mktime(0,0,0,$month,$day,$year));
}
function register_total($week){
select_db();
$areaid = $_SESSION['kt_AreaId'];
$query = mysql_query("SELECT SUM(Total) FROM tbl_register WHERE WeekNo = '$week' AND AreaId = '$areaid' GROUP BY WeekNo")or die(mysql_error());
$row = mysql_fetch_array($query);

$total = $row['SUM(Total)'];
echo $total;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<style>
-->
</style>

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
<style type="text/css">
<!--
.style1 {font-size: 12px}
.style3 {
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php include('menu.php'); ?>
      </td>
    <td align="left" valign="top" bordercolor="#FFFFFF"><table width="900" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">
		<table border="0" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="center">
<form name="form1" method="post" action="register_edit.php?week=<?php echo $_GET['week']; ?>">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table border="0" cellpadding="3" cellspacing="1" bordercolor="#FFFFFF" class="combo" align="center">
<tr><td></td>
<td colspan="8" align="center" bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">Week <?php echo $row_Recordset2['WeekNo']; ?> <?php year($date); ?></td>
</tr><tr><td></td><td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">
<?php 
$date = $row_Recordset1['DateMon'];
reg_date($date); ?></td>
<td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">
<?php 
$date = $row_Recordset1['DateTue'];
reg_date($date); ?></td><td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">
<?php 
$date = $row_Recordset1['DateWed'];
reg_date($date); ?></td><td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">
<?php 
$date = $row_Recordset1['DateThu'];
reg_date($date); ?></td><td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">
<?php 
$date = $row_Recordset1['DateFri'];
reg_date($date); ?></td><td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">
<?php 
$date = $row_Recordset1['DateSat'];
reg_date($date); ?></td><td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">
<?php 
$date = $row_Recordset1['DateSun'];
reg_date($date); ?>
</td>
<td bordercolor="000066" bgcolor="#000066" class="td-header" style="color:#FFFFFF; font-weight:bold;">Overtime</td>
</tr>
<?php do { ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onmouseover="this.oldClassName = this.className; this.className='list-over';" onmouseout="this.className = this.oldClassName;">
    <td nowrap><?php echo $row_Recordset1['Name']; ?>
      <input name="rate[<?php echo $row_Recordset1['Id']; ?>]" type="hidden" id="rate[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset2['Rate']; ?>">
      <input name="Id[<?php echo $row_Recordset1['Id']; ?>]" type="hidden" id="Id[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Id']; ?>"></td>
    <td nowrap bordercolor="000066">
      
      <input type="radio" name="Mon[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Rate']; ?>" <?php if($row_Recordset1['RateMon'] == $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?> />
      Yes
      
      <input type="radio" name="Mon[<?php echo $row_Recordset1['Id']; ?>]" value="0.00" <?php if($row_Recordset1['RateMon'] != $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      No</td>
    <td nowrap bordercolor="000066">
      <input type="radio" name="Tue[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Rate']; ?>"  <?php if($row_Recordset1['RateTue'] == $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      Yes
      
      <input type="radio" name="Tue[<?php echo $row_Recordset1['Id']; ?>]" value="0.00" <?php if($row_Recordset1['RateTue'] != $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>No</td>
    <td nowrap bordercolor="000066">
      <input type="radio" name="Wed[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Rate']; ?>" <?php if($row_Recordset1['RateWed'] == $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      Yes
      
      <input type="radio" name="Wed[<?php echo $row_Recordset1['Id']; ?>]" value="0.00" <?php if($row_Recordset1['RateWed'] != $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      No</td>
    <td nowrap bordercolor="000066">
      
      <input type="radio" name="Thu[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Rate']; ?>" <?php if($row_Recordset1['RateThu'] == $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      Yes
      
      <input type="radio" name="Thu[<?php echo $row_Recordset1['Id']; ?>]" value="0.00" <?php if($row_Recordset1['RateThu'] != $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      No</td>
    <td nowrap bordercolor="000066">
      <input type="radio" name="Fri[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Rate']; ?>" <?php if($row_Recordset1['RateFri'] == $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      Yes
      
      <input type="radio" name="Fri[<?php echo $row_Recordset1['Id']; ?>]" value="0.00" <?php if($row_Recordset1['RateFri'] != $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      No</td>
    <td nowrap bordercolor="000066">
      <input type="radio" name="Sat[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Rate']; ?>" <?php if($row_Recordset1['RateSat'] == $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      Yes
      
      <input type="radio" name="Sat[<?php echo $row_Recordset1['Id']; ?>]" value="0.00" <?php if($row_Recordset1['RateSat'] != $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      No</td>
    <td nowrap bordercolor="000066">
      
      <input type="radio" name="Sun[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['Rate']; ?>" <?php if($row_Recordset1['RateSun'] == $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      Yes
      
      <input type="radio" name="Sun[<?php echo $row_Recordset1['Id']; ?>]" value="0.00" <?php if($row_Recordset1['RateSun'] != $row_Recordset1['Rate']){ echo 'checked="checked"'; } ?>/>
      No</td>
    <td align="center" nowrap bordercolor="000066"><input name="overtime[<?php echo $row_Recordset1['Id']; ?>]" type="text" class="tarea" id="overtime[<?php echo $row_Recordset1['Id']; ?>]" value="<?php echo $row_Recordset1['HoursOver']; ?>" size="5"></td>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    <tr>
      <td align="right" colspan="9"class="td-header">
	  Total: R
	    <?php $week = $_GET['week'];
	  register_total($week);
	  ?>	  
     </td>
    </tr>
    <tr>
      <td align="right" colspan="9" class="td-header">
        <input type="submit" value="Submit" class="tarea2">
      </td>
    </tr></table>
</form>	
      <p>&nbsp;</p>
      </td>
		</tr>
</table>
	</td>
      </tr>
    </table>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
mysql_free_result($Recordset3);
?>
