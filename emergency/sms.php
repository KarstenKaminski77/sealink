<?php
require_once('../functions/functions.php');

select_db();

$area = $_GET['Id'];
$company = $_POST['select2'];
$site = $_POST['select3'];
$description = $_POST['desc'];
$requestor = $_POST['requestor'];
$telephone = $_POST['telephone'];
$email = $_POST['email'];
$date = date('D d M Y');

$query = mysql_query("SELECT * FROM tbl_companies WHERE Id = '$company'")or die(mysql_error());
$row = mysql_fetch_array($query);

$company_name = $row['Name'];

$query2 = mysql_query("SELECT * FROM tbl_sites WHERE Id = '$site'")or die(mysql_error());
$row2 = mysql_fetch_array($query2);

$site_name = $row2['Name'];

$query3 = mysql_query("SELECT * FROM tbl_emergency_cells")or die(mysql_error());
while($row3 = mysql_fetch_array($query3)){

//$to  = 'sms@messaging.clickatell.com'; 
//$subject = 'Seavest';
//$from = "control@seavest.co.za";
//
//$cell = $row3['Cell'];
//
//$message = '
//user:seavest
//password:abc123
//api_id:3232946
//to:'. $cell .'
//reply: control@seavest.co.za
//concat: 3
//text:Company: '. $company_name .', Site: '. $site_name .', Requestor: '. $requestor .', Telephone: '. $telephone .', Email: '. $email .', Description: '. $description;
//
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//
//$headers .= 'FROM: '. $from . "\r\n";
//
//mail($to, $subject, $message, $headers);
}

mysql_query("INSERT INTO tbl_emergency (AreaId,CompanyId,SiteId,Description,Requestor,Cell,Email,DateSubmitted) VALUES ('$area','$company','$site','$description','$requestor','$telephone','$email','$date')")or die(mysql_error());

$query4 = mysql_query("SELECT * FROM tbl_emergency ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row4 = mysql_fetch_array($query4);

$id = $row4['Id'];

header('Location: confirm.php?Id='. $id);
?>
