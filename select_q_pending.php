<?php 
session_start();

require_once('Connections/seavest.php');
require_once('functions/functions.php'); 


$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);


if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	
	$areaid = $_SESSION['areaid'];

} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = "-1";
if (isset($_SESSION['areaid'])) {
  $colname_area = $_SESSION['areaid'];
}
$query_area = sprintf("SELECT * FROM tbl_areas WHERE Id = '$colname_area'", $colname_area);
$area = mysqli_query($con, $query_area) or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if(isset($_SESSION['areaid'])){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

$userid = $_SESSION['kt_login_id'];
$companyid = $row_Recordset3['CompanyId'];

	
$where = "AND tbl_qs.AreaId = ". $areaid .""; 

//$query_Recordset3 = "
//	SELECT 
//	  tbl_sites.Name AS Name_1,
//	  tbl_qs.QuoteNo,
//	  tbl_users.Name AS Username,
//	  tbl_companies.Name,
//	  tbl_companies.Id AS CompanyId,
//	  tbl_qs.Date,
//	  tbl_qs.Time,
//	  tbl_qs.JobDescription,
//	  tbl_qs.Id 
//	FROM
//	  (
//		(
//		  (
//			tbl_qs 
//			LEFT JOIN tbl_companies 
//			  ON tbl_companies.Id = tbl_qs.CompanyId
//		  ) 
//		  LEFT JOIN tbl_sites 
//			ON tbl_sites.Id = tbl_qs.SiteId
//		) 
//		LEFT JOIN tbl_users 
//		  ON tbl_users.Id = tbl_qs.UserId
//	  ) 
//	WHERE STATUS = '0' 
//	  AND tbl_qs.UserId != '0'  
//	GROUP BY QuoteNo 
//	ORDER BY tbl_qs.Date ASC ";

$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_qs.QuoteNo, tbl_users.Name AS Username, tbl_companies.Name, tbl_companies.Id AS CompanyId, tbl_qs.Date, tbl_qs.Time, tbl_qs.JobDescription, tbl_qs.Id FROM (((tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_qs.SiteId) LEFT JOIN tbl_users ON tbl_users.Id=tbl_qs.UserId) WHERE Status = '0' $where GROUP BY QuoteNo ORDER BY tbl_qs.Date ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);

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
    <td width="200" valign="top">
      <?php include('menu.php'); ?>
        </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="200" colspan="4" align="center" bordercolor="#9E9E9E"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
            <tr>
              <td colspan="4" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4"><div style="padding-left:25px">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="left" nowrap class="combo"><table border="0" align="center" cellpadding="3" cellspacing="1" class="combo">
                      <tr class="td-header">
                        <td width="50" align="center" bordercolor="#68A4E6"><strong>Quote </strong></td>
                        <td width="250" bordercolor="#68A4E6"><strong>Company</strong></td>
                        <td width="300" bordercolor="#68A4E6"><strong>Site Address </strong></td>
                        <td width="90" bordercolor="#68A4E6"><strong>Date</strong></td>
                        <td width="75" bordercolor="#68A4E6">Time</td>
                        <td width="80" bordercolor="#68A4E6">Age</td>
                        <td width="80" bordercolor="#68A4E6">User</td>
                        </tr>
					<?php do { ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                           <td width="65" align="center">
						  <a href="quote_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
						  <?php echo $row_Recordset3['QuoteNo']; ?></a>
						  </td>
                          <td width="250">
						  <a href="quote_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu">
						  <?php echo $row_Recordset3['Name']; ?></a>
						  </td>
                          <td width="300">
						  <a href="quote_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu">
						  <?php echo $row_Recordset3['Name_1']; ?></a>
						  </td>
                          <td width="90">
                            <a href="quote_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu">
                              <?php echo $row_Recordset3['Date']; ?></a>
                          </td>
                          <td width="75"><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"><?php echo $row_Recordset3['Time']; ?></a></td>
                          <td width="80" nowrap><?php time_schedule_quotes($row_Recordset3['QuoteNo']); ?></td>
                          <td width="80" nowrap><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"><?php echo $row_Recordset3['Username']; ?></a></td>
                          </tr>
                          <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                    </table></td>
                    </tr>
                </table>
              </div>
                </td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
?>
