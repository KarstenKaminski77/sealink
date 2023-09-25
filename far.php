<?php require_once('Connections/seavest.php'); ?>
<?php
require_once('functions/functions.php');

$risk = $_POST['risk'];
$type = $_POST['type'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_far_risc_classification";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

if($_POST['risk'] == 3){

$colname_Recordset3 = "-1";
if (isset($_POST['type'])) {
  $colname_Recordset3 = (get_magic_quotes_gpc()) ? $_POST['type'] : addslashes($_POST['type']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT * FROM tbl_far_high_risk_compulsary WHERE RiskId = %s", $colname_Recordset3);
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

$riskid = $_POST['risk'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT * FROM tbl_far_competence WHERE CompanyId = '$companyid' AND RiskId = '$riskid'";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

select_db();

$jobno = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_far WHERE JobNo = '$jobno'")or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

if($numrows == 0){

mysql_query("INSERT INTO tbl_far (JobNo) VALUES ('$jobno')")or die(mysql_error());

} else {

mysql_query("UPDATE tbl_far SET RiskType = '$risk', RiskClassification = '$type' WHERE JobNo = '$jobno'")or die(mysql_error());

}

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
   border: 1px solid #000066;
 }
.td2 {
   border-bottom: 1px solid #000066;
 }
.big {
    font-size:14px;
}
-->
</style>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>

<body <?php if(isset($_POST['Submit'])){ ?>onLoad="window.print(); window.close();"<?php } ?>>
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
              <td width="20%" nowrap class="td">Risk Classification</td>
              <td width="80%" class="td">
  <script type="text/javascript">
function submitform()
{
  document.form1.submit();
}
</script> 
                
                <select name="risk" class="tarea" id="risk" style="width:100%" onChange="MM_jumpMenu('parent',this,0)">
                  <option value=" ">Select one...</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_Recordset1['Id']?>" onClick="submitform()" <?php if($row_Recordset1['Id'] == $_POST['risk']){ echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['Risk']?></option>
                  <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                </select></td>
            </tr>
          <?php if($_POST['risk'] == 3){ ?>
            <tr>
              <td nowrap class="td">High risk classification</td>
              <td class="td">
  <script type="text/javascript">
function submitform()
{
  document.form1.submit();
}
</script> 
                
                <select style="width:100%" name="type" class="tarea" id="type" onChange="MM_jumpMenu('parent',this,0)">
                  <option value=" ">Select one...</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_Recordset2['Id']?>" onClick="submitform()" <?php if($row_Recordset2['Id'] == $_POST['type']){ echo "selected=\"selected\"";} ?>><?php echo $row_Recordset2['Risk']?></option>
                  <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                </select></td>
            </tr>
          <?php } if($totalRows_Recordset3 >= 1){ ?>
            <tr>
              <td valign="top" nowrap class="td">Complusary equipment</td>
              <td class="td"><?php do { ?>
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
              <td valign="top" class="td">Competence </td>
              <td class="td"><?php do { ?>
                  <table border="0" cellspacing="3" cellpadding="2">
                    <tr>
                      <td class="combo"><?php echo $row_Recordset5['Competenance']; ?></td>
                    </tr>
                  </table>
              <?php } while ($row_Recordset5 = mysql_fetch_assoc($Recordset5)); ?></td>
            </tr>
			<?php } ?>
        </table>          </td>
        <td width="50%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
          <tr>
            <td colspan="2" class="big">On site evaluation</td>
          </tr>
          
          <tr>
            <td nowrap class="td">Ppe</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td nowrap class="td">Customer communication</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" nowrap class="td">Safety documents</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" class="td">Barricades</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td class="td">Signage</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td class="td">Planning</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td class="td">Team control</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td class="td">Housekeeping</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td class="td">Speed </td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td class="td">Tools/equipment</td>
            <td width="20" class="td">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
          <tr>
            <td width="273" class="td">Inspector&nbsp;&nbsp;&nbsp;</td>
            <td width="186" class="td">Date</td>
            <td width="49" class="td"><b>Total</b></td>
            <td width="16" class="td">&nbsp;</td>
          </tr>
        </table>
          <table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">

          <tr>
            <td colspan="8" class="big">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8" class="big">Site visual inspection report</td>
          </tr>
          <tr>
            <td colspan="8" nowrap class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8" nowrap class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8" valign="top" nowrap class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8" valign="top" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="8" class="td">&nbsp;</td>
          </tr>
          <tr>
            <td width="105" nowrap class="td">No of pictures</td>
            <td width="20" nowrap class="td">&nbsp;</td>
            <td width="105" nowrap class="td">Low hsse</td>
            <td width="20" nowrap class="td">&nbsp;</td>
            <td width="105" nowrap class="td">Medium hsse</td>
            <td width="20" nowrap class="td">&nbsp;</td>
            <td width="105" nowrap class="td">High hsse</td>
            <td width="20" nowrap class="td">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
          <tr>
            <td colspan="4" class="big">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" class="big">Feedback</td>
          </tr>
          <tr>
            <td colspan="4" class="td"><div style="padding:5px"><?php echo $row_Recordset4['JobDescription']; ?></div></td>
          </tr>

          <tr>
            <td colspan="4" nowrap class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" nowrap class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" nowrap class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" nowrap class="td">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" nowrap class="td">&nbsp;</td>
          </tr>

          <tr>
            <td colspan="4" nowrap>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" nowrap class="big">Official Use Only</td>
          </tr>
          <tr>
            <td width="38%" nowrap class="td">Score &amp; Pics Recorded</td>
            <td width="4%" nowrap class="td">&nbsp;</td>
            <td nowrap class="td">Findings expanded to</td>
            <td width="4%" nowrap class="td">&nbsp;</td>
          </tr>

          <tr>
            <td colspan="4" nowrap class="td" style="border-left:0px; border-right:0px; border-top:0px"><br>
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

if($_POST['risk'] == 3){
mysql_free_result($Recordset3);
}
mysql_free_result($Recordset4);

mysql_free_result($Recordset5);


?>
