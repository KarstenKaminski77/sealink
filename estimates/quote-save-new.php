<?php
session_start();

require_once('../functions/functions.php');

$query2 = mysqli_query($con, "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1")or die(mysqli_error());
$row2 = mysqli_fetch_array($query2);

$quote_no = $row2['QuoteNo']+1;
$date = date('Y-m-d');
$time = date('H:i:s');
$days = date('Y-m-j');

$quote = $_GET['quote'];

$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quote'")or die(mysqli_error());
while($row = mysqli_fetch_array($query)){
	
	$form_data = array(
		'QuoteNo' => $quote_no,
		'AreaId' => $row['AreaId'],
		'CompanyId' => $row['CompanyId'], 
		'SiteId' => $row['SiteId'], 
		'Notes' => $row['Notes'], 
		'Description' => $row['Description'], 
		'Unit' => $row['Unit'], 
		'Qty' => $row['Qty'], 
		'Price' => $row['Price'], 
		'Total1' => $row['Total1'], 
		'Email' => $row['Email'], 
		'SubTotal' => $row['SubTotal'], 
		'VAT' => $row['VAT'], 
		'Total2' => $row['Total2'], 
		'Labour' => $row['Labour'], 
		'Material' => $row['Material'], 
		'Transport' => $row['Transport'], 
		'JobDescription' => $row['JobDescription'], 
		'Total' => $row['Total'], 
		'Message' => $row['Message'], 
		'Attention' => $row['Attention'], 
		'FMC' => $row['FMC'], 
		'TransportComment' => $row['TransportComment'], 
		'Status' => 0, 
		'Parent' => $row['Parent'], 
		'Type' => $row['Type'],
		'InternalNotes' => $row['InternalNotes'],
		'UserId' => $_COOKIE['userid'],
		'UsersName' => $_COOKIE['name'],
		'SlaStart' => $row['SlaStart'],
		'SlaEnd' => $row['SlaEnd']
	);
	
	dbInsert('tbl_qs', $form_data, $con);
	
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


header('Location: quote-calc.php?Id='.$quote_no.'&new');
?>