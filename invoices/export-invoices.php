<?php
session_start();
//https://thisinterestsme.com/php-export-excel/
require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Seavest Asset Management</title>

  <link href="../css/layout.css" rel="stylesheet" type="text/css" />
  <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>

  <link rel="stylesheet" href="../menu/styles.css">
  <script src="../menu/script.js"></script>

  <script type="text/javascript">
    function MM_jumpMenu(targ, selObj, restore) { //v3.0
      eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
      if (restore) selObj.selectedIndex = 0;
    }

    $(document).ready(function() {
      $(".toggler").click(function(e) {
        e.preventDefault();
        $('.row' + $(this).attr('data-prod-cat')).toggle();
      });
    });
  </script>

  <!-- TinyMCE -->
  <script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript">
    tinymce.init({
      mode: "specific_textareas",
      editor_selector: "mceEditor",
      theme: "modern",
      menubar: false,
      toolbar: false,
      statusbar: false,
      height: 44,
      force_br_newlines: false,
      force_p_newlines: false,
      forced_root_block: '',

      plugins: [
        "textcolor lists link image responsivefilemanager autoresize",
      ],

    });
  </script>
  <!-- End TinyMCE -->

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
      <li><a href="#">Export</a></li>
      <li></li>
    </ul>
  </div>
  <!-- End Breadcrumbs -->

  <!-- Search -->
  <div class="search-container">
    <form id="form1" name="form1" method="post" action="">
      <input name="textfield" type="text" class="search-top" id="textfield" value="Search..." />
      <input name="button" type="submit" class="search-top-btn" id="button" value="" />
    </form>
  </div>
  <!-- End Search -->

  <!-- Main Form -->
  <div id="main-wrapper">
    <form action="exporter.php" method="post" name="form2">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <div id="list-border" style="margin-top:15px">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="2" class="td-header">
                    Monthly Export
                  </td>
                </tr>
                <tr class="row1">
                  <td width="16%" class="td-left">Month To Export</td>
                  <td width="42%" class="td-right">
                    <select name="year" id="year" class="tarea-100">
                      <option>Select year...</option>
                      <?php
                      $year = 2010;
                      while ($year <= date('Y')) {
                        echo '<option value="' . $year . '">' . $year . '</option>';
                        $year++;
                      }
                      ?>
                    </select>
                    <select name="month" id="month" class="tarea-100">
                      <option>Select month...</option>
                      <?php
                      $month = 1;
                      $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                      while ($month <= 12) {
                        echo '<option value="' . $month . '">' . $months[$month - 1] . '</option>';
                        $month++;
                      }
                      ?>
                    </select>
                  </td>
                </tr>
              </table>
            </div>

            <!-- Batch Allocate -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35%">
                  <?php
                  // if ($_GET['rest'] == 0) {
                  //   echo "Please try again, there was an error creating export.";
                  // }
                  // if ($_GET['rest'] == 1) {
                  //   echo "File succesfully exported for download";
                  // }
                  ?>                  
                </td>
                <td width="65%" align="right">
                  <input name="submit" type="submit" class="btn-new" id="allocate" value="Export Data" style="margin-bottom:15px" />
                </td>
              </tr>
            </table>
            <!-- End Batch Allocate -->
          </td>
        </tr>
      </table>
    </form>
    <form action="exporter.php" method="post" name="form2">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <div id="list-border" style="margin-top:15px">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="2" class="td-header">
                    Daily Invoice KPI Export
                  </td>
                </tr>
                <tr class="row1">
                  <td width="16%" class="td-left">Month To Export</td>
                  <td width="42%" class="td-right">
                    <select name="year" id="year" class="tarea-100">
                      <option>Select year...</option>
                      <?php
                      $year = 2010;
                      while ($year <= date('Y')) {
                        echo '<option value="' . $year . '">' . $year . '</option>';
                        $year++;
                      }
                      ?>
                    </select>
                    <select name="month" id="month" class="tarea-100">
                      <option>Select month...</option>
                      <?php
                      $month = 1;
                      $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                      while ($month <= 12) {
                        echo '<option value="' . $month . '">' . $months[$month - 1] . '</option>';
                        $month++;
                      }
                      ?>
                    </select>
                  </td>
                </tr>
              </table>
            </div>

            <!-- Batch Allocate -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35%">
                  <?php
                  // if ($_GET['rest'] == 0) {
                  //   echo "Please try again, there was an error creating export.";
                  // }
                  // if ($_GET['rest'] == 1) {
                  //   echo "File succesfully exported for download";
                  // }
                  ?>                  
                </td>
                <td width="65%" align="right">
                  <input name="submit" type="submit" class="btn-new" id="allocate" value="Export KPI Data" style="margin-bottom:15px" />
                </td>
              </tr>
            </table>
            <!-- End Batch Allocate -->
          </td>
        </tr>
      </table>
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
mysqli_free_result($rows);
?>