<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once('Connections/seavest.php'); 

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

require_once('includes/tng/triggers/tNG_DynamicThumbnail.class.php');

require_once('functions/functions.php');

// Load the KT_back class
require_once('includes/nxt/KT_back.php');

require_once('functions/functions.php');

select_db();

if(isset($_GET['delete'])){

$photo = $_GET['delete'];

mysql_query("DELETE FROM tbl_photos WHERE Id = '$photo'")or die(mysql_error());

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
$query_Recordset3 = sprintf("SELECT * FROM tbl_photos WHERE QuoteNo = '%s'", $colname_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

// Show Dynamic Thumbnail
$objDynamicThumb1 = new tNG_DynamicThumbnail("../", "KT_thumbnail1");
$objDynamicThumb1->setFolder("photos/");
$objDynamicThumb1->setRenameRule("{Recordset3.Image}");
$objDynamicThumb1->setResize(100, 100, true);
$objDynamicThumb1->setWatermark(false);
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
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><form action="fpdf16/pdf/quote_mail.php" method="post" enctype="multipart/form-data" name="form2">
          <table width="700" border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td align="left" nowrap class="tb_border"><strong>Quotation </strong><?php echo $row_Recordset3['QuoteNo']; ?></td>
              </tr>
			</table>
           <?php do { ?>
             <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="120"><img border="0" src="<?php echo $objDynamicThumb1->Execute(); ?>" class="tb_border" style="margin-bottom:15px" /></td>
                    <td><a href="q_photos.php?delete=<?php echo $row_Recordset3['Id']; ?>&amp;Id=<?php echo $_GET['Id']; ?>"><img src="images/no.jpg" width="15" height="15" border="0"></a></td>
                  </tr>
                            </table>
             <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?><p>&nbsp;</p>
        </form>
        <br>
          <br>
          <BR />
          <div class="KT_bottomnav" align="center">
            <div class="combo"></div>
          </div></td></tr>
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
