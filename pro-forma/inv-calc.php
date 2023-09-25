<?php 
session_start();
require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

$today = date('Y-m-j');
$jobid = $_GET['Id'];

if(isset($_GET['Search'])){
	
	$form_param = '&Search';
}

$query_jc = "
	SELECT
		tbl_jc.*,
		tbl_companies.`Name` AS CompanyName
	FROM
		tbl_jc
	INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
	WHERE
		tbl_jc.JobId = '$jobid'";

$query_jc = mysqli_query($con, $query_jc)or die(mysqli_error($con));
$row_jc = mysqli_fetch_array($query_jc);

$jobno = $row_jc['JobNo'];

// Add & Delete Rows
if(isset($_GET['AddLabour']) || isset($_GET['AddMaterial']) || isset($_GET['AddTransport']) || isset($_GET['Delete'])){
	
	$form_data = array(
	
		'JobId' => $jobid,
		'JobNo' => $row_jc['JobNo'],
	);
	
	// Labour
	if(isset($_GET['AddLabour'])){
		
		$form_data['Labour'] = '1';
		$form_data['Unit'] = 'hours';
		$form_data['CompanyId'] = $row['CompanyId'];
		$form_data['SiteId'] = $row['SiteId'];
		$form_data['Status'] = '1';

		dbInsert('tbl_jc', $form_data, $con);
		header('Location: inv-calc.php?Id='. $_GET['Id'] . $form_param .'#Labour');
		
		exit();
	}
	
	// Material
	if(isset($_GET['AddMaterial'])){
		
		$form_data['Material'] = '1';
		$form_data['Unit'] = 'each';
		$form_data['CompanyId'] = $row['CompanyId'];
		$form_data['SiteId'] = $row['SiteId'];
		$form_data['Status'] = '1';

		dbInsert('tbl_jc', $form_data, $con);
		header('Location: inv-calc.php?Id='. $_GET['Id'] . $form_param .'#Material');
		
		exit();
	}
	
	// Transport
	if(isset($_GET['AddTransport'])){
		
		dbInsert('tbl_travel', $form_data, $con);
		header('Location: inv-calc.php?Id='. $_GET['Id'] . $form_param .'#Transport');
		
		exit();
	}
	
	// Delete Labour
	if(isset($_GET['Delete'])){
		
		$delete = $_GET['Delete'];
	
		mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$delete'") or die(mysqli_error($con));
		header('Location: inv-calc.php?Id='. $_GET['Id'] . $form_param .'#Delete');
		
		exit();
	}
}
// End Add & Delete Rows

if(isset($_POST['update'])){
	
    // Update Labour
    for ($i=0;$i<count($_POST['id_l']);$i++){
		
		$labour_data = array(
	
			'Description' => $_POST['labour'][$i],
			'Unit' => $_POST['unit_l'][$i],
			'Qty' => $_POST['qty_l'][$i],
			'Price' => $_POST['price_l'][$i],
		);
		
		$labour_data['Total1'] = $_POST['price_l'][$i] * $_POST['qty_l'][$i];
		
		dbUpdate('tbl_jc', $labour_data, $where_clause="Id = '". $_POST['id_l'][$i] ."'",$con);
	}

    // Update Material
    for ($i=0;$i<count($_POST['id_m']);$i++){
		
		$material_data = array(
	
			'Description' => $_POST['material'][$i],
			'Unit' => $_POST['unit_m'][$i],
			'Qty' => $_POST['qty_m'][$i],
			'Price' => $_POST['price_m'][$i],
		);
		
		$material_data['Total1'] = $_POST['price_m'][$i] * $_POST['qty_m'][$i];
		
		dbUpdate('tbl_jc', $material_data, $where_clause="Id = '". $_POST['id_m'][$i] ."'",$con);
	}

	
	// General Update
	$jc_data = array(
		
		'JobNo' => $_POST['jobno'],
		'ContractorId' => $_POST['contractor'],
		'Reference' => $_POST['reference'],
		'RefNo' => $_POST['refno'],
		'Date2' => $_POST['date2'],
		'JobDescription' => addslashes($_POST['service']),
		'JobDescriptionOperator' => $_COOKIE['name'],
		'JobDescriptionDate' => date('Y-m-d'),
		'CustomerFeedBack' => $_POST['CFB'],
		'Progress' => $_POST['progress'],
		'Status' => $_POST['status']
	);
	
	if(!empty($_POST['Date1'])){
		
		$jc_data['Date1'] = $_POST['date1'];
	}
	
	$sla_data = array(
		
		'SlaEnd' => $_POST['date2'],
	);
	
	// Upload PDF Job Card
	$target_path = "../jc-pdf/";
	$target_path = $target_path.basename($_FILES['pdf']['name']);
	
	if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)) {
	
		$jc_data['JobcardPDF'] = $_FILES['pdf']['name'];
	}
	
	dbUpdate('tbl_jc', $jc_data, $where_clause="JobId = '". $_GET['Id'] ."'",$con);
	dbUpdate('tbl_sla_history', $sla_data, $where_clause="JobId = '". $_GET['Id'] ."'",$con);
	// End General Update
	
	// Import Quote
	if(isset($_POST['import'])) {
	
		$quoteno = $_POST['quote'];
		
		quote_import($jobid, $quoteno);
	
	}
	// End Import Quote
	
    // Allocate technicians to actual history
    if(isset($_POST['tech'])) {

        mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'") or die(mysqli_error($con));

        $technician_id = array();

        for ($i=0;$i<count($_POST['tech']);$i++) {

            $tech_id = $_POST['tech'][$i];

            array_push($technician_id, $tech_id);

			$history_data = array(
			
				'Site' => addslashes($row_jc['SiteName']),
				'JobNo' => $row_jc['JobNo'],
				'Date' => $date = date('Y-m-d'),
				'JobId' => $_GET['Id'],
				'TechnicianId' => $_POST['tech'][$i],
			);
			
			dbInsert('tbl_history_alerts', $history_data, $con);
		}		
	}
	// End Allocate Technicians
	
	// Actual History
	$actual_history = $_POST['comment'];
	
	if(!empty($_POST['comment'])) {
		
		$history_data = array(
		
			'JobId' => $_GET['Id'],
			'TechnicianId' => $_SESSION['kt_login_id'],
			'Date' => date('Y-m-d H:i:s'),
			'Comments' => $_POST['comment'],
		);
		
		dbInsert('tbl_actual_history', $history_data, $con);
	
	}
	// End Actual History
	
	// Image Gallery
	$target_path = "../images/history/";
	$target_path = $target_path.basename($_FILES['photo']['name']);
	
	if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
	
		$file_attachment = $_FILES['photo']['name'];
		$ext = explode(".", $file_attachment);

		$image = rename('../images/history/'.$file_attachment, '../images/history/'. $_GET['Id']. '-'.date('H-i-s'). '.'. $ext[1]);
		$image_name = $_GET['Id'] .'-'. date('H-i-s') .'.'. $ext[1];
	
		mysqli_query($con, "INSERT INTO tbl_history_photos (Photo) VALUES ('$image_name')") or die(mysqli_error($con));
	
		$query = mysqli_query($con, "SELECT * FROM tbl_history_photos ORDER BY Id DESC") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
	
		$photoid = $row['Id'];
	
		mysqli_query($con, "INSERT INTO tbl_history_relation (JobId,PhotoId) VALUES ('$jobid','$photoid')") or die(mysqli_error($con));
	}
	// End Image Gallery
	
		
	// Dealers Feedback	
	if(!empty($_POST['feedback'])) {
	
		$feedback = $row_jc['FeedBack'];
		$feedback .= ' '. $_POST['feedback'];
		$fb_date = $_POST['fb_date'];
	
		$loginid = $_SESSION['kt_login_id'];
	
		$fbtech = $_COOKIE['name'];
	
		mysqli_query($con, "UPDATE tbl_jc SET FeedBack = '$feedback', FeedBackTech = '$fbtech', FeedBackDate = '$fb_date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
	
	}
	// End Dealers Feedback
	
	// Move To In Progress
	if(isset($_POST['in-progress'])){
		
		if(empty($_POST['service'])){
			
			header('Location: inv-calc.php?Id='. $_GET['Id'] .'&Service');
			
			exit();
	
		} else {
			
			$alert_data = array(
			
				'JobNo' => $row_jc['JobNo'],
				'Date' => date('Y-m-d'),
				'TechnicianId' => $_POST['tech'],
				'Site' => addslashes($row_jc['SiteName']),
			);
		
			dbInsert('tbl_history_alerts', $alert_data, $con);
			
			$jc_data = array(
				
				'Status' => '2',
				'Days' => $today
			);
			
			dbUpdate('tbl_jc', $jc_data, $where_clause="JobId = '". $_GET['Id'] ."'",$con);
			
			$fb_data = array(
				
				'Reference' => $row_jc['JobNo'],
				'Status' => '2'
			);
			
			dbInsert('tbl_feedback', $fb_data, $con);
			
			header('Location: ../jc_complete.php?Id=' . $row_jc['JobNo']);
			
			exit();
		
		}
	}
	// End Move To In Progress 
	
	// Move To Costing
	if(isset($_POST['costing']) || isset($_POST['request-pre-po'])){
		
		// Check Mandatory Fields
        $_SESSION['service'] = 0;
        $_SESSION['feedback'] = 0;
        $_SESSION['reference'] = 0;

        $compulsory = array('service', 'feedback', 'reference');
		
		if(empty($_POST['service']) || empty($_POST['feedback']) || empty($_POST['reference'])) {
		
			for($i=0;$i<3;$i++){
				
				if(empty($_POST[$compulsory[$i]])){
					
					$_SESSION[$compulsory[$i]] = 1;
				}
			}

            header('Location: inv-calc.php?Id='.$jobid);
			
			exit();

        } else {
		// End Check Mandatory Fields

            if($row_jc['CompanyId'] == 6 && $row_jc['RequestPreWorkPo'] == 0) {

                if(isset($_POST['request-pre-po'])) {

                    mysqli_query($con, "UPDATE tbl_jc SET RequestPreWorkPo = '1', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
                }

                // Send to Awaiting Order No.
                $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '18', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));

            } else {

                // Send to Costing
                $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '3', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));

            }

            mysqli_query($con, "INSERT INTO tbl_feedback (Reference,Status) VALUES ('$jobno','3')") or die(mysqli_error($con));
            mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'") or die(mysqli_error($con));

            header('Location: in-progress.php?Id='.$_GET['Id']);
        }
		
	}
	// End Move To Costing
	
	// Move To Awaiting Paperwork
	if(isset($_POST['con-complete']) || isset($_POST['awaiting-pwk'])) {
	
		mysqli_query($con, "UPDATE tbl_jc SET Status = '20', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
	
		header('Location: in-progress.php');
		exit();
	}
	// End Move To Awaiting Paperwork
	
	// Move To Rework Archives
	if(isset($_POST['rework'])){
		
		$query = mysqli_query($con, "UPDATE tbl_jc SET Status = '21', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
		
		header('Location: mail.php?Rework='. $jobid);
		exit();
	}
	// EndMove To Rework Archives
	
	// Move To Pending Invoices
    if(isset($_POST['invoice'])) {

        $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '7', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));

        header('Location: in-progress.php');

    }
	// End Move To Pending Invoices
		
	header('Location: inv-calc.php?Id='. $_GET['Id'] .'#Btm');
}

if(($row_jc['InvoiceNo'] == 0) && ($row_jc['Status'] != 1)) {

    $invoiceno = invno($con);
    $inv_date = date('Y-m-d');
    mysqli_query($con, "UPDATE tbl_jc SET InvoiceNo = '$invoiceno', NewInvoiceDate = '$inv_date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

if(isset($_GET['Search'])){
	
	$query_status = mysqli_query($con, "SELECT * FROM tbl_status ORDER BY Status ASC") or die(mysqli_error($con));
	
} else {
	
	$query_status = mysqli_query($con, "SELECT * FROM tbl_status WHERE Id <= 5 OR Id = '17' OR Id = '18' OR Id = '19' OR Id = '20'") or die(mysqli_error($con));
}
		
$query_sum = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' OR Material = '1'") or die(mysqli_error($con));
$row_sum = mysqli_fetch_array($query_sum_labour);

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$subtotal = $row_sum['SUM(Total1)'] + $$row['SUM(Total1)'];

mysqli_query($con, "UPDATE tbl_jc SET SubTotal = '$subtotal' WHERE JobId = '$jobid'") or die(mysqli_error($con));

$query_Recordset4 = "
SELECT
tbl_technicians.
`Name`
AS Name_1,
tbl_actual_history.JobId,
    tbl_users.
`Name`,
tbl_actual_history.Date,
    tbl_actual_history.Comments,
    tbl_actual_history.Mobile
FROM
    (
        (
            tbl_actual_history LEFT JOIN tbl_users ON tbl_users.Id = tbl_actual_history.TechnicianId
        ) LEFT JOIN tbl_technicians ON tbl_technicians.Id = tbl_actual_history.TechnicianId
    )
WHERE
tbl_actual_history.JobId = '$jobid'
ORDER BY
tbl_actual_history.Id ASC ";

$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$company = $_SESSION['company'];
$site = $_SESSION['site'];


$query_Recordset5 = "
	SELECT
		tbl_jc.*,
		tbl_companies.*
	FROM
		tbl_jc
	INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
	WHERE
		tbl_jc.JobId = '$jobid'
	ORDER BY
		tbl_jc.Id ASC
	LIMIT 1";

$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$areaid = $row_Recordset5['AreaId'];

$Recordset100 = mysqli_query($con, "SELECT * FROM tbl_technicians") or die(mysqli_error($con));
$totalRows_Recordset100 = mysqli_num_rows($Recordset100);

$companyid = $row_Recordset5['Id_1'];

$query_Recordset101 = "
SELECT
tbl_rates.NAME AS Name_1,
    tbl_companies.NAME,
    tbl_rates.Rate,
    tbl_rates.CompanyId
FROM
    (
        tbl_companies LEFT JOIN tbl_rates ON tbl_rates.CompanyId = tbl_companies.Id
    )
WHERE
tbl_rates.CompanyId = '$companyid'
";

$Recordset101 = mysqli_query($con, $query_Recordset101) or die(mysqli_error($con));
$row_Recordset101 = mysqli_fetch_assoc($Recordset101);
$totalRows_Recordset101 = mysqli_num_rows($Recordset101);

$query_41 = "
	SELECT
	tbl_far_risc_classification.Risk,
	tbl_far_high_risk_classification.Risk AS Risk2,
	tbl_far.JobNo,
	tbl_far.Id,
	tbl_far_risc_classification.Id AS RiskId
	FROM
		tbl_far_risc_classification
	INNER JOIN tbl_far ON tbl_far.RiskType = tbl_far_risc_classification.Id
	INNER JOIN tbl_far_high_risk_classification ON tbl_far.RiskClassification = tbl_far_high_risk_classification.Id
	WHERE
		tbl_far.JobNo = '$jobno'";

$Recordset41 = mysqli_query($con, $query_41) or die(mysqli_error($con));
$row_Recordset41 = mysqli_fetch_assoc($Recordset41);
$totalRows_Recordset41 = mysqli_num_rows($Recordset41);

$query_job_history = "
SELECT
tbl_history_relation.PhotoId,
    tbl_history_photos.Photo,
    tbl_history_relation.JobId
FROM
    (
        tbl_history_relation LEFT JOIN tbl_history_photos ON tbl_history_photos.Id = tbl_history_relation.PhotoId
    )
WHERE
tbl_history_relation.JobId = '$jobid'
";

$job_history = mysqli_query($con, $query_job_history) or die(mysqli_error($con));
$row_job_history = mysqli_fetch_assoc($job_history);
$totalRows_job_history = mysqli_num_rows($job_history);

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers ORDER BY Name ASC") or die(mysqli_error($con));
$query_contractor = mysqli_query($con, "SELECT * FROM tbl_users WHERE Contractor = '1'") or die(mysqli_error($con));

$query_sla = "
	SELECT
		tbl_sla_cat.Category
	FROM
		tbl_sla_cat
	INNER JOIN tbl_jc ON tbl_jc.SlaCatId = tbl_sla_cat.Id
	WHERE
		tbl_jc.JobId = '$jobid'";
	
$query_sla = mysqli_query($con, $query_sla)or die(mysqli_error($con));
$row_sla = mysqli_fetch_array($query_sla);

$query_sla_sub_cat = "
	SELECT
		tbl_sla_subcat.SubCat
	FROM
		tbl_jc
	INNER JOIN tbl_sla_subcat ON tbl_jc.SlaSubCatId = tbl_sla_subcat.Id
	WHERE
		tbl_jc.JobId = '$jobid'";
	
$query_sla_sub_cat = mysqli_query($con, $query_sla_sub_cat)or die(mysqli_error($con));
$row_sla_sub_cat = mysqli_fetch_array($query_sla_sub_cat);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="countdown/jquery.countdown.css">
      
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
              
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="../menu/script.js"></script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if(restore) selObj.selectedIndex=0;
        }
		
        $(document).ready(function () {
            $(".toggler").click(function (e) {
				e.preventDefault();
                $('.row' + $(this).attr('data-row')).toggle();
            });
        });
		
      </script>
      
	  <script type="text/javascript" src="../fancyBox-2/lib/jquery-1.10.1.min.js"></script>
      <script type="text/javascript" src="../fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <script type="text/javascript" src="../fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
      <link rel="stylesheet" type="text/css" href="../fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />
  
      <script type="text/javascript">
          $(document).ready(function() {
  
              $('.fancybox').fancybox({
			  
				  autoSize    : true, 
				  closeClick  : false, 
				  fitToView   : false, 
				  openEffect  : 'none', 
				  closeEffect : 'none', 
				  type : 'iframe',
                  afterClose : function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
				  parent.location.reload(true);
                  }				  
			  });
  
  
          });
      </script>
      
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
      
	  <script type="text/javascript" src="../highslide/highslide-with-html.js"></script>
      <script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
      <link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />
      
      <script type="text/javascript" src="../highslide/highslide.js"></script>
      <script type="text/javascript">
      <!--
      hs.graphicsDir = '../highslide/graphics/';
          hs.outlineType = 'rounded-white';
      
      function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
      }
      
      function MM_popupMsg(msg) { //v1.0
        alert(msg);
      }
      //-->
      </script>
      
      <script src="countdown/jquery.plugin.js"></script>
      <script src="countdown/jquery.countdown.js"></script>
      
      <!-- Countdown Timer -->
	  <?php     
      $now = strtotime(date('Y-m-d H:i:s'));
      $to = strtotime($row_jc['SlaEnd']);
      
      $secs_before = $to - $now;
      $secs_after = $now - $to;
	  
	  $complete_before = strtotime($row_jc['SlaEnd']) - strtotime($row_jc['DateCompleted']);
	  $complete_after = strtotime($row_jc['SlaEnd']) - strtotime($row_jc['DateCompleted']);
      
	  
      ?>
      
      <script>
      $(function () {
		  
		  <!-- In Progress Counters -->
          <?php
          if(date('Y-m-d H:i:s') < $row_jc['SlaEnd'] && empty($row_jc['DateCompleted'])){
              
              $class = 'bg-blue';
          ?>
          $('#defaultCountdown').countdown({until: +<?php echo $secs_before; ?>});
          <?php 
          } 
		  
          if(date('Y-m-d H:i:s') > $row_jc['SlaEnd'] && empty($row_jc['DateCompleted'])){
              
              $class = 'bg-red';
              
          ?>
          $('#defaultCountdown').countdown({since: -<?php echo $secs_after; ?>});
          <?php } ?>
		  <!-- End In Progress Counters -->
		  
		  <!-- SLA Closed Counters -->
          <?php
          if($row_jc['DateCompleted'] < $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted'])){
              
              $class = 'bg-blue';
          ?>
          $('#defaultCountdown').countdown({until: <?php echo $complete_before; ?>});
		  $('#defaultCountdown').countdown('pause') // Stop the countdown but don't clear it
          <?php 
          } 
		  
          if($row_jc['DateCompleted'] > $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted'])){
              
              $class = 'bg-red';
              
          ?>
          $('#defaultCountdown').countdown({since: <?php echo $complete_after; ?>});
		  $('#defaultCountdown').countdown('pause') // Stop the countdown but don't clear it
          <?php } ?>
		  <!-- EndSLA Closed Counters -->

      });
      </script>
      
      <?php 
	  
	  if(date('Y-m-d H:i:s') < $row_jc['SlaEnd'] || ($row_jc['DateCompleted'] < $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted']))){
		  
		  if(empty($row_jc['DateCompleted'])){
			  
			  $sla_status = 'REMAINING <br> <span class="stop">Click To Stop</span>';
			  $sla_open = 1;
			  
		  } else {
			  
			  $sla_status = 'CLOSED <br> <span class="stop">In SLA</span>';
			  
		  }
	  
	  ?>
		  
          <style type="text/css">
		  
		  #defaultCountdown{
			  background: #2b74bc; /* Old browsers */
			  background: -moz-linear-gradient(top,  #2b74bc 0%, #19446f 100%); /* FF3.6-15 */
			  background: -webkit-linear-gradient(top,  #2b74bc 0%,#19446f 100%); /* Chrome10-25,Safari5.1-6 */
			  background: linear-gradient(to bottom,  #2b74bc 0%,#19446f 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2b74bc', endColorstr='#19446f',GradientType=0 ); /* IE6-9 */
			  
			  border:solid 1px #012c58;
			  color: #FFF;
		  }
		  
		  </style>
		  
		  
	  <?php 
	  }
	  
	  if(date('Y-m-d H:i:s') > $row_jc['SlaEnd'] || ($row_jc['DateCompleted'] > $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted']))){
		  
		  if(empty($row_jc['DateCompleted'])){
			  
			  $sla_status = 'EXPIRED <br> <span class="stop">Click To Stop</span>';
			  $sla_open = 1;
			  
		  } else {
			  
			  $sla_status = 'CLOSED <br> <span class="stop">Out of SLA</span>';
			  
		  }

	  ?>
		  
          <style type="text/css">
		  
		  #defaultCountdown{
			  background: #ed0000; /* Old browsers */
			  background: -moz-linear-gradient(top,  #ed0000 0%, #c10000 100%); /* FF3.6-15 */
			  background: -webkit-linear-gradient(top,  #ed0000 0%,#c10000 100%); /* Chrome10-25,Safari5.1-6 */
			  background: linear-gradient(to bottom,  #ed0000 0%,#c10000 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ed0000', endColorstr='#c10000',GradientType=0 ); /* IE6-9 */
			  
			  border:solid 1px #840101;
			  color:#FFF;
		  }
		  
		  </style>
		  
	  <?php } ?>
      <!-- End Countdown Timer -->
      
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
           <li><a href="#">Pro Forma Invoices</a></li>
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
        <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
        
        <!-- Warning Messages -->
        <?php if(isset($_GET['Service']) || $_SESSION['service'] == 1 || $_SESSION['feedback'] == 1 || $_SESSION['reference'] == 1){ ?>
            <div id="banner-warning">
            <?php
            if(isset($_GET['Service']) || $_SESSION['service'] == 1){
                
                echo "Service Requested is empty! <br>";
            }
            
            if($_SESSION['feedback'] == 1){
                
                echo "Dealers Feedback is empty! <br>";
            }
            
            if($_SESSION['reference'] == 1){
                
                echo "Reference is empty! <br>";
            }
            ?>
            </div>
        <?php } ?>
        <!-- End Warning Messages -->
        
          <!-- Job Cared Details -->
          <div id="list-border">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td colspan="6" class="td-header">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>Job Card</td>
                      <td align="right">
                      <select name="status" class="select-transparent" id="status"  dir="rtl">
                        <option value=""  dir="ltr">Select one...</option>
                        <?php while($row_status = mysqli_fetch_array($query_status)){  ?>
                        <option value="<?php echo $row_status['Id']?>" <?php if($row_status['Id'] == $row_jc['Status']){ echo 'selected="selected"'; } ?>  dir="ltr"><?php echo $row_status['Status']?></option>
                        <?php } ?>
                      </select></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td width="90" class="td-left">Oil Company</td>
                <td width="200" class="td-right"><?php echo $row_Recordset5['Name']; ?></td>
                <td width="90" rowspan="3" valign="top" class="td-left">Address</td>
                <td width="200" rowspan="3" valign="top" class="td-right"><?php echo nl2br($row_Recordset5['Address']); ?></td>
                <td width="90" class="td-left">Job Card No.</td>
                <td class="td-right"><input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $row_Recordset5['JobNo']; ?>" /></td>
              </tr>
              <tr>
                <td class="td-left">Contact</td>
                <td class="td-right"><?php echo $row_Recordset5['FirstName'] .' '. $row_Recordset5['LastName']; ?></td>
                <td class="td-left">Order No.</td>
                <td class="td-right"><input name="refno" type="text" class="tarea-100" id="refno" value="<?php echo $row_Recordset5['RefNo']; ?>" size="18" /></td>
              </tr>
              <tr>
                <td class="td-left">Telephone</td>
                <td class="td-right"><?php echo $row_Recordset5['Telephone']; ?></td>
                <td class="td-left">Reference</td>
                <td class="td-right"><input name="reference" type="text" class="tarea-100" id="reference" value="<?php echo $row_Recordset5['Reference']; ?>" size="18" /></td>
              </tr>
            </table>
          </div>
          <!-- End Job Cared Details -->
          
          <!-- Proactive Risk Assessment -->
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td colspan="2" class="td-header">Job Type</td>
              </tr>
              <tr>
                <td width="25%" class="td-left">SLA Category</td>
                <td class="td-right"><?php echo $row_sla['Category']; ?></td>
              </tr>
              
              <?php
			  
			  $class = '';
			  
			  if($row_Recordset41['RiskId'] == 3){
				  
				  $class = 'style="color: #FF0000; font-weight:bold"';
			  }
			  ?>
            </table>
          </div>
          <!-- End Proactive Risk Assessment -->
          
          <!-- Requested Completion Date --><!-- End Requested Completion Date -->
                    
          <!-- Actual Work Carried Out -->   
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="td-header">Actual Work Carried Out</td>
                <td width="50" align="center" class="td-header">Unit</td>
                <td width="50" align="center" class="td-header">Qty</td>
                <td width="120" align="right" class="td-header">Unit Price</td>
                <td width="15" align="center" class="td-header">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5" class="td-sub-header">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>
                        Labour
                        <a name="Labour" id="Labour"></a>
                        <a name="Btm" id="Btm"></a>
                      </td>
                      <td width="15" align="right"><a href="inv-calc.php?Id=<?php echo $_GET['Id']; ?>&AddLabour" class="add-row-jc"></a></td>
                    </tr>
                </table></td>
              </tr>
              
              <!-- Labour -->
              <?php
              $jobid = $_GET['Id'];
              
              $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC") or die(mysqli_error($con));
              $numrows = mysqli_num_rows($query);
              while($row = mysqli_fetch_array($query)){
                  
                  $id = $row['Id'];
              
                  $query1 = "SELECT * FROM tbl_jc WHERE Id = '$id'";
                  $result1 = mysqli_query($con, $query1) or die(mysqli_error($con));
                  $row1 = mysqli_fetch_array($result1);
                  
                  $rate = $row1['Price'];
                  $rate = explode(".", $rate);
                  $unit = $row['Unit'];
                  
                  $query3 = mysqli_query($con, "SELECT * FROM tbl_rates WHERE Fuel = '1'")or die(mysqli_error($con));
                  $row3 = mysqli_fetch_array($query3);
                  
                  $unit_display = $row['Name'];
				  
				  if($row['Description'] == NULL){
					  
					  $value = trim('Conducted risk assessment & completed necessary safety documents');
					  
				  } else {
					  
					  $value = trim($row['Description']);
				  }
              
              ?>
              
              <tr>
                <td class="td-right"><textarea name="labour[]" rows="1" wrap="hard" class="tarea-100" id="labour"><?php echo $value; ?></textarea></td>
                <td class="td-right"><input name="unit_l[]" type="text" class="tarea-100-centre" id="unit_l" value="<?php echo $unit; ?>"></td>
                <td class="td-right"><input name="qty_l[]" type="text" class="tarea-100-centre" id="qty_l" value="<?php echo $row['Qty']; ?>"></td>
                <td width="120" class="td-right"><input name="price_l[]" type="text" class="tarea-100-dd" id="price_l[]" value="<?php echo $row['Price']; ?>" /></td>
                <td class="td-right">
                  <a href="inv-calc.php?Id=<?php echo $_GET['Id']; ?>&Delete=<?php echo $row['Id']; ?>" class="remove"></a>
                  <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>">
                </td>
              </tr>
              <?php } ?>
              <!-- End Labour -->
              
              <!-- Material -->
              <tr>
                <td colspan="5" class="td-sub-header">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>
                        Materials
                        <a name="Material" id="Material"></a>
                      </td>
                      <td width="15"><a href="inv-calc.php?Id=<?php echo $_GET['Id']; ?>&AddMaterial" class="add-row-jc"></a></td>
                    </tr>
                </table></td>
              </tr>
              <?php
              $query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
              $result = mysqli_query($con, $query) or die(mysqli_error($con));
              while($row = mysqli_fetch_array($result)){
              ?>
              <tr>
                <td class="td-right"><input name="material[]" type="text" class="tarea-100" id="material" value="<?php echo $row['Description']; ?>" /></td>
                <td class="td-right"><input name="unit_m[]" type="text" class="tarea-100-centre" id="unit_m" value="<?php echo $row['Unit']; ?>" /></td>
                <td class="td-right"><input name="qty_m[]" type="text" class="tarea-100-centre" id="qty_m" value="<?php echo $row['Qty']; ?>" /></td>
                <td class="td-right"><input name="price_m[]" type="text" class="tarea-100-right" style="text-align:" id="price_m" value="<?php echo $row['Price']; ?>" /></td>
                <td class="td-right">
                  <a href="inv-calc.php?Id=<?php echo $_GET['Id']; ?>&Delete=<?php echo $row['Id']; ?>" class="remove"></a>
                  <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>">
                </td>
              </tr>
              <?php } ?>
              <!-- End Material -->
              
              <!-- Transport -->
              <tr>
                <td colspan="5" class="td-sub-header">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>
                        Transport
                        <a name="Transport" id="Transport"></a>
                      </td>
                      <td width="15"><a href="inv-calc.php?Id=<?php echo $_GET['Id']; ?>&AddTransport" class="add-row-jc"></a></td>
                    </tr>
                </table></td>
              </tr>
              <?php
              $query2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
              $row2 = mysqli_fetch_array($query2);
              
              $companyid = $row2['CompanyId'];
              
              $query = mysqli_query($con, "SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysqli_error($con));
              while($row = mysqli_fetch_array($query)){
                  
                  $siteid = $row['SiteId'];
              
                  $query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
                  $result2 = mysqli_query($con, $query2) or die(mysqli_error($con));
                  $row2 = mysqli_fetch_array($result2);
              ?>
              <tr>
                <td class="td-right">
                  <input name="t_comment[]" type="text" class="tarea-100" onFocus="if(this.value=='Comments'){this.value=''}" 
                  onBlur="if(this.value==''){this.value='Comments'}" value="<?php field_val($row['TransportComment'],'Comments'); ?>">
                </td>
                <td class="td-right">
                  <input name="transport[]" type="text" class="tarea-100-centre" id="transport" onFocus="if(this.value=='Trips'){this.value=''}" 
                  onBlur="if(this.value==''){this.value='Trips'}" value="<?php field_val($row['Description'],'Trips'); ?>">
                </td>
                <td class="td-right">
                  <input name="qty_t[]" type="text" class="tarea-100-centre" id="qty_t" onfocus="if(this.value=='Rtn Dist'){this.value=''}" 
                  onblur="if(this.value==''){this.value='Rtn Dist'}" value="<?php field_val($row['Qty'],'Rtn Dist'); ?>" />
                </td>
                <td class="td-right">
                  <input name="price_t[]" type="text" class="tarea-100-right" id="price_t[]" onfocus="if(this.value=='Fuel Rate'){this.value=''}" 
                  onblur="if(this.value==''){this.value='Fuel Rate'}" value="<?php field_val($row['Price'],'Fuel Rate'); ?>" />
                </td>
                <td class="td-right">
                  <a href="inv-calc.php?Id=<?php echo $_GET['Id']; ?>&Delete=<?php echo $row['Id']; ?>" class="remove"></a>
                  <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
                  <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km">
                </td>
              </tr>
              <?php } ?>
              <!-- End Transport -->
            </table>
          </div>
          <!-- End Actual Work Carried Out -->
          
          <!-- Buttons -->   
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td>
                <?php if($row_jc['Status'] == 1 || $row_jc['Status'] == 5 || $row_jc['Status'] == 20){ ?>
                    <input name="in-progress" type="submit" class="btn-new" id="in-progress" value="In Progress" />
                <?php } ?>
                
                <?php
				
				$rework = explode('-', $row_jc['JobNo']);
				if($rework[0] == 'RWRK'){
					
					$btn = '<input name="rework" type="submit" class="btn-new" id="rework" value="Close Rework" />';
				}
				
				?>
                
                <?php if($row_jc['Status'] == 2  || $row_jc['Status'] == 5 || $row_jc['Status'] == 20){ ?>
                
                    <?php if(isset($btn)){
						
						echo $btn;
						
					} else {
						
					?>
                        <input name="awaiting-pwk" type="submit" class="btn-new" id="awaiting-pwk" value="Awaiting PWK" />
                        <input name="costing" type="submit" class="btn-new" id="costing" value="Costing" />
					<?php } ?>
                <?php } ?>
                <?php if($row_jc5['CompanyId'] == 6 && $row_jc['RequestPreWorkPo'] == 0){ ?>
                    <input name="pre-work-po" type="submit" class="btn-new" id="pre-work-po" value="Request Pre Work PO" />
                <?php } elseif($row_jc['Status'] == 3){ ?>
                    <input name="invoice" type="submit" class="btn-new" id="invoice" value="Invoice" />
                <?php } ?>
              </td>
              <td align="right">
                <a href="../pdf/pdf-pro-forma.php?Id=<?php echo $_GET['Id']; ?>&InvoiceNo=<?php echo $row_jc['InvoiceNo']; ?>" class="btn-new fancybox">Email Proforma</a> 
                <input name="update" type="submit" class="btn-new" id="update" value="Save" />
              </td>
            </tr>
          </table>
          <!-- End Buttons -->   
        
        </form>  
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
</body>
</html>
<?php

  unset($_SESSION['feedback']);
  unset($_SESSION['services']);
  
  mysqli_close($con);
  mysqli_free_result($query_jc);
  mysqli_free_result($query);
  mysqli_free_result($query1);
  mysqli_free_result($query2);
  mysqli_free_result($query3);
  mysqli_free_result($query_41);
  mysqli_free_result($query_contractor);
  mysqli_free_result($query_engineers);
  mysqli_free_result($query_job_history);
  mysqli_free_result($query_Recordset101);
  mysqli_free_result($query_Recordset4);
  mysqli_free_result($query_Recordset5);
  mysqli_free_result($query_status);
  mysqli_free_result($query_sum);
  mysqli_free_result($query_sla);
  mysqli_free_result($query_sla_sub_cat);
?>
