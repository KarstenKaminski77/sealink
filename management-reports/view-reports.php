<?php
session_start();

require_once('../functions/functions.php');

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysql_error());
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

if($_SESSION['kt_login_level'] == 0){

$areaid = $_SESSION['kt_AreaId'];
}


if(!isset($_SESSION['kt_login_id']) || empty($_SESSION['kt_login_id'])){
	
	header('Location: index.php');
}

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
    <td valign="top" valign="top"><strong>Subject</strong></td>
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
$headers .= 'Cc: '.$cc . "\r\n";

if(mail($to, $subject, $message, $headers)){
	
	$mail_message = 'Mail successfully sent to <spna class="green"><b>'. $to .'</b></span>';
}
}

$id = $_SESSION['kt_login_id'];

if(!empty($_POST['date'])){
	
	$where = "AND tbl_management_report_details.`Date` = '". $_POST['date'] ."'";
}

$query_list = mysqli_query($con, "SELECT tbl_users.Email AS Email_1, tbl_users.Name AS Name_1, tbl_management_report_details.ReportId, tbl_management_report_levels.Level, tbl_management_report_details.Date, tbl_management_report_priorities.Priority, tbl_management_report_details.Report, tbl_management_reports.AreaId, tbl_management_report_details.Id, tbl_management_report_details.Subject, tbl_users_0.Email, tbl_users_0.Name, tbl_management_report_frequencies.Frequency
FROM ((((((tbl_management_reports
LEFT JOIN tbl_management_report_details ON tbl_management_report_details.ReportId=tbl_management_reports.Id)
LEFT JOIN tbl_management_report_frequencies ON tbl_management_report_frequencies.Id=tbl_management_reports.Frequency)
LEFT JOIN tbl_management_report_levels ON tbl_management_report_levels.Id=tbl_management_report_details.Level)
LEFT JOIN tbl_management_report_priorities ON tbl_management_report_priorities.Id=tbl_management_report_details.Priority)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_management_reports.ManagerId)
LEFT JOIN tbl_users AS tbl_users_0 ON tbl_users_0.Id=tbl_management_reports.CoordinatorId) WHERE tbl_management_reports.CoordinatorId = '$id' $where")or die(mysqli_error($con));

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/script.js"></script>
      
	  <script type="text/javascript" src="../highslide/highslide-with-html.js"></script>
      <link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />
      
      
      <script type="text/javascript">
          hs.graphicsDir = '../highslide/graphics/';
          hs.outlineType = 'rounded-white';
      </script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
   </head>
   <body>
   
      <!-- Banner -->
      <div id="logo">
         <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
      </div>
      <!-- End Banner -->
      
      <!-- Navigatiopn -->
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Estimates</a></li>
            <li><a href="#">Quotations</a></li>
            <li><a href="#">Credit Note</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="">
          <input name="textfield" type="text" class="search-top" id="textfield" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
        <form action="" method="post" enctype="multipart/form-data" name="form2" id="form2">
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <?php if(!empty($mail_message)){ ?>
              <tr>
                <td colspan="8" nowrap="nowrap">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" align="center" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><div id="banner-success-mail"><?php echo $mail_message; ?></div></td>
                  </tr>
                </table></td>
              </tr>
              <?php } ?>
              <tr>
                <td colspan="8" align="center" nowrap="nowrap">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" align="center" nowrap="nowrap">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5" align="right" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><input name="date" class="select" id="date" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" wdg:readonly="true" /></td>
                    <td><input name="button2" type="submit" class="btn" id="button2" value="Filter" /></td>
                  </tr>
                </table></td>
              </tr>
              </table>
              
              <div id="list-border">
              <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr>
                <td width="100" nowrap="nowrap" class="td-header"><strong>&nbsp; Date</strong></td>
                <td align="left" class="td-header"><strong>&nbsp;Subject&nbsp;</strong></td>
                <td width="100" align="left" class="td-header"><strong>&nbsp;Frequency</strong></td>
                <td width="20" align="center" class="td-header-right">&nbsp;</td>
                <td width="20" align="center" class="td-header-right">&nbsp;</td>
              </tr>
              <?php 
  $i = 0;
  while($row_list = mysqli_fetch_array($query_list)){ 
  $i++;
  ?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                <td width="100" class="combo">&nbsp;<?php echo $row_list['Date']; ?></td>
                <td class="combo">&nbsp;&nbsp;<?php echo $row_list['Subject']; ?></td>
                <td width="100" class="combo">&nbsp;<?php echo $row_list['Frequency']; ?></td>
                <td width="25" align="center">
                <a href="view-reports.php?Id=<?php echo $_GET['Id']; ?>&amp;View=<?php echo $row_list['Id']; ?>" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html<?php echo $i; ?>' } )"
		class="view highslide"></a></td>
                <td width="25" align="center">
                  <a href="view-reports.php?Id=<?php echo $_GET['Id']; ?>&amp;Mail=<?php echo $row_list['Id']; ?>" class="mail"></a>
                  <div class="highslide-html-content" id="highslide-html<?php echo $i; ?>" style="width:800px;">
                    <div class="highslide-header">
                      <ul>
                        <li class="highslide-move"> <a href="#" onclick="return false">Move</a> </li>
                        <li class="highslide-close"> <a href="#" onclick="return hs.close(this)">Close</a> </li>
                      </ul>
                    </div>
                    <div class="highslide-body">
                      <div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px"> <img src="http://www.kwd.co.za/chevron/images/sealinklogo.jpg" width="150" height="38" /> <br />
                        <br />
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
                            <td valign="top"><strong>Subject</strong></td>
                            <td colspan="2"><?php echo $row_list['Subject']; ?></td>
                          </tr>
                          <tr>
                            <td valign="top"><strong>Report</strong></td>
                            <td colspan="2"><?php echo nl2br($row_list['Report']); ?></td>
                          </tr>
                        </table>
                        </p>
                        <br />
                        <img src="http://www.kwd.co.za/chevron/images/signature.jpg" /></div>
                    </div>
                    <div class="highslide-footer">
                      <div> <span class="highslide-resize" title="Resize"> <span></span> </span> </div>
                    </div>
                  </div></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </form>
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
</body>
</html>
<?php 
  mysqli_close($con); 
  mysqli_free_result($query);
  mysqli_free_result($query_areas);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
  mysqli_free_result($query_user_menu);
?>