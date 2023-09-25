<?php
session_start();
require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

$pending = '';

if (isset($_GET['Pending'])) {

  $param = '&Pending';
}

if (isset($_POST['Submit'])) {

  $errors = '';

  if (empty($_POST['orderno'])) {

    $errors .= "Please enter an order number!" . "<br>";
  }

  if (empty($_POST['inv_date'])) {

    $errors .= "Please enter an invoice date!" . "<br>";
  }

  if (empty($errors)) {

    $orderno = $_POST['orderno'];
    $jobid = $_GET['Id'];
    $date = date('d M Y', strtotime($_POST['inv_date']));

    mysqli_query($con, "UPDATE tbl_jc SET RefNo = '$orderno', InvoiceDate = '$date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
    //echo '../fpdf16/test.php?order&Id='. $jobid.$param;

    echo '<script>parent.document.location.href="../fpdf16/test.php?order&Id=' . $jobid . $param . '"</script>';
    echo "<script>parent.jQuery.fancybox.close();</script>";
    //header('Location: ../fpdf16/test.php?order&Id='. $jobid.$param);
  }
}
$jobid = $_GET['Id'];

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

if (!empty($row['InvoiceDate'])) {

  $inv_date = date('Y-m-d', strtotime($row['InvoiceDate']));
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Untitled Document</title>

  <link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../styles/layout.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />

  <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>

</head>

<table width="500" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="500" valign="middle">
      <form id="form1" name="form1" method="post" action="order-no.php?Id=<?php echo $_GET['Id'] . $param; ?>">
        <div align="center" class="big2">Invoice No: <?php echo $row['InvoiceNo']; ?></div>
        <br />
        <?php

        if (!empty($errors)) {

          echo '<div class="alert" style="text-align:center">' . $errors . '<br></div>';
        }

        ?>
        <table width="375" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <div id="list-brdr">
                <table width="375" border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td width="75" class="td-left">Order&nbsp;No</td>
                    <td width="300" class="td-right"><input name="orderno" type="text" class="tarea-new-100" id="orderno" value="<?php echo $row['RefNo']; ?>" /></td>
                  </tr>
                  <tr>
                    <td width="75" class="td-left">Inv Date</td>
                    <td align="right" class="td-right">
                      <input name="inv_date" class="tarea-new-100" id="inv_date" value="<?php echo $inv_date; ?>" />

                      <script type="text/javascript">
                        $('#inv_date').datepicker({
                          dateFormat: "yy-mm-dd"
                        });
                      </script>

                    </td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
        </table>
        <table width="375" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right"><input name="Submit" type="submit" class="btn-new" value="Submit" /></td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>

<body>

</html>
<?php
mysqli_close($con);
mysqli_free_result($query);
?>