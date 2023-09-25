<?php require_once('../../Connections/seavest.php'); ?>
<?php require_once('../../Connections/inv.php'); ?>
<?php

require_once('../../functions/functions.php'); 

select_db();

// Add row to job steps

if(isset($_POST['add'])){
	
	$id = $_GET['Id'];
	
	mysql_query("INSERT INTO tbl_job_steps (JSAId) VALUES ('$id')")or die(mysql_error());
	
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
}

// Update  Personal Protective Equipment (PPE)

if(isset($_POST['save'])){
	
	$rows = count($_POST['comments']);
	
	$select = $_POST['select'];
	$comment = $_POST['comments'];
	$id = $_POST['id'];
			
	for($i=0;$i<$rows;$i++){
		
			$select2 = $select[$i];
			$comment2 = $comment[$i];
			$id2 = $id[$i];
						
			mysql_query("UPDATE tbl_ppe_relation SET Selected = '$select2', Comments = '$comment2' WHERE Id = '$id2'")or die(mysql_error());
			
	}
			
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
$query_Recordset1 = sprintf("SELECT tbl_sites.Name AS Name_1, tbl_companies.Name, tbl_jsa.Reference, tbl_jsa.WorkActivity, tbl_jsa.Id, tbl_far_high_risk_classification.Risk, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Telephone, tbl_sites.Address, tbl_sites.Email FROM (((tbl_jsa LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jsa.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jsa.CompanyId) LEFT JOIN tbl_far_high_risk_classification ON tbl_far_high_risk_classification.Id=tbl_jsa.JSAType) WHERE tbl_jsa.Id=%s ", GetSQLValueString($KTColParam1_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$KTColParam1_Recordset2 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset2 = $_GET["Id"];
}
mysql_select_db($database_inv, $inv);
$query_Recordset2 = sprintf("SELECT tbl_ppe_relation.Id AS Id_1, tbl_jsa.Id, tbl_ppe_list.PPE, tbl_ppe_relation.PPEId, tbl_ppe_relation.Selected, tbl_ppe_relation.Comments FROM ((tbl_jsa LEFT JOIN tbl_ppe_relation ON tbl_ppe_relation.JSAId=tbl_jsa.Id) LEFT JOIN tbl_ppe_list ON tbl_ppe_list.Id=tbl_ppe_relation.PPEId) WHERE tbl_jsa.Id=%s ", GetSQLValueString($KTColParam1_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $inv) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_Recordset3 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset3 = $_GET['Id'];
}
mysql_select_db($database_inv, $inv);
$query_Recordset3 = sprintf("SELECT * FROM tbl_job_steps WHERE JSAId = %s", GetSQLValueString($colname_Recordset3, "int"));
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/print.css" rel="stylesheet" type="text/css" />
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css" media="print" />
</head>

<body onLoad="window.print(); window.close();">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="images/mail_logo.jpg" width="127" height="105" /></td>
    <td>&nbsp;</td>
    <td align="right" valign="top"><span class="header-jsa">Job Safety Analysis</span><br />
      <span class="top-details"><br />
    <br />
    <?php echo date('d M Y'); ?></span><br /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="top-details"><table border="0" cellpadding="0" cellspacing="0" class="top-details">
      <tr>
        <td class="combo-grey"><strong>Site:</strong></td>
        <td><?php echo $row_Recordset1['Name_1']; ?></td>
      </tr>
      <tr>
        <td class="combo-grey"><strong>Reference:&nbsp;</strong></td>
        <td><?php echo $row_Recordset1['Reference']; ?></td>
        </tr>
      <tr>
        <td><strong>JSA Type</strong>: </td>
        <td><?php echo $row_Recordset1['Risk']; ?></td>
        </tr>
      <tr>
        <td><div style="padding-right:10px"> <strong>Work Activity:</strong></div></td>
        <td><?php echo $row_Recordset1['WorkActivity']; ?></td>
        </tr>
    </table></td>
    <td valign="top">&nbsp;</td>
    <td align="right" class="top-details">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><br />
      <br />
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo-grey">
        <tr>
          <td valign="top" class="top-details"><table width="800" border="1" cellpadding="3" cellspacing="0" class="table">
            <tr>
              <td class="td-header"><strong>&nbsp;Personal  Protective Equipment (PPE)</strong></td>
              <td align="center" class="td-header"><strong>Selected</strong></td>
              <td class="td-header"><strong>&nbsp;Comments</strong></td>
            </tr>
            <?php 
		
		$c = -1;
		
		do { 
		
		$c++;
		?>
            <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onmouseover="this.oldClassName = this.className; this.className='list-over';" onmouseout="this.className = this.oldClassName;">
              <td>&nbsp;<?php echo $row_Recordset2['PPE']; ?></td>
              <td align="center">
			  <?php 
			  
			  if($row_Recordset2['Selected'] == 1){
				  
				  echo 'Yes';
				  
			  } else {
				  
				  echo '&nbsp;';
				  
			  }
			  
			  ?></td>
              <td align="left"> &nbsp;<?php echo $row_Recordset2['Comments']; ?></td>
            </tr>
            <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>
<div style="page-break-before:always">
  <p><br />
    <strong>Job Steps</strong><br />
    <br />
  </p>
</div>
<table width="800" border="1" cellpadding="3" cellspacing="0" class="table">
  <tr>
    <td width="20" class="td-header">&nbsp;</td>
    <td width="33%" class="td-header"><strong>&nbsp;Job Steps</strong></td>
    <td width="33%" class="td-header"><strong> &nbsp;Potential  Hazard</strong></td>
    <td width="33%" class="td-header"><strong>&nbsp;Critical Actions</strong></td>
  </tr>
  <?php 
  $i = 0;
  
  do { 
  
  $i++;
  ?>
  <tr>
    <td width="20" valign="top"><?php echo $i; ?></td>
    <td width="33%" height="200" valign="top">&nbsp;<?php echo stripslashes(nl2br($row_Recordset3['JobSteps'])); ?></td>
    <td width="33%" height="200" valign="top">&nbsp;<?php echo stripslashes(nl2br($row_Recordset3['PotentialHazard'])); ?></td>
    <td width="33%" height="200" valign="top">&nbsp;<?php echo stripslashes(nl2br($row_Recordset3['CriticalActions'])); ?></td>
  </tr>
  <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
</table>
</body>
</html>