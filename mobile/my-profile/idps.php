<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$userid = $_SESSION['userid'];
$edit = $_GET['Edit'];

if(!isset($_SESSION['userid'])){
	
	header('Location: ../index.php');
	
	exit();
}

$form_data = array(
	
	'TechId' => $userid,
	'DateConducted' => $_POST['date-conducted'],
	'NextExaminationDate' => $_POST['date-next'],
	'Practitioner' => $_POST['practitioner'],
	'MedicalStatus' => $_POST['status'],
	
	);

if(isset($_POST['insert'])){
	
	dbInsert('tbl_profile_medicals', $form_data, $con);
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_medicals', $form_data, $where_clause="Id = '". $edit ."'",$con);
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_profile_medicals', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: medicals.php');
	
	exit();
}

$query_list = mysqli_query($con, "SELECT * FROM tbl_profile_idps WHERE TechId = '$userid' ORDER BY AssessmentDates ASC")or die(mysqli_error($con));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Sealink</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Include scripts -->
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript" src="../menu/js/responsivemultimenu.js"></script>
    
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
    
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>
    
    <script type="text/javascript">

        $(document).ready(function () {
            $(".toggler").click(function (e) {
				e.preventDefault();
                $('.row' + $(this).attr('data-prod-cat')).toggle();
            });
        });

    </script>

    <!-- Include styles -->
    <link rel="stylesheet" href="../menu/css/responsivemultimenu.css" type="text/css" />

    <!-- Include media queries -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href="../../SpryAssets/SpryCollapsiblePanel4.css" rel="stylesheet" type="text/css" />
    <link href="../../css/mobile.css" rel="stylesheet" type="text/css" />

</head>
<body id="site">

    <div id="wrapper">

        <div id="content">
        
        <?php include('../menu/menu.php'); ?>

            <form action="" method="post" id="form-1">
            
            <div style="width:100%">
                
            <!-- Logged Medical Examinations -->
            <?php if(mysqli_num_rows($query_list) >= 1){ ?>
                <div id="list-border" style="margin-top:30px">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr>
                            <td colspan="3" class="td-header">
                              Logged Medical Examinations
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" class="td-sub-header2">Training Needed</td>
                            <td width="15" class="td-sub-header2 td-header-no-padding">&nbsp;</td>
                          </tr>
                          <?php
                          
                          $i = 0;
                          
                          while($row_list = mysqli_fetch_array($query_list)){
                              
                              $i++;
                          ?>
                              <tr>
                                <td colspan="2" class="td-right"><?php echo $row_list['TrainingNeeded']; ?></td>
                                <td class="td-right"><a href="#" class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                              </tr>
                              <tr class="row<?php echo $i; ?>" style="display: none">
                                <td width="130" class="td-left">Facilitation</td>
                                <td colspan="2" class="td-right"><?php echo $row_list['Facilitation']; ?></td>
                              </tr>
                              <tr class="row<?php echo $i; ?>" style="display: none">
                                <td class="td-left">Planned Asmt. Date</td>
                                <td colspan="2" class="td-right"><?php echo $row_list['AssessmentDates']; ?></td>
                              </tr>
                              <tr class="row<?php echo $i; ?>" style="display: none">
                                <td class="td-left">Progress</td>
                                <td colspan="2" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['Progress']); ?></td>
                              </tr>
                              <tr class="row<?php echo $i; ?>" style="display: none">
                                <td class="td-left">Remarks</td>
                                <td colspan="2" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['Remarks']); ?></td>
                              </tr>
                          <?php } ?>
                    </table>
                </div>
            <?php } ?>
            <!-- End Logged Medical Examinations -->
                            
            </div>
            </form>
    </div>
    </div>
</body>
</html>








