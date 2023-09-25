<?php
/** Error reporting */
error_reporting(E_ALL);

require_once('../functions/functions.php');

select_db();

/** Include path **/
ini_set('include_path', ini_get('include_path').'Classes/');

/** PHPExcel */
include 'Classes/PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'Classes/PHPExcel/Writer/Excel2007.php';

$query_batch = "
	SELECT 
	  tbl_jc.InvoiceNo,
	  tbl_jc.JobNo,
	  tbl_jc.JobId,
	  tbl_sites.Name AS SiteName,
	  tbl_jc.Date AS DateLogged,
	  tbl_jc.JobDescription,
	  tbl_sla_cat.Category,
	  tbl_jc.Qty,
	  tbl_jc.Price,
	  tbl_jc.SubTotal,
	  tbl_jc.VAT,
	  tbl_jc.Total2 
	FROM
	  tbl_jc 
	  INNER JOIN tbl_companies 
		ON (
		  tbl_jc.CompanyId = tbl_companies.Id
		) 
	  INNER JOIN tbl_sites 
		ON (
		  tbl_jc.SiteId = tbl_sites.Id
		) 
	  INNER JOIN tbl_sla_cat 
		ON (
		  tbl_jc.SlaCatId = tbl_sla_cat.Id
		)
	WHERE tbl_jc.BatchNo = '". $_GET['Batch'] ."'
	GROUP BY JobId";

$query_batch = mysqli_query($con, $query_batch)or die(mysqli_error($con));

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Sealink");
$objPHPExcel->getProperties()->setLastModifiedBy("Sealink");
$objPHPExcel->getProperties()->setTitle("Seavest Batch Invoice");
$objPHPExcel->getProperties()->setSubject("Seavest Batch Invoice");
$objPHPExcel->getProperties()->setDescription("Seavest Batch Invoice");


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(40);

$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->getStartColor()->setARGB('19456f');
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Sevest Ref.');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Invoice No.');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Site Name');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Date Logged');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Issue / Incident Description');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Category');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Solution: Reason For ADHOC Billing');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', '#R');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Labour Hrs.');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Rate');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Labour Ttl.');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Material Ttl.');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Material Description');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Mileage (Km)');
$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Mileage Ttl');
$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Inv Excl.');
$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'TAX');
$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Inclusive');
$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Approved');
$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Seavest Comments');
$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'BP Comments');

// Initiate counter
$i = 1;

while($row_batch = mysqli_fetch_array($query_batch)){

	$i++;
	
	$_SESSION['material'] = '';
	$_SESSION['labour'] = '';
	
	$query_material = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '". $row_batch['JobId'] ."' AND Material = '1'")or die(mysqli_error($con));
	while($row_material = mysqli_fetch_array($query_material)){
		
		$_SESSION['material'] .= $row_material['Description'] .', ';
	}
	
	$query_labour = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '". $row_batch['JobId'] ."' AND labour = '1'")or die(mysqli_error($con));
	while($row_labour = mysqli_fetch_array($query_labour)){
		
		$_SESSION['labour'] .= $row_labour['Description'] .', ';
	}
	
	$query_labour_rate = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '". $row_batch['JobId'] ."' AND labour = '1' AND Price >= '1'")or die(mysqli_error($con));
	$row_labour_rate = mysqli_fetch_array($query_labour_rate);
	
	// VAT Rate
	$vat_rate = getInvVatRate($con, $row_batch['JobId']) / 100;
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $row_batch['JobNo']);
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i , 'B' . $_GET['Batch']);
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i , $row_batch['SiteName']);
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i , $row_batch['DateLogged']);
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i , $row_batch['JobDescription']);
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i , $row_batch['Category']);
	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i , $_SESSION['labour']);
	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i , '');
	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i , batch_labour_totals($con, $row_batch['JobId'], 'Qty'));
	$objPHPExcel->getActiveSheet()->SetCellValue('J'.$i , $row_labour_rate['Price']);
	$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i , batch_labour_totals($con, $row_batch['JobId'], 'Total1'));
	$objPHPExcel->getActiveSheet()->SetCellValue('L'.$i , batch_material_totals($con, $row_batch['JobId'], 'Total1'));
	$objPHPExcel->getActiveSheet()->SetCellValue('M'.$i , $_SESSION['material']);
	$objPHPExcel->getActiveSheet()->SetCellValue('N'.$i , batch_transport_totals($con, $row_batch['JobId'], 'Mileage'));
	$objPHPExcel->getActiveSheet()->SetCellValue('O'.$i , batch_transport_totals($con, $row_batch['JobId'], 'Total'));
	$objPHPExcel->getActiveSheet()->SetCellValue('P'.$i , $row_batch['SubTotal']);
	$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$i , $row_batch['SubTotal'] * $vat_rate);
	$objPHPExcel->getActiveSheet()->SetCellValue('R'.$i , $row_batch['Total2']);
	$objPHPExcel->getActiveSheet()->SetCellValue('S'.$i , '');
	$objPHPExcel->getActiveSheet()->SetCellValue('T'.$i , '');
	$objPHPExcel->getActiveSheet()->SetCellValue('U'.$i , '');

}

$end = mysqli_num_rows($query_batch) + 1;
$write = mysqli_num_rows($query_batch) + 2;

$objPHPExcel->getActiveSheet()->getStyle("I". $write .":R". $write)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle("I". $write .":R". $write)->getFill()->getStartColor()->setARGB('c5d9f1');
$objPHPExcel->getActiveSheet()->getStyle("I". $write .":R". $write)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle("I". $write .":R". $write)->getFont()->setBold(true);

// Total Labour Hours
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'I'. $write,
        '=SUM(I2:I'. $end .')'
    );

// Total Labour
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'K'. $write,
        '=SUM(K2:K'. $end .')'
    );

// tOTAL mATERIAL
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'L'. $write,
        '=SUM(L2:L'. $end .')'
    );

// Mileage
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'N'. $write,
        '=SUM(N2:N'. $end .')'
    );

// Mileage Total
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'O'. $write,
        '=SUM(O2:O'. $end .')'
    );

// Excl
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'P'. $write,
        '=SUM(P2:P'. $end .')'
    );

// VAT
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'Q'. $write,
        '=SUM(Q2:Q'. $end .')'
    );

// Incl
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'R'. $write,
        '=SUM(R2:R'. $end .')'
    );

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$objWriter->save('Seavest-Batch-Invoice-'. $_GET['Batch'].'.xlsx');

if(isset($_GET['Debtors'])){
	
	header('Location: ../pdf/pdf-batch.php?Batch='. $_GET['Batch'] .'&Debtors');
	
} else {
	
	header('Location: ../pdf/pdf-batch.php?Batch='. $_GET['Batch']);
	
}
?>