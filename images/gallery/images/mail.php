<?php
session_start();

$email = $_SESSION['email'];
$message = $_SESSION['message'];
	
$files = array();

if(!empty($_SESSION['images'])){
	
	for($i=0;$i<count($_SESSION['images']);$i++){
		
		$image = $_SESSION['images'][$i];
		
		array_push($files, $image);
		
	}
}

// email fields: to, from, subject, and so on
$to = $email;
$from = "hemi@seavest.co.za"; 
$subject ="Seavest Images"; 
$message = "<body style=\"font-family:tahoma; font-size:12px; margin: 20px; line-height:18px; color:#333366\"><img src=\"http://www.seavest.co.za/inv/fpdf16/mail_logo.jpg\"><br><br>". $message ."</body>";
$headers = "From: $from";

// boundary 
$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

// headers for attachment 
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

// multipart boundary 
$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-type:text/html; charset=utf8\r\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
$message .= "--{$mime_boundary}\n";

	// preparing attachments
	for($x=0;$x<count($files);$x++){ echo $files[$x] .' | '. $_SESSION['images'][$x] .'<br>';
		$file = fopen($files[$x],"rb");
		$data = fread($file,filesize($files[$x]));
		fclose($file);
		$data = chunk_split(base64_encode($data));
		$message .= 'Content-Type: {"application/octet-stream"};\n"' . '" name="'.$files[$x].'"\n"' . 
	    'Content-Disposition: attachment;\n" . " filename="'.$files[$x].'"\n"' . 
	    'Content-Transfer-Encoding: base64\n\n"' . $data . '"\n\n"';
	    $message .= "--{$mime_boundary}\n";
	}

$ok = @mail($to, $subject, $message, $headers);

if(isset($_GET['Recent'])){
	
	header('Location: recent.php');
	
} else {
	
	//header('Location: index.php');
}

?>
