<?php 
session_start();

if(isset($_POST['email']) || isset($_POST['message'])){
	
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['message'] = $_POST['message'];
}

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');
require('mc_table.php');

$pdf=new PDF_MC_Table('P', 'mm', 'A4');

$files = $_SESSION['files'];

for($f=0;$f<count($files);$f++){

	$jobid = $files[$f];
	
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
	
	$query_invno = mysqli_query($con, $query_invno)or die(mysqli_error($con));
	while($row_invno = mysqli_fetch_array($query_invno)){
		
		$subtotal = $row_invno['SubTotal'];
		
		if($row_invno['VAT_1'] == 1){
			
			$vat = $row_invno['VAT'];
		}
		
		$total = $subtotal + $vat;
		
		mysqli_query($con, "UPDATE tbl_jc SET Total2 = '$total' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	}
	
	select_db();
	
	$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	$numrows = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)){
	
	$id = $row['Id'];
	$labour = addslashes($row['Description']);
	
	mysqli_query($con, "UPDATE tbl_jc SET Description = '$labour' WHERE Id = '$id'")or die(mysqli_error($con));
	
	} // close loop
	
	$query_Recordset4 = "SELECT tbl_jc.CompanyId, tbl_jc.JobId, tbl_jc.JobNo, tbl_jc.InvoiceDate, tbl_companies.* FROM (tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId= '$jobid' ORDER BY Id DESC LIMIT 1";
	$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
	$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
	$totalRows_Recordset4 = mysqli_num_rows($Recordset4);
	
	$query_Recordset5 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Address AS Address_1, tbl_companies.ContactName, tbl_companies.ContactNumber, tbl_companies.ContactEmail, tbl_companies.Name, tbl_companies.VATNO, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Telephone, tbl_sites.Email, tbl_jc.Id, tbl_jc.JobId, tbl_jc.InvoiceNo, tbl_jc.JobNo, tbl_jc.Date, tbl_jc.JobDescription, tbl_jc.CompanyId, tbl_jc.Reference FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId = '$jobid' ORDER BY Id ASC LIMIT 1";
	$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
	$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
	$totalRows_Recordset5 = mysqli_num_rows($Recordset5);
	
	$query_refno = "SELECT JobId, RefNo FROM tbl_jc WHERE JobId = '$jobid'";
	$refno = mysqli_query($con, $query_refno) or die(mysqli_error($con));
	$row_refno = mysqli_fetch_assoc($refno);
	$totalRows_refno = mysqli_num_rows($refno);
	
	$colname_Recordset9 = "-1";
	if (isset($_GET['Id'])) {
	  $colname_Recordset9 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
	}
	$query_Recordset9 = "SELECT * FROM tbl_jc WHERE JobId = '$jobid'";
	$Recordset9 = mysqli_query($con, $query_Recordset9) or die(mysqli_error($con));
	$row_Recordset9 = mysqli_fetch_assoc($Recordset9);
	$totalRows_Recordset9 = mysqli_num_rows($Recordset9);
	
	$pdf->AddPage();
	
	// $pdf->Image('../images/no.jpg',10,10);
	// $pdf->Ln(10);
	$pdf->SetDrawColor(166,202,240);
	$pdf->SetTextColor(0, 132, 181);
	$pdf->SetFont('Arial','B',16);
	$pdf->Image('logo.jpg',10,4,33);
	$pdf->Cell(190,10,'Tax Invoice: '. $row_Recordset5['InvoiceNo'] .'','','','R');
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->Ln(10);
	$pdf ->Cell(190,10,$row_Recordset4['InvoiceDate'],'','','R');
	$pdf->Cell(140,10,'');
	$pdf->Ln(20);
	$pdf->Cell(50,5,'P.O.BOX 201153','','','L');
	$pdf ->Cell(50,5,'VAT NO: 4230211908','','','R');
	$pdf ->Cell(90,5,'Tel : (031) 5637735','','','R');
	$pdf->Ln(4);
	$pdf->Cell(50,5,'Durban North','','','L');
	$pdf ->Cell(140,5,'Fax : 0865 191 153','','','R');
	$pdf->Ln(4);
	$pdf->Cell(50,5,'4016','','','L');
	$pdf->Cell(140,4,'email : hemi@seavest.co.za','','','R');
	
	$pdf->Ln(7);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0,132,181);
	$pdf->SetDrawColor(255,255,255);
	$pdf->Cell(45,5,$row_Recordset5['Name'],'TLR','','','0','');
	$pdf->Cell(25,5,'','','','','0','');
	$pdf->SetTextColor(0,132,181);
	$pdf->Cell(45,5,'Site Address','LTR','','','0','');
	$pdf->Cell(25,5,'','','','','0','');
	$pdf->SetTextColor(0, 0, 102);
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Ln(4);
	
		$pdf->SetWidths(array(45,25,45));
		$pdf->Row(array($row_Recordset5['Address_1'],'',$row_Recordset5['Name_1']));
	
	$pdf->Ln(4);
	$pdf->Cell(45,5, $row_Recordset5['ContactNumber'],'LR');
	$pdf->Cell(25,5,'','','','','0','');
	$pdf->Cell(45,5, $row_Recordset5['Address'],'LR');
	$pdf->Cell(25,5,'','','','','0','');
	$pdf->Ln(4);
	$pdf->Cell(45,5, $row_Recordset5['VATNO'],'LRB');
	$pdf->Cell(25,5,'','','','','0','');
	$pdf->Cell(45,5,'','LRB','','','0','');
	$pdf->Ln(10);
	$pdf->SetDrawColor(0,132,181);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0,132,181);
	$pdf ->Cell(20,5,'Job No.','LRTB','','','0','');
	$pdf ->Cell(1,5,'','','','','0','');
	$pdf ->Cell(24,5,'Reference','LRTB','','C','0','');
	$pdf->Cell(25,5,'','','','','0','');
	$pdf->SetTextColor(0,132,181);
	$pdf->Cell(45,5,'Order No.','LRTB','','','0','');
	$pdf->Cell(25,5,'','','','','0','');
	if($row_Recordset4['CompanyId'] == 1){
	$pdf ->Cell(25,10,'');
	} else {
	$pdf ->Cell(25,10);
	}
	$pdf->Ln(4);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',8);
	$pdf ->Cell(20,10,$row_Recordset4['JobNo']);
	$pdf ->Cell(6,10);
	$pdf ->Cell(19,10,$row_Recordset5['Reference']);
	$pdf->Cell(25,5,'','','','','0','');
	$pdf ->Cell(25,10,$row_refno['RefNo']);
	$pdf->Ln(12);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0,132,181);
	$pdf->Cell(119,5,'Description','LRTB','','','0','');
	$pdf ->Cell(1,5,'','','','','0','');
	$pdf->Cell(14,5,'Unit','LRTB','',C,'0','');
	$pdf ->Cell(1,5,'','','','','0','');
	$pdf->Cell(9,5,'Qty','LRTB','',C,'0','');
	$pdf ->Cell(1,5,'','','','','0','');
	$pdf->Cell(19,5,'Unit Price','LRTB','',R,'0','');
	$pdf ->Cell(1,5,'','','','','0','');
	$pdf->Cell(25,5,'Total','LRTB','',C,'0','');
	$pdf->Ln(6);
	select_db();
	$pdf->Cell(190,1);
	$pdf->Ln(1);
	
	// Labour
	$pdf->Cell(190,5,'Labour');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',8);
	$pdf->Ln(4);
	
	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		$pdf->Ln(1);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetWidths(array(120,15,10,20,25));
		$pdf->Row(array($row['Description'],$row['Unit'],$row['Qty'],$row['Price'],$row['Total1']));
		$pdf->Ln(2);
	}
	
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0, 132, 181);
	
	// Sum Labour
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumlabour = $row['SUM(Total1)'];
	$pdf->Cell(190,4,'R'.$sumlabour,'','',R);
	
	$pdf->Ln(5);
	
	// Material
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0,132,181);
	$pdf->Cell(190,5,'Material','','','','0','');
	$pdf->Ln(4);
	
	$pdf->SetTextColor(0, 0, 102);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',8);
	select_db();
	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		$pdf->Ln(1);
		$pdf->Cell(120,4,$row['Description']);
		$pdf->Cell(15,4,$row['Unit'],'','',C);
		$pdf->Cell(10,4,$row['Qty'],'','',C);
		$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
		$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
		$pdf->Ln(2);
		$pdf->Cell(190,1);
		$pdf->Ln(1);
	}
	
	$pdf->Ln(1);
	$pdf->Cell(190,1,'',B);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0, 132, 181);
	
	// Sum Material
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumlabour = $row['SUM(Total1)'];
	$pdf->Ln(2);
	$pdf->Cell(190,4,'R'.$sumlabour,'','',R);
	
	
	$pdf->Ln(5);
	
	// Transport
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0,132,181);
	$pdf->Cell(190,5,'Transport','0','','','0','');
	
	$pdf->Ln();
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',8);
	
	select_db();
	$company_id = $row_Recordset5['CompanyId'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
	
		if($company_id == 1){
			
			$pdf->Cell(80,4,$company_id .' - '.$row['TransportComment']);
			$pdf->Cell(40,4,$row['Description'] .' round trips');
			$pdf->Cell(15,4,$row['Unit'],'','',C);
			$pdf->Cell(10,4,$row['Qty'],'','',C);
			$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
			$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		}
		
		if($company_id == 2){
			
			$pdf->Cell(25,4,'Km: '.$row['DistanceKm']);
			$pdf->Cell(34,4,'Km Rate: R'.$row['KmRate'],'','',L);
			$pdf->Cell(37,4,'Total Km: '.$row['TotalKm'],'','',L);
			$pdf->Cell(34,4,'Travel Time: '.$row['TravelTime'],'','',L);
			$pdf->Cell(50,4,'Travel Time Rate: R'.$row['TravelTimeRate'],'','',L);
			$pdf->Cell(18,4,$row['TotalPragma'],'','',L);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		
		}
		
		if($company_id == 3){
			
			$pdf->Cell(80,4,$row['TransportComment']);
			$pdf->Cell(40,4,$row['Description'] .' round trips');
			$pdf->Cell(15,4,$row['Unit'],'','',C);
			$pdf->Cell(10,4,$row['Qty'],'','',C);
			$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
			$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		
		}
		
		if($company_id == 4){
			
			$pdf->Cell(80,4,$row['TransportComment']);
			$pdf->Cell(40,4,$row['Description'] .' round trips');
			$pdf->Cell(15,4,$row['Unit'],'','',C);
			$pdf->Cell(10,4,$row['Qty'],'','',C);
			$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
			$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		
		}
		
		if($company_id == 5){
			
			$pdf->Cell(80,4,$row['TransportComment']);
			$pdf->Cell(40,4,$row['Description'] .' round trips');
			$pdf->Cell(15,4,$row['Unit'],'','',C);
			$pdf->Cell(10,4,$row['Qty'],'','',C);
			$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
			$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		
		}
		
		if($company_id == 6){
			
			$pdf->Cell(80,4,$row['TransportComment']);
			$pdf->Cell(40,4,$row['Description'] .' round trips');
			$pdf->Cell(15,4,$row['Unit'],'','',C);
			$pdf->Cell(10,4,$row['Qty'],'','',C);
			$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
			$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		
		}
		
		if($company_id == 10){
			
			$pdf->Cell(80,4,$row['TransportComment']);
			$pdf->Cell(40,4,$row['Description'] .' round trips');
			$pdf->Cell(15,4,$row['Unit'],'','',C);
			$pdf->Cell(10,4,$row['Qty'],'','',C);
			$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
			$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		
		}
		
		if($company_id == 12){
			
			$pdf->Cell(80,4,$row['TransportComment']);
			$pdf->Cell(40,4,$row['Description'] .' round trips');
			$pdf->Cell(15,4,$row['Unit'],'','',C);
			$pdf->Cell(10,4,$row['Qty'],'','',C);
			$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
			$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
			$pdf->Ln(2);
			$pdf->Cell(190,1);
			$pdf->Ln(2);
		
		}
	}
	$pdf->Cell(190,1,'',T);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0, 132, 181);
	if($company_id == 1){
		
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(Total1)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	
	if($company_id == 2){
		
	$query = mysqli_query($con, "SELECT SUM(TotalPragma) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(TotalPragma)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	
	if($company_id == 3){
		
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(Total1)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	
	if($company_id == 4){
		
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(Total1)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	
	if($company_id == 5){
		
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(Total1)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	
	if($company_id == 6){
		
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(Total1)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	
	if($company_id == 10){
		
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(Total1)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	
	if($company_id == 12){
		
	$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$sumtransport = $row['SUM(Total1)'];
	$pdf->Ln(1);
	$pdf->Cell(190,4,'R'.$sumtransport,'','',R);
	
	}
	$pdf->Ln(3);
	$pdf->Cell(190,1,'','B','','','0','');
	$pdf->Ln(15);
	$pdf->Cell(150,1);
	$pdf->Cell(40,1,'',T);
	$pdf->Ln(1);
	$pdf->Cell(165,4,'Sub Total:','','',R);
	$pdf->Cell(25,4,'R'.$row_Recordset9['SubTotal'],'','',R);
	$pdf->Ln(4);
	
	$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND InvoiceQ = '0' AND Total1 >= '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$site_id = $row['SiteId'];
	
	$query_vat = mysqli_query($con, "SELECT tbl_sites.Id, tbl_sites.Name, tbl_jc.SubTotal, tbl_sites.VAT
	FROM (tbl_jc
	LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) 
	WHERE tbl_sites.Id = '$site_id'")or die(mysqli_error($con));
	$row_vat = mysqli_fetch_assoc($query_vat);
	
	if($row_vat['VAT'] == 1){
		
		$subtotal = $row['SubTotal'];
		$vat = ($subtotal / 100) * 14;
		
		mysqli_query($con, "UPDATE tbl_jc SET VAT = '$vat' WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		
		$vat = $row['VAT'];
		$pdf->Cell(165,4,'VAT:','','',R);
		$pdf->Cell(25,4,'R'.$vat,'','',R);
		$pdf->Ln(4);
	}
	
	$query = mysqli_query($con, "SELECT tbl_jc.VAT AS VAT_1, tbl_sites.Id, tbl_sites.Name, tbl_jc.SubTotal, tbl_sites.VAT
	FROM (tbl_jc
	LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId)
	WHERE tbl_jc.JobId = '$jobid' AND Total1 >= '1'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$subtotal = $row['SubTotal'];
	
	if($row['VAT'] == 1){
		
		$vat = $row['VAT_1'];
	}
	
	$total = $subtotal + $vat;
	
	$pdf->Cell(155,4,'','','',R);
	$pdf->Cell(10,4,'Total:','T','',R);
	$pdf->Cell(25,4,'R'.number_format($total,2),'T','',R);
	$pdf->Ln(4);
	$pdf->Cell(155,4,'','','',R);
	$pdf->Cell(35,4,'','T','',R);
	$pdf->Cell(150,1);
	$pdf->Cell(40,1,'',B);


}
//$pdf->Output();
$pdf->Output("pdf/batch.pdf");
?>

<script type="text/javascript"> 
function Imprim() { 
    var LaFrame = document.getElementById("MyFrame"); 
    LaFrame.focus(); 
    LaFrame.contentWindow.print(); 
	LaFrame.contentWindow.close();
} 
</script> 

<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" onLoad="Imprim()"> 
<iframe id="MyFrame" src="pdf/batch.pdf" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" align="middle"></iframe> 
</body>