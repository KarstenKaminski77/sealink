<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

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

if ($_SESSION['kt_login_level'] != 1) {

  $query = mysqli_query($con, "SELECT * FROM tbl_tool_relation WHERE AreaId = '$areaid'") or die(mysqli_error($con));
  $row = mysqli_fetch_array($query);
  $tool_numrows = mysqli_num_rows($query);

  if ($tool_numrows >= 1) {

    if ($row['Date'] != "Array") {;

      $date1 = $row['Date'];

      echo $date1;

      $date = explode("-", $date1);

      $year = $date[0];
      $month = $date[1];
      $day = $date[2];

      $current_year = date('Y');
      $current_month = date('m');
      $current_day = date('d');

      $past_date = mktime(0, 0, 0, $month, $day, $year);

      $current_date = mktime(0, 0, 0, $current_month, $current_day, $current_year);

      $difference =  floor(($past_date - $current_date) / 86400);

      if ($difference == 0) {

        $warning =  "Your tool report is due today";
      } elseif (($difference <= 3) && ($difference >= 1)) {

        $warning =  $difference . " days to go untill your tool report is due...";
      } elseif ($difference <= -1) {

        $overdue = $difference * -1;

        $warning =  "<span style=\"color: #FF0000\">Your tool report is " . $overdue . " days overdue...</span>";
      }

      if ($difference <= -3) {

        header('Location: alert.php');
      }
    }
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

type_getter();
system_select();

$sql_where = system_parameters('tbl_jc');

$where = "AND tbl_jc.AreaId = " . $areaid . "";

$query_Recordset3 = "
	SELECT
		tbl_sites.Name AS Name_1,
		tbl_jc.QuoteNo,
		tbl_jc.CompanyId,
		tbl_jc.JobDescription,
		tbl_jc.SiteId,
		tbl_jc.CommentText,
		tbl_jc.Date,
		tbl_jc.JobcardPDF,
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
		Status = '20'
	AND
		Comment = '1'
		$where
		$sql_where
	GROUP BY
		tbl_jc.JobId
	ORDER BY
		tbl_jc.Id ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

if (isset($_GET['Id'])) {

  $onload = "onLoad=\"MM_openBrWindow('../costing_mail.php?Id=" . $_GET['Id'] . "','','width=400,height=100')\"'";
}
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
      <li><a href="#">Pending Awaiting PWK</a></li>
      <li><a href="#">(<?= jc_paperwork($con, false); ?>)</a></li>
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
      <table id="myTable" width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
        <thead>
          <tr>
            <th width="130" nowrap><strong>Jobcard </strong></th>
            <th width="150"><strong>Company</strong></th>
            <th><strong>Site Address </strong></th>
            <th width="20" align="center"><strong>Age</strong></th>
            <th align="center"></th>
            <th align="center"></th>
            <th align="center"></th>
          </tr>
        </thead>
        <tbody>

          <?php if ($totalRows_Recordset3 >= 1) { ?>
            <?php do { ?>
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
                  <a href="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu">
                    <?php time_schedule($jobid); ?>
                  </a>
                </td>
                <td width="20" align="center">
                  <?php if ($row_Recordset3['JobcardPDF'] != NULL) { ?>
                    <a href="jc-pdf/<?php echo $row_Recordset3['JobcardPDF']; ?>" target="_blank" class="icon-pdf" title="View Job Card"></a>
                  <?php } ?>
                </td>
                <td width="20" align="center">
                  <?php
                  $jobno = $row_Recordset3['JobNo'];

                  $query = mysqli_query($con, "SELECT * FROM tbl_far WHERE JobNo = '$jobno'") or die(mysqli_error($con));
                  $numrows = mysqli_num_rows($query);
                  if ($numrows >= 1) { ?>

                    <a href="../far-print.php?Id=<?php echo $row_Recordset3['JobNo']; ?>" title="Print" class="icon-print"></a>

                  <?php } ?>
                </td>
                <td width="20">
                  <?php
                  $jobid = $row_Recordset3['JobId'];

                  $query = "
                    SELECT
                        tbl_history_relation.PhotoId,
                        tbl_history_photos.Photo,
                        tbl_history_relation.JobId
                    FROM
                        (
                            tbl_history_relation
                            LEFT JOIN tbl_history_photos ON tbl_history_photos.Id = tbl_history_relation.PhotoId
                        )
                    WHERE
                        tbl_history_relation.JobId = '$jobid'";
                  $query = mysqli_query($con, $query) or die(mysqli_error($con));
                  $numrows = mysqli_num_rows($query);

                  if ($numrows >= 1) {

                    echo '
                    <table cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td>
                          <a href="../photo_view_history.php?Id=' . $row_Recordset3['JobId'] . '&photos" class="icon-camera" title="View Gallery"><i class="fa fa-camera line-height"></i>
                          </a>
                        </td>
                        <td align="right">&nbsp;<a href="../photo_view_history.php?Id=' . $row_Recordset3['JobId'] . '&photos" class="menu" title="View Gallery">(' . $numrows . ')</a>
                        </td>
                      </tr>
                    </table>';
                  } else {

                    echo '
                    <table cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          <div class="icon-circle" title="View Gallery"><i class="fa fa-camera line-height"></i>
                          </div>
                        </td>
                      </tr>
                    </table>';
                  }

                  ?>
                </td>
              </tr>
              <?php

              $jobid = NULL;
              $row_Recordset3['JobId'] = NULL;
              $row_Recordset3['JobDescription'] = NULL;
              $row_Recordset3['CommentText'] = NULL;
              $row_Recordset3['QuoteNo'] = NULL;
              $row_Recordset3['JobcardPDF'] = NULL;
              $row_Recordset3['JobNo'] = NULL;


              ?>
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