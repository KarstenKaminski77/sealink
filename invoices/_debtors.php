<?php
session_start();

require_once('../functions/functions.php');

if (isset($_GET['archive'])) {
  $jobid = $_GET['archive'];
  mysqli_query($con, "UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

if (isset($_GET['Id'])) {

  $jobid = $_GET['Id'];

  mysqli_query($con, "UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

$where = '';
if (isset($_GET['Company'])) {

  $where = 'AND tbl_companies.Id = ' . $_GET['Company'];
}

// Remove Invoice From Batch
if (isset($_GET['BatchRemove'])) {

  $inv_array = array();

  mysqli_query($con, "UPDATE tbl_jc SET BatchNo = '0', Status = '7' WHERE JobId = '" . $_GET['BatchRemove'] . "'") or die(mysqli_error($con));

  $_SESSION['total'] = 0;

  $query = mysqli_query($con, "SELECT Total2 AS Total FROM tbl_jc WHERE BatchNo = '" . $_GET['Batch'] . "' AND Total2 >= '1' GROUP BY JobId") or die(mysqli_error($con));
  while ($row = mysqli_fetch_array($query)) {

    $_SESSION['total'] += $row['Total'];
  }

  $form_data = array(

    'OilCompany' => $row_batch['CompanyId'],
    'Site' => 'Batch Invoice',
    'Date' => date('Y-m-d'),
    'Total' => $_SESSION['total']
  );

  dbUpdate('tbl_batch_inv', $form_data, $where_clause = "Id = '" . $_GET['Batch'] . "'", $con);

  header('Location: ../PHPExcel/batch-invoice.php?Batch=' . $_GET['Batch'] . '&Debtors');
}

$query_Recordset3 = "
SELECT
tbl_sites.Name AS Name_1,
STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort,
tbl_jc.InvoiceDate AS InvoiceDate,
tbl_jc.BatchNo,
tbl_jc.CompanyId,
tbl_sent_invoices.PDF,
tbl_sent_invoices.DateSent,
tbl_sent_invoices.Sent,
tbl_jc.JobDescription,
tbl_jc.Status,
tbl_jc.JobId,
tbl_jc.Total2,
tbl_companies.Name,
tbl_sent_invoices.InvoiceNo AS InvoiceNo_1
FROM
(
(
(
tbl_sent_invoices
LEFT JOIN tbl_jc ON tbl_jc.JobId = tbl_sent_invoices.JobId
)
LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
)
LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
)
WHERE
tbl_jc.Status = '12'
AND tbl_jc.Total2 > '0' " . $where . "
AND tbl_sent_invoices.InvoiceNo != '0'
GROUP BY IF(tbl_jc.BatchNo IS NULL, tbl_jc.JobId, tbl_jc.BatchNo)
ORDER BY
date_for_sort ASC";
$sumDebtors = 0;
$Recordset7 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
while ($row = mysqli_fetch_assoc($Recordset7)) {
  $sumDebtors += $row['Total2'];
}
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_rs_totals  = $_SESSION['kt_login_id'];

$rs_totals = mysqli_query($con, "SELECT * FROM tbl_menu_relation WHERE UserId = '$colname_rs_totals' AND MenuId = '47'") or die(mysqli_error($con));
$row_rs_totals = mysqli_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysqli_num_rows($rs_totals);

$query_Recordset4 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Seavest Asset Management</title>

  <link href="../css/layout.css" rel="stylesheet" type="text/css" />
  <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

  <link rel="stylesheet" href="../menu/styles.css">
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <script src="../menu/script.js"></script>

  <script type="text/javascript">
    function MM_jumpMenu(targ, selObj, restore) { //v3.0
      eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
      if (restore) selObj.selectedIndex = 0;
    }
  </script>

  <script type="text/javascript" src="../fancyBox-2/lib/jquery-1.10.1.min.js"></script>
  <script type="text/javascript" src="../fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <script type="text/javascript" src="../fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
  <link rel="stylesheet" type="text/css" href="../fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />

  <script type="text/javascript">
    $(document).ready(function() {

      $('.fancybox').fancybox({

        autoSize: true,
        closeClick: false,
        fitToView: true,
        openEffect: 'none',
        closeEffect: 'none',
        scrolling: 'no',
        type: 'iframe',

      });



    });
  </script>

  <script LANGUAGE="JavaScript">
    function confirmSubmit() {

      var agree = confirm("Are you sure you wish to continue?");

      if (agree)

        return true;
      else
        return false;
    }

    function MM_jumpMenu(targ, selObj, restore) { //v3.0
      eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
      if (restore) selObj.selectedIndex = 0;
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $(".toggler").click(function(e) {
        e.preventDefault();
        $('.row1').toggle();
        $('.row-2-1').toggleClass("td-sub-header2");
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
      <li><a href="#">Debtors</a></li>
      <li></li>
    </ul>
  </div>
  <!-- End Breadcrumbs -->

  <!-- Search -->
  <!-- <div class="search-container">
    <form id="form1" name="form1" method="post" action="search.php">
      <input name="search-field" type="text" class="search-top" id="search-field" placeholder="Search..." />
      <input name="button" type="submit" class="search-top-btn" id="button" value="" />
    </form>
  </div> -->
  <!-- End Search -->

  <!-- Main Form -->
  <div id="main-wrapper">
    <!-- <form action="../fpdf16/pdf/jc_resend_mail.php" method="post" enctype="multipart/form-data" name="form2" style="width:100%"> -->

    <?php
    if (isset($_GET['Mail'])) {

      echo $_SESSION['message'];
    }
    ?>
    <form action="../PHPExcel/create-export.php" method="post" enctype="multipart/form-data" name="form2">
      <table width="100%" border="0" cellpadding="3" cellspacing="1">
        <tr>
          <td colspan="10" nowrap>
            <input name="email" type="text" class="select" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';">
          </td>
        </tr>
        <tr>
          <!-- <td colspan="3" align="left" nowrap>
            <div id='file_browse_wrapper'>
              <input name="xls" type='file' id='file_browse'>
            </div>
            <input name="upload" type="submit" class="btn-new" id="upload" value="Upload Spreadsheet">
          </td> -->
          <td colspan="7" align="right" nowrap>
            <input name="test" type="submit" class="btn-new" id="test" value="Send Email">
            <!-- <input name="engineer" type="submit" class="btn-new" id="engineer" value="Send to Engineers"> -->
          </td>
        </tr>

        <?php /* <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">

          <?php if (isset($_GET['Success'])) { ?>
            <tr>
              <td colspan="10" align="left" nowrap class="combo">
                <div id="banner-success">Batch Invoice No. <?php echo $_GET['Success']; ?> Created.</div>
              </td>
            </tr>
          <?php } ?>

          <?php if (isset($_GET['BatchRemoved'])) { ?>
            <tr>
              <td colspan="10" align="left" nowrap class="combo">
                <div id="banner-success">Invoice Removed From Batch.</div>
              </td>
            </tr>
          <?php } ?>

          <tr>
            <td colspan="10" align="left" nowrap class="combo"><input name="email" type="text" class="select-normal" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';"></td>
          </tr>
          <tr>
            <td colspan="10" align="left" nowrap class="combo"><input name="attach" type="file" class="tarea-100" id="attach"></td>
          </tr>
          <tr>
            <td colspan="10" align="left" nowrap class="combo"><textarea name="message" rows="5" class="select-normal" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';">Message</textarea></td>
          </tr>
          <tr>
            <td colspan="10" align="right" nowrap class="combo">
              <input name="Submit2" type="submit" class="btn-new" value="Send Email">
            </td>
          </tr> */ ?>
          <tr>
            <td colspan="10" align="left" nowrap class="combo">
              <table border="0">
                <tr>
                  <td>
                    <select name="jumpMenu" class="select" id="jumpMenu" onChange="MM_jumpMenu('parent',this,0)">
                      <?php do { ?>
                        <option value="debtors.php?Company=<?php echo $row_Recordset4['Id']; ?>" <?php if ($_GET['Company'] == $row_Recordset4['Id']) {
                                                                                                    echo 'selected="selected"';
                                                                                                  } ?>><?php echo $row_Recordset4['Name']; ?></option>
                      <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                    </select>
                  </td>
                  <?php if (isset($_GET['Company'])) { ?>
                  <?php } ?>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <div id="list-border">
          <table width="100%" border="0" cellpadding="3" cellspacing="1">
            <tr>
              <td colspan="10" align="right"><span class="total-container">Total: R<?= $sumDebtors; ?></span></td>
            </tr>
          </table>
          <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr>
              <td class="td-header" width="50" align="center" nowrap><strong>In No. </strong></td>
              <td class="td-header" width="160" align="left"><strong>Company</strong></td>
              <td class="td-header" align="left"><strong>Site Address </strong></td>
              <td class="td-header" width="85" align="left"><strong>Date</strong></td>
              <td class="td-header" width="85" align="left">Total</td>
              <?php if ($_COOKIE['userid'] == 50 || $_COOKIE['userid'] == 142) : ?>
              <td class="td-header" width="85" align="left">Archive</td>
              <?php endif; ?>
              <td class="td-header-right" width="20" align="center">&nbsp;</td>
              <td class="td-header-right" width="20" align="center">&nbsp;</td>
              <td class="td-header-right" width="20" align="center">&nbsp;</td>
              <td class="td-header-right" width="20" align="center">&nbsp;</td>
              <td class="td-header-right" width="20" align="center">&nbsp;</td>
            </tr>
            <?php
            $_SESSION['total-debtors'] = '';

            do {

              $_SESSION['total-debtors'] += $row_Recordset3['Total2'];

              $query_proforma = mysqli_query($con, "SELECT * FROM tbl_sent_invoices WHERE JobId = '" . $row_Recordset3['JobId'] . "'") or die(mysqli_error($con));
              $row_proforma = mysqli_fetch_array($query_proforma);

              if (empty($row_proforma['Proforma'])) {

                $url = '../revive.php?Id=' . $row_Recordset3['JobId'];
                $site = $row_Recordset3['Name_1'];
                $pdf = '../fpdf16/inv-preview.php?Id=' . $row_Recordset3['JobId'];
              } else {

                $url = 'scheduled-maintenance-calc.php?menu=' . $_GET['menu'] . '&Id=' . $row_Recordset3['JobId'] . '&job';
                $site = 'Scheduled Maintenance';
                $pdf = '../pdf/pdf-scheduled-maintenance.php?Id=' . $row_Recordset3['JobId'] . '&Preview';
              }

              $jobid = $row_Recordset3['JobId'];
            ?>

              <?php

              if ($row_Recordset3['BatchNo'] >= 1) {

                $query_batch = "
                SELECT
                tbl_batch_inv.Id,
                tbl_sites.Name,
                tbl_jc.JobId,
                tbl_jc.InvoiceNo,
                STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort,
                tbl_jc.Total2
                FROM
                tbl_batch_inv
                INNER JOIN tbl_jc
                ON (
                tbl_batch_inv.Id = tbl_jc.BatchNo
                )
                INNER JOIN tbl_sites
                ON (tbl_jc.SiteId = tbl_sites.Id)
                WHERE (tbl_batch_inv.Id = '" . $row_Recordset3['BatchNo'] . "')
                GROUP BY tbl_jc.JobId";

                $query_batch = mysqli_query($con, $query_batch) or die(mysqli_error($con));

                $query_batch_total = mysqli_query($con, "SELECT Total, `Date` FROM tbl_batch_inv WHERE tbl_batch_inv.Id = '" . $row_Recordset3['BatchNo'] . "'") or die(mysqli_error($con));
                $row_batch_total = mysqli_fetch_array($query_batch_total);

              ?>

                <!-- Batch Invoices -->
                <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? "odd" : "even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                  <td align="center" nowrap class="combo row-2-1">
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>">B<?php echo $row_Recordset3['BatchNo']; ?></a>
                  </td>
                  <td nowrap class="combo row-2-1" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Name']; ?></a>
                  </td>
                  <td class="combo row-2-1" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>">Batch Invoice</a>
                  </td>
                  <td class="combo row-2-1" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_batch_total['Date']; ?></a>
                  </td>
                  <td nowrap class="combo row-2-1" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>">R<?php echo number_format($row_batch_total['Total'], 2); ?></a>
                  </td>
              <?php if ($_COOKIE['userid'] == 50 || $_COOKIE['userid'] == 142) : ?>
                  <td align="center" class="combo row-2-1">
                    <a href="?archive=<?=$jobid?>" title="Archive" class="">Archive</a>
                  </td>
              <?php endif; ?>
                  <td align="center" class="combo row-2-1">
                    <a href="../pdf/pdf-batch.php?Batch=<?php echo $row_Recordset3['BatchNo']; ?>&Preview" target="_blank" title="View PDF" class="view fancybox"></a>
                  </td>
                  <td align="center" class="combo row-2-1">
                    <a onClick="return confirmSubmit()" href="<?php echo 'processing.php?Update=' . $row_Recordset3['BatchNo']; ?>" class="edit" title="Edit Batch Invoice"></a>
                  </td>
                  <td align="center" class="combo row-2-1">
                    <a href="#" title="Mail History" class="expand toggler" view="1"></a>
                  </td>
                  <td align="center" class="combo row-2-1">
                    <a href="../fpdf16/pdf/credit-pdf.php?Id=<?php echo $row_Recordset3['BatchNo']; ?>" class="icon-credit" title="Credit Note"></a>
                  </td>
                  <td align="center" class="combo row-2-1">
                    <input name="batch[]" type="checkbox" id="btch[]" value="<?php echo $row_Recordset3['BatchNo']; ?>">
                  </td>
                </tr>

                <!-- Invoice Details -->
                <?php while ($row_batch = mysqli_fetch_array($query_batch)) { ?>

                  <tr style="display:none" class="row1">
                    <td align="center" nowrap class="td-batch-sub-header">
                      <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_batch['JobDescription']; ?>"><?php echo $row_batch['InvoiceNo']; ?></a>
                    </td>
                    <td nowrap class="td-batch-sub-header" <?php debtors_overdue($jobid); ?>>
                      <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_batch['JobDescription']; ?>"><?php echo $row_Recordset3['Name']; ?></a>
                    </td>
                    <td class="td-batch-sub-header" <?php debtors_overdue($jobid); ?>>
                      <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_batch['JobDescription']; ?>"><?php echo $row_batch['Name']; ?></a>
                    </td>
                    <td class="td-batch-sub-header" <?php debtors_overdue($jobid); ?>>
                      <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_batch['JobDescription']; ?>"><?php echo $row_batch['date_for_sort']; ?></a>
                    </td>
                    <td nowrap class="td-batch-sub-header" <?php debtors_overdue($jobid); ?>>
                      <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_batch['JobDescription']; ?>">R<?php echo number_format($row_batch['Total2'], 2); ?></a>
                    </td>
              <?php if ($_COOKIE['userid'] == 50 || $_COOKIE['userid'] == 142) : ?>
                    <td align="center" class="combo row-2-1">
                      <a href="?archive=<?=$jobid?>" title="Archive" class="">Archive</a>
                    </td>
              <?php endif; ?>
                    <td align="center" class="td-batch-sub-header">
                      <a href="<?php echo '../fpdf16/inv-preview.php?Id=' . $row_batch['JobId']; ?>" target="_blank" title="View PDF" class="view fancybox"></a>
                    </td>
                    <td align="center" class="td-batch-sub-header">
                      <a onClick="return confirmSubmit()" href="<?php echo 'inv-calc.php?Id=' . $row_batch['JobId']; ?>" class="edit" title="Edit Invoice"></a>
                    </td>
                    <td align="center" class="td-batch-sub-header">
                      <a onClick="return confirmSubmit()" href="<?php echo $url; ?>" class="icon-info" title="Edit"></a>
                    </td>
                    <td align="center" class="td-batch-sub-header">
                      <a href="debtors.php?BatchRemove=<?php echo $row_batch['JobId']; ?>&Batch=<?php echo $row_batch['Id']; ?>" title="Remove From Batch" class="delete"></a>
                    </td>
                    <td align="center" class="td-batch-sub-header">&nbsp;</td>
                  </tr>

                  <?php

                  $row_batch['JobDescription'] = NULL;
                  $row_batch['InvoiceNo'] = NULL;
                  $row_Recordset3['Name'] = NULL;
                  $row_batch['Name'] = NULL;
                  $row_batch['date_for_sort'] = NULL;
                  $row_batch['Total2'] = NULL;
                  $row_batch['JobId'] = NULL;
                  $row_batch['Id'] = NULL;

                  ?>

                <?php

                }

                $url = NULL;
                $site = NULL;
                $pdf = NULL;
                $row_Recordset3['JobId'] = NULL;

                ?>
                <!-- End Invoice Details -->

                <!-- End Batch Invoices -->

              <?php } else { ?>

                <!-- General Invoices -->
                <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? "odd" : "even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                  <td align="center" nowrap class="combo">
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo_1']; ?></a>
                  </td>
                  <td nowrap class="combo" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Name']; ?></a>
                  </td>
                  <td class="combo" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $site; ?></a>
                  </td>
                  <td class="combo" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['date_for_sort']; ?></a>
                  </td>
                  <td nowrap class="combo" <?php debtors_overdue($jobid); ?>>
                    <a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>">R<?php echo $row_Recordset3['Total2']; ?></a>
                  </td>
              <?php if ($_COOKIE['userid'] == 50 || $_COOKIE['userid'] == 142) : ?>
                  <td align="center" class="combo row-2-1">
                    <a href="?archive=<?=$jobid?>" title="Archive" class="">Archive</a>
                  </td>
              <?php endif; ?>
                  <td align="center">
                    <a href="<?php echo $pdf; ?>" target="_blank" title="View PDF" class="view fancybox"></a>
                  </td>
                  <td align="center">
                    <a onClick="return confirmSubmit()" href="<?php echo $url; ?>" class="edit" title="Edit"></a>
                  </td>
                  <td align="center">
                    <a href="../jc_history.php?Id=<?php echo $row_Recordset3['JobId']; ?>" title="Mail History" class="icon-info"></a>
                  </td>
                  <td align="center">
                    <a href="../fpdf16/pdf/credit-pdf.php?Id=<?php echo $row_Recordset3['JobId']; ?>" class="icon-credit" title="Credit Note"></a>
                  </td>
                  <td align="center">
                    <?php
                    // Check if Pragma and send XL format

                    // $companyid = $row_Recordset3['CompanyId'];

                    // if ($companyid == 2) {

                      $value = $row_Recordset3['JobId'];
                    // } else {

                    //   $value = $row_Recordset3['PDF'];
                    // }

                    ?>
                    <input name="file[]" type="checkbox" id="file[]" value="<?php echo $value; ?>">
                  </td>
                </tr>
                <!-- End General Invoices -->

                <?php

                $row_Recordset3['JobDescription'] = NULL;
                $row_Recordset3['InvoiceNo_1'] = NULL;
                $row_Recordset3['Name'] = NULL;
                $row_Recordset3['date_for_sort'] = NULL;
                $row_Recordset3['Total2'] = NULL;
                $row_Recordset3['CompanyId'] = NULL;
                $row_Recordset3['JobId'] = NULL;
                $row_Recordset3['PDF'] = NULL;
                $value = NULL;
                $site = NULL;
                ?>

              <?php } ?>

            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>

            <tr>
              <td colspan="5" align="right" class="td-header">
                <?php
                if ($totalRows_rs_totals >= 1) {

                  if (isset($_GET['Company'])) {

                    $where = "WHERE Status = '12' AND CompanyId = '" . $_GET['Company'] . "'";
                  } else {

                    $where = "WHERE Status = '12'";
                  }

                  sum_outstanding($where);
                }

                //echo $totalRows_Recordset3 .' - '. number_format($_SESSION['total-debtors'],2);
                ?>
              </td>
              <?php if ($_COOKIE['userid'] == 50 || $_COOKIE['userid'] == 142) : ?>
              <td align="center" class="combo row-2-1">
                <a href="?archive=<?=$jobid?>" title="Archive" class="">Archive</a>
              </td>
              <?php endif; ?>
              <td align="right" class="td-header">&nbsp;</td>
              <td align="right" class="td-header">&nbsp;</td>
              <td align="right" class="td-header">&nbsp;</td>
              <td align="right" class="td-header">&nbsp;</td>
              <td align="right" class="td-header">&nbsp;</td>
            </tr>
          </table>
        </div>
    </form>
  </div>
  <!-- End Main Form -->

  <!-- Footer -->
  <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
  <!-- End Footer -->

</body>

</html>
<?php
mysqli_close($con);
mysqli_free_result($query);
mysqli_free_result($query_areas);
mysqli_free_result($query_list);
mysqli_free_result($query_form);
mysqli_free_result($query_user_menu);
?>