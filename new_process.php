<?php
session_start();

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

select_db();

$company = $_POST['company'];
$site = $_POST['site'];
$jobnumber = $_POST['jobnumber'];
$date = date('d M Y');
$today = date('Y-m-j');
$ref = $_POST['reference'];
$areaid = $_SESSION['areaid'];

if(!empty($_POST['cell'])){
$cell = $_POST['cell'];
mysql_query("INSERT INTO tbl_jc_mobile (SiteId,Mobile) VALUES ('$site','$cell')")or die(mysql_error());
}

if(!empty($_POST['cell2'])){
$cell = $_POST['cell2'];
mysql_query("INSERT INTO tbl_jc_mobile (SiteId,Mobile) VALUES ('$site','$cell2')")or die(mysql_error());
}

mysql_query("INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysql_error());

$query = mysql_query("SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysql_error());
$row = mysql_fetch_array($query);
$jobid = $row['Id'] + 1;

$_SESSION['jobid'] = $jobid;

$query = mysql_query("SELECT Id, Prefix FROM tbl_companies WHERE Id = '$company'")or die(mysql_error());
$row = mysql_fetch_array($query);

$prefix = $row['Prefix'];
$jobno = $prefix . $jobnumber;

$query2 = mysql_query("SELECT * FROM tbl_jc WHERE JobNo = '$jobno' LIMIT 1") or die(mysql_error());
$numrows = mysql_num_rows($query2);

if($numrows >= 1){
header('Location: job_card.php?duplicate');
} else {

$companyid = $row['Id'];

$query = mysql_query("SELECT * FROM tbl_rates WHERE CompanyId = '$companyid' AND Fuel = '1'")or die(mysql_error());
$row = mysql_fetch_array($query);
$rate = $row['Rate'];
 
$jcdate = date('Y-m-d');
 
if($companyid == 3){
$description = "Travel rate: Artisan plus two labour";
mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Labour,Date,Reference,Description,Unit,Price,Status,Days,JcDate) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','$description','km','$rate','1','$today','$jcdate')") or die(mysql_error());
}
mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Labour,Date,Reference,Unit,Status,Days,JcDate) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','hours','1','$today','$jcdate')") or die(mysql_error());

mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Material,Date,Reference,Status,Days,JcDate) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today','$jcdate')") or die(mysql_error());

//mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Transport,Date,Reference,Status,Days,JcDate) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today','$jcdate')") or die(mysql_error());

mysql_query("INSERT INTO tbl_travel (JobId) VALUES ('$jobid')") or die(mysql_error());

mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Comment,Date,Reference,Status,Days,JcDate) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today','$jcdate')") or die(mysql_error());

$date2 = date('d M Y');

mysql_query("INSERT INTO tbl_feedback (Reference,Date,Status) VALUES ('$jobno','$date2','1')")or die(mysql_error());

$target_path = "jc-pdf/";

$target_path = $target_path . basename( $_FILES['pdf']['name']); 

if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)) {
	
$pdf = $_FILES['pdf']['name'];
$id = $_GET['Id'];

mysql_query("UPDATE tbl_jc SET JobcardPDF = '$pdf' WHERE JobId = '$jobid'")or die(mysql_error());
}

header('Location: jc_mobile.php?Id='. $jobid .'');
}
?>
