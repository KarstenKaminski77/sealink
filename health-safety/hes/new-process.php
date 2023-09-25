<?php
    
//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);

require_once('../../functions/functions.php'); 

select_db();

$date = date('Y-m-d');
$company = $_POST['company'];
$site = $_POST['site'];
$jobno = $_POST['ref'];

$scope = $_POST['scope'];
$equipment = $_POST['equipment'];
$risk = $_POST['risk'];
$contractor = $_POST['contractor'];
$chesm = $_POST['chesm'];
$issuer = $_POST['issuer'];
$so = $_POST['so'];

// Find Company Prefix

$query = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '$site'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$companyid = $row['Company'];

$query2 = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$companyid'")or die(mysqli_error($con));
$row2 = mysqli_fetch_array($query2);

$prefix = $row2['Prefix'];

$ref = $prefix.$jobno;

// Create New HES Instance

mysqli_query($con, "INSERT INTO tbl_hes (CompanyId,SiteId,JobNo,Date,ScopeOfWork,Equipment,RiskRanking,Contractor,ContractorRating,PermitIssuer,SafetyOfficer,FallProtecionPlan) VALUES ('$company','$site','$ref','$date','$scope','$equipment','$risk','$contractor','$chesm','$issuer','$so','$fpp')")or die(mysqli_error($con));

$query = mysqli_query($con, "SELECT * FROM tbl_hes ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$hesid = $row['Id'];

// Attached Safety Documents
	
// Check Which Documents Will Be Attached And Update Or Create Instances In Tables
	
$query = mysqli_query($con, "SELECT * FROM tbl_hes_documents_relation WHERE HESId = '$hesid'")or die(mysqli_error($con));
$numrows = mysqli_num_rows($query);
	
if($numrows == 0){
		
	$query2 = mysqli_query($con, "SELECT * FROM tbl_hes_documents")or die(mysqli_error($con));
	while($row2 = mysqli_fetch_array($query2)){
			
		$documentid = $row2['Id'];
			
		mysqli_query($con, "INSERT INTO tbl_hes_documents_relation (HESId,DocumentId) VALUES ('$hesid','$documentid')")or die(mysqli_error($con));
			
	}
		
	//mysqli_query($con, "UPDATE tbl_hes_documents_relation SET PDF = 'Seavest Fall Protection.pdf' WHERE DocumentId = '4'")or die(mysqli_error($con)); 
	
	mysqli_query($con, "UPDATE tbl_hes_documents_relation SET PDF = 'Seavest Fall Protection.pdf' WHERE DocumentId = '5'")or die(mysqli_error($con)); 
		
}
	
// Job Safety Analysis

$count = count($_POST['documents']);
$documents = $_POST['documents'];
	
for($i=0;$i<$count;$i++){
	
	$docs = $documents[$i];
	
	mysqli_query($con, "UPDATE tbl_hes_documents_relation SET Active = '1' WHERE HESId = '$hesid' AND DocumentId = '$docs'")or die(mysqli_error($con));
	
	// Job Safety Analysis
	
	if($docs == 2){
		
		// Loop Through And Insert The JSA Id And PPE Id's Into The Relational Table
		
		// Hot Work - Work Type = 1
		
		$query2 = mysqli_query($con, "SELECT * FROM tbl_ppe_list")or die(mysqli_error($con));
		while($row2 = mysqli_fetch_array($query2)){
			
			$ppe = $row2['Id'];
			mysqli_query($con, "INSERT INTO tbl_ppe_relation (HESId,PPEId,WorkType) VALUES ('$hesid','$ppe','1')")or die(mysqli_error($con));
		}
		
		// Insert First Job Step Row
		
		mysqli_query($con, "INSERT INTO tbl_job_steps (HESId,WorkType) VALUES ('$hesid','1')")or die(mysqli_error($con));
	}
	
	if($docs == 7){
		
		// Loop Through And Insert The JSA Id And PPE Id's Into The Relational Table
		
		// Elevated Work - Work Type = 2
		
		$query3 = mysqli_query($con, "SELECT * FROM tbl_ppe_list")or die(mysqli_error($con));
		while($row3 = mysqli_fetch_array($query3)){
			
			$ppe = $row3['Id'];
			mysqli_query($con, "INSERT INTO tbl_ppe_relation (HESId,PPEId,WorkType) VALUES ('$hesid','$ppe','2')")or die(mysqli_error($con));
		}
		
		// Insert First Job Step Row
		
		mysqli_query($con, "INSERT INTO tbl_job_steps (HESId,WorkType) VALUES ('$hesid','2')")or die(mysqli_error($con));
	}
}


// Insert Job Risk
	
	$count = count($_POST['jsa-risk']);
	
	$jsa_risk = $_POST['jsa-risk'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_hes ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$jobno = $row['JobNo'];
	
	mysqli_query($con, "DELETE FROM tbl_hes_jsa_relation WHERE JobNo = '$jobno'")or die(mysqli_error($con));
	
	for($i=0;$i<$count;$i++){
		
		$risk = $jsa_risk[$i];
		
		mysqli_query($con, "INSERT INTO tbl_hes_jsa_relation (JobNo,JSAId) VALUES ('$jobno','$risk')")or die(mysqli_error($con));
		
	}

header('Location: ../../fpdf16/pdf-hes.php?Id='.$hesid);

?>