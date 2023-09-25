<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

logout($con);

$today = date('Y-m-j');
$jobid = $_GET['Id'];

if(isset($_GET['Search'])){
	
	$form_param = '&Search';
}

$query_jc = "
	SELECT
		tbl_jc.*,
		tbl_companies.`Name` AS CompanyName,
		tbl_sites.`Name` AS SiteName
	FROM
		tbl_jc
	INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
	INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
	WHERE
		JobId = '$jobid'";

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
		header('Location: jc-calc.php?Id='. $_GET['Id'] . $form_param .'#Labour');
		
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
		header('Location: jc-calc.php?Id='. $_GET['Id'] . $form_param .'#Material');
		
		exit();
	}
	
	// Transport
	if(isset($_GET['AddTransport'])){
		
		dbInsert('tbl_travel', $form_data, $con);
		header('Location: jc-calc.php?Id='. $_GET['Id'] . $form_param .'#Transport');
		
		exit();
	}
	
	// Delete Labour
	if(isset($_GET['Delete'])){
		
		$delete = $_GET['Delete'];
	
		mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$delete'") or die(mysqli_error($con));
		header('Location: jc-calc.php?Id='. $_GET['Id'] . $form_param .'#Delete');
		
		exit();
	}
}
// End Add & Delete Rows

if(isset($_GET['Rotate'])){
	
			
	$target_path = "../images/history/";
	$target_path2 = "../images/history/thumbnails/";
	
	rotateImage($target_path . $_GET['Rotate'], $target_path . $_GET['Rotate'],90);
	rotateImage($target_path2 . $_GET['Rotate'], $target_path2 . $_GET['Rotate'],90);
	
	header('Location: jc-calc.php?Id='. $_GET['Id'] .'#Gallery');

}

if(isset($_POST['update']) || isset($_POST['in-progress']) || isset($_POST['awaiting-pwk']) || isset($_POST['costing']) || isset($_POST['pre-work-po']) || isset($_POST['invoice']) || isset($_POST['import']) || isset($_FILES['pdf']['name'])){
	
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

    // Update transport
	for ($i=0;$i<count($_POST['id_t']);$i++){
		
		$transport_data = array(
		
			'Description' => $_POST['transport'][$i],
			'Unit' => $_POST['unit_t'][$i],
			'Qty' => $_POST['qty_t'][$i],
			'Price' => $_POST['price_t'][$i],
			'TransportComment' => $_POST['t_comment'][$i],
			'DistanceKm' => $_POST['km'][$i],
			'KmRate' => $_POST['km-rate'][$i],
			'TotalKm' => $_POST['total-km'][$i],
			'TravelTime' => $_POST['travel-time'][$i],
			'TravelTimeRate' => $_POST['travel-time-rate'][$i]
		);
		
		$transport_data['Total1'] = $_POST['price_t'][$i] * $_POST['qty_t'][$i];
		
		dbUpdate('tbl_travel', $transport_data, $where_clause="Id = '". $_POST['id_t'][$i] ."'",$con);
	}
	
	// General Update
	$jc_data = array(
		
		'JobNo' => $_POST['jobno'],
		'ContractorId' => $_POST['contractor'],
		'Reference' => $_POST['reference'],
		'Date1' => $_POST['date1'],
		'Date2' => $_POST['date2'],
		'JobDescription' => addslashes($_POST['service']),
		'JobDescriptionOperator' => $_COOKIE['name'],
		'JobDescriptionDate' => date('Y-m-d'),
		'CustomerFeedBack' => $_POST['CFB'],
		'Progress' => $_POST['progress'],
		'Status' => $_POST['status']
	);
	
	// Upload PDF Job Card
	$target_path = "../jc-pdf/";
	$target_path = $target_path.basename($_FILES['pdf']['name']);
	
	if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)) {
	
		$jc_data['JobcardPDF'] = $_FILES['pdf']['name'];
	}
	
	dbUpdate('tbl_jc', $jc_data, $where_clause="JobId = '". $_GET['Id'] ."'",$con);
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
			
			header('Location: jc-calc.php?Id='. $_GET['Id'] .'&Service');
			
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

            header('Location: jc-calc.php?Id='.$jobid);
			
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
	
	// Move To Pending Invoices
    if(isset($_POST['invoice'])) {

        $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '7', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));

        header('Location: in-progress.php');

    }
	// End Move To Pending Invoices
		
	header('Location: jc-calc.php?Id='. $_GET['Id'] .'#Btm');
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
		tbl_sites.`Name` AS Name_1,
		tbl_sites.Id AS SiteId,
		tbl_companies.Id AS Id_1,
		tbl_jc.RequestPreWorkPo,
		tbl_companies.`Name`,
		tbl_sites.Company,
		tbl_sites.Site,
		tbl_sites.Address,
		tbl_sites.FirstName,
		tbl_sites.LastName,
		tbl_sites.Telephone,
		tbl_sites.Email,
		tbl_jc.Id,
		tbl_jc.AreaId,
		tbl_jc.JobId,
		tbl_jc.ContractorId,
		tbl_jc.FeedBackTech,
		tbl_jc.FeedBack,
		tbl_jc.FeedBackDate,
		tbl_jc.JobNo,
		tbl_jc.Date,
		tbl_jc.`Status`,
		tbl_jc.JobDescription,
		tbl_jc.JobCardPDF,
		tbl_jc.Progress,
		tbl_jc.Reference,
		tbl_jc.InvoiceNo,
		tbl_jc.QuoteNo,
		tbl_status.`Status` AS JobStatus
	FROM
		(
			(
				tbl_jc
				LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
			)
			LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		)
	INNER JOIN tbl_status ON tbl_jc.`Status` = tbl_status.Id
	WHERE
		tbl_jc.JobId = '$jobid'
	ORDER BY
		Id ASC
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
    tbl_far.Id
FROM
tbl_far_risc_classification
INNER JOIN tbl_far ON tbl_far.RiskType = tbl_far_risc_classification.Id
INNER JOIN tbl_far_high_risk_classification ON tbl_far.RiskClassification = tbl_far_high_risk_classification.Id
WHERE
tbl_far.JobNo = '$jobno'
";

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
		tbl_jc.JobId = '30537'";
	
$query_sla = mysqli_query($con, $query_sla)or die(mysqli_error($con));
$row_sla = mysqli_fetch_array($query_sla);

$query_sla_sub_cat = "
	SELECT
		tbl_sla_subcat.SubCat
	FROM
		tbl_jc
	INNER JOIN tbl_sla_subcat ON tbl_jc.SlaSubCatId = tbl_sla_subcat.Id
	WHERE
		tbl_jc.JobId = '30537'";
	
$query_sla_sub_cat = mysqli_query($con, $query_sla_sub_cat)or die(mysqli_error($con));
$row_sla_sub_cat = mysqli_fetch_array($query_sla_sub_cat);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" href="jquery.countdown.css">
      
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
              
      <link rel="stylesheet" href="../../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../../menu/script.js"></script>
      
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
      
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>
      
	  <script type="text/javascript" src="../../highslide/highslide-with-html.js"></script>
      <script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
      <link rel="stylesheet" type="text/css" href="../../highslide/highslide.css" />
      
      <script type="text/javascript" src="../../highslide/highslide.js"></script>
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
      
      <script src="jquery.plugin.js"></script>
      <script src="jquery.countdown.js"></script>
      
<?php     
$now = strtotime(date('Y-m-d H:i:s'));
$to = strtotime('2016-02-10 13:20:00');

$secs_before = $to - $now;
$secs_after = $now - $to;
?>

<script>
$(function () {
	<?php
	if(date('Y-m-d H:i:s') < '2016-02-10 13:20:00'){
		
		$class = 'bg-blue';
	?>
	$('#defaultCountdown').countdown({until: +<?php echo $secs_before; ?>});
	<?php 
	} else {
		
		$class = 'bg-red';
		
	?>
	$('#defaultCountdown').countdown({since: -<?php echo $secs_after; ?>});
	<?php } ?>
});
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
      <?php include('../../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Job Cards</a></li>
            <li><a href="#"><?php echo $row_Recordset5['JobStatus']; ?></a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="../search.php">
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
                <td colspan="7" class="td-header">
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
                <td width="90" class="td-left">Site</td>
                <td width="200" class="td-right"><?php echo $row_Recordset5['Name_1']; ?></td>
                <td width="90" class="td-left">Job Card No.</td>
                <td colspan="2" class="td-right"><input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $row_Recordset5['JobNo']; ?>" /></td>
              </tr>
              <tr>
                <td class="td-left">Contact</td>
                <td class="td-right"><?php echo $row_Recordset5['FirstName'] .' '. $row_Recordset5['LastName']; ?></td>
                <td class="td-left">Address</td>
                <td class="td-right"><?php echo char_limit($row_Recordset5['Address'], 30); ?></td>
                <td class="td-left">Quote No</td>
                <td class="td-right"><input name="quote" type="text" class="tarea-100" id="quote" value="<?php echo $value; ?>" size="18" /></td>
                <td class="td-right"><input name="import" type="submit" class="btn-new-2" id="import" value="Import" /></td>
              </tr>
              <tr>
                <td class="td-left">Telephone</td>
                <td class="td-right"><?php echo $row_Recordset5['Telephone']; ?></td>
                <td class="td-left">Email</td>
                <td class="td-right"><?php echo $row_Recordset5['Email']; ?></td>
                <td class="td-left">Reference</td>
                <td colspan="2" class="td-right">
                  <select name="reference" class="tarea-100" id="reference">
                    <option value="">Select an Engineer</option>
                    <?php while($row_engineers = mysqli_fetch_array($query_engineers)){ ?>
                    <option value="<?php echo trim($row_engineers['Name']); ?>" <?php if($row_engineers['Name'] == $row_Recordset5['Reference']){ echo 'selected="selected"'; } ?>><?php echo $row_engineers['Name']; ?>
                      <?php } ?>
                    </option>
                  </select>
                  <?php if(isset($_GET['reference'])){ ?>
                  <span class="form_validation_field_error_error_message">Required Field</span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td class="td-right">&nbsp;</td>
                <td class="td-right">&nbsp;</td>
                <td class="td-left">Contractor</td>
                <td class="td-right">
                <select name="contractor" class="tarea-100" id="contractor">
                  <option value="0">None selected...</option>
                  <?php while($row_contractor = mysqli_fetch_array($query_contractor)){ ?>
                  <option value="<?php echo $row_contractor['Id']; ?>" <?php if($row_Recordset5['ContractorId'] == $row_contractor['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_contractor['Name']; ?></option>
                  <?php } ?>
                </select></td>
                <td class="td-left">Job Card PDF</td>
                <td class="td-right"><div class="cloak"><input name="pdf" type="file" class="pdf-file-input" id="pdf" /></div></td>
                <td class="td-right">
                  <?php if(!empty($row_Recordset5['JobCardPDF'])){ ?>
                      <a href="../jc-pdf/<?php echo $row_Recordset5['JobCardPDF']; ?>" target="_blank" class="btn-new-2 btn-new-2-pdf">View</a>
                  <?php } ?>
                </td>
              </tr>
            </table>
          </div>
          <!-- End Job Cared Details -->
          
          <!-- Proactive Risk Assessment -->
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td colspan="4" class="td-header">Job Type</td>
              </tr>
              <tr>
                <td class="td-left">SLA Category</td>
                <td class="td-right"><?php echo $row_sla['Category']; ?></td>
                <td class="td-left">SLA Sub Category</td>
                <td class="td-right"><?php echo $row_sla_sub_cat['SubCat']; ?></td>
              </tr>
              <tr>
                <td width="25%" class="td-left"> Risk Classification</td>
                <td width="25%" class="td-right"><?php echo $row_Recordset41['Risk']; ?></td>
                <td width="25%" class="td-left">High risk classification</td>
                <td width="25%" class="td-right"><?php echo $row_Recordset41['Risk2']; ?></td>
              </tr>
            </table>
          </div>
          <!-- End Proactive Risk Assessment -->
          
          <!-- Requested Completion Date -->
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td rowspan="2" width="137">
                <div id="defaultCountdown" class="<?php echo $class; ?>"></div>
                </td>
                <td class="td-left" width="165">Received Date              
                </span></td>
                <td align="right" class="td-right">
                  <input name="date1" <?php echo $sla; ?> class="tarea-100" id="date1" value="<?php echo $row_jc['Date1']; ?>" />
                </td>
              </tr>
              <tr>
                <td class="td-left">Requested Completion
                </td>
                <td align="right" class="td-right">
                  <input name="date2" <?php echo $sla; ?> class="tarea-100" id="date2" value="<?php echo $row_jc['Date2']; ?>" />
                </td>
              </tr>
            </table>
          </div>
          <!-- End Requested Completion Date -->
          
          <!-- Service Requested -->
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td colspan="7" class="td-header">Service Requested</td>
              </tr>
              <tr>
                <td colspan="7" class="td-right">
                
                <?php
                echo '<span class="history-bg-con">
                        <span class="history-bg">
                        '.
                        $row_jc['JobDescriptionOperator'] .' '. $row_jc['JobDescriptionDate'] .'
                        </span> '. $row_jc['JobDescription'] .'
                      </span>';
                ?>
                </td>
              </tr>
              <tr>
                <td colspan="7" class="td-right">
                  <textarea name="service" rows="4" class="tarea-100" id="service"><?php echo stripslashes($row_jc['JobDescription']); ?></textarea>
                </td>
              </tr>
            </table>
          </div>
          <!-- End Service Requested -->
          
          <!-- Costing Hint -->
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="td-header">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>Costing Hint</td>
                      <td>&nbsp;</td>
                      <td align="right">
                        <a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;costing">
                          <img src="../../images/icons/edit-mini.png" alt="" width="13" height="15" border="0">
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="td-right">
                  <textarea name="costing-hint" rows="4" class="tarea-100" id="costing-hint" >
                  <?php 
                      if(isset($_GET['costing'])){
                          
                          echo $row_jc['CostingHint']; 
                      }
                      ?>
                  </textarea>
                </td>
              </tr>
            </table>
          </div>
          <!-- End Costing Hint -->
                    
          <!-- Customer Status Report -->
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="td-header">Customer Status Report</td>
              </tr>
              <tr>
                <td class="td-right"><table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>
                    <select name="progress" class="tarea-100" id="progress">
                      <option>Select one...</option>
                      <?php
                      $i = 0;
                      
                      for($i=1;$i<=100;$i++){
                      ?>
                      <option value="<?php echo $i; ?>" <?php if($i == $row_progress['Progress']){ ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
                      <?php } ?>
                    </select></td>
                    <td width="25" align="center">%</td>
                  </tr>
                </table>
                </td>
              </tr>
              <tr>
                <td class="td-right"><textarea name="cfb" rows="4" class="tarea-100" id="cfb"><?php echo $row_jc['CustomerFeedBack']; ?></textarea></td>
              </tr>
            </table>
          </div>
          <!-- End Customer Status Report -->
          
          <!-- Actual History -->
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="td-header">Actual History</td>
              </tr>
              
              <!-- Allocate Technicians -->
              <tr>
                <td class="td-sub-header">
                  <a href="#" class="toggler sm-bar" data-row="Allocate">Allocate Technicians</a>
                </td>
              </tr>
              <tr class="rowAllocate" style="display: none">
                <td class="td-right">
                <?php
                  $i = 0;
                  
                  while($row_Recordset100 = mysqli_fetch_array($Recordset100)){
                  
                      $i++;
                      $id = $row_Recordset100['Id'];
                      
                      $query = mysqli_query($con, "SELECT * FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId = '$id'")or die(mysqli_error($con));
                      $row = mysqli_fetch_array($query);
                  
                 ?>
                <div class="allocate-tech">
                  <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="20">
                        <input name="tech[]" type="checkbox" id="tech<?php echo $i; ?>" value="<?php echo $row_Recordset100['Id']; ?>"<?php if($row_Recordset100['Id'] == $row['TechnicianId']) { ?> checked="checked"<?php } ?> />
                      </td>
                      <td><label for="tech<?php echo $i; ?>"><?php echo $row_Recordset100['Name']; ?></label></td>
                    </tr>
                  </table>
                </div>
                <?php } ?>
                </td>
              </tr>
              <!-- End Allocate Technicians -->
              
              <tr>
                <td class="td-right">
                  <div id="history-log">
                    <?php do {
                        
                      if($row_Recordset4['Mobile'] == 1){
                          
                          $name = $row_Recordset4['Name_1'];
                      
                      } else {
                          
                          $name = $row_Recordset4['Name'];
                      
                      } 
  
                      echo '<span class="history-bg-con">
                              <span class="history-bg">
                              '.
                              $name .' '. date('m/d H:i', strtotime($row_Recordset4['Date'])) .'
                              </span> '. $row_Recordset4['Comments'] .'
                            </span>';
                      ?>
                    
                    <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-sub-header">Comments</td>
              </tr>
              <tr>
                <td class="td-right">
                  <textarea name="comment" rows="4" class="tarea-100" id="comment" type="text" <?php echo $disabled; ?>></textarea>
                </td>
              </tr>
              
              <!-- Image Gallery -->
              <tr>
                <td class="td-sub-header">Image Gallery</td>
              </tr>
              <tr>
                <td class="td-sub-sub-sub-header"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><?php echo $totalRows_job_history .' Photos'; ?><a name="Gallery" id="Gallery"></a></td>
                      <td align="right"><a href="../../jc-photos.php?Id=<?php echo $_GET['Id']; ?>" class="search-gallery">Search Gallery</a></span></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="td-right">
                  <table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
                    <tr>
                      <td width="100"><div class="cloak2"><input type="file" class="custom-file-input" name="photo"></div></td>
                      <td align="right">
                      
					  <?php
                        do {
                            
                            createThumbs('../images/history/','../images/history/thumbnails/',100,$row_job_history['Photo'])
                        ?>
                        <div style="float:right">
                          <table border="0" cellpadding="2" cellspacing="3">
                            <tr>
                              <td>
                                <a href="../images/history/<?php echo $row_job_history['Photo']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})">
                                  <img src="../images/history/thumbnails/<?php echo $row_job_history['Photo']; ?>" class="img-bdr" />
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td><a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;Rotate=<?php echo $row_job_history['Photo']; ?>" style="text-align:center" class="btn-new-2">Rotate</a></td>
                            </tr>
                          </table>
                        </div>
                        <?php } while ($row_job_history = mysqli_fetch_assoc($job_history)); ?>
                        
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- End Image Gallery -->
              
          </table>
          </div>
          <!-- End Actual History -->
          
          <!-- Dealers Feedback -->   
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="td-header">Desalers Feedback</td>
              </tr>
              <tr>
                <td class="td-right">
                
                  <?php
                  $jobid = $_GET['Id'];
                  
                  $query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
                  $result = mysqli_query($con, $query) or die(mysqli_error($con));
                  $numrows = mysqli_num_rows($result);
                  $row = mysqli_fetch_array($result);
                  
                  if(!empty($row['FeedBackDate'])){
                      
                      $fb_date = $row['FeedBackDate'];
                      
                  } else {
                      
                      $fb_date = date('Y-m-d');
                  }
                  ?>
                  
                  <input name="fb_date" class="tarea-100" id="fb_date" value="<?php echo $fb_date; ?>">
                  <script type="text/javascript">
                    $('#fb_date').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                  </script>
                </td>
              </tr>
              <tr>
                <td class="td-right">
                  <div id="history-log">
                    <?php do {
                        
                      echo '<span class="history-bg-con">
                              <span class="history-bg">
                              '.
                              $row_jc['FeedBackTech'] .' '. date('m/d', strtotime($row_jc['FeedBackDate'])) .'
                              </span> '. $row_jc['FeedBack'] .'
                            </span>'; ?>
                    
                    <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="td-right">
                  <textarea name="feedback" rows="4" class="tarea-100" id="feedback"></textarea>
                  <input name="id_c" type="hidden" id="id_c" value="<?php echo $row['Id']; ?>">
                </td>
              </tr>
            </table>
          </div>
          <!-- End Dealers Feedback -->
          
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
                      <td width="15" align="right"><a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;AddLabour" class="add-row-jc"></a></td>
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
                <td class="td-right"><textarea name="labour[]" wrap="hard" class="tarea-100" id="labour"><?php echo $value; ?></textarea></td>
                <td class="td-right"><input name="unit_l[]" type="text" class="tarea-100-centre" id="unit_l" value="<?php echo $unit; ?>"></td>
                <td class="td-right"><input name="qty_l[]" type="text" class="tarea-100-centre" id="qty_l" value="<?php echo $row['Qty']; ?>"></td>
                <td width="120" class="td-right">
                <select name="price_l[]" class="tarea-100" id="price_l[]" style="text-align:right">
                  <?php do {  ?>
                  <option value="<?php echo $row_Recordset101['Rate']?>"<?php if (!(strcmp($row_Recordset101['Rate'], $rate[0]))) {echo "selected=\"selected\"";} ?> style="text-align:right"><?php echo $row_Recordset101['Name_1']?></option>
                  <?php
                  } while ($row_Recordset101 = mysqli_fetch_assoc($Recordset101));
                  
                  $rows = mysqli_num_rows($Recordset101);
                  
                  if($rows > 0) {
                      
                      mysqli_data_seek($Recordset101, 0);
                      $row_Recordset101 = mysqli_fetch_assoc($Recordset101);
                  }
                  ?>
                </select>
                </td>
                <td class="td-right">
                  <a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;Delete=<?php echo $row['Id']; ?>" class="remove"></a>
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
                      <td width="15"><a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;AddMaterial" class="add-row-jc"></a></td>
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
                  <a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;Delete=<?php echo $row['Id']; ?>" class="remove"></a>
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
                      <td width="15"><a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;AddTransport" class="add-row-jc"></a></td>
                    </tr>
                </table></td>
              </tr>
              <?php
              $query2 = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
              $row2 = mysql_fetch_array($query2);
              
              $companyid = $row2['CompanyId'];
              
              $query = mysql_query("SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysql_error());
              while($row = mysql_fetch_array($query)){
                  
                  $siteid = $row['SiteId'];
              
                  $query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
                  $result2 = mysql_query($query2) or die(mysql_error());
                  $row2 = mysql_fetch_array($result2);
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
                  <a href="../jc-calc.php?Id=<?php echo $_GET['Id']; ?>&amp;Delete=<?php echo $row['Id']; ?>" class="remove"></a>
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
                <?php if($row_jc['Status'] == 2  || $row_jc['Status'] == 5 || $row_jc['Status'] == 20){ ?>
                    <input name="awaiting-pwk" type="submit" class="btn-new" id="awaiting-pwk" value="Awaiting PWK" />
                    <input name="costing" type="submit" class="btn-new" id="costing" value="Costing" />
                <?php } ?>
                <?php if($row_jc5['CompanyId'] == 6 && $row_jc['RequestPreWorkPo'] == 0){ ?>
                    <input name="pre-work-po" type="submit" class="btn-new" id="pre-work-po" value="Request Pre Work PO" />
                <?php } elseif($row_jc['Status'] == 3){ ?>
                    <input name="invoice" type="submit" class="btn-new" id="invoice" value="Invoice" />
                <?php } ?>
              </td>
              <td align="right"><input name="update" type="submit" class="btn-new" id="update" value="Save" />
              </td>
            </tr>
          </table>
          <!-- End Buttons -->   
        
        </form>  
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
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
