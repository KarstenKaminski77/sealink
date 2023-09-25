<?php require_once('Connections/seavest.php'); ?>
<?php
require_once('Connections/seavest.php');

require_once('functions/functions.php');

$id = $_GET['Id'];
$where = "WHERE Status = '12' AND CompanyId = ". $id ."";

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name1 ,tbl_jc.InvoiceNo, tbl_companies.Name, tbl_jc.InvoiceDate, tbl_jc.JobId, tbl_jc.JobDescription, tbl_jc.Total2 ,tbl_jc.VatIncl ,sum(tbl_jc.Total1) AS sum_Total1_1 FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) $where GROUP BY tbl_jc.JobId ORDER BY tbl_jc.InvoiceDate ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3); echo $totalRows_Recordset3;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
</head>

<body>
<table width="525" border="0" cellpadding="0" cellspacing="1">
  <tr>
    <td colspan="4" align="left" nowrap="nowrap" class="tb_border" style="background-color:#FFFFFF; line-height:40px; border:none; font-weight:normal">
	<span class="sub_header"><?php echo $row_Recordset3['Name']; ?></span>
	<br />
	Generated: <?php echo date('d M Y'); ?>
	</td>
  </tr>
  <tr>
    <td width="100" align="left" nowrap="nowrap" class="tb_border"><strong>Invoice </strong></td>
    <td width="250" align="left" class="tb_border"><strong>Site Address </strong></td>
    <td width="100" align="left" class="tb_border"><strong>Date</strong></td>
    <td width="75" align="right" class="tb_border">Total</td>
  </tr>
  <?php do { ?>
  <tr>
      <td style="padding:2px" align="left" nowrap="nowrap" class="combo2"><?php echo $row_Recordset3['InvoiceNo']; ?></td>
      <td style="padding:2px" align="left" class="combo2"><?php echo $row_Recordset3['Name1']; ?></td>
      <td style="padding:2px" align="left" class="combo2"><?php echo $row_Recordset3['InvoiceDate']; ?></td>
      <td style="padding:2px" align="right" class="combo2">R<?php echo $row_Recordset3['Total2']; ?></td>
    </tr>
	  <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
  <tr>
    <td colspan="4" align="right" class="combo2 style1">
	<div style="border-top:solid 1px #A6CAF0;"></div>
	<b><?php sum_outstanding($where); ?></b></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset3);
?>
