<?php require_once('../Connections/inv.php'); ?>
<?php require_once('../Connections/inv.php'); ?>
<?php require_once('../Connections/inv.php'); 

session_start();

require_once('../functions/functions.php');

select_db();

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
$query_Recordset1 = "SELECT * FROM tbl_status";
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_Recordset2 = $_SESSION['kt_login_id'];
}
mysql_select_db($database_inv, $inv);
$query_Recordset2 = sprintf("SELECT * FROM tbl_support WHERE UserId = %s AND Status = 'Resolved' ORDER BY `Date` DESC", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $inv) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
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
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../menu.php'); ?></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <p>&nbsp;
          <form name="form2" method="post" action="" style="margin-left:30px">
            <?php 
			
			if($totalRows_Recordset2 >= 1){
			
			$num_rows = $totalRows_Recordset2;
			
			$i = 0;
			
			do { 
			
			$i++;
			?>
              <table width="100%" border="0" cellpadding="2" cellspacing="3" class="comb-sms">
                <tr>
                  <td width="13%"><em><strong>Date</strong></em></td>
                  <td width="87%"><?php echo $row_Recordset2['Date']; ?></td>
                  </tr>
                <tr>
                  <td><em><strong>Job / Invoice No</strong></em></td>
                  <td><?php echo $row_Recordset2['JobNo']; ?></td>
                  </tr>
                <tr>
                  <td><em><strong>Location</strong></em></td>
                  <td><?php echo $row_Recordset2['JobStatus']; ?></td>
                  </tr>
                <tr>
                  <td><strong><em>Status</em></strong></td>
                  <td><?php echo $row_Recordset2['Status']; ?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  </tr>
                <tr>
                  <td valign="top"><em><strong>Error / Request</strong></em></td>
                  <td><?php echo nl2br($row_Recordset2['Error']); ?></td>
                  </tr>
                <tr>
                  <td colspan="2">
                  <?php if($i != $totalRows_Recordset2){ ?>
                  <div id="support-hr"></div>
                  <?php } else { ?>
                  <div id="support-hr-2"></div>
                  <?php } ?>
                  </td>
                  </tr>
              </table>
              <?php 
			  
			  } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); 
			  
			}
			  ?>
          </form>
          <p> 
          <p>&nbsp;</p>
          </p>
          </p>
</td>
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
