<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');
require_once('includes/tng/triggers/tNG_DynamicThumbnail.class.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

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

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT * FROM tbl_reports WHERE QuoteNo = '%s'", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

// Show Dynamic Thumbnail
$objDynamicThumb1 = new tNG_DynamicThumbnail("", "KT_thumbnail1");
$objDynamicThumb1->setFolder("photos/");
$objDynamicThumb1->setRenameRule("{Recordset3.Image}");
$objDynamicThumb1->setResize(200, 0, true);
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
.img_border {
	margin: 0px;
	padding: 2px;
	border: 1px solid #0067AA;
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
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%"cellpadding="0" cellspacing="1">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center"><div id="add_row">
          <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td width="9%" class="tb_border"><form name="form2" method="post" action="photo_view.php?Id=<?php echo $_GET['Id']; ?>&photos">
                  <input name="Submit2" type="submit" class="tarea2" value="View Photos">
                                      </form>                </td>
                <td width="91%" class="tb_border"><form name="form3" method="post" action="photo_view.php?Id=<?php echo $_GET['Id']; ?>">
                  <input name="Submit3" type="submit" class="tarea2" value="View Report">
                          </form>                </td>
              </tr>
              </table>
        </div>
          
          <p>
<?php if(isset($_GET['photos'])){ ?>
<div style="width:750px">
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
<?php
  do { // horizontal looper version 3
?>
  <td align="center"><div style="margin-bottom:40px"><img border="0" src="<?php echo $objDynamicThumb1->Execute(); ?>" class="img_border" /> &nbsp; <a href="photo_view.php?delete=<?php echo $row_Recordset3['Id']; ?>&amp;Id=<?php echo $_GET['Id']; ?>&amp;photos"><img src="images/no.jpg" width="15" height="15" border="0"></a></div></td>
  <?php
    $row_Recordset3 = mysql_fetch_assoc($Recordset3);
    if (!isset($nested_Recordset3)) {
      $nested_Recordset3= 1;
    }
    if (isset($row_Recordset3) && is_array($row_Recordset3) && $nested_Recordset3++ % 3==0) {
      echo "</tr><tr>";
    }
  } while ($row_Recordset3); //end horizontal looper version 3
?>
            </tr>
          </table>
		  </p></div>
<?php } else { ?>
<div style="width:700px; margin-right:30px; text-align:left"><span class="combo"><?php echo $row_Recordset4['Report']; ?></span></div>
<?php } ?>          </td>
      </tr>
    </table>
	
	</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>
