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
	
	header('Location: achievements.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '". $_GET['ProfileId'] ."'")or die(mysqli_error($con));
$row_techs = mysqli_fetch_array($query_techs);

$form_data = array(
	
	'CreatedBy' => $_COOKIE['userid'],
	'TechId' => $row_techs['Id'],
	'Date' => date('Y-m-d'),
	'Achievement' => $_POST['achievement'],
	'Details' => $_POST['details'],
	'FinalComment' => $_POST['comment'],
	
	);

if(isset($_POST['insert'])){
	
	dbInsert('tbl_profile_achievements', $form_data, $con);
	
	$body = '
	<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a"><div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px"><br>
	<table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
	<tr>
	  <td colspan="2" style="font-size:18px;"><strong>NOTABLE ACHIEVEMENT</strong></td>
	</tr>
	<tr>
	  <td colspan="2" style="font-size:18px;">&nbsp;</td>
	</tr>
	<tr>
	  <td><strong>Operator</strong></td>
	  <td>'. $_COOKIE['name'] .'</td>
	</tr>
	<tr>
	  <td width="7%"><strong>Tecnician</strong></td>
	  <td width="93%">'. $row_techs['Name'] .'</td>
	</tr>
	<tr>
	  <td width="7%"><strong>Date</strong></td>
	  <td width="93%">'. date('Y-m-d') .'</td>
	</tr>
	<tr>
	  <td width="7%"><strong>Task</strong></td>
	  <td width="93%">'. $_POST['achievement'] .'</td>
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
	$mail->Subject = 'Seavest Africa Notable Achievement'. $to_go;
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
	
	header('Location: achievements.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_achievements', $form_data, $where_clause="Id = '". $edit ."'",$con);
	
	header('Location: achievements.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_achievements', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: achievements.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_achievements WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_profile_achievements.Id,
		tbl_profile_achievements.TechId,
		tbl_profile_achievements.Date,
		tbl_profile_achievements.Achievement,
		tbl_profile_achievements.Details,
		tbl_profile_achievements.FinalComment,
		tbl_technicians.`Name`,
		tbl_users.`Name` AS Creator,
		tbl_profile_achievements.CreatedBy
	FROM
		tbl_profile_achievements
	INNER JOIN tbl_technicians ON tbl_profile_achievements.TechId = tbl_technicians.Id
	INNER JOIN tbl_users ON tbl_profile_achievements.CreatedBy = tbl_users.Id
	WHERE
		tbl_profile_achievements.TechId = '". $_GET['ProfileId'] ."'
	ORDER BY
		tbl_profile_achievements.Date DESC";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

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
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
      
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
          <li><a href="#">Notable Achievements</a></li>
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
        <form id="form2" name="form2" method="post" action="">
          <div id="list-border">
          
          <?php 
		  
		  if(isset($_GET['Edit'])){
			  
			  $action = 'Update';
			  
		  } else {
			  
			  $action = 'Create';
		  }
		  
		  ?>
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="4" class="td-header"><a href="#" class="toggler sm-bar" data-prod-cat="1"><span class="icon-big">+</span> <?php echo $action; ?> Notable Achievement</a></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td width="20%" class="td-left">Task being performed </td>
                <td width="80%" colspan="3" class="td-right"><input name="achievement" type="text" class="tarea-100" id="achievement" value="<?php echo $row_form['Achievement']; ?>" /></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Details of Achievement</td>
                <td colspan="3" class="td-right" style="padding:0px"><textarea name="details" rows="5" class="tarea-100 mceEditor" id="details"><?php echo $row_form['Details']; ?></textarea></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Final Comment</td>
                <td colspan="3" class="td-right" style="padding:0px"><textarea name="comment" rows="5" class="tarea-100 mceEditor" id="comment"><?php echo $row_form['FinalComment']; ?></textarea></td>
              </tr>
            </table>
          </div>

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
      </form>

     <div id="list-border" style="margin-top:20px;">
       <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr>
              <td colspan="6" class="td-sub-header2">Notable Achievements</td>
            </tr>
            <tr class="row1000">
              <td width="159" class="td-sub-header">CreatedBy</td>
              <td width="159" class="td-sub-header">Full Name</td>
              <td width="100" class="td-sub-header">Date</td>
              <td class="td-sub-header">Task Being Performed </td>
              <td width="20" align="center" class="td-sub-header">&nbsp;</td>
              <td width="20" align="center" class="td-sub-header">&nbsp;</td>
            </tr>
            
            <?php 
			
			$i = 1;
			
			while($row_list = mysqli_fetch_array($query_list)){
				
				$i++;
			
			?>
           
                <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?> row1000" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                  <td><?php echo $row_list['Creator']; ?></td>
                  <td><?php echo $row_list['Name']; ?></td>
                  <td><?php echo $row_list['Date']; ?></td>
                  <td><?php echo $row_list['Achievement']; ?></td>
                  <td align="center"><a href="achievements.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_list['Id']; ?>"  class="edit"></a></td>
                  <td align="center"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td colspan="6" align="center" class="td-header">
                  </td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Details of Achievement</td>
                  <td colspan="5" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('','<br>'), $row_list['Details']); ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Final Comment</td>
                  <td colspan="5" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('','<br>'), $row_list['FinalComment']); ?></td>
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