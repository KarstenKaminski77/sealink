<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

if(isset($_POST['save']) || isset($_POST['update'])){
	
	$site = $_POST['name'];
	$date = $_POST['date'];
	$techid1 = $_POST['tech-1'];
	$techid2 = $_POST['tech-2'];
	$siteid = $_POST['siteid'];
	
	$id = $_POST['id'];

	$query_jobid  = mysqli_query($con, "SELECT * FROM tbl_scheduled_maintenance WHERE SiteId = '$siteid' AND Quarter = '". $_POST['quarter'] ."'")or die(mysqli_error($con));
	$row_jobid = mysqli_fetch_array($query_jobid);

	$jobid = $row_jobid['JobId'];
	
	$query_jc  = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
	$row_jc = mysqli_fetch_array($query_jc);
	
	$jobno = $row_jc['JobNo'];
	
}

if(isset($_POST['save']) || isset($_POST['update'])){
	
	if(!empty($_POST['name']) && !empty($_POST['quarter'])){
	
	   $form_data = array(
		  
		  'Date' => addslashes($_POST['date']),
		);
		
	   $form_data_2 = array(
		  
		  'JobNo' => $_POST['jobno'],
		);
		
		// Add Tech 1 To History Alert
		if(!empty($_POST['tech-1'])){
							
			mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId = '$techid1'") or die(mysqli_error($con));
			mysqli_query($con, "INSERT INTO tbl_history_alerts (Site,JobNo,Date,JobId,TechnicianId,Description) VALUES ('$site','$jobno','$date','$jobid','$techid1','Scheduled Maintenance')") or die(mysqli_error($con));
			
			$form_data['TechnicianId'] = $techid1;
		}
		
		// Add Tech 2 To History Alert
		if(!empty($_POST['tech-2'])){
							
			mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId = '$techid2'") or die(mysqli_error($con));
			mysqli_query($con, "INSERT INTO tbl_history_alerts (Site,JobNo,Date,JobId,TechnicianId,Description) VALUES ('$site','$jobno','$date','$jobid','$techid2','Scheduled Maintenance')") or die(mysqli_error($con));
			
			$form_data['TechnicianId2'] = $techid2;
		}
		
		
		$query_assets = mysqli_query($con, "SELECT * FROM tbl_sm_assets WHERE Company = '14'")or die(mysqli_error($con));
		while($row_assets = mysqli_fetch_array($query_assets)){
			
			$description = $row_assets['Asset'];
			$assetid = $row_assets['Id'];
			$catid = $row_assets['CatId'];
	
			// Insert Check List
			$query_check = mysqli_query($con, "SELECT * FROM tbl_sm_checklist_items WHERE Company = '14' AND Asset = '$assetid'")or die(mysqli_error($con));
			while($row_check = mysqli_fetch_array($query_check)){
				
				$id = $row_check['Id'];
				
				mysqli_query($con, "INSERT INTO tbl_sm_checklist_relation (JobId,CatId,AssetId,ListItemId)
				VALUES ('$jobid','$catid','$assetid','$id')")or die(mysqli_error($con));
			
			}
		}
		
			
		dbUpdate('tbl_scheduled_maintenance', $form_data, $where_clause="SiteId = '". $_POST['siteid'] ."' AND Quarter = '". $_POST['quarter'] ."'",$con);
		dbUpdate('tbl_jc', $form_data_2, $where_clause="JobId = '". $jobid ."'",$con);

	} else {
		
		$border = 'style="border-color: #FF0000"';
	}
}

if(isset($_POST['update'])){
	
   $form_data = array(
	  
	  'Date' => addslashes($_POST['date']),
	);
	
	
   $form_data_2 = array(
	  
	  'JobNo' => $_POST['jobno'],
	);
	
	if(!empty($_POST['tech-1'])){
		
		$form_data['TechnicianId'] = $_POST['tech-1'];
	}
	
	if(!empty($_POST['tech-2'])){
		
		$form_data['TechnicianId2'] = $_POST['tech-2'];
	}
		
	dbUpdate('tbl_scheduled_maintenance', $form_data, $where_clause="Id = '". $_POST['id'] ."'",$con);
	dbUpdate('tbl_jc', $form_data_2, $where_clause="JobId = '". $_POST['jobid'] ."'",$con);
	
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
		tbl_scheduled_maintenance.SiteId,
		tbl_scheduled_maintenance.Description,
		tbl_scheduled_maintenance.Date,
		tbl_scheduled_maintenance.`Quarter`,
		tbl_scheduled_maintenance.TechnicianId,
		tbl_scheduled_maintenance.TechnicianId2,
		tbl_scheduled_maintenance.Id,
		tbl_jc.JobNo,
		tbl_jc.JobId
	FROM
		tbl_scheduled_maintenance
	INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
	INNER JOIN tbl_jc ON tbl_scheduled_maintenance.JobId = tbl_jc.JobId
	WHERE
		tbl_scheduled_maintenance.Id = '$id'";

$query_form = mysqli_query($con, $query_form)or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
    SELECT
		tbl_sites.`Name`,
		tbl_scheduled_maintenance.JobId,
		tbl_scheduled_maintenance.SiteId,
		tbl_scheduled_maintenance.Description,
		tbl_scheduled_maintenance.Date,
		tbl_scheduled_maintenance.`Quarter`,
		tbl_scheduled_maintenance.Id,
		tbl_jc.JobNo
	FROM
		tbl_scheduled_maintenance
	INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
	INNER JOIN tbl_jc ON tbl_scheduled_maintenance.JobId = tbl_jc.JobId
	WHERE
		tbl_scheduled_maintenance.Date = '$date'";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

$query_tech = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));
$query_tech2 = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));
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
    
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <div id="list-border">
  
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td colspan="5" align="center" class="td-header"><?php echo date('d F Y', strtotime($_GET['Day'] .'-'. $_GET['Month'] .'-'. $_GET['Year'])); ?></td>
      </tr>
      <tr>
        <td width="90" class="td-left">Site Name</td>
        <td colspan="2" class="td-right" <?php echo $border; ?>><input name="name" type="text" class="tarea-100" id="name" value="<?php echo $row_form['Name']; ?>" /></td>
        <td class="td-left">Job Number</td>
        <td class="td-right"><input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $row_form['JobNo']; ?>" /></td>
      </tr>
      <tr>
        <td class="td-left">Technicians</td>
        <td class="td-right">
        <select name="tech-1" class="tarea-100" id="tech-1">
          <option value="">Select one...</option>
          <?php while($row_tech = mysqli_fetch_array($query_tech)){ ?>
            <option value="<?php echo $row_tech['Id']; ?>" <?php if($row_tech['Id'] == $row_form['TechnicianId']){ echo 'selected="selected"'; } ?>><?php echo $row_tech['Name']; ?></option>
          <?php } ?>
        </select>
        </td>
        <td class="td-right"><select name="tech-2" class="tarea-100" id="tech-2">
          <option value="">Select one...</option>
          <?php while($row_tech2 = mysqli_fetch_array($query_tech2)){ ?>
          <option value="<?php echo $row_tech2['Id']; ?>" <?php if($row_tech2['Id'] == $row_form['TechnicianId2']){ echo 'selected="selected"'; } ?>><?php echo $row_tech2['Name']; ?></option>
          <?php } ?>
        </select></td>
        <td width="90" class="td-left">Quarter</td>
        <td class="td-right" <?php echo $border; ?>>
          <select name="quarter" class="tarea-100" id="quarter">
            <option value="">Select one...</option>
            <option value="1" <?php if($row_form['Quarter'] == '1'){ echo 'selected="selected"'; } ?>>First Quarter</option>
            <option value="2" <?php if($row_form['Quarter'] == '2'){ echo 'selected="selected"'; } ?>>Second Quarter</option>
            <option value="3" <?php if($row_form['Quarter'] == '3'){ echo 'selected="selected"'; } ?>>Third Quarter</option>
            <option value="4" <?php if($row_form['Quarter'] == '4'){ echo 'selected="selected"'; } ?>>Fourth Quarter</option>
          </select>
        </td>
      </tr>
    </table>
  </div>
  
  <input name="siteid" type="hidden" id="siteid" value="<?php echo $row_form['SiteId']; ?>" />
  <input name="jobid" type="hidden" id="jobid" value="<?php echo $row_form['JobId']; ?>" />
  <input name="id" type="hidden" id="id" value="<?php echo $row_form['Id']; ?>" />
  <input name="date" type="hidden" id="date" value="<?php echo date('Y-m-d', strtotime($_GET['Day'] .'-'. $_GET['Month'] .'-'. $_GET['Year'])); ?>" />
  
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
  
  <?php if(mysqli_num_rows($query_list) >= 1){ ?>
  <div id="list-border" style="margin-top:10px">
    <table width="100%" border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td class="td-header">Site Name</td>
        <td width="100" class="td-header">Job No.</td>
        <td width="150" class="td-header">Maintenance</td>
        <td width="50" align="center" class="td-header">Quarter</td>
        <td width="20" class="td-header-right">&nbsp;</td>
        <td width="20" class="td-header-right">&nbsp;</td>
      </tr>
      <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
      <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
        <td><a href="#" class="toggler odd" data-prod-cat="<?php echo $x; ?>"><?php echo $row_list['Name']; ?></a></td>
        <td><?php echo $row_list['JobNo']; ?></td>
        <td><?php echo $row_list['Description']; ?></td>
        <td align="center"><?php echo $row_list['Quarter']; ?></td>
        <td><a href="events.php?Edit=<?php echo $row_list['Id'] .'&Day='. $_GET['Day'] .'&Month='. $_GET['Month'] .'&Year='. $_GET['Year']; ?>" class="edit"></a></td>
        <td><a href="events.php?Delete=<?php echo $row_list['Id'] .'&Day='. $_GET['Day'] .'&Month='. $_GET['Month'] .'&Year='. $_GET['Year']; ?>" class="delete"></a></td>
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