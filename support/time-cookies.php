<?php
$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$jobid = $_GET['Id'];

if(isset($_POST['power'])){
	
	$comments = $_POST['comments'];
	
	mysqli_query($con, "UPDATE tbl_support SET Error = '$comments' WHERE Id = '$jobid'")or die(mysqli_error($con));
	
	header('Location: current.php?Id='. $_GET['Id'] .'&Power='. $_GET['Id']);
	
	exit();
}

if(isset($_POST['start'])){
	
	$expire = 60 * 60 * 24 * 365 + time();
	
	$jobid = $_GET['Id'];
	$userid = $_POST['userid'];
	$login = date('Y-m-d H:i:s');
	$comments = $_POST['comments'];
	
	$query = mysqli_query($con, "SELECT Balance FROM tbl_time_log ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$balance = $row['Balance'];
	
	mysqli_query($con, "INSERT INTO tbl_time_log (UserId,JobId,Login,Balance) VALUES ('$userid','$jobid','$login','$balance')")or die(mysqli_error($con));
	
	mysqli_query($con, "UPDATE tbl_support SET Error = '$comments' WHERE Id = '$jobid'")or die(mysqli_error($con));
	
	$record = $row['Id'];
	
	header('Location: current.php?Id='. $jobid .'&Start='. $jobid);
	
	exit();
	
}

if(isset($_POST['stop'])){
	
	$query = mysqli_query($con, "SELECT * FROM tbl_time_log WHERE JobId = '$jobid' ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$logout = date('Y-m-d H:i:s');
	$comments = $_POST['comments'];
	$record = $row['Id'];
		
	mysqli_query($con, "UPDATE tbl_time_log SET Logout = '$logout', Comments = '$comments' WHERE Id = '$record'")or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT TIMEDIFF(Logout, Login) AS Diff, Balance FROM tbl_time_log WHERE Id = '$record'")or die(mysqli_error($con));
	$timediff = mysqli_fetch_array($query);
	
	$balance = $timediff['Balance'];
	$difference = $timediff['Diff'];
	
	$new_balance = gmdate('H:i:s',(strtotime($balance) - strtotime($difference)));
	
	mysqli_query($con, "UPDATE tbl_time_log SET Difference = '$difference' WHERE Id = '$record'")or die(mysqli_error($con));
	
	mysqli_query($con, "UPDATE tbl_time_log SET Balance = '$new_balance'")or die(mysqli_error($con));
	
	mysqli_query($con, "UPDATE tbl_support SET Error = '$comments' WHERE Id = '$jobid'")or die(mysqli_error($con));
	
	header('Location: current.php?Id='. $jobid .'&Power='. $jobid);
	
	exit();
}	
	
header('Location: current.php?Id='. $jobid);
?>
