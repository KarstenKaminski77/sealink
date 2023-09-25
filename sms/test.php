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

if(isset($_POST['send'])){

select_db();

if(isset($_POST['contact'])){

$rows = count($_POST['contact']);
	
	$contact = $_POST['contact'];
	$sms = $_POST['message'];
	
} else {

$rows = count($_POST['cell']);
	
	$contact = $_POST['cell'];
	$sms = $_POST['message'];

}

	for($i=0;$i<$rows;$i++){

//   $to  = 'sms@messaging.clickatell.com'; 
//   $subject = 'Seavest';
//   $from = "control@seavest.co.za";
//   $cell = $contact[$i];
//
//$message = '
//user:seavest
//password:abc123
//api_id:3232946
//to:'. $cell .'
//reply: control@seavest.co.za
//concat: 3
//text: '. $sms;
//
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
//
//$headers .= 'FROM: '. $from . "\r\n";
//
//mail($to, $subject, $message, $headers);

	}}

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
$query_Recordset3 = "SELECT * FROM tbl_sms_group ORDER BY Name ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
	
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
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
.new-supplier, a.new-supplier:link, a.new-supplier:visited, a.new-supplier:active {
	background-image: url(../order-forms/images/add-new.png);
	display: block;
	padding: 0px;
	height: 15px;
	width: 15px;
	text-decoration:none;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 5px;
	border:none;
}
.new-supplier:hover {
	background-image: url(../order-forms/images/add-new.png);
	text-decoration:none;
	background-position: center -15px;
}
-->
</style>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<script type="text/javascript" src="../SpryAssets/SpryURLUtils.js"></script>
<script type="text/javascript">
var params = Spry.Utils.getLocationParamsAsObject();
</script>
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
<form action="" method="post" style="padding-left:30px">
<div id="CollapsiblePanel1" class="CollapsiblePanel">
  <div class="CollapsiblePanelTab" tabindex="0">&nbsp;Compose</div>
  <div class="CollapsiblePanelContent">
    <div id="comb-sms" class="sms-spacer">
      <textarea name="message" cols="50" rows="5" class="ta-sms" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" >Message</textarea>
    </div>
  </div>
</div>

<div id="CollapsiblePanel2" class="CollapsiblePanel">
  <div class="CollapsiblePanelTab" tabindex="0">&nbsp;Contacts</div>
  <div class="CollapsiblePanelContent">
                      <div id="comb-sms" class="sms-spacer">
                        <table border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <?php
  do { // horizontal looper version 3
?>
                              <td><table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td><input type="checkbox" name="contact[]" id="<?php echo $row_Recordset3['Id']; ?>" value="<?php echo $row_Recordset3['Cell']; ?>"></td>
                                  <td><div class="comb-sms" style="padding-right:20px"><label for="<?php echo $row_Recordset3['Id']; ?>"><?php echo $row_Recordset3['Name']; ?></label></div></td>
                                </tr>
                                </table></td>
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
                      </div>
  </div>
</div>

<div id="CollapsiblePanel3" class="CollapsiblePanel">
  <div class="CollapsiblePanelTab" tabindex="0">&nbsp;Keypad</div>
  <div class="CollapsiblePanelContent">
                      <div id="comb-sms" class="comb-sms">
                      <table width="100%">
                      <tr>
                      <td width="238" align="right" class="comb-sms">Add Number</td>
                      <td width="20" align="right"><input name="add" type="submit" class="new-supplier" id="add" value=""></td>
                      </tr>
                      <?php 
					  if(isset($_POST['cell']) && isset($_POST['add'])){
	
	                  $rows = count($_POST['cell']) + 1;
					  
					  } elseif(isset($_POST['cell']) && !isset($_POST['add'])){
						  
						  $rows = count($_POST['cell']);
						  
						  } else {
							  
							  $rows = 1;
							  
							  }

					  $cell = $_POST['cell'];
					  
					  for($i=0;$i<$rows;$i++){ 
					  
					  $cell_no = $cell[$i];
					  
					  ?>
                      <tr>
                      <td colspan="2"><input name="cell[]" type="text" class="td-sms" id="cell[]" size="50" value="<?php echo $cell_no; ?>"></td>
                      </tr>
                      <?php } ?>
                      </table>
                      </div>
  </div>
</div>
</form>          
</td>
      </tr>
    </table></td>
  </tr>
</table>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen: params.col1 ? true : false});
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen: params.col2 ? true : false});
var CollapsiblePanel3 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel3", {contentIsOpen: params.col3 ? true : false});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
