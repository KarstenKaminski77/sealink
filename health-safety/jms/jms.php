<?php require_once('../../Connections/seavest.php'); ?>
<?php 
session_start();

require_once('../../Connections/seavest.php'); ?>
<?php require_once('../../Connections/inv.php'); ?>
<?php
//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');
?>
<?php

require_once('../../functions/functions.php'); 

select_db();

$id = $_GET['Id'];
	
$query = mysql_query("SELECT * FROM tbl_job_steps WHERE HESId = '$id' AND JMS = '1'")or die(mysql_error());
$numrows = mysql_num_rows($query);

if($numrows == 0){
		
	mysql_query("INSERT INTO tbl_job_steps (HESId,JMS) VALUES ('$id','1')")or die(mysql_error());
}


// Add row to job steps

if(isset($_POST['add'])){
	
	$id = $_GET['Id'];
	
	mysql_query("INSERT INTO tbl_job_steps (HESId,JMS) VALUES ('$id','1')")or die(mysql_error());
	
}

// Delete job steps row

if(isset($_POST['delete'])){
	
	$delete = $_POST['delete'];
	
	foreach($delete as $c){
		
		mysql_query("DELETE FROM tbl_job_steps WHERE Id = '$c'")or die(mysql_error());
		
	}
}

// Update  Job Steps

if(isset($_POST['jobsteps'])){
		
	$jobstep = $_POST['jobsteps'];
	$hazard = $_POST['hazard'];
	$actions = $_POST['actions'];
	$id = $_POST['id_2'];
	
	$rows = count($_POST['jobsteps']);
    
	for($i=0;$i<$rows;$i++){
		
		$jobstep1 = addslashes($jobstep[$i]);
		$hazard1 = addslashes($hazard[$i]);
		$actions1 = addslashes($actions[$i]);
		$id2 = $id[$i];
		
		mysql_query("UPDATE tbl_job_steps SET JobSteps = '$jobstep1', PotentialHazard = '$hazard1', CriticalActions = '$actions1' WHERE Id = '$id2'")or die(mysql_error());
		
	}
	
	// Activate Document
	
	mysql_query("UPDATE tbl_hes_documents_relation SET New = '0' WHERE DocumentId = '3'")or die(mysql_error());
}

// Redirects

if(isset($_POST['pdf'])){
	
	header('Location: ../../fpdf16/pdf-jms.php?Id='. $_GET['Id']);
	
}

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

$KTColParam1_Recordset1 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset1 = $_GET["Id"];
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT tbl_sites.Id AS Id_2, tbl_sites.Name AS Name_1, tbl_far_high_risk_classification.Risk, tbl_hes.JobNo, tbl_hes.Date, tbl_hes.ScopeOfWork, tbl_hes_jsa_relation.JSAId, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Address, tbl_sites.Suburb, tbl_sites.Telephone, tbl_hes.Id, tbl_sites.Email, tbl_companies.Name FROM ((((tbl_hes LEFT JOIN tbl_hes_jsa_relation ON tbl_hes_jsa_relation.JobNo=tbl_hes.JobNo) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_hes.CompanyId) LEFT JOIN tbl_far_high_risk_classification ON tbl_far_high_risk_classification.Id=tbl_hes_jsa_relation.JSAId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_hes.SiteId) WHERE tbl_hes.Id=%s ", GetSQLValueString($KTColParam1_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$KTColParam1_Recordset2 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset2 = $_GET["Id"];
}
mysql_select_db($database_inv, $inv);
$query_Recordset2 = sprintf("SELECT tbl_ppe_relation.Id AS Id_1, tbl_jsa.Id, tbl_ppe_list.PPE, tbl_ppe_relation.PPEId, tbl_ppe_relation.Selected, tbl_ppe_relation.Comments FROM ((tbl_jsa LEFT JOIN tbl_ppe_relation ON tbl_ppe_relation.HESId=tbl_jsa.Id) LEFT JOIN tbl_ppe_list ON tbl_ppe_list.Id=tbl_ppe_relation.PPEId) WHERE tbl_jsa.Id=%s ", GetSQLValueString($KTColParam1_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $inv) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_Recordset3 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset3 = $_GET['Id'];
}
mysql_select_db($database_inv, $inv);
$query_Recordset3 = sprintf("SELECT * FROM tbl_job_steps WHERE HESId = %s AND JMS = '1'", GetSQLValueString($colname_Recordset3, "int"));
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$KTColParam1_Recordset4 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset4 = $_GET["Id"];
}
mysql_select_db($database_inv, $inv);
$query_Recordset4 = sprintf("SELECT tbl_hes_documents_relation.HESId, tbl_hes_documents_relation.DocumentId, tbl_hes_documents.Document FROM (tbl_hes_documents_relation LEFT JOIN tbl_hes_documents ON tbl_hes_documents.Id=tbl_hes_documents_relation.DocumentId) WHERE tbl_hes_documents_relation.HESId=%s ", GetSQLValueString($KTColParam1_Recordset4, "int"));
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

mysql_select_db($database_inv, $inv);
$query_Recordset5 = "SELECT * FROM tbl_sites";
$Recordset5 = mysql_query($query_Recordset5, $inv) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

if(isset($_POST['save'])){
	
	header('Location: ../../fpdf16/pdf-jms.php?Id='. $_GET['Id']);
}

if(isset($_POST['eap'])){
	
	header('Location: ../eap/eap.php?Id='. $_GET['Id']);
}

if(isset($_POST['jsa'])){
	
	header('Location: ../jsa/jsa.php?Id='. $_GET['Id']);
}

if(isset($_POST['close'])){
	
	// Save and Close
		
	header('Location: ../../fpdf16/pdf-jms.php?Id='. $_GET['Id'] .'&Close');
		
}

if(isset($_POST['preview'])){
	
?><script type="text/javascript" language="Javascript">window.open('../../fpdf16/pdf-jms.php?Id=<?php echo $_GET['Id']; ?>&Preview');</script>
    
<?php } ?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
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
.new-supplier, a.new-supplier:link, a.new-supplier:visited, a.new-supplier:active {
	background-image: url(../../order-forms/images/add-new.png);
	display: block;
	padding: 0px;
	height: 15px;
	width: 15px;
	text-decoration:none;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 5px;
	border:none;
}
.new-supplier:hover {
	background-image: url(../../order-forms/images/add-new.png);
	text-decoration:none;
	background-position: center -15px;
}

-->
</style>
<script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../../includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/DependentDropdown.js"></script>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../../menu.php'); ?></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
  <form name="form2" method="post" action="jms.php?Id=<?php echo $_GET['Id']; ?>">
<div style="margin-left:30px; margin-top:1px">
  <table width="795" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="td-header"><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="eap" type="submit" class="btn-green-generic" id="eap" value="EAP Document"></td>
          <td><input name="jsa" type="submit" class="btn-green-generic" id="jsa" value="JSA Document"></td>
        </tr>
      </table></td>
      <td align="right" class="td-header"><table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="preview" type="submit" class="btn-blue-generic" id="preview" value="Preview"></td>
          <td><input name="save" type="submit" class="btn-blue-generic" id="save" value="Save"></td>
          <td><input name="close" type="submit" class="btn-blue-generic" id="close" value="Save &amp; Close"></td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>
<div style="border:solid 1px #A6CAF0; width:753px; background-color:#EEE; margin-left: 30px; padding: 20px; margin-top:5px; color: #333;"">
  <table width="100%" border="0" cellpadding="3" cellspacing="0" bordercolor="#0000FF">
            <tr bordercolor="#FFFFFF">
              <td bordercolor="#0000FF"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                <tr bordercolor="#FFFFFF">
                  <td width="50%" valign="top">
                    <table border="0" cellpadding="2" cellspacing="3" class="combo-grey">
                      <tr>
                        <td valign="top"><?php echo $row_Recordset1['Name']; ?></td>
                        <td width="50">&nbsp;</td>
                        <td nowrap><?php echo $row_Recordset1['FirstName']; ?>&nbsp; <?php echo $row_Recordset1['LastName']; ?></td>
                        </tr>
                      <tr>
                        <td><?php echo $row_Recordset1['Name_1']; ?></td>
                        <td width="50">&nbsp;</td>
                        <td nowrap><?php echo $row_Recordset1['Telephone']; ?></td>
                        </tr>
                      <tr>
                        <td><?php echo $row_Recordset1['Address']; ?></td>
                        <td width="50">&nbsp;</td>
                        <td nowrap><?php echo $row_Recordset1['Email']; ?></td>
                        </tr>
                      </table>
                    </td>
                  <td width="50%" align="right" valign="top" class="combo"><table border="0" align="right" cellpadding="2" cellspacing="3" class="combo-grey">
                    <tr>
                      <td class="combo-grey"> <strong>Reference:&nbsp;</strong></td>
                      <td><?php echo $row_Recordset1['JobNo']; ?></td>
                      </tr>
                    <tr>
                      <td><div style="padding-right:10px"> <strong>Work Activity:</strong></div></td>
                      <td><?php echo $row_Recordset1['ScopeOfWork']; ?></td>
                    </tr>
                    </table></td>
                  </tr>
                </table></td>
            </tr>
            </table>
        </div>
<div style="margin-left:28px; margin-top: 5px;">
  <div style="margin-top:5px">
    <table width="800" border="0" cellpadding="2" cellspacing="2">
        <tr>
          <td width="33%" class="td-header"><strong>&nbsp;Job Step</strong></td>
          <td width="33%" class="td-header"><strong> Job Step Hazard</strong></td>
          <td width="33%" class="td-header">&nbsp;Job Step Control</td>
          <td class="td-header">&nbsp;</td>
          </tr>
        <?php do { ?>
          <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
            <td width="33%"><textarea name="jobsteps[]" cols="45" rows="5" class="tarea-jsa-job-steps" id="jobsteps[]"><?php echo stripslashes($row_Recordset3['JobSteps']); ?></textarea></td>
            <td width="33%" align="center"><textarea name="hazard[]" cols="45" rows="5" class="tarea-jsa-job-steps" id="hazard[]"><?php echo stripslashes($row_Recordset3['PotentialHazard']); ?></textarea></td>
            <td align="center"><textarea name="actions[]" cols="45" rows="5" class="tarea-jsa-job-steps-2" id="actions[]"><?php echo stripslashes($row_Recordset3['CriticalActions']); ?></textarea></td>
  <td align="center" valign="top"><input name="delete[]" type="checkbox" id="delete[]" value="<?php echo $row_Recordset3['Id']; ?>">
    <input name="id_2[]" type="hidden" id="id_2[]" value="<?php echo $row_Recordset3['Id']; ?>"></td>
            </tr>
          <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
          <tr>
          <td colspan="4" align="right"><input name="add" type="submit" class="new-supplier" id="add" value="" onClick="document.form.action='compose.php?col3=open#CollapsiblePanel3'; document.form.submit();"></td>
          <tr>
            <td colspan="4" class="td-header">Contractor</td>
          <tr>
            <td colspan="4" class="even"><input name="contractor" type="text" class="tarea-jsa-job-steps" id="contractor" value="Seavest Africa"></td>
          <tr>
            <td colspan="4" align="right" class="td-header"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="td-header"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><input name="eap" type="submit" class="btn-green-generic" id="eap" value="EAP Document"></td>
                    <td><input name="jsa" type="submit" class="btn-green-generic" id="jsa" value="JSA Document"></td>
                  </tr>
                </table></td>
                <td align="right" class="td-header"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><input name="preview" type="submit" class="btn-blue-generic" id="preview" value="Preview"></td>
                    <td><input name="save" type="submit" class="btn-blue-generic" id="save" value="Save"></td>
                    <td><input name="close" type="submit" class="btn-blue-generic" id="close" value="Save &amp; Close"></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </table>
    </div>
</div>
</form>
</td>
      </tr>
    </table>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);
?>
