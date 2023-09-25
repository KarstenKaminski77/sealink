<?php
require_once('../Connections/seavest.php');
/** Include path **/
ini_set('include_path', ini_get('include_path') . 'Classes/');
/** PHPExcel */
include 'Classes/PHPExcel.php';
/** PHPExcel_Writer_Excel2007 */
include 'Classes/PHPExcel/Writer/Excel2007.php';

if (isset($_POST['upload']) && !empty($_FILES['xls']['name'])) {

    $target_path = basename($_FILES['xls']['name']);

    if (move_uploaded_file($_FILES['xls']['tmp_name'], $target_path)) {

        $filename = $target_path;
        $handle = fopen("$filename", "r");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $jobid = addslashes($data[0]);
            $inv_date = addslashes($data[3]);
            $orderno = addslashes($data[6]);
            $today = date('Y-m-j');

            $date = strtotime($inv_date) - 60 * 60 * 24 * 14;
            $invoice_date = date('d M Y', $date);
            $new_invoice_date = date('Y-m-d', $date);

            // Update Total2
            $query_invno = "
			  SELECT
				  tbl_jc.SubTotal,
				  tbl_jc.Total2,
				  tbl_jc.VAT,
				  tbl_sites.VAT AS VAT_1
			  FROM
				  tbl_jc
			  INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
			  WHERE
				  JobId = '$jobid'";

            $query_invno = mysqli_query($con, $query_invno) or die(mysqli_error($con));
            while ($row_invno = mysqli_fetch_array($query_invno)) {

                $subtotal = $row_invno['SubTotal'];

                if ($row_invno['VAT_1'] == 1) {

                    $vat = $row_invno['VAT'];
                }

                $total = $subtotal + $vat;

                mysqli_query($con, "UPDATE tbl_jc SET Total2 = '$total' WHERE JobId = '$jobid'") or die(mysqli_error($con));
            }

            mysqli_query($con, "UPDATE tbl_jc SET RefNo = '$orderno', Days = '$today', InvoiceDate = '$invoice_date', 
			NewInvoiceDate = '$new_invoice_date' Status = '12', OrderNoStatus = '2' 
			WHERE JobId = '$jobid'") or die(mysqli_error());

            $query_sent = mysqli_query($con, "SELECT tbl_sites.Name AS Name_1, tbl_jc.InvoiceNo, tbl_companies.Name, tbl_jc.JobId 
			FROM ((tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) 
			WHERE tbl_jc.JobId = '$jobid'") or die(mysqli_error($con));
            $row_sent = mysqli_fetch_array($query_sent);

            $invoice = $row_sent['InvoiceNo'];
            $company = $row_sent['Name'];
            $site = $row_sent['Name_1'];
            $pdf = 'Seavest Invoice ' . $invoice . '.pdf';
            $sent = date('d M Y H:i:s');

            mysqli_query($con, "INSERT INTO tbl_sent_invoices (InvoiceNo,CompanyId,SiteId,JobId,PDF,DateSent) 
			VALUES ('$invoice','$company','$site','$jobid','$pdf','$sent')") or die(mysqli_error($con));
        }

        fclose($handle);

        $_SESSION['success'] = '<div id="banner-success-mail"><span class="success-header">' . $_FILES['xls']['name'] . ' Successfully Uploaded.</span></div>';
    }

    header('Location: ../outbox-new.php');

    exit();
}

$jobIDS = isset($_POST['jobid']) ? $_POST['jobid'] : $_POST['file'];

if (isset($jobIDS) && !empty($jobIDS) && isset($_POST['email']) && !empty($_POST['email'])) {
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    // Set properties
    $objPHPExcel->getProperties()->setCreator("KWD");
    $objPHPExcel->getProperties()->setLastModifiedBy("KWD");
    $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

    $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    // Add some data
    $objPHPExcel->getActiveSheet()->getStyle('A1:HG1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A1:HG1')->getFill()->getStartColor()->setARGB('FFFF00');
    $objPHPExcel->getActiveSheet()->getStyle('A1:HG1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $objPHPExcel->getActiveSheet()->getStyle('A1:HG1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $objPHPExcel->getActiveSheet()->getStyle('A1:HG1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $objPHPExcel->getActiveSheet()->getStyle('A1:HG1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $objPHPExcel->getActiveSheet()->getStyle('A1:HG1')->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Job No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'P.O. No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Area');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Site');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Engineer');
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'SubTotal');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'VAT');
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Total');
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Invoice No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Invoice Date');
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Age');
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Invoice PDF');
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Comments');

    $i = 1;
    $selectedIDs = implode(', ', $jobIDS);
    $filename = 'Seavest Order Number Export - ' . date('Y-m-d H:i:s') . '.xlsx';
    $sql = "
        SELECT tbl_jc.InvoiceNo, tbl_jc.InvoiceDate AS InvDate, tbl_jc.JobNo, tbl_jc.Total2, tbl_sites.`Name`, tbl_jc.JobId, RefNo, tbl_jc.VAT, Reference, SubTotal, DATEDIFF(now(), Days) AS Days, tbl_jc.Status, tbl_areas.Area
        FROM tbl_jc 
        INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id 
        INNER JOIN tbl_areas ON tbl_areas.Id = tbl_sites.AreaId 
        WHERE tbl_jc.JobId IN (" . $selectedIDs . ") GROUP BY tbl_jc.JobId ORDER BY InvDate ASC
        ";
    $results = mysqli_query($con, $sql) or die(mysqli_error($con));

    while ($row = mysqli_fetch_array($results)) {
    	$invoicePdf = '';
    	if ($row['Status'] == 11 || $row['Status'] == 12) {
    		$invoicePdf = 'http://www.seavest.co.za/inv/fpdf16/inv-preview.php?Id='.$row['JobId'];
    	}
        $sub = $row['SubTotal'];
        if ($sub < 1 && $row['Total2'] > 1 && $row['VAT'] > 1) {
            $sub = $row['Total2'] - $row['VAT'];
        }
        $i++;
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $row['JobNo']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $row['RefNo']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $row['Area']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $row['Name']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $row['Reference']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $sub);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $row['VAT']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $row['Total2']);
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $row['InvoiceNo']);
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $row['InvDate']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, ($row['Days'] + 1));
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, '=Hyperlink("'.$invoicePdf.'","Click for Invoice PDF")');
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, '');
    }

    // Rename sheet
    $objPHPExcel->getActiveSheet()->setTitle('Simple');
    // Save Excel 2007 file
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save($filename);

    // Email
    $message = '
        Hello <br><br>
        Please find attached the exported spreadsheet<br><br>
        <img src="http://www.seavest.co.za/inv/images/signature-new.jpg" />
        ';

    $to = $_POST['email'];
    $from = "Seavest Africa <info@seavest.co.za>";
    $subject = "Seavest Africa Order No.";
    $message = "<body style=\"font-family:Arial; font-size:12px; margin: 20px; line-height:18px; color:#333333\">" . $message . "</body>";
    $headers = "From: $from";

    // boundary 
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    // headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

    // multipart boundary 
    $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-type:text/html; charset=utf8\r\n" .
        "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
    $message .= "--{$mime_boundary}\n";

    // preparing attachments
    $file = fopen($filename, "rb");
    $data = fread($file, filesize($filename));
    fclose($file);
    $data = chunk_split(base64_encode($data));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$filename\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"$filename\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";

    @mail($to, $subject, $message, $headers);
}

if (isset($_POST['jobid'])) {
    header('Location: ../invoices/approved-awaiting-order-no.php');
} else {
    header('Location: ../invoices/debtors.php');
}
