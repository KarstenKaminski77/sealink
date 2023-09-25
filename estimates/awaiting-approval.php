<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$user_id = $_COOKIE['userid'];

logout($con);

if($_SESSION['kt_login_level'] >= 1){
	
	if(isset($_SESSION['areaid'])){
		
		$areaid = $_SESSION['areaid'];

	} else {
		
		$_SESSION['areaid'] = '1';
		
		$areaid = $_SESSION['areaid'];
		
	}
}

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

system_select();

$sql_where = system_parameters('tbl_qs'); 


$Recordset3 = "
	SELECT 
	  tbl_qs.AreaId,
	  tbl_sites.Name AS Name_1,
	  tbl_qs.UserId,
	  tbl_qs.UsersName AS Users_Name,
	  tbl_users.Name AS Username,
	  tbl_qs.QuoteNo,
	  tbl_companies.Name,
	  tbl_companies.Id AS CompanyId,
	  tbl_qs.Date,
	  tbl_qs.Time,
	  tbl_qs.JobDescription,
	  tbl_qs.Id,
	  tbl_qs.Rejected 
	FROM
	  (
		(
		  (
			tbl_qs 
			LEFT JOIN tbl_companies 
			  ON tbl_companies.Id = tbl_qs.CompanyId
		  ) 
		  LEFT JOIN tbl_sites 
			ON tbl_sites.Id = tbl_qs.SiteId
		) 
		LEFT JOIN tbl_users 
		  ON tbl_users.Id = tbl_qs.UserId
	  ) 
	WHERE tbl_qs.Status = '0'
	$sql_where
	GROUP BY QuoteNo 
	ORDER BY tbl_qs.Date ASC ";

$Recordset3 = mysqli_query($con, $Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);

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
        <?php system_dd($con); ?>
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
            <li><a href="#">Awaiting Approval</a></li>
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
        <div id="list-border">
          <table border="0" cellpadding="3" cellspacing="1" class="combo">
            <tr>
              <td width="50" align="center" class="td-header"><strong>Quote </strong></td>
              <td width="250" class="td-header"><strong>Company</strong></td>
              <td width="300" class="td-header"><strong>Site Address </strong></td>
              <td width="90" class="td-header"><strong>Date</strong></td>
              <td width="75" class="td-header">Time</td>
              <td width="80" class="td-header">Age</td>
              <td width="80" class="td-header">User</td>
            </tr>
            <?php 
					do { 
					$style = '';
					
					if($row_Recordset3['Rejected'] == 1){
						
						$style = 'style="color:#FF0000 !important; font-weight:bold;"';
					}
					?>
            <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;" <?php echo $style; ?>>
              <td width="65" align="center">
                <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>" <?php echo $style; ?>>
				  <?php echo $row_Recordset3['QuoteNo']; ?>
                </a>
              </td>
              <td width="250">
                <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" <?php echo $style; ?>>
				  <?php echo $row_Recordset3['Name']; ?>
                </a>
              </td>
              <td width="300">
                <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" <?php echo $style; ?>>
				  <?php echo $row_Recordset3['Name_1']; ?>
                </a>
              </td>
              <td width="90">
                <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" <?php echo $style; ?>>
				  <?php echo $row_Recordset3['Date']; ?>
                </a>
              </td>
              <td width="75">
                <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" <?php echo $style; ?>>
				  <?php echo $row_Recordset3['Time']; ?>
                </a>
              </td>
              <td width="80" nowrap="nowrap">
                <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" <?php echo $style; ?>>
                <?php 
						  $quoteno = $row_Recordset3['QuoteNo'];
						  time_schedule_quotes($quoteno); ?>
                </a>
              </td>
              <td width="80" nowrap="nowrap">
                <a href="quote-calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;job" class="menu" <?php echo $style; ?>>
				  <?php
                  
                  if(!empty($row_Recordset3['Username'])){
                      
                      echo $row_Recordset3['Username']; 
                      
                  } else {
                      
                      if(!empty($row_Recordset3['Users_Name'])){
                          
                          echo $row_Recordset3['Users_Name'];
                          
                      } else {
                          
                          echo 'User Id '. $row_Recordset3['UserId'];
                      }
                  }
                  
                  ?>
                </a>
              </td>
            </tr>
            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
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
  mysqli_free_result($query_areas);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
  mysqli_free_result($query_user_menu);
?>