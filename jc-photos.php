<?php require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
<?php

if(isset($_GET['Id'])){
	
	setcookie('jobid', $_GET['Id'], 60 * 60 * 24 * 365 + time());
}
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

require_once('includes/tng/triggers/tNG_DynamicThumbnail.class.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

// Load the KT_back class
require_once('includes/nxt/KT_back.php');
 
if(isset($_POST['image'])){
	
	select_db();
	
	for($i=0;$i<count($_POST['image']);$i++){
		
		$image = $_POST['image'][$i];
		
		mysql_query("INSERT INTO tbl_history_photos (Photo) VALUES ('$image')")or die(mysql_error());
			
		$query = mysql_query("SELECT * FROM tbl_history_photos ORDER BY Id DESC")or die(mysql_error());
		$row = mysql_fetch_array($query);
			
		$photoid = $row['Id'];
		$jobid = $_COOKIE['jobid'];
			
		mysql_query("INSERT INTO tbl_history_relation (JobId,PhotoId) VALUES ('$jobid','$photoid')")or die(mysql_error());
		
		copy('gallery/images/'. $image, 'images/history/'. $image);
	}
}

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

mysql_select_db($database_inv, $inv);
$query_Recordset3 = "SELECT * FROM tbl_areas ORDER BY Area ASC";
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_inv, $inv);
$query_Recordset4 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

mysql_select_db($database_inv, $inv);
$query_Recordset5 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
$Recordset5 = mysql_query($query_Recordset5, $inv) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$limit = 0;

if(is_numeric($_POST['limit'])){
	
	$limit = $_POST['limit'];
}

mysql_select_db($database_inv, $inv);
$query_Recordset6 = "SELECT * FROM tbl_gallery ORDER BY Id DESC LIMIT $limit";
$Recordset6 = mysql_query($query_Recordset6, $inv) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

// Show Dynamic Thumbnail
$objDynamicThumb1 = new tNG_DynamicThumbnail("../", "KT_thumbnail1");
$objDynamicThumb1->setFolder("gallery/images/");
$objDynamicThumb1->setRenameRule("{Recordset6.Image}");
$objDynamicThumb1->setResize(100, 100, true);
$objDynamicThumb1->setWatermark(false);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
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
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

<style type="text/css">
<!--
.img_border {	margin: 0px;
	padding: 2px;
	border: 1px solid #0067AA;
}
-->
</style>
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset5 = new WDG_JsRecordset("Recordset5");
echo $jsObject_Recordset5->getOutput();
//end JSRecordset
?>

<script type="text/javascript" src="highslide/highslide.js"></script>
<script type="text/javascript">
<!--
hs.graphicsDir = 'highslide/graphics/';
    hs.outlineType = 'rounded-white';

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
//-->
</script>
</head>

<body>
<table width="750" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php include('menu.php'); ?>
    </td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" cellpadding="0" cellspacing="1">
          <tr>
            <td colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151" border="0"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td><form name="form3" method="post" action="jc-photos.php" style="margin-left:30px">
          <p>&nbsp;</p>
          <table width="705" border="0" cellpadding="3" cellspacing="1" class="td-header">
            <tr>
              <td><a href="jc_calc.php?Id=<?php if(isset($_GET['Id'])){ echo $_GET['Id']; } else { echo $_COOKIE['jobid']; } ?>">Return to job card</a></td>
            </tr>
          </table>
          <p>&nbsp;</p>
          <table width="705" border="0" cellpadding="3" cellspacing="1">
              <tr>
                <td class="td-header">Photos from gallery</td>
              </tr>
              <tr>
                <td><?php
				  if(is_numeric($_POST['limit'])){
					  
					  $value = 'value="'. $_POST['limit'] .'"';
					  
				  } else {
					  
					  $value = "onFocus=\"if(this.value=='Number of photos'){this.value=''}\" onBlur=\"if(this.value==''){this.value='Number of photos'}\" value=\"Number of photos\"";
					  
				  }
				  ?>
                  <input name="limit" type="text" class="tarea-hes" id="limit" style="width:100%" <?php echo $value; ?> ></td>
              </tr>
              <tr>
                <td align="right"><input name="button2" type="submit" class="btn-blue-generic" id="button2" value="Search"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="3">
                  <?php if($totalRows_Recordset6 >= 1){ ?>
                  <tr>
                      <?php
  do { // horizontal looper version 3
?>
                        <td align="center" valign="bottom"><table border="0" cellspacing="3" cellpadding="2">
                          <tr>
                            <td align="center"><a href="gallery/images/<?php echo $row_Recordset6['Image']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})"> <img src="<?php echo $objDynamicThumb1->Execute(); ?>" border="0" class="img_border" /></a></td>
                            <td>&nbsp;</td>
                            </tr>
                          <tr>
                            <td align="center" class="blue-generic"><?php echo $row_Recordset6['Name']; ?></td>
                            <td><input name="image[]" type="checkbox" id="image[]" value="<?php echo $row_Recordset6['Image']; ?>"></td>
                            </tr>
                          <tr>
                            <td align="center">&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                          </table></td>
                        <?php
    $row_Recordset6 = mysql_fetch_assoc($Recordset6);
    if (!isset($nested_Recordset6)) {
      $nested_Recordset6= 1;
    }
    if (isset($row_Recordset6) && is_array($row_Recordset6) && $nested_Recordset6++ % 4==0) {
      echo "</tr><tr>";
    }
  } while ($row_Recordset6); //end horizontal looper version 3
?>
                    </tr>
                    <?php } ?>
                </table></td></tr>
                <?php if($totalRows_Recordset6 >= 1){ ?>
              <tr>
                <td align="right"><input name="attach-image" type="submit" class="btn-blue-generic" id="attach-image" value="Attach"></td>
              </tr>
              <?php } ?>
            </table>
          </form>
          <p>&nbsp;</p>
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

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset6);
?>
