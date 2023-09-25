<?php
ini_set('max_execution_time', 1200);
ini_set('max_input_time', 1200);
ini_set('memory_limit','512M');

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');
$con_backup = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_234','8Tp8CIj1','seavest_db_482');

$query = mysqli_query($con, "SELECT JobId FROM tbl_jc WHERE Status = '7' GROUP BY JobId")or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	$query_bk = mysqli_query($con_backup, "SELECT Status FROM tbl_jc WHERE JobId = '". $row['JobId'] ."' GROUP BY JobId")or die(mysqli_error($con));
	$row_bk = mysqli_fetch_array($query_bk);
	
	mysqli_query($con, "UPDATE tbl_jc SET Status = '". $row_bk['Status'] ."' WHERE JobId = '". $row['JobId'] ."'")or die(mysqli_error($con));
			
}

mysqli_close($con);
mysqli_free_result($query);

exit();
?>