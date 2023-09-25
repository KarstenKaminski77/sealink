<?php 
require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

if(isset($_GET['Status'])){
	
	$status = $_GET['Status'];
	$jobid = $_GET['Id'];
	$today = date('Y-m-d');
	
	mysqli_query($con, "UPDATE tbl_jc SET Status = '$status', Days = '$today' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	header('Location: total-costing.php?Success');
}

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset3 = "
SELECT
	tbl_companies. NAME AS Name_1,
	tbl_companies.Id AS CompanyId,
	tbl_jc.JobNo,
	tbl_jc.JobDescription,
	tbl_jc.Id,
	tbl_jc.Days,
	tbl_jc.InvoiceNo,
	STR_TO_DATE(
		tbl_jc.InvoiceDate,
		'%d %M %Y'
	) AS date_for_sort,
	tbl_jc.JobId,
	tbl_jc.InvoiceQ,
	tbl_jc.Status,
	tbl_sites.Name,
	tbl_sites.FirstName,
	tbl_sites.LastName,
	tbl_sent_invoices.PDF
FROM
	(
		(
			(
				tbl_jc
				LEFT JOIN tbl_sent_invoices ON tbl_sent_invoices.JobId = tbl_jc.JobId
			)
			LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
		)
		LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
	)
WHERE
	STATUS = '18'
AND tbl_jc.CompanyId != '0'
AND tbl_jc.InvoiceNo != '0'
GROUP BY
	tbl_jc.JobId
ORDER BY
	tbl_jc.Days ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error());
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_rs_totals = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_rs_totals = $_SESSION['kt_login_id'];
}
$query_rs_totals = "SELECT * FROM tbl_menu_relation WHERE UserId = '$colname_rs_totals' AND MenuId = '47'";
$rs_totals = mysqli_query($con, $query_rs_totals) or die(mysqli_error($con));
$row_rs_totals = mysqli_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysqli_num_rows($rs_totals);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
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
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
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

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
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
include('menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">
        <form action="PHPExcel/order-numbers.php" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
          <table width="100%" border="0" cellpadding="3" cellspacing="1">
          <?php if(isset($_GET['Success'])){ ?>
            <tr class="td-row">
              <td colspan="7" nowrap><div id="banner-success-mail">Job Card&nbsp;Successfully Moved</div></td>
            </tr>
            <tr class="td-row">
              <td colspan="7" align="center" nowrap>&nbsp;</td>
              </tr>
            <?php } ?>
            <tr class="td-row">
              <td width="50" align="center" nowrap class="td-header"><strong>In No. </strong></td>
              <td width="180" align="left" class="td-header"><strong>Company</strong></td>
              <td width="260" align="left" class="td-header"><strong>Site Address </strong></td>
              <td width="50" align="center" class="td-header"><strong>Age</strong></td>
              <td width="80" align="left" class="td-header">Status</td>
              <td width="30" align="center" class="td-header">&nbsp;</td>
              <td width="30" align="center" class="td-header">&nbsp;</td>
              </tr>
  <?php do { 

$jobid = $row_Recordset3['JobId'];

?><tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td width="50" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
              <td width="180" class="combo"><?php echo $row_Recordset3['Name_1']; ?></td>
              <td class="combo"><?php echo $row_Recordset3['Name']; ?></td>
              <td align="center" class="combo"><?php time_schedule($jobid); ?></td>
              <td class="combo">
              <select name="status" class="tarea-100per" id="status" onChange="MM_jumpMenu('parent',this,0)">
                <?php $query_status = "SELECT * FROM tbl_status WHERE Id <= '3' OR Id = '17' OR Id = '18' OR Id = '19'"; ?>
                <?php $query_status = mysqli_query($con, $query_status)or die(mysqli_error($con)); ?>
                <?php while($row_status = mysqli_fetch_array($query_status)){ ?>
                <?php
                $checked = '';
                if($row_Recordset3['Status'] == $row_status['Id']){
					
					$checked = 'selected="selected"';
				}
				?>
                <option value="total-costing.php?Status=<?php echo $row_status['Id']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>" <?php echo $checked; ?>><?php echo $row_status['Status']; ?></option>
                <?php } ?>
              </select></td>
              <td width="30" align="center"><a href="jc_calc.php?Id=<?php echo $jobid; ?>"><img title="View Job Card" src="images/icons/jc.png" width="25" height="25" border="0"></a></td>
              <td width="30" align="center">
                <a href="quote_calc.php?Costing=<?php echo $row_Recordset3['JobId']; ?>" >
                  <img title="Quote" src="images/icons/qs.png" width="25" height="25" border="0"></a></td>
              </tr>
            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
            <?php unset($_SESSION['jobids']); ?>			
            <tr>
            <td colspan="9" align="right" class="td-row">&nbsp;</td>
            </tr>
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