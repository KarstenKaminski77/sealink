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

if(isset($_POST['reset'])){
	
	header('Location: achievements.php');
	
	exit();
}

$form_data = array(
	
	'TechId' => $userid,
	'Date' => $_POST['date'],
	'Achievement' => $_POST['achievement'],
	'Details' => $_POST['details'],
	'FinalComment' => $_POST['comments'],
	
	);

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_achievements', $form_data, $where_clause="Id = '". $edit ."'",$con);
}

if(isset($_POST['insert'])){
	
	dbInsert('tbl_profile_achievements', $form_data, $con);
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_achievements WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_profile_achievements.Id,
		tbl_profile_achievements.TechId,
		tbl_profile_achievements.Date,
		tbl_profile_achievements.Achievement,
		tbl_profile_achievements.Details,
		tbl_profile_achievements.FinalComment,
		tbl_technicians.`Name`,
		tbl_users.`Name` AS Creator,
		tbl_profile_achievements.CreatedBy
	FROM
		tbl_profile_achievements
	INNER JOIN tbl_technicians ON tbl_profile_achievements.TechId = tbl_technicians.Id
	INNER JOIN tbl_users ON tbl_profile_achievements.CreatedBy = tbl_users.Id
	WHERE
		tbl_profile_achievements.TechId = '$userid'
	ORDER BY
		tbl_profile_achievements.Date DESC";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Sealink</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Include scripts -->
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript" src="../menu/js/responsivemultimenu.js"></script>
    
	<script type="text/javascript">

        $(document).ready(function () {
            $(".toggler").click(function (e) {
                e.preventDefault();
                $('.row' + $(this).attr('data-prod-cat')).toggle();
            });
        });

    </script>
    
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
    
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>
    
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
                                <td colspan="3" class="td-header">Logged Achievements</td>
                              </tr>
                              <tr>
                                <td width="130" class="td-sub-header2">Date</td>
                                <td class="td-sub-header2">Task being performed</td>
                                <td class="td-sub-header2">&nbsp;</td>
                              </tr>
                                  
								  <?php
                                  
                                  $i = 0;
                                  
                                  while($row_list = mysqli_fetch_array($query_list)){
                                      
                                      $i++;
                                  ?>
                                  
                                  <tr>
                                    <td class="td-sub-sub-header"><?php echo $row_list['Date']; ?></td>
                                    <td class="td-sub-sub-header"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['Achievement']); ?></td>
                                    <td width="25" class="td-sub-sub-header td-header-no-padding"><a href="#" class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td width="130" class="td-left">Created By</td>
                                    <td colspan="2" class="td-right"><?php echo $row_list['Creator']; ?></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td class="td-left">Details of Achievement</td>
                                    <td colspan="2" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['Details']); ?></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td class="td-left">Final Comment</td>
                                    <td colspan="2" class="td-right"><?php echo str_replace(array('<p>','</p>'), array('', '<br>'), $row_list['FinalComment']); ?></td>
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








