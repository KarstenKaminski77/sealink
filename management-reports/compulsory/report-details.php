<?php require_once('../../Connections/seavest.php'); ?>
<?php
session_start();

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$query_levels = mysqli_query($con, "SELECT * FROM tbl_management_report_levels")or die(mysqli_error($con));

$query_priorities = mysqli_query($con, "SELECT * FROM tbl_management_report_priorities")or die(mysqli_error($con));

$mail_message = '';

if(isset($_POST)){
	
	$id = $_GET['Id'];
	$level = $_POST['level'];
	$date = date('Y-m-d');
	$priority = $_POST['priority'];
	$subject = $_POST['subject'];
	$report = $_POST['report'];
}

if(!empty($_POST['insert'])){
	
	$reportid = $_GET['Id'];
	
	mysqli_query($con, "UPDATE tbl_management_report_details SET Old = '1' WHERE ReportId = '$reportid'")or die(mysqli_error($con));
	
	mysqli_query($con, "INSERT INTO tbl_management_report_details (ReportId,Level,Date,Priority,Subject,Report) VALUES ('$id','$level','$date','$priority','$subject','$report')")or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_management_report_details ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$reportid = $row['Id'];
	
$query_mail = mysqli_query($con, "SELECT tbl_users.Email AS Email_1, tbl_users.Name AS Name_1, tbl_management_report_details.ReportId, tbl_management_report_levels.Level, tbl_management_report_details.Date, tbl_management_report_priorities.Priority, tbl_management_report_details.Report, tbl_management_reports.AreaId, tbl_management_report_details.Id, tbl_management_report_details.Subject, tbl_users_0.Email, tbl_users_0.Name
FROM (((((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_management_report_levels ON tbl_management_report_levels.Id=tbl_management_report_details.Level)
LEFT JOIN tbl_management_report_priorities ON tbl_management_report_priorities.Id=tbl_management_report_details.Priority)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId)
WHERE tbl_management_report_details.Id = '$reportid'")or die(mysqli_error($con));
$row_mail = mysqli_fetch_array($query_mail);
	
	$to  = $row_mail['Email_1'];
	$from = $row_mail['Email'];
	$subject = $row_mail['Subject'];
	
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
    <td><strong>Priority</strong></td>
    <td>'. $row_mail['Priority'] .'</td>
    </tr>
  <tr>
    <td><strong>Level</strong></td>
    <td>'. $row_mail['Level'] .'</td>
    <td align="right" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><strong>Subject</strong></td>
    <td colspan="2">'. $row_mail['Subject'] .'</td>
  </tr>
  <tr>
    <td><strong>Report</strong></td>
    <td colspan="2">'. $row_mail['Report'] .'</td>
  </tr>
</table>
</p><br><img src="http://www.kwd.co.za/chevron/images/signature.jpg"></div></body>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'FROM: '.$from . "\r\n";

if(mail($to, $subject, $message, $headers)){
	
	$mail_message = 'Mail successfully sent to <spna class="green"><b>'. $to .'</b></span>';
}
header('Location: ../../welcome.php');
}

if(!empty($_POST['update'])){
	
	$id = $_GET['Edit'];
	
	mysqli_query($con, "UPDATE tbl_management_report_details SET Level = '$level', Date = '$date', Priority = '$priority', Subject = '$subject', Report = '$report' WHERE Id = '$id'")or die(mysqli_error($con));
	
	$reportid = $_GET['Edit'];
	
$query_mail = mysqli_query($con, "SELECT tbl_users.Email AS Email_1, tbl_users.Name AS Name_1, tbl_management_report_details.ReportId, tbl_management_report_levels.Level, tbl_management_report_details.Date, tbl_management_report_priorities.Priority, tbl_management_report_details.Report, tbl_management_reports.AreaId, tbl_management_report_details.Id, tbl_management_report_details.Subject, tbl_users_0.Email, tbl_users_0.Name
FROM (((((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_management_report_levels ON tbl_management_report_levels.Id=tbl_management_report_details.Level)
LEFT JOIN tbl_management_report_priorities ON tbl_management_report_priorities.Id=tbl_management_report_details.Priority)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId)
WHERE tbl_management_report_details.Id = '$reportid'")or die(mysqli_error($con));
$row_mail = mysqli_fetch_array($query_mail);
	
	$to  = $row_mail['Email_1'];
	$from = $row_mail['Email'];
	$subject = $row_mail['Subject'];
	
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
    <td><strong>Priority</strong></td>
    <td>'. $row_mail['Priority'] .'</td>
    </tr>
  <tr>
    <td><strong>Level</strong></td>
    <td>'. $row_mail['Level'] .'</td>
    <td align="right" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><strong>Subject</strong></td>
    <td colspan="2">'. $row_mail['Subject'] .'</td>
  </tr>
  <tr>
    <td><strong>Report</strong></td>
    <td colspan="2">'. $row_mail['Report'] .'</td>
  </tr>
</table>
</p><br><img src="http://www.kwd.co.za/chevron/images/signature.jpg"></div></body>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'FROM: '.$from . "\r\n";

if(mail($to, $subject, $message, $headers)){
	
	$mail_message = 'Mail successfully sent to <spna class="green"><b>'. $to .'</b></span>';
}

}

if(isset($_GET['Mail'])){
	
		$reportid = $_GET['Mail'];
	
$query_mail = mysqli_query($con, "SELECT tbl_users.Email AS Email_1, tbl_users.Name AS Name_1, tbl_management_report_details.ReportId, tbl_management_report_levels.Level, tbl_management_report_details.Date, tbl_management_report_priorities.Priority, tbl_management_report_details.Report, tbl_management_reports.AreaId, tbl_management_report_details.Id, tbl_management_report_details.Subject, tbl_users_0.Email, tbl_users_0.Name
FROM (((((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_management_report_levels ON tbl_management_report_levels.Id=tbl_management_report_details.Level)
LEFT JOIN tbl_management_report_priorities ON tbl_management_report_priorities.Id=tbl_management_report_details.Priority)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId)
WHERE tbl_management_report_details.Id = '$reportid'")or die(mysqli_error($con));
$row_mail = mysqli_fetch_array($query_mail);
	
	$to  = $row_mail['Email_1'];
	$from = $row_mail['Email'];
	$subject = $row_mail['Subject'];
	
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
    <td><strong>Priority</strong></td>
    <td>'. $row_mail['Priority'] .'</td>
    </tr>
  <tr>
    <td><strong>Level</strong></td>
    <td>'. $row_mail['Level'] .'</td>
    <td align="right" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><strong>Subject</strong></td>
    <td colspan="2">'. $row_mail['Subject'] .'</td>
  </tr>
  <tr>
    <td><strong>Report</strong></td>
    <td colspan="2">'. $row_mail['Report'] .'</td>
  </tr>
</table>
</p><br><img src="http://www.kwd.co.za/chevron/images/signature.jpg"></div></body>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'FROM: '.$from . "\r\n";

if(mail($to, $subject, $message, $headers)){
	
	$mail_message = 'Mail successfully sent to <spna class="green"><b>'. $to .'</b></span>';
}
}

$id = $_GET['Id'];

$query_list = mysqli_query($con, "SELECT tbl_users.Email AS Email_1, tbl_users.Name AS Name_1, tbl_management_report_details.ReportId, tbl_management_report_levels.Level, tbl_management_report_details.Date, tbl_management_report_priorities.Priority, tbl_management_report_details.Report, tbl_management_reports.AreaId, tbl_management_report_details.Id, tbl_management_report_details.Subject, tbl_users_0.Email, tbl_users_0.Name, tbl_management_report_frequencies.Frequency
FROM ((((((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_management_report_frequencies ON tbl_management_report_frequencies.Id=tbl_management_reports.Frequency)
LEFT JOIN tbl_management_report_levels ON tbl_management_report_levels.Id=tbl_management_report_details.Level)
LEFT JOIN tbl_management_report_priorities ON tbl_management_report_priorities.Id=tbl_management_report_details.Priority)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId) WHERE tbl_management_report_details.ReportId = '$id'")or die(mysqli_error($con));

$reportid = $_GET['Edit'];

$query_form = mysqli_query($con, "SELECT tbl_management_report_levels.Id AS Id_2, tbl_management_report_priorities.Id AS Id_1, tbl_management_reports.ManagerId, tbl_management_reports.CoordinatorId, tbl_management_report_details.Id, tbl_management_report_details.ReportId, tbl_management_report_details.Date, tbl_management_report_details.Report, tbl_management_report_details.Subject, tbl_management_report_levels.Level, tbl_management_report_priorities.Priority, tbl_management_report_frequencies.Frequency
FROM ((((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_management_report_frequencies ON tbl_management_report_frequencies.Id=tbl_management_reports.Frequency)
LEFT JOIN tbl_management_report_levels ON tbl_management_report_levels.Id=tbl_management_report_details.Level)
LEFT JOIN tbl_management_report_priorities ON tbl_management_report_priorities.Id=tbl_management_report_details.Priority) WHERE tbl_management_report_details.Id = '$reportid'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$reportid = $_GET['Id'];

$query_check = mysqli_query($con, "SELECT * FROM tbl_management_reports WHERE Id = '$reportid'")or die(mysqli_error($con));
$row_check = mysqli_fetch_array($query_check);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
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
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../../styles/layout.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../../highslide/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="../../highslide/highslide.css" />


<!--
    2) Optionally override the settings defined at the top
    of the highslide.js file. The parameter hs.graphicsDir is important!
-->

<script type="text/javascript">
    hs.graphicsDir = '../../highslide/graphics/';
    hs.outlineType = 'rounded-white';
</script>

</head>

</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">&nbsp;</td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
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
          <?php if($row_check['CoordinatorId'] == $_COOKIE['userid']){ ?>
          <?php if(!empty($mail_message)){ ?>
            <tr>
  <td colspan="8" nowrap>&nbsp;</td>
              </tr>
            <tr>
              <td colspan="8" align="center" nowrap><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><div id="banner-success-mail"><?php echo $mail_message; ?></div></td>
                </tr>
              </table></td>
            </tr>
          <?php } ?>
            <tr>
              <td colspan="8" align="center" nowrap>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="8" align="center" nowrap><select name="level" class="tarea-100per" id="level" style="padding-right:0px">
                <option>Select a level...</option>
                <?php while($row_levels = mysqli_fetch_array($query_levels)){ ?>
                <option value="<?php echo $row_levels['Id']; ?>" <?php if($row_levels['Id'] == $row_form['Id_2']){ ?>selected="selected"<?php } ?> ><?php echo $row_levels['Level']; ?></option>
                <?php } ?>
              </select></td>
            </tr>
            <tr>
              <td colspan="8" align="center" nowrap><select name="priority" class="tarea-100per" id="priority" style="padding-right:0px">
                <option>Select a priority...</option>
                <?php while($row_priorities = mysqli_fetch_array($query_priorities)){ ?>
                <option value="<?php echo $row_priorities['Id']; ?>" <?php if($row_priorities['Id'] == $row_form['Id_1']){ ?>selected="selected"<?php } ?> ><?php echo $row_priorities['Priority']; ?></option>
                <?php } ?>
              </select></td>
            </tr>
            <tr>
              <td colspan="8" align="center" nowrap><input name="subject" type="text" class="tarea-100per" id="subject" value="<?php echo $row_form['Subject']; ?>"></td>
            </tr>
            <tr>
              <td colspan="8" align="center" nowrap><textarea name="report" cols="45" rows="5" class="tarea-100per" id="report"><?php echo $row_form['Report']; ?></textarea></td>
            </tr>
            <tr>
              <td colspan="8" align="right" nowrap><?php if(isset($_GET['Edit'])){ ?>
                <input name="update" type="submit" class="btn-blue-generic" id="update" value="Update">
                <?php } else { ?>
                <input name="insert" type="submit" class="btn-blue-generic" id="insert" value="Insert">
                <?php } ?></td>
            </tr>
          <?php } ?>
            <tr>
              <td colspan="8" align="center" nowrap>&nbsp;</td>
            </tr>
            <tr class="td-header">
              <td nowrap><strong>Subject</strong></td>
              <td width="100" align="left">Level</td>
              <td width="100" align="left"><strong>Frequency</strong></td>
              <td width="100" align="left">&nbsp;Date</td>
              <td width="75" align="left">Priority</td>
              <td width="25" align="center">&nbsp;</td>
              <?php if($row_check['CoordinatorId'] == $_SESSION['kt_login_id']){ ?>
              <td width="25" align="center">&nbsp;</td>
              <?php } ?>
              <td width="25" align="center">&nbsp;</td>
              </tr>
  <?php 
  $i = 0;
  while($row_list = mysqli_fetch_array($query_list)){ 
  $i++;
  ?>
  <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td class="combo"> &nbsp;<?php echo $row_list['Subject']; ?></td>
              <td width="100" class="combo"><?php echo $row_list['Level']; ?></td>
              <td width="100" class="combo"> &nbsp;<?php echo $row_list['Frequency']; ?></td>
              <td width="100" class="combo"> &nbsp;<?php echo $row_list['Date']; ?></td>
              <td width="75" class="combo"> &nbsp;<?php echo $row_list['Priority']; ?></td>
              <td width="25" align="center">
              <a href="report-details.php?Id=<?php echo $_GET['Id']; ?>&View=<?php echo $row_list['Id']; ?>" onClick="return hs.htmlExpand(this, { contentId: 'highslide-html<?php echo $i; ?>' } )"
		class="highslide">
              <img src="../../images/icons/btn-view.png" width="25" height="25" border="0">
              </a></td>
              <?php if($row_check['CoordinatorId'] == $_SESSION['kt_login_id']){ ?>
              <td width="25" align="center">
              <a href="report-details.php?Id=<?php echo $_GET['Id']; ?>&Edit=<?php echo $row_list['Id']; ?>" class="menu">
              <img src="../../images/icons/btn-edit.png" width="25" height="25" border="0">
              </a></td>
              <?php } ?>
              <td width="25" align="center">
              <a href="report-details.php?Id=<?php echo $_GET['Id']; ?>&Mail=<?php echo $row_list['Id']; ?>" class="menu">
              <img src="../../images/icons/btn-mail.png" width="25" height="25" border="0">
              </a>
<div class="highslide-html-content" id="highslide-html<?php echo $i; ?>" style="width:800px;">
	<div class="highslide-header">
		<ul>
			<li class="highslide-move">
				<a href="#" onClick="return false">Move</a>
			</li>
			<li class="highslide-close">
				<a href="#" onClick="return hs.close(this)">Close</a>
			</li>
		</ul>
	</div>
	<div class="highslide-body">
<div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px">
<img src="http://www.kwd.co.za/chevron/images/sealinklogo.jpg" width="150" height="38" />
<br>
<br>
  <table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
  <tr>
    <td><strong>From</strong></td>
    <td><?php echo $row_list['Name']; ?></td>
    <td width="43%" rowspan="5" align="right" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>To</strong></td>
    <td><?php echo $row_list['Name_1']; ?></td>
    </tr>
  <tr>
    <td width="15%"><strong>Date</strong></td>
    <td width="42%"><?php echo $row_list['Date']; ?></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Priority</strong></td>
    <td><?php echo $row_list['Priority']; ?></td>
    </tr>
  <tr>
    <td><strong>Level</strong></td>
    <td><?php echo $row_list['Level']; ?></td>
    <td align="right" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><strong>Subject</strong></td>
    <td colspan="2"><?php echo $row_list['Subject']; ?></td>
  </tr>
  <tr>
    <td><strong>Report</strong></td>
    <td colspan="2"><?php echo $row_list['Report']; ?></td>
  </tr>
</table>
</p><br><img src="http://www.kwd.co.za/chevron/images/signature.jpg"></div>	</div>
    <div class="highslide-footer">
        <div>
            <span class="highslide-resize" title="Resize">
                <span></span>
            </span>
        </div>
    </div>
</div>
              </td>
              </tr>
            <?php } ?>
            </table>
        </form>
        <br><br>
          <div class="KT_bottomnav" align="center">
            <div class="combo"></div>
          </div></td></tr>
    </table></td>
  </tr>
</table>
</body>
</html>