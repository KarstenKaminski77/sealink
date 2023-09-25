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
	
	header('Location: tool-allocation.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$form_data = array(
	
	'TechId' => $_POST['tech'],
	'IssuedBy' => $_POST['issuer'],
	'Date' => $_POST['date'],
	'Make' => $_POST['make'],
	'Equipment' => $_POST['equipment'],
	'ConditionAllocated' => $_POST['condition'],
	'SerialNo' => $_POST['serial'],
	'ReturnDate' => $_POST['return-date'],
	'ConditionReturned' => $_POST['return-condition'],
	
	);

if(isset($_POST['insert'])){
	
	dbInsert('tbl_profile_tool_allocation', $form_data, $con);
	
	header('Location: tool-allocation.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_tool_allocation', $form_data, $where_clause="Id = '". $edit ."'",$con);
	
	header('Location: tool-allocation.php?ProfileId='. $_GET['ProfileId'] .'&Edit='. $_GET['Edit']);
	exit();
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_tool_allocation', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: tool-allocation.php?ProfileId='. $_GET['ProfileId']);
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_tool_allocation WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_current = "
	SELECT
		tbl_profile_tool_allocation.Id,
		tbl_profile_tool_allocation.TechId,
		tbl_profile_tool_allocation.Date,
		tbl_profile_tool_allocation.Equipment,
		tbl_profile_tool_allocation.Make,
		tbl_profile_tool_allocation.SerialNo,
		tbl_profile_tool_allocation.ConditionAllocated,
		tbl_profile_tool_allocation.ReturnDate,
		tbl_profile_tool_allocation.ConditionReturned,
		tbl_profile_tool_allocation.IssuedBy,
		tbl_technicians.`Name`
	FROM
		tbl_profile_tool_allocation
	INNER JOIN tbl_technicians ON tbl_profile_tool_allocation.TechId = tbl_technicians.Id	
	WHERE tbl_profile_tool_allocation.TechId = '". $_GET['ProfileId'] ."'
	ORDER BY
		tbl_profile_tool_allocation.Date DESC";
	
$query_current = mysqli_query($con, $query_current)or die(mysqli_error($con));

$query_list = "
	SELECT
		tbl_profile_tool_allocation.Id,
		tbl_profile_tool_allocation.TechId,
		tbl_profile_tool_allocation.Date,
		tbl_profile_tool_allocation.Equipment,
		tbl_profile_tool_allocation.Make,
		tbl_profile_tool_allocation.SerialNo,
		tbl_profile_tool_allocation.ConditionAllocated,
		tbl_profile_tool_allocation.ReturnDate,
		tbl_profile_tool_allocation.ConditionReturned,
		tbl_profile_tool_allocation.IssuedBy,
		tbl_technicians.`Name`
	FROM
		tbl_profile_tool_allocation
	INNER JOIN tbl_technicians ON tbl_profile_tool_allocation.TechId = tbl_technicians.Id	
	WHERE tbl_profile_tool_allocation.TechId = '". $_GET['ProfileId'] ."'
	ORDER BY
		tbl_profile_tool_allocation.Date DESC";
	
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
          <li><a href="#">Tool Allocation</a></li>
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
                <td colspan="4" class="td-header"><a href="#" class="toggler sm-bar" data-prod-cat="1"><span class="icon-big">+</span> Allocate Tools</a></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td width="20%" class="td-left">Issued By</td>
                <td width="30%" class="td-right">
                  <select name="issuer" class="tarea-100" id="issuer">
                    <option value="">Full Name...</option>
                    <?php while($row_techs = mysqli_fetch_array($query_techs)){ ?>
                    <option value="<?php echo $row_techs['Name']; ?>" <?php if($row_form['IssuedBy'] == $row_techs['Name']){ echo 'selected="selected"'; } ?>><?php echo $row_techs['Name']; ?></option>
                    <?php } ?>
                </select></td>
                <td width="20%" class="td-left">Full Name</td>
                <td width="30%" class="td-right">
                <select name="tech" class="tarea-100" id="tech">
                  <option value="">Full Name...</option>
                  <?php while($row_techs_dd = mysqli_fetch_array($query_techs_dd)){ ?>
                  <option value="<?php echo $row_techs_dd['Id']; ?>" <?php if($row_form['TechId'] == $row_techs_dd['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_techs_dd['Name']; ?></option>
                  <?php } ?>
                </select></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Date</td>
                <td class="td-right">
                  <input name="date" type="text" class="tarea-100" id="date" value="<?php echo $row_form['Date']; ?>" />
                  
				  <script type="text/javascript">
                    $('#date').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                   </script>
                  
                </td>
                <td class="td-left">Equipment</td>
                <td class="td-right"><input name="equipment" type="text" class="tarea-100" id="equipment" value="<?php echo $row_form['Equipment']; ?>" /></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Make</td>
                <td class="td-right">
                  <input name="make" type="text" class="tarea-100" id="make"  value="<?php echo $row_form['Make']; ?>">
                </td>
                <td class="td-left">Serial Number</td>
                <td class="td-right">
                  <input name="serial" type="text" class="tarea-100" id="serial" value="<?php echo $row_form['SerialNo']; ?>">
                </td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Condition</td>
                <td colspan="3" class="td-right" style="padding:0px">
                  <textarea name="condition" rows="5" class="tarea-100 mceEditor" id="condition"><?php echo $row_form['ConditionAllocated']; ?></textarea>
                </td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Return Date</td>
                <td colspan="3" class="td-right">
                
                <input name="return-date" type="text" class="tarea-100" id="return-date" value="<?php echo $row_form['ReturnDate']; ?>" />
                
                <script type="text/javascript">
                          $('#return-date').datepicker({
                          dateFormat: "yy-mm-dd"
                          });
                         </script>
                
                </td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td valign="top" class="td-left">Condition Returned In</td>
                <td colspan="3" class="td-right" style="padding:0px">
                  <textarea name="return-condition" rows="5" class="tarea-100 mceEditor" id="return-condition"><?php echo $row_form['ConditionReturned']; ?></textarea>
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
            <tr>
              <td colspan="7" class="td-sub-header2">Current Tool Allocations</td>
            </tr>
            <tr>
              <td width="159" class="td-sub-header">Full Name</td>
              <td width="205" class="td-sub-header">Equipment</td>
              <td width="150" class="td-sub-header">Make</td>
              <td width="120" class="td-sub-header">Date</td>
              <td width="100" class="td-sub-header">Return Date</td>
              <td width="20" align="center" class="td-sub-header">&nbsp;</td>
              <td width="20" align="center" class="td-sub-header">&nbsp;</td>
            </tr>
            
            <?php 
			
			$i = 1;
			
			while($row_current = mysqli_fetch_array($query_current)){
				
				$i++;
				
				if($row_current['ReturnDate'] == '0000-00-00'){
			
			?>
           
                    <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                      <td><?php echo $row_current['Name']; ?></td>
                      <td><?php echo $row_current['Equipment']; ?></td>
                      <td><?php echo $row_current['Make']; ?></td>
                      <td><?php echo $row_current['Date']; ?></td>
                      <td><?php echo $row_current['ReturnDate']; ?></td>
                      <td align="center"><a href="tool-allocation.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_current['Id']; ?>"  class="edit"></a></td>
                      <td align="center"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td colspan="7" align="center" class="td-header">
                      </td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Issued By</td>
                      <td colspan="6" class="td-right"><?php echo $row_current['IssuedBy']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Serial Number</td>
                      <td colspan="6" class="td-right"><?php echo $row_current['SerialNo']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Condition</td>
                      <td colspan="6" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_current['ConditionAllocated']); ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Return Date</td>
                      <td colspan="6" class="td-right"><?php echo $row_current['ReturnDate']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Condition Returned In</td>
                      <td colspan="6" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_current['ConditionReturned']); ?></td>
                    </tr>
                    
                  <?php } ?>
            
            <?php } ?>
            
          </table>
        </div>
   
     <div id="list-border">
       <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr>
              <td colspan="7" class="td-sub-header2">Tool Allocations History</td>
            </tr>
            <tr>
              <td width="159" class="td-sub-header">Full Name</td>
              <td width="205" class="td-sub-header">Equipment</td>
              <td width="150" class="td-sub-header">Make</td>
              <td width="120" class="td-sub-header">Date</td>
              <td width="100" class="td-sub-header">Return Date</td>
              <td width="20" align="center" class="td-sub-header">&nbsp;</td>
              <td width="20" align="center" class="td-sub-header">&nbsp;</td>
            </tr>
            
            <?php 
			
			$i = 1;
			
			while($row_list = mysqli_fetch_array($query_list)){
				
				$i++;
				
				if($row_list['ReturnDate'] != '0000-00-00'){
			
			?>
           
                    <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                      <td><?php echo $row_list['Name']; ?></td>
                      <td><?php echo $row_list['Equipment']; ?></td>
                      <td><?php echo $row_list['Make']; ?></td>
                      <td><?php echo $row_list['Date']; ?></td>
                      <td><?php echo $row_list['ReturnDate']; ?></td>
                      <td align="center"><a href="tool-allocation.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Edit=<?php echo $row_list['Id']; ?>"  class="edit"></a></td>
                      <td align="center"><a href=""  class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td colspan="7" align="center" class="td-header">
                      </td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Issued By</td>
                      <td colspan="6" class="td-right"><?php echo $row_list['IssuedBy']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Serial Number</td>
                      <td colspan="6" class="td-right"><?php echo $row_list['SerialNo']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Condition</td>
                      <td colspan="6" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['ConditionAllocated']); ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Return Date</td>
                      <td colspan="6" class="td-right"><?php echo $row_list['ReturnDate']; ?></td>
                    </tr>
                    <tr class="row<?php echo $i; ?>">
                      <td class="td-left">Condition Returned In</td>
                      <td colspan="6" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['ConditionReturned']); ?></td>
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