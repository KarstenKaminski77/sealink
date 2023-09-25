<?php
session_start();

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

require_once('../functions/functions.php');

logout($con);

$jobid = $_GET['Id'];

$query_jc = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
$row_jc = mysqli_fetch_array($query_jc);

if(isset($_POST['Submit'])){
	
	$query_qs = mysqli_query($con, "SELECT * FROM tbl_qs WHERE JobId = '$jobid'")or die(mysqli_error($con));
	$row_qs = mysqli_fetch_array($query_qs);
	
	if(mysqli_num_rows($query_qs) == 0){
				
		$query = mysqli_query($con, "SELECT * FROM tbl_qs ORDER BY QuoteNo DESC LIMIT 1")or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		
		$quoteno = $row['QuoteNo']+1;
		
		mysqli_query($con, "UPDATE tbl_jc SET QuoteNo = '$quoteno', Status = '24' WHERE JobId = '$jobid'")or die(mysqli_error($con));
		
		// SLA
//		$query_end = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE Id = '". $_POST['sub_cat'] ."'")or die(mysqli_error($con));
//		$row_end = mysqli_fetch_array($query_end);
//		
//		$duration = $row_end['Duration'];
//		
//		$start = date('Y-m-d H:i:s');
//		
//		$future = addRollover(date('Y-m-d H:i:s'), $duration, '8:00', '16:30', true);
//		
//		$end = $future->format('Y-m-d H:i:s').'</br>';
		// End SLA
		
		$quote_data = array(
		
		    'AreaId' => $row_jc['AreaId'],
			'CompanyId' => $row_jc['CompanyId'],
			'SiteId' => $row_jc['SiteId'],
			'JobNo' => $row_jc['JobNo'],
			'QuoteNo' => $quoteno,
			'Date' => date('Y-m-d'),
			'SlaStart' => $start,
			'SlaEnd' => $end,
			'Time' => date('H:i:s'),
			'JobDescription' => $_POST['desc'],
			'Days' => date('Y-m-d'),
			'Status' => 4,
			'UserId' => $_COOKIE['userid'],
			'FMC' => $row_jc['JobNo'],
			'Notes' => $_POST['desc'],
			'JobId' => $_GET['Id']
		);
		
		$quote_data['Labour'] = 1;
		$quote_data['Material'] = 0;
		$quote_data['Transport'] = 0;
		
		dbInsert('tbl_qs', $quote_data, $con);
		
		$quote_data['Labour'] = 0;
		$quote_data['Material'] = 1;
		$quote_data['Transport'] = 0;
		
		dbInsert('tbl_qs', $quote_data, $con);
		
		$quote_data['Labour'] = 0;
		$quote_data['Material'] = 0;
		$quote_data['Transport'] = 1;
		
		dbInsert('tbl_qs', $quote_data, $con);
				
		$qs_data = array('QuoteNo' => $quoteno);
		
		dbInsert('tbl_qs_hes', $qs_data, $con);
		dbInsert('tbl_qs_equipment', $qs_data, $con);
		dbInsert('tbl_costing_material', $qs_data, $con);
		dbInsert('tbl_costing_labour', $qs_data, $con);
		dbInsert('tbl_costing_outsourcing', $qs_data, $con);
		dbInsert('tbl_costing_hes', $qs_data, $con);
		dbInsert('tbl_costing_equipment', $qs_data, $con);
		dbInsert('tbl_costing_transport', $qs_data, $con);
		
		
		$query_history = "
			SELECT
			tbl_technicians.
			`Name`
			AS Name_1,
			tbl_actual_history.JobId,
				tbl_users.
			`Name`,
			tbl_actual_history.Date,
				tbl_actual_history.Comments,
				tbl_actual_history.Mobile
			FROM
				(
					(
						tbl_actual_history LEFT JOIN tbl_users ON tbl_users.Id = tbl_actual_history.TechnicianId
					) LEFT JOIN tbl_technicians ON tbl_technicians.Id = tbl_actual_history.TechnicianId
				)
			WHERE
			tbl_actual_history.JobId = '$jobid'
			ORDER BY
			tbl_actual_history.Id ASC ";
		
		$history = mysqli_query($con, $query_history) or die(mysqli_error($con));
		$row_history = mysqli_fetch_assoc($history);
		
		$_SESSION['history'] = '';
		
	    do {
		  
			if($row_history['Mobile'] == 1){
				
				$name = $row_history['Name_1'];
			
			} else {
				
				$name = $row_history['Name'];
			
			} 
	
			$_SESSION['history'] .= '<span class="history-bg-con">
							<span class="history-bg">
							'.
							$name .' '. date('m/d H:i', strtotime($row_history['Date'])) .'
							</span> '. $row_history['Comments'] .'
						  </span>';
	  
	   } while ($row_history = mysqli_fetch_assoc($history));

	   mysqli_query($con, "UPDATE tbl_qs SET InternalNotes = '". addslashes($_SESSION['history']) ."' WHERE JobId = '". $_GET['Id'] ."'")or die(mysqli_error($con));
	   mysqli_query($con, "UPDATE tbl_actual_history SET QuoteNo = '$quoteno' WHERE JobId = '". $_GET['Id'] ."'")or die(mysqli_error($con));
	   
	   $query_sla = "
		  SELECT 
			`tbl_jc`.`JobNo`,
			`tbl_jc`.`JobId`,
			`tbl_companies`.`Name` AS CompanyName,
			`tbl_sites`.`Name` AS SiteName,
			`tbl_sla_subcat`.`SubCat`,
			`tbl_qs`.`QuoteNo` 
		  FROM
			`tbl_jc` 
			INNER JOIN `tbl_companies` 
			  ON (
				`tbl_jc`.`CompanyId` = `tbl_companies`.`Id`
			  ) 
			INNER JOIN `tbl_sites` 
			  ON (
				`tbl_jc`.`SiteId` = `tbl_sites`.`Id`
			  ) 
			INNER JOIN `tbl_sla_subcat` 
			  ON (
				`tbl_jc`.`SlaSubCatId` = `tbl_sla_subcat`.`Id`
			  ) 
			INNER JOIN `tbl_qs` 
			  ON (
				`tbl_jc`.`JobNo` = `tbl_qs`.`JobNo`
			  ) 
		  WHERE tbl_jc.JobId = '$jobid' ";	 
			
	   $query_sla = mysqli_query($con, $query_sla)or die(mysqli_error($con));
	   $row_sla = mysqli_fetch_array($query_sla);
	   
	  date_default_timezone_set('Etc/UTC');
	  
	  require '../PHPMailer/PHPMailerAutoload.php';
	  
	  //Create a new PHPMailer instance
	  $mail = new PHPMailer;
	  //Tell PHPMailer to use SMTP
	  $mail->isSMTP();
	  //Enable SMTP debugging
	  // 0 = off (for production use)
	  // 1 = client messages
	  // 2 = client and server messages
	  $mail->SMTPDebug = 0;
	  //Ask for HTML-friendly debug output
	  $mail->Debugoutput = 'html';
	  //Set the hostname of the mail server
	  $mail->Host = "www27.jnb1.host-h.net";
	  //Set the SMTP port number - likely to be 25, 465 or 587
	  $mail->Port = 587;
	  //Whether to use SMTP authentication
	  $mail->SMTPAuth = true;
	  //Username to use for SMTP authentication
	  $mail->Username = "test@kwd.co.za";
	  //Password to use for SMTP authentication
	  $mail->Password = "K4rsten001";
	  //Set who the message is to be sent from
	  $mail->setFrom('test@kwd.co.za', 'Seavest Africa');
	  //Set an alternative reply-to address
	  $mail->addReplyTo('test@kwd.co.za', 'Seavest Africa');
	  //Set who the message is to be sent to
	  $mail->addAddress('test@kwd.co.za', 'Seavest Africa');
	  //$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
	  //Set the subject line
	  $mail->Subject = 'Estimate - '. $row_sla['SubCat'];
	  //Read an HTML message body from an external file, convert referenced images to embedded,
	  //convert HTML into a basic plain-text alternative body
	  $mail->msgHTML($html = '
		  <body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a"><div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px"><br>
			  <table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
				<tr>
				  <td colspan="2" style="font-size:18px;"><strong>ESTIMATE NOTIFICATION</strong></td>
				</tr>
				<tr>
				  <td colspan="2" style="font-size:18px;">&nbsp;</td>
				</tr>
				<tr>
				  <td width="100"><strong>Oil Company</strong></td>
				  <td>'. $row_sla['CompanyName'] .'</td>
				</tr>
				<tr>
				  <td><strong>Site</strong></td>
				  <td>'. $row_sla['SiteName'] .'</td>
				</tr>
				<tr>
				  <td><strong>Job Number</strong></td>
				  <td>'. $row_sla['JobNo'] .'</td>
				</tr>
				<tr>
				  <td><strong>Quote Number</strong></td>
				  <td>'. $row_sla['QuoteNo'] .'</td>
				</tr>
				<tr>
				  <td><strong>SLA</strong></td>
				  <td>'. $row_sla['SubCat'] .'</td>
				</tr>
			</table>
			<br />
			<br />
			<img src="http://www.seavest.co.za/inv/images/signature-new.jpg" width="600" height="68" /> </div>
		  </body>
		  ');
	  //Replace the plain text body with one created manually
	  $mail->AltBody = 'This is a plain-text message body';
	  //Attach an image file
	  $mail->addAttachment('images/phpmailer_mini.png');
	  
	  //send the message, check for errors
	  if (!$mail->send()) {
		  
		  
	  } else {
		  
	  }
		
	   header('Location: create-quote.php?Success');
	}
}

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '". $row_jc['CompanyId'] ."' AND CatId = '7' ORDER BY SubCat ASC")or die(mysqli_error($con));
$sla_rows = mysqli_num_rows($query_sla_sub_cat);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Untitled Document</title>
      <link href="../css/fancybox.css" rel="stylesheet" type="text/css" />

	  <script language=JavaScript>
	  
		function reload1(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			self.location='create-quote.php?Company=' + val ;
		}
		
		function reload2(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			var val2=form.site.options[form.site.options.selectedIndex].value; 
			self.location='create-quote.php?Company=' + val + '&Site=' + val2;
		}
		
      </script>
      
</head>

<body>
<form action="" method="post" name="form1" id="form1">

<?php if(isset($_GET['Success'])){ ?>

   <div id="banner-success">Estimate Created Successfully</div>

<?php } else { ?>

  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" style="margin:0">
    <tr>
      <td colspan="2" align="center" class="td-header">Create Estimate</td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="td-left"><p class="red-title">Create estimate for job <?php echo $_GET['JobNo']; ?></p></td>
    </tr>
    <tr>
      <td width="120" valign="top" class="td-left">Job Description</td>
      <td width="1220" class="td-left"><textarea name="desc" rows="5" class="tarea-100" id="desc"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input name="Submit" type="submit" class="btn-new" value="Create Estimate" id="Submit" /></td>
    </tr>
  </table>
 
<?php } ?> 
  
</form>
</body>
</html>