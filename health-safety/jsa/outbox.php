<?php require_once('../../Connections/seavest.php'); ?>
<?php
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

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');

require_once('../../functions/functions.php');

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
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_jsa.Id, tbl_jsa.Date, tbl_companies.Name, tbl_sites.Address, tbl_jsa.Reference, tbl_far_high_risk_classification.Risk, tbl_jsa.Status FROM (((tbl_jsa LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jsa.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jsa.CompanyId) LEFT JOIN tbl_far_high_risk_classification ON tbl_far_high_risk_classification.Id=tbl_jsa.JSAType) WHERE tbl_jsa.Status=1 ";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

if(isset($_GET['Id'])){

$jobid = $_GET['Id'];

mysql_query("UPDATE tbl_jc SET Status = '12' WHERE JobId = '$jobid'")or die(mysql_error());

$jobid = $_GET['Id'];

$query = mysql_query("SELECT tbl_sites.Name AS Name_1, tbl_jc.InvoiceNo, tbl_companies.Name, tbl_jc.JobId FROM ((tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) WHERE tbl_jc.JobId = '$jobid'")or die(mysql_error());
$row = mysql_fetch_array($query);

$invoice = $row['InvoiceNo'];
$company = $row['Name'];
$site = $row['Name_1'];
$pdf = 'Seavest Invoice '.$invoice.'.pdf';
$sent = date('d M Y H:i:s');

mysql_query("INSERT INTO tbl_sent_invoices (InvoiceNo,CompanyId,SiteId,JobId,PDF,DateSent) VALUES ('$invoice','$company','$site','$jobid','$pdf','$sent')")or die(mysql_error());

}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
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
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../../styles/layout.css" rel="stylesheet" type="text/css">
<script LANGUAGE="JavaScript">
<!--
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
//-->
</script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../../menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><form action="../../fpdf16/pdf/jsa-mail.php" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
          <table width="705" border="0" cellpadding="3" cellspacing="1">
            <tr>
  <td colspan="7" nowrap><input name="email" type="text" class="td-mail" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:705px"></td>
              </tr>
            <tr>
              <td colspan="7" nowrap><input name="attach" type="file" class="td-mail" id="attach"></td>
            </tr>
            <tr>
              <td colspan="7" nowrap><textarea name="message" rows="5" class="td-mail" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:705px">Message</textarea></td>
            </tr>
            <tr>
              <td colspan="7" align="right" nowrap><input name="Submit2" type="submit" class="td-mail" value="Send"></td>
            </tr>
            <tr>
              <td colspan="7" align="center" nowrap>&nbsp;</td>
              </tr>
            <tr class="td-header">
              <td width="79" align="center" nowrap><strong>JSA No. </strong></td>
              <td width="205" align="left"><strong>Company</strong></td>
              <td width="205" align="left"><strong>Site Address </strong></td>
              <td width="104" align="left"><strong>Date</strong></td>
              <td width="26" align="center">&nbsp;</td>
              <td width="26" align="center">&nbsp;</td>
              <td width="20" align="center">&nbsp;</td>
              </tr>
  <?php do { 

$jobid = $row_Recordset3['JobId'];

?><tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td width="79" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Id']; ?></a><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"></a></td>
              <td width="205" class="combo"><?php echo $row_Recordset3['Name']; ?></td>
              <td width="205" class="combo"><?php echo $row_Recordset3['Name_1']; ?></td>
              <td width="104" class="combo">&nbsp;<?php echo $row_Recordset3['Date']; ?></td>
              <td width="26" align="center"><a href="../../fpdf16/pdf-jsa.php?Id=<?php echo $row_Recordset3['Id']; ?>&Preview" target="_blank"><img title="View PDF" src="../../images/icons/btn-view.png" width="25" height="25" border="0"></a></td>
              <td width="26" align="center">
                <a onClick="return confirmSubmit()" href="jsa.php?Id=<?php echo $row_Recordset3['Id']; ?>">
                  <img title="Edit" src="../../images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
              <td align="center">
                <input name="file[]" type="checkbox" id="file[]" value="<?php echo 'Seavest JSA.pdf #'. $row_Recordset3['Id'].'.pdf'; ?>"></td>
              </tr>
            <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>			</table>
        </form>
        <br><br>
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