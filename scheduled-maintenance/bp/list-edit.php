<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

if(isset($_POST)){
	
	$name = $_POST['name'];
	$type = $_POST['type'];
	$date = $_POST['date'];
	$siteid = $_POST['siteid'];
}

if(isset($_POST['save'])){
	
   $form_data = array(
	  
	  'Date' => addslashes($_POST['date']),
	);
		
	dbUpdate('tbl_scheduled_maintenance', $form_data, $where_clause="SiteId = '". $_POST['siteid'] ."' AND Quarter = '". $_POST['quarter'] ."' AND Description = '". $_POST['type'] ."'",$con);
	
}

if(isset($_GET['Delete'])){
	
   $form_data = array(
	  
	  'Date' => '',
	);
	
	dbUpdate('tbl_scheduled_maintenance', $form_data, $where_clause="Id = '". $_GET['Delete'] ."'",$con);
}

$id = $_GET['Edit'];

$query_form = "
	SELECT
		tbl_sites.`Name`,
		tbl_scheduled_maintenance.Description,
		tbl_scheduled_maintenance.Date,
		tbl_scheduled_maintenance.`Quarter`,
		tbl_scheduled_maintenance.Id
	FROM
		tbl_scheduled_maintenance
	INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
	WHERE
		tbl_scheduled_maintenance.Id = '$id'";

$query_form = mysqli_query($con, $query_form)or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <link href="../../css/calendar.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
    
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>
    
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <div id="list-border">
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td colspan="4" align="center" class="td-header"><?php echo date('d F Y', strtotime($row_form['Date'])); ?></td>
      </tr>
      <tr>
        <td width="90" class="td-left">Site Name</td>
        <td class="td-right"><?php echo $row_form['Name']; ?></td>
        <td class="td-right">Maintenance</td>
        <td class="td-right"><?php echo $row_form['Description']; ?></td>
      </tr>
      <tr>
        <td class="td-left">Date</td>
        <td class="td-right">
          <input name="date" class="tarea-100" id="date" value="<?php echo $row_form['Date']; ?>" />
          
		  <script type="text/javascript">
          $('#date').datepicker({
          dateFormat: "yy-mm-dd"
          });
          </script>
          
        </td>
        <td width="90" class="td-left">Quarter</td>
        <td class="td-right"><?php echo $row_form['Quarter']; ?></td>
      </tr>
    </table>
  </div>
  <input name="siteid" type="hidden" id="siteid" value="" />
  <input name="date" type="hidden" id="date" value="<?php echo date('Y-m-d', strtotime($_GET['Day'] .'-'. $_GET['Month'] .'-'. $_GET['Year'])); ?>" />
  <input type="hidden" name="id" id="id" />

  <table width="100%" border="0" cellspacing="1" cellpadding="0">
    <tr>
      <td align="right">
      <?php if(isset($_GET['Edit'])){ ?>
        <input name="update" type="submit" class="btn-new" id="update" value="Update" />
      <?php } else { ?>
        <input name="save" type="submit" class="btn-new" id="save" value="Save" />
      <?php } ?>
      </td>
    </tr>
  </table>
  
</form>
</body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($query_form);
?>