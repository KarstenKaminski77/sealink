<?php require_once('../Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../includes/tng/tNG.inc.php');

require_once('../functions/functions.php');

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

if($_SESSION['kt_login_level'] ==1){
if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}} else {
$areaid = $_SESSION['kt_AreaId'];
}
if(isset($_POST['date1'])){
$date1 = $_POST['date1'];
$date_1 = date('Y m d', strtotime($date1));
$date2 = $_POST['date2'];
$date_2 = date('Y m d', strtotime($date2));
$area_id = "AreaId = '". $areaid ."'";
$where = "WHERE SearchDate >= '". $date_1 ."' AND SearchDate <= '". $date_2 ."' AND ". $area_id ."";
} else {
$where = "WHERE AreaId = '". $areaid ."'";
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_beneficiaries.Category, tbl_budget.Date, tbl_budget.Reference, tbl_budget.Description, tbl_budget.BankBalance, tbl_budget.TransactionAmount, tbl_budget.SearchDate, tbl_budget.SystemBalance, tbl_budget.TransactionType, tbl_areas.Area, tbl_budget.Beneficiary FROM ((tbl_budget LEFT JOIN tbl_areas ON tbl_areas.Id=tbl_budget.AreaId) LEFT JOIN tbl_beneficiaries ON tbl_beneficiaries.Id=tbl_budget.Beneficiary) $where AND Ini = '0' ORDER BY tbl_budget.Id ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT * FROM tbl_areas";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
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
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../includes/resources/calendar.js"></script>
<script type="text/javascript">
<!--
$NAV_SETTINGS = {
     'show_as_buttons': false
  }

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include($_SERVER['DOCUMENT_ROOT'] .'/inv/menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="823" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">
		<p>&nbsp;</p>
		<p> </p>

		<p>
		<table width="638" border="0" cellpadding="2" cellspacing="3" bordercolor="#FFFFFF">
          <tr>
            <td colspan="6" bordercolor="#FFFFFF" class="combo_bold"><p>&nbsp;</p>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="170" nowrap class="combo_bold"><form name="form1" method="post" action="">
                    Search By:
                        <select name="menu1" class="tarea" onChange="MM_jumpMenu('parent',this,0)">
                          <option>Select one...</option>
                          <option value="statements.php">All</option>
                          <option value="statements.php?date">Date</option>
                        </select>
                  </form></td>
                  <td width="400" nowrap><?php if(isset($_GET['date'])){ ?>
                      <form name="form2" method="post" action="statements.php?date">
                        <input name="date1" class="tarea" id="date1" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                        <input name="date2" class="tarea" id="date2" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                        <input name="Submit3" type="submit" class="tarea2" id="Submit3" value="Search">
                      </form>
                    <?php } ?></td>
                  <td width="200" align="right" nowrap class="combo_bold">
<?php
select_db();

$query = mysql_query("SELECT * FROM tbl_budget ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);
$bank_total = $row['BankBalance'];
?>
<span style="color:<?php echo colour_bank(); ?>">System Balance R <?php echo $row['BankBalance']; ?>&nbsp; <?php balance_out($areaid); ?></span></td>
                </tr>
              </table>
              <br></td>
            </tr>
          <tr class="td-header">
            <td width="100">Date</td>
            <td width="100">Reference</td>
            <td width="100">Beneficiary</td>
            <td width="200">Comments</td>
            <td width="50" align="right">Amount</td>
            <td width="50" align="right">Balance</td>
          </tr>
		<?php 
		do { 
		?>
<tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onmouseover="this.oldClassName = this.className; this.className='list-over';" onmouseout="this.className = this.oldClassName;">
                <td width="100" <?php if($row_Recordset3['Description'] == 'Seaveast Africa Deposit'){ echo '  style="color:#F00; font-weight:bold"'; } ?>><?php echo $row_Recordset3['Date']; ?></td>
                <td width="100" <?php if($row_Recordset3['Description'] == 'Seaveast Africa Deposit'){ echo '  style="color:#F00; font-weight:bold"'; } ?>><?php echo $row_Recordset3['Reference']; ?></td>
                <td width="100" <?php if($row_Recordset3['Description'] == 'Seaveast Africa Deposit'){ echo '  style="color:#F00; font-weight:bold"'; } ?>><?php echo $row_Recordset3['Beneficiary']; ?></td>
                <td width="200" <?php if($row_Recordset3['Description'] == 'Seaveast Africa Deposit'){ echo '  style="color:#F00; font-weight:bold"'; } ?>><?php echo $row_Recordset3['Description']; ?></td>
                <td width="50" align="right" <?php if($row_Recordset3['Description'] == 'Seaveast Africa Deposit'){ echo '  style="color:#F00; font-weight:bold"'; } ?>><?php
				if($row_Recordset3['TransactionType'] == 0){
				echo "-".$row_Recordset3['TransactionAmount']; 
				} else {
				echo $row_Recordset3['TransactionAmount']; 
				}
				?>                </td>
                <td width="50" align="right" <?php if($row_Recordset3['Description'] == 'Seaveast Africa Deposit'){ echo '  style="color:#F00; font-weight:bold"'; } ?>>R<?php echo $row_Recordset3['SystemBalance']; ?>				</td>
              </tr>
		    <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
        </table>
		  </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>
