<?php require_once('../../Connections/seavest.php'); ?>
<?php
// Load the Navigation classes
require_once('../../includes/nav/NAV.php'); 

$nav_Recordset3 = new NAV_Regular("nav_Recordset3", "Recordset3", "", KT_getPHP_SELF(), 50);

//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');

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

require_once('../../functions/functions.php');

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

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT Id, Name FROM tbl_companies";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$scope = 1;

if(!empty($_POST['search'])){
	
	$where = "WHERE tbl_hes.ScopeOfWork LIKE '%". $_POST['search'] ."%' OR tbl_sites.Name LIKE '%". $_POST['search'] ."%'";
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_hes.JobNo, tbl_companies.Name, tbl_hes.ScopeOfWork, tbl_hes.Date, tbl_hes.Id FROM ((tbl_hes LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_hes.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_hes.SiteId) $where";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

mysql_select_db($database_seavest, $seavest);
$query_Recordset6 = "SELECT * FROM tbl_sites";
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

$nav_Recordset3->checkBoundries();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../../includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/DependentDropdown.js"></script>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../../includes/skins/style.js" type="text/javascript"></script>
<script type="text/javascript">
  $NAV_SETTINGS = {
     'show_as_buttons': false
  }
</script>
<script type="text/javascript">
<!--
$NAV_SETTINGS = {
     'show_as_buttons': false
  }
//-->
</script>
<script type="text/javascript" src="../../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../../includes/resources/calendar.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset6 = new WDG_JsRecordset("Recordset6");
echo $jsObject_Recordset6->getOutput();
//end JSRecordset
?>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../../menu.php'); ?>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
            <tr>
              <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
            <tr>
              <td colspan="3" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4">
              <div style="margin-left:30px">
                <table border="0" cellpadding="2" cellspacing="3">
                  <tr>
                    <td><form name="form6" method="post" action="search.php">
                      <table border="0" cellspacing="3" cellpadding="2">
                        <tr>
                          <td><input name="search" type="text" class="combo" id="search" style="width:400px"></td>
                          <td><input name="search_btn" type="submit" class="btn_search" id="search_btn" value=""></td>
                          </tr>
                        </table>
                      </form>
                      </td>
                  </tr>
                </table>
              <br>
              <table border="0" cellpadding="2" cellspacing="3" class="combo_bold">
                <tr>
                  <td><?php echo $totalRows_Recordset3; ?> Results</td>
                </tr>
              </table>
              <br>
              <div id="list-brdr">
                <table border="0" cellpadding="3" cellspacing="2">
                  <tr class="td-header">
                    <td width="50" align="left" nowrap><strong>&nbsp;Job No</strong></td>
                    <td width="127" align="left"><strong>&nbsp;Company</strong></td>
                    <td width="151" align="left"><strong>&nbsp;Site Address </strong></td>
                    <td width="141" align="left">&nbsp;Scope of Work</td>
                    <td width="98" align="left"><strong>&nbsp;Date</strong></td>
                    <td width="30" align="center">&nbsp;</td>
                    <td width="30" align="center">DOA</td>
                    <td width="30" align="center">EAP</td>
                    <td width="30" align="center">JSA</td>
                    <td width="30" align="center">JMS</td>
                    </tr>
                  <?php do { ?>
<tr onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;" class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>">
                    <td width="50" align="left" nowrap><a href="hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&New" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"> &nbsp;&nbsp;<?php echo $row_Recordset3['JobNo']; ?></a></td>
                    <td align="left" nowrap><a href="hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&New" class="menu"> &nbsp;&nbsp;<?php echo $row_Recordset3['Name']; ?></a></td>
                    <td align="left" nowrap><a href="hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&New" class="menu"> &nbsp;&nbsp;<?php echo $row_Recordset3['Name_1']; ?></a></td>
                    <td width="141" align="left"><a href="hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&New" class="menu"> &nbsp;&nbsp;<?php echo $row_Recordset3['ScopeOfWork']; ?></a></td>
                    <td align="left" nowrap><a href="hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&New" class="menu"> &nbsp;&nbsp;<?php echo $row_Recordset3['Date']; ?></a></td>
                    <td width="30" align="center" nowrap><a href="hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&New"><img src="../../images/icons/btn-new.png" width="25" height="25" border="0"></a></td>
                    <td width="30" align="center" nowrap>
                    <a href="../../fpdf16/pdf-hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&Preview" target="_blank">
                    <img src="../../images/icons/btn-view.png" title="Preview Delegation of Work Document" width="25" height="25" border="0">
                    </a></td>
                    <td width="30" align="center" nowrap>
                    <a href="../../fpdf16/pdf-eap.php?Id=<?php echo $row_Recordset3['Id']; ?>&Preview" target="_blank">
                    <img src="../../images/icons/btn-view.png" title="Preview EAP Document" width="25" height="25" border="0">
                    </a></td>
                    <td width="30" align="center" nowrap>
                    <a href="../../fpdf16/pdf-jsa.php?Id=<?php echo $row_Recordset3['Id']; ?>&Preview" target="_blank">
                    <img src="../../images/icons/btn-view.png" title="Preview JSA Document" width="25" height="25" border="0">
                    </a></td>
                    <td width="30" align="center" nowrap>
                    <a href="../../fpdf16/pdf-jms.php?Id=<?php echo $row_Recordset3['Id']; ?>&Preview" target="_blank">
                    <img src="../../images/icons/btn-view.png" title="Preview JMS Document" width="25" height="25" border="0">
                    </a></td>
</tr>
                  <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                  </table>
              </div>
              </div>
<br>
                <br>
                <BR />
                <div class="KT_bottomnav" align="center">
                  <div class="combo">
                    <?php
      //Display Navigation		
      $nav_Recordset3->Prepare();
      require("../../includes/nav/NAV_Text_Navigation.inc.php");
    ?>
                  </div>
                </div></td>
            </tr>
        </table></td>
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

mysql_free_result($Recordset5);

mysql_free_result($Recordset6);

mysql_free_result($Recordset4);
?>
