<?php
session_start();

function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/html; charset=utf8\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use diff. tyoes here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
if (mail($mailto, $subject, "", $header)) {

select_db();

$jobid = $_GET['Id'];
$invdate = date('d M Y');
$searchdate = date('Y m d');
// mysql_query("UPDATE tbl_jc SET InvoiceSent = '1', Archived = '1', SearchDate = '$searchdate' WHERE JobId = '$jobid'") or die(mysql_error());

} }

require_once('../functions/functions.php');

select_db();

$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$row = mysql_fetch_array($query);

$invoice_no = $row['InvoiceNo'];

$file = $_GET['file'];
$file2 = explode("_", $file);
$my_file = $file2[0] ." ". $file2[1] ." ". $invoice_no .".". $file2[3];
$my_path = $_SERVER['DOCUMENT_ROOT']."/inv/fpdf16/pdf/";
$my_name = "Seavest Trading";
$my_mail = "test@kwd.co.za";
$my_replyto = "test@kwd.co.za";
$my_subject = $my_file;
$my_message = "<body style=\"font-family:tahoma; font-size:12px; line-height:18px; color:#333366\">". $_SESSION['message'] ."</body>";
$to = "info@kwd.co.za";
mail_attachment($my_file, $my_path, $to, $my_mail, $my_name, $my_replyto, $my_subject, $my_message);

$date = date('d M Y');
$job_id = $file2[2];

select_db();

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$job_id' LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$company_id = $row['CompanyId'];
$site_id = $row['SiteId'];
$invoice_no = $row['InvoiceNo'];

mysql_query("INSERT INTO tbl_sent_invoices (InvoiceNo,CompanyId,SiteId,JobId,PDF,DateSent) VALUES ('$invoice_no','$company_id','$site_id','$job_id','$my_file','$date')")or die(mysql_error());

header('Location: test3.php')
?>
