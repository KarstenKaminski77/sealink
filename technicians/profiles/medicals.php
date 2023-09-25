<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$edit = $_GET['Edit'];

if(!isset($_COOKIE['userid'])){
	
	header('Location: ../../index.php');
	
	exit();
}

$form_data = array(
	
	'TechId' => $_POST['tech'],
	'DateConducted' => $_POST['date-conducted'],
	'NextExaminationDate' => $_POST['date-next'],
	'Practitioner' => $_POST['practitioner'],
	'MedicalStatus' => $_POST['status'],
	
	);

$target_path = "medicals/";
$target_path = $target_path . basename($_FILES['pdf']['name']); 

if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)) {
	
	$form_data['Certificate'] = $_FILES['pdf']['name'];
}

if(isset($_POST['insert'])){
	
	dbInsert('tbl_profile_medicals', $form_data, $con);
	
	header('Location: medicals.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_medicals', $form_data, $where_clause="Id = '". $edit ."'",$con);
	
	header('Location: medicals.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_medicals', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: medicals.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_medicals WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_profile_medicals.Id,
		tbl_profile_medicals.TechId,
		tbl_profile_medicals.DateConducted,
		tbl_profile_medicals.NextExaminationDate,
		tbl_profile_medicals.Practitioner,
		tbl_profile_medicals.MedicalStatus,
		tbl_profile_medicals.Certificate,
		tbl_technicians.`Name`
	FROM
		tbl_profile_medicals
	INNER JOIN tbl_technicians ON tbl_profile_medicals.TechId = tbl_technicians.Id
	WHERE tbl_profile_medicals.TechId = '". $_GET['ProfileId'] ."'
	ORDER BY tbl_profile_medicals.DateConducted DESC";

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
          <li><a href="#">Medicals</a></li>
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
   
<?php
   
   if(isset($_GET['Edit'])){
	   
	   $display = 'table-row';
	   $display2 = 'table';
	   
   } else {
	   
	   $display = 'none';
	   $display2 = 'none';
   }
   
   ?>
   
          <div id="list-border">
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="4" class="td-header"><a href="#" class="toggler sm-bar" data-prod-cat="1"><span class="icon-big">+</span> Medical Report</a></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td width="20%" class="td-left">Full Name</td>
                <td width="30%" class="td-right">
                <select name="tech" class="tarea-100" id="tech">
                  <option value="">Full Name...</option>
                  <?php while($row_techs_dd = mysqli_fetch_array($query_techs_dd)){ ?>
                  <option value="<?php echo $row_techs_dd['Id']; ?>" <?php if($_GET['ProfileId'] == $row_techs_dd['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_techs_dd['Name']; ?></option>
                  <?php } ?>
                </select></td>
                <td width="20%" class="td-left">Date Conducted</td>
                <td width="30%" class="td-right">
                  <input name="date-conducted" type="text" class="tarea-100" id="date-conducted" value="<?php echo $row_form['DateConducted']; ?>" />
                  
                    <script type="text/javascript">
                        $('#date-conducted').datepicker({
                        dateFormat: "yy-mm-dd"
                        });
                       </script>
                  
                </td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Next Examination</td>
                <td class="td-right">
                    <input name="date-next" type="text" class="tarea-100" id="date-next" value="<?php echo $row_form['NextExaminationDate']; ?>">
                    
                    <script type="text/javascript">
                        $('#date-next').datepicker({
                        dateFormat: "yy-mm-dd"
                        });
                       </script>
                  
                </td>
                <td class="td-left">Medical Status</td>
                <td class="td-right"><span class="field-container">
                  <input name="status" type="text" class="tarea-100" id="status" value="<?php echo $row_form['MedicalStatus']; ?>" />
                </span></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Medical Practitioner and Contact Details</td>
                <td colspan="3" class="td-right" style="padding:0px">
                  <textarea name="practitioner" rows="5" class="tarea-100 mceEditor" id="practitioner"><?php echo $row_form['Practitioner']; ?></textarea>
                </td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Medical Certificate</td>
                <td colspan="3" class="td-right" style="padding:0px"><input name="pdf" type="file" class="tarea-100" id="pdf" /></td>
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
                <td width="150" class="td-sub-header2">Full Name</td>
                <td width="120" class="td-sub-header2">Date Conducted</td>
                <td width="120" class="td-sub-header2">Next Examination</td>
                <td class="td-sub-header2">Medical Status</td>
                <td width="15" class="td-sub-header2">&nbsp;</td>
                <td width="15" class="td-sub-header2">&nbsp;</td>
                <td width="15" class="td-sub-header2">&nbsp;</td>
              </tr>
              
              <?php 
              
              $i = 1;
              
              while($row_list = mysqli_fetch_array($query_list)){
                  
                  $i++;
              
              ?>
             
              <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                <td><?php echo $row_list['Name']; ?></td>
                <td><?php echo $row_list['DateConducted']; ?></td>
                <td><?php echo $row_list['NextExaminationDate']; ?></td>
                <td><?php echo $row_list['MedicalStatus']; ?></td>
                <td width="20" align="center"><a href="medicals/<?php echo $row_list['Certificate']; ?>" target="_blank" class="icon-pdf"></a></td>
                <td width="20" align="center"><a href="medicals.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_list['Id']; ?>"  class="edit"></a></td>
                <td width="20" align="center"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
              </tr>
              <tr class="row<?php echo $i; ?>" style="display: none">
                <td colspan="7" align="center" class="td-header">
                </td>
              </tr>
              <tr class="row<?php echo $i; ?>" style="display: none">
                <td colspan="7" class="td-sub-sub-header">Medical Practioner & Contact Details</td>
              </tr>
              <tr class="row<?php echo $i; ?>" style="display: none">
                <td colspan="7" class="td-right"><?php echo str_replace(array('<p>','</p>'),array('','<br>'), $row_list['Practitioner']); ?></td>
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