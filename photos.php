<?php 
error_reporting(ERROR);
ini_set('display_errors', '1');

require_once('Connections/seavest.php');

require_once('Connections/inv.php');

if(isset($_GET['Id'])){
	
	setcookie('quoteno', $_GET['Id'], 60 * 60 * 24 * 365 + time());
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
		$quoteno = $_COOKIE['quoteno'];
		
		mysql_query("INSERT INTO tbl_photos (QuoteNo,Image) VALUES ('$quoteno','$image')")or die(mysql_error());
		
		copy('gallery/images/'. $image, 'photos/'. $image);
	}
}

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("QuoteNo", true, "text", "", "", "", "Rquired Field");
$formValidation->addField("Image", true, "", "", "", "", "Rquired Field");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("photos/");
  $deleteObj->setDbFieldName("Image");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("Image");
  $uploadObj->setDbFieldName("Image");
  $uploadObj->setFolder("photos/");
  $uploadObj->setMaxSize(50000);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

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
$query_Recordset6 = "SELECT * FROM tbl_photos ORDER BY Id DESC LIMIT $limit";
$Recordset6 = mysql_query($query_Recordset6, $inv) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

// Make an insert transaction instance
$ins_tbl_photos = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_photos);
// Register triggers
$ins_tbl_photos->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_photos->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_photos->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$ins_tbl_photos->registerTrigger("AFTER", "Trigger_ImageUpload", 98);
// Add columns
$ins_tbl_photos->setTable("tbl_photos");
$ins_tbl_photos->addColumn("QuoteNo", "STRING_TYPE", "POST", "QuoteNo");
$ins_tbl_photos->addColumn("Image", "FILE_TYPE", "FILES", "Image");
$ins_tbl_photos->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_photos = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_photos);
// Register triggers
$upd_tbl_photos->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_photos->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_photos->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$upd_tbl_photos->registerTrigger("AFTER", "Trigger_ImageUpload", 98);
// Add columns
$upd_tbl_photos->setTable("tbl_photos");
$upd_tbl_photos->addColumn("QuoteNo", "STRING_TYPE", "POST", "QuoteNo");
$upd_tbl_photos->addColumn("Image", "FILE_TYPE", "FILES", "Image");
$upd_tbl_photos->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_photos = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_photos);
// Register triggers
$del_tbl_photos->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_photos->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$del_tbl_photos->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_tbl_photos->setTable("tbl_photos");
$del_tbl_photos->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_photos = $tNGs->getRecordset("tbl_photos");
$row_rstbl_photos = mysql_fetch_assoc($rstbl_photos);
$totalRows_rstbl_photos = mysql_num_rows($rstbl_photos);

// Show Dynamic Thumbnail
$objDynamicThumb1 = new tNG_DynamicThumbnail("../", "KT_thumbnail1");
$objDynamicThumb1->setFolder("photos/");
$objDynamicThumb1->setRenameRule("{Recordset6.Image}");
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
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="includes/nxt/scripts/form.js.php" type="text/javascript"></script>
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

<link rel="stylesheet" type="text/css" href="highslide/highslide.css" />

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
        <?php
include('menu.php'); ?>
    </td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" cellpadding="0" cellspacing="1">
          <tr>
            <td colspan="4" align="center"><img src="images/banner.jpg" width="823" height="213" border="0"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;
          <?php
	echo $tNGs->getErrorMsg();
?>
          <table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><div id="list-brdr">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><form action="photo_count.php?Id=<?php echo $_GET['Id']; ?>" method="post" id="form2">
              <table width="100%" border="0" cellspacing="1" cellpadding="3">
                <tr>
                  <td width="81%" class="td-header">
                    <a href="quote_calc.php?Id=<?php if(isset($_GET['Id'])){ echo $_GET['Id']; } else { echo $_COOKIE['quoteno']; } ?>">Return to quotation</a> </td>
                  <td width="9%" align="right" class="td-header"><select name="no_new" class="tarea2" id="no_new">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="6">6</option>
                    </select></td>
                  <td width="10%" class="td-header"><input name="Submit2" type="submit" class="tarea2" value="Submit"></td>
                  </tr>
                </table>
            </form>
            
            <div class="KT_tng">
              <h1>&nbsp;</h1>
              <div>
                <form method="post" id="form2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
                  <?php $cnt1 = 0; ?>
                  <?php do { ?>
                  <?php $cnt1++; ?>
                  <table cellpadding="2" cellspacing="0">
                    <tr>
                      <td class="KT_th"><label for="Image_<?php echo $cnt1; ?>"></label>
                        <input name="Image_<?php echo $cnt1; ?>" type="file" class="tarea2" id="Image_<?php echo $cnt1; ?>" size="32" />
                        <?php echo $tNGs->displayFieldError("tbl_photos", "Image", $cnt1); ?> </td>
                      <td class="KT_th"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" /></td>
                      </tr>
                    </table>
                  <input type="hidden" name="kt_pk_tbl_photos_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_photos['kt_pk_tbl_photos']); ?>" />
                  <input type="hidden" name="QuoteNo_<?php echo $cnt1; ?>" id="QuoteNo_<?php echo $cnt1; ?>" value="<?php if(isset($_GET['Id'])){ echo $_GET['Id']; } else { echo $_COOKIE['quoteno']; } ?>" />
                  <?php } while ($row_rstbl_photos = mysql_fetch_assoc($rstbl_photos)); ?>
                  <div>
                    <div></div>
                    </div>
                  </form>
                </div>
              </div>
            </td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>


          <p>&nbsp;</p>
          <form name="form3" method="post" action="photos.php" style="margin-left:30px">
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
                          <td align="center"><a href="photos/<?php echo $row_Recordset6['Image']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})"> <img src="<?php echo $objDynamicThumb1->Execute(); ?>" border="0" class="img_border" /></a></td>
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
