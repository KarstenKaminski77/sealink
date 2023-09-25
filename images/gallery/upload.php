<?php require_once('../Connections/inv.php'); ?>
<?php 
session_start();

require_once('../Connections/inv.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

require_once('../includes/tng/triggers/tNG_DynamicThumbnail.class.php');


require_once('../functions/functions.php');

select_db();

// Find the number of times to loop the file field
$numrows = 1;

if(isset($_POST['add'])){
	
	$numrows = count($_POST['id']) + 1;
	
	if($numrows == 0){
		
		$numrows = 1;
	}
}

if(isset($_FILES['photo']['name'])){
	
	select_db();
		
	$area = $_POST['area'];
	$company = $_POST['company'];
	$site = $_POST['site']; echo $numrows;
	
	for($i=0;$i<=$numrows;$i++){
		
		$target_path = "../gallery/images/";
		$target_path = $target_path . basename( $_FILES['photo']['name'][$i]); 
		
		if(move_uploaded_file($_FILES['photo']['tmp_name'][$i], $target_path)){
			
			$file_attachment = $_FILES['photo']['name'][$i];
			$ext = explode(".", $file_attachment);
			$extension = $ext[1];
			
			$name = $_POST['name'][$i];
			
			$image = rename('../gallery/images/'.$file_attachment, '../gallery/images/'. $i .'-'. date('H-i-s') .'.'. $extension);
			$image_name = $i .'-'. date('H-i-s') .'.'. $extension;
			
			mysql_query("INSERT INTO tbl_gallery (AreaId,CompanyId,SiteId,Name,Image) VALUES ('$area','$company','$site','$name','$image_name')")or die(mysql_error());
		}
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

mysql_select_db($database_inv, $inv);
$query_Recordset1 = "SELECT * FROM tbl_areas ORDER BY Area ASC";
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_inv, $inv);
$query_Recordset2 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset2 = mysql_query($query_Recordset2, $inv) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_inv, $inv);
$query_Recordset3 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

if(!empty($_POST['area'])){
	
	$area = "AreaId = '". $_POST['area'] ."' AND ";
}

if(!empty($_POST['company'])){
	
	$company = "CompanyId = '". $_POST['company'] ."' AND ";
}

if(!empty($_POST['site'])){
	
	$site = "SiteId = '". $_POST['site'] ."' AND ";
}

if(!empty($_POST['name'])){
	
	$name = "Name LIKE '%". $_POST['name'] ."%' AND ";
}

if(!isset($area) && !isset($company) && !isset($site) && !isset($name)){
	
	$end = "Id = -1";
	
} else {
	
	$end = "1=1";
}

$where = "WHERE ". $area . $company . $site . $name . $end;

mysql_select_db($database_inv, $inv);
$query_Recordset4 = "SELECT * FROM tbl_gallery $where";
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

// Show Dynamic Thumbnail
$objDynamicThumb1 = new tNG_DynamicThumbnail("../", "KT_thumbnail1");
$objDynamicThumb1->setFolder("../gallery/images/");
$objDynamicThumb1->setRenameRule("{Recordset4.Image}");
$objDynamicThumb1->setResize(200, 200, true);
$objDynamicThumb1->setWatermark(false);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
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
.img_border {
	margin: 0px;
	padding: 2px;
	border: 1px solid #0067AA;
}

-->
</style>
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset3 = new WDG_JsRecordset("Recordset3");
echo $jsObject_Recordset3->getOutput();
//end JSRecordset
?>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="../highslide/highslide.js"></script>
<script type="text/javascript">
<!--
hs.graphicsDir = '../highslide/graphics/';
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
        <td><p>&nbsp;</p><form action="" method="post" enctype="multipart/form-data" name="form2" style="margin-left:30px">
              <table width="705" border="0" cellpadding="3" cellspacing="1">
                <tr>
<td colspan="4" class="td-header">Upload Image</td>
                </tr>
                <tr>
                  <td width="837" colspan="4"><select name="area" class="tarea-100per" id="area">
                    <option value="">Region</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset1['Id']; ?>"<?php if($row_Recordset1['Id'] == $_POST['area']){ ?> selected="selected"<?php } ?>><?php echo $row_Recordset1['Area']?></option>
                    <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td colspan="4"><select name="company" class="tarea-100per" id="company">
                    <option value="">Oil Company</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset2['Id']?>"<?php if($row_Recordset2['Id'] == $_POST['company']){ ?> selected="selected"<?php } ?>><?php echo $row_Recordset2['Name']?></option>
                    <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td colspan="4"><select name="site" class="tarea-100per" id="site" wdg:subtype="DependentDropdown" wdg:type="widget" wdg:recordset="Recordset3" wdg:displayfield="Name" wdg:valuefield="Id" wdg:fkey="Company" wdg:triggerobject="company" <?php if(isset($_POST['site'])){ ?>wdg:selected="<?php echo $_POST['site']; ?>"<?php } ?>>
                    <option>Site</option>
                  </select></td>
                </tr>
                <tr>
                  <td colspan="4" align="right">  <?php for($i=0;$i<$numrows;$i++){ ?>
<table width="100%" border="0" align="left" cellpadding="2" cellspacing="3">
<tr>
                                  <td width="218"><input name="photo[]" type="file" class="tarea-100per" id="photo[]" size="0"></td>
                                  <td width="882"><input name="name[]" type="text" class="tarea-100per" id="name[]" onFocus="if(this.value=='Name'){this.value=''}" onBlur="if(this.value==''){this.value='Name'}" value="Name" /></td>
                                  <td width="13"><input name="id[]" type="hidden" id="id[]" value="<?php echo $i; ?>" /></td>
                                  <td width="15">
                                  <input name="add" type="submit" class="new-image" id="add" value="">
                                  </td>
                      </tr>
                              </table>
                              <?php } ?>
</td>
                </tr>
                <tr>
                  <td colspan="4" align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4" align="right"><input name="button2" type="submit" class="btn-blue-generic" id="button2" value="Upload"></td>
                </tr>
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

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>
