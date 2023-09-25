<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

logout($con);

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

$colname_area = $_SESSION['areaid'];

$query_area = sprintf("SELECT * FROM tbl_areas WHERE Id = '$colname_area'", $colname_area);
$area = mysqli_query($con, $query_area) or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	if(isset($_SESSION['areaid'])){
		
		$areaid = $_SESSION['areaid'];

	} else {
		
		$areaid = 1;
	}
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

$where = "AND tbl_jc.AreaId = ". $areaid ."";

$query_Recordset3 = "
	SELECT
		tbl_sites.`Name` AS Name_1,
		tbl_jc.QuoteNo,
		tbl_jc.CompanyId,
		tbl_jc.JobDescription,
		tbl_jc.SiteId,
		tbl_jc.Date,
		tbl_jc.JobNo,
		tbl_jc.JobId,
		tbl_companies.`Name`
	FROM
		(
			(
				tbl_jc
				LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
			)
			LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
		)
	WHERE
		tbl_jc.`Status` = '3'
	GROUP BY
		tbl_jc.JobId
	ORDER BY
		tbl_jc.Id ASC";
		
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/script.js"></script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
   </head>
   <body>
   
      <!-- Banner -->
      <div id="logo">
         <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
      </div>
      <!-- End Banner -->
      
      <!-- Navigatiopn -->
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Job Cards</a></li>
            <li><a href="#">Final Costing</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="search.php">
          <input name="search" type="text" class="search-top" onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
        <div id="list-border">
          <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr>
              <td class="td-header" width="100" align="left" nowrap><strong>Jobcard </strong>
              </td>
              <td class="td-header" width="150" align="left"><strong>Company</strong>
              </td>
              <td class="td-header" align="left"><strong>Site Address </strong>
              </td>
              <td class="td-header-right" width="40" align="center"><strong>Age</strong>
              </td>
              <td class="td-header-right" width="50" align="center">Photos</td>
            </tr>
            <?php if($totalRows_Recordset3>= 1){ ?>
            <?php do { ?>
            <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td width="100">
                <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                  <?php echo $row_Recordset3[ 'JobNo']; ?>
                </a>
              </td>
              <td width="150">
                <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu">
                  <?php echo $row_Recordset3[ 'Name']; ?>
                </a>
              </td>
              <td>
                <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu">
                  <?php echo $row_Recordset3[ 'Name_1']; ?>
                </a>
              </td>
              <td width="40" align="center">
                <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu">
                  <?php time_schedule($jobid); ?>
                </a>
              </td>
              <td width="40" align="center">
                      <?php 
                      $jobid = $row_Recordset3[ 'JobId']; 
                      
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
                          
                          echo '
                          <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                              <td align="center">
							    <a href="../photo_view_history.php?Id='. $row_Recordset3['JobId'] .'&photos" class="menu" title="View Photos">('.$numrows.')</a>
                              </td>
                            </tr>
                          </table>';
                          
                      } else {
                          
                          echo '';
                          
                      }
                      
                     ?>
              </td>
            </tr>
            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
            <?php } ?>
          </table>
        </div>
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
</body>
</html>
<?php 
  mysqli_close($con); 
  mysqli_free_result($query);
  mysqli_free_result($area);
  mysqli_free_result($Recordset1);
  mysqli_free_result($Recordset2);
  mysqli_free_result($Recordset3);
?>
