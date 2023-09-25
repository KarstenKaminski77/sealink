<?php 
session_start();

require_once('functions/functions.php');

$db_con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');
$quoteno = $_GET['Id'];

// Total Cosring
if(isset($_GET['Costing'])){
	
	$jobid = $_GET['Costing'];
	
	$query_qs = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE JobId = '$jobid'")or die(mysqli_error($db_con));
	$row_qs = mysqli_fetch_array($query_qs);
	
	if(mysqli_num_rows($query_qs) == 0){
		
		$query_jc = mysqli_query($db_con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($db_con));
		$row_jc = mysqli_fetch_array($query_jc);
		
		$jobno = $row_jc['JobNo'];
		$areaid = $row_jc['AreaId'];
		$company = $row_jc['CompanyId'];
		$site = $row_jc['SiteId'];
		
		if($row_jc['RequestPreWorkPo'] == '1'){
			
			$description = 'The site has been visited and this estimate requires approval in order for work to proceed';
			
		} else {
			
			$description = 'This maintenance request has been executed and herewith costing for P.O. approval.';
		}
		
		$query = mysqli_query($db_con, "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1")or die(mysqli_error($db_con));
		$row = mysqli_fetch_array($query);
		
		$quote = $row['QuoteNo']+1;
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$today = date('Y-m-j');
		$userid = $_SESSION['kt_login_id'];
		
		mysqli_query($db_con, "UPDATE tbl_jc SET QuoteNo = '$quote' WHERE JobId = '$jobid'")or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Labour,Date,Time,JobDescription,Days,Status,UserId,FMC,Notes,JobId) 
		VALUES ('$areaid','$company','$site','$quote','1','$date','$time','$description','$today','4','$userid','$jobno','$description','$jobid')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Material,Date,Time,JobDescription,Days,Status,UserId,FMC,Notes,JobId) 
		VALUES ('$areaid','$company','$site','$quote','1','$date','$time','$description','$today','4','$userid','$jobno','$description','$jobid')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_qs (AreaId,CompanyId,SiteId,QuoteNo,Transport,Date,Time,JobDescription,Days,Status,UserId,FMC,Notes,JobId) 
		VALUES ('$areaid','$company','$site','$quote','1','$date','$time','$description','$today','4','$userid','$jobno','$description','$jobid')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_qs_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_qs_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_costing_material (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_costing_labour (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_costing_outsourcing (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_costing_hes (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_costing_equipment (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		mysqli_query($db_con, "INSERT INTO tbl_costing_transport (QuoteNo) VALUES ('$quote')") or die(mysqli_error($db_con));
		
		header('Location: quote_calc.php?Id='. $quote);
		
	} else {
		
		header('Location: quote_calc.php?Id='. $row_qs['QuoteNo']);
	}
}

$quoteno = $_GET['Id'];

// Costing

// Costing HES
if(isset($_POST['btn-hes'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_costing_hes (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($db_con));
	
}
	
if(isset($_POST['hes-c'])){
	
	for($i=0;$i<count($_POST['hes-c']);$i++){
		
		$id = $_POST['id-c-hes'][$i];
		$desc = $_POST['hes-c'][$i];
		$price = $_POST['hes-price-c'][$i];
		
		mysqli_query($db_con, "UPDATE tbl_costing_hes SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($db_con));
		
	}
}

if(isset($_POST['delete-hes-c'])){
	
	$id = $_POST['delete-hes-c'];
	
	foreach($id as $c){
		
		mysqli_query($db_con, "DELETE FROM tbl_costing_hes WHERE Id = '$c'")or die(mysqli_error($db_con));
	}
}

// Costing Material
if(isset($_POST['btn-material'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_costing_material (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($db_con));
	
}
	
if(isset($_POST['material-c'])){
	
	for($i=0;$i<count($_POST['material-c']);$i++){
		
		$id = $_POST['id-c-m'][$i];
		$desc = $_POST['material-c'][$i];
		$price = $_POST['material-price-c'][$i];
		
		mysqli_query($db_con, "UPDATE tbl_costing_material SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($db_con));
		
	}
}

if(isset($_POST['delete-m-c'])){
	
	$id = $_POST['delete-m-c'];
	
	foreach($id as $c){
		
		mysqli_query($db_con, "DELETE FROM tbl_costing_material WHERE Id = '$c'")or die(mysqli_error($db_con));
	}
}


// Costing Equipment
if(isset($_POST['btn-equipment'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_costing_equipment (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($db_con));
	
}
	
if(isset($_POST['equipment-c'])){
	
	for($i=0;$i<count($_POST['equipment-c']);$i++){
		
		$id = $_POST['id-c-e'][$i];
		$desc = $_POST['equipment-c'][$i];
		$price = $_POST['equipment-price-c'][$i];
		
		mysqli_query($db_con, "UPDATE tbl_costing_equipment SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($db_con));
		
	}
}

if(isset($_POST['delete-e-c'])){
	
	$id = $_POST['delete-e-c'];
	
	foreach($id as $c){
		
		mysqli_query($db_con, "DELETE FROM tbl_costing_equipment WHERE Id = '$c'")or die(mysqli_error($db_con));
	}
}

// Costing Labour
if(isset($_POST['btn-labour'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_costing_labour (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($db_con));
	
}
	
if(isset($_POST['labour-c'])){
	
	for($i=0;$i<count($_POST['labour-c']);$i++){
		
		$id = $_POST['id-c-l'][$i];
				
		$pieces = explode('-', $_POST['labour-price-c'][$i]);
		
		$desc = $pieces[0];
		$price = $pieces[1];
		
		mysqli_query($db_con, "UPDATE tbl_costing_labour SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($db_con));
		
	}
}

if(isset($_POST['delete-l-c'])){
	
	$id = $_POST['delete-l-c'];
	
	foreach($id as $c){
		
		mysqli_query($db_con, "DELETE FROM tbl_costing_labour WHERE Id = '$c'")or die(mysqli_error($db_con));
	}
}

// Costing Outsource
if(isset($_POST['btn-outsource'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_costing_outsourcing (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($db_con));
	
}
	
if(isset($_POST['outsource-c'])){
	
	for($i=0;$i<count($_POST['outsource-c']);$i++){
		
		$id = $_POST['id-c-o'][$i];
		$desc = $_POST['outsource-c'][$i];
		$price = $_POST['outsource-price-c'][$i];
		
		mysqli_query($db_con, "UPDATE tbl_costing_outsourcing SET QuoteNo = '$quoteno', Description = '$desc', Price = '$price' WHERE Id = '$id'")or die(mysqli_error($db_con));
		
	}
}

if(isset($_POST['delete-o-c'])){
	
	$id = $_POST['delete-o-c'];
	
	foreach($id as $c){
		
		mysqli_query($db_con, "DELETE FROM tbl_costing_outsourcing WHERE Id = '$c'")or die(mysqli_error($db_con));
	}
}

// HES
if(isset($_POST['btn-hes'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_qs_hes (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($db_con));
	
}

if(isset($_POST['delete-hes'])){
	
	$id = $_POST['delete-hes'];
	
	foreach($id as $c){
		
		mysqli_query($db_con, "DELETE FROM tbl_qs_hes WHERE Id = '$c'")or die(mysqli_error($db_con));
	}
}

// Quote Equipment
if(isset($_POST['btn-e'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_qs_equipment (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($db_con));
	
}

if(isset($_POST['delete-e'])){
	
	$id = $_POST['delete-e'];
	
	foreach($id as $c){
		
		mysqli_query($db_con, "DELETE FROM tbl_qs_equipment WHERE Id = '$c'")or die(mysqli_error($db_con));
	}
}

$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' ORDER BY Id ASC LIMIT 1") or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);

$companyid = $row['CompanyId'];
$siteid = $row['SiteId'];
$areaid = $row['AreaId'];

$today = date('Y-m-d');
$userid = $_COOKIE['userid'];

// Quote Labour
if(isset($_POST['btn-add-labour'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_qs (AreaId,QuoteNo,CompanyId,SiteId,Labour,Days,Userid) VALUES ('$areaid','$quoteno','$companyid','$siteid','1','$today','$userid')") or die(mysqli_error($db_con));
}

// Quote Material
if(isset($_POST['btn-add-material'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_qs (AreaId,QuoteNo,CompanyId,SiteId,Material,Days,Userid) VALUES ('$areaid','$quoteno','$companyid','$siteid','1','$today','$userid')") or die(mysqli_error($db_con));
}

// Quote Transport
if(isset($_POST['btn-add-transport'])){
	
	mysqli_query($db_con, "INSERT INTO tbl_qs (AreaId,QuoteNo,CompanyId,SiteId,Transport,Days,Userid) VALUES ('$areaid','$quoteno','$companyid','$siteid','1','$today','$userid')") or die(mysqli_error($db_con));
}

// Costing Transport

$query = mysqli_query($db_con, "SELECT * FROM tbl_costing_transport WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$numrows = mysqli_num_rows($query);

if($_POST){
	
	$distance = $_POST['return'];
	$trips = $_POST['trips'];
	$days = $_POST['days'];
	$subsistence = $_POST['subsistence'];
	
	if($numrows == 0){
		
		mysqli_query($db_con, "INSERT INTO tbl_costing_transport (QuoteNo,ReturnDistance,Trips) VALUES ('$quoteno','$distance','$trips')")or die(mysqli_error($db_con));
	
	} else {
		
		mysqli_query($db_con, "UPDATE tbl_costing_transport SET QuoteNo = '$quoteno', ReturnDistance = '$distance', Trips = '$trips' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
	
	}
	
	mysqli_query($db_con, "UPDATE tbl_costing_labour SET Days = '$days', Subsistence = '$subsistence' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}
		
///////////////////////////////
/// Labour Rates Drop Down ///
/////////////////////////////

$query_dd = mysqli_query($db_con, "SELECT * FROM tbl_costing_labour_rates")or die(mysqli_error($db_con));

// Set Cookie after costing to make the quotation visible.
if(isset($_POST['quote']) && !empty($_POST['internal_notes'])){
	
	header('Location: costing-quote.php?Id='. $_GET['Id']);
	
}

if(isset($_POST['company'])){

$company_id = $_POST['company'];
$site_id = $_POST['site'];

$areaid = $row['AreaId'];

$query = mysqli_query($db_con, "SELECT AreaId FROM tbl_sites WHERE Id = '$site_id'")or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);

mysqli_query($db_con, "UPDATE tbl_qs SET CompanyId = '$company_id', SiteId = '$site_id', AreaId = '$areaid' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}

if(isset($_POST['jobdescription'])){
$jd = $_POST['jobdescription'];
mysqli_query($db_con, "UPDATE tbl_qs SET JobDescription = '$jd' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}

if(isset($_POST['att'])){
$att = $_POST['att'];
mysqli_query($db_con, "UPDATE tbl_qs SET Attention = '$att' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}

if(isset($_POST['notes'])){
$notes = $_POST['notes'];
mysqli_query($db_con, "UPDATE tbl_qs SET Notes = '$notes' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}

if(isset($_POST['internal_notes'])){
$internal_notes = addslashes($_POST['internal_notes']);
mysqli_query($db_con, "UPDATE tbl_qs SET InternalNotes = '$internal_notes' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}

if(isset($_POST['fmc'])){
	
	$fmcnumber = $_POST['fmc'];
	mysqli_query($db_con, "UPDATE tbl_qs SET FMC = '$fmcnumber' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}

if(isset($_POST['delete_l'])){
$delete = $_POST['delete_l'];

foreach($delete as $c){

mysqli_query($db_con, "DELETE FROM tbl_qs WHERE Id = '$c'") or die(mysqli_error($db_con));
}}
if(isset($_POST['delete_m'])){
$delete = $_POST['delete_m'];

foreach($delete as $c){

mysqli_query($db_con, "DELETE FROM tbl_qs WHERE Id = '$c'") or die(mysqli_error($db_con));
}}
if(isset($_POST['delete_t'])){
$delete = $_POST['delete_t'];

foreach($delete as $c){

mysqli_query($db_con, "DELETE FROM tbl_qs WHERE Id = '$c'") or die(mysqli_error($db_con));
}}

if(isset($_POST['date'])){
$date = $_POST['date'];
mysqli_query($db_con, "UPDATE tbl_qs SET Date = '$date' WHERE QuoteNo = '$quoteno'") or die(mysqli_error($db_con));
}

$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' ORDER BY Id ASC LIMIT 1") or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);

$companyid = $row['CompanyId'];
$siteid = $row['SiteId'];

if($_POST['labour_row'] >= 1){

$quoteno = $_GET['Id'];
$rows = $_POST['labour_row'];

for($i=0;$i<$rows;$i++){

mysqli_query($db_con, "INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Labour) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysqli_error($db_con));
}}

if($_POST['material_row'] >= 1){

$quoteno = $_GET['Id'];
$rows = $_POST['material_row'];

for($i=0;$i<$rows;$i++){

mysqli_query($db_con, "INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Material) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysqli_error($db_con));
}}

if($_POST['transport_row'] >= 1){

$quoteno = $_GET['Id'];
$rows = $_POST['transport_row'];

for($i=0;$i<$rows;$i++){

mysqli_query($db_con, "INSERT INTO tbl_qs (QuoteNo,CompanyId,SiteId,Transport) VALUES ('$quoteno','$companyid','$siteid','1')") or die(mysqli_error($db_con));
}}

$quoteno = $_GET['Id'];

if(isset($_GET['update']) || isset($_GET['new'])){
	
	// Upadate Health and Safety
	for($i=0;$i<count($_POST['desc-hes']);$i++){
		
		$id = $_POST['id-hes'][$i];
		$quoteno = $_GET['Id'];
		$description = $_POST['desc-hes'][$i];
		$unit = $_POST['unit-hes'][$i];
		$qty = $_POST['qty-hes'][$i];
		$price = $_POST['price-hes'][$i];
		$total = $price * $qty;
		
		mysqli_query($db_con, "UPDATE tbl_qs_hes SET Description = '$description', Unit = '$unit', Qty = '$qty', UnitPrice = '$price', Total = '$total' WHERE Id = '$id'")or die(mysqli_error($db_con));
		
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
		
		mysqli_query($db_con, "UPDATE tbl_qs_equipment SET Description = '$description', Unit = '$unit', Qty = '$qty', UnitPrice = '$price', Total = '$total' WHERE Id = '$id'")or die(mysqli_error($db_con));
		
	}

$query = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'";
$result = mysqli_query($db_con, $query) or die(mysqli_error($db_con));
$numrows = mysqli_num_rows($result);

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
mysqli_query($db_con, "UPDATE tbl_qs SET  Description = '$labour', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysqli_error($db_con));
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

mysqli_query($db_con, "UPDATE tbl_qs SET  Description = '$material', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysqli_error($db_con));
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
mysqli_query($db_con, "UPDATE tbl_qs SET TransportComment = '$t_comment', Description = '$transport', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysqli_error($db_con));
}}

$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'") or die(mysqli_error($db_con));
while($row = mysqli_fetch_array($query)){
$id = $row['Id'];
$qty = $row['Qty'];
$price = $row['Price'];
$labour_total = $qty * $price;
$vat = ($labour_total / 100) * 14;
mysqli_query($db_con, "UPDATE tbl_qs SET SubTotal = '$labour_total', VAT = '$vat' WHERE Id = '$id'") or die(mysqli_error($db_con));
}
$query = mysqli_query($db_con, "SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Labour = '1'") or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);
$labour_total = $row['SUM(Total1)'];

$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysqli_error($db_con));
while($row = mysqli_fetch_array($query)){
$id = $row['Id'];
$qty = $row['Qty'];
$price = $row['Price'];
$material_total = $qty * $price;
$vat = ($material_total / 100) * 14;
mysqli_query($db_con, "UPDATE tbl_qs SET SubTotal = '$material_total', VAT = '$vat' WHERE Id = '$id'") or die(mysqli_error($db_con));
}
$query = mysqli_query($db_con, "SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Material = '1'") or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);
$material_total = $row['SUM(Total1)'];

$quoteno = $_GET['Id'];

$query = mysqli_query($db_con, "SELECT CompanyId FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);

echo $row_Recordset1['Rate'];
$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysqli_error($db_con));
while($row = mysqli_fetch_array($query)){
$transport_total = $row['Description'] * ($row['Qty'] * $row['Price']);
$vat = ($transport_total / 100) * 14;
mysqli_query($db_con, "UPDATE tbl_qs SET SubTotal = '$transport_total', VAT = '$vat' WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysqli_error($db_con));
}
$query = mysqli_query($db_con, "SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quoteno' AND Transport = '1'") or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);
$transport_total = $row['SUM(Total1)'];

if(isset($_POST['date'])){
$date = $_POST['date'];
mysqli_query($db_con, "UPDATE tbl_qs SET Date = '$date' WHERE QuoteNo = '$quoteno'") or die(mysqli_error($db_con));
}

// Allocate Technician
if($_POST){
	
	$tech_id = $_POST['tech'];
	
	mysqli_query($db_con, "UPDATE tbl_qs SET TechnicianId = '$tech_id' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
}

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysqli_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT SUM(Total1), SUM(VAT) FROM tbl_qs WHERE QuoteNo = %s GROUP BY QuoteNo", $colname_Recordset4);
$Recordset4 = mysqli_query($db_con, $query_Recordset4, $seavest) or die(mysqli_error($db_con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$total = $row_Recordset4['SUM(Total1)'];
$vat = $row_Recordset4['SUM(VAT)'];
$total = $total + $vat;
$quoteno = $_GET['Id'];

mysqli_query($db_con, "UPDATE tbl_qs SET Total = '$total' WHERE QuoteNo = '$quoteno'") or die(mysqli_error($db_con));

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysqli_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_qs WHERE QuoteNo = '%s'", $colname_Recordset2);
$Recordset2 = mysqli_query($db_con, $query_Recordset2, $seavest) or die(mysqli_error($db_con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$quoteno = $_GET['Id'];

$query = "
  SELECT
	  tbl_sites.Name AS Name_1,
	  tbl_companies.Name,
	  tbl_sites.Company,
	  tbl_sites.Site,
	  tbl_sites.Address,
	  tbl_qs.Id,
	  tbl_qs.SiteId,
	  tbl_qs.QuoteNo,
	  tbl_qs.AreaId,
	  tbl_qs.Date,
	  tbl_qs.JobDescription,
	  tbl_qs.Attention,
	  tbl_qs.Status,
	  tbl_qs.InternalNotes,
	  tbl_qs.Notes,
	  tbl_qs.FMC
  FROM
	  (
		  (
			  tbl_qs
			  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_qs.SiteId
		  )
		  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_qs.CompanyId
	  )
  WHERE
	  tbl_qs.QuoteNo = '$quoteno'
  ORDER BY
	  Id ASC
  LIMIT 1";

$Recordset5 = mysqli_query($db_con, $query) or die(mysqli_error($db_con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$KTColParam1_Recordset3 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset3 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}

$query_Recordset3 = "SELECT tbl_qs.CompanyId, tbl_qs.QuoteNo, tbl_companies.* FROM (tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo = '$KTColParam1_Recordset3' ";
$Recordset3 = mysqli_query($db_con, $query_Recordset3) or die(mysqli_error($db_con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$today = date('Y-m-j');
$submitted = $_POST['submitted'];

// Send to awaiting approval
if(isset($_POST['approval'])){
	
	mysqli_query($db_con, "UPDATE tbl_qs SET Status = '0', Days = '$today' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
	
	header('Location: select_q_pending.php');

}

// Send to qued
if(isset($_POST['qued'])){
	
	mysqli_query($db_con, "UPDATE tbl_qs SET Status = '4', Days = '$today', Rejected = '1' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
	
	header('Location: qs-qued.php');

}

// Send to outbox
if(isset($_POST['outbox'])){
	
	for($i=0;$i<count($_POST['attach']);$i++){
		
		$image = $_POST['attach'][$i];
		
		mysqli_query($db_con, "UPDATE tbl_photos SET Attach = '1' WHERE Image = '$image' AND QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
	}
		
	$query = mysqli_query($db_con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
	
	if(mysqli_num_rows($query) >= 1){
		
		$query = mysqli_query($db_con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno' AND Attach = '1'")or die(mysqli_error($db_con));
		
		if(mysqli_num_rows($query) >= 1){
			
			mysqli_query($db_con, "UPDATE tbl_qs SET Status = '1', Days = '$today' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
				
			$query = mysqli_query($db_con, "SELECT * FROM tbl_sent_quotes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
			$numrows = mysqli_num_rows($query);
				
			if($numrows == 0){
					
				$company = $row_Recordset5['Name'];
				$site = addslashes($row_Recordset5['Name_1']);
				$quote_no = $_GET['Id'];
				$document = 'Seavest Quotation '. $_GET['Id'] .'.pdf';
				$date = date('d M Y H:i:s');
					
				mysqli_query($db_con, "INSERT INTO tbl_sent_quotes (CompanyId,SiteId,QuoteNo,PDF,DateSent) VALUES ('$company','$site','$quote_no','$document','$date')")or die(mysqli_error($db_con));
			}
				
				header('Location: fpdf16/pdf_quotation.php?Id='.$quoteno);
			
		} else {
			
			$image_error = "Please select at least one photo.";
			$class = 'class="qs-check"';
		}
		
	} else {
		
		header('Location: fpdf16/pdf_quotation.php?Id='.$quoteno);
	}
}
?>
<?php
$query_rs_companies = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$rs_companies = mysqli_query($db_con, $query_rs_companies) or die(mysqli_error($db_con));
$row_rs_companies = mysqli_fetch_assoc($rs_companies);
$totalRows_rs_companies = mysqli_num_rows($rs_companies);

$query_rs_sites = "SELECT * FROM tbl_sites ORDER BY Name ASC";
$rs_sites = mysqli_query($db_con, $query_rs_sites) or die(mysqli_error($db_con));

$query_rs_area_q = "SELECT * FROM tbl_areas ORDER BY Area ASC";
$rs_area_q = mysqli_query($db_con, $query_rs_area_q) or die(mysqli_error($db_con));
$row_rs_area_q = mysqli_fetch_assoc($rs_area_q);
$totalRows_rs_area_q = mysqli_num_rows($rs_area_q);

////////////////////////
/// Transport Query ///
//////////////////////

$query_transport = mysqli_query($db_con, "SELECT * FROM tbl_costing_transport WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row_transport = mysqli_fetch_array($query_transport);

$query_labour = mysqli_query($db_con, "SELECT * FROM tbl_costing_labour WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row_labour = mysqli_fetch_array($query_labour);

$query_sum_hes = mysqli_query($db_con, "SELECT SUM(Total) AS Total_1 FROM tbl_qs_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row_sum_hes = mysqli_fetch_array($query_sum_hes);
		
$query_sum_e = mysqli_query($db_con, "SELECT SUM(Total) AS Total_1 FROM tbl_qs_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row_sum_e = mysqli_fetch_array($query_sum_e);
		
$query_sum_m = mysqli_query($db_con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row_sum_m = mysqli_fetch_array($query_sum_m);
		
$query_sum_safety = mysqli_query($db_con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row_sum_safety = mysqli_fetch_array($query_sum_safety);
		
$query_sum_outsourcing = mysqli_query($db_con, "SELECT SUM(Price) AS Total_1 FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row_sum_outsourcing = mysqli_fetch_array($query_sum_outsourcing);

// Allocate Technicians
$query_techs = mysqli_query($db_con, "SELECT * FROM tbl_technicians")or die(mysqli_error($db_con));

// Allocate technicians to actual history
			
if(isset($_POST['tech'])){
	
	mysqli_query($db_con, "DELETE FROM tbl_history_alerts WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
			
	$techid = $_POST['tech'];
	$quoteno = $_GET['Id'];
	$date = date('Y-m-d');
				
	$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
	$row = mysqli_fetch_array($query);
				
	$siteid = $row['SiteId'];
				
	$query = mysqli_query($db_con, "SELECT * FROM tbl_sites WHERE Id = '$siteid'")or die(mysqli_error($db_con));
	$row = mysqli_fetch_array($query);
								
	$site = addslashes($row['Name']);
				
	mysqli_query($db_con, "INSERT INTO tbl_history_alerts (Site,Date,QuoteNo,TechnicianId) VALUES ('$site','$date','$quoteno','$techid')")or die(mysqli_error($db_con));
			
	//mysqli_query($db_con, "DELETE FROM tbl_history_alerts WHERE JobNo = '' AND Site = '' AND QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
			
	//mysqli_query($db_con, "DELETE FROM tbl_history_alerts WHERE QuoteNo = '$quoteno' AND TechnicianId NOT IN ('".join("','", $techid)."')")or die(mysqli_error($db_con));
}

// Internal Notes
$actual_history = $_POST['internal_notes'];

$query = mysqli_query($db_con, "SELECT * FROM tbl_actual_history WHERE QuoteNo = '$quoteno' AND Comments = '$actual_history'") or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);
$numrows = mysqli_num_rows($query);

if(isset($_POST['internal_notes']) && !empty($_POST['internal_notes']) && $numrows == 0){
	
	$technicianid = $_SESSION['kt_login_id'];
	$date = date('Y-m-d H:i:s');
	
	mysqli_query($db_con, "INSERT INTO tbl_actual_history (QuoteNo,TechnicianId,Date,Comments) VALUES ('$quoteno','$technicianid','$date','$actual_history')")or die(mysqli_error($db_con));
	
}

$KTColParam1_query_note_comments = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_query_note_comments = $_GET["Id"];
}
$query_note_comments = mysqli_query($db_con, "SELECT tbl_technicians.Name AS Name_1, tbl_actual_history.JobId, tbl_users.Name, tbl_actual_history.Date, tbl_actual_history.Comments, tbl_actual_history.Mobile FROM ((tbl_actual_history LEFT JOIN tbl_users ON tbl_users.Id=tbl_actual_history.TechnicianId) LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_actual_history.TechnicianId) WHERE tbl_actual_history.QuoteNo='$KTColParam1_query_note_comments'  ORDER BY tbl_actual_history.Id ASC ") or die(mysqli_error($db_con));
$row_note_comments = mysqli_fetch_assoc($query_note_comments);
$totalRows_query_note_comments = mysqli_num_rows($query_note_comments);

$query_thumbs = mysqli_query($db_con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
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

<link href="SpryAssets/SpryCollapsiblePanel-jc.css" rel="stylesheet" type="text/css">
<script src="SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="highslide/highslide.css" />

<script type="text/javascript" src="highslide/highslide.js"></script>
<script type="text/javascript">
<!--
hs.graphicsDir = 'highslide/graphics/';
    hs.outlineType = 'rounded-white';

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
//-->
</script>

	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

	  <script type="text/javascript" src="fancyBox-2/lib/jquery-1.10.1.min.js"></script>
      <script type="text/javascript" src="fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <script type="text/javascript" src="fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
      <link rel="stylesheet" type="text/css" href="fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />
  
      <script type="text/javascript">
          $(document).ready(function() {
  
              $('.fancybox').fancybox({
			  
				  autoSize    : true, 
				  closeClick  : false, 
				  fitToView   : false, 
				  openEffect  : 'none', 
				  closeEffect : 'none', 
				  type : 'iframe',
                  afterClose : function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
				  parent.location.reload(true);
                  }				  
			  });
  
  
          });
      </script>

      <script src="countdown/jquery.plugin.js"></script>
      <script src="countdown/jquery.countdown.js"></script>
      
      <link rel="stylesheet" media="all" type="text/css" href="jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="jquery-ui-sliderAccess.js"></script>
      
      <!-- Countdown Timer -->
      <link rel="stylesheet" href="countdown/jquery.countdown.css">
      
	  <?php
	  $query_jc = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '". $_GET['Id'] ."'")or die(mysqli_error($db_con));
	  $row_jc = mysqli_fetch_array($query_jc);
	     
      $now = strtotime(date('Y-m-d H:i:s'));
      $to = strtotime($row_jc['SlaEnd']);
      
      $secs_before = $to - $now;
      $secs_after = $now - $to;
	  
	  $complete_before = strtotime($row_jc['SlaEnd']) - strtotime($row_jc['DateCompleted']);
	  $complete_after = strtotime($row_jc['SlaEnd']) - strtotime($row_jc['DateCompleted']);
      
	  
      ?>
      
      <script>
      $(function () {
		  
		  <!-- In Progress Counters -->
          <?php
          if(date('Y-m-d H:i:s') < $row_jc['SlaEnd'] && empty($row_jc['DateCompleted'])){
              
              $class = 'bg-blue';
          ?>
          $('#defaultCountdown').countdown({until: +<?php echo $secs_before; ?>});
          <?php 
          } 
		  
          if(date('Y-m-d H:i:s') > $row_jc['SlaEnd'] && empty($row_jc['DateCompleted'])){
              
              $class = 'bg-red';
              
          ?>
          $('#defaultCountdown').countdown({since: -<?php echo $secs_after; ?>});
          <?php } ?>
		  <!-- End In Progress Counters -->
		  
		  <!-- SLA Closed Counters -->
          <?php
          if($row_jc['DateCompleted'] < $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted'])){
              
              $class = 'bg-blue';
          ?>
          $('#defaultCountdown').countdown({until: <?php echo $complete_before; ?>});
		  $('#defaultCountdown').countdown('pause') // Stop the countdown but don't clear it
          <?php 
          } 
		  
          if($row_jc['DateCompleted'] > $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted'])){
              
              $class = 'bg-red';
              
          ?>
          $('#defaultCountdown').countdown({since: <?php echo $complete_after; ?>});
		  $('#defaultCountdown').countdown('pause') // Stop the countdown but don't clear it
          <?php } ?>
		  <!-- EndSLA Closed Counters -->

      });
      </script>
      
      <?php 
	  
	  if(date('Y-m-d H:i:s') < $row_jc['SlaEnd'] || ($row_jc['DateCompleted'] < $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted']))){
		  
		  if(empty($row_jc['DateCompleted'])){
			  
			  $sla_status = 'REMAINING <br> <span class="stop">Click To Stop</span>';
			  $sla_open = 1;
			  
		  } else {
			  
			  $sla_status = 'CLOSED <br> <span class="stop">In SLA</span>';
			  
		  }
	  
	  ?>
		  
          <style type="text/css">
		  
		  #defaultCountdown{
			  background: #2b74bc; /* Old browsers */
			  background: -moz-linear-gradient(top,  #2b74bc 0%, #19446f 100%); /* FF3.6-15 */
			  background: -webkit-linear-gradient(top,  #2b74bc 0%,#19446f 100%); /* Chrome10-25,Safari5.1-6 */
			  background: linear-gradient(to bottom,  #2b74bc 0%,#19446f 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2b74bc', endColorstr='#19446f',GradientType=0 ); /* IE6-9 */
			  
			  border:solid 1px #012c58;
			  color: #FFF !important;
		  }
		  
		  </style>
		  
		  
	  <?php 
	  }
	  
	  if(date('Y-m-d H:i:s') > $row_jc['SlaEnd'] || ($row_jc['DateCompleted'] > $row_jc['SlaEnd'] && !empty($row_jc['DateCompleted']))){
		  
		  if(empty($row_jc['DateCompleted'])){
			  
			  $sla_status = 'EXPIRED <br> <span class="stop">Click To Stop</span>';
			  $sla_open = 1;
			  
		  } else {
			  
			  $sla_status = 'CLOSED <br> <span class="stop">Out of SLA</span>';
			  
		  }

	  ?>
		  
          <style type="text/css">
		  
		  #defaultCountdown{
			  background: #ed0000; /* Old browsers */
			  background: -moz-linear-gradient(top,  #ed0000 0%, #c10000 100%); /* FF3.6-15 */
			  background: -webkit-linear-gradient(top,  #ed0000 0%,#c10000 100%); /* Chrome10-25,Safari5.1-6 */
			  background: linear-gradient(to bottom,  #ed0000 0%,#c10000 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ed0000', endColorstr='#c10000',GradientType=0 ); /* IE6-9 */
			  
			  border:solid 1px #840101;
			  color:#FFF !important;
		  }
		  
		  </style>
		  
	  <?php } ?>
      <!-- End Countdown Timer -->

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
                  <td nowrap><form name="form2" method="post" action="quote_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
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
          <?php if(isset($_GET['new'])){ $new = '&new'; } ?>
          <form name="form1" method="post" action="quote_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $quoteno; ?>&update">
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
                                <select name="company" class="tarea-white-q" id="company" style="font-weight:bold">
                                  <?php
do {  
?>
                                  <option value="<?php echo $row_rs_companies['Id']?>"<?php if (!(strcmp($row_rs_companies['Id'], $row_Recordset3['CompanyId']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_companies['Name']?></option>
                                  <?php
} while ($row_rs_companies = mysqli_fetch_assoc($rs_companies));
  $rows = mysqli_num_rows($rs_companies);
  if($rows > 0) {
      mysqli_data_seek($rs_companies, 0);
	  $row_rs_companies = mysqli_fetch_assoc($rs_companies);
  }
?>
                                  </select>
                                <br>
                                <?php echo nl2br($row_Recordset3['Address']); ?></div></td>
                              </tr>
                            </table>
                          </div>                    </td>
                    <td width="50%" valign="top"><table border="0" align="right" cellpadding="0" cellspacing="0">
                      <tr>
                        <td><div id="list-brdr">
                          <table width="300" border="0" align="right" cellpadding="3" cellspacing="1">
                            <tr>
                              <td width="120" nowrap class="odd">&nbsp;Date:</td>
                              <td width="200" class="odd">
                              <input name="date" class="tarea-white" id="date" style="cursor:text" value="<?php echo $row_Recordset5['Date']; ?>" size="10">
								<script type="text/javascript">
                                  $('#date').datepicker({
                                  dateFormat: "yy-mm-dd"
                                  });
                                </script>
                              </td>
                              </tr>
                            <tr>
                              <td width="120" nowrap class="even">&nbsp;Quotation Number: </td>
                              <td width="200" class="even">&nbsp;<?php echo $row_Recordset5['QuoteNo']; ?></td>
                              </tr>
                            <tr>
                              <td nowrap class="odd">&nbsp;Reference:</td>
                              <td width="200" class="odd"><input name="fmc" type="text" class="tarea-white-q" id="fmc" style="cursor:text" value="<?php fmc($db_con, $quoteno); ?>"></td>
                              </tr>
                            <?php if(isset($_GET['new'])){ ?>
                            <tr>
                              <?php } ?>
                              <td width="120" nowrap class="even">&nbsp;Site / Customer:</td>
                              <td width="200" class="even">
                              <select name="site" class="tarea-white-q" style="font-weight:bold; color:#000" id="site">
                                <?php while($row_rs_sites = mysqli_fetch_assoc($rs_sites)){ ?>
                                  <option value="<?php echo $row_rs_sites['Id']; ?>" <?php if($row_Recordset5['SiteId'] == $row_rs_sites['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_rs_sites['Name']; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            </tr>
                            <tr>
                              <td width="120" nowrap class="odd">&nbsp;Address:</td>
                              <td width="200" class="odd">&nbsp;<?php echo $row_Recordset5['Address']; ?></td>
                              </tr>
                            <tr>
                              <td width="120" nowrap class="even">&nbsp;Description:</td>
                              <td width="200" class="even"><input name="jobdescription" type="text" class="tarea-white-q" id="jobdescription" style="" value="<?php echo $row_Recordset5['JobDescription']; ?>"></td>
                              </tr>
                            <tr>
                              <td width="120" nowrap class="odd">&nbsp;Att:</td>
                              <td width="200" class="odd"><input name="att" type="text" class="tarea-white-q" id="att" style="cursor:text; font-size:12px" value="<?php echo $row_Recordset5['Attention']; ?>"></td>
                              </tr>
                            </table>
                          </div></td>
                        </tr>
                      </table></td>
                  </tr>
                    </table>
                    
                  <!-- Requested Completion Date -->
                  <div id="list-border" style="margin-top:15px; margin-bottom:15px;">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td width="30%" rowspan="2" class="td-right">
                        <div id="<?php echo $class; ?>">
                          <div id="defaultCountdown"></div>
                        </div>
                        </td>
                        <td width="30%" rowspan="2" class="td-right">

                          <div id="defaultCountdown" class="sla-status">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                              <tr>
                                <td height="40" align="center" valign="middle"><?php echo $sla_status; ?>
                                </td>
                              </tr>
                            </table>
                          </div>

                        </td>
                        <td width="20%" class="td-left">Received Date
                        </td>
                        <td width="20%" align="right" class="td-right">
                          <input name="date1" <?php echo $sla; ?> class="tarea-100" id="date1" value="<?php echo $row_jc['SlaStart']; ?>" disabled="disabled" />
                          <script type="text/javascript">
                              $('#date1').datepicker({
                              dateFormat: "yy-mm-dd"
                              });
                             </script>
                        </td>
                      </tr>
                      <tr>
                        <td width="20%" class="td-left">Requested Completion
                        </td>
                        <td width="20%" align="right" class="td-right">
                          <input name="date2" <?php echo $sla; ?> class="tarea-100" id="date2" value="<?php echo $row_jc['SlaEnd']; ?>" />
                          <script type="text/javascript">
                            $('#date2').datepicker({
                            dateFormat: "yy-mm-dd"
                            });
                          </script>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <!-- End Requested Completion Date -->
                    
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="td-header">Overview</td>
                  </tr>
              </table>
			  <div style="border:solid 1px #A6CAF0; margin-top: 5px; margin-bottpx;margin-bottom: 5px;">
                            
<?php
$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);
?>
			  <textarea name="notes" rows="5" class="tarea-jc" id="notes" style="width:759px; border:none; overflow:scroll; resize: none;"><?php echo $row['Notes']; ?></textarea>
			  </div>
              <div id="CollapsiblePanel1" class="CollapsiblePanel" style="background-color:none; padding:0px; margin-bottom:5px; border:none">
              <div class="CollapsiblePanelTab" tabindex="0"  style="background-color:#FFF; padding:0px; border: none">
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="tb_border2">Allocate Technician</td>
                  </tr>
              </table>
</div>
              <div class="CollapsiblePanelContent">
              			  <div style="border:solid 1px #A6CAF0; background-color:#FFF; margin-top: 5px; margin-bottpx;margin-bottom: 5px; overflow:hidden">
                          <?php while($row_techs = mysqli_fetch_array($query_techs)){ ?>
              			    <div id="qs-tech">
              			      <table border="0" cellspacing="3" cellpadding="2">
              			        <tr>
              			          <td><input name="tech" type="radio" id="tech" value="<?php echo $row_techs['Id']; ?>"<?php if($row_techs['Id'] == $row['TechnicianId']) { ?> checked="checked"<?php } ?>></td>
              			          <td class="combo"><?php echo $row_techs['Name']; ?></td>
           			            </tr>
           			          </table>
           			        </div>
                           <?php } ?>
              			  </div>
              </div>
              </div>
              
              <div id="CollapsiblePanel3" class="CollapsiblePanel" style="background-color:none; padding:0px; margin-bottom:5px; border:none">
              <div class="CollapsiblePanelTab" tabindex="0"  style="background-color:#FFF; padding:0px; border: none">
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="tb_border2">Internal Notes </td>
                  </tr>
              </table>
</div>
     
     <!-- Internal Notes -->         
    <div id="history-log">
      <?php 
	  if(!empty($row_Recordset5['Notes'])){
		  
		  $row_Recordset5['Notes'] .'<br><br>';
	  }
	  ?>
      <?php 
	  if(!empty($row_Recordset5['InternalNotes'])){
		  
		  echo $row_Recordset5['InternalNotes'] .'<br><br>';
	  }
	  ?>
      
      <?php do { ?>
        <span class="history-bg-con">
        <span class="history-bg">
        
		<?php 
		
		$newdate = date('d/m H:i', strtotime($row_note_comments['Date']));
		
		if($row_note_comments['Mobile'] == 1){ 
		
		echo $row_note_comments['Name_1'];
		
		} else { 
		
		echo $row_note_comments['Name'];
		
		} 
		?>
		<?php echo $newdate; ?>
        </span>
        </span>
        &nbsp;<?php echo nl2br($row_note_comments['Comments']); ?>
	<?php } while ($row_note_comments = mysqli_fetch_assoc($query_note_comments)); ?>
   </div>
              
              <div class="CollapsiblePanelContent">
              	 <div style="border:solid 1px #A6CAF0; background-color:#FFF; margin-top: 5px; margin-bottpx;margin-bottom: 5px;">
              	  <textarea name="internal_notes" rows="10" class="tarea-jc" id="internal_notes" style="width:759px; border:none; color:#620000; resize: none; text-align:left"></textarea>
              </div>
              </div>
              </div>
              <?php if(mysqli_num_rows($query_thumbs) >= 1){ ?>
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="td-header">Images &nbsp; <span class="btn-red-generic"><?php echo $image_error; ?></span></td>
                </tr>
                <tr>
                  <td>
                  <?php while($row_thumbs = mysqli_fetch_array($query_thumbs)){ ?>
                  <a href="photos/<?php echo $row_thumbs['Image']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})"></a>
                  <div id="qs-thumbs">
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><a href="photos/<?php echo $row_thumbs['Image']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})"><img src="photos/thumbnails/<?php echo $row_thumbs['Image']; ?>" width="100"></a>
                          </td>
                        <?php // if($row_Recordset5['Status'] == 0){ ?>
                        <td valign="bottom">
                          <input name="attach[]" type="checkbox" id="attach[]" value="<?php echo $row_thumbs['Image']; ?>" <?php echo $class; ?>>
                          </td>
                        <?php // } ?>
                        </tr>
                    </table>
                  </div>
                  <a href="photos/<?php echo $row_thumbs['Image']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})">
                  </a>
                  <?php } ?>
                  </td>
                </tr>
              </table>
              <?php } ?>
              <div id="CollapsiblePanel2" class="CollapsiblePane2" style="background-color:none; padding:0px; margin-bottom:5px; border:none">
              <div class="CollapsiblePanelTab" tabindex="0" style="background-color:#FFF; padding:0px; margin-bottom:5px; border:none">
              <table width="100%" border="0" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
                <tr>
                  <td class="tb_border2">Costing</td>
                </tr>
              </table>
              </div>
              <div class="CollapsiblePanelContent">
			  <div style="border:solid 1px #A6CAF0; background-color:#FFF; margin-top: 5px; margin-bottpx;margin-bottom: 5px; border-bottom: solid 5px #F00">
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
						
						$query = mysqli_query($db_con, "SELECT * FROM tbl_costing_hes WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($db_con));
						$numrows = mysqli_num_rows($query);
						while($row = mysqli_fetch_array($query)){
							
					?>
			        <tr>
			          <td colspan="2"><input name="hes-c[]" type="text" class="tarea-100per" id="hes-c[]" value="Risk assessment, safety documents, barricades and total health &amp; safety compliance."></td>
			          <td width="85" align="right"><input name="hes-price-c[]" type="text" class="tarea-100per" id="hes-price-c[]" value="<?php echo $row['Price']; ?>" style="text-align:right"></td>
			          <td width="20" align="center"><input type="hidden" name="id-c-hes[]" id="id-c-hes[]" value="<?php echo $row['Id']; ?>">
			            <input type="checkbox" name="delete-hes-c[]" id="delete-hes-c[]" value="<?php echo $row['Id']; ?>"></td>
			          </tr>
			        <?php } 
				  
				  $query_material2 = mysqli_query($db_con, "SELECT SUM(Price) AS Total FROM tbl_costing_hes WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
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
						
						$query = mysqli_query($db_con, "SELECT * FROM tbl_costing_material WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($db_con));
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
				  
				  $query_material2 = mysqli_query($db_con, "SELECT SUM(Price) AS Total FROM tbl_costing_material WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
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
						$query = mysqli_query($db_con, "SELECT * FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($db_con));
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
					  
					  $query_material2 = mysqli_query($db_con, "SELECT SUM(Price) AS Total FROM tbl_costing_equipment WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
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
						$query = mysqli_query($db_con, "SELECT * FROM tbl_costing_labour WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($db_con));
						$numrows = mysqli_num_rows($query);
						while($row = mysqli_fetch_array($query)){
							
					?>
			        <tr>
			          <td colspan="2"><input name="labour-c[]" type="text" class="tarea-100per" id="labour-c[]" value="<?php echo $row['Description']; ?>"></td>
			          <td width="85"><select name="labour-price-c[]" class="tarea-100per" id="labour-price-c[]">
			            <option value="">Select one...</option>
			            <?php 
						$query_dd = mysqli_query($db_con, "SELECT * FROM tbl_costing_labour_rates")or die(mysqli_error($db_con));
						while($row_costing_dd = mysqli_fetch_array($query_dd)){ ?>
			            <option value="<?php echo $row_costing_dd['Name'] .'-'. $row_costing_dd['Rate']; ?>" <?php if($row_costing_dd['Rate'] == $row['Price']){ ?>selected="selected"<?php } ?>><?php echo $row_costing_dd['Name']; ?></option>
			            <?php } ?>
			            </select></td>
			          <td width="20" align="center"><input type="hidden" name="id-c-l[]" id="id-c-l[]" value="<?php echo $row['Id']; ?>"></td>
			          </tr>
			        <?php } ?>
                    <?php
					$query_material2 = mysqli_query($db_con, "SELECT SUM(Price) AS Total FROM tbl_costing_labour WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
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
						$query = mysqli_query($db_con, "SELECT * FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno' ORDER BY Id ASC")or die(mysqli_error($db_con));
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
					$query_material2 = mysqli_query($db_con, "SELECT SUM(Price) AS Total FROM tbl_costing_outsourcing WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
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
			          <td width="85" align="right">&nbsp;</td>
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
          <?php if(isset($_COOKIE['costing-'. $_GET['Id']]) || ($_SESSION['kt_login_id'] == 50 || $_SESSION['kt_login_id'] == 1)){ ?>
            <td colspan="3" class="combo_bold"><div style=" border:solid 1px #A6CAF0">
              <table width="100%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                  <td width="450" class="td-header">Description</td>
                  <td width="50" align="center" class="td-header">Unit</td>
                  <td width="40" align="center" class="td-header">Qty.</td>
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
$result = mysqli_query($db_con, $query) or die(mysqli_error($db_con));
$numrows = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result)){
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
$result = mysqli_query($db_con, $query) or die(mysqli_error($db_con));
$numrows = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result)){

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
$result = mysqli_query($db_con, $query) or die(mysqli_error($db_con));
$numrows = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result)){
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
$result = mysqli_query($db_con, $query) or die(mysqli_error($db_con));
$numrows = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result)){
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
$result = mysqli_query($db_con, $query) or die(mysqli_error($db_con));
$numrows = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result)){
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
$query = mysqli_query($db_con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($db_con));
$row = mysqli_fetch_array($query);

$status = $row['Status'];

// Awaiting approval
if($status == 0 || !empty($row_Recordset2['FMC'])){
?>
  <input name="qued" type="submit" class="btn-red-generic" id="qued" value="Reject">
  <input name="outbox" type="submit" class="btn-green-generic" id="outbox" value="Approve">
  <?php 
// Qued
} if($status == 4 && empty($row_Recordset2['FMC'])){ ?>
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
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel3", { contentIsOpen: false });
//-->
</script>
</body>
</html>
<?php
mysqli_free_result($Recordset2);


mysqli_free_result($Recordset4);

mysqli_free_result($Recordset5);

mysqli_free_result($Recordset3);

mysqli_free_result($rs_companies);

mysqli_free_result($rs_sites);

mysqli_free_result($rs_area_q);
?>
