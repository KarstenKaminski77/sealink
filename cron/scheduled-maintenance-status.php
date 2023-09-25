<?php 
session_start();

require_once('../functions/functions.php');

$date = date('Y-m-d');
  
$query = mysqli_query($con, "SELECT * FROM tbl_scheduled_maintenance WHERE Date = '$date' AND Status = 'Qued'")or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	$id = $row['Id'];
	
	mysqli_query($con, "UPDATE tbl_scheduled_maintenance SET Status = 'In Progress' WHERE Id = '$id'");
	
}
?>