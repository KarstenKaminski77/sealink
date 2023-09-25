<?php 
//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);

    session_start();

    require_once('../Connections/seavest.php');
    require_once('../Connections/inv.php');

    if (!function_exists("GetSQLValueString")) {
        function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
        
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

    $order_id = $_GET["Id"];
    
    $sql = "
        SELECT 
            tbl_orders.OrderNo, 
            tbl_suppliers.Name, 
            tbl_suppliers.Address, 
            tbl_order_details.Qty, 
            tbl_order_details.Description, 
            tbl_orders.TechnicianId, 
            tbl_orders.Date, 
            tbl_orders.SiteId, 
            tbl_orders.JobNo, 
            tbl_orders.Issuer, 
            tbl_order_relation.OrderId 
        FROM 
            tbl_orders 
            LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId=tbl_orders.Id
            LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId
            LEFT JOIN tbl_suppliers ON tbl_suppliers.Id=tbl_orders.SupplierId
        WHERE 
            tbl_order_relation.OrderId = $order_id
    ";

    $Recordset1 = mysqli_query($con, $sql) or die(mysqli_error($con));
    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($Recordset1);

    $sql = "
        SELECT 
            tbl_orders.OrderNo, 
            tbl_suppliers.Name, 
            tbl_suppliers.Address, 
            tbl_order_details.Qty, 
            tbl_order_details.Description, 
            tbl_order_details.Amount, 
            tbl_orders.TechnicianId, 
            tbl_orders.SiteId, 
            tbl_orders.JobNo, 
            tbl_orders.Issuer, 
            tbl_order_relation.OrderId 
        FROM
            tbl_orders 
            LEFT JOIN tbl_order_relation ON tbl_order_relation.OrderId=tbl_orders.Id
            LEFT JOIN tbl_order_details ON tbl_order_details.Id=tbl_order_relation.ItemId
            LEFT JOIN tbl_suppliers ON tbl_suppliers.Id=tbl_orders.SupplierId
        WHERE 
            tbl_order_relation.OrderId = $order_id
    ";

    $Recordset2 = mysqli_query($con, $sql) or die(mysqli_error($con));
    $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
    $totalRows_Recordset2 = mysqli_num_rows($Recordset2);

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
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
    </td>
    <td valign="top"><table width="660" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td><table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><form name="form2" method="post" action="order-form.php?Id=<?php echo $_GET['Id']; ?>">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
                <tr>
                  <td><table width="660" border="0" cellpadding="0" cellspacing="0" class="combo">
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
                      <td colspan="3" valign="top"><div style="border-bottom:solid 1px #A6CAF0; width:660px">
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
                      </div></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="17">&nbsp;</td>
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
                        <td class="td-order-form"><span style="padding-left:5px">
                          <?php 
							if($row_Recordset2['Amount'] >= 0.001){
							echo $row_Recordset2['Amount']; 
							}
							?>
                        </span></td>
                      </tr>
                      <?php } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2)); ?>
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
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Recordset1);
mysqli_free_result($Recordset2);
?>
