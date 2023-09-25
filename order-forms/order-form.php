<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$colname_Recordset1 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = $_GET['Id'];
}
$query_Recordset1 = "SELECT * FROM tbl_orders WHERE Id = '$colname_Recordset1'";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset3 = "SELECT * FROM tbl_technicians WHERE Id != 6 ORDER BY Id ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$orderid = $_GET['Id'];

$query_Recordset4 = "SELECT tbl_order_relation.OrderId, tbl_order_details.Description, tbl_order_details.Qty FROM (tbl_order_relation LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) WHERE OrderId = '$orderid'";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$query_Recordset5 = "SELECT tbl_order_relation.OrderId, tbl_order_details.Description, tbl_order_details.Qty FROM (tbl_order_relation LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) WHERE OrderId = '$orderid'";
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$qty_a = array();
$desc_a = array();
$amount_a = array();

$quantity = $_POST['qty'];
$description = $_POST['description'];
$amount = $_POST['amount'];

$list_rows = count($_POST['qty']);

for($i=0;$i<$list_rows;$i++){
	
	$qty = $quantity[$i];
	$desc = $description[$i];
	$amnt = $amount[$i];

	array_push($qty_a,$qty);
	array_push($desc_a,$desc);
	array_push($amount_a,$amnt);
		
}

$delete = $_POST['delete'];

	if(isset($_POST['delete'])){
		
		foreach($delete as $c){
			 unset($qty_a[$c]);
			 unset($desc_a[$c]);
			 
             $qty_a = array_values($qty_a);
			 
             $desc_a = array_values($desc_a);
						  
					 }}

$list_rows = 1;

if(isset($_POST['supplier-btn'])){
	
	if(count($qty_a) == 0){
		$list_rows = 1;
	} else {
		$list_rows = count($qty_a);
	}}
if(isset($_POST['add'])){
	$list_rows = count($qty_a) + $_POST['num-rows'];
}
if(isset($_POST['save'])){
	$list_rows = count($qty_a);
}
if(isset($_POST['print'])){
	$list_rows = count($qty_a);
}
if(isset($_POST['email'])){
	$list_rows = count($qty_a);
}
if(isset($_POST['edit'])){
	$list_rows = $_POST['numrows'];
}

select_db();
	
if(isset($_POST['save']) || isset($_POST['print']) || isset($_POST['email']) && !empty($_POST['supplier']) && !empty($_POST['issuer']) && !empty($_POST['requestor']) && !empty($_POST['site'])){
	
	$order_id = $_GET['Id'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_order_relation WHERE OrderId = '$order_id'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$item_id = $row['ItemId'];
		
		mysqli_query($con, "DELETE FROM tbl_order_details WHERE Id = '$item_id'")or die(mysqli_error($con));
		
	}
	
	    mysqli_query($con, "DELETE FROM tbl_order_relation WHERE OrderId = '$order_id'")or die(mysqli_error($con));
		
$count = count($qty_a);

for($i=0;$i<$count;$i++){
	
	$qty = $qty_a[$i];
	$desc = $desc_a[$i];
	$amnt = $amount[$i];
	
	mysqli_query($con, "INSERT INTO tbl_order_details (Qty, Description,Amount) VALUES ('$qty','$desc','$amnt')")or die(mysqli_error($con));
		
	$query = mysqli_query($con, "SELECT * FROM tbl_order_details ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$item_id = $row['Id'];
	$order_id = $_GET['Id'];
	
	mysqli_query($con, "INSERT INTO tbl_order_relation (OrderId,ItemId) VALUES ('$order_id','$item_id')")or die(mysqli_error($con));
		
}

   $query_check = mysqli_query($con, "SELECT * FROM tbl_suppliers WHERE Name = '". $_POST['supplier'] ."'")or die(mysqli_error($con));
   
   $account = '';
   
   if(mysqli_num_rows($query_check) == 0){
	   
	   $account = ' - Cash';
   }
   
   $supplierid = $_POST['supplierid'];
   $supplier = $_POST['supplier'];
   $requestor = $_POST['requestor'];
   $requestorid = $_POST['requestor-id'];

   $issuer = $_POST['issuer'];
   
   $pieces = explode('-', $_POST['site']);
   
   $site = trim($pieces[0]);
   $jobno = trim($pieces[1]);
   
   $jobnumber = '';
   
   if(!empty($jobno)){
	   
	   $jobnumber = ", JobNo = '". $jobno ."'";
   }
   
   $job_id = $_GET['Id'];
   $date = date('Y-m-d');
   
   mysqli_query($con, "UPDATE tbl_orders SET SupplierId = '$supplierid', Supplier = '$supplier', TechnicianId = '$requestor', RequestorId = '$requestorid', SiteId = '$site', Issuer = '$issuer', Date = '$date', Account = '$account' $jobnumber WHERE Id = '$job_id'")or die(mysqli_error($con));
   
   $print = 1;
   $_SESSION['print'] = $print;
   
   if(isset($_POST['print'])){
	   
	   ?>
	   
	   <script>
       window.open("http://www.seavest.co.za/inv/order-forms/print.php?Id=<?php echo $_GET['Id']; ?>", '_blank');
      </script>

      <?php
	   
   }
   
   if(isset($_POST['email'])){
	   
	   ?>
	   <script>
       window.open ("http://www.seavest.co.za/inv/order-forms/mail-address.php?Id=<?php echo $_GET['Id']; ?>","Email Address","menubar=0,resizable=0,width=350,height=100");
      </script>

<?php
	   
   }

}

if((isset($_POST['save']) || isset($_POST['print'])) && empty($_POST['supplier'])){
	
	$supplier_error = '<br><span style=\"color:#FF0000\">Required Field</span>';
	
}

if((isset($_POST['save']) || isset($_POST['print'])) && empty($_POST['issuer'])){
	
	$issuer_error = '<br><span style=\"color:#FF0000\">Required Field</span>';
	
}

if((isset($_POST['save']) || isset($_POST['print'])) && empty($_POST['requestor'])){
	
	$requestor_error = '<br><span style=\"color:#FF0000\">Required Field</span>';
	
}

if((isset($_POST['save']) || isset($_POST['print'])) && empty($_POST['site'])){
	
	$site_error = '<span style=\"color:#FF0000\">Required Field</span>';
	
}

if(isset($_POST['address']) && !empty($_POST['supplier-name']) && !empty($_POST['address']) && isset($_POST['new-supplier'])){
	
	$name = addslashes($_POST['supplier-name']);
	
	$query2 = mysqli_query($con, "SELECT * FROM tbl_suppliers WHERE `Name` = '$name'")or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);
	
	if(mysqli_num_rows($query2) == 0){
	
		$name = $_POST['supplier-name'];
		$address = $_POST['address'];
		
		mysqli_query($con, "INSERT INTO tbl_suppliers (Name,Address) VALUES ('$name','$address')")or die(mysqli_error($con));
	}
	
}

mysqli_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_suppliers WHERE Active = '1' ORDER BY Name ASC";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
      <link rel="stylesheet" type="text/css" href="../autocomplete/css/jquery-ui.min.css" />
      <link rel="stylesheet" type="text/css" href="../autocomplete/css/main.css" />
      
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="../autocomplete/js/jquery-ui.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        
      <link rel="stylesheet" href="../menu/styles-sub.css">
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
            <li><a href="#">Edit</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <a href="index.php" class="search-container">Create New</a>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
      
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <form name="form2" method="post" action="order-form.php?Id=<?php echo $_GET['Id']; ?>">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                  <tr>
                    <td>
                      <div id="list-border">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                          <tr>
                            <td align="center" valign="top">
                              <table width="100%" border="0" cellpadding="0" cellspacing="1" class="combo">
                                <?php if(isset($_POST['supplier-btn'])){ ?>
                                <tr>
                                  <td class="td-left">Order No.</td>
                                  <td class="td-right" colspan="2">
                                    <span class="logout"><?php echo $row_Recordset1['OrderNo']; ?></span>
                                    <input type="hidden" name="new-supplier" value="New Supplier" />
                                  </td>
                                </tr>
                                <tr>
                                  <td width="67" class="td-left">To:</td>
                                  <td class="td-right" colspan="2"><input name="supplier-name" type="text" class="tarea-100" id="supplier-name"></td>
                                </tr>
                                <tr>
                                  <td width="67" valign="top" class="td-left">Address</td>
                                  <td class="td-right" colspan="2"><textarea name="address" cols="45" rows="5" class="tarea-100" id="address"></textarea></td>
                                </tr>
                                <?php } else { ?>
                                <tr>
                                  <td width="67" valign="middle" nowrap class="td-left">To: </td>
                                  <td valign="middle" nowrap class="td-right">
                                    <input name="supplier" type="text" class="tarea-100" id="supplier" value="<?php echo $_POST['supplier']; ?>">
                                    <input type="hidden" name="supplierid" id="supplierid" value="<?php echo $_POST['supplierid']; ?>" />
                                    <input type="hidden" name="address" id="address" />
									<?php echo stripslashes($supplier_error); ?>
                                  </td>
                                  <td width="20" valign="middle" nowrap class="td-right">
                                    <input name="supplier-btn" type="submit" class="btn-add-new" id="supplier-btn" value="">
                                  </td>
                                </tr>
                                <?php } ?>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td valign="top">
                              <table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr>
                                  <td width="67" class="td-left"><div id="field-padding">Site</div></td>
                                  <td class="td-right">
                                    <div id="field-padding">
                                      <input name="site" type="text" class="tarea-100" id="site" value="<?php echo $_POST['site']; ?>">
                                      <?php echo stripslashes($site_error); ?>
                                    </div>
                                  </td>
                                </tr>
<!--                                <tr>
                                  <td width="67" class="td-left">Job No</td>
                                  <td class="td-right">
                                    <div id="field-padding">
                                      <input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $_POST['jobno']; ?>">
                                    </div>
                                  </td>
                                </tr>
-->                              </table>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div id="request">Please supply  the following without any additions or alterations:</div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>
                      <div id="list-border">
                        <table width="100%" border="0" cellpadding="0" cellspacing="1">
                          <tr>
                            <td width="50" align="center" class="td-header">Qty</td>
                            <td class="td-header">Description</td>
                            <td width="150" align="right" class="td-header">Amount</td>
                            <td width="20" class="td-header-right">&nbsp;</td>
                          </tr>
                          <?php
        
                          $quantity = $qty_a;
                          $description = $desc_a;
                          $amount = $amount_a;
                          $delete = $_POST['delete'];
        
                          for($i=0;$i<$list_rows;$i++){
							  
							 $qty=$quantity[$i];
							 $desc=$description[$i];
							 $amnt=$amount[$i];
							 $del=$delete[$i];
							 
							?>
                            <tr>
                              <td width="30" class="td-right">
                                <input name="qty[]" type="text" class="tarea-100-centre" id="qty[]" value="<?php echo $qty; ?>">
                              </td>
                              <td class="td-right">
                                <?php
                                $post = $_POST['description'][$i];
                                $result = $row2['Description'];
                                ?>
                                <input name="description[]" type="text" class="tarea-100" id="description[]" value="<?php echo $desc; ?>">
                              </td>
                              <td class="td-right">
                                <input name="amount[]" type="text" class="tarea-100" id="amount[]" value="<?php echo $amnt; ?>" style="text-align:right">
                              </td>
                              <td width="20" class="td-right">
                                <input type="checkbox" name="delete[]" id="delete[]" value="<?php echo $i; ?>">
                                <input name="item-id[]" type="hidden" id="item-id[]" value="<?php echo $itemid; ?>">
                              </td>
                            </tr>
                            <?php } ?>
                        </table>
                      </div>
                      <table border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                          <td>
                            <select name="num-rows" class="select-orders-dd" id="num-rows">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                            </select>
                          </td>
                          <td><input name="row-memory" type="hidden" id="row-memory" value="<?php if($list_rows >= 1){ echo $list_rows; } else { echo 1; } ?>" />                              <input name="add" type="submit" class="btn-new-2" id="add" value="Add Row" style="padding-bottom:6px; padding-top:6px; margin-left:5px" /></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="0" cellspacing="1" class="combo">
                  <tr>
                    <td colspan="4">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4">
                      <div id="list-border" style="overflow:hidden">
                        <table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="100" class="td-left"><div id="field-padding3">Requestor</div></td>
                            <td class="td-right">
                              <div id="field-padding2">
                                <input name="requestor" type="text" class="tarea-100" id="requestor" value="<?php echo $_POST['requestor']; ?>" />
                                <input name="requestor-id" type="hidden" class="tarea-100" id="requestor-id" value="<?php echo $_POST['requestor-id']; ?>" />
                                <?php echo stripslashes($requestor_error); ?>
                              </div>
                            </td>
                            <td width="100" class="td-left"><div id="field-padding">Issuer</div></td>
                            <td class="td-right">
                              <div id="field-padding">
                                <input name="issuer" type="text" class="tarea-100" id="issuer" value="<?php echo $_COOKIE['name']; ?>" readonly>
                                <?php echo stripslashes($issuer_error); ?>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td class="td-left"><div id="field-padding">Telephone</div></td>
                            <td class="td-right"><input name="requestor-mobile" type="text" class="tarea-100" id="requestor-mobile" value="<?php echo $_POST['requestor-mobile']; ?>" /></td>
                            <td class="td-left"><div id="field-padding">Telephone</div></td>
                            <td class="td-right"><input name="issuer-mobile" type="text" class="tarea-100" id="issuer-mobile" value="<?php echo $_COOKIE['telephone']; ?>" readonly /></td>
                          </tr>
                        </table>
                      </div>
        
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="62">&nbsp;</td>
                    <td colspan="3" align="right">
                      <input name="save" type="submit" class="btn-new" id="save" value="save">
                      <?php if(isset($_SESSION['print'])){ ?>
                      <input name="print" type="submit" class="btn-new" id="print" value="Print">
                      <input name="email" type="submit" class="btn-new" id="email" value="Email">
                      <?php } ?>
                    </td>
                  </tr>
                </table>
              </form>
            </td>
          </tr>
        </table>
      
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
      <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
	  <script type="text/javascript">
	  
      $('#supplier').autocomplete({
          source: function( request, response ) {
              $.ajax({
                  url : '../autocomplete/suppliers.php',
                  dataType: "json",
                  method: 'post',
                  data: {
                     name_startsWith: request.term,
                     type: 'country_table',
                     row_num : 1
                  },
                   success: function( data ) {
                       response( $.map( data, function( item ) {
                          var code = item.split("|");
                          return {
                              label: code[0],
                              value: code[0],
                              data : item
                          }
                      }));
                  }
              });
          },
          autoFocus: true,	      	
          minLength: 0,
          select: function( event, ui ) {
              var names = ui.item.data.split("|");						
              $('#supplierid').val(names[1]);
              $('#address').val(names[2]);
          }		      	
      });
	  
      $('#site').autocomplete({
          source: function( request, response ) {
              $.ajax({
                  url : '../autocomplete/sites.php',
                  dataType: "json",
                  method: 'post',
                  data: {
                     name_startsWith: request.term,
                     type: 'country_table',
                     row_num : 1
                  },
                   success: function( data ) {
                       response( $.map( data, function( item ) {
                          var code = item.split("|");
                          return {
                              label: code[0],
                              value: code[0],
                              data : item
                          }
                      }));
                  }
              });
          },
          autoFocus: true,	      	
          minLength: 0,
          select: function( event, ui ) {
              var names = ui.item.data.split("|");						
              $('#siteid').val(names[1]);
          }		      	
      });
	  
	  
      $('#requestor').autocomplete({
          source: function( request, response ) {
              $.ajax({
                  url : '../autocomplete/requestor.php',
                  dataType: "json",
                  method: 'post',
                  data: {
                     name_startsWith: request.term,
                     type: 'country_table',
                     row_num : 1
                  },
                   success: function( data ) {
                       response( $.map( data, function( item ) {
                          var code = item.split("|");
                          return {
                              label: code[0],
                              value: code[0],
                              data : item
                          }
                      }));
                  }
              });
          },
          autoFocus: true,	      	
          minLength: 0,
          select: function( event, ui ) {
              var names = ui.item.data.split("|");						
              $('#requestor-id').val(names[1]);
              $('#requestor-id').val(names[1]);
              $('#requestor-mobile').val(names[2]);
          }		      	
      });
	  
      </script>
      
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