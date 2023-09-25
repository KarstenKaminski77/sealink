<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

if (isset($_GET['Id'])) {
  $onload = "onLoad=\"MM_openBrWindow('../costing_mail.php?Id=" . $_GET['Id'] . "','','width=400,height=100')\"'";
}

if (isset($_GET['Status'])) {
  $status = $_GET['Status'];
  $jobid = $_GET['Id'];
  $today = date('Y-m-d');
  mysqli_query($con, "UPDATE tbl_jc SET Status = '$status', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
  header('Location: in-progress.php?Success');
}

logout($con);

if (isset($_POST['master_area'])) {
  $_SESSION['areaid'] = $_POST['master_area'];
  $areaid = $_SESSION['areaid'];
} else {
  $areaid = $_SESSION['areaid'];
}

if ($_SESSION['kt_login_level'] >= 1) {
  if (isset($_SESSION['areaid'])) {
    $areaid = $_SESSION['areaid'];
  } else {
    $_SESSION['areaid'] = '1';
  }
}

if ($_SESSION['kt_login_level'] == 0) {
  $areaid = $_SESSION['kt_AreaId'];
}

if ($_SESSION['kt_login_level'] != 1) {
  $query = mysqli_query($con, "SELECT * FROM tbl_tool_relation WHERE AreaId = '$areaid'") or die(mysqli_error($con));
  $row = mysqli_fetch_array($query);

  if (mysqli_num_rows($query) >= 1) {

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

type_getter();
system_select();

$status = 2;
$page = 'In Progress';
$user_id = $_COOKIE['userid'];
$jcCount = jc_current($con, false);
$whereCond = system_parameters('tbl_jc');
$whereCond .= ($areaid != 5) ? " AND tbl_jc.AreaId = '$areaid' " : '';
$whereCond .= ($_COOKIE['contractor'] == 1) ? " AND tbl_jc.ContractorId = '" . $user_id . "'" : '';

$query = "
SELECT tbl_jc.JobNo, tbl_companies.`Name`, tbl_sites.`Name` AS Address, tbl_systems.`Name` AS System, tbl_sla_cat.Category, tbl_far_risc_classification.Risk, tbl_jc.JobcardPDF, tbl_jc.CommentText, tbl_jc.JobDescription, tbl_jc.JobId, tbl_sla_history.SlaStart, tbl_sla_history.SlaEnd, DATEDIFF(now(), Days) + 1 AS Days, COUNT(tbl_history_relation.PhotoId) AS Photos
FROM tbl_jc 
LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId  
LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId  
LEFT JOIN tbl_sla_history ON tbl_jc.JobId = tbl_sla_history.JobId 
LEFT JOIN tbl_sla_cat ON tbl_jc.SlaCatId = tbl_sla_cat.Id 
LEFT JOIN tbl_systems ON tbl_jc.SystemId = tbl_systems.Id 
LEFT JOIN tbl_far ON tbl_far.JobNo = tbl_jc.JobNo 
LEFT JOIN tbl_far_risc_classification ON tbl_far_risc_classification.Id = tbl_far.RiskType 
LEFT JOIN tbl_history_relation ON tbl_history_relation.JobId = tbl_jc.JobId
WHERE Status = " . $status . " AND Comment = 1 " . $whereCond . "
GROUP BY tbl_jc.JobId 
ORDER BY tbl_jc.Id ASC
";

$results = mysqli_query($con, $query);
include 'header.php';

?>

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

  <div style="margin-bottom:10px">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="16">
          <div class="in-sla-icon"></div>
        </td>
        <td>In SLA</td>
        <td width="20">&nbsp;</td>
        <td width="16">
          <div class="sla-alert-icon"></div>
        </td>
        <td>30% or Less of SLA Remaining</td>
        <td width="20">&nbsp;</td>
        <td width="15">
          <div class="sla-expired-icon"></div>
        </td>
        <td>SLA Expired</td>
      </tr>
    </table>
  </div>

  <div id="list-border">
    <table id="myTable" class="display" width="100%">
      <thead>
        <tr>
          <th>Jobcard</th>
          <th>Company</th>
          <th>Site Address</th>
          <th>Category</th>
          <th>Type</th>
          <th>Risk</th>
          <th>SLA</th>
          <th>Jobcard</th>
          <th>Pics</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($results)) :

          $link = "jc-calc.php?menu=" . $_GET['menu'] . "&Id=" . $row['JobId'] . "&job";
          $class = 'style="background-color:green;"';
          $data = sla_indicator_blink2($row['SlaStart'], $row['SlaEnd']);
          if ($data == ' blink_me') {
            $class = 'class="blink_me" style="background-color:#FFA800;"';
          } else if ($data == ' sla-expired') {
            $class = 'class="sla-expired"  style="background-color:#FF0000;"';
          }
        ?>
          <tr>
            <td>
              <a href="<?= $link; ?>" title="<?= $row['JobDescription']; ?>">
                <?= $row['JobNo']; ?>
              </a>
            </td>
            <td width="30%">
              <a href="<?= $link; ?>" title="<?= $row['CommentText']; ?>">
                <?= $row['Name']; ?>
              </a>
            </td>
            <td>
              <a href="<?= $link; ?>" title="<?= $row['CommentText']; ?>">
                <?= $row['Address']; ?>
              </a>
            </td>
            <td align="center">
              <a href="<?= $link; ?>">
                <?= $row['System']; ?>
              </a>
            </td>
            <td align="center">
              <a href="<?= $link; ?>">
                <?= $row['Category']; ?>
              </a>
            </td>
            <td align="center">
              <a href="<?= $link; ?>">
                <?= $row['Risk']; ?>
              </a>
            </td>
            <td align="center" <?= $class; ?>>
              <span style="color: #FFF;"><?= $row['Days']; ?></span>
            </td>
            </td>
            <td>
              <?= ($row['JobcardPDF']) ? "<a class='icon-pdf fancybox' href='../jc-pdf/" . $row['JobcardPDF'] . "'></a>" : ''; ?>
            </td>
            <td align="center">
              <a href="../photo_view_history.php?Id=<?= $row['JobId']; ?>&photos"><?= $row['Photos']; ?></a>
            </td>
          </tr>
        <?php endwhile; ?>
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
?>