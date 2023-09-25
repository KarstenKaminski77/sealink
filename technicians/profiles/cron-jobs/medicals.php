<?php 
session_start();
require_once('../../../Connections/seavest.php');
require_once('../../../functions/functions.php');

$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians")or die(mysqli_error($con));
while($row_techs = mysqli_fetch_array($query_techs)){
	
	$query_medicals = mysqli_query($con, "SELECT * FROM tbl_profile_medicals WHERE TechId = '". $row_techs['Id'] ."' ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row_medicals = mysqli_fetch_array($query_medicals);
	
	if(!empty($row_medicals['NextExaminationDate'])){
		
		$now = time(); 
		$next_date = strtotime($row_medicals['NextExaminationDate']);
		$datediff = $next_date - $now;
		
		$days = floor($datediff/(60*60*24));
		
		if($days == '27' || $days == '6' || $days == '0' || $days == '-1'){
			
			if($days == '27'){
				
				$to_go = ' In 4 Weeks';
				
			} elseif($days == '6'){
			
				$to_go = ' In 1 Week';
			
			} elseif($days == '0'){
			
				$to_go = ' Tomorrow';
			
			} elseif($days == '-1'){
			
				$to_go = ' Today';
			
			}
			
			date_default_timezone_set('Etc/UTC');
			
			require '../../../PHPMailer/PHPMailerAutoload.php';
			
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
			$mail->addAddress($row_techs['Email'], $row_techs['Name']);
			$mail->addCC('marcus.abrahams@seavest.co.za', 'Seavest Africa');
			//Set the subject line
			$mail->Subject = 'Seavest Africa Medical Examination'. $to_go;
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($html = '<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a"><div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px"><br>
		  <table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
			<tr>
			  <td colspan="2" style="font-size:18px;"><strong>MEDICAL EXAMINATION</strong></td>
		    </tr>
			<tr>
			  <td colspan="2" style="font-size:18px;">&nbsp;</td>
		    </tr>
			<tr>
			  <td width="7%"><strong>Tecnician</strong></td>
			  <td width="93%">'. $row_techs['Name'] .'</td>
			</tr>
			<tr>
			  <td width="7%"><strong>Date</strong></td>
			  <td width="93%">'. $row_medicals['NextExaminationDate'] .'</td>
			</tr>
			<tr>
			  <td width="7%"><strong>Practitioner</strong></td>
			  <td width="93%">'. $row_medicals['Practitioner'] .'</td>
			</tr>
		</table>
		<br />
		<br />
		<img src="http://www.seavest.co.za/inv/images/icons/signature-new.jpg" width="600" height="68" /> </div>
		</body>');
			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';
			//Attach an image file
			$mail->addAttachment('images/phpmailer_mini.png');
			
			//send the message, check for errors
			if (!$mail->send()) {
				
				
			} else {
				
			}
					
		}
	}
}

?>

