<?php 
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$today = date('Y-m-j');
$jobid = $_GET['Id'];

if(isset($_POST['Submit3']) || isset($_POST['complete'])){
	
// Insert / update Pragma table
	
	$jobid = $_GET['Id'];
	
	$query = mysqli_query($con, "SELECT CompanyId, JobNo FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	if($row['CompanyId'] == 2){
		
		$jobno = $row['JobNo'];
		$jobid = $_GET['Id'];
		$component = $_POST['component'];
		$failure_type = $_POST['failure-type'];
		$failure = $_POST['failure'];
		$root_cause = $_POST['root-cause'];
		$repair = $_POST['repair'];
		$followup = $_POST['followup'];
		
		if(empty($_POST['ptw-number'])){
			
			$ptw = 'No';
			$ptw_number =  'N/A';
			
		} else {
			
			$ptw = 'Yes';
			$ptw_number = $_POST['ptw-number'];
			
		}
		
		mysqli_query($con, "DELETE FROM tbl_pragma WHERE JobNo = '$jobno'")or die(mysqli_error($con));
		
		mysqli_query($con, "INSERT INTO tbl_pragma (JobNo,JobId,Component,FailureTypeCode,Failure,RootCause,Repair,PTW,PTWNumber,FollowUpWork) VALUES ('$jobno','$jobid','$component','$failure_type','$failure','$root_cause','$repair','$ptw','$ptw_number','$followup')")or die(mysqli_error($con));
		
	}
	
// End insert / update Pragma table
	
}

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC LIMIT 1")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$siteid = $row['SiteId'];
$companyid = $row['CompanyId'];
$jobno = $row['JobNo'];

mysqli_query($con, "UPDATE tbl_jc SET CompanyId = '$companyid', SiteId = '$siteid', JobNo = '$jobno' WHERE JobId = '$jobid'")or die(mysqli_error($con));

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$invoiceno = $row['InvoiceNo'];

if (isset($_GET['delete'])) {
    mysqli_query($con, "UPDATE tbl_jc SET Status = '14', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

if (isset($_POST['delete'])) {
    $delete = $_POST['delete'];

    foreach($delete as $c) {

        mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysqli_error($con));
    }
}

if (isset($_POST['delete_m'])) {
    $delete = $_POST['delete_m'];

    foreach($delete as $c) {

        mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysqli_error($con));
    }
}

if (isset($_POST['delete_c'])) {
    $delete = $_POST['delete_c'];

    foreach($delete as $c) {

        mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysqli_error($con));
    }
}

// Delete transport rows
if(isset($_POST['delete_t'])){
	
	$delete = $_POST['delete_t'];
	
	foreach($delete as $c){
		
		mysqli_query($con, "DELETE FROM tbl_travel WHERE Id = '$c'") or die(mysqli_error($con));
	}
}

if(isset($_POST['date1'])){

	$date1 = $_POST['date1'];
	$date2 = $_POST['date2'];
	$jobid = $_GET['Id'];
	$service = $_POST['service'];
	
	mysqli_query($con, "UPDATE tbl_jc SET Date1 = '$date1', Date2 = '$date2', JobDescription = '$service' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

if(isset($_POST['inv_date'])){

	$date = $_POST['inv_date'];
	$date = date('d M Y',strtotime($date));
	$jobid = $_GET['Id'];
	
	mysqli_query($con, "UPDATE tbl_jc SET InvoiceDate = '$date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

// Get Invoice No
$query_invno = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceNo >= '1'")or die(mysqli_error($con));
$row_invno = mysqli_fetch_array($query_invno);

$invoiceno = $row_invno['InvoiceNo'];

// Add labour row
if($_POST['labour_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['labour_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysqli_query($con, "INSERT INTO tbl_jc (InvoiceNo,JobId,Labour,Unit) VALUES ('$invoiceno','$jobid','1','hours')") or die(mysqli_error($con));
	}
}

// Add material row
if($_POST['material_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['material_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysqli_query($con, "INSERT INTO tbl_jc (InvoiceNo,JobId,Material) VALUES ('$invoiceno','$jobid','1')") or die(mysqli_error($con));
	}
}

// Add transport row
if($_POST['transport_row'] >= 1){
		
	$jobid = $_GET['Id'];
	$rows = $_POST['transport_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysqli_query($con, "INSERT INTO tbl_travel (InvoiceNo,JobId) VALUES ('$invoiceno','$jobid')") or die(mysqli_error($con));
	}
}

// Add comment row
if($_POST['comment_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['comment_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysqli_query($con, "INSERT INTO tbl_jc (InvoiceNo,JobId,Comment) VALUES ('$invoiceno','$jobid','1')") or die(mysqli_error($con));
	}
}

$jobid = $_GET['Id'];

if (isset($_GET['update'])) {

    $query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $numrows = mysqli_num_rows($result);

    for($i = 0; $i < $numrows; $i++) {
		
        $id = $_POST['id_l'][$i];
        $labour = $_POST['labour'][$i];
        $unit = $_POST['unit_l'][$i];
        $qty = $_POST['qty_l'][$i];
        $qty_fuel = $_POST['qty_t'][0];
        $trips = $_POST['transport'][$i];

        $price = explode("_", $_POST['price_l'][$i]);

        $new_price = $price[0];
        $labour_name = $price[1];

        $total = $qty * $new_price;
		
        $total_fuel = $qty_fuel * $new_price * $trips;

        if ($unit == 'hours') {
			
            mysqli_query($con, "UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty', Price = '$new_price', Total1 = '$total', LabourType = '$labour_name' WHERE Id = '$id'") or die(mysqli_error($con));
        }
        elseif($unit == 'km') {
			
            mysqli_query($con, "UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty_fuel', Price = '$new_price', Total1 = '$total_fuel', LabourType = '$labour_name' WHERE Id = '$id'") or die(mysqli_error($con));
        }
    }
	
	// Update Material
    $query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $numrows = mysqli_num_rows($result);

    for ($i = 0; $i < $numrows; $i++) {
        $id = $_POST['id_m'][$i];
        $material = $_POST['material'][$i];
        $unit = $_POST['unit_m'][$i];
        $qty = $_POST['qty_m'][$i];
        $price = $_POST['price_m'][$i];
        $total = $qty * $price;
        mysqli_query($con, "UPDATE tbl_jc SET  Description = '$material', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysqli_error($con));
    }
	
	// Update transport
	$query = mysqli_query($con, "SELECT * FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);

	for($i=0;$i<$numrows;$i++){
		
		$id = $_POST['id_t'][$i];
		$transport = $_POST['transport'][$i];
		$unit = $_POST['unit_t'][$i];
		$qty = $_POST['qty_t'][$i];
		$price = $_POST['price_t'][$i];
		$comment = $_POST['t_comment'][$i];
		$jobno = $_POST['jobno'][$i];
		
		$travel_time_rate = $travel_time_rate_t[$i];
		
		$total_pragma = $qty *  $travel_time_rate;
		$total = $qty * $transport * $price;
		
		mysqli_query($con, "UPDATE tbl_travel SET  JobNo = '$jobno', Description = '$transport', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total', TransportComment = '$comment', TravelTime = '$travel_time', TravelTimeRate = '$travel_time_rate', TotalPragma = '$total_pragma' WHERE Id = '$id'") or die(mysqli_error($con));
	}

	if (isset($_POST['id_c'])) {
		$idc = $_POST['id_c'];
		$jobid = $_GET['Id'];
		$day_c = $_POST['day'];
		$month_c = $_POST['month'];
		$year_c = $_POST['year'];
		$hour_c = $_POST['hour'];
		$minute_c = $_POST['minute'];
		$date_c = $day." ".$month_c." ".$year_c." ".$hour_c." ".$minute_c;
		$name_c = $_POST['comment_name'];
		$comment_c = $_POST['comment'];
		$feedback_c = $_POST['feedback'];
		$fbtech_c = $_POST['tech'];
		$fb_day_c = $_POST['fb_day'];
		$fb_month_c = $_POST['fb_month'];
		$fb_year_c = $_POST['fb_year'];
		$fb_hour_c = $_POST['fb_hour'];
		$fb_minute_c = $_POST['fb_minute'];
		$fb_date_c = $fb_day_c." ".$fb_month_c." ".$fb_year_c." ".$fb_hour_c." ".$fb_minute_c;
	
		if (isset($_POST['comment1'])) {
			for ($i = 0; $i < $numrows; $i++) {
				$id = $idc[$i];
				$day = $day_c[$i];
				$month = $month_c[$i];
				$year = $year_c[$i];
				$hour = $hour_c[$i];
				$minute = $minute_c[$i];
				$date = $day_c[$i]." ".$month_c[$i]." ".$year_c[$i]." ".$hour_c[$i]." ".$minute_c[$i];
				$name = $name_c[$i];
				$comment = $comment_c[$i];
				$feedback = $feedback_c[$i];
				$feedback_c;
				$fbtech = $fbtech_c[$i];
				$fb_day = $fb_day_c[$i];
				$fb_month = $fb_month_c[$i];
				$fb_year = $fb_year_c[$i];
				$fb_hour = $fb_hour_c[$i];
				$fb_minute = $fb_minute_c[$i];
				$fb_date = $fb_day_c[$i]." ".$fb_month_c[$i]." ".$fb_year_c[$i]." ".$fb_hour_c[$i]." ".$fb_minute_c[$i];
	
				mysqli_query($con, "UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date', CommentName = '$name', CommentText = '$comment', FeedBack = '$feedback', FeedBackTech = '$fbtech', FeedBackDate = '$fb_date' WHERE Id = '$id' AND Comment = '1'") or die(mysqli_error($con));
	
			}
			
		} else {
			
			mysqli_query($con, "UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date_c', CommentName = '$name_c', CommentText = '$comment_c' ,FeedBack = '$feedback_c', FeedBackTech = '$fbtech_c', FeedBackDate = '$fb_date_c' WHERE JobId = '$jobid' AND Comment = '1' ORDER BY Id ASC LIMIT 1") or die(mysqli_error($con));
		}
	}
}
$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$subtotal_l = $row['SUM(Total1)'];

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$subtotal_m = $row['SUM(Total1)'];

$query = mysqli_query($con, "SELECT CompanyId FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$companyid = $row['CompanyId'];

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$subtotal_t = $row['SUM(Total1)'];

$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

mysqli_query($con, "UPDATE tbl_jc SET SubTotal = '$subtotal' WHERE JobId = '$jobid'") or die(mysqli_error($con));

// START
$KTColParam1_Recordset4 = $_GET["Id"];

$query_Recordset4 = "
  SELECT
	  tbl_technicians.Name AS Name_1,
	  tbl_actual_history.JobId,
	  tbl_users.Name,
	  tbl_actual_history.Date,
	  tbl_actual_history.Comments,
	  tbl_actual_history.Mobile
  FROM
	  (
		  (
			  tbl_actual_history
			  LEFT JOIN tbl_users ON tbl_users.Id = tbl_actual_history.TechnicianId
		  )
		  LEFT JOIN tbl_technicians ON tbl_technicians.Id = tbl_actual_history.TechnicianId
	  )
  WHERE
	  tbl_actual_history.JobId = '$KTColParam1_Recordset4'
  ORDER BY
	  tbl_actual_history.Id ASC";
	  
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$colname_Recordset2 = $_GET['Id'];

$Recordset2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$colname_Recordset1'") or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$jobid = $_GET['Id'];

$KTColParam1_Recordset5 = $_GET["Id"];

$query_Recordset5 = "
  SELECT
	  tbl_companies.Name,
	  tbl_companies.Id,
	  tbl_companies.Address,
	  tbl_companies.ContactName,
	  tbl_companies.ContactNumber,
	  tbl_companies.ContactEmail,
	  tbl_jc.CompanyId,
	  tbl_jc.JobId,
	  tbl_jc.Status,
	  tbl_jc.JobNo,
	  tbl_jc.InvoiceNo,
	  tbl_jc.InvoiceDate
  FROM
	  (
		  tbl_jc
		  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
	  )
  WHERE
	  tbl_jc.JobId = '$KTColParam1_Recordset5'";
	  
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$KTColParam1_Recordset3 = $_GET["Id"];

$query_Recordset3 = "
  SELECT
	  tbl_jc.CompanyId,
	  tbl_jc.JobId,
	  tbl_companies.*
  FROM
	  (
		  tbl_jc
		  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
	  )
  WHERE
	  tbl_jc.JobId = '$KTColParam1_Recordset3'
  ORDER BY
	  Id ASC
  LIMIT 1";

$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_Recordset6 = $_GET['Id'];

$Recordset6 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$colname_Recordset6'") or die(mysqli_error($con));
$row_Recordset6 = mysqli_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysqli_num_rows($Recordset6);

$query_Recordset7 = "SELECT * FROM tbl_rates";
$Recordset7 = mysqli_query($con, $query_Recordset7) or die(mysqli_error($con));
$row_Recordset7 = mysqli_fetch_assoc($Recordset7);
$totalRows_Recordset7 = mysqli_num_rows($Recordset7);

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC LIMIT 1")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$companyid = $row['CompanyId'];

$query_Recordset8 = "SELECT * FROM tbl_fuel WHERE Company = '$companyid'";
$Recordset8 = mysqli_query($con, $query_Recordset8) or die(mysqli_error($con));
$row_Recordset8 = mysqli_fetch_assoc($Recordset8);
$totalRows_Recordset8 = mysqli_num_rows($Recordset8);

$query_Recordset100 = "SELECT * FROM tbl_technicians";
$Recordset100 = mysqli_query($con, $query_Recordset100) or die(mysqli_error($con));
$row_Recordset100 = mysqli_fetch_assoc($Recordset100);
$totalRows_Recordset100 = mysqli_num_rows($Recordset100);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$jobid = $_GET['Id'];

$query_Recordset99 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Id AS Id_1, tbl_companies.Name, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Telephone, tbl_sites.Email, tbl_jc.Id, tbl_jc.JobId, tbl_jc.JobNo, tbl_jc.Date, tbl_jc.JobDescription, tbl_jc.Reference FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId = '$jobid' ORDER BY Id ASC LIMIT 1";
$Recordset99 = mysqli_query($con, $query_Recordset99) or die(mysqli_error($con));
$row_Recordset99 = mysqli_fetch_assoc($Recordset99);
$totalRows_Recordset99 = mysqli_num_rows($Recordset99);

$companyid = $row_Recordset99['Id_1'];
$query_Recordset101 = "SELECT tbl_rates.Name AS Name_1, tbl_companies.Name, tbl_rates.Rate, tbl_rates.CompanyId FROM (tbl_companies LEFT JOIN tbl_rates ON tbl_rates.CompanyId=tbl_companies.Id) WHERE tbl_rates.CompanyId='$companyid' ";
$Recordset101 = mysqli_query($con, $query_Recordset101) or die(mysqli_error($con));
$row_Recordset101 = mysqli_fetch_assoc($Recordset101);
$totalRows_Recordset101 = mysqli_num_rows($Recordset101);

$KTColParam1_rs_site_name = $_GET["Id"];

$query_rs_site_name = "
  SELECT
	  tbl_jc.JobId,
	  tbl_sites.Name,
	  tbl_sites.Address,
	  tbl_sites.Suburb
  FROM
	  (
		  tbl_jc
		  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
	  )
  WHERE
	  tbl_jc.JobId = '$KTColParam1_rs_site_name'";
$rs_site_name = mysqli_query($con, $query_rs_site_name) or die(mysqli_error($con));
$row_rs_site_name = mysqli_fetch_assoc($rs_site_name);
$totalRows_rs_site_name = mysqli_num_rows($rs_site_name);

$companyid = $row_Recordset5['Id'];

$query_rs_transport_rates = "SELECT * FROM tbl_rates WHERE CompanyId = '$companyid' AND Fuel = '1' ORDER BY Name ASC";
$rs_transport_rates = mysqli_query($con, $query_rs_transport_rates) or die(mysqli_error($con));
$row_rs_transport_rates = mysqli_fetch_assoc($rs_transport_rates);
$totalRows_rs_transport_rates = mysqli_num_rows($rs_transport_rates);

$colname_rs_pragma = $_GET['Id'];

$rs_pragma = mysqli_query($con, "SELECT * FROM tbl_pragma WHERE JobId = '$colname_rs_pragma'") or die(mysqli_error($con));
$row_rs_pragma = mysqli_fetch_assoc($rs_pragma);
$totalRows_rs_pragma = mysqli_num_rows($rs_pragma);

$companyid = $row_Recordset5['Id'];

$rs_transport_rates = mysqli_query($con, "SELECT * FROM tbl_rates WHERE CompanyId = '$companyid' AND Description LIKE '%Travel%' ORDER BY Name ASC") or die(mysqli_error($con));
$row_rs_transport_rates = mysqli_fetch_assoc($rs_transport_rates);
$totalRows_rs_transport_rates = mysqli_num_rows($rs_transport_rates);

$query_rs_components = "SELECT * FROM tbl_pragma_components ORDER BY `Description` ASC";
$rs_components = mysqli_query($con, $query_rs_components) or die(mysqli_error($con));
$row_rs_components = mysqli_fetch_assoc($rs_components);
$totalRows_rs_components = mysqli_num_rows($rs_components);

$query_rs_failure = "SELECT CompDescription, Code2, Description2 FROM tbl_pragma_failures ORDER BY Description2 ASC";
$rs_failure = mysqli_query($con, $query_rs_failure) or die(mysqli_error($con));
$row_rs_failure = mysqli_fetch_assoc($rs_failure);
$totalRows_rs_failure = mysqli_num_rows($rs_failure);
//END

$query_rs_root_cause = "SELECT * FROM tbl_pragma_root_cause ORDER BY `Description` ASC";
$rs_root_cause = mysqli_query($con, $query_rs_root_cause) or die(mysqli_error($con));
$row_rs_root_cause = mysqli_fetch_assoc($rs_root_cause);
$totalRows_rs_root_cause = mysqli_num_rows($rs_root_cause);

$query_rs_repair = "SELECT * FROM tbl_pragma_repair ORDER BY `Description` ASC";
$rs_repair = mysqli_query($con, $query_rs_repair) or die(mysqli_error($con));
$row_rs_repair = mysqli_fetch_assoc($rs_repair);
$totalRows_rs_repair = mysqli_num_rows($rs_repair);

$colname_rs_pragma = $_GET['Id'];

$rs_pragma = mysqli_query($con, "SELECT * FROM tbl_pragma WHERE JobId = '$colname_rs_pragma'") or die(mysqli_error($con));
$row_rs_pragma = mysqli_fetch_assoc($rs_pragma);
$totalRows_rs_pragma = mysqli_num_rows($rs_pragma);

$KTColParam1_job_history = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_job_history = $_GET["Id"];
}
$query_job_history = "
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
	  tbl_history_relation.JobId = '$KTColParam1_job_history'";
$job_history = mysqli_query($con, $query_job_history) or die(mysqli_error($con));
$row_job_history = mysqli_fetch_assoc($job_history);
$totalRows_job_history = mysqli_num_rows($job_history);

// Update Sub Totals
$query_update = mysqli_query($con, "SELECT SUM(Total1) AS Total FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row_update = mysqli_fetch_array($query_update);

$query_transport = mysql_query("SELECT SUM(Total1) AS Total FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
$row_transport = mysql_fetch_array($query_transport);

$sub_total = $row_update['Total'] + $row_transport['Total'];
$vat = $sub_total * 0.15;
$total = $sub_total + $vat;

mysqli_query($con, "UPDATE tbl_jc SET SubTotal = '$sub_total', VAT = '$vat', Total2 = '$total' WHERE JobId = '$jobid'")or die(mysqli_error($con));
// End Update Sub Total

// Send to approved
if(isset($_POST['complete'])){
	
	$jobid = $_GET['Id'];
	$date = date('d M Y');
	
	if($row_Recordset99['Id_1'] == 1 || $row_Recordset99['Id_1'] == 12){ // Engen requires order number
		
		$query = mysqli_query($con, "UPDATE tbl_jc SET Status = '11',  Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$search = '?'. $_SESSION['search'];
		
	} else {
		
		$query = mysqli_query($con, "UPDATE tbl_jc SET Status = '12',  Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$search = '?'. $_SESSION['search'];
	}
	
	header('Location: ../fpdf16/approved-pdf.php?Id='. $_GET['Id']);
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SEAVEST AFRICA TRADING CC</title>
  <link href="../styles/layout.css" rel="stylesheet" type="text/css" />
  <link href="../styles/fonts.css" rel="stylesheet" type="text/css">
  <link href="../font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
  
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
  
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>

  <script type="text/JavaScript">
  <!--
  function MM_openBrWindow(theURL,winName,features) { //v2.0
    window.open(theURL,winName,features);
  }
  //-->
  </script>

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
  
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
      <?php include( '../menu.php'); ?>
    </td>
    <td valign="top">
      <table width="823" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center"><img src="../images/banner.jpg" width="823" height="151">
          </td>
        </tr>
        <tr>
          <td>
            <div style="margin-left:30px">

              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <form name="form2" method="post" action="inv-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
                      <div style="margin-bottom:5px">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="td-header">
                          <tr>
                            <td>
                              <table border="0" cellpadding="2" cellspacing="0" class="td-header">
                                <tr>
                                  <td>&nbsp;Labour
                                    <select name="labour_row" class="tarea-white" id="labour_row">
                                      <option value="0">0</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                    </select>
                                    &nbsp; Materials
                                    <select name="material_row" class="tarea-white" id="material_row">
                                      <option value="0">0</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                    </select>
                                    &nbsp; Transport
                                    <select name="transport_row" class="tarea-white" id="transport_row">
                                      <option value="0">0</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                    </select>
                                    &nbsp; Comments
                                    <select name="comment_row" class="tarea-white" id="comment_row">
                                      <option value="0">0</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                    </select>
                                  </td>
                                  <td>
                                    <input name="Submit2" type="submit" class="btn-go-search-2" value="">
                                  </td>
                                  <td width="50">&nbsp;</td>
                                  <td width="50" align="right" valign="middle">&nbsp;</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </form>
                  </td>
                </tr>
              </table>
              
              <form name="form1" method="post" action="inv-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $jobid; ?>&update#btm">
              <div style="border:solid 1px #A6CAF0; background-color:#EEE">
                  <table width="100% " border="0 " cellpadding="3 " cellspacing="0">
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2 " valign="top "><table width="100% " border="0 " align="center " cellpadding="0 " cellspacing="1">
                        <tr>
                          <td width="34% " valign="top "><table width="100%" border="0" cellpadding="2" cellspacing="3" class="blue-generic">
                            <tr>
                              <td class="combo_bold"><?php echo $row_Recordset5['Name']; ?></td>
                            </tr>
                            <tr>
                              <td><?php echo $row_Recordset5['ContactName']; ?></td>
                            </tr>
                            <tr>
                              <td><?php echo nl2br($row_Recordset5['Address']); ?></td>
                            </tr>
                            <tr>
                              <td><?php echo $row_Recordset5['ContactNumber']; ?></td>
                            </tr>
                            <tr>
                              <td><?php echo $row_Recordset5['ContactEmail']; ?></td>
                            </tr>
                          </table>                            
                          
                          </td>
                          <td width="25% " valign="top " class="combo2 "><table width="100%" border="0" cellpadding="2" cellspacing="3" class="blue-generic">
                            <tr>
                              <td><?php echo $row_rs_site_name['Name']; ?></td>
                              </tr>
                            <tr>
                              <td><?php echo $row_rs_site_name['Address']; ?></td>
                              </tr>
                            <tr>
                              <td><?php echo $row_rs_site_name['Suburb']; ?></td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </table>
                          
                            </td>
                          <td width="41% " align="right " valign="top " class="combo "><div style="padding-right:10px ">
                            <table border="0" align="right" cellpadding="2" cellspacing="3" class="blue-generic">
                              <tr>
                                <td width="75"><strong>Date:</strong></td>
                                <td width="100">
                                  <?php 
								  $date = $row_Recordset5['InvoiceDate']; 
								  
								  if(!empty($date)){
									  
									  $date2 = date('Y-m-d',strtotime($date));
									  
								  } else {
									  
									  $date2 = date('Y-m-d');
								  }
								  ?>
                                  <input name="inv_date" class="tarea-100per" id="inv_date" value="<?php echo $date2; ?>">
                                  
									<script type="text/javascript">
                                    $('#inv_date').datepicker({
                                    dateFormat: "yy-mm-dd"
                                    });
                                    </script>
                                </td>
                              </tr>
                              <tr>
                                <td><strong>Reference: </strong></td>
                                <td><input name="textfield" type="text" class="tarea-100per" id="textfield" value="<?php echo $row_Recordset5[ 'JobNo']; ?>"></td>
                              </tr>
                              <tr>
                                <td><strong>Invoice No:</strong></td>
                                <td>
                                  <input name="textfield2" type="text" class="tarea-100per" id="textfield2" value="<?php echo $invoiceno; ?>"></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                            </table>
                            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="combo_bold">&nbsp;</td>
          <td width="41%" class="combo_bold">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3"><span class="combo_bold">&nbsp; Received Date</span>
            <input name="date1" class="tarea" id="date1" value="<?php echo $row_Recordset6['Date1']; ?>" /> 
            
			<script type="text/javascript">
            $('#date1').datepicker({
            dateFormat: "yy-mm-dd"
            });
            </script>
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span class="combo_bold">Requested Completion:</span>&nbsp;
            <input name="date2" class="tarea" id="date2" value="<?php echo $row_Recordset6['Date2']; ?>"\ />
            
			<script type="text/javascript">
            $('#date2').datepicker({
            dateFormat: "yy-mm-dd"
            });
            </script>
            
            &nbsp;
            <?php schedule($jobid); ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
</table>
</div>

<?php if($row_Recordset5['Id_1'] == 2){ ?>
<div style="padding-bottom:5px; padding-top:5px">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
  <tr>
    <td bgcolor="#A6CAF0" class="td-header">&nbsp; Invoice Details</td>
  </tr>
</table>
</div>

<div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
  <table border="0" cellpadding="2" cellspacing="3" class="combo">
    <tr>
      <td>
        <select name="component" class="combo" id="component" style="width:500px">
          <option value="" class="menuHeader" <?php if (!(strcmp( "", $row_rs_pragma[ 'Component']))) {echo "selected=\"selected\"";} ?>>Component</option>
          <?php do { ?>
          <option value="<?php echo $row_rs_components['Code']?>" <?php if (!(strcmp($row_rs_components[ 'Code'], $row_rs_pragma[ 'Component']))) {echo "selected=\"selected\"";} ?>>
            <?php echo $row_rs_components[ 'Description']?>
          </option>
          <?php } while ($row_rs_components = mysqli_fetch_assoc($rs_components)); 
		  $rows = mysqli_num_rows($rs_components); 
		  if($rows> 0) { mysqli_data_seek($rs_components, 0); 
		  $row_rs_components = mysqli_fetch_assoc($rs_components); 
		  }
		  ?>
        </select>
        <select name="failure-type" class="combo" id="failure-type" style="width:227px">
          <option value="" selected style="font-weight:bold" <?php if (!(strcmp( "", $row_rs_pragma[ 'FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Failure Type</option>
          <option value="GEN" <?php if (!(strcmp( "GEN", $row_rs_pragma[ 'FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Generic Type</option>
          <option value="VER" <?php if (!(strcmp( "VER", $row_rs_pragma[ 'FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Verification</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <select name="failure" class="combo" id="failure" style="width:730px">
          <option value="">Failure</option>
          <?php do { ?>
          <option value="<?php echo $row_rs_failure['Code2']; ?>" <?php if (!(strcmp($row_rs_failure[ 'Code2'], $row_rs_pragma[ 'Failure']))) {echo "selected=\"selected\"";} ?>>
            <?php echo $row_rs_failure[ 'Description2'] . ' - '. $row_rs_failure[ 'CompDescription']; ?>
          </option>
          <?php 
		  } while ($row_rs_failure = mysqli_fetch_assoc($rs_failure)); 
		  $rows = mysqli_num_rows($rs_failure); 
		  if($rows> 0) {
			  
			  mysqli_data_seek($rs_failure, 0); 
			  $row_rs_failure = mysqli_fetch_assoc($rs_failure); 
		  } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <select name="root-cause" class="combo" id="root-cause" style="width:248px">
          <option value="" <?php if (!(strcmp( "", $row_rs_pragma[ 'RootCause']))) {echo "selected=\"selected\"";} ?>>Root Cause</option>
          <?php do { ?>
          <option value="<?php echo $row_rs_root_cause['Code']?>" <?php if (!(strcmp($row_rs_root_cause[ 'Code'], $row_rs_pragma[ 'RootCause']))) {echo "selected=\"selected\"";} ?>>
            <?php echo $row_rs_root_cause[ 'Description']?>
          </option>
          <?php 
		  } while ($row_rs_root_cause = mysqli_fetch_assoc($rs_root_cause)); 
		  $rows = mysqli_num_rows($rs_root_cause); 
		  
		  if($rows> 0) {
			  
			  mysqli_data_seek($rs_root_cause, 0); 
			  $row_rs_root_cause = mysqli_fetch_assoc($rs_root_cause); 
		  }
		  ?>
        </select>
        <select name="repair" id="repair" class="combo" style="width:248px">
          <option value="" <?php if (!(strcmp( "", $row_rs_pragma[ 'Repair']))) {echo "selected=\"selected\"";} ?>>Repair</option>
          <?php do { ?>
          <option value="<?php echo $row_rs_repair['Code']?>" <?php if (!(strcmp($row_rs_repair[ 'Code'], $row_rs_pragma[ 'Repair']))) {echo "selected=\"selected\"";} ?>>
            <?php echo $row_rs_repair[ 'Description']?>
          </option>
          <?php
          } while ($row_rs_repair = mysqli_fetch_assoc($rs_repair)); 
		  $rows = mysqli_num_rows($rs_repair); 
		  
		  if($rows> 0) {
			  
			  mysqli_data_seek($rs_repair, 0); 
			  $row_rs_repair = mysqli_fetch_assoc($rs_repair); 
		  }
		  ?>
        </select>
        <input name="ptw-number" type="text" class="combo" id="ptw-number" style="width:227px" onFocus="if(this.value=='PTW Number'){this.value=''}" onBlur="if(this.value==''){this.value='PTW Number'}" value="<?php if(!empty($row_rs_pragma['PTWNumber'])){ echo $row_rs_pragma['PTWNumber']; } else { ?>PTW Number<?php } ?>">
      </td>
    </tr>
    <tr>
      <td>
        <table border="0" cellpadding="0" cellspacing="0" class="combo">
          <tr>
            <td width="152">Follow up work required</td>
            <td width="163">
              <table border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td width="20">
                    <input type="radio" <?php if($row_rs_pragma[ 'FollowUpWork']=="Yes" ) {echo "checked=\"checked\"";} ?> name="followup" id="followup" value="Yes"></td>
                  <td width="21">Yes</td>
                  <td width="20">
                    <input name="followup" <?php if ($row_rs_pragma[ 'FollowUpWork']=="No" ) {echo "checked=\"checked\"";} ?> type="radio" id="followup" value="No"></td>
                  <td width="37">No</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
<?php } ?>

<div style="padding-bottom:5px; padding-top:5px; padding-bottom:5px">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#A6CAF0" class="td-header">&nbsp; Service Requested</td>
    </tr>
  </table>
  </div>
  
  <div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE; overflow:hidden">
  <textarea name="service" rows="5" class="tarea-100per" id="service"><?php echo $row_Recordset6[ 'JobDescription']; ?></textarea>
  </div>

<div style="padding-bottom:3px; padding-top:5px">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#A6CAF0" class="td-header">&nbsp; History</td>
    </tr>
  </table>
</div>

<div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE; overflow:hidden; margin-bottom:5px">
  <div id="history-log">
    <?php do { ?>
    <span class="combo" style="color:#306294; background-image:url(../images/icons/history-bg.jpg); height:19px; border:solid 1px #85afd7; font-size:8px"><b>
                        
                        <?php 
	    $jobid = $row['JobId'];
	    $string = explode(" ",$row_Recordset4['Date']);
	
	    $date = $string[0];
	    $time = $string[1];
		$split_time = explode(":", $time);
		
		$new_time = $split_time[0] .':'. $split_time[1];
	
	    $date_split = explode("-",$date);
	
	    $day = $date_split[2];
	    $month = $date_split[1];
	    $year = $date_split[2];
		
		if($row_Recordset4['Mobile'] == 1){ 
		
		echo $row_Recordset4['Name_1'];
		
		} else { 
		
		echo $row_Recordset4['Name'];
		
		} 
		?></b>&nbsp;<span class="combo" style="font-size:8px; color:#4383C2"><?php echo $day .' / '. $month .' '. $new_time; ?></span>
    </span>
    &nbsp;
    <?php echo '<span class="combo">'. nl2br($row_Recordset4[ 'Comments']) .'</span>'; ?>
    <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
  </div>
</div>



<?php 
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'" ;
$result = mysqli_query($con,$query) or die(mysqli_error());
$numrows = mysqli_num_rows($result); 
if($numrows>= 2){
	
	while($row = mysqli_fetch_array($result)){
		
		$date = $row['CommentDate']; 
		$name = $row['CommentName']; 
		$comment = $row['CommentText']; 
		$split = explode(" ", $date); 
		$year = $split[2]; 
		$month = $split[1]; 
		$day = $split[0]; 
		$hour = $split[3]; 
		$minute = $split[4]; 
?>
<div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"> <br>
                <div style="padding-bottom:5px; ">
                  <select name="day[] " class="tarea " id="day[] ">
                    <option value="1 " selected <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\" ";} ?>>1</option>
                    <option value="2 " <?php if (!(strcmp(2, $day))) {echo "selected=\"selected\" ";} ?>>2</option>
                    <option value="3 " <?php if (!(strcmp(3, $day))) {echo "selected=\"selected\" ";} ?>>3</option>
                    <option value="4 " <?php if (!(strcmp(4, $day))) {echo "selected=\"selected\" ";} ?>>4</option>
                    <option value="5 " <?php if (!(strcmp(5, $day))) {echo "selected=\"selected\" ";} ?>>5</option>
                    <option value="6 " <?php if (!(strcmp(6, $day))) {echo "selected=\"selected\" ";} ?>>6</option>
                    <option value="7 " <?php if (!(strcmp(7, $day))) {echo "selected=\"selected\" ";} ?>>7</option>
                    <option value="8 " <?php if (!(strcmp(8, $day))) {echo "selected=\"selected\" ";} ?>>8</option>
                    <option value="9 " <?php if (!(strcmp(9, $day))) {echo "selected=\"selected\" ";} ?>>9</option>
                    <option value="10 " <?php if (!(strcmp(10, $day))) {echo "selected=\"selected\" ";} ?>>10</option>
                    <option value="11 " <?php if (!(strcmp(11, $day))) {echo "selected=\"selected\" ";} ?>>11</option>
                    <option value="12 " <?php if (!(strcmp(12, $day))) {echo "selected=\"selected\" ";} ?>>12</option>
                    <option value="13 " <?php if (!(strcmp(13, $day))) {echo "selected=\"selected\" ";} ?>>13</option>
                    <option value="14 " <?php if (!(strcmp(14, $day))) {echo "selected=\"selected\" ";} ?>>14</option>
                    <option value="15 " <?php if (!(strcmp(15, $day))) {echo "selected=\"selected\" ";} ?>>15</option>
                    <option value="16 " <?php if (!(strcmp(16, $day))) {echo "selected=\"selected\" ";} ?>>16</option>
                    <option value="17 " <?php if (!(strcmp(17, $day))) {echo "selected=\"selected\" ";} ?>>17</option>
                    <option value="18 " <?php if (!(strcmp(18, $day))) {echo "selected=\"selected\" ";} ?>>18</option>
                    <option value="19 " <?php if (!(strcmp(19, $day))) {echo "selected=\"selected\" ";} ?>>19</option>
                    <option value="20 " <?php if (!(strcmp(20, $day))) {echo "selected=\"selected\" ";} ?>>20</option>
                    <option value="21 " <?php if (!(strcmp(21, $day))) {echo "selected=\"selected\" ";} ?>>21</option>
                    <option value="22 " <?php if (!(strcmp(22, $day))) {echo "selected=\"selected\" ";} ?>>22</option>
                    <option value="23 " <?php if (!(strcmp(23, $day))) {echo "selected=\"selected\" ";} ?>>23</option>
                    <option value="24 " <?php if (!(strcmp(24, $day))) {echo "selected=\"selected\" ";} ?>>24</option>
                    <option value="25 " <?php if (!(strcmp(25, $day))) {echo "selected=\"selected\" ";} ?>>25</option>
                    <option value="26 " <?php if (!(strcmp(26, $day))) {echo "selected=\"selected\" ";} ?>>26</option>
                    <option value="27 " <?php if (!(strcmp(27, $day))) {echo "selected=\"selected\" ";} ?>>27</option>
                    <option value="28 " <?php if (!(strcmp(28, $day))) {echo "selected=\"selected\" ";} ?>>28</option>
                    <option value="29 " <?php if (!(strcmp(29, $day))) {echo "selected=\"selected\" ";} ?>>29</option>
                    <option value="30 " <?php if (!(strcmp(30, $day))) {echo "selected=\"selected\" ";} ?>>30</option>
                    <option value="31 " <?php if (!(strcmp(31, $day))) {echo "selected=\"selected\" ";} ?>>31</option>
                  </select>
                  <select name="month[] " class="tarea " id="month[] ">
                    <option value="January " <?php if (!(strcmp("January ", $month))) {echo "selected=\"selected\" ";} ?>>January</option>
                    <option value="February " <?php if (!(strcmp("February ", $month))) {echo "selected=\"selected\" ";} ?>>February</option>
                    <option value="March " <?php if (!(strcmp("March ", $month))) {echo "selected=\"selected\" ";} ?>>March</option>
                    <option value="April " <?php if (!(strcmp("April ", $month))) {echo "selected=\"selected\" ";} ?>>April</option>
                    <option value="May " <?php if (!(strcmp("May ", $month))) {echo "selected=\"selected\" ";} ?>>May</option>
                    <option value="June " <?php if (!(strcmp("June ", $month))) {echo "selected=\"selected\" ";} ?>>June</option>
                    <option value="July " <?php if (!(strcmp("July ", $month))) {echo "selected=\"selected\" ";} ?>>July</option>
                    <option value="August " <?php if (!(strcmp("August ", $month))) {echo "selected=\"selected\" ";} ?>>August</option>
                    <option value="September " <?php if (!(strcmp("September ", $month))) {echo "selected=\"selected\" ";} ?>>September</option>
                    <option value="October " <?php if (!(strcmp("October ", $month))) {echo "selected=\"selected\" ";} ?>>October</option>
                    <option value="November " <?php if (!(strcmp("November ", $month))) {echo "selected=\"selected\" ";} ?>>November</option>
                    <option value="December " <?php if (!(strcmp("December ", $month))) {echo "selected=\"selected\" ";} ?>>December</option>
                  </select>
                  <select name="year[] " class="tarea " id="year[] ">
                    <option value="2009 " selected <?php if (!(strcmp(2009, $year))) {echo "selected=\"selected\" ";} ?>>2009</option>
                    <option value="2010 " <?php if (!(strcmp(2010, $year))) {echo "selected=\"selected\" ";} ?>>2010</option>
                    <option value="2011 " <?php if (!(strcmp(2011, $year))) {echo "selected=\"selected\" ";} ?>>2011</option>
                    <option value="2012 " <?php if (!(strcmp(2012, $year))) {echo "selected=\"selected\" ";} ?>>2012</option>
                    <option value="2013 " <?php if (!(strcmp(2013, $year))) {echo "selected=\"selected\" ";} ?>>2013</option>
                    <option value="2014 " <?php if (!(strcmp(2014, $year))) {echo "selected=\"selected\" ";} ?>>2014</option>
                    <option value="2015 " <?php if (!(strcmp(2015, $year))) {echo "selected=\"selected\" ";} ?>>2015</option>
                    <option value="2017 " <?php if (!(strcmp(2017, $year))) {echo "selected=\"selected\" ";} ?>>2016</option>
                    <option value="2018 " <?php if (!(strcmp(2018, $year))) {echo "selected=\"selected\" ";} ?>>2018</option>
                    <option value="2019 " <?php if (!(strcmp(2019, $year))) {echo "selected=\"selected\" ";} ?>>2019</option>
                    <option value="2020 " <?php if (!(strcmp(2020, $year))) {echo "selected=\"selected\" ";} ?>>2020</option>
                  </select>
                  <select name="hour[] " class="tarea " id="hour[] ">
                    <option value="1 " selected <?php if (!(strcmp(1, $hour))) {echo "selected=\"selected\" ";} ?>>1</option>
                    <option value="2 " <?php if (!(strcmp(2, $hour))) {echo "selected=\"selected\" ";} ?>>2</option>
                    <option value="3 " <?php if (!(strcmp(3, $hour))) {echo "selected=\"selected\" ";} ?>>3</option>
                    <option value="4 " <?php if (!(strcmp(4, $hour))) {echo "selected=\"selected\" ";} ?>>4</option>
                    <option value="5 " <?php if (!(strcmp(5, $hour))) {echo "selected=\"selected\" ";} ?>>5</option>
                    <option value="6 " <?php if (!(strcmp(6, $hour))) {echo "selected=\"selected\" ";} ?>>6</option>
                    <option value="7 " <?php if (!(strcmp(7, $hour))) {echo "selected=\"selected\" ";} ?>>7</option>
                    <option value="8 " <?php if (!(strcmp(8, $hour))) {echo "selected=\"selected\" ";} ?>>8</option>
                    <option value="9 " <?php if (!(strcmp(9, $hour))) {echo "selected=\"selected\" ";} ?>>9</option>
                    <option value="10 " <?php if (!(strcmp(10, $hour))) {echo "selected=\"selected\" ";} ?>>10</option>
                    <option value="11 " <?php if (!(strcmp(11, $hour))) {echo "selected=\"selected\" ";} ?>>11</option>
                    <option value="12 " <?php if (!(strcmp(12, $hour))) {echo "selected=\"selected\" ";} ?>>12</option>
                    <option value="13 " <?php if (!(strcmp(13, $hour))) {echo "selected=\"selected\" ";} ?>>13</option>
                    <option value="14 " <?php if (!(strcmp(14, $hour))) {echo "selected=\"selected\" ";} ?>>14</option>
                    <option value="15 " <?php if (!(strcmp(15, $hour))) {echo "selected=\"selected\" ";} ?>>15</option>
                    <option value="16 " <?php if (!(strcmp(16, $hour))) {echo "selected=\"selected\" ";} ?>>16</option>
                    <option value="17 " <?php if (!(strcmp(17, $hour))) {echo "selected=\"selected\" ";} ?>>17</option>
                    <option value="18 " <?php if (!(strcmp(18, $hour))) {echo "selected=\"selected\" ";} ?>>18</option>
                    <option value="19 " <?php if (!(strcmp(19, $hour))) {echo "selected=\"selected\" ";} ?>>19</option>
                    <option value="20 " <?php if (!(strcmp(20, $hour))) {echo "selected=\"selected\" ";} ?>>20</option>
                    <option value="21 " <?php if (!(strcmp(21, $hour))) {echo "selected=\"selected\" ";} ?>>21</option>
                    <option value="22 " <?php if (!(strcmp(22, $hour))) {echo "selected=\"selected\" ";} ?>>22</option>
                    <option value="23 " <?php if (!(strcmp(23, $hour))) {echo "selected=\"selected\" ";} ?>>23</option>
                    <option value="24 " <?php if (!(strcmp(24, $hour))) {echo "selected=\"selected\" ";} ?>>24</option>
                  </select>
                  <select name="minute[] " class="tarea " id="minute[] ">
                    <option value="00 " selected <?php if (!(strcmp(00, $minute))) {echo "selected=\"selected\" ";} ?>>00</option>
                    <option value="05 " <?php if (!(strcmp(05, $minute))) {echo "selected=\"selected\" ";} ?>>05</option>
                    <option value="10 " <?php if (!(strcmp(10, $minute))) {echo "selected=\"selected\" ";} ?>>10</option>
                    <option value="15 " <?php if (!(strcmp(15, $minute))) {echo "selected=\"selected\" ";} ?>>15</option>
                    <option value="20 " <?php if (!(strcmp(20, $minute))) {echo "selected=\"selected\" ";} ?>>20</option>
                    <option value="25 " <?php if (!(strcmp(25, $minute))) {echo "selected=\"selected\" ";} ?>>25</option>
                    <option value="30 " <?php if (!(strcmp(30, $minute))) {echo "selected=\"selected\" ";} ?>>30</option>
                    <option value="35 " <?php if (!(strcmp(35, $minute))) {echo "selected=\"selected\" ";} ?>>35</option>
                    <option value="40 " <?php if (!(strcmp(40, $minute))) {echo "selected=\"selected\" ";} ?>>40</option>
                    <option value="45 " <?php if (!(strcmp(45, $minute))) {echo "selected=\"selected\" ";} ?>>45</option>
                    <option value="50 " <?php if (!(strcmp(50, $minute))) {echo "selected=\"selected\" ";} ?>>50</option>
                    <option value="55 " <?php if (!(strcmp(55, $minute))) {echo "selected=\"selected\" ";} ?>>55</option>
                  </select>
                  &nbsp;
                  <input name="history-date " type="text " class="tarea " id="textfield ">
                  <input name="comment_name[] " type="text " class="tarea " id="comment_name[] " value="<?php echo $row[ 'CommentName']; ?>" size="87">
  <input name="delete_c[]" type="checkbox" id="delete_c[]" value="<?php echo $row['Id']; ?>">
</div>
<br>
<input name="comment1" type="hidden" id="comment1" value="<?php echo $row['Id']; ?>">
<input name="id_c[]" type="hidden" id="id_c[]" value="<?php echo $row['Id']; ?>">
<textarea name="comment[]" rows="4" class="tarea-100per" id="textarea" type="text" value="<?php echo $row['CommentText']; ?>">
  <?php echo $row[ 'CommentText']; ?>
</textarea>
<br>
<br>
</div>
<?php }} else { ?>
<div>
  <div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE; overflow:hidden">
    <div style="padding-bottom:5px;">
      <select name="day" class="tarea" id="day">
        <option value="1" <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\"";} ?>>1</option>
        <option value="2" <?php if (!(strcmp(2, $day))) {echo "selected=\"selected\"";} ?>>2</option>
        <option value="3" <?php if (!(strcmp(3, $day))) {echo "selected=\"selected\"";} ?>>3</option>
        <option value="4" <?php if (!(strcmp(4, $day))) {echo "selected=\"selected\"";} ?>>4</option>
        <option value="5" <?php if (!(strcmp(5, $day))) {echo "selected=\"selected\"";} ?>>5</option>
        <option value="6" <?php if (!(strcmp(6, $day))) {echo "selected=\"selected\"";} ?>>6</option>
        <option value="7" <?php if (!(strcmp(7, $day))) {echo "selected=\"selected\"";} ?>>7</option>
        <option value="8" <?php if (!(strcmp(8, $day))) {echo "selected=\"selected\"";} ?>>8</option>
        <option value="9" <?php if (!(strcmp(9, $day))) {echo "selected=\"selected\"";} ?>>9</option>
        <option value="10" <?php if (!(strcmp(10, $day))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="11" <?php if (!(strcmp(11, $day))) {echo "selected=\"selected\"";} ?>>11</option>
        <option value="12" <?php if (!(strcmp(12, $day))) {echo "selected=\"selected\"";} ?>>12</option>
        <option value="13" <?php if (!(strcmp(13, $day))) {echo "selected=\"selected\"";} ?>>13</option>
        <option value="14" <?php if (!(strcmp(14, $day))) {echo "selected=\"selected\"";} ?>>14</option>
        <option value="15" <?php if (!(strcmp(15, $day))) {echo "selected=\"selected\"";} ?>>15</option>
        <option value="16" <?php if (!(strcmp(16, $day))) {echo "selected=\"selected\"";} ?>>16</option>
        <option value="17" <?php if (!(strcmp(17, $day))) {echo "selected=\"selected\"";} ?>>17</option>
        <option value="18" <?php if (!(strcmp(18, $day))) {echo "selected=\"selected\"";} ?>>18</option>
        <option value="19" <?php if (!(strcmp(19, $day))) {echo "selected=\"selected\"";} ?>>19</option>
        <option value="20" <?php if (!(strcmp(20, $day))) {echo "selected=\"selected\"";} ?>>20</option>
        <option value="21" <?php if (!(strcmp(21, $day))) {echo "selected=\"selected\"";} ?>>21</option>
        <option value="22" <?php if (!(strcmp(22, $day))) {echo "selected=\"selected\"";} ?>>22</option>
        <option value="23" <?php if (!(strcmp(23, $day))) {echo "selected=\"selected\"";} ?>>23</option>
        <option value="24" <?php if (!(strcmp(24, $day))) {echo "selected=\"selected\"";} ?>>24</option>
        <option value="25" <?php if (!(strcmp(25, $day))) {echo "selected=\"selected\"";} ?>>25</option>
        <option value="26" <?php if (!(strcmp(26, $day))) {echo "selected=\"selected\"";} ?>>26</option>
        <option value="27" <?php if (!(strcmp(27, $day))) {echo "selected=\"selected\"";} ?>>27</option>
        <option value="28" <?php if (!(strcmp(28, $day))) {echo "selected=\"selected\"";} ?>>28</option>
        <option value="29" <?php if (!(strcmp(29, $day))) {echo "selected=\"selected\"";} ?>>29</option>
        <option value="30" <?php if (!(strcmp(30, $day))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="31" <?php if (!(strcmp(31, $day))) {echo "selected=\"selected\"";} ?>>31</option>
      </select>
      <select name="month" class="tarea" id="month">
        <option value="January" <?php if (!(strcmp( "January", $month))) {echo "selected=\"selected\"";} ?>>January</option>
        <option value="February" <?php if (!(strcmp( "February", $month))) {echo "selected=\"selected\"";} ?>>February</option>
        <option value="March" <?php if (!(strcmp( "March", $month))) {echo "selected=\"selected\"";} ?>>March</option>
        <option value="April" <?php if (!(strcmp( "April", $month))) {echo "selected=\"selected\"";} ?>>April</option>
        <option value="May" <?php if (!(strcmp( "May", $month))) {echo "selected=\"selected\"";} ?>>May</option>
        <option value="June" <?php if (!(strcmp( "June", $month))) {echo "selected=\"selected\"";} ?>>June</option>
        <option value="July" <?php if (!(strcmp( "July", $month))) {echo "selected=\"selected\"";} ?>>July</option>
        <option value="August" <?php if (!(strcmp( "August", $month))) {echo "selected=\"selected\"";} ?>>August</option>
        <option value="September" <?php if (!(strcmp( "September", $month))) {echo "selected=\"selected\"";} ?>>September</option>
        <option value="October" <?php if (!(strcmp( "October", $month))) {echo "selected=\"selected\"";} ?>>October</option>
        <option value="November" <?php if (!(strcmp( "November", $month))) {echo "selected=\"selected\"";} ?>>November</option>
        <option value="December" <?php if (!(strcmp( "December", $month))) {echo "selected=\"selected\"";} ?>>December</option>
      </select>
      <select name="year" class="tarea" id="year">
        <option value="2009" selected="selected" <?php if (!(strcmp(2009, $year))) {echo "selected=\"selected\"";} ?>>2009</option>
        <option value="2010" <?php if (!(strcmp(2010, $year))) {echo "selected=\"selected\"";} ?>>2010</option>
        <option value="2011" <?php if (!(strcmp(2011, $year))) {echo "selected=\"selected\"";} ?>>2011</option>
        <option value="2012" <?php if (!(strcmp(2012, $year))) {echo "selected=\"selected\"";} ?>>2012</option>
        <option value="2013" <?php if (!(strcmp(2013, $year))) {echo "selected=\"selected\"";} ?>>2013</option>
        <option value="2014" <?php if (!(strcmp(2014, $year))) {echo "selected=\"selected\"";} ?>>2014</option>
        <option value="2015" <?php if (!(strcmp(2015, $year))) {echo "selected=\"selected\"";} ?>>2015</option>
        <option value="2017" <?php if (!(strcmp(2017, $year))) {echo "selected=\"selected\"";} ?>>2016</option>
        <option value="2018" <?php if (!(strcmp(2018, $year))) {echo "selected=\"selected\"";} ?>>2018</option>
        <option value="2019" <?php if (!(strcmp(2019, $year))) {echo "selected=\"selected\"";} ?>>2019</option>
        <option value="2020" <?php if (!(strcmp(2020, $year))) {echo "selected=\"selected\"";} ?>>2020</option>
      </select>
      <select name="hour" class="tarea" id="hour">
        <option value="1" selected="selected" <?php if (!(strcmp(1, $hour))) {echo "selected=\"selected\"";} ?>>1</option>
        <option value="2" <?php if (!(strcmp(2, $hour))) {echo "selected=\"selected\"";} ?>>2</option>
        <option value="3" <?php if (!(strcmp(3, $hour))) {echo "selected=\"selected\"";} ?>>3</option>
        <option value="4" <?php if (!(strcmp(4, $hour))) {echo "selected=\"selected\"";} ?>>4</option>
        <option value="5" <?php if (!(strcmp(5, $hour))) {echo "selected=\"selected\"";} ?>>5</option>
        <option value="6" <?php if (!(strcmp(6, $hour))) {echo "selected=\"selected\"";} ?>>6</option>
        <option value="7" <?php if (!(strcmp(7, $hour))) {echo "selected=\"selected\"";} ?>>7</option>
        <option value="8" <?php if (!(strcmp(8, $hour))) {echo "selected=\"selected\"";} ?>>8</option>
        <option value="9" <?php if (!(strcmp(9, $hour))) {echo "selected=\"selected\"";} ?>>9</option>
        <option value="10" <?php if (!(strcmp(10, $hour))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="11" <?php if (!(strcmp(11, $hour))) {echo "selected=\"selected\"";} ?>>11</option>
        <option value="12" <?php if (!(strcmp(12, $hour))) {echo "selected=\"selected\"";} ?>>12</option>
        <option value="13" <?php if (!(strcmp(13, $hour))) {echo "selected=\"selected\"";} ?>>13</option>
        <option value="14" <?php if (!(strcmp(14, $hour))) {echo "selected=\"selected\"";} ?>>14</option>
        <option value="15" <?php if (!(strcmp(15, $hour))) {echo "selected=\"selected\"";} ?>>15</option>
        <option value="16" <?php if (!(strcmp(16, $hour))) {echo "selected=\"selected\"";} ?>>16</option>
        <option value="17" <?php if (!(strcmp(17, $hour))) {echo "selected=\"selected\"";} ?>>17</option>
        <option value="18" <?php if (!(strcmp(18, $hour))) {echo "selected=\"selected\"";} ?>>18</option>
        <option value="19" <?php if (!(strcmp(19, $hour))) {echo "selected=\"selected\"";} ?>>19</option>
        <option value="20" <?php if (!(strcmp(20, $hour))) {echo "selected=\"selected\"";} ?>>20</option>
        <option value="21" <?php if (!(strcmp(21, $hour))) {echo "selected=\"selected\"";} ?>>21</option>
        <option value="22" <?php if (!(strcmp(22, $hour))) {echo "selected=\"selected\"";} ?>>22</option>
        <option value="23" <?php if (!(strcmp(23, $hour))) {echo "selected=\"selected\"";} ?>>23</option>
        <option value="24" <?php if (!(strcmp(24, $hour))) {echo "selected=\"selected\"";} ?>>24</option>
      </select>
      <select name="minute" class="tarea" id="minute">
        <option value="00" selected="selected" <?php if (!(strcmp(00, $minute))) {echo "selected=\"selected\"";} ?>>00</option>
        <option value="05" <?php if (!(strcmp(05, $minute))) {echo "selected=\"selected\"";} ?>>05</option>
        <option value="10" <?php if (!(strcmp(10, $minute))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="15" <?php if (!(strcmp(15, $minute))) {echo "selected=\"selected\"";} ?>>15</option>
        <option value="20" <?php if (!(strcmp(20, $minute))) {echo "selected=\"selected\"";} ?>>20</option>
        <option value="25" <?php if (!(strcmp(25, $minute))) {echo "selected=\"selected\"";} ?>>25</option>
        <option value="30" <?php if (!(strcmp(30, $minute))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="35" <?php if (!(strcmp(35, $minute))) {echo "selected=\"selected\"";} ?>>35</option>
        <option value="40" <?php if (!(strcmp(40, $minute))) {echo "selected=\"selected\"";} ?>>40</option>
        <option value="45" <?php if (!(strcmp(45, $minute))) {echo "selected=\"selected\"";} ?>>45</option>
        <option value="50" <?php if (!(strcmp(50, $minute))) {echo "selected=\"selected\"";} ?>>50</option>
        <option value="55" <?php if (!(strcmp(55, $minute))) {echo "selected=\"selected\"";} ?>>55</option>
      </select>
      &nbsp;
      <input name="comment_name" type="text" class="tarea" style="width:190px" id="comment_name" value="<?php echo $row['CommentName']; ?>" />
      <select name="tech" class="tarea" id="tech" style="width:195px; height:15px">
        <option value="" <?php if (!(strcmp( "", $row[ 'FeedBackTech']))) {echo "selected=\"selected\"";} ?>>Select one...</option>
        <?php do { ?>
        <option value="<?php echo $row_Recordset100['Id']?>" <?php if (!(strcmp($row_Recordset100[ 'Id'], $row[ 'FeedBackTech']))) {echo "selected=\"selected\"";} ?>>
          <?php echo $row_Recordset100[ 'Name']?>
        </option>
        <?php 
		} while ($row_Recordset100 = mysqli_fetch_assoc($Recordset100)); 
		$rows = mysqli_num_rows($Recordset100); 
		
		if($rows> 0) {
			
			mysqli_data_seek($Recordset100, 0); 
			$row_Recordset100 = mysqli_fetch_assoc($Recordset100); 
		}
		?>
      </select>
      <input name="delete_c" type="checkbox" id="delete_c" value="<?php echo $row['Id']; ?>" />
    </div>
    <textarea name="comment" rows="8" class="tarea-100per" id="comment" type="text">
      <?php echo $row[ 'CommentText']; ?>
    </textarea>
  </div>
  <div style="padding-bottom:5px; padding-top:5px">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#A6CAF0" class="td-header">&nbsp;Feed Back </td>
      </tr>
    </table>
  </div>
  
  <?php 
  $jobid = $_GET[ 'Id']; 
  $query="SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'"; 
  $result = mysqli_query($con, $query) or die(mysqli_error()); 
  $numrows = mysqli_num_rows($result);
  
  $row = mysqli_fetch_array($result); 
  $fb_date = $row[ 'FeedBackDate']; 
  $split = explode( " ", $fb_date); 
  $day = $split[0]; 
  $month = $split[1]; 
  $year = $split[2]; 
  $hour = $split[3];
  $minute = $split[4]; 
  ?>
  
  <div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE; overflow:hidden">
    <div style="padding-bottom:5px;">
      <select name="fb_day" class="tarea" id="fb_day">
        <option value="1" selected="selected" <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\"";} ?>>1</option>
        <option value="2" <?php if (!(strcmp(2, $day))) {echo "selected=\"selected\"";} ?>>2</option>
        <option value="3" <?php if (!(strcmp(3, $day))) {echo "selected=\"selected\"";} ?>>3</option>
        <option value="4" <?php if (!(strcmp(4, $day))) {echo "selected=\"selected\"";} ?>>4</option>
        <option value="5" <?php if (!(strcmp(5, $day))) {echo "selected=\"selected\"";} ?>>5</option>
        <option value="6" <?php if (!(strcmp(6, $day))) {echo "selected=\"selected\"";} ?>>6</option>
        <option value="7" <?php if (!(strcmp(7, $day))) {echo "selected=\"selected\"";} ?>>7</option>
        <option value="8" <?php if (!(strcmp(8, $day))) {echo "selected=\"selected\"";} ?>>8</option>
        <option value="9" <?php if (!(strcmp(9, $day))) {echo "selected=\"selected\"";} ?>>9</option>
        <option value="10" <?php if (!(strcmp(10, $day))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="11" <?php if (!(strcmp(11, $day))) {echo "selected=\"selected\"";} ?>>11</option>
        <option value="12" <?php if (!(strcmp(12, $day))) {echo "selected=\"selected\"";} ?>>12</option>
        <option value="13" <?php if (!(strcmp(13, $day))) {echo "selected=\"selected\"";} ?>>13</option>
        <option value="14" <?php if (!(strcmp(14, $day))) {echo "selected=\"selected\"";} ?>>14</option>
        <option value="15" <?php if (!(strcmp(15, $day))) {echo "selected=\"selected\"";} ?>>15</option>
        <option value="16" <?php if (!(strcmp(16, $day))) {echo "selected=\"selected\"";} ?>>16</option>
        <option value="17" <?php if (!(strcmp(17, $day))) {echo "selected=\"selected\"";} ?>>17</option>
        <option value="18" <?php if (!(strcmp(18, $day))) {echo "selected=\"selected\"";} ?>>18</option>
        <option value="19" <?php if (!(strcmp(19, $day))) {echo "selected=\"selected\"";} ?>>19</option>
        <option value="20" <?php if (!(strcmp(20, $day))) {echo "selected=\"selected\"";} ?>>20</option>
        <option value="21" <?php if (!(strcmp(21, $day))) {echo "selected=\"selected\"";} ?>>21</option>
        <option value="22" <?php if (!(strcmp(22, $day))) {echo "selected=\"selected\"";} ?>>22</option>
        <option value="23" <?php if (!(strcmp(23, $day))) {echo "selected=\"selected\"";} ?>>23</option>
        <option value="24" <?php if (!(strcmp(24, $day))) {echo "selected=\"selected\"";} ?>>24</option>
        <option value="25" <?php if (!(strcmp(25, $day))) {echo "selected=\"selected\"";} ?>>25</option>
        <option value="26" <?php if (!(strcmp(26, $day))) {echo "selected=\"selected\"";} ?>>26</option>
        <option value="27" <?php if (!(strcmp(27, $day))) {echo "selected=\"selected\"";} ?>>27</option>
        <option value="28" <?php if (!(strcmp(28, $day))) {echo "selected=\"selected\"";} ?>>28</option>
        <option value="29" <?php if (!(strcmp(29, $day))) {echo "selected=\"selected\"";} ?>>29</option>
        <option value="30" <?php if (!(strcmp(30, $day))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="31" <?php if (!(strcmp(31, $day))) {echo "selected=\"selected\"";} ?>>31</option>
      </select>
      <select name="fb_month" class="tarea" id="select2">
        <option value="January" <?php if (!(strcmp( "January", $month))) {echo "selected=\"selected\"";} ?>>January</option>
        <option value="February" <?php if (!(strcmp( "February", $month))) {echo "selected=\"selected\"";} ?>>February</option>
        <option value="March" <?php if (!(strcmp( "March", $month))) {echo "selected=\"selected\"";} ?>>March</option>
        <option value="April" <?php if (!(strcmp( "April", $month))) {echo "selected=\"selected\"";} ?>>April</option>
        <option value="May" <?php if (!(strcmp( "May", $month))) {echo "selected=\"selected\"";} ?>>May</option>
        <option value="June" <?php if (!(strcmp( "June", $month))) {echo "selected=\"selected\"";} ?>>June</option>
        <option value="July" <?php if (!(strcmp( "July", $month))) {echo "selected=\"selected\"";} ?>>July</option>
        <option value="August" <?php if (!(strcmp( "August", $month))) {echo "selected=\"selected\"";} ?>>August</option>
        <option value="September" <?php if (!(strcmp( "September", $month))) {echo "selected=\"selected\"";} ?>>September</option>
        <option value="October" <?php if (!(strcmp( "October", $month))) {echo "selected=\"selected\"";} ?>>October</option>
        <option value="November" <?php if (!(strcmp( "November", $month))) {echo "selected=\"selected\"";} ?>>November</option>
        <option value="December" <?php if (!(strcmp( "December", $month))) {echo "selected=\"selected\"";} ?>>December</option>
      </select>
      <select name="fb_year" class="tarea" id="select3">
        <option value="2009" selected="selected" <?php if (!(strcmp(2009, $year))) {echo "selected=\"selected\"";} ?>>2009</option>
        <option value="2010" <?php if (!(strcmp(2010, $year))) {echo "selected=\"selected\"";} ?>>2010</option>
        <option value="2011" <?php if (!(strcmp(2011, $year))) {echo "selected=\"selected\"";} ?>>2011</option>
        <option value="2012" <?php if (!(strcmp(2012, $year))) {echo "selected=\"selected\"";} ?>>2012</option>
        <option value="2013" <?php if (!(strcmp(2013, $year))) {echo "selected=\"selected\"";} ?>>2013</option>
        <option value="2014" <?php if (!(strcmp(2014, $year))) {echo "selected=\"selected\"";} ?>>2014</option>
        <option value="2015" <?php if (!(strcmp(2015, $year))) {echo "selected=\"selected\"";} ?>>2015</option>
        <option value="2017" <?php if (!(strcmp(2017, $year))) {echo "selected=\"selected\"";} ?>>2016</option>
        <option value="2018" <?php if (!(strcmp(2018, $year))) {echo "selected=\"selected\"";} ?>>2018</option>
        <option value="2019" <?php if (!(strcmp(2019, $year))) {echo "selected=\"selected\"";} ?>>2019</option>
        <option value="2020" <?php if (!(strcmp(2020, $year))) {echo "selected=\"selected\"";} ?>>2020</option>
      </select>
      <select name="fb_hour" class="tarea" id="select4">
        <option value="1" selected="selected" <?php if (!(strcmp(1, $hour))) {echo "selected=\"selected\"";} ?>>1</option>
        <option value="2" <?php if (!(strcmp(2, $hour))) {echo "selected=\"selected\"";} ?>>2</option>
        <option value="3" <?php if (!(strcmp(3, $hour))) {echo "selected=\"selected\"";} ?>>3</option>
        <option value="4" <?php if (!(strcmp(4, $hour))) {echo "selected=\"selected\"";} ?>>4</option>
        <option value="5" <?php if (!(strcmp(5, $hour))) {echo "selected=\"selected\"";} ?>>5</option>
        <option value="6" <?php if (!(strcmp(6, $hour))) {echo "selected=\"selected\"";} ?>>6</option>
        <option value="7" <?php if (!(strcmp(7, $hour))) {echo "selected=\"selected\"";} ?>>7</option>
        <option value="8" <?php if (!(strcmp(8, $hour))) {echo "selected=\"selected\"";} ?>>8</option>
        <option value="9" <?php if (!(strcmp(9, $hour))) {echo "selected=\"selected\"";} ?>>9</option>
        <option value="10" <?php if (!(strcmp(10, $hour))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="11" <?php if (!(strcmp(11, $hour))) {echo "selected=\"selected\"";} ?>>11</option>
        <option value="12" <?php if (!(strcmp(12, $hour))) {echo "selected=\"selected\"";} ?>>12</option>
        <option value="13" <?php if (!(strcmp(13, $hour))) {echo "selected=\"selected\"";} ?>>13</option>
        <option value="14" <?php if (!(strcmp(14, $hour))) {echo "selected=\"selected\"";} ?>>14</option>
        <option value="15" <?php if (!(strcmp(15, $hour))) {echo "selected=\"selected\"";} ?>>15</option>
        <option value="16" <?php if (!(strcmp(16, $hour))) {echo "selected=\"selected\"";} ?>>16</option>
        <option value="17" <?php if (!(strcmp(17, $hour))) {echo "selected=\"selected\"";} ?>>17</option>
        <option value="18" <?php if (!(strcmp(18, $hour))) {echo "selected=\"selected\"";} ?>>18</option>
        <option value="19" <?php if (!(strcmp(19, $hour))) {echo "selected=\"selected\"";} ?>>19</option>
        <option value="20" <?php if (!(strcmp(20, $hour))) {echo "selected=\"selected\"";} ?>>20</option>
        <option value="21" <?php if (!(strcmp(21, $hour))) {echo "selected=\"selected\"";} ?>>21</option>
        <option value="22" <?php if (!(strcmp(22, $hour))) {echo "selected=\"selected\"";} ?>>22</option>
        <option value="23" <?php if (!(strcmp(23, $hour))) {echo "selected=\"selected\"";} ?>>23</option>
        <option value="24" <?php if (!(strcmp(24, $hour))) {echo "selected=\"selected\"";} ?>>24</option>
      </select>
      <select name="fb_minute" class="tarea" id="select5">
        <option value="00" selected="selected" <?php if (!(strcmp(00, $minute))) {echo "selected=\"selected\"";} ?>>00</option>
        <option value="05" <?php if (!(strcmp(05, $minute))) {echo "selected=\"selected\"";} ?>>05</option>
        <option value="10" <?php if (!(strcmp(10, $minute))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="15" <?php if (!(strcmp(15, $minute))) {echo "selected=\"selected\"";} ?>>15</option>
        <option value="20" <?php if (!(strcmp(20, $minute))) {echo "selected=\"selected\"";} ?>>20</option>
        <option value="25" <?php if (!(strcmp(25, $minute))) {echo "selected=\"selected\"";} ?>>25</option>
        <option value="30" <?php if (!(strcmp(30, $minute))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="35" <?php if (!(strcmp(35, $minute))) {echo "selected=\"selected\"";} ?>>35</option>
        <option value="40" <?php if (!(strcmp(40, $minute))) {echo "selected=\"selected\"";} ?>>40</option>
        <option value="45" <?php if (!(strcmp(45, $minute))) {echo "selected=\"selected\"";} ?>>45</option>
        <option value="50" <?php if (!(strcmp(50, $minute))) {echo "selected=\"selected\"";} ?>>50</option>
        <option value="55" <?php if (!(strcmp(55, $minute))) {echo "selected=\"selected\"";} ?>>55</option>
      </select>
      &nbsp;</div>
    <textarea name="feedback" rows="8" class="tarea-100per" id="feedback"><?php echo $row[ 'FeedBack']; ?></textarea>
    <input name="id_c" type="hidden" id="id_c" value="<?php echo $row['Id']; ?>" />
  </div>
</div>
<?php } // close loop ?>
<div style="padding-top:5px;">
  <div style="padding-bottom:5px;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#A6CAF0" class="td-header">&nbsp;Images</td>
      </tr>
    </table>
  </div>
  <div class="combo_bold" style="background-color:#E1E1E1; color:#333; padding:3px; border:solid 1px #A6CAF0">
    <table border="0" cellpadding="2" cellspacing="3" class="combo">
      <tr>
        <td>
          <?php if($totalRows_job_history>= 1){ echo $totalRows_job_history .' Photos'; ?></td>
        <td>
          <table border="0" cellpadding="2" cellspacing="3">
            <tr>
              <?php do { // horizontal looper version 3 ?>
              <td>
                <a href="../images/history/<?php echo $row_job_history['Photo']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})"> <img src="../images/icons/btn-image.png" alt="" width="25" height="25" border="0"> </a>
              </td>
              <?php 
			  $row_job_history = mysqli_fetch_assoc($job_history); 
			  
			  if(!isset($nested_job_history)){
				  
				  $nested_job_history = 1; 
				  
			  } 
			  
			  if(isset($row_job_history) && is_array($row_job_history) && $nested_job_history++ % 6==0) { echo "</tr><tr>"; } } while ($row_job_history); //end horizontal looper version 3 ?>
            </tr>
          </table>
          <?php } // close if ?>
        </td>
      </tr>
    </table>
  </div>
</div>

<div style="padding-bottom:5px; padding-top:5px">
  <table width="100%" border="0" cellpadding="0" cellspacing="1">
    <tr>
      <td bgcolor="#A6CAF0" class="td-header">&nbsp;Actual Work Carried Out </td>
      <td width="90" align="center" bgcolor="#A6CAF0" class="td-header">Unit</td>
      <td width="90" align="center" bgcolor="#A6CAF0" class="td-header">Qty.</td>
      <td width="90" align="center" bgcolor="#A6CAF0" class="td-header">Unit Price </td>
      <td width="30" align="center" bgcolor="#A6CAF0" class="td-header" style="padding:0">X</td>
    </tr>
  </table>
</div>

<div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left">
        <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin-left:5px; margin-right:5px; padding:3px; border:solid 1px #C8C8C8"> Labour</div>
        <?php 
		$jobid = $_GET[ 'Id']; 
		
		$query="SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC";
		$result = mysqli_query($con, $query) or die(mysqli_error());
		$numrows = mysqli_num_rows($result); 
		while($row = mysqli_fetch_array($result)){
			
			$id = $row['Id']; 
			
			$query1="SELECT * FROM tbl_jc WHERE Id = '$id'" ; 
			$result1 = mysqli_query($con, $query1) or die(mysqli_error()); 
			$row1 = mysqli_fetch_array($result1); 
			
			$rate = $row1[ 'Price']; 
			$rate = explode( ".", $rate); 
			$unit = $row[ 'Unit'];
			 
			$query3 = mysqli_query($con, "SELECT * FROM tbl_rates WHERE Fuel = '1'")or die(mysqli_error()); 
			$row3 = mysqli_fetch_array($query3); 
			$unit_display = $row[ 'Name']; 
		?>
        <div>
          <table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
              <td valign="bottom"><textarea name="labour[]" rows="5" class="tarea-100per" id="labour"><?php echo $row[ 'Description']; ?></textarea></td>
              <td width="100" valign="bottom"><input name="unit_l[]" type="text" class="tarea-100per" id="unit_l" value="<?php echo $unit; ?>" size="6"></td>
              <td width="100" valign="bottom"><input name="qty_l[]" type="text" class="tarea-100per" id="qty_l" value="<?php echo $row['Qty']; ?>" size="6"></td>
              <td width="100" valign="bottom"><?php if($unit=='hours' ){ ?>
                <select name="price_l[]" class="tarea-100per" id="price_l[]" style="width:100px;">
                  <?php 
			$labour_type = $row1['LabourType']; 
			
			do { 
			?>
                  <option value="<?php echo $row_Recordset101['Rate'] .'_'.$row_Recordset101['Name_1']; ?>" <?php if ($row_Recordset101[ 'Name_1'] == $labour_type){ echo "selected=\"selected\""; } ?>> <?php echo $row_Recordset101[ 'Name_1']; ?> </option>
                  <?php 
			} while ($row_Recordset101 = mysqli_fetch_assoc($Recordset101)); 
			$rows = mysqli_num_rows($Recordset101); 
			
			if($rows> 0){
				
				mysqli_data_seek($Recordset101, 0); 
				$row_Recordset101 = mysqli_fetch_assoc($Recordset101); 
			}
			?>
                </select>
                <?php } elseif($unit=='km' ){ ?>
                <input name="price_display_l[]" type="text" class="tarea-100per" id="price_display_l[]" value="<?php echo $row3['Name']; ?>" style="width:100px">
                <input name="price_l[]" type="hidden" class="tarea" id="price_l[]" value="<?php echo $row['Price']; ?>">
                <?php } ?></td>
              <td width="15" valign="bottom"><input name="delete[]" type="checkbox" id="delete" value="<?php echo $row['Id']; ?>" />
                <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>" /></td>
            </tr>
          </table>
        </div>
        <?php } // close loop ?>
        
        <!-- Materials -->
        <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; margin-bottom:0px; padding:3px; border:solid 1px #C8C8C8"> Material</div>
        <?php 
		$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'" ; 
		$result = mysqli_query($con, $query) or die(mysqli_error()); 
		while($row = mysqli_fetch_array($result)){ 
		?>
            <div>
              <table width="100%" border="0" cellspacing="2" cellpadding="0">
                <tr>
                  <td><input name="material[]" type="text" class="tarea-100per" id="material" size="95" value="<?php echo $row['Description']; ?>" /></td>
                  <td width="100"><input name="unit_m[]" type="text" class="tarea-100per" id="unit_m" value="<?php echo $row['Unit']; ?>" size="6" /></td>
                  <td width="100"><input name="qty_m[]" type="text" class="tarea-100per" id="qty_m" value="<?php echo $row['Qty']; ?>" size="6" /></td>
                  <td width="100"><input name="price_m[]" type="text" class="tarea-100per" id="price_m" value="<?php echo $row['Price']; ?>" size="16" /></td>
                  <td width="15"><input name="delete_m[]" type="checkbox" id="delete_m[]" value="<?php echo $row['Id']; ?>" />
                    <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>" /></td>
                </tr>
              </table>
              </div>
        <?php }  ?>
        <!-- End Materials -->
        
        
        <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; margin-bottom:0px; padding:3px; border:solid 1px #C8C8C8"> Transport</div>
        <?php include( 'transport.php'); ?>
      </td>
    </tr>
  </table>
</div>


<?php if($row_Recordset5['Status'] == 7){ ?>

  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
    <tr>
      <td valign="top"></span>      <a alt="" class="btn-new" onClick="MM_openBrWindow('../reject.php?Id=<?php echo $_GET['Id']; ?>','','width=600,height=200')">Reject</a>      </td>
      <td align="right" valign="top">
        <a alt="" class="btn-new" border="0" onClick="MM_openBrWindow('../fpdf16/inv-preview.php?Id=<?php echo $_GET['Id']; ?>','','scrollbars=yes,width=750')">Preview</a>
        <input name="Submit3" type="submit" class="btn-new" id="Submit3" value="Save">
        <input name="complete" type="submit" class="btn-new" id="complete" value="Processing Complete" /></td>
      </tr>
  </table>
  
<?php } else { ?>

  <table border="0" align="center" cellpadding="0" cellspacing="3">
    <tr>
      <td align="right" valign="top"><a alt="" class="btn-new" border="0" onClick="MM_openBrWindow('../fpdf16/inv-preview.php?Id=<?php echo $_GET['Id']; ?>','','scrollbars=yes,width=750')">Preview</a>
      </td>
      <td align="right" valign="top">
        <input name="Submit3" type="submit" class="btn-new" id="Submit3" value="Save">
      </td>
      <td align="right" valign="top">
        <input name="complete" type="submit" class="btn-new" id="complete" value="Approve" />
      </span>
      </td>
      <td valign="top"><a alt="" class="btn-new" onClick="MM_openBrWindow('../reject.php?Id=<?php echo $_GET['Id']; ?>','','width=600,height=200')">Reject</a>
      </td>
      <td valign="top">
        <a href="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&delete&Id=<?php echo $_GET['Id']; ?>" class="btn-new">Cancel</a>
          <a name="btm"></a>
      </td>
      </tr>
  </table>

<?php } ?>
</form>

</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($result);
  mysqli_free_result($result1);
  mysqli_free_result($query3);
  mysqli_free_result($query_invno);
  mysqli_free_result($job_history);
  mysqli_free_result($Recordset100);
  mysqli_free_result($Recordset101);
  mysqli_free_result($Recordset3);
  mysqli_free_result($Recordset4);
  mysqli_free_result($Recordset5);
  mysqli_free_result($Recordset7);
  mysqli_free_result($Recordset8);
  mysqli_free_result($Recordset99);
  mysqli_free_result($rs_components);
  mysqli_free_result($rs_failure);
  mysqli_free_result($rs_repair);
  mysqli_free_result($rs_root_cause);
  mysqli_free_result($rs_site_name);
  mysqli_free_result($rs_transport_rates);
?>  
