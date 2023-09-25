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

$query_form = "
	SELECT
		tbl_profile_medicals.Id,
		tbl_profile_medicals.TechId,
		tbl_profile_medicals.DateConducted,
		tbl_profile_medicals.NextExaminationDate,
		tbl_profile_medicals.Practitioner,
		tbl_profile_medicals.MedicalStatus,
		tbl_technicians.`Name`
	FROM
		tbl_profile_medicals
	INNER JOIN tbl_technicians ON tbl_profile_medicals.TechId = tbl_technicians.Id
	WHERE tbl_profile_medicals.TechId = '$userid'";

$query_form = mysqli_query($con, $query_form)or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_profile_medicals.Id,
		tbl_profile_medicals.TechId,
		tbl_profile_medicals.DateConducted,
		tbl_profile_medicals.NextExaminationDate,
		tbl_profile_medicals.Practitioner,
		tbl_profile_medicals.MedicalStatus,
		tbl_profile_medicals.Certificate,
		tbl_technicians.`Name`
	FROM
		tbl_profile_medicals
	INNER JOIN tbl_technicians ON tbl_profile_medicals.TechId = tbl_technicians.Id
	ORDER BY tbl_profile_medicals.NextExaminationDate DESC";

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
                                <td colspan="5" class="td-header">
                                  Logged Medical Examinations
                                </td>
                              </tr>
                              <tr>
                                <td width="130" class="td-sub-header2">Date Conducted</td>
                                <td width="130" class="td-sub-header2">Next Examination</td>
                                <td class="td-sub-header2">Medical Status</td>
                                <td width="15" class="td-sub-header2 td-header-no-padding">&nbsp;</td>
                                <td width="15" class="td-sub-header2 td-header-no-padding">&nbsp;</td>
                              </tr>
                              <?php
							  
							  $i = 0;
							  
							  while($row_list = mysqli_fetch_array($query_list)){
								  
								  $i++;
							  ?>
                                  <tr>
                                    <td width="130" class="td-right"><?php echo $row_list['DateConducted']; ?></td>
                                    <td width="130" class="td-right"><?php echo $row_list['NextExaminationDate']; ?></td>
                                    <td class="td-right"><?php echo $row_list['MedicalStatus']; ?></td>
                                    <td class="td-right"><a href="../../technicians/profiles/medicals/<?php echo $row_list['Certificate']; ?>" target="_blank" class="icon-pdf"></a></td>
                                    <td class="td-right"><a href="#" class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td colspan="5" class="td-right-odd"><?php echo $row_list['Practitioner']; ?></td>
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








