<?php require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
<?php
// Load the Navigation classes
require_once('includes/nav/NAV.php'); 

$nav_Recordset3 = new NAV_Regular("nav_Recordset3", "Recordset3", "", KT_getPHP_SELF(), 50);

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

require_once('functions/functions.php');

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

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT Id, Name FROM tbl_companies";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

if(isset($_POST['invoiceno'])){
$invoiceno = $_POST['invoiceno'];
$where = "WHERE Status = '12' AND InvoiceNo = ". $invoiceno ."";
}
elseif(isset($_POST['jobno'])){
$jobno = $_POST['jobno'];
$where = "WHERE Status = '12' AND JobNo = '". $jobno ."'";
}
elseif(isset($_POST['quoteno'])){
$quoteno = $_POST['quoteno'];
$where = "WHERE Status = '12' AND QuoteNo = ". $quoteno ."";
}
elseif(isset($_POST['oil'])){
$oil = $_POST['oil'];
$where = "WHERE Status = '12' AND CompanyId = ". $oil ."";
}
elseif(isset($_POST['date1'])){
$date1 = $_POST['date1'];
$date_1 = date('Y m d', strtotime($date1));
$date2 = $_POST['date2'];
$date_2 = date('Y m d', strtotime($date2));
$where = "WHERE Status = '12' AND SearchDate >= '". $date_1 ."' AND SearchDate <= '". $date_2 ."'";
} 
elseif(isset($_POST['area'])){
$area = $_POST['area'];
$where = "WHERE Status = '12' AND tbl_jc.AreaId = ". $area ."";
}
elseif(isset($_GET['all'])){
$oil = $_POST['oil'];
$where = "WHERE Status = '12'";
} else {
$where = "WHERE Status = '12'";
}
$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$siteid = $row['SiteId'];
$companyid = $row['CompanyId'];

mysql_query("UPDATE tbl_jc SET CompanyId = '$companyid', SiteId = '$siteid' WHERE JobId = '$jobid'")or die(mysql_error());

if(isset($_GET['Id'])){

$jobid = $_GET['Id'];

mysql_query("UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'")or die(mysql_error());
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT  tbl_sites.Name AS Name1, STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort ,tbl_jc.InvoiceNo, tbl_companies.Name, tbl_jc.InvoiceDate, tbl_jc.JobId, tbl_jc.JobDescription, tbl_jc.Total2 ,tbl_jc.VatIncl ,sum(tbl_jc.Total1) AS sum_Total1_1 FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) $where GROUP BY tbl_jc.JobId ORDER BY date_for_sort ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
echo $totalRows_Recordset3;
mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT * FROM tbl_areas";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$colname_rs_totals = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_rs_totals = $_SESSION['kt_login_id'];
}
mysql_select_db($database_inv, $inv);
$query_rs_totals = sprintf("SELECT * FROM tbl_menu_relation WHERE UserId = %s AND MenuId = '47'", GetSQLValueString($colname_rs_totals, "int"));
$rs_totals = mysql_query($query_rs_totals, $inv) or die(mysql_error());
$row_rs_totals = mysql_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysql_num_rows($rs_totals);



$nav_Recordset3->checkBoundries();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset2 = new WDG_JsRecordset("Recordset2");
echo $jsObject_Recordset2->getOutput();
//end JSRecordset
?>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/skins/style.js" type="text/javascript"></script>
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
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>
<body <?php if(isset($_GET['jcn'])){ ?>onload="document.form2.jobno.focus();"<?php } ?>
      <?php if(isset($_GET['in'])){ ?>onload="document.form2.invoiceno.focus();"<?php } ?>
      <?php if(isset($_GET['qn'])){ ?>onload="document.form2.quoteno.focus();"<?php } ?>
>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
     </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
            <tr>
              <td colspan="3" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4"><table border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="combo_bold">
				  <form name="form1" method="post" action="">
                    Search By:
                    <select name="menu1" class="tarea2" onChange="MM_jumpMenu('parent',this,0)">
                      <option>Select one...</option>
                      <option value="invoice_outstanding.php?all">All</option>
                      <option value="invoice_outstanding.php?area">Area</option>
                      <option value="invoice_outstanding.php?in">Inovice Number</option>
                      <option value="invoice_outstanding.php?qn">Quote Number</option>
                      <option value="invoice_outstanding.php?jcn">Job Card Number</option>
                      <option value="invoice_outstanding.php?date">Date</option>
                      <option value="invoice_outstanding.php?oil">Oil Company</option>
                    </select>                  
                      </form>                  </td>
                  <td nowrap>
				  <?php if(isset($_GET['jcn'])){ ?>
				  <form name="form2" method="post" action="invoice_outstanding.php?jcn">
                     &nbsp;
                     <input name="jobno" type="text" class="tarea2" id="jobno" style="cursor:text">
                    <input name="Submit" type="submit" class="tarea2" id="Submit" value="Search">
                  </form>
				  <?php } ?>
				  <?php if(isset($_GET['in'])){ ?>
                    <form name="form2" method="post" action="invoice_outstanding.php?in">
                       &nbsp;
                       <input name="invoiceno" type="text" class="tarea2" id="invoiceno" style="cursor:text">
                      <input name="Submit2" type="submit" class="tarea2" id="Submit2" value="Search">
                    </form>
				  <?php } ?>
				  <?php if(isset($_GET['qn'])){ ?>
                    <form name="form2" method="post" action="invoice_outstanding.php?qn">
                       &nbsp;
                       <input name="quoteno" type="text" class="tarea2" id="quoteno" style="cursor:text">
                      <input name="Submit2" type="submit" class="tarea2" id="Submit2" value="Search">
                    </form>
				  <?php } ?>
				  <?php if(isset($_GET['date'])){ ?>
                    <form name="form2" method="post" action="invoice_outstanding.php?date">
                       &nbsp;
                       <input name="date1" class="tarea2" id="date1" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                      <input name="date2" class="tarea2" id="date2" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
<input name="Submit3" type="submit" class="tarea2" id="Submit3" value="Search">
                    </form>
					<?php } ?>
				  <?php if(isset($_GET['oil'])){ ?>
                    <form name="form2" method="post" action="invoice_outstanding.php?oil">
                       &nbsp;
                       <select name="oil" class="tarea2" id="oil">
                        <?php
do {  
?>
                        <option value="<?php echo $row_Recordset4['Id']?>"><?php echo $row_Recordset4['Name']?></option>
                        <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
                      </select>
                      <input name="Submit4" type="submit" class="tarea2" value="Search">
                    </form>
					<?php } ?>
				  <?php if(isset($_GET['area'])){ ?>
                    <form name="form2" method="post" action="invoice_outstanding.php?area">
                       &nbsp;
                       <select name="area" class="tarea2" id="area">
                         <?php
do {  
?>
                         <option value="<?php echo $row_Recordset5['Id']?>"><?php echo $row_Recordset5['Area']?></option>
                         <?php
} while ($row_Recordset5 = mysql_fetch_assoc($Recordset5));
  $rows = mysql_num_rows($Recordset5);
  if($rows > 0) {
      mysql_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysql_fetch_assoc($Recordset5);
  }
?>
                       </select>
                      <input name="Submit4" type="submit" class="tarea2" value="Search">
                    </form>
					<?php } ?>					</td>
                  </tr>
              </table>
                <br>
                <br>
                <div style="padding-left:30px;">
                  <table border="0" cellpadding="3" cellspacing="1">
                    <tr class="td-header">
                      <td width="75" align="center" nowrap><strong>Invoice </strong></td>
                      <td width="150" align="left"><strong>Company</strong></td>
                      <td width="200" align="left"><strong>Site Address </strong></td>
                      <td width="100" align="left"><strong>Date</strong></td>
                      <td width="75" align="left">Total</td>
                      <td width="40" align="left">&nbsp;</td>
                      <td width="40" align="left">&nbsp;</td>
                      </tr>
                    <?php do { 
				                  $jobid = $row_Recordset3['JobId'];
								  
								  $query = mysql_query("SELECT PDF FROM tbl_sent_invoices WHERE JobId = '$jobid'")or die(mysql_error());
								  $row = mysql_fetch_array($query);
								  $numrows = mysql_num_rows($query);
								 
				                  ?>
                    <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                      <td width="75" align="center" nowrap><a href="<?php check_invoice_old($jobid); ?>" class="menu"<?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                      <td width="150" nowrap><a href="<?php check_invoice_old($jobid); ?>" class="menu"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name']; ?></a></td>
                      <td width="200" nowrap><a href="<?php check_invoice_old($jobid); ?>" class="menu"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name1']; ?></a></td>
                      <td width="100" nowrap><a href="<?php check_invoice_old($jobid); ?>" class="menu"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['InvoiceDate']; ?></a></td>
                      <td width="75" nowrap><a href="<?php check_invoice_old($jobid); ?>" class="menu"<?php debtors_overdue($jobid); ?>>R
                        <?php if($row_Recordset3['Total2'] != 0){ 
							echo $row_Recordset3['Total2']; 
							} else {
							echo $row_Recordset3['VatIncl']; 
							}
							?></a></td>
                      <td width="40" align="center" nowrap>
                        <a href="fpdf16/pdf/<?php echo $row['PDF']; ?>" target="_blank">
                          <img title="View PDF" src="images/icons/btn-view.png" width="25" height="25" border="0"></a>
                        </td>
                      <td width="40" align="center" nowrap><a href="invoice_outstanding.php?Id=<?php echo $row_Recordset3['JobId']; ?>"><img title="Paid" src="images/icons/btn-paid.png" width="25" height="25" border="0"></a></td>
                      </tr>
                    <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                    <tr>
                      <td colspan="5" align="right" class="td-header">
					  <?php 
					  if($totalRows_rs_totals >= 1){
					  sum_outstanding($where); 
					  }
					  ?>
                      </td>
                      <td align="right" class="td-header">&nbsp;</td>
                      <td align="right" class="td-header">&nbsp;</td>
                      </tr>
                  </table>
                </div>
                <br>
                <br>
                <BR />
                <div class="KT_bottomnav" align="center">
                  <div class="combo">
                    <?php
      //Display Navigation		
      $nav_Recordset3->Prepare();
      require("includes/nav/NAV_Text_Navigation.inc.php");
    ?>
                  </div>
                </div></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset4);

mysql_free_result($Recordset3);

mysql_free_result($Recordset5);

mysql_free_result($rs_totals);
?>
