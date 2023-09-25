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

type_getter();
system_select();

$status = 1;
$page = 'Qued';
$jcCount = jc_que($con, false);
$whereCond = system_parameters('tbl_jc');
$whereCond .= ($areaid != 5) ? " AND tbl_jc.AreaId = '$areaid' " : '';

$query = "
SELECT tbl_jc.JobNo, tbl_companies.Name, tbl_sites.Name AS Address, tbl_systems.Name AS System, tbl_sla_cat.Category, tbl_far_risc_classification.Risk, tbl_jc.JobcardPDF, tbl_jc.CommentText, tbl_jc.JobDescription, tbl_jc.JobId, tbl_sla_history.SlaStart, tbl_sla_history.SlaEnd, DATEDIFF(now(), Days) + 1 AS Days, COUNT(tbl_history_relation.PhotoId) AS Photos
FROM tbl_companies 
LEFT JOIN tbl_jc ON tbl_jc.CompanyId = tbl_companies.Id 
LEFT JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id 
LEFT JOIN tbl_systems ON tbl_jc.SystemId = tbl_systems.Id 
LEFT JOIN tbl_sla_cat ON tbl_jc.SlaCatId = tbl_sla_cat.Id 
LEFT JOIN tbl_far ON tbl_far.JobNo = tbl_jc.JobNo 
LEFT JOIN tbl_far_risc_classification ON tbl_far_risc_classification.Id = tbl_far.RiskType 
LEFT JOIN tbl_sla_history ON tbl_jc.JobId = tbl_sla_history.JobId 
LEFT JOIN tbl_history_relation ON tbl_jc.JobId = tbl_history_relation.JobId
WHERE Status = " . $status . " AND Comment = 1 " . $whereCond . "
GROUP BY tbl_jc.JobId 
ORDER BY tbl_jc.Id ASC
";

$results = mysqli_query($con, $query);
include 'header.php';

?>

  <!-- Main Form -->
  <div id="main-wrapper">

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
            <th>Photos</th>
          </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($results)) :

          $link = "jc-calc.php?menu=" . $_GET['menu'] . "&Id=" . $row['JobId'] . "&job";
          $link1 = "qued-details.php?menu=" . $_GET['menu'] . "&Id=" . $row['JobId'] . "&job";
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
              <a href="<?= $link1; ?>" title="<?= $row['JobDescription']; ?>">
                <?= $row['JobNo']; ?>
              </a>
            </td>
            <td width="30%">
              <a href="<?= $link1; ?>" title="<?= $row['CommentText']; ?>">
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
mysqli_free_result($area);
mysqli_free_result($query);
mysqli_free_result($Recordset3);
?>