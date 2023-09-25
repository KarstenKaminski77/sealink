<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
require_once 'config.php';
if($_POST['type'] == 'country_table'){
	$row_num = $_POST['row_num'];
	$name = $_POST['name_startsWith'];
	$query = "SELECT Name, Id, Address, Active FROM tbl_suppliers WHERE UPPER(Name) LIKE '%".strtoupper($name)."%' AND Active  = '1'";
	$result = mysqli_query($con, $query);
	$data = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$name = $row['Name'].'|'.$row['Id'].'|'.$row['Address'].'|'.$row_num;
		array_push($data, $name);	
	}	
	echo json_encode($data);
}


