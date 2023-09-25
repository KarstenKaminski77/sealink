<?php require_once('../Connections/seavest.php'); ?>
<?php 
session_start();

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

require_once('../functions/functions.php');

$companyid = $_SESSION['c_id'];
$ref = $_SESSION['r_id'];

$query_sla = "
  SELECT
	  tbl_companies.Name AS CompanyName,
	  tbl_sites.Name AS SiteName,
	  tbl_engineers.Name AS EngineerName,
	  tbl_engineers.Email,
	  tbl_jc.JobNumber,
	  tbl_jc.Reference,
	  tbl_jc.Date1,
	  tbl_jc.Date2,
	  tbl_jc.JobId
  FROM
	  tbl_companies
  INNER JOIN tbl_sites ON tbl_companies.Id = tbl_sites.Company
  INNER JOIN tbl_engineers ON tbl_sites.EngineerId = tbl_engineers.Id
  INNER JOIN tbl_jc ON tbl_engineers.CompanyId = tbl_jc.CompanyId
  WHERE
	  tbl_jc.CompanyId = '$companyid'
	  AND tbl_jc.Reference = '$ref'
	  AND (tbl_jc.`Status` = '1'
	  OR tbl_jc.`Status` = '2'
	  OR tbl_jc.`Status` = '3')
  GROUP BY
	  tbl_jc.JobId";
$query_sla = mysqli_query($con, $query_sla)or die(mysqli_error($con));
$row_sla = mysqli_fetch_array($query_sla);

require('mc_table.php');

$pdf=new PDF_MC_Table('P', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetDrawColor(166,202,240);
$pdf->SetTextColor(237,28,36);
$pdf->Image('quote-banner.jpg',10,4,190);
$pdf->Ln(18);
$pdf->Cell(140,10,'');
$pdf->Ln(25);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,10,'SLA Report','','','C');
$pdf->Ln(8);
$pdf->Cell(140,10,'');
$pdf->Ln(5);
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(166,202,240);
	$pdf->SetWidths(array(30,160));
    $pdf->Row(array('Attention',$row_sla['Reference']));
    $pdf->Row(array('Client',$row_sla['CompanyName']));
    $pdf->Row(array('Date',date('Y-m-d')));

    $pdf->SetDrawColor(255,255,255);

$pdf->Ln(10);

// Labour
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 103, 171);

$pdf->SetDrawColor(123,181,242);
$pdf->SetWidths(array(20,50,30,30,60));
$pdf->Row(array('Job No.','Site','Received Date','Req Completion','Comments'));

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial','',8);

$query = "
  SELECT
	  tbl_companies.Name AS CompanyName,
	  tbl_sites.Name AS SiteName,
	  tbl_engineers.Name AS EngineerName,
	  tbl_engineers.Email,
	  tbl_jc.JobNo,
	  tbl_jc.Date1,
	  tbl_jc.Date2,
	  tbl_jc.SLAComments,
	  tbl_jc.SLAClose,
	  tbl_jc.JobDescription,
	  tbl_jc.JobId
  FROM
	  tbl_engineers
  INNER JOIN tbl_jc ON tbl_engineers.`Name` = tbl_jc.Reference
  INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
  INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
  WHERE
	  tbl_jc.CompanyId = '$companyid'
	  AND tbl_jc.Reference = '$ref'
	  AND (tbl_jc.`Status` = '1'
	  OR tbl_jc.`Status` = '2'
	  OR tbl_jc.`Status` = '3')

  GROUP BY
	  tbl_jc.JobId";

$query = mysqli_query($con, $query)or die(mysql_error());
while($row = mysqli_fetch_array($query)){
	
	if($row['SLAClose'] == 1){
		
		$pdf->SetTextColor(42,145,51);
		
	} else {
		
		$pdf->SetTextColor(153,0,0);
		
	}
	
	$pdf->SetWidths(array(20,50,30,30,60));
    $pdf->Row(array($row['JobNo'],$row['SiteName'],$row['Date1'],$row['Date2'],$row['SLAComments']));
	$pdf->SetWidths(array(190));
    $pdf->Row(array($row['JobDescription']));
}

$pdf->Ln(10);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(255,255,255);
$pdf->Cell(190,10,'Please Note: Jobs highlighted in green are complete.','','','C');



$_SESSION['pdf'] = "SLA Report ". $row_sla['CompanyName'] . " " . $row_sla['Reference'] . " " . date('Y-m-d') .".pdf";
	
//$pdf->Output();
$pdf->Output("pdf/" . $_SESSION['pdf']);

header('location: pdf/sla_report_mail.php');


mysqli_free_result($query_sla);
mysqli_free_result($query_sla_report);
mysqli_free_result($query);
?>