<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

if (isset($_GET['Status'])) {

  $status = $_GET['Status'];
  $jobid = $_GET['Id'];

  mysqli_query($con, "UPDATE tbl_jc SET Status = '$status' WHERE JobId = '$jobid'") or die(mysqli_error($con));

  header('Location: in-progress.php?Success');
}

$user_id = $_COOKIE['userid'];

logout($con);

if ($_SESSION['kt_login_level'] >= 1) {

  if (isset($_SESSION['areaid'])) {

    $areaid = $_SESSION['areaid'];
  } else {

    $_SESSION['areaid'] = '1';
  }
}

$menu = mysqli_query($con, "SELECT * FROM tbl_areas") or die(mysqli_error($con));
$row_menu = mysqli_fetch_assoc($menu);
$totalRows_menu = mysqli_num_rows($menu);

if (isset($_POST['master_area'])) {

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


if ($_SESSION['kt_login_level'] == 0) {

  $areaid = $_SESSION['kt_AreaId'];
}

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$where = "AND tbl_jc.AreaId = " . $areaid . "";

type_getter();
system_select();

$sql_where = system_parameters('tbl_jc');

$query_Recordset3 = "
	SELECT
		tbl_sites.`Name` AS Name_1,
		tbl_jc.QuoteNo,
		tbl_jc.CompanyId,
		tbl_jc.JobDescription,
		tbl_jc.SiteId,
		tbl_jc.CommentText,
		tbl_jc.Date,
		tbl_jc.JobcardPDF,
		tbl_jc.JobNo,
		tbl_jc.JobId,
		tbl_jc.`Status`,
		tbl_companies.`Name`,
		tbl_sla_history.SlaStart,
		tbl_sla_history.SlaEnd
	FROM
		(
			(
				tbl_jc
				LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
			)
			LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
		)
	INNER JOIN tbl_sla_history ON tbl_jc.JobId = tbl_sla_history.JobId
	WHERE
		STATUS = '21'
	AND 
		COMMENT = '1'
		$sql_where
	GROUP BY
		tbl_jc.JobId
	ORDER BY
		tbl_jc.Id ASC";

$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

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
      <li><a href="#">Rework Archives</a></li>
      <li><a href="#">(<?= jc_rework($con, false); ?>)</a></li>
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

    <!-- Messages -->
    <?php if (isset($_GET['Success'])) { ?>
      <div id="banner-success">Job Successfully Closed</div>
    <?php } ?>
    <!-- End Messages -->

    <?php if (($difference >= -3) && ($difference <= 3)) { ?>
      <table align="center">
        <tr>
          <td>
            <p>&nbsp;</p>
            <p align="center">
              <div style="padding-left:2px; border:solid 1px #cccccc; margin:2px; margin-left:35px; width:570px">
                <table width="570" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE" class="combo_bold">
                  <tr>
                    <td align="center" nowrap>
                      <br>
                      <?php echo $warning; ?>
                      <br>
                      <br>
                    </td>
                  </tr>

                </table>
              </div>
            </p>
            <p>&nbsp;</p>
          </td>
        </tr>
      </table>
    <?php } ?>
    <div id="list-border">
      <table id="myTable" class="display" width="100%">
        <thead>
          <tr>
            <th>Jobcard</th>
            <th>Company</th>
            <th>Site Address</th>
            <th>Status</th>
            <th>PDF</th>
            <th>Prt</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_Recordset3 >= 1) { ?>
            <?php do { ?>
              <?php $x++; ?>
              <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? " odd " : "even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                <td width="130">
                  <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php echo $row_Recordset3['JobNo']; ?>
                  </a>
                </td>
                <td width="150">
                  <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu">
                    <?php echo $row_Recordset3['Name']; ?>
                  </a>
                </td>
                <td>
                  <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['CommentText']; ?>">
                    <?php echo $row_Recordset3['Name_1']; ?>
                  </a>
                </td>
                <td align="center">
                  <select name="status" style="color:#3E7DBD" class="tarea-100" id="status" onChange="MM_jumpMenu('parent',this,0)">
                    <?php

                    $query_status = "SELECT * FROM tbl_status WHERE Id <= '3' OR Id = '17' OR Id = '18' OR Id = '19' OR Id = '21'";
                    $query_status = mysqli_query($con, $query_status) or die(mysqli_error($con));
                    while ($row_status = mysqli_fetch_array($query_status)) {

                      $checked = '';

                      if ($row_Recordset3['Status'] == $row_status['Id']) {

                        $checked = 'selected="selected"';
                      }
                    ?>
                      <option value="in-progress.php?Status=<?php echo $row_status['Id']; ?>&amp;Id=<?php echo $row_Recordset3['JobId']; ?>" <?php echo $checked; ?>>
                        <?php echo $row_status['Status']; ?>
                      </option>
                    <?php } ?>
                  </select>
                </td>
                <td width="20" align="center">
                  <?php if ($row_Recordset3['JobcardPDF'] != NULL) { ?>
                    <a href="../jc-pdf/<?php echo $row_Recordset3['JobcardPDF']; ?>" target="_blank" class="icon-pdf" title="View Job Card"></a>
                  <?php } ?>
                </td>
                <td width="20" align="center">
                  <?php
                  $jobno = $row_Recordset3['JobNo'];

                  $query = mysqli_query($con, "SELECT * FROM tbl_far WHERE JobNo = '$jobno'") or die(mysqli_error($con));
                  $numrows = mysqli_num_rows($query);
                  if ($numrows >= 1) { ?>


                    <script type="text/javascript">
                      function printIframePdf<?php echo $x; ?>() {
                        window.frames["printf<?php echo $x; ?>"].focus();
                        try {
                          window.frames["printf<?php echo $x; ?>"].print();
                        } catch (e) {
                          window.print();
                          console.log(e);
                        }
                      }

                      function printObjectPdf<?php echo $x; ?>() {
                        try {
                          document.getElementById('idPdf<?php echo $x; ?>').Print();
                        } catch (e) {
                          printIframePdf<?php echo $x; ?>();
                          console.log(e);
                        }
                      }

                      function idPdf_onreadystatechange() {
                        if (idPdf.readyState === 4)
                          setTimeout(printObjectPdf<?php echo $x; ?>, 1000);
                      }
                    </script>
                    <iframe id="printf<?php echo $x; ?>" name="printf<?php echo $x; ?>" src="http://www.seavest.co.za/inv/jc-pdf/<?php echo $row_Recordset3['JobcardPDF']; ?>" frameborder="0" width="440" height="580" style="width: 440px; height: 580px;display: none;"></iframe>

                    <object id="idPdf<?php echo $x; ?>" width="0" height="0" type="application/pdf" data="http://www.seavest.co.za/inv/jc-pdf/<?php echo $row_Recordset3['JobcardPDF']; ?>">
                      <embed src="http://www.seavest.co.za/inv/jc-pdf/<?php echo $row_Recordset3['JobcardPDF']; ?>" width="0" height="0" type="application/pdf">
                      </embed>
                      <span>PDF plugin is not available.</span>
                    </object>


                    <a onclick="printObjectPdf<?php echo $x; ?>()" title="Print" class="icon-print"></a>

                  <?php } ?>
                </td>
              </tr>
            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <br>
    <div class="KT_bottomnav" align="center">
      <div class="combo">
      </div>
    </div>
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