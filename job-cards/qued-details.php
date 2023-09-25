<?php
session_start();
require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

$today = date('Y-m-j');
$jobid = $_GET['Id'];

$query_jc = "
	SELECT
		tbl_jc.*,
		tbl_sla_history.*,
		tbl_companies.`Name` AS CompanyName,
		tbl_sites.`Name` AS SiteName
	FROM
		tbl_jc
	INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
	INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
	INNER JOIN tbl_sla_history ON tbl_jc.JobId = tbl_sla_history.JobId
	WHERE
		tbl_jc.JobId = '$jobid'";

$query_jc = mysqli_query($con, $query_jc) or die(mysqli_error($con));
$row_jc = mysqli_fetch_array($query_jc);

$jobno = $row_jc['JobNo'];

// Update Status
$status_data = array(

  'Status' => $_GET['Status'],
  'Days' => $today
);

if (isset($_GET['Status'])) {

  dbUpdate('tbl_jc', $status_data, $where_clause = "JobId = '" . $_GET['Id'] . "'", $con);
}

if (isset($_POST['update']) || isset($_FILES['pdf']['name'])) {
  // General Update
  $jc_data = array(

    'JobNo' => $_POST['jobno'],
    'ContractorId' => $_POST['contractor'],
    'Reference' => $_POST['reference'],
    'Date1' => $_POST['date1'],
    'Date2' => $_POST['date2'],
  );

  if (isset($_POST['system'])) {
    $jc_data['SystemId'] = (int)$_POST['system'];
  }

  if (isset($_POST['sla'])) {
    $jc_data['SlaCatId'] = (int)$_POST['sla'];
  }

  foreach ($jc_data as $key => $data) {
    if ($data == '') {
      unset($jc_data[$key]);
    }
  }

  if (isset($_POST['risk'])) {
    mysqli_query($con, "UPDATE tbl_far SET RiskType = " . $_POST['risk'] . " WHERE JobNo = '" . $_POST['jobno'] . "'");
  }

  if (isset($_POST['highRisk'])) {
    mysqli_query($con, "UPDATE tbl_far SET RiskClassification = " . $_POST['highRisk'] . " WHERE JobNo = '" . $_POST['jobno'] . "'");
  }

  if (!empty($_POST['first-contact'])) {

    if (!empty($row_jc['FacilityFirstContact'])) {

      $ffc = mysqli_real_escape_string($con, $row_jc['FacilityFirstContact']) . ' ' . addslashes('<b><span class="history-bg-con"><span class="history-bg">' . $_COOKIE['name'] . ':</b></span> ' . $_POST['first-contact'] . '</span>');
    } else {

      $ffc = addslashes('<b><span class="history-bg-con"><span class="history-bg">' . $_COOKIE['name'] . ':</b></span> ' . $_POST['first-contact'] . '<span>');
    }

    $jc_data['FacilityFirstContact'] = $ffc;
  }


  // Upload PDF Job Card
  $target_path = "../jc-pdf/";
  $target_path = $target_path . basename($_FILES['pdf']['name']);

  if (move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)) {

    $jc_data['JobcardPDF'] = $_FILES['pdf']['name'];
  }

  dbUpdate('tbl_jc', $jc_data, $where_clause = "JobId = '" . $_GET['Id'] . "'", $con);
  // End General Update
  header('Location: qued-details.php?Id=' . $_GET['Id']);
}

if (isset($_POST['in-progress'])) {
  // Move To In Progress
  if (isset($_POST['in-progress'])) {

    $_SESSION['errors'] = '';

    if (empty($_POST['first-contact']) && empty($row_jc['FacilityFirstContact'])) {

      $_SESSION['errors'] .= '<div>Facility First Contact Is Empty!</div>';
    } else {

      $words = count(explode(' ', $_POST['first-contact']));

      if ($words < 5 && !empty($_POST['first-contact'])) {

        $_SESSION['errors'] .= "Please enter 5 or more words into Initial First Contact" . "\r\n";
      }
    }

    //    if(empty($_POST['root-cause'])){
    //      
    //      $_SESSION['errors'] .= "Please Select The Root Cause!" . "\r\n";
    //    }
    //    
    if (empty($_SESSION['errors'])) {

      $jc_data = array(
        'Status' => '2',
        'Days' => $today
      );

      foreach ($jc_data as $key => $data) {
        if ($data == '') {
          unset($jc_data[$key]);
        }
      }

      dbUpdate('tbl_jc', $jc_data, $where_clause = "JobId = '" . $_GET['Id'] . "'", $con);

      header('Location: qued.php?JobNo=' . $row_jc['JobNo']);
      exit();
    }
  }
  // End Move To In Progress
  header('Location: qued-details.php?Id=' . $_GET['Id']);
}

if (isset($_POST['import'])) {
  // Import Quote
  if (isset($_POST['import'])) {

    $quoteno = $_POST['quote'];

    quote_import($jobid, $quoteno, $_POST['jobno']);
  }
  // End Import Quote	
  header('Location: qued-details.php?Id=' . $_GET['Id']);
}

$query_status = mysqli_query($con, "SELECT * FROM tbl_status WHERE Id <= 5 OR Id = '17' OR Id = '18' OR Id = '19' OR Id = '20'") or die(mysqli_error($con));


$query_Recordset4 = "
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
	tbl_actual_history.Id ASC ";

$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$company = $_SESSION['company'];
$site = $_SESSION['site'];


$query_Recordset5 = "
	SELECT
		tbl_sites.`Name` AS Name_1,
		tbl_sites.Id AS SiteId,
		tbl_companies.Id AS Id_1,
		tbl_jc.RequestPreWorkPo,
		tbl_companies.`Name`,
		tbl_sites.Company,
		tbl_sites.Site,
		tbl_sites.Address,
		tbl_sites.FirstName,
		tbl_sites.LastName,
		tbl_sites.Telephone,
		tbl_sites.Email,
		tbl_jc.Id,
		tbl_jc.AreaId,
		tbl_jc.JobId,
		tbl_jc.ContractorId,
		tbl_jc.FeedBackTech,
		tbl_jc.FeedBack,
		tbl_jc.FeedBackDate,
		tbl_jc.JobNo,
		tbl_jc.Date,
		tbl_jc.`Status`,
		tbl_jc.JobDescription,
		tbl_jc.JobCardPDF,
		tbl_jc.Progress,
		tbl_jc.Reference,
		tbl_jc.InvoiceNo,
		tbl_jc.QuoteNo,
		tbl_jc.SystemId,
		tbl_status.`Status` AS JobStatus
	FROM
		(
			(
				tbl_jc
				LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
			)
			LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		)
	INNER JOIN tbl_status ON tbl_jc.`Status` = tbl_status.Id
	WHERE
		tbl_jc.JobId = '$jobid'
	ORDER BY
		Id ASC
	LIMIT 1";

$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$areaid = $row_Recordset5['AreaId'];

$Recordset100 = mysqli_query($con, "SELECT * FROM tbl_technicians") or die(mysqli_error($con));
$totalRows_Recordset100 = mysqli_num_rows($Recordset100);

$companyid = $row_Recordset5['Id_1'];

$query_Recordset101 = "
SELECT
tbl_rates.NAME AS Name_1,
    tbl_companies.NAME,
    tbl_rates.Rate,
    tbl_rates.CompanyId
FROM
    (
        tbl_companies LEFT JOIN tbl_rates ON tbl_rates.CompanyId = tbl_companies.Id
    )
WHERE
tbl_rates.CompanyId = '$companyid'
";

$Recordset101 = mysqli_query($con, $query_Recordset101) or die(mysqli_error($con));
$row_Recordset101 = mysqli_fetch_assoc($Recordset101);
$totalRows_Recordset101 = mysqli_num_rows($Recordset101);

$query_41 = "
	SELECT
	tbl_far_risc_classification.Risk,
	tbl_far_high_risk_classification.Risk AS Risk2,
	tbl_far.JobNo,
	tbl_far.RiskType,
	tbl_far.RiskClassification,
	tbl_far.Id,
	tbl_far_risc_classification.Id AS RiskId
	FROM
		tbl_far_risc_classification
	INNER JOIN tbl_far ON tbl_far.RiskType = tbl_far_risc_classification.Id
	INNER JOIN tbl_far_high_risk_classification ON tbl_far.RiskClassification = tbl_far_high_risk_classification.Id
	WHERE
		tbl_far.JobNo = '$jobno'";

$Recordset41 = mysqli_query($con, $query_41) or die(mysqli_error($con));
$row_Recordset41 = mysqli_fetch_assoc($Recordset41);
$totalRows_Recordset41 = mysqli_num_rows($Recordset41);

$query_job_history = "
SELECT
tbl_history_relation.PhotoId,
    tbl_history_photos.Photo,
    tbl_history_relation.JobId
FROM
    (
        tbl_history_relation LEFT JOIN tbl_history_photos ON tbl_history_photos.Id = tbl_history_relation.PhotoId
    )
WHERE
tbl_history_relation.JobId = '$jobid'";

$job_history = mysqli_query($con, $query_job_history) or die(mysqli_error($con));
$row_job_history = mysqli_fetch_assoc($job_history);
$totalRows_job_history = mysqli_num_rows($job_history);

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers ORDER BY Name ASC") or die(mysqli_error($con));
$query_contractor = mysqli_query($con, "SELECT * FROM tbl_users WHERE Contractor = '1'") or die(mysqli_error($con));

$query_sla = "
	SELECT
		tbl_sla_cat.Category
	FROM
		tbl_sla_cat
	INNER JOIN tbl_jc ON tbl_jc.SlaCatId = tbl_sla_cat.Id
	WHERE
		tbl_jc.JobId = '$jobid'";

$query_sla = mysqli_query($con, $query_sla) or die(mysqli_error($con));
$row_sla = mysqli_fetch_array($query_sla);

$query_sla_sub_cat = "
	SELECT
		tbl_sla_subcat.SubCat
	FROM
		tbl_jc
	INNER JOIN tbl_sla_subcat ON tbl_jc.SlaSubCatId = tbl_sla_subcat.Id
	WHERE
		tbl_jc.JobId = '$jobid'";

$query_sla_sub_cat = mysqli_query($con, $query_sla_sub_cat) or die(mysqli_error($con));
$row_sla_sub_cat = mysqli_fetch_array($query_sla_sub_cat);

$query_root = mysqli_query($con, "SELECT * FROM tbl_root_cause ORDER BY RootCause ASC") or die(mysqli_error($con));
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Seavest Asset Management</title>

  <link href="../css/layout.css" rel="stylesheet" type="text/css" />
  <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="countdown/jquery.countdown.css">

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

  <link rel="stylesheet" href="../menu/styles.css">
  <script src="../menu/script.js"></script>

  <script type="text/javascript">
    function MM_jumpMenu(targ, selObj, restore) { //v3.0
      eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
      if (restore) selObj.selectedIndex = 0;
    }

    $(document).ready(function() {
      $(".toggler").click(function(e) {
        e.preventDefault();
        $('.row' + $(this).attr('data-row')).toggle();
      });
    });
  </script>

  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />

  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>

  <script type="text/javascript" src="../highslide/highslide-with-html.js"></script>
  <script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />

  <script type="text/javascript" src="../highslide/highslide.js"></script>
  <script type="text/javascript">
    // hs.graphicsDir = '../highslide/graphics/';
    // hs.outlineType = 'rounded-white';

    // function MM_openBrWindow(theURL, winName, features) { //v2.0
    //   window.open(theURL, winName, features);
    // }

    // function MM_popupMsg(msg) { //v1.0
    //   alert(msg);
    // }
  </script>

  <script src="countdown/jquery.plugin.js"></script>
  <script src="countdown/jquery.countdown.js"></script>

  <!-- Countdown Timer -->
  <?php
  $now = strtotime(date('Y-m-d H:i:s'));
  $to = strtotime($row_jc['Date2']);

  $secs_before = $to - $now;
  $secs_after = $now - $to;

  ?>

  <script>
    $(function() {
      <?php
      if (date('Y-m-d H:i:s') < $row_jc['Date2']) {

        $class = 'bg-blue';
      ?>
        $('#defaultCountdown').countdown({
          until: +<?php echo $secs_before; ?>
        });
      <?php
      } else {

        $class = 'bg-red';

      ?>
        $('#defaultCountdown').countdown({
          since: -<?php echo $secs_after; ?>
        });
      <?php } ?>
      <?php echo $class; ?>
    });
  </script>

  <?php

  if (date('Y-m-d H:i:s') < $row_jc['SlaEnd']) {

    $sla_status = 'REMAINING <br> <span class="stop">Click To Stop</span>';

  ?>

    <style type="text/css">
      #defaultCountdown {
        background: #2b74bc;
        /* Old browsers */
        background: -moz-linear-gradient(top, #2b74bc 0%, #19446f 100%);
        /* FF3.6-15 */
        background: -webkit-linear-gradient(top, #2b74bc 0%, #19446f 100%);
        /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom, #2b74bc 0%, #19446f 100%);
        /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2b74bc', endColorstr='#19446f', GradientType=0);
        /* IE6-9 */

        border: solid 1px #012c58;
        color: #FFF;
      }
    </style>


  <?php

  } else {

    $sla_status = 'EXPIRED <br> <span class="stop">Click To Stop</span>';

  ?>

    <style type="text/css">
      #defaultCountdown {
        background: #ed0000;
        /* Old browsers */
        background: -moz-linear-gradient(top, #ed0000 0%, #c10000 100%);
        /* FF3.6-15 */
        background: -webkit-linear-gradient(top, #ed0000 0%, #c10000 100%);
        /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom, #ed0000 0%, #c10000 100%);
        /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ed0000', endColorstr='#c10000', GradientType=0);
        /* IE6-9 */

        border: solid 1px #840101;
        color: #FFF;
      }
    </style>

  <?php } ?>
  <!-- End Countdown Timer -->

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
  <?php include('../menu/menu.php'); ?>
  <!-- End Navigation -->

  <!-- Breadcrumbs -->
  <div class="td-bread">
    <ul class="breadcrumb">
      <li><a href="#">Seavest Asset Management</a></li>
      <li><a href="#">Scheduled Maintenance</a></li>
      <li><a href="#">Job Cards</a></li>
      <li><a href="#"><?php echo $row_Recordset5['JobStatus']; ?></a></li>
      <li></li>
    </ul>
  </div>
  <!-- End Breadcrumbs -->

  <!-- Search -->
  <div class="search-container">
    <form id="form1" name="form1" method="post" action="search.php">
      <input name="search" type="text" class="search-top" onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" value="Search..." />
      <input name="button" type="submit" class="search-top-btn" id="button" value="" />
    </form>
  </div>
  <!-- End Search -->

  <!-- Main Form -->
  <div id="main-wrapper">
    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">

      <!-- Warning Messages -->
      <?php if (!empty($_SESSION['errors'])) { ?>
        <div id="banner-warning"><?php echo $_SESSION['errors']; ?></div>
      <?php } ?>
      <!-- End Warning Messages -->

      <!-- Job Cared Details -->
      <div id="list-border">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td colspan="7" class="td-header">Job Card</td>
          </tr>
          <tr>
            <td width="90" class="td-left">Oil Company</td>
            <td width="200" class="td-right"><?php echo $row_Recordset5['Name']; ?></td>
            <td width="90" class="td-left">Site</td>
            <td width="200" class="td-right"><?php echo $row_Recordset5['Name_1']; ?></td>
            <td width="90" class="td-left">Job Card No.</td>
            <td colspan="2" class="td-right"><input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $row_Recordset5['JobNo']; ?>" /></td>
          </tr>
          <tr>
            <td class="td-left">Contact</td>
            <td class="td-right"><?php echo $row_Recordset5['FirstName'] . ' ' . $row_Recordset5['LastName']; ?></td>
            <td class="td-left">Address</td>
            <td class="td-right"><?php echo char_limit($row_Recordset5['Address'], 30); ?></td>
            <td class="td-left">Quote No</td>
            <td class="td-right"><input name="quote" type="text" class="tarea-100" id="quote" value="<?php echo $value; ?>" size="18" /></td>
            <td class="td-right"><input name="import" type="submit" class="btn-new-2" id="import" value="Import" /></td>
          </tr>
          <tr>
            <td class="td-left">Telephone</td>
            <td class="td-right"><?php echo $row_Recordset5['Telephone']; ?></td>
            <td class="td-left">Email</td>
            <td class="td-right"><?php echo $row_Recordset5['Email']; ?></td>
            <td class="td-left">Reference</td>
            <td colspan="2" class="td-right">
              <select name="reference" class="tarea-100" id="reference">
                <option value="">Select an Engineer</option>
                <?php while ($row_engineers = mysqli_fetch_array($query_engineers)) { ?>
                  <option value="<?php echo trim($row_engineers['Name']); ?>" <?php if ($row_engineers['Name'] == $row_Recordset5['Reference']) {
                                                                                echo 'selected="selected"';
                                                                              } ?>><?php echo $row_engineers['Name']; ?>
                  <?php } ?>
                  </option>
              </select>
              <?php if (isset($_GET['reference'])) { ?>
                <span class="form_validation_field_error_error_message">Required Field</span>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td class="td-right">Category</td>
            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <select name="system" class="tarea-100" data-parsley-required>
                  <?php
                  $sql = " SELECT Id, Name FROM tbl_systems WHERE Id != 3 ORDER BY Id ASC ";
                  $query_system = mysqli_query($con, $sql) or die(mysqli_error($con));
                  while ($row_system = mysqli_fetch_array($query_system)) { ?>
                    <option value="<?php echo $row_system['Id']; ?>" <?php if ($row_system['Id'] == $row_Recordset5['SystemId']) {
                                                                        echo 'selected="selected"';
                                                                      } ?>><?php echo $row_system['Name']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </td>
            <td class="td-left">Contractor</td>
            <td class="td-right">
              <select name="contractor" class="tarea-100" id="contractor">
                <option value="0">None selected...</option>
                <?php while ($row_contractor = mysqli_fetch_array($query_contractor)) { ?>
                  <option value="<?php echo $row_contractor['Id']; ?>" <?php if ($row_Recordset5['ContractorId'] == $row_contractor['Id']) {
                                                                          echo 'selected="selected"';
                                                                        } ?>><?php echo $row_contractor['Name']; ?></option>
                <?php } ?>
              </select></td>
            <td class="td-left">Job Card PDF</td>
            <td class="td-right">
              <div class="cloak"><input name="pdf" type="file" class="pdf-file-input" id="pdf" /></div>
            </td>
            <td class="td-right">
              <?php if (!empty($row_Recordset5['JobCardPDF'])) { ?>
                <a href="../jc-pdf/<?php echo $row_Recordset5['JobCardPDF']; ?>" target="_blank" class="btn-new-2 btn-new-2-pdf">View</a>
              <?php } ?>
            </td>
          </tr>
        </table>
      </div>
      <!-- End Job Cared Details -->

      <!-- Proactive Risk Assessment -->
      <div id="list-border" style="margin-top:15px">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td colspan="4" class="td-header">Job Type</td>
          </tr>
          <tr>
            <td width="25%" class="td-left">SLA Category</td>
            <td width="25%" class="td-right">
              <select name="sla" class="tarea-100" id="sla" onChange="getSlaSub(this.value);" autocomplete="off" data-parsley-required>
                <?php
                $query_sla_cat = mysqli_query($con, "SELECT * FROM tbl_sla_cat WHERE Module = 'JC' ORDER BY Category ASC") or die(mysqli_error($con));
                while ($row_sla_cat = mysqli_fetch_array($query_sla_cat)) { ?>
                  <option value="<?php echo $row_sla_cat['Id']; ?>" <?php if ($row_sla['Category'] == $row_sla_cat['Category']) {
                                                                      echo 'selected="selected"';
                                                                    } ?>> <?php echo $row_sla_cat['Category']; ?> </option>
                <?php } ?>
              </select>
            </td>
            <td width="25%" class="td-left">SLA Sub Category</td>
            <td width="25%" class="td-right"><?php echo $row_sla_sub_cat['SubCat']; ?></td>
          </tr>

          <?php

          $class = '';

          if ($row_Recordset41['RiskId'] == 3) {

            $class = 'style="color: #FF0000; font-weight:bold"';
          }
          ?>

          <tr>
            <td width="25%" class="td-left"> Risk Classification</td>
            <td width="25%" class="td-right">
              <select name="risk" class="tarea-100" id="risk" <?php echo $class; ?> autocomplete="off" data-parsley-required>
                <?php
                $query_risk = mysqli_query($con, "SELECT Id, Risk FROM tbl_far_risc_classification") or die(mysqli_error($con));
                while ($row_risk = mysqli_fetch_array($query_risk)) { ?>
                  <option value="<?php echo $row_risk['Id']; ?>" <?php if ($row_Recordset41['RiskType'] == $row_risk['Id']) {
                                                                    echo 'selected="selected"';
                                                                  } ?>> <?php echo $row_risk['Risk']; ?> </option>
                <?php } ?>
              </select>
            </td>
            <td width="25%" class="td-left">High risk classification</td>
            <td width="25%" class="td-right">
              <select name="highRisk" class="tarea-100" id="highRisk" <?php echo $class; ?> autocomplete="off" data-parsley-required>
                <?php
                $query_risk = mysqli_query($con, "SELECT Id, Risk FROM tbl_far_high_risk_classification") or die(mysqli_error($con));
                while ($row_risk = mysqli_fetch_array($query_risk)) { ?>
                  <option value="<?php echo $row_risk['Id']; ?>" <?php if ($row_Recordset41['RiskClassification'] == $row_risk['Id']) {
                                                                    echo 'selected="selected"';
                                                                  } ?>> <?php echo $row_risk['Risk']; ?> </option>
                <?php } ?>
              </select>
            </td>
          </tr>
        </table>
      </div>
      <!-- End Proactive Risk Assessment -->

      <!-- Requested Completion Date -->
      <div id="list-border" style="margin-top:15px">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td width="25%" rowspan="2" class="td-right">
              <div id="<?php echo $class; ?>">
                <div id="defaultCountdown"></div>
              </div>
            </td>
            <td width="25%" rowspan="2" class="td-right">
              <div id="defaultCountdown" class="sla-status">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="40" align="center" valign="middle"><?php echo $sla_status; ?></td>
                  </tr>
                </table>
              </div>
            </td>
            <td width="25%" class="td-left">Received Date </td>
            <td width="25%" align="right" class="td-right"><input name="date1" <?php echo $sla; ?> class="tarea-100" id="date1" value="<?php echo $row_jc['Date1']; ?>" />
              <script type="text/javascript">
                $('#date1').datepicker({
                  dateFormat: "yy-mm-dd"
                });
              </script>
            </td>
          </tr>
          <tr>
            <td width="25%" class="td-left">Requested Completion </td>
            <td width="25%" align="right" class="td-right"><input name="date2" <?php echo $sla; ?> class="tarea-100" id="date2" value="<?php echo $row_jc['Date2']; ?>" />
              <script type="text/javascript">
                $('#date2').datepicker({
                  dateFormat: "yy-mm-dd"
                });
              </script>
            </td>
          </tr>
        </table>
      </div>
      <!-- End Requested Completion Date -->

      <!-- Service Requested -->
      <div id="list-border" style="margin-top:15px">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td colspan="7" class="td-header">Service Requested</td>
          </tr>
          <tr>
            <td colspan="7" class="td-right">

              <?php
              echo '<span class="history-bg-con">
                        <span class="history-bg">
                        ' .
                $row_jc['JobDescriptionOperator'] . ' ' . $row_jc['JobDescriptionDate'] . '
                        </span> ' . $row_jc['JobDescription'] . '
                      </span>';
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="7" class="td-right">
              <textarea name="service" rows="4" class="tarea-100" id="service"><?php echo stripslashes($row_jc['JobDescription']); ?></textarea>
            </td>
          </tr>
        </table>
      </div>
      <!-- End Service Requested -->

      <!-- Facility First Contact -->
      <div id="list-border" style="margin-top:15px">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td colspan="7" class="td-header">Facility First Contact</td>
          </tr>

          <?php if (!empty($row_jc['FacilityFirstContact'])) { ?>
            <tr>
              <td colspan="7" class="td-right"><?php echo stripslashes($row_jc['FacilityFirstContact']); ?></td>
            </tr>
          <?php } ?>

          <tr>
            <td colspan="7" class="td-right">
              <textarea name="first-contact" rows="4" class="tarea-100" id="first-contact"></textarea>
            </td>
          </tr>
        </table>
      </div>
      <!-- End Facility First Contact -->

      <!-- Root Cause 
          <div id="list-border" style="margin-top:15px">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td colspan="7" class="td-header">Root Cause</td>
              </tr>
              <tr>
                <td colspan="7" class="td-right">
                  <select name="root-cause" id="root-cause" class="tarea-100">
                    <option value="">Select one...</option>
                    <?php while ($row_root = mysqli_fetch_array($query_root)) { ?>
                      <option value="<?php echo $row_root['Id']; ?>"><?php echo $row_root['RootCause']; ?></option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <!-- End Root Cause -->

      <!-- Buttons -->
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td align="right"><input name="in-progress" type="submit" class="btn-new" id="in-progress" value="In Progress" /> <input name="update" type="submit" class="btn-new" id="update" value="Save" /> </td>
        </tr>
      </table>
      <!-- End Buttons -->

    </form>
  </div>
  <!-- End Main Form -->

  <!-- Footer -->
  <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
  <!-- End Footer -->

</body>

</html>
<?php

unset($_SESSION['feedback']);
unset($_SESSION['services']);

mysqli_close($con);
mysqli_free_result($query_jc);
mysqli_free_result($query);
mysqli_free_result($query1);
mysqli_free_result($query2);
mysqli_free_result($query3);
mysqli_free_result($query_41);
mysqli_free_result($query_contractor);
mysqli_free_result($query_engineers);
mysqli_free_result($query_job_history);
mysqli_free_result($query_Recordset101);
mysqli_free_result($query_Recordset4);
mysqli_free_result($query_Recordset5);
mysqli_free_result($query_status);
mysqli_free_result($query_sum);
mysqli_free_result($query_sla);
mysqli_free_result($query_sla_sub_cat);
?>