<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

logout($con);

  if(isset($_GET['Engineer'])){
	  
	  $where = "AND tbl_jc.Reference = '". $_GET['Engineer'] ."'";
  }
  
  $query_Recordset3 = "
  SELECT
	  tbl_companies. NAME AS Name_1,
	  tbl_companies.Id AS CompanyId,
	  tbl_jc.JobNo,
	  tbl_jc.QuoteNo,
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
	  STATUS = '17'
  AND tbl_jc.CompanyId != '0'
  AND tbl_jc.InvoiceNo != '0'
  $where
  GROUP BY
	  tbl_jc.JobId
  ORDER BY
	  tbl_jc.Days ASC";
	  
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error());
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers WHERE CompanyId = '6' ORDER BY Name ASC")or die(mysqli_error($con));

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
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Job Cards</a></li>
            <li><a href="#">AWT Post Work PO</a></li>
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
      
      <!-- Filter -->
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td>
          <form name="form" id="form">
            <select name="jumpMenu" class="select-no-width" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
              <option>Filter</option>
              <?php while($row_engineers = mysqli_fetch_array($query_engineers)){ ?>
                <option value="awaiting-post-work-po.php?Engineer=<?php echo $row_engineers['Name']; ?>"><?php echo $row_engineers['Name']; ?></option>
              <?php } ?>
            </select>
          </form>
          </td>
        </tr>
      </table>
      <!-- End Filter -->

        <form action="../PHPExcel/order-numbers.php" method="post" enctype="multipart/form-data" name="form2">
          <div id="list-border">
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr>
                <td class="td-header" width="50" align="center" nowrap><strong>Job No. </strong>
                </td>
                <td class="td-header" width="100" align="center" nowrap>Quote No</td>
                <td class="td-header" width="180" align="left"><strong>Company</strong>
                </td>
                <td class="td-header" width="284" align="left"><strong>Site Address </strong>
                </td>
                <td class="td-header" width="50" align="center"><strong>Age</strong>
                </td>
                <td class="td-header" width="150" align="left">Status</td>
                <td class="td-header-right" width="20" align="center">&nbsp;</td>
                <td class="td-header-right" width="20" align="center">&nbsp;</td>
                <td class="td-header-right" width="20" align="center">&nbsp;</td>
              </tr>
              <?php 

            do {
				
                $jobid= $row_Recordset3[ 'JobId']; 
            ?>
              <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                <td width="50" align="center">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                    <?php echo $row_Recordset3['JobNo']; ?>
                  </a>
                </td>
                <td width="100" align="center" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                   <?php echo $row_Recordset3['QuoteNo']; ?>
                  </a>
                </td>
                <td width="180" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                   <?php echo $row_Recordset3['Name_1']; ?>
                  </a>
                </td>
                <td width="284" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                   <?php echo $row_Recordset3['Name']; ?>
                  </a>
                </td>
                <td width="50" align="center" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                   <?php time_schedule($jobid); ?>
                  </a>
                </td>
                <td width="150" class="combo">
                  <a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
                   <?php on_status($con, $jobid); ?>
                  </a>
                </td>
                <td align="center">
                  <a href="jc-calc.php?Id=<?php echo $jobid; ?>" target="_blank" title="View Job Card" class="icon-jc"></a>
                </td>
                <td align="center">
                  <a href="../fpdf16/pdf_quotation.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&preview" target="_blank" class="icon-qs" title="Quote"></a>
                </td>
                <td align="center">
                  <a href="javascript:;" onClick="window.open('../jc-order-no.php?Id=<?php echo $row_Recordset3['JobId']; ?>','winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=200')" class="icon-po" title="Order No"></a>
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
  mysqli_free_result($query_engineers);
?>