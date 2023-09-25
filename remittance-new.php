<?php require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

if(isset($_POST['submit'])){
	
	$_SESSION['total'] = '';
	
	for($i=0;$i<count($_POST['file']);$i++){
		
		$file = $_POST['file'][$i];
								
		$query2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE InvoiceNo = '$file' AND Total2 >= '1' LIMIT 1")or die(mysqli_error($con));
		$row2 = mysqli_fetch_array($query2);
			
		$total = $row2['Total2'];
			
		$_SESSION['total'] = $_SESSION['total'] + $total;
			
	}
	
	$total = number_format($_SESSION['total'],2);
	$remittance = number_format($_POST['amount'] + $_POST['discount'],2);
	
	if($total == $remittance){
				
		$amount = $_POST['amount'];
		$discount = $_POST['discount'];
		$to = $_POST['email'];
		$message = $_POST['message'];
		
		$companyid = $_POST['company'];
		$date = $_POST['date'];
		$userid = $_SESSION['kt_login_id'];
		$datesubmitted = date('Y-m-d');
		
		mysqli_query($con, "INSERT INTO tbl_remittance (UserId,CompanyId,Date,DateSubmitted,Amount,Discount,Email,Message) VALUES ('$userid','$companyid','$date','$datesubmitted','$amount','$discount','$to','$message')")or die(mysqli_error($con));
		$query = mysqli_query($con, "SELECT * FROM tbl_remittance ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		
		$remittance_id = $row['Id'];
		
		for($i=0;$i<count($_POST['file']);$i++){
			
			$file = $_POST['file'][$i];
			
			$query_id = mysqli_query($con, "SELECT * FROM tbl_jc WHERE InvoiceNo = '$file'")or die(mysqli_error($con));
			$row_id = mysqli_fetch_array($query_id);
			
			$jobid = $row_id['JobId'];
			
			mysqli_query($con, "UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'")or die(mysqli_error($con));
			
			$query2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
			$row2 = mysqli_fetch_array($query2);
			
			$total = $row2['Total2'];
		    $siteid = $row2['SiteId'];
		    $jobno = $row2['JobNo'];
		    $inv_date = date('Y-m-d', strtotime($row2['InvoiceDate']));
			$invoiceno = $file;
			
			mysqli_query($con, "INSERT INTO tbl_remittance_details (RemittanceId,JobNo,InvoiceNo,InvoiceDate,JobId,SiteId,Amount) 
		    VALUES ('$remittance_id','$jobno','$invoiceno','$inv_date','$jobid','$siteid','$total')")or die(mysqli_error($con));
			
		}
		
		header('Location: pdf/pdf-remittance.php?Id='. $remittance_id);
	}
}

select_db();

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

$companyid = $_POST['company'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort, tbl_jc.CompanyId, tbl_sent_invoices.PDF, tbl_sent_invoices.DateSent, tbl_sent_invoices.Sent, tbl_jc.JobDescription, tbl_jc.Status, tbl_jc.JobId, tbl_jc.Total2, tbl_companies.Name, tbl_sent_invoices.InvoiceNo AS InvoiceNo_1 
FROM (((tbl_sent_invoices 
  LEFT JOIN tbl_jc ON tbl_jc.JobId=tbl_sent_invoices.JobId) 
  LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) 
  LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) 
WHERE tbl_jc.Status = '12' AND tbl_companies.Id = '$companyid' GROUP BY tbl_jc.JobId ORDER BY date_for_sort ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$colname_rs_totals = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_rs_totals = $_SESSION['kt_login_id'];
}
mysql_select_db($database_seavest, $seavest);
$query_rs_totals = sprintf("SELECT * FROM tbl_menu_relation WHERE UserId = %s AND MenuId = '47'", GetSQLValueString($colname_rs_totals, "int"));
$rs_totals = mysql_query($query_rs_totals, $seavest) or die(mysql_error());
$row_rs_totals = mysql_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysql_num_rows($rs_totals);

mysql_select_db($database_inv, $inv);
$query_Recordset4 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
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
<script LANGUAGE="JavaScript">
<!--
// Nannette Thacker http://www.shiningstar.net
function confirmSubmit() 
{
var agree=confirm("Are you sure you wish to continue?");
if (agree)
	return true ;
else
	return false ;
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
      </td>
    <td width="823" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><form action="" method="post" enctype="multipart/form-data" name="form2">
          <table width="725" border="0" align="center" cellpadding="3" cellspacing="1">
            <?php if(isset($_GET['Error'])){ ?>
            <tr>
              <td colspan="6" align="right" nowrap class="combo"><table border="0" align="center">
                <tr>
                  <td><div class="big2" id="remittance-failiure">Remittance report unsuccessfull</div></td>
                </tr>
              </table></td>
            </tr>
            <?php } ?>
            <tr>
              <td colspan="6" align="right" nowrap class="combo">
              <select name="company" class="tarea-100per" id="company">
              <option value="">Select one...</option>
                <?php do { ?>
                <option value="<?php echo $row_Recordset4['Id']; ?>" <?php if($_POST['company'] == $row_Recordset4['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_Recordset4['Name']; ?></option>
                <?php } while ($row_Recordset4 = mysql_fetch_assoc($Recordset4)); ?>
              </select></td>
            </tr>
            <tr>
              <td colspan="6" align="right" nowrap class="combo">
              <?php 
			  if(isset($_POST['remittance']) || isset($_POST['submit'])){
				  
				  $var = $_POST['date'];
				  
			  } else {
				  
				  $var = 'Date Received';
			  }
			  ?>
             <input name="date" class="tarea-100per" id="date" style="width:100%" onFocus="if (this.value=='Date Received') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Date Received';" value="<?php echo $var; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes"></td>
            </tr>
            <tr>
              <td colspan="6" align="right" nowrap class="combo">
                <?php 
			  if(isset($_POST['remittance']) || isset($_POST['submit'])){
				  
				  $var = $_POST['amount'];
				  
			  } else {
				  
				  $var = 'Remittance Amount';
			  }
			  ?>
                <input name="amount" type="text" class="tarea-100per" id="amount" value="<?php echo $var; ?>" onFocus="if (this.value=='Remittance Amount') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Remittance Amount';" style="width:100%"></td>
            </tr>
            <tr>
              <td colspan="6" align="right" nowrap class="combo"><?php 
			  if(isset($_POST['remittance']) || isset($_POST['submit'])){
				  
				  $var = $_POST['discount'];
				  
				  if($_POST['discount'] == 'Remittance Discount' || empty($_POST['discount'])){
					  
					  $var = '0.00';
				  }
				  
			  } else {
				  
				  $var = 'Remittance Discount';
			  }
			  ?>
                <input name="discount" type="text" class="tarea-100per" id="discount" value="<?php echo $var; ?>" onFocus="if (this.value=='Remittance Discount') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Remittance Discount';" style="width:100%"></td>
            </tr>
            <tr>
              <td colspan="6" align="right" nowrap class="combo"><input name="remittance" type="submit" class="btn-blue-generic" id="remittance" value="Select Invoices"></td>
            </tr>
            <tr>
              <td colspan="6" align="left" nowrap class="combo">
              </td>
            </tr>
            <?php if(isset($_POST['remittance']) || isset($_POST['submit'])){ ?>
            <tr class="td-header">
              <td width="50" align="center" nowrap><strong>In No. </strong></td>
              <td width="180" align="left"><strong>Company</strong></td>
              <td width="250" align="left"><strong>Site Address </strong></td>
              <td width="173" align="left"><strong>Date</strong></td>
              <td width="75" align="left">Total</td>
              <td width="25" align="center">&nbsp;</td>
              </tr>
			  <?php 
			  
			  $i = -1;
			  
			  do {
				  
				  $i++;
				  
				  $jobid = $row_Recordset3['JobId']; ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                          <td width="50" align="center" nowrap class="combo"><a href="#" class="menu"<?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo_1']; ?></a></td>
                  <td width="180" nowrap class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name']; ?></td>
  <td width="250" class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name_1']; ?> </td>
                          <td width="173" class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['date_for_sort']; ?></td>
                          <td width="75" nowrap class="combo"<?php debtors_overdue($jobid); ?>>R<?php echo $row_Recordset3['Total2']; ?></td>
                          <td align="center">
                          <?php
						  $checked = '';
						  
						  for($i=0;$i<count($_POST['file']);$i++){
							  
							  if($_POST['file'][$i] == $row_Recordset3['InvoiceNo_1']){
								  
								  $checked = 'checked="checked"';
							  }
						  }
						  ?>
                          <input name="file[]" type="checkbox" id="file[]" value="<?php echo $row_Recordset3['InvoiceNo_1']; ?>" <?php echo $checked; ?>>
                          </td>
                  </tr>
						<?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                    <tr>
                      <td colspan="6" align="right" class="td-header">&nbsp;</td>
                      </tr>
                    <tr>
                      <td colspan="6" align="right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="6" align="right"><input name="submit" type="submit" class="btn-blue-generic" id="submit" value="Submit"></td>
                    </tr>
			<?php } ?>
            </table>
        </form>
          <div class="KT_bottomnav" align="center">
            <div class="combo"></div>
          </div></td></tr>
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
