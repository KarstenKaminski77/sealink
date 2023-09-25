<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysqli_error($con));
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

if(isset($_POST['submit']) && !empty($_POST['error'])){
	
	$userid = $_SESSION['kt_login_id'];
	$date = date('Y-m-d H:i:s');
	$jobno = $_POST['jobno'];
	
	if(empty($_POST['status']) && $_SESSION['kt_login_level'] == 1){
		
		$status = 'Maintenance';
		
	} else {
		
		$status = $_POST['status'];
	}
	
	$error = $_POST['error'];
	
	mysqli_query($con, "INSERT INTO tbl_support (UserId,Date,JobNo,JobStatus,Error,Status) VALUES ('$userid','$date','$jobno','$status','$error','Pending')")or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$userid'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$query2 = mysqli_query($con, "SELECT * FROM tbl_support ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);
	
	$to  = 'karsten@kwd.co.za'; 
	$subject = 'Sealink Support';
		
	$message = '
	<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
	<div style="padding-left:55px; padding-top:50px">
	<span class="header"><img src="http://www.seavest.co.za/inv/images/logo.jpg" /></span>
	</div>
	<div style="padding-left:55px; padding-top:50px">
	<table border="0" cellpadding="2" width="700" cellspacing="3" style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
	  <tr>
		<td width="130"><strong>User </strong></td>
		<td>'. $row['Name'] .'</td>
	  </tr>
	  <tr>
		<td width="130"><strong>Date </strong></td>
		<td>'. $date .'</td>
	  </tr>
	  <tr>
		<td width="130"><strong>Job / Invoice No</strong></td>
		<td>'. $jobno .'</td>
	  </tr>
	  <tr>
		<td width="130"><strong>Status</strong></td>
		<td>'. $status .'</td>
	  </tr>
	  <tr>
		<td width="130" valign="top"><strong>Error / Request</strong></td>
		<td>'. $error .'</td>
	  </tr>
	  <tr>
		<td width="130" valign="top">&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td width="130" valign="top">&nbsp;</td>
		<td><a href="http://www.seavest.co.za/inv/support/support-status.php?Id='. $row2['Id'] .'&User='. $userid .'" class="support">Acknowledge</a></td>
	  </tr>
	</table>
	</div>
	</body>
	';
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'FROM: '. $row['Email'] . "\r\n";
	
	mail($to, $subject, $message, $headers);

}

$query_Recordset1 = "SELECT * FROM tbl_status";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_Recordset2 = $_SESSION['kt_login_id'];
}
mysqli_select_db($database_inv, $inv);
$query_Recordset2 = "SELECT * FROM tbl_support WHERE UserId = '$colname_Recordset2' AND Status != 'Resolved' ORDER BY `Date` DESC";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

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
      
	  <script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
      <script>
          tinymce.init({
          mode : "specific_textareas",
          editor_selector : "mceEditor",
          theme: "modern",
          plugins: [
              ["autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
              ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
              ["save table contextmenu directionality emoticons template paste importcss  responsivefilemanager"]
          ],
          add_unload_trigger: true,
          schema: "html5",
          inline: false,
          toolbar: "undo redo | style-h1 style-h2 style-h3 style-h4 style-h5 style-h6 | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image responsivefilemanager",
          statusbar: false,
          relative_urls:false,
          external_filemanager_path:"/inv/tinymce/filemanager/",
          filemanager_title:"Responsive Filemanager" ,
          external_plugins: { "filemanager" : "/inv/tinymce/filemanager/plugin.min.js"},
      });
      
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
            <li><a href="#">Settings</a></li>
            <li><a href="#">Technical Support</a></li>
            <li><a href="#">Create New</a></li>
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
        <form action="" method="post" name="form2" id="form2">
          <div id="list-brdr-supprt">
            <table width="100%" border="0" cellpadding="4" cellspacing="1" class="comb-sms">
              <tr>
                <td valign="top" class="td-left">Job / Invoice No</td>
                <td class="td-right"><input name="jobno" type="text" class="tarea-100" id="jobno" /></td>
              </tr>
              <tr>
                <td valign="top" class="td-left">Location</td>
                <td class="td-right"><select name="status" class="tarea-100" id="status">
                  <option value="">Select one...</option>
                  <?php do {  ?>
                  <option value="<?php echo $row_Recordset1['Status']?>"><?php echo $row_Recordset1['Status']?></option>
                  <?php
				  } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
				  
				  $rows = mysqli_num_rows($Recordset1);
				  
				  if($rows > 0) {
					  
					  mysqli_data_seek($Recordset1, 0);
					  $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
				  }
				  ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td width="16%" valign="top" class="td-left">Error / Request</td>
                <td width="84%" class="td-right"><textarea name="error" rows="10" class="tarea-100 mceEditor" id="error"></textarea></td>
              </tr>
            </table>
          </div>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="right"><input name="submit" type="submit" class="btn-new" id="submit" value="Submit Ticket"  /></td>
            </tr>
          </table>
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
  mysqli_free_result($query2);
  mysqli_free_result($areas);
  mysqli_free_result($query_Recordset1);
  mysqli_free_result($query_Recordset2);
  mysqli_free_result($query_user_menu);
?>