<?php require_once('../Connections/inv.php'); 

date_default_timezone_set('UTC');

$month = '09';

$query_bal = mysqli_query($con, "SELECT Balance FROM tbl_time_log ")or die(mysqli_query($con));
$row_bal = mysqli_fetch_array($query_bal);

$query_log = "
	SELECT
		`tbl_support`.`Date`
		, `tbl_support`.`Id`
		, `tbl_time_log`.`Id` AS  Record
		, `tbl_time_log`.`Login`
		, `tbl_time_log`.`Logout`
		, SEC_TO_TIME( SUM( TIME_TO_SEC(tbl_time_log.Difference))) AS Hrs
		, `tbl_time_log`.`Balance`
		, `tbl_time_log`.`JobId`
	FROM
		`tbl_support`
		INNER JOIN `tbl_time_log` 
			ON (`tbl_support`.`Id` = `tbl_time_log`.`JobId`)
		WHERE MONTH(tbl_support.Date) = '$month'
		GROUP BY MONTH(tbl_support.Date)";
 
$query_log = mysqli_query($con, $query_log)or die(mysqli_error($con));
$row_log = mysqli_fetch_array($query_log);

$opening_bal = strtotime($row_bal['Balance']);
$hours = strtotime('18:00:00');

if($row_log['Hrs'] > '18:00:00'){
	
	$opening_bal = date('H:i:s', strtotime($row_bal['Balance']));
	$diff = date('H:i:s', strtotime($row_log['Hrs']) - strtotime('18:00:00')); 
	$new_balance = date('H:i:s', (strtotime($opening_bal) - strtotime($diff)));
	$balance = date('H:i:s', strtotime ('+18 hours', strtotime($row_bal['Balance'])));
			
	echo '<b>OVER 18 HOURS</b><br>';
	echo 'Opening Balance: ' . $opening_bal .'<br>';
	echo 'Total Time '. date('M', mktime(0,0,0,$month + 1,0,0)) .': ' . $row_log['Hrs'] . '<br>';
	echo 'Difference: ' . $diff . '<br>';
	echo 'Balance For Next Month: ' . $new_balance . '<br>';
	
	echo 'Added Monthly: ' . $balance .'<br>';
	
	echo 'Test: '. $row_bal['Balance'];
	
	
} else {
	
	$opening_bal = date('H:i:s', strtotime($row_bal['Balance']));
	$balance = date('H:i:s', strtotime ('+18 hours', strtotime($row_bal['Balance'])));
	$diff = date('H:i:s', 64800 - strtotime($row_log['Hrs'])); 
	$new_balance = date('H:i:s', 64800 + strtotime($diff));
	
	echo '<b>UNDER 18 HOURS</b><br>';
	echo 'Opening Balance: ' . $opening_bal .'<br>';
	echo 'Added Monthly: ' . $balance .'<br>';
	echo 'Total Time '. date('M', mktime(0,0,0,$month + 1,0,0)) .': ' . $row_log['Hrs'] . '<br>';
	echo 'Difference: ' . $diff . '<br>';
	echo 'Balance Brought Forward: ' . $new_balance . '<br>';
}

	

//mysqli_query($con, "UPDATE tbl_time_log SET Balance = '$new_balance'")or die(mysqli_error($con));
	

?>