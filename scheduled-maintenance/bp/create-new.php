<?php
session_start();

set_time_limit(0);
ignore_user_abort(1);

//ini_set('max_execution_time', 300);
//ini_set('max_input_time', 1200);
//ini_set('memory_limit','512M');

require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$start = date('Y-01-18');
$end = date('Y-12-23', strtotime('+1 year'));

$query = "
	SELECT
		tbl_sites.`Name`,
		tbl_scheduled_maintenance.Date,
		tbl_scheduled_maintenance.ScheduledMaintenanceId
	FROM
		tbl_scheduled_maintenance
	INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
	WHERE
		tbl_scheduled_maintenance.Date IS NULL
	ORDER BY
		tbl_scheduled_maintenance.ScheduledMaintenanceId DESC
	LIMIT 1";
	
$query = mysqli_query($con, $query)or die(mysqli_error($con));

if(mysqli_num_rows($query) >= 1){
	
	header('Location: list.php?Alert');
	
} else {

	mysqli_query($con, "INSERT INTO tbl_scheduled_maintenance_details (Company,Description,DateStart,DateEnd,Status)
	VALUES ('14','Scheduled Maintenance','$start','$end','In Progress')")or die(mysqli_error($con));
	
	$maintenanceid = last_id($con, 'tbl_scheduled_maintenance_details');
	
	$query = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Company = '14' AND AreaId = '1' ORDER BY Name ASC")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$siteid = $row['Id'];
		
		mysqli_query($con, "INSERT INTO tbl_scheduled_maintenance_sites (ScheduledMaintenanceId,SiteId)
		VALUES ('$maintenanceid','$siteid')")or die(mysqli_error($con));
				
		// Insert a job for each quarter (4 times)
		for($i=1;$i<=4;$i++){
			
			// Create Job Card	
			mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));
			
			$query_jobid = mysqli_query($con, "SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
			$row_jobid = mysqli_fetch_array($query_jobid);
			
			$jobid = $row_jobid['Id'] + 1;
			
			$jobno = 'Job No.';
			
			$_SESSION['jobid'] = $jobid;
			
			mysqli_query($con, "INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId) 
			VALUES 
			('1','14','$siteid','$jobno','$jobid')") or die(mysqli_error($con));
		
			// Insert Scheduled Maintenance
			mysqli_query($con, "INSERT INTO tbl_scheduled_maintenance (ScheduledMaintenanceId,SiteId,Description,Quarter,Status,JobId)
			VALUES ('$maintenanceid','$siteid','Scheduled Maintenance','$i','Qued','$jobid')")or die(mysqli_error($con));
			
		}
	}
}

  mysqli_close($con); 
  mysqli_free_result($query);
  mysqli_free_result($query_jobid);
  mysqli_free_result($query_assets);
  mysqli_free_result($query_check);

  header('Location: calendar.php');

?>