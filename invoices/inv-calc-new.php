<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

logout($con);

$today = date('Y-m-j');
$jobid = $_GET['Id'];

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

// Delete Labour & Materials
if (isset($_GET['DeleteRow'])){
	
    $delete = $_GET['DeleteRow'];
    mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$delete'") or die(mysqli_error($con));
	
	header('Location: inv-calc-new.php?Id='. $_GET['Id'] .'#Labour');
}

// Delete transport rows
if (isset($_GET['DeleteTransport'])){
	
    $delete = $_GET['DeleteTransport'];
    mysqli_query($con, "DELETE FROM tbl_travel WHERE Id = '$delete'") or die(mysqli_error($con));
	
	header('Location: inv-calc-new.php?Id='. $_GET['Id'] .'#Transport');
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
if(isset($_GET['AddLabour'])){
	
	$jobid = $_GET['Id'];
	
	mysqli_query($con, "INSERT INTO tbl_jc (InvoiceNo,JobId,Labour,Unit) VALUES ('$invoiceno','$jobid','1','hours')") or die(mysqli_error($con));
	
	header('Location: inv-calc-new.php?Id='. $_GET['Id'] .'#Labour');
}

// Add material row
if(isset($_GET['AddMaterial'])){
	
	$jobid = $_GET['Id'];
	
	mysqli_query($con, "INSERT INTO tbl_jc (InvoiceNo,JobId,Material) VALUES ('$invoiceno','$jobid','1')") or die(mysqli_error($con));
	
	header('Location: inv-calc-new.php?Id='. $_GET['Id'] .'#Material');
}

// Add transport row
if(isset($_GET['AddTransport'])){
		
	$jobid = $_GET['Id'];
		
	mysqli_query($con, "INSERT INTO tbl_travel (JobId) VALUES ('$jobid')") or die(mysqli_error($con));
	
	header('Location: inv-calc-new.php?Id='. $_GET['Id'] .'#Transport');
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

    $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
    $numrows = mysqli_num_rows($query);

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

        if ($unit == 'hours'){
			
            mysqli_query($con, "UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty', Price = '$new_price', Total1 = '$total', LabourType = '$labour_name' WHERE Id = '$id'") or die(mysqli_error($con));
        }
        
		if($unit == 'km'){
			
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

if($companyid == 1){
	
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 2){
	
	$query = mysqli_query($con, "SELECT SUM(TotalPragma) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(TotalPragma)'];
	
}

if($companyid == 3){
	
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 4){
	
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 5){
	
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 6){
	
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 10){
	
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 12){
	
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

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
		tbl_sites.`Name` AS Name_1,
		tbl_sites.Id AS SiteId,
		tbl_companies.Id AS Id_1,
		tbl_companies.Address AS CompanyAddress,
		tbl_companies.ContactNumber,
		tbl_jc.RequestPreWorkPo,
		tbl_companies.`Name`,
		tbl_sites.Company,
		tbl_sites.Site,
		tbl_sites.Address,
		tbl_sites.FirstName,
		tbl_sites.LastName,
		tbl_sites.Telephone,
		tbl_sites.Email,
		tbl_sites.Telephone,
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
		tbl_jc.JobId = '$KTColParam1_Recordset5'
	ORDER BY
		Id ASC
	LIMIT 1";
	  
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
	
	header('Location: fpdf16/approved-pdf.php?Id='. $_GET['Id']);
}

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
      
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
      
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
      
      <script type="text/javascript" src="../js/sticky.js"></script>
      
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
				  scrolling   : 'no',
				  type : 'iframe',
				  iframe : {
					  preload: false
				  }

			  });			  
  
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

<table>
<tr>
<td width="250" valign="top">  
<!-- Navigatiopn -->
    <?php include('../menu/menu.php'); ?>
    
<!-- End Navigation -->

</td><td>
    
<!-- Breadcrumbs -->
    <div class="td-bread">
        <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Invoices</a></li>
            <li><a href="#">Pending</a></li>
            <li></li>
        </ul>
    </div>
<!-- End Breadcrumbs -->
    
<!-- Search -->
<div class="search-container">
        <form id="form1" name="form1" method="post" action="">
            <input name="textfield" type="text" class="search-top" id="textfield" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
</div>
<!-- End Search -->
    
<!-- Main Form -->
<form name="form1" method="post" action="inv-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $jobid; ?>&update">
<div id="main-wrapper" style="margin-bottom:105px">

        <!-- Invoice Details -->
        <div id="list-border" style="margin-bottom:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td colspan="6" class="td-header">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>Invoice</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="90" class="td-left">Oil Company</td>
                    <td width="200" class="td-right"><?php echo $row_Recordset5['Name']; ?></td>
                    <td width="90" class="td-left">Site</td>
                    <td width="200" class="td-right"><?php echo $row_Recordset5['Name_1']; ?></td>
                    <td width="90" class="td-left">Invoice No.</td>
                    <td class="td-right"><?php echo $row_Recordset5['InvoiceNo']; ?></td>
                </tr>
                <tr>
                    <td class="td-left">Address</td>
                    <td class="td-right"><?php echo char_limit($row_Recordset5['CompanyAddress'], 30); ?></td>
                    <td class="td-left">Address</td>
                    <td class="td-right"><?php echo char_limit($row_Recordset5['Address'], 30); ?></td>
                    <td class="td-left">Job No</td>
                    <td class="td-right"><?php echo $row_Recordset5['JobNo']; ?></td>
                </tr>
                <tr>
                    <td class="td-left">Telephone</td>
                    <td class="td-right"><?php echo $row_Recordset5['ContactNumber']; ?></td>
                    <td class="td-left">Telephone</td>
                    <td class="td-right"><?php echo $row_Recordset5['Telephone']; ?></td>
                    <td class="td-left">Quote No</td>
                    <td class="td-right"><?php echo $row_Recordset5['QuoteNo']; ?></td>
                </tr>
            </table>
        </div>
        <!-- End Invoice Details -->
        
        <!-- Service Requested -->
        <div id="list-border" style="margin-bottom:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td colspan="8" class="td-header">Service Requested</td>
                </tr>
                <tr>
                    <td colspan="8" class="td-right">
                        <textarea name="service" rows="5" class="tarea-100" id="service"><?php echo $row_Recordset6[ 'JobDescription']; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <!-- End Service Requested -->
        
        <!-- History -->
        <div id="list-border" style="margin-bottom:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td colspan="8" class="td-header">History</td>
                </tr>
                <tr>
                    <td colspan="8" class="td-right">

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
                <tr>
                    <td class="td-right">
                        <select name="day[] " class="tarea-navy" id="day[] ">
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
                    </td>
                    <td class="td-right">
                        <select name="month[] " class="tarea-navy" id="month[] ">
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
                    </td>
                    <td class="td-right">
                        <select name="year[] " class="tarea-navy" id="year[] ">
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
                    </td>
                    <td class="td-right">
                        <select name="hour[] " class="tarea-navy" id="hour[] ">
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
                    </td>
                    <td class="td-right">
                        <select name="minute[] " class="tarea-navy" id="minute[] ">
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
                    </td>
                    <td class="td-right">
                        <input name="history-date " type="text " class="tarea-100" id="textfield ">
                    </td>
                    <td class="td-right">
                        <input name="comment_name[] " type="text " class="tarea-100" id="comment_name[] " value="<?php echo $row[ 'CommentName']; ?>">

                    </td>
                    <td width="20" class="td-right"><input name="delete_c[]" type="checkbox" id="delete_c[]" value="<?php echo $row['Id']; ?>"></td>
                </tr>
                <tr>
                    <td colspan="8" class="td-right">
                        <input name="comment1" type="hidden" id="comment1" value="<?php echo $row['Id']; ?>" />
                        <input name="id_c[]" type="hidden" id="id_c[]" value="<?php echo $row['Id']; ?>" />
                        <textarea name="comment[]" rows="4" class="tarea-100" id="textarea" type="text" value="<?php echo $row['CommentText']; ?>"><?php echo $row[ 'CommentText']; ?></textarea>
                    </td>
                </tr>
                <?
                }

                } else {
                ?>
                <tr>
                    <td class="td-right">
                        <select name="day" class="tarea-navy" id="day">
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
                    </td>
                    <td class="td-right">
                        <select name="month" class="tarea-navy" id="month">
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
                    </td>
                    <td class="td-right">
                        <select name="year" class="tarea-navy" id="year">
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
                    </td>
                    <td class="td-right">
                        <select name="hour" class="tarea-navy" id="hour">
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
                    </td>
                    <td class="td-right">
                        <select name="minute" class="tarea-navy" id="minute">
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
                    </td>
                    <td class="td-right">
                        <input name="comment_name" type="text" class="tarea-100" id="comment_name" value="<?php echo $row['CommentName']; ?>" />
                    </td>
                    <td class="td-right">
                        <select name="tech" class="tarea-100" id="tech">
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
                    </td>
                    <td class="td-right">
                        <input name="delete_c" type="checkbox" id="delete_c" value="<?php echo $row['Id']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="td-right">
                        <textarea name="comment" rows="8" class="tarea-100" id="comment" type="text"><?php echo $row[ 'CommentText']; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <!-- End History -->
        
        <!-- Feed Back -->
        <div id="list-border" style="margin-bottom:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td colspan="8" class="td-header">Feed Back</td>
                </tr>
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
                <tr>
                    <td class="td-right">
                        <select name="fb_day" class="tarea-navy" id="fb_day">
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
                    </td>
                    <td class="td-right">
                        <select name="fb_month" class="tarea-navy" id="select2">
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
                    </td>
                    <td class="td-right">
                        <select name="fb_year" class="tarea-navy" id="select3">
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
                    </td>
                    <td class="td-right">
                        <select name="fb_hour" class="tarea-navy" id="select4">
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
                    </td>
                    <td class="td-right">
                        <select name="fb_minute" class="tarea-navy" id="select5">
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
                    </td>
                    <td colspan="3" class="td-right">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="8" class="td-right">
                        <textarea name="feedback" rows="8" class="tarea-100" id="feedback"><?php echo $row[ 'FeedBack']; ?></textarea>
                        <input name="id_c" type="hidden" id="id_c" value="<?php echo $row['Id']; ?>" />
                    </td>
                </tr>
                <?php } // close loop ?>
            </table>
        </div>
        <!-- End Feed Back -->
        
        <!-- Gallery -->
        <div id="list-border" style="margin-bottom:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td colspan="8" class="td-header">Gallery <?php if($totalRows_job_history>= 1){ echo ' | '. $totalRows_job_history .' Photos'; ?></td>
                </tr>
                <tr>
                    <td colspan="8" class="td-right">
                        <table border="0" cellpadding="2" cellspacing="3">
                            <tr>
                                <td>
                                    <table border="0" cellpadding="2" cellspacing="3">
                                        <tr>
                                            <?php do { // horizontal looper version 3 ?>
                                            <td>
                                                <a href="../images/history/<?php echo $row_job_history['Photo']; ?>" class="look_inside" onclick="return hs.expand(this, {captionId: 'caption1'})"> <img src="../images/icons/icon-image.png" alt="" width="25" height="24" border="0"> </a>
                                            </td>
                                            <?php
                                            $row_job_history = mysqli_fetch_assoc($job_history);

                                            if(!isset($nested_job_history)){

                                            $nested_job_history = 1;

                                            }

                                            if(isset($row_job_history) && is_array($row_job_history) && $nested_job_history++ % 27==0) { echo "
                                        </tr>
                                        <tr>
                                            "; } } while ($row_job_history); //end horizontal looper version 3 ?>
                                        </tr>
                                    </table>
                                    <?php } // close if ?>
                                </td>
                            </tr>
                        </table>
                    </td>
            </table>
        </div>
        <!-- End Gallery -->

        <!-- Actual Work Carried Out -->
        <div id="list-border" style="margin-bottom:15px">
            <table width="100%" border="0" cellpadding="0" cellspacing="1">
                <tr>
                    <td class="td-header">Actual Work Carried Out </td>
                    <td width="75" align="center" class="td-header-right">Unit</td>
                    <td width="75" align="center" class="td-header-right">Qty</td>
                    <td width="140" align="right" class="td-header-right"><div style="padding-right:10px">Unit Price</div></td>
                    <td width="30" align="center" class="td-header-right">X</td>
                </tr>
                <tr>
                    <td colspan="5" class="td-sub-sub-header" style="padding-right:0"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td> Labour <a name="Labour" id="Labour"></a> <a name="Btm" id="Btm"></a></td>
                        <td width="35" align="center"><a href="inv-calc-new.php?Id=<?php echo $_GET['Id']; ?>&AddLabour" class="add-row-inv"></a></td>
                      </tr>
                    </table></td>
                </tr>
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
                <tr>
                    <td class="td-right"><textarea name="labour[]" rows="5" class="tarea-100" id="labour"><?php echo $row[ 'Description']; ?></textarea></td>
                    <td align="center" valign="top" class="td-right-jc"><input name="unit_l[]" type="text" class="tarea-100" id="unit_l" value="<?php echo $unit; ?>" style="text-align:center" /></td>
                    <td align="center" valign="top" class="td-right-jc"><input name="qty_l[]" type="text" class="tarea-100" id="qty_l" value="<?php echo $row['Qty']; ?>" style="text-align:center" /></td>
                    <td align="center" valign="top" class="td-right-jc">
                        <?php if($unit=='hours' ){ ?>
                        <select name="price_l[]" class="tarea-100" id="price_l[]" dir="rtl">
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
                        <input name="price_display_l[]" type="text" class="tarea-100" id="price_display_l[]" value="<?php echo $row3['Name']; ?>">
                        <input name="price_l[]" type="hidden" class="tarea" id="price_l[]" value="<?php echo $row['Price']; ?>">
                        <?php } ?>
                    </td>
                    <td align="center" valign="top" class="td-right-jc" style="padding:0">
                      <a href="inv-calc-new.php?Id=<?php echo $_GET['Id']; ?>&DeleteRow=<?php echo $row['Id']; ?>" class="remove"></a>
                      <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>" />
                  </td>
                </tr>
                <?php } ?>
                <!-- End Labour -->
                <!-- Materials -->
                <tr>
                    <td colspan="5" class="td-sub-sub-header" style="padding-right:0"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td> Materials <a name="Material" id="Material"></a></td>
                        <td width="35"><a href="inv-calc-new.php?Id=<?php echo $_GET['Id']; ?>&AddMaterial" class="add-row-inv"></a></td>
                      </tr>
                    </table></td>
                </tr>
                <?php
                $query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'" ;
                $result = mysqli_query($con, $query) or die(mysqli_error());
                while($row = mysqli_fetch_array($result)){
                ?>
                <tr>
                    <td class="td-right"><input name="material[]" type="text" class="tarea-100" id="material" value="<?php echo $row['Description']; ?>" /></td>
                    <td align="center" class="td-right-jc"><input name="unit_m[]" type="text" class="tarea-100" id="unit_m" value="<?php echo $row['Unit']; ?>" style="text-align:center" /></td>
                    <td align="center" class="td-right-jc"><input name="qty_m[]" type="text" class="tarea-100" id="qty_m" value="<?php echo $row['Qty']; ?>" style="text-align:center" /></td>
                    <td align="center" class="td-right-jc"><input name="price_m[]" type="text" class="tarea-100" id="price_m" value="<?php echo $row['Price']; ?>" style="text-align:right" /></td>
                    <td align="center" class="td-right-jc" style="padding:0">
                        <a href="inv-calc-new.php?Id=<?php echo $_GET['Id']; ?>&DeleteRow=<?php echo $row['Id']; ?>" class="remove"></a>
                        <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>" />
                    </td>
                </tr>
                <?php }  ?>
                <!-- End Materials -->

                <tr>
                    <td colspan="5" class="td-sub-sub-header" style="padding-right:0"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td> Transport <a name="Transport" id="Transport"></a></td>
                        <td width="35"><a href="inv-calc-new.php?Id=<?php echo $_GET['Id']; ?>&AddTransport" class="add-row-inv"></a></td>
                      </tr>
                    </table></td>
                </tr>
                <?php
                $query = mysqli_query($con, "SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysqli_error($con));
                while($row = mysqli_fetch_array($query)){
                ?>
                <tr>
                    <td class="td-right"><input name="t_comment[]" type="text" class="tarea-100" id="t_comment[]" onfocus="if(this.value=='Comments'){this.value=''}" onblur="if(this.value==''){this.value='Comments'}" value="<?php field_val($row['TransportComment'],'Comments'); ?>"></td>
                    <td class="td-right"><input name="transport[]" type="text" class="tarea-100" id="transport" onfocus="if(this.value=='Trips to site'){this.value=''}" onblur="if(this.value==''){this.value='Trips to site'}" value="<?php field_val($row['Description'],'Trips to site'); ?>" style="text-align:center"></td>
                    <td class="td-right">
                        <input name="qty_t[]" type="text" class="tarea-100" id="qty_t" onfocus="if(this.value=='Return Distance'){this.value=''}" onblur="if(this.value==''){this.value='Return Distance'}" value="<?php field_val($row['Qty'],'Return Distance'); ?>" style="text-align:center" />
                    </td>
                    <td class="td-right"><input name="price_t[]" type="text" class="tarea-100" id="price_t[]" onfocus="if(this.value=='Fuel Rate'){this.value=''}" onblur="if(this.value==''){this.value='Fuel Rate'}" value="<?php field_val($row['Price'],'Fuel Rate'); ?>" style="text-align:right" /></td>
                    <td class="td-right">
                      <a href="inv-calc-new.php?Id=<?php echo $_GET['Id']; ?>&DeleteTransport=<?php echo $row['Id']; ?>" class="remove"></a>
                      <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>" />
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <!-- End Actual Work Carried Out -->
        
        <!-- Footer -->
        <div id="footer-sticky">
          <table width="910" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td>
              
              <?php if($row_Recordset5['Status'] == 7){ ?>
              
              <table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">
                  <tr>
                      <td align="left" valign="top"></span>      <a alt="" class="btn-new" onclick="MM_openBrWindow('../reject.php?Id=<?php echo $_GET['Id']; ?>','','width=600,height=200')">Reject</a>      </td>
                      <td align="right" valign="top">
                          <a href="../fpdf16/inv-preview.php?Id=<?php echo $_GET['Id']; ?>" class="btn-new fancybox">Preview</a>
                          <input name="Submit3" type="submit" class="btn-new" id="Submit3" value="Save">
                          <input name="complete" type="submit" class="btn-new" id="complete" value="Processing Complete" />
                      </td>
                  </tr>
              </table>
              
              <?php } else { ?>
              
              <table border="0" align="left" cellpadding="0" cellspacing="3">
                  <tr>
                      <td align="center" valign="top">
                          <a href="../fpdf16/inv-preview.php?Id=<?php echo $_GET['Id']; ?>" class="btn-new fancybox">Preview</a>
                      </td>
                      <td align="right" valign="top">
                          <input name="Submit3" type="submit" class="btn-new" id="Submit3" value="Save">
                      </td>
                      <td align="right" valign="top">
                          <input name="complete" type="submit" class="btn-new" id="complete" value="Approve" />
                          </span>
                      </td>
                      <td valign="top">
                          <a alt="" class="btn-new" onclick="MM_openBrWindow('../reject.php?Id=<?php echo $_GET['Id']; ?>','','width=600,height=200')">Reject</a>
                      </td>
                      <td valign="top">
                          <a href="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&delete&Id=<?php echo $_GET['Id']; ?>" class="btn-new">Cancel</a>
                          <a name="btm"></a>
                      </td>
                  </tr>
              </table>
              
              <?php } ?>
              
              </td>
            </tr>
          </table>
        </div>
        <!-- End Footer -->
                
    </div>
    </form>
    <!-- End Main Form -->
    
    </td>
  </tr>
</table>
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