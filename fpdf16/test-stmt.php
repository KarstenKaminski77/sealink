<?php 
session_start();

require_once('../functions/functions.php');

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE BatchNo >= '1'")or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	mysqli_query($con, "UPDATE tbl_travel SET BatchNo = '". $row['BatchNo'] ."' WHERE JobId = '". $row['JobId'] ."'")or die(mysqli_error($con));

		
}

$total_due = 0;
$total_due2 = 0;

$query = "
	SELECT 
	  tbl_companies.Name AS Name_1,
	  tbl_jc.JobNo,
	  tbl_jc.RefNo,
	  tbl_jc.CompanyId,
	  tbl_jc.Labour,
	  tbl_jc.Material,
	  tbl_jc.Transport,
	  tbl_jc.InvoiceNo,
	  IF(NOW() > STR_TO_DATE(InvoiceDate, '%d %M %Y'), STR_TO_DATE(InvoiceDate, '%d %M %Y'), DATE(InvoiceDate)) AS date_for_sort,
	  tbl_jc.InvoiceDate AS StmtDate,
	  tbl_jc.JobId,
	  tbl_companies.Address,
	  tbl_companies.ContactNumber,
	  tbl_companies.VATNO,
	  SUM(tbl_jc.Total1) AS Total_1,
	  tbl_jc.Total2,
	  tbl_sites.Name 
	FROM
	  (
		(
		  tbl_jc 
		  LEFT JOIN tbl_sites 
			ON tbl_sites.Id = tbl_jc.SiteId
		) 
		LEFT JOIN tbl_companies 
		  ON tbl_companies.Id = tbl_jc.CompanyId
	  ) 
	WHERE STATUS = '12' 
	  AND tbl_jc.CompanyId = '14' 
	  AND (Labour = '1' 
		OR Material = '1') 
	GROUP BY tbl_jc.JobId 
	ORDER BY date_for_sort ASC";

$query = mysqli_query($con, $query)or die(mysqli_error($con));	
while($row = mysqli_fetch_array($query)){
	
	$query2 = mysql_query("SELECT SUM(Total1) AS Travel FROM tbl_travel WHERE JobId = '". $row['JobId'] ."' GROUP BY JobId")or die(mysql_error());
	$row2 = mysql_fetch_array($query2);

	$total = $row['Total_1'] + $row2['Travel'];
	$total = $total + ($total * 0.14);
	$total_due += $total;
	
	$total_due2 += $row['Total2'];
	
	$alert = '';
	
	if(number_format($total,2) != number_format($row['Total2'],2)){
		
		$alert = '<b>Incorrect</b>';
	}
	
	echo $row['JobId'] .' - '. number_format($total,2) . ' - '. number_format($row['Total2'],2) . $alert . '<br>';
}

echo '<br><b>Single</b><br>';
echo mysqli_num_rows($query) .' - '. number_format($total_due,2) .' - '. number_format($total_due2,2);
echo '<br><br>';

$total_due = 0;
$total_due2 = 0;

$query = "
	SELECT 
	  tbl_companies.Name AS Name_1,
	  tbl_jc.JobNo,
	  tbl_jc.BatchNo,
	  tbl_jc.RefNo,
	  tbl_jc.CompanyId,
	  tbl_jc.Labour,
	  tbl_jc.Material,
	  tbl_jc.Transport,
	  tbl_jc.InvoiceNo,
	  IF(NOW() > STR_TO_DATE(InvoiceDate, '%d %M %Y'), STR_TO_DATE(InvoiceDate, '%d %M %Y'), DATE(InvoiceDate)) AS date_for_sort,
	  tbl_jc.InvoiceDate AS StmtDate,
	  tbl_jc.JobId,
	  tbl_companies.Address,
	  tbl_companies.ContactNumber,
	  tbl_companies.VATNO,
	  SUM(tbl_jc.Total1) AS Total_1,
	  tbl_jc.Total2,
	  tbl_sites.Name 
	FROM
	  (
		(
		  tbl_jc 
		  LEFT JOIN tbl_sites 
			ON tbl_sites.Id = tbl_jc.SiteId
		) 
		LEFT JOIN tbl_companies 
		  ON tbl_companies.Id = tbl_jc.CompanyId
	  ) 
	WHERE STATUS = '12' 
	  AND tbl_jc.CompanyId = '14' 
	  AND (Labour = '1' 
		OR Material = '1') 
	GROUP BY IF(tbl_jc.BatchNo >= '1', tbl_jc.BatchNo, tbl_jc.JobId)
	ORDER BY date_for_sort ASC";

$query = mysqli_query($con, $query)or die(mysqli_error($con));	
while($row = mysqli_fetch_array($query)){
	
	if($row['BatchNo'] >= '1'){
		
		$query2 = mysql_query("SELECT SUM(Total1) AS Travel FROM tbl_travel WHERE BatchNo = '". $row['BatchNo'] ."' GROUP BY BatchNo")or die(mysql_error());
		$row2 = mysql_fetch_array($query2);
	
	} else {
		
		$query2 = mysql_query("SELECT SUM(Total1) AS Travel FROM tbl_travel WHERE JobId = '". $row['JobId'] ."' GROUP BY JobId")or die(mysql_error());
		$row2 = mysql_fetch_array($query2);
		
	}

	$total = $row['Total_1'] + $row2['Travel'];
	$total = $total + ($total * 0.14);
	$total_due += $total;
	
	echo $row['JobId'] .' - '. number_format($total,2) . ' - '. number_format($row['Total2'],2) . '<br>';
}

echo '<br><b>Batch</b><br>';
echo mysqli_num_rows($query) .' - '. number_format($total_due,2);
echo '<br><br>';

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE BatchNo >= '1' GROUP BY BatchNo")or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	if($row['JobId'] >= 1 && $row['BatchNo'] >= 1){
		
		mysqli_query($con, "UPDATE tbl_jc SET BatchNo = '". $row['BatchNo'] ."' WHERE JobId = '". $row['JobId'] ."'")or die(mysqli_error($con));
	}
}

?>
