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
	
	header('Location: non-conformance.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$form_data = array(
	
	'Operator' => $_COOKIE['name'],
	'TechId' => $_GET['ProfileId'],
	'Date' => date('Y-m-d'),
	'NonConformance' => $_POST['non-conformance'],
	'Details' => $_POST['details'],
	'FinalComment' => $_POST['comment'],
	
	);

if(isset($_POST['insert'])){
	
	$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '". $_GET['ProfileId'] ."'")or die(mysqli_error($con));
	$row_techs = mysqli_fetch_array($query_techs);
	
	$body = '
	<body style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a"><div style="font-family:Arial; font-size:12px; line-height:18px; color:#43525a; padding-left:25px"><br>
	<table width="600" border="0" cellspacing="10" cellpadding="0" style="color:#43525a; font-family:arial; font-size:12px">
	<tr>
	  <td colspan="2" style="font-size:18px;"><strong>NON CONFORMANCE REPORT</strong></td>
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
	  <td width="22%"><strong>Non Conformance</strong></td>
	  <td width="78%">'. $_POST['non-conformance'] .'</td>
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
	$mail->Subject = 'Seavest Africa Non Conformance Report';
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
	
	dbInsert('tbl_profile_ncr', $form_data, $con);
	
	header('Location: non-conformance.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_ncr', $form_data, $where_clause="Id = '". $edit ."'",$con);
	
	header('Location: non-conformance.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_ncr', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: non-conformance.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_ncr WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_profile_medicals.Id,
		tbl_profile_medicals.TechId,
		tbl_profile_medicals.DateConducted,
		tbl_profile_medicals.NextExaminationDate,
		tbl_profile_medicals.Practitioner,
		tbl_profile_medicals.MedicalStatus,
		tbl_technicians.`Name`
	FROM
		tbl_profile_medicals
	INNER JOIN tbl_technicians ON tbl_profile_medicals.TechId = tbl_technicians.Id
	ORDER BY tbl_profile_medicals.NextExaminationDate DESC";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

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
          <li><a href="#">Non Conformance Reports</a></li>
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
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="4" class="td-header"><a href="#" class="toggler sm-bar" data-prod-cat="1"><span class="icon-big">+</span> Create Non Conformance Report</a></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td width="21%" class="td-left">Non Conformance</td>
                <td width="79%" colspan="3" class="td-right"><input name="non-conformance" type="text" class="tarea-100" id="non-conformance" value="<?php echo $row_form['NonConformance']; ?>" /></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Details of Non Conformance</td>
                <td colspan="3" class="td-right" style="padding:0px"><textarea name="details" rows="5" class="tarea-100 mceEditor" id="details"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_form['Details']); ?></textarea></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Final Comment</td>
                <td colspan="3" class="td-right" style="padding:0px"><textarea name="comment" rows="5" class="tarea-100 mceEditor" id="comment"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_form['FinalComment']); ?></textarea></td>
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

      
     <div id="list-border" style="margin-top:30px">
          <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr>
              <td colspan="3" class="td-sub-header2">Non Conformance Summary</td>
            </tr>
            <tr>
              <td width="150" class="td-sub-header">Month</td>
              <td colspan="2" class="td-sub-header">Number of NCR's</td>
            </tr>
            
			<?php
			
			$i = 1;
			
            for ($m=1; $m<=12; $m++) {
				
				$i++;
                
                $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                
            ?>
            
            <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
              <td class="combo_bold"><?php echo $month; ?></td>
              <td><?php non_conformance_reports($con, $_GET['ProfileId'], $m, '2016', $i); ?></td>
              <td width="20"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
            </tr>
                                
                    <?php
					
					$query_reports = "
						SELECT
							*
						FROM
							tbl_profile_ncr
						WHERE
							TechId = '". $_GET['ProfileId'] ."'
						AND YEAR (`Date`) = '2016'
						AND MONTH(`Date`) = '$m'";
				
					$query_reports = mysqli_query($con, $query_reports)or die(mysqli_error($con));
					while($row_reports = mysqli_fetch_array($query_reports)){

					?>
                    <tr class="row<?php echo $i; ?>" style="display: none">
                      <td colspan="3" align="center" class="td-header">
                      
                        <div id="container">
                            <div id="background">
                            <a href="non-conformance.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_reports['Id']; ?>" class="edit-mini">Edit</a>
                            </div>
                            <?php echo $row_reports['Date']; ?>
                        </div>
                      
                      </td>
                    </tr>
                    <tr class="row<?php echo $i; ?>" style="display: none">
                      <td class="td-sub-sub-header">Non Conformance</td>
                      <td colspan="2" class="td-sub-sub-header"><?php echo $row_reports['NonConformance']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>" style="display: none">
                      <td valign="top" class="td-left">Created By</td>
                      <td colspan="2" class="td-right"><?php echo $row_reports['Operator']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>" style="display: none">
                      <td valign="top" class="td-left">Details of Non Conformance</td>
                      <td colspan="2" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br><br>'), $row_reports['Details']); ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>" style="display: none">
                      <td valign="top" class="td-left">Explanation</td>
                      <td colspan="2" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br><br>'), $row_reports['MyExplanation']); ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>" style="display: none">
                      <td valign="top" class="td-left">Final Comment</td>
                      <td colspan="2" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br><br>'), $row_reports['FinalComment']); ?></td>
                    </tr>
            
            <?php } ?>
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