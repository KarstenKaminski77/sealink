<?php require_once('Connections/seavest.php'); ?>
<?php
session_start();

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_technicians ORDER BY Name ASC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

select_db(); 

if(isset($_POST['date1'])){

$tech = $_POST['technician'];
$date1 = explode("-",$_POST['date1']);
$date2 = explode("-",$_POST['date2']);

$year1 = $date1[0];
$month1 = $date1[1];
$day1 = $date1[2];

$year2 = $date2[0];
$month2 = $date2[1];
$day2 = $date2[2];

$search1 = $year1 ." ". $month1 ." ". $day1;
$search2 = $year2 ." ". $month2 ." ". $day2;

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_technicians WHERE Id = '$tech'";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$query = mysql_query("SELECT SUM(Score), Date FROM tbl_score_relational WHERE TechnicianId = '$tech' AND Date >= '$search1' AND Date <= '$search2' GROUP BY TechnicianId")or die(mysql_error());
$row = mysql_fetch_array($query);

$query2 = mysql_query("SELECT * FROM tbl_score_relational WHERE TechnicianId = '$tech' AND Date >= '$search1' AND Date <= '$search2'")or die(mysql_error());
$numrows = mysql_num_rows($query2);

$score = $row['SUM(Score)'];
$total = $numrows * 10;

$average = ($score / $total) * 100;

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
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%"cellpadding="0" cellspacing="1">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">
		<form name="form2" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		  <p>&nbsp;</p>
		  <table border="0" cellspacing="3" cellpadding="2">
            <tr>
              <td><select name="technician" class="tarea" id="technician">
			  <option value=" ">Select one...</option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset1['Id']?>"><?php echo $row_Recordset1['Name']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select></td>
              <td><input name="date1" id="date1" value="" size="10" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" wdg:readonly="true"></td>
              <td><input name="date2" id="date2" value="" size="10" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" wdg:readonly="true"></td>
              <td><input name="Submit" type="submit" class="tarea2" value="Go"></td>
            </tr>
          </table>
		  <p>&nbsp;</p>
		  <table border="0" cellpadding="2" cellspacing="3" class="combo">
            <tr>
              <td class="combo_bold"><?php echo date('D d M Y', strtotime($_POST['date1'])) .' - '. date('D d M Y', strtotime($_POST['date2'])); ?></td>
              </tr>
            <tr>
              <td><?php echo $row_Recordset2['Name']; ?> - <?php echo $average; ?>%</td>
              </tr>
          </table>
		  <p>&nbsp;</p>
		</form>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
