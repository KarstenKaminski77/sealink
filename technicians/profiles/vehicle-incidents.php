<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

date_default_timezone_set('Etc/UTC');
require '../../PHPMailer/PHPMailerAutoload.php';

$edit = $_GET['Edit'];

if(!isset($_COOKIE['userid'])){
	
	header('Location: ../../index.php');
	
	exit();
}

if(isset($_POST['reset'])){
	
	header('Location: vehicle-incidents.php?ProfileId='. $_GET['ProfileId']);
	
	exit();
}

$form_data = array(
	
	'Operator' => $_COOKIE['name'],
	'TechId' => $_GET['ProfileId'],
	'Date' => $_POST['date'],
	'Time' => $_POST['time'],
	'Driver' => $_POST['driver'],
	'Incident' => $_POST['incident'],
	'Description' => $_POST['description'],
	'ActionTaken' => $_POST['action'],
	'FinalComments' => $_POST['comments'],
	'Registration' => $_POST['registration'],
	'Model' => $_POST['model'],
	
	);


if(isset($_POST['insert'])){
	
	$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '". $_GET['ProfileId'] ."'")or die(mysqli_error($con));
	$row_techs = mysqli_fetch_array($query_techs);
	
	$body = '
	<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a"><div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px"><br>
	<table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
	<tr>
	  <td colspan="2" style="font-size:18px;"><strong>VEHICLE  INCIDENT REPORT</strong></td>
	</tr>
	<tr>
	  <td colspan="2" style="font-size:18px;">&nbsp;</td>
	</tr>
	<tr>
	  <td><strong>Operator</strong></td>
	  <td>'. $_COOKIE['name'] .'</td>
	</tr>
	<tr>
	  <td width="22%"><strong>Tecnician</strong></td>
	  <td width="78%">'. $row_techs['Name'] .'</td>
	</tr>
	<tr>
	  <td width="22%"><strong>Date</strong></td>
	  <td width="78%">'. date('Y-m-d') .'</td>
	</tr>
	<tr>
	  <td width="22%"><strong>Incident</strong></td>
	  <td width="78%">'. $_POST['incident'] .'</td>
	</tr>
	</table>
	<br />
	<br />
	<img src="http://www.seavest.co.za/inv/images/icons/signature-new.jpg" width="600" height="68" /> </div>
	</body>';
		
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
	//Set the subject line
	$mail->Subject = 'Seavest Africa Vehicle Incident Report';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($html = $body);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if (!$mail->send()) {
		
		
	} else {
		
	}
	
	dbInsert('tbl_profile_vir', $form_data, $con);
	
	header('Location: vehicle-incidents.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_vir', $form_data, $where_clause="Id = '". $edit ."'",$con);
	
	header('Location: vehicle-incidents.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_ncr', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: vehicle-incidents.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_ncr WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_profile_vir.*,
		tbl_technicians.`Name`
	FROM
		tbl_profile_vir
	INNER JOIN tbl_technicians ON tbl_profile_vir.TechId = tbl_technicians.Id
	WHERE tbl_profile_vir.TechId = '". $_GET['ProfileId'] ."'";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

$query_form = "
	SELECT
		tbl_profile_vir.*,
		tbl_technicians.`Name`
	FROM
		tbl_profile_vir
	INNER JOIN tbl_technicians ON tbl_profile_vir.TechId = tbl_technicians.Id
	WHERE
		tbl_profile_vir.Id = '". $_GET['Edit'] ."'";
		
$query_form = mysqli_query($con, $query_form)or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));
$query_techs_dd = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../../menu/script.js"></script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
	  <script type="text/javascript">
  
          $(document).ready(function () {
              $(".toggler").click(function (e) {
                  e.preventDefault();
                  $('.row' + $(this).attr('data-prod-cat')).toggle();
              });
          });
  
      </script>
      
	  <script type="text/javascript" src="../../tinymce/js/tinymce/tinymce.min.js"></script>
      <script>
          tinymce.init({
		  menubar:false,
		  mode : "specific_textareas",
		  editor_selector : "mceEditor",
          theme: "modern",
          browser_spellcheck : true,
          plugins: [
              ["autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
              ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
              ["save table contextmenu directionality emoticons template paste"]
          ],
          add_unload_trigger: true,
          schema: "html5",
          inline: false,
          toolbar: "undo redo bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
          statusbar: false
      });
      
      </script>
      
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
      
	  <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../../i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
      <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>
      
      <script type="text/javascript">
          
          $(function(){
      
              $('.date-container > script').each(function(i){
                  eval($(this).text());
              });
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
      <?php include('../../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
        <ul class="breadcrumb">
          <li><a href="#">Personnel</a></li>
          <li><a href="#">Profiles</a></li>
          <li><a href="index.php?ProfileId=<?php echo $_GET['ProfileId']; ?>"><?php profile_name($con, $_GET['ProfileId']); ?></a></li>
          <li><a href="#">Vehicle Incident Reports</a></li>
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
      <?php
	  
	  if(isset($_GET['Edit'])){
		  
		  $display = 'table-row';
		  $display2 = 'table';
		  
	  } else {
		  
		  $display = 'none'; 
		  $display2 = 'none'; 
	  }
	  
	  ?>
      
   <div id="main-wrapper">
   
     <form id="form3" name="form3" method="post" action="">
          <div id="list-border">
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="4" class="td-header"><a href="#" class="toggler sm-bar" data-prod-cat="1"><span class="icon-big">+</span> Create Vehicle Incident Report</a></td>
              </tr>
              <tr class="row1" style="display:<?php echo $display; ?>">
                <td width="20%" class="td-left">Date </td>
                <td width="30%" class="td-right">
                
                <input name="date" type="text" class="tarea-100" id="date" onfocus="if(this.value=='Date'){this.value=''}" onblur="if(this.value==''){this.value='Date'}" value="<?php echo $row_form['Date']; ?>">
                
                  <script type="text/javascript">
                      $('#date').datepicker({
                          dateFormat: "yy-mm-dd"
                      });
                   </script>
                
                </td>
                <td width="20%" class="td-left">Time</td>
                <td width="30%" class="td-right">
                
                <input name="time" type="text" class="tarea-100" id="time" onfocus="if(this.value=='Time'){this.value=''}" onblur="if(this.value==''){this.value='Time'}" value="<?php echo $row_form['Time']; ?>">
                
                  <script type="text/javascript">
                      $('#time').timepicker();
                   </script>
                
                </td>
              </tr>
              <tr class="row1" style="display:<?php echo $display; ?>">
                <td class="td-left">Vehicle Registration</td>
                <td class="td-right"><span class="field-container">
                  <input name="registration" type="text" class="tarea-100" id="registration" value="<?php echo $row_form['Registration']; ?>" />
                </span></td>
                <td class="td-left">Vehicle Model</td>
                <td class="td-right"><span class="field-container">
                  <input name="model" type="text" class="tarea-100" id="model" value="<?php echo $row_form['Model']; ?>" />
                </span></td>
              </tr>
              <tr class="row1" style="display:<?php echo $display; ?>">
                <td class="td-left">Driver</td>
                <td colspan="3" class="td-right"><span class="field-container">
                  <input name="driver" type="text" class="tarea-100" id="driver" value="<?php echo $row_form['Driver']; ?>" />
                </span></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Nature of Incident</td>
                <td colspan="3" class="td-right"><textarea name="incident" rows="3" class="tarea-100" id="incident"><?php echo $row_form['Incident']; ?></textarea></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Brief Description</td>
                <td colspan="3" class="td-right"><textarea name="description" rows="3" class="tarea-100" id="description"><?php echo $row_form['Description']; ?></textarea></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Action Taken</td>
                <td colspan="3" class="td-right"><textarea name="action" rows="3" class="tarea-100" id="action"><?php echo $row_form['ActionTaken']; ?></textarea></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Final Comments</td>
                <td colspan="3" class="td-right">
                  <textarea name="comments" rows="3" class="tarea-100" id="comments"><?php echo $row_form['FinalComments']; ?></textarea>
                </td>
              </tr>
            </table>
          </div>
          <div style="margin-bottom:20px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="row1" style="display: <?php echo $display2; ?>">
            <tr>
              <td align="right">
              <? if(isset($_GET['Edit'])){ ?>
              <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
              <input name="update" type="submit" class="btn-new" id="update" value="Update" />
              <?php } else { ?>
              
                  <input name="insert" type="submit" class="btn-new" id="insert" value="Insert" />
              
              <?php } ?>
              </td>
            </tr>
          </table>
       </div>
             
     </form>
   
     <div id="list-border">
       <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr>
              <td width="255" class="td-header">Operator</td>
              <td width="255" class="td-header">Driver</td>
              <td width="100" class="td-header">Vehicle Reg</td>
              <td width="120" class="td-header">Vehicle Model</td>
              <td width="90" class="td-header">Date</td>
              <td width="90" class="td-header">Time</td>
              <td width="20" align="center" class="td-header">&nbsp;</td>
              <td width="20" align="center" class="td-header">&nbsp;</td>
            </tr>
            
            <?php 
			
			$i = 0;
			
			while($row_list = mysqli_fetch_array($query_list)){
				
				$i++;
			
			?>
           
            <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
              <td><?php echo $row_list['Operator']; ?></td>
              <td><?php echo $row_list['Driver']; ?></td>
              <td><?php echo $row_list['Registration']; ?></td>
              <td><?php echo $row_list['Model']; ?></td>
              <td><?php echo $row_list['Date']; ?></td>
              <td><?php echo $row_list['Time']; ?></td>
              <td align="center"><a href="vehicle-incidents.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_list['Id']; ?>"  class="edit"></a></td>
              <td align="center"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
            </tr>
            <tr class="row<?php echo $i; ?>" style="display: none">
              <td colspan="8" align="center" class="td-header">
              </td>
            </tr>
            <tr class="row<?php echo $i; ?>" style="display: none">
              <td class="td-left">Nature of Incident</td>
              <td colspan="7" class="td-right"><?php echo $row_list['Incident']; ?></td>
            </tr>
            <tr class="row<?php echo $i; ?>" style="display: none">
              <td class="td-left">Brief Description</td>
              <td colspan="7" class="td-right"><?php echo $row_list['Description']; ?></td>
            </tr>
            <tr class="row<?php echo $i; ?>" style="display: none">
              <td class="td-left">Action Taken</td>
              <td colspan="7" class="td-right"><?php echo $row_list['ActionTaken']; ?></td>
            </tr>
            <tr class="row<?php echo $i; ?>" style="display: none">
              <td class="td-left">Final Comments</td>
              <td colspan="7" class="td-right"><?php echo $row_list['FinalComments']; ?></td>
            </tr>
            
            <?php } ?>
            
          </table>
        </div>
   </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
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