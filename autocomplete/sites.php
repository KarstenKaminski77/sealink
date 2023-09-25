<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
require_once 'config.php';
if($_POST['type'] == 'country_table'){
	$row_num = $_POST['row_num'];
	$name = $_POST['name_startsWith'];
	
	$query = "
		SELECT
			tbl_sites.`Name`,
			tbl_sites.Id,
			tbl_jc.`Status`,
			tbl_sites.Cell,
			tbl_jc.JobNo,
			tbl_jc.InvoiceNo
		FROM
			tbl_sites
		INNER JOIN tbl_jc ON tbl_sites.Id = tbl_jc.SiteId
		WHERE
			UPPER(Name) LIKE '%".strtoupper($name)."%'
		AND
			(tbl_jc.`Status` = '2' OR tbl_jc.`Status` = '1' AND tbl_jc.InvoiceNo >= '1')
		GROUP BY
			tbl_jc.JobId
		ORDER BY
			tbl_sites.`Name` ASC";	
	
	$result = mysqli_query($con, $query);
	$data = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$name = $row['Name'] .' - '. $row['JobNo'] .'|'.$row['Id'].'|'.$row['InvoiceNo'].'|'.$row_num;
		array_push($data, $name);	
	}	
	echo json_encode($data);
}


