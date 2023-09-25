<?php require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
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

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

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

$keywords = trim(strtolower($_POST['search-field']));

mysql_select_db($database_inv, $inv);
$query_Recordset3 = "SELECT LOWER(tbl_jc.JobNo) AS Job, tbl_jc.InvoiceNo, tbl_jc.JobId, tbl_jc.Status AS StatusId, tbl_sites.Name AS Site, tbl_status.Status FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_status ON tbl_status.Id=tbl_jc.Status) WHERE tbl_jc.JobNo='$keywords' GROUP BY tbl_jc.JobId";
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
$var = '3';

if($totalRows_Recordset3 == 0){

mysql_select_db($database_inv, $inv);
$query_Recordset4 = "SELECT LOWER(tbl_jc.JobNo) AS Job, tbl_jc.InvoiceNo, tbl_jc.JobId, tbl_jc.Status AS StatusId, tbl_sites.Name AS Site, tbl_status.Status FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_status ON tbl_status.Id=tbl_jc.Status) WHERE tbl_sites.Name LIKE'%$keywords%' GROUP BY tbl_jc.JobId";
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);
$var = '4';

} if($totalRows_Recordset3 == 0 && $totalRows_Recordset4 == 0){

mysql_select_db($database_inv, $inv);
$query_Recordset5 = "SELECT LOWER(tbl_jc.JobNo) AS Job, tbl_jc.InvoiceNo, tbl_jc.JobId, tbl_jc.Status AS StatusId, tbl_sites.Name AS Site, tbl_status.Status FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_status ON tbl_status.Id=tbl_jc.Status) WHERE tbl_jc.InvoiceNo='$keywords' GROUP BY tbl_jc.JobId";
$Recordset5 = mysql_query($query_Recordset5, $inv) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);
$var = '5';

}
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
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td><div style="margin-left:30px; margin-top:30px">
          <table width="650" border="0" cellpadding="3" cellspacing="1">
            <tr class="td-header">
              <td width="100" align="left" nowrap><strong>Jobcard </strong></td>
              <td width="150" align="left"><strong>Invoice No</strong></td>
              <td width="250" align="left"><strong>Site Address </strong></td>
              <td width="150" align="left"><strong>Status</strong></td>
              <td width="40" align="left">&nbsp;</td>
              <td width="40" align="left">&nbsp;</td>
              </tr>
            <?php 
			if($totalRows_Recordset3 >= 1){
			do { 
$userid = $_SESSION['kt_login_id'];
$jobid = $row_Recordset3['JobId'];

$query = mysql_query("SELECT tbl_users.Id AS Id_1, tbl_menu_relation.*, tbl_jc.JobId, tbl_menu_items.Menu, tbl_users.Username, tbl_menu_items.Status, tbl_jc.InvoiceNo, tbl_jc.JobNo
FROM (((tbl_users
LEFT JOIN tbl_menu_relation ON tbl_menu_relation.UserId=tbl_users.Id)
LEFT JOIN tbl_menu_items ON tbl_menu_items.Id=tbl_menu_relation.MenuId)
LEFT JOIN tbl_jc ON tbl_jc.Status=tbl_menu_items.Status)
WHERE tbl_users.Id='$userid' AND JobId='$jobid'")or die(mysql_error());
$numrows = mysql_num_rows($query);

			?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                <td width="100" class="combo"><?php echo $row_Recordset3['Job']; ?></td>
                <td width="150" class="combo"><?php echo $row_Recordset3['InvoiceNo']; ?></td>
                <td width="250" class="combo"><?php echo $row_Recordset3['Site']; ?></td>
                <td width="150" class="combo"><?php echo $row_Recordset3['Status']; ?></td>
                <td width="40" align="center" class="combo">
                <?php if($numrows >= 1){ ?>
                <a href="jc_calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>"><img src="images/icons/btn-job-card.png" width="25" height="25"></a>
                <?php } ?>
                </td>
                <td width="40" align="center" class="combo">
                <?php if($row_Recordset3['StatusId'] >= 7 && $numrows >= 1){ ?>
                <a href="invoice_calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>"><img src="images/icons/btn-invoice.png" width="25" height="25"></a>
                <?php } ?>
                </td>
              </tr>
              <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
            <?php 
			}
			  ?>
            <?php 
			if($totalRows_Recordset4 >= 1){
			do { 
$userid = $_SESSION['kt_login_id'];
$jobid = $row_Recordset4['JobId'];

$query = mysql_query("SELECT tbl_users.Id AS Id_1, tbl_menu_relation.*, tbl_jc.JobId, tbl_menu_items.Menu, tbl_users.Username, tbl_menu_items.Status, tbl_jc.InvoiceNo, tbl_jc.JobNo
FROM (((tbl_users
LEFT JOIN tbl_menu_relation ON tbl_menu_relation.UserId=tbl_users.Id)
LEFT JOIN tbl_menu_items ON tbl_menu_items.Id=tbl_menu_relation.MenuId)
LEFT JOIN tbl_jc ON tbl_jc.Status=tbl_menu_items.Status)
WHERE tbl_users.Id='$userid' AND JobId='$jobid'")or die(mysql_error());
$numrows = mysql_num_rows($query);

			?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                <td width="100" class="combo"><?php echo $row_Recordset4['Job']; ?></td>
                <td width="150" class="combo"><?php echo $row_Recordset4['InvoiceNo']; ?></td>
                <td width="250" class="combo"><?php echo $row_Recordset4['Site']; ?></td>
                <td width="150" class="combo"><?php echo $row_Recordset4['Status']; ?></td>
                <td width="40" align="center" class="combo">
                <?php if($numrows >= 1){ ?>
                <a href="jc_calc.php?Id=<?php echo $row_Recordset4['JobId']; ?>"><img src="images/icons/btn-job-card.png" width="25" height="25"></a>
                <?php } ?>
                </td>
                <td width="40" align="center" class="combo">
                <?php if($row_Recordset4['StatusId'] >= 7 && $numrows >= 1){ ?>
                <a href="invoice_calc.php?Id=<?php echo $row_Recordset4['JobId']; ?>"><img src="images/icons/btn-invoice.png" width="25" height="25"></a>
                <?php } ?>
                </td>
              </tr>
              <?php } while ($row_Recordset4 = mysql_fetch_assoc($Recordset4)); ?>
            <?php 
			}
			  ?>
            <?php 
			if($totalRows_Recordset5 >= 1){
			do { 
$userid = $_SESSION['kt_login_id'];
$jobid = $row_Recordset5['JobId'];

$query = mysql_query("SELECT tbl_users.Id AS Id_1, tbl_menu_relation.*, tbl_jc.JobId, tbl_menu_items.Menu, tbl_users.Username, tbl_menu_items.Status, tbl_jc.InvoiceNo, tbl_jc.JobNo
FROM (((tbl_users
LEFT JOIN tbl_menu_relation ON tbl_menu_relation.UserId=tbl_users.Id)
LEFT JOIN tbl_menu_items ON tbl_menu_items.Id=tbl_menu_relation.MenuId)
LEFT JOIN tbl_jc ON tbl_jc.Status=tbl_menu_items.Status)
WHERE tbl_users.Id='$userid' AND JobId='$jobid'")or die(mysql_error());
$numrows = mysql_num_rows($query);

			?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                <td width="100" class="combo"><?php echo $row_Recordset5['Job']; ?></td>
                <td width="150" class="combo"><?php echo $row_Recordset5['InvoiceNo']; ?></td>
                <td width="250" class="combo"><?php echo $row_Recordset5['Site']; ?></td>
                <td width="150" class="combo"><?php echo $row_Recordset5['Status']; ?></td>
                <td width="40" align="center" class="combo">
                <?php if($numrows >= 1){ ?>
                <a href="jc_calc.php?Id=<?php echo $row_Recordset5['JobId']; ?>"><img src="images/icons/btn-job-card.png" width="25" height="25"></a>
                <?php } ?>
                </td>
                <td width="40" align="center" class="combo">
                <?php if($row_Recordset5['StatusId'] >= 7 && $numrows >= 1){ ?>
                <a href="invoice_calc.php?Id=<?php echo $row_Recordset5['JobId']; ?>"><img src="images/icons/btn-invoice.png" width="25" height="25"></a>
                <?php } ?>
                </td>
              </tr>
              <?php } while ($row_Recordset5 = mysql_fetch_assoc($Recordset5)); ?>
            <?php 
			}
			  ?>
          </table>
        </div></td>
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

if($totalRows_Recordset4 >= 1){
mysql_free_result($Recordset4);
}
if($totalRows_Recordset5 >= 1){
mysql_free_result($Recordset5);
}
?>
