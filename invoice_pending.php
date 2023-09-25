<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();

require_once('Connections/seavest.php'); ?>
<?php
// Load the Navigation classes
//require_once('includes/nav/NAV.php');

//$nav_Recordset3 = new NAV_Regular("nav_Recordset3", "Recordset3", "", KT_getPHP_SELF(), 50);

//MX Widgets3 include
//require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

select_db();

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con,$query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);


$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con,$query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);


$query_Recordset4 = "SELECT Id, Name FROM tbl_companies";
$Recordset4 = mysqli_query($con,$query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

if(isset($_POST['invoiceno'])){
$invoiceno = $_POST['invoiceno'];
$where = "WHERE Status = '7' AND InvoiceNo = ". $invoiceno ." AND tbl_jc.CompanyId != '0'";
}
elseif(isset($_POST['jobno'])){
$jobno = $_POST['jobno'];
$where = "WHERE Status = '7' AND JobNo = '". $jobno ."' AND tbl_jc.CompanyId != '0'";
}
elseif(isset($_POST['quoteno'])){
$quoteno = $_POST['quoteno'];
$where = "WHERE Status = '7' AND QuoteNo = ". $quoteno ." AND tbl_jc.CompanyId != '0'";
}
elseif(isset($_POST['oil'])){
$oil = $_POST['oil'];
$where = "WHERE Status = '7' AND CompanyId = ". $oil ." AND tbl_jc.CompanyId != '0'";
}
elseif(isset($_POST['date1'])){
$date1 = $_POST['date1'];
$date_1 = date('Y m d', strtotime($date1));
$date2 = $_POST['date2'];
$date_2 = date('Y m d', strtotime($date2));
$where = "WHERE Status = '7' AND SearchDate >= '". $date_1 ."' AND SearchDate <= '". $date_2 ."' AND tbl_jc.CompanyId != '0'";
}
elseif(isset($_GET['all'])){
$oil = $_POST['oil'];
$where = "WHERE Status = '7' AND tbl_jc.CompanyId != '0'";
}
elseif(isset($_POST['area'])){
$area = $_POST['area'];
$where = "WHERE Status = '7' AND tbl_jc.AreaId = ". $area ." AND tbl_jc.CompanyId != '0'";
} else {
$where = "WHERE Status = '7' AND tbl_jc.CompanyId != '0'";
}


$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_jc.Id, tbl_jc.JobDescription, tbl_jc.JobNo, tbl_jc.Date, tbl_companies.Name, tbl_sites.Address, tbl_jc.InvoiceSent, tbl_jc.JobId FROM ((tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) $where GROUP BY JobId ORDER BY tbl_jc.Id ASC";
$Recordset3 = mysqli_query($con,$query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);


$query_Recordset5 = "SELECT * FROM tbl_areas";
$Recordset5 = mysqli_query($con,$query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

//$nav_Recordset3->checkBoundries();
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
//$jsObject_Recordset2 = new WDG_JsRecordset("Recordset2");
//echo $jsObject_Recordset2->getOutput();
//end JSRecordset
?>
<script type="text/javascript">
  $NAV_SETTINGS = {
     'show_as_buttons': false
  }
</script>
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

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script>!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');</script>
  <script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
  <script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
  <link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>

<body <?php if(isset($_GET['jcn'])){ ?>onload="document.form2.jobno.focus();"<?php } ?>>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
      </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
            <tr>
              <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>

            <tr>
              <td colspan="3" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4"><div style="margin-left:30px">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="combo_bold">
                      <form name="form1" method="post" action="">
                        Search By:
                        <select name="menu1" class="tarea2" onChange="MM_jumpMenu('parent',this,0)">
                          <option>Select one...</option>
                          <option value="invoice_pending.php?all">All</option>
                          <option value="invoice_pending.php?area">Area</option>
                          <option value="invoice_pending.php?jcn">Job Card Number</option>
                          <option value="invoice_pending.php?date">Date</option>
                          <option value="invoice_pending.php?oil">Oil Company</option>
                          </select>
                        </form>                  </td>
                    <td nowrap>
                      <?php
				  if(isset($_GET['jcn'])){
				  $_SESSION['search'] = "jcn";
				  ?>
                      <form name="form2" method="post" action="invoice_pending.php?jcn">
                        &nbsp;
                        <input name="jobno" type="text" class="tarea2" id="jobno" style="cursor:text">
                        <input name="Submit" type="submit" class="tarea2" id="Submit" value="Search">
                        </form>
                      <?php } ?>
                      <?php
				  if(isset($_GET['in'])){
				  $_SESSION['search'] = "in";
				  ?>
                      <form name="form2" method="post" action="invoice_pending.php?in">
                        &nbsp;
                        <input name="invoiceno" type="text" class="tarea2" id="invoiceno" style="cursor:text">
                        <input name="Submit2" type="submit" class="tarea2" id="Submit2" value="Search">
                        </form>
                      <?php } ?>
                      <?php
				  if(isset($_GET['qn'])){
				  $_SESSION['search'] = "qn";
				  ?>
                      <form name="form2" method="post" action="invoice_pending.php?qn">
                        &nbsp;
                        <input name="quoteno" type="text" class="tarea2" id="quoteno" style="cursor:text">
                        <input name="Submit2" type="submit" class="tarea2" id="Submit2" value="Search">
                        </form>
                      <?php } ?>
                      <?php
				  if(isset($_GET['date'])){
				  $_SESSION['search'] = "date";
				  ?>
                      <form name="form2" method="post" action="invoice_pending.php?date">
                        &nbsp;
                        <input name="date1" class="tarea2" id="date1" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                        <input name="date2" class="tarea2" id="date2" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                        <input name="Submit3" type="submit" class="tarea2" id="Submit3" value="Search">
                        </form>
                      <?php } ?>
                      <?php
				  if(isset($_GET['oil'])){
				  $_SESSION['search'] = "oil";
				  ?>
                      <form name="form2" method="post" action="invoice_pending.php?oil">
                        &nbsp;
                        <select name="oil" class="tarea2" id="oil">
                          <?php
do {
?>
                          <option value="<?php echo $row_Recordset4['Id']?>"><?php echo $row_Recordset4['Name']?></option>
                          <?php
} while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4));
  $rows = mysqli_num_rows($Recordset4);
  if($rows > 0) {
      mysqli_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
  }
?>
                          </select>
                        <input name="Submit4" type="submit" class="tarea2" value="Search">
                        </form>
                      <?php } ?>
                      <?php if(isset($_GET['area'])){ ?>
                      <form name="form2" method="post" action="invoice_pending.php?rea">
                        &nbsp;
                        <select name="area" class="tarea2" id="area">
                          <?php
do {
?>
                          <option value="<?php echo $row_Recordset5['Id']?>"><?php echo $row_Recordset5['Area']?></option>
                          <?php
} while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5));
  $rows = mysqli_num_rows($Recordset5);
  if($rows > 0) {
      mysqli_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysqli_fetch_assoc($Recordset5);
  }
?>
                          </select>
                        <input name="Submit4" type="submit" class="tarea2" value="Search">
                        </form>
                      <?php } ?>					</td>
                    </tr>
                </table>
              </div>
              <br>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><div style="margin-left:30px"> <br>
<?php $jobid = $row_Recordset3['JobId']; ?>
                          <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="combo">
<tr>
  <td width="100" align="left" nowrap class="td-header"><strong>&nbsp;Invoice </strong></td>
  <td align="left" class="td-header"><strong>&nbsp;Company</strong></td>
  <td width="250" align="left" class="td-header"><strong>&nbsp;Site Address </strong></td>
  <td width="50" align="center" class="td-header"><strong>&nbsp;Age</strong></td>
  <td width="20" align="left" class="td-header" style="padding:0">&nbsp;</td>
</tr>
<?php
do {

	$jobid = $row_Recordset3['JobId'];
?>
<tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                                <td><a href="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"> &nbsp;<?php echo $row_Recordset3['JobNo']; ?></a></td>
                                <td><a href="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['Name']; ?></a></td>
                                <td><a href="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['Name_1']; ?></a></td>
                                <td align="center"><a href="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php time_schedule($jobid); ?></a><a name="<?php echo $row_Recordset3['JobId']; ?>"></a></td>
                                <td align="center" style="padding-left:0; padding-right:0"><a href="invoices/order-no.php?Id=<?php echo $row_Recordset3['JobId']; ?>&Pending" title="Order No" class="icon-circle icon-text2 various3">+</a></td>
                              </tr>
<?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                                                  </table>
                  </div></td>
                </tr>
              </table>
                </td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);

mysqli_free_result($Recordset5);

mysqli_free_result($Recordset4);
?>
