<?php
    $Content = file_get_contents("http://www.applejack.co.za/cartoons.php");

    $Headers  = "MIME-Version: 1.0\n";
    $Headers .= "Content-type: text/html; charset=iso-8859-1\n";
    $Headers .= "From: KWD <test@kwd.co.za>\n";
    $Headers .= "Reply-To: info@kwd.co.za \n";
    $Headers .= "X-Sender: <test@kwd.co.za>\n";
    $Headers .= "X-Mailer: PHP\n"; 
    $Headers .= "X-Priority: 1\n"; 
    $Headers .= "Return-Path: <test@kwd.co.za>\n";  

    if(mail('info@kwd.co.za', 'test', $Content, $Headers) == false) {
        //Error
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php echo $Content; ?><br />
<br />
<?php echo phpinfo(); ?>
</body>
</html>