<?php require_once('../Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../functions/functions.php');

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
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#6699CC">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
        <?php
include('../menu.php'); ?>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
          <tr>
            <td colspan="4" align="center" bordercolor="#9E9E9E" bgcolor="#E0E0E0"><span class="HEADER">SEAVEST AFRICA TRADING CC</span></td>
            </tr>
            <tr>
              <td width="263" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;P.O.Box 201153 Durban North. 4016</td>
              <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Tel: 031 563 7735</td>
              <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Fax: 0865 191 153</td>
              <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;nicky@seavest.co.za</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
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
