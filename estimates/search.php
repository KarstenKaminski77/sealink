<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysql_error());
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

if($_SESSION['kt_login_level'] == 0){

$areaid = $_SESSION['kt_AreaId'];
}

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
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

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

if(isset($_POST['search-field'])){
	
	$search = $_POST['search-field'];
	
	//$count = count(explode("+", $jobdescription));
	//$string = explode("+", $jobdescription);
	
	$where = "WHERE tbl_qs.Description LIKE '%$search%' OR tbl_qs.JobNo LIKE '%$search%' OR tbl_qs.JobDescription LIKE '%$search%' OR tbl_qs.Notes LIKE '%$search%' OR tbl_qs.InternalNotes LIKE '%$search%' OR tbl_qs.QuoteNo LIKE '$search%' OR tbl_sites.Name LIKE '%$search%'";

} else {
	
	$where = "WHERE tbl_qs.QuoteNo=-1";

}

$query_Recordset3 = "
  SELECT
	  tbl_sites. NAME AS Name_1,
	  tbl_sites.AreaId,
	  tbl_qs.QuoteNo,
	  tbl_qs.CompanyId,
	  tbl_qs.SiteId,
	  tbl_qs.`Status`,
	  tbl_companies.`Name`,
	  tbl_qs.Date,
	  tbl_qs.JobDescription,
	  tbl_qs.Id
  FROM
	  (
		  (
			  tbl_qs
			  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_qs.CompanyId
		  )
		  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_qs.SiteId
	  ) $where
  AND tbl_qs.AreaId = '$areaid'
  GROUP BY
	  QuoteNo
  ORDER BY
	  tbl_qs.Id DESC";
	  
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_Recordset5 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$query_Recordset6 = "SELECT * FROM tbl_sites WHERE AreaId = '$areaid'";
$Recordset6 = mysqli_query($con, $query_Recordset6) or die(mysqli_error($con));
$row_Recordset6 = mysqli_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysqli_num_rows($Recordset6);

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
            <li><a href="#">Estimates</a></li>
            <li><a href="#">Quotations</a></li>
            <li><a href="#">Search</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="search.php">
          <input name="search-field" type="text" class="search-top" id="search-field" placeholder="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="left" nowrap="nowrap" class="combo"><div id="list-brdr-supprt" style="display:block; overflow:hidden">
              <table width="100%" border="0" cellpadding="4" cellspacing="1" class="combo">
                <tr>
                  <td width="50" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Quote </strong></td>
                  <td width="150" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Company</strong></td>
                  <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Site Address </strong></td>
                  <td width="75" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Date</strong></td>
                  <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">Photos</td>
                  <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">Location</td>
                  <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">New</td>
                </tr>
                <?php do { ?>
                <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                  <td>
                    <div>
                      <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"> 
					    <?php echo $row_Recordset3['QuoteNo']; ?>
                      </a>
                    </div>
                  </td>
                  <td><div><a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu"><?php echo $row_Recordset3['Name']; ?></a></div></td>
                  <td><div><a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu"> <?php echo $row_Recordset3['Name_1']; ?></a></div></td>
                  <td><a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu"> <?php echo $row_Recordset3['Date']; ?></a></td>
                  <td align="center">
				    <?php
					
					  $quoteno = $row_Recordset3['QuoteNo'];
					  $query = mysqli_query($con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
					  $numrows = mysqli_num_rows($query);
					  
					  if($numrows >= 1){
						  
						  echo "<a href=\"photo_view.php?Id=". $quoteno ."&photos\" class=\"menu2\">(".$numrows.")</a>";
						  
					  } else {
						  
						  echo '('. $numrows .')';
					  }
					  
					?>
                  </td>
                  <td align="center">
                   <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
				  <?php
					if($row_Recordset3['Status'] == "0"){
						
						echo "Awaiting Approval";
						
					} elseif($row_Recordset3['Status'] == "1"){
						
						echo "Outbox";
						
					} elseif($row_Recordset3['Status'] == "2"){
						
						echo "Submitted";
						
					} elseif($row_Recordset3['Status'] == "3"){
						
						echo "Archives";
						
					} elseif($row_Recordset3['Status'] == "4"){
						
						echo "Qued";
					}
					
					?>
                    </a>
                   </td>
                  <td align="center"><a href="quote-save-new.php?quote=<?php echo $row_Recordset3['QuoteNo']; ?>" class="menu">Create New</a></td>
                </tr>
                <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
              </table>
            </div></td>
          </tr>
        </table>
      
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
  mysqli_free_result($query_areas);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
  mysqli_free_result($query_user_menu);
?>