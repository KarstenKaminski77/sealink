<?php require_once('../../Connections/seavest.php'); ?>
<?php

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$query_regions = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC")or die(mysqli_error($con));

$query_managers = mysqli_query($con, "SELECT * FROM tbl_users ORDER BY Name ASC")or die(mysqli_error($con));

$query_coordinators = mysqli_query($con, "SELECT * FROM tbl_users ORDER BY Name ASC")or die(mysqli_error($con));

$query_frequency = mysqli_query($con, "SELECT * FROM tbl_management_report_frequencies")or die(mysqli_error($con));

$userid = $_GET['Edit'];

if(isset($_POST)){
	
	$area = $_POST['area'];
	$manager = $_POST['manager'];
	$coordinator = $_POST['coordinator'];
	$frequency = $_POST['frequency'];
}

if(isset($_GET['Deactivate'])){
	
	$id = $_GET['Deactivate'];
	
	mysqli_query($con, "UPDATE tbl_management_reports SET Active = '0' WHERE Id = '$id'")or die(mysqli_error($con));
}

if(isset($_GET['Activate'])){
	
	$id = $_GET['Activate'];
	
	mysqli_query($con, "UPDATE tbl_management_reports SET Active = '1' WHERE Id = '$id'")or die(mysqli_error($con));
}

if(isset($_GET['Delete'])){
	
	$delete = $_GET['Delete'];
	
	mysqli_query($con, "DELETE FROM tbl_management_reports WHERE Id = '$delete'")or die(mysqli_error($con));
	
	header('Location: index.php');
	
}

if(!empty($_POST['insert'])){
	
	mysqli_query($con, "INSERT INTO tbl_management_reports (AreaId,ManagerId,CoordinatorId,Frequency) VALUES ('$area','$manager','$coordinator','$frequency')")or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_management_reports ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$report_id = $row['Id'];
	$date = date('Y-m-d');
	
	mysqli_query($con, "INSERT INTO tbl_management_report_details (ReportId,Level,Date,Priority,Subject,Report,Old) VALUES ('$report_id','1','$date','1','New Management Report Logged','New Management Report Logged','0')")or die(mysqli_error($con));
	
}

if(!empty($_POST['update'])){
	
	$id = $_GET['Edit'];
	
	mysqli_query($con, "UPDATE tbl_management_reports SET AreaId = '$area', ManagerId = '$manager', CoordinatorId = '$coordinator', Frequency = '$frequency' WHERE Id = '$id'")or die(mysqli_error($con));
	
}

$query_form = mysqli_query($con, "SELECT tbl_technicians.Id AS Id_2, tbl_technicians_0.Id AS Id_1, tbl_technicians_0.Name AS Name_1, tbl_management_reports.Id, tbl_areas.Area, tbl_technicians.Name, tbl_management_report_frequencies.Frequency, tbl_management_reports.AreaId, tbl_management_reports.ManagerId, tbl_management_reports.CoordinatorId, tbl_management_reports.Frequency
						   FROM ((((tbl_management_reports
						    LEFT JOIN tbl_management_report_frequencies ON tbl_management_report_frequencies.Id=tbl_management_reports.Frequency)
						     LEFT JOIN tbl_areas ON tbl_areas.Id=tbl_management_reports.AreaId)
						      LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_management_reports.ManagerId)
							   LEFT JOIN tbl_technicians AS tbl_technicians_0 ON tbl_technicians_0.Id=tbl_management_reports.CoordinatorId) WHERE tbl_management_reports.Id = '$userid'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT tbl_users_0.Name AS Name_1, tbl_management_reports.Id, tbl_areas.Area, tbl_management_report_frequencies.Frequency, tbl_management_reports.AreaId, tbl_management_reports.ManagerId, tbl_management_reports.CoordinatorId, tbl_management_reports.Active, tbl_users.Name
						   FROM ((((tbl_management_reports
							LEFT JOIN tbl_management_report_frequencies ON tbl_management_report_frequencies.Id=tbl_management_reports.Frequency)
							  LEFT JOIN tbl_areas ON tbl_areas.Id=tbl_management_reports.AreaId)
								 LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
								   LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId)")or die(mysqli_error($con));



// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');

require_once('../../functions/functions.php');

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

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_companies.Name AS Name_1, tbl_companies.Id AS CompanyId, tbl_jc.JobNo, tbl_jc.JobDescription, tbl_jc.Id, tbl_jc.InvoiceNo,  STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort, tbl_jc.JobId, tbl_jc.InvoiceQ, tbl_sites.Name, tbl_sites.FirstName, tbl_sites.LastName, tbl_sent_invoices.PDF FROM (((tbl_jc LEFT JOIN tbl_sent_invoices ON tbl_sent_invoices.JobId=tbl_jc.JobId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE Status = '11' AND tbl_jc.CompanyId != '0' AND tbl_jc.InvoiceNo != '0' GROUP BY tbl_jc.JobId ORDER BY date_for_sort ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
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
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../../styles/layout.css" rel="stylesheet" type="text/css">
<script LANGUAGE="JavaScript">
<!--
<!--
// Nannette Thacker http://www.shiningstar.net
function confirmSubmit() 
{
var agree=confirm("Are you sure you wish to continue?");
if (agree)
	return true ;
else
	return false ;
}
// -->
//-->
</script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../../menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">
        <form action="" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
          <table width="705" border="0" cellpadding="3" cellspacing="1">
            <tr>
  <td colspan="7" nowrap>&nbsp;</td>
              </tr>
            <tr>
              <td colspan="7" nowrap>
              <select name="area" class="tarea-100per" id="area" style="padding-right:0px">
                <option>Select a region...</option>
                <?php while($row_regions = mysqli_fetch_array($query_regions)){ ?>
                <option value="<?php echo $row_regions['Id']; ?>" <?php if($row_regions['Id'] == $row_form['AreaId']){ ?>selected="selected"<?php } ?> ><?php echo $row_regions['Area']; ?></option>
                <?php } ?>
              </select>
              </td>
            </tr>
            <tr>
              <td colspan="7" nowrap>
              <select name="manager" class="tarea-100per" id="manager" style="padding-right:0px">
                <option>Select a report manager...</option>
                <?php while($row_managers = mysqli_fetch_array($query_managers)){ ?>
                <option value="<?php echo $row_managers['Id']; ?>" <?php if($row_managers['Id'] == $row_form['ManagerId']){ ?>selected="selected"<?php } ?>><?php echo $row_managers['Name']; ?></option>
                <?php } ?>
              </select>
              </td>
            </tr>
            <tr>
              <td colspan="7" nowrap>
              <select name="coordinator" class="tarea-100per" id="coordinator" style="padding-right:0px">
                <option>Select a reporter...</option>
                <?php while($row_coordinators = mysqli_fetch_array($query_coordinators)){ ?>
                <option value="<?php echo $row_coordinators['Id']; ?>" <?php if($row_coordinators['Id'] == $row_form['CoordinatorId']){ ?>selected="selected"<?php } ?>><?php echo $row_coordinators['Name']; ?></option>
                <?php } ?>
              </select>
              </td>
            </tr>
            <tr>
              <td colspan="7" align="right" nowrap>
              <select name="frequency" class="tarea-100per" id="frequency" style="padding-right:0px">
                <option>Select a frequency...</option>
                <?php while($row_frequency = mysqli_fetch_array($query_frequency)){ ?>
                <option value="<?php echo $row_frequency['Id']; ?>" <?php if($row_frequency['Id'] == $row_form['Frequency']){ ?>selected="selected"<?php } ?>><?php echo $row_frequency['Frequency']; ?></option>
                <?php } ?>
              </select>
              </td>
            </tr>
            <tr>
              <td colspan="7" align="right" nowrap>
              <?php if(isset($_GET['Edit'])){ ?>
              <input name="update" type="submit" class="btn-blue-generic" id="update" value="Update"> 
              <?php } else { ?>               
              <input name="insert" type="submit" class="btn-blue-generic" id="insert" value="Insert">
              <?php } ?>
              </td>
            </tr>
            <tr>
              <td colspan="7" align="center" nowrap>&nbsp;</td>
              </tr>
            <tr class="td-header">
              <td width="50" align="center" nowrap><strong>Region </strong></td>
              <td width="180" align="left"><strong>Report Manager</strong></td>
              <td width="250" align="left"><strong>Reporter</strong></td>
              <td width="167" align="left"><strong>Frequency</strong></td>
              <td width="40" align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              </tr>
  <?php while($row_list = mysqli_fetch_array($query_list)){ 

$jobid = $row_Recordset3['JobId'];

?><tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td width="50" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_list['Area']; ?></a></td>
              <td width="180" class="combo"><?php echo $row_list['Name']; ?></td>
              <td width="250" class="combo"><?php echo $row_list['Name_1']; ?></td>
              <td width="167" class="combo"><?php echo $row_list['Frequency']; ?></td>
              <td width="40" align="center">
                <a href="index.php?Delete=<?php echo $row_list['Id']; ?>">
                <img src="../../images/icons/btn-delete.png" width="25" height="25" border="0">
                </a></td>
              <td align="center">
                <a href="index.php?Edit=<?php echo $row_list['Id']; ?>">
                <img title="Edit" src="../../images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
              <td align="center">
              <?php if($row_list['Active'] == 1){ ?>
              <a href="index.php?Deactivate=<?php echo $row_list['Id']; ?>"><img src="../../images/icons/power-on.png" width="25" height="25" border="0" title="Deactivate"></a>
              <?php } else { ?>
              <a href="index.php?Activate=<?php echo $row_list['Id']; ?>"><img src="../../images/icons/power-off.png" width="25" height="25" border="0" title="Activate"></a>
              <?php } ?>
              </td>
              </tr>
            <?php } ?>
            </table>
        </form>
        <br><br>
          <div class="KT_bottomnav" align="center">
            <div class="combo"></div>
          </div></td></tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>