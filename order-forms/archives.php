<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$currentPage = $_SERVER["PHP_SELF"];


if(isset($_GET['Id'])){
	
	$id = $_GET['Id'];
	
	mysqli_query($con, "DELETE FROM tbl_orders WHERE Id = '$id'")or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_order_relation WHERE OrderId = '$id'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$item = $row['ItemId'];
		
		mysqli_query($con, "DELETE FROM tbl_order_details WHERE Id = '$item'")or die(mysqli_error($con));
													
	
	}

	mysqli_query($con, "DELETE FROM tbl_order_relation WHERE OrderId = '$id'")or die(mysqli_error($con));
	
}


$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);	

$rpp = 30; // results per page
$adjacents = 4;

$page = intval($_GET["page"]);

if($page<=0) $page = 1;

$reload = $_SERVER['PHP_SELF'];

$query_Recordset3 = "
	SELECT 
	  tbl_orders.Id,
	  tbl_orders.OrderNo,
	  tbl_orders.Date,
	  tbl_suppliers.Name,
	  tbl_orders.SiteId,
	  tbl_orders.JobNo,
	  tbl_orders.Issuer,
	  tbl_orders.TechnicianId 
	FROM
	  (
		tbl_orders 
		LEFT JOIN tbl_suppliers 
		  ON tbl_suppliers.Id = tbl_orders.SupplierId
	  ) 
	WHERE tbl_orders.Id >= '1002'
	ORDER BY Id DESC";

$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);

// count total number of appropriate listings:
$tcount = mysqli_num_rows($Recordset3);

// count number of pages:
$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number

$count = 0;
$i = ($page-1)*$rpp;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      <link href="../pagination/paginate.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../menu/styles-sub.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/sub-menu-script.js"></script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
      <script type="text/javascript">
$(document).ready(function (){
	  /* trigger myFunction() when select changes */
	  $(document).on('change','select',myFunction());
	  /* calling myFunction() when page loads */
	  $(document).ready(myFunction);
	  function myFunction(){
	  var el = $('select');
	  /* toggle class when value is empty */
	  el.toggleClass('default', !el.val());
	  }
	  
	  /* trigger myFunction() when select changes */
	  $(document).on('change','select',myFunction());
	   
	  /* calling myFunction() when page loads */
	  $(document).ready(myFunction);
	   
	  function myFunction(){
	  var el = $('select');
	  /* toggle class when value is empty */
	  el.toggleClass('default', !el.val());
	  }
	  });
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
      </script>
      
	  <style>
        select:required:invalid {
        color: #999;
        }
        option[value=””][disabled] {
        display: none;
        }
        option {
        color: #000;
        }
      </style>
      
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
      <?php include('../menu/sub-menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Order Forms</a></li>
            <li><a href="#">Archives</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="../invoices/search.php">
          <input name="search-field" type="text" class="search-top" id="search-field" placeholder="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
      
      <form name="form2" method="post" action="batch-print-process.php" target="_blank">
      
        <table width="100%" border="0" align="right" cellpadding="3" cellspacing="1" class="combo">
          <tr>
            <td class="td-header">Order No</td>
            <td class="td-header">Job No</td>
            <td class="td-header">Site</td>
            <td class="td-header">Date</td>
            <td width="20" class="td-header-right" align="center">&nbsp;</td>
            <td width="20" class="td-header-right" align="center">&nbsp;</td>
            <td width="20" class="td-header-right" align="center">&nbsp;</td>
            <td width="20" class="td-header-right" align="center">&nbsp;</td>
            <td width="20" class="td-header-right" align="center">&nbsp;</td>
          </tr>
          <?php 
		  
		  while(($count<$rpp) && ($i<$tcount)) {
			  
			  mysqli_data_seek($Recordset3,$i);
			  $row_Recordset3 = mysqli_fetch_array($Recordset3);
		  
		   ?>
          <tr class="<?php echo ($ac_sw1++%2==0)?" odd":"even"; ?>
            " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
            <td width="75"><?php echo $row_Recordset3['OrderNo']; ?></td>
            <td width="100"><?php echo $row_Recordset3['JobNo']; ?></td>
            <td><?php echo $row_Recordset3['SiteId']; ?></td>
            <td width="100"><?php echo $row_Recordset3['Date']; ?></td>
            <td align="center"><a href="archive-view.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="view"></a></td>
            <td align="center"><a href="print.php?menu=Archives&Id=<?php echo $row_Recordset3['Id']; ?>" target="_blank" class="icon-print"></a></td>
            <td align="center"><a href="javascript:;" class="mail"onclick="MM_openBrWindow('mail-address.php?Id=<?php echo $row_Recordset2['Id']; ?>','','width=350,height=100')"></a></td>
            <td align="center"><a href="archives.php?menu=Archives&Id=<?php echo $row_Recordset3['Id']; ?>" class="delete"></a></td>
            <td align="center"><input name="print[]" type="checkbox" id="print[]" value="<?php echo $row_Recordset3['Id']; ?>"></td>
          </tr>
          <?php
		  
		  $i++;
		  $count++;
		  
		  }  
		  
		  ?>
          <tr>
            <td colspan="9" align="right">
              <div id="print-icon">
                <input name="button" type="submit" class="btn-new" id="button" value="Print">
              </div>
            </td>
          </tr>
        </table>
        
      <?php
	  // Show pqaginator only if results exceed one page
	  if(mysqli_num_rows($Recordset3) >= 30){
		  
		  include("../pagination/pagination3.php");
		  echo paginate_three($reload, $page, $tpages, $adjacents);
	  }
	  
	  ?> 
      
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