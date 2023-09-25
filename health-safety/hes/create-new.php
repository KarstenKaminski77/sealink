<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once('../../functions/functions.php'); 

select_db();

$date = date('Y-m-d');
$company = $_POST['company'];
$site = $_POST['site'];
$ref = $_POST['ref']; echo $ref .' x';

$scope = $_POST['scope'];
$equipment = $_POST['equipment'];
$risk = $_POST['risk'];
$contractor = $_POST['contractor'];
$chesm = $_POST['chesm'];
$issuer = $_POST['issuer'];
$so = $_POST['so'];

// Create New HES Instance

mysql_query("INSERT INTO tbl_hes (CompanyId,SiteId,JobNo,Date,ScopeOfWork,Equipment,RiskRanking,Contractor,ContractorRating,PermitIssuer,SafetyOfficer,FallProtecionPlan) VALUES ('$company','$site','$ref','$date','$scope','$equipment','$risk','$contractor','$chesm','$issuer','$so','$fpp')")or die(mysql_error());

$query3 = mysql_query("SELECT * FROM tbl_hes ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row3 = mysql_fetch_array($query3);

$old_id = $_GET['Id'];
$hesid = $row3['Id'];

// Attached Safety Documents
	
// Loop Through Old Records And Duplicate Them With A New HES Id
	
$query = mysql_query("SELECT * FROM tbl_hes_documents_relation WHERE HESId = '$old_id'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	
	$doc_id = $row['DocumentId'];
	$pdf = $row['PDF'];
	$active = $row['Active'];
	
	mysql_query("INSERT INTO tbl_hes_documents_relation (HESId,DocumentId,PDF,Active) VALUES ('$hesid','$doc_id','$pdf','$active')")or die(mysql_error());
	
}

mysql_query("UPDATE tbl_hes_documents_relation SET PDF = 'Seavest Fall Protection.pdf' WHERE DocumentId = '4'")or die(mysql_error()); 
		
	
// Personal Protective Equipment

$query = mysql_query("SELECT * FROM tbl_ppe_relation WHERE HESId = '$old_id'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	
	$ppeid = $row['PPEId'];
	$selected = $row['Selected'];
	$comments = $row['Comments'];
	
	mysql_query("INSERT INTO tbl_ppe_relation (HESId,PPEId,Selected,Comments) VALUES ('$hesid','$ppeid','$selected','$comments')")or die(mysql_error());
}

// Job Steps

$query = mysql_query("SELECT * FROM tbl_job_steps WHERE HESId = '$old_id'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	
	$js = $row['JobSteps'];
	$hazard = $row['PotentialHazrd'];
	$actions = $row['CriticalActions'];
	
	mysql_query("INSERT INTO tbl_job_steps (HESId,JobSteps,PotentialHazard,CriticalActions) VALUES ('$hesid','$js','$hazard','$actions')")or die(mysql_error());
}

// Insert Job Risk

$query2 = mysql_query("SELECT JobNo FROM tbl_hes WHERE Id = 'old_id'")or die(mysql_error());
$row2 = mysql_fetch_array($query2);

$oldjobno = $row2['JobNo']; 

$query = mysql_query("SELECT * FROM tbl_hes_jsa_relation WHERE JobNo = '$oldjobno'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	
	$jsaid = $row2['JSAId'];
	
	mysql_query("INSERT INTO tbl_job_steps (JobNo,JSAId) VALUES ('$ref','$jsaid')")or die(mysql_error());
}
	
header('Location: ../../fpdf16/pdf-hes.php?Id='.$hesid);

?>