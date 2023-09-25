<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

if (isset($_GET['Reject'])) {

  $jobid = $_GET['Reject'];

  $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
  $row = mysqli_fetch_array($query);

  $quoteno = $row['QuoteNo'];
  $comments = 'Quote No ' . $quoteno . ' Rejected';
  $date = date('Y-m-d H:i:s');
  $comments = $_POST['comment'];

  mysqli_query($con, "INSERT INTO tbl_actual_history (JobId,TechnicianId,Date,Comments) VALUES ('$jobid','62','$date','$comments')") or die(mysqli_error($con));

  // Send to Qued
  mysqli_query($con, "UPDATE tbl_jc SET Status = '3' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}


$Recordset1 = mysqli_query($con, "SELECT * FROM tbl_companies") or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$Recordset2 = mysqli_query($con, "SELECT * FROM tbl_sites") or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$where = '';

if (isset($_GET['Engineer'])) {

  $where = " AND tbl_jc.Reference = '" . $_GET['Engineer'] . "'";
}

type_getter();
system_select();

$sql_where = system_parameters('tbl_jc');

$query_Recordset3 = "
SELECT
	tbl_companies. NAME AS Name_1,
	tbl_companies.Id AS CompanyId,
	tbl_jc.JobNo,
	tbl_jc.QuoteNo,
	tbl_jc.JobDescription,
	tbl_jc.Id,
	tbl_jc.Days,
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
	STATUS = '19'
AND tbl_jc.CompanyId != '0'
AND tbl_jc.InvoiceNo != '0'
$where
$sql_where
GROUP BY
	tbl_jc.JobId
ORDER BY
	tbl_jc.Days ASC";
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

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers WHERE CompanyId = '6' ORDER BY Name ASC") or die(mysqli_error($con));

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

  <link href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
  <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#myTable').DataTable({
        "bFilter": false
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
    <?php type_select($con); ?>
    <?php system_dd($con); ?>
  </div>
  <!-- End Banner -->

  <!-- Navigatiopn -->
  <?php include('../menu/menu.php'); ?>
  <!-- End Navigation -->

  <!-- Breadcrumbs -->
  <div class="td-bread">
    <ul class="breadcrumb">
      <li><a href="#">Job Cards</a></li>
      <li><a href="#">AWT Pre Work PO</a></li>
      <li><a href="#">(<?= jc_awaiting_pre_work_on($con, false); ?>)</a></li>
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

    <!-- Filter -->
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td>
          <form name="form" id="form">
            <select name="jumpMenu" class="select-no-width" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
              <option>Filter</option>
              <?php while ($row_engineers = mysqli_fetch_array($query_engineers)) { ?>
                <option value="awaiting-pre-work-po.php?Engineer=<?php echo $row_engineers['Name']; ?>"><?php echo $row_engineers['Name']; ?></option>
              <?php } ?>
            </select>
          </form>
        </td>
      </tr>
    </table>
    <!-- End Filter -->

    <form action="../PHPExcel/order-numbers.php" method="post" enctype="multipart/form-data" name="form2">
      <div id="list-border">
        <table id="myTable" class="display" width="100%">
          <thead>
            <tr>
              <th>Job No.</th>
              <th>Quote No</th>
              <th>Company</th>
              <th>Site Address</th>
              <th>Age</th>
              <th>Status</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>

            <?php do {
              $jobid = $row_Recordset3['JobId'];
            ?>
              <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? " odd " : "even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                <td width="50" align="center">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php echo $row_Recordset3['JobNo']; ?>
                  </a>
                </td>
                <td width="100" align="center" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php echo $row_Recordset3['QuoteNo']; ?>
                  </a>
                </td>
                <td width="180" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php echo $row_Recordset3['Name_1']; ?>
                  </a>
                </td>
                <td width="284" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php echo $row_Recordset3['Name']; ?>
                  </a>
                </td>
                <td width="50" align="center" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php time_schedule($jobid); ?>
                  </a>
                </td>
                <td width="150" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php on_status($con, $jobid); ?>
                  </a>
                </td>
                <td align="center">
                  <a href="jc-calc.php?Id=<?php echo $jobid; ?>" target="_blank" class="icon-jc"></a>
                </td>
                <td align="center">
                  <a href="../fpdf16/pdf_quotation.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&preview" target="_blank" class="icon-qs"></a>
                </td>
                <td align="center">
                  <a href="../jc-awaiting-pre-work-po.php?Reject=<?php echo $jobid; ?>" class="delete" title="Reject"><i class="fa fa-remove line-height"></i></a>
                </td>
                <td align="center">
                  <a href="javascript:;" onClick="window.open('../jc-pre-order-no.php?Approve=<?php echo $jobid; ?>','winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=200')" class="icon-po" title="Add Order No.">
                  </a>
                </td>
              </tr>
              <?php

              $jobid = NULL;
              $row_Recordset3['JobId'] = NULL;
              $row_Recordset3['JobDescription'] = NULL;
              $row_Recordset3['QuoteNo'] = NULL;


              ?>
            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
            <?php unset($_SESSION['jobids']); ?>

          </tbody>
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
mysqli_free_result($query);
mysqli_free_result($Recordset3);
mysqli_free_result($query_engineers);
?>