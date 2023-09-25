<?php require_once('Connections/seavest.php'); ?>
<?php
require_once('functions/functions.php');

select_db();

$KTColParam1_Recordset1 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset1 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT tbl_far_high_risk_classification.Risk AS Risk_1, tbl_far.JobNo, tbl_far_risc_classification.Risk, tbl_far.RiskType, tbl_far.RiskClassification FROM ((tbl_far LEFT JOIN tbl_far_high_risk_classification ON tbl_far_high_risk_classification.Id=tbl_far.RiskClassification) LEFT JOIN tbl_far_risc_classification ON tbl_far_risc_classification.Id=tbl_far.RiskType) WHERE tbl_far.JobNo='%s' ", $KTColParam1_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

if($row_Recordset1['RiskType'] == 3){

$type = $row_Recordset1['RiskClassification'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT * FROM tbl_far_high_risk_compulsary WHERE RiskId = '$type'");
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
}

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT * FROM tbl_jc WHERE JobNo = '%s'", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$companyid = $row_Recordset4['CompanyId'];
$riskid = $row_Recordset1['RiskType'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT * FROM tbl_far_competence WHERE CompanyId = '$companyid' AND RiskId = '$riskid'";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$KTColParam1_Recordset6 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset6 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset6 = sprintf("SELECT tbl_far_high_risk_classification.Risk AS Risk_1, tbl_far.JobNo, tbl_far_risc_classification.Risk, tbl_far.RiskType, tbl_far.RiskClassification FROM ((tbl_far LEFT JOIN tbl_far_high_risk_classification ON tbl_far_high_risk_classification.Id=tbl_far.RiskClassification) LEFT JOIN tbl_far_risc_classification ON tbl_far_risc_classification.Id=tbl_far.RiskType) WHERE tbl_far.JobNo='%s' ", $KTColParam1_Recordset6);
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

$jobno = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobNo = '$jobno'")or die(mysql_error());
$row = mysql_fetch_array($query);

$jobid = $row['JobId'];

mysql_query("UPDATE tbl_jc SET Print = '1' WHERE JobId = '$jobid'")or die(mysql_error());
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FF0000}

.td {
   border: solid 1px #9FBAFF;
 }
.big {    font-size:14px;
}
.td1 {   border: 1px solid #000066;
}
-->
</style>
</head>

<body onLoad="window.print(); window.close();">
<form name="form1" method="post" action="far.php?Id=<?php echo $_GET['Id']; ?>">
  <div style="padding:10px; margin:0px; width:640px; overflow:hidden">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2"><?php
  $mail = $_POST['mail'];
  
?>
            <table width="100%" border="0" cellpadding="0" cellspacing="1">
              <tr>
                <td rowspan="2"><img src="fpdf16/mail_logo.jpg" width="109" height="89"></td>
                <td align="right" class="big">FIELD ASSESMENT REPORT </td>
              </tr>
              <tr>
                <td align="right" class="combo">Reference: <span class="style1"><?php echo $_GET['Id']; ?></span></td>
              </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="50%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
            <tr>
              <td colspan="2" class="big">Proactive  Risk  Assessement</td>
            </tr>
            <tr>
              <td width="20%" nowrap class="td1">Risk Classification</td>
              <td width="80%" class="td1">
              <?php echo $row_Recordset1['Risk']; ?></td>
            </tr>
            <?php if($row_Recordset1['RiskType'] == 3){ ?>
            <tr>
              <td nowrap class="td1">High risk classification</td>
              <td class="td1">
      <?php echo $row_Recordset1['Risk_1']; ?></td>
            </tr>
            <?php } if($totalRows_Recordset3 >= 1){ ?>
            <tr>
              <td valign="top" nowrap class="td1">Complusary equipment</td>
              <td class="td1"><?php do { ?>
                  <table border="0" cellpadding="2" cellspacing="3">
                    <tr>
                      <td class="combo"><?php echo $row_Recordset3['Item']; ?></td>
                    </tr>
                  </table>
              <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?></td>
            </tr>
            <?php } ?>
            <?php if($totalRows_Recordset5 >= 1){ ?>
            <tr>
              <td valign="top" class="td1">Competence </td>
              <td class="td1"><?php do { ?>
                  <table border="0" cellspacing="3" cellpadding="2">
                    <tr>
                      <td class="combo"><?php echo $row_Recordset5['Competenance']; ?></td>
                    </tr>
                  </table>
              <?php } while ($row_Recordset5 = mysql_fetch_assoc($Recordset5)); ?></td>
            </tr>
            <?php } ?>
        </table></td>
        <td width="50%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
            <tr>
              <td colspan="2" class="big">On site evaluation</td>
            </tr>
            <tr>
              <td nowrap class="td1">Ppe</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td nowrap class="td1">Customer communication</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" nowrap class="td1">Safety documents</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" class="td1">Barricades</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td class="td1">Signage</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td class="td1">Planning</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td class="td1">Team control</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td class="td1">Housekeeping</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td class="td1">Speed </td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td class="td1">Tools/equipment</td>
              <td width="20" class="td1">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
            <tr>
              <td width="273" class="td1">Inspector&nbsp;&nbsp;&nbsp;</td>
              <td width="186" class="td1">Date</td>
              <td width="49" class="td1"><b>Total</b></td>
              <td width="16" class="td1">&nbsp;</td>
            </tr>
          </table>
            <table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
              <tr>
                <td colspan="8" class="big1">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" class="big">Site visual inspection report</td>
              </tr>
              <tr>
                <td colspan="8" nowrap class="td1">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" nowrap class="td1">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" valign="top" nowrap class="td1">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" valign="top" class="td1">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" class="td1">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" class="td1">&nbsp;</td>
              </tr>
              <tr>
                <td width="105" nowrap class="td1">No of pictures</td>
                <td width="20" nowrap class="td1">&nbsp;</td>
                <td width="105" nowrap class="td1">Low hsse</td>
                <td width="20" nowrap class="td1">&nbsp;</td>
                <td width="105" nowrap class="td1">Medium hsse</td>
                <td width="20" nowrap class="td1">&nbsp;</td>
                <td width="105" nowrap class="td1">High hsse</td>
                <td width="20" nowrap class="td1">&nbsp;</td>
              </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
            <tr>
              <td colspan="4" class="big1">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" class="big">Feedback</td>
            </tr>
            <tr>
              <td colspan="4" class="td1"><div style="padding:5px"><?php echo $row_Recordset4['JobDescription']; ?></div></td>
            </tr>
            <tr>
              <td colspan="4" nowrap class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" nowrap class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" nowrap class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" nowrap class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" nowrap class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" nowrap>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" nowrap class="big">Official Use Only</td>
            </tr>
            <tr>
              <td width="38%" nowrap class="td1">Score &amp; Pics Recorded</td>
              <td width="4%" nowrap class="td1">&nbsp;</td>
              <td nowrap class="td1">Findings expanded to</td>
              <td width="4%" nowrap class="td1">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" nowrap class="td1" style="border-left:0px; border-right:0px; border-top:0px"><br>
                Administrator:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature:&nbsp;   &nbsp;   &nbsp;  &nbsp;   &nbsp;   &nbsp;   &nbsp;   &nbsp;   &nbsp;   &nbsp;   &nbsp;&nbsp;  &nbsp;  &nbsp;&nbsp; &nbsp;   &nbsp;   &nbsp;&nbsp; </td>
            </tr>
        </table></td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
<?php

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

if($row['RiskType'] == 3){
mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset6);
}

?>
