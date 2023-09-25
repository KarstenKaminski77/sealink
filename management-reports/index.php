<?php require_once('../Connections/seavest.php'); ?>
<?php
session_start();

if(!isset($_SESSION['kt_login_id']) || empty($_SESSION['kt_login_id'])){
	
	header('Location: index.php');
}

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$query_regions = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC")or die(mysqli_error($con));

$query_managers = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));

$query_coordinators = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));

$query_frequency = mysqli_query($con, "SELECT * FROM tbl_management_report_frequencies")or die(mysqli_error($con));

$userid = $_SESSION['kt_login_id'];

$query_list = mysqli_query($con, "SELECT tbl_users_0.Name AS Name_1, tbl_management_reports.Id, tbl_areas.Area, tbl_management_report_frequencies.Frequency, tbl_management_reports.AreaId, tbl_management_reports.ManagerId, tbl_management_reports.CoordinatorId, tbl_users.Name, tbl_management_report_details.Date, tbl_management_report_details.Old
FROM (((((tbl_management_reports
LEFT JOIN tbl_management_report_frequencies ON tbl_management_report_frequencies.Id=tbl_management_reports.Frequency)
LEFT JOIN tbl_areas ON tbl_areas.Id=tbl_management_reports.AreaId)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId)
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id) WHERE tbl_management_report_details.Old = '0' AND (tbl_management_reports.ManagerId = '$userid' OR tbl_management_reports.CoordinatorId = '$userid')  GROUP BY tbl_management_reports.Id DESC ORDER BY tbl_management_report_details.Id ASC")or die(mysqli_error($con));



// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../functions/functions.php');

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
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
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
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
include('../menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
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
              <td colspan="7" align="center" nowrap>&nbsp;</td>
            </tr>
            <tr class="td-header">
              <td width="50" align="center" nowrap><strong>Region </strong></td>
              <td width="180" align="left"><strong>Manager</strong></td>
              <td width="250" align="left"><strong>Coordinator</strong></td>
              <td width="167" align="left"><strong>Frequency</strong></td>
              <td width="167" align="left">&nbsp;Date</td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
              </tr>
  <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
  <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td width="50" align="center" class="combo"><?php echo $row_list['Area']; ?></td>
              <td width="180" class="combo"><?php echo $row_list['Name']; ?></td>
              <td width="250" class="combo"><?php echo $row_list['Name_1']; ?></td>
              <td width="167" class="combo"><?php echo $row_list['Frequency']; ?></td>
              <td width="167" class="combo"><?php echo $row_list['Date']; ?></td>
              <td align="center"><a href="report-details.php?Id=<?php echo $row_list['Id']; ?>" class="menu"><img src="../images/icons/btn-reports.png" width="25" height="25" border="0"></a></td>
              <td align="center"><?php report_alert($row_list['Id']); ?></td>
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