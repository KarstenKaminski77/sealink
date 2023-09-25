<?php require_once('Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

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

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort, tbl_sent_invoices.PDF, tbl_sent_invoices.DateSent, tbl_sent_invoices.Sent, tbl_jc.JobDescription, tbl_jc.Status, tbl_jc.JobId, tbl_jc.Total2, tbl_companies.Name, tbl_sent_invoices.InvoiceNo AS InvoiceNo_1 FROM (((tbl_sent_invoices LEFT JOIN tbl_jc ON tbl_jc.JobId=tbl_sent_invoices.JobId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.Status=16 GROUP BY tbl_jc.JobId ORDER BY date_for_sort ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

if(isset($_GET['Id'])){

$jobid = $_GET['Id'];

mysql_query("UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'")or die(mysql_error());
}

$colname_rs_totals = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_rs_totals = $_SESSION['kt_login_id'];
}
mysql_select_db($database_seavest, $seavest);
$query_rs_totals = sprintf("SELECT * FROM tbl_menu_relation WHERE UserId = %s AND MenuId = '47'", GetSQLValueString($colname_rs_totals, "int"));
$rs_totals = mysql_query($query_rs_totals, $seavest) or die(mysql_error());
$row_rs_totals = mysql_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysql_num_rows($rs_totals);

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
// -->
</script>
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
        <td colspan="4"><form action="fpdf16/pdf/jc_resend_mail.php" method="post" enctype="multipart/form-data" name="form2">
          <table width="725" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr>
              <td colspan="9" align="left" nowrap class="combo"><input name="email" type="text" class="td-mail" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:720px"></td>
            </tr>
            
            <tr>
              <td colspan="9" align="left" nowrap class="combo"><input name="attach" type="file" class="td-mail" id="attach"></td>
            </tr>
            <tr>
              <td colspan="9" align="left" nowrap class="combo"><textarea name="message" rows="5" class="td-mail" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:720px">Message</textarea></td>
            </tr>
            <tr>
              <td colspan="9" align="right" nowrap class="combo"><input name="Submit2" type="submit" class="td-mail" value="Send"></td>
            </tr>
            <tr>
              <td colspan="9" align="left" nowrap class="combo">&nbsp;</td>
              </tr>
            <tr class="td-header">
              <td width="50" align="center" nowrap><strong>In No. </strong></td>
              <td width="180" align="left"><strong>Company</strong></td>
              <td width="250" align="left"><strong>Site Address </strong></td>
              <td width="173" align="left"><strong>Date</strong></td>
              <td width="75" align="right">Total</td>
              <td width="40" align="center">&nbsp;</td>
              <td width="40" align="center">&nbsp;</td>
              <td width="40" align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              </tr>
			  <?php do { 
				                  $jobid = $row_Recordset3['JobId'];
				                  ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                          <td width="50" align="center" nowrap class="combo"><a href="#" class="menu"<?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo_1']; ?></a></td>
                  <td width="180" nowrap class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name']; ?></td>
  <td width="250" class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name_1']; ?> </td>
                          <td width="173" class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['date_for_sort']; ?></td>
                          <td width="75" align="right" nowrap class="combo"<?php debtors_overdue($jobid); ?>>R<?php echo $row_Recordset3['Total2']; ?></td>
                          <td width="40" align="center">
                          <a href="fpdf16/pdf/<?php echo $row_Recordset3['PDF']; ?>" target="_blank">
                          <img title="View PDF" src="images/icons/btn-view.png" width="25" height="25" border="0"></a></td>
                          <td width="40" align="center">
						  <a onClick="return confirmSubmit()" href="revive.php?Id=<?php echo $row_Recordset3['JobId']; ?>">
						  <img title="Edit" src="images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
                          <td width="40" align="center">
                          <a href="jc_history.php?Id=<?php echo $row_Recordset3['JobId']; ?>"><img title="Mail History" src="images/icons/btn-information.png" width="25" height="25" border="0"></a></td>
                          <td align="center">
                            <?php
			  
			  // Check if Pragma and send XL format
			  
			  $companyid = $row_Recordset3['CompanyId'];
			  
			  if($companyid == 2){
				  
				  $value = $row_Recordset3['JobId'];
				  
			  } else {
				  
				  $value = $row_Recordset3['PDF'];
			  }
			  			  
			  ?>
                          <input name="file[]" type="checkbox" id="file[]" value="<?php echo $value; ?>"></td>
                  </tr>
						<?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                    <tr>
                      <td colspan="5" align="right" class="td-header">
					  <?php 
					  if($totalRows_rs_totals >= 1){
					  $where = "WHERE Status = '12'";
					  sum_outstanding($where); 
					  }
					  ?>
                      </td>
                      <td align="right" class="td-header">&nbsp;</td>
                      <td align="right" class="td-header">&nbsp;</td>
                      <td align="right" class="td-header">&nbsp;</td>
                      <td align="right" class="td-header">&nbsp;</td>
                      </tr>
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
?>
