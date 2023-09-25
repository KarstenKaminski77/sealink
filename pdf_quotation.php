<?php require_once('../Connections/seavest.php'); ?>
<?php 
session_start();

$_SESSION['email'] = $_POST['email'];
$_SESSION['message'] = $_POST['message'];

require_once('../Connections/seavest.php');

require_once('../functions/functions.php');

$quote_no = $_GET['Id'];

select_db();

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT tbl_qs.CompanyId, tbl_qs.QuoteNo, tbl_qs.JobNo, tbl_companies.* FROM (tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) WHERE tbl_qs.QuoteNo= '$quote_no' ORDER BY Id DESC LIMIT 1";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$colname_Recordset9 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset9 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset9 = "SELECT * FROM tbl_qs WHERE QuoteNo = '$quote_no'";
$Recordset9 = mysql_query($query_Recordset9, $seavest) or die(mysql_error());
$row_Recordset9 = mysql_fetch_assoc($Recordset9);
$totalRows_Recordset9 = mysql_num_rows($Recordset9);

$KTColParam1_Recordset5 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset5 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = sprintf("SELECT tbl_sites.Email AS Email_1, tbl_sites.Address AS Address_1, tbl_sites.Name AS Name_1, tbl_qs.QuoteNo, tbl_qs.Attention, tbl_qs.JobNumber, tbl_qs.Description, tbl_qs.Unit, tbl_qs.Price, tbl_qs.Qty, tbl_qs.Total1, tbl_qs.Email, tbl_qs.SubTotal, tbl_qs.VAT, tbl_qs.Total2, tbl_qs.Date, tbl_qs.Labour, tbl_qs.Material, tbl_qs.Transport, tbl_qs.JobDescription, tbl_qs.Total, tbl_qs.Message, tbl_qs.Attention, tbl_qs.FMC, tbl_qs.TransportComment, tbl_qs.Status, tbl_companies.Name, tbl_companies.ContactName, tbl_companies.Address, tbl_companies.ContactNumber, tbl_companies.ContactEmail, tbl_companies.VATNO, tbl_sites.Company, tbl_sites.Site, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Suburb, tbl_sites.Telephone, tbl_sites.Fax, tbl_sites.Cell FROM ((tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_qs.SiteId) WHERE tbl_qs.QuoteNo=%s ORDER BY tbl_qs.Id ASC", $KTColParam1_Recordset5);
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

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
$pdf->Cell(190,10,'QUOTATION NO: '. $row_Recordset5['QuoteNo'] .'','','','C');
$pdf->Ln(8);
$pdf->Cell(140,10,'');
$pdf->Ln(5);
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(166,202,240);
	$pdf->SetWidths(array(30,160));
    $pdf->Row(array('Attention',$row_Recordset5['Attention']));
    $pdf->Row(array('Client',$row_Recordset5['Name']));
    $pdf->Row(array('Site',$row_Recordset5['Name_1'] .', '. $row_Recordset5['Address_1']));
    $pdf->Row(array('Description',$row_Recordset5['JobDescription']));
    $pdf->Row(array('Reference',$row_Recordset5['FMC']));
    $pdf->Row(array('Date',date('d M Y')));

    $pdf->SetDrawColor(255,255,255);

$pdf->Ln(10);
$pdf->SetFont('Arial','B',9);
$pdf->SetDrawColor(166,202,240);
$pdf->SetTextColor(0, 103, 171);

$pdf->Cell(190,5,'Comments','LTRB');
$pdf->Ln();
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',8);

$quoteno = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysql_error());
$row = mysql_fetch_array($query);

	$pdf->SetWidths(array(190));
    $pdf->Row(array($row['Notes']));

$query = mysql_query("SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

$pdf->SetDrawColor(166,202,240);


$pdf->Cell(168,5,'','','','','0','');

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(166,202,240);

if($numrows >= 1){

if($numrows == 1){

$pdf->Cell(22,5,$numrows .' photo to view','LTRB','','C',true,'http://www.seavest.co.za/inv/view_details.php?Id='. $quote_no .'&photos');

} else {

$pdf->Cell(22,5,$numrows .' photos to view','LTRB','','C',true,'http://www.seavest.co.za/inv/view_details.php?Id='. $quote_no .'&photos');

}}

$pdf->SetFillColor(0,0,0);

$pdf->Ln(15);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 103, 171);

    $pdf->SetDrawColor(123,181,242);
	$pdf->SetWidths(array(120,15,10,20,25));
    $pdf->Row(array('Labour','Unit','Qty','Unit Price','Total'));
select_db();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial','',8);
$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quote_no' AND Labour = '1' ORDER BY Id ASC")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	$pdf->SetWidths(array(120,15,10,20,25));
    $pdf->Row(array($row['Description'],$row['Unit'],$row['Qty'],$row['Price'],$row['Total1']));
}
    $pdf->SetDrawColor(255,255,255);

$pdf->Cell(190,1,'',B);
$pdf->SetFont('Arial','B',8);
$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quote_no' AND Labour = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$sumlabour = $row['SUM(Total1)'];
$pdf->Ln(0);
    $pdf->SetDrawColor(255,255,255);
$pdf->Cell(165,5,'','','','','0','');
    $pdf->SetDrawColor(166,202,240);
$pdf->Cell(25,5,'R'.$sumlabour,'LTRB','0','','0','');
    $pdf->SetDrawColor(255,255,255);
$pdf->Ln(3);
$pdf->Cell(190,1,'','B','','','0','');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 103, 171);
    $pdf->SetDrawColor(166,202,240);
	$pdf->SetWidths(array(120,15,10,20,25));
    $pdf->Row(array('Material','Unit','Qty','Unit Price','Total'));
select_db();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial','',8);
$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quote_no' AND Material = '1'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	$pdf->SetWidths(array(120,15,10,20,25));
    $pdf->Row(array($row['Description'],$row['Unit'],$row['Qty'],$row['Price'],$row['Total1']));
}
    $pdf->SetDrawColor(255,255,255);
	
$pdf->Cell(190,1,'',B);
$pdf->SetFont('Arial','B',8);
$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quote_no' AND Material = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$summaterial = $row['SUM(Total1)'];
$pdf->Ln(0);
    $pdf->SetDrawColor(255,255,255);
$pdf->Cell(165,5,'','','','','0','');
    $pdf->SetDrawColor(166,202,240);
$pdf->Cell(25,5,'R'.$summaterial,'LTRB','0','','0','');
    $pdf->SetDrawColor(255,255,255);
$pdf->Ln(3);
$pdf->Cell(190,1,'','B','','','0','');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 103, 171);
    $pdf->SetDrawColor(166,202,240);
	$pdf->SetWidths(array(60,60,15,10,20,25));
    $pdf->Row(array('Transport','Round Trips','Unit','Qty','Unit Price','Total'));
select_db();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial','',8);
$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quote_no' AND Transport = '1'")or die(mysql_error());
while($row = mysql_fetch_array($query)){
	$pdf->SetWidths(array(60,60,15,10,20,25));
    $pdf->Row(array($row['TransportComment'],$row['Description'].' round trips',$row['Unit'],$row['Qty'],$row['Price'],$row['Total1']));
}
$pdf->Cell(190,1,'',T);
$pdf->SetFont('Arial','B',8);
$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quote_no' AND Transport = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);
$sumtravel = $row['SUM(Total1)'];
$pdf->Ln(0);
    $pdf->SetDrawColor(255,255,255);
$pdf->Cell(165,5,'','','','','0','');
    $pdf->SetDrawColor(166,202,240);
$pdf->Cell(25,5,'R'.$sumtravel,'LTRB','0','','0','');
    $pdf->SetDrawColor(255,255,255);
$pdf->Ln(3);
$pdf->Cell(190,1,'','B','','','0','');
$pdf->Ln(15);
$pdf->Cell(150,1);
$pdf->Cell(40,1,'',T);
$pdf->Ln(1);
    $pdf->SetDrawColor(166,202,240);
$pdf->Cell(145,4,'','','',R);
$pdf->Cell(20,4,'Sub Total:','LTRB','',R);
$query = mysql_query("SELECT SUM(Total1) FROM tbl_qs WHERE QuoteNo = '$quote_no'") or die(mysql_error());
$row = mysql_fetch_array($query);
$sub_total = $row['SUM(Total1)'];
$pdf->Cell(25,4,'R'.$sub_total,'LTRB');
$pdf->Ln(4);
$query = mysql_query("SELECT SUM(VAT) FROM tbl_qs WHERE QuoteNo = '$quote_no'") or die(mysql_error());
$row = mysql_fetch_array($query);
$vat = $row['SUM(VAT)'];
$pdf->Cell(145,4,'','','',R);
$pdf->Cell(20,4,'VAT:','LTRB','',R);
$pdf->Cell(25,4,'R'.$vat,'LTRB');
$pdf->Ln(4);

$total = $sub_total + $vat;
mysql_query("UPDATE tbl_qs SET Total2 = '$total' WHERE QuoteNo = '$quote_no'") or die(mysql_error());
$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quote_no'") or die(mysql_error());
$row = mysql_fetch_array($query);
$total = $row['Total2'];
$pdf->Cell(145,4,'','','',R);
$pdf->Cell(20,4,'Total:','LTRB','',R);
$pdf->Cell(25,4,'R'.$total,'LTRB');
$pdf->Ln(4);
$pdf->Cell(155,4,'','','',R);
$pdf->Cell(35,4,'','T','',R);
$pdf->Cell(150,1);
$pdf->Cell(40,1,'',B);


//$pdf->Output();

$query = mysql_query("SELECT QuoteNo FROM tbl_qs WHERE QuoteNo = '$quote_no' ORDER BY Id ASC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$pdf->Output("pdf/Seavest Quotation ". $row['QuoteNo'] .".pdf");

$document = "Seavest Quotation ". $row['QuoteNo'] .".pdf";

$query = mysql_query("SELECT * FROM tbl_sent_quotes WHERE QuoteNo = '$quote_no'")or die(mysql_error());
$numrows = mysql_num_rows($query);

if($numrows == 0){

$company = $row_Recordset5['Name'];
$site = $row_Recordset5['Name_1'];
$quote_no = $row_Recordset5['QuoteNo'];
$date = date('d M Y H:i:s');

mysql_query("INSERT INTO tbl_sent_quotes (CompanyId,SiteId,QuoteNo,PDF,DateSent) VALUES ('$company','$site','$quote_no','$document','$date')")or die(mysql_error());
}

$quote_no = $_GET['Id'];
$invdate = date('d M Y');
$searchdate = date('Y m d');

if(!isset($_GET['preview'])){
	
	mysql_query("UPDATE tbl_qs SET Status = '1' WHERE QuoteNo = '$quote_no'") or die(mysql_error());
}

header('location: ../quotation_outbox.php');


mysql_free_result($Recordset5);
?>