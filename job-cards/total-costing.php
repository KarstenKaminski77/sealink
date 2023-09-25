<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

logout($con);

if(isset($_GET['Status'])){

	$status = $_GET['Status'];
	$jobid = $_GET['Id'];

	mysqli_query($con, "UPDATE tbl_jc SET Status = '$status' WHERE JobId = '$jobid'")or die(mysqli_error($con));

	header('Location: total-costing.php?Success');
}

system_select();

$sql_where = system_parameters();

$query_Recordset3 = "
  SELECT
	  tbl_companies. NAME AS Name_1,
	  tbl_companies.Id AS CompanyId,
	  tbl_jc.JobNo,
	  tbl_jc.JobDescription,
	  tbl_jc.Id,
	  tbl_jc.Days,
	  tbl_jc.InvoiceNo,
	  STR_TO_DATE(
		  tbl_jc.InvoiceDate,
		  '%d %M %Y'
	  ) AS date_for_sort,
	  tbl_jc.JobId,
	  tbl_jc.InvoiceQ,
	  tbl_jc.Status,
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
	  STATUS = '18'
  AND tbl_jc.CompanyId != '0'
  AND tbl_jc.InvoiceNo != '0'
  $sql_where
  GROUP BY
	  tbl_jc.JobId
  ORDER BY
	  tbl_jc.Days ASC";

$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error());
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
        <?PHP system_dd($con); ?>
      </div>
      <!-- End Banner -->

      <!-- Navigatiopn -->
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->

      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Job Cards</a></li>
            <li><a href="#">Total Costing</a></li>
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
        <form action="../PHPExcel/order-numbers.php" method="post" enctype="multipart/form-data" name="form2">
          <div id="list-border">
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <?php if(isset($_GET[ 'Success'])){ ?>
              <tr class="td-row">
                <td colspan="7" nowrap>
                  <div id="banner-success">Job Card&nbsp;Successfully Moved</div>
                </td>
              </tr>
              <tr class="td-row">
                <td colspan="7" align="center" nowrap>&nbsp;</td>
              </tr>
              <?php } ?>
              <tr>
                <td width="50" align="center" nowrap class="td-header"><strong>In No. </strong>
                </td>
                <td width="180" align="left" class="td-header"><strong>Company</strong>
                </td>
                <td width="260" align="left" class="td-header"><strong>Site Address </strong>
                </td>
                <td width="50" align="center" class="td-header"><strong>Age</strong>
                </td>
                <td width="80" align="left" class="td-header">Status</td>
                <td width="20" align="center" class="td-header-right">&nbsp;</td>
                <td width="20" align="center" class="td-header-right">&nbsp;</td>
              </tr>
              <?php do { $jobid = $row_Recordset3[ 'JobId']; ?>
              <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                <td width="50" align="center">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php echo $row_Recordset3[ 'InvoiceNo']; ?>
                  </a>
                </td>
                <td width="180" class="combo">
                 <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                  <?php echo $row_Recordset3[ 'Name_1']; ?>
                 </a>
                </td>
                <td class="combo">
                 <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                  <?php echo $row_Recordset3[ 'Name']; ?>
                 </a>
                </td>
                <td align="center" class="combo">
                 <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                  <?php time_schedule($jobid); ?>
                 </a>
                </td>
                <td class="combo">
                  <select name="status" class="tarea-100" id="status" onChange="MM_jumpMenu('parent',this,0)" style="color:#3E7DBD">
                    <?php

                  $query_status = "SELECT * FROM tbl_status WHERE Id <= '3' OR Id = '17' OR Id = '18' OR Id = '19'" ;
                  $query_status = mysqli_query($con, $query_status)or die(mysqli_error($con));
                  while($row_status = mysqli_fetch_array($query_status)){

                      $checked = '';

                      if($row_Recordset3['Status']== $row_status['Id']){

                          $checked='selected="selected"' ;
                      }
                  ?>
                    <option value="../total-costing.php?Status=<?php echo $row_status['Id']; ?>&amp;Id=<?php echo $row_Recordset3['JobId']; ?>" <?php echo $checked; ?>>
                      <?php echo $row_status[ 'Status']; ?>
                    </option>
                    <?php } ?>
                  </select>
                </td>
                <td align="center">
                  <a href="jc-calc.php?Id=<?php echo $jobid; ?>" class="icon-jc" title="View Job Card"></a>
                </td>
                <td align="center">
                  <a href="../quote_calc.php?Costing=<?php echo $row_Recordset3['JobId']; ?>" class="icon-qs" title="Quote"></a>
                </td>
              </tr>
              <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
              <?php unset($_SESSION[ 'jobids']); ?>
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
  mysqli_free_result($query_status);
  mysqli_free_result($Recordset3);
?>
