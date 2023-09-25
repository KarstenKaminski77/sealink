<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$user_id = $_GET['ProfileId'];

if(!isset($_COOKIE['userid'])){
	
	header('Location: ../../index.php');
	
	exit();
}

if(isset($_POST['reset'])){
	
	header('Location: work-scope.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$form_data = array(
	
	'Position' => $_POST['position'],
	'EmploymentDate' => $_POST['date'],
	'ContractLength' => $_POST['contract'],
	'Position' => $_POST['position'],
	
	);

if(isset($_POST['update'])){
	
	dbUpdate('tbl_technicians', $form_data, $where_clause="Id = '". $user_id ."'",$con);
	
	for($i=0;$i<count($_POST['duty']);$i++){
		
		$duties = array(
		  
		  'Duty' => $_POST['duty'][$i],
		);
		
		dbUpdate('tbl_profile_work_duties', $duties, $where_clause="Id = '". $_POST['duty-id'][$i] ."'",$con);
	}
	
	header('Location: work-scope.php?ProfileId='. $_GET['ProfileId'].'&Edit='. $_GET['Edit']);
	exit();
}

// Work Duties
$query_duties = mysqli_query($con, "SELECT * FROM tbl_profile_work_duties WHERE TechId = '". $_GET['ProfileId'] ."'")or die(mysqli_error($con));

if((mysqli_num_rows($query_duties) == 0 || isset($_GET['Add']))){
	
	$form_data = array(
	
	  'TechId' => $user_id
	);
	
	dbInsert('tbl_profile_work_duties', $form_data, $con);
	
	header('Location: work-scope.php?ProfileId='. $_GET['ProfileId']);
	exit();
	
}

if(isset($_GET['Remove'])){
	
	dbDelete('tbl_profile_work_duties', $where_clause="Id = '". $_GET['Remove'] ."'",$con);
	
	header('Location: work-scope.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '". $_GET['ProfileId'] ."'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '". $_GET['ProfileId'] ."' ORDER BY Name ASC")or die(mysqli_error($con));

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
          <li><a href="#">Work Scope</a></li>
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
                <td colspan="4" class="td-header">General Info</td>
              </tr>
              <tr class="row1">
                <td width="20%" class="td-left">Full Name</td>
                <td width="30%" class="td-right"><?php echo $row_form['Name']; ?></td>
                <td width="20%" class="td-left">Period of Contract</td>
                <td width="30%" class="td-right"><input name="contract" type="text" class="tarea-100" id="contract" value="<?php echo $row_form['ContractLength']; ?>" /></td>
              </tr>
              <tr class="row1">
                <td class="td-left">Date of Employment</td>
                <td class="td-right">
                  <input name="date" type="text" class="tarea-100" id="date" value="<?php echo $row_form['EmploymentDate']; ?>" />
                  
				  <script type="text/javascript">
                    $('#date').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                   </script>
                  
                </td>
                <td class="td-left">Position</td>
                <td class="td-right"><input name="position" type="text" class="tarea-100" id="position" value="<?php echo $row_form['Position']; ?>" /></td>
              </tr>
              <tr class="row1">
                <td colspan="4" class="td-sub-header" style="padding-right:2px">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                      <td>Work Scope</td>
                      <td width="15" align="right">
                        <a href="work-scope.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $_GET['ProfileId']; ?>&Add=<?php echo $_GET['ProfileId']; ?>" class="add-row"></a>
                      </td>
                    </tr>
                </table></td>
              </tr>
              
              <?php while($row_duties = mysqli_fetch_array($query_duties)){ ?>
              
              <tr class="row1">
                <td colspan="4" valign="top" class="td-right">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>
                        <input name="duty[]" type="text" class="tarea-100" id="duty[]" value="<?php echo $row_duties['Duty']; ?>" />
                        <input type="hidden" name="duty-id[]" id="duty-id[]" value="<?php echo $row_duties['Id']; ?>" />
                      </td>
                      <td width="15" align="right"><a href="work-scope.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $_GET['ProfileId']; ?>&Remove=<?php echo $row_duties['Id']; ?>" class="remove"></a></td>
                    </tr>
                  </table>
                </td>
              </tr>
              
              <?php } ?>
              
            </table>
          </div>
          
        <div  style="margin-bottom:20px">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="row1">
          <tr>
            <td align="right">
            <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
            <input name="update" type="submit" class="btn-new" id="update" value="Update" />
            </td>
          </tr>
        </table>
      </div>
      </form>
        
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