<?php
ini_set('max_execution_time', 300);
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);


if (isset($_GET['archive'])) {
  $jobid = $_GET['archive'];
  mysqli_query($con, "UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

$query_Recordset3 = "
SELECT
tbl_companies. NAME AS Name_1,
tbl_companies.Id AS CompanyId,
tbl_jc.JobNo,
tbl_jc.Total2,
tbl_jc.JobDescription,
tbl_jc.Id,
tbl_jc.InvoiceNo,
STR_TO_DATE(
tbl_jc.InvoiceDate,
'%d %M %Y'
) AS date_for_sort,
tbl_jc.JobId,
tbl_jc.InvoiceQ,
tbl_sites.Name,
tbl_sites.FirstName,
tbl_sites.LastName,
tbl_sent_invoices.PDF
FROM
(
(
(
tbl_jc
LEFT JOIN tbl_sent_invoices ON tbl_sent_invoices.JobId = tbl_jc.JobId
)
LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
)
LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
)
WHERE
STATUS = '11'
AND tbl_jc.CompanyId != '0'
AND tbl_jc.InvoiceNo != '0'
AND tbl_jc.RefNo IS NULL
GROUP BY
tbl_jc.JobId
ORDER BY
date_for_sort ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_rs_totals = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_rs_totals = $_SESSION['kt_login_id'];
}
$query_rs_totals = "SELECT * FROM tbl_menu_relation WHERE UserId = '$colname_rs_totals' AND MenuId = '47'";
$rs_totals = mysqli_query($con, $query_rs_totals) or die(mysqli_error($con));
$row_rs_totals = mysqli_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysqli_num_rows($rs_totals);

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
  <script src="../menu/script.js"></script>

  <script type="text/javascript">
    function MM_jumpMenu(targ, selObj, restore) { //v3.0
      eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
      if (restore) selObj.selectedIndex = 0;
    }
  </script>

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
      <li><a href="#">Approved Awt. Ord. No.</a></li>
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
    <form action="../PHPExcel/create-export.php" method="post" enctype="multipart/form-data" name="form2">
      <table width="100%" border="0" cellpadding="3" cellspacing="1">
        <tr>
          <td colspan="10" nowrap>
            <input name="email" type="text" class="select" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';">
          </td>
        </tr>
        <tr>
          <td colspan="3" align="left" nowrap>
            <div id='file_browse_wrapper'>
              <input name="xls" type='file' id='file_browse'>
            </div>
            <input name="upload" type="submit" class="btn-new" id="upload" value="Upload Spreadsheet">
          </td>
          <td colspan="7" align="right" nowrap>
            <input name="test" type="submit" class="btn-new" id="test" value="Send Email">
            <!-- <input name="engineer" type="submit" class="btn-new" id="engineer" value="Send to Engineers"> -->
          </td>
        </tr>
        <tr>
          <td colspan="10" align="center" nowrap>&nbsp;</td>
        </tr>
        <?php if (isset($_SESSION['mail_to'])) { ?>
          <tr>
            <td colspan="10" align="center" nowrap>
              <div id="banner-success-mail">
                <span class="success-header">Mail Successfully Sent To:</span>
                <?php
                for ($i = 0; $i < count($_SESSION['mail_to']); $i++) {

                  echo $_SESSION['mail_to'][$i];
                }
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="10" align="center" nowrap>&nbsp;</td>
          </tr>
        <?php
        }
        unset($_SESSION['mail_to']);
        ?>
        <?php if (isset($_SESSION['success'])) { ?>
          <tr>
            <td colspan="10" align="center" nowrap><?php echo $_SESSION['success']; ?></td>
          </tr>
          <tr>
            <td colspan="10" align="center" nowrap></td>
          </tr>
        <?php
        }
        unset($_SESSION['success']);
        ?>
        <tr>
          <td colspan="10" align="right"><span class="total-container">Total: <?php sum_approved($con); ?></span></td>
        </tr>
      </table>
      <div id="list-border">
        <table width="100%" border="0" cellpadding="3" cellspacing="1">
          <tr>
            <td class="td-header" width="7%" align="center" nowrap><strong>In No. </strong></td>
            <td class="td-header" width="10%" align="center" nowrap>Job No</td>
            <td class="td-header" width="15%" align="left"><strong>Company</strong></td>
            <td class="td-header" width="22%" align="left"><strong>Site Address </strong></td>
            <td class="td-header" width="6%" align="center"><strong>Age</strong></td>
            <td class="td-header" width="7%" align="left">Status</td>
            <td class="td-header" width="7%" align="right">Total</td>
            <td class="td-header-right" width="4%" align="center">INV</td>
            <td class="td-header-right" width="4%" align="center">&nbsp;</td>
            <td class="td-header-right" width="8%" align="center">Order No.</td>
            <td class="td-header-right" width="6%" align="center">Recycle</td>
            <?php if ($_COOKIE['userid'] == 50 || $_COOKIE['userid'] == 142) : ?>
            <td class="td-header" width="85" align="left">Archive</td>
            <?php endif; ?>
            <td class="td-header-right" width="4%" align="center">&nbsp;</td>
          </tr>
          <?php do {
            $jobid = $row_Recordset3['JobId'];

          ?>
            <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? "odd" : "even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
              <td width="50" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
              <td width="50" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['JobNo']; ?></a></td>
              <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Name_1']; ?></a></td>
              <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Name']; ?></a></td>
              <td align="center" class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php time_schedule($jobid); ?></a></td>
              <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php on_status($con, $jobid); ?></a></td>
              <td align="right" class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Total2']; ?></a></td>
              <td align="center">
                <a href="../fpdf16/inv-preview.php?Id=<?php echo $jobid; ?>" target="_blank" title="View PDF" class="view"></a>
              </td>
              <td align="center">
                <a onClick="return confirmSubmit()" href="../revive.php?Id=<?php echo $row_Recordset3['JobId']; ?>" class="edit"></a>
              </td>
              <td align="center">
                <a href="order-no.php?Id=<?php echo $row_Recordset3['JobId']; ?>" title="Order No" class="po various3"></a>
              </td>
              <td align="center">
                <a href="../inv-recycle-process.php?Id=<?php echo $row_Recordset3['JobId']; ?>" class="icon-recycle" title="Recycle Bin"></a>
              </td>
              <?php if ($_COOKIE['userid'] == 50 || $_COOKIE['userid'] == 142) : ?>
              <td align="center" class="combo row-2-1">
                <a href="?archive=<?=$jobid?>" title="Archive" class="">Archive</a>
              </td>
              <?php endif; ?>
              <td align="center">
                <input name="jobid[]" type="checkbox" id="jobid[]" value="<?php echo $row_Recordset3['JobId']; ?>" >
              </td>
            </tr>
          <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
          <?php unset($_SESSION['jobids']); ?>
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
mysqli_free_result($rs_totals);
mysqli_free_result($Recordset3);
?>