<?php 
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

$query_mail = "
	SELECT
		tbl_jc.*,
		tbl_sla_history.*,
		tbl_companies.`Name` AS CompanyName,
		tbl_sites.`Name` AS SiteName
	FROM
		tbl_jc
	INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
	INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
	INNER JOIN tbl_sla_history ON tbl_jc.JobId = tbl_sla_history.JobId
	WHERE
		tbl_jc.JobId = '". $_GET['Id'] ."'";
		
$query_mail = mysqli_query($con, $query_mail)or die(mysqli_error($con));
$row_mail = mysqli_fetch_array($query_mail);

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '". $row_mail['CompanyId'] ."' AND CatId = '". $row_mail['SlaCatId'] ."'")or die(mysqli_error($con));
$sla_rows = mysqli_num_rows($query_sla_sub_cat);

if(isset($_POST['send'])){
	
	// Generate Job Id
	mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$jobid = $row['Id'] + 1;
	
	$query_jc = "
		SELECT
			tbl_jc.*,
			tbl_sla_history.*,
			tbl_companies.`Name` AS CompanyName,
			tbl_sites.`Name` AS SiteName,
			tbl_sla_subcat.Duration,
			tbl_sla_subcat.SubCat
		FROM
			tbl_jc
		INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
		INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
		INNER JOIN tbl_sla_history ON tbl_jc.JobId = tbl_sla_history.JobId
		INNER JOIN tbl_sla_subcat ON tbl_jc.SlaSubCatId = tbl_sla_subcat.Id
		WHERE
			tbl_jc.JobId = '". $_GET['Id'] ."'";
	
	$query_jc = mysqli_query($con, $query_jc)or die(mysqli_error($con));
	while($row_jc = mysqli_fetch_array($query_jc)){
		
		$_SESSION['jobid'] = $row_jc['JobId'];
	
		// Calculate SLA End date & time
		if(!empty($_POST['date1']) && !empty($_POST['date2'])){
			
			$date1 = $_POST['date1'];
			$date2 = $_POST['date2'];
			
		} else {
			
			$date1 = $row_jc['Date1'];
			$date2 = $row_jc['Date2'];
			
		}
		
		$hours = round((strtotime($date2) - strtotime($date1)) / 3600, 1);
		$start = date('Y-m-d H:i:s');
		
		$future = addRollover($start, $row_jc['Duration'], '8:00', '16:30', true);
		$end = $future->format('Y-m-d H:i:s');
		
		$form_data = array(
		
			'JobId' => $jobid,
			'CompanyId' => $row_jc['CompanyId'],
			'SiteId' => $row_jc['SiteId'],
			'JobNo' => 'RWRK-' . $row_jc['JobNo'],
			'Description' => $row_jc['Description'],
			'Date' => date('d M Y'),
			'Days' => date('Y-m-j'),
			'Reference' => $row_jc['Reference'],
			'AreaId' => $_SESSION['areaid'],
			'Date1' => $start,
			'Date2' => $end,
			'SlaCatId' => $_POST['sla'],
			'SlaSubCatId' => $_POST['sub_cat'],
			'ContractorId' => $row_jc['Contractor'],
			'Labour' => $row_jc['Labour'],
			'Material' => $row_jc['Material'],
			'Comment' => $row_jc['Comment'],
			'Status' => '2',
			'JcDate' => $row_jc['JcDate'],
			'JobDescription' => $row_jc['JobDescription'],
			'JobcardPDF' => $row_jc['JobcardPDF'],
		
		);
		
		dbInsert('tbl_jc', $form_data, $con);
	}
	
	$travel_data = array(
	
		'JobId' => $jobid
	);
	
	dbInsert('tbl_travel', $travel_data, $con);
	
	$sla_data = array(
	
		'JobId' => $jobid,
		'JobNo' => 'RWRK-' . $row_mail['JobNo'],
		'SlaStart' => $start,
		'SlaEnd' => $end,
	);
	
	dbInsert('tbl_sla_history', $sla_data, $con);
	
	$feedback_data = array(
	
		'Reference' => 'RWRK-' . $row_mail['JobNo'],
		'Date' => date('Y-m-d'),
		'Status' =>	'2',
	);
	
	dbInsert('tbl_feedback', $feedback_data, $con);
	
	$query_far = mysqli_query($con, "SELECT * FROM tbl_far WHERE JobNo = '". $row_mail['JobNo'] ."'")or die(mysqli_error($con));
	$row_far = mysqli_fetch_array($query_far);
	
	$far_data = array(
	
		'JobNo' => 'RWRK-' . $row_mail['JobNo'],
		'RiskType' => $row_far['RiskType'],
		'RiskClassification' => $row_far['RiskClassification'],
	
	);
	
	dbInsert('tbl_far', $far_data, $con);
	
	$rework_data = array(
	
		'Date' => date('Y-m-d'),
		'OldJobId' => $_SESSION['jobid'],
		'NewJobId' => $jobid,
		'Creator' => $_COOKIE['name'],
		'Requestor' => $_POST['requestor'],
		'Reason' => $_POST['reason'],
	
	);
	
	dbInsert('tbl_jc_rework', $rework_data, $con);
  
	date_default_timezone_set('Africa/Johannesburg');
	
	require '../PHPMailer/PHPMailerAutoload.php';
			
	$body = '
		<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a">
			<div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px">
				<br>
				<table width="100%" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
					<tr>
						<td colspan="2" style="font-size:18px;"><strong>REWORK</strong></td>
					</tr>
					<tr>
						<td colspan="2" style="font-size:18px;">&nbsp;</td>
					</tr>
					<tr>
						<td width="120"><strong>Job No</strong></td>
						<td>'. 'RWRK-' . $row_mail['JobNo'] .'</td>
					</tr>
					<tr>
						<td><strong>Site</strong></td>
						<td>'. $row_mail['SiteName'] .'</td>
					</tr>
					<tr>
						<td><strong>Date</strong></td>
						<td>'. date('Y-m-d') .'</td>
					</tr>
					<tr>
					  <td><strong>Rejected By</strong></td>
					  <td>'. $_POST['requestor'] .'</td>
				  </tr>
					<tr>
					  <td><strong>Created By</strong></td>
					  <td>'. $_COOKIE['name'] .'</td>
				  </tr>
					<tr>
						<td><strong>Service Requested</strong></td>
						<td>'. stripslashes(nl2br($row_mail['JobDescription'])) .'</td>
					</tr>
					<tr>
						<td><strong>Reason Rejected</strong></td>
						<td>'. stripslashes(nl2br($_POST['reason'])) .'</td>
					</tr>
				</table>
				<br />
				<br />
				<img src="http://www.seavest.co.za/inv/images/signature-new.jpg" />
			</div>
		</body>';
	
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
	$mail->setFrom('control@seavest.co.za', 'Seavest Africa');
	//Set an alternative reply-to address
	$mail->addReplyTo('control@seavest.co.za', 'Seavest Africa');
	//Set who the message is to be sent to
	$mail->addAddress('nicky@seavest.co.za', 'Seavest Africa');
	//$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
	//Set the subject line
	$mail->Subject = 'Rework Job Alert';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($html = $body);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if ($mail->send()){
				
		//dbInsert('tbl_jc', $form_data, $con);
				
	}
}

$query_sla_cat = mysqli_query($con, "SELECT * FROM tbl_sla_cat ORDER BY Category ASC")or die(mysqli_error($con));

if(isset($_GET['SLA'])){
	
	$catid = $_GET['SLA'];
	
} else {
	
	$catid = $row_mail['SlaCatId'];
}

$companyid = $row_mail['CompanyId'];

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '$companyid' AND CatId = '$catid' ORDER BY SubCat ASC")or die(mysqli_error($con));
$sla_rows = mysqli_num_rows($query_sla_sub_cat);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/mobile.css" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>

	  <script language=JavaScript>
	  		
		function reload1(form){
			var val_1 = document.getElementById('requestor').value;
			var val_2 = document.getElementById('reason').value;
			var val_3 = form.sla.options[form.sla.options.selectedIndex].value; 
			self.location='rework-new.php?Id=<?php echo $_GET['Id']; ?>&Requestor=' + val_1 + '&Reason=' + val_2 + '&SLA=' + val_3 ;
		}
		
		function reload2(form){
			var val_1 = document.getElementById('requestor').value;
			var val_2 = document.getElementById('reason').value;
			var val_3 = form.sla.options[form.sla.options.selectedIndex].value; 
			var val_4 = form.sub_cat.options[form.sub_cat.options.selectedIndex].value; 
			self.location='rework-new.php?Id=<?php echo $_GET['Id']; ?>&Requestor=' + val_1 + '&Reason=' + val_2 + '&SLA=' + val_3 + '&SLA=' + val_4 ;
		}
		
      </script>

</head>

<body>
<form id="form1" name="form1" method="post" action="">
  
    <?php 
	
	if(!empty($errors) && isset($_POST['send'])){
		
		echo '<div id="banner-error">';
	
		for($i=0;$i<count($errors);$i++){
			
			echo nl2br($errors);
		}
		
		echo '</div>';
	}
	
	?>
  
  <div id="list-border">
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td colspan="4" align="center" class="td-header">Create Rework Job Card #<?php echo $row_mail['JobNo']; ?></td>
      </tr>
      <tr>
        <td width="120" class="td-left">Requestor</td>
        <td colspan="3" class="td-right"><input name="requestor" type="text" class="tarea-100" id="requestor" value="<?php echo $_GET['Requestor']; ?>" /></td>
      </tr>
      <tr>
        <td valign="top" class="td-left">Reason</td>
        <td colspan="3" class="td-right"><textarea name="reason" rows="5" class="tarea-100" id="reason"><?php echo $_GET['Reason']; ?></textarea></td>
      </tr>
      
	  <?php 
      if($sla_rows >= 1){
          
          $colspan = 1;
          
      } else {
          
          $colspan = 3;
      }
      
      ?>
      
      <tr>
        <td class="td-left">SLA Category</td>
        <td class="td-right" colspan="<?php echo $colspan; ?>">
        
        <select name="sla" class="tarea-100" id="sla" onchange="reload1(this.form)">
          <option value="">Category...</option>
          <?php while($row_sla_cat = mysqli_fetch_array($query_sla_cat)){ ?>
          <option value="<?php echo $row_sla_cat['Id']; ?>" <?php if($_GET['SLA'] == $row_sla_cat['Id'] || $row_mail['SlaCatId'] == $row_sla_cat['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_sla_cat['Category']; ?></option>
          <?php } ?>
        </select>
        
        </td>
        
        <?php if($sla_rows >= 1){ ?>
        
        <td width="120" class="td-left">SLA Sub Category</td>
        <td class="td-right"><select name="sub_cat" class="tarea-100" id="sub_cat" onchange="reload2(this.form)">
          <option value="">Sub Category...</option>
          <?php while($row_sla_sub_cat = mysqli_fetch_array($query_sla_sub_cat)){ ?>
          <option value="<?php echo $row_sla_sub_cat['Id']; ?>" <?php if($_GET['SubCat'] == $row_sla_sub_cat['Id'] || $row_mail['SlaSubCatId'] == $row_sla_sub_cat['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_sla_sub_cat['SubCat']; ?></option>
          <?php } ?>
        </select></td>
        
        <?php } ?>
        
      </tr>
      
      <?php if(isset($_GET['SLA']) && $sla_rows == 0){ ?>
      <tr>
        <td class="td-left">Strat Date</td>
        <td class="td-right">
        
        <input name="date1" type="text" class="tarea-100" id="date1" value="<?php echo $row_mail['Date1']; ?>" />
        
          <script type="text/javascript">
                $('#date1').datepicker({
                dateFormat: "yy-mm-dd"
                });
                </script>
        </td>
        <td class="td-left">End Date</td>
        <td class="td-right">
        
        <input name="date2" type="text" class="tarea-100" id="date2" value="<?php echo $row_mail['Date2']; ?>" />
        
          <script type="text/javascript">
                $('#date2').datepicker({
                dateFormat: "yy-mm-dd"
                });
                </script>
                
      </td>
      </tr>
      <?php } ?>
      
    </table>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="right"><input name="send" type="submit" class="btn-flat" id="send" value="Create Job Card" /></td>
    </tr>
  </table>
</form>
</body>
</html>