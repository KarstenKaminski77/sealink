<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

// SLA
//$query_end = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE Id = '". $_POST['sub_cat'] ."'")or die(mysqli_error($con));
//$row_end = mysqli_fetch_array($query_end);
//
//$duration = $row_end['Duration'];
//
//$start = date('Y-m-d H:i:s');
//
//$future = addRollover(date('Y-m-d H:i:s'), $duration, '8:00', '16:30', true);
//
//$end = $future->format('Y-m-d H:i:s').'</br>';
//
//$sla_subcat = $_POST['sub_cat'];
// End SLA

$company = $_POST['company'];
$site = $_POST['site'];
$description = $_POST['description'];

$query = "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$row = mysqli_fetch_array($result);

$quote = $row['QuoteNo']+1;
$date = date('Y-m-d');
$time = date('H:i:s');
$today = date('Y-m-j');

$userid = $_COOKIE['userid'];
$areaid = $_SESSION['areaid'];
$usersname = $_COOKIE['name'];
$systemid = $_POST['system'];
$sla_subcat = 0;
$start = '00:00:00';
$end = '00:00:00';

mysqli_query($con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Labour,Date,SlaSubCat,SlaStart,SlaEnd,Time,JobDescription,Days,Status,UserId,UsersName,SystemId)
VALUES ('$areaid','$company','$site','$quote','1','$date',$sla_subcat,'$start','$end','$time','$description','$today','4','$userid','$usersname',$systemid)") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Material,Date,SlaSubCat,SlaStart,SlaEnd,Time,JobDescription,Days,Status,UserId,UsersName,SystemId)
VALUES ('$areaid','$company','$site','$quote','1','$date',$sla_subcat,'$start','$end','$time','$description','$today','4','$userid','$usersname',$systemid)") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Transport,Date,SlaSubCat,SlaStart,SlaEnd,Time,JobDescription,Days,Status,UserId,UsersName,SystemId)
VALUES ('$areaid','$company','$site','$quote','1','$date',$sla_subcat,'$start','$end','$time','$description','$today','4','$userid','$usersname',$systemid)") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_qs_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_qs_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_costing_material (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_costing_labour (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_costing_outsourcing (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_costing_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_costing_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

mysqli_query($con, "INSERT INTO tbl_costing_transport (QuoteNo) VALUES ('$quote')") or die(mysqli_error($con));

header('Location: quote-calc.php?Id='. $quote);
?>
