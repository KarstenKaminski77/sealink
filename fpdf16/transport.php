$company_id = $row_Recordset5['CompanyId'];

if($company_id = 1){
	
	$pdf->Ln(1);
	$pdf->Cell(80,4,$row['TransportComment']);
	$pdf->Cell(40,4,$row['Description'] .' round trips');
	$pdf->Cell(15,4,$row['Unit'],'','',C);
	$pdf->Cell(10,4,$row['Qty'],'','',C);
	$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
	$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
	$pdf->Ln(4);
	$pdf->Cell(190,1);
	$pdf->Ln(1);

}

if($company_id = 2){
	
	$pdf->Ln(1);
	$pdf->Cell(80,4,$row['TransportComment']);
	$pdf->Cell(40,4,'Km '.$row['DistanceKm']);
	$pdf->Cell(15,4'Km Rate '.$row['KmRate'],'','',C);
	$pdf->Cell(10,4,'Total Km '.$row['TotalKm'],'','',C);
	$pdf->Cell(20,4,'Travel Time '.$row['TravelTime'],'','',R);
	$pdf->Cell(25,4,'Travel Time Rate '$row['TravelTimeRate'],'','',R);
	$pdf->Ln(4);
	$pdf->Cell(190,1);
	$pdf->Ln(1);

}

if($company_id = 1){
	
	$pdf->Ln(3);
	$pdf->Cell(80,4,$row['TransportComment']);
	$pdf->Cell(40,4,$row['Description'] .' round trips');
	$pdf->Cell(15,4,$row['Unit'],'','',C);
	$pdf->Cell(10,4,$row['Qty'],'','',C);
	$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
	$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
	$pdf->Ln(4);
	$pdf->Cell(190,1);
	$pdf->Ln(1);

}

if($company_id = 4){
	
	$pdf->Ln(1);
	$pdf->Cell(80,4,$row['TransportComment']);
	$pdf->Cell(40,4,$row['Description'] .' round trips');
	$pdf->Cell(15,4,$row['Unit'],'','',C);
	$pdf->Cell(10,4,$row['Qty'],'','',C);
	$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
	$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
	$pdf->Ln(4);
	$pdf->Cell(190,1);
	$pdf->Ln(1);

}

if($company_id = 1){
	
	$pdf->Ln(5);
	$pdf->Cell(80,4,$row['TransportComment']);
	$pdf->Cell(40,4,$row['Description'] .' round trips');
	$pdf->Cell(15,4,$row['Unit'],'','',C);
	$pdf->Cell(10,4,$row['Qty'],'','',C);
	$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
	$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
	$pdf->Ln(4);
	$pdf->Cell(190,1);
	$pdf->Ln(1);

}

if($company_id = 1){
	
	$pdf->Ln(6);
	$pdf->Cell(80,4,$row['TransportComment']);
	$pdf->Cell(40,4,$row['Description'] .' round trips');
	$pdf->Cell(15,4,$row['Unit'],'','',C);
	$pdf->Cell(10,4,$row['Qty'],'','',C);
	$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
	$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
	$pdf->Ln(4);
	$pdf->Cell(190,1);
	$pdf->Ln(1);

}

if($company_id = 7){
	
	$pdf->Ln(1);
	$pdf->Cell(80,4,$row['TransportComment']);
	$pdf->Cell(40,4,$row['Description'] .' round trips');
	$pdf->Cell(15,4,$row['Unit'],'','',C);
	$pdf->Cell(10,4,$row['Qty'],'','',C);
	$pdf->Cell(20,4,'R'.$row['Price'],'','',R);
	$pdf->Cell(25,4,'R'.$row['Total1'],'','',R);
	$pdf->Ln(4);
	$pdf->Cell(190,1);
	$pdf->Ln(1);

}
