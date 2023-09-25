<?php
session_start();
error_reporting(E_ALL);
error_reporting(1);
ini_set('error_reporting', E_ALL);
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

include("../../pChart/pChart/pData.class");
include("../../pChart/pChart/pChart.class");

require_once("../../dropdown/dbcontroller.php");
$db_handle = new DBController();

$query = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$results = $db_handle->runQuery($query);

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset4 = "SELECT Id, Name FROM tbl_companies";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

if (isset($_POST['search'])) {

  $where = "WHERE ";

  foreach ($_POST as $key => $val) {

    if (!empty($val) && $key != 'search' && $key != 'type') {

      if ($key == 'From') {

        $key = 'NewInvoiceDate';
        $sets[] = $key . " >= '" . $val . "'";
      } elseif ($key == 'To') {

        $key = 'NewInvoiceDate';
        $sets[] = $key . " <= '" . $val . "'";
      } else if ($key == 'SlaCatId' && $val == 8) {
      } else if ($key == 'AreaId') {
        if ($val != 5) {
          $key = 'tbl_jc.AreaId';
          $sets[] = $key . " = '" . $val . "'";
        }
      } else if ($key == 'min_amount') {

        $key = 'tbl_jc.Total2';
        $sets[] = $key . " > " . (int) $val . "";
      } else {

        $sets[] = $key . " = '" . $val . "'";
      }
    }
  }

  $where .= implode(' AND ', $sets);

  // Awaiting ON, Invoice Archives, Engen Awaiting ON, Debtors, Awaiting Post Work PO, Total Costing, Rework Archives
  $status = array('9', '10', '11', '12', '17', '18', '21');

  $where .= " AND (Status = '";

  $where .= implode("' OR Status = '", $status);

  $where .= "')";

  if ($_POST) {

    $creator = array(

      // 'SlaCatId' => $_POST['SlaCatId'],
      'SlaSubCatId' => $_POST['SlaSubCatId'],
      'Company' => $_POST['CompanyId'],
      'Site' => $_POST['SiteId'],
      'DateFrom' => $_POST['From'],
      'DateTo' => $_POST['To'],
      'Creator' => $_COOKIE['name'],
      'Date' => date('Y-m-d')
    );

    dbInsert('tbl_stat_reports', $creator, $con);

    // Main List Query
    $query_list = "
		  SELECT
			  tbl_sites.Id AS SiteId,
			  tbl_sites.`Name` AS SiteName,
			  tbl_sites.Address,
			  tbl_companies.Id AS CompanyId,
			  tbl_companies.`Name` AS CompanyName,
			  tbl_jc.Id,
			  tbl_jc.JobNo,
			  tbl_jc.SlaCatId,
			  tbl_jc.NewInvoiceDate AS `Date`,
			  tbl_jc.InvoiceNo,
			  tbl_jc.JobId,
			  tbl_jc.Total2 AS Total,
			  tbl_root_cause.RootCause
		  FROM
			  (
				  (
					  tbl_jc
					  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
				  )
				  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
			  )
		  LEFT JOIN tbl_root_cause ON tbl_jc.RootCause = tbl_root_cause.Id
		  $where
		  GROUP BY
			  tbl_jc.JobId
		  ORDER BY
			  tbl_jc.InvoiceNo DESC";
    // print_r($query_list);
    // die;

    $query_list = mysqli_query($con, $query_list) or die(mysqli_error($con));
    $totalRows_Recordset3 = mysqli_num_rows($query_list);

    $list = array();
    $list_total = 0;

    while ($row_list_result = mysqli_fetch_array($query_list)) {

      $data = array(

        'ReportId' => last_id($con, 'tbl_stat_reports'),
        'InvoiceNo' => $row_list_result['InvoiceNo'],
        'JobNo' => $row_list_result['JobNo'],
        'Company' => $row_list_result['CompanyId'],
        'Site' => $row_list_result['SiteId'],
        'SlaCatId' => $row_list_result['SlaCatId'],
        'SlaSubCatId' => $row_list_result['SlaSubCatId'],
        'RootCause' => $row_list_result['RootCause'],
        'Date' => $row_list_result['Date'],
        'Total' => $row_list_result['Total']
      );

      dbInsert('tbl_stat_report_details', $data, $con);

      array_push($list, $row_list_result);
      $list_total += $row_list_result['Total'];
    }
  }

  // echo '<pre>';
  // print_r($list);
  // die;

  if (mysqli_num_rows($query_list) >= 1) {

    $total = array();
    $root = array();

    $query_image = mysqli_query($con, "SELECT SUM(Total) AS Total, RootCause FROM tbl_stat_report_details WHERE ReportId = '" . last_id($con, 'tbl_stat_reports') . "' GROUP BY RootCause") or die(mysqli_error($con));
    while ($row_image = mysqli_fetch_array($query_image)) {

      array_push($total, $row_image['Total']);
      array_push($root, $row_image['RootCause'] . ' R' . $row_image['Total']);
    }

    // die('here');
    // // Dataset definition 
    // $DataSet = new pData;
    // $DataSet->AddPoint($total,"Serie1");
    // $DataSet->AddPoint($root,"Serie2");
    // $DataSet->AddAllSeries();
    // $DataSet->SetAbsciseLabelSerie("Serie2");

    // // Initialise the graph
    // $Test = new pChart(900,250);
    // $Test->drawFilledRoundedRectangle(7,7,513,343,5,255,255,255);
    // $Test->drawRoundedRectangle(5,5,415,245,5,255,255,255);
    // $Test->createColorGradientPalette(0,76,127,25,163,255,0);

    // // Draw the pie chart
    // $Test->setFontProperties("../../pChart/Fonts/tahoma.ttf",14);
    // $Test->AntialiasQuality = 0;
    // $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),450,130,110,PIE_PERCENTAGE,FALSE,50,20,0);
    // $Test->drawPieLegend(650,15,$DataSet->GetData(),$DataSet->GetDataDescription(),255,255,255);



    // // Write the title
    // $Test->setFontProperties("../../pChart/Fonts/tahoma.ttf",16);
    // $Test->drawTitle(13,40,"Total: R". number_format($list_total,2),255,0,0);

    // $Test->Render("pie-chart-". last_id($con, 'tbl_stat_reports') .".png");
  }
}
// View Main List Query
if (isset($_GET['ViewReport'])) {

  $query_list = "
	SELECT
		tbl_companies.`Name` AS CompanyName,
		tbl_sites.`Name` AS SiteName,
		tbl_stat_report_details.InvoiceNo,
		tbl_stat_report_details.JobNo,
		tbl_sla_cat.Category AS SLA,
		tbl_stat_reports.DateFrom,
		tbl_stat_reports.DateTo,
		tbl_stat_report_details.Date,
		tbl_stat_report_details.Total,
		tbl_stat_report_details.RootCause
	FROM
		tbl_stat_report_details
	LEFT JOIN tbl_sites ON tbl_stat_report_details.Site = tbl_sites.Id
	LEFT JOIN tbl_companies ON tbl_stat_report_details.Company = tbl_companies.Id
	LEFT JOIN tbl_sla_cat ON tbl_stat_report_details.SlaCatId = tbl_sla_cat.Id
	LEFT JOIN tbl_stat_reports ON tbl_stat_reports.Id = tbl_stat_report_details.ReportId
	WHERE
		tbl_stat_report_details.ReportId = '" . $_GET['ViewReport'] . "'";

  $list = array();

  $query_list = mysqli_query($con, $query_list) or die(mysqli_error($con));
  while ($row_list_result = mysqli_fetch_array($query_list)) {

    array_push($list, $row_list_result);
  }
}

//Delete
if (isset($_GET['Delete'])) {

  dbDelete('tbl_stat_reports', "Id = '" . $_GET['Delete'] . "'", $con);
  dbDelete('tbl_stat_report_details', "ReportId = '" . $_GET['Delete'] . "'", $con);
}

// Archive List Query
$limit = 'LIMIT 8';
$rpp = 10; // results per page
$adjacents = 4;

$page = intval($_GET["page"]);

if ($page <= 0) $page = 1;

$reload = $_SERVER['PHP_SELF'];
$where = $_SESSION['where'];

$archive_list = "
  SELECT
	  tbl_stat_reports.Id AS ReportId,
	  tbl_companies.Id,
	  tbl_stat_report_details.JobNo,
	  tbl_stat_reports.Id AS RewportId,
	  tbl_stat_reports.Creator,
	  tbl_companies.`Name` AS CompanyName,
	  tbl_stat_reports.DateFrom,
	  tbl_stat_reports.DateTo,
	  tbl_stat_reports.Date,
	  SUM(tbl_stat_report_details.Total) AS Total,
	  tbl_sla_cat.Category AS SLA
  FROM
	  tbl_stat_reports
  LEFT JOIN tbl_stat_report_details ON tbl_stat_reports.Id = tbl_stat_report_details.ReportId
  LEFT JOIN tbl_sla_cat ON tbl_stat_reports.SlaCatId = tbl_sla_cat.Id
  LEFT JOIN tbl_companies ON tbl_stat_reports.Company = tbl_companies.Id
  WHERE
	  tbl_stat_reports.pdf LIKE 'Seavest%'
  GROUP BY
	  tbl_stat_report_details.ReportId
  ORDER BY tbl_stat_report_details.ReportId DESC";

$archive_list = mysqli_query($con, $archive_list) or die(mysqli_error($con));

$query_bread = "
SELECT
	tbl_stat_reports.Id AS RewportId,
	tbl_companies.`Name` AS CompanyName,
	tbl_stat_reports.DateFrom,
	tbl_stat_reports.DateTo,
	tbl_stat_reports.Date,
	tbl_sla_cat.Category AS SLA,
	tbl_sla_subcat.SubCat
FROM
	tbl_stat_reports
LEFT JOIN tbl_stat_report_details ON tbl_stat_reports.Id = tbl_stat_report_details.ReportId
LEFT JOIN tbl_sla_cat ON tbl_stat_reports.SlaCatId = tbl_sla_cat.Id
LEFT JOIN tbl_companies ON tbl_stat_reports.Company = tbl_companies.Id
LEFT JOIN tbl_sla_subcat ON tbl_stat_reports.SlaSubCatId = tbl_sla_subcat.Id
WHERE
	tbl_stat_reports.Id = '" . $_GET['ViewReport'] . "'";

$query_bread = mysqli_query($con, $query_bread) or die(mysqli_error($con));
$row_bread = mysqli_fetch_array($query_bread);

$query_area = mysqli_query($con, "SELECT * FROM tbl_areas") or die(mysqli_error($con));
$query_cat = mysqli_query($con, "SELECT * FROM tbl_sla_cat") or die(mysqli_error($con));
// $query_cat = mysqli_query($con, "SELECT * FROM tbl_sla_cat WHERE Id = '5' OR Id = '6'")or die(mysqli_error($con));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Seavest Asset Management</title>

  <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
  <link href="../../css/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
  <link href="../../pagination/paginate.css" rel="stylesheet" type="text/css" />


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

  <link rel="stylesheet" href="../../menu/styles.css">
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <script src="../../menu/script.js"></script>

  <!-- Date Picker -->
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>

  <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
  <!-- End Date Picker -->

  <!-- Form Validation -->
  <link rel="stylesheet" href="../../form-validation/css/normalize.css">
  <link rel="stylesheet" href="../../form-validation/css/style.css">
  <!-- End Form Validation -->

  <script>
    function getSites(val) {
      $.ajax({
        type: "POST",
        url: "../../dropdown/get-sites.php",
        data: 'company_id=' + val,
        success: function(data) {
          $("#site-list").html(data);
        }
      });
    }

    function getSlaSub(val) {
      $.ajax({
        type: "POST",
        url: "../../dropdown/get-sla-sub.php",
        data: 'sla_id=' + val,
        success: function(data) {
          $("#sla-list").html(data);

        }
      });
    }
  </script>

  <script type="text/javascript">
    function MM_jumpMenu(targ, selObj, restore) { //v3.0
      eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
      if (restore) selObj.selectedIndex = 0;
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function() {

      $("#checkAll").change(function() {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
      });
    });
  </script>

  <style>
    .td-right-no-padding {
      padding-left: 5px;
      padding-right: 5px;
    }
  </style>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">



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
      <li><a href="#">Reports</a></li>
      <li><a href="#">Job Stats</a></li>
      <li><a href="#">Search</a></li>
      <li></li>
    </ul>
  </div>
  <!-- End Breadcrumbs -->

  <!-- Main Form -->
  <div id="main-wrapper">

    <?php if (isset($_GET['Success'])) { ?>

      <div id="banner-success">Report <?php echo $_GET['Success']; ?> Successfully Created!</div>

    <?php } ?>

    <?php if (isset($_GET['MailSuccess'])) { ?>

      <div id="banner-success">Report <?php echo $_GET['Report']; ?> Successfully Sent!</div>

    <?php } ?>

    <?php if (isset($_GET['Delete'])) { ?>

      <div id="banner-success">Report <?php echo $_GET['Delete']; ?> Successfully Deleted!</div>

    <?php } ?>

    <!-- Search Bar -->
    <form id="form1" name="form1" method="post" action="search.php?Report" class="uk-form bt-flabels js-flabels" data-parsley-validate data-parsley-errors-messages-disabled>

      <div id="list-border" style="margin-bottom:30px">
        <table width="100%" border="0" cellpadding="2" cellspacing="1">
          <tr>

            <td width="171" class="td-right">
              <div class="bt-flabels__wrapper">
                <select name="CompanyId" id="country-list" class="tarea-100" onChange="getSites(this.value);" autocomplete="off" data-parsley-required>
                  <option value="">Oil Company</option>
                  <?php foreach ($results as $company) { ?>
                    <option value="<?php echo $company["Id"]; ?>" <?php if ($company["Id"] == $_POST['CompanyId']) {
                                                                    echo 'selected="selected"';
                                                                  } ?>>
                      <?php echo $company["Name"]; ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>

            <td class="td-right">
              <!-- <div class="bt-flabels__wrapper">
            <select name="SiteId" id="site-list" class="tarea-100">
              <option value="">Site...</option>
            </select>
            <span class="bt-flabels__error-desc-dd">Required</span>
          </div> -->
              <div class="bt-flabels__wrapper">
                <select name="AreaId" class="tarea-100" id="area" autocomplete="off" data-parsley-required>
                  <option value="">Area</option>
                  <?php while ($row_area = mysqli_fetch_array($query_area)) { ?>
                    <option value="<?php echo $row_area['Id']; ?>" <?php if ($_POST['AreaId'] == $row_area['Id']) {
                                                                      echo 'selected="selected"';
                                                                    } ?>>
                      <?php echo $row_area['Area']; ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>

          </tr>

          <tr>

            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <select name="SlaCatId" class="tarea-100" id="sla" onChange="getSlaSub(this.value);" autocomplete="off" data-parsley-required>
                  <option value="">Category</option>
                  <?php while ($row_cat = mysqli_fetch_array($query_cat)) { ?>
                    <option value="<?php echo $row_cat['Id']; ?>" <?php if ($_POST['SlaCatId'] == $row_cat['Id']) {
                                                                    echo 'selected="selected"';
                                                                  } ?>>
                      <?php echo $row_cat['Category']; ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>

            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <select name="SlaSubCatId" class="tarea-100" id="sla-list" autocomplete="off" data-parsley-required>
                  <option value="">Type</option>
                </select>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>

          </tr>

          <tr>

            <td width="90" class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="From" type="text" class="tarea-100" id="From" value="<?php echo $_POST['From']; ?>" placeholder="From" autocomplete="off" data-parsley-required />
                <span class="bt-flabels__error-desc">Required</span>
              </div>
              <script type="text/javascript">
                $('#From').datepicker({
                  dateFormat: "yy-mm-dd"
                });
              </script>
            </td>

            <td width="90" class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="To" type="text" class="tarea-100" id="To" value="<?php echo $_POST['To']; ?>" placeholder="To" autocomplete="off" data-parsley-required />
                <span class="bt-flabels__error-desc">Required</span>
              </div>
              <script type="text/javascript">
                $('#To').datepicker({
                  dateFormat: "yy-mm-dd"
                });
              </script>
            </td>

          </tr>

          <tr>

            <td width="90" class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="min_amount" type="number" step="0.01" min="0" class="tarea-100" id="min_amount" value="<?php echo $_POST['min_amount']; ?>" placeholder="Minimum Amount" autocomplete="off" />
              </div>
            </td>
            <td width="50" class="td-right"><input name="search" type="submit" class="btn-new-2" id="Submit" value="Search" /></td>
          </tr>
        </table>
      </div>
    </form>
    <!-- End Search Bar -->

    <!-- Email -->
    <?php if (isset($_GET['Mail'])) { ?>
      <form action="email.php?Mail=<?php echo $_GET['Mail']; ?>" method="post">
        <div id="list-border">
          <table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr>
              <td class="td-right"><label for="textfield"></label>
                <input type="text" class="tarea-100" name="to" id="to" placeholder="To:" />
              </td>
            </tr>
            <tr>
              <td class="td-right"><label for="textfield"></label>
                <input type="text" class="tarea-100" name="subject" id="subject" placeholder="Subject:" value="Stats Report" />
              </td>
            </tr>
            <tr>
              <td class="td-right"><label for="textarea"></label>
                <textarea name="body" class="tarea-100" id="body" placeholder="Email Body:"></textarea>
              </td>
            </tr>
          </table>
        </div>
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td align="right"><input name="send" type="submit" class="btn-new" value="Send Email" /></td>
          </tr>
        </table>
      </form>
    <?php } ?>
    <!-- End Email -->

    <p>&nbsp;</p>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>

          <!-- Pie Chart -->
          <?php /* if (mysqli_num_rows($query_list) >= 1 && !isset($_GET['ViewReport'])) { ?>
            <div style="margin-bottom:30px">
              <img src="<?php echo "pie-chart-" . last_id($con, 'tbl_stat_reports') . ".png"; ?>" />
            </div>
          <?php } ?>

          <?php

          if (isset($_GET['ViewReport'])) {

            $breadcrumbs = array($row_bread['ReporNo'], $row_bread['SLA'], $row_bread['SubCat'], $row_bread['CompanyName'], $row_bread['SiteName'], $row_bread['DateFrom'], $row_bread['DateTo']);

            foreach ($breadcrumbs as $bread) {

              if (!empty($bread)) {

                $set[] = $bread;
              }
            }

            echo '<div class="HEADER">' . implode(' >> ', $set) . '</div>';

          ?>
            <div style="margin-bottom:30px">
              <img src="<?php echo "pie-chart-" . $_GET['ViewReport'] . ".png"; ?>" />
            </div>
          <?php } */ ?>
          <!-- End Pie Chart -->

        </td>
      </tr>
    </table>

    <?php if (mysqli_num_rows($query_list) >= 1) { ?>

      <form action="../../pdf/pdf-stats.php?Report=<?php echo last_id($con, 'tbl_stat_reports'); ?>" method="post" name="form2">
        <div id="list-border">

          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                <div id="list-brdr-supprt">
                  <table id="example" width="100%" border="0" cellpadding="0" cellspacing="1">
                    <tr>
                      <td class="td-header" width="54" align="left" nowrap><strong>&nbsp;Invoice </strong>
                      </td>
                      <td width="50" align="left" nowrap class="td-header">Job No</td>
                      <td class="td-header" width="150" align="left"><strong>&nbsp;Company</strong>
                      </td>
                      <td class="td-header" align="left"><strong>&nbsp;Site Address </strong>
                      </td>
                      <td class="td-header" width="75" align="left"><strong>&nbsp;Date</strong>
                      </td>
                      <td class="td-header" width="80" align="right">&nbsp;Total</td>
                      <td class="td-header" width="150" align="right">Root Cause</td>
                    </tr>
                    <?php
                    if (!empty($list)) {
                      foreach ($list as $row_list) { ?>
                        <tr class="even">
                          <td align="left" nowrap><?php echo $row_list['InvoiceNo']; ?></td>
                          <td align="left" nowrap><?php echo $row_list['JobNo']; ?></td>
                          <td align="left"><?php echo $row_list['CompanyName']; ?></td>
                          <td align="left"><?php echo $row_list['SiteName']; ?></td>
                          <td align="left" nowrap><?php echo $row_list['Date']; ?></td>
                          <td align="right" nowrap><?php echo $row_list['Total']; ?></td>
                          <td width="150" align="right" nowrap><?php echo $row_list['RootCause']; ?></td>
                        </tr>
                    <?php
                      }
                    }
                    ?>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right">
              <?php if (!isset($_GET['ViewReport'])) { ?>
                <input name="save" type="submit" class="btn-new" id="save" value="Save Report" />
              <?php } ?>
            </td>
          </tr>
        </table>
      </form>

    <?php } ?>

    <!-- Archives -->
    <?php if (mysqli_num_rows($archive_list) >= 1) { ?>
      <div id="list-border" style="margin-top:30px">

        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <div id="list-brdr-supprt">
                <table width="100%" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td class="td-header" width="60" align="left" nowrap><strong>&nbsp;Report</strong>
                    </td>
                    <td class="td-header" width="60" align="left" nowrap>JobNo</td>
                    <td class="td-header" width="75" align="left" nowrap>Category</td>
                    <td class="td-header" align="left"><strong>&nbsp;Company</strong>
                    </td>
                    <td width="180" align="left" class="td-header"><strong>&nbsp;Date Range</strong>
                    </td>
                    <td class="td-header" width="65" align="left"><strong>&nbsp;Date</strong>
                    </td>
                    <td class="td-header" width="80" align="right">&nbsp;Total</td>
                    <td class="td-header-right" width="15" align="right">&nbsp;</td>
                    <td class="td-header-right" width="15" align="right">&nbsp;</td>
                    <td class="td-header-right" width="15" align="right">&nbsp;</td>
                    <td class="td-header-right" width="15" align="right">&nbsp;</td>
                  </tr>

                  <?php

                  // count total number of appropriate listings:
                  $tcount = mysqli_num_rows($archive_list);

                  // count number of pages:
                  $tpages = ($tcount) ? ceil($tcount / $rpp) : 1; // total pages, last page number

                  $count = 0;
                  $i = ($page - 1) * $rpp;


                  $c = 0;
                  while (($count < $rpp) && ($i < $tcount)) {

                    mysqli_data_seek($archive_list, $i);
                    $archive_row = mysqli_fetch_array($archive_list);

                    $c++;

                  ?>
                    <tr class="even">
                      <td align="left" nowrap><?php echo $archive_row['ReportId']; ?></td>
                      <td align="left" nowrap><?php echo $archive_row['JobNo']; ?></td>
                      <td align="left" nowrap><?php echo $archive_row['SLA']; ?></td>
                      <td align="left"><?php echo $archive_row['CompanyName']; ?></td>
                      <td align="left"><?php echo $archive_row['DateFrom'] . ' - ' . $archive_row['DateTo']; ?></td>
                      <td align="left" nowrap><?php echo $archive_row['Date']; ?></td>
                      <td align="right" nowrap><?php echo $archive_row['Total']; ?></td>
                      <td align="right" class="td-right-no-padding">
                        <a href="search.php?Delete=<?php echo $archive_row['ReportId']; ?>" title="Delete" class="delete"></a>
                      </td>
                      <td align="right" class="td-right-no-padding">
                        <a href="search.php?ViewReport=<?php echo $archive_row['ReportId']; ?>" title="PDF" class="view"></a>
                      </td>
                      <td align="right" class="td-right-no-padding">
                        <a href="../../pdf/pdf-stats.php?Report=<?php echo $archive_row['ReportId']; ?>&Preview" target="_blank" title="PDF" class="download"></a>
                      </td>
                      <td align="right" class="td-right-no-padding">
                        <a href="search.php?Mail=<?php echo $archive_row['ReportId']; ?>&Report=<?php echo $archive_row['ReportId']; ?>" title="Send Email" class="mail"></a>
                      </td>
                    </tr>
                  <?php

                    $i++;
                    $count++;
                  }

                  ?>
                </table>
              </div>
            </td>
          </tr>
        </table>
      </div>
    <?php } ?>
    <!-- End Archives -->

    <?php
    // Show pqaginator only if results exceed one page
    if (mysqli_num_rows($archive_list) >= 9) {

      include("../../pagination/pagination3.php");
      echo paginate_three($reload, $page, $tpages, $adjacents);
    }

    ?>

  </div>
  <!-- End Main Form -->

  <!-- Footer -->
  <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
  <!-- End Footer -->

  <script src='https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.1.2/parsley.min.js'></script>
  <script src="../../form-validation/js/index.js"></script>

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>


  <script>
    $(document).ready(function() {
      $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ]
      });
    });
  </script>

</body>

</html>
<?php
mysqli_close($con);
// mysqli_free_result($query);
mysqli_free_result($Recordset1);
mysqli_free_result($Recordset2);
mysqli_free_result($Recordset3);
mysqli_free_result($Recordset4);
mysqli_free_result($Recordset5);
?>