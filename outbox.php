<?php
require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

//mysqli_query($con, "UPDATE tbl_jc SET OrderNoStatus = '0'")or die(mysq1li_error($con));

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset3 = "
SELECT
tbl_companies. NAME AS Name_1,
tbl_companies.Id AS CompanyId,
tbl_jc.JobNo,
tbl_jc.JobDescription,
tbl_jc.Id,
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
STATUS = '11'
AND tbl_jc.CompanyId != '0'
AND tbl_jc.InvoiceNo != '0'
GROUP BY
tbl_jc.JobId
ORDER BY
date_for_sort ASC";
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
    -->
  </style>
  <link href="styles/fonts.css" rel="stylesheet" type="text/css">
  <link href="styles/layout.css" rel="stylesheet" type="text/css">
  <script LANGUAGE="JavaScript">
    <!--
    <!--
    // Nannette Thacker http://www.shiningstar.net
    function confirmSubmit() {
      var agree = confirm("Are you sure you wish to continue?");
      if (agree)
        return true;
      else
        return false;
    }
    // 
    -->
    function
    MM_openBrWindow(theURL, winName, features)
    {
    //v2.0
    window.open(theURL, winName, features);
    }
    //-->
  </script>
  <style>
    #dek {
      POSITION: absolute;
      VISIBILITY: hidden;
      Z-INDEX: 200;
    }
  </style>
</head>

<body>
  <table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="200" valign="top">
        <?php
        include('menu.php'); ?>
      </td>
      <td width="823" valign="top">
        <table width="823" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                <tr>
                  <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
                </tr>

              </table>
            </td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4">
              <form action="PHPExcel/order-numbers.php" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
                <table width="100%" border="0" cellpadding="3" cellspacing="1">
                  <tr>
                    <td colspan="10" nowrap>
                      <input name="email" type="text" class="tarea-100per" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:100%">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left" nowrap>
                      <div id='file_browse_wrapper'>
                        <input name="xls" type='file' id='file_browse'>
                      </div>
                      <input name="upload" type="submit" class="btn-green-xl" id="upload" value="Upload Spreadsheet">
                    </td>
                    <td colspan="7" align="right" nowrap>
                      <input name="test" type="submit" class="btn-blue-generic" id="test" value="Send Test">
                      <input name="engineer" type="submit" class="btn-red-generic" id="engineer" value="Send to Engineers"></td>
                  </tr>
                  <tr>
                    <td colspan="10" align="center" nowrap>&nbsp;</td>
                  </tr>
                  <?php if (isset($_SESSION['mail_to'])) { ?>
                    <tr>
                      <td colspan="10" align="center" nowrap>
                        <div id="banner-success-mail">
                          <span class="success-header">Mail Successfully Sent To:</span>
                          <?php
                          for ($i = 0; $i < count($_SESSION['mail_to']); $i++) {

                            echo $_SESSION['mail_to'][$i];
                          }
                          ?>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="10" align="center" nowrap>&nbsp;</td>
                    </tr>
                  <?php
                  }
                  unset($_SESSION['mail_to']);
                  ?>
                  <?php if (isset($_SESSION['success'])) { ?>
                    <tr>
                      <td colspan="10" align="center" nowrap>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="10" align="center" nowrap><?php echo $_SESSION['success']; ?></td>
                    </tr>
                    <tr>
                      <td colspan="10" align="center" nowrap>&nbsp;</td>
                    </tr>
                  <?php
                  }
                  unset($_SESSION['success']);
                  ?>
                  <tr class="td-header">
                    <td width="50" align="center" nowrap><strong>In No. </strong></td>
                    <td width="180" align="left"><strong>Company</strong></td>
                    <td width="284" align="left"><strong>Site Address </strong></td>
                    <td width="150" align="left"><strong>Date</strong></td>
                    <td width="150" align="left">Status</td>
                    <td width="40" align="center">&nbsp;</td>
                    <td width="40" align="center">&nbsp;</td>
                    <td width="40" align="center">&nbsp;</td>
                    <td width="40" align="center">&nbsp;</td>
                    <td width="40" align="center">&nbsp;</td>
                  </tr>
                  <?php do {

                    $jobid = $row_Recordset3['JobId'];

                  ?><tr class="<?php echo ($ac_sw1++ % 2 == 0) ? "odd" : "even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                      <td width="50" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                      <td width="180" class="combo"><?php echo $row_Recordset3['Name_1']; ?></td>
                      <td width="284" class="combo"><?php echo $row_Recordset3['Name']; ?></td>
                      <td width="150" class="combo"><?php time_schedule($jobid); ?></td>
                      <td width="150" class="combo"><?php on_status($con, $jobid); ?></td>
                      <td width="40" align="center"><a href="fpdf16/inv-preview.php?Id=<?php echo $jobid; ?>" target="_blank"><img title="View PDF" src="images/icons/btn-view.png" width="25" height="25" border="0"></a></td>
                      <td align="center">
                        <a onClick="return confirmSubmit()" href="revive.php?Id=<?php echo $row_Recordset3['JobId']; ?>">
                          <img title="Edit" src="images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
                      <td align="center"><a href="javascript:;" onClick="MM_openBrWindow('order_no.php?Id=<?php echo $row_Recordset3['JobId']; ?>','','width=400,height=400')"><img title="Order No" src="images/icons/btn-add.png" width="25" height="25" border="0"></a></td>
                      <td width="40" align="center"><a href="inv-recycle-process.php?Id=<?php echo $row_Recordset3['JobId']; ?>"></a><a href="inv-recycle-process.php?Id=<?php echo $row_Recordset3['JobId']; ?>"><img src="images/icons/btn-recycle.jpg" width="25" height="25" border="0" title="Recycle Bin"></a></td>
                      <td width="40" align="center">
                        <input name="jobid[]" type="checkbox" id="jobid[]" value="<?php echo $row_Recordset3['JobId']; ?>" <?php if (in_array($row_Recordset3['JobId'], $_SESSION['jobids'])) {
                                                                                                                              echo 'checked="checked"';
                                                                                                                            } ?>></td>
                    </tr>
                  <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                  <?php unset($_SESSION['jobids']); ?>
                  <tr>
                    <td colspan="10" align="right" class="td-header">
                      <?php
                      //if($totalRows_rs_totals >= 1){

                      sum_approved($where);
                      //}
                      ?>
                    </td>
                  </tr>
                </table>
              </form>
              <br><br>
              <div class="KT_bottomnav" align="center">
                <div class="combo"></div>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);
?>