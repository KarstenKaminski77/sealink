<?php require_once('../Connections/seavest.php'); ?>
<?php require_once('../Connections/seavest.php'); ?>
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

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../functions/functions.php');

select_db();

if(isset($_POST['submit'])){
	
	$date = date('Y-m-d');
	$techician = $_POST['technician'];
	$alcohol = $_POST['alcohol'];
	$remarks = $_POST['remarks'];
	
	mysql_query("INSERT INTO tbl_alcohol_testing (Date, Technician, AlcoholLevel, Remarks) VALUES ('$date','$technician','$alcohol','$remarks')")or die(mysql_error());
	
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_technicians ORDER BY Name ASC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$string = "WHERE"; 

if(isset($_POST['search'])){
	
	if(!empty($_POST['tech'])){
		
		$tech = $_POST['tech'];
		$string .= ' Technician = "'. $tech .'" AND';
		
	}
	
if(!empty($_POST['date1'])){
	
	$string .= ' `Date` >= "'. $_POST['date1'] .'" AND';
}

if(!empty($_POST['date2'])){
	
	$string .= ' `Date` <= "'. $_POST['date2'] .'" AND';
}

}
	$string .= ' 1=1';

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_alcohol_testing $string ORDER BY Date ASC";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT * FROM tbl_technicians ORDER BY Name ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
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
.test {	padding: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 10px;
}
-->
</style>
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="Calendar.js"></script>
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
        <td><p><br>
          </p>
          <div style="padding-left:30px">
            <form name="form2" method="post" action="alcohol-testing.php">
              <table border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td>Technician</td>
                  <td><select name="technician" class="td-mail" id="technician">
                  <option value="">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset1['Name']?>"><?php echo $row_Recordset1['Name']?></option>
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
                  <td>Alcohol Level</td>
                  <td><input name="alcohol" type="text" class="td-mail" id="alcohol" style="width:300px;"></td>
                </tr>
                <tr>
                  <td valign="top">Remarks</td>
                  <td><textarea name="remarks" cols="45" rows="5" class="td-mail" id="remarks" style="width:300px"></textarea></td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td align="right"><input name="submit" type="submit" class="btn-submit" id="submit" value=""></td>
                </tr>
              </table>
            </form>
            <br>
            <br>
            <table border="0" cellpadding="3" cellspacing="1" class="combo">
              <tr>
                <td colspan="4"><form name="form3" method="post" action="alcohol-testing.php">
                  <table border="0" cellpadding="2" cellspacing="3" class="combo">
                    <tr>
                      <td><strong>Technician</strong></td>
                      <td><span class="test"><strong>From</strong></span></td>
                      <td><span class="test"><strong>To</strong></span></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><span class="test">
                        <select name="tech" class="combo" id="tech">
                          <option value="">Select one...</option>
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset3['Name']?>"><?php echo $row_Recordset3['Name']?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                          </select>
                      </span></td>
                      <td><span class="test">
                        <input name="date1" class="combo" id="date1" style="width:60px" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no">
                      </span></td>
                      <td><span class="test">
                        <input name="date2" class="combo" id="date2" style="width:60px" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no">
                      </span></td>
                      <td><span class="test">
                        <input type="submit" name="search" id="search" value="" class="btn_search">
                      </span></td>
                    </tr>
                  </table>
                </form></td>
              </tr>
              <?php if(isset($_POST['search'])){ ?>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo $totalRows_Recordset2 .' Results'; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td colspan="4">&nbsp;</td>
                </tr>
              <tr class="td-header">
                <td width="100">Date</td>
                <td width="200">Technician</td>
                <td width="100">Alcohol Level</td>
                <td width="400">Remarks</td>
              </tr>
              <?php do { ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                  <td width="100"><?php echo $row_Recordset2['Date']; ?></td>
                  <td width="200"><?php echo $row_Recordset2['Technician']; ?></td>
                  <td width="100"><?php echo $row_Recordset2['AlcoholLevel']; ?></td>
                  <td width="400"><?php echo $row_Recordset2['Remarks']; ?></td>
              </tr>
                  <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
            </table>
          </div>
          <p><br>
            <br>
          </p></td>
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

?>
