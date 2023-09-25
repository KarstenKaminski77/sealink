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

$areaid = $_SESSION['area'];
$companyid = $_SESSION['company_id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT tbl_jc.JobNo, tbl_jc.FeedBackTech, tbl_jc.Status, tbl_jc.CustomerFeedBack, tbl_jc.JobDescription, tbl_companies.Id, tbl_module_users.AreaId FROM ((tbl_jc LEFT JOIN tbl_module_users ON tbl_module_users.AreaId=tbl_jc.AreaId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobNo = '$ref' GROUP BY JobNo";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_feedback WHERE Reference = '$ref' ORDER BY Id DESC LIMIT 1";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
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
	<?php if($totalRows_Recordset1 >= 1){ ?>
	<p class="header">Your Reference No. <?php echo $ref; ?></p>
	<div style="margin:0px; padding:0px; margin-bottom:30px"></div>
	<div style="margin:0px; padding:0px; margin-bottom:15px">
    <table width="600" border="0" cellpadding="2" cellspacing="3">
      <tr>
        <td class="td"><div style="padding-left:5px"><strong>Assigned Technician </strong></div></td>
      </tr>
      <tr>
        <td><div style="padding-left:5px; padding-right:5px"> 
		<?php 
		$tech = $row_Recordset1['FeedBackTech'];

        $query = mysql_query("SELECT * FROM tbl_technicians WHERE Id = '$tech'")or die(mysql_error());
        $row = mysql_fetch_array($query);

		echo $row['Name']; ?> 
		</div></td>
      </tr>
    </table>
    </div>
	<div style="margin:0px; padding:0px; margin-bottom:15px">
    <table width="600" border="0" cellpadding="2" cellspacing="3">
      <tr>
        <td class="td"><div style="padding-left:5px"><strong>Service Requested </strong></div></td>
      </tr>
      <tr>
        <td><div style="padding-left:5px; padding-right:5px"> <?php echo $row_Recordset1['JobDescription']; ?> </div></td>
      </tr>
    </table>
    </div>
	<div style="margin:0px; padding:0px; margin-bottom:15px">
    <table width="600" border="0" cellpadding="2" cellspacing="3">
      <tr>
        <td class="td"><div style="padding-left:5px"><strong>Status</strong></div></td>
      </tr>
      <tr>
        <td><div style="padding-left:5px; padding-right:5px">
          <table border="0" cellspacing="3" cellpadding="3">
		  <?php
		  select_db();
		  
		  $query = mysql_query("SELECT * FROM tbl_feedback WHERE Reference = '$ref' AND Status = '1'")or die(mysql_error());
		  $row = mysql_fetch_array($query);
		  
		  if($row['Status'] >= 1){
		  ?>
            <tr>
              <td nowrap="nowrap"><img src="../images/check.jpg" width="15" height="15" /></td>
              <td width="30" nowrap="nowrap">Allocated to Seavest </td>
              <td nowrap="nowrap"><?php echo $row['Date']; ?></td>
              <td width="30" nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
              <td width="30" nowrap="nowrap">&nbsp;</td>
            </tr>
		  <?php
		  }
		  select_db();
		  
		  $query = mysql_query("SELECT * FROM tbl_feedback WHERE Reference = '$ref' AND Status = '2'")or die(mysql_error());
		  $row = mysql_fetch_array($query);
		  
		  if($row['Status'] >= 2){
		  ?>
            <tr>
              <td nowrap="nowrap"><img src="../images/check.jpg" width="15" height="15" /></td>
              <td nowrap="nowrap">
               Service In Progress              </td>
              <td nowrap="nowrap"><?php echo $row['Date']; ?></td>
              <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
            </tr>
		  <?php } 
		  
		  $query = mysql_query("SELECT * FROM tbl_feedback WHERE Reference = '$ref' AND Status = '3'")or die(mysql_error());
		  $row = mysql_fetch_array($query);
		  
		  if($row['Status'] >= 3){
		  ?>
            <tr>
              <td nowrap="nowrap"><img src="../images/check.jpg" width="15" height="15" /></td>
              <td nowrap="nowrap">
                Service Complete                </td>
              <td nowrap="nowrap"><?php echo $row['Date']; ?></td>
              <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
            </tr>
			<?php } ?>
          </table>
        </div></td>
      </tr>
    </table>
    </div>
	<div style="margin:0px; padding:0px; margin-bottom:15px">
    <table width="600" border="0" cellpadding="2" cellspacing="3">
      <tr>
        <td class="td"><div style="padding-left:5px"><strong>Feed Back </strong></div></td>
      </tr>
      <tr>
        <td><div style="padding-left:5px; padding-right:5px"> 
		<?php 
		if($row_Recordset2['Status'] >= 2){
		echo nl2br($row_Recordset1['CustomerFeedBack']); 
		} else {
		echo "Awaitng commencement";
		}
		?> 
		</div></td>
      </tr>
    </table>
	</div>
	<div style="margin:0px; padding:0px;">
          <form id="form3" name="form3" method="post" action="mail_detail.php">
    <table width="600" border="0" cellpadding="2" cellspacing="3">
      <tr>
        <td class="td"><div style="padding-left:5px"><strong>Submit Comment </strong></div></td>
      </tr>
      <tr>
        <td><textarea name="mail" rows="5" class="tarea" id="mail" style="width:580px; padding-left:5px; padding-right:5px"></textarea></td>
      </tr>
      <tr>
        <td align="right"><input name="ref" type="hidden" id="ref" value="<?php echo $_POST['ref']; ?>" />
          <input type="image" src="sdmenu/blue/btn_go.jpg" name="Submit3" value="Submit" /></td>
      </tr>
    </table>
          </form>
	</div>
        <?php } elseif(($totalRows_Recordset1 == 0) && (isset($_POST['Submit2']))){ ?>
      <p>&nbsp;</p>
      <p align="center">No results found ....
      </p>  
		<?php } else { ?>
      <p>&nbsp;</p>
      <p align="center">Welcome to ....
      </p>  
	  <?php } ?>
    </div></td>
  </tr>
</table>
<div id="bar">Seavest Africa</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
