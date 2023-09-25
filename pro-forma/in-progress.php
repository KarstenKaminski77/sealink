<?php 
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php'); 

if(isset($_GET['Company'])){
	
	$where = 'AND tbl_companies.Id = '. $_GET['Company'];
	
}

$query_Recordset3 = "
	SELECT
		tbl_jc.JobNo,
		tbl_jc.SlaCatId,
		tbl_jc.CompanyId,
		tbl_jc.JobDescription,
		tbl_jc.Status,
		tbl_jc.Reference,
		tbl_jc.InvoiceNo,
		tbl_jc.InvoiceDate,
		tbl_companies.Name,
		tbl_jc.JobId
	FROM
		tbl_jc
		INNER JOIN tbl_companies 
			ON (tbl_jc.CompanyId = tbl_companies.Id)
	WHERE tbl_jc.Status = '22'
	GROUP BY tbl_jc.JobId";
	
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_rs_totals  = $_SESSION['kt_login_id'];

$rs_totals = mysqli_query($con, "SELECT * FROM tbl_menu_relation WHERE UserId = '$colname_rs_totals' AND MenuId = '47'") or die(mysqli_error($con));
$row_rs_totals = mysqli_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysqli_num_rows($rs_totals);

$query_Recordset4 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

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
	  
	  <script LANGUAGE="JavaScript">
    
      function MM_jumpMenu(targ,selObj,restore){ //v3.0
        eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
        if (restore) selObj.selectedIndex=0;
      }
      </script>
      
	  <script type="text/javascript" src="../fancyBox-2/lib/jquery-1.10.1.min.js"></script>
      <script type="text/javascript" src="../fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <script type="text/javascript" src="../fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
      <link rel="stylesheet" type="text/css" href="../fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />
  
      <script type="text/javascript">
          $(document).ready(function() {
  
              $('.fancybox').fancybox({
			  
				  autoSize    : true, 
				  closeClick  : false, 
				  fitToView   : true, 
				  openEffect  : 'none', 
				  closeEffect : 'none', 
				  scrolling   : 'no',
				  type : 'iframe',

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
            <li><a href="#">Debtors</a></li>
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
        <form action="../fpdf16/pdf/jc_resend_mail.php" method="post" enctype="multipart/form-data" name="form2" style="width:100%">
          <div id="list-border">
            <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                <tr>
                  <td class="td-header" width="50" align="center" nowrap><strong>In No. </strong></td>
                  <td class="td-header" align="left"><strong>Company</strong></td>
                  <td width="150" align="left" class="td-header">Date</td>
                  <td width="150" align="left" class="td-header"><strong>Job No.</strong></td>
                  <td class="td-header" width="150" align="left"><strong>Reference</strong></td>
                  <td class="td-header-right" width="20" align="center">&nbsp;</td>
                  <td class="td-header-right" width="20" align="center">&nbsp;</td>
                </tr>
                <?php do { 
              $jobid = $row_Recordset3['JobId'];
              ?>
                <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                  <td align="center" nowrap class="combo"><a href="#" class="menu"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                  <td nowrap class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Name']; ?></a></td>
                  <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceDate']; ?></a></td>
                  <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['JobNo']; ?></a></td>
                  <td class="combo"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['Reference']; ?></a></td>
                  <td align="center">
                    <a href="../pdf/pdf-pro-forma.php?Id=<?php echo $row_Recordset3['JobId']; ?>&Preview" target="_blank" class="icon-pdf fancybox" title="View PDF"></a>
                  </td>
                  <td align="center">
                    <a onClick="return confirmSubmit()" href="inv-calc.php?Id=<?php echo $row_Recordset3['JobId']; ?>" class="edit" title="Edit"></a>
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