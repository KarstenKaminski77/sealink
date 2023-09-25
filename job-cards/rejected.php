<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

if (isset($_POST['master_area'])) {

  $_SESSION['areaid'] = $_POST['master_area'];
  $areaid = $_SESSION['areaid'];
} else {

  $areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$query_area = sprintf("SELECT * FROM tbl_areas WHERE Id = '$colname_area'");
$area = mysqli_query($con, $query_area) or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if ($_SESSION['kt_login_level'] >= 1) {

  if (isset($_SESSION['areaid'])) {

    $areaid = $_SESSION['areaid'];
  } else {

    $areaid = 1;
  }
} else {

  $areaid = $_SESSION['kt_AreaId'];
}

$where = "AND tbl_jc.AreaId = " . $areaid . "";

type_getter();
system_select();

$sql_where = system_parameters('tbl_jc');

$query_Recordset3 = "
  SELECT
	  tbl_sites.Name AS Name_1,
	  tbl_jc.QuoteNo,
	  tbl_jc.CompanyId,
	  tbl_jc.SiteId,
	  tbl_jc.Date,
	  tbl_jc.JobDescription,
	  tbl_jc.JobNo,
	  tbl_jc.JobId,
	  tbl_companies.Name
  FROM
	  (
		  (
			  tbl_jc
			  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		  )
		  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
	  )
  WHERE
	  Status = '5'
	  $where
	  $sql_where
  GROUP BY
	  tbl_jc.JobId";

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

    function MM_openBrWindow(theURL, winName, features) { //v2.0
      window.open(theURL, winName, features);
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
      <li><a href="#">Rejected</a></li>
      <li><a href="#">(<?= jc_rejected($con, false); ?>)</a></li>
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
    <div id="list-border">
      <table id="myTable" class="display" width="100%">
        <thead>
          <tr>
            <th>Jobcard</th>
            <th>Company</th>
            <th>Site Address</th>
            <th>Age</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_Recordset3 >= 1) { ?>
            <?php do { ?>
              <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? "odd" : "even"; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                <td><a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&amp;Id=<?php echo $row_Recordset3['JobId']; ?>&amp;job" class="menu" onmouseover="popup('<?php echo $row_Recordset3['JobDescription']; ?>','#CEE1F7')" ; onmouseout="kill()"> <?php echo $row_Recordset3['JobNo']; ?> </a></td>
                <td><a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&amp;Id=<?php echo $row_Recordset3['JobId']; ?>&amp;job" class="menu"> <?php echo $row_Recordset3['Name']; ?> </a></td>
                <td><a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&amp;Id=<?php echo $row_Recordset3['JobId']; ?>&amp;job" class="menu"> <?php echo $row_Recordset3['Name_1']; ?> </a></td>
                <td align="center"><a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&amp;Id=<?php echo $row_Recordset3['JobId']; ?>&amp;job" class="menu">
                    <?php time_schedule($jobid); ?>
                  </a></td>
                <td width="40" align="center" style="cursor: hand">
                  <a class="icon-info" onclick="MM_openBrWindow('../reason.php?Id=<?php echo $row_Recordset3['JobId']; ?>','','scrollbars=yes,width=400,height=400')"></a>
                </td>
              </tr>
              <?php

              $row_Recordset3['JobDescription'] = NULL;
              $row_Recordset3['JobId'] = NULL;
              $row_Recordset3['JobNo'] = NULL;
              $row_Recordset3['Name_1'] = NULL;

              ?>
            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
          <?php } ?>
        </tbody>
      </table>
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