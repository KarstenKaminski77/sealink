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

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");


// Add some data

$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'InvoiceNumber');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Day');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Month');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Year');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Work Order No');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Day');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Month');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Year');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'HH');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'mm');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Work Performed');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'FollowupWorkRequired');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Cost Element');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Description');
$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Qty');
$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Unit');
$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Unit Price - Incl Markup');
$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Discount/Mark-up');
$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'VAT');
$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Total Excl. VAT');
$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Component');
$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'FailureTypeCode');
$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'Failure');
$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'RootCause');
$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'Repair');
$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'PTW Required');
$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'PTW Number');

// Initiate counter
$i = 1;

$query = mysql_query("SELECT InvoiceNo, STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS new_date FROM tbl_jc WHERE JobNo LIKE 'FMC%' GROUP BY JobId")or die(mysql_error());
while($row = mysql_fetch_array($query)){

$i++;
$date = $row['new_date'];

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $row['InvoiceNo']);
$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i , $date);

}

// Rename sheet
echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";

?>