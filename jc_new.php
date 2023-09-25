<?php
require_once('functions/functions.php');

select_db();

$quoteno = $_GET['quoteno'];
$jobno = $_POST['jobno'];

mysql_query("INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysql_error());

$query = mysql_query("SELECT * FROM tbl_jc ORDER BY Id DESC LIMIT 1") or die(mysql_error());
$row = mysql_fetch_array($query);

$jobid = $row['Id'];

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
$total = $row['Total1']; 	
$date = date('j M Y');	
$labour = $row['Labour'];
$material = $row['Material']; 	
$transport = $row['Transport'];
$t_comment = $row['TransportComment'];
if(isset($_POST['jobno'])){ 
$jobno = $_POST['jobno'];
} else {
$jobno = $row['FMC'];
}

mysql_query ("INSERT INTO tbl_jc (QuoteNo, JobNo, CompanyId, SiteId, Description, Unit, Qty, Price,Total1,Date,Labour,Material,Transport,JobId,TransportComment,JC) VALUES ('$quote','$jobno','$companyid','$siteid','$desc','$unit','$qty','$price','$total','$date','$labour','$material','$transport','$jobid','$t_comment','1')") or die(mysql_error());
}

$row = mysql_fetch_array($result);
$quote = $row['QuoteNo']; 
$companyid = $row['CompanyId'];
$siteid = $row['SiteId'];
$desc = $row['Description']; 	

mysql_query ("INSERT INTO tbl_jc (QuoteNo, JobNo, CompanyId, SiteId, Description, Comment, JobId) VALUES ('$quote','$jobno','$companyid','$siteid','$desc','1','$jobid')") or die(mysql_error());

header('Location: jc_calc.php?Id='. $jobid .'');
?>