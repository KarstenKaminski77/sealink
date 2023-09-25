<?php
session_start();

require_once('functions/functions.php');

if(isset($_POST['message'])){
$message = $_POST['message'];
$_SESSION['message'] = $message;
$quoteno = $_GET['Id'];

mysql_query("INSERT INTO tbl_qs (Message) VALUES ('$message') WHERE QuoteNo = '$quoteno'")or die(mysql_error());

header('Location: mail_quote.php?Id='. $quoteno .'');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="quote_mail.php?Id=<?php echo $_GET['Id']; ?>">
  <div align="center">
    <table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td align="center"><input name="mail" type="text" class="tarea2" id="mail" style="cursor:text" size="70"/></td>
      </tr>
      <tr>
        <td align="center"><textarea name="message" cols="70" rows="10" class="tarea2" id="message" style="cursor:text"></textarea></td>
      </tr>
      <tr>
        <td align="center"><input name="Submit" type="submit" class="camcel" value="Send Email" /></td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
