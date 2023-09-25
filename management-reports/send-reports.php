<?php require_once('../Connections/seavest.php'); ?>
<?php
session_start();

if(!isset($_SESSION['kt_login_id']) || empty($_SESSION['kt_login_id'])){
	
	header('Location: index.php');
}

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$query_levels = mysqli_query($con, "SELECT * FROM tbl_management_report_levels")or die(mysqli_error($con));

$query_priorities = mysqli_query($con, "SELECT * FROM tbl_management_report_priorities")or die(mysqli_error($con));

$mail_message = '';

if(!empty($_POST['insert'])){
	
	$userid = $_SESSION['kt_login_id'];
	
	$query = mysqli_query($con, "SELECT tbl_management_reports.*, tbl_users.Name FROM (tbl_management_reports LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.CoordinatorId) WHERE tbl_management_reports.CoordinatorId = '$userid'")or die(mysql_error($con));
	$row = mysqli_fetch_array($query);
	
	$reportid = $row['Id'];
	$date = date('Y-m-d H:i:s');
	$report = addslashes($_POST['report']);
	$subject = $row['Name'] .' Report - '. $date;
	
	mysqli_query($con, "UPDATE tbl_management_report_details SET Old = '1' WHERE ReportId = '$reportid'")or die(mysqli_error($con));
	
	mysqli_query($con, "INSERT INTO tbl_management_report_details (ReportId,Date,Subject,Report) VALUES ('$reportid','$date','$subject','$report')")or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_management_report_details ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$reportid = $row['Id'];
	
$query_mail = mysqli_query($con, "SELECT tbl_users.Email AS Email_1, tbl_users.Name AS Name_1, tbl_management_report_details.ReportId, tbl_management_report_details.Date, tbl_management_report_details.Report, tbl_management_reports.AreaId, tbl_management_report_details.Id, tbl_management_report_details.Subject, tbl_users_0.Email, tbl_users_0.Name
FROM (((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId)
WHERE tbl_management_report_details.Id = '$reportid'")or die(mysqli_error($con));
$row_mail = mysqli_fetch_array($query_mail);
	
	$to  = $row_mail['Email_1'];
	$from = $row_mail['Email'];
	$subject = $row_mail['Subject'];
	$cc = 'test@kwd.co.za';
	
	$message = '
<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a">
<div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px">
<img src="http://www.kwd.co.za/chevron/images/sealinklogo.jpg" width="150" height="38" />
<br>
<br>
  <table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
  <tr>
    <td><strong>From</strong></td>
    <td>'. $row_mail['Name'] .'</td>
    <td width="43%" rowspan="5" align="right" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>To</strong></td>
    <td>'. $row_mail['Name_1'] .'</td>
    </tr>
  <tr>
    <td width="15%"><strong>Date</strong></td>
    <td width="42%">'. $row_mail['Date'] .'</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><strong>Subject</strong></td>
    <td colspan="2">'. $row_mail['Subject'] .'</td>
  </tr>
  <tr>
    <td><strong>Report</strong></td>
    <td colspan="2">'. stripslashes($row_mail['Report']) .'</td>
  </tr>
</table>
</p><br><img src="http://www.kwd.co.za/chevron/images/signature.jpg"></div></body>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'FROM: '.$from . "\r\n";
$headers .= 'Cc: '.$cc . "\r\n";

if(mail($to, $subject, $message, $headers)){
	
	$mail_message = 'Mail successfully sent to <spna class="green"><b>'. $to .'</b></span>';
}
}

$userid = $_SESSION['kt_login_id'];

$query_previous = mysqli_query($con, "SELECT tbl_management_reports.CoordinatorId, tbl_management_report_details.Date, tbl_management_report_details.Report, tbl_management_report_details.Subject
FROM (tbl_management_reports LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id) WHERE tbl_management_reports.CoordinatorId = '$userid' ORDER BY tbl_management_report_details.Id DESC LIMIT 1")or die(mysqli_error($con));
$row_previous = mysqli_fetch_array($query_previous);
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

<script type="text/javascript" src="../highslide/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />


<!--
    2) Optionally override the settings defined at the top
    of the highslide.js file. The parameter hs.graphicsDir is important!
-->

<script type="text/javascript">
    hs.graphicsDir = '../highslide/graphics/';
    hs.outlineType = 'rounded-white';
</script>

<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
    selector: "textarea",
    theme: "modern",
	browser_spellcheck : true,
    plugins: [
        ["autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
        ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
        ["save table contextmenu directionality emoticons template paste"]
    ],
    add_unload_trigger: true,
    schema: "html5",
    inline: false,
    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image     | print preview media",
    statusbar: false
});

</script> 
</head>

</head>

<body>
<table width="1023" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">
        <form action="" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
          <table width="100%" border="0" cellpadding="3" cellspacing="1">
          <?php if(!empty($mail_message)){ ?>
            <tr>
  <td width="450" nowrap>&nbsp;</td>
              </tr>
            <tr>
              <td align="center" nowrap><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><div id="banner-success-mail"><?php echo $mail_message; ?></div></td>
                </tr>
              </table></td>
            </tr>
          <?php } ?>
            <tr>
              <td align="center" nowrap>&nbsp;</td>
            </tr>
            <tr>
              <td nowrap class="td-header">Previous Report</td>
            </tr>
            <tr>
              <td class="tarea-bdr">
			  <p><strong><?php echo $row_previous['Subject']; ?></strong></p>
			  <?php echo stripslashes(nl2br($row_previous['Report'])); ?></td>
            </tr>
            <tr>
              <td align="center" nowrap>&nbsp;</td>
            </tr>
            <tr>
              <td nowrap class="td-header">Report</td>
            </tr>
            <tr>
              <td align="center" nowrap><textarea name="report" cols="45" class="tarea-100per" id="report"><?php echo stripslashes($row_form['Report']); ?></textarea></td>
            </tr>
            <tr>
              <td align="right" nowrap><input name="insert" type="submit" class="btn-blue-generic" id="insert" value="Submit Report"></td>
            </tr>
            </table>
        </form>
</td></tr>
    </table></td>
  </tr>
</table>
</body>
</html>