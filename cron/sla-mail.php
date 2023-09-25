<?php 
session_start();

require_once('../functions/functions.php');

$query = "
SELECT
	tbl_sla_email.Id,
	tbl_sla_email.CatId,
	tbl_sla_email.`Name`,
	tbl_sla_email.Email,
	tbl_sla_email.Content,
	tbl_jc.JobNo,
	tbl_jc.Date2,
	tbl_jc.JobId,
	tbl_jc.AreaId,
	tbl_companies.`Name` AS OilCompany,
	tbl_sites.`Name` AS SiteName,
	tbl_sla_subcat.Duration
FROM
	tbl_sla_email
INNER JOIN tbl_jc ON tbl_jc.SlaCatId = tbl_sla_email.CatId
INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
INNER JOIN tbl_sla_subcat ON tbl_jc.SlaSubCatId = tbl_sla_subcat.Id
WHERE
	tbl_jc.`Status` = 1
OR tbl_jc.`Status` = 2
OR tbl_jc.`Status` = 7
GROUP BY
	tbl_sla_email.Id,
	tbl_jc.JobId";
	  
$query = mysqli_query($con, $query)or die(mysqli_error($con));
while($row = mysqli_fetch_array($query)){
	
	date_default_timezone_set("Africa/Johannesburg");
	
	$start = strtotime($row['Date1']);
	$end = strtotime(date('Y-m-d H:i:s'));
	$sla = $row['Duration'];
	
	$hours = work_hours_diff($start,$end);
	
	if($hours > $sla){
	
		$mailid = $row['Id'];
		$areaid = $row['AreaId'];
		
		$query_area = mysqli_query($con, "SELECT * FROM tbl_sla_areas WHERE MailId = '$mailid' AND AreaId = '$areaid'")or die(mysqli_error($con));
		
		if(mysqli_num_rows($query_area) >= 1){
		
			$to  = $row['Email']; echo $to .' - '. $row['JobNo'] .'<br>';
			$from = 'control@seavest.co.za';
			$subject = 'SLA Overdue: Job No '. $row['JobNo'];
			
			$message = '
			<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a">
			<div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px">
			<table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td width="80"><strong>Oil Company</strong></td>
				<td>'. $row['OilCompany'] .'</td>
			  </tr>
			  <tr>
				<td><strong>Site Name</strong></td>
				<td>'. $row['SiteName'] .'</td>
			  </tr>
			  <tr>
				<td><strong>Job Numbr</strong></td>
				<td>'. $row['JobNo'] .'</td>
			  </tr>
			  <tr>
				<td><strong>Due Date</strong></td>
				<td>'. $row['Date2'] .'</td>
			  </tr>
			</table>
			<br><img src="http://www.seavest.co.za/inv/images/icons/signature-new.jpg"></div>
			</body>';
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'FROM: '.$from . "\r\n";
			
			mail($to, $subject, $message, $headers);
		}
	}
}
?>