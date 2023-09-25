<?php 
session_start();

require_once('../Connections/inv.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_inv = new KT_connection($inv, $database_inv);

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

// Make a custom transaction instance
$customTransaction = new tNG_custom($conn_inv);
$tNGs->addTransaction($customTransaction);
// Register triggers
$customTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "VALUE", "1");
// Add columns
// End of custom transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);

ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="http://seavest.co.za/inv/includes/skins/mxkollection3.css" media="all">
<link rel="stylesheet" type="text/css" href="http://seavest.co.za/inv/styles/layout.css">
<link rel="stylesheet" type="text/css" href="http://seavest.co.za/inv/styles/fonts.css">
<style>

body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333;
}
</style>
</head>

<body>
<?php
  $status_id = $_POST['status-id'];
  
  for($i=0;$i<1;$i++){
	  
	  $jobid = $status_id[$i];

mysql_select_db($database_inv, $inv);
$query_Recordset1 = "SELECT tbl_companies.Name AS Name_2, tbl_engineers.Name AS Name_1, tbl_sites.Name, tbl_engineers.Id, tbl_jc.CustomerFeedBack, tbl_engineers.CompanyId, tbl_engineers.Email, tbl_jc.JobNo, tbl_history_photos.Photo, tbl_history_relation.JobId, tbl_history_relation.Active, tbl_jc.JcDate, tbl_jc.Status, tbl_jc.Progress, tbl_jc.JobId AS JobId_1 FROM (((((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_history_relation ON tbl_history_relation.JobId=tbl_jc.JobId) LEFT JOIN tbl_history_photos ON tbl_history_photos.Id=tbl_history_relation.PhotoId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_engineers ON tbl_engineers.Id=tbl_sites.EngineerId) WHERE tbl_jc.JobId = '$jobid' AND tbl_history_relation.Active = '1' GROUP BY tbl_jc.JobId";
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

$_SESSION['site'] = $row_Recordset1['Name'];
  }

  $email = $_POST['email'];
  $cc = $_POST['cc'];
  $sectemailObj = new tNG_EmailPageSection();
  $sectemailObj->getCSSFrom(__FILE__);
  $sectemailObj->setTo($email);
  $sectemailObj->setCc($cc);
  $sectemailObj->setFrom("control@seavest.co.za");
  $sectemailObj->setSubject("Seavest Status Report ". $_SESSION['site']);
  $sectemailObj->setFormat("HTML/Text");
  $sectemailObj->setEncoding("UTF-8");
  $sectemailObj->setImportance("Normal");
  $sectemailObj->BeginContent();
?>
    <br />
  <table width="680" border="0" align="center" cellpadding="1" cellspacing="3">
    <tr>
      <td colspan="2"><img src="http://www.seavest.co.za/inv/images/status-report.jpg" width="660" height="93" /></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="330"><span style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#0161AB; font-weight:bold">Status Report</span></td>
      <td width="330" align="right" valign="bottom" class="blue-generic"><?php echo date('d-m-Y'); ?></td>
    </tr>
  </table>
  <br />
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
  <div style="padding:2px; border:solid 1px #CCC; width:660px">
  <table width="660" border="0" align="center" cellpadding="3" cellspacing="1">
    <tr>
      <td bgcolor="#F4F4F4"><div style="padding:10px; font-family:Arial; font-size:12px; color:#000"><?php echo nl2br($_POST['message']); ?></div></td>
    </tr>
  </table>
  </div>
    </td>
  </tr>
</table>
<br />
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <div id="list-border">
<table width="660" border="0" align="center" cellpadding="3" cellspacing="1" class="combo">
      <tr class="odd">
        <td width="100" bgcolor="#E1E1E1" class="td-header"><strong>JobNo</strong></td>
        <td width="350" bgcolor="#E1E1E1" class="td-header"><strong>Site</strong></td>
        <td width="150" bgcolor="#E1E1E1" class="td-header"><strong>Progress</strong></td>
        <td colspan="2" align="right" bgcolor="#E1E1E1" class="td-header" width="60">&nbsp;</td>
      </tr>
  <?php
  
  $count = count($_POST['status-id']);
  
  $status_id = $_POST['status-id'];
  
  for($i=0;$i<$count;$i++){
	  
	  $jobid = $status_id[$i];
	  
mysql_select_db($database_inv, $inv);
$query_Recordset5 = "SELECT tbl_companies.Name AS Name_2, tbl_engineers.Name AS Name_1, tbl_sites.Name, tbl_engineers.Id, tbl_jc.CustomerFeedBack, tbl_engineers.CompanyId, tbl_engineers.Email, tbl_jc.JobNo, tbl_history_photos.Photo, tbl_history_relation.JobId, tbl_jc.JcDate, tbl_jc.Status, tbl_jc.Progress, tbl_jc.JobId AS JobId_1 FROM (((((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_history_relation ON tbl_history_relation.JobId=tbl_jc.JobId) LEFT JOIN tbl_history_photos ON tbl_history_photos.Id=tbl_history_relation.PhotoId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_engineers ON tbl_engineers.Id=tbl_sites.EngineerId) WHERE tbl_jc.JobId = '$jobid' GROUP BY tbl_jc.JobId";
$Recordset5 = mysql_query($query_Recordset5, $inv) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
  
  ?>
      <?php do { ?>
      <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
        <td width="100"><?php echo $row_Recordset5['JobNo']; ?></td>
        <td width="350"><?php echo $row_Recordset5['Name']; ?></td>
        <td width="150">
        <div id="status-graph-brdr" style="border:none; width:150px">
        <table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>
<table width="150" border="1" cellspacing="0" cellpadding="0" bordercolor="#FF0000" style="border-color:#F00">
        <tr><td height="5">
        <?php if($row_Recordset5['Progress'] >= 1){ ?>
          <div id="status-graph" style="width:<?php echo $row_Recordset5['Progress']; ?>%; border:none">
          <table width="<?php echo $row_Recordset5['Progress']; ?>%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="5" bgcolor="#FF0000">&nbsp;</td>
  </tr>
</table>
</div>
<?php } else { ?>
          <div id="status-graph" style="width:<?php echo $row_Recordset5['Progress']; ?>%; border:none">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="5">&nbsp;</td>
  </tr>
</table>
</div>
<?php } ?>
</td></tr>
</table>    </td>
  </tr>
</table>

        </div></td>
        <td width="30" align="center" ><strong><?php echo $row_Recordset5['Progress']; ?>%</strong></td>
        <td width="30" align="center"><?php if($row_Recordset5['JobId'] != NULL){ ?>
          <a href="../status-photos.php?Id=<?php echo $row_Recordset5['JobId']; ?>&amp;photos"> <img src="http://www.seavest.co.za/inv/images/camera-icon.png" width="25" height="20" border="0" /> </a>
          <?php } ?></td>
    </tr>
    <?php if(!empty($row_Recordset5['CustomerFeedBack'])){ ?>
      <tr bgcolor="#E1E1E1">
        <td colspan="5" class="comb-sms"><table border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td><span style="padding:10px; width:640px"><?php echo nl2br($row_Recordset5['CustomerFeedBack']); ?></span></td>
          </tr>
        </table></td>
      </tr>
      <?php } ?>
      <?php } while ($row_Recordset5 = mysql_fetch_assoc($Recordset5)); } ?>
    </table>
        </div></td>
  </tr>
</table>

  <?php
  
  $sectemailObj->EndContent();
  $sectemailObj->Execute();
?>
</body>
</html>
<?php
mysql_free_result($Recordset5);

 $redObj = new tNG_Redirect(null);
 $redObj->setURL("index.php?Success");
 $redObj->setKeepURLParams(false);
 $redObj->Execute();

?>
