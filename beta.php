<?php 
error_reporting(ERROR);
ini_set('display_errors', '1');

session_start();

require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');
?>
<?php
require_once('functions/functions.php');

require_once('includes/common/KT_common.php');

require_once('includes/tng/tNG.inc.php');

select_db();

$quoteno = $_GET['Id'];

// Costing

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

// Costing HES
if(isset($_POST['btn-hes'])){
	
	mysqli_query($con, "INSERT INTO tbl_costing_hes (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));
	
}
	
if(isset($_POST['hes-c'])){
	
	for($i=0;$i<count($_POST['hes-c']);$i++){
		
		$id = $_POST['id-c-hes'][$i];
		$desc = $_POST['hes-c'][$i];
		$price = $_POST['hes-price-c'][$i];
		
		mysqli_query($con, "UPDATE tbl_costing_hes SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($con));
		
	}
}

if(isset($_POST['delete-hes-c'])){
	
	$id = $_POST['delete-hes-c'];
	
	foreach($id as $c){
		
		mysqli_query($con, "DELETE FROM tbl_costing_hes WHERE Id = '$c'")or die(mysqli_error($con));
	}
}

// Costing Material
if(isset($_POST['btn-material'])){
	
	mysqli_query($con, "INSERT INTO tbl_costing_material (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));
	
}
	
if(isset($_POST['material-c'])){
	
	for($i=0;$i<count($_POST['material-c']);$i++){
		
		$id = $_POST['id-c-m'][$i];
		$desc = $_POST['material-c'][$i];
		$price = $_POST['material-price-c'][$i];
		
		mysqli_query($con, "UPDATE tbl_costing_material SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($con));
		
	}
}

if(isset($_POST['delete-m-c'])){
	
	$id = $_POST['delete-m-c'];
	
	foreach($id as $c){
		
		mysqli_query($con, "DELETE FROM tbl_costing_material WHERE Id = '$c'")or die(mysqli_error($con));
	}
}


// Costing Equipment
if(isset($_POST['btn-equipment'])){
	
	mysqli_query($con, "INSERT INTO tbl_costing_equipment (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));
	
}
	
if(isset($_POST['equipment-c'])){
	
	for($i=0;$i<count($_POST['equipment-c']);$i++){
		
		$id = $_POST['id-c-e'][$i];
		$desc = $_POST['equipment-c'][$i];
		$price = $_POST['equipment-price-c'][$i];
		
		mysqli_query($con, "UPDATE tbl_costing_equipment SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($con));
		
	}
}

if(isset($_POST['delete-e-c'])){
	
	$id = $_POST['delete-e-c'];
	
	foreach($id as $c){
		
		mysqli_query($con, "DELETE FROM tbl_costing_equipment WHERE Id = '$c'")or die(mysqli_error($con));
	}
}

// Costing Labour
if(isset($_POST['btn-labour'])){
	
	mysqli_query($con, "INSERT INTO tbl_costing_labour (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));
	
}
	
if(isset($_POST['labour-c'])){
	
	for($i=0;$i<count($_POST['labour-c']);$i++){
		
		$id = $_POST['id-c-l'][$i];
		$desc = $_POST['labour-c'][$i];
		$price = $_POST['labour-price-c'][$i];
		
		mysqli_query($con, "UPDATE tbl_costing_labour SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($con));
		
	}
}

if(isset($_POST['delete-l-c'])){
	
	$id = $_POST['delete-l-c'];
	
	foreach($id as $c){
		
		mysqli_query($con, "DELETE FROM tbl_costing_labour WHERE Id = '$c'")or die(mysqli_error($con));
	}
}

// Costing Outsource
if(isset($_POST['btn-outsource'])){
	
	mysqli_query($con, "INSERT INTO tbl_costing_outsourcing (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));
	
}
	
if(isset($_POST['outsource-c'])){
	
	for($i=0;$i<count($_POST['outsource-c']);$i++){
		
		$id = $_POST['id-c-o'][$i];
		$desc = $_POST['outsource-c'][$i];
		$price = $_POST['outsource-price-c'][$i];
		
		mysqli_query($con, "UPDATE tbl_costing_outsourcing SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($con));
		
	}
}

if(isset($_POST['delete-o-c'])){
	
	$id = $_POST['delete-o-c'];
	
	foreach($id as $c){
		
		mysqli_query($con, "DELETE FROM tbl_costing_outsourcing WHERE Id = '$c'")or die(mysqli_error($con));
	}
}

// HES
if(isset($_POST['btn-hes'])){
	
	mysqli_query($con, "INSERT INTO tbl_qs_hes (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));
	
}

if(isset($_POST['delete-hes'])){
	
	$id = $_POST['delete-hes'];
	
	foreach($id as $c){
		
		mysqli_query($con, "DELETE FROM tbl_qs_hes WHERE Id = '$c'")or die(mysqli_error($con));
	}
}

// Quote Equipment
if(isset($_POST['btn-e'])){
	
	mysqli_query($con, "INSERT INTO tbl_qs_equipment (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));
	
}

if(isset($_POST['delete-e'])){
	
	$id = $_POST['delete-e'];
	
	foreach($id as $c){
		
		mysqli_query($con, "DELETE FROM tbl_qs_equipment WHERE Id = '$c'")or die(mysqli_error($con));
	}
}

$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' ORDER BY Id ASC LIMIT 1") or die(mysql_error($con));
$row = mysqli_fetch_array($query);

$companyid = $row['CompanyId'];
$siteid = $row['SiteId'];


// Quote Labour
if(isset($_POST['btn-add-labour'])){
	
	mysql_query("INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Labour) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysql_error());
}

// Quote Material
if(isset($_POST['btn-add-material'])){
	
	mysql_query("INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Material) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysql_error());
}

// Quote Transport
if(isset($_POST['btn-add-transport'])){
	
	mysql_query("INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Transport) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysql_error());
}

// Costing Transport

$query = mysqli_query($con, "SELECT * FROM tbl_costing_transport WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$numrows = mysqli_num_rows($query);

if($_POST){
	
	$distance = $_POST['return'];
	$trips = $_POST['trips'];
	$days = $_POST['days'];
	$subsistence = $_POST['subsistence'];
	
	if($numrows == 0){
		
		mysqli_query($con, "INSERT INTO tbl_costing_transport (QuoteNo,ReturnDistance,Trips) VALUES ('$quoteno','$distance','$trips')")or die(mysqli_error($con));
	
	} else {
		
		mysqli_query($con, "UPDATE tbl_costing_transport SET QuoteNo = '$quoteno', ReturnDistance = '$distance', Trips = '$trips' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	
	}
	
	mysqli_query($con, "UPDATE tbl_costing_labour SET Days = '$days', Subsistence = '$subsistence' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
}
		
///////////////////////////////
/// Labour Rates Drop Down ///
/////////////////////////////

$query_dd = mysqli_query($con, "SELECT * FROM tbl_costing_labour_rates")or die(mysql_error($con));

// Set Cookie after costing to make the quotation visible.
if(isset($_POST['quote'])){
	
	header('Location: costing-quote.php?Id='. $_GET['Id']);
	
}

if(isset($_POST['company'])){

$company_id = $_POST['company'];
$site_id = $_POST['site'];
$province = $_POST['province'];

mysql_query("UPDATE tbl_qs SET CompanyId = '$company_id', SiteId = '$site_id', AreaId = '$province' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
}

if(isset($_POST['jobdescription'])){
$jd = $_POST['jobdescription'];
mysql_query("UPDATE tbl_qs SET JobDescription = '$jd' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
}

if(isset($_POST['att'])){
$att = $_POST['att'];
mysql_query("UPDATE tbl_qs SET Attention = '$att' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
}

if(isset($_POST['notes'])){
$notes = $_POST['notes'];
mysql_query("UPDATE tbl_qs SET Notes = '$notes' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
}

if(isset($_POST['internal_notes'])){
$internal_notes = $_POST['internal_notes'];
mysql_query("UPDATE tbl_qs SET InternalNotes = '$internal_notes' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
}

if(isset($_POST['fmc'])){
$fmcnumber = $_POST['fmc'];
mysql_query("UPDATE tbl_qs SET FMC = '$fmcnumber' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
}

if(isset($_POST['delete_l'])){
$delete = $_POST['delete_l'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_qs WHERE Id = '$c'") or die(mysql_error());
}}
if(isset($_POST['delete_m'])){
$delete = $_POST['delete_m'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_qs WHERE Id = '$c'") or die(mysql_error());
}}
if(isset($_POST['delete_t'])){
$delete = $_POST['delete_t'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_qs WHERE Id = '$c'") or die(mysql_error());
}}

if(isset($_POST['date'])){
$date = $_POST['date'];
mysql_query("UPDATE tbl_qs SET Date = '$date' WHERE QuoteNo = '$quoteno'") or die(mysql_error());
}

$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' ORDER BY Id ASC LIMIT 1") or die(mysql_error());
$row = mysql_fetch_array($query);

$companyid = $row['CompanyId'];
$siteid = $row['SiteId'];

if($_POST['labour_row'] >= 1){

$quoteno = $_GET['Id'];
$rows = $_POST['labour_row'];

for($i=0;$i<$rows;$i++){

mysql_query("INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Labour) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysql_error());
}}

if($_POST['material_row'] >= 1){

$quoteno = $_GET['Id'];
$rows = $_POST['material_row'];

for($i=0;$i<$rows;$i++){

mysql_query("INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Material) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysql_error());
}}

if($_POST['transport_row'] >= 1){

$quoteno = $_GET['Id'];
$rows = $_POST['transport_row'];

for($i=0;$i<$rows;$i++){

mysql_query("INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Transport) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysql_error());
}}

$quoteno = $_GET['Id'];

if(isset($_GET['update'])){
	
	// Upadate Health and Safety
	for($i=0;$i<count($_POST['desc-hes']);$i++){
		
		$id = $_POST['id-hes'][$i];
		$quoteno = $_GET['Id'];
		$description = $_POST['desc-hes'][$i];
		$unit = $_POST['unit-hes'][$i];
		$qty = $_POST['qty-hes'][$i];
		$price = $_POST['price-hes'][$i];
		$total = $price * $qty;
		
		mysqli_query($con, "UPDATE tbl_qs_hes SET Description = '$description', Unit = '$unit', Qty = '$qty', UnitPrice = '$price', Total = '$total' WHERE Id = '$id'")or die(mysqli_error($con));
		
	}
	
	// Upadate Equipment / Machinery
	for($i=0;$i<count($_POST['desc-e']);$i++){
		
		$id = $_POST['id-e'][$i];
		$quoteno = $_GET['Id'];
		$description = $_POST['desc-e'][$i];
		$unit = $_POST['unit-e'][$i];
		$qty = $_POST['qty-e'][$i];
		$price = $_POST['price-e'][$i];
		$total = $price * $qty;
		
		mysqli_query($con, "UPDATE tbl_qs_equipment SET Description = '$description', Unit = '$unit', Qty = '$qty', UnitPrice = '$price', Total = '$total' WHERE Id = '$id'")or die(mysqli_error($con));
		
	}

$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);

$idl = $_POST['id_l'];
$quoteno = $_GET['Id'];
$labour_l = $_POST['labour'];
$unit_l = $_POST['unit_l'];
$qty_l = $_POST['qty_l'];
$price_l = $_POST['price_l'];

for($i=0;$i<$numrows;$i++){
$id = $idl[$i];
$labour = $labour_l[$i];
$unit = $unit_l[$i];
$qty = $qty_l[$i];
$price = $price_l[$i];
$total = $qty * $price;
mysql_query("UPDATE tbl_qs SET  Description = '$labour', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysql_error());
}

$idm = $_POST['id_m'];
$quoteno = $_GET['Id'];
$material_m = $_POST['material'];
$unit_m = $_POST['unit_m'];
$qty_m = $_POST['qty_m'];
$price_m = $_POST['price_m'];
for($i=0;$i<$numrows;$i++){
$id = $idm[$i];
$material = $material_m[$i];
$unit = $unit_m[$i];
$qty = $qty_m[$i];
$price = $price_m[$i];
$total = $qty * $price;

mysql_query("UPDATE tbl_qs SET  Description = '$material', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysql_error());
}

$idt = $_POST['id_t'];
$quoteno = $_GET['Id'];
$comment_t = $_POST['t_comment'];
$transport_t = $_POST['transport'];
$unit_t = $_POST['unit_t'];
$qty_t = $_POST['qty_t'];
$price_t = $_POST['price_t'];

for($i=0;$i<$numrows;$i++){
$id = $idt[$i];
$t_comment = $comment_t[$i];
$transport = $transport_t[$i];
$unit = $unit_t[$i];
$qty = $qty_t[$i];
$price = $price_t[$i];
$total = $transport * ($qty * $price) ;
mysql_query("UPDATE tbl_qs SET TransportComment = '$t_comment', Description = '$transport', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysql_error());
}}

$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'") or die(mysql_error());
while($row = mysql_fetch_array($query)){
$id = $row['Id'];
$qty = $row['Qty'];
$price = $row['Price'];
$labour_total = $qty * $price;
$vat = ($labour_total / 100) * 14;
mysql_query("UPDATE tbl_qs SET SubTotal = '$labour_total', VAT = '$vat' WHERE Id = '$id'") or die(mysql_error());
}
$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$labour_total = $row['SUM(Total1)'];

$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysql_error());
while($row = mysql_fetch_array($query)){
$id = $row['Id'];
$qty = $row['Qty'];
$price = $row['Price'];
$material_total = $qty * $price;
$vat = ($material_total / 100) * 14;
mysql_query("UPDATE tbl_qs SET SubTotal = '$material_total', VAT = '$vat' WHERE Id = '$id'") or die(mysql_error());
}
$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$material_total = $row['SUM(Total1)'];

$quoteno = $_GET['Id'];

$query = mysql_query("SELECT CompanyId FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysql_error());
$row = mysql_fetch_array($query);

echo $row_Recordset1['Rate'];
$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysql_error());
while($row = mysql_fetch_array($query)){
$transport_total = $row['Description'] * ($row['Qty'] * $row['Price']);
$vat = ($transport_total / 100) * 14;
mysql_query("UPDATE tbl_qs SET SubTotal = '$transport_total', VAT = '$vat' WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysql_error());
}
$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$transport_total = $row['SUM(Total1)'];

if(isset($_POST['date'])){
$date = $_POST['date'];
mysql_query("UPDATE tbl_qs SET Date = '$date' WHERE QuoteNo = '$quoteno'") or die(mysql_error());
}

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT SUM(Total1), SUM(VAT) FROM tbl_qs WHERE QuoteNo = %s GROUP BY QuoteNo", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$total = $row_Recordset4['SUM(Total1)'];
$vat = $row_Recordset4['SUM(VAT)'];
$total = $total + $vat;
$quoteno = $_GET['Id'];

mysql_query("UPDATE tbl_qs SET Total = '$total' WHERE QuoteNo = '$quoteno'") or die(mysql_error());

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_qs WHERE QuoteNo = '%s'", $colname_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$quoteno = $_GET['Id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Name, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_qs.Id, tbl_qs.SiteId, tbl_qs.QuoteNo, tbl_qs.AreaId, tbl_qs.Date, tbl_qs.JobDescription, tbl_qs.Attention FROM ((tbl_qs LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_qs.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo = '$quoteno' ORDER BY Id ASC LIMIT 1";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$KTColParam1_Recordset3 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset3 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_qs.CompanyId, tbl_qs.QuoteNo, tbl_companies.* FROM (tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo=%s ", $KTColParam1_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$today = date('Y-m-j');
$submitted = $_POST['submitted'];

// Send to awaiting approval
if(isset($_POST['approval'])){
	
	mysql_query("UPDATE tbl_qs SET Status = '0', Days = '$today' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
	
	header('Location: select_q_pending.php');

}

// Send to qued
if(isset($_POST['qued'])){
	
	mysql_query("UPDATE tbl_qs SET Status = '4', Days = '$today' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
	
	header('Location: qs-qued.php');

}

// Send to outbox
if(isset($_POST['outbox'])){
	
	mysql_query("UPDATE tbl_qs SET Status = '1', Days = '$today' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
	
	$query = mysql_query("SELECT * FROM tbl_sent_quotes WHERE QuoteNo = '$quoteno'")or die(mysql_error());
	$numrows = mysql_num_rows($query);
	
	if($numrows == 0){
		
		$company = $row_Recordset5['Name'];
		$site = addslashes($row_Recordset5['Name_1']);
		$quote_no = $_GET['Id'];
		$document = 'Seavest Quotation '. $_GET['Id'] .'.pdf';
		$date = date('d M Y H:i:s');
		mysql_query("INSERT INTO tbl_sent_quotes (CompanyId,SiteId,QuoteNo,PDF,DateSent) VALUES ('$company','$site','$quote_no','$document','$date')")or die(mysql_error());
	}

	
	header('Location: fpdf16/pdf_quotation.php?Id='.$quoteno);
}
?>
<?php
mysql_select_db($database_inv, $inv);
$query_rs_companies = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$rs_companies = mysql_query($query_rs_companies, $inv) or die(mysql_error());
$row_rs_companies = mysql_fetch_assoc($rs_companies);
$totalRows_rs_companies = mysql_num_rows($rs_companies);

mysql_select_db($database_inv, $inv);
$query_rs_sites = "SELECT * FROM tbl_sites ORDER BY Name ASC";
$rs_sites = mysql_query($query_rs_sites, $inv) or die(mysql_error());
$row_rs_sites = mysql_fetch_assoc($rs_sites);
$totalRows_rs_sites = mysql_num_rows($rs_sites);

mysql_select_db($database_inv, $inv);
$query_rs_area_q = "SELECT * FROM tbl_areas ORDER BY Area ASC";
$rs_area_q = mysql_query($query_rs_area_q, $inv) or die(mysql_error());
$row_rs_area_q = mysql_fetch_assoc($rs_area_q);
$totalRows_rs_area_q = mysql_num_rows($rs_area_q);

////////////////////////
/// Transport Query ///
//////////////////////

$query_transport = mysqli_query($con, "SELECT * FROM tbl_costing_transport WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_transport = mysqli_fetch_array($query_transport);

$query_labour = mysqli_query($con, "SELECT * FROM tbl_costing_labour WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_labour = mysqli_fetch_array($query_labour);

$query_sum_hes = mysqli_query($con, "SELECT SUM(Total) AS Total_1 FROM tbl_qs_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_hes = mysqli_fetch_array($query_sum_hes);
		
$query_sum_e = mysqli_query($con, "SELECT SUM(Total) AS Total_1 FROM tbl_qs_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_e = mysqli_fetch_array($query_sum_e);
		
$query_sum_m = mysqli_query($con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_m = mysqli_fetch_array($query_sum_m);
		
$query_sum_safety = mysqli_query($con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_safety = mysqli_fetch_array($query_sum_safety);
		
$query_sum_outsourcing = mysqli_query($con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
$row_sum_outsourcing = mysqli_fetch_array($query_sum_outsourcing);


?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<style type="text/css">
<!--
.quotation_to {
	font-size: 18px;
	color:#000 !important;
}

.tarea3 {
    width:200px;
	font-weight:bold;
}
.tarea31 {    width:200px;
	font-weight:bold;
}

-->
</style>
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_rs_sites = new WDG_JsRecordset("rs_sites");
echo $jsObject_rs_sites->getOutput();
//end JSRecordset
?>
<script type="text/javascript" src="Calendar.js"></script>
<link href="SpryAssets/SpryCollapsiblePanel-jc.css" rel="stylesheet" type="text/css">
<script src="SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
        </tr>
          
		</table>
        <div style="margin-left:30px">
		  <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3">
<table width="100%" border="0" cellpadding="2" cellspacing="0" class="td-header">
                <tr>
                  <td nowrap><form name="form2" method="post" action="beta.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
                    &nbsp;Labour
                    <select name="labour_row" class="tarea-white" id="labour_row">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    &nbsp; Material
                    <select name="material_row" class="tarea-white" id="material_row">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    &nbsp; Transport
                    <select name="transport_row" class="tarea-white" id="transport_row">
                    <?php for($i=0;$i<=100;$i++) { ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                    </select>
                    <input name="Submit2" type="submit" class="btn-blue-generic" id="Submit2" value="Add Rows">
                    &nbsp;&nbsp;
                  </form></td>
                  <td nowrap><form name="form4" method="post" action="delete.php">
                      <input name="quoteno" type="hidden" id="quoteno" value="<?php echo $_GET['Id']; ?>">
                      <input name="Submit4" type="submit" class="btn-red-generic" id="Submit4" value="Delete">
                  </form></td>
                  <td nowrap><form name="form7" method="post" action="inv2quote.php?Id=<?php echo $_GET['Id']; ?>">
                    <input name="Submit7" type="submit" class="btn-blue-generic" id="Submit7" value="Invoice">
                  </form></td>
                  <td nowrap><form action="photos.php?Id=<?php echo $_GET['Id']; ?>" method="post" name="form6">
                    <input name="Submit6" type="submit" class="btn-blue-generic" id="Submit6" value="Photos">
                  </form></td>
                  <td nowrap><form name="form3" method="post" action="report_check.php?Id=<?php echo $_GET['Id']; ?>">
                    <input name="Submit8" type="submit" class="btn-blue-generic" id="Submit8" value="Report">
                  </form>                  </td>
                  <td nowrap><form name="form5" method="post" action="jc_new.php?quoteno=<?php echo $_GET['Id']; ?>">
                    <input name="jobno" type="text" class="tarea-white" id="jobno" style="cursor:text" value="<?php echo $row_Recordset2['FMC']; ?>" size="10">
                    <input name="Submit5" type="submit" class="btn-blue-generic" id="Submit5" value="Jobcard">
                  </form></td>
                  </tr>
              </table>            </td>
            </tr>
			</table>
          <form name="form1" method="post" action="beta.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $quoteno; ?>&update">
			<table width="100%">
          <tr>
            <td colspan="3" bordercolor="#FFFFFF" class="combo">
              <table width="100%" border="0" cellspacing="0" cellpadding="3">
                  <tr>
                    <td width="50%" valign="top">
<div id="list-brdr">
                          <table width="430" border="0" cellpadding="3" cellspacing="1">
                            <tr>
                              <td class="odd"><div class="quotation_to">&nbsp;QUOTATION TO:</div></td>
                              </tr>
                            <tr>
                              <td height="90" nowrap bordercolor="#FFFFFF" class="even"><div class="combo" style="padding:3px; color:#000">
                                <?php if(isset($_GET['new'])){ ?>
                                <select name="company" class="tarea-white-q" id="company" style="font-weight:bold">
                                  <?php
do {  
?>
                                  <option value="<?php echo $row_rs_companies['Id']?>"<?php if (!(strcmp($row_rs_companies['Id'], $row_Recordset3['CompanyId']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_companies['Name']?></option>
                                  <?php
} while ($row_rs_companies = mysql_fetch_assoc($rs_companies));
  $rows = mysql_num_rows($rs_companies);
  if($rows > 0) {
      mysql_data_seek($rs_companies, 0);
	  $row_rs_companies = mysql_fetch_assoc($rs_companies);
  }
?>
                                  </select>
                                <?php } else {
echo $row_Recordset5['Name'];
} ?>
                                <br>
                                <?php echo nl2br(KT_escapeAttribute($row_Recordset3['Address'])); ?></div></td>
                              </tr>
                            </table>
                          </div>                    </td>
                    <td width="50%" valign="top"><table border="0" align="right" cellpadding="0" cellspacing="0">
                      <tr>
                        <td><div id="list-brdr">
                          <table width="300" border="0" align="right" cellpadding="3" cellspacing="1">
                            <tr>
                              <td width="120" nowrap class="odd">&nbsp;Date:</td>
                              <td width="200" class="odd"><input name="date" class="tarea-white" id="date" style="cursor:text" value="<?php echo $row_Recordset5['Date']; ?>" size="10" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" wdg:readonly="true"></td>
                              </tr>
                            <tr>
                              <td width="120" nowrap class="even">&nbsp;Quotation Number: </td>
                              <td width="200" class="even">&nbsp;<?php echo $row_Recordset5['QuoteNo']; ?></td>
                              </tr>
                            <tr>
                              <td nowrap class="odd">&nbsp;Reference:</td>
                              <td width="200" class="odd"><input name="fmc" type="text" class="tarea-white-q" id="fmc" style="cursor:text" value="<?php fmc($quoteno); ?>"></td>
                              </tr>
                            <?php if(isset($_GET['new'])){ ?>
                            <tr>
                              <td nowrap class="even">&nbsp;Province:</td>
                              <td class="even"><select name="province" class="tarea-white-q" id="province">
                                <?php
do {  
?>
                                <option value="<?php echo $row_rs_area_q['Id']?>"<?php if (!(strcmp($row_rs_area_q['Id'], $row_Recordset5['AreaId']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_area_q['Area']?></option>
                                <?php
} while ($row_rs_area_q = mysql_fetch_assoc($rs_area_q));
  $rows = mysql_num_rows($rs_area_q);
  if($rows > 0) {
      mysql_data_seek($rs_area_q, 0);
	  $row_rs_area_q = mysql_fetch_assoc($rs_area_q);
  }
?>
                                </select></td>
                              </tr>
                            <tr>
                              <?php } ?>
                              <td width="120" nowrap class="odd">&nbsp;Site / Customer:</td>
                              <td width="200" class="odd"><?php if(isset($_GET['new'])){ ?>
                                <select name="site" class="tarea-white-q" style="font-weight:bold; color:#000" id="site" wdg:subtype="DependentDropdown" wdg:type="widget" wdg:recordset="rs_sites" wdg:displayfield="Name" wdg:valuefield="Id" wdg:fkey="Company" wdg:triggerobject="company" wdg:selected="<?php echo $row_Recordset5['SiteId'] ?>">
                                  </select>
                                <?php } else {
						    echo $row_Recordset5['Name_1']; 
							} ?></td>
                              </tr>
                            <tr>
                              <td width="120" nowrap class="even">&nbsp;Address:</td>
                              <td width="200" class="even">&nbsp;<?php echo $row_Recordset5['Address']; ?></td>
                              </tr>
                            <tr>
                              <td width="120" nowrap class="odd">&nbsp;Description:</td>
                              <td width="200" class="odd"><input name="jobdescription" type="text" class="tarea-white-q" id="jobdescription" style="" value="<?php echo $row_Recordset5['JobDescription']; ?>"></td>
                              </tr>
                            <tr>
                              <td width="120" nowrap class="even">&nbsp;Att:</td>
                              <td width="200" class="even"><input name="att" type="text" class="tarea-white-q" id="att" style="cursor:text; font-size:12px" value="<?php echo $row_Recordset5['Attention']; ?>"></td>
                              </tr>
                            </table>
                          </div></td>
                        </tr>
                      </table></td>
                  </tr>
                    </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="td-header">Overview</td>
                  </tr>
              </table>
			  <div style="border:solid 1px #A6CAF0; margin-top: 5px; margin-bottpx;margin-bottom: 5px;">
<?php
$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysql_error());
$row = mysql_fetch_array($query);
?>
			  <textarea name="notes" class="tarea-jc" id="notes" style="width:759px; border:none"><?php echo $row['Notes']; ?></textarea>
			  </div>
              <div id="CollapsiblePanel1" class="CollapsiblePanel" style="background-color:none; padding:0px; margin-bottom:5px; border:none">
              <div class="CollapsiblePanelTab" tabindex="0"  style="background-color:none; padding:0px; border: none">
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="tb_border2">Internal Notes </td>
                  </tr>
              </table>
</div>
              <div class="CollapsiblePanelContent">
              			  <div style="border:solid 1px #A6CAF0; margin-top: 5px; margin-bottpx;margin-bottom: 5px;">
			    <textarea name="internal_notes" class="tarea-jc" id="internal_notes" style="width:759px; border:none; color:#620000"><?php echo $row['InternalNotes']; ?></textarea>
			  </div>
              </div>
              </div>
              <div id="CollapsiblePanel2" class="CollapsiblePane2" style="background-color:none; padding:0px; margin-bottom:5px; border:none">
              <div class="CollapsiblePanelTab" tabindex="0" style="background-color:none; padding:0px; margin-bottom:5px; border:none">
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="tb_border2">Costing</td>
                </tr>
              </table>
              </div>
              <div class="CollapsiblePanelContent">
			  <div style="border:solid 1px #A6CAF0; margin-top: 5px; margin-bottpx;margin-bottom: 5px; border-bottom: solid 5px #F00">
			    <table width="100%" border="0" cellspacing="1" cellpadding="4">
			    <tr>
			      <td align="right"><table width="100%" border="0" cellpadding="4" cellspacing="1">
			        <tr>
			          <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
			            <tr>
			              <td>Health &amp; Safety</td>
			              <td align="right"><input name="btn-hes" type="submit" class="btn-add-new" id="btn-hes" value=""></td>
			              </tr>
			            </table></td>
			          </tr>
			        <?php
					
					$_SESSION['totals'] = '';
						
						$query = mysqli_query($con, "SELECT * FROM tbl_costing_hes WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
						$numrows = mysqli_num_rows($query);
						while($row = mysqli_fetch_array($query)){
							
					?>
			        <tr>
			          <td colspan="2"><input name="hes-c[]" type="text" class="tarea-100per" id="hes-c[]" value="<?php echo $row['Description']; ?>"></td>
			          <td width="85" align="right"><input name="hes-price-c[]" type="text" class="tarea-100per" id="hes-price-c[]" value="<?php echo $row['Price']; ?>" style="text-align:right"></td>
			          <td width="20" align="center"><input type="hidden" name="id-c-hes[]" id="id-c-hes[]" value="<?php echo $row['Id']; ?>">
			            <input type="checkbox" name="delete-hes-c[]" id="delete-hes-c[]" value="<?php echo $row['Id']; ?>"></td>
			          </tr>
			        <?php } 
				  
				  $query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
				  $row2 = mysqli_fetch_array($query_material2);
							
				  $_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
				  ?>
			        <tr>
			          <td colspan="2" align="right">&nbsp;</td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>"></td>
			          <td align="center">&nbsp;</td>
			          </tr>
			        <tr>
			          <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
			            <tr>
			              <td>Material</td>
			              <td align="right"><input name="btn-material" type="submit" class="btn-add-new" id="btn-material" value=""></td>
			              </tr>
			            </table></td>
			          </tr>
			        <?php
						
						$query = mysqli_query($con, "SELECT * FROM tbl_costing_material WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
						$numrows = mysqli_num_rows($query);
						while($row = mysqli_fetch_array($query)){
							
					?>
			        <tr>
			          <td colspan="2"><input name="material-c[]" type="text" class="tarea-100per" id="material-c[]" value="<?php echo $row['Description']; ?>"></td>
			          <td width="85" align="right"><input name="material-price-c[]" type="text" class="tarea-100per" id="material-price-c[]" value="<?php echo $row['Price']; ?>" style="text-align:right"></td>
			          <td width="20" align="center"><input type="hidden" name="id-c-m[]" id="id-c-m[]" value="<?php echo $row['Id']; ?>">
			            <input type="checkbox" name="delete-m-c[]" id="delete-m-c[]" value="<?php echo $row['Id']; ?>"></td>
			          </tr>
			        <?php } 
				  
				  $query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_material WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
				  $row2 = mysqli_fetch_array($query_material2);
							
				  $_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
				  ?>
			        <tr>
			          <td colspan="2" align="right">&nbsp;</td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>"></td>
			          <td align="center">&nbsp;</td>
			          </tr>
			        <tr>
			          <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
			            <tr>
			              <td>Equipment / Machinery</td>
			              <td align="right"><input name="btn-equipment" type="submit" class="btn-add-new" id="btn-equipment" value=""></td>
			              </tr>
			            </table></td>
			          </tr>
			        <?php
						$query = mysqli_query($con, "SELECT * FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
						$numrows = mysqli_num_rows($query);
						while($row = mysqli_fetch_array($query)){
							
					?>
			        <tr>
			          <td colspan="2"><input name="equipment-c[]" type="text" class="tarea-100per" id="equipment-c[]" value="<?php echo $row['Description']; ?>"></td>
			          <td width="85"><input name="equipment-price-c[]" type="text" class="tarea-100per" id="equipment-price-c[]" value="<?php echo $row['Price']; ?>" style="text-align:right"></td>
			          <td width="20" align="center"><input type="hidden" name="id-c-e[]" id="id-c-e[]" value="<?php echo $row['Id']; ?>">
			            <input type="checkbox" name="delete-e-c[]" id="delete-e-c[]" value="<?php echo $row['Id']; ?>"></td>
			          </tr>
			        <?php } 
					  
					  $query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
					  $row2 = mysqli_fetch_array($query_material2);
							
					  $_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
					  ?>
			        <tr>
			          <td colspan="2" align="right">&nbsp;</td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>"></td>
			          <td width="20" align="center">&nbsp;</td>
			          </tr>
			        <tr>
			          <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
			            <tr>
			              <td>Labour</td>
			              <td align="right">&nbsp;</td>
			              </tr>
			            </table></td>
			          </tr>
			        <?php
						$query = mysqli_query($con, "SELECT * FROM tbl_costing_labour WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
						$numrows = mysqli_num_rows($query);
						while($row = mysqli_fetch_array($query)){
							
					?>
			        <tr>
			          <td colspan="2"><input name="labour-c[]" type="text" class="tarea-100per" id="labour-c[]" value="<?php echo $row['Description']; ?>"></td>
			          <td width="85"><select name="labour-price-c[]" class="tarea-100per" id="labour-price-c[]">
			            <option value="">Select one...</option>
			            <?php 
						$query_dd = mysqli_query($con, "SELECT * FROM tbl_costing_labour_rates")or die(mysql_error($con));
						while($row_costing_dd = mysqli_fetch_array($query_dd)){ ?>
			            <option value="<?php echo $row_costing_dd['Rate']; ?>" <?php if($row_costing_dd['Rate'] == $row['Price']){ ?>selected="selected"<?php } ?>><?php echo $row_costing_dd['Name']; ?></option>
			            <?php } ?>
			            </select></td>
			          <td width="20" align="center"><input type="hidden" name="id-c-l[]" id="id-c-l[]" value="<?php echo $row['Id']; ?>"></td>
			          </tr>
			        <?php } ?>
                    <?php
					$query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_labour WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
					$row2 = mysqli_fetch_array($query_material2);
							
					$_SESSION['totals'] = ($row2['Total'] * $row_labour['Days']) + $_SESSION['totals'];
					?>
			        <tr>
			          <td colspan="2" align="right">&nbsp;</td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format(($row2['Total'] * $row_labour['Days']), 2); ?>"></td>
			          <td width="20" align="center">&nbsp;</td>
			          </tr>
			        <tr>
			          <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
			            <tr>
			              <td>Outsourced Service</td>
			              <td align="right"><input name="btn-outsource" type="submit" class="btn-add-new" id="btn-outsource" value=""></td>
			              </tr>
			            </table></td>
			          </tr>
			        <?php
						$query = mysqli_query($con, "SELECT * FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($con));
						$numrows = mysqli_num_rows($query);
						while($row = mysqli_fetch_array($query)){
					?>
			        <tr>
			          <td colspan="2"><input name="outsource-c[]" type="text" class="tarea-100per" id="outsource-c[]" value="<?php echo $row['Description']; ?>"></td>
			          <td width="85"><input name="outsource-price-c[]" type="text" class="tarea-100per" id="outsource-price-c[]" value="<?php echo $row['Price']; ?>" style="text-align:right"></td>
			          <td width="20" align="center"><input type="hidden" name="id-c-o[]" id="id-c-o[]" value="<?php echo $row['Id']; ?>">
			            <input type="checkbox" name="delete-o-c[]" id="delete-o-c[]" value="<?php echo $row['Id']; ?>"></td>
			          </tr>
			        <?php } ?>
                    <?php
					$query_material2 = mysqli_query($con, "SELECT SUM(Price) AS Total FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
					$row2 = mysqli_fetch_array($query_material2);
							
					$_SESSION['totals'] = $row2['Total'] + $_SESSION['totals'];
					?>
			        <tr>
			          <td colspan="2" align="right">&nbsp;</td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row2['Total'],2); ?>"></td>
			          <td width="20" align="center">&nbsp;</td>
			          </tr>
			          <?php
					$costing_transport = $row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate'];
					$costing_labour = $row_labour['Days'] * $row_labour['Price'];
					$costing_subsistence  = $row_labour['Subsistence'] * $row_labour['Days'];
					$costing_material = $row_sum_m['Total_1'];
					$costing_hes = $row_sum_safety['Total_1'];
					$costing_equipment = $row_sum_e['Total_1'];
					$costing_outsourcing = $row_sum_outsourcing['Total_1'];
					
					$admin_fee = ($_SESSION['totals'] + $costing_subsistence + ($row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate'])) * 0.1;
					
					?>
			        <tr>
			          <td colspan="2" align="right">&nbsp;</td>
			          <td width="85" align="right">&nbsp;</td>
			          <td width="20" align="center">&nbsp;</td>
			          </tr>
			        <tr>
			          <td align="right" class="blue-generic"><strong>Return Distance</strong></td>
			          <td width="85" align="right"><input name="return" type="text" class="tarea-100per" id="return" value="<?php echo $row_transport['ReturnDistance']; ?>"></td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate'], 2); ?>"></td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        <tr>
			          <td align="right" class="blue-generic"><strong>Trips</strong></td>
			          <td width="85" align="right"><input name="trips" type="text" class="tarea-100per" id="trips" value="<?php echo $row_transport['Trips']; ?>"></td>
			          <td width="85" align="right">&nbsp;</td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        <tr>
			          <td align="right" class="blue-generic"><strong>Days On Site</strong></td>
			          <td width="85" align="right"><input name="days" type="text" class="tarea-100per" id="days" value="<?php echo $row_labour['Days']; ?>"></td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row_labour['Days'] * $row_labour['Price'], 2); ?>"></td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        <tr>
			          <td align="right" class="blue-generic"><strong>Subsistence / Night</strong></td>
			          <td width="85" align="right"><input name="subsistence" type="text" class="tarea-100per" id="subsistence" value="<?php echo $row_labour['Subsistence']; ?>"></td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row_labour['Subsistence'] * $row_labour['Days'], 2); ?>"></td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        <tr>
			          <td align="right" class="blue-generic"><strong>Admin Fee</strong></td>
			          <td width="85" align="right"><input name="admin-fee" type="text" class="tarea-100per" id="admin-fee" value="10%" readonly></td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($admin_fee, 2); ?>"></td>
			          <td>&nbsp;</td>
			          </tr>
			        <tr>
			          <td align="right" class="blue-generic"><strong>Zone Charge</strong></td>
			          <td width="85" align="right"><input name="zone" type="text" class="tarea-100per" id="zone" value="<?php zone_charge($row_transport['Trips'], $row_transport['ReturnDistance'], $_SESSION['totals']); ?>" readonly></td>
			          <td width="85" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php zone_charge($row_transport['Trips'], $row_transport['ReturnDistance'], $_SESSION['totals']); ?>"></td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        <tr>
			          <td colspan="2">&nbsp;</td>
			          <td width="85" align="right" class="btn-red-generic">
                      <?php
					  $costing_total = $_SESSION['totals'] + $costing_subsistence + $_SESSION['zone-charge'] + $admin_fee + ($row_transport['ReturnDistance'] * $row_transport['Trips'] * $row_transport['Rate']); 
					  ?>
                      <input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold; color:#F00" value="R<?php echo number_format($costing_total, 2); ?>"></td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        <tr>
			          <td colspan="2">&nbsp;</td>
			          <td width="85" align="right" class="btn-red-generic">&nbsp;</td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        <tr>
			          <td colspan="3" align="right"><table border="0" cellspacing="0" cellpadding="0">
			            <tr>
			              <td><input name="quote" type="submit" class="btn-red-generic" id="quote" value="Continue To Quote"></td>
			              <td><input name="save" type="submit" class="btn-green-generic" id="save" value="Save"></td>
			              </tr>
			            </table></td>
			          <td width="20">&nbsp;</td>
			          </tr>
			        </table></td>
			      </tr>
			    </table>
			  </div>
              </div>
              </div>
              </td>
            </tr>
          <tr>
          <?php if(isset($_COOKIE['costing-'. $_GET['Id']])){ ?>
            <td colspan="3" class="combo_bold"><div style=" border:solid 1px #A6CAF0">
              <table width="100%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                  <td width="450" class="td-header">Description</td>
                  <td width="50" align="center" class="td-header">Unit</td>
                  <td width="50" align="center" class="td-header">Qty.</td>
                  <td width="75" align="center" class="td-header">Unit Price </td>
                  <td width="75" align="right" class="td-header">Total</td>
                  <td width="20" align="center" class="td-header">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
                    <tr>
                      <td>Health &amp; Safety Compliance</td>
                      <td align="right">&nbsp;</td>
                    </tr>
                  </table></td>
                  </tr>
                                    <?php
$query = "SELECT * FROM tbl_qs_hes WHERE QuoteNo = '$quoteno'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){
?>
                <tr>
                  <td width="450"><input name="desc-hes[]" type="text" class="tarea-100per" id="material" value="Risk assessment, safety documents, barricades and total health & safety compliance."></td>
                  <td width="50" align="center"><input name="unit-hes[]" type="text" class="tarea-100per" id="unit_m" value="<?php echo $row['Unit']; ?>" size="6"></td>
                  <td width="50" align="center"><input name="qty-hes[]" type="text" class="tarea-100per" id="qty_m" value="<?php echo $row['Qty']; ?>" size="6"></td>
                  <td width="75" align="center"><input name="price-hes[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row['UnitPrice']; ?>" size="11"></td>
                  <td width="75" align="center"><input name="total-hes[]" type="text" disabled="disabled" class="tarea-100per" id="total_m" value="<?php echo $row['Total']; ?>" size="7" style="text-align:right"></td>
                  <td width="20" align="center"><?php if($numrows >= 2){ ?>
                    <input name="delete-hes[]" type="checkbox" id="delete-hes[]" value="<?php echo $row['Id']; ?>">
                    <?php } ?>
                    <input name="id-hes[]" type="hidden" id="id-hes[]" value="<?php echo $row['Id']; ?>"></td>
                </tr>
				<?php } // close loop ?>
                <tr>
                  <td width="450">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="75" align="center">&nbsp;</td>
                  <td width="75" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo $row_sum_hes['Total_1']; ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="450">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="75" align="center">&nbsp;</td>
                  <td width="75" align="right">&nbsp;</td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
                    <tr>
                      <td>Labour</td>
                      <td align="right"><input name="btn-add-labour" type="submit" class="btn-add-new" id="btn-add-labour" value=""></td>
                    </tr>
                  </table></td>
                  </tr>
                  <?php
$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1' ORDER BY Id ASC";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){

?>
                <tr>
                  <td width="450" valign="bottom"><textarea name="labour[]" rows="5" class="tarea-100per" id="labour"><?php  echo $row['Description']; ?>
                  </textarea></td>
                  <td width="50" align="center" valign="bottom"><input name="unit_l[]" type="text" class="tarea-100per" id="unit_l" value="hours" size="6"></td>
                  <td width="50" align="center" valign="bottom"><input name="qty_l[]" type="text" class="tarea-100per" id="qty_l" value="<?php echo $row['Qty']; ?>" size="6"></td>
                  <td width="75" align="center" valign="bottom"><input name="price_l[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row['Price']; ?>" size="11"></td>
                  <td width="75" align="right" valign="bottom"><?php if($row['Parent'] != 1){ ?><input name="total_l[]" type="text" disabled="disabled" class="tarea-100per" id="total_l" value="<?php echo $row['Total1']; ?>" size="7" style="text-align:right"><?php } ?></td>
                  <td width="20" align="center" valign="bottom"><?php if($numrows >= 2){ ?>
                    <input name="delete_l[]" type="checkbox" id="delete_l[]" value="<?php echo $row['Id']; ?>">
                    <?php } ?>
                    <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>"></td>
                </tr>
                <?php } // close loop ?>
                <tr>
                  <td width="450">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="75" align="center">&nbsp;</td>
                  <td width="75" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo $labour_total; ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="450">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="75" align="center">&nbsp;</td>
                  <td width="75" align="right">&nbsp;</td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
                    <tr>
                      <td>Material</td>
                      <td align="right"><input name="btn-add-material" type="submit" class="btn-add-new" id="btn-add-material" value=""></td>
                    </tr>
                  </table></td>
                  </tr>
                  <?php
$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){
?>
                <tr>
                  <td width="450"><input name="material[]" type="text" class="tarea-100per" id="material" value="<?php echo $row['Description']; ?>"></td>
                  <td width="50" align="center"><input name="unit_m[]" type="text" class="tarea-100per" id="unit_m" value="<?php echo $row['Unit']; ?>" size="6"></td>
                  <td width="50" align="center"><input name="qty_m[]" type="text" class="tarea-100per" id="qty_m" value="<?php echo $row['Qty']; ?>" size="6"></td>
                  <td width="75" align="center"><input name="price_m[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row['Price']; ?>" size="11"></td>
                  <td align="right"><input name="total_m[]" type="text" disabled="disabled" class="tarea-100per" id="total_m" value="<?php echo $row['Total1']; ?>" size="7" style="text-align:right"></td>
                  <td width="20" align="center"><?php if($numrows >= 2){ ?>
                    <input name="delete_m[]" type="checkbox" id="delete_m[]" value="<?php echo $row['Id']; ?>">
                    <?php } ?>
                    <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>"></td>
                </tr>
                <?php } // close loop ?>
                <tr>
                  <td width="450" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="75" align="right">&nbsp;</td>
                  <td align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo $material_total; ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="6" align="right">&nbsp;</td>
                  </tr>
                <tr>
                  <td colspan="6"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
                    <tr>
                      <td>Equipment &amp; Machinery</td>
                      <td align="right"><input name="btn-e" type="submit" class="btn-add-new" id="btn-e" value=""></td>
                    </tr>
                  </table></td>
                  </tr>
<?php
$query = "SELECT * FROM tbl_qs_equipment WHERE QuoteNo = '$quoteno'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){
?>                <tr>
                  <td width="450"><input name="desc-e[]" type="text" class="tarea-100per" id="material" value="<?php echo $row['Description']; ?>"></td>
                  <td width="50" align="center"><input name="unit-e[]" type="text" class="tarea-100per" id="unit_m" value="<?php echo $row['Unit']; ?>" size="6"></td>
                  <td width="50" align="center"><input name="qty-e[]" type="text" class="tarea-100per" id="qty_m" value="<?php echo $row['Qty']; ?>" size="6"></td>
                  <td width="75" align="center"><input name="price-e[]" type="text" class="tarea-100per" id="Price" value="<?php echo $row['UnitPrice']; ?>" size="11"></td>
                  <td width="75" align="right"><input name="total-e[]" type="text" disabled="disabled" class="tarea-100per" id="total_m" value="<?php echo $row['Total']; ?>" size="7" style="text-align:right"></td>
                  <td width="20" align="center"><?php if($numrows >= 2){ ?>
                    <input name="delete-e[]" type="checkbox" id="delete-e[]" value="<?php echo $row['Id']; ?>">
                    <?php } ?>
                    <input name="id-e[]" type="hidden" id="id-e[]" value="<?php echo $row['Id']; ?>"></td>
                </tr>
                <?php } // close loop ?>
                <tr>
                  <td width="450">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="75" align="center">&nbsp;</td>
                  <td width="75" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo number_format($row_sum_e['Total_1'],2); ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="450">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="75" align="center">&nbsp;</td>
                  <td width="75" align="right">&nbsp;</td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="6" align="right"><table width="100%" border="0" cellpadding="2" cellspacing="3" class="td-mail">
                    <tr>
                      <td>Transport</td>
                      <td align="right"><input name="btn-add-transport" type="submit" class="btn-add-new" id="btn-add-transport" value=""></td>
                    </tr>
                  </table></td>
                  </tr>
                  <?php
$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){
?>
                <tr>
                  <td width="450" align="right"><input name="t_comment[]" type="text" class="tarea-100per" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>" size="85"></td>
                  <td width="50" align="right"><input name="unit_t[]" type="text" class="tarea-100per" id="unit_t" value="km" size="5"></td>
                  <td width="50" align="right"><input name="transport[]" type="text" class="tarea-100per" id="transport" size="5" value="<?php echo $row['Description']; ?>"></td>
                  <td width="75" align="right"><input name="qty_t[]" type="text" class="tarea-100per" style="width:42%; margin-right:10px" id="qty_t" value="<?php echo $row['Qty']; ?>" size="7">
                    <input name="price_t[]" type="text" class="tarea-100per" style="width:42%" id="price_t" value="<?php echo $row['Price']; ?>" size="6"></td>
                  <td width="75" align="right"><input name="total_t[]" type="text" disabled="disabled" class="tarea-100per" id="total_t" value="<?php echo $row['Total1']; ?>" size="7" style="text-align:right"></td>
                  <td width="20" align="center">
                    <?php if($numrows >= 2){ ?>
                    <input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
                    <?php } ?>
                    <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>"></td>
                </tr>
                <?php } // close loop ?>
                <tr>
                  <td width="450" align="right"><span class="combo_bold">&nbsp; </span></td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="75" align="right">&nbsp;</td>
                  <td width="75" align="right"><input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold" value="R<?php echo $transport_total; ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="450" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="75" align="right">&nbsp;</td>
                  <td width="75" align="right">&nbsp;</td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="450" rowspan="3" align="center">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="75" nowrap class="combo_bold">Sub Total:</td>
                  <td width="75" align="right" class="combo_bold">
                  <?php $subt = $row_Recordset4['SUM(Total1)'] + $row_sum_hes['Total_1'] + $row_sum_e['Total_1']; ?>
                  <input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold; color:#F00" value="R<?php echo number_format( $subt,2); ?>">
                    <input name="subtotal" type="hidden" id="subtotal" value="<?php echo $subt; ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="75" nowrap class="combo_bold">Vat: </td>
                  <td width="75" align="right" class="combo_bold">
                  <?php
				  	$vat_rate = getVatRate($con, date('Y-m-d H:i:s'));
				  	$vat = ($row_Recordset4['SUM(Total1)'] + $row_sum_hes['Total_1'] + $row_sum_e['Total_1']) * ($vat_rate / 100); 
				  ?>
                  <input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold; color:#F00" value="R<?php echo number_format($vat,2); ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="75" nowrap class="combo_bold">Total:</td>
                  <td width="75" align="right" nowrap class="combo_bold">
                  <input name="textfield" type="text" disabled class="tarea-100per" id="textfield" style="text-align:right; font-weight:bold; color:#F00" value="R<?php echo number_format($subt + $vat,2); ?>"></td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="450" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="50" align="right">&nbsp;</td>
                  <td width="75" nowrap class="combo_bold">&nbsp;</td>
                  <td width="75" align="right" nowrap class="combo_bold">&nbsp;</td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td width="450" align="right">&nbsp;</td>
                  <td colspan="3" align="right" class="btn-red-generic">&nbsp;</td>
                  <td width="75" nowrap class="btn-red-generic">&nbsp;</td>
                  <td width="20" align="center">&nbsp;</td>
                </tr>
              </table>
              <table width="100%" border="0" cellpadding="2" cellspacing="3" bordercolor="#FFFFFF">
                <tr>
                  <td align="right">
                        <table border="0" align="right" cellpadding="0" cellspacing="0">
                          <tr>
                            <td align="right" valign="bottom" class="combo">&nbsp;</td>
                            <td align="right" valign="bottom" class="combo">&nbsp;</td>
                            <td width="20" align="right" valign="bottom" class="combo">&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="right" valign="bottom" class="combo">
                            <input name="button2" type="submit" class="combo_bold" id="button2" value="<?php echo round((($subt - $costing_total) / $subt) * 100); ?>"></td>
                            <td align="right" valign="bottom" class="combo">&nbsp;
                              <input name="Submit3" type="submit" class="btn-green-generic" id="Submit3" value="Save">
                              <?php
$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysql_error());
$row = mysql_fetch_array($query);

$status = $row['Status'];

// Awaiting approval
if($status == 0){
?>
  <input name="qued" type="submit" class="btn-red-generic" id="qued" value="Qued">
  <input name="outbox" type="submit" class="btn-red-generic" id="outbox" value="Outbox">
  <?php 
// Qued
} if($status == 4){ ?>
  <input name="approval" type="submit" class="btn-red-generic" id="approval" value="Awaiting Approval">
  <?php }  ?> 
                            </td>
                            <td width="20" align="right" valign="bottom" class="combo">&nbsp;</td>
                            </tr>
                        </table>
                          </td>
                  </tr>
              </table>
            </div>			  </td>
          <?php } ?>
          </tr>
        </table>   
		            </form> 
        </div>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
<script type="text/javascript">
<!--
<?php 
if(isset($_POST['save']) || isset($_POST['btn-material']) || isset($_POST['btn-equipment']) || isset($_POST['btn-outsourcing'])){
	
	$costing = 'true';
	
} else {
	
	$costing  = 'false';
}
?>

var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", { contentIsOpen: false });
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", { contentIsOpen: <?php echo $costing; ?> });
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Recordset2);


mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset3);

mysql_free_result($rs_companies);

mysql_free_result($rs_sites);

mysql_free_result($rs_area_q);
?>
