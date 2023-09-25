<?php require_once('../Connections/seavest.php'); ?>
<?php require_once('../Connections/seavest.php'); ?>
<?php
session_start();

require_once('../functions/functions.php');

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

if(isset($_POST['ref'])){
$ref = $_POST['ref'];
} else {
$ref = $_GET['ref'];
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT tbl_feedback.*, tbl_jc.JobNo, tbl_jc.JobDescription, tbl_jc.JobId, tbl_jc.CustomerFeedBack FROM (tbl_feedback LEFT JOIN tbl_jc ON tbl_jc.JobNo=tbl_feedback.Reference) WHERE tbl_jc.JobNo='$ref' ";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_feedback WHERE Reference = '$ref' ORDER BY Id DESC LIMIT 1";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$areaid = $_SESSION['area'];
$companyid = $_SESSION['company_id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_jc.JobNo, tbl_jc.Status, tbl_module_users.AreaId, tbl_jc.JobDescription, tbl_companies.Id FROM ((tbl_jc LEFT JOIN tbl_module_users ON tbl_module_users.AreaId=tbl_jc.AreaId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.Status=3  AND tbl_module_users.AreaId = '$areaid' AND tbl_companies.Id = '$companyid' GROUP BY JobNo";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
	<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
	<script src="../includes/common/js/base.js" type="text/javascript"></script>
	<script src="../includes/common/js/utility.js" type="text/javascript"></script>
	<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
	<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
	<script type="text/javascript" src="Calendar.js"></script>
	<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
	<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
	<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
	<script src="../includes/resources/calendar.js"></script>
	<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
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
</head>

<body>
<div id="bar">Seavest Africa </div>
<table width="880" border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td width="240" align="center" class="td" valign="top">
	<img src="sdmenu/blue/logo.jpg" />
	<br />
	<br />
	<?php include('menu.php'); ?></td>
    <td width="640" valign="top" class="td"><div id="right">
      <?php if(isset($_SESSION['user_id'])){ ?>
<form action="costing-detail.php" method="post" name="form1" id="form1">
        <table border="0" cellspacing="3" cellpadding="2">
          <tr>
            <td>Search</td>
            <td><select name="menu1" class="tarea" onchange="MM_jumpMenu('parent',this,0)">
                <option value=" ">Select one..</option>
                <option value="costing.php">View All</option>
                <option value="costing.php?num">By Reference No.</option>
                <option value="costing.php?date">By Date</option>
                          </select>            </td>
            <?php if(isset($_GET['date'])){ ?>
            <td>From:
              <input name="date1" class="tarea" id="date1" value="" size="10" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" /></td>
            <td>To:
              <input name="date2" class="tarea" id="date2" value="" size="10" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" /></td>
            <?php } // close date
			  if(isset($_GET['num'])){ ?>
			<td><input name="ref" type="text" class="tarea" id="ref" size="20" /></td>
			  <?php } // close client 
			  if(isset($_GET['date']) || isset($_GET['num'])){
			  ?>
            <td><input type="image" src="sdmenu/blue/btn_go.jpg" name="Submit" />
			</td>
            <?php }?>
            
          </tr>
        </table>
      </form>
	<?php } ?>
    <div style="margin:0px; padding:0px; margin-bottom:30px"></div>
	<div style="margin:0px; padding:0px; margin-bottom:15px">
    <table width="600" border="0" cellpadding="2" cellspacing="3">
      <tr>
        <td width="91" class="td"><div style="padding-left:5px"><strong>Reference No. </strong></div></td>
        <td width="492" class="td"><div style="padding-left:5px"><strong>Job Description</strong></div></td>
      </tr>
        <?php do { ?>
      <tr>
          <td valign="top"><div style="padding-left:5px; padding-right:5px">
		  <a href="detail.php?ref=<?php echo $row_Recordset3['JobNo']; ?>">
		  <?php echo $row_Recordset3['JobNo']; ?>
		  </a></div></td>
          <td valign="top"><div style="padding-left:5px; padding-right:5px">
		  <a href="detail.php?ref=<?php echo $row_Recordset3['JobNo']; ?>">
		  <?php 
		  $words = explode(" ",$row_Recordset3['JobDescription']);
		  $count = count($words);

		  for($i=0;$i<=11;$i++){
		  
		  $word = $words[$i];
		  	  
		  echo $word ." ";
		  }
		  
		  if($count >= 12){
		  $extra = "...";
		  echo $extra;
		  }
		  ?>
		  </a></div></td>
		  </tr>
          <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
    </table>
    </div>
	<div style="margin:0px; padding:0px; margin-bottom:15px"></div>
	</div></td>
  </tr>
</table>
<div id="bar">Seavest Africa</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
