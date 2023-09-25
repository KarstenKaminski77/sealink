<?php
session_start();

require_once('Connections/seavest.php');
require_once('functions/functions.php');

// Print Invoices
if (isset($_POST['files'])) {

  $files = array();

  for ($i = 0; $i < count($_POST['files']); $i++) {

    array_push($files, $_POST['files'][$i]);
  }

  $_SESSION['files'] = $files;

  //header('Location: fpdf16/print.php');

  echo '<script type="text/javascript" language="Javascript">window.open("fpdf16/print.php");</script>';
}

// Change status
if (isset($_GET['Status'])) {

  $status = $_GET['Status'];
  $jobid = $_GET['Id'];
  $today = date('Y-m-j');

  mysqli_query($con, "UPDATE tbl_jc SET Status = '$status', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));

  if ($status == '12') {  // If sent to approved 

    $query = mysqli_query($con, "SELECT tbl_sites.Name AS Name_1, tbl_jc.InvoiceNo, tbl_companies.Name, tbl_jc.JobId FROM ((tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) WHERE tbl_jc.JobId = '$jobid'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);

    $invoice = $row['InvoiceNo'];
    $company = $row['Name'];
    $site = $row['Name_1'];
    $pdf = 'Seavest Invoice ' . $invoice . '.pdf';
    $sent = date('d M Y H:i:s');

    mysqli_query($con, "INSERT INTO tbl_sent_invoices (InvoiceNo,CompanyId,SiteId,JobId,PDF,DateSent) VALUES ('$invoice','$company','$site','$jobid','$pdf','$sent')") or die(mysqli_error($con));

    header('Location: fpdf16/approved-pdf.php?Id=' . $jobid . '&Search');
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

if (isset($_POST['Submit2'])) {

  // Get search parameters
  $where = "WHERE 1=1";

  if ($_POST['invoiceno'] != 'Invoice No') {

    $where .= " AND tbl_jc.InvoiceNo = " . $_POST['invoiceno'] . " AND tbl_jc.CompanyId != '0'";
  }

  if ($_POST['jobno'] != 'Job No') {

    $where .= " AND tbl_jc.JobNo = '" . $_POST['jobno'] . "' AND tbl_jc.CompanyId != '0'";
  }

  if (!empty($_POST['company'])) {

    $where .= " AND tbl_jc.CompanyId = '" . $_POST['company'] . "'";
  }

  if (!empty($_POST['month'])) {

    $where .= " AND MONTH(STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y')) = '" . $_POST['month'] . "'";
  }

  if (!empty($_POST['year'])) {

    $where .= " AND YEAR(STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y')) = '" . $_POST['year'] . "'";
  }
} else {

  $where = "WHERE tbl_jc.JobNo = '-1'";
}

if (isset($_GET['Search'])) {

  $where = "WHERE tbl_jc.JobId = '" . $_GET['Search'] . "'";
}

if (isset($_POST['search-field'])) {

  $search = $_POST['search-field'];

  $where = " WHERE tbl_jc.JobDescription LIKE '%$search%' OR tbl_jc.JobNo LIKE '$search%' OR tbl_jc.InvoiceNo LIKE '$search%' OR tbl_sites.Name LIKE '%$search%' AND tbl_jc.CompanyId != '0'";
}

$query_Recordset3 = "
  SELECT
	  tbl_sites.Name AS Name_1,
	  tbl_status.Id AS Id_1,
	  tbl_sites.Address,
	  tbl_companies.Name,
	  tbl_jc.Id,
	  tbl_jc.JobNo,
	  tbl_jc.BatchNo,
	  tbl_jc.InvoiceDate,
	  tbl_jc.InvoiceNo,
	  tbl_jc.JobId,
    tbl_systems.Name AS System,
    tbl_sla_cat.Category,
	  tbl_status.Status
  FROM tbl_jc 
  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId 
  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
  LEFT JOIN tbl_status ON tbl_status.Id = tbl_jc.Status
  LEFT JOIN tbl_systems ON tbl_jc.SystemId = tbl_systems.Id
  LEFT JOIN tbl_sla_cat ON tbl_jc.SlaCatId = tbl_sla_cat.Id 
  $where
  GROUP BY
	  tbl_jc.JobId
  ORDER BY
	  tbl_jc.InvoiceNo DESC";
    // die($query_Recordset3);

$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_Recordset5 = "SELECT * FROM tbl_status WHERE Id >= 7 AND Id <= 12 AND Id != '10'";
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$query_companies = mysqli_query($con, "SELECT * FROM tbl_companies") or die(mysqli_error($con));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Seavest Asset Management</title>

  <link href="css/layout.css" rel="stylesheet" type="text/css" />
  <link href="css/fonts.css" rel="stylesheet" type="text/css" />
  <link href="css/breadcrumbs.css" rel="stylesheet" type="text/css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

  <link rel="stylesheet" href="menu/styles.css">
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <script src="menu/script.js"></script>

  <link href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
  <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#myTable').DataTable({
        "bFilter": false,
        // "order": [ 6, 'desc' ]
      });
    });
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
  <?php include('menu/menu.php'); ?>
  <!-- End Navigation -->

  <!-- Breadcrumbs -->
  <div class="td-bread">
    <ul class="breadcrumb">
      <li><a href="#">Seavest Asset Management</a></li>
      <li><a href="#">Accounts</a></li>
      <li><a href="#">Invoices</a></li>
      <li><a href="#">Search</a></li>
      <li></li>
    </ul>
  </div>
  <!-- End Breadcrumbs -->

  <!-- Search -->
  <div class="search-container">
    <form id="form1" name="form1" method="post" action="search.php">
      <input name="search-field" type="text" class="search-top" id="search-field" placeholder="Search..." />
      <input name="button" type="submit" class="search-top-btn" id="button" value="" />
    </form>
  </div>
  <!-- End Search -->

  <!-- Main Form -->
  <div id="main-wrapper">
    <form name="form2" method="post" action="search.php">
      <p>&nbsp;</p>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table border="0" cellpadding="2" cellspacing="3">
              <tr>
                <td width="171" class="btn-blue-generic"><input name="jobno" type="text" class="select" id="jobno" onfocus="if(this.value=='Job No'){this.value=''}" onblur="if(this.value==''){this.value='Job No'}" value="Job No" /></td>
                <td width="171" class="btn-blue-generic"><input name="invoiceno" type="text" class="select" id="invoiceno" onfocus="if(this.value=='Invoice No'){this.value=''}" onblur="if(this.value==''){this.value='Invoice No'}" value="Invoice No" /></td>
                <td width="180" class="btn-blue-generic">
                  <select name="company" class="select" id="company">
                    <option value="">Oil Company</option>
                    <?php while ($row_companies = mysqli_fetch_array($query_companies)) { ?>
                      <option value="<?php echo $row_companies['Id']; ?>" <?php if ($row_companies['Id'] == $_POST['company']) {
                                                                            echo 'selected="selected"';
                                                                          } ?>><?php echo $row_companies['Name']; ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td width="130" class="btn-blue-generic"><select name="month" class="select" id="month">
                    <option value="">Month</option>
                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                      <option value="<?php echo date('m', mktime(0, 0, 0, $i, 0, 0)); ?>" <?php if (date('m', mktime(0, 0, 0, $i, 0, 0)) == $_POST['month']) {
                                                                                        echo 'selected="selected"';
                                                                                      } ?>><?php echo date('F', mktime(0, 0, 0, $i, 0, 0)); ?></option>
                    <?php } ?>
                  </select></td>
                <td width="130" class="btn-blue-generic"><select name="year" class="select" id="year">
                    <option value="">Year</option>
                    <option value="<?php echo date('Y', strtotime('-1 year')); ?>" <?php if (date('Y', strtotime('-1 year')) == $_POST['year']) {
                                                                                      echo 'selected="selected"';
                                                                                    } ?>><?php echo date('Y', strtotime('-1 year')); ?></option>
                    <option value="<?php echo date('Y'); ?>" <?php if (date('Y') == $_POST['year']) {
                                                                echo 'selected="selected"';
                                                              } ?>><?php echo date('Y'); ?></option>
                    <option value="<?php echo date('Y', strtotime('+1 year')); ?>" <?php if (date('Y', strtotime('+1 year')) == $_POST['year']) {
                                                                                      echo 'selected="selected"';
                                                                                    } ?>><?php echo date('Y', strtotime('+1 year')); ?></option>
                  </select></td>
              </tr>
            </table>
          </td>
          <td align="right"><span class="btn-blue-generic">
              <input name="Submit2" type="submit" class="btn-new-2" id="Submit" value="Search" />
            </span></td>
        </tr>
      </table>
    </form>

    <form name="form3" method="post" action="">
      <div id="list-border">

        <table id="myTable" class="display" width="100%">
          <thead>
            <tr>
              <th>Invoice</th>
              <th>Company</th>
              <th>Site Address</th>
              <th>Category</th>
              <th>Type</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <?php while ($row = mysqli_fetch_assoc($Recordset3)) : ?>
            <tr>
              <td>
                <a href="invoices/inv-calc.php?Id=<?= $row['JobId']; ?>&job" title="<?= $row['JobDescription']; ?>">
                  <?= $row['InvoiceNo']; ?>
                </a>
              </td>
              <td>
                <a href="invoices/inv-calc.php?Id=<?= $row['JobId']; ?>&job">
                  <?= $row['Name']; ?>
                </a>
              </td>
              <td>
                <a href="invoices/inv-calc.php?Id=<?= $row['JobId']; ?>&job">
                  <?= $row['Name_1']; ?>
                </a>
              </td>
              <td>
                <a href="invoices/inv-calc.php?Id=<?= $row['JobId']; ?>&job">
                  <?= $row['System']; ?>
                </a>
              </td>
              <td>
                <a href="invoices/inv-calc.php?Id=<?= $row['JobId']; ?>&job">
                  <?= $row['Category']; ?>
                </a>
              </td>
              <td>
                <a href="invoices/inv-calc.php?Id=<?= $row['JobId']; ?>&job">
                  <?= $row['Status']; ?>
                </a>
              </td>
              <td>
                <a href="invoices/inv-calc.php?Id=<?= $row['JobId']; ?>&job">
                  <?= $row['InvoiceDate']; ?>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
          <tbody>
          </tbody>
        </table>

        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <div id="list-brdr-supprt">
                <table width="100%" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td class="td-header" width="50" align="left" nowrap><strong>&nbsp;Invoice </strong>
                    </td>
                    <td class="td-header" width="50" align="left" nowrap>Batch No.</td>
                    <td class="td-header" width="150" align="left"><strong>&nbsp;Company</strong>
                    </td>
                    <td class="td-header" align="left"><strong>&nbsp;Site Address </strong>
                    </td>
                    <td class="td-header" width="150" align="left"><strong>&nbsp;Date</strong>
                    </td>
                    <td class="td-header" width="150" align="left">&nbsp;Status</td>
                    <td class="td-header-right" width="20" align="center"><input type="checkbox" id="checkAll" /></td>
                  </tr>
                  <?php do { ?>
                    <tr class="even">
                      <td align="left" nowrap><a href="invoices/inv-calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"> &nbsp;<?php echo $row_Recordset3['InvoiceNo']; ?></a>
                      </td>
                      <td align="left" nowrap>&nbsp;<?php echo $row_Recordset3['BatchNo']; ?></td>
                      <td align="left"><a href="invoices/inv-calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['Name']; ?></a>
                      </td>
                      <td align="left"><a href="invoices/inv-calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['Name_1']; ?></a>
                      </td>
                      <td width="150" align="left" nowrap><a href="invoices/inv-calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"> &nbsp;<?php echo $row_Recordset3['InvoiceDate']; ?></a>
                      </td>
                      <td width="150" align="left" nowrap>
                        <select name="status" class="tarea-100" id="status" style="color:#3E7DBD" onChange="MM_jumpMenu('parent',this,0)">
                          <option value="">Select one...</option>
                          <?php do { ?>
                            <option value="search.php?Status=<?php echo $row_Recordset5['Id'] ?>&amp;Id=<?php echo $row_Recordset3['JobId']; ?>" <?php if (!(strcmp($row_Recordset5['Id'], $row_Recordset3['Id_1']))) {
                                                                                                                                                  echo "selected=\"selected\"";
                                                                                                                                                } ?>>
                              <?php echo $row_Recordset5['Status'] ?>
                            </option>
                          <?php } while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5));
                          $rows = mysqli_num_rows($Recordset5);

                          if ($rows > 0) {

                            mysqli_data_seek($Recordset5, 0);
                            $row_Recordset5 = mysqli_fetch_assoc($Recordset5);
                          }
                          ?>
                        </select>
                        <input name="jobid" value="<?php echo $row_Recordset3['JobId']; ?>" type="hidden" id="jobid">
                      </td>
                      <td align="center"><input name="files[]" type="checkbox" id="files[]" value="<?php echo $row_Recordset3['JobId']; ?>" /></td>
                    </tr>
                  <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                </table>
              </div>
            </td>
          </tr>
        </table> -->
      </div>
      <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="right"><input name="button2" type="submit" class="btn-new" id="button2" value="Print Selected Invoices" /></td>
        </tr>
      </table> -->
    </form>
  </div>
  <!-- End Main Form -->

  <!-- Footer -->
  <div id="footer"><a href="support/index.php"><img src="images/KWD-SS.png" width="200" height="24" /></a></div>
  <!-- End Footer -->

</body>

</html>
<?php
mysqli_close($con);
mysqli_free_result($query);
mysqli_free_result($Recordset1);
mysqli_free_result($Recordset2);
mysqli_free_result($Recordset3);
mysqli_free_result($Recordset4);
mysqli_free_result($Recordset5);
?>