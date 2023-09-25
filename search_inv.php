<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the Navigation classes
require_once('includes/nav/NAV.php'); 

$nav_Recordset3 = new NAV_Regular("nav_Recordset3", "Recordset3", "", KT_getPHP_SELF(), 50);

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

select_db();

// Change status

if(isset($_POST['status'])){
	
	$status = $_GET['Status'];
	$today = date('Y-m-d');
	
	mysqli_query($con, "UPDATE tbl_jc SET Status = '$status', Days = '$today' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	if($status == '12'){  // If sent to approved 
		
		$query = mysql_query("SELECT tbl_sites.Name AS Name_1, tbl_jc.InvoiceNo, tbl_companies.Name, tbl_jc.JobId FROM ((tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) WHERE tbl_jc.JobId = '$jobid'")or die(mysql_error());
		$row = mysql_fetch_array($query);
		
		$invoice = $row['InvoiceNo'];
		$company = $row['Name'];
		$site = $row['Name_1'];
		$pdf = 'Seavest Invoice '.$invoice.'.pdf';
		$sent = date('d M Y H:i:s');
		
		mysql_query("INSERT INTO tbl_sent_invoices (InvoiceNo,CompanyId,SiteId,JobId,PDF,DateSent) VALUES ('$invoice','$company','$site','$jobid','$pdf','$sent')")or die(mysql_error());
		
		header('Location: fpdf16/approved-pdf.php?Id='. $jobid.'&Search');
		
	}
}

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

// Get search parameters

if(!empty($_POST['invoiceno'])){
	
	$invoiceno = $_POST['invoiceno'];
	
	$where = "WHERE InvoiceNo = ". $invoiceno ." AND tbl_jc.CompanyId != '0'";

} elseif(!empty($_POST['jobno'])){
	
	$jobno = $_POST['jobno'];
	
	$where = "WHERE  JobNo = '". $jobno ."' AND tbl_jc.CompanyId != '0'";
	
} else {
	
	$where = "WHERE JobNo = 1=1";
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_status.Id AS Id_1, tbl_sites.Address, tbl_companies.Name, tbl_jc.Id, tbl_jc.JobNo, tbl_jc.InvoiceDate, tbl_jc.InvoiceNo, tbl_jc.JobId, tbl_status.Status FROM (((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_status ON tbl_status.Id=tbl_jc.Status) $where GROUP BY tbl_jc.InvoiceNo ORDER BY tbl_jc.InvoiceNo DESC LIMIT 1";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT * FROM tbl_status WHERE Id >= 7 AND Id <= 12";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

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
  $NAV_SETTINGS = {
     'show_as_buttons': false
  }
</script>
<script type="text/javascript">
<!--

$NAV_SETTINGS = {
     'show_as_buttons': false
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

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
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
              <td colspan="4">
              <div  style="margin-left:30px">
                <form name="form2" method="post" action="search_inv.php">
    <p>&nbsp;</p>
    <table border="0" cellpadding="2" cellspacing="3">
                      <tr>
                        <td class="btn-blue-generic"> Job No.: </td>
                        <td nowrap><input name="jobno" type="text" class="tarea-white" id="jobno" style="cursor:text"></td>
                        <td width="80" align="right" nowrap class="btn-blue-generic">Invoice No. </td>
                        <td nowrap><input name="invoiceno" type="text" class="tarea-white" id="invoiceno"></td>
                        <td align="center" nowrap><input name="Submit2" type="submit" class="btn-go-search-2" id="Submit" value=""></td>
                      </tr>
                    </table>
                </form>
                <br>
              <br>
              <form name="form3" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0">
              <tr><td>
              <div id="list-brdr">
              <table border="0" cellpadding="0" cellspacing="1">
                <tr class="td-header">
                  <td width="50" align="left" nowrap><strong>&nbsp;Invoice </strong></td>
                  <td width="150" align="left"><strong>&nbsp;Company</strong></td>
                  <td width="300" align="left"><strong>&nbsp;Site Address </strong></td>
                  <td width="80" align="left"><strong>&nbsp;Date</strong></td>
                  <td align="left">&nbsp;Status</td>
                </tr>
                <?php do { ?>
                <tr class="even">
                  <td align="left" nowrap><a href="invoice_calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"> &nbsp;<?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                  <td align="left"><a href="invoice_calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['Name']; ?></a></td>
                  <td align="left"><a href="invoice_calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['Name_1']; ?></a></td>
                  <td align="left" nowrap><a href="invoice_calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['InvoiceDate']; ?></a></td>
                  <td align="left" nowrap> &nbsp;<select name="status" class="combo" id="status">
                    <option value="">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset5['Id']?>"<?php if (!(strcmp($row_Recordset5['Id'], $row_Recordset3['Id_1']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset5['Status']?></option>
                    <?php
} while ($row_Recordset5 = mysql_fetch_assoc($Recordset5));
  $rows = mysql_num_rows($Recordset5);
  if($rows > 0) {
      mysql_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysql_fetch_assoc($Recordset5);
  }
?>
                  </select>
                    <input name="Submit3" type="submit" class="btn-go-small" value="">
                    <input name="jobid" value="<?php echo $row_Recordset3['JobId']; ?>" type="hidden" id="jobid"></td>
                </tr>
                <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
				</table>
              </div>
              </td></tr>
              </table>              
              </form>
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
                </div>
                </div>
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
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset5);

mysql_free_result($Recordset4);
?>
