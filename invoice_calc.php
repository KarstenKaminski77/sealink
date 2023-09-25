<?php require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
<?php 
session_start();

$today = date('Y-m-j');

require_once('Connections/seavest.php');

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

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

$today = date('Y-m-j');

select_db();

$jobid = $_GET['Id'];

if(isset($_POST['Submit3']) || isset($_POST['complete'])){
	
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
	
}

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$siteid = $row['SiteId'];
$companyid = $row['CompanyId'];
$jobno = $row['JobNo'];

mysql_query("UPDATE tbl_jc SET CompanyId = '$companyid', SiteId = '$siteid', JobNo = '$jobno' WHERE JobId = '$jobid'")or die(mysql_error());

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysql_error());
$row = mysql_fetch_array($query);

$invoiceno = $row['InvoiceNo'];

if(isset($_GET['delete'])){
mysql_query("UPDATE tbl_jc SET Status = '14', Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());
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

if(isset($_POST['date1'])){

$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$jobid = $_GET['Id'];
$service = $_POST['service'];

mysql_query("UPDATE tbl_jc SET Date1 = '$date1', Date2 = '$date2', JobDescription = '$service' WHERE JobId = '$jobid'") or die(mysql_error());
}

if(isset($_POST['inv_date'])){

$date = $_POST['inv_date'];
$date = date('d M Y',strtotime($date));
$jobid = $_GET['Id'];

mysql_query("UPDATE tbl_jc SET InvoiceDate = '$date' WHERE JobId = '$jobid'") or die(mysql_error());
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
		
		mysql_query("INSERT INTO tbl_jc (InvoiceNo,JobId,Labour,Unit) VALUES ('$invoiceno','$jobid','1','hours')") or die(mysql_error());
	}
}

// Add material row

if($_POST['material_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['material_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysql_query("INSERT INTO tbl_jc (InvoiceNo,JobId,Material) VALUES ('$invoiceno','$jobid','1')") or die(mysql_error());
	}
}

// Add transport row

if($_POST['transport_row'] >= 1){
		
	$jobid = $_GET['Id'];
	$rows = $_POST['transport_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysql_query("INSERT INTO tbl_travel (InvoiceNo,JobId) VALUES ('$invoiceno','$jobid')") or die(mysql_error());
	}
}

// Add comment row

if($_POST['comment_row'] >= 1){
	
	$jobid = $_GET['Id'];
	$rows = $_POST['comment_row'];
	
	for($i=0;$i<$rows;$i++){
		
		mysql_query("INSERT INTO tbl_jc (InvoiceNo,JobId,Comment) VALUES ('$invoiceno','$jobid','1')") or die(mysql_error());
	}
}

$jobid = $_GET['Id'];

if(isset($_GET['update'])){

$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);

$idl = $_POST['id_l'];
$jobid = $_GET['Id'];
$labour_l =  $_POST['labour'];
$unit_l = $_POST['unit_l'];
$qty_l = $_POST['qty_l'];
$qty_t = $_POST['qty_t'];
$transport_t = $_POST['transport'];
$price_l = $_POST['price_l'];

for($i=0;$i<$numrows;$i++){
$id = $idl[$i];
$labour = $labour_l[$i];
$unit = $unit_l[$i];
$qty = $qty_l[$i];
$qty_fuel = $qty_t[0];
$trips = $transport_t[0];

$price = explode("_",$price_l[$i]);

$new_price = $price[0];
$labour_name = $price[1];

$total = $qty * $new_price;
$total_fuel = $qty_fuel * $new_price * $trips;

if($unit == 'hours'){
mysql_query("UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty', Price = '$new_price', Total1 = '$total', LabourType = '$labour_name' WHERE Id = '$id'") or die(mysql_error());
} elseif($unit == 'km'){
mysql_query("UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty_fuel', Price = '$new_price', Total1 = '$total_fuel', LabourType = '$labour_name' WHERE Id = '$id'") or die(mysql_error());
}}

$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);

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

$idt = $_POST['id_t'];
$jobid = $_GET['Id'];
$transport_t = $_POST['transport'];
$unit_t = $_POST['unit_t'];
$qty_t = $_POST['qty_t'];
$price_t = $_POST['price_t'];
$comment_t = $_POST['t_comment'];
$travel_time_rate_t = $_POST['travel-time-rate'];
$jobno_t = $_POST['jobno'];

$query = mysql_query("SELECT * FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
$numrows = mysql_num_rows($query);

for($i=0;$i<$numrows;$i++){
	
	$id = $idt[$i];
	$transport = $transport_t[$i];
	$unit = $unit_t[$i];
	$qty = $qty_t[$i];
	$price = $price_t[$i];
	$comment = $comment_t[$i];
	$jobno = $jobno_t[$i];
	
	$travel_time_rate = $travel_time_rate_t[$i];
	
	$total_pragma = $qty *  $travel_time_rate;
	$total = $qty * $transport * $price;
	
	mysql_query("UPDATE tbl_travel SET  JobNo = '$jobno', Description = '$transport', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total', TransportComment = '$comment', TravelTime = '$travel_time', TravelTimeRate = '$travel_time_rate', TotalPragma = '$total_pragma' WHERE Id = '$id'") or die(mysql_error());
}

if(isset($_POST['id_c'])){
$idc = $_POST['id_c'];
$jobid = $_GET['Id'];
$day_c = $_POST['day'];
$month_c = $_POST['month'];
$year_c = $_POST['year'];
$hour_c = $_POST['hour'];
$minute_c = $_POST['minute'];
$date_c = $day ." ". $month_c ." ". $year_c ." ". $hour_c ." ". $minute_c;
$name_c = $_POST['comment_name'];
$comment_c = $_POST['comment'];
$feedback_c = $_POST['feedback'];
$fbtech_c = $_POST['tech'];
$fb_day_c = $_POST['fb_day'];
$fb_month_c = $_POST['fb_month'];
$fb_year_c = $_POST['fb_year'];
$fb_hour_c = $_POST['fb_hour'];
$fb_minute_c = $_POST['fb_minute'];
$fb_date_c = $fb_day_c ." ". $fb_month_c ." ". $fb_year_c ." ". $fb_hour_c ." ". $fb_minute_c;

if(isset($_POST['comment1'])){
for($i=0;$i<$numrows;$i++){
$id = $idc[$i];
$day = $day_c[$i];
$month = $month_c[$i];
$year = $year_c[$i];
$hour = $hour_c[$i];
$minute = $minute_c[$i];
$date = $day_c[$i] ." ". $month_c[$i] ." ". $year_c[$i] ." ". $hour_c[$i] ." ". $minute_c[$i];
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
$fb_date = $fb_day_c[$i] ." ". $fb_month_c[$i] ." ". $fb_year_c[$i] ." ". $fb_hour_c[$i] ." ". $fb_minute_c[$i];

mysql_query("UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date', CommentName = '$name', CommentText = '$comment', FeedBack = '$feedback', FeedBackTech = '$fbtech', FeedBackDate = '$fb_date' WHERE Id = '$id' AND Comment = '1'") or die(mysql_error());

}} else {
mysql_query("UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date_c', CommentName = '$name_c', CommentText = '$comment_c' ,FeedBack = '$feedback_c', FeedBackTech = '$fbtech_c', FeedBackDate = '$fb_date_c' WHERE JobId = '$jobid' AND Comment = '1' ORDER BY Id ASC LIMIT 1") or die(mysql_error());
}}}

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

if($companyid == 12){
	
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

$KTColParam1_Recordset5 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset5 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = sprintf("SELECT tbl_companies.Name, tbl_companies.Id, tbl_companies.Address, tbl_companies.ContactName, tbl_companies.ContactNumber, tbl_companies.ContactEmail, tbl_jc.CompanyId, tbl_jc.JobId, tbl_jc.JobNo, tbl_jc.InvoiceNo, tbl_jc.InvoiceDate FROM (tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId=%s ", $KTColParam1_Recordset5);
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

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
$query_Recordset6 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset6);
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

mysql_select_db($database_seavest, $seavest);
$query_Recordset7 = "SELECT * FROM tbl_rates";
$Recordset7 = mysql_query($query_Recordset7, $seavest) or die(mysql_error());
$row_Recordset7 = mysql_fetch_assoc($Recordset7);
$totalRows_Recordset7 = mysql_num_rows($Recordset7);

$jobid = $_GET['Id'];
$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);
$companyid = $row['CompanyId'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset8 = "SELECT * FROM tbl_fuel WHERE Company = '$companyid'";
$Recordset8 = mysql_query($query_Recordset8, $seavest) or die(mysql_error());
$row_Recordset8 = mysql_fetch_assoc($Recordset8);
$totalRows_Recordset8 = mysql_num_rows($Recordset8);

mysql_select_db($database_seavest, $seavest);
$query_Recordset100 = "SELECT * FROM tbl_technicians";
$Recordset100 = mysql_query($query_Recordset100, $seavest) or die(mysql_error());
$row_Recordset100 = mysql_fetch_assoc($Recordset100);
$totalRows_Recordset100 = mysql_num_rows($Recordset100);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$jobid = $_GET['Id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset99 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Id AS Id_1, tbl_companies.Name, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Telephone, tbl_sites.Email, tbl_jc.Id, tbl_jc.JobId, tbl_jc.JobNo, tbl_jc.Date, tbl_jc.JobDescription, tbl_jc.Reference FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId = '$jobid' ORDER BY Id ASC LIMIT 1";
$Recordset99 = mysql_query($query_Recordset99, $seavest) or die(mysql_error());
$row_Recordset99 = mysql_fetch_assoc($Recordset99);
$totalRows_Recordset99 = mysql_num_rows($Recordset99);

$companyid = $row_Recordset99['Id_1'];
mysql_select_db($database_seavest, $seavest);
$query_Recordset101 = "SELECT tbl_rates.Name AS Name_1, tbl_companies.Name, tbl_rates.Rate, tbl_rates.CompanyId FROM (tbl_companies LEFT JOIN tbl_rates ON tbl_rates.CompanyId=tbl_companies.Id) WHERE tbl_rates.CompanyId='$companyid' ";
$Recordset101 = mysql_query($query_Recordset101, $seavest) or die(mysql_error());
$row_Recordset101 = mysql_fetch_assoc($Recordset101);
$totalRows_Recordset101 = mysql_num_rows($Recordset101);

$KTColParam1_rs_site_name = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_rs_site_name = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_rs_site_name = sprintf("SELECT tbl_jc.JobId, tbl_sites.Name, tbl_sites.Address, tbl_sites.Suburb FROM (tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) WHERE tbl_jc.JobId=%s ", $KTColParam1_rs_site_name);
$rs_site_name = mysql_query($query_rs_site_name, $seavest) or die(mysql_error());
$row_rs_site_name = mysql_fetch_assoc($rs_site_name);
$totalRows_rs_site_name = mysql_num_rows($rs_site_name);


$companyid = $row_Recordset5['Id'];

mysql_select_db($database_seavest, $seavest);
$query_rs_transport_rates = "SELECT * FROM tbl_rates WHERE CompanyId = '$companyid' AND Fuel = '1' ORDER BY Name ASC";
$rs_transport_rates = mysql_query($query_rs_transport_rates, $seavest) or die(mysql_error());
$row_rs_transport_rates = mysql_fetch_assoc($rs_transport_rates);
$totalRows_rs_transport_rates = mysql_num_rows($rs_transport_rates);

$colname_rs_pragma = "-1";
if (isset($_GET['Id'])) {
  $colname_rs_pragma = $_GET['Id'];
}
mysql_select_db($database_inv, $inv);
$query_rs_pragma = sprintf("SELECT * FROM tbl_pragma WHERE JobId = %s", GetSQLValueString($colname_rs_pragma, "int"));
$rs_pragma = mysql_query($query_rs_pragma, $inv) or die(mysql_error());
$row_rs_pragma = mysql_fetch_assoc($rs_pragma);
$totalRows_rs_pragma = mysql_num_rows($rs_pragma);

$companyid = $row_Recordset5['Id'];

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

$KTColParam1_job_history = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_job_history = $_GET["Id"];
}
mysql_select_db($database_seavest, $seavest);
$query_job_history = sprintf("SELECT tbl_history_relation.PhotoId, tbl_history_photos.Photo, tbl_history_relation.JobId FROM (tbl_history_relation LEFT JOIN tbl_history_photos ON tbl_history_photos.Id=tbl_history_relation.PhotoId) WHERE tbl_history_relation.JobId=%s ", GetSQLValueString($KTColParam1_job_history, "int"));
$job_history = mysql_query($query_job_history, $seavest) or die(mysql_error());
$row_job_history = mysql_fetch_assoc($job_history);
$totalRows_job_history = mysql_num_rows($job_history);

// Send to approved

if(isset($_POST['complete'])){
	
	$jobid = $_GET['Id'];
	$date = date('d M Y');
	
	if($row_Recordset99['Id_1'] == 1 || $row_Recordset99['Id_1'] == 12){ // Engen requires order number
		
		$query = mysql_query("UPDATE tbl_jc SET Status = '11',  Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());
		$search = '?'. $_SESSION['search'];
		
	} else {
		
		$query = mysql_query("UPDATE tbl_jc SET Status = '12',  Days = '$today' WHERE JobId = '$jobid'") or die(mysql_error());
		$search = '?'. $_SESSION['search'];
	}
	
	header('Location: fpdf16/approved-pdf.php?Id='. $_GET['Id']);
}

?>
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
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
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
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <td colspan="4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="images/banner.jpg" alt="" width="823" height="151"></td>
            </tr>
          </table>
            <table width="759" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><form name="form2" method="post" action="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="td-header">
                    <tr>
                      <td><table border="0" cellpadding="2" cellspacing="0" class="td-header">
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
                            </select></td>
                          <td><input name="Submit2" type="submit" class="btn-go-search-2" value=""></td>
                          <td width="50">&nbsp;</td>
                          <td width="50" align="right" valign="middle">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                  </table>
                </form></td>
              </tr>
            </table></td>
          </tr>
        </table>
          <form name="form1" method="post" action="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $jobid; ?>&update#btm" id="inv-padding">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="3" class="combo"><div style="border:solid 1px #A6CAF0; width:759px; background-color:#EEE"">
                  <table width="100%" border="0" cellpadding="3" cellspacing="0">
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                        <tr>
                          <td width="34%" valign="top"><p class="combo"><span style="padding-right:10px"><span class="combo_bold"><?php echo $row_Recordset5['Name']; ?></span><br>
                            <?php echo $row_Recordset5['ContactName']; ?><br>
                            <?php echo nl2br($row_Recordset5['Address']); ?>&nbsp;<br />
                            <?php echo $row_Recordset5['ContactNumber']; ?><br />
                            <?php echo $row_Recordset5['ContactEmail']; ?></span></p></td>
                          <td width="25%" valign="top" class="combo2"><?php echo $row_rs_site_name['Name']; ?><br>
                            <?php echo $row_rs_site_name['Address']; ?><br>
                            <?php echo $row_rs_site_name['Suburb']; ?></td>
                          <td width="41%" align="right" valign="top" class="combo"><div style="padding-right:10px"><span class="combo_bold">Date:
                            <?php 
								  $date = $row_Recordset5['InvoiceDate']; 
								  $date2 = date('Y-m-d',strtotime($date));
								  ?>
                            <input name="inv_date" class="tarea" id="inv_date" value="<?php echo $date2; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                            &nbsp;<br>
                            Reference:</span> <?php echo $row_Recordset5['JobNo']; ?><br />
                            <span class="combo_bold">&nbsp; Invoice No:</span> <?php echo $invoiceno; ?></div></td>
                        </tr>
                        <tr>
                          <td colspan="2" class="combo_bold">&nbsp;</td>
                          <td width="41%" class="combo_bold">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3"><span class="combo_bold">&nbsp; Received Date</span>
                            <input name="date1" class="tarea" id="date1" value="<?php echo $row_Recordset6['Date1']; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="combo_bold">Requested Completion:</span>&nbsp;
                            <input name="date2" class="tarea" id="date2" value="<?php echo $row_Recordset6['Date2']; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
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
          <?php if($row_Recordset5['Id'] == 2){ ?>
          <tr>
            <td bordercolor="#FFFFFF" class="combo">
            <table width="760" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header">&nbsp; Invoice Details</td>
              </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td bordercolor="#FFFFFF" class="combo"><div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
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
                <td colspan="3" class="combo"><div style="padding-bottom:5px; padding-top:5px">
                  <table width="760" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td bgcolor="#A6CAF0" class="td-header">&nbsp; Service Requested</td>
                      </tr>
                  </table>
                </div></td>
              </tr>
              <tr>
                <td colspan="3" class="combo"><div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
                  <textarea name="service" rows="5" class="tarea-tech" id="service"><?php echo $row_Recordset6['JobDescription']; ?></textarea>
                </div></td>
              </tr>
              <tr>
                <td colspan="3" class="combo"><div style="padding-bottom:5px; padding-top:5px">
                  <table width="760" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td bgcolor="#A6CAF0" class="td-header">&nbsp; History</td>
                      </tr>
                  </table>
                </div></td>
              </tr>
              <tr>
                <td colspan="3" class="combo">
                <div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
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
                </div>
                </td>
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
                    <div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
                      <div style="padding-bottom:5px;">
                        <select name="day" class="tarea" id="day">
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
                        <select name="month" class="tarea" id="month">
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
                          <option value="" <?php if (!(strcmp("", $row['FeedBackTech']))) {echo "selected=\"selected\"";} ?>>Select one...</option>
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset100['Id']?>"<?php if (!(strcmp($row_Recordset100['Id'], $row['FeedBackTech']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset100['Name']?></option>
                          <?php
} while ($row_Recordset100 = mysql_fetch_assoc($Recordset100));
  $rows = mysql_num_rows($Recordset100);
  if($rows > 0) {
      mysql_data_seek($Recordset100, 0);
	  $row_Recordset100 = mysql_fetch_assoc($Recordset100);
  }
?>
                        </select>
                        <input name="delete_c" type="checkbox" id="delete_c" value="<?php echo $row['Id']; ?>" />
                      </div>
                      <textarea name="comment" rows="8" class="tarea-tech" id="comment" type="text"><?php echo $row['CommentText']; ?></textarea>
                    </div>
                    <div style="padding-bottom:5px; padding-top:5px">
                      <table width="760" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td bgcolor="#A6CAF0" class="td-header">&nbsp;Feed Back </td>
                        </tr>
                      </table>
                    </div>
                    <?php
$jobid = $_GET['Id'];

$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
$row = mysql_fetch_array($result);

$fb_date = $row['FeedBackDate'];

$split = explode(" ", $fb_date);

$day = $split[0];
$month = $split[1];
$year = $split[2];
$hour = $split[3];
$minute = $split[4];
			?>
                    <div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
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
                      <textarea name="feedback" rows="8" class="tarea-tech" id="feedback"><?php echo $row['FeedBack']; ?></textarea>
                      <input name="id_c" type="hidden" id="id_c" value="<?php echo $row['Id']; ?>" />
                    </div>
                  </div>
                  <?php } // close loop ?>
                  </div>
                  <div style="padding-top:5px;">
                    <div style="padding-bottom:5px;">
                      <table width="760" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td bgcolor="#A6CAF0" class="td-header">&nbsp;Images</td>
                        </tr>
                      </table>
                    </div>
                    <div class="combo_bold" style="background-color:#E1E1E1; color:#333; padding:3px; border:solid 1px #A6CAF0">
                      <table border="0" cellpadding="2" cellspacing="3" class="combo">
                      <tr>
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
    if (isset($row_job_history) && is_array($row_job_history) && $nested_job_history++ % 6==0) {
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
                  </td>
              </tr>
              <tr>
                <td colspan="3" class="combo"><div style="padding-bottom:5px; padding-top:5px">
                  <table width="760" border="0" cellpadding="0" cellspacing="1">
                    <tr>
                      <td width="485" bgcolor="#A6CAF0" class="td-header">&nbsp;Actual Work Carried Out </td>
                      <td width="50" align="center" bgcolor="#A6CAF0" class="td-header">Unit</td>
                      <td width="50" align="center" bgcolor="#A6CAF0" class="td-header">Qty.</td>
                      <td width="100" align="center" bgcolor="#A6CAF0" class="td-header">Unit Price </td>
                      <td width="15" align="center" bgcolor="#A6CAF0" class="td-header">Delete</td>
                      </tr>
                  </table>
                </div></td>
              </tr>
              <tr>
                <td colspan="3" class="combo_bold"><div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="left"><div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Labour</div>
                        <?php
$jobid = $_GET['Id'];						
						
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){

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
                          <textarea name="labour[]" rows="5" class="tfield-jc" id="labour"><?php echo $row['Description']; ?></textarea>
                          <input name="unit_l[]" type="text" class="tarea" id="unit_l" value="<?php echo $unit; ?>" size="6">
                          <input name="qty_l[]" type="text" class="tarea" id="qty_l" value="<?php echo $row['Qty']; ?>" size="6">
                          <?php if($unit == 'hours'){ ?>
                          <select name="price_l[]" class="tarea" id="price_l[]" style="width:100px;">
                            <?php
$labour_type = $row1['LabourType'];
do {

?>
                            <option value="<?php echo $row_Recordset101['Rate'] .'_'.$row_Recordset101['Name_1']; ?>"<?php if ($row_Recordset101['Name_1'] == $labour_type) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset101['Name_1']; ?></option>
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
                          <input name="delete[]" type="checkbox" id="delete" value="<?php echo $row['Id']; ?>" />
                          <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>" />
                        </div>
                        <?php } // close loop ?>
                        <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Material</div>
                        <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                        <div> &nbsp;
                          <input name="material[]" type="text" class="tfield-jc" id="material" size="95" value="<?php echo $row['Description']; ?>" />
                          <input name="unit_m[]" type="text" class="tarea" id="unit_m" value="<?php echo $row['Unit']; ?>" size="6" />
                          <input name="qty_m[]" type="text" class="tarea" id="qty_m" value="<?php echo $row['Qty']; ?>" size="6" />
                          <input name="price_m[]" type="text" class="tarea" id="price_m" value="<?php echo $row['Price']; ?>" size="16" />
                          &nbsp;&nbsp;
                          <input name="delete_m[]" type="checkbox" id="delete_m[]" value="<?php echo $row['Id']; ?>" />
                          <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>" />
                        </div>
                        <?php } // close loop ?>
                        <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Transport</div>
                        <?php include('transport.php'); ?></td>
                    </tr>
                  </table>
                </div></td>
              </tr>
            </table>
            <div class="combo_bold" style="margin-top:2px; margin-bottom:15px; clear:both; background-color:#5891C9; margin-left:30px; margin-right:30px">
              <table border="0" cellspacing="3" cellpadding="0">
                <tr>
                  <td align="right" valign="middle"><img src="images/btn-preview.jpg" alt="" width="58" height="17" border="0" onClick="MM_openBrWindow('fpdf16/inv-preview.php?Id=<?php echo $_GET['Id']; ?>','','scrollbars=yes,width=750')"></td>
                  <td align="right" valign="middle"><span style="padding-bottom:5px">
                    <input name="Submit3" type="submit" class="tarea4" id="Submit3" value="Save">
                  </span></td>
                  <td align="right" valign="middle"><span style="padding-bottom:5px">
                    <input name="complete" type="submit" class="tarea5" id="complete" value="Approve" style="width:58px" />
                  </span></td>
                  <td valign="middle"><img src="images/btn-reject.jpg" alt="" width="58" height="17" border="0" onClick="MM_openBrWindow('reject.php?Id=<?php echo $_GET['Id']; ?>','','width=600,height=200')"></td>
                  <td valign="middle"><a href="invoice_calc.php?menu=<?php echo $_GET['menu']; ?>&delete&Id=<?php echo $_GET['Id']; ?>"><img src="images/btn-cancel.jpg" alt="" width="58" height="17" border="0"></a></td>
                  <td width="30" valign="middle">&nbsp;</td>
                  <td align="center" valign="middle" class="combo"><a name="btm"></a></td>
                  <td valign="middle">&nbsp;</td>
                </tr>
              </table>
            </div>
          </form></td>
      </tr>
    </table>
    
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset3);

mysql_free_result($Recordset6);

mysql_free_result($Recordset7);

mysql_free_result($Recordset8);

mysql_free_result($Recordset100);

mysql_free_result($rs_site_name);

mysql_free_result($rs_pragma);
?>
