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
if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}

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
 
if($companyid == 3){
$description = "Travel rate: Artisan plus two labour";
mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Labour,Date,Reference,Description,Unit,Price,Status,Days) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','$description','km','$rate','1','$today')") or die(mysql_error());
}
mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Labour,Date,Reference,Unit,Status,Days) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','hours','1','$today')") or die(mysql_error());

mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Material,Date,Reference,Status,Days) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today')") or die(mysql_error());

mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Transport,Date,Reference,Status,Days) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today')") or die(mysql_error());

mysql_query("INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId,Comment,Date,Reference,Status,Days) VALUES ('$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today')") or die(mysql_error());

$date2 = date('d M Y');

mysql_query("INSERT INTO tbl_feedback (Reference,Date,Status) VALUES ('$jobno','$date2','1')")or die(mysql_error());

header('Location: jc_mobile.php?Id='. $jobid .'');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
AreaId,JobNo,JobNumber,CompanyId,SiteId,Description,Unit,Qty,Price,Total1,Email,SubTotal,VAT,Total2,Date,Labour,Material,Comment,CommentDate,CommentName,CommentText,FeedBack,FeedBackTech,FeedBackDate,Transport,JobDescription,Date1,Date2,CompletionDate,InvoiceNo,InvoiceSent,InvoiceDate,Total,JobId,SearchDate,Description1,Unit1,Qty1,Price1,Labour1,Material1,Transport1,SubTotal1,VAT1,Total3,InvoiceQ,VatIncl,TransportComment,Reason,Message,Reference,RefNo,String,Status

</body>
</html>
