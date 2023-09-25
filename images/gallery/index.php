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

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

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

if(isset($_POST['send'])){
	
	$images = array();
	
	for($i=0;$i<count($_POST['image']);$i++){
		
		$image = $_POST['image'][$i]; 
		
		array_push($images, $image);
	}
	
	$_SESSION['images'] = $images;
	
	$_SESSION['message'] = $_POST['message'];
	$_SESSION['email'] = $_POST['email'];
	
	header('Location: mail.php');
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

if(!empty($_POST['jobno'])){
	
	$jobno = $_POST['jobno'];
	
	$query_Recordset4 = "
	  SELECT
		  tbl_history_relation.PhotoId,
		  tbl_history_photos.Photo,
		  tbl_history_relation.JobId,
		  tbl_jc.JobNo,
		  tbl_history_relation.Active
	  FROM
		  (
			  tbl_history_relation
			  LEFT JOIN tbl_history_photos ON tbl_history_photos.Id = tbl_history_relation.PhotoId
		  )
	  INNER JOIN tbl_jc ON tbl_jc.JobId = tbl_history_relation.JobId
	  WHERE
		  tbl_jc.JobNo = '$jobno'
	  GROUP BY
		  tbl_history_photos.Id";
	
	mysql_select_db($database_inv, $inv);		
	$query_Recordset4 = $query_Recordset4;
	$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
	$row_Recordset4 = mysql_fetch_assoc($Recordset4);
	$totalRows_Recordset4 = mysql_num_rows($Recordset4);
	
	
} else {

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

}

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
        <td><p>&nbsp;</p>
          <p>&nbsp;
            <form name="form2" method="post" action="index.php" style="margin-left:30px">
              <table width="705" border="0" cellpadding="3" cellspacing="1">
                <?php if($totalRows_Recordset4 >= 1){ ?>
                <tr>
                  <td width="837" colspan="4"><input name="email" type="text" class="td-mail" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:705px"></td>
                </tr>
                <tr>
                  <td colspan="4"><input name="attach" type="file" class="td-mail" id="attach"></td>
                </tr>
                <tr>
                  <td colspan="4"><textarea name="message" rows="5" class="td-mail" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:705px">Message</textarea></td>
                </tr>
                <tr>
                  <td colspan="4" align="right"><input name="send" type="submit" class="btn-blue-generic" id="send" value="Send"></td>
                </tr>
                <tr>
                  <td colspan="4" align="right">&nbsp;</td>
                </tr>
                <?php } ?>
              </table>
              <table width="705" border="0" cellpadding="3" cellspacing="1">
                <tr>
                  <td width="106" class="td-header">Region</td>
                  <td width="106" class="td-header">Company</td>
                  <td width="233" class="td-header">Site</td>
                  <td width="233" class="td-header">Job Number</td>
                </tr>
                <tr>
                  <td width="106"><select name="area" class="tarea-106" id="area">
                    <option value="">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset1['Id']?>"><?php echo $row_Recordset1['Area']?></option>
                    <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                  </select></td>
                  <td width="106"><select name="company" class="tarea-106" id="company">
                    <option value="">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset2['Id']?>"><?php echo $row_Recordset2['Name']?></option>
                    <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                  </select></td>
                  <td width="233"><select name="site" class="tarea-247" id="site" wdg:subtype="DependentDropdown" wdg:type="widget" wdg:recordset="Recordset3" wdg:displayfield="Name" wdg:valuefield="Id" wdg:fkey="Company" wdg:triggerobject="company">
                    <option value="">Select one...</option>
                  </select></td>
                  <td width="233"><input name="jobno" type="text" class="tarea-247" id="jobno" value="<?php echo $_POST['jobno']; ?>"></td>
                </tr>
                <tr>
                  <td colspan="4" align="right"><input name="search" type="submit" class="btn-blue-generic" id="search" value="Search"></td>
                </tr>
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4">
                  
                  <?php do{ ?>
                  <div style="float:left; width:33%">
                    <table border="0" cellspacing="3" cellpadding="2">
                      <tr>
                        <td align="center">
                          <a href="../images/history/<?php echo $row_Recordset4['Photo']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})">
                            <?php createThumbs('../images/history/','../images/history/thumbnails/',200,$row_Recordset4['Photo']); ?>
                            <img src="../images/history/thumbnails/<?php echo $row_Recordset4['Photo']; ?>" border="0" class="img_border" />
                          </a>
                        </td>
                        <td>&nbsp;</td>
                        </tr>
                      <tr>
                        <td align="center" class="blue-generic"><?php echo $row_Recordset4['Name']; ?></td>
                        <td><input name="image[]" type="checkbox" id="image[]" value="<?php echo $row_Recordset4['Photo']; ?>"></td>
                        </tr>
                      <tr>
                        <td align="center">&nbsp;</td>
                        <td>&nbsp;</td>
                        </tr>
                    </table>
                  </div>
                  <?php } while ($row_Recordset4 = mysql_fetch_assoc($Recordset4)); ?>
                  </td>
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
