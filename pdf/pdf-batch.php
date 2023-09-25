<?php
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

$batchid = $_GET['Batch'];

$query_batch = "
	SELECT
		tbl_jc.JobNo
		, tbl_sites.Name
		, tbl_jc.Date
		, tbl_jc.BatchNo
		, tbl_jc.RefNo
		, tbl_jc.JobId
		, tbl_jc.NewInvoiceDate
	FROM
		tbl_jc
		INNER JOIN tbl_sites 
			ON (tbl_jc.SiteId = tbl_sites.Id)
	WHERE (tbl_jc.BatchNo = '$batchid')
	GROUP BY JobId";

$query_batch = mysqli_query($con, $query_batch)or die(mysqli_error($con));
		
$query_invoice_to = "
	SELECT
		tbl_jc.*,
		tbl_companies.*
	FROM
		tbl_jc
	INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
	WHERE
		tbl_jc.BatchNo = '$batchid'
	ORDER BY
		tbl_jc.Id ASC
	LIMIT 1";
			
$query_invoice_to = mysqli_query($con, $query_invoice_to)or die(mysqli_error($con));
$row_invoice_to = mysqli_fetch_array($query_invoice_to);
	
$query_supplier = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$supplierid'")or die(mysqli_error($con));
$row_supplier = mysqli_fetch_array($query_supplier);

//create the fpdf object and do some initialization
$oPdf = new myPdf();
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
$oPdf->Image('../images/logo.png',10,13,80);
$oPdf->SetFont('Arial','B',16);
$oPdf->Cell(190,10,'TAX INVOICE','','','R');
$oPdf->Ln(10);

$oPdf->SetDrawColor(166,202,240);
$oPdf->SetTextColor(0,0,0);

$oPdf->SetFont('Arial','',9);
$oPdf->Multicell(190,'5','P.O.BOX 201153
Durban North
4016','','R');
$oPdf->Ln(1);

$oPdf->Cell(95,5,'Tel: '. 'Tel : (031) 564 4568','','','L');
$oPdf->Ln(5);
$oPdf->Cell(95,5,'Fax : 0865 191 153','','','L');
$oPdf->Ln(5);
$oPdf->Cell(95,5,'Email: accounts@seavest.co.za','','','L');
$oPdf->Cell(95,5,'VAT NO: 4230211908','','','R');

$oPdf->Ln(20);

$nColumns = 4;

//Initialize the table class, 3 columns
$oTable->initialize(array(30,65,30,65),$aCustomConfiguration);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['TEXT'] = 'Invoice No'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,0,0);
$aRow[0]['TEXT_TYPE'] = 'B';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = 'B'. $_GET['Batch']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Invoice Date'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,0,0);
$aRow[2]['TEXT_TYPE'] = 'B';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = date('Y-m-d'); 
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
$aRow[0]['TEXT'] = 'Invoice To'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,0,0);
$aRow[0]['TEXT_TYPE'] = 'B';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = $row_invoice_to['Name']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'VAT No'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,0,0);
$aRow[2]['TEXT_TYPE'] = 'B';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $row_invoice_to['VATNO']; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['VERTICAL_ALIGN'] = 'T';
$aRow[0]['TEXT'] = 'Address'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,0,0);
$aRow[0]['TEXT_TYPE'] = 'B';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['COLSPAN'] = 3;
$aRow[1]['TEXT'] = $row_invoice_to['Address']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

//close the table
$oTable->close();

$oPdf->Ln(15);

$nColumns = 5;

//Initialize the table class, 3 columns
$oTable->initialize(array(31.66,31.66,31.66,31.66,31.66,31.66),$aCustomConfiguration);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['TEXT'] = 'Date'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[0]['TEXT_COLOR'] = array(0,0,0);
$aRow[0]['TEXT_TYPE'] = 'B';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = 'Ref. No.'; 
$aRow[1]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = 'B';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Description'; 
$aRow[2]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[2]['TEXT_COLOR'] = array(0,0,0);
$aRow[2]['TEXT_TYPE'] = 'B';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = 'Price Excl'; 
$aRow[3]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = 'B';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'R';

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'VAT'; 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = 'B';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

$aRow[5]['BORDER_TYPE'] = 'LRTB';
$aRow[5]['TEXT'] = 'Total Incl.'; 
$aRow[5]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[5]['TEXT_COLOR'] = array(0,0,0);
$aRow[5]['TEXT_TYPE'] = 'B';
$aRow[5]['BORDER_COLOR'] = array(102,102,102);
$aRow[5]['TEXT_ALIGN'] = 'R';

$oTable->addRow($aRow);

//close the table
$oTable->close();

$oPdf->Ln(5);

$oTable->initialize(array(31.66,31.66,31.66,31.66,31.66,31.66),$aCustomConfiguration);
$_SESSION['total-excl'] = 0;
$_SESSION['vat-due'] = 0;

while($row_batch = mysqli_fetch_array($query_batch)){
			
	$aRow = array();
	
	$aRow[0]['COLSPAN'] = 6;
	$aRow[0]['TEXT_TYPE'] = 'B';
	$aRow[0]['BORDER_TYPE'] = 'LRTB';
	$aRow[0]['TEXT'] = $row_batch['Name'] .' - ON: '. $row_batch['RefNo']; 
	$aRow[0]['TEXT_ALIGN'] = "L";
	$aRow[0]['BORDER_COLOR'] = array(102,102,102);
	
	$oTable->addRow($aRow);
	
	// Labour
	$query_labour = mysqli_query($con, "SELECT JobNo, SUM(Total1) AS LabourTotal, JcDate FROM tbl_jc WHERE JobId = '". $row_batch['JobId'] ."' AND Labour = '1' ORDER BY Id ASC") or die(mysqli_error($con));
	$row_labour = mysqli_fetch_array($query_labour);
	
	$_SESSION['jobno'] = $row_labour['JobNo'];
	$_SESSION['date'] = $row_labour['JcDate'];
	
	$aRow2 = array();

	$aRow2[0]['BORDER_TYPE'] = 'LRTB';
	$aRow2[0]['TEXT'] = $_SESSION['date']; 
	$aRow2[0]['TEXT_ALIGN'] = "L";
	$aRow2[0]['BORDER_COLOR'] = array(102,102,102);

	$aRow2[1]['BORDER_TYPE'] = 'LRTB';
	$aRow2[1]['TEXT'] = $_SESSION['jobno']; 
	$aRow2[1]['TEXT_ALIGN'] = "L";
	$aRow2[1]['BORDER_COLOR'] = array(102,102,102);
	
	$aRow2[2]['BORDER_TYPE'] = 'LRTB';
	$aRow2[2]['TEXT'] = 'Labour'; 
	$aRow2[2]['BORDER_COLOR'] = array(102,102,102);
	$aRow2[2]['TEXT_ALIGN'] = 'L';
	
	$aRow2[3]['BORDER_TYPE'] = 'LRTB';
	$aRow2[3]['TEXT'] = 'R'. number_format($row_labour['LabourTotal'],2); 
	$aRow2[3]['TEXT_ALIGN'] = "R";
	$aRow2[3]['BORDER_COLOR'] = array(102,102,102);
	//var_dump($row_batch['JobId']);
	$vat_rate = getInvVatRate($con, $row_batch['JobId']) / 100;
//var_dump($vat_rate . ' ' . $_SESSION['jobno'] . ' ' . $row_batch['NewInvoiceDate']);
	$aRow2[4]['BORDER_TYPE'] = 'LRTB';
	$aRow2[4]['TEXT'] = 'R'. number_format($row_labour['LabourTotal'] * $vat_rate,2); 
	$aRow2[4]['BORDER_COLOR'] = array(102,102,102);
	$aRow2[4]['TEXT_ALIGN'] = 'R';

	$aRow2[5]['BORDER_TYPE'] = 'LRTB';
	$aRow2[5]['TEXT'] = 'R'. number_format($row_labour['LabourTotal'] * $vat_rate + $row_labour['LabourTotal'],2); 
	$aRow2[5]['BORDER_COLOR'] = array(102,102,102);
	$aRow2[5]['TEXT_ALIGN'] = 'R';
	
	$_SESSION['total-excl'] += $row_labour['LabourTotal'];

	$oTable->addRow($aRow2);
	
	// Material
	$query_material = mysqli_query($con, "SELECT JobNo, SUM(Total1) AS MaterialTotal  FROM tbl_jc WHERE JobId = '". $row_batch['JobId'] ."' AND Material = '1' ORDER BY Id ASC") or die(mysqli_error($con));
	$row_material = mysqli_fetch_array($query_material);
	
	$aRow3 = array();

	$aRow3[0]['BORDER_TYPE'] = 'LRTB';
	$aRow3[0]['TEXT'] = $_SESSION['date']; 
	$aRow3[0]['TEXT_ALIGN'] = "L";
	$aRow3[0]['BORDER_COLOR'] = array(102,102,102);

	$aRow3[1]['BORDER_TYPE'] = 'LRTB';
	$aRow3[1]['TEXT'] = $_SESSION['jobno']; 
	$aRow3[1]['TEXT_ALIGN'] = "L";
	$aRow3[1]['BORDER_COLOR'] = array(102,102,102);
	
	$aRow3[2]['BORDER_TYPE'] = 'LRTB';
	$aRow3[2]['TEXT'] = 'Material'; 
	$aRow3[2]['BORDER_COLOR'] = array(102,102,102);
	$aRow3[2]['TEXT_ALIGN'] = 'L';
	
	$aRow3[3]['BORDER_TYPE'] = 'LRTB';
	$aRow3[3]['TEXT'] = 'R'. number_format($row_material['MaterialTotal'],2); 
	$aRow3[3]['TEXT_ALIGN'] = "R";
	$aRow3[3]['BORDER_COLOR'] = array(102,102,102);

	$aRow3[4]['BORDER_TYPE'] = 'LRTB';
	$aRow3[4]['TEXT'] = 'R'. number_format($row_material['MaterialTotal'] * $vat_rate,2); 
	$aRow3[4]['BORDER_COLOR'] = array(102,102,102);
	$aRow3[4]['TEXT_ALIGN'] = 'R';

	$aRow3[5]['BORDER_TYPE'] = 'LRTB';
	$aRow3[5]['TEXT'] = 'R'. number_format($row_material['MaterialTotal'] * $vat_rate + $row_material['MaterialTotal'],2); 
	$aRow3[5]['BORDER_COLOR'] = array(102,102,102);
	$aRow3[5]['TEXT_ALIGN'] = 'R';
	
	$_SESSION['total-excl'] += $row_material['MaterialTotal'];

	$oTable->addRow($aRow3);
	
	// Transport
	$query_transport = mysqli_query($con, "SELECT JobNo, SUM(Total1) AS TransportTotal  FROM tbl_travel WHERE JobId = '". $row_batch['JobId'] ."' ORDER BY Id ASC") or die(mysqli_error($con));
	$row_transport = mysqli_fetch_array($query_transport);
	
	$aRow3 = array();

	$aRow3[0]['BORDER_TYPE'] = 'LRTB';
	$aRow3[0]['TEXT'] = $_SESSION['date']; 
	$aRow3[0]['TEXT_ALIGN'] = "L";
	$aRow3[0]['BORDER_COLOR'] = array(102,102,102);

	$aRow3[1]['BORDER_TYPE'] = 'LRTB';
	$aRow3[1]['TEXT'] = $_SESSION['jobno']; 
	$aRow3[1]['TEXT_ALIGN'] = "L";
	$aRow3[1]['BORDER_COLOR'] = array(102,102,102);
	
	$aRow3[2]['BORDER_TYPE'] = 'LRTB';
	$aRow3[2]['TEXT'] = 'Travel'; 
	$aRow3[2]['BORDER_COLOR'] = array(102,102,102);
	$aRow3[2]['TEXT_ALIGN'] = 'L';
	
	$aRow3[3]['BORDER_TYPE'] = 'LRTB';
	$aRow3[3]['TEXT'] = 'R'. number_format($row_transport['TransportTotal'],2); 
	$aRow3[3]['TEXT_ALIGN'] = "R";
	$aRow3[3]['BORDER_COLOR'] = array(102,102,102);

	$aRow3[4]['BORDER_TYPE'] = 'LRTB';
	$aRow3[4]['TEXT'] = 'R'. number_format($row_transport['TransportTotal'] * $vat_rate,2); 
	$aRow3[4]['BORDER_COLOR'] = array(102,102,102);
	$aRow3[4]['TEXT_ALIGN'] = 'R';

	$aRow3[5]['BORDER_TYPE'] = 'LRTB';
	$aRow3[5]['TEXT'] = 'R'. number_format($row_transport['TransportTotal'] * $vat_rate + $row_transport['TransportTotal'],2); 
	$aRow3[5]['BORDER_COLOR'] = array(102,102,102);
	$aRow3[5]['TEXT_ALIGN'] = 'R';
	
	$_SESSION['total-excl'] += $row_transport['TransportTotal'];

	$oTable->addRow($aRow3);
	
	// Totals
	$aRow = array();
	
	$subtotal = $row_labour['LabourTotal'] + $row_material['MaterialTotal'] + $row_transport['TransportTotal'];
	$vat = $subtotal * $vat_rate;
	$_SESSION['vat-due'] += $vat;
	$total_incl = $subtotal + $vat;
	
	$aRow[0]['BORDER_TYPE'] = 'T';
	$aRow[0]['COLSPAN'] = 3; 
	$aRow[0]['TEXT'] = '&nbsp;'; 
	$aRow[0]['TEXT_ALIGN'] = "L";
	$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
	$aRow[0]['BORDER_COLOR'] = array(102,102,102);
	
	$aRow[3]['BORDER_TYPE'] = 'LRTB';
	$aRow[3]['TEXT'] = 'R' . number_format($subtotal,2); 
	$aRow[3]['BACKGROUND_COLOR'] = array(227,227,227);
	$aRow[3]['TEXT_COLOR'] = array(0,0,0);
	$aRow[3]['TEXT_TYPE'] = 'B';
	$aRow[3]['BORDER_COLOR'] = array(102,102,102);
	$aRow[3]['TEXT_ALIGN'] = 'R';
	
	$aRow[4]['BORDER_TYPE'] = 'LRTB';
	$aRow[4]['TEXT'] = 'R' . number_format($vat, 2); 
	$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
	$aRow[4]['TEXT_COLOR'] = array(0,0,0);
	$aRow[4]['TEXT_TYPE'] = 'B';
	$aRow[4]['BORDER_COLOR'] = array(102,102,102);
	$aRow[4]['TEXT_ALIGN'] = 'R';
	
	$aRow[5]['BORDER_TYPE'] = 'LRTB';
	$aRow[5]['TEXT'] = 'R' . number_format($total_incl,2); 
	$aRow[5]['BACKGROUND_COLOR'] = array(227,227,227);
	$aRow[5]['TEXT_COLOR'] = array(0,0,0);
	$aRow[5]['TEXT_TYPE'] = 'B';
	$aRow[5]['BORDER_COLOR'] = array(102,102,102);
	$aRow[5]['TEXT_ALIGN'] = 'R';
	
	$oTable->addRow($aRow);
	// End Totals
			
	$aRow4 = array();
	
	$aRow4[0]['COLSPAN'] = 3;
	$aRow4[0]['BORDER_TYPE'] = '';
	$aRow4[0]['TEXT'] = '&nbsp;'; 
	$aRow4[0]['TEXT_ALIGN'] = "L";
	$aRow4[0]['BORDER_COLOR'] = array(102,102,102);
	
	$aRow4[3]['COLSPAN'] = 3;
	$aRow4[3]['BORDER_TYPE'] = 'T';
	$aRow4[3]['TEXT'] = '&nbsp;'; 
	$aRow4[3]['TEXT_ALIGN'] = "L";
	$aRow4[3]['BORDER_COLOR'] = array(102,102,102);
	
	$oTable->addRow($aRow4);
		
}

// Sub Total
$aRow = array();

$aRow[0]['BORDER_TYPE'] = '';
$aRow[0]['TEXT'] = ''; 
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = '';
$aRow[1]['TEXT'] = ''; 
$aRow[1]['BORDER_COLOR'] = array(102,102,102);

$aRow[2]['BORDER_TYPE'] = '';
$aRow[2]['TEXT'] = ''; 
$aRow[2]['BORDER_COLOR'] = array(102,102,102);

$aRow[3]['BORDER_TYPE'] = '';
$aRow[3]['TEXT'] = ''; 
$aRow[3]['BORDER_COLOR'] = array(102,102,102);

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'Sub Total'; 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = 'B';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

$aRow[5]['BORDER_TYPE'] = 'LRTB';
$aRow[5]['TEXT'] = 'R'. number_format($_SESSION['total-excl'],2); 
$aRow[5]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[5]['TEXT_COLOR'] = array(0,0,0);
$aRow[5]['TEXT_TYPE'] = '';
$aRow[5]['BORDER_COLOR'] = array(102,102,102);
$aRow[5]['TEXT_ALIGN'] = 'R';

$oTable->addRow($aRow);

// VAT
$aRow = array();

$aRow[0]['BORDER_TYPE'] = '';
$aRow[0]['TEXT'] = ''; 
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = '';
$aRow[1]['TEXT'] = ''; 
$aRow[1]['BORDER_COLOR'] = array(102,102,102);

$aRow[2]['BORDER_TYPE'] = '';
$aRow[2]['TEXT'] = ''; 
$aRow[2]['BORDER_COLOR'] = array(102,102,102);

$aRow[3]['BORDER_TYPE'] = '';
$aRow[3]['TEXT'] = ''; 
$aRow[3]['BORDER_COLOR'] = array(102,102,102);

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'VAT'; 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = 'B';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

$aRow[5]['BORDER_TYPE'] = 'LRTB';
$aRow[5]['TEXT'] = 'R'. number_format($_SESSION['vat-due'],2); 
$aRow[5]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[5]['TEXT_COLOR'] = array(0,0,0);
$aRow[5]['TEXT_TYPE'] = '';
$aRow[5]['BORDER_COLOR'] = array(102,102,102);
$aRow[5]['TEXT_ALIGN'] = 'R';

$oTable->addRow($aRow);

// Total
$aRow = array();

$aRow[0]['BORDER_TYPE'] = '';
$aRow[0]['TEXT'] = ''; 
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = '';
$aRow[1]['TEXT'] = ''; 
$aRow[1]['BORDER_COLOR'] = array(102,102,102);

$aRow[2]['BORDER_TYPE'] = '';
$aRow[2]['TEXT'] = ''; 
$aRow[2]['BORDER_COLOR'] = array(102,102,102);

$aRow[3]['BORDER_TYPE'] = '';
$aRow[3]['TEXT'] = ''; 
$aRow[3]['BORDER_COLOR'] = array(102,102,102);

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'Total'; 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = 'B';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

$aRow[5]['BORDER_TYPE'] = 'LRTB';
$aRow[5]['TEXT'] = 'R'. number_format($_SESSION['total-excl'] + $_SESSION['vat-due'],2); 
$aRow[5]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[5]['TEXT_COLOR'] = array(0,0,0);
$aRow[5]['TEXT_TYPE'] = '';
$aRow[5]['BORDER_COLOR'] = array(102,102,102);
$aRow[5]['TEXT_ALIGN'] = 'R';

$oTable->addRow($aRow);

//close the table
$oTable->close();

$oPdf->Ln(20);
$oPdf->SetDrawColor(166,202,240);
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFont('Arial','',9);
$oPdf->Multicell(190,'6','Subject to our Standard Trading terms and conditions','','C');

$searchdate = date('Y m d');
$today = date('Y-m-d');
	
mysqli_query($con, "UPDATE tbl_jc SET Status = '12', Days = '$today', SearchDate = '$searchdate' WHERE BatchNo = '". $_GET['Batch'] ."'") or die(mysqli_error($con));

//send the pdf to the browser
if(isset($_GET['Preview'])){
	
	$_SESSION['pdf'] = 'Seavest Batch Invoice #'. $_GET['Batch'] .'.pdf';
					
	$oPdf->Output('pdf/'. $_SESSION['pdf']);
	
	$oPdf->Output();
	
} elseif(isset($_GET['Debtors'])){
	
	$_SESSION['pdf'] = 'Seavest Batch Invoice #'. $_GET['Batch'] .'.pdf';
					
	$oPdf->Output('pdf/'. $_SESSION['pdf']);
		
	header('Location: ../invoices/debtors.php?BatchRemoved='. $_GET['Batch']);
		
} else {
	
	$_SESSION['pdf'] = 'Seavest Batch Invoice #'. $_GET['Batch'] .'.pdf';
					
	$oPdf->Output('pdf/'. $_SESSION['pdf']);
		
	header('Location: ../invoices/debtors.php?Success='. $_GET['Batch']);
}
?>
