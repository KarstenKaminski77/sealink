<?php
require_once('functions/functions.php');

select_db();

$invoiceno = invno($con);

$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1' ORDER BY Id ASC LIMIT 1") or die(mysql_error());
$numrows = mysql_num_rows($query);

if($numrows >= 1){
mysql_query("DELETE FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '1'")or die(mysql_error());
}

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC LIMIT 1") or die(mysql_error());
$row = mysql_fetch_array($query);

$quoteno = $row['QuoteNo'];
$jobno = $row['JobNo'];

$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){
$quote = $row['QuoteNo']; 
$companyid = $row['CompanyId'];
$siteid = $row['SiteId'];
$desc = $row['Description']; 	
$unit = $row['Unit']; 	
$qty = $row['Qty']; 	
$price = $row['Price']; 	
$total1 = $row['Total1'];
$subtotal = $row['SubTotal'];
$vat = ($subtotal / 100) * 14;
$total2 = $subtotal + $vat;
$date = date('j M Y');	
$labour = $row['Labour'];
$material = $row['Material']; 	
$transport = $row['Transport']; 
$jobdesc = $row['JobDescription'];
$total = $row['Total'];

mysql_query ("INSERT INTO tbl_jc (JobNo, QuoteNo, CompanyId, SiteId, Description1, Unit1, Qty1, Price1, Total3, SubTotal1, VAT1, InvoiceDate, Labour1, Material1, Transport1, InvoiceQ, InvoiceNo, Invoice, JobId) 
VALUES ('$jobno','$quote','$companyid','$siteid','$desc','$unit','$qty','$price','$total1','$subtotal','$vat','$date','$labour','$material','$transport','1','$invoiceno','1','$jobid')") or die(mysql_error());
}
header('Location: invoiceQ_calc.php?Id='. $jobid .'');
?>
