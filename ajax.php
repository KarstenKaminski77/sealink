<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
require_once '../config.php';
if($_POST['type'] == 'country_table'){
	$row_num = $_POST['row_num'];
	$name = $_POST['name_startsWith'];
	$query = "SELECT Id, Name FROM tbl_sites WHERE UPPER(Company) LIKE '%".strtoupper($name)."%'";
	$result = mysqli_query($con, $query);
	$data = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$name = $row['Name'] .' '. $row['Surname'].'|'.$row['Id'].'|'.$row['CategoryId'].'|'.$row['iso3'].'|'.$row_num;
		array_push($data, $name);	
	}	
	echo json_encode($data);
}


