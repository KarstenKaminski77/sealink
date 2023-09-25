<?php 
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

$query_jc = "
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

$query_jc = mysqli_query($con, $query_jc)or die(mysqli_error($con));
$row_jc = mysqli_fetch_array($query_jc);

if($_GET['Company'] == 6){
	
	$to = 'theo.devraj@seavest.co.za';
	
} else {
	
	$query_engineer = mysqli_query($con, "SELECT * FROM tbl_engineers WHERE Name = '". $_GET['Engineer'] ."'")or die(mysqli_error($con));
	$row_engineer = mysqli_fetch_array($query_engineer);
	
	$to = $row_engineer['Email'];
	
}

$errors = '';

if(empty($_POST['to'])){
	
	$errors .= "Please enter an email address to send to!" . "\r\n";
}

if(empty($_POST['from'])){
	
	$errors .= "Please enter an email address to send from!" . "\r\n";
}

if(empty($_POST['subject'])){
	
	$errors .= "Please enter a subject!" . "\r\n";
}

if(empty($_POST['body'])){
	
	$errors .= "Please enter some text to the body!" . "\r\n";
}

if(isset($_POST['send']) && empty($errors)){
	
	date_default_timezone_set('Africa/Johannesburg');
	
	require '../PHPMailer/PHPMailerAutoload.php';
	
	$close_date = date('Y-m-d H:i:s');
		stripslashes($row_jc['JobDescription']);
	$body = '
		<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a">
			<div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px">
				<br>
				<table width="100%" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
					<tr>
						<td colspan="2" style="font-size:18px;"><strong>JOB CLOSED</strong></td>
					</tr>
					<tr>
						<td colspan="2" style="font-size:18px;">&nbsp;</td>
					</tr>
					<tr>
						<td width="120"><strong>JobNo</strong></td>
						<td>'. $row_jc['JobNo'] .'</td>
					</tr>
					<tr>
						<td><strong>Site</strong></td>
						<td>'. $row_jc['SiteName'] .'</td>
					</tr>
					<tr>
						<td><strong>Date</strong></td>
						<td>'. $close_date .'</td>
					</tr>
					<tr>
						<td><strong>Service Requested</strong></td>
						<td>'. stripslashes(nl2br($row_jc['JobDescription'])) .'</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">'. $_POST['body'] .'</td>
					</tr>
				</table>
				<br />
				<br />
				<img src="http://www.seavest.co.za/inv/images/icons/signature-new.jpg" width="600" height="68" />
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
	$mail->setFrom($_POST['from'], 'Seavest Africa');
	//Set an alternative reply-to address
	$mail->addReplyTo($_POST['from'], 'Seavest Africa');
	//Set who the message is to be sent to
	$mail->addAddress($to, $row_jc['Name']);
	//$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
	//Set the subject line
	$mail->Subject = $_POST['subject'];
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($html = $body);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if ($mail->send()){
		
		$form_data = array(
		  
		  'DateCompleted' => $close_date,
		  'EmailFrom' => $_POST['from'],
		  'EmailTo' => $to,
		  'EmailBody' => $body,
		  'Sender' => $_COOKIE['name']
		);
		
		dbUpdate('tbl_sla_history', $form_data, $where_clause="JobId = '". $_GET['Id'] ."'",$con);
				
	}
	
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/mobile.css" rel="stylesheet" type="text/css" />
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
        <td colspan="2" align="center" class="td-header">Stop SLA</td>
      </tr>
      <tr>
        <td width="7%" class="td-left">From</td>
        <td width="93%" class="td-right"><input name="from" type="text" class="tarea-100" id="from" value="control@seavest.co.za" /></td>
      </tr>
      <tr>
        <td class="td-left">To</td>
        <td class="td-right"><input name="to" type="text" class="tarea-100" id="to" value="<?php echo $to; ?>" /></td>
      </tr>
      <tr>
        <td class="td-left">Subject</td>
        <td class="td-right"><input name="subject" type="text" class="tarea-100" id="subject" value="<?php echo $row_jc['JobNo'] .' - '. $row_jc['SiteName']; ?>" /></td>
      </tr>
      <tr>
        <td valign="top" class="td-left">Body</td>
        <td class="td-right"><textarea name="body" rows="8" class="tarea-100" id="body"></textarea></td>
      </tr>
    </table>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="right"><input name="send" type="submit" class="btn-flat" id="send" value="Send" /></td>
    </tr>
  </table>
</form>
</body>
</html>