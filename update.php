<?php
$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$query_debtors = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '25064' GROUP BY JobId")or die(mysqli_error($con));
while($row_debtors = mysqli_fetch_array($query_debtors)){

	// Update Sub Totals
	$query_update = mysqli_query($con, "SELECT SUM(Total1) AS Total, SiteId FROM tbl_jc WHERE JobId = '25064' AND (Labour = '1' OR Material = '1') GROUP BY JobId") or die(mysqli_error($con));
	$row_update = mysqli_fetch_array($query_update);
	
	$query_transport = mysqli_query($con, "SELECT SUM(Total1) AS Total FROM tbl_travel WHERE JobId = '25064'") or die(mysqli_error($con));
	$row_transport = mysqli_fetch_array($query_transport);
	
	$query_sites = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '". $row_debtors['SiteId'] ."'")or die(mysqli_error($con));
	$row_sites = mysqli_fetch_array($query_sites);
		
	$sub_total = $row_update['Total'] + $row_transport['Total'];
	
	if($row_sites['VAT'] == 1){
		
		$vat_rate = getVatRate($con, $row_debtors['NewInvoiceDate']);
		$vat = $sub_total * ($vat_rate / 100);
	}
	
	$total = $sub_total + $vat;
	
	echo '<b>'. $row_debtors['InvoiceNo'] .'</b> - '. $row_debtors['Total2'] .' - '. $total .'<br>';

   // mysqli_query($con, "UPDATE tbl_jc SET Total2 = '$total' WHERE JobId = '". $row_debtors['JobId'] ."'")or die(mysqli_error($con));

}
exit();
?>