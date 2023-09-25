<?php
session_start();

require_once('Connections/seavest.php');

require_once('functions/functions.php');

$areaid = $_SESSION['areaid'];

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
echo $day .' - '. $month .' - '. $year;
echo date('F Y', mktime(0,0,0,$month,$day,$year));
}
function register_total($week){
select_db();
$areaid = $_SESSION['areaid'];
$query = mysql_query("SELECT SUM(Total) FROM tbl_register WHERE WeekNo = '$week' AND AreaId = '$areaid' GROUP BY WeekNo")or die(mysql_error());
$row = mysql_fetch_array($query);

$total = $row['SUM(Total)'];
echo $total;
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
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
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
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
        <td align="center"><table width="823" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <p>
          <div style="padding-left:25px;">
            <table border="0" align="center" cellpadding="3" cellspacing="1" bordercolor="#FFFFFF">
              <tr align="center">
                <td>&nbsp;</td>
              <td align="center" class="td-header">Days Worked </td>
              <td align="center" class="td-header">Overtime (hrs) </td>
              <td align="center" class="td-header">Overtime Due </td>
              <td align="center" class="td-header">Sub Total </td>
              <td align="center" class="td-header">Total Due </td>
            </tr>
              <?php do { ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onmouseover="this.oldClassName = this.className; this.className='list-over';" onmouseout="this.className = this.oldClassName;">
                  <td><span><?php echo $row_Recordset3['Name']; ?></span></td>
                <td align="center"><?php echo $row_Recordset3['DaysWorked']; ?></td>
                <td align="center"><?php echo $row_Recordset3['HoursOver']; ?></td>
                <td align="center"><?php echo $row_Recordset3['Overtime']; ?></td>
                <td align="center"><?php echo $row_Recordset3['SubTotal']; ?></td>
                <td align="center"><?php echo $row_Recordset3['Total']; ?></td>
              </tr>
                <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
              <tr>
                <td colspan="6" align="right" class="td-header" >Total: R
                  <?php $week = $_GET['week'];
	  register_total($week);
	  ?>
                 </td>
              </tr>
                </table>
          </div>
          </p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset3);

mysql_free_result($Recordset2);
?>
