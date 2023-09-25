<?php require_once('Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

if(isset($_POST['report'])){

$report = $_POST['report'];
$quoteno = $_GET['Id'];

mysql_query("UPDATE tbl_reports SET Report = '$report' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_Recordset3 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset3 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT * FROM tbl_reports WHERE QuoteNo = '%s'", $colname_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
include('menu.php'); ?>    </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%"cellpadding="0" cellspacing="1">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center" valign="top">
            <div id="add_row">
              <table width="100%" border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td class="tb_border">
				  <a href="quote_calc.php?Id=<?php if(isset($_GET['Id'])){ echo $_GET['Id']; } else { echo $_COOKIE['quoteno']; } ?>" style="color:#003399">Return to quotation</a> </td>
                </tr>
              </table>
            </div>
              <p>&nbsp;</p>
		<form name="form2" method="post" action="report.php?Id=<?php echo $_GET['Id']; ?>" style="margin-left:15px">
		<table border="0" align="center" cellpadding="0" cellspacing="0">
		<tr><td><textarea name="report" cols="145" rows="7" class="combo" id="report"><?php echo $row_Recordset3['Report']; ?></textarea></td>
		</tr>
<tr>
  <td align="right"><br>
    <input name="Submit2" type="submit" class="tarea2" value="Save"></td></tr>
</table>
</form>        </td>
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
