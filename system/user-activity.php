<?php require_once('../Connections/seavest.php'); ?>
<?php require_once('../Connections/inv.php'); ?>
<?php
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

$currentPage = $_SERVER["PHP_SELF"];

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

if(isset($_POST['Submit'])){
	
	if($_POST['date1'] != NULL){
			  
			  $date1 = $_POST['date1'];
			  
			  $where_1 = '`Date` >= "'. $date1 .'" AND ';
			  
			  }
	
	if($_POST['date2'] != NULL){
			  
			  $date2 = $_POST['date2'];
			  
			  $where_2 = '`Date` <= "'. $date1 .'" AND ';
			  
			  }
	
	if($_POST['user'] != NULL){
			  
			  $user = $_POST['user'];
			  
			  $where_3 = 'Name = "'. $user .'" AND ';
			  
			  }
			  
			  $where = 'WHERE '.$where_1.$where_2.$where_3."'1=1'";
}
	
$maxRows_Recordset3 = 30;
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

mysql_select_db($database_inv, $inv);
$query_Recordset3 = "SELECT * FROM tbl_login $where ORDER BY `Date` DESC";
$query_limit_Recordset3 = sprintf("%s LIMIT %d, %d", $query_Recordset3, $startRow_Recordset3, $maxRows_Recordset3);
$Recordset3 = mysql_query($query_limit_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);

if (isset($_GET['totalRows_Recordset3'])) {
  $totalRows_Recordset3 = $_GET['totalRows_Recordset3'];
} else {
  $all_Recordset3 = mysql_query($query_Recordset3);
  $totalRows_Recordset3 = mysql_num_rows($all_Recordset3);
}
$totalPages_Recordset3 = ceil($totalRows_Recordset3/$maxRows_Recordset3)-1;

mysql_select_db($database_inv, $inv);
$query_Recordset4 = "SELECT * FROM tbl_users ORDER BY Name ASC";
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$queryString_Recordset3 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset3") == false && 
        stristr($param, "totalRows_Recordset3") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset3 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset3 = sprintf("&totalRows_Recordset3=%d%s", $totalRows_Recordset3, $queryString_Recordset3);
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
-->
</style>
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../Calendar.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../includes/resources/calendar.js"></script>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
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
        <td><br>
          <br>
          <div style="margin-left:30px">
            <form name="form2" method="post" action="">
              <table border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td><input name="date1" class="tarea" id="date1" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format.' '.$KT_screen_time_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no"></td>
                  <td><input name="date2" class="tarea" id="date2" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format.' '.$KT_screen_time_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no"></td>
                  <td><select name="user" class="tarea" id="user">
                  <option value="">Select 0ne...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset4['Name']?>"><?php echo $row_Recordset4['Name']?></option>
                    <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
                  </select></td>
                  <td><input name="Submit" type="submit" class="btn_search" id="Submit" value=""></td>
                </tr>
              </table>
            </form>
            <br>
            <br>
            <?php
			if($totalRows_Recordset3 >= 1){
			?>
            <table border="0" cellspacing="1" cellpadding="3">
              <tr class="td-header">
                <td>User</td>
                <td>Date</td>
                <td>Location</td>
                </tr>
              <?php do { ?>
                <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                  <td><?php echo $row_Recordset3['Name']; ?></td>
                  <td><?php echo $row_Recordset3['Date']; ?></td>
                  <td><?php echo $row_Recordset3['Location']; ?></td>
                  </tr>
                <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                <?php if($totalRows_Recordset3 >= 31){ ?>
                <tr>
                <td colspan="3" align="center" class="combo"><a style="color:#006; font-weight:normal" href="<?php printf("%s?pageNum_Recordset3=%d%s", $currentPage, max(0, $pageNum_Recordset3 - 1), $queryString_Recordset3); ?>">Previous</a>&nbsp; &nbsp; <a style="color:#006; font-weight:normal" href="<?php printf("%s?pageNum_Recordset3=%d%s", $currentPage, min($totalPages_Recordset3, $pageNum_Recordset3 + 1), $queryString_Recordset3); ?>">Next</a></td>
                </tr>
                <?php } ?>
            </table>
            <?php } else { ?>
            <span class="combo">No results found</span>
<?php } ?>
          </div></td>
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
