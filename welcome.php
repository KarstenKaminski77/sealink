<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();

set_time_limit (1);

require_once('Connections/seavest.php');
require_once('functions/functions.php');

logout($con);

$userid = $_SESSION['kt_login_id']; 

$query = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$userid'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$name = $row['Name'];
$date = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];

mysqli_query($con, "INSERT INTO tbl_login (Name, Date, Location) VALUES ('$name','$date','$ip')")or die(mysqli_error($con));

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$userid = $_SESSION['kt_login_id'];
	
$query = mysqli_query($con, "
  SELECT
	  tbl_management_report_details.Date,
	  tbl_management_report_details.Id AS `Row`,
	  tbl_management_reports.Id,
	  tbl_management_reports.CoordinatorId,
	  tbl_management_reports.Frequency,
	  tbl_management_reports.Active,
	  tbl_management_report_details.Old
  FROM
	  (
		  tbl_management_reports
		  LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId = tbl_management_reports.Id
	  )
  WHERE
	  tbl_management_reports.CoordinatorId = '$userid'
  AND tbl_management_report_details.Old = '0'
  GROUP BY
	  tbl_management_report_details.ReportId")or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	setcookie('userid', $_SESSION['kt_login_id'], time()+3600, '/', '.seavest.co.za');
	
	$first_monday = date('Y-m-d', strtotime('First Monday of '.date('F o')));
	$first_day_week = date('Y-m-d', strtotime('Last Monday', time()));
	$today = date('Y-m-d H:i:s');
	if($row['Active'] == '1'){
		
		// Daily
		if($row['Frequency'] == 1){
			
			$report_date = date('Y-m-d 16:00:00', strtotime($row['Date'] . ' + 1 day')); 
			
			if($today >= $report_date){ 
				
				if(date('D', strtotime($row['Date'])) == 'Thu' && date('D') == 'Mon'){
					
					$friday = strtotime('last Friday');
					$date = date('Y-m-d', $friday);
					
					header('Location: management-reports/compulsory/send-reports.php?Date='.$date );
					exit();
				
				} else {
					
					header('Location: management-reports/compulsory/send-reports.php');
				    exit();
				}
			}
		}
		
		
		// Weekly
		if($row['Frequency'] == 2){
			
			if($first_day_week > $row['Date']){
				
				header('Location: http://www.seavest.co.za/inv/management-reports/compulsory/send-reports.php');
				exit();
			}
		}
		
		// Monthly
		if($row['Frequency'] == 3){
			
			if($first_monday > $row['Date']){
				
				header('Location: http://www.seavest.co.za/inv/management-reports/compulsory/send-reports.php');
				exit();
			}
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <link rel="icon" href="favicon.ico" type="image/x-icon" />
      <title>Seavest Asset Management</title>
      
      <link href="css/layout.css" rel="stylesheet" type="text/css" />
      <link href="css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="menu/script.js"></script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>

      <script>
      	document.addEventListener('DOMContentLoaded', function(event) {
		  //the event occurred
			// Get the modal
			var modal = document.getElementById("myModal");

			// Get the <span> element that closes the modal
			var span = document.getElementById("close");

			// When the user clicks on <span> (x), close the modal
			span.onclick = function() {
			  modal.style.display = "none";
			}

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			  if (event.target == modal) {
			    modal.style.display = "none";
			  }
			}
		})
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
      <?php include('menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Welcome</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search --><!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper" align="center">
        <img src="images/accreditations.png" width="665" height="280">
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="support/index.php"><img src="images/sealinklogo.png" width="95" height="24" /></a></div>
      <!-- End Footer -->
      

<?php
$jc = mysqli_query($con, "SELECT Id, JobId, `JobNo`, FacilityFirstContact FROM tbl_jc WHERE JobId IN (SELECT JobId FROM tbl_jc WHERE `Status` = 1 AND FacilityFirstContact IS NULL) ORDER BY FacilityFirstContact ASC") or die(mysqli_error($con));
?>
<?php
/*
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" id="close">&times;</span>
      <h2>First Dealer Contact</h2>
    </div>
    <div class="modal-body">
      <p>Please note that the following Job Cards are missing first dealer contact;</p>
      <?php
      $data = [];
      while ($jcData = mysqli_fetch_array($jc)) { 
      	if (empty($jcData['FacilityFirstContact']) || $jcData['FacilityFirstContact'] == '') {
      		$data[$jcData['JobId']] = [
      			'JobNo' => $jcData['JobNo'],
      		];
      	} else {
      		unset($data[$jcData['JobId']]);
      	}
      }
      foreach ($data as $key => $value) {
      ?>
	      <p>
	      	<a href="/inv/job-cards/qued-details.php?menu=&Id=<?= $key; ?>&job">
	      		<?= ($value['JobNo']) ? 'Job No - ' . $value['JobNo'] : 'Job Id - ' . $key; ?>	      			
      		</a>
      	</p>
	  <?php } ?>
    </div>
    <div class="modal-footer">
    </div>
  </div>

</div>
*/ ?>
</body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($query);
  mysqli_free_result($Recordset1);
  mysqli_free_result($Recordset2);
?>
