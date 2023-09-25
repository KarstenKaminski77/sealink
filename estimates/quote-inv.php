<?php
require_once('../functions/functions.php');

$invoiceno = invno($con);
$quoteno = $_GET['Id'];

mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));

$query2 = mysqli_query($con, "SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
$row2 = mysqli_fetch_array($query2);

$jobid = $row2['Id'] + 1;

$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'") or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	$quote = $row['QuoteNo']; 
	$companyid = $row['CompanyId'];
	$siteid = $row['SiteId'];
	$desc = $row['Description']; 	
	$unit = $row['Unit']; 	
	$qty = $row['Qty']; 	
	$price = $row['Price']; 	
	$total1 = $row['Total1'];
	$subtotal = $row['SubTotal'];
	$vat = ($subtotal / 100) * 15;
	$total2 = $subtotal + $vat;
	$date = date('j M Y');	
	$labour = $row['Labour'];
	$material = $row['Material']; 	
	$transport = $row['Transport']; 
	$jobdesc = $row['JobDescription'];
	$total = $row['Total'];

	mysqli_query ($con, "INSERT INTO tbl_jc (JobNo, QuoteNo, CompanyId, SiteId, Description1, Unit1, Qty1, Price1, Total3, SubTotal1, VAT1, InvoiceDate, Labour1, Material1, Transport1, InvoiceQ, InvoiceNo, JobId) 
	VALUES ('$jobno','$quote','$companyid','$siteid','$desc','$unit','$qty','$price','$total1','$subtotal','$vat','$date','$labour','$material','$transport','1','$invoiceno','$jobid')") or die(mysqli_error($con));
}
header('Location: ../invoices/inv-calc.php?Id='. $jobid .'');

?>
