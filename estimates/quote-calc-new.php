<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$quoteno = $_GET['Id'];

$query_qs = "
	SELECT
		tbl_qs.*,
		tbl_companies.`Name` AS CompanyName,
		tbl_sites.`Name` AS SiteName
	FROM
		tbl_qs
	INNER JOIN tbl_companies ON tbl_qs.CompanyId = tbl_companies.Id
	INNER JOIN tbl_sites ON tbl_qs.SiteId = tbl_sites.Id
	WHERE
		tbl_qs.QuoteNo = '". $_GET['Id'] ."'";

$query_qs = mysqli_query($con, $query_qs)or die(mysqli_error($con));
$row_qs = mysqli_fetch_array($query_qs);

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

$quoteno = $_GET['Id'];

// Total Cosring
//if(isset($_GET['Costing'])){
//	
//	$jobid = $_GET['Costing'];
//	
//	$query_qs = mysqli_query($con, "SELECT * FROM tbl_qs WHERE JobId = '$jobid'")or die(mysqli_error($con));
//	$row_qs = mysqli_fetch_array($query_qs);
//	
//	if(mysqli_num_rows($query_qs) == 0){
//		
//		$query_jc = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
//		$row_jc = mysqli_fetch_array($query_jc);
//		
//		$jobno = $row_jc['JobNo'];
//		$areaid = $row_jc['AreaId'];
//		$company = $row_jc['CompanyId'];
//		$site = $row_jc['SiteId'];
//		
//		if($row_jc['RequestPreWorkPo'] == '1'){
//			
//			$description = 'The site has been visited and this estimate requires approval in order for work to proceed';
//			
//		} else {
//			
//			$description = 'This maintenance request has been executed and herewith costing for P.O. approval.';
//		}
//		
//		$query = mysqli_query($con, "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1")or die(mysqli_error($con));
//		$row = mysqli_fetch_array($query);
//		
//		$quote = $row['QuoteNo']+1;
//		$date = date('Y-m-d');
//		$time = date('H:i:s');
//		$today = date('Y-m-j');
//		$userid = $_SESSION['kt_login_id'];
//		
//		mysqli_query($con, "UPDATE tbl_jc SET QuoteNo = '$quote' WHERE JobId = '$jobid'")or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Labour,Date,Time,JobDescription,Days,Status,UserId,FMC,Notes,JobId) 
//		VALUES ('$areaid','$company','$site','$quote','1','$date','$time','$description','$today','4','$userid','$jobno','$description','$jobid')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Material,Date,Time,JobDescription,Days,Status,UserId,FMC,Notes,JobId) 
//		VALUES ('$areaid','$company','$site','$quote','1','$date','$time','$description','$today','4','$userid','$jobno','$description','$jobid')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Transport,Date,Time,JobDescription,Days,Status,UserId,FMC,Notes,JobId) 
//		VALUES ('$areaid','$company','$site','$quote','1','$date','$time','$description','$today','4','$userid','$jobno','$description','$jobid')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_qs_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_qs_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_costing_material (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_costing_labour (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_costing_outsourcing (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_costing_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_costing_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		mysqli_query($con, "INSERT INTO tbl_costing_transport (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));
//		
//		header('Location: quote-calc.php?Id='. $quote);
//		
//	} else {
//		
//		header('Location: quote-calc.php?Id='. $row_qs['QuoteNo']);
//	}
//}

// Costing

// Costing HES
if(isset($_POST['hes-c'])){
	
	dbDelete('tbl_costing_hes', $where_clause="QuoteNo = '". $quoteno ."'", $con);
	
	for($i=0;$i<count($_POST['hes-c']);$i++){
		
		$form_data = array(
			'QuoteNo' => $quoteno,
			'Description' => $_POST['hes-c'][$i],
			'Price' => $_POST['hes-price-c'][$i]
		);
		
		dbInsert('tbl_costing_hes', $form_data, $con);
				
	}
}

// Costing Material
if(isset($_POST['material-c'])){
	
	dbDelete('tbl_costing_material', $where_clause="QuoteNo = '". $quoteno ."'", $con);
	
	for($i=0;$i<count($_POST['material-c']);$i++){
		
		$form_data = array(
			'QuoteNo' => $quoteno,
			'Description' => $_POST['material-c'][$i],
			'Price' => $_POST['material-price-c'][$i]
		);
		
		dbInsert('tbl_costing_material', $form_data, $con);
				
	}
}

// Costing Equipment
if(isset($_POST['equipment-c'])){
	
	dbDelete('tbl_costing_equipment', $where_clause="QuoteNo = '". $quoteno ."'", $con);
	
	for($i=0;$i<count($_POST['equipment-c']);$i++){
		
		$form_data = array(
			'QuoteNo' => $quoteno,
			'Description' => $_POST['equipment-c'][$i],
			'Price' => $_POST['equipment-price-c'][$i]
		);

		dbInsert('tbl_costing_equipment', $form_data, $con);
				
	}
}

// Costing Labour
if(isset($_POST['labour-c'])){
	
	dbDelete('tbl_costing_labour', $where_clause="QuoteNo = '". $quoteno ."'", $con);
	
	for($i=0;$i<count($_POST['labour-c']);$i++){
		
		$pieces = explode('-', $_POST['labour-price-c'][$i]);
		
		$form_data = array(
			'QuoteNo' => $quoteno,
			'Description' => $pieces[0],
			'Price' => $pieces[1]
		);

		dbInsert('tbl_costing_labour', $form_data, $con);
				
	}
}

// Costing Outsource
if(isset($_POST['outsource-c'])){
	
	dbDelete('tbl_costing_outsourcing', $where_clause="QuoteNo = '". $quoteno ."'", $con);
	
	for($i=0;$i<count($_POST['outsource-c']);$i++){
		
		$pieces = explode('-', $_POST['outsource-price-c'][$i]);
		
		$form_data = array(
			'QuoteNo' => $quoteno,
			'Description' => $_POST['outsource-c'][$i],
			'Price' => $_POST['outsource-price-c'][$i]
		);

		dbInsert('tbl_costing_outsourcing', $form_data, $con);
				
	}
}

// Costing Transport
$query = mysqli_query($con, "SELECT * FROM tbl_costing_transport WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$numrows = mysqli_num_rows($query);

if($_POST){
	
	$distance = $_POST['return'];
	$trips = $_POST['trips'];
	$days = $_POST['days'];
	$subsistence = $_POST['subsistence'];
	
	if($numrows == 0){
		
		mysqli_query($con, "INSERT INTO tbl_costing_transport (QuoteNo,ReturnDistance,Trips) VALUES ('$quoteno','$distance','$trips')")or die(mysqli_error($con));
	
	} else {
		
		mysqli_query($con, "UPDATE tbl_costing_transport SET QuoteNo = '$quoteno', ReturnDistance = '$distance', Trips = '$trips' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	
	}
	
	mysqli_query($con, "UPDATE tbl_costing_labour SET Days = '$days', Subsistence = '$subsistence' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
}
		
///////////////////////////////
/// Labour Rates Drop Down ///
/////////////////////////////

$query_dd = mysqli_query($con, "SELECT * FROM tbl_costing_labour_rates")or die(mysqli_error($con));

// Internal Notes
$actual_history = $_POST['internal_notes'];

$query = mysqli_query($con, "SELECT * FROM tbl_actual_history WHERE QuoteNo = '$quoteno' AND Comments = '$actual_history'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);
$numrows = mysqli_num_rows($query);

if(isset($_POST['internal_notes']) && !empty($_POST['internal_notes']) && $numrows == 0){
	
	$technicianid = $_SESSION['kt_login_id'];
	$date = date('Y-m-d H:i:s');
	
	mysqli_query($con, "INSERT INTO tbl_actual_history (QuoteNo,TechnicianId,Date,Comments) VALUES ('$quoteno','$technicianid','$date','$actual_history')")or die(mysqli_error($con));
	
}

$KTColParam1_query_note_comments = addslashes($_GET["Id"]);

$query_note_comments = mysqli_query($con, "SELECT tbl_technicians.Name AS Name_1, tbl_actual_history.JobId, tbl_users.Name, tbl_actual_history.Date, tbl_actual_history.Comments, tbl_actual_history.Mobile FROM ((tbl_actual_history LEFT JOIN tbl_users ON tbl_users.Id=tbl_actual_history.TechnicianId) LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_actual_history.TechnicianId) WHERE tbl_actual_history.QuoteNo = '$KTColParam1_query_note_comments'  ORDER BY tbl_actual_history.Id ASC ") or die(mysqli_error($con));
$row_note_comments = mysqli_fetch_assoc($query_note_comments);
$totalRows_query_note_comments = mysqli_num_rows($query_note_comments);

// Set Cookie after costing to make the quotation visible.
if(isset($_POST['quote']) && $totalRows_query_note_comments >= 1){
	
	header('Location: costing-quote.php?Id='. $_GET['Id']);
	
}

if(isset($_COOKIE['costing-'. $_GET['Id']]) && isset($_POST['desc-hes'])){
	
	// Upadate Health and Safety
	dbDelete('tbl_qs_hes', $where_clause="QuoteNo = '". $quoteno ."'", $con);
	
	for($i=0;$i<count($_POST['desc-hes']);$i++){
		
		$form_data = array(
		
			'QuoteNo' => $quoteno,
			'Description' => $_POST['desc-hes'][$i],
			'Unit' => $_POST['unit-hes'][$i],
			'Qty' => $_POST['qty-hes'][$i],
			'UnitPrice' => $_POST['price-hes'][$i],
			'Total' => $_POST['qty-hes'][$i] * $_POST['price-hes'][$i]
		);
		
		dbInsert('tbl_qs_hes', $form_data, $con);
		
	}
	
	// Upadate Equipment / Machinery
	dbDelete('tbl_qs_equipment', $where_clause="QuoteNo = '". $quoteno ."'", $con);
	
	for($i=0;$i<count($_POST['desc-e']);$i++){
		
		$form_data = array(
		
			'QuoteNo' => $quoteno,
			'Description' => $_POST['desc-e'][$i],
			'Unit' => $_POST['unit-e'][$i],
			'Qty' => $_POST['qty-e'][$i],
			'UnitPrice' => $_POST['price-e'][$i],
			'Total' => $_POST['qty-e'][$i] * $_POST['price-e'][$i]
		);
		
		dbInsert('tbl_qs_equipment', $form_data, $con);
		
	}

	$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$query_area = mysqli_query($con, "SELECT AreaId FROM tbl_sites WHERE Id = '". $_POST['site'] ."'")or die(mysqli_error($con));
	$row_area = mysqli_fetch_array($query_area);
	
	dbDelete('tbl_qs', $where_clause="QuoteNo = '". $quoteno ."'", $con);

	// Labour
	for($i=0;$i<count($_POST['labour']);$i++){
		
		$form_data = array(
		
			'QuoteNo' => $_GET['Id'],
			'AreaId' => $row['AreaId'],
			'CompanyId' => $row['CompanyId'],
			'SiteId' => $row['SiteId'],
			'JobNo' => $row['JobNo'],
			'Notes' => $_POST['notes'],
			'InternalNotes' => addslashes($_POST['internal_notes']),
			'Description' => $_POST['labour'][$i],
			'Unit' => $_POST['unit_l'][$i],
			'Qty' => $_POST['qty_l'][$i],
			'Price' => $_POST['price_l'][$i],
			'Total1' => $_POST['qty_l'][$i] * $_POST['price_l'][$i],
			'Email' => $row['Email'],
			'Date' => $_POST['date'],
			'SlaStart' => $row['SlaStart'],
			'SlaEnd' => $row['SlaEnd'],
			'Time' => $row['Time'],
			'Labour' => 1,
			'Material' => 0,
			'Transport' => 0,
			'JobDescription' => $_POST['jobdescription'],
			'Total' => $row['Total'],
			'Message' => $row['Message'],
			'Attention' => $_POST['att'],
			'FMC' => $_POST['fmc'],
			'TransportComment' => $row['TransportComment'],
			'Status' => $row['Status'],
			'Days' => $row['Days'],
			'UserId' => $_COOKIE['userid'],
			'UsersName' => $_COOKIE['username'],
			'Rejected' => $row['Rejected'],
			'TechnicianId' => $row['TechnicianId'],
			'JobId' => $row['JobId']
		);
		
		dbInsert('tbl_qs', $form_data, $con);
	}

	// Material
	for($i=0;$i<count($_POST['material']);$i++){
		
		$form_data = array(
		
			'QuoteNo' => $_GET['Id'],
			'AreaId' => $row['AreaId'],
			'CompanyId' => $row['CompanyId'],
			'SiteId' => $row['SiteId'],
			'JobNo' => $row['JobNo'],
			'Notes' => $_POST['notes'],
			'InternalNotes' => addslashes($_POST['internal_notes']),
			'Description' => $_POST['material'][$i],
			'Unit' => $_POST['unit_m'][$i],
			'Qty' => $_POST['qty_m'][$i],
			'Price' => $_POST['price_m'][$i],
			'Total1' => $_POST['qty_m'][$i] * $_POST['price_m'][$i],
			'Email' => $row['Email'],
			'Date' => $_POST['date'],
			'SlaStart' => $row['SlaStart'],
			'SlaEnd' => $row['SlaEnd'],
			'Time' => $row['Time'],
			'Labour' => 0,
			'Material' => 1,
			'Transport' => 0,
			'JobDescription' => $_POST['jobdescription'],
			'Total' => $row['Total'],
			'Message' => $row['Message'],
			'Attention' => $_POST['att'],
			'FMC' => $_POST['fmc'],
			'TransportComment' => $row['TransportComment'],
			'Status' => $row['Status'],
			'Days' => $row['Days'],
			'UserId' => $_COOKIE['userid'],
			'UsersName' => $_COOKIE['username'],
			'Rejected' => $row['Rejected'],
			'TechnicianId' => $row['TechnicianId'],
			'JobId' => $row['JobId']
		);
		
		dbInsert('tbl_qs', $form_data, $con);
		
	}
	
	// Transport
	for($i=0;$i<count($_POST['transport']);$i++){
		
		$form_data = array(
		
			'QuoteNo' => $_GET['Id'],
			'AreaId' => $row['AreaId'],
			'CompanyId' => $row['CompanyId'],
			'SiteId' => $row['SiteId'],
			'JobNo' => $row['JobNo'],
			'Notes' => $_POST['notes'],
			'InternalNotes' => addslashes($_POST['internal_notes']),
			'TransportComment' => $_POST['t_comment'][$i],
			'Description' => $_POST['transport'][$i],
			'Unit' => $_POST['unit_t'][$i],
			'Qty' => $_POST['qty_t'][$i],
			'Price' => $_POST['price_t'][$i],
			'Total1' => $_POST['qty_t'][$i] * $_POST['price_t'][$i],
			'Email' => $row['Email'],
			'Date' => $_POST['date'],
			'SlaStart' => $row['SlaStart'],
			'SlaEnd' => $row['SlaEnd'],
			'Time' => $row['Time'],
			'Labour' => 0,
			'Material' => 0,
			'Transport' => 1,
			'JobDescription' => $_POST['jobdescription'],
			'Total' => $row['Total'],
			'Message' => $row['Message'],
			'Attention' => $_POST['att'],
			'FMC' => $_POST['fmc'],
			'Status' => $row['Status'],
			'Days' => $row['Days'],
			'UserId' => $_COOKIE['userid'],
			'UsersName' => $_COOKIE['username'],
			'Rejected' => $row['Rejected'],
			'TechnicianId' => $row['TechnicianId'],
			'JobId' => $row['JobId']
		);
		
		dbInsert('tbl_qs', $form_data, $con);
	}
}

$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'") or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	$id = $row['Id'];
	$qty = $row['Qty'];
	$price = $row['Price'];
	$labour_total = $qty * $price;
	$vat = ($labour_total / 100) * 15;
	
	mysqli_query($con, "UPDATE tbl_qs SET SubTotal = '$labour_total', VAT = '$vat' WHERE Id = '$id'") or die(mysqli_error($con));
}

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$labour_total = $row['SUM(Total1)'];

$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	$id = $row['Id'];
	$qty = $row['Qty'];
	$price = $row['Price'];
	$material_total = $qty * $price;
	$vat = ($material_total / 100) * 15;
	
	mysqli_query($con, "UPDATE tbl_qs SET SubTotal = '$material_total', VAT = '$vat' WHERE Id = '$id'") or die(mysqli_error($con));
}

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$material_total = $row['SUM(Total1)'];

$query = mysqli_query($con, "SELECT CompanyId FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	$transport_total = $row['Description'] * ($row['Qty'] * $row['Price']);
	$vat = ($transport_total / 100) * 15;
	
	mysqli_query($con, "UPDATE tbl_qs SET SubTotal = '$transport_total', VAT = '$vat' WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysqli_error($con));
}

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$transport_total = $row['SUM(Total1)'];

// Allocate Technician
if(isset($_POST['tech'])){
	
	$tech_id = $_POST['tech'];
	
	mysqli_query($con, "UPDATE tbl_qs SET TechnicianId = '$tech_id' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
}

$colname_Recordset4 = intval($_GET['Id']);

$Recordset4 = mysqli_query($con, "SELECT SUM(Total1), SUM(VAT) FROM tbl_qs WHERE QuoteNo = '$colname_Recordset4' GROUP BY QuoteNo") or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$total = $row_Recordset4['SUM(Total1)'];
$vat = $row_Recordset4['SUM(VAT)'];
$total = $total + $vat;

mysqli_query($con, "UPDATE tbl_qs SET Total = '$total' WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));

$colname_Recordset2 = addslashes($_GET['Id']);

$Recordset2 = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$colname_Recordset2'") or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$query = "
  SELECT
	  tbl_sites.Name AS Name_1,
	  tbl_companies.Name,
	  tbl_sites.Company,
	  tbl_sites.Site,
	  tbl_sites.Address,
	  tbl_qs.Id,
	  tbl_qs.SiteId,
	  tbl_qs.QuoteNo,
	  tbl_qs.AreaId,
	  tbl_qs.Date,
	  tbl_qs.JobDescription,
	  tbl_qs.Attention,
	  tbl_qs.Status,
	  tbl_qs.InternalNotes,
	  tbl_qs.Notes,
	  tbl_qs.FMC
  FROM
	  (
		  (
			  tbl_qs
			  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_qs.SiteId
		  )
		  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_qs.CompanyId
	  )
  WHERE
	  tbl_qs.QuoteNo = '$quoteno'
  ORDER BY
	  Id ASC
  LIMIT 1";

$Recordset5 = mysqli_query($con, $query) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$KTColParam1_Recordset3 = addslashes($_GET["Id"]);

$Recordset3 = mysqli_query($con, "SELECT tbl_qs.CompanyId, tbl_qs.QuoteNo, tbl_companies.* FROM (tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo = '$KTColParam1_Recordset3'") or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$today = date('Y-m-j');
$submitted = $_POST['submitted'];

// Send to awaiting approval
if(isset($_POST['approval'])){
	
	mysqli_query($con, "UPDATE tbl_qs SET Status = '0', Days = '$today' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	
	header('Location: select_q_pending.php');

}

// Send to qued
if(isset($_POST['qued'])){
	
	mysqli_query($con, "UPDATE tbl_qs SET Status = '4', Days = '$today', Rejected = '1' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	
	header('Location: qs-qued.php');

}

// Send to outbox
if(isset($_POST['outbox'])){
	
	for($i=0;$i<count($_POST['attach']);$i++){
		
		$image = $_POST['attach'][$i];
		
		mysqli_query($con, "UPDATE tbl_photos SET Attach = '1' WHERE Image = '$image' AND QuoteNo = '$quoteno'")or die(mysqli_error($con));
	}
		
	$query = mysqli_query($con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	
	if(mysqli_num_rows($query) >= 1){
		
		$query = mysqli_query($con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno' AND Attach = '1'")or die(mysqli_error($con));
		
		if(mysqli_num_rows($query) >= 1){
			
			mysqli_query($con, "UPDATE tbl_qs SET Status = '1', Days = '$today' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
				
			$query = mysqli_query($con, "SELECT * FROM tbl_sent_quotes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
			$numrows = mysqli_num_rows($query);
				
			if($numrows == 0){
					
				$company = $row_Recordset5['Name'];
				$site = addslashes($row_Recordset5['Name_1']);
				$quote_no = $_GET['Id'];
				$document = 'Seavest Quotation '. $_GET['Id'] .'.pdf';
				$date = date('d M Y H:i:s');
					
				mysqli_query($con, "INSERT INTO tbl_sent_quotes (CompanyId,SiteId,QuoteNo,PDF,DateSent) VALUES ('$company','$site','$quote_no','$document','$date')")or die(mysqli_error($con));
			}
				
				header('Location: fpdf16/pdf_quotation.php?Id='.$quoteno);
			
		} else {
			
			$image_error = "Please select at least one photo.";
			$class = 'class="qs-check"';
		}
		
	} else {
		
		header('Location: fpdf16/pdf_quotation.php?Id='.$quoteno);
	}
}

// Create Job Card
if(isset($_POST['jobcard']) && !empty($_POST['jobno'])){
	
	$jobno = $_POST['jobno'];
	
	mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_jc ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$jobid = $row['Id'] + 1;
	
	$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$quote = $row['QuoteNo']; 
		$companyid = $row['CompanyId'];
		$siteid = $row['SiteId'];
		$desc = $row['Description']; 	
		$unit = $row['Unit']; 	
		$qty = $row['Qty']; 	
		$price = $row['Price']; 	
		$total = $row['Total1']; 	
		$date = date('j M Y');	
		$labour = $row['Labour'];
		$material = $row['Material']; 	
		$transport = $row['Transport'];
		$t_comment = $row['TransportComment'];
		
		if(isset($_POST['jobno'])){
			
			$jobno = $_POST['jobno'];
			
		} else {
			
			$jobno = $row['FMC'];
		}
	
		mysqli_query($con, "INSERT INTO tbl_jc (QuoteNo, JobNo, CompanyId, SiteId, Description, Unit, Qty, Price,Total1,Date,Labour,Material,Transport,JobId,TransportComment,JC) 
		VALUES ('$quote','$jobno','$companyid','$siteid','$desc','$unit','$qty','$price','$total','$date','$labour','$material','$transport','$jobid','$t_comment','1')") or die(mysqli_error($con));
	}
	
	$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$quote = $row['QuoteNo']; 
	$companyid = $row['CompanyId'];
	$siteid = $row['SiteId'];
	
	mysqli_query($con, "INSERT INTO tbl_jc (QuoteNo, JobNo, CompanyId, SiteId, Comment, JobId) VALUES ('$quote','$jobno','$companyid','$siteid','1','$jobid')") or die(mysqli_error($con));
	
	header('Location: ../job-cards/jc-calc.php?Id='. $jobid .'');
	
}
// End Create Job Card

$query_rs_area_q = "SELECT * FROM tbl_areas ORDER BY Area ASC";
$rs_area_q = mysqli_query($con, $query_rs_area_q, $inv) or die(mysqli_error($con));
$row_rs_area_q = mysqli_fetch_assoc($rs_area_q);
$totalRows_rs_area_q = mysqli_num_rows($rs_area_q);

////////////////////////
/// Transport Query ///
//////////////////////

$query_transport = mysqli_query($con, "SELECT * FROM tbl_costing_transport WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_transport = mysqli_fetch_array($query_transport);

$query_labour = mysqli_query($con, "SELECT * FROM tbl_costing_labour WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_labour = mysqli_fetch_array($query_labour);

$query_sum_hes = mysqli_query($con, "SELECT SUM(Total) AS Total_1 FROM tbl_qs_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_hes = mysqli_fetch_array($query_sum_hes);
		
$query_sum_e = mysqli_query($con, "SELECT SUM(Total) AS Total_1 FROM tbl_qs_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_e = mysqli_fetch_array($query_sum_e);
		
$query_sum_m = mysqli_query($con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_m = mysqli_fetch_array($query_sum_m);
		
$query_sum_safety = mysqli_query($con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_safety = mysqli_fetch_array($query_sum_safety);
		
$query_sum_outsourcing = mysqli_query($con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_outsourcing = mysqli_fetch_array($query_sum_outsourcing);

// Allocate Technicians
$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians")or die(mysqli_error($con));

// Allocate technicians to actual history
			
if(isset($_POST['tech'])){
	
	mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
			
	$techid = $_POST['tech'];
	$date = date('Y-m-d');
				
	$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
				
	$siteid = $row['SiteId'];
				
	$query = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '$siteid'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
								
	$site = addslashes($row['Name']);
				
	mysqli_query($con, "INSERT INTO tbl_history_alerts (Site,Date,QuoteNo,TechnicianId) VALUES ('$site','$date','$quoteno','$techid')")or die(mysqli_error($con));
			
}

$query_thumbs = mysqli_query($con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />

      <link href="SpryAssets/SpryCollapsiblePanel-jc.css" rel="stylesheet" type="text/css">
      <script src="SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
      
      <link rel="stylesheet" type="text/css" href="highslide/highslide.css" />
      
      <script type="text/javascript" src="highslide/highslide.js"></script>
      <script type="text/javascript">
      <!--
      hs.graphicsDir = 'highslide/graphics/';
          hs.outlineType = 'rounded-white';
      
      function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
      }
      
      function MM_popupMsg(msg) { //v1.0
        alert(msg);
      }
      //-->
      </script>

	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
      
      
      <script type="text/javascript">
		
        $(document).ready(function () {
            $(".toggler").click(function (e) {
				e.preventDefault();
                $('.row' + $(this).attr('data-row')).slideToggle();
            });
        });
		
      </script>
      
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/script.js"></script>

      <!-- Fancybox -->
      <script type="text/javascript" src="../fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <script type="text/javascript" src="../fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
      
      <link rel="stylesheet" type="text/css" href="../fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />
      
      <script type="text/javascript">
          $(document).ready(function() {
  
              $('.fancybox').fancybox({
              
                  autoSize    : false, 
                  closeClick  : false, 
                  fitToView   : true, 
                  openEffect  : 'none', 
                  closeEffect : 'none',
				  width		  : '440',
				  height	  : '113',		 
                  type : 'iframe',
				  'afterClose' : function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
					  window.parent.location.reload(true);
                      },	
              });
  
              $('.photos').fancybox({
              
                  autoSize    : false, 
                  closeClick  : false, 
                  fitToView   : true, 
                  openEffect  : 'none', 
                  closeEffect : 'none',
				  width		  : '905',
				  height	  : '640',		 
                  type : 'iframe',
				  'afterClose' : function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
					  window.parent.location.reload(true);
                      },	
              });
  
              $('.reports').fancybox({
              
                  autoSize    : false, 
                  closeClick  : false, 
                  fitToView   : true, 
                  openEffect  : 'none', 
                  closeEffect : 'none',
				  width		  : '905',
				  height	  : '200',		 
                  type : 'iframe',
              });
			  
          });
      </script>
      <!-- End Fancybox -->

      <script src="countdown/jquery.plugin.js"></script>
      <script src="countdown/jquery.countdown.js"></script>
      
      <link rel="stylesheet" media="all" type="text/css" href="jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="jquery-ui-sliderAccess.js"></script>
      
      <!-- Countdown Timer -->
      <link rel="stylesheet" href="countdown/jquery.countdown.css">
      
	  <?php
	  $query_jc = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '". $_GET['Id'] ."'")or die(mysqli_error($con));
	  $row_jc = mysqli_fetch_array($query_jc);
	     
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
			  color: #FFF !important;
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
			  color:#FFF !important;
		  }
		  
		  </style>
		  
	  <?php } ?>
      <!-- End Countdown Timer -->
      
      <!-- Costing -->
	  <script type="text/javascript">
	  
	  <!-- Health & Safety -->
  $(document).ready(function() {
		  
	  $('#add-row').click(function() {
		  
		  $('#health tbody').append('<tr><td colspan="2" class="td-right"><input name="hes-c[]" type="text" class="tarea-100" id="hes-c[]" value="" placeholder="Description"></td><td width="85" align="right" class="td-right"><input name="hes-price-c[]" type="text" class="tarea-100-right" id="hes-price-c[]" value="" placeholder="0.00"></td><td class="td-right" width="25" align="center"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRow(this)"></a></td></tr>');
		 
	  });
	  <!-- End Health & Safety -->
          
	  <!-- Material -->
	  $('#add-row-m').click(function() {
		  
		  $('#material tbody').append('<tr><td colspan="2" class="td-right"><input name="material-c[]" type="text" class="tarea-100" id="hmaterial-c[]" value="" placeholder="Description"></td><td width="85" align="right" class="td-right"><input name="material-price-c[]" type="text" class="tarea-100-right" id="material-price-c[]" value="" placeholder="0.00"></td><td class="td-right" width="25" align="center"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowMat(this)"></a></td></tr>');
		 
	  });
	  <!-- End Material -->
          
	  <!-- Equipment -->
	  $('#add-row-e').click(function() {
		  
		  $('#equipment tbody').append('<tr><td colspan="2" class="td-right"><input name="equipment-c[]" type="text" class="tarea-100" id="equipment-c[]" value="" placeholder="Description"></td><td width="85" align="right" class="td-right"><input name="equipment-price-c[]" type="text" class="tarea-100-right" id="equipment-price-c[]" value="" placeholder="0.00"></td><td class="td-right" width="25" align="center"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowEquip(this)"></a></td></tr>');
		 
	  });
	  <!-- End Equipment -->
          
	  <!-- Labour -->
	  $('#add-row-l').click(function() {
		  
		  $('#labour tbody').append('<tr><td colspan="2" class="td-right"><input labour-c[]" type="text" class="tarea-100" id="hlabour-c[]" value="" placeholder="Description"></td><td width="85" align="right" class="td-right"><select name="labour-price-c[]" class="tarea-100" id="labour-price-c[]"><option value="">Select one...</option><?php 
			
			$query_dd = mysqli_query($con, "SELECT * FROM tbl_costing_labour_rates")or die(mysqli_error($con));
			while($row_costing_dd = mysqli_fetch_array($query_dd)){ 
			
			?><option value="<?php echo $row_costing_dd['Name'] .'-'. $row_costing_dd['Rate']; ?>" <?php if($row_costing_dd['Rate'] == $row['Price']){ ?>selected="selected"<?php } ?>><?php echo $row_costing_dd['Name']; ?></option><?php } ?></select></td><td class="td-right" width="25" align="center"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowLabour(this)"></a></td></tr>');
		 
	  });
	  <!-- End Labour -->
          
	  <!-- Outsourced -->
	  $('#add-row-o').click(function() {
		  
		  $('#outsource tbody').append('<tr><td colspan="2" class="td-right"><input name="outsource-c[]" type="text" class="tarea-100" id="houtsource-c[]" value="" placeholder="Description"></td><td width="85" align="right" class="td-right"><input name="outsource-price-c[]" type="text" class="tarea-100-right" id="outsource-price-c[]" value="" placeholder="0.00"></td><td class="td-right" width="25" align="center"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowOutsource(this)"></a></td></tr>');
		 
	  });
	  <!-- End Outsourced -->
          
	  <!-- Health Quote -->
	  $('#add-row-h-q').click(function() {
		  
		  $('#health-q tbody').append('<tr><td class="td-right"><input name="desc-hes[]" type="text" class="tarea-100" id="material" value="" /></td><td width="75" align="center" class="td-right"><input name="unit-hes[]" type="text" class="tarea-100-centre" id="unit_m" value="" /></td><td width="75" align="center" class="td-right"><input name="qty-hes[]" type="text" class="tarea-100-centre" id="qty_m" value="" placeholder="0" /></td><td width="75" align="center" class="td-right"><input name="price-hes[]" type="text" class="tarea-100-right" id="Price" value="" placeholder="0.00" /></td><td width="75" align="center" class="td-right"><input name="total-hes[]" type="text" disabled="disabled" class="tarea-100-right" id="total_m" value="" placeholder="0.00" /></td><td width="20" align="center" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowHealthQ(this)"></a></td></tr>');
		 
	  });
	  <!-- End Health Quote -->
          
	  <!-- Labour Quote -->
	  $('#add-row-l-q').click(function() {
		  
		  $('#labour-q tbody').append('<tr><td valign="bottom" class="td-right"><textarea name="labour[]" rows="5" class="tarea-100" id="labour"></textarea></td><td width="75" align="center" valign="top" class="td-right"><input name="unit_l[]" type="text" class="tarea-100" id="unit_l" value="hours" /></td><td width="75" align="center" valign="top" class="td-right"><input name="qty_l[]" type="text" class="tarea-100" id="qty_l" value="" /></td><td width="75" align="center" valign="top" class="td-right"><input name="price_l[]" type="text" class="tarea-100" id="Price" value="" /></td><td width="75" align="right" valign="top" class="td-right"><input name="total_l[]" type="text" disabled="disabled" class="tarea-100-right" id="total_l" value="" /></td><td width="20" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowLabourQ(this)"></a></td></tr>');
		 
	  });      
	  <!-- End Labour Quote -->
          
	  <!-- Material Quote -->
	  $('#add-row-m-q').click(function() {
		  
		  $('#material-q tbody').append('<tr><td class="td-right"><input name="material[]" type="text" class="tarea-100" id="material" value="" placeholder="Description" /></td><td width="75" class="td-right"><input name="unit_m[]" type="text" class="tarea-100-centre" id="unit_m" value="" /></td><td width="75" class="td-right"><input name="qty_m[]" type="text" class="tarea-100-centre" id="qty_m" value="" /></td><td width="75" class="td-right"><input name="price_m[]" type="text" class="tarea-100-right" id="Price" value="" /></td><td width="75" align="right" class="td-right"><input name="total_m[]" type="text" disabled="disabled" class="tarea-100-right" id="total_m" value="" /></td><td width="20" valign="top" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowMaterialQ(this)"></a></td></tr>');
	  });
	  <!-- End Material Quote -->
          
	  <!-- Equipment Quote -->
	  $('#add-row-e-q').click(function() {
		  
		  $('#equipment-q tbody').append('<tr><td class="td-right"><input name="desc-e[]" type="text" class="tarea-100" id="material" value="" placeholder="Description" /></td><td class="td-right"><input name="unit-e[]" type="text" class="tarea-100-centre" id="unit_m" value="" /></td><td class="td-right"><input name="qty-e[]" type="text" class="tarea-100-centre" id="qty_m" value="" /></td><td class="td-right"><input name="price-e[]" type="text" class="tarea-100-right" id="Price" value="" /></td><td class="td-right"><input name="total-e[]" type="text" disabled="disabled" class="tarea-100" id="total_m" value="" /></td><td class="td-right" width="20"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowEquipmentQ(this)"></a></td></tr>');
	  });
	  <!-- End Equipment Quote -->
          
	  <!-- Transport Quote -->
	  $('#add-row-t-q').click(function() {
		  
		  $('#transport-q tbody').append('<tr><td class="td-right"><input name="t_comment[]" type="text" class="tarea-100" id="t_comment[]" value="" placeholder="Description" /></td><td width="58" class="td-right"><input name="unit_t[]" type="text" class="tarea-100-centre" id="unit_t" value="km" /></td><td width="58" class="td-right"><input name="transport[]" type="text" class="tarea-100-centre" id="transport" value="" /></td><td width="58" class="td-right"><input name="qty_t[]" type="text" class="tarea-100-centre" id="qty_t" value="" /></td><td width="58" class="td-right"><input name="price_t[]" type="text" class="tarea-100-right" id="price_t" value="" /></td><td width="58" class="td-right"><input name="total_t[]" type="text" disabled="disabled" class="tarea-100-right" id="total_t" value="" /></td><td class="td-right" width="20"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowTransportQ(this)"></a></td></tr>');
	  });	  
  });
	  
      function deleteRow(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("health").deleteRow(i);
      }
	  
      function deleteRowMat(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("material").deleteRow(i);
      }
	  
      function deleteRowEquip(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("equipment").deleteRow(i);
      }
	  
      function deleteRowLabour(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("labour").deleteRow(i);
      }
	  
      function deleteRowOutsource(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("outsource").deleteRow(i);
      }
	  
      function deleteRowHealthQ(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("health-q").deleteRow(i);
      }
	  
      function deleteRowLabourQ(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("labour-q").deleteRow(i);
      }
	  
      function deleteRowMaterialQ(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("material-q").deleteRow(i);
      }
      
      function deleteRowEquipmentQ(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("equipment-q").deleteRow(i);
      }
      
      function deleteRowTransportQ(r) {
          
          var i = r.parentNode.parentNode.rowIndex;
          document.getElementById("transport-q").deleteRow(i);
      }
	  <!-- End Equipment Quote -->
	  
      </script>
      <!-- Transport Costing -->
      
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
            <li><a href="#">Credit Note</a></li>
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
      <div id="main-wrapper">
      <form id="form2" name="form2" method="post" action="quote-calc-new.php?Id=<?php echo $quoteno; ?>&update">
      
      <!-- Quote To Details -->
       <div id="list-border">
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
           <tr>
             <td colspan="7" class="td-header"><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                 <td>Job Card</td>
                 <td align="right"><select name="status" class="select-transparent" id="status"  dir="rtl" onchange="MM_jumpMenu('parent',this,0)">
                   <option value=""  dir="ltr">Select one...</option>
                   <?php while($row_status = mysqli_fetch_array($query_status)){  ?>
                   <option value="jc-calc.php?Id=<?php echo $_GET['Id']; ?>&Status=<?php echo $row_status['Id']?>" <?php if($row_status['Id'] == $row_jc['Status']){ echo 'selected="selected"'; } ?>  dir="ltr"><?php echo $row_status['Status']?></option>
                   <?php } ?>
                 </select></td>
               </tr>
             </table></td>
           </tr>
           <tr>
             <td width="90" class="td-left">Oil Company</td>
             <td width="200" class="td-right">
			   <a href="company.php?Id=<?php echo $_GET['Id']; ?>" class="fancybox">
			     <?php echo $row_Recordset5['Name']; ?>
               </a>
             </td>
             <td width="90" class="td-left">Site</td>
             <td width="200" class="td-right">
			   <a href="company.php?Id=<?php echo $_GET['Id']; ?>" class="fancybox">
			     <?php echo $row_Recordset5['Name_1']; ?>
               </a>
             </td>
             <td width="90" class="td-left">Date</td>
             <td colspan="2" class="td-right"><input name="date" class="tarea-100" id="date" style="cursor:text" value="<?php echo $row_Recordset5['Date']; ?>" />
               <script type="text/javascript">
                    $('#date').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                  </script></td>
           </tr>
           <tr>
             <td rowspan="2" valign="top" class="td-left">Address</td>
             <td rowspan="2" valign="top" class="td-right" style="padding-bottom:0px;"><?php echo nl2br($row_Recordset3['Address']); ?></td>
             <td class="td-left">Address</td>
             <td class="td-right"><?php echo char_limit($row_Recordset5['Address'], 30); ?></td>
             <td class="td-left">Quote No.</td>
             <td colspan="2" class="td-right"><input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $row_Recordset5['QuoteNo']; ?>" /></td>
           </tr>
           <tr>
             <td class="td-left">Attention</td>
             <td class="td-right"><input name="att" type="text" class="tarea-100" id="att" value="<?php echo $row_Recordset5['Attention']; ?>" /></td>
             <td class="td-left">Reference</td>
             <td colspan="2" class="td-right"><input name="fmc" type="text" class="tarea-100" id="fmc" style="cursor:text" value="<?php fmc($con, $_GET['Id']); ?>" /></td>
           </tr>
           <tr>
             <td class="td-left">Description</td>
             <td colspan="6" class="td-right"><input name="jobdescription" type="text" class="tarea-100" id="jobdescription" style="" value="<?php echo $row_Recordset5['JobDescription']; ?>" /></td>
           </tr>
         </table>
       </div>
       <!-- End Quote To Details -->
       
       <!-- Requested Completion Date -->
       <div id="list-border" style="margin-top:15px; margin-bottom:15px;">
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
           <tr>
             <td width="25%" rowspan="2" class="td-right"><div id="<?php echo $class; ?>">
               <div id="defaultCountdown"></div>
             </div></td>
             <td width="25%" rowspan="2" class="td-right"><div id="defaultCountdown" class="sla-status">
               <table width="100%" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                 <tr>
                   <td height="40" align="center" valign="middle"><?php echo $sla_status; ?></td>
                 </tr>
               </table>
             </div></td>
             <td width="25%" class="td-left">Received Date </td>
             <td width="25%" align="right" class="td-right"><input name="date1" <?php echo $sla; ?> class="tarea-100" id="date1" value="<?php echo $row_jc['SlaStart']; ?>" disabled="disabled" />
               <script type="text/javascript">
                    $('#date1').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                   </script></td>
           </tr>
           <tr>
             <td width="25%" class="td-left">Requested Completion </td>
             <td width="25%" align="right" class="td-right"><input name="date2" <?php echo $sla; ?> class="tarea-100" id="date2" value="<?php echo $row_jc['SlaEnd']; ?>" />
               <script type="text/javascript">
                  $('#date2').datepicker({
                  dateFormat: "yy-mm-dd"
                  });
                </script></td>
           </tr>
         </table>
       </div>
       <!-- End Requested Completion Date -->
       
       <!-- Overview -->
       <div id="list-border" style="margin-top:15px">
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
           <tr>
             <td colspan="7" class="td-header">Overview</td>
           </tr>
           <tr>
             <td colspan="7" class="td-right"><textarea name="notes" rows="5" class="tarea-100" id="notes"><?php echo $row_qs['Notes']; ?></textarea></td>
           </tr>
         </table>
       </div>
       <!-- End Overview -->
       
       <!-- Allocate Technicians -->
       <div id="list-border" style="margin-top:15px">
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
           <tr>
             <td class="td-header"><a href="#" class="toggler sm-bar" data-row="Allocate">Allocate Technicians</a></td>
           </tr>
           <tr class="rowAllocate" style="display: none">
             <td class="td-right"><?php
                $i = 0;
                
                while($row_techs = mysqli_fetch_array($query_techs)){
                
                    $i++;
                
               ?>
               <div class="allocate-tech">
                 <table border="0" cellspacing="0" cellpadding="0">
                   <tr>
                     <td width="20"><input name="tech" type="radio" id="tech" value="<?php echo $row_techs['Id']; ?>"<?php if($row_techs['Id'] == $row_qs['TechnicianId']) { ?> checked="checked"<?php } ?> /></td>
                     <td><label for="tech<?php echo $i; ?>"><?php echo $row_techs['Name']; ?></label></td>
                   </tr>
                 </table>
               </div>
               <?php } ?></td>
           </tr>
         </table>
       </div>
       <!-- End Allocate Technicians -->
       
       <!-- Internal Notes -->
       <div id="list-border" style="margin-top:15px">
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
           <tr>
             <td colspan="7" class="td-header"><a href="#" class="toggler sm-bar" data-row="Internal">Internal Notes</a></td>
           </tr>
           <tr class="rowInternal" style="display:none">
             <td colspan="7" class="td-right">
			 <?php do { ?>
               <span class="history-bg-con"> <span class="history-bg">
                 <?php 
                
                $newdate = date('d/m H:i', strtotime($row_note_comments['Date']));
                
                if($row_note_comments['Mobile'] == 1){ 
                
                echo $row_note_comments['Name_1'];
                
                } else { 
                
                echo $row_note_comments['Name'];
                
                } 
                ?>
                 <?php echo $newdate; ?> </span> </span> &nbsp;<?php echo nl2br($row_note_comments['Comments']); ?>
               <?php } while ($row_note_comments = mysqli_fetch_assoc($query_note_comments)); ?></td>
           </tr>
           <tr>
             <td colspan="7" class="td-right">
               <textarea name="internal_notes" rows="3" class="tarea-100" id="internal_notes"></textarea>
             </td>
           </tr>
         </table>
       </div>
       <!-- End Internal Notes -->
       
       <!-- Images -->
		<?php if(mysqli_num_rows($query_thumbs) >= 1){ ?>
        <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
              <tr>
                <td class="td-header">Images &nbsp; <span class="btn-red-generic"><?php echo $image_error; ?></span></td>
              </tr>
              <tr>
                <td>
                  <?php while($row_thumbs = mysqli_fetch_array($query_thumbs)){ ?>
                  <a href="photos/<?php echo $row_thumbs['Image']; ?>" class="look_inside" onclick="return hs.expand(this, {captionId: 'caption1'})"></a>
                  <div id="qs-thumbs">
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <a href="../photos/<?php echo $row_thumbs['Image']; ?>" class="look_inside" onclick="return hs.expand(this, {captionId: 'caption1'})"><img src="../photos/thumbnails/<?php echo $row_thumbs['Image']; ?>" width="100"></a>
                        </td>
                        <?php // if($row_Recordset5['Status'] == 0){ ?>
                        <td valign="bottom">
                          <input name="attach[]" type="checkbox" id="attach[]" value="<?php echo $row_thumbs['Image']; ?>" <?php echo $class; ?>>
                        </td>
                        <?php // } ?>
                      </tr>
                    </table>
                  </div>
                  <a href="photos/<?php echo $row_thumbs['Image']; ?>" class="look_inside" onclick="return hs.expand(this, {captionId: 'caption1'})">
                  </a>
                  <?php } ?>
                </td>
              </tr>
            </table>
        </div>
		<?php } ?>
       <!-- End Images -->
       
       <!-- Costing -->
       <div id="list-border" style="margin-top:15px">
         <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
           <tr>
             <td colspan="7" class="td-header"><a href="#" class="toggler sm-bar" data-row="Costing">Costing</a></td>
           </tr>
         </table>
       </div>
         
         <!-- Health & Safety -->
         <?php
		 
		 if(isset($_POST['save'])){
			 
			 $display_row = 'table';
			 $display_div = 'block';
			 
		 } else {
			 
			 $display_row = 'none';
			 $display_div = 'none';
			 
		 }
		 ?>
         
         <div class="rowCosting" style="display:<?php echo $display_div; ?>">
         
         <div id="list-border" class="rowCosting" style="display:<?php echo $display_div; ?>">
         
         <table width="100%" border="0" cellpadding="4" cellspacing="1" id="health">
           <tr>
             <td colspan="3" class="td-sub-header">Health &amp; Safety</td>
             <td width="20" class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row"></a></td>
           </tr>
           <?php
            
            $_SESSION['totals'] = '';
			$i = 0;
                
			$query = mysqli_query($con, "SELECT * FROM tbl_costing_hes WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
			while($row = mysqli_fetch_array($query)){
				
				$i++;
				
				if($i == 1){
					
					$value = 'Risk assessment, safety documents, barricades and total health &amp; safety compliance.';
					
				} else {
					
					$value = $row['Description'];
				}
                    
            ?>
           <tr>
             <td colspan="2" class="td-right"><input name="hes-c[]" type="text" class="tarea-100" id="hes-c[]" value="<?php echo $value; ?>" /></td>
             <td width="85" align="right" class="td-right"><input name="hes-price-c[]" type="text" class="tarea-100-right" id="hes-price-c[]" value="<?php echo $row['Price']; ?>" placeholder="0.00" /></td>
             <td width="25" align="center" class="td-right">
               <a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRow(this)"></a>
               <input type="hidden" name="id-c-hes[]" id="id-c-hes[]" value="<?php echo $row['Id']; ?>">
             </td>
           </tr>
           <?php 
			
			} 
			
			$query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
			$row2 = mysqli_fetch_array($query_material2);
					  
			$_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
			
			?>
         </table>
         <table width="100%" border="0" cellpadding="4" cellspacing="1">
           <tr>
             <td colspan="2" class="td-right">&nbsp;</td>
             <td width="85" align="right" class="td-right"><input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>" /></td>
             <td width="35" align="center" class="td-right">&nbsp;</td>
           </tr>
         </table>
         <!-- End Health & Safety -->
         
         <!-- Material -->
         <table width="100%" border="0" cellpadding="4" cellspacing="1" id="material">
           <tr>
             <td colspan="3" class="td-sub-header">Material</td>
             <td width="20" class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-m"></a></td>
           </tr>
           <?php
                
			$query = mysqli_query($con, "SELECT * FROM tbl_costing_material WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
			while($row = mysqli_fetch_array($query)){
                    
            ?>
           <tr>
             <td colspan="2" class="td-right"><input name="material-c[]" type="text" class="tarea-100" id="material-c[]" value="<?php echo $row['Description']; ?>" placeholder="Description" /></td>
             <td width="85" align="right" class="td-right"><input name="material-price-c[]" type="text" class="tarea-100-right" id="material-price-c[]" value="<?php echo $row['Price']; ?>" placeholder="0.00" /></td>
             <td width="25" align="center" class="td-right">
               <a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowMat(this)"></a>
               <input type="hidden" name="id-c-m[]" id="id-c-m[]" value="<?php echo $row['Id']; ?>">
             </td>
           </tr>
           <?php 
			
			} 
          
			$query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_material WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
			$row2 = mysqli_fetch_array($query_material2);
					  
			$_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
			?>
         </table>
         <table width="100%" border="0" cellpadding="4" cellspacing="1">
           <tr>
             <td colspan="2" class="td-right">&nbsp;</td>
             <td width="85" align="right" class="td-right"><input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>" /></td>
             <td width="35" align="center" class="td-right">&nbsp;</td>
           </tr>
         </table>
         <!-- End Material -->
         
         <!-- Equipment -->
         <table width="100%" border="0" cellpadding="4" cellspacing="1" id="equipment">
           <tr>
             <td colspan="3" class="td-sub-header">Equipment & Machinery</td>
             <td width="20" class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-e"></a></td>
           </tr>
           <?php
			
			$query = mysqli_query($con, "SELECT * FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
			while($row = mysqli_fetch_array($query)){
                    
            ?>
           <tr>
             <td colspan="2" class="td-right"><input name="equipment-c[]" type="text" class="tarea-100" id="equipment-c[]" value="<?php echo $row['Description']; ?>" placeholder="Description" /></td>
             <td width="85" align="right" class="td-right"><input name="equipment-price-c[]" type="text" class="tarea-100-right" id="equipment-price-c[]" value="<?php echo $row['Price']; ?>" placeholder="0.00" /></td>
             <td width="25" align="center" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowEquip(this)"></a></td>
           </tr>
           <?php 
			
			} 
              
			$query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
			$row2 = mysqli_fetch_array($query_material2);
				  
			$_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
			
            ?>
         </table>
         <table width="100%" border="0" cellpadding="4" cellspacing="1">
           <tr>
             <td colspan="2" class="td-right">&nbsp;</td>
             <td width="85" align="right" class="td-right"><input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>" /></td>
             <td width="35" align="center" class="td-right">&nbsp;</td>
           </tr>
         </table>
         <!-- End Equipment -->
         
         <!-- Labour -->
         <table width="100%" border="0" cellpadding="4" cellspacing="1" id="labour">
           <tr>
             <td colspan="3" class="td-sub-header">Labour</td>
             <td width="20" class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-l"></a></td>
           </tr>
           <?php
			
			$query = mysqli_query($con, "SELECT * FROM tbl_costing_labour WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
			while($row = mysqli_fetch_array($query)){
                    
            ?>
           <tr>
             <td colspan="2" class="td-right"><input name="labour-c[]" type="text" class="tarea-100" id="labour-c[]" value="<?php echo $row['Description']; ?>" placeholder="Description" /></td>
             <td width="85" align="right" class="td-right">
             
             <select name="labour-price-c[]" class="tarea-100" id="labour-price-c[]">
               <option value="">Select one...</option>
               <?php 
				
                $query_dd = mysqli_query($con, "SELECT * FROM tbl_costing_labour_rates")or die(mysqli_error($con));
                while($row_costing_dd = mysqli_fetch_array($query_dd)){ 
				
				?>
               <option value="<?php echo $row_costing_dd['Name'] .'-'. $row_costing_dd['Rate']; ?>" <?php if($row_costing_dd['Rate'] == $row['Price']){ ?>selected="selected"<?php } ?>><?php echo $row_costing_dd['Name']; ?></option>
               <?php } ?>
             </select></td>
             <td width="25" align="center" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowLabour(this)"></a></td>
           </tr>
           <?php 
			
			} 
			
            $query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_labour WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
            $row2 = mysqli_fetch_array($query_material2);
                    
            $_SESSION['totals'] = ($row2['Total'] * $row_labour['Days']) + $_SESSION['totals'];
            ?>
         </table>
         <table width="100%" border="0" cellpadding="4" cellspacing="1">
           <tr>
             <td colspan="2" class="td-right">&nbsp;</td>
             <td width="85" align="right" class="td-right">
               <input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php echo number_format(($row2['Total'] * $row_labour['Days']), 2); ?>" />
             </td>
             <td width="35" align="center" class="td-right">&nbsp;</td>
           </tr>
         </table>
         <!-- End Labour -->
         
         <!-- Outsourced -->
         <table width="100%" border="0" cellpadding="4" cellspacing="1" id="outsource">
           <tr>
             <td colspan="3" class="td-sub-header">Outsourced Service</td>
             <td width="20" class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-o"></a></td>
           </tr>
           <?php
			
			$query = mysqli_query($con, "SELECT * FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($query)){
				
            ?>
           <tr>
             <td colspan="2" class="td-right"><input name="outsource-c[]" type="text" class="tarea-100" id="outsource-c[]" value="<?php echo $row['Description']; ?>" placeholder="Description" /></td>
             <td width="85" align="right" class="td-right"><input name="outsource-price-c[]" type="text" class="tarea-100-right" id="outsource-price-c[]" value="<?php echo $row['Price']; ?>" placeholder="0.00" /></td>
             <td width="25" align="center" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowOutsource(this)"></a></td>
           </tr>
           <?php 
			
			} 
			
            $query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
            $row2 = mysqli_fetch_array($query_material2);
                    
            $_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
			
            ?>
         </table>
         <table width="100%" border="0" cellpadding="4" cellspacing="1">
           <tr>
             <td colspan="2" class="td-right">&nbsp;</td>
             <td width="85" align="right" class="td-right"><input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>" /></td>
             <td width="35" align="center" class="td-right">&nbsp;</td>
           </tr>
         </table>
         <!-- End Outsourced -->
         
       
	  <?php
      $costing_transport = $row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate'];
      $costing_labour = $row_labour['Days'] * $row_labour['Price'];
      $costing_subsistence  = $row_labour['Subsistence'] * $row_labour['Days'];
      $costing_material = $row_sum_m['Total_1'];
      $costing_hes = $row_sum_safety['Total_1'];
      $costing_equipment = $row_sum_e['Total_1'];
      $costing_outsourcing = $row_sum_outsourcing['Total_1'];
      
      $admin_fee = ($_SESSION['totals'] + $costing_subsistence + ($row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate'])) * 0.1;
      
      ?>
       
       <table width="100%" border="0" cellpadding="4" cellspacing="1" id="outsource">
         <tr>
           <td align="right" class="td-right"><strong>Return Distance</strong></td>
           <td width="85" class="td-right">
             <input name="return" type="text" class="tarea-100-right" id="return" value="<?php echo $row_transport['ReturnDistance']; ?>">
           </td>
           <td width="85" align="right" class="td-right">
             <input name="textfield" type="text" disabled class="tarea-100-right" style="font-weight:bold" value="R<?php echo number_format($row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate'], 2); ?>">
           </td>
           <td width="35" align="center" class="td-right">&nbsp;</td>
         </tr>
         <tr>
           <td align="right" class="td-right"><strong>Trips</strong></td>
           <td width="85" class="td-right">
             <input name="trips" type="text" class="tarea-100-right" id="trips" value="<?php echo $row_transport['Trips']; ?>">
           </td>
           <td width="85" align="right" class="td-right">&nbsp;</td>
           <td width="35" align="center" class="td-right">&nbsp;</td>
         </tr>
         <tr>
           <td align="right" class="td-right"><strong>Days On Site</strong></td>
           <td width="85" class="td-right">
             <input name="days" type="text" class="tarea-100-right" id="days" value="<?php echo $row_labour['Days']; ?>">
           </td>
           <td width="85" align="right" class="td-right">&nbsp;</td>
           <td width="35" align="center" class="td-right">&nbsp;</td>
         </tr>
         <tr>
           <td align="right" class="td-right"><strong>Subsistence / Night</strong></td>
           <td class="td-right"><input name="subsistence" type="text" class="tarea-100-right" id="subsistence" value="<?php echo $row_labour['Subsistence']; ?>" /></td>
           <td align="right" class="td-right"><input name="textfield" type="text" disabled="disabled" class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php echo number_format($row_labour['Subsistence'] * $row_labour['Days'], 2); ?>" /></td>
           <td align="center" class="td-right">&nbsp;</td>
         </tr>
         <tr>
           <td align="right" class="td-right"><strong>Admin Fee</strong></td>
           <td class="td-right"><input name="admin-fee" type="text" class="tarea-100-right" id="admin-fee" value="10%" readonly="readonly" /></td>
           <td align="right" class="td-right"><input name="textfield" type="text" disabled="disabled" class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php echo number_format($admin_fee, 2); ?>" /></td>
           <td align="center" class="td-right">&nbsp;</td>
         </tr>
         <tr>
           <td align="right" class="td-right"><strong>Zone Charge</strong></td>
           <td class="td-right"><input name="zone" type="text" class="tarea-100-right" id="zone" value="<?php zone_charge($row_transport['Trips'], $row_transport['ReturnDistance'], $_SESSION['totals']); ?>" readonly="readonly" /></td>
           <td align="right" class="td-right"><input name="textfield" type="text" disabled="disabled" class="tarea-100-right" id="textfield" style="font-weight:bold" value="R<?php zone_charge($row_transport['Trips'], $row_transport['ReturnDistance'], $_SESSION['totals']); ?>" /></td>
           <td align="center" class="td-right">&nbsp;</td>
         </tr>
         <tr>
           <td align="right" class="td-right">&nbsp;</td>
           <td class="td-right">&nbsp;</td>
           <td align="right" class="td-right">
			<?php
            $costing_total = $_SESSION['totals'] + $costing_subsistence + $_SESSION['zone-charge'] + $admin_fee + ($row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate']); 
            ?>
            <input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="font-weight:bold; color:#F00" value="R<?php echo number_format($costing_total, 2); ?>"></td>
           </td>
         </tr>
       </table>
       </div>
         
       <table width="100%">
         <tr>
           <td>
             <div style="margin-top:15px;">
               <table border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td><input name="quote" type="submit" class="btn-new" id="quote" value="Continue To Quote"></td>
                  <td><input name="save" type="submit" class="btn-new" id="save" value="Save" style="margin-left:5px"></td>
                </tr>
               </table>  
             </div>
           </td>
         </tr>
       </table>  
       </div> 
       <!-- End Costing -->
       
       <!-- Quotation -->
       <?php if(isset($_COOKIE['costing-'. $_GET['Id']]) || ($_SESSION['kt_login_id'] == 50)){ ?>
       
       <div id="list-border" style="margin-top:15px">
         <table width="100%" border="0" cellspacing="1" cellpadding="0" id="health-q">
           <tr>
             <td class="td-header">Description</td>
             <td align="center" class="td-header">Unit</td>
             <td align="center" class="td-header">Qty</td>
             <td align="right" class="td-header">Unit Price</td>
             <td align="right" class="td-header">Total</td>
             <td class="td-header-right">&nbsp;</td>
           </tr>
           
           <!-- Health & Safety -->
           <tr>
             <td colspan="5" class="td-sub-header">Health &amp; Safety Compliance</td>
             <td class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-h-q"></a></td>
           </tr>
           <?php
			
			$i = 0;
			
            $query = mysqli_query($con, "SELECT * FROM tbl_qs_hes WHERE QuoteNo = '$quoteno'") or die(mysqli_error($db_con));
            while($row = mysqli_fetch_array($query)){
				
				$i++;
				
				if($i == 0){
					
					$value = 'Risk assessment, safety documents, barricades and total health &amp; safety compliance.';
					
				} else {
					
					$value = $row['Description'];
				}
				
            ?>
           <tr>
             <td class="td-right"><input name="desc-hes[]" type="text" class="tarea-100" id="material" value="<?php echo $value; ?>" /></td>
             <td width="75" align="center" class="td-right"><input name="unit-hes[]" type="text" class="tarea-100-centre" id="unit_m" value="<?php echo $row['Unit']; ?>" /></td>
             <td width="75" align="center" class="td-right"><input name="qty-hes[]" type="text" class="tarea-100-centre" id="qty_m" value="<?php echo $row['Qty']; ?>" /></td>
             <td width="75" align="center" class="td-right"><input name="price-hes[]" type="text" class="tarea-100-right" id="Price" value="<?php echo $row['UnitPrice']; ?>" /></td>
             <td width="75" align="center" class="td-right"><input name="total-hes[]" type="text" disabled="disabled" class="tarea-100-right" id="total_m" value="<?php echo $row['Total']; ?>" /></td>
             <td width="20" align="center" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowHealthQ(this)"></a></td>
           </tr>
           <?php } ?>
         </table>
         <!-- Health & Safety -->
         
         <!-- Labour -->
         <table width="100%" border="0" cellspacing="1" cellpadding="0" id="labour-q">
           <tr>
             <td colspan="5" class="td-sub-header">Labour</td>
             <td class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-l-q"></a></td>
           </tr>
           <?php
			
			$query = mysqli_query($con,"SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1' ORDER BY Id ASC")or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($query)){
			
			?>
           <tr>
             <td valign="bottom" class="td-right">
             <textarea name="labour[]" rows="5" class="tarea-100" id="labour"><?php  echo $row['Description']; ?></textarea>
           </td>
             <td width="75" align="center" valign="top" class="td-right">
               <input name="unit_l[]" type="text" class="tarea-100-centre" id="unit_l" value="hours" />
             </td>
             <td width="75" align="center" valign="top" class="td-right">
               <input name="qty_l[]" type="text" class="tarea-100-centre" id="qty_l" value="<?php echo $row['Qty']; ?>" />
             </td>
             <td width="75" align="center" valign="top" class="td-right">
               <input name="price_l[]" type="text" class="tarea-100-right" id="Price" value="<?php echo $row['Price']; ?>" />
             </td>
             <td width="75" align="right" valign="top" class="td-right">
               <input name="total_l[]" type="text" disabled="disabled" class="tarea-100-right" id="total_l" value="<?php echo $row['Total1']; ?>" />
             </td>
             <td width="20" valign="top" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowLabourQ(this)"></a></td>
           </tr>
           <?php } ?>
         </table>
         <!-- End Labour -->
         
         <!-- Material -->
         <table width="100%" border="0" cellspacing="1" cellpadding="0" id="material-q">
           <tr>
             <td colspan="5" valign="bottom" class="td-sub-header">Material</td>
             <td class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-m-q"></a></td>
           </tr>
           <?php
			
			$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($query)){
			?>
           <tr>
             <td class="td-right"><input name="material[]" type="text" class="tarea-100" id="material" value="<?php echo $row['Description']; ?>" placeholder="Description" /></td>
             <td width="75" class="td-right"><input name="unit_m[]" type="text" class="tarea-100-centre" id="unit_m" value="<?php echo $row['Unit']; ?>" /></td>
             <td width="75" class="td-right"><input name="qty_m[]" type="text" class="tarea-100-centre" id="qty_m" value="<?php echo $row['Qty']; ?>" /></td>
             <td width="75" class="td-right"><input name="price_m[]" type="text" class="tarea-100-right" id="Price" value="<?php echo $row['Price']; ?>" /></td>
             <td width="75" align="right" class="td-right"><input name="total_m[]" type="text" disabled="disabled" class="tarea-100-right" id="total_m" value="<?php echo $row['Total1']; ?>" /></td>
             <td width="20" valign="top" class="td-right"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowMaterialQ(this)"></a></td>
           </tr>
           <?php } ?>
         </table>
         <!-- End Material -->
         
         <!-- Equipment -->
         <table width="100%" border="0" cellspacing="1" cellpadding="0" id="equipment-q">
           <tr>
             <td colspan="5" valign="bottom" class="td-sub-header">Equipment</td>
             <td class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-e-q"></a></td>
           </tr>
           <?php
			
			$query = mysqli_query($con, "SELECT * FROM tbl_qs_equipment WHERE QuoteNo = '$quoteno'") or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($query)){
			?>
           <tr>
             <td class="td-right"><input name="desc-e[]" type="text" class="tarea-100" id="material" value="<?php echo $row['Description']; ?>" placeholder="Description" /></td>
             <td width="75" class="td-right"><input name="unit-e[]" type="text" class="tarea-100-centre" id="unit_m" value="<?php echo $row['Unit']; ?>" /></td>
             <td width="75" class="td-right"><input name="qty-e[]" type="text" class="tarea-100-centre" id="qty_m" value="<?php echo $row['Qty']; ?>" /></td>
             <td width="75" class="td-right"><input name="price-e[]" type="text" class="tarea-100-right" id="Price" value="<?php echo $row['UnitPrice']; ?>" /></td>
             <td width="75" class="td-right"><input name="total-e[]" type="text" disabled="disabled" class="tarea-100-right" id="total_m" value="<?php echo $row['Total']; ?>" /></td>
             <td class="td-right" width="20"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowEquipmentQ(this)"></a></td>
           </tr>
           <?php } ?>
         </table>
         <!-- End Equipment -->
         
         <!-- Transport -->
         <table width="100%" border="0" cellspacing="1" cellpadding="0" id="transport-q">
           <tr>
             <td colspan="6" valign="bottom" class="td-sub-header">Transport</td>
             <td class="td-sub-header"><a href="javascript:void(0);" class="btn-add-new" id="add-row-t-q"></a></td>
           </tr>
           <?php
			
			$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysqli_error($db_con));
			while($row = mysqli_fetch_array($query)){
			?>
           <tr>
             <td class="td-right"><input name="t_comment[]" type="text" class="tarea-100" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>" placeholder="Description" /></td>
             <td width="58" class="td-right"><input name="unit_t[]" type="text" class="tarea-100-centre" id="unit_t" value="km" /></td>
             <td width="58" class="td-right"><input name="transport[]" type="text" class="tarea-100-centre" id="transport" value="<?php echo $row['Description']; ?>" /></td>
             <td width="58" class="td-right"><input name="qty_t[]" type="text" class="tarea-100-centre" id="qty_t" value="<?php echo $row['Qty']; ?>" /></td>
             <td width="58" class="td-right"><input name="price_t[]" type="text" class="tarea-100-right" id="price_t" value="<?php echo $row['Price']; ?>" /></td>
             <td width="58" class="td-right"><input name="total_t[]" type="text" disabled="disabled" class="tarea-100-right" id="total_t" value="<?php echo $row['Total1']; ?>" /></td>
             <td class="td-right" width="20"><a name="remove-row" class="remove-row" id="remove-row" onclick="deleteRowTransportQ(this)"></a></td>
           </tr>
           <?php } ?>
         </table>
         <!-- End Transport -->
         
       </div>
       
       <!-- Totals -->
       <table border="0" align="right" cellpadding="0" cellspacing="0">
         <tr>
           <td>

             <div id="list-border" style="margin-top:15px; overflow:hidden">
             
              <table border="0" cellpadding="0" cellspacing="1">
                <tr>
                  <td align="right" class="td-left"><strong>Sub Total:&nbsp;&nbsp;</strong></td>
                  <td width="90" align="right" class="td-right">
                    <?php $subt = $row_Recordset4['SUM(Total1)'] + $row_sum_hes['Total_1'] + $row_sum_e['Total_1']; ?>
                    <input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="text-align:right; font-weight:bold; color:#F00" value="R<?php echo number_format( $subt,2); ?>">
                    <input name="subtotal" type="hidden" id="subtotal" value="<?php echo $subt; ?>">
                  </td>
                </tr>
                <tr>
                  <td align="right" class="td-left"><strong>Vat:&nbsp;&nbsp;</strong></td>
                  <td align="right" class="td-right">
                    <?php 
						
						$vat_rate = getVatRate($con, date('Y-m-d H:i:s'));
						$vat = ($row_Recordset4['SUM(Total1)'] + $row_sum_hes['Total_1'] + $row_sum_e['Total_1']) * ($vat_rate / 100); 
					?>
                    <input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="text-align:right; font-weight:bold; color:#F00" value="R<?php echo number_format($vat,2); ?>">
                  </td>
                </tr>
                <tr>
                  <td align="right" class="td-left"><strong>Total:&nbsp;&nbsp;</strong></td>
                  <td align="right" nowrap class="td-right">
                    <input name="textfield" type="text" disabled class="tarea-100-right" id="textfield" style="text-align:right; font-weight:bold; color:#F00" value="R<?php echo number_format($subt + $vat,2); ?>">
                  </td>
                </tr>
              </table>         
             
             </div>

           </td>
         </tr>
       </table>
       <!-- End Totals -->
       
       <!-- Buttons -->
       <table width="100%">
         <tr>
           <td align="right">
           
            <table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="3" align="right" valign="bottom" class="combo">&nbsp;</td>
              </tr>
              <tr>
                <td valign="bottom" class="combo">
                  <a href="delete.php?Id=<?php echo $_GET['Id']; ?>" class="btn-new">Delete</a>
                  <a href="quote-inv.php?Id=<?php echo $_GET['Id']; ?>" class="btn-new">Invoice</a>
                  <a href="images.php?Id=<?php echo $_GET['Id']; ?>" class="btn-new photos">Photos</a>
                  <a href="report.php?Id=<?php echo $_GET['Id']; ?>" class="btn-new reports">Report</a>
                  
                  <input name="jobno" type="text" class="quote-jc-no" id="jobno" value="<?php echo $row_Recordset2['FMC']; ?>">
                <input name="jobcard" type="submit" class="btn-new" id="jobcard" value="Job Card" /></td>
                <td colspan="2" align="right" valign="bottom" class="combo">
                  <input name="button2" type="submit" class="btn-new" id="button2" value="<?php echo round((($subt - $costing_total) / $subt) * 100); ?>">&nbsp;
                  <input name="Submit3" type="submit" class="btn-new" id="Submit3" value="Save">
                  <?php
                  $query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
                  $row = mysqli_fetch_array($query);
            
                  $status = $row['Status'];
            
                  // Awaiting approval
                  if($status == 0 || !empty($row_Recordset2['FMC'])){
                  ?>
                  <input name="qued" type="submit" class="btn-new" id="qued" value="Reject">
                  <input name="outbox" type="submit" class="btn-new" id="outbox" value="Approve">
                  <?php
                  // Qued
                  } if($status == 4 && empty($row_Recordset2['FMC'])){ ?>
                  <input name="approval" type="submit" class="btn-new" id="approval" value="Awaiting Approval">
                  <?php }  ?>
                </td>
              </tr>
            </table>
           
           </td>
         </tr>
       </table>
       <!-- End Buttons -->
       <?php } ?>
       <!-- End Quotation -->
          
   </form>           
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