<?php require_once('../Connections/seavest.php'); ?>
<?php
include('Mail.php');
include('Mail/mime.php');

if(isset($_POST['Submit2'])){
	
	$target_path = "files";
	$target_path = $target_path . basename( $_FILES['attach']['name']); 
	
	if(move_uploaded_file($_FILES['attach']['tmp_name'], $target_path)) {
		
		$file_attachment = $_FILES['attach']['name'];
	}
	
	$from = "Anti Spam <control@stop-spam.co.za>";
	$to = $_POST['email'];
	$subject = $_POST['subject'];
	
	$headers = array ('From' => $from, 'To' => $to, 'Subject' => $subject);
	$text = 'Text version of email'; // text and html versions of email.
	$html = '<html><body>HTML version of email. <strong>This should be bold</strong></body></html>';
	
	$file = './file/'.$file_attachment; // attachment
	$crlf = "\n";
	
	$mime = new Mail_mime($crlf);
	$mime->setTXTBody($text);
	$mime->setHTMLBody($html);
	$mime->addAttachment($file, 'text/plain');
	
	//do not ever try to call these lines in reverse order
	$body = $mime->get();
	$headers = $mime->headers($headers);
	
	$host = "197.221.14.68";
	$username = "control@stop-spam.co.za";
	$password = "4ntisp4M";
	$port = '587';
	
	$smtp = Mail::factory('smtp', array ('host' => $host, 'auth' => true, 'username' => $username,'password' => $password, 'port' => $port));
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
		
		echo("<p>" . $mail->getMessage() . "</p>");
	
	} else {
		
		echo("<p>Message successfully sent!</p>");
	}
}

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../functions/functions.php');

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FFFFFF;
}
a:hover {
	text-decoration: none;
	color: #CCCCCC;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
-->
</style>
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><form action="" method="post" enctype="multipart/form-data" name="form2">
          <table width="705" border="0" cellpadding="3" cellspacing="1">
            <tr>
              <td colspan="6" nowrap><input name="email" type="text" class="td-mail" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:705px"></td>
            </tr>
            <tr>
              <td colspan="6" nowrap><input name="attach" type="file" class="td-mail" id="attach"></td>
            </tr>
            <tr>
              <td colspan="6" nowrap><textarea name="message" rows="5" class="td-mail" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:705px">Message</textarea></td>
            </tr>
            <tr>
              <td colspan="6" align="right" nowrap><input name="Submit2" type="submit" class="td-mail" value="Send"></td>
            </tr>
            <tr>
              <td colspan="6" align="center" nowrap>&nbsp;</td>
            </tr>
            <tr class="td-header">
              <td width="129" align="center" nowrap><strong>Date </strong></td>
              <td width="243" align="left"><strong>Sender</strong></td>
              <td width="84" align="center"><strong>Emails Sent</strong></td>
              <td width="168" align="left">Email Address</td>
              <td width="27" align="center">&nbsp;</td>
              <td width="21" align="center">&nbsp;</td>
            </tr>
            <?php do { 

$jobid = $row_Recordset3['JobId'];

?>
            <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td width="129" align="center" class="combo"><?php echo $row_Recordset3['InvoiceNo']; ?></td>
              <td width="243" class="combo"><?php echo $row_Recordset3['Name_1']; ?></td>
              <td width="84" class="combo">&nbsp;</td>
              <td width="168" class="combo">&nbsp;</td>
              <td align="center"><img title="Send to Debtors" src="../images/icons/btn-delete.png" width="25" height="25" border="0"></td>
              <td width="21" align="center"><?php
			  
			  // Check if Pragma and send XL format
			  
			  $companyid = $row_Recordset3['CompanyId'];
			  
			  if($companyid == 2){
				  
				  $value = $row_Recordset3['JobId'];
				  
			  } else {
				  
				  $value = $row_Recordset3['PDF'];
			  }
			  			  
			  ?>
                <input name="file[]" type="checkbox" id="file[]" value="<?php echo $value; ?>"></td>
            </tr>
            <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
          </table>
        </form></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
