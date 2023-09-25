<?php 
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php'); 

$query_Recordset3 = "
  SELECT
	  tbl_companies.Name AS Name_1,
	  tbl_jc.JobNo,
	  tbl_jc.JobDescription,
	  tbl_jc.Id,
	  tbl_jc.InvoiceNo,
	  STR_TO_DATE(tbl_jc.InvoiceDate,'%d %M %Y') AS date_for_sort,
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
	  tbl_jc.Status = '7'
  AND tbl_jc.CompanyId != '0'
  GROUP BY
	  tbl_jc.JobId
  ORDER BY
	  date_for_sort ASC";
	  
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

if(isset($_GET['Id'])){
	
	$jobid = $_GET['Id'];
	
	mysqli_query($con, "UPDATE tbl_jc SET Status = '12' WHERE JobId = '$jobid'")or die(mysqli_error());
	
	$jobid = $_GET['Id'];

	$query = mysqli_query($con, "SELECT tbl_sites.Name AS Name_1, tbl_jc.InvoiceNo, tbl_companies.Name, tbl_jc.JobId FROM ((tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) WHERE tbl_jc.JobId = '$jobid'")or die(mysqli_error());
	$row = mysqli_fetch_array($query);

	$invoice = $row['InvoiceNo'];
	$company = $row['Name'];
	$site = $row['Name_1'];
	$pdf = 'Seavest Invoice '.$invoice.'.pdf';
	$sent = date('d M Y H:i:s');

	mysqli_query($con, "INSERT INTO tbl_sent_invoices (InvoiceNo,CompanyId,SiteId,JobId,PDF,DateSent) VALUES ('$invoice','$company','$site','$jobid','$pdf','$sent')")or die(mysqli_error());

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
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
	  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
      <script>!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');</script>
      <script type="text/javascript" src="../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
      <script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
      <link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
      
	  <script type="text/javascript">
      
        jQuery(document).ready(function() {	
            
                    $(".various3").fancybox({
                        'width'				: 500,
                        'height'			: 230,
                        'autoScale'			: true,
                        'transitionIn'		: 'none',
                        'transitionOut'		: 'none',
                        'type'				: 'iframe',
                        'padding'           : 0,
                        'overlayOpacity'    : '0.8',
                        'overlayColor'      : 'black',
        
                    });
                    
                    
            
        });
      </script>
      
      <script LANGUAGE="JavaScript">
    
      function confirmSubmit(){
          
          var agree=confirm("Are you sure you wish to continue?");
          
          if (agree)
          
          return true ;
      else
          return false ;
      }
      
      function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
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
            <li><a href="#">Awaiting Order No</a></li>
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
        <form action="../fpdf16/pdf/test.php" method="post" enctype="multipart/form-data" name="form2">
          <div id="list-border">
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr>
                <td class="td-header" width="50" align="center" nowrap><strong>In No. </strong></td>
                <td class="td-header" width="200" align="left"><strong>Company</strong></td>
                <td class="td-header" align="left"><strong>Site Address </strong></td>
                <td class="td-header" align="center"><strong>Age</strong></td>
                <td class="td-header-right" width="30" align="center">&nbsp;</td>
                <td class="td-header-right" width="30" align="center">&nbsp;</td>
                <td class="td-header-right" width="30" align="center">&nbsp;</td>
                <td class="td-header-right" width="30" align="center">&nbsp;</td>
              </tr>
              <?php do { 
              $jobid = $row_Recordset3['JobId'];
              ?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                <td align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Name_1']; ?></a></td>
                <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Name']; ?></a></td>
                <td align="center" class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php time_schedule($jobid); ?></a></td>
                <td align="center">
                  <a href="../fpdf16/inv-preview.php?Id=<?php echo $jobid; ?>" target="_blank" class="view" title="View PDF"></a>
                </td>
                <td align="center">
                  <a onClick="return confirmSubmit()" href="../revive.php?Id=<?php echo $row_Recordset3['JobId']; ?>" class="edit" title="Edit"></a>
                </td>
                <td align="center">
                  <a href="order-no.php?Id=<?php echo $row_Recordset3['JobId']; ?>" title="Order No" class="po various3"></a>
                </td>
                <td align="center"><input name="file[]" type="checkbox" id="file[]" value="<?php echo $row_Recordset3['PDF']; ?>"></td>
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
  mysqli_free_result($Recordset3);
?>