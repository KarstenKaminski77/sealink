<?php require_once('../Connections/inv.php'); ?>
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

$query_Recordset1 = "
	SELECT
		tbl_orders.OrderNo,
		tbl_orders.`Supplier` AS Name,
		tbl_suppliers.Address,
		tbl_order_details.Qty,
		tbl_order_details.Description,
		tbl_orders.TechnicianId,
		tbl_orders.Date,
		tbl_orders.SiteId,
		tbl_orders.JobNo,
		tbl_orders.`Issuer`,
		tbl_orders.Account,
		tbl_order_relation.OrderId
	FROM
		(
			(
				(
					tbl_orders
					LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId = tbl_orders.Id
				)
				LEFT JOIN tbl_order_details ON tbl_order_details.Id = tbl_order_relation.ItemId
			)
			LEFT JOIN tbl_suppliers ON tbl_suppliers.Id = tbl_orders.SupplierId
		)
	WHERE
		tbl_orders.Supplier = '". $_POST['supplier'] ."'";
	
mysql_select_db($database_inv, $inv);
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);


if(!empty($_POST['date1'])){
$date1 =  $_POST['date1'];
}
if(!empty($_POST['date2'])){
$date2 = $_POST['date2'];
}
if(!empty($_POST['supplier'])){ 
$supplier = 'tbl_suppliers.Id='. $_POST['supplier'] .' AND ';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Recon for <?php echo $row_Recordset1['Name'] .' '. $date1 .' - '. $date2; ?></title>
<link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<style type="text/css" media="print">
<!--
table {
page-break-inside: avoid;
}
-->
</style>
</head>

<body onLoad="window.print();">
<img src="images/logo.jpg" width="100" height="102" />
<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><form name="form2" method="post" action="order-form.php?Id=<?php echo $_GET['Id']; ?>">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                    <tr>
                      <td>
                      <table width="660" border="0" cellpadding="0" cellspacing="0" class="combo">
                        <tr>
                          <td colspan="3" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="right"><strong class="logout">Recon for: <?php echo $row_Recordset1['Name'] . $row_Recordset1['Account']; ?></strong>
                                <br />
                                <br />                            
                                <?php echo '<span style="color:black"><b>From:</b> '. $_GET['date1'] .'<br> <b>To:</b> '. $_GET['date2'] .'</span>'; ?>                              </td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td width="33%" valign="top">&nbsp;</td>
                          <td width="33%" valign="top">&nbsp;</td>
                          <td width="33%" valign="top">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>
<?php
$numrows = count($_POST['print']);
						  
$orderid = $_POST['print'];
						  
for($i=0;$i<$numrows;$i++){
							  
$id = $orderid[$i];
							  
$KTColParam1_Recordset3 = $id; 

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_orders.OrderNo, tbl_order_details.Amount, tbl_suppliers.Name, tbl_suppliers.Address, tbl_order_details.Qty, tbl_order_details.Description, tbl_orders.TechnicianId, tbl_orders.Date, tbl_orders.SiteId, tbl_orders.JobNo, tbl_orders.Issuer, tbl_order_relation.OrderId FROM (((tbl_orders LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId=tbl_orders.Id) LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) LEFT JOIN tbl_suppliers ON tbl_suppliers.Id=tbl_orders.SupplierId) WHERE tbl_orders.Id=%s ", GetSQLValueString($KTColParam1_Recordset3, "int"));
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
?>
                      <div style="margin-bottom:50px; page-break-inside:avoid">
                        <span class="combo_bold" style="color:#000">Order No: <?php echo $row_Recordset3['OrderNo']; ?></span><br />
                        <br />
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="page-break-inside:avoid">
                          <tr class="combo" style="page-break-inside:avoid">
                            <td width="67" align="center" class="td-order-form" style="border-right:none"><strong>Job No.</strong></td>
                            <td width="60" align="center" class="td-order-form" style="border-right:none"><div style="padding-left:5px"><?php echo $row_Recordset3['JobNo']; ?></div></td>
                            <td width="60" align="center" class="td-order-form" style="border-right:none"><strong><strong>Site</strong></strong></td>
                            <td class="td-order-form" style="border-right:none"><div style="padding-left:5px"><?php echo stripslashes($row_Recordset3['SiteId']); ?></div></td>
                            <td width="60" align="center" class="td-order-form" style="border-right:none"><strong>Inv. No.</strong></td>
                            <td width="60" class="td-order-form"><div style="padding-left:5px"></div></td>
                          </tr>
                          <tr>
                            <td align="center" class="td-order-form-qty" style="border-right:none"><strong>Ord Qty</strong></td>
                            <td align="center" class="td-order-form-qty" style="border-right:none"><strong>Act Qty</strong></td>
                            <td colspan="3" class="td-order-form-qty" style="border-right:none"><div style="padding-left:5px"><strong>Description</strong></div></td>
                            <td align="center" class="td-order-form-qty" style="border-right:solid 1px #000"><strong>Value</strong></td>
                          </tr>
						  
						  <?php
						  
							$total = 0;
							
							$query2 = "
								SELECT
									tbl_orders.OrderNo,
									tbl_suppliers.Id,
									tbl_suppliers.`Name`,
									tbl_suppliers.Address,
									tbl_order_details.Qty,
									tbl_orders.Date,
									tbl_order_details.Description,
									tbl_order_details.Amount,
									tbl_orders.TechnicianId,
									tbl_orders.SiteId,
									tbl_orders.JobNo,
									tbl_orders.`Issuer`,
									tbl_order_relation.OrderId
								FROM
									(
										(
											(
												tbl_orders
												LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId = tbl_orders.Id
											)
											LEFT JOIN tbl_order_details ON tbl_order_details.Id = tbl_order_relation.ItemId
										)
										LEFT JOIN tbl_suppliers ON tbl_suppliers.Id = tbl_orders.SupplierId
									)
								WHERE
									tbl_order_relation.OrderId = '$id'
								ORDER BY
									tbl_order_details.Id ASC";						   
						  
							  $query2 = mysql_query($query2)or die(mysql_error());
							  while($row2 = mysql_fetch_array($query2)){
								  
								  $total += $row2['Amount'];
						  ?>
                          <tr>
                            <td width="67" align="center" class="td-order-form-qty"><div id="field-padding"><?php echo $row2['Qty']; ?></div></td>
                            <td class="td-order-form" style="border-right:none">&nbsp;</td>
                            <td colspan="3" class="td-order-form-qty"><div id="field-padding" style="padding-left:5px; border-right:none"><?php echo $row2['Description']; ?></div></td>
                            <td align="right" class="td-order-form"><div id="field-padding" style="padding-right:5px; border-right:none"></div></td>
                          </tr>
                            <?php } ?>
                          <tr>
                            <td colspan="4" align="center" class="td-order-form" style="border-left:none; border-right:none; border-bottom:none">&nbsp;</td>
                            <td class="td-order-form-qty" style="border-bottom: solid 1px #000"><strong>Total</strong></td>
                            <td align="right" class="td-order-form" style="border-bottom: solid 1px #000">&nbsp;</td>
                          </tr>
                        </table>
                      </div>
                      <?php } // close for loop ?>
                      </td>
                    </tr>
              </table>
            </form></td>
  </tr>
        </table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset3);

mysql_free_result($query2);
?>
