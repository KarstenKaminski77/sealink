<?php require_once('../../Connections/inv.php'); ?>
<?php 
session_start();

if(!isset($_SESSION['userid'])){
	header('Location: ../index.php');
}

require_once('../../Connections/inv.php');

require_once('../../functions/functions.php');

$jobid = $_GET['Id'];

if(isset($_POST)){
	
	select_db();
	
	$technicianid = $_SESSION['userid'];
	$date = date('Y-m-d H:i:s');
	$comments = addslashes("<span style='color:#FF0000;'>".$_POST['history']."</span>");
	$date2 = date('Y-m-d');
	
	$root = array('RootCause' => $_POST['root-cause']);
	dbUpdate('tbl_jc',$root,$where_clause="JobId = '". $jobid ."'",$con);
	
	if(!empty($_POST['history'])){
		
		mysqli_query($con, "INSERT INTO tbl_actual_history (JobId,TechnicianId,Date,Comments,Mobile,Type) VALUES ('$jobid','$technicianid','$date','$comments','1','JC')")or die(mysqli_error($con));
		
		mysqli_query($con, "UPDATE tbl_history_alerts SET Date = '$date2', OnHold = '0' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	}
	
	for($i=0;$i<=count($_POST['check-id']);$i++){

		$form_data = array(
		  
		  'Comments' => $_POST['comments'][$i],
		  'Checked' => $_POST['checked-'.$i],
		);

		$form_data2 = array(
		  
		  'ManagerPosition' => $_POST['manager']
		);
		
		
		dbUpdate('tbl_sm_checklist_relation', $form_data, $where_clause="Id = '". $_POST['check-id'][$i] ."'",$con);
	}
	
}

// Facility Manager Request
if(isset($_GET['ManagerRequest'])){
	
	$query_site = "
		SELECT
			tbl_jc.JobNumber,
			tbl_jc.CompanyId,
			tbl_jc.SiteId,
			tbl_sites.`Name` AS SiteName,
			tbl_companies.`Name` AS CompanyName
		FROM
			tbl_jc
		INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
		INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
		WHERE
			tbl_jc.JobId = '$jobid'";
			
	$query_site = mysqli_query($con, $query_site)or die(mysqli_fetch_array($query));
	$row_site = mysqli_fetch_array($query_site);
	
	$query = mysqli_query($con, "SELECT * FROM tbl_facility_manager_request WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	$form_data = array(
		
		'JobId'			=> $jobid,
		'DateLogged' 	=> date('Y-m-d'),
		'OilCompany' 	=> $row_site['CompanyName'],
		'SiteName' 		=> $row_site['SiteName']
	);
	
	$form_data2 = array(
		
		'JobId'			=> $jobid
	);
	
	if(mysqli_num_rows($query) == 0){
			
		dbInsert('tbl_facility_manager_request', $form_data, $con);	
		dbInsert('tbl_facility_manager_details', $form_data2, $con);	
	
	} else {
		
		dbInsert('tbl_facility_manager_details', $form_data2, $con);
	}
	
	header('Location: history.php?Id='. $jobid .'&Cat=Request');	

}

if(isset($_POST)){
	
	for($i=0;$i<count($_POST['manager']);$i++){

		$form_data = array(
		  
		  'ManagerPosition' => $_POST['manager'][$i]
		);
		
		dbUpdate('tbl_facility_manager_request', $form_data, $where_clause="JobId = '". $jobid ."'",$con);
		
		$requestid = $_POST['requestid'][$i];
		$inscope = '0';
		$outscope = '0';
		$painscope = '0';
				
		if($_POST['scope'.$i] == 'In Scope'){
			
			$inscope = '1';
		}
		
		if($_POST['scope'.$i] == 'Out of Scope'){
			
			$outscope = '1';
		}
		
		if($_POST['scope'.$i] == 'PA Out of Scope'){
			
			$painscope = '1';
		}
		
		$form_data2 = array(
		  
		  'Request' => $_POST['request'][$i],
		  'InScope' => $inscope,
		  'OutScope' => $outscope,
		  'PaScope' => $painscope,
		  'Comments' => $_POST['comments2'][$i],
		);
		
		dbUpdate('tbl_facility_manager_details', $form_data2, $where_clause="Id = '". $requestid ."'",$con);
	}
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_facility_manager_details', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	$query = mysqli_query($con, "SELECT * FROM tbl_facility_manager_details WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	if(mysqli_num_rows($query) == 0){
		
		dbDelete('tbl_facility_manager_request', $where_clause="JobId = '". $jobid ."'",$con);
	}
}
// End Facility Manager Request

if(isset($_POST['costing'])){
	
	select_db();
	
	$jobid = $_GET['Id'];
	
		
	$complete = addslashes("<span style='color:#FF0000;'>".$_POST['history'] . " Job is complete</span>");
	
	mysqli_query($con, "UPDATE tbl_jc SET CommentText = '$complete' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	mysqli_query($con, "UPDATE tbl_scheduled_maintenance SET Status = 'Complete' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	header('Location: history.php?costing');
	
}

if(isset($_POST['onhold'])){
	
	select_db();
	
	$jobid = $_GET['Id'];
		
	mysqli_query($con, "UPDATE tbl_history_alerts SET OnHold = '1' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	
	header('Location: history.php?on-hold');
	
}

if(isset($_FILES['photo']['name'])){
	
	$target_path = "../../images/history/";
	
	$target_path = $target_path . basename( $_FILES['photo']['name']); 
	
	if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
		
		$file_attachment = $_FILES['photo']['name'];
		$ext = explode(".", $file_attachment);
		$extension = $ext[1];
		
		$image = rename('../../images/history/'.$file_attachment, '../../images/history/'. $_GET['Id'] .'-'. date('H-i-s') .'.'. $extension);
		$image_name = $_GET['Id'] .'-'. date('H-i-s') .'.'. $extension;
		
		mysqli_query($con, "INSERT INTO tbl_history_photos (Photo) VALUES ('$image_name')")or die(mysqli_error($con));
		
		$query = mysqli_query($con, "SELECT * FROM tbl_history_photos ORDER BY Id DESC")or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		
		$photoid = $row['Id'];
		$jobid = $_GET['Id'];
		
		mysqli_query($con, "INSERT INTO tbl_history_relation (JobId,PhotoId) VALUES ('$jobid','$photoid')")or die(mysqli_error($con));
		
		createThumbs('../../images/history/','../../images/history/thumbnails/',100,$_FILES['photo']['name']);
	}
}


$colname_Recordset1 = "-1";
if (isset($_SESSION['userid'])) {
  $colname_Recordset1 = $_SESSION['userid'];
}
$query_Recordset1 = "SELECT * FROM tbl_history_alerts WHERE TechnicianId = '$colname_Recordset1'";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = $_GET['Id'];
}
$query_Recordset2 = "SELECT * FROM tbl_jc WHERE JobId = '$colname_Recordset2' AND Comment = '1'";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error());
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$jobid = $_GET['Id'];

$query_history = "
	SELECT
	tbl_technicians.
	`Name`
	AS Name_1,
	tbl_actual_history.JobId,
		tbl_users.
	`Name`,
	tbl_actual_history.Date,
		tbl_actual_history.Comments,
		tbl_actual_history.Mobile
	FROM
		(
			(
				tbl_actual_history LEFT JOIN tbl_users ON tbl_users.Id = tbl_actual_history.TechnicianId
			) LEFT JOIN tbl_technicians ON tbl_technicians.Id = tbl_actual_history.TechnicianId
		)
	WHERE
	tbl_actual_history.JobId = '$jobid'
	ORDER BY
	tbl_actual_history.Id ASC";
	
$query_history = mysqli_query($con, $query_history)or die(mysqli_error($con));

$query_checklist = "
	SELECT
		tbl_sm_cat.Cat,
		tbl_sm_cat.Id AS CatId,
		tbl_sm_assets.Asset,
		tbl_sm_checklist_relation.Id,
		tbl_sm_checklist_relation.JobId,
		tbl_sm_checklist_relation.ListItemId,
		tbl_sm_checklist_relation.Checked,
		tbl_sm_checklist_relation.Comments,
		tbl_sm_checklist_items.ListItem
	FROM
		tbl_sm_cat
	INNER JOIN tbl_sm_assets ON tbl_sm_cat.Id = tbl_sm_assets.CatId
	INNER JOIN tbl_sm_checklist_relation ON tbl_sm_assets.Id = tbl_sm_checklist_relation.AssetId
	INNER JOIN tbl_sm_checklist_items ON tbl_sm_checklist_relation.ListItemId = tbl_sm_checklist_items.Id
	WHERE
		tbl_sm_checklist_relation.JobId = '$jobid'
	ORDER BY
		tbl_sm_cat.Cat ASC,
		tbl_sm_assets.Asset ASC,
		tbl_sm_checklist_items.Id ASC";
		
$query_checklist = mysqli_query($con, $query_checklist)or die(mysqli_error($con));

$query_required = mysqli_query($con, "SELECT * FROM tbl_sm_checklist_relation WHERE JobId = '$jobid' AND Checked = '0'")or die(mysqli_error($con));
$row_required = mysqli_fetch_array($query_required);

$query_request = mysqli_query($con, "SELECT * FROM tbl_facility_manager_request WHERE JobId = '$jobid'")or die(mysqli_error($con));
$row_request = mysqli_fetch_array($query_request);

$query_request_details = mysqli_query($con, "SELECT * FROM tbl_facility_manager_details WHERE JobId = '$jobid'")or die(mysqli_error($con));

$query_root = mysqli_query($con, "SELECT * FROM tbl_root_cause ORDER BY RootCause ASC")or die(mysqli_error($con));

$query_jc = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
$row_jc = mysqli_fetch_array($query_jc);

echo $row_jc['RootCause'] .'xx';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
          "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Sealink</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Include scripts -->
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script type="text/javascript" src="../menu/js/responsivemultimenu.js"></script>

    <!-- Include styles -->
    <link rel="stylesheet" href="../menu/css/responsivemultimenu.css" type="text/css" />

    <!-- Include media queries -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <script type="text/javascript">
        function MM_jumpMenu(targ, selObj, restore) { //v3.0
            eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
            if (restore) selObj.selectedIndex = 0;
        }

        $(document).ready(function () {
            $(".toggler").click(function (e) {
				e.preventDefault();
                $('.row' + $(this).attr('data-prod-cat')).toggle();
            });
        });

    </script>
    
	<script>
      function form_action(param){
      // Check browser support
          if (typeof(Storage) !== "undefined") {
              // Store
              localStorage.setItem("param", param);
              // Retrieve
              document.fm_form.action = localStorage.getItem("param");
              
          }
      }
	  
      function delete_url(del_url){
      // Check browser support
          if (typeof(Storage) !== "undefined") {
              // Store
              localStorage.setItem("del_url", del_url);
              // Retrieve
              document.fm_form.action = localStorage.getItem("del_url");
              
          }
      }
    </script>

    <link href="../../css/mobile.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="../../form-validation/css/normalize.css">
    <link rel="stylesheet" href="../../form-validation/css/style.css">
    
</head>
<body id="site">

    <div id="wrapper">

        <div id="content">
            <form action="history.php<?php if(isset($_GET['Id'])){ ?>?Id=<?php echo $_GET['Id']; } ?><?php if(isset($_GET['Cat'])){ ?>&Cat=<?php echo $_GET['Cat']; } ?>" method="post" enctype="multipart/form-data" id="fm_form" name="fm_form"  class="uk-form bt-flabels js-flabels" data-parsley-validate data-parsley-errors-messages-disabled>
                <?php include('../menu/menu.php'); ?>

                <select name="jumpMenu" class="tfield" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)" style="width:100%">
                    <option value="history.php" <?php if($_GET['Id'] == $row_Recordset1['JobId']) {echo "selected=\"selected\"";} ?>>Job Number</option>
                    <?php
                    do {
						
						$description = '';
						
						if(!empty($row_Recordset1['Description'])){
							
							$description = ' - '. $row_Recordset1['Description'];
						}
						
						$display = 'Yes';
						
						if(!empty($row_Recordset1['Description'])){
							
							if($row_Recordset1['Date'] > date('Y-m-d')){
								
								$display = 'No';
							}
						}
						
						if($display == 'Yes'){
							
							?>
                            
                            <option value="history.php?Id=<?php echo $row_Recordset1['JobId']?>"<?php if($_GET['Id'] == $row_Recordset1['JobId']) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['JobNo'] .' '. stripslashes($row_Recordset1['Site']) . $description; ?></option>
                    <?php
						}
					} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
                    $rows = mysqli_num_rows($Recordset1);
                    if($rows > 0) {
						mysqli_data_seek($Recordset1, 0);
                    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
                    }

                    ?>
                </select>
                
                <!-- Root Cause -->
                <?php if($row_Recordset2['SlaCatId'] == 5){ ?>
                <div class="bt-flabels__wrapper">
                  <select name="root-cause" id="root-cause" class="tfield"  style="width:100%" autocomplete="off" data-parsley-required>
                    <option value="">Root Cause</option>
                    <?php while($row_root = mysqli_fetch_array($query_root)){ ?>
                      <option value="<?php echo $row_root['Id']; ?>" <?php if($row_root['Id'] == $row_jc['RootCause']){ echo 'selected="selected"'; } ?>><?php echo $row_root['RootCause']; ?></option>
                    <?php } ?>
                  </select>
                  <span class="bt-flabels__error-desc-dd" style="top:9px">Required</span>
                </div>
                <?php } ?>
                <!-- End Root Cause -->

                <?php if(isset($_GET['Id'])){ ?>

                <div class="btn-flat" tabindex="0" style="width:calc(100% - 20px)"><a href="#" class="toggler sm-bar" data-prod-cat="1">View History</a></div>

                    <div class="row1 tfield" align="left" style="margin-bottom:5px; display:none">
                        <?php
                        while($row_history = mysqli_fetch_array($query_history)){

							echo '<span class="history-bg-con" style="visibility: visible">
                              <span class="history-bg">
								'.
								$row_history['Name'] .' '. $row_history['Date'] .'
							</span> '. $row_history['Comments'] .'
                            </span>';
							}
                        ?>
                    </div>

                <textarea name="history" id="history" cols="45" class="tfield"></textarea>
                
                <!-- Checklists -->
                <div id="list-border" style="margin-top:10px">
                    <?php
                    if(mysqli_num_rows($query_checklist) >= 1){

                    ?>
                    <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <?php

                        $z = -1;
						$x = -1;
						$c = -1;
						$_SESSION['cat'] = '';
						$_SESSION['asset'] = '';

                        while($row_checklist = mysqli_fetch_array($query_checklist)){
							
							$x++;
							
                        ?>
                        
							<?php 
							if($row_checklist['Cat'] != $_SESSION['cat']){
								
								$z++;
								
								if($_GET['Cat'] == $row_checklist['CatId']){
									
									$display = 'table-row';
									
								} else {
									
									$display = 'none';
								}
						    ?>
                            <tr>
                              <td colspan="3" class="td-header">
                                <a href="#" class="toggler sm-bar" data-prod-cat="<?php echo $z; ?>" onClick="form_action('history.php<?php if(isset($_GET['Id'])){ ?>?Id=<?php echo $_GET['Id']; } ?>&Cat=<?php echo $row_checklist['CatId']; ?>')"><?php echo $row_checklist['Cat']; ?></a>
                              </td>
                            </tr>
                            <?php } ?>
                            
                            <?php if($row_checklist['Asset'] != $_SESSION['asset']){ ?>
                            <?php $c++; ?>
                                <tr class="row<?php echo $z; ?>" style="display:<?php echo $display; ?>">
                                  <td colspan="3" class="td-sub-header">
								  <a href="#" class="toggler sm-bar" data-prod-cat="2-<?php echo $c; ?>" onClick="form_action('history.php<?php if(isset($_GET['Id'])){ ?>?Id=<?php echo $_GET['Id']; } ?>&Cat=<?php echo $row_checklist['CatId']; ?>')"><?php echo $row_checklist['Asset']; ?></a>
                                  <input name="cat" type="hidden" id="cat" value="ggg"></td>
                                </tr>
                                <tr class="row2-<?php echo $c; ?>" style="display:<?php echo $display; ?>">
                                    <td width="120" class="td-sub-sub-header">Item</td>
                                    <td width="130" align="center" class="td-sub-sub-header">Checked</td>
                                    <td class="td-sub-sub-header">Comments</td>
                                </tr>
                            <?php } ?>
                        
							<?php
                            
                            $_SESSION['cat'] = $row_checklist['Cat'];
                            $_SESSION['asset'] = $row_checklist['Asset'];
                            
                            ?>
                        
                            <tr class="row2-<?php echo $c; ?>" style="display:<?php echo $display; ?>">
                                <td align="left" class="td-left">
                                    <?php echo $row_checklist['ListItem']; ?>
                                </td>
                                <td align="center" class="td-right" style="text-align:center">
    
                                    <table border="0" align="center" cellpadding="0" cellspacing="1">
                                        <tr>
                                            <td><input type="radio" name="checked-<?php echo $x; ?>" value="1" id="checked-1-<?php echo $x; ?>" <?php if($row_checklist['Checked'] == 1){ echo 'checked="checked"'; } ?>></td>
                                            <td><label for="checked-1-<?php echo $x; ?>">Yes</label></td>
                                            <td width="20">&nbsp;</td>
                                            <td><input name="checked-<?php echo $x; ?>" type="radio" id="checked-2-<?php echo $x; ?>" value="0" <?php if($row_checklist['Checked'] == 0){ echo 'checked="checked"'; } ?>></td>
                                            <td><label for="checked-2-<?php echo $x; ?>">No</label></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="td-right">
                                    <textarea name="comments[]" id="comments[]" cols="45" class="tarea-100"><?php echo $row_checklist['Comments']; ?></textarea>
                                    <input name="check-id[]" type="hidden" id="check-id[]" value="<?php echo $row_checklist['Id']; ?>" />
                                </td>
                            </tr>
							<?php } ?>
                    </table>
                </div>
                <!-- End Checklists -->
                
                <!-- Facility Manager Requests -->
                <div id="list-border" style="margin-top:10px">
                
					<?php 
                        
					if($_GET['Cat'] == 'Request'){
						
						$display = 'table-row';
						
					} else {
						
						$display = 'none';
					}
					
                    ?>
                
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                            <tr>
                              <td colspan="5" class="td-header">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td><a href="#" class="toggler sm-bar" data-prod-cat="99" onClick="form_action('history.php<?php if(isset($_GET['Id'])){ ?>?Id=<?php echo $_GET['Id']; } ?>&Cat=Request')">Facility Manager Request</a></td>
                                    <td width="20" align="center"><a href="history.php?Id=<?php echo $_GET['Id']; ?>&ManagerRequest" class="add-sign">+</a></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <?php if(mysqli_num_rows($query_request) >= 1){ ?>
                            <?php 
							
							$a = -1;
							
							while($row_request_details = mysqli_fetch_array($query_request_details)){
								
								$a++; 
							
							?>
                                <tr class="row99" style="display:<?php echo $display; ?>">
                                  <td class="td-sub-header2">Facility Manager Position</td>
                                  <td width="120" align="center" class="td-sub-header2">In Scope</td>
                                  <td width="120" align="center" class="td-sub-header2">Out of Scope</td>
                                  <td width="120" align="center" class="td-sub-header2">PA Out of Scope</td>
                                  <td width="20" align="center" class="td-sub-header2">&nbsp;</td>
                                </tr>
                                <tr class="row99" style="display:<?php echo $display; ?>">
                                    <td class="td-right"><input name="manager[]" type="text" class="tarea-100" id="manager[]" value="<?php echo $row_request['ManagerPosition']; ?>"></td>
                                    <td align="center" class="td-right"><input type="radio" name="scope<?php echo $a; ?>" value="In Scope" <?php if($row_request_details['InScope'] == 1){ echo 'checked="checked"'; } ?>></td>
                                    <td align="center" class="td-right"><input type="radio" name="scope<?php echo $a; ?>" value="Out of Scope" <?php if($row_request_details['OutScope'] == 1){ echo 'checked="checked"'; } ?>></td>
                                    <td align="center" class="td-right"><input type="radio" name="scope<?php echo $a; ?>" value="PA Out of Scope" <?php if($row_request_details['PaScope'] == 1){ echo 'checked="checked"'; } ?>></td>
                                    <td align="center" class="td-right"><input name="requestid[]" type="hidden" id="requestid[]" value="<?php echo $row_request_details['Id']; ?>"></td>
                    </tr>
                            <tr class="row99" style="display:<?php echo $display; ?>">
                                <td colspan="4" align="left" class="td-sub-header">Request</td>
                                <td align="center" class="td-sub-header"><a href="history.php?Id=<?php echo $_GET['Id']; ?>&Delete=<?php echo $row_request_details['Id']; ?>&Cat=Request" class="add-sign">-</a></td>
                            </tr>
                            <tr class="row99" style="display:<?php echo $display; ?>">
                              <td colspan="5" align="left" class="td-right"><textarea name="request[]" rows="5" class="tarea-100" id="request[]"><?php echo $row_request_details['Request']; ?></textarea></td>
                            </tr>
                            <tr class="row99" style="display:<?php echo $display; ?>">
                              <td colspan="5" align="left" class="td-sub-sub-header">Comments</td>
                            </tr>
                            <tr class="row99" style="display:<?php echo $display; ?>">
                              <td colspan="5" align="left" class="td-right">
                                <textarea name="comments2[]" rows="5" class="tarea-100" id="comments2[]"><?php echo $row_request_details['Comments']; ?></textarea>
                              </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                  </table>
              </div>
                <!-- End Facility Manager Requests -->

                <?php } ?>

                <div class="fileUpload btn btn-primary">
                    <span>Upload Image</span>
                    <input name="photo" type="file" class="upload" id="photo" style="width:calc(100% - 20px)" />
                </div>

                <input class="btn-flat" name="Submit" value="Update History" type="submit" />
                <input class="btn-flat" name="costing" value="Job Complete" type="submit" />
                <input class="btn-flat" name="onhold" value="Put Job on Hold" type="submit" />

                </p>


                <?php } elseif(isset($_GET['costing'])){ ?>
                <p>
                    <div align="center">Job card sent to costing.</div>
                </p>
                <?php } elseif(isset($_GET['on-hold'])){ ?>
                <p>
                    <div align="center">Job is on hold.</div>
                </p>
                <?php } else { ?>
                <p>
                    <div align="center">Please select a job from the list above.</div>
                </p>
                <?php } ?>


            </form>
        </div><!--content-->
    </div><!--end wrapper-->
    
  <script src='https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.1.2/parsley.min.js'></script>
  <script src="../../form-validation/js/index.js"></script>

</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);
?>
