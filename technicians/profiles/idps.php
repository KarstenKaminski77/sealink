<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$edit = $_GET['Edit'];

if(!isset($_COOKIE['userid'])){
	
	header('Location: ../../index.php');
	
	exit();
}

if(isset($_POST['reset'])){
	
	header('Location: idps.php');
	exit();
}

$form_data = array(
	
	'TechId' => $_POST['tech'],
	'TrainingNeeded' => $_POST['needed'],
	'Facilitation' => $_POST['facilitation'],
	'TrainingObtained' => $_POST['obtained'],
	'AssessmentDates' => $_POST['date'],
	'Progress' => $_POST['progress'],
	'Remarks' => $_POST['remarks'],
	
	);

if(isset($_POST['insert'])){
	
	dbInsert('tbl_profile_idps', $form_data, $con);
	
	header('Location: idps.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_idps', $form_data, $where_clause="Id = '". $edit ."'",$con);
	
	header('Location: idps.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_idps', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: idps.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_idps WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_profile_idps.Id,
		tbl_profile_idps.TechId,
		tbl_profile_idps.TrainingNeeded,
		tbl_profile_idps.Facilitation,
		tbl_profile_idps.TrainingObtained,
		tbl_profile_idps.AssessmentDates,
		tbl_profile_idps.Progress,
		tbl_profile_idps.Remarks,
		tbl_technicians.`Name`
	FROM
		tbl_profile_idps
	INNER JOIN tbl_technicians ON tbl_profile_idps.TechId = tbl_technicians.Id
	WHERE tbl_profile_idps.TechId = '". $_GET['ProfileId'] ."'";
	
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
          <li><a href="#">Seavest Asset Management</a></li>
          <li><a href="#">Personnel</a></li>
          <li><a href="i#">Profiles</a></li>
          <li><a href="index.php?ProfileId=<?php echo $_GET['ProfileId']; ?>"><?php profile_name($con, $_GET['ProfileId']); ?></a></li>
          <li><a href="#">IDP's</a></li>
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
                <td colspan="4" class="td-header"><a href="#" class="toggler sm-bar" data-prod-cat="1"><span class="icon-big">+</span> Create IDP</a></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td width="20%" class="td-left">Full Name</td>
                <td width="30%" class="td-right"><select name="tech" class="tarea-100" id="tech">
                  <option value="">Full Name...</option>
                  <?php while($row_techs_dd = mysqli_fetch_array($query_techs_dd)){ ?>
                  <option value="<?php echo $row_techs_dd['Id']; ?>" <?php if($row_form['TechId'] == $row_techs_dd['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_techs_dd['Name']; ?></option>
                  <?php } ?>
                </select></td>
                <td width="20%" class="td-left">Training Needed</td>
                <td width="30%" class="td-right"><input name="needed" type="text" class="tarea-100" id="needed" value="<?php echo $row_form['TrainingNeeded']; ?>" /></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Facilitation</td>
                <td class="td-right">
                  <input name="facilitation" type="text" class="tarea-100" id="facilitation" value="<?php echo $row_form['Facilitation']; ?>" />
                  
                </td>
                <td class="td-left">Training Obtained</td>
                <td class="td-right"><input name="obtained" type="text" class="tarea-100" id="obtained" value="<?php echo $row_form['TrainingObtained']; ?>" /></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Planned Assessment Date</td>
                <td colspan="3" class="td-right">

                  <input name="date" type="text" class="tarea-100" id="date" value="<?php echo $row_form['AssessmentDates']; ?>" />
                  
				  <script type="text/javascript">
                    $('#date').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                   </script>

                </td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Progress</td>
                <td colspan="3" class="td-right" style="padding:0px">
                  <textarea name="progress" rows="5" class="tarea-100 mceEditor" id="progress"><?php echo $row_form['Progress']; ?></textarea>
                </td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Remarks</td>
                <td colspan="3" class="td-right" style="padding:0px">
                  <textarea name="remarks" rows="5" class="tarea-100 mceEditor" id="remarks"><?php echo $row_form['Remarks']; ?></textarea>
                </td>
              </tr>
            </table>
          </div>

        <div  style="margin-bottom:20px">
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
   
     <div id="list-border" style="margin-bottom:20px">
       <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr class="row2000">
              <td width="159" class="td-header">Full Name</td>
              <td class="td-header">Training Needed</td>
              <td width="30" align="center" class="td-header-right">&nbsp;</td>
              <td width="30" align="center" class="td-header-right">&nbsp;</td>
              <td width="30" align="center" class="td-header-right">&nbsp;</td>
            </tr>
            
            <?php 
			
			$i = 1;
			
			while($row_list = mysqli_fetch_array($query_list)){
				
				$i++;
							
			?>
           
                <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?> row2000" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                  <td><?php echo $row_list['Name']; ?></td>
                  <td><?php echo $row_list['TrainingNeeded']; ?></td>
                  <td align="center" style="padding:0"><a href="idps.php?Delete=<?php echo $row_list['Id']; ?>"  class="delete"></a></td>
                  <td align="center" style="padding:0"><a href="idps.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_list['Id']; ?>"  class="edit"></a></td>
                  <td align="center" style="padding:0"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td colspan="5" align="center" class="td-header">
                  </td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Facilitation</td>
                  <td colspan="4" class="td-right"><?php echo $row_list['Facilitation']; ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Training Obtained</td>
                  <td colspan="4" class="td-right"><?php echo $row_list['TrainingObtained']; ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Planned Assessment Date</td>
                  <td colspan="4" class="td-right"><?php echo $row_list['AssessmentDates']; ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Progress</td>
                  <td colspan="4" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['Progress']); ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Remarks</td>
                  <td colspan="4" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['Remarks']); ?></td>
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