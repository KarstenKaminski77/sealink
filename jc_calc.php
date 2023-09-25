<?php require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
<?php

$today = date('Y-m-j');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

// Start trigger
$formValidation = new tNG_FormValidation();
$tNGs->prepareValidation($formValidation);
// End trigger

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

require_once('functions/functions.php');

require_once('includes/common/KT_common.php');

require_once('includes/tng/tNG.inc.php');

select_db();

$jobid = $_GET['Id'];

if(isset($_POST['Submit3'])){
	
// Insert / update Pragma table
	
	$jobid = $_GET['Id'];
	
	$query = mysql_query("SELECT CompanyId, JobNo FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
	$row = mysql_fetch_array($query);
	
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
		
		mysql_query("DELETE FROM tbl_pragma WHERE JobNo = '$jobno'")or die(mysql_error());
		
		mysql_query("INSERT INTO tbl_pragma (JobNo,JobId,Component,FailureTypeCode,Failure,RootCause,Repair,PTW,PTWNumber,FollowUpWork) VALUES ('$jobno','$jobid','$component','$failure_type','$failure','$root_cause','$repair','$ptw','$ptw_number','$followup')")or die(mysql_error());
		
	}
	
// End insert / update Pragma table
	
// Allocate technicians to actual history
			
		if(isset($_POST['tech'])){
			
			mysql_query("DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'")or die(mysql_error());
			
		    $numrows = count($_POST['tech']);
			$techid = $_POST['tech'];
			$jobid = $_GET['Id'];
			$date = date('Y-m-d');
			
			$technician_id = array();
			
			for($i=0;$i<$numrows;$i++){
				
				$techid_1 = $techid[$i];
				$jobid = $_GET['Id'];
				
				$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
				$row = mysql_fetch_array($query);
				
				$siteid = $row['SiteId'];
				
				$query = mysql_query("SELECT * FROM tbl_sites WHERE Id = '$siteid'")or die(mysql_error());
				$row = mysql_fetch_array($query);
				
				$query2 = mysql_query("SELECT * FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId = '$techid_1'")or die(mysql_error());
				$row2 = mysql_fetch_array($query2);
				$numrows2 = mysql_num_rows($query2);
				
				$query3 = mysql_query("SELECT JobNo FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
				$row3 = mysql_fetch_array($query3);
				$numrows3 = mysql_num_rows($query3);
				
				$jobno = $row3['JobNo'];
				
				array_push($technician_id,$techid_1);
				
				$site = addslashes($row['Name']);
				
				mysql_query("INSERT INTO tbl_history_alerts (Site,JobNo,Date,JobId,TechnicianId) VALUES ('$site','$jobno','$date','$jobid','$techid_1')")or die(mysql_error());
				
			}
			
			mysql_query("DELETE FROM tbl_history_alerts WHERE JobNo = '' AND Site = '' AND JobId = '$jobid'")or die(mysql_error());
			
			mysql_query("DELETE FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId NOT IN ('".join("','", $technician_id)."')")or die(mysql_error());
		}
			
	
	
	if($_POST['complete'] == 1) {
		
		$siteid = $row['SiteId'];
		
		$query3 = mysql_query("SELECT * FROM tbl_sites WHERE Id = '$siteid'")or die(mysql_error());
		$row3 = mysql_fetch_array($query3);
		
		$site = $row3['Name'];
		$jobno = $row['JobNo'];
        $date = date('Y-m-d');
        $technician = $_POST['tech'];

        mysql_query("INSERT INTO tbl_history_alerts (JobNo,Date,JobId,TechnicianId,Site) VALUES ('$jobno','$date','$jobid','$technician','$site')")or die(mysql_error());
		
//        $techid = $_POST['tech'];
//		
//		$query4 = mysql_query("SELECT * FROM tbl_technicians WHERE Id = '$techid'")or die(mysql_error());
//		$row4 = mysql_fetch_array($query4);
//		
//		$cell = $row4['Cell'];
//		$to  = 'sms@messaging.clickatell.com'; 
//        $subject = 'Seavest';
//        $from = "control@seavest.co.za";
//
//$message = '
//user:seavest
//password:abc123
//api_id:3232946
//to:'. $cell .'
//reply: control@seavest.co.za
//concat: 3
//text: Job '. $jobno .' '. $site .' is now in progress';
//
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//
//$headers .= 'FROM: '. $from . "\r\n";
//
//mail($to, $subject, $message, $headers);
		
	}}

// Actual History

$actual_history = $_POST['comment'];
$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_actual_history WHERE JobId = '$jobid' AND Comments = '$actual_history'") or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

if(isset($_POST['comment']) && !empty($_POST['comment']) && $numrows == 0){
	
	$technicianid = $_SESSION['kt_login_id'];
	$date = date('Y-m-d H:i:s');
	$comments = $_POST['comment'];
	
	mysql_query("INSERT INTO tbl_actual_history (JobId,TechnicianId,Date,Comments) VALUES ('$jobid','$technicianid','$date','$comments')")or die(mysql_error());
	
}

// Upload PDF Job Card

$target_path = "jc-pdf/";

$target_path = $target_path . basename( $_FILES['pdf']['name']); 

if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)) {
	
	$pdf = $_FILES['pdf']['name'];
	$id = $_GET['Id'];
	
	mysql_query("UPDATE tbl_jc SET JobcardPDF = '$pdf' WHERE JobId = '$id'")or die(mysql_error());
}

$target_path = "images/history/";

$target_path = $target_path . basename( $_FILES['photo']['name']); 

if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
	
	$file_attachment = $_FILES['photo']['name'];
	$ext = explode(".", $file_attachment);
	$extension = $ext[1];
	$image = rename('images/history/'.$file_attachment, 'images/history/'. $_GET['Id'] .'-'. date('H-i-s') .'.'. $extension);
	$image_name = $_GET['Id'] .'-'. date('H-i-s') .'.'. $extension;
	
	mysql_query("INSERT INTO tbl_history_photos (Photo) VALUES ('$image_name')")or die(mysql_error());
	
	$query = mysql_query("SELECT * FROM tbl_history_photos ORDER BY Id DESC")or die(mysql_error());
	$row = mysql_fetch_array($query);
	
	$photoid = $row['Id'];
	$jobid = $_GET['Id'];
	
	mysql_query("INSERT INTO tbl_history_relation (JobId,PhotoId) VALUES ('$jobid','$photoid')")or die(mysql_error());
}

if(!empty($_POST['score'])){

$techid = $_POST['technician'];
$jobno = $_POST['job'];
$score = $_POST['score'];
$jobid = $_GET['Id'];
$scoredate = $_POST['score_date'];

mysql_query("INSERT INTO tbl_score_relational (JobId,TechnicianId,Score,Date) VALUES ('$jobid','$techid','$score','$scoredate')")or die(mysql_error());

}

if(isset($_GET['recordid'])){

$recordid = $_GET['recordid'];

mysql_query("DELETE FROM tbl_score_relational WHERE Id = '$recordid'")or die(mysql_error());

}

if(isset($_POST['status'])){
$status = $_POST['status'];
$jobid = $_GET['Id'];
mysql_query("UPDATE tbl_jc SET Status = '$status' WHERE JobId = '$jobid'") or die(mysql_error());
}

if(isset($_POST['delete'])){
$delete = $_POST['delete'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysql_error());
}}
if(isset($_POST['delete_m'])){
$delete = $_POST['delete_m'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysql_error());
}}
if(isset($_POST['delete_c'])){
$delete = $_POST['delete_c'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysql_error());
}}

// Delete transport rows

if(isset($_POST['delete_t'])){
	
	$delete = $_POST['delete_t'];
	
	foreach($delete as $c){
		
		mysql_query("DELETE FROM tbl_travel WHERE Id = '$c'") or die(mysql_error());
	}
}

if(isset($_POST['reference'])){
$ref = $_POST['reference'];
$jobid = $_GET['Id'];
mysql_query("UPDATE tbl_jc SET Reference = '$ref' WHERE JobId = '$jobid'") or die(mysql_error());
} 


// Service Requested

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$row = mysql_fetch_array($query);

if(!empty($_POST['service']) && ($_POST['service'] != $row['JobDescription'])){
	
	$service = addslashes($_POST['service']);	
	$loginid = $_SESSION['kt_login_id'];
	
	$query2 = mysql_query("SELECT * FROM tbl_users WHERE Id = '$loginid'")or die(mysql_error());
	$row2 = mysql_fetch_array($query2);
	
	$user = $row2['Name'];
	$date = date('Y-m-d');

	mysql_query("UPDATE tbl_jc SET JobDescription = '$service', JobDescriptionOperator = '$user', JobDescriptionDate = '$date' WHERE JobId = '$jobid'") or die(mysql_error());
	
}

// Date Received, Date Requested

if(isset($_POST['date1'])){
	
	$date1 = $_POST['date1'];
	$date2 = $_POST['date2'];
	$jobid = $_GET['Id'];
	$service = addslashes($_POST['service']);
	
	mysql_query("UPDATE tbl_jc SET Date1 = '$date1', Date2 = '$date2' WHERE JobId = '$jobid'") or die(mysql_error());

}

// Add Labour Row

if($_POST['labour_row'] >= 1){
		
	$jobid = $_GET['Id'];
	$rows = $_POST['labour_row'];
	
	$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
	$row = mysql_fetch_array($query);
	
	$jobno = $row['JobNo'];
	$companyid = $row['CompanyId'];
	$siteid = $row['SiteId'];
	
	for($i=0;$i<$rows;$i++){
		
		mysql_query("INSERT INTO tbl_jc (JobId,CompanyId,SiteId,JobNo,Labour,Unit,Status) VALUES ('$jobid','$companyid','$siteid','$jobno','1','hours','1')") or die(mysql_error());
		
	}
}

// Add Material Row

if($_POST['material_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['material_row'];
	
	$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
	$row = mysql_fetch_array($query);
	
	$jobno = $row['JobNo'];
	$companyid = $row['CompanyId'];
	$siteid = $row['SiteId'];
	
	for($i=0;$i<$rows;$i++){
		
		mysql_query("INSERT INTO tbl_jc (JobId,CompanyId,SiteId,JobNo,Material,Status) VALUES ('$jobid','$companyid','$siteid','$jobno','1','1')") or die(mysql_error());
		
	}
}

// Add Transport Row

if($_POST['transport_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['transport_row'];
	
	$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
	$row = mysql_fetch_array($query);
	
	$jobno = $row['JobNo'];
	
	for($i=0;$i<$rows;$i++){
		
		mysql_query("INSERT INTO tbl_travel (JobId,JobNo,Description,DistanceKm,KmRate,TotalKm,TravelTime,TravelTimeRate) VALUES ('$jobid','$jobno','','60','4.2','252','1','365')") or die(mysql_error());
		
	}
}

// Add Comment Row

if($_POST['comment_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['comment_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysql_query("INSERT INTO tbl_jc (JobId,Comment) VALUES ('$jobid','1')") or die(mysql_error());
		
	}
}

$jobid = $_GET['Id'];

if(isset($_GET['update']) && isset($_COOKIE['contractor']) && $_COOKIE['contractor'] == 0){
	
	// Update Labour
	
	$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid'";
	$result = mysql_query($query) or die(mysql_error());
	$numrows = mysql_num_rows($result);
	
	$idl = $_POST['id_l'];
	$jobid = $_GET['Id'];
	$labour_l = $_POST['labour'];
	$unit_l = $_POST['unit_l'];
	$qty_l = $_POST['qty_l'];
	$qty_t = $_POST['qty_t'];
	$price_l = $_POST['price_l'];
	
	for($i=0;$i<$numrows;$i++){
		
		$id = $idl[$i];
		$labour = $labour_l[$i];
		$unit = $unit_l[$i];
		$qty = $qty_l[$i];
		$qty_fuel = $qty_t[$i];
		$price = $price_l[$i];
		$total = $qty * $price;
		$total_fuel = $qty_fuel * $price;
		
		if($unit == 'hours'){
			
			mysql_query("UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysql_error());
			
		} elseif($unit == 'km'){
			
			mysql_query("UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty_fuel', Price = '$price', Total1 = '$total_fuel' WHERE Id = '$id'") or die(mysql_error());
		}
	}
	
	// Update Material
	
	$idm = $_POST['id_m'];
	$jobid = $_GET['Id'];
	$material_m = $_POST['material'];
	$unit_m = $_POST['unit_m'];
	$qty_m = $_POST['qty_m'];
	$price_m = $_POST['price_m'];
	
	for($i=0;$i<$numrows;$i++){
		
		$id = $idm[$i];
		$material = $material_m[$i];
		$unit = $unit_m[$i];
		$qty = $qty_m[$i];
		$price = $price_m[$i];
		$total = $qty * $price;
		
		mysql_query("UPDATE tbl_jc SET  Description = '$material', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysql_error());
	}
	
	// Update transport
	
	$query = mysql_query("SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysql_error());
	$numrows = mysql_num_rows($query);
	
	$idt = $_POST['id_t'];
	$jobid = $_GET['Id'];
	$transport_t = $_POST['transport'];
	$unit_t = $_POST['unit_t'];
	$qty_t = $_POST['qty_t'];
	$price_t = $_POST['price_t'];
	$comment_t = $_POST['t_comment'];
	$km_t = $_POST['km'];
	$km_rate_t = $_POST['km-rate'];
	$total_km_t = $_POST['total-km'];
	$travel_time_t = $_POST['travel-time'];
	$travel_time_rate_t = $_POST['travel-time-rate'];
	
	for($i=0;$i<$numrows;$i++){
		
		$id = $idt[$i];
		$transport = $transport_t[$i];
		$unit = $unit_t[$i];
		$qty = $qty_t[$i];
		$price = $price_t[$i];
		$comment = $comment_t[$i];
		$total = $qty* $transport * $price ;
		
		$km = $km_t[$i];
		$km_rate = $km_rate_t[$i];
		$total_km = $total_km_t[$i];
		$travel_time = $travel_time_t[$i];
		$travel_time_rate = $travel_time_rate_t[$i];
		
		$total_pragma = $qty * $travel_time_rate;
		$total = $qty* $transport * $price ;
		
		mysql_query("UPDATE tbl_travel SET  Description = '$transport', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total', TransportComment = '$comment', DistanceKm = '$km', KmRate = '$km_rate',TotalKm = '$total_km', TravelTime = '$travel_time', TravelTimeRate = '$travel_time_rate', TotalPragma = '$total_pragma' WHERE Id = '$id'") or die(mysql_error());
		
	}

if(isset($_POST['id_c'])){
$idc = $_POST['id_c'];
$jobid = $_GET['Id'];
$date_c = $_POST['history-date'];
$name_c = $_POST['comment_name'];
$comment_c = $_POST['comment'];
$feedback_c = $_POST['feedback'];
$fbtech_c = $_POST['tech'];
$fb_date_c = $_POST['fb_date'];


if(isset($_POST['comment1'])){
for($i=0;$i<$numrows;$i++){
$id = $idc[$i];
$date = $date_c[$i];
$name = $name_c[$i];
$comment = $comment_c[$i];
$feedback = $feedback_c[$i];
$feedback_c;
$fbtech = $fbtech_c[$i];
$fb_date = $fb_date_c[$i];

mysql_query("UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date', CommentName = '$name', CommentText = '$comment', HistoryPlatform = '1' WHERE Id = '$id' AND Comment = '1'") or die(mysql_error());

}} else {
mysql_query("UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date_c', CommentName = '$name_c', CommentText = '$comment_c' ,HistoryPlatform = '1' WHERE JobId = '$jobid' AND Comment = '1' ORDER BY Id ASC LIMIT 1") or die(mysql_error());

}}}

// Dealers Feedback

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'")or die(mysql_error());
$row = mysql_fetch_array($query);

if(!empty($_POST['feedback']) && ($_POST['feedback'] != $row['FeedBack'])){
	
	$feedback = $_POST['feedback'];
	$fb_date = $_POST['fb_date'];
	
	$loginid = $_SESSION['kt_login_id'];
	
	$query2 = mysql_query("SELECT * FROM tbl_users WHERE Id = '$loginid'")or die(mysql_error());
	$row2 = mysql_fetch_array($query2);
	
	$fbtech = $row2['Name'];

	mysql_query("UPDATE tbl_jc SET FeedBack = '$feedback', FeedBackTech = '$fbtech', FeedBackDate = '$fb_date' WHERE JobId = '$jobid'") or die(mysql_error());
	
}


$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_l = $row['SUM(Total1)'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_m = $row['SUM(Total1)'];

$query = mysql_query("SELECT CompanyId FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$row = mysql_fetch_array($query);

$companyid = $row['CompanyId'];

if($companyid == 1){
	
	$query = mysql_query("SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 2){
	
	$query = mysql_query("SELECT SUM(TotalPragma) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	
    $subtotal_t = $row['SUM(TotalPragma)'];
	
}

if($companyid == 3){
	
	$query = mysql_query("SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 4){
	
	$query = mysql_query("SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 5){
	
	$query = mysql_query("SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 6){
	
	$query = mysql_query("SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

if($companyid == 10){
	
	$query = mysql_query("SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	
    $subtotal_t = $row['SUM(Total1)'];
	
}

$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

mysql_query("UPDATE tbl_jc SET SubTotal = '$subtotal' WHERE JobId = '$jobid'") or die(mysql_error());

$KTColParam1_Recordset4 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset4 = $_GET["Id"];
}
mysql_select_db($database_inv, $inv);
$query_Recordset4 = sprintf("SELECT tbl_technicians.Name AS Name_1, tbl_actual_history.JobId, tbl_users.Name, tbl_actual_history.Date, tbl_actual_history.Comments, tbl_actual_history.Mobile FROM ((tbl_actual_history LEFT JOIN tbl_users ON tbl_users.Id=tbl_actual_history.TechnicianId) LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_actual_history.TechnicianId) WHERE tbl_actual_history.JobId=%s  ORDER BY tbl_actual_history.Id ASC ", GetSQLValueString($KTColParam1_Recordset4, "int"));
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$jobid = $_GET['Id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT tbl_sites.Name AS Name_1, tbl_sites.Id AS SiteId, tbl_companies.Id AS Id_1, tbl_jc.RequestPreWorkPo, tbl_companies.Name, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Telephone, tbl_sites.Email, tbl_jc.Id, tbl_jc.AreaId, tbl_jc.JobId, tbl_jc.FeedBackTech, tbl_jc.FeedBack, tbl_jc.FeedBackDate, tbl_jc.JobNo, tbl_jc.Date, tbl_jc.Status, tbl_jc.JobDescription, tbl_jc.Progress, tbl_jc.Reference, tbl_jc.InvoiceNo, tbl_jc.QuoteNo FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId = '$jobid' ORDER BY Id ASC LIMIT 1";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);


if(isset($_POST['import'])){

$jobid = $_GET['Id'];
$quoteno = $_POST['quote'];

quote_import($jobid,$quoteno);

}

select_db();

if(isset($_POST['risk'])){

$jobno = $row_Recordset5['JobNo'];

$query = mysql_query("SELECT * FROM tbl_far WHERE JobNo = '$jobno'")or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

mysql_query("UPDATE tbl_far SET RiskType = '$risk', RiskClassification = '$type' WHERE JobNo = '$jobno'")or die(mysql_error());

}

select_db();

$jobid = $_GET['Id'];

$sql = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$hint = mysql_fetch_array($sql);

$cfb = $_POST['cfb'];
$jobid = $_GET['Id'];

if(!empty($_POST['costing-hint'])){
	
	$costinghint = $_POST['costing-hint'];
	
} else {
	
	$costinghint = $hint['CostingHint'];
}

// UPDATE CUSTOMER STATUS REPORTS

if(isset($_POST['Submit3'])){
	
	$progress = $_POST['progress'];
	
	mysql_query("UPDATE tbl_jc SET CustomerFeedBack = '$cfb', CostingHint = '$costinghint', Progress = '$progress' WHERE JobId = '$jobid'")or die(mysql_error());
}

$KTColParam1_Recordset3 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset3 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_jc.CompanyId, tbl_jc.JobId, tbl_companies.* FROM (tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId=%s ORDER BY Id ASC LIMIT 1", $KTColParam1_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$colname_Recordset6 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset6 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset6 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s ORDER BY Id ASC", $colname_Recordset6);
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

mysql_select_db($database_seavest, $seavest);
$query_Recordset7 = "SELECT * FROM tbl_rates";
$Recordset7 = mysql_query($query_Recordset7, $seavest) or die(mysql_error());
$row_Recordset7 = mysql_fetch_assoc($Recordset7);
$totalRows_Recordset7 = mysql_num_rows($Recordset7);

$KTColParam1_Recordset8 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset8 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset8 = sprintf("SELECT tbl_jc.CompanyId, tbl_jc.JobId, tbl_jc.Price, tbl_jc.Transport FROM tbl_jc WHERE tbl_jc.JobId=%s  AND tbl_jc.Transport=1 ", $KTColParam1_Recordset8);
$Recordset8 = mysql_query($query_Recordset8, $seavest) or die(mysql_error());
$row_Recordset8 = mysql_fetch_assoc($Recordset8);
$totalRows_Recordset8 = mysql_num_rows($Recordset8);

$areaid = $row_Recordset5['AreaId'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset100 = "SELECT * FROM tbl_technicians";
$Recordset100 = mysql_query($query_Recordset100, $seavest) or die(mysql_error());
$row_Recordset100 = mysql_fetch_assoc($Recordset100);
$totalRows_Recordset100 = mysql_num_rows($Recordset100);

$companyid = $row_Recordset5['Id_1'];
mysql_select_db($database_seavest, $seavest);
$query_Recordset101 = "SELECT tbl_rates.Name AS Name_1, tbl_companies.Name, tbl_rates.Rate, tbl_rates.CompanyId FROM (tbl_companies LEFT JOIN tbl_rates ON tbl_rates.CompanyId=tbl_companies.Id) WHERE tbl_rates.CompanyId='$companyid' ";
$Recordset101 = mysql_query($query_Recordset101, $seavest) or die(mysql_error());
$row_Recordset101 = mysql_fetch_assoc($Recordset101);
$totalRows_Recordset101 = mysql_num_rows($Recordset101);

mysql_select_db($database_seavest, $seavest);
$query_Recordset9 = "SELECT * FROM tbl_status WHERE Id <= 5";
$Recordset9 = mysql_query($query_Recordset9, $seavest) or die(mysql_error());
$row_Recordset9 = mysql_fetch_assoc($Recordset9);
$totalRows_Recordset9 = mysql_num_rows($Recordset9);

$query = mysqli_query($con, "SELECT JobNo FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$jobno = $row['JobNo'];

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
	WHERE tbl_far.JobNo = '$jobno'";

mysql_select_db($database_seavest, $seavest);
$query_Recordset41 = $query_41;
$Recordset41 = mysql_query($query_Recordset41, $seavest) or die(mysql_error());
$row_Recordset41 = mysql_fetch_assoc($Recordset41);
$totalRows_Recordset41 = mysql_num_rows($Recordset41);

mysql_select_db($database_seavest, $seavest);
$query_Recordset42 = "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC";
$Recordset42 = mysql_query($query_Recordset42, $seavest) or die(mysql_error());
$row_Recordset42 = mysql_fetch_assoc($Recordset42);
$totalRows_Recordset42 = mysql_num_rows($Recordset42);

$jobno = $row_Recordset5['JobNo'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset43 = "SELECT * FROM tbl_far WHERE JobNo = '$jobno'";
$Recordset43 = mysql_query($query_Recordset43, $seavest) or die(mysql_error());
$row_Recordset43 = mysql_fetch_assoc($Recordset43);
$totalRows_Recordset43 = mysql_num_rows($Recordset43);

$KTColParam1_Recordset44 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset44 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset44 = sprintf("SELECT tbl_score_relational.Id, tbl_score_relational.JobId, tbl_technicians.Name, tbl_score_relational.Score, tbl_jc.JobNo FROM ((tbl_score_relational LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_score_relational.TechnicianId) LEFT JOIN tbl_jc ON tbl_jc.JobId=tbl_score_relational.JobId) WHERE tbl_score_relational.JobId=%s GROUP BY tbl_technicians.Id", $KTColParam1_Recordset44);
$Recordset44 = mysql_query($query_Recordset44, $seavest) or die(mysql_error());
$row_Recordset44 = mysql_fetch_assoc($Recordset44);
$totalRows_Recordset44 = mysql_num_rows($Recordset44);

mysql_select_db($database_seavest, $seavest);
$query_Recordset45 = "SELECT * FROM tbl_technicians ORDER BY Name ASC";
$Recordset45 = mysql_query($query_Recordset45, $seavest) or die(mysql_error());
$row_Recordset45 = mysql_fetch_assoc($Recordset45);
$totalRows_Recordset45 = mysql_num_rows($Recordset45);

mysql_select_db($database_inv, $inv);
$query_rs_companies = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$rs_companies = mysql_query($query_rs_companies, $inv) or die(mysql_error());
$row_rs_companies = mysql_fetch_assoc($rs_companies);
$totalRows_rs_companies = mysql_num_rows($rs_companies);

mysql_select_db($database_inv, $inv);
$query_rs_sites = "SELECT * FROM tbl_sites ORDER BY Name ASC";
$rs_sites = mysql_query($query_rs_sites, $inv) or die(mysql_error());
$row_rs_sites = mysql_fetch_assoc($rs_sites);
$totalRows_rs_sites = mysql_num_rows($rs_sites);

$KTColParam1_job_history = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_job_history = $_GET["Id"];
}
mysql_select_db($database_seavest, $seavest);
$query_job_history = sprintf("SELECT tbl_history_relation.PhotoId, tbl_history_photos.Photo, tbl_history_relation.JobId FROM (tbl_history_relation LEFT JOIN tbl_history_photos ON tbl_history_photos.Id=tbl_history_relation.PhotoId) WHERE tbl_history_relation.JobId=%s ", GetSQLValueString($KTColParam1_job_history, "int"));
$job_history = mysql_query($query_job_history, $seavest) or die(mysql_error());
$row_job_history = mysql_fetch_assoc($job_history);
$totalRows_job_history = mysql_num_rows($job_history);

$colname_progress = "-1";
if (isset($_GET['Id'])) {
  $colname_progress = $_GET['Id'];
}
mysql_select_db($database_inv, $inv);
$query_progress = sprintf("SELECT Progress FROM tbl_jc WHERE JobId = %s", GetSQLValueString($colname_progress, "int"));
$progress = mysql_query($query_progress, $inv) or die(mysql_error());
$row_progress = mysql_fetch_assoc($progress);
$totalRows_progress = mysql_num_rows($progress);

$companyid = $row_Recordset5['Id_1'];

mysql_select_db($database_seavest, $seavest);
$query_rs_transport_rates = "SELECT * FROM tbl_rates WHERE CompanyId = '$companyid' AND Description LIKE '%Travel%' ORDER BY Name ASC";
$rs_transport_rates = mysql_query($query_rs_transport_rates, $seavest) or die(mysql_error());
$row_rs_transport_rates = mysql_fetch_assoc($rs_transport_rates);
$totalRows_rs_transport_rates = mysql_num_rows($rs_transport_rates);

mysql_select_db($database_inv, $inv);
$query_rs_components = "SELECT * FROM tbl_pragma_components ORDER BY `Description` ASC";
$rs_components = mysql_query($query_rs_components, $inv) or die(mysql_error());
$row_rs_components = mysql_fetch_assoc($rs_components);
$totalRows_rs_components = mysql_num_rows($rs_components);

mysql_select_db($database_inv, $inv);
$query_rs_failure = "SELECT CompDescription, Code2, Description2 FROM tbl_pragma_failures ORDER BY Description2 ASC";
$rs_failure = mysql_query($query_rs_failure, $inv) or die(mysql_error());
$row_rs_failure = mysql_fetch_assoc($rs_failure);
$totalRows_rs_failure = mysql_num_rows($rs_failure);

mysql_select_db($database_inv, $inv);
$query_rs_root_cause = "SELECT * FROM tbl_pragma_root_cause ORDER BY `Description` ASC";
$rs_root_cause = mysql_query($query_rs_root_cause, $inv) or die(mysql_error());
$row_rs_root_cause = mysql_fetch_assoc($rs_root_cause);
$totalRows_rs_root_cause = mysql_num_rows($rs_root_cause);

mysql_select_db($database_inv, $inv);
$query_rs_repair = "SELECT * FROM tbl_pragma_repair ORDER BY `Description` ASC";
$rs_repair = mysql_query($query_rs_repair, $inv) or die(mysql_error());
$row_rs_repair = mysql_fetch_assoc($rs_repair);
$totalRows_rs_repair = mysql_num_rows($rs_repair);

$colname_rs_pragma = "-1";
if (isset($_GET['Id'])) {
  $colname_rs_pragma = $_GET['Id'];
}
mysql_select_db($database_inv, $inv);
$query_rs_pragma = sprintf("SELECT * FROM tbl_pragma WHERE JobId = %s", GetSQLValueString($colname_rs_pragma, "int"));
$rs_pragma = mysql_query($query_rs_pragma, $inv) or die(mysql_error());
$row_rs_pragma = mysql_fetch_assoc($rs_pragma);
$totalRows_rs_pragma = mysql_num_rows($rs_pragma);

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers ORDER BY Name ASC")or die(mysqli_error($con));

// Make a custom transaction instance
$customTransaction = new tNG_custom($conn_seavest);
$tNGs->addTransaction($customTransaction);
// Register triggers
$customTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "complete");
$customTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
// Add columns
$customTransaction->addColumn("feedback", "STRING_TYPE", "POST", "feedback");
// End of custom transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);

if(isset($_POST['complete'])){
if($_POST['complete'] == 1){

$jobid = $_GET['Id'];

mysql_query("UPDATE tbl_jc SET Status = '2', Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());

select_db();

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'")or die(mysql_error());
$row = mysql_fetch_array($query);

$jobno = $row['JobNo'];
$date = date('Y-m-d');
$technician = $row['FeedBackTech'];

$jobno = $row_Recordset5['JobNo'];
$site = $row_Recordset5['SiteId'];
$required = $row_Recordset5['JobDescription'];

mysql_query("INSERT INTO tbl_feedback (Reference,Status) VALUES ('$jobno','2')")or die(mysql_error());

$query40 = mysql_query("SELECT * FROM tbl_jc_mobile WHERE SiteId = '$site'")or die(mysql_error());
$numrows40 = mysql_num_rows($query40);
while($row40 = mysql_fetch_array($query40)){
	
	if($numrows40 >= 1){
		
		if(empty($_POST['service'])){
			
			$jobid = $_GET['Id'];
			header('Location: jc_calc.php?Id='. $jobid .'&service');

		} else {

//$cell = $row40['Mobile'];
//
//$to  = 'sms@messaging.clickatell.com'; 
//$subject = 'Seavest';
//$from = "control@seavest.co.za";
//
//$message = '
//user:seavest
//password:abc123
//api_id:3232946
//to:'. $cell .'
//reply: control@seavest.co.za
//concat: 3
//text:Your request: "'. $required .'" has been received by Seavest. Use ref. No. '. $jobno .' and log onto www.seavest.co.za/progress for updates.';
//
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//
//$headers .= 'FROM: '. $from . "\r\n";
//
//mail($to, $subject, $message, $headers);
}}

header('Location: jc_complete.php?Id='.$jobno);

}
} 

// Send to costing

elseif($_POST['complete'] == "2" || $_POST['complete'] == "request-pre-po"){
	
	$jobid = $_GET['Id'];
	
	unset($_SESSION['services']);
	unset($_SESSION['feedback']);
	unset($_SESSION['reference']);
	
	if(empty($_POST['service']) || empty($_POST['feedback']) || empty($_POST['reference'])){
		
		if(empty($_POST['service'])){ 
		
		$_SESSION['services'] = "&service";
		
		}
		
		if(empty($_POST['feedback'])){
			
			$_SESSION['feedback'] = "&feedback";
		}
		
		if(empty($_POST['reference'])){
			
			$_SESSION['reference'] = "&reference";
		}
				
		header('Location: jc_calc.php?Id='. $jobid . $_SESSION['services'] . $_SESSION['feedback'] . $_SESSION['reference']);
		
	} else {
				
		if($row_Recordset5['Id_1'] == 6 && $row_Recordset5['RequestPreWorkPo'] == 0){
			
			if($_POST['complete'] == "request-pre-po"){
				
				mysqli_query($con, "UPDATE tbl_jc SET RequestPreWorkPo = '1', Days = '$today' WHERE JobId = '$jobid'")or die(mysqli_error($con));
			}
			
			// Send to Awaiting Order No.
			$query = mysql_query("UPDATE tbl_jc SET Status = '18', Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());
			
		} else {
			
			// Send ro Costing
			$query = mysql_query("UPDATE tbl_jc SET Status = '3', Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());
			
		}
		
		$jobno = $row_Recordset5['JobNo'];
		
		mysql_query("INSERT INTO tbl_feedback (Reference,Status) VALUES ('$jobno','3')")or die(mysql_error());
		
		$query3 = mysql_query("SELECT * FROM tbl_sms WHERE Id = '2'")or die(mysql_error());
		$row3 = mysql_fetch_array($query3);
		
		$qued = $row3['SMS'];
		
		$query4 = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
		$row4 = mysql_fetch_array($query4);
		
		$site = $row4['SiteId'];
		$jobnumber = $row4['JobNo'];
		$required = $row4['JobDescription'];
		
		$query5 = mysql_query("SELECT * FROM tbl_jc_mobile WHERE SiteId = '$site'")or die(mysql_error());
		$numrows5 = mysql_num_rows($query5);
		while($row5 = mysql_fetch_array($query5)){
			
			if($numrows5 >= 1){
				
//				$cell = $row5['Mobile'];
//				$to  = 'sms@messaging.clickatell.com'; 
//				$subject = 'Seavest';
//				$from = "control@seavest.co.za";
//				
//				$message = '
//				user:seavest
//				password:abc123
//				api_id:3232946
//				to:'. $cell .'
//				reply: control@seavest.co.za
//				concat: 3
//				text:Ref. No. '. $jobnumber .': '. $required .' has successfully been completed. Thank you for your support.';
//				
//				$headers  = 'MIME-Version: 1.0' . "\r\n";
//				$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//				$headers .= 'FROM: '. $from . "\r\n";
//				
//				mail($to, $subject, $message, $headers);
			}
		}
		
		$jobid = $_GET['Id'];
		
		mysql_query("DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'")or die(mysql_error());
		
		header('Location: jc_select.php?Id='. $_GET['Id']);
	}

} elseif($_POST['complete'] == "3"){
	
	$query = mysql_query("UPDATE tbl_jc SET Status = '7', Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());
	
	header('Location: jc_select.php');
	
} elseif($_POST['complete'] == "5"){
	
	$query = mysql_query("UPDATE tbl_jc SET Status = '7', Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());
	
	header('Location: jc_select.php');
}
}

$invoiceno = $row_Recordset5['InvoiceNo'];

if(($invoiceno == 0) && ($row_Recordset5['Status'] != 1)){

$jobid = $_GET['Id'];

$invoiceno = invno($con);
$inv_date = date('Y-m-d');
mysql_query("UPDATE tbl_jc SET InvoiceNo = '$invoiceno', NewInvoiceDate = '$inv_date' WHERE JobId = '$jobid'") or die(mysql_error());
}


if($_POST['complete'] == 6) {
?>
<script>
window.open("far-print.php?Id=<?php echo $row_Recordset5['JobNo']; ?>");
</script>
<?php } ?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script type="text/javascript" src="includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_rs_sites = new WDG_JsRecordset("rs_sites");
echo $jsObject_rs_sites->getOutput();
//end JSRecordset
?>
<script type="text/javascript" src="highslide/highslide-with-html.js"></script>
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
<script type="text/javascript" src="Calendar.js"></script>
<script type="text/javascript">
function validateForm()
{
var x=document.forms["form1"]["tech"].value;
if (x==null || x=="")
  {
  alert("Please select a technician under Actual History");
  return false;
  }
}
</script>
<link href="SpryAssets/SpryCollapsiblePanel-jc.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<style type="text/css">
<!--
.big1 {    font-size:14px;
}

.menuHeader{
  font-weight:bold;
  font-size: 8pt;
}-->
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
if(!isset($_COOKIE['status'])){
include('menu.php'); 
} else {
include('status_menu.php'); 
}
?>
    </td>
    <td valign="top"><table width="761" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="200" colspan="4" align="center"><img src="images/banner.jpg" alt="" width="823" height="151"></td>
      </tr>
    </table>
      </form>
      <div style="margin-left:30px">
        <table width="759" border="0" cellpadding="0" cellspacing="0" class="td-header">
          <tr>
            <td><form name="form2" method="post" action="jc_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
              &nbsp;Labour
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
              <input name="Submit2" type="submit" class="btn-go-search-2" value="">
            </form></td>
            <td width="25"><form name="form4" method="post" action="jc-recycle.php?Id=<?php echo $_GET['Id']; ?>">
              <input name="Submit4" type="submit" class="camcel" value="">
            </form></td>
            <td align="right" valign="middle"><form name="form3" method="post" action="jc_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
              Status
              <select name="status" class="tarea-white" id="status">
                <option value="">Select one...</option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset9['Id']?>"><?php echo $row_Recordset9['Status']?></option>
                <?php
} while ($row_Recordset9 = mysql_fetch_assoc($Recordset9));
  $rows = mysql_num_rows($Recordset9);
  if($rows > 0) {
      mysql_data_seek($Recordset9, 0);
	  $row_Recordset9 = mysql_fetch_assoc($Recordset9);
  }
?>
                </select>
              <input name="Submit" type="submit" class="btn-go-search-2" value="">
              &nbsp;
            </form></td>
          </tr>
        </table>
      </div>
      <form action="jc_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $jobid; ?>&update" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="return validateForm();" style="margin-left:27px">
        <table width="759">
          <tr>
            <td colspan="3" valign="top" class="combo"><div style="border:solid 1px #A6CAF0; width:759px; background-color:#EEE"">
              <table width="100%" border="0" cellpadding="3" cellspacing="0">
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td valign="top"><span class="combo"><span style="padding-right:10px"><br>
                        <table border="0" cellpadding="2" cellspacing="3" class="combo">
                          <tr>
                            <td><span class="combo_bold" style="padding-right:10px"><?php echo $row_Recordset5['Name']; ?></span></td>
                            <td width="65">&nbsp;</td>
                            <td><?php echo $row_Recordset5['FirstName']; ?>&nbsp; <?php echo $row_Recordset5['LastName']; ?></td>
                          </tr>
                          <tr>
                            <td><span style="padding-right:10px"><?php echo $row_Recordset5['Name_1']; ?></span></td>
                            <td width="65">&nbsp;</td>
                            <td><?php echo $row_Recordset5['Telephone']; ?></td>
                          </tr>
                          <tr>
                            <td><span style="padding-right:10px"><?php echo $row_Recordset5['Address']; ?></span></td>
                            <td width="65">&nbsp;</td>
                            <td><a href="mailto:<?php echo $row_Recordset5['Email']; ?>"><?php echo $row_Recordset5['Email']; ?></a></td>
                          </tr>
                        </table>
                        </span></span> <span class="combo"><span style="padding-right:10px"><br>
                          </span><br>
                        </span></td>
                      <td align="right" valign="top" class="combo"><table border="0" align="right" cellpadding="2" cellspacing="3" class="combo">
                        <tr>
                          <td class="combo_bold"> Jobcard No:&nbsp;</td>
                          <td><input name="jobno" type="text" class="tarea" id="jobno" value="<?php echo $row_Recordset5['JobNo']; ?>" size="30"></td>
                        </tr>
                        <?php if(($row_Recordset5['Status'] == 3) || ($row_Recordset5['Status'] == 4)){ ?>
                        <tr>
                          <td><strong>Quote No</strong>: </td>
                          <?php
						  if($row_Recordset5['QuoteNo'] > 0){
							  
							  $value = $row_Recordset5['QuoteNo'];
							  
						  } else {
							  
							  $value = $_POST['quote'];
						  }
						  ?>
                          <td><input name="quote" type="text" class="tarea" id="quote" value="<?php echo $value; ?>" size="18">
                            <input name="import" type="submit" class="tarea2" id="import" value="Import"></td>
                        </tr>
                        <?php } ?>
                        <?php if($row_Recordset5['Status'] == 3){ ?>
                        <tr>
                          <td><span class="combo_bold">Invoice No:</span></td>
                          <td><input name="textfield" type="text" class="tarea" value="<?php echo $row_Recordset5['InvoiceNo']; ?>" size="30"></td>
                        </tr>
                        <?php } ?>
                        <tr>
                          <td><div style="padding-right:10px"> <span class="combo_bold">Reference:</span></div></td>
                          <td><span style="padding-right:10px">
                            <select name="reference" class="tarea" id="reference">
                              <option value="">Select an Engineer</option>
                              <?php while($row_engineers = mysqli_fetch_array($query_engineers)){ ?>
								  <option value="<?php echo trim($row_engineers['Name']); ?>" <?php if($row_engineers['Name'] == $row_Recordset5['Reference']){ echo 'selected="selected"'; } ?>><?php echo $row_engineers['Name']; ?>
                              <?php } ?>
                          </select>
                          <?php if(isset($_GET['reference'])){ ?>
                            <span class="form_validation_field_error_error_message">Required Field</span>
					      <?php } ?>
                          </span></td>
                        </tr>
                        <tr>
                          <td nowrap><strong>Job Card PDF</strong></td>
                          <td><input name="pdf" type="file" class="tarea" id="pdf" size="12"></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td width="50%" class="combo_bold">&nbsp;</td>
                      <td width="50%" align="right" class="combo_bold"></td>
                    </tr>
                    <tr>
                      <td colspan="2"><span class="combo_bold">&nbsp; Received Date</span>
                        <input name="date1" class="tarea" id="date1" value="<?php echo $row_Recordset6['Date1']; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format.' '.$KT_screen_time_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="combo_bold">Requested Completion:</span>&nbsp;
                        <input name="date2" class="tarea" id="date2" value="<?php echo $row_Recordset6['Date2']; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format.' '.$KT_screen_time_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                        &nbsp;
                        <?php
							 schedule($jobid); ?></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                </tr>
              </table>
            </div></td>
          </tr>
          <?php if($row_Recordset5['Id_1'] == 2){ ?>
          <tr>
            <td class="combo">
            <table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header">&nbsp; Invoice Details</td>
              </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td class="combo"><div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
              <table border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td>
                    <select name="component" class="combo" id="component" style="width:500px">
                      <option value="" class="menuHeader" <?php if (!(strcmp("", $row_rs_pragma['Component']))) {echo "selected=\"selected\"";} ?>>Component</option>
                      <?php
do {  
?>
<option value="<?php echo $row_rs_components['Code']?>"<?php if (!(strcmp($row_rs_components['Code'], $row_rs_pragma['Component']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_components['Description']?></option>
                      <?php
} while ($row_rs_components = mysql_fetch_assoc($rs_components));
  $rows = mysql_num_rows($rs_components);
  if($rows > 0) {
      mysql_data_seek($rs_components, 0);
	  $row_rs_components = mysql_fetch_assoc($rs_components);
  }
?>
                      </select>
                    <select name="failure-type" class="combo" id="failure-type" style="width:227px">
                      <option value="" selected style="font-weight:bold" <?php if (!(strcmp("", $row_rs_pragma['FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Failure Type</option>
                      <option value="GEN" <?php if (!(strcmp("GEN", $row_rs_pragma['FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Generic Type</option>
                      <option value="VER" <?php if (!(strcmp("VER", $row_rs_pragma['FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Verification</option>
                    </select></td>
                  </tr>
                <tr>
                  <td><select name="failure" class="combo" id="failure" style="width:730px">
                    <option value="">Failure</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_failure['Code2']; ?>" <?php if (!(strcmp($row_rs_failure['Code2'], $row_rs_pragma['Failure']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_failure['Description2'] .' - '. $row_rs_failure['CompDescription']; ?></option>
                    <?php
} while ($row_rs_failure = mysql_fetch_assoc($rs_failure));
  $rows = mysql_num_rows($rs_failure);
  if($rows > 0) {
      mysql_data_seek($rs_failure, 0);
	  $row_rs_failure = mysql_fetch_assoc($rs_failure);
  }
?>
                  </select></td>
                  </tr>
                <tr>
                  <td>
                    <select name="root-cause" class="combo" id="root-cause" style="width:248px">
                      <option value="" <?php if (!(strcmp("", $row_rs_pragma['RootCause']))) {echo "selected=\"selected\"";} ?>>Root Cause</option>
                      <?php
do {  
?>
<option value="<?php echo $row_rs_root_cause['Code']?>"<?php if (!(strcmp($row_rs_root_cause['Code'], $row_rs_pragma['RootCause']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_root_cause['Description']?></option>
                      <?php
} while ($row_rs_root_cause = mysql_fetch_assoc($rs_root_cause));
  $rows = mysql_num_rows($rs_root_cause);
  if($rows > 0) {
      mysql_data_seek($rs_root_cause, 0);
	  $row_rs_root_cause = mysql_fetch_assoc($rs_root_cause);
  }
?>
                      </select>
                    <select name="repair" id="repair" class="combo" style="width:248px">
                      <option value="" <?php if (!(strcmp("", $row_rs_pragma['Repair']))) {echo "selected=\"selected\"";} ?>>Repair</option>
                      <?php
do {  
?>
<option value="<?php echo $row_rs_repair['Code']?>"<?php if (!(strcmp($row_rs_repair['Code'], $row_rs_pragma['Repair']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_repair['Description']?></option>
                      <?php
} while ($row_rs_repair = mysql_fetch_assoc($rs_repair));
  $rows = mysql_num_rows($rs_repair);
  if($rows > 0) {
      mysql_data_seek($rs_repair, 0);
	  $row_rs_repair = mysql_fetch_assoc($rs_repair);
  }
?>
                    </select>                    
                    <input name="ptw-number" type="text" class="combo" id="ptw-number" style="width:227px" onFocus="if(this.value=='PTW Number'){this.value=''}" onBlur="if(this.value==''){this.value='PTW Number'}" value="<?php if(!empty($row_rs_pragma['PTWNumber'])){ echo $row_rs_pragma['PTWNumber']; } else { ?>PTW Number<?php } ?>"></td>
                  </tr>
                <tr>
                  <td><table border="0" cellpadding="0" cellspacing="0" class="combo">
                    <tr>
                      <td width="152">Follow up work required</td>
                      <td width="163"><table border="0" cellpadding="2" cellspacing="3" class="combo">
                        <tr>
                          <td width="20"><input type="radio" <?php if($row_rs_pragma['FollowUpWork'] == "Yes") {echo "checked=\"checked\"";} ?> name="followup" id="followup" value="Yes"></td>
                          <td width="21">Yes</td>
                          <td width="20"><input name="followup" <?php if ($row_rs_pragma['FollowUpWork'] == "No") {echo "checked=\"checked\"";} ?> type="radio" id="followup" value="No"></td>
                          <td width="37">No</td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
            </div></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="3" class="combo">
            <table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header">&nbsp; Service Requested</td>
              </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
            <div id="history-log">
        <span style="color:#306294; background-image:url(images/icons/history-bg.jpg); height:19px; border:solid 1px #85afd7; font-size:8px"><b>
        
		<?php 
		
		echo $row_Recordset6['JobDescriptionOperator'];
		
		?></b>&nbsp;<span style="font-size:8px; color:#4383C2"><?php echo $row_Recordset6['JobDescriptionDate']; ?></span>
        </span>
        &nbsp;<?php echo nl2br($row_Recordset6['JobDescription']); ?>
                  </div>
            <textarea name="service" rows="4" class="tarea-tech" id="service"><?php echo stripslashes($row_Recordset6['JobDescription']); ?></textarea>
            </div>
              <?php if(isset($_GET['service'])){ ?>
              <span class="form_validation_field_error_error_message">Required Field</span>
              <?php } ?></td>
          </tr>
          <tr>
            <td colspan="3" class="td-header"><table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><table width="195" border="0" cellpadding="0" cellspacing="0" class="td-header">
                  <tr>
                    <td width="87"><a title="<?php echo $row_Recordset6['CostingHint']; ?>">&nbsp;Costing Hint</a></td>
                    <td width="108"><a href="jc_calc.php?Id=<?php echo $_GET['Id']; ?>&costing"><img src="images/icons/btn-edit-small.png" alt="" width="15" height="15" border="0"></a></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
              <textarea name="costing-hint" rows="4" class="tarea-tech" id="costing-hint" ><?php 
			  if(isset($_GET['costing'])){
			  echo $row_Recordset6['CostingHint']; 
			  }
			  ?>
      </textarea>
            </div></td>
          </tr>
          <tr>
            <td bgcolor="#A6CAF0" class="td-header" style="background-color:<?php echo $row_Recordset41['Colour']; ?>">&nbsp;Proactive  Risk  Assessement</td>
          </tr>
          <tr>
            <td class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
              <table border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td width="108">&nbsp;Risk Classification</td>
                  <td width="150"><?php echo $row_Recordset41['Risk']; ?>
                  </td>
                  <td width="183">&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; High risk classification</td>
                  <td width="152"><?php echo $row_Recordset41['Risk2']; ?></td>
                </tr>
              </table>
            </div></td>
          </tr>
          <tr>
            <td bgcolor="#A6CAF0" class="td-header">&nbsp;Technician Score </td>
          </tr>
          <tr>
            <td class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE""> <?php echo $norecord; ?>
              <table border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td class="combo"><input name="job" type="hidden" id="job" value="<?php echo $_GET['Id']; ?>">
                    Technician</td>
                  <td><select name="technician" class="tarea" id="technician">
                    <option value=" ">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset45['Id']?>"><?php echo $row_Recordset45['Name']?></option>
                    <?php
} while ($row_Recordset45 = mysql_fetch_assoc($Recordset45));
  $rows = mysql_num_rows($Recordset45);
  if($rows > 0) {
      mysql_data_seek($Recordset45, 0);
	  $row_Recordset45 = mysql_fetch_assoc($Recordset45);
  }
?>
                  </select></td>
                  <td class="combo">Score</td>
                  <td><input name="score_date" type="hidden" value="<?php echo date('Y m d'); ?>"></td>
                  <td><input name="Score" type="submit" class="btn-go-small" id="Score" value=""></td>
                </tr>
              </table>
              <?php 
			if($totalRows_Recordset44 >= 1){
			do { ?>
<table width="200" border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td><?php echo $row_Recordset44['Name']; ?></td>
                  <td width="20"><?php echo $row_Recordset44['Score']; ?></td>
                  <td width="20"><a href="<?php echo $_SERVER["REQUEST_URI"] .'&recordid='. $row_Recordset44['Id']; ?>"> <img src="images/no.jpg" alt="" width="15" height="15" border="0" /></a></td>
                </tr>
              </table>
<input name="score" type="text" class="tarea" id="score" size="20">
              <?php } while ($row_Recordset44 = mysql_fetch_assoc($Recordset44)); } ?>
            </div></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header">&nbsp;Customer Status Report</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
              <div style="padding-bottom:5px;">
                <table border="0" cellpadding="2" cellspacing="3" class="combo2">
                  <tr>
                    <td><select name="progress" class="combo" id="progress">
                      <option>Select one...</option>
                      <?php
				$i = 0;
				
				for($i=1;$i<=100;$i++){
				?>
                      <option value="<?php echo $i; ?>" <?php if($i == $row_progress['Progress']){ ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
                      <?php } ?>
                    </select></td>
                    <td>%</td>
                  </tr>
                </table>
              </div>
              <?php
$jobid = $_GET['Id'];
$queryfb = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$rowfb = mysql_fetch_array($queryfb);
?>
              <textarea name="cfb" rows="4" class="tarea-tech" id="cfb"><?php echo $rowfb['CustomerFeedBack']; ?></textarea>
            </div></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header"><table border="0" cellpadding="0" cellspacing="0" class="td-header">
                    <tr>
                      <td><strong>&nbsp;Actual History</strong> <span style="font-weight:normal"><?php echo $row_Recordset4['HistoryLog']; ?></span></td>
                      <td width="40" align="right">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
if($numrows >= 2){
while($row = mysql_fetch_array($result)){

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
              <div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE""> <br>
                <div style="padding-bottom:5px;">
                  <select name="day[]" class="tarea" id="day[]">
                    <option value="1" selected <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\"";} ?>>1</option>
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
                  <select name="month[]" class="tarea" id="month[]">
                    <option value="January" <?php if (!(strcmp("January", $month))) {echo "selected=\"selected\"";} ?>>January</option>
                    <option value="February" <?php if (!(strcmp("February", $month))) {echo "selected=\"selected\"";} ?>>February</option>
                    <option value="March" <?php if (!(strcmp("March", $month))) {echo "selected=\"selected\"";} ?>>March</option>
                    <option value="April" <?php if (!(strcmp("April", $month))) {echo "selected=\"selected\"";} ?>>April</option>
                    <option value="May" <?php if (!(strcmp("May", $month))) {echo "selected=\"selected\"";} ?>>May</option>
                    <option value="June" <?php if (!(strcmp("June", $month))) {echo "selected=\"selected\"";} ?>>June</option>
                    <option value="July" <?php if (!(strcmp("July", $month))) {echo "selected=\"selected\"";} ?>>July</option>
                    <option value="August" <?php if (!(strcmp("August", $month))) {echo "selected=\"selected\"";} ?>>August</option>
                    <option value="September" <?php if (!(strcmp("September", $month))) {echo "selected=\"selected\"";} ?>>September</option>
                    <option value="October" <?php if (!(strcmp("October", $month))) {echo "selected=\"selected\"";} ?>>October</option>
                    <option value="November" <?php if (!(strcmp("November", $month))) {echo "selected=\"selected\"";} ?>>November</option>
                    <option value="December" <?php if (!(strcmp("December", $month))) {echo "selected=\"selected\"";} ?>>December</option>
                  </select>
                  <select name="year[]" class="tarea" id="year[]">
                    <option value="2009" selected <?php if (!(strcmp(2009, $year))) {echo "selected=\"selected\"";} ?>>2009</option>
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
                  <select name="hour[]" class="tarea" id="hour[]">
                    <option value="1" selected <?php if (!(strcmp(1, $hour))) {echo "selected=\"selected\"";} ?>>1</option>
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
                  <select name="minute[]" class="tarea" id="minute[]">
                    <option value="00" selected <?php if (!(strcmp(00, $minute))) {echo "selected=\"selected\"";} ?>>00</option>
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
                  <input name="history-date" type="text" class="tarea" id="textfield">
                  <input name="comment_name[]" type="text" class="tarea" id="comment_name[]" value="<?php echo $row['CommentName']; ?>" size="87">
                  <input name="delete_c[]" type="checkbox" id="delete_c[]" value="<?php echo $row['Id']; ?>">
                </div>
                <br>
                <input name="comment1" type="hidden" id="comment1" value="<?php echo $row['Id']; ?>">
                <input name="id_c[]" type="hidden" id="id_c[]" value="<?php echo $row['Id']; ?>">
                <textarea name="comment[]" rows="4" class="tarea-tech" id="textarea" type="text" value="<?php echo $row['CommentText']; ?>"><?php echo $row['CommentText']; ?></textarea>
                <br>
                <br>
              </div>
              <?php }} else { ?>
              <div>
                <div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
                  <div style="padding-bottom:5px;">
                    <div id="CollapsiblePanel1" class="CollapsiblePanel">
                      <div class="CollapsiblePanelTab" tabindex="0">&nbsp;Allocate Technicians</div>
                    <div class="CollapsiblePanelContent">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <?php
do { // horizontal looper version 3

$id = $row_Recordset100['Id'];

$query = mysql_query("SELECT * FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId = '$id'")or die(mysql_error());
$row = mysql_fetch_array($query);

?>
                          <td><table border="0" cellspacing="3" cellpadding="2">
                            <tr>
                              <td><input name="tech[]" type="checkbox" id="tech[]" value="<?php echo $row_Recordset100['Id']; ?>"<?php if($row_Recordset100['Id'] == $row['TechnicianId']) { ?> checked="checked"<?php } ?>></td>
                              <td class="combo"><?php echo $row_Recordset100['Name']; ?></td>
                              </tr>
                          </table></td>
                          <?php
    $row_Recordset100 = mysql_fetch_assoc($Recordset100);
    if (!isset($nested_Recordset100)) {
      $nested_Recordset100= 1;
    }
    if (isset($row_Recordset100) && is_array($row_Recordset100) && $nested_Recordset100++ % 5==0) {
      echo "</tr><tr>";
    }
  } while ($row_Recordset100); //end horizontal looper version 3
?>
                      </tr>
                    </table>                    </div>
                  </div>
                  </div>
                  
                  <div id="history-log">
      <?php do { ?>
        <span style="color:#306294; background-image:url(images/icons/history-bg.jpg); height:19px; border:solid 1px #85afd7; font-size:8px"><b>
        
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
		?></b>&nbsp;<span style="font-size:8px; color:#4383C2"><?php echo $day .' / '. $month .' '. $new_time; ?></span>
        </span>
        &nbsp;<?php echo nl2br($row_Recordset4['Comments']); ?>
	<?php } while ($row_Recordset4 = mysql_fetch_assoc($Recordset4)); ?>
                  </div>
                  <div style="background-color:#E1E1E1; color:#333; margin-bottom:5px; padding:3px; border:solid 1px #A6CAF0; font-size:12px">Comments</div>
                  <textarea name="comment" rows="4" class="tarea-tech" id="comment" type="text"></textarea>
                  <input name="jobno" type="hidden" id="jobno" value="<?php echo $row_Recordset5['JobNo']; ?>">
                  <div style="padding-top:5px;">
                  <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin-bottom:5px; padding:3px; border:solid 1px #A6CAF0">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="50%"><span class="combo_bold" style="color:#333;">Upload Image</span></td>
                        <td width="50%" align="right"><span class="combo_bold" style="color:#333;"><a href="jc-photos.php?Id=<?php echo $_GET['Id']; ?>">Search Gallery</a></span></td>
                      </tr>
                    </table>
                  </div>
                    <table border="0" cellpadding="2" cellspacing="3" class="combo">
                      <tr>
                        <td><input type="file" name="photo" id="photo"></td>
                        <td><?php if($totalRows_job_history >= 1){ echo $totalRows_job_history .' Photos'; ?></td>
                        <td><table border="0" cellpadding="2" cellspacing="3">
                          <tr>
                            <?php
  do { // horizontal looper version 3
?>
<td><a href="images/history/<?php echo $row_job_history['Photo']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})"> <img src="images/icons/btn-image.png" alt="" width="25" height="25" border="0"> </a></td>
                            <?php
    $row_job_history = mysql_fetch_assoc($job_history);
    if (!isset($nested_job_history)) {
      $nested_job_history= 1;
    }
    if (isset($row_job_history) && is_array($row_job_history) && $nested_job_history++ % 12==0) {
      echo "</tr><tr>";
    }
  } while ($row_job_history); //end horizontal looper version 3
?>
                          </tr>
                        </table>
                          <?php } // close if ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div style="padding-bottom:5px; padding-top:5px">
                  <table width="760" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td bgcolor="#A6CAF0" class="td-header">&nbsp;Dealers Feed Back </td>
                    </tr>
                  </table>
                </div>
                <?php
$jobid = $_GET['Id'];

$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
$row = mysql_fetch_array($result);

if(!empty($row['FeedBackDate'])){
	
	$fb_date = $row['FeedBackDate'];
	
} else {
	
	$fb_date = date('Y-m-d');
}
			?>
                <div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
                  <div style="padding-bottom:5px;">
                    <input name="fb_date" class="tarea" id="fb_date" value="<?php echo $fb_date; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no" wdg:readonly="true">
                  </div>
                  <?php
	echo $tNGs->getErrorMsg();
?>
<div id="history-log">
        <span style="color:#306294; background-image:url(images/icons/history-bg.jpg); height:19px; border:solid 1px #85afd7; font-size:8px"><b>
        
		<?php 
		
		echo $row_Recordset6['FeedBackTech'];
		
		?></b>&nbsp;<span style="font-size:8px; color:#4383C2"><?php echo $row_Recordset6['FeedBackDate']; ?></span>
        </span>
        &nbsp;<?php echo nl2br($row_Recordset6['FeedBack']); ?>
                  </div>                  <textarea name="feedback" rows="4" class="tarea-tech" id="feedback"><?php echo $row_Recordset6['FeedBack']; ?></textarea>
                  <input name="id_c" type="hidden" id="id_c" value="<?php echo $row['Id']; ?>">
                </div>
              </div>
              <?php } // close loop ?>
              </div>
              <?php if(isset($_GET['feedback'])){ ?>
              <span class="form_validation_field_error_error_message">Required Field</span>
              <?php } ?></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><table width="760" border="0" cellpadding="2" cellspacing="2">
              <tr>
                <td width="485" class="td-header">&nbsp;Actual Work Carried Out </td>
                <td width="50" align="center" class="td-header">Unit</td>
                <td width="50" align="center" class="td-header">Qty.</td>
                <td width="100" align="center" class="td-header">Unit Price </td>
                <td width="15" align="center" class="td-header">Delete</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo_bold"><div style=" border:solid 1px #A6CAF0; background-color:#EEE">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left"><div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Labour</div>
                    <?php
$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC") or die(mysql_error());
$numrows = mysql_num_rows($query);
while($row = mysql_fetch_array($query)){

$id = $row['Id'];

$query1 = "SELECT * FROM tbl_jc WHERE Id = '$id'";
$result1 = mysql_query($query1) or die(mysql_error());
$row1 = mysql_fetch_array($result1);

$rate = $row1['Price'];
$rate = explode(".", $rate);
$unit = $row['Unit'];

$query3 = mysql_query("SELECT * FROM tbl_rates WHERE Fuel = '1'")or die(mysql_error());
$row3 = mysql_fetch_array($query3);

$unit_display = $row['Name'];

?>
                    <div> &nbsp;
                      <textarea name="labour[]" rows="5" wrap="hard" class="tfield-jc" id="labour"><?php if($row['Description'] == NULL){ echo 'Conducted risk assessment & completed necessary safety documents'; } else { echo $row['Description']; } ?>
          </textarea>
                      <input name="unit_l[]" type="text" class="tarea" id="unit_l" value="<?php echo $unit; ?>" size="6">
                      <input name="qty_l[]" type="text" class="tarea" id="qty_l" value="<?php echo $row['Qty']; ?>" size="6">
                      <?php if($unit == 'hours'){ ?>
                      <select name="price_l[]" class="tarea" id="price_l[]" style="width:100px;">
                        <?php
do {  
?>
                        <option value="<?php echo $row_Recordset101['Rate']?>"<?php if (!(strcmp($row_Recordset101['Rate'], $rate[0]))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset101['Name_1']?></option>
                        <?php
} while ($row_Recordset101 = mysql_fetch_assoc($Recordset101));
  $rows = mysql_num_rows($Recordset101);
  if($rows > 0) {
      mysql_data_seek($Recordset101, 0);
	  $row_Recordset101 = mysql_fetch_assoc($Recordset101);
  }
?>
                      </select>
                      <?php } elseif($unit == 'km'){ ?>
                      <input name="price_display_l[]" type="text" class="tarea" id="price_display_l[]" value="<?php echo $row3['Name']; ?>" style="width:100px">
                      <input name="price_l[]" type="hidden" class="tarea" id="price_l[]" value="<?php echo $row['Price']; ?>">
                      <?php } ?>
                      &nbsp;&nbsp;
                      <input name="delete[]" type="checkbox" id="delete" value="<?php echo $row['Id']; ?>">
                      <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>">
                    </div>
                    <?php } // close loop ?>
                    <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Material</div>
                    <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                    <div> &nbsp;
                      <input name="material[]" type="text" class="tfield-jc" id="material" value="<?php echo $row['Description']; ?>">
                      <input name="unit_m[]" type="text" class="tarea" id="unit_m" value="<?php echo $row['Unit']; ?>" size="6">
                      <input name="qty_m[]" type="text" class="tarea" id="qty_m" value="<?php echo $row['Qty']; ?>" size="6">
                      <input name="price_m[]" type="text" class="tarea" id="price_m" value="<?php echo $row['Price']; ?>" size="16">
                      &nbsp;&nbsp;
                      <input name="delete_m[]" type="checkbox" id="delete_m[]" value="<?php echo $row['Id']; ?>">
                      <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>">
                    </div>
                    <?php } // close loop ?>
                    <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Transport</div>
                    <?php include('transport.php'); ?></td>
                </tr>
              </table>
            </div></td>
          </tr>
          <tr>
            <td colspan="3" class="combo_bold"><div class="combo_bold" style="margin-top:2px; margin-bottom:15px; clear:both; background-color:#5891C9">
            <?php if($row_Recordset5['Status'] <= 6){ ?>
              <table border="0" cellpadding="0" cellspacing="3" class="td-header">
                <tr>
                  <?php if($row_Recordset5['Status'] <= 5){ ?>
                  <td valign="middle"><span style="padding-bottom:5px">
                    <input name="Submit3" type="submit" class="btn-save" value="" style="padding:5px">
                  </span></td>
                  <?php } ?>
                  <?php
							$jobid = $_GET['Id'];
							$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
							$row = mysql_fetch_array($query);
							$status = $row['Status'];
							$print = $row['Print'];
							if($status == 1){ 
							?>
                  <td align="center" valign="middle">Send To In Progress </td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="1"></td>
                  <?php } elseif($status == 2 || $status == 5){ ?>
                  <td align="center" valign="middle">Send To Costing</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="2"></td>
                  <?php if($row_Recordset5['Id_1'] == 6 && $row_Recordset5['RequestPreWorkPo'] == 0){ ?>
                  <td align="center" valign="middle">Request Pre Work PO</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="request-pre-po"></td>
                  <?php } ?>
                  <?php } elseif($status == 3){ ?>
                  <td align="center" valign="middle">Send To Invoices</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="3"></td>
                  <?php } elseif($status == 5){ ?>
                  <td align="center" valign="middle">Send To In Progress </td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="5"></td>
                  <?php } ?>
                </tr>
              </table>
            <?php } ?>
            </div></td>
          </tr>
        </table>
    </form></td>
  </tr>
</table>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
//-->
</script>
</body>
</html>
<?php

unset($_SESSION['feedback']);
unset($_SESSION['services']);

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset3);

mysql_free_result($Recordset6);

mysql_free_result($Recordset7);

mysql_free_result($Recordset8);

mysql_free_result($Recordset100);

mysql_free_result($Recordset101);

mysql_free_result($Recordset9);

mysql_free_result($Recordset41);

mysql_free_result($Recordset42);

mysql_free_result($Recordset43);

mysql_free_result($Recordset44);

mysql_free_result($Recordset45);

mysql_free_result($rs_companies);

mysql_free_result($rs_sites);

mysql_free_result($job_history);

mysql_free_result($progress);

mysql_free_result($rs_transport_rates);

mysql_free_result($rs_components);

mysql_free_result($rs_failure);

mysql_free_result($rs_root_cause);

mysql_free_result($rs_repair);

mysql_free_result($rs_pragma);
?>
