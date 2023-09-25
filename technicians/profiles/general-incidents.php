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
	
	header('Location: general-incidents.php?ProfileId='. $_GET['ProfileId']);
	
	exit();
}

$form_data = array(
	
	'OperatorId' => $_COOKIE['name'],
	'TechId' => $_GET['ProfileId'],
	'Date' => $_POST['date'],
	'Time' => $_POST['time'],
	'Incident' => $_POST['incident'],
	'Description' => $_POST['description'],
	'ActionTaken' => $_POST['action'],
	'FinalComments' => $_POST['comments']
	
	);


if(isset($_POST['insert'])){
	
	dbInsert('tbl_profile_gir', $form_data, $con);
	
	header('Location: general-incidents.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_gir', $form_data, $where_clause="Id = '". $edit ."'",$con);
	
	header('Location: general-incidents.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_ncr', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: general-incidents.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_list = "
	SELECT
		tbl_profile_gir.Id,
		tbl_profile_gir.OperatorId,
		tbl_profile_gir.TechId,
		tbl_profile_gir.Date,
		tbl_profile_gir.Time,
		tbl_profile_gir.Incident,
		tbl_profile_gir.Description,
		tbl_profile_gir.ActionTaken,
		tbl_profile_gir.FinalComments,
		tbl_technicians.`Name`
	FROM
		tbl_profile_gir
	INNER JOIN tbl_technicians ON tbl_profile_gir.TechId = tbl_technicians.Id
	WHERE tbl_profile_gir.TechId = '". $_GET['ProfileId'] ."'";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_gir WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);
		
$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));

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
          <li><a href="#">General Incident Reports</a></li>
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
                <td colspan="4" class="td-header"><a href="#" class="toggler sm-bar" data-prod-cat="1"><span class="icon-big">+</span> Create General Incident Report</a></td>
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
                  <td class="td-header">Operator</td>
                  <td class="td-header">Full Name</td>
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
                  <td><?php echo $row_list['OperatorId']; ?></td>
                  <td><?php echo $row_list['Name']; ?></td>
                  <td><?php echo $row_list['Date']; ?></td>
                  <td><?php echo $row_list['Time']; ?></td>
                  <td align="center"><a href="general-incidents.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_list['Id']; ?>"  class="edit"></a></td>
                  <td align="center"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td colspan="6" align="center" class="td-header">
                  </td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td width="255" class="td-left">Nature of Incident</td>
                  <td colspan="5" class="td-right"><?php echo $row_list['Incident']; ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Brief Description</td>
                  <td colspan="5" class="td-right"><?php echo $row_list['Description']; ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Action Taken</td>
                  <td colspan="5" class="td-right"><?php echo $row_list['ActionTaken']; ?></td>
                </tr>
                <tr class="row<?php echo $i; ?>" style="display: none">
                  <td class="td-left">Final Comments</td>
                  <td colspan="5" class="td-right"><?php echo $row_list['FinalComments']; ?></td>
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