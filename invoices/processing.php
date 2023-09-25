<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
set_time_limit(0);
ini_set('max_execution_time', 500);

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

$inv_check = array();

$query_inv_nos = "
  SELECT
	  tbl_sites.Name AS Name_1,
	  tbl_companies.Name,
	  tbl_jc.InvoiceNo,
	  tbl_jc.JobId
  FROM
	  (
		  (
			  tbl_jc
			  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		  )
		  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
	  ) 
  WHERE tbl_jc.Status IN ('7', '3')
  GROUP BY
	  tbl_jc.JobId
  ORDER BY
	  tbl_jc.Id ASC";

$query_inv_nos = mysqli_query($con, $query_inv_nos) or die(mysqli_error($con));
while ($row_inv_nos = mysqli_fetch_array($query_inv_nos)) {

    array_push($inv_check, $row_inv_nos['InvoiceNo']);

    // Check if invoice no is set
    if ($row_inv_nos['InvoiceNo'] <= 0) {

        $query_check = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '" . $row_inv_nos['JobId'] . "' AND InvoiceNo >= '1'") or die(mysqli_error($con));
        $row_check = mysqli_fetch_array($query_check);

        if (mysqli_num_rows($query_check) == 0) {

            $invoiceno = invno($con);

            mysqli_query($con, "UPDATE tbl_jc SET InvoiceNo = '$invoiceno' WHERE JobId = '" . $row_inv_nos['JobId'] . "'") or die(mysqli_error($con));
        }
    }
}

if (!empty($_POST['inv-no']) || isset($_POST['batch'])) {

    // Remove white spaces and put user invoice numbers into array
    if (!empty($_POST['inv-no'])) {

        $pieces = explode(',', preg_replace('/\s/', '', strip_tags($_POST['inv-no'])));

        $inv_array = array();

        for ($i = 0; $i < count($pieces); $i++) {

            array_push($inv_array, $pieces[$i]);
        }
    } else {

        $inv_array = $_POST['batch'];
    }

    $post_inv = array();
    $error_count = 0;

    for ($i = 0; $i < count($inv_array); $i++) {

        // Check invoice no entered is in the list
        if (in_array($inv_array[$i], $inv_check)) {

            array_push($post_inv, $inv_array[$i]);
        } else {

            array_push($post_inv, '<span style="color:#FF0000">' . $inv_array[$i] . '</span>');
            $error_count += 1;
        }

        if ($error_count >= 1) {

            if ($error_count >= 2) {

                $plural = 's';
            }

            $error = '<span style="color:#FF0000; display: block">' . $error_count . ' Invoice' . $plural . ' unaccounted for.</span>' . "\r\n";
        }

        $query_check_company = mysqli_query($con, "SELECT CompanyId FROM tbl_jc WHERE InvoiceNo IN (" . implode(',', $inv_array) . ") GROUP BY CompanyId") or die(mysqli_error($con));

        if (mysqli_num_rows($query_check_company) >= 2) {

            $error_companies = '<span style="color:#FF0000; display: block">You have chosen more than one oil company.</span>';
        }
    }
}

// Create Batch Invoice
if (isset($_POST['create']) && $error_count == 0 && mysqli_num_rows($query_check_company) == 1) {

    $query_update = mysqli_query($con, "SELECT SUM(Total1) AS Total FROM tbl_jc WHERE InvoiceNo IN (" . implode(',', $inv_array) . ")") or die(mysqli_error($con));
    $row_update = mysqli_fetch_array($query_update);

    $jobid_array = array();

    $query_jobid = mysqli_query($con, "SELECT * FROM tbl_jc WHERE InvoiceNo IN (" . implode(',', $inv_array) . ") GROUP BY JobId") or die(mysqli_error($con));
    while ($row_jobid = mysqli_fetch_array($query_jobid)) {

        array_push($jobid_array, $row_jobid['JobId']);
    }

    $query_transport = mysqli_query($con, "SELECT SUM(Total1) AS Total FROM tbl_travel WHERE JobId IN (" . implode(',', $jobid_array) . ")") or die(mysqli_error($con));
    $row_transport = mysqli_fetch_array($query_transport);

    $sub_total = $row_update['Total'] + $row_transport['Total'];
    $vat_rate = getInvVatRate($con, $jobid) / 100;
    $vat = $sub_total * $vat_rate;
    $total = $sub_total + $vat;

    if (isset($_POST['batch-date'])) {

        $date = $_POST['batch-date'];
    } else {

        $date = date('Y-m-d');
    }

    $form_data = array(

        'OilCompany' => $row_batch['CompanyId'],
        'Site' => 'Batch Invoice',
        'Date' => $date,
        'Total' => $total
    );

    dbInsert('tbl_batch_inv', $form_data, $con);

    $query = mysqli_query($con, "SELECT * FROM tbl_batch_inv ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);

    for ($i = 0; $i < count($jobid_array); $i++) {

        mysqli_query($con, "UPDATE tbl_travel SET BatchNo = '" . $row['Id'] . "' WHERE JobId = '" . $jobid_array[$i] . "'") or die(mysqli_error($con));
    }

    $batch_data = array(

        'BatchNo' => $row['Id'],
        'RefNo' => $_POST['order-no'],
        'Status' => 7
    );

    $travel_data = array(

        'BatchNo' => $row['Id'],
    );

    for ($i = 0; $i < count($_POST['batch']); $i++) {

        dbUpdate('tbl_jc', $batch_data, $where_clause = "InvoiceNo = '" . $_POST['batch'][$i] . "'", $con);

        $query_jobid = mysqli_query($con, "SELECT JobId FROM tbl_jc WHERE InvoiceNo = '" . $_POST['batch'][$i] . "' AND JobId >= '1' GROUP BY JobId") or die(mysqli_error($con));
        $row_jobid = mysqli_fetch_array($query_jobid);

        dbUpdate('tbl_travel', $travel_data, $where_clause = "JobId = '" . $row_jobid['JobId'] . "'", $con);
    }

    $batch_data = array(

        'BatchNo' => $row['Id'],
    );

    for ($i = 0; $i < count($_POST['batch']); $i++) {

        $query_batch = mysqli_query($con, "SELECT JobId FROM tbl_jc WHERE InvoiceNo = '" . $_POST['batch'][$i] . "' GROUP BY InvoiceNo") or die(mysqli_error($con));
        $row_batch = mysqli_fetch_array($query_batch);

        $batch_data = array(

            'BatchNo' => $row['Id'],
        );

        dbUpdate('tbl_travel', $batch_data, $where_clause = "JobId = '" . $row_batch['JobId'] . "'", $con);
    }

    $query_sent = mysqli_query($con, "SELECT * FROM tbl_jc WHERE BatchNo = '" . $row['Id'] . "' GROUP BY JobId") or die(mysqli_error($con));
    while ($row_sent = mysqli_fetch_array($query_sent)) {

        $query2 = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '" . $row_sent['SiteId'] . "'") or die(mysqli_error($con));
        $row2 = mysqli_fetch_array($query2);


        $invoice = $row_sent['InvoiceNo'];
        $company = 'BP';
        $site = addslashes($row2['Name']);
        $jobid = $row_sent['JobId'];
        $document = 'Seavest Invoice ' . $invoice . '.pdf';
        $date = date('d M Y H:i:s');

        mysqli_query($con, "INSERT INTO tbl_sent_invoices (InvoiceNo,CompanyId,SiteId,JobId,PDF,DateSent) VALUES ('$invoice','$company','$site','$jobid','$document','$date')") or die(mysqli_error($con));
    }

    $_SESSION['batch_invno'] = $inv_array;

    header('Location: ../PHPExcel/batch-invoice.php?Batch=' . $row['Id']);
}

// Update Batch Invoice
if (isset($_GET['Update']) && !isset($_POST['create']) && !isset($_POST['allocate'])) {

    $inv_array = array();
    $i = 1;

    $query_inv_no = mysqli_query($con, "SELECT * FROM tbl_jc WHERE BatchNo = '" . intval($_GET['Update']) . "' AND InvoiceNo >= '1' GROUP BY JobId") or die(mysqli_error($con));
    while ($row_inv_no = mysqli_fetch_array($query_inv_no)) {

        $i++;

        array_push($inv_array, $row_inv_no['InvoiceNo']);

        $batch_data = array(

            'BatchNo' => 0,
            'Status' => 7,
        );

        dbUpdate('tbl_jc', $batch_data, $where_clause = "JobId = '" . $row_inv_no['JobId'] . "'", $con);
    }
}

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

if (isset($_POST['invoiceno'])) {
    $invoiceno = $_POST['invoiceno'];
    $where = "WHERE Status = '7' AND InvoiceNo = '" . $invoiceno . "' AND tbl_jc.CompanyId != '0'";
} elseif (isset($_POST['jobno'])) {
    $jobno = $_POST['jobno'];
    $where = "WHERE Status = '7' AND JobNo = '" . $jobno . "' AND tbl_jc.CompanyId != '0'";
} elseif (isset($_POST['quoteno'])) {
    $quoteno = $_POST['quoteno'];
    $where = "WHERE Status = '7' AND QuoteNo = '" . $quoteno . "' AND tbl_jc.CompanyId != '0'";
} elseif (isset($_POST['oil'])) {
    $oil = $_POST['oil'];
    $where = "WHERE Status = '7' AND CompanyId = '" . $oil . "' AND tbl_jc.CompanyId != '0'";
} elseif (isset($_POST['date1'])) {
    $date1 = $_POST['date1'];
    $date_1 = date('Y m d', strtotime($date1));
    $date2 = $_POST['date2'];
    $date_2 = date('Y m d', strtotime($date2));
    $where = "WHERE Status = '7' AND SearchDate >= '" . $date_1 .
        "' AND SearchDate <= '" . $date_2 .
        "' AND tbl_jc.CompanyId != '0'";
} elseif (isset($_GET['all'])) {
    $oil = $_POST['oil'];
    $where = "WHERE Status = '7' AND tbl_jc.CompanyId != '0'";
} elseif (isset($_POST['area'])) {
    $area = $_POST['area'];
    $where = "WHERE Status = '7' AND tbl_jc.AreaId = " . $area .
        " AND tbl_jc.CompanyId != '0'";
} else {

    $where = "WHERE Status = '7' AND tbl_jc.CompanyId != '0'";
}

$rpp = 50; // results per page
$adjacents = 4;
$page = intval($_GET["page"]);
$limit = 50;

if ($page <= 0) {
    $offset = 0;
    $page = 1;
} else {
    $offset = ($page - 1) * 50;
}


$query_Recordset3 = "
  SELECT
	  tbl_sites.Name AS Name_1,
	  tbl_jc.Id,
	  tbl_jc.InvoiceNo,
	  tbl_jc.JobDescription,
	  tbl_jc.JobNo,
	  tbl_jc.Date,
	  tbl_companies.Name,
	  tbl_sites.Address,
	  tbl_jc.InvoiceSent,
	  tbl_jc.JobId
  FROM
	  (
		  (
			  tbl_jc
			  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		  )
		  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
	  ) $where
  GROUP BY
	  JobId
  ORDER BY
	  tbl_jc.Id ASC
  LIMIT $limit
  OFFSET $offset";

// echo $limit . ' - ' . $offset . ' - ' . $query_Recordset3;

// die($query_Recordset3);
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_Recordset5 = "SELECT * FROM tbl_areas";
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Seavest Asset Management</title>

    <link href="../css/layout.css" rel="stylesheet" type="text/css" />
    <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
    <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>

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
                $('.row' + $(this).attr('data-prod-cat')).toggle();
            });
        });
    </script>

    <!-- TinyMCE -->
    <script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            mode: "specific_textareas",
            editor_selector: "mceEditor",
            theme: "modern",
            menubar: false,
            toolbar: false,
            statusbar: false,
            height: 44,
            force_br_newlines: false,
            force_p_newlines: false,
            forced_root_block: '',

            plugins: [
                "textcolor lists link image responsivefilemanager autoresize",
            ],

        });
    </script>
    <!-- End TinyMCE -->

    <script>
        !window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
    </script>
    <script type="text/javascript" src="../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />

    <script type="text/javascript">
        jQuery(document).ready(function() {

            $(".various3").fancybox({
                'width': 500,
                'height': 230,
                'autoScale': true,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'type': 'iframe',
                'padding': 0,
                'overlayOpacity': '0.8',
                'overlayColor': 'black',

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
    <?php include('../menu/menu.php'); ?>
    <!-- End Navigation -->

    <!-- Breadcrumbs -->
    <div class="td-bread">
        <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Invoices</a></li>
            <li><a href="#">Pending</a></li>
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
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="bottom" class="combo_bold">
                    <form name="form1" method="post" action="">
                        <select name="menu1" class="select-5" onchange="MM_jumpMenu('parent',this,0)">
                            <option>Search...</option>
                            <option value="processing.php?all">All</option>
                            <option value="processing.php?area">Area</option>
                            <option value="processing.php?jcn">Job Card Number</option>
                            <option value="processing.php?date">Date</option>
                            <option value="processing.php?oil">Oil Company</option>
                        </select>
                    </form>
                </td>
                <td valign="bottom" nowrap>
                    <?php
                    if (isset($_GET['jcn'])) {

                        $_SESSION['search'] = "jcn";
                    ?>
                        <form name="form2" method="post" action="processing.php?jcn">
                            <input name="jobno" type="text" class="select" id="jobno" style="cursor:text">
                            <input name="Submit" type="submit" class="btn-new" id="Submit" value="Search">
                        </form>

                    <?php } ?>

                    <?php
                    if (isset($_GET['in'])) {

                        $_SESSION['search'] = "in";
                    ?>
                        <form name="form2" method="post" action="processing.php?in">
                            <input name="invoiceno" type="text" class="select" id="invoiceno" style="cursor:text">
                            <input name="Submit2" type="submit" class="btn-new" id="Submit2" value="Search">
                        </form>

                    <?php } ?>

                    <?php
                    if (isset($_GET['qn'])) {

                        $_SESSION['search'] = "qn";
                    ?>
                        <form name="form2" method="post" action="processing.php?qn">
                            <input name="quoteno" type="text" class="select" id="quoteno" style="cursor:text">
                            <input name="Submit2" type="submit" class="btn-new" id="Submit2" value="Search">
                        </form>

                    <?php } ?>

                    <?php
                    if (isset($_GET['date'])) {

                        $_SESSION['search'] = "date";
                    ?>
                        <form name="form2" method="post" action="processing.php?date">
                            <input name="date1" class="select" id="date1" value="">
                            <script type="text/javascript">
                                $('#date1').datepicker({
                                    dateFormat: "yy-mm-dd"
                                });
                            </script>

                            <input name="date2" class="select" id="date2" value="">
                            <script type="text/javascript">
                                $('#date2').datepicker({
                                    dateFormat: "yy-mm-dd"
                                });
                            </script>

                            <input name="Submit3" type="submit" class="btn-new" id="Submit3" value="Search">
                        </form>
                    <?php } ?>

                    <?php
                    if (isset($_GET['oil'])) {

                        $_SESSION['search'] = "oil";
                    ?>

                        <form name="form2" method="post" action="processing.php?oil">
                            <select name="oil" class="select" id="oil">
                                <?php
                                do {
                                ?>
                                    <option value="<?php echo $row_Recordset4['Id'] ?>"><?php echo $row_Recordset4['Name'] ?></option>
                                <?php
                                } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4));
                                $rows = mysqli_num_rows($Recordset4);
                                if ($rows > 0) {
                                    mysqli_data_seek($Recordset4, 0);
                                    $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
                                }
                                ?>
                            </select>
                            <input name="Submit4" type="submit" class="btn-new" value="Search">
                        </form>

                    <?php } ?>

                    <?php if (isset($_GET['area'])) { ?>
                        <form name="form2" method="post" action="processing.php?rea">
                            <select name="area" class="select" id="area">
                                <?php
                                do {
                                ?>
                                    <option value="<?php echo $row_Recordset5['Id'] ?>"><?php echo $row_Recordset5['Area'] ?></option>
                                <?php
                                } while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5));
                                $rows = mysqli_num_rows($Recordset5);
                                if ($rows > 0) {
                                    mysqli_data_seek($Recordset5, 0);
                                    $row_Recordset5 = mysqli_fetch_assoc($Recordset5);
                                }
                                ?>
                            </select>
                            <input name="Submit4" type="submit" class="btn-new" value="Search">
                        </form>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <form id="form3" name="form3" method="post" action="">

            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="list-border" style="margin-top:15px">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td colspan="2" class="td-header">
                                        Batch Invoice Numbers
                                    </td>
                                </tr>


                                <tr class="row1">
                                    <td colspan="2" class="td-right">
                                        <textarea name="inv-no" id="inv-no" class="tarea-100 mceEditor" rows="3" placeholder="Invoice Numbers (eg. 46367 46368 46369)">
                                            <?php
                                            if (!empty($post_inv)) {

                                                for ($c = 0; $c < count($post_inv); $c++) {

                                                    if ($c < count($post_inv) - 1) {

                                                        $comma = ', ';
                                                    } else {

                                                        $comma = '';
                                                    }

                                                    echo $post_inv[$c] . $comma;
                                                }
                                            } else if (!empty($inv_array)) {

                                                for ($c = 0; $c < count($inv_array); $c++) {

                                                    if ($c < count($inv_array) - 1) {

                                                        $comma = ', ';
                                                    } else {

                                                        $comma = '';
                                                    }

                                                    echo $inv_array[$c] . $comma;
                                                }
                                            }
                                            ?>
                                        </textarea>
                                    </td>
                                </tr>
                                <tr class="row1">
                                    <td width="16%" class="td-left">Batch Invoice Date</td>
                                    <td width="84%" class="td-right">

                                        <input name="batch-date" type="text" class="tarea-100" id="batch-date" placeholder="YYYY-mm-dd" />

                                        <script type="text/javascript">
                                            $('#batch-date').datepicker({

                                                dateFormat: "yy-mm-dd"
                                            });
                                        </script>

                                    </td>
                                </tr>
                                <tr class="row1">
                                    <td class="td-left">Batch Order No.</td>
                                    <td class="td-right"><input name="order-no" type="text" class="tarea-100" id="order-no" /></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Batch Allocate -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="35%"><?php echo $error . $error_companies; ?></td>
                                <td width="65%" align="right">
                                    <input name="allocate" type="submit" class="btn-new" id="allocate" value="Allocate To Batch Invoice" style="margin-bottom:15px" />

                                    <?php if ($error_count == 0 && isset($_POST['allocate'])) { ?>

                                        <input name="create" type="submit" class="btn-new" id="create" value="Create Batch Invoice" style="margin-bottom:15px" />

                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                        <!-- End Batch Allocate -->

                    </td>
                </tr>
            </table>

            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="list-border">
                            <?php $jobid = $row_Recordset3['JobId']; ?>
                            <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="combo">
                                <tr>
                                    <td width="100" align="left" nowrap class="td-header"><strong>&nbsp;Invoice </strong></td>
                                    <td width="100" align="left" nowrap class="td-header">&nbsp; Ref.</td>
                                    <td width="200" align="left" class="td-header"><strong>&nbsp;Company</strong></td>
                                    <td align="left" class="td-header"><strong>&nbsp;Site Address </strong></td>
                                    <td width="75" align="center" class="td-header"><strong>&nbsp;Age</strong></td>
                                    <td width="20" align="center" class="td-header-right">&nbsp;</td>
                                    <td width="20" align="center" class="td-header-right">&nbsp;</td>
                                </tr>
                                <?php
                                do {

                                    $query_proforma = mysqli_query($con, "SELECT * FROM tbl_sent_invoices WHERE JobId = '" . $row_Recordset3['JobId'] . "'") or die(mysqli_error($con));
                                    $row_proforma = mysqli_fetch_array($query_proforma);

                                    if (!isset($row_proforma['Proforma']) || empty($row_proforma['Proforma'])) {

                                        $url = 'inv-calc.php?menu=' . $_GET['menu'] . '&Id=' . $row_Recordset3['JobId'] . '&job';
                                        $site = $row_Recordset3['Name_1'];
                                    } else {

                                        $url = 'scheduled-maintenance-calc.php?menu=' . $_GET['menu'] . '&Id=' . $row_Recordset3['JobId'] . '&job';
                                        $site = 'Scheduled Maintenance';
                                    }

                                    $jobid = $row_Recordset3['JobId'];
                                ?>
                                    <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? " even" : "odd"; ?>
                                    " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                                        <td><a href="<?php echo $url; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"> &nbsp;<?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                                        <td><a href="<?php echo $url; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['JobNo']; ?></a></td>
                                        <td><a href="<?php echo $url; ?>" class="menu"> &nbsp;<?php echo $row_Recordset3['Name']; ?></a></td>
                                        <td><a href="<?php echo $url; ?>" class="menu"> &nbsp;<?php echo $site; ?></a></td>
                                        <td align="center"><a href="<?php echo $url; ?>" class="menu"> &nbsp;<?php time_schedule($jobid); ?></a><a name="<?php echo $row_Recordset3['JobId']; ?>"></a></td>
                                        <td align="center"><a href="order-no.php?Id=<?php echo $row_Recordset3['JobId']; ?>&Pending" title="Order No" class="po various3"></a></td>
                                        <td align="center">

                                            <?php
                                            $checked = '';

                                            if (isset($_POST['batch']) && !empty($_POST['batch'])) {
                                                for ($i = 0; $i < count($_POST['batch']); $i++) {

                                                    if ($_POST['batch'][$i] == $row_Recordset3['InvoiceNo']) {

                                                        $checked = 'checked="checked"';
                                                    }
                                                }
                                            }


                                            if (!empty($inv_array) && in_array($row_Recordset3['InvoiceNo'], $inv_array)) {

                                                $checked = 'checked="checked"';
                                            }

                                            ?>

                                            <input type="checkbox" name="batch[]" id="batch[]" value="<?php echo $row_Recordset3['InvoiceNo']; ?>" <?php echo $checked; ?> />

                                        </td>
                                    </tr>
                                <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                            </table>
                        </div>

                        <!-- Batch Allocate -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="35%"><?php echo $error; ?></td>
                                <td width="65%" align="right">
                                    <?php if (isset($_GET['Update'])) { ?>

                                        <input name="update" type="submit" class="btn-new" id="update" value="Update Batch Invoice" style="margin-bottom:15px" />

                                    <?php } else { ?>

                                        <input name="allocate" type="submit" class="btn-new" id="allocate" value="Allocate To Batch Invoice" style="margin-bottom:15px" />

                                    <?php } ?>

                                    <?php if ($error_count == 0 && isset($_POST['allocate'])) { ?>

                                        <input name="create" type="submit" class="btn-new" id="create" value="Create Batch Invoice" style="margin-bottom:15px" />

                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                        <!-- End Batch Allocate -->

                    </td>
                </tr>
            </table>
            <div>Current Page - <?= $page; ?></div>
            <div>
                <a href="/inv/invoices/processing.php?page=<?= $page - 1; ?>">Prev</a> | <a href="/inv/invoices/processing.php?page=<?= $page + 1; ?>">Next</a>
            </div>
        </form>
    </div>
    <!-- End Main Form -->
    <!-- Footer -->
    <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
    <!-- End Footer -->

</html>
<?php
mysqli_close($con);
mysqli_free_result($Recordset1);
mysqli_free_result($Recordset2);
mysqli_free_result($Recordset3);
mysqli_free_result($Recordset5);
mysqli_free_result($Recordset4);
?>