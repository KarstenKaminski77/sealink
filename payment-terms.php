<?php require_once('Connections/seavest.php'); ?>
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
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = $_GET['Id'];
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_companies WHERE Id = %s", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

if(isset($_POST['Submit'])){
	
	select_db();
	
	$rate = $_POST['rate'];
	$company = $_POST['company'];
	
	mysql_query("UPDATE tbl_companies SET Payment = '$rate' WHERE Id = '$company'")or die(mysql_error());
	
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
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
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td><br>
          <br>
          <form name="form2" method="post" action="payment-terms.php" style="padding-left:30px">
          <?php if($totalRows_Recordset2 >= 1){ ?>
            <table border="0" cellspacing="1" cellpadding="3">
              <tr class="td-header">
                <td width="200"><?php echo $row_Recordset2['Name']; ?></td>
                <td width="60"><input name="rate" type="text" class="combo" id="rate" style="width:60px" value="<?php echo $row_Recordset2['Payment']; ?>"></td>
              </tr>
              <tr class="even">
                <td width="200"><input name="company" type="hidden" id="company" value="<?php echo $row_Recordset2['Id']; ?>"></td>
                <td width="60" align="right"><input name="Submit" type="submit" class="btn-edit" id="Submit" value=""></td>
              </tr>
            </table>
            <p>&nbsp;</p>
            <?php } ?>
            <table border="0" cellspacing="1" cellpadding="3">
              <tr class="td-header">
                <td width="200">Company</td>
                <td width="60">Rate</td>
                <td width="40" align="center">&nbsp;</td>
              </tr>
              <?php do { ?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onmouseover="this.oldClassName = this.className; this.className='list-over';" onmouseout="this.className = this.oldClassName;">
                <td width="200"><?php echo $row_Recordset1['Name']; ?></td>
                <td width="60"><?php echo $row_Recordset1['Payment']; ?></td>
                <td width="40" align="center"><a href="payment-terms.php?Id=<?php echo $row_Recordset1['Id']; ?>"><img src="images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
              </tr>
              <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
            </table>
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
?>
