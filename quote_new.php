<?php require_once('Connections/seavest.php'); ?>
<?php 
session_start();

require_once('Connections/seavest.php');

require_once('functions/functions.php');

require_once('includes/common/KT_common.php');

require_once('includes/tng/tNG.inc.php');

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

session_start();
$company = $_SESSION['company'];
$site = $_SESSION['site'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT * FROM tbl_sites WHERE Id = '$site'";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_qs.CompanyId, tbl_qs.QuoteNo, tbl_companies.* FROM (tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo='%s' ", $KTColParam1_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT * FROM tbl_companies WHERE Id = '$company'";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$query = "SELECT * FROM tbl_qs ORDER BY Id DESC LIMIT 1" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

$quoteno = $row['QuoteNo'] + 1;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#6699CC"><p>&nbsp;</p>
        <p>&nbsp;</p>
      <p>&nbsp;</p>
      <?php
include('menu.php'); ?>
      <p>&nbsp;</p>      <p>&nbsp;</p></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
          <tr>
            <td colspan="3" align="center" bordercolor="#9E9E9E" bgcolor="#E0E0E0"><span class="HEADER">SEAVEST AFRICA TRADING CC</span></td>
              </tr>
          <tr>
            <td bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;P.O.Box 201153 Durban North. 4016</td>
                <td bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Tel/Fax: 031-5637735</td>
                <td bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;nicky@seavest.co.za</td>
              </tr>
        </table>
            <div id="add_row">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo_bold">
                <tr>
                  <td><form name="form2" method="post" action="quote_new.php">
                      &nbsp;&nbsp;&nbsp;Labour
                      <select name="labour_row" class="tarea2" id="labour_row">
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                      &nbsp; Materials
                      <select name="material_row" class="tarea2" id="material_row">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                      &nbsp; Transport
                      <select name="transport_row" class="tarea2" id="transport_row">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                      <input name="Submit2" type="submit" class="tarea2" value="Add Rows">
                      <br>
                  </form></td>
                  <td width="101" align="right">&nbsp;</td>
                </tr>
              </table>
              </div>          <form name="form1" method="post" action="quote_process.php">
                  <table>
                    <tr>
                      <td colspan="3" bordercolor="#FFFFFF" class="combo"><div style="border:solid 1px #A6CAF0; margin-bottom:6px">
                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                            <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                              <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
                              <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
                            </tr>
                            <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                              <td valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="180" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                                  <tr>
                                    <td width="180" align="center" bordercolor="#68A4E6" bgcolor="#a6caf0" class="sub_header">QUOTATION TO: </td>
                                  </tr>
                                  <tr>
                                    <td align="center" bordercolor="#FFFFFF" class="sub_header">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td bordercolor="#68A4E6" bgcolor="#a6caf0"><div class="combo" style="padding:3px"><?php echo $row_Recordset4['Name']; ?><br>
                                    <?php echo nl2br(KT_escapeAttribute($row_Recordset4['Address'])); ?></div></td>
                                  </tr>
                                                                          </table></td>
                              <td valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="300" border="1" align="right" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                                  <tr>
                                    <td width="120" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Date:</td>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo">&nbsp;<?php echo date('d M Y'); ?></td>
                                  </tr>
                                  <tr>
                                    <td width="120" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Quotation Number: </td>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo">&nbsp;<?php echo $quoteno; ?></td>
                                  </tr>
                                  <tr>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Reference:</td>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo"><input name="fmc" type="text" class="tarea3" id="fmc" style="cursor:text" value="<?php fmc($quoteno); ?>"></td>
                                  </tr>
                                  <tr>
                                    <td width="120" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Site / Customer:</td>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo">&nbsp;<?php echo $row_Recordset5['Name']; ?></td>
                                  </tr>
                                  <tr>
                                    <td width="120" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Address:</td>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo">&nbsp;<?php echo $row_Recordset5['Address']; ?></td>
                                  </tr>
                                  <tr>
                                    <td width="120" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Description:</td>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo">&nbsp;<?php echo $_SESSION['description']; ?> </td>
                                  </tr>
                                  <tr>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Att:</td>
                                    <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo">&nbsp;
                                    <input name="att" type="text" class="tarea3" id="att" style="cursor:text"></td>
                                  </tr>
                                                                          </table></td>
                            </tr>
                            <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                              <td valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
                              <td valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
                            </tr>
                                                              </table>
                                  </div>                          <table width="750" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                                    <tr>
                                      <td width="450" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Description</td>
                                      <td width="50" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Unit</td>
                                      <td width="50" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Qty.</td>
                                      <td width="100" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Unit Price </td>
                                      <td width="50" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Total</td>
                                    </tr>
                                                      </table></td>
                    </tr>
                    <tr>
                      <td colspan="3"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                          <tr>
                            <td bordercolor="#A6CAF0"><br>
                              &nbsp;&nbsp; <span class="combo_bold">Labour</span><br />
                              <?php
if(isset($_POST['labour'])){
$id = $_POST['labour'];
}
?>
                              <?php
if(isset($_POST['labour_row'])){
$rows_l = $_POST['labour_row'];
$_SESSION['rows_l'] = $rows_l;
} else {
$rows_l = 1;
$_SESSION['rows_l'] = $rows_l;
}
for($i=1; $i<=$rows_l; $i=$i+1)
{ 
?>
                              &nbsp;&nbsp;&nbsp;
                              <input name="labour[]" type="text" class="tarea" id="labour" size="85">
                              <input name="unit_l[]" type="text" class="tarea" id="unit_l[]" value="hours" size="7">
                              <input name="qty_l[]" type="text" class="tarea" id="qty_l[]" size="7">
                              <input name="price_l[]" type="text" class="tarea" id="price_l[]" size="16">
                              <input name="total_l" type="text" disabled="disabled" class="tarea" id="total_l" size="7">
                              <br>
                              <?php
	}

?>
                              <br>
                              <?php
if(isset($_POST['material'])){
$id = $_POST['labour'];
} ?>
                              &nbsp;&nbsp;&nbsp;<span class="combo_bold">Materials</span><br>
                              <?php
if(isset($_POST['material_row'])){
$rows_m = $_POST['material_row'];
$_SESSION['rows_m'] = $rows_m;
} else {
$rows_m = 1;
$_SESSION['rows_m'] = $rows_m;
}
	for($i=1; $i<=$rows_m; $i=$i+1)
	{ ?>
                              &nbsp;&nbsp;&nbsp;
                              <input name="material[]" type="text" class="tarea" id="labour" size="85">
                              <input name="unit_m[]" type="text" class="tarea" id="unit_m[]" value="lot" size="7">
                              <input name="qty_m[]" type="text" class="tarea" id="qty_m[]" size="7">
                              <input name="price_m[]" type="text" class="tarea" id="price_m[]" size="16">
                              <input name="textfield5" type="text" class="tarea" size="7" disabled="disabled">
                              <br>
                              <?php
}
?>
                              <br>
                              <?php
if(isset($_POST['transport'])){
$id = $_POST['labour'];
} ?>
                              &nbsp;&nbsp;&nbsp;<span class="combo_bold">Transport</span><br>
                              <?php
if(isset($_POST['transport_row'])){
$rows_t = $_POST['transport_row'];
$_SESSION['rows_t'] = $rows_t;
} else {
$rows_t = 1;
}
	for($i=1; $i<=$rows_t; $i=$i+1)
	{ ?>
                              &nbsp;&nbsp;&nbsp;
                              <input name="transport_description[]" type="text" class="tarea" id="transport_description[]" size="77">
                              <input name="transport[]" type="text" class="tarea" id="transport[]" size="3">
                              <input name="unit_t[]" type="text" class="tarea" id="unit_t[]" value="km" size="7">
                              <input name="qty_t[]" type="text" class="tarea" id="qty_t[]" size="7">
                              <input name="price_t[]" type="text" class="tarea" id="price_t[]" size="16">
                              <input name="textfield5" type="text" class="tarea" size="7" disabled="disabled">
                              <br>
                              <?php
	}

?>
                              <br>
                              <br>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input name="Submit" type="submit" class="tarea2" value="Calculate Quote">
                              <br /></td>
                          </tr>
                      </table></td>
                    </tr>
                                      </table>
          </form></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset5);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

?>
