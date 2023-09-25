<?php require_once('Connections/seavest.php'); ?>
<?php
require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

$areaid = $_SESSION['areaid'];

$colname_Recordset1 = "-1";
if (isset($_SESSION['areaid'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_SESSION['areaid'] : addslashes($_SESSION['areaid']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT * FROM tbl_register WHERE AreaId = %s GROUP BY WeekNo", $colname_Recordset1);
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
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
            <div style="padding-left:25px">
              <?php do { ?>
                <table border="0" cellpadding="2" cellspacing="0">
                  <tr><td class="combo">
                    <a href="register_view.php?week=<?php echo $row_Recordset1['WeekNo']; ?>" class="menu">
                      <?php
$week_number = $row_Recordset1['WeekNo'];
$date = $row_Recordset1['DateMon'];
$date1 = explode(" ", $date);
$day = $date1[0];
$month = $date1[1];
$year = $date1[2];

echo date('d M Y', mktime(0,0,0,$month,$day,$year));
?>		  
                      </a>
                    </td></tr>
                </table>
                <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
            </div>
            <?php } // Show if recordset not empty ?><p>&nbsp;</p></td>
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
