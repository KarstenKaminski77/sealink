<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

logout($con);

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

$where = "WHERE 1=1";

if(!empty($_POST['jobno'])){
	
	$where .= " AND tbl_jc.JobNo LIKE '%". $_POST['jobno'] ."%'";
}

if(!empty($_POST['name'])){
	
	$where .= " AND tbl_sites.Name = '". $_POST['name'] ."'";
}

if(!empty($_POST['type'])){
	
	$where .= " AND tbl_scheduled_maintenance.Description = '". $_POST['type'] ."'";
}

if(!empty($_POST['quarter'])){
	
	$where .= " AND tbl_scheduled_maintenance.Quarter = '". $_POST['quarter'] ."'";
}

if(!empty($_POST['tech'])){
	
	$where .= " AND tbl_scheduled_maintenance.TechnicianId = '". $_POST['tech'] ."'";
}

if(!empty($_POST['date'])){
	
	$where .= " AND tbl_scheduled_maintenance.Date = '". $_POST['date'] ."'";
}

$query_Recordset3 = "
	SELECT
		tbl_scheduled_maintenance.Id,
		tbl_scheduled_maintenance.TechnicianId,
		tbl_scheduled_maintenance.JobId,
		tbl_scheduled_maintenance.Description,
		tbl_scheduled_maintenance.`Quarter`,
		tbl_scheduled_maintenance.Date,
		tbl_scheduled_maintenance.`Status`,
		tbl_jc.JobId,
		tbl_jc.JobNo,
		tbl_scheduled_maintenance.SiteId,
		tbl_sites.`Name`
	FROM
		tbl_scheduled_maintenance
	INNER JOIN tbl_jc ON tbl_scheduled_maintenance.JobId = tbl_jc.JobId
	INNER JOIN tbl_sites ON tbl_scheduled_maintenance.SiteId = tbl_sites.Id
	    $where
	AND
		tbl_scheduled_maintenance.`Status` = 'Qued'
	ORDER BY
		tbl_scheduled_maintenance.Date ASC";
	
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_tech = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />

    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>

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
            <li><a href="#">Qued</a></li>
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
    <div id="main-wrapper">
        <form name="form2" method="post" action="">
            <div id="list-border">
                <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                    <tr>
                        <td class="td-header" width="80" align="left" nowrap><strong>Job Card </strong></td>
                        <td class="td-header" align="left"><strong>Site</strong></td>
                        <td width="120" align="left" class="td-header"><strong>Maintenance </strong></td>
                        <td width="120" align="left" class="td-header">Technician</td>
                        <td width="120" align="left" class="td-header">Date</td>
                        <td width="40" align="center" class="td-header">Quarter</td>
                        <td width="20" class="td-header-right">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="td-search" align="left" nowrap><input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $_POST['jobno']; ?>" style="color:#19446f" /></td>
                        <td class="td-search" align="left" nowrap="nowrap"><input name="name" type="text" class="tarea-100" id="name" value="<?php echo $_POST['name']; ?>" style="color:#19446f" /></td>
                        <td class="td-search" align="left">
                            <select name="type" class="tarea-100" id="type" style="color:#19446f">
                                <option value="">Select one...</option>
                                <option value="HVAC" <?php if($_POST['type'] == 'HVAC'){ echo 'selected="selected"'; } ?>>HVAC</option>
                                <option value="General Building"<?php if($_POST['type'] == 'General Building'){ echo 'selected="selected"'; } ?>>General Building</option>
                            </select>
                        </td>
                        <td align="left" class="td-search">
                            <select name="tech" class="tarea-100" id="tech" style="color:#19446f">
                                <option value="">Select one...</option>
                                <?php while($row_tech = mysqli_fetch_array($query_tech)){ ?>
                                <option value="<?php echo $row_tech['Id']; ?>" <?php if($row_tech['Id'] == $_POST['tech']){ echo 'selected="selected"'; } ?>><?php echo $row_tech['Name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td align="left" class="td-search">

                            <input name="date" class="tarea-100" id="date" value="<?php echo $date2; ?>" style="color:#19446f" />
                            <script type="text/javascript">
                                $('#date').datepicker({
                                    dateFormat: "yy-mm-dd"
                                });
                            </script>

                        </td>
                        <td align="left" class="td-search">
                            <select name="quarter" class="tarea-100" id="quarter" style="color:#19446f">
                                <option value="">Select one...</option>
                                <option value="1" <?php if($row_form['Quarter'] == '1'){ echo 'selected="selected"'; } ?>>First Quarter</option>
                                <option value="2" <?php if($row_form['Quarter'] == '2'){ echo 'selected="selected"'; } ?>>Second Quarter</option>
                                <option value="3" <?php if($row_form['Quarter'] == '3'){ echo 'selected="selected"'; } ?>>Third Quarter</option>
                                <option value="4" <?php if($row_form['Quarter'] == '4'){ echo 'selected="selected"'; } ?>>Fourth Quarter</option>
                            </select>
                        </td>
                        <td align="left" class="td-search" style="padding:0"><input name="search-2" type="submit" class="btn-search-small" id="search-2" value="" /></td>
                    </tr>

                    <?php $x = 0; ?>
                    <?php  if($totalRows_Recordset3 >= 1){ ?>
                    <?php  do { ?>
                    <?php $x++; ?>
                    <?php $jobid = $row_Recordset3['JobId']; ?>

                    <tr class="<?php echo ($ac_sw1++%2==0)?" odd":"even"; ?>
                        " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                        <td align="left" nowrap><?php  echo $row_Recordset3['JobNo']; ?></td>
                        <td align="left" nowrap><?php  echo $row_Recordset3['Name']; ?></td>
                        <td align="left" nowrap><?php  echo $row_Recordset3['Description']; ?></td>
                        <td align="left" nowrap><?php  tech_name($con,$row_Recordset3['TechnicianId']); ?></td>
                        <td align="left" nowrap><?php  echo $row_Recordset3['Date']; ?></td>
                        <td align="center" nowrap><?php  echo $row_Recordset3['Quarter']; ?></td>
                        <td align="center" nowrap><a href="#" class="toggler expand" data-prod-cat="<?php echo $x; ?>"></a></td>
                    </tr>
                    <tr class="cat<?php echo $x; ?>" style="display:none">
                        <td colspan="7" align="left" nowrap class="td-right">&nbsp;</td>
                    </tr>
                    <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                    <?php  } ?>
                </table>
            </div>
        </form>
    </div>
    <!-- End Main Form -->
    
    <!-- Footer -->
    <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
    <!-- End Footer -->

</body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($query_tech);
  mysqli_free_result($area);
  mysqli_free_result($query);
  mysqli_free_result($Recordset3);
?>
