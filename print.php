<?php require_once('Connections/seavest.php'); ?>
<?php
require_once('functions/functions.php');

require_once('includes/common/KT_common.php');

require_once('includes/tng/tNG.inc.php');

select_db();

$quoteno = $_GET['Id'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$labour_total = $row['SUM(Total1)'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$material_total = $row['SUM(Total1)'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$transport_total = $row['SUM(Total1)'];

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '%s'", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$colname_Recordset1 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT * FROM tbl_qs WHERE QuoteNo = %s", $colname_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$quoteno = $_GET['Id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Name, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_qs.Id, tbl_qs.QuoteNo, tbl_qs.Date, tbl_qs.JobDescription FROM ((tbl_qs LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_qs.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo = '$quoteno' ORDER BY Id ASC LIMIT 1";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$KTColParam1_Recordset2 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset2 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT tbl_qs.CompanyId, tbl_qs.QuoteNo, tbl_qs.Attention, tbl_companies.* FROM (tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo=%s ", $KTColParam1_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_Recordset10 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset10 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset10 = sprintf("SELECT SUM(Total1), SUM(VAT), Total FROM tbl_qs WHERE QuoteNo = '%s' GROUP BY QuoteNo", $colname_Recordset10);
$Recordset10 = mysql_query($query_Recordset10, $seavest) or die(mysql_error());
$row_Recordset10 = mysql_fetch_assoc($Recordset10);
$totalRows_Recordset10 = mysql_num_rows($Recordset10);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="window.print(); window.close();">
<table width="650" border="0" cellpadding="0" cellspacing="5">
  <tr>
    <td><?php
  $mail = $_POST['mail'];
  
?>
      <table width="761" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
          <tr>
            <td colspan="4" align="center" bordercolor="#9E9E9E" bgcolor="#E0E0E0"><span class="HEADER">SEAVEST AFRICA TRADING CC</span></td>
        </tr>
          <tr>
            <td width="263" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;P.O.Box 201153 Durban North. 4016</td>
          <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Tel: 031 563 7735</td>
          <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Fax: 0865 191 153</td>
          <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;nicky@seavest.co.za</td>
        </tr>
	  </table>
      <div style="border:solid 1px #A6CAF0; margin-bottom:0px; width:759px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
          </tr>
          <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <td valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="180" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr class="sub_header">
                  <td width="180" align="center" class="tb_border">QUOTATION TO: </td>
                </tr>
                <tr>
                  <td align="center" bordercolor="#FFFFFF" class="sub_header">&nbsp;</td>
                </tr>
                <tr>
                  <td class="tb_border"><div style="padding:3px"><span class="combo_bold"><?php echo $row_Recordset2['Name']; ?></span><br>
                  <?php echo nl2br(KT_escapeAttribute($row_Recordset2['Address'])); ?></div></td>
                </tr>
            </table></td>
            <td valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="300" border="0" align="right" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <tr>
                <td width="120" nowrap class="tb_border"><span class="combo_bold">Date:</span></td>
                <td class="tb_border">&nbsp;<?php echo $row_Recordset5['Date']; ?></td>
              </tr>
              <tr>
                <td width="120" nowrap class="tb_border"><span class="combo_bold">Quotation Number: </span></td>
                <td class="tb_border">&nbsp;<?php echo $row_Recordset5['QuoteNo']; ?></td>
              </tr>
              <tr>
                <td nowrap class="tb_border"><span class="combo_bold">Reference:</span></td>
                <td class="tb_border"><input name="fmc" type="text" class="tarea3" id="fmc" style="cursor:text" value="<?php fmc($quoteno); ?>"></td>
              </tr>
              <tr>
                <td width="120" nowrap class="tb_border"><span class="combo_bold">Site / Customer:</span></td>
                <td class="tb_border">&nbsp;<?php echo $row_Recordset5['Name_1']; ?></td>
              </tr>
              <tr>
                <td nowrap class="tb_border"><span class="combo_bold">Address:</span></td>
                <td class="tb_border">&nbsp;<?php echo $row_Recordset5['Address']; ?></td>
              </tr>
              <tr>
                <td width="120" nowrap class="tb_border"><span class="combo_bold">Description:</span></td>
                <td class="tb_border">&nbsp;<?php echo $row_Recordset5['JobDescription']; ?></td>
              </tr>
              <tr>
                <td width="120" nowrap class="tb_border"><span class="combo_bold">Att:</span></td>
                <td class="tb_border">&nbsp;<?php echo $row_Recordset2['Attention']; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <td colspan="2" valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="combo_bold">We thank you for your valid enquiry and hereby submit the following:<br>
                <br></td>
          </tr>
        </table>
      </div>
      <table width="762" border="0" cellpadding="2" cellspacing="1" bordercolor="#FFFFFF" class="combo">
        <tr>
          <td width="450" class="tb_border">Description</td>
          <td width="50" align="center" class="tb_border">Unit</td>
          <td width="50" align="center" class="tb_border">Qty.</td>
          <td width="100" align="center" class="tb_border">Unit Price </td>
          <td width="100" align="center" class="tb_border">Total</td>
        </tr>
      </table>
      <div style=" border:solid 1px #A6CAF0; width:759px">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td align="left" bordercolor="#A6CAF0"><br>
                <span class="combo_bold">&nbsp;Labour</span><br>
                <br />
                <form name="form1" method="post" action="quote_calc.php?Id=<?php echo $quoteno; ?>&update">
                  <?php
$quoteno = $_GET['Id'];

$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){

?>
                  <div>
                    <table width="750" border="0" cellpadding="0" cellspacing="0" class="combo">
                      <tr>
                        <td width="450"><div style="padding-left:5px; padding-right:5px; padding-top:5px; text-align:justify; width:430px"><?php echo $row['Description']; ?></div></td>
                        <td width="50" align="center" valign="bottom"><?php echo $row['Unit']; ?></td>
                        <td width="50" align="center" valign="bottom"><?php echo $row['Qty']; ?></td>
                        <td width="100" align="center" valign="bottom">R<?php echo $row['Price']; ?></td>
                        <td width="100" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
                      </tr>
                    </table>
                    <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>">
                  </div>
                  <?php } // close loop ?>
                  <div align="right" class="combo_bold" style="border-top:solid 1px #A6CAF0; margin-right:16px; margin-left:5px; ">R<?php echo $labour_total; ?></div>
                  <br>
                  <br>
                  &nbsp;<span class="combo_bold">Material</span>
                  <br>
                  <br>
                  <?php
$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                  <div>
                    <table width="750" border="0" cellpadding="0" cellspacing="0" class="combo">
                      <tr>
                        <td width="450"><div style="padding-left:5px; padding-right:5px; padding-top:5px; text-align:justify; width:430px"><?php echo $row['Description']; ?></div></td>
                        <td width="50" align="center" valign="bottom"><?php echo $row['Unit']; ?></td>
                        <td width="50" align="center" valign="bottom"><?php echo $row['Qty']; ?></td>
                        <td width="100" align="center" valign="bottom">R<?php echo $row['Price']; ?></td>
                        <td width="100" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
                      </tr>
                    </table>
                    <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>">
                  </div>
                  <?php } // close loop ?>
                  <div align="right" class="combo_bold" style="border-top:solid 1px #A6CAF0; margin-right:16px; margin-left:5px; ">R<?php echo $material_total; ?></div>
                  <br>
                  <br>
                  &nbsp;<span class="combo_bold">Transport</span><br>
                  <br>
                  <?php
$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                  <div>
                    <table width="750" border="0" cellpadding="0" cellspacing="0" class="combo">
                      <tr>
                        <td width="224">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?>
                    </td>
                        <td width="224" align="right"><div style="padding-right:5px; text-align:right; width:224px"><?php echo $row['Description']; ?> round trips </div></td>
                        <td width="50" align="center" valign="bottom"><?php echo $row['Unit']; ?></td>
                        <td width="50" align="center" valign="bottom"><?php echo $row['Qty']; ?></td>
                        <td width="100" align="center" valign="bottom">R<?php echo $row['Price']; ?></td>
                        <td width="100" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
                      </tr>
                    </table>
                  </div>
                  <?php } // close loop ?>
                  <div align="right" class="combo_bold" style="border-top:solid 1px #A6CAF0; margin-right:16px; margin-left:5px; ">R<?php echo $transport_total; ?></div>
                  <br>
                  <div align="right" class="combo_bold"><br>
              <div style="padding-right:12px">
                <table border="0" cellpadding="0" cellspacing="5" class="combo_bold">
                  <tr>
                    <td>Sub Total:&nbsp; </td>
                    <td align="right">R <?php echo $row_Recordset4['SUM(Total1)']; ?>
                        <input name="subtotal" type="hidden" id="subtotal" value="<?php echo $row_Recordset10['SUM{Total1)']; ?>"></td>
                  </tr>
                  <tr>
                    <td>VAT:</td>
                    <td align="right">R <?php echo $row_Recordset10['SUM(VAT)']; ?></td>
                  </tr>
                  <tr>
                    <td>Total:</td>
                    <td align="right">R<?php echo $row_Recordset10['Total']; ?></td>
                  </tr>
                </table>
              </div>
                  </div>
                  <div style="padding-bottom:5px"></div>
                </form></td>
          </tr>
        </table>
    </div></td>
  </tr>
</table>
</body>
</html>
<?php

mysql_free_result($Recordset4);

mysql_free_result($Recordset1);

mysql_free_result($Recordset5);

mysql_free_result($Recordset2);

mysql_free_result($Recordset10);
?>
