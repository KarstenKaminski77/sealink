<?php require_once('../Connections/inv.php'); ?>
<?php
('../Connections/inv.php'); 

session_start();

require_once('../functions/functions.php');

if(isset($_POST['ref'])){
$ref = $_POST['ref'];
} else {
$ref = $_GET['ref'];
}

$KTColParam1_Recordset1 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset1 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_inv, $inv);
$query_Recordset1 = sprintf("SELECT tbl_sites.Name AS Name_1, tbl_companies.Name, tbl_emergency.Id, tbl_emergency.Description, tbl_emergency.Cell, tbl_emergency.Email, tbl_emergency.Requestor, tbl_areas.Area FROM (((tbl_emergency LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_emergency.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_emergency.SiteId) LEFT JOIN tbl_areas ON tbl_areas.Id=tbl_emergency.AreaId) WHERE tbl_emergency.Id=%s ", $KTColParam1_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

select_db();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seavest Africa</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="sdmenu/blue/sdmenu.css" />
	<script type="text/javascript" src="sdmenu/sdmenu.js">
	</script>
	<script type="text/javascript">
<!--
var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.init();
	};

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
	<script type="text/javascript" src="Calendar.js"></script>
	<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
	<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
	<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
	<script src="../includes/resources/calendar.js"></script>
<script type="text/javascript">
function clickclear(thisfield, defaulttext) {
if (thisfield.value == defaulttext) {
thisfield.value = "";
}
}

function clickrecall(thisfield, defaulttext) {
if (thisfield.value == "") {
thisfield.value = defaulttext;
}
}
</script>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
	color: #FF0000;
}
-->
</style>
</head>

<body>
<div id="bar">Seavest Africa </div>
<table width="880" border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td width="240" align="center" class="td" valign="top">
	<img src="sdmenu/blue/logo.jpg" />
	</td>
    <td width="640" valign="top" class="td"><div id="right">
      <p><span class="style1">Seavest Emergency Reactive Service</span><br />
          <br />
          Hi <?php echo $row_Recordset1['Requestor']; ?></p>
      <p>You have submitted the following details:<br />
        <br />
</p>
      <table border="0" cellspacing="3" cellpadding="2">
        <tr>
          <td><b>Area:</b></td>
          <td><?php echo $row_Recordset1['Area']; ?></td>
        </tr>
        <tr>
          <td><b>Company:</b></td>
          <td><?php echo $row_Recordset1['Name']; ?></td>
        </tr>
        <tr>
          <td><b>Site:</b></td>
          <td><?php echo $row_Recordset1['Name_1']; ?></td>
        </tr>
        <tr>
          <td valign="top"><b>Description:</b></td>
          <td><?php echo $row_Recordset1['Description']; ?></td>
        </tr>
        <tr>
          <td><b>Requestor:</b></td>
          <td><?php echo $row_Recordset1['Requestor']; ?></td>
        </tr>
        <tr>
          <td><b>Contact no: </b></td>
          <td><?php echo $row_Recordset1['Cell']; ?></td>
        </tr>
        <tr>
          <td><b>Email:</b></td>
          <td><?php echo $row_Recordset1['Email']; ?></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
<div id="bar">Seavest Africa</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
