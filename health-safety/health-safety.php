<?php require_once('../Connections/seavest.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

//start Trigger_FileUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileUpload(&$tNG) {
  $uploadObj = new tNG_FileUpload($tNG);
  $uploadObj->setFormFieldName("file");
  $uploadObj->setDbFieldName("File");
  $uploadObj->setFolder("documents/");
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("pdf, txt, doc, docx");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_FileUpload trigger

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

require_once('../functions/functions.php');

if(isset($_GET['Id'])){
	
	$id = $_GET['Id'];
	
	mysql_query("DELETE FROM tbl_health_safety WHERE Id = '$id'")or die(mysql_error());
	
}

if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = "-1";
if (isset($_SESSION['areaid'])) {
  $colname_area = $_SESSION['areaid'];
}
mysql_select_db($database_seavest, $seavest);
$query_area = sprintf("SELECT * FROM tbl_areas WHERE Id = %s", GetSQLValueString($colname_area, "int"));
$area = mysql_query($query_area, $seavest) or die(mysql_error());
$row_area = mysql_fetch_assoc($area);
$totalRows_area = mysql_num_rows($area);

// Make an insert transaction instance
$ins_tbl_health_safety = new tNG_insert($conn_seavest);
$tNGs->addTransaction($ins_tbl_health_safety);
// Register triggers
$ins_tbl_health_safety->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "upload");
$ins_tbl_health_safety->registerTrigger("AFTER", "Trigger_FileUpload", 97);
// Add columns
$ins_tbl_health_safety->setTable("tbl_health_safety");
$ins_tbl_health_safety->addColumn("Name", "STRING_TYPE", "POST", "name");
$ins_tbl_health_safety->addColumn("File", "FILE_TYPE", "FILES", "file");
$ins_tbl_health_safety->setPrimaryKey("Id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_health_safety = $tNGs->getRecordset("tbl_health_safety");
$row_rstbl_health_safety = mysql_fetch_assoc($rstbl_health_safety);
$totalRows_rstbl_health_safety = mysql_num_rows($rstbl_health_safety);

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_health_safety ORDER BY Id DESC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if($_SESSION['kt_login_level'] >= 1){
if(isset($_SESSION['areaid'])){
$areaid = $_SESSION['areaid'];
} else {
$areaid = 1;
}
} else {
$areaid = $_SESSION['kt_AreaId'];
}
$where = "AND tbl_jc.AreaId = ". $areaid ."";

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top">
	<table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
            <tr>
              <td width="834" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
            <tr>
              <td colspan="4" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4"><form action="" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
                <p>&nbsp;</p>
                <table border="0" cellpadding="2" cellspacing="3">
                  <tr>
                    <td><input name="name" type="text" class="combo" id="name"></td>
                    <td><input name="file" type="file" class="combo" id="file" size="10"></td>
                    </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="right"><input name="upload" type="submit" class="btn-upload" id="upload" value=""></td>
                    </tr>
                </table>
              </form>
                <br>
                <br>
                <div style="padding-left:30px">
                  <table border="0" cellpadding="3" cellspacing="1">
                    <tr class="td-header">
                      <td width="100" align="left" nowrap><strong>Document Name </strong></td>
                      <td width="40" align="center">&nbsp;</td>
                      <td width="40" align="center">&nbsp;</td>
                      <td width="40" align="center">&nbsp;</td>
                      </tr>
                    <?php
$jobid = $row_Recordset3['JobId'];
?><?php do { ?>
                    <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                      
                        <td width="100" style="padding-left:2px; padding-right:2px"><?php echo $row_Recordset1['Name']; ?></td>
                        <td width="40" align="center" style="padding-left:2px; padding-right:2px"><form name="form3" method="post" action="download.php">
                          <div class="btn-download" id="btn-download-bg">
                            <input title="Download PDF" name="button" type="submit" class="btn-download" id="button" value="">
                          </div>
                          <input name="file" type="hidden" id="file" value="<?php echo $row_Recordset1['File']; ?>">
                        </form></td>
                        <td width="40" align="center" style="padding-left:2px; padding-right:2px"><a href="documents/<?php echo $row_Recordset1['File']; ?>" target="_blank"><img src="../images/icons/btn-view.png" width="25" height="25" border="0"></a></td>
                        <td width="40" align="center" style="padding-left:2px; padding-right:2px"><a href="health-safety.php?Id=<?php echo $row_Recordset1['Id']; ?>"><img src="../images/icons/btn-delete.png" width="25" height="25" border="0" title="Delete"></a></td>
                        
                    </tr>
                    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                  </table>
                </div>
              </td>
                </tr>
              </table>
</td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($area);

mysql_free_result($Recordset1);
?>
