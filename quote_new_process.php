<?php
require_once('Connections/seavest.php');
require_once('includes/tng/tNG.inc.php');
require_once('functions/functions.php');

select_db();

// SLA
$query_end = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE Id = '". $_POST['sub_cat'] ."'")or die(mysqli_error($con));
$row_end = mysqli_fetch_array($query_end);

$duration = $row_end['Duration'];

$start = date('Y-m-d H:i:s');

$future = addRollover(date('Y-m-d H:i:s'), $duration, '8:00', '16:30', true);

$end = $future->format('Y-m-d H:i:s').'</br>';
// End SLA

$company = $_POST['company'];
$site = $_POST['site'];
$description = $_POST['description'];
if($_SESSION['kt_login_level'] >= 1){
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['kt_AreaId'];
}

$query = "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

$quote = $row['QuoteNo']+1;
$date = date('Y-m-d');
$time = date('H:i:s');
$today = date('Y-m-j');

$userid = $_SESSION['kt_login_id'];

$query_user = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$userid'")or die(mysqli_error($con));
$row_user = mysqli_fetch_array($query_user);

$usersname = $row_user['Name'];

mysql_query("INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Labour,Date,SlaStart,SlaEnd,Time,JobDescription,Days,Status,UserId,UsersName) 
VALUES ('$areaid','$company','$site','$quote','1','$date','$start','$end','$time','$description','$today','4','$userid','$usersname')") or die(mysql_error());

mysql_query("INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Material,Date,SlaStart,SlaEnd,Time,JobDescription,Days,Status,UserId,UsersName) 
VALUES ('$areaid','$company','$site','$quote','1','$date','$start','$end','$time','$description','$today','4','$userid','$usersname')") or die(mysql_error());

mysql_query("INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Transport,Date,SlaStart,SlaEnd,Time,JobDescription,Days,Status,UserId,UsersName) 
VALUES ('$areaid','$company','$site','$quote','1','$date','$start','$end','$time','$description','$today','4','$userid','$usersname')") or die(mysql_error());

mysql_query("INSERT INTO tbl_qs_hes (QuoteNo) VALUES ('$quote')") or die(mysql_error());

mysql_query("INSERT INTO tbl_qs_equipment (QuoteNo) VALUES ('$quote')") or die(mysql_error());

mysql_query("INSERT INTO tbl_costing_material (QuoteNo) VALUES ('$quote')") or die(mysql_error());

mysql_query("INSERT INTO tbl_costing_labour (QuoteNo) VALUES ('$quote')") or die(mysql_error());

mysql_query("INSERT INTO tbl_costing_outsourcing (QuoteNo) VALUES ('$quote')") or die(mysql_error());

mysql_query("INSERT INTO tbl_costing_hes (QuoteNo) VALUES ('$quote')") or die(mysql_error());

mysql_query("INSERT INTO tbl_costing_equipment (QuoteNo) VALUES ('$quote')") or die(mysql_error());

mysql_query("INSERT INTO tbl_costing_transport (QuoteNo) VALUES ('$quote')") or die(mysql_error());

header('Location: quote_calc.php?Id='. $quote .'');
?>