<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
//include fpdf class
require_once("tfpdf.php");

// Connect To The Database
require_once('../functions/functions.php');


/**
 * myfpdf extends fpdf class, it is used to draw the header and footer
 */
require_once ("mypdf-table.php");

//Tag Based Multicell Class
require_once ("classes/tfpdftable.php");

$jobid = $_GET['Id'];

$query_report = "
  SELECT
	  tbl_stat_reports.Id,
	  tbl_stat_reports.Creator,
	  tbl_stat_reports.SlaCatId,
	  tbl_stat_reports.Company,
	  tbl_stat_reports.Site AS SiteName,
	  tbl_stat_reports.DateFrom,
	  tbl_stat_reports.DateTo,
	  tbl_stat_reports.Date,
	  tbl_stat_reports.PDF,
	  tbl_sites.Site,
	  tbl_companies.`Name` AS CompanyName,
	  tbl_sla_cat.Category AS SLA,
	  tbl_stat_reports.SlaSubCatId,
	  tbl_sla_subcat.SubCat
  FROM
	  tbl_stat_reports
  LEFT JOIN tbl_sites ON tbl_stat_reports.Site = tbl_sites.Id
  LEFT JOIN tbl_sla_cat ON tbl_stat_reports.SlaCatId = tbl_sla_cat.Id
  LEFT JOIN tbl_companies ON tbl_stat_reports.Company = tbl_companies.Id
  LEFT JOIN tbl_sla_subcat ON tbl_stat_reports.SlaSubCatId = tbl_sla_subcat.Id
  WHERE
	  tbl_stat_reports.Id = '". $_GET['Report'] ."'";
	
$query_report = mysqli_query($con, $query_report) or die(mysqli_error($con));
$row_report = mysqli_fetch_array($query_report);

$query_list = "
SELECT
	tbl_stat_report_details.InvoiceNo,
	tbl_companies.`Name` AS CompanyName,
	tbl_sla_cat.Id,
	tbl_sla_cat.Category AS SLA,
	tbl_stat_report_details.RootCause,
	tbl_stat_report_details.Date,
	tbl_stat_report_details.Total,
	tbl_sites.`Name` AS SiteName,
	tbl_stat_report_details.JobNo
FROM
	tbl_stat_report_details
LEFT JOIN tbl_companies ON tbl_stat_report_details.Company = tbl_companies.Id
LEFT JOIN tbl_sites ON tbl_stat_report_details.Site = tbl_sites.Id
LEFT JOIN tbl_sla_cat ON tbl_stat_report_details.SlaCatId = tbl_sla_cat.Id
WHERE
	tbl_stat_report_details.ReportId = '". $_GET['Report'] ."'
ORDER BY tbl_stat_report_details.RootCause ASC";
	
$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

//create the fpdf object and do some initialization
$oPdf = new myPdf('P','mm','Letter');
$oPdf->Open();
$oPdf->SetAutoPageBreak(true, 20);
$oPdf->SetMargins(10, 20, 20);

$oPdf->AddFont('dejavusans',   '',     'DejaVuSans.ttf',       true);
$oPdf->AddFont('dejavusans',   'B',    'DejaVuSans-Bold.ttf',  true);
$oPdf->AddFont('dejavusans',   'BI',   'DejaVuSans-BoldOblique.ttf', true);
$oPdf->AddFont('dejavuserif',  '',     'DejaVuSerif.ttf',      true);
$oPdf->AddFont('dejavuserif',  'B',    'DejaVuSerif-Bold.ttf', true);
$oPdf->AddFont('dejavuserif',  'BI',   'DejaVuSerif-BoldItalic.ttf', true);

$oPdf->AddPage();
$oPdf->AliasNbPages();
	
$oTable = new TfpdfTable($oPdf);

	$aCustomConfiguration = array(
        'TABLE' => array(
                'TABLE_ALIGN'       => 'L',                 //left align
                'BORDER_COLOR'      => array(166,202,240),      //border color
                'BORDER_SIZE'       => '0.1',               //border size
				'BORDER_TYPE'       => 'LRTB',
        ),
    
        'HEADER' => array(
                'TEXT_COLOR'        => array(0,102,170),   //text color
                'TEXT_SIZE'         => 9,                   //font size
                'LINE_SIZE'         => 6,                   //line size for one row
                'BACKGROUND_COLOR'  => array(255,255,255),  //background color
                'BORDER_SIZE'       => '0.1',                 //border size
                'BORDER_TYPE'       => 'LRTB',                 //border type, can be: 0, 1 or a combination of: "LRTB"
                'BORDER_COLOR'      => array(166,202,240),      //border color
        ),

        'ROW' => array(
                'TEXT_COLOR'        => array(0,0,0),        //text color
                'TEXT_SIZE'         => 8,                   //font size
                'BACKGROUND_COLOR'  => array(255,255,255),  //background color
                'BORDER_COLOR'      => array(166,202,240),     //border color
				'PADDING_TOP'       => 1,
				'PADDING_BOTTOM'       => 1,
				'PADDING_LEFT'       => 1,
				'PADDING_RIGHT'       => 1,
				'BORDER_SIZE'       => '0.1',
        ),
);
	

$oPdf->SetDrawColor(166,202,240);
$oPdf->SetTextColor(0,0,0);

$oPdf->Ln(30);

$oPdf->SetDrawColor(166,202,240);
$oPdf->SetTextColor(0,0,0);
$oPdf->Image('../images/logo.png',10,13,80);
$oPdf->SetFont('Arial','B',16);
$oPdf->Cell(190,10,'STATISTICS REPORT','','','C');

$oPdf->Ln(20);

$nColumns = 4;

//Initialize the table class, 3 columns
$oTable->initialize(array(30,65,30,65),$aCustomConfiguration);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['TEXT'] = 'Report No'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,64,246);
$aRow[0]['TEXT_TYPE'] = '';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = $row_report['Id']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Report Date'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,64,246);
$aRow[2]['TEXT_TYPE'] = '';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $row_report['Date']; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

//close the table
$oTable->close();
$oPdf->Ln(10);

$nColumns = 4;

//Initialize the table class, 3 columns
$oTable->initialize(array(30,65,30,65),$aCustomConfiguration);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['VERTICAL_ALIGN'] = 'T';
$aRow[0]['TEXT'] = 'Report To'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,64,246);
$aRow[0]['TEXT_TYPE'] = '';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = $row_report['CompanyName']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['VERTICAL_ALIGN'] = 'T';
$aRow[2]['TEXT'] = 'Site'; 
$aRow[2]['TEXT_ALIGN'] = "L";
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,64,246);
$aRow[2]['TEXT_TYPE'] = '';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);

if(empty($row_report['SiteName'])){
	
	$site = 'All Sites.';
	
} else {
	
	$site = $row_report['SiteName'];
}


$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $site; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

$aRow = array();


$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['VERTICAL_ALIGN'] = 'T';
$aRow[0]['TEXT'] = 'From'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,64,246);
$aRow[0]['TEXT_TYPE'] = '';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = $row_report['DateFrom']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'To'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,64,246);
$aRow[2]['TEXT_TYPE'] = '';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $row_report['DateTo']; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

$aRow = array();


$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['VERTICAL_ALIGN'] = 'T';
$aRow[0]['TEXT'] = 'Category'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,64,246);
$aRow[0]['TEXT_TYPE'] = '';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = $row_report['SLA']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Work Type'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,64,246);
$aRow[2]['TEXT_TYPE'] = '';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $row_report['SubCat']; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

//close the table
$oTable->close();

// $oPdf->Image('../Reports/JobStats/pie-chart-'. $_GET['Report'] .'.png',10,105,200,55);

$oPdf->Ln(60);

$oPdf->Ln(15);

$nColumns = 5;

//Initialize the table class, 3 columns
$oTable->initialize(array(25,30,60,25,25,25),$aCustomConfiguration);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['TEXT'] = 'Invoice'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[0]['TEXT_COLOR'] = array(0,64,246);
$aRow[0]['TEXT_TYPE'] = '';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = 'Job No.';
$aRow[1]['BACKGROUND_COLOR'] = array(227, 227, 227);
$aRow[1]['TEXT_COLOR'] = array(0, 64, 246);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102, 102, 102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Company'; 
$aRow[2]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[2]['TEXT_COLOR'] = array(0,64,246);
$aRow[2]['TEXT_TYPE'] = '';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = 'Site Address'; 
$aRow[3]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[3]['TEXT_COLOR'] = array(0,64,246);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'Date'; 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,64,246);
$aRow[4]['TEXT_TYPE'] = '';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'L';

$aRow[5]['BORDER_TYPE'] = 'LRTB';
$aRow[5]['TEXT'] = 'Total'; 
$aRow[5]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[5]['TEXT_COLOR'] = array(0,64,246);
$aRow[5]['TEXT_TYPE'] = '';
$aRow[5]['BORDER_COLOR'] = array(102,102,102);
$aRow[5]['TEXT_ALIGN'] = 'R';

$oTable->addRow($aRow);

//close the table
$oTable->close();

$oTable->initialize(array(25,30,60,25,25,25),$aCustomConfiguration);

$i = 0;

$count = mysqli_num_rows($query_list);

while($row_list = mysqli_fetch_array($query_list)){
	
	$i++;
	
	
	if($_SESSION['root'] != $row_list['RootCause']){
		
		if($i != 1){
			
			$aRow = array();
			
			$aRow[0]['BORDER_TYPE'] = 'LRTB';
			$aRow[0]['TEXT'] = 'R' . number_format($total,2); 
			$aRow[0]['TEXT_ALIGN'] = "R";
			$aRow[0]['BORDER_COLOR'] = array(102,102,102);
			$aRow[0]['COLSPAN'] = 6;
			$aRow[0]['TEXT_COLOR'] = array(0,64,246);
			$aRow[0]['TEXT_TYPE'] = 'B';
			
			$oTable->addRow($aRow);
		}
		
		$total = 0;
		
		$aRow = array();
		
		$aRow[0]['BORDER_TYPE'] = 'T';
		$aRow[0]['TEXT'] = '&nbsp;'; 
		$aRow[0]['TEXT_ALIGN'] = "L";
		$aRow[0]['BORDER_COLOR'] = array(102,102,102);
		$aRow[0]['COLSPAN'] = 6;
		
		$oTable->addRow($aRow);
		
		$aRow = array();
		
		$aRow[0]['BORDER_TYPE'] = 'LRTB';
		$aRow[0]['TEXT'] = $row_list['RootCause']; 
		$aRow[0]['TEXT_ALIGN'] = "R";
		$aRow[0]['BORDER_COLOR'] = array(102,102,102);
		$aRow[0]['COLSPAN'] = 6;
		$aRow[0]['TEXT_COLOR'] = array(0,64,246);
		$aRow[0]['TEXT_TYPE'] = '';
		
		$oTable->addRow($aRow);
		
	}
			
	$aRow = array();
	
	$aRow[0]['BORDER_TYPE'] = 'LRTB';
	$aRow[0]['TEXT'] = $row_list['InvoiceNo']; 
	$aRow[0]['TEXT_ALIGN'] = "L";
	$aRow[0]['BORDER_COLOR'] = array(102,102,102);

	$aRow[1]['BORDER_TYPE'] = 'LRTB';
	$aRow[1]['TEXT'] = $row_list['JobNo'];
	// $aRow[1]['TEXT'] = $row_list['RootCause'];
	$aRow[1]['BORDER_COLOR'] = array(102, 102, 102);
	$aRow[1]['TEXT_ALIGN'] = 'L';

	$aRow[2]['BORDER_TYPE'] = 'LRTB';
	$aRow[2]['TEXT'] = $row_list['CompanyName']; 
	$aRow[2]['BORDER_COLOR'] = array(102,102,102);
	$aRow[2]['TEXT_ALIGN'] = 'L';
	
	$aRow[3]['BORDER_TYPE'] = 'LRTB';
	$aRow[3]['TEXT'] = $row_list['SiteName']; 
	$aRow[3]['TEXT_ALIGN'] = "L";
	$aRow[3]['BORDER_COLOR'] = array(102,102,102);

	$aRow[4]['BORDER_TYPE'] = 'LRTB';
	$aRow[4]['TEXT'] = $row_list['Date']; 
	$aRow[4]['BORDER_COLOR'] = array(102,102,102);
	$aRow[4]['TEXT_ALIGN'] = 'L';

	$aRow[5]['BORDER_TYPE'] = 'LRTB';
	$aRow[5]['TEXT'] = ' R'. $row_list['Total']; 
	$aRow[5]['BORDER_COLOR'] = array(102,102,102);
	$aRow[5]['TEXT_ALIGN'] = 'R';

	$oTable->addRow($aRow);
	
	$_SESSION['root'] = $row_list['RootCause'];
	$total += $row_list['Total'];
	
	if($i == $count){
		
		$aRow = array();
		
		$aRow[0]['BORDER_TYPE'] = 'LRTB';
		$aRow[0]['TEXT'] = 'R' . number_format($total,2); 
		$aRow[0]['TEXT_ALIGN'] = "R";
		$aRow[0]['BORDER_COLOR'] = array(102,102,102);
		$aRow[0]['COLSPAN'] = 6;
		$aRow[0]['TEXT_COLOR'] = array(0,64,246);
		$aRow[0]['TEXT_TYPE'] = 'B';
		
		$oTable->addRow($aRow);
	}
		
}

$oTable->close();

$oPdf->Ln(20);
$oPdf->SetDrawColor(166,202,240);
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFont('Arial','',9);
$oPdf->Multicell(190,'6','Subject to our Standard Trading terms and conditions','','C');

//send the pdf to the browser

if(isset($_GET['Preview'])){
	
	$oPdf->Output();
		
} else {
	
	$_SESSION['pdf'] = 'Seavest-Statistics-Report-#'. $_GET['Report'] .'.pdf';
	
	$data = array('PDF' => $_SESSION['pdf']);
	
	dbUpdate('tbl_stat_reports', $data, "Id = '". $_GET['Report'] ."'", $con);
					
	//$oPdf->Output();
	$oPdf->Output('pdf/'. $_SESSION['pdf']);
		
	header('Location: ../Reports/JobStats/search.php?Success='. $_GET['Report']);
}
	


?>
