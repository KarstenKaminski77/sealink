<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$query_Recordset1 = "SELECT * FROM tbl_suppliers GROUP BY Name ORDER BY Name ASC";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$where = '1 = 1 ';

if(!empty($_POST['jobno'])){
	
	$where .= "AND tbl_orders.JobNo = '". $_POST['jobno'] ."'";
}
if(!empty($_POST['date1'])){
	
	$where .= "AND tbl_orders.Date >= '". $_POST['date1'] ."'";
}
if(!empty($_POST['date2'])){
	
	$where .= "AND tbl_orders.Date <= '". $_POST['date2'] ."'";
}
if(!empty($_POST['supplier'])){
	
	$where .= "AND tbl_orders.Supplier = '". $_POST['supplier'] ."'";
}
if(!empty($_POST['site'])){
	
	$where .= "AND tbl_orders.SiteId = '". $_POST['site'] ."'";
}

$query_Recordset2 = "
	SELECT 
	  tbl_orders.OrderNo,
	  tbl_orders.Id,
	  tbl_orders.Date,
	  tbl_orders.TechnicianId,
	  tbl_orders.SiteId,
	  tbl_orders.JobNo,
	  tbl_orders.Issuer,
	  tbl_order_details.Qty,
	  tbl_order_details.Description
	FROM
	  tbl_orders 
	  INNER JOIN tbl_order_relation 
		ON (
		  tbl_orders.Id = tbl_order_relation.OrderId
		) 
	  INNER JOIN tbl_order_details 
		ON (
		  tbl_order_relation.ItemId = tbl_order_details.Id
		) 
	WHERE $where
	GROUP BY tbl_orders.Id";

$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

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
        
      <link rel="stylesheet" href="../menu/styles-sub.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/sub-menu-script.js"></script>
      
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
      
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
	  </script>
      
	  <script language='JavaScript'>
checked = false;
            function checkedAll () {
              if (checked == false){checked = true}else{checked = false}
          for (var i = 0; i < document.getElementById('form3').elements.length; i++) {
            document.getElementById('form3').elements[i].checked = checked;
          }
            }
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
            <li><a href="#">Search</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
            
      <!-- Main Form -->
      <div id="main-wrapper">
      
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <?php $orderid = $row_Recordset2['Id']; ?>
              <td align="center">
                <form name="form2" method="post" action="search.php">
                  <table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
                    <tr>
                      <td>
                        <span class="test">
                          <input name="jobno" type="text" class="select" id="jobno" placeholder="Job No">
                        </span>
                      </td>
                      <td>
                        <span class="test">
                          <input type=text list=browsers name="supplier" class="select" placeholder="Supplier">
                            <datalist id=browsers>
                            <?php
                            do {
                            ?>
                            <option value="<?php echo trim($row_Recordset1['Name']); ?>"><?php echo trim($row_Recordset1['Name']); ?></option>
                            <?php
                            } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
                            $rows = mysqli_num_rows($Recordset1);
                            if($rows > 0) {
                            mysqli_data_seek($Recordset1, 0);
                            $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
                            }
                            ?>
                          </datalist>
                        </span>
                      </td>
                      <td><input style="width:150px" name="site" type="text" class="select" id="site" placeholder="Site"></td>
                      <td>
                        <span class="test">
                          <input name="date1" class="select" id="date1" placeholder="From">
						  <script type="text/javascript">
                              $('#date1').datepicker({
                              dateFormat: "yy-mm-dd"
                              });
                             </script>
                          
                        </span>
                      </td>
                      <td>
                        <span class="test">
                          <input name="date2" class="select" id="date2" placeholder="To">
						  <script type="text/javascript">
                              $('#date2').datepicker({
                              dateFormat: "yy-mm-dd"
                              });
                             </script>
                          
                        </span>
                      </td>
                      <td>
                        <span class="test">
                          <input type="submit" name="edit" id="edit" value="" class="search-orders-btn">
                        </span>
                      </td>
                    </tr>
                  </table>
                  <br>
                </form>
                <br>
                <br>
                <?php
                $orderid = $row_Recordset2['Id'];
          
                if(isset($_POST['edit'])){
                ?>
                <?php
                if(!empty($_POST['date1'])){
                $date1 = 'date1='. $_POST['date1'] .'&';
                }
                if(!empty($_POST['date2'])){
                $date2 = 'date2='. $_POST['date2'] .'&';
                }
                if(!empty($_POST['supplier'])){
                $supplier = 'supplier='. $_POST['supplier'];
                }
                $where = $date1 . $date2 . $supplier;
                ?>
                <form action="batch-print-process2.php?<?php echo $where; ?>" method="post" name="form3" target="_blank" id="form3">
                  <div id="list-border">
                    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="combo">
                      <tr>
                        <td class="td-header">Order No</td>
                        <td class="td-header">Job No</td>
                        <td class="td-header">Site</td>
                        <td class="td-header">Date</td>
                        <td width="20" class="td-header-right">&nbsp;</td>
                        <td width="20" class="td-header-right">&nbsp;</td>
                        <td width="20" class="td-header-right">&nbsp;</td>
                        <td width="20" class="td-header-right">&nbsp;</td>
                        <td width="20" align="center" class="td-header-right"><input type='checkbox' name='checkall' onclick='checkedAll();'></td>
                      </tr>
                      <?php do { ?>
                      <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                        <td width="100"><?php echo $row_Recordset2['OrderNo']; ?></td>
                        <td width="100"><?php echo $row_Recordset2['JobNo']; ?></td>
                        <td><?php echo $row_Recordset2['SiteId']; ?></td>
                        <td width="100"><?php echo $row_Recordset2['Date']; ?></td>
                        <td align="center"><a href="archive-view.php?Id=<?php echo $row_Recordset2['Id']; ?>" class="view"></a></td>
                        <td align="center"><a href="print.php?menu=Archives&Id=<?php echo $row_Recordset2['Id']; ?>" target="_blank" class="print"></a></td>
                        <td align="center"><a href="javascript:;" class="mail"onclick="MM_openBrWindow('mail-address.php?Id=<?php echo $row_Recordset2['Id']; ?>','','width=350,height=100')"></a></td>
                        <td align="center"><a href="search.php?menu=Archives&delete&Id=<?php echo $row_Recordset2['Id']; ?>" class="delete"></a></td>
                        <td align="center"><input name="print[]" type="checkbox" id="print[]" value="<?php echo $row_Recordset2['Id']; ?>"></td>
                      </tr>
                      <?php } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2)); ?>
                    </table>
                  </div>
                        <input name="supplier" type="hidden" id="supplier" value="<?php echo $_POST['supplier']; ?>">
                        <input name="date1" type="hidden" id="date1" value="<?php echo $_POST['date1']; ?>">
                        <input name="date2" type="hidden" id="date2" value="<?php echo $_POST['date2']; ?>">                      
                        <div id="print-icon" style="margin:0px; text-align:right">
                          <input name="button" type="submit" class="btn-new" id="button" value="Print">
                        </div>                     
                </form>
                <?php } ?>
              </td>
            </tr>
          </table>
      
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