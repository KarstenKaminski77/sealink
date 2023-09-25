<?php 
session_start();

require_once('functions/functions.php'); 

$sql = "
	SELECT
		Menu,
		Url
	FROM
		tbl_menu_items
	";
	
$sql = mysqli_query($con, $sql)or die(mysqli_error($con));
while($row = mysqli_fetch_array($sql)){
	
	echo $row['Menu'] .' '. $row['Url'] .'<br>';
}

 ?>