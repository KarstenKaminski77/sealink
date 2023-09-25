<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

if(isset($_POST)){
	
	$site = $_POST['name'];
	$type = $_POST['type'];
	$date = $_POST['date'];
	$techid = $_POST['tech'];
	$siteid = $_POST['siteid'];
	
}

if(isset($_POST['save'])){
	
   $form_data = array(
	  
	  'Date' => addslashes($_POST['date']),
	);
	
	if(!empty($_POST['tech'])){
		
		$id = $_POST['id'];

		$query_jobid  = mysqli_query($con, "SELECT * FROM tbl_scheduled_maintenance WHERE SiteId = '$siteid' AND Quarter = '". $_POST['quarter'] ."' AND Description = '". $_POST['type'] ."'")or die(mysqli_error($con));
		$row_jobid = mysqli_fetch_array($query_jobid);

		$jobid = $row_jobid['JobId'];
		
		$query_jc  = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
		$row_jc = mysqli_fetch_array($query_jc);
		
		$jobno = $row_jc['JobNo'];
				
        mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'") or die(mysqli_error($con));
		mysqli_query($con, "INSERT INTO tbl_history_alerts (Site,JobNo,Date,JobId,TechnicianId,Description) VALUES ('$site','$jobno','$date','$jobid','$techid','$type')") or die(mysqli_error($con));
		
		$form_data['TechnicianId'] = $techid;
	}
		
	dbUpdate('tbl_scheduled_maintenance', $form_data, $where_clause="SiteId = '". $_POST['siteid'] ."' AND Quarter = '". $_POST['quarter'] ."' AND Description = '". $_POST['type'] ."'",$con);
	
}

if(isset($_POST['update'])){
	
   $form_data = array(
	  
	  'Date' => addslashes($_POST['date']),
	);
	
	if(!empty($_POST['tech'])){
		
		mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));
		
		$query = mysqli_query($con, "SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		
		$jobid = $row['Id'] + 1;
		
		$query_jobno  = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobNo LIKE '%SSM%' ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
		$row_jobno = mysqli_fetch_array($query_jobno);
		
		$no = intval(preg_replace('/[^0-9]+/', '', $row_jobno['JobNo']), 10) + 1;
		$jobno = 'SSM' . $no;
		
		$_SESSION['jobid'] = $jobid;
		$siteid = $_POST['id'];
		
		mysqli_query($con, "INSERT INTO tbl_jc (AreaId,CompanyId,SiteId,JobNo,JobId) 
		VALUES 
		('1','14','$siteid','$jobno','$jobid')") or die(mysqli_error($con));
		
        mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'") or die(mysqli_error($con));
                                
		$query_site = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '$siteid'") or die(mysqli_error($con));
		$row_site = mysqli_fetch_array($query_site);
		
		$site = addslashes($row_site['Name']);
		
		mysqli_query($con, "INSERT INTO tbl_history_alerts (Site,JobNo,Date,JobId,TechnicianId,Description) VALUES ('$site','$jobno','$date','$jobid','$techid','$type')") or die(mysqli_error($con));
		
		$form_data['TechnicianId'] = $_POST['tech'];
	}
		
	dbUpdate('tbl_scheduled_maintenance', $form_data, $where_clause="Id = '". $_POST['id'] ."'",$con);
	
}

if(isset($_GET['Delete'])){
	
   $form_data = array(
	  
	  'Date' => '',
	);
	
	dbUpdate('tbl_scheduled_maintenance', $form_data, $where_clause="Id = '". $_GET['Delete'] ."'",$con);
}

$date = date('Y-m-d', strtotime($_GET['Day'] .'-'. $_GET['Month'] .'-'. $_GET['Year']));
$id = $_GET['Edit'];

$query_form = "
	SELECT
		tbl_sites.`Name`,
		tbl_scheduled_maintenance.Description,
		tbl_scheduled_maintenance.Date,
		tbl_scheduled_maintenance.`Quarter`,
		tbl_scheduled_maintenance.TechnicianId,
		tbl_scheduled_maintenance.Id
	FROM
		tbl_scheduled_maintenance
	INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
	WHERE
		tbl_scheduled_maintenance.Id = '$id'";

$query_form = mysqli_query($con, $query_form)or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_sites.`Name`,
		tbl_scheduled_maintenance.Description,
		tbl_scheduled_maintenance.JobId,
		tbl_scheduled_maintenance.Date,
		tbl_scheduled_maintenance.`Quarter`,
		tbl_scheduled_maintenance.Status,
		tbl_scheduled_maintenance.Id
	FROM
		tbl_scheduled_maintenance
	INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
	WHERE
		tbl_scheduled_maintenance.Date = '$date'";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

$query_tech = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <link href="../../css/calendar.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" type="text/css" href="../../autocomplete/css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="../../autocomplete/css/main.css" />
    
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="../../autocomplete/js/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <input name="siteid" type="hidden" id="siteid" value="" />
  <input name="id" type="hidden" id="id" value="<?php echo $row_form['Id']; ?>" />
  <input name="date" type="hidden" id="date" value="<?php echo date('Y-m-d', strtotime($_GET['Day'] .'-'. $_GET['Month'] .'-'. $_GET['Year'])); ?>" />
  
  <script type="text/javascript">
      function MM_jumpMenu(targ, selObj, restore) { //v3.0
          eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
          if (restore) selObj.selectedIndex = 0;
      }

      $(document).ready(function () {
          $(".toggler").click(function (e) {
              e.preventDefault();
              $('.cat' + $(this).attr('data-prod-cat')).toggle();
          });
      });

  </script>
  
  <script type="text/javascript">
  $('#name').autocomplete({
      source: function( request, response ) {
          $.ajax({
              url : '../../autocomplete/ajax.php',
              dataType: "json",
              method: 'post',
              data: {
                 name_startsWith: request.term,
                 type: 'country_table',
                 row_num : 1
              },
               success: function( data ) {
                   response( $.map( data, function( item ) {
                      var code = item.split("|");
                      return {
                          label: code[0],
                          value: code[0],
                          data : item
                      }
                  }));
              }
          });
      },
      autoFocus: true,	      	
      minLength: 0,
      select: function( event, ui ) {
          var names = ui.item.data.split("|");						
          $('#siteid').val(names[1]);
      }		      	
  });
  </script>
  
  <?php if(mysqli_num_rows($query_list) >= 1){ ?>
  <div id="list-border" style="margin-top:10px">
    <table width="100%" border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td colspan="5" align="center" class="td-header"><?php echo date('d F Y', strtotime($_GET['Day'] .'-'. $_GET['Month'] .'-'. $_GET['Year'])); ?></td>
      </tr>
      <tr>
        <td class="td-search">Site Name</td>
        <td width="150" class="td-search">Maintenance</td>
        <td width="100" align="center" class="td-search">Quarter</td>
        <td width="100" class="td-search">Status</td>
      </tr>
      <?php 
	  $x = 0;
	  
	  while($row_list = mysqli_fetch_array($query_list)){
		  
		  $x++;
		  ?>
      <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
        <td><a href="#" class="toggler odd" data-prod-cat="<?php echo $x; ?>"><?php echo $row_list['Name']; ?></a></td>
        <td><?php echo $row_list['Description']; ?></td>
        <td align="center"><?php echo $row_list['Quarter']; ?></td>
        
        <?php
		$class = '';
		
		if($row_list['Date'] < date('Y-m-d') && $row_list['Status'] == 'Qued' || $row_list['Status'] == 'In Progress'){
			
			$class = 'cal-alert';
		}
		
		if($row_list['Status'] == 'Complete'){
			
			$class = 'cal-complete';
		}
		?>
        
        <td><div class="<?php echo $class; ?>"><?php echo $row_list['Status']; ?></div></td>
      </tr>
      <tr  class="cat<?php echo $x; ?>" style="display:none">
        <td colspan="4">
		  <?php
		  $jobid = $row_list['JobId'];
		  
          $query_history = "
			  SELECT
				  tbl_actual_history.Date,
				  tbl_actual_history.Comments,
				  tbl_technicians.`Name`
			  FROM
				  tbl_actual_history
			  INNER JOIN tbl_technicians ON tbl_actual_history.TechnicianId = tbl_technicians.Id
			  WHERE
				  tbl_actual_history.JobId = '$jobid'";
              
          $query_history = mysqli_query($con, $query_history)or die(mysqli_error($con));

          while($row_history = mysqli_fetch_array($query_history)){

          echo '<div style="font-family:Arial, Helvetica, sans-serif; font-size: 12px">';
		  echo '<span style="color:#306294; background-image:url(../../images/icons/history-bg.jpg); border:solid 1px #85afd7">
              '.
              $row_history['Name'] .' '. $row_history['Date'] .'
          </span> '. $row_history['Comments'];
		  echo '</div>';
          }
          ?>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
</form>
</body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($query_form);
  mysqli_free_result($query_list);
?>