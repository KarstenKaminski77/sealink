<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');
?>
<?php
require_once('functions/functions.php');

require_once('includes/common/KT_common.php');

require_once('includes/tng/tNG.inc.php');

select_db();

$jobid = $_GET['Id'];
$invdate = date('d M Y');
$searchdate = date('Y m d');
mysql_query("UPDATE tbl_jc SET InvoiceSent = '1', Archived = '1', SearchDate = '$searchdate' WHERE JobId = '$jobid'") or die(mysql_error());

$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_l = $row['SUM(Total1)'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_m = $row['SUM(Total1)'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Transport = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_t = $row['SUM(Total1)'];

$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

mysql_query("UPDATE tbl_jc SET SubTotal = '$subtotal' WHERE JobId = '$jobid'") or die(mysql_error());

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$jobid = $_GET['Id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Address AS Address_1, tbl_companies.ContactName, tbl_companies.ContactNumber, tbl_companies.ContactEmail, tbl_companies.Name, tbl_companies.VATNO, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Telephone, tbl_sites.Email, tbl_jc.Id, tbl_jc.JobId, tbl_jc.InvoiceNo, tbl_jc.JobNo, tbl_jc.Date, tbl_jc.JobDescription, tbl_jc.Reference FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId = '$jobid' ORDER BY Id ASC LIMIT 1";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$KTColParam1_Recordset3 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset3 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_jc.CompanyId, tbl_jc.JobId, tbl_jc.JobNo, tbl_companies.* FROM (tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId=%s ORDER BY Id ASC LIMIT 1", $KTColParam1_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$KTColParam1_Recordset4 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset4 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT tbl_jc.CompanyId, tbl_jc.JobId, tbl_jc.JobNo, tbl_companies.* FROM (tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId=%s ORDER BY Id DESC LIMIT 1", $KTColParam1_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$colname_Recordset6 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset6 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset6 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset6);
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

mysql_select_db($database_seavest, $seavest);
$query_Recordset7 = "SELECT * FROM tbl_rates";
$Recordset7 = mysql_query($query_Recordset7, $seavest) or die(mysql_error());
$row_Recordset7 = mysql_fetch_assoc($Recordset7);
$totalRows_Recordset7 = mysql_num_rows($Recordset7);

$jobid = $_GET['Id'];
$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);
$companyid = $row['CompanyId'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset8 = "SELECT * FROM tbl_fuel WHERE Company = '$companyid'";
$Recordset8 = mysql_query($query_Recordset8, $seavest) or die(mysql_error());
$row_Recordset8 = mysql_fetch_assoc($Recordset8);
$totalRows_Recordset8 = mysql_num_rows($Recordset8);

$colname_Recordset9 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset9 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset9 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset9);
$Recordset9 = mysql_query($query_Recordset9, $seavest) or die(mysql_error());
$row_Recordset9 = mysql_fetch_assoc($Recordset9);
$totalRows_Recordset9 = mysql_num_rows($Recordset9);

$colname_refno = "-1";
if (isset($_GET['Id'])) {
  $colname_refno = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_refno = sprintf("SELECT JobId, RefNo FROM tbl_jc WHERE JobId = %s", $colname_refno);
$refno = mysql_query($query_refno, $seavest) or die(mysql_error());
$row_refno = mysql_fetch_assoc($refno);
$totalRows_refno = mysql_num_rows($refno);

ob_start();
?>
<?php
$colname_date = "-1";
if (isset($_GET['Id'])) {
  $colname_date = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_date = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_date);
$date = mysql_query($query_date, $seavest) or die(mysql_error());
$row_date = mysql_fetch_assoc($date);
$totalRows_date = mysql_num_rows($date);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
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
</head>
<body onLoad="window.print(); window.close();">
<?php
  $mail = $_POST['mail'];
  $subject = 'SEAVEST AFRICA TRADING INVOICE#'. $row_Recordset5['InvoiceNo'];
  $sectemailObj = new tNG_EmailPageSection();
  $sectemailObj->getCSSFrom(__FILE__);
  $sectemailObj->setTo($mail);
  $sectemailObj->setFrom("nicky@seavest.co.za");
  $sectemailObj->setSubject($subject);
  $sectemailObj->setFormat("HTML/Text");
  $sectemailObj->setEncoding("ISO-8859-1");
  $sectemailObj->setImportance("Normal");
  $sectemailObj->BeginContent();
?>
  <table width="761" class="combo">
  <tr>
  <td class="combo" colspan="3">
    <p><br>
      <?php echo $_POST['message']; ?>    </p>
    <p>&nbsp;  </p></td>
  </tr>
    <tr>
      <td class="combo"><div style="border:solid 1px #A6CAF0;">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="2" valign="top"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="combo">
                  <tr>
                    <td width="50%"><div style="padding:5px">
                        <p class="big"><span class="big2">SEAVEST AFRICA TRADING CC</span></p>
                      <p class="combo2">P.O.BOX 201153<br>
                        DURBAN NORTH&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp; VAT  NO: 4230211908<br>
                        4016</p>
                      <p class="combo2">Tel : (031) 5637735<br>
                        Fax : 0865 191 153 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; email : hemi@seavest.co.za<br>
                        <br>
                        </p>
                    </div></td>
                    <td width="50%" valign="top" class="combo"><div style="padding-top:14px"> <br>
                            <div id="BLOCK" style="width:200px; float:right; height:auto">
                              <table border="0" cellspacing="1" cellpadding="0">
                                <tr>
                                  <td align="left" class="combo"><span class="combo_bold">TAX INVOICE:</span>&nbsp; <?php echo $row_Recordset5['InvoiceNo']; ?><br>
                                      <br></td>
                                </tr>
                                <tr>
                                  <td align="left" class="combo"><span class="combo_bold">DATE: </span><?php echo $row_date['InvoiceDate']; ?></td>
                                </tr>
                              </table>
                            </div>
                    </div></td>
                  </tr>
                  <tr>
                    <td><div id="BLOCK"><span style="padding-right:10px"><span class="combo_bold"><?php echo $row_Recordset5['Name']; ?></span><br>
                                            <?php echo $row_Recordset5['ContactName']; ?><br>
                                            <?php echo $row_Recordset5['Address_1']; ?>&nbsp;<br />
                                            <?php echo $row_Recordset5['ContactNumber']; ?><br />
                                            <?php echo $row_Recordset5['ContactEmail']; ?></span><span class="combo2"><br>
VAT NO: <?php echo $row_Recordset5['VATNO']; ?></span></div></td>
                                    <td width="50%" class="combo2"><div id="BLOCK"><span class="combo_bold">SITE ADDRESS </span><br>
                                            <?php echo $row_Recordset5['Name_1']; ?><br>
                                            <?php echo $row_Recordset5['Address']; ?><br>
                                            <br>
                                            <br>
                                    </div></td>
                  </tr>
                  <tr>
                    <td width="50%" valign="top">&nbsp;</td>
                    <td width="50%" align="right" class="combo">&nbsp;</td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td width="50%" valign="top">
							  <?php if($row_Recordset4['CompanyId'] == 1){ ?>
							  <table width="100%" border="1" cellpadding="0" cellspacing="4" bordercolor="#FFFFFF">
                                <tr style="padding-bottom:5px; padding-top:5px;">
                                  <td width="33%" bordercolor="#68A4E6" bgcolor="#a6caf0" class="combo_bold">&nbsp; JOB NO </td>
                                  <td width="33%" bordercolor="#68A4E6" bgcolor="#a6caf0" class="combo_bold">&nbsp; REFERENCE</td>
                                  <td width="33%" bordercolor="#68A4E6" bgcolor="#a6caf0" class="combo_bold">&nbsp;ORDER NO </td>
                                </tr>
                                <tr>
                                  <td width="33%" bordercolor="#FFFFFF" class="combo2">&nbsp;<?php echo $row_Recordset4['JobNo']; ?></td>
                                  <td width="33%" bordercolor="#FFFFFF" class="combo2">&nbsp;<?php echo $row_Recordset5['Reference']; ?></td>
                                  <td width="33%" bordercolor="#FFFFFF" class="combo2"><?php echo $row_refno['RefNo']; ?></td>
                                </tr>
                              </table>
                                <?php } else { ?>
							    <table width="100%" border="1" cellpadding="0" cellspacing="4" bordercolor="#FFFFFF">
                                <tr style="padding-bottom:5px; padding-top:5px;">
                                  <td width="50%" bordercolor="#68A4E6" bgcolor="#a6caf0" class="combo_bold">&nbsp; JOB NO </td>
                                  <td width="50%" bordercolor="#68A4E6" bgcolor="#a6caf0" class="combo_bold">&nbsp; REFERENCE</td>
                                </tr>
                                <tr>
                                  <td width="50%" bordercolor="#FFFFFF" class="combo2">&nbsp;<?php echo $row_Recordset4['JobNo']; ?></td>
                                  <td width="50%" bordercolor="#FFFFFF" class="combo2">&nbsp;<?php echo $row_Recordset5['Reference']; ?>&nbsp;</td>
                                </tr>
                              </table>
							  <?php } // close if CompanyId ?>
			  </td>
              <td width="50%" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top">&nbsp;</td>
              <td valign="top">&nbsp;</td>
            </tr>
          </table>
      </div></td>
    </tr>
    <tr>
      <td bordercolor="#FFFFFF" class="combo"><table width="100%" border="1" cellpadding="3" cellspacing="1" bordercolor="#FFFFFF">
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
      <td class="combo_bold"><div style=" border:solid 1px #A6CAF0">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
            <tr>
              <td align="left" bordercolor="#A6CAF0"><br>
                &nbsp;<span class="combo_bold">Labour</span><br />
                <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){

$id = $row['Id'];

$query1 = "SELECT * FROM tbl_jc WHERE Id = '$id'";
$result1 = mysql_query($query1) or die(mysql_error());
$row1 = mysql_fetch_array($result1);

$rate = $row1['Price'];
$rate = explode(".", $rate);

?>
                <table width="100%" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td width="450" class="combo2"><div style="padding-left:6px"><?php echo $row['Description']; ?></div></td>
                    <td width="50" align="center" valign="bottom" class="combo2"><?php echo $row['Unit']; ?></td>
                    <td width="50" align="center" valign="bottom" class="combo2"><?php echo $row['Qty']; ?></td>
                    <td width="100" align="center" valign="bottom" class="combo2"><?php echo $row['Price']; ?></td>
                    <td width="50" align="center" valign="bottom" class="combo">R<?php echo $row['Total1']; ?></td>
                  </tr>
                </table>
                <?php } // close loop ?>
                <div style="border-top:solid 1px #A6CAF0; margin-left:5px; margin-right:5px"></div>
                <table width="760" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td align="right" class="combo_bold">R
                      <?php sum_labour($jobid); ?>
                      &nbsp; </td>
                  </tr>
                </table>
                <div style="border-top:solid 1px #A6CAF0; margin-left:5px; margin-right:5px"></div>
                <br>
                <br>
                &nbsp;<span class="combo_bold">Material</span>
                <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                <table width="100%" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td width="450" class="combo2"><div style="padding-left:6px"><?php echo $row['Description']; ?></div></td>
                    <td width="50" align="center" class="combo2"><?php echo $row['Unit']; ?></td>
                    <td width="50" align="center" class="combo2"><?php echo $row['Qty']; ?></td>
                    <td width="100" align="center" class="combo2">R<?php echo $row['Price']; ?></td>
                    <td width="50" align="center" class="combo">R<?php echo $row['Total1']; ?>&nbsp;&nbsp; </td>
                  </tr>
                </table>
                <?php } // close loop ?>
                <div style="border-top:solid 1px #A6CAF0; margin-left:5px; margin-right:5px"></div>
                <table width="760" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td align="right" class="combo_bold">R
                      <?php sum_material($jobid); ?>
                      &nbsp; </td>
                  </tr>
                </table>
                <div style="border-top:solid 1px #A6CAF0; margin-left:5px; margin-right:5px"></div>
                <br>
                <br>
                &nbsp;<span class="combo_bold">Transport</span>
                <?php
$query = "SELECT * FROM tbl_jc WHERE Qty >= '1' AND JobId = '$jobid' AND Transport = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                                  <tr>
                                    <td width="350">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?> </td>
                                    <td width="100" align="right"><div style="padding-right:5px; text-align:right; width:95px"><span class="combo2"><?php echo $row['Description']; ?></span> round trips </div></td>
                                    <td width="50" align="center" valign="bottom">km</td>
                                    <td width="50" align="center" valign="bottom"><span class="combo2"><?php echo $row['Qty']; ?></span></td>
                                    <td width="100" align="center" valign="bottom">R<span class="combo2"><?php echo $row_Recordset8['Rate']; ?></span></td>
                                    <td width="50" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
                                  </tr>
                </table>
                <?php } // close loop ?>
                <div style="border-top:solid 1px #A6CAF0; margin-left:5px; margin-right:5px"></div>
                <table width="760" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td align="right" class="combo_bold">R
                      <?php sum_transport($jobid); ?>
                      &nbsp; </td>
                  </tr>
                </table>
                <div style="border-top:solid 1px #A6CAF0; margin-left:5px; margin-right:5px"><br>
                </div>
                <table width="760" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td align="right" class="combo_bold"><table border="0" cellpadding="0" cellspacing="3" class="combo2">
                      <tr>
                        <td nowrap class="combo_bold">Sub Total:&nbsp;&nbsp;&nbsp;&nbsp; </td>
                        <td align="right" nowrap class="combo_bold">R<?php echo $row_Recordset9['SubTotal']; ?></td>
                      </tr>
                      <tr>
                        <td nowrap class="combo_bold">Vat: </td>
                        <td align="right" nowrap class="combo_bold"><?php
								  vat($jobid)
								  ?>                        </td>
                      </tr>
                      <tr>
                        <td colspan="2" nowrap class="combo_bold"><div style="border-top:solid 1px #A6CAF0;"></div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr class="combo2">
                                <td nowrap class="combo_bold">Total:</td>
                                <td align="right" nowrap class="combo_bold"><?php 
								  total($jobid)								  ?>                                </td>
                              </tr>
                            </table>
                          <div style="border-top:solid 1px #A6CAF0;"></div></td>
                      </tr>
                    </table>                      
                        <br>
                    &nbsp; </td>
                  </tr>
                </table></td>
            </tr>
          </table>
      </div></td>
    </tr>
  </table>
  <?php
  $sectemailObj->EndContent();
  $sectemailObj->Execute();
?></body>
</html>
<?php

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset3);

mysql_free_result($Recordset6);

mysql_free_result($Recordset7);

mysql_free_result($Recordset8);

mysql_free_result($Recordset9);

mysql_free_result($refno);

mysql_free_result($date);

//Redirect Email sectemailObj
  $redObj = new tNG_Redirect(null);
  $redObj->setURL("mail_confirm.php");
  $redObj->setKeepURLParams(false);
  $redObj->Execute();
//End Redirect Email sectemailObj
?>
