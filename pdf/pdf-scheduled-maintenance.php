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

$jobid = $_GET['Id'];

$vat_rate = getInvVatRate($con, $jobid) / 100;

// Update Sub Totals
$query_update = mysqli_query($con, "SELECT SUM(Total1) AS Total FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row_update = mysqli_fetch_array($query_update);

$query_transport = mysql_query("SELECT SUM(Total1) AS Total FROM tbl_travel WHERE JobId = '$jobid'") or die(mysql_error());
$row_transport = mysql_fetch_array($query_transport);

$sub_total = $row_update['Total'] + $row_transport['Total'];

mysqli_query($con, "UPDATE tbl_jc SET SubTotal = '$sub_total' WHERE JobId = '$jobid'")or die(mysqli_error($con));
// End Update Sub Total
	
$query_labour = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC") or die(mysqli_error($con));
$query_material = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1' ORDER BY Id ASC") or die(mysqli_error($con));
		
$query_invoice_to = "
	SELECT
		tbl_jc.*,
		tbl_companies.*
	FROM
		tbl_jc
	INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
	WHERE
		tbl_jc.JobId = '$jobid'
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
$aRow[1]['TEXT'] = $row_invoice_to['InvoiceNo']; 
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
$aRow[3]['TEXT'] = $row_invoice_to['InvoiceDate']; 
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
$aRow[0]['ROWSPAN'] = 3;
$aRow[0]['VERTICAL_ALIGN'] = 'T';
$aRow[0]['TEXT'] = 'Address'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[0]['TEXT_COLOR'] = array(0,0,0);
$aRow[0]['TEXT_TYPE'] = 'B';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['ROWSPAN'] = 3;
$aRow[1]['TEXT'] = $row_invoice_to['Address']; 
$aRow[1]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = '';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'L';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Job No'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,0,0);
$aRow[2]['TEXT_TYPE'] = 'B';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $row_invoice_to['JobNo']; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

$aRow = array();

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Order No'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,0,0);
$aRow[2]['TEXT_TYPE'] = 'B';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $row_invoice_to['RefNo']; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

$aRow = array();

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Reference'; 
$aRow[2]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[2]['TEXT_COLOR'] = array(0,0,0);
$aRow[2]['TEXT_TYPE'] = 'B';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'L';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = $row_invoice_to['Reference']; 
$aRow[3]['BACKGROUND_COLOR'] = array(255,255,255);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = '';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'L';

$oTable->addRow($aRow);

//close the table
$oTable->close();

$oPdf->Ln(15);

$nColumns = 5;

//Initialize the table class, 3 columns
$oTable->initialize(array(190),$aCustomConfiguration);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['TEXT'] = 'Description'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[0]['TEXT_COLOR'] = array(0,0,0);
$aRow[0]['TEXT_TYPE'] = 'B';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);


$oTable->addRow($aRow);

//close the table
$oTable->close();

$oTable->initialize(array(190),$aCustomConfiguration);

while($row_labour = mysqli_fetch_array($query_labour)){
			
	$aRow = array();
	
	$aRow[0]['BORDER_TYPE'] = 'LRTB';
	$aRow[0]['TEXT'] = $row_labour['Description']; 
	$aRow[0]['TEXT_ALIGN'] = "L";
	$aRow[0]['BORDER_COLOR'] = array(102,102,102);
	
	$oTable->addRow($aRow);
		
}

//close the table
$oTable->close();

$oPdf->Ln(15);

$nColumns = 5;

//Initialize the table class, 3 columns
$oTable->initialize(array(90,25,25,25,25),$aCustomConfiguration);

$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'LRTB';
$aRow[0]['TEXT'] = 'Description'; 
$aRow[0]['TEXT_ALIGN'] = "L";
$aRow[0]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[0]['TEXT_COLOR'] = array(0,0,0);
$aRow[0]['TEXT_TYPE'] = 'B';
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'LRTB';
$aRow[1]['TEXT'] = 'Qty'; 
$aRow[1]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[1]['TEXT_COLOR'] = array(0,0,0);
$aRow[1]['TEXT_TYPE'] = 'B';
$aRow[1]['BORDER_COLOR'] = array(102,102,102);
$aRow[1]['TEXT_ALIGN'] = 'C';

$aRow[2]['BORDER_TYPE'] = 'LRTB';
$aRow[2]['TEXT'] = 'Unit'; 
$aRow[2]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[2]['TEXT_COLOR'] = array(0,0,0);
$aRow[2]['TEXT_TYPE'] = 'B';
$aRow[2]['BORDER_COLOR'] = array(102,102,102);
$aRow[2]['TEXT_ALIGN'] = 'C';

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = 'Price'; 
$aRow[3]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = 'B';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'R';

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'Total'; 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = 'B';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

$oTable->addRow($aRow);

//close the table
$oTable->close();

$oTable->initialize(array(90,25,25,25,25),$aCustomConfiguration);

while($row_material = mysqli_fetch_array($query_material)){
			
	$aRow = array();
	
	$aRow[0]['BORDER_TYPE'] = 'LRTB';
	$aRow[0]['TEXT'] = $row_material['Description']; 
	$aRow[0]['TEXT_ALIGN'] = "L";
	$aRow[0]['BORDER_COLOR'] = array(102,102,102);
	
	$aRow[1]['BORDER_TYPE'] = 'LRTB';
	$aRow[1]['TEXT'] = $row_material['Qty']; 
	$aRow[1]['BORDER_COLOR'] = array(102,102,102);
	$aRow[1]['TEXT_ALIGN'] = 'C';
	
	$aRow[2]['BORDER_TYPE'] = 'LRTB';
	$aRow[2]['TEXT'] = $row_material['Unit']; 
	$aRow[2]['TEXT_ALIGN'] = "C";
	$aRow[2]['BORDER_COLOR'] = array(102,102,102);

	$aRow[3]['BORDER_TYPE'] = 'LRTB';
	$aRow[3]['TEXT'] = $row_material['Price']; 
	$aRow[3]['BORDER_COLOR'] = array(102,102,102);
	$aRow[3]['TEXT_ALIGN'] = 'R';

	$aRow[4]['BORDER_TYPE'] = 'LRTB';
	$aRow[4]['TEXT'] = $row_material['Total1']; 
	$aRow[4]['BORDER_COLOR'] = array(102,102,102);
	$aRow[4]['TEXT_ALIGN'] = 'R';

	$oTable->addRow($aRow);
		
}

// Totals
$query_sub_total = mysqli_query($con, "SELECT SUM(Total1) AS SubTotal FROM tbl_jc WHERE JobId = '". $_GET['Id'] ."'")or die(mysqli_error($con));
$row_sub_total = mysqli_fetch_array($query_sub_total);

$total = $row_sub_total['SubTotal'] + ($row_sub_total['SubTotal'] * $vat_rate);
$total_data = array(

    'Total2' => $total

);

dbUpdate('tbl_jc', $total_data, $where_clause="JobId = '". $_GET['Id'] ."'",$con);

// Sub Total
$aRow = array();

$aRow[0]['BORDER_TYPE'] = 'T';
$aRow[0]['TEXT'] = ''; 
$aRow[0]['BORDER_COLOR'] = array(102,102,102);

$aRow[1]['BORDER_TYPE'] = 'T';
$aRow[1]['TEXT'] = ''; 
$aRow[1]['BORDER_COLOR'] = array(102,102,102);

$aRow[2]['BORDER_TYPE'] = 'T';
$aRow[2]['TEXT'] = ''; 
$aRow[2]['BORDER_COLOR'] = array(102,102,102);

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = 'Sub Total'; 
$aRow[3]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = 'B';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'R';

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'R'. number_format($row_sub_total['SubTotal'],2); 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = '';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

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

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = 'VAT'; 
$aRow[3]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = 'B';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'R';

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'R'. number_format($row_sub_total['SubTotal'] * $vat_rate,2); 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = '';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

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

$aRow[3]['BORDER_TYPE'] = 'LRTB';
$aRow[3]['TEXT'] = 'Total'; 
$aRow[3]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[3]['TEXT_COLOR'] = array(0,0,0);
$aRow[3]['TEXT_TYPE'] = 'B';
$aRow[3]['BORDER_COLOR'] = array(102,102,102);
$aRow[3]['TEXT_ALIGN'] = 'R';

$aRow[4]['BORDER_TYPE'] = 'LRTB';
$aRow[4]['TEXT'] = 'R'. number_format($row_sub_total['SubTotal'] + ($row_sub_total['SubTotal'] * $vat_rate),2); 
$aRow[4]['BACKGROUND_COLOR'] = array(227,227,227);
$aRow[4]['TEXT_COLOR'] = array(0,0,0);
$aRow[4]['TEXT_TYPE'] = '';
$aRow[4]['BORDER_COLOR'] = array(102,102,102);
$aRow[4]['TEXT_ALIGN'] = 'R';

$oTable->addRow($aRow);

//close the table
$oTable->close();

$oPdf->Ln(20);
$oPdf->SetDrawColor(166,202,240);
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFont('Arial','',9);
$oPdf->Multicell(190,'6','Subject to our Standard Trading terms and conditions','','C');

//send the pdf to the browser

if(isset($_GET['Preview'])){
	
	$oPdf->Output();
	$oPdf->Output('pdf/'. $_SESSION['pdf']);
		
} else {
	
	$_SESSION['pdf'] = 'Seavest Tax Invoice #'. $row_invoice_to['InvoiceNo'] .'.pdf';
					
	//$oPdf->Output();
	$oPdf->Output('pdf/'. $_SESSION['pdf']);
	
	if(isset($_GET['Tax'])){
		
		header('Location:../invoices/scheduled-maintenance-email.php?Id='. $jobid .'&InvoiceNo='. $row_invoice_to['InvoiceNo']);
		
	} else {
		
		header('Location:../pro-forma/email.php?Id='. $jobid .'&InvoiceNo='. $row_invoice_to['InvoiceNo']);
		
	}
}
	


?>
