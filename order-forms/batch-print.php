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

$KTColParam1_Recordset1 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset1 = $_GET["Id"];
}
mysql_select_db($database_inv, $inv);
$query_Recordset1 = sprintf("SELECT tbl_orders.OrderNo, tbl_suppliers.Name, tbl_suppliers.Address, tbl_order_details.Qty, tbl_order_details.Description, tbl_orders.TechnicianId, tbl_orders.Date, tbl_orders.SiteId, tbl_orders.JobNo, tbl_orders.Issuer, tbl_order_relation.OrderId FROM (((tbl_orders LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId=tbl_orders.Id) LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) LEFT JOIN tbl_suppliers ON tbl_suppliers.Id=tbl_orders.SupplierId) WHERE tbl_order_relation.OrderId=%s ", GetSQLValueString($KTColParam1_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$KTColParam1_Recordset2 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset2 = $_GET["Id"];
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT tbl_orders.OrderNo, tbl_suppliers.Name, tbl_suppliers.Address, tbl_order_details.Qty, tbl_order_details.Description, tbl_order_details.Amount, tbl_orders.TechnicianId, tbl_orders.SiteId, tbl_orders.JobNo, tbl_orders.Issuer, tbl_order_relation.OrderId FROM (((tbl_orders LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId=tbl_orders.Id) LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId) LEFT JOIN tbl_suppliers ON tbl_suppliers.Id=tbl_orders.SupplierId) WHERE tbl_order_relation.OrderId=%s ORDER BY tbl_order_details.Id ASC", GetSQLValueString($KTColParam1_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
</head>

<body onLoad="window.print(); window.close();">
<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><form name="form2" method="post" action="order-form.php?Id=<?php echo $_GET['Id']; ?>">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                    <tr>
                      <td>
                      <table width="660" border="0" cellpadding="0" cellspacing="0" class="combo">
                        <tr>
                          <td colspan="3" valign="top"><img src="images/quote-banner.jpg" width="660" height="155" /></td>
                        </tr>
                        <tr>
                          <td valign="top">&nbsp;</td>
                          <td valign="top">&nbsp;</td>
                          <td valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" align="center" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" align="center" valign="top"><strong class="logout">ORDER NO: <?php echo $row_Recordset1['OrderNo']; ?></strong></td>
                        </tr>
                        <tr>
                          <td valign="top">&nbsp;</td>
                          <td valign="top">&nbsp;</td>
                          <td valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="33%" valign="top">&nbsp;</td>
                          <td width="33%" valign="top">&nbsp;</td>
                          <td width="33%" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" valign="top">
                          <div style="border-bottom:solid 1px #A6CAF0; width:660px">
                            <table width="660" border="0" cellpadding="0" cellspacing="0" class="combo">
                              <tr>
                                <td width="67" class="td-order-form-qty"><strong>Supplier</strong></td>
                                <td width="547" class="td-order-form"><div style="padding-left:5px"><?php echo $row_Recordset1['Name']; ?></div></td>
                              </tr>
                              <tr>
                                <td valign="top" class="td-order-form-qty"><strong>Address</strong></td>
                                <td class="td-order-form"><div style="padding-left:5px"><?php echo nl2br($row_Recordset1['Address']); ?></div></td>
                              </tr>
                              <tr>
                                <td class="td-order-form-qty"><strong>Site</strong></td>
                                <td class="td-order-form"><div style="padding-left:5px"><?php echo $row_Recordset1['SiteId']; ?></div></td>
                              </tr>
                              <tr>
                                <td class="td-order-form-qty"><strong>Job No.</strong></td>
                                <td class="td-order-form"><div style="padding-left:5px"><?php echo $row_Recordset1['JobNo']; ?></div></td>
                              </tr>
                              <tr>
                                <td class="td-order-form-qty"><strong>Date</strong></td>
                                <td class="td-order-form"><div style="padding-left:5px"><?php echo $row_Recordset1['Date']; ?></div></td>
                              </tr>
                            </table>
                            </div>
                          </td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><div id="request">Please supply  the following without any additions or alterations:</div></td>
                    </tr>
                    <tr>
                      <td><div style="border-bottom:solid 1px #A6CAF0; width:660px">
                        <table width="660" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="30" align="center" class="td-order-form-qty"><strong>Qty</strong></td>
                            <td width="555" class="td-order-form"><strong style="padding-left:5px">Description</strong></td>
                            <td width="75" class="td-order-form"><strong>Amount</strong></td>
                          </tr>
                          <?php do { ?>
                          <tr>
                            <td width="30" align="center" class="td-order-form-qty"><div id="field-padding"><?php echo $row_Recordset2['Qty']; ?></div></td>
                            <td class="td-order-form"><div id="field-padding" style="padding-left:5px"><?php echo $row_Recordset2['Description']; ?></div></td>
                            <td class="td-order-form"><div id="field-padding" style="padding-left:5px">
							<?php 
							if($row_Recordset2['Amount'] >= 0.001){
							echo $row_Recordset2['Amount']; 
							}
							?></div></td>
                          </tr>
                            <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
                        </table>
                      </div></td>
                    </tr>
                    </table>
                  <table width="660" border="0" cellpadding="0" cellspacing="1" class="combo">
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      </tr>
                    <tr>
                      <td><div id="field-padding"><strong>Requestor</strong></div></td>
                      <td><div id="field-padding" style="padding-left:5px"><?php echo $row_Recordset1['TechnicianId']; ?></div></td>
                      <td valign="bottom"><div style="padding-left:5px">..................................................</div></td>
                      <td><div id="field-padding" style="padding-left:10px"><strong>Issuer</strong></div></td>
                      <td><div id="field-padding" style="padding-left:5px"><?php echo $row_Recordset1['Issuer']; ?></div></td>
                      <td valign="bottom"><div style="padding-left:5px">..................................................</div></td>
                      </tr>
                    </table>
            </form></td>
          </tr>
        </table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
