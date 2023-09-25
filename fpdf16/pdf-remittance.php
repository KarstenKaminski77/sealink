<?php
session_start();

//include fpdf class
require_once("tfpdf.php");

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$id = $_GET['Id'];

$query = mysqli_query($con, "SELECT tbl_sites.Name AS Name_1, tbl_remittance_details.Amount AS Amount_1, tbl_remittance.Id, tbl_remittance.Date, 
tbl_remittance.Amount, tbl_remittance.Email, tbl_remittance.Message, tbl_remittance_details.InvoiceNo, tbl_companies.Name, tbl_remittance_details.JobId,
tbl_remittance_details.InvoiceDate
FROM (((tbl_remittance
LEFT JOIN tbl_remittance_details ON tbl_remittance_details.RemittanceId=tbl_remittance.Id)
LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_remittance.CompanyId)
LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_remittance_details.SiteId)
WHERE tbl_remittance.Id = '$id'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);


/**
 * myfpdf extends fpdf class, it is used to draw the header and footer
 */
require_once ("mypdf-table.php");

//Tag Based Multicell Class
require_once ("classes/tfpdftable.php");

//define some background colors
$aBgColor1 = array(0, 100, 67);
$aBgColor2 = array(165, 250, 220);
$aBgColor3 = array(255, 252, 249);
$aBgColor4 = array(86, 155, 225);
$aBgColor5 = array(207, 247, 239);
$aBgColor6 = array(246, 211, 207);
$bg_color7 = array(216, 243, 228);
$bg_color8 = array(255, 255, 255);

//create the fpdf object and do some initialization
$oPdf = new myPdf();
$oPdf->Open();
$oPdf->SetAutoPageBreak(true, 20);
$oPdf->SetMargins(10, 20, 20);
	
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
	$oPdf->SetTextColor(0, 132, 181);
	$oPdf->Image('logo.jpg',10,10,33);
	$oPdf->SetFont('Arial','B',16);
	$oPdf->Cell(190,10,'Remittance Advice: '. $row['Id'] .'','','','R');
	
	$oPdf->SetTextColor(0, 0, 0);
	$oPdf->SetFont('Arial','',8);
	$oPdf->Ln(20);
	$oPdf->Cell(190,10,'Date: '. $row['Date'] .'','','','R');
	$oPdf->Ln(25);
	
	$nColumns = 3;
	
	//Initialize the table class, 3 columns
	$oTable->initialize(array(63,63,64),$aCustomConfiguration);
	
	$aRow = array();

	$aRow[0]['TEXT_SIZE'] = '8';
	$aRow[1]['TEXT_SIZE'] = '8';
	$aRow[2]['TEXT_SIZE'] = '8';
	$aRow[0]['TEXT_ALIGN'] = "L";
	$aRow[1]['TEXT_ALIGN'] = "L";
	$aRow[2]['TEXT_ALIGN'] = "R";
	$aRow[0]['BORDER_TYPE'] = '';
	$aRow[1]['BORDER_TYPE'] = '';
	$aRow[2]['BORDER_TYPE'] = '';

	$aRow[0]['TEXT'] = 'P.O.BOX 201153'; 
	$aRow[1]['TEXT'] =  'VAT NO: 4230211908';
	$aRow[2]['TEXT'] =  'Tel : (031) 5637735';
	
	$oTable->addRow($aRow);	

	$aRow[0]['TEXT'] = 'Durban North'; 
	$aRow[1]['TEXT'] =  '';
	$aRow[2]['TEXT'] =  'Fax : 0865 191 153';
	
	$oTable->addRow($aRow);	

	$aRow[0]['TEXT'] = ''; 
	$aRow[1]['TEXT'] =  '';
	$aRow[2]['TEXT'] =  'email : hemi@seavest.co.za';
	
	$oTable->addRow($aRow);	

	$aRow[0]['TEXT'] = 'Date'; 
	$aRow[1]['TEXT'] =  '';
	
	$oTable->addRow($aRow);	
	
	//close the table
	$oTable->close();
	
	$oPdf->Ln(15);
	
	$nColumns = 1;
	
	//Initialize the table class, 3 columns
	$oTable->initialize(array(170,22),$aCustomConfiguration);
	
	$aHeader = array();
	
	//Table Header
		
	$aHeader[0]['TEXT_ALIGN'] = "L";
	$aHeader[0]['COLSPAN'] = '2';
	$aHeader[0]['TEXT'] = 'Overview';
	$aRow[0]['BORDER_TYPE'] = 'T';
	$aRow[0]['BORDER_SIZE'] = '0.1';
		
	//add the header
	$oTable->addHeader($aHeader);
	
	$aRow = array();
	
	$aRow[0]['COLSPAN'] = '2';
	$aRow[0]['TEXT_ALIGN'] = "L";
	//$aRow[0]['BACKGROUND_COLOR'] = array(250,250,250);
	$aRow[0]['TEXT'] = '';
	
	$oTable->addRow($aRow);
	
	$aRow = array();

	$aRow[0]['TEXT'] = '';
	$aRow[1]['TEXT'] = '';
	$aRow[0]['BORDER_TYPE'] = 'T';
	$aRow[1]['BORDER_TYPE'] = 'LRTB';
	$aRow[0]['BORDER_SIZE'] = '0.1';
	$aRow[1]['BACKGROUND_COLOR'] = array(166,202,240);
	
	$oTable->addRow($aRow);
	
	//close the table
	$oTable->close();
	
	$oPdf->Ln(15);
	
	$nColumns = 5;
	
	//Initialize the table class, 3 columns
	$oTable->initialize(array(110,20,20,20,22),$aCustomConfiguration);
	
	$aHeader = array();
	
	//Table Header
	
	$names = array('Labour','Unit','Qty','Unit Price','Total');
	for ($i = 0; $i < $nColumns; $i ++) {
		
		$aHeader[0]['TEXT_ALIGN'] = "L";
		$aHeader[1]['TEXT_ALIGN'] = "L";
		$aHeader[2]['TEXT_ALIGN'] = "C";
		$aHeader[3]['TEXT_ALIGN'] = "R";
		$aHeader[4]['TEXT_ALIGN'] = "R";
		
		$headings = $names[$i];
		$aHeader[$i]['TEXT'] = $headings;
	}
		
	//add the header
	$oTable->addHeader($aHeader);
	
	$aRow = array();
	$x = 0;
			
		$x++;
		
		//if($x % 2 == 0){
			
			//$aRow[0]['BACKGROUND_COLOR'] = array(233,233,233);
			//$aRow[1]['BACKGROUND_COLOR'] = array(233,233,233);
			//$aRow[2]['BACKGROUND_COLOR'] = array(233,233,233);
			//$aRow[3]['BACKGROUND_COLOR'] = array(233,233,233);
			//$aRow[4]['BACKGROUND_COLOR'] = array(233,233,233);
			
		//} else {
			
			//$aRow[0]['BACKGROUND_COLOR'] = array(250,250,250);
			//$aRow[1]['BACKGROUND_COLOR'] = array(250,250,250);
			//$aRow[2]['BACKGROUND_COLOR'] = array(250,250,250);
			//$aRow[3]['BACKGROUND_COLOR'] = array(250,250,250);
			//$aRow[4]['BACKGROUND_COLOR'] = array(250,250,250);

		//}

		$aRow[0]['TEXT_ALIGN'] = "L";
		$aRow[0]['TEXT'] = '';

		$aRow[1]['TEXT_ALIGN'] = "L";
		$aRow[1]['TEXT'] = 'Hours';

		$aRow[2]['TEXT_ALIGN'] = "C";
		$aRow[2]['TEXT'] = '';

		$aRow[3]['TEXT_ALIGN'] = "R";
		$aRow[3]['TEXT'] = 'R'. '';

		$aRow[4]['TEXT_ALIGN'] = "R";
		$aRow[4]['TEXT'] = 'R'. '';
		
		$oTable->addRow($aRow);
			
	$aRow = array();
	
	$aRow[0]['BACKGROUND_COLOR'] = array(255,255,255);
	$aRow[4]['BACKGROUND_COLOR'] = array(255,255,255);
	$aRow[0]['BORDER_TYPE'] = 'T';
	$aRow[0]['COLSPAN'] = '4';
	$aRow[4]['TEXT_ALIGN'] = "R"; 
	$aRow[4]['TEXT_COLOR'] = array(0,0,0);
	$aRow[4]['TEXT_TYPE'] = 'B';
	$aRow[4]['TEXT'] = ''; 
	
	$oTable->addRow($aRow);
	
	//close the table
	$oTable->close();
	
	$oPdf->Ln(15);
	
	$nColumns = 5;
	
	//Initialize the table class, 3 columns
	$oTable->initialize(array(110,20,20,20,22),$aCustomConfiguration);
	
	$aHeader = array();
	
	//Table Header
		
	$names = array('Material','Unit','Qty','Unit Price','Total');
	for ($i = 0; $i < $nColumns; $i ++) {
		
		$aHeader[0]['TEXT_ALIGN'] = "L";
		$aHeader[1]['TEXT_ALIGN'] = "L";
		$aHeader[2]['TEXT_ALIGN'] = "C";
		$aHeader[3]['TEXT_ALIGN'] = "R";
		$aHeader[4]['TEXT_ALIGN'] = "R";
		
		$headings = $names[$i];
		$aHeader[$i]['TEXT'] = $headings;
	}
			
	$oPdf->Ln(15);
		
	$nColumns = 5;
	
	
	//send the pdf to the browser
			
		$oPdf->Output();
				
		//$oPdf->Output('test.pdf');
	
	    //header('Location: ../qs-select.php?Status='. $_GET['Status']);
		
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
