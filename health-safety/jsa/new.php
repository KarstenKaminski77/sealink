<?php require_once('../../Connections/inv.php'); ?>
<?php
//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');

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

mysql_select_db($database_inv, $inv);
$query_Recordset1 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_inv, $inv);
$query_Recordset2 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
$Recordset2 = mysql_query($query_Recordset2, $inv) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_inv, $inv);
$query_Recordset3 = "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC";
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
?>
<?php require_once('../../functions/functions.php'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
-->
</style>
<script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../../includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset2 = new WDG_JsRecordset("Recordset2");
echo $jsObject_Recordset2->getOutput();
//end JSRecordset
?>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../../menu.php'); ?></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center">
        <br>
<br>
<form name="form2" method="post" action="new-process.php" style="margin-left:30px">
          <div id="list-brdr">
            <table border="0" cellpadding="3" cellspacing="1">
              <tr class="td-header">
                <td width="150" align="left"><strong>&nbsp; Company</strong></td>
                <td width="150" align="left"><strong>&nbsp;Site</strong></td>
                <td width="150" align="left"><strong>&nbsp;Reference</strong></td>
                <td width="150" align="left">&nbsp;&nbsp;JSA Type</td>
                <td width="150" align="left">&nbsp;Work Activity</td>
              </tr>
              <tr class="even">
                <td width="150" align="left">
                  <select name="company" class="tarea-jla" id="company">
                  <option value="">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset1['Id']?>"><?php echo $row_Recordset1['Name']?></option>
                    <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                  </select>
               </td>
                <td width="150" align="left"><select name="site" class="tarea-jla" id="site" wdg:subtype="DependentDropdown" wdg:type="widget" wdg:recordset="Recordset2" wdg:displayfield="Name" wdg:valuefield="Id" wdg:fkey="Company" wdg:triggerobject="company">
                    <option value="">Select one...</option>
                  </select>
                </td>
                <td width="150" align="left" nowrap>
                  <input name="ref" type="text" class="tarea-jla" id="ref">
                </td>
                <td width="150" align="left" nowrap><select name="jsa" class="tarea-jla" id="jsa">
                  <option value="">Select one...</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_Recordset3['Id']?>"><?php echo $row_Recordset3['Risk']?></option>
                  <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                </select></td>
                <td width="150" align="left" nowrap><input name="work" type="text" class="tarea-jla" id="work"></td>
              </tr>
              <tr class="even">
                <td colspan="5" align="right" class="td-header"><input name="button2" type="submit" class="btn-blue-generic" id="button2" value="Create New"></td>
                </tr>
            </table>
          </div>
        </form></td>
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
?>
