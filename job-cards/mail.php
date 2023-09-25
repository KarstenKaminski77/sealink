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
		tbl_jc.JobId = '". $_GET['Rework'] ."'";
		
$query_mail = mysqli_query($con, $query_mail)or die(mysqli_error($con));
$row_mail = mysqli_fetch_array($query_mail);

  
date_default_timezone_set('Africa/Johannesburg');

require '../PHPMailer/PHPMailerAutoload.php';
		
$body = '
	<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a">
		<div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px">
			<br>
			<table width="100%" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
				<tr>
					<td colspan="2" style="font-size:18px;"><strong>REWORK COMPLETE</strong></td>
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
				<tr>
					<td><strong>Job Card</strong></td>
					<td><a href="http://www.seavest.co.za/inv/job-cards/jc-calc.php?Id='. $row_mail['JobId'] .'">View Job Card</a></td>
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
$mail->addAddress('karsten@kwd.co.za', 'KWD');
//$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
//Set the subject line
$mail->Subject = 'Rework Job Complete';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($html = $body);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if ($mail->send()){
			
	header('Location: rework.php?Success');
			
}
	
?>