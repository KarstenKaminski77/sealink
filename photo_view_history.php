<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($con, $theValue) : mysqli_escape_string($con, $theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}
require_once('includes/tng/triggers/tNG_DynamicThumbnail.class.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

$con = mysqli_connect('sql25.jnb1.host-h.net', 'kwdaco_43', 'SBbB38c8Qh8', 'seavest_db333');

require_once('functions/functions.php');

select_db();

if (isset($_POST['update'])) {

  for ($i = 0; $i < 1; $i++) {

    $id = explode("_", $_POST['photo-id'][$i]);

    $jobid = $id[1];

    mysqli_query($con, "UPDATE tbl_history_relation SET Active = '0' WHERE JobId = '$jobid'") or die(mysqli_error($con));
  }

  for ($i = 0; $i < count($_POST['photo-id']); $i++) {

    $id = explode("_", $_POST['photo-id'][$i]);

    $photoid = $id[0];
    $jobid = $id[1];

    mysqli_query($con, "UPDATE tbl_history_relation SET Active = '1' WHERE JobId = '$jobid' AND PhotoId = '$photoid'") or die(mysqli_error($con));
  }

  $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND AreaId != '0'") or die(mysqli_error($con));
  $row = mysqli_fetch_array($query);

  header('Location: status-reports/index.php?Company=' . $row['CompanyId'] . '&Site=' . $row['SiteId']);
}

if (isset($_GET['delete'])) {

  $photo = $_GET['delete'];
  $jobid = $_GET['Id'];

  mysqli_query($con, "DELETE FROM tbl_history_photos WHERE Id = '$photo'") or die(mysql_error());
  mysqli_query($con, "DELETE FROM tbl_history_relation WHERE PhotoId = '$photo' AND JobId = '$jobid'") or die(mysql_error());
}

// mysqli_select_db($con, $database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysql_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

// mysqli_select_db($con, $database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysql_error());
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$KTColParam1_Recordset3 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset3 = $_GET["Id"];
}
// mysqli_select_db($con, $database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_history_relation.PhotoId, tbl_history_photos.Photo, tbl_history_relation.JobId FROM (tbl_history_relation LEFT JOIN tbl_history_photos ON tbl_history_photos.Id=tbl_history_relation.PhotoId) WHERE tbl_history_relation.JobId=%s ", GetSQLValueString($KTColParam1_Recordset3, "int"));
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysql_error());
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
// mysqli_select_db($con, $database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT * FROM tbl_reports WHERE QuoteNo = '%s'", $colname_Recordset4);
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysql_error());
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

// Show Dynamic Thumbnail
$objDynamicThumb1 = new tNG_DynamicThumbnail("", "KT_thumbnail1");
$objDynamicThumb1->setFolder("images/history/");
$objDynamicThumb1->setRenameRule("{Recordset3.Photo}");
$objDynamicThumb1->setResize(200, 0, true);
$objDynamicThumb1->setWatermark(false);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SEAVEST AFRICA TRADING CC</title>
  <link href="styles/layout.css" rel="stylesheet" type="text/css" />
  <link href="styles/fonts.css" rel="stylesheet" type="text/css">
  <style type="text/css">
    <!--
    body,
    td,
    th {
      font-family: Arial;
    }

    a {
      font-family: Arial;
      font-size: 11px;
      color: #FFFFFF;
      font-weight: bold;
    }

    a:link {
      text-decoration: none;
    }

    a:visited {
      text-decoration: none;
      color: #FFFFFF;
    }

    a:hover {
      text-decoration: none;
      color: #CCCCCC;
    }

    a:active {
      text-decoration: none;
      color: #FFFFFF;
    }

    .img_border {
      margin: 0px;
      padding: 2px;
      border: 1px solid #0067AA;
    }
    -->
  </style>
  <link href="styles/fonts.css" rel="stylesheet" type="text/css">
  <link href="styles/layout.css" rel="stylesheet" type="text/css">

  <script type="text/javascript" language="javascript">
    // <![CDATA[
    function checkAll(formname, checktoggle) {
      var checkboxes = new Array();
      checkboxes = document[formname].getElementsByTagName('input');

      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == 'checkbox') {
          checkboxes[i].checked = checktoggle;
        }
      }
    }
    // ]]>
  </script>
</head>

<body>
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="200" valign="top">
        <?php
        include('menu.php'); ?>
      </td>
      <td valign="top">
        <table width="750" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center">
              <table width="100%" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
                </tr>

              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <p>
                <br>
                <br>
                <?php if (isset($_GET['photos'])) { ?>
                  <div style="width:750px">
                    <form name="form2" method="post" action="">
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td>
                            <a href="javascript:void();" class="combo" onclick="javascript:checkAll('form2', true);">Check all</a>
                            &nbsp; | &nbsp;
                            <a href="javascript:void();" class="combo" onclick="javascript:checkAll('form2', false);">Uncheck all</a>
                          </td>
                        </tr>
                        <tr>
                          <?php
                          do { // horizontal looper version 3
                          ?>
                            <td align="center">
                              <div style="margin-bottom:40px">&nbsp;
                                <table border="0">
                                  <tr>
                                    <td rowspan="2"><img border="0" src="<?php echo $objDynamicThumb1->Execute(); ?>" class="img_border" /></td>
                                    <td valign="top"><input name="photo-id[]" type="checkbox" id="photo-id[]" value="<?php echo $row_Recordset3['PhotoId'] . '_' . $_GET['Id']; ?>"></td>
                                  </tr>
                                  <tr>
                                    <td valign="bottom"><a href="photo_view_history.php?delete=<?php echo $row_Recordset3['PhotoId']; ?>&amp;Id=<?php echo $_GET['Id']; ?>&amp;photos"><img src="images/no.jpg" width="15" height="15" border="0"></a></td>
                                  </tr>
                                </table>
                              </div>
                            </td>
                          <?php
                            $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
                            if (!isset($nested_Recordset3)) {
                              $nested_Recordset3 = 1;
                            }
                            if (isset($row_Recordset3) && is_array($row_Recordset3) && $nested_Recordset3++ % 3 == 0) {
                              echo "</tr><tr>";
                            }
                          } while ($row_Recordset3); //end horizontal looper version 3
                          ?>
                        </tr>
                        <tr>
                          <td align="right" colspan="3"><input name="update" type="submit" class="btn-blue-generic" id="update" value="Submit"></td>
                        </tr>
                      </table>
                    </form>
              </p>
              </div>
            <?php } else { ?>
              <div style="width:700px; margin-right:30px; text-align:left"><span class="combo"><?php echo $row_Recordset4['Report']; ?></span></div>
            <?php } ?>
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</body>

</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>