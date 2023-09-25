<?php 
session_start();
require_once('Connections/seavest.php');
require_once('functions/functions.php');

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

$where = "AND tbl_jc.AreaId = ". $areaid ."";

$query_Recordset3 = "
	SELECT
		tbl_jc.Id,
		tbl_jc.Days,
		tbl_jc.JobId,
		tbl_jc.JobNo,
		tbl_sites.`Name` AS Name_1,
		tbl_companies.`Name`
	FROM
		tbl_companies
	INNER JOIN tbl_jc ON tbl_jc.CompanyId = tbl_companies.Id
	INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
	WHERE
		tbl_jc.`Status` = 1
	AND tbl_jc.`Comment` = 1
	AND tbl_jc.AreaId = '$areaid'";
	
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php include('menu.php'); ?>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            <tr>
              <td colspan="3" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4">
              <div style="margin-left:30px">
                <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                  <tr class="td-header">
                    <td width="111" align="left" nowrap><strong>Jobcard </strong></td>
                    <td width="175" align="left"><strong>Company</strong></td>
                    <td align="left"><strong>Site Address </strong></td>
                    <td width="45" align="center">PDF</td>
                    <td width="45" align="center"><strong>Age</strong></td>
                    <td width="40" align="left">Photos</td>
                    </tr>
                  
                  <?php  if($totalRows_Recordset3 >= 1){ ?>
                  <?php  do { ?>
                  <?php $jobid = $row_Recordset3['JobId']; ?>
                  
                  <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                    <td width="111" align="left" nowrap><a href="jc_calc.php?menu=<?php  echo $_GET['menu']; ?>&Id=<?php  echo $row_Recordset3['JobId']; ?>&job" title="<?php  echo $row_Recordset3['JobDescription']; ?>" class="menu"><?php  echo $row_Recordset3['JobNo']; ?></a></td>
                    <td width="175" align="left" nowrap><a href="jc_calc.php?menu=<?php  echo $_GET['menu']; ?>&Id=<?php  echo $row_Recordset3['JobId']; ?>&job" class="menu"><?php  echo $row_Recordset3['Name']; ?></a></td>
                    <td align="left" nowrap><a href="jc_calc.php?menu=<?php  echo $_GET['menu']; ?>&Id=<?php  echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php  echo $row_Recordset3['CommentText']; ?>"><?php  echo $row_Recordset3['Name_1']; ?></a></td>
                    <td width="45" align="center" nowrap>
                      <?php  if($row_Recordset3['JobcardPDF'] != NULL){ ?>
                      <a href="jc-pdf/<?php  echo $row_Recordset3['JobcardPDF']; ?>" target="_blank"><img src="images/icons/btn-view.png" width="25" height="25" border="0"></a>
                      <?php  } ?>
                      </td>
                    <td width="45" align="center" nowrap><a href="jc_calc.php?Id=<?php  echo $row_Recordset3['JobId']; ?>&job" class="menu"><?php  time_schedule($jobid); ?></a></td>
                    <td width="45" align="center">
                      <?php
							  $jobid = $row_Recordset3['JobId'];
							  
							  $query = "
								  SELECT
									  tbl_history_relation.PhotoId,
									  tbl_history_photos.Photo,
									  tbl_history_relation.JobId
								  FROM
									  (
										  tbl_history_relation
										  LEFT JOIN tbl_history_photos ON tbl_history_photos.Id = tbl_history_relation.PhotoId
									  )
								  WHERE
									  tbl_history_relation.JobId = '$jobid'";
									  
							  $query = mysqli_query($con, $query)or die(mysqli_error());
							  $numrows = mysqli_num_rows($query);
							  
							  if($numrows >= 1){
								  
								  echo "<table cellpadding=\"0\" cellspacing=\"0\">
										  <tr>
											<td>
											  <a href=\"photo_view_history.php?Id=". $jobid ."&photos\" class=\"menu2\">
											  <img src=\"images/icons/btn-tick.png\" width=\"25\" height=\"25\" border=\"0\">
											</a>
											</td>
											<td><a href=\"photo_view_history.php?Id=". $jobid ."&photos\" class=\"menu2\">(".$numrows.")</a></td>
										  </tr>
										</table>";
							  } else {
								  
								  echo '<div class="icon-circle"><i class="fa fa-remove line-height"></i></div>';
							  }
			  
							  ?>						  
                      </td>
                    </tr>
                  <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                  <?php  } ?>
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
  mysqli_close($con);
  mysqli_free_result($area);
  mysqli_free_result($query);
  mysqli_free_result($Recordset3);
?>
