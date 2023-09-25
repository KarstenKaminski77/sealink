<?php require_once('../Connections/seavest.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../functions/functions.php');

select_db();

if(isset($_POST['add'])){
	
	for($i=1;$i<=$_POST['num-rows'];$i++){
		
		mysql_query("INSERT INTO tbl_order_details (Qty) VALUES ('1')")or die(mysql_error());
		
	   $query = mysql_query("SELECT * FROM tbl_order_details ORDER BY Id DESC LIMIT 1")or die(mysql_error());
	   $row = mysql_fetch_array($query);
	
	   $item_id = $row['Id'];
	   $order_id = $_GET['Id'];
	
	   mysql_query("INSERT INTO tbl_order_relation (OrderId,ItemId) VALUES ('$order_id','$item_id')")or die(mysql_error());
	   
	}
}
	
if(isset($_POST['save'])){
	
$quantity = $_POST['qty'];
$description = $_POST['description'];
$delete = $_POST['delete'];
$item_id = $_POST['item-id'];

$count = count($_POST['qty']);

for($i=0;$i<$count;$i++){
	
	$id = $item_id[$i];
	$qty = $quantity[$i];
	$desc = $description[$i];
	$del = $delete[$i];
		
	mysql_query("UPDATE tbl_order_details set Qty = '$qty', Description = '$desc' WHERE Id = '$id'")or die(mysql_error());
	
	$query = mysql_query("SELECT * FROM tbl_order_details ORDER BY Id DESC LIMIT 1")or die(mysql_error());
	$row = mysql_fetch_array($query);
		
	if($del == 1){
		
		mysql_query("DELETE FROM tbl_order_relation WHERE Id = '$id'")or die(mysql_error());
		
	   $query2 = mysql_query("SELECT * FROM tbl_order_details WHERE Qty = '$qty' AND Description = '$desc'")or die(mysql_error());
	   $row2 = mysql_fetch_array($query2);
	   $numrows = mysql_num_rows($query2);
	   
	}
}}
	
$colname_Recordset1 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = $_GET['Id'];
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT * FROM tbl_orders WHERE Id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_suppliers ORDER BY Name ASC";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT * FROM tbl_technicians WHERE Id != 6 ORDER BY Id ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$orderid = $_GET['Id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT tbl_order_relation.OrderId, tbl_order_details.Description, tbl_order_details.Qty FROM (tbl_order_relation LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) WHERE OrderId = '$orderid'";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT tbl_order_relation.OrderId, tbl_order_details.Description, tbl_order_details.Qty FROM (tbl_order_relation LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) WHERE OrderId = '$orderid'";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$num_rows = mysql_num_rows($Recordset4);

if($num_rows == 0){
	$list_rows = 1;
}

if(isset($_POST['add'])){
	$list_rows = $num_rows + $_POST['num-rows'];
}
if(isset($_POST['add']) && ($num_rows == 0)){
	$list_rows = $_POST['num-rows'];
}
if(isset($_POST['save'])){
	$list_rows = count($_POST['qty']);
}

function field_check($result,$post){
	if(!empty($post)){
		
		echo $post;
		
		} else {
			
			echo $result;
			
		}}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
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
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
<link href="css/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="../images/banner.jpg" width="823" height="213"></td>
      </tr>
      <tr>
        <td><table width="695" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><form name="form2" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
              <div style=" border:solid 1px #A6CAF0; width:665px; padding:15px">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                      <tr>
                        <td valign="top"><img src="../fpdf16/mail_logo.jpg" width="193" height="160"></td>
                      </tr>
                      <tr>
                        <td valign="top">&nbsp;</td>
                      </tr>
                      <tr>
                        <td valign="top">
                        <?php if(isset($_GET['supplier'])){ ?>
                        <table border="0" cellpadding="2" cellspacing="3" class="combo">
                          <tr>
                            <td>To:</td>
                            <td><input name="supplier" type="text" class="suppliers-wide" id="supplier"></td>
                          </tr>
                          <tr>
                            <td valign="top">Address</td>
                            <td><textarea name="address" cols="45" rows="5" class="suppliers-wide" id="address"></textarea></td>
                          </tr>
                        </table>
                        <?php } else { ?>
                          <table border="0" cellpadding="0" cellspacing="0" class="combo">
                          <tr>
                            <td width="45%" valign="middle" nowrap>To:
                              <select name="supplier" class="suppliers" id="supplier">
                                <option value="">Select one...</option>
                                <?php
do {  
?>
                                <option value="<?php echo $row_Recordset2['Id']?>"><?php echo $row_Recordset2['Name']?></option>
                                <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                              </select>
                              </td>
                            <td width="55%" valign="middle" nowrap><input name="button" type="submit" class="new-supplier" id="button" value=""></td>
                            </tr>
                        </table>  
                        <?php } ?>                         
                         </td>
                      </tr>
                    </table></td>
                    <td valign="top"><table border="0" align="right" cellpadding="2" cellspacing="3" class="combo">
                      <tr>
                        <td align="right" class="combo"><strong>ORDER NO: <?php echo $row_Recordset1['OrderNo']; ?></strong></td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">P.O BOX 201153</td>
                      </tr>
                      <tr>
                        <td align="right">Durban North</td>
                      </tr>
                      <tr>
                        <td align="right">4016</td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right">Tel: 0.0861474705</td>
                      </tr>
                      <tr>
                        <td align="right">Fax: 0.0865191153</td>
                      </tr>
                      <tr>
                        <td align="right">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="right"><?php echo date('d M Y'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="2"><div id="request">Please supply  the following without any additions or alterations:</div></td>
                    </tr>
                  <tr>
                    <td colspan="2"><table width="650" border="0" cellpadding="0" cellspacing="1">
                      <tr>
                        <td width="30" class="tb_border">Qty</td>
                        <td width="600" class="tb_border">Description</td>
                        <td width="20">&nbsp;</td>
                      </tr>
                      <?php
					  
					  $quantity = $_POST['qty'];
                      $description = $_POST['description'];
					  $orderid = $_GET['Id'];
					  $i = 0;
					  
	                  $query = mysql_query("SELECT tbl_order_relation.OrderId, tbl_order_details.Qty, tbl_order_details.Description, tbl_order_relation.ItemId FROM (tbl_order_relation LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) WHERE tbl_order_relation.OrderId = '$orderid'")or die(mysql_error());
					  $test = mysql_num_rows($query);
	                  while($row = mysql_fetch_array($query, MYSQL_BOTH)){
						  
						  $i++;
						  
						  echo $_POST['description'][$i].' -';
						  
						  $itemid = $row['ItemId'];
						  
						  $query2 = mysql_query("SELECT * FROM tbl_order_details WHERE Id = '$itemid'")or die(mysql_query());
						  $row2 = mysql_fetch_array($query2);
	
					  ?>
                      <tr>
                        <td width="30"><div id="field-padding">
                        <?php
						$post = $_POST['qty'][$i];
						$result = $row2['Qty'];
						?>
                          <input name="qty[]" type="text" class="order-qty" id="qty[]" value="<?php echo $_POST['qty'][$i]; ?>">
                        </div></td>
                        <td width="600"><div id="field-padding">
                        <?php
						$post = $_POST['description'][$i];
						$result = $row2['Description'];
						?>
                          <input name="description[]" type="text" class="order-description" id="description[]" value="<?php echo $_POST['description'][$i]; ?>">
                        </div></td>
                        <td width="20">
                        <div id="field-padding">
                        <input type="checkbox" name="delete[]" id="delete[]" value="1">
                        <input name="item-id[]" type="hidden" id="item-id[]" value="<?php echo $itemid; ?>">
                        </div></td>
                      </tr>
                      <tr>
                      <?php } ?>
                        <td>&nbsp;</td>
                        <td><input name="row-memory" type="hidden" id="row-memory" value="<?php if($list_rows >= 1){ echo $list_rows; } else { echo 1; } ?>">
                          </td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td align="right"><table border="0" align="right" cellpadding="0" cellspacing="0">
                          <tr>
                            <td><select name="num-rows" class="suppliers" id="num-rows">
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
                            </select></td>
                            <td><input name="add" type="submit" class="btn-add" id="add" value=""></td>
                          </tr>
                        </table></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                <table width="630" border="0" cellpadding="0" cellspacing="1" class="combo">
                  <tr>
                    <td width="13%">&nbsp;</td>
                    <td width="42%">&nbsp;</td>
                    <td width="13%">&nbsp;</td>
                    <td width="32%">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div id="field-padding">Requestor</div></td>
                    <td><div id="field-padding">
                      <select name="requestor" class="suppliers-wide" id="requestor">
                      <option value="">Select one...</option>
                        <?php
do {  
?>
                        <option value="<?php echo $row_Recordset3['Id']?>"><?php echo $row_Recordset3['Name']?></option>
                        <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                      </select>
                    </div></td>
                    <td><div id="field-padding">Site</div></td>
                    <td><div id="field-padding">
                      <input name="site" type="text" class="suppliers-wide" id="site">
                    </div></td>
                  </tr>
                  <tr>
                    <td><div id="field-padding">Issuer</div></td>
                    <td><div id="field-padding">
                      <input name="issuer" type="text" class="suppliers-wide" id="issuer">
                    </div></td>
                    <td><div id="field-padding">Job No</div></td>
                    <td><div id="field-padding">
                      <input name="jobno" type="text" class="suppliers-wide" id="jobno">
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right"><input name="save" type="submit" class="btn-save" id="save" value=""></td>
                  </tr>
                </table>
              </div>
            </form></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>
