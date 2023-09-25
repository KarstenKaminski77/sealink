<?php 
session_start();

	
	date_default_timezone_set('Africa/Johannesburg');
	
	require '../../PHPMailer/PHPMailerAutoload.php';
	
	$close_date = date('Y-m-d H:i:s');
		stripslashes($row_jc['JobDescription']);
	$body = '
		<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a">
			<div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px">
				<br>
				<table width="100%" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
					<tr>
						<td width="120">'. nl2br($_POST['body']) .'</td>
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
	// $mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 2;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
	// $mail->Host = "www27.jnb1.host-h.net";
	//Set the SMTP port number - likely to be 25, 465 or 587
	// $mail->Port = 587;
	//Whether to use SMTP authentication
	// $mail->SMTPAuth = true;
	//Username to use for SMTP authentication
	// $mail->Username = "test@kwd.co.za";
	//Password to use for SMTP authentication
	// $mail->Password = "K4rsten001";
	//Set who the message is to be sent from
	$mail->setFrom('control@seavest.co.za', 'Seavest Africa');
	//Set an alternative reply-to address
	$mail->addReplyTo('control@seavestafrica.co.za', 'Seavest Africa');
	//Set who the message is to be sent to
	$mail->addAddress($_POST['to'], $row_jc['Name']);
	//$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
	//Set the subject line
	$mail->Subject = $_POST['subject'];
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($html = $body);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	$mail->addAttachment('../../pdf/pdf/Seavest-Statistics-Report-#'.$_GET['Mail'].'.pdf');
	

	// $mail->SMTPOptions = array(
	// 'ssl' => array(
	// 'verify_peer' => false,
	// 'verify_peer_name' => false,
	// 'allow_self_signed' => true
	// ));
	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		header('Location: search.php?MailSuccess&Report='. $_GET['Mail']);
	}
	
	
	
		