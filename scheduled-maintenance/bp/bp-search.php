<?php 
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

logout($con);

if(isset($_POST['reset'])){
	
	unset($_SESSION['where']);
	header('location: bp-search.php');
}

if($_SESSION['kt_login_level'] >= 1){
	
	if(isset($_SESSION['areaid'])){
		
		$areaid = $_SESSION['areaid'];
		
	} else {
		
		$areaid = 1;
	}
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

// Update Status
if(isset($_GET['Status'])){
	
	$id = $_GET['Id'];
	$status = $_GET['Status'];
	
	$query = mysqli_query($con, "UPDATE tbl_scheduled_maintenance SET Status = '$status' WHERE Id = '$id'")or die(mysqli_error($con));
	
	$success = '<div id="banner-success">Status successfully updated.</div>';
}

$where = "WHERE 1=1";

if(!empty($_POST['name'])){
	
	$where .= " AND tbl_sites.Name = '". $_POST['name'] ."'";
}

if(!empty($_POST['type'])){
	
	$where .= " AND tbl_scheduled_maintenance.Description = '". $_POST['type'] ."'";
}

if(!empty($_POST['quarter'])){
	
	$where .= " AND tbl_scheduled_maintenance.Quarter = '". $_POST['quarter'] ."'";
}

if(!empty($_POST['date'])){
	
	$where .= " AND tbl_scheduled_maintenance.Date = '". $_POST['date'] ."'";
}

if(!empty($_POST['status'])){
	
	$where .= " AND tbl_scheduled_maintenance.Status = '". $_POST['status'] ."'";
	
}
	
if(!empty($_GET['Site'])){
	
	$where .= " AND tbl_sites.Name = '". $_GET['Site'] ."'";
}

if(!empty($_GET['Description'])){
	
	$where .= " AND tbl_scheduled_maintenance.Description = '". $_GET['Description'] ."'";
}

if(!empty($_GET['Quarter'])){
	
	$where .= " AND tbl_scheduled_maintenance.Quarter = '". $_GET['Quarter'] ."'";
}

if(!isset($_GET['Site']) && !isset($_GET['Description']) && !isset($_GET['Quarter']) && !isset($_POST['search-2'])){
	
	if(date('m-d') >= '11-01' && date('m-d') <= '01-31'){
		
		$where .= " AND tbl_scheduled_maintenance.Quarter = '1'";
	}
		
	if(date('m-d') >= '02-01' && date('m-d') <= '04-30'){
		
		$where .=  "AND tbl_scheduled_maintenance.Quarter = '2'";
	}
	
	if(date('m-d') >= '05-01' && date('m-d') <= '07-31'){
		
		$where .= " AND tbl_scheduled_maintenance.Quarter = '3'";
	}
	
	if(date('m-d') >= '08-01' && date('m-d') <= '10-31'){
		
		$where .= " AND tbl_scheduled_maintenance.Quarter = '4'";
	}
}

if(empty($_GET) && empty($_POST)){
	
	$where = "WHERE tbl_scheduled_maintenance.Id = '-1'";
}

if(!isset($_GET['Status'])){
	
	$_SESSION['where'] = $where;
}

if(isset($_GET['Status'])){
	
	$where = $_SESSION['where'];
}

$query_list = "
SELECT
	tbl_sites.`Name`,
	tbl_scheduled_maintenance.Date,
	tbl_scheduled_maintenance.ScheduledMaintenanceId,
	tbl_scheduled_maintenance.Id,
	tbl_scheduled_maintenance.`Status`,
	tbl_scheduled_maintenance.`Quarter`,
	tbl_scheduled_maintenance.Description,
	tbl_scheduled_maintenance.SiteId
FROM
	tbl_scheduled_maintenance
INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
$where
ORDER BY
	tbl_scheduled_maintenance.Date ASC, tbl_scheduled_maintenance.Quarter ASC";
	  
$query_list = mysqli_query($con, $query_list) or die(mysqli_error($con));
$row_list = mysqli_fetch_assoc($query_list);



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
      
	  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
      <script>!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');</script>
      <script type="text/javascript" src="../../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
      <script type="text/javascript" src="../../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
      <link rel="stylesheet" type="text/css" href="../../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>
            
	  <script type="text/javascript">
jQuery(document).ready(function() {	
            
					$(".various3").fancybox({
					  'hideOnContentClick' : true,
					  'width' : 700,
					  'type' : 'iframe',
					  'padding' : 0,
					  'scrolling' : 'yes',
                      'onClosed' : function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
					  parent.location.reload(true);
                      },				  
			          'onComplete' : function() {
						$('#fancybox-frame').load(function() { // wait for frame to load and then gets it's height
						  $('#fancybox-content').height($(this).contents().find('body').height()+18);
						});
					  }
					});
            
        });
		
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
      <?php include('../../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">BP</a></li>
            <li><a href="#">Search</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search --><!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
      
      <?php if(isset($success)){ ?>
        <?php echo $success; ?>
      <?php } ?>
      
        <div id="list-border">
          <form id="form2" name="form2" method="post" action="bp-search.php">
            <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
              <tr>
                <td class="td-header" width="200" align="left" nowrap="nowrap"><strong>Site</strong></td>
                <td class="td-header" width="200" align="left"><strong>Description</strong></td>
                <td width="120" align="left" class="td-header"><strong>Quarter</strong></td>
                <td class="td-header" width="150" align="left"><strong>Date</strong></td>
                <td width="129" colspan="2" align="left" class="td-header">Status</td>
              </tr>
              <tr>
                <td class="td-search" align="left" nowrap="nowrap">
                <input name="name" type="text" class="tarea-100" id="name" value="<?php echo $_POST['name']; ?>" style="color:#19446f" />
                </td>
                <td class="td-search" align="left">
                <select name="type" class="tarea-100" id="type" style="color:#19446f">
                  <option value="">Select one...</option>
                  <option value="HVAC" <?php if($_POST['type'] == 'HVAC'){ echo 'selected="selected"'; } ?>>HVAC</option>
                  <option value="General Building"<?php if($_POST['type'] == 'General Building'){ echo 'selected="selected"'; } ?>>General Building</option>
                </select></td>
                <td align="left" class="td-search">
                <select name="quarter" class="tarea-100" id="quarter" style="color:#19446f">
                  <option value="">Select one...</option>
                  <option value="1" <?php if($row_form['Quarter'] == '1'){ echo 'selected="selected"'; } ?>>First Quarter</option>
                  <option value="2" <?php if($row_form['Quarter'] == '2'){ echo 'selected="selected"'; } ?>>Second Quarter</option>
                  <option value="3" <?php if($row_form['Quarter'] == '3'){ echo 'selected="selected"'; } ?>>Third Quarter</option>
                  <option value="4" <?php if($row_form['Quarter'] == '4'){ echo 'selected="selected"'; } ?>>Fourth Quarter</option>
                </select></td>
                <td class="td-search" align="left">
                <input name="date" class="tarea-100" id="date" value="<?php echo $date2; ?>" style="color:#19446f" />
                <script type="text/javascript">
                          $('#date').datepicker({
                          dateFormat: "yy-mm-dd"
                          });
                          </script>
                </td>
                <td class="td-search" align="left">
                <select name="status" class="tarea-100" id="status" style="color:#19446f">
                  <option value="">Select one...</option>
                  <option value="Qued">Qued</option>
                  <option value="In Progress">In Progress</option>
                  <option value="Complete">Complete</option>
                </select></td>
                <td width="20" align="left" class="td-search" style="padding:0"><input name="search-2" type="submit" class="btn-search-small" id="search-2" value="" /></td>
              </tr>
              <?php if(mysqli_num_rows($query_list) >= 1){ ?>
              <?php $i = 0; ?>
              <?php do { ?>
              <?php $i++; ?>
              <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                <td width="200"><?php echo $row_list[ 'Name']; ?></td>
                <td width="200"><?php echo $row_list[ 'Description']; ?></td>
                <td><?php echo $row_list[ 'Quarter']; ?></td>
                <td width="150"><?php echo $row_list[ 'Date']; ?></td>
                <td colspan="2">
                <select name="status-jump" class="tarea-100" id="status-jump" onchange="MM_jumpMenu('parent',this,0)">
                  <option value="">Status</option>
                  <option value="bp-search.php?Id=<?php echo $row_list['Id']; ?>&Status=Qued" <?php if($row_list['Status'] == 'Qued'){ echo 'selected="selected"'; } ?>>Qued</option>
                  <option value="bp-search.php?Id=<?php echo $row_list['Id']; ?>&Status=In Progress" <?php if($row_list['Status'] == 'In Progress'){ echo 'selected="selected"'; } ?>>In Progress</option>
                  <option value="bp-search.php?Id=<?php echo $row_list['Id']; ?>&Status=Complete" <?php if($row_list['Status'] == 'Complete'){ echo 'selected="selected"'; } ?>>Complete</option>
                </select></td>
                <?php
				
				$pieces = explode('-',$row_list['Date']);
				
				$year = $pieces[0];
				$month = $pieces[1];
				$day = $pieces[2];
				
				?>
              </tr>
              <?php } while ($row_list = mysqli_fetch_assoc($query_list)); ?>
              <?php } ?>
            </table>
          </form>
        </div>
        
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td width="200">&nbsp;</td>
            <td width="200">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td width="120">&nbsp;</td>
            <td width="120" align="right">
            <form id="form3" name="form3" method="post" action="">
              <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
              <input name="status" type="hidden" id="status" value="In Progress" />
            </form></td>
          </tr>
        </table>
        
   </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
</body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($Recordset3);
?>