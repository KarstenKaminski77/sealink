<?php
session_start();

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$query2 = mysqli_query($con, "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1")or die(mysqli_error());
$row2 = mysqli_fetch_array($query2);

$quote_no = $row2['QuoteNo']+1;
$date = date('Y-m-d');
$time = date('H:i:s');
$days = date('Y-m-j');

$quote = $_GET['quote'];

$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quote'")or die(mysqli_error());
while($row = mysqli_fetch_array($query)){
	
	$area = $row['AreaId']; 
	$company = $row['CompanyId']; 
	$site = $row['SiteId']; 
	$notes = $row['Notes']; 
	$description = $row['Description']; 
	$unit = $row['Unit']; 
	$qty = $row['Qty']; 
	$price = $row['Price']; 
	$total1 = $row['Total1']; 
	$email = $row['Email']; 
	$sub_total = $row['SubTotal']; 
	$vat = $row['VAT']; 
	$total2 = $row['Total2']; 
	$labour = $row['Labour']; 
	$material = $row['Material']; 
	$transport = $row['Transport']; 
	$job_description = $row['JobDescription']; 
	$total = $row['Total']; 
	$message = $row['Message']; 
	$attention = $row['Attention']; 
	$fmc = $row['FMC']; 
	$transport_comment = $row['TransportComment']; 
	$status = $status = 0; 
	$parent = $row['Parent']; 
	$type = $row['Type'];
	$InternalNotes = $row['InternalNotes'];
	$userid = $_SESSION['kt_login_id'];
	
	mysqli_query($con, "INSERT INTO tbl_qs (AreaId,QuoteNo,CompanyId,SiteId,Notes,Description,Unit,Qty,Price,Total1,Email,SubTotal,VAT,Total2,Date,Days,Time,Labour,Material,Transport,JobDescription,Total,Message,Attention,FMC,TransportComment,Status,Parent,Type,InternalNotes,UserId) 
	VALUES ('$area','$quote_no','$company','$site','$notes','$description','$unit','$qty','$price','$total1','$email','$sub_total','$vat','$total2','$date','$days','$time','$labour','$material','$transport','$job_description','$total','$message','$attention','$fmc','$transport_comment','$status','$parent','$type','$InternalNotes','$userid')")or die(mysqli_error($con));

}

$query = mysqli_query($con, "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1")or die(mysqli_error());
$row = mysqli_fetch_array($query);

$quote = $row['QuoteNo'];

mysqli_query($con, "INSERT INTO tbl_qs_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error());

mysqli_query($con, "INSERT INTO tbl_qs_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error());

mysqli_query($con, "INSERT INTO tbl_costing_material (QuoteNo) VALUES ('$quote')") or die(mysqli_error());

mysqli_query($con, "INSERT INTO tbl_costing_labour (QuoteNo) VALUES ('$quote')") or die(mysqli_error());

mysqli_query($con, "INSERT INTO tbl_costing_outsourcing (QuoteNo) VALUES ('$quote')") or die(mysqli_error());

mysqli_query($con, "INSERT INTO tbl_costing_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error());

mysqli_query($con, "INSERT INTO tbl_costing_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error());

mysqli_query($con, "INSERT INTO tbl_costing_transport (QuoteNo) VALUES ('$quote')") or die(mysqli_error());


header('Location: quote_calc.php?Id='.$quote_no.'&new');
?>