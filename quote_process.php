<?php 
require_once('Connections/seavest.php');

session_start();

require_once('functions/functions.php');

select_db();

$jobdescription = $_SESSION['description'];

$query = "SELECT * FROM tbl_qs ORDER BY Id DESC LIMIT 1" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

$quote = $row['QuoteNo']+1;

           $labour = $_POST['labour'];
           $unit_l = $_POST['unit_l'];
           $qty_l = $_POST['qty_l'];
           $price_l = $_POST['price_l'];
		   
		   
  $rows_l = $_SESSION['rows_l'];
  $rows = $rows_l;

	for($i=0;$i<$rows;$i++) {
		    $labour_desc = $labour[$i];
            $labour_unit =$unit_l[$i];
            $labour_qty = $qty_l[$i];
            $labour_price = $price_l[$i];
			$labour_total = $qty_l[$i] * $price_l[$i];
			$company = $_SESSION['company'];
            $site = $_SESSION['site'];
			
mysql_query ("INSERT INTO tbl_qs (QuoteNo, CompanyId, SiteId, Description, Unit, Qty, Price, Total1, Labour,JobDescription) VALUES ('$quote','$company','$site','$labour_desc','$labour_unit','$labour_qty','$labour_price','$labour_total','1','$jobdescription')") or die(mysql_error());
}
           $material = $_POST['material']; 
           $unit_m = $_POST['unit_m'];
           $qty_m = $_POST['qty_m'];
           $price_m = $_POST['price_m'];
  
  $rows_m = $_SESSION['rows_m'];
  $rows = $rows_m;

	for($i=0;$i<$rows;$i++) {
            $material_desc = $material[$i];
            $material_unit =$unit_m[$i];
            $material_qty = $qty_m[$i];
            $material_price = $price_m[$i];
			$material_total = $total_m[$i];
			$material_total = $qty_m[$i] * $price_m[$i];
			$company = $_SESSION['company'];
            $site = $_SESSION['site'];

mysql_query ("INSERT INTO tbl_qs (QuoteNo, CompanyId, SiteId, Description, Unit, Qty, Price, Total1, Material,JobDescription) VALUES ('$quote','$company','$site','$material_desc','$material_unit','$material_qty','$material_price','$material_total','1','$jobdescription')") or die(mysql_error());
}
		                           

           $transport_description = $_POST['transport_description'];
		   $transport = $_POST['transport']; 
           $unit_t = $_POST['unit_t'];
           $qty_t = $_POST['qty_t'];
           $price_t = $_POST['price_t'];

                        
  $rows_l = $_SESSION['rows_l'];
  $rows_m = $_SESSION['rows_m'];
  if($rows_l >= $rows_m){
  $rows = $rows_l;
  } else {
  $rows = $rows_m;
  }
	for($i=0;$i<$rows;$i++) {
            $transport_comment = $transport_description[$i];
			$transport_desc = $transport[$i];
            $transport_unit =$unit_t[$i];
            $transport_qty = $qty_t[$i];
            $transport_price = $price_t[$i];
			$transport_total = $total_t[$i];
			$transport_total = $qty_t[$i] * $price_t[$i];

$company = $_SESSION['company'];
$site = $_SESSION['site'];
$description = $_SESSION['description'];
					
mysql_query ("INSERT INTO tbl_qs (QuoteNo, CompanyId, SiteId, Description, Unit, Qty, Price, Total1, Transport,JobDescription,TransportComment) VALUES ('$quote','$company','$site','$transport_desc','$transport_unit','$transport_qty','$transport_price','$transport_total','1','$jobdescription','$transport_comment')") or die(mysql_error());
}

$query = mysql_query("SELECT * FROM tbl_qs WHERE QuoteNo = '$quote' LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);
$companyid = $row['CompanyId'];

$query = mysql_query("SELECT * FROM tbl_companies WHERE Id = '$companyid'")or die(mysql_error());
$row = mysql_fetch_array($query);
$prefix = $row['Prefix'];
$fmc = $_POST['fmc'];
$fmc = $prefix . $fmc;
$att = $_POST['att'];
mysql_query("UPDATE tbl_qs SET FMC = '$fmc', Attention = '$att' WHERE QuoteNo = '$quote'")or die(mysql_error());
header('Location: quote_calc.php?Id='. $quote .'&update');
?>