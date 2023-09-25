<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort, tbl_sent_invoices.PDF, tbl_sent_invoices.DateSent, tbl_sent_invoices.Sent, tbl_jc.JobDescription, tbl_jc.Status, tbl_jc.JobId, tbl_jc.Total2, tbl_companies.Name, tbl_sent_invoices.InvoiceNo AS InvoiceNo_1 FROM (((tbl_sent_invoices LEFT JOIN tbl_jc ON tbl_jc.JobId=tbl_sent_invoices.JobId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.Status=16 GROUP BY tbl_jc.JobId ORDER BY date_for_sort ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

if(isset($_GET['Id'])){

$jobid = $_GET['Id'];

mysqli_query($con, "UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'")or die(mysqli_error($con));
}

$colname_rs_totals = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_rs_totals = $_SESSION['kt_login_id'];
}
mysqli_select_db($database_seavest, $seavest);
$query_rs_totals = "SELECT * FROM tbl_menu_relation WHERE UserId = '$colname_rs_totals' AND MenuId = '47'";
$rs_totals = mysqli_query($con, $query_rs_totals) or die(mysqli_error($con));
$row_rs_totals = mysqli_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysqli_num_rows($rs_totals);

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
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
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
              <li><a href="#">Credit Notes</a></li>
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
          <form action="../fpdf16/pdf/jc_resend_mail.php" method="post" enctype="multipart/form-data" name="form2">
              <div id="list-border">
                  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                      <tr>
                          <td colspan="9" align="left" nowrap class="td-right"><input name="email" type="text" class="tarea-100" id="email" value="To" onfocus="if (this.value=='To') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='To';"></td>
                      </tr>
      
                      <tr>
                          <td colspan="9" align="left" nowrap class="td-right"><input name="attach" type="file" class="tarea-100" id="attach"></td>
                      </tr>
                      <tr>
                          <td colspan="9" align="left" nowrap class="td-right"><textarea name="message" rows="5" class="tarea-100" id="message" onfocus="if (this.value=='Message') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Message';">Message</textarea></td>
                      </tr>
                  </table>
              </div>
              <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                  <tr>
                      <td colspan="9" align="right" nowrap class="combo"><input name="Submit2" type="submit" class="btn-new" value="Send"></td>
                  </tr>
              </table>
              <div id="list-border" style="margin-top:20px;">
                  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                      <tr>
                          <td width="50" align="center" nowrap class="td-header"><strong>In No. </strong></td>
                          <td align="left" class="td-header"><strong>Company</strong></td>
                          <td width="250" align="left" class="td-header"><strong>Site Address </strong></td>
                          <td width="100" align="left" class="td-header"><strong>Date</strong></td>
                          <td width="75" align="right" class="td-header">Total</td>
                          <td width="20" align="center" class="td-header-right">&nbsp;</td>
                          <td width="20" align="center" class="td-header-right">&nbsp;</td>
                          <td width="20" align="center" class="td-header-right">&nbsp;</td>
                          <td width="20" align="center" class="td-header-right">&nbsp;</td>
                      </tr>
                      <?php do {
                      $jobid = $row_Recordset3['JobId'];
                      ?>
                      <tr class="<?php echo ($ac_sw1++%2==0)?" odd":"even"; ?>
                          " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                          <td align="center" nowrap class="combo"><a href="#" class="menu"<?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo_1']; ?></a></td>
                          <td nowrap class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name']; ?></td>
                          <td class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name_1']; ?> </td>
                          <td class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['date_for_sort']; ?></td>
                          <td align="right" nowrap class="combo"<?php debtors_overdue($jobid); ?>>R<?php echo $row_Recordset3['Total2']; ?></td>
                          <td align="center">
                              <a href="fpdf16/pdf/<?php echo $row_Recordset3['PDF']; ?>" target="_blank" class="view"></a>
                          </td>
                          <td align="center">
                              <a onclick="return confirmSubmit()" href="../revive.php?Id=<?php echo $row_Recordset3['JobId']; ?>" class="edit"></a>
                          </td>
                          <td align="center">
                              <a href="../jc_history.php?Id=<?php echo $row_Recordset3['JobId']; ?>" class="icon-info"></a>
                          </td>
                          <td align="center">
                              <?php
      
                              // Check if Pragma and send XL format
      
                              $companyid = $row_Recordset3['CompanyId'];
      
                              if($companyid == 2){
      
								  $value = $row_Recordset3['JobId'];
      
                              } else {
      
								  $value = $row_Recordset3['PDF'];
                              }
      
                              ?>
                              <input name="file[]" type="checkbox" id="file[]" value="<?php echo $value; ?>">
                          </td>
                      </tr>
                      <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                  </table>
              </div>
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
  mysqli_free_result($query);
  mysqli_free_result($query_areas);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
  mysqli_free_result($query_user_menu);
?>