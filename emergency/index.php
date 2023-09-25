<?php require_once('../Connections/inv.php'); ?>
<?php
('../Connections/inv.php'); 

session_start();

require_once('../functions/functions.php');

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_inv = new KT_connection($inv, $database_inv);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("area", true, "text", "", "", "", "Required Field");
$formValidation->addField("company", true, "text", "", "", "", "Required Field");
$formValidation->addField("site", true, "text", "", "", "", "Required Field");
$formValidation->addField("description", true, "text", "", "", "", "Required Field");
$formValidation->addField("requestor", true, "text", "", "", "", "Required Field");
$formValidation->addField("telephone", true, "text", "", "", "", "Required Field");
$formValidation->addField("email", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

if(isset($_POST['ref'])){
$ref = $_POST['ref'];
} else {
$ref = $_GET['ref'];
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

$colname_Recordset3 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset3 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_inv, $inv);
$query_Recordset3 = sprintf("SELECT * FROM tbl_sites WHERE AreaId = %s", $colname_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

// Make a custom transaction instance
$customTransaction = new tNG_custom($conn_inv);
$tNGs->addTransaction($customTransaction);
// Register triggers
$customTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "Submit");
$customTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
// Add columns
$customTransaction->addColumn("area", "STRING_TYPE", "POST", "area");
$customTransaction->addColumn("company", "STRING_TYPE", "POST", "select2");
$customTransaction->addColumn("site", "STRING_TYPE", "POST", "select3");
$customTransaction->addColumn("description", "STRING_TYPE", "POST", "desc");
$customTransaction->addColumn("requestor", "STRING_TYPE", "POST", "requestor");
$customTransaction->addColumn("telephone", "STRING_TYPE", "POST", "telephone");
$customTransaction->addColumn("email", "STRING_TYPE", "POST", "email");
// End of custom transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);

select_db();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seavest Africa</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="sdmenu/blue/sdmenu.css" />
	<script type="text/javascript" src="sdmenu/sdmenu.js">
	</script>
	<script type="text/javascript">
<!--
var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.init();
	};

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
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
<script type="text/javascript">
function clickclear(thisfield, defaulttext) {
if (thisfield.value == defaulttext) {
thisfield.value = "";
}
}

function clickrecall(thisfield, defaulttext) {
if (thisfield.value == "") {
thisfield.value = defaulttext;
}
}
</script>
<script type="text/javascript" src="../includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset3 = new WDG_JsRecordset("Recordset3");
echo $jsObject_Recordset3->getOutput();
//end JSRecordset
?>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<style type="text/css">
<!--
.style1 {font-size: 16px}
-->
</style>
</head>

<body>
<div id="bar">Seavest Africa </div>
<table width="880" border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td width="240" align="center" class="td" valign="top">
	<img src="sdmenu/blue/logo.jpg" />
	</td>
    <td width="640" valign="top" class="td"><div id="right">
      <form id="form1" name="form1" method="post" action="sms.php?Id=<?php $_GET['Id']; ?>">
        <span class="form_validation_field_error_error_message style1">Seavest  Reactive Service        </span>
        <br />
        <br />
        To engage reactive response for maintenance, complete all fields and submit:.<br />
        <br />
        <table border="0" cellspacing="3" cellpadding="2">
          <tr>
            <td>Region</td>
            <td class="form_validation_field_error_error_message">*</td>
            <td><select name="area" class="tarea" id="area" onchange="MM_jumpMenu('parent',this,0)">
			  <option value="">Select one...</option>
			  <?php
do {  
?>
			  <option value="index.php?Id=<?php echo $row_Recordset1['Id']?>" <?php if($row_Recordset1['Id'] == $_GET['Id']){ ?>selected="selected" <?php } ?>><?php echo $row_Recordset1['Area']?></option>
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
            <td>Company</td>
            <td class="form_validation_field_error_error_message">*</td>
            <td><select name="select2" class="tarea" id="select2">
			<option value="">Select one..</option>
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
          </tr>
          <tr>
            <td>Site</td>
            <td class="form_validation_field_error_error_message">*</td>
            <td><select name="select3" class="tarea" id="select3" wdg:subtype="DependentDropdown" wdg:type="widget" wdg:recordset="Recordset3" wdg:displayfield="Name" wdg:valuefield="Id" wdg:fkey="Company" wdg:triggerobject="select2">
            </select></td>
          </tr>
          <tr>
            <td valign="top">Description</td>
            <td valign="top" class="form_validation_field_error_error_message">*</td>
            <td><textarea name="desc" rows="5" class="tarea" id="desc" style="width:400px"></textarea></td>
          </tr>
          <tr>
            <td>Requestor</td>
            <td class="form_validation_field_error_error_message">*</td>
            <td><input name="requestor" type="text" class="tarea" id="requestor" style="width:400px" /></td>
          </tr>
          <tr>
            <td>Contact no </td>
            <td class="form_validation_field_error_error_message">*</td>
            <td><input name="telephone" type="text" class="tarea" id="telephone" style="width:400px" /></td>
          </tr>
          <tr>
            <td>Email</td>
            <td class="form_validation_field_error_error_message">*</td>
            <td><input name="email" type="text" class="tarea" id="email" style="width:400px" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="form_validation_field_error_error_message">&nbsp;</td>
            <td align="right"><input type="submit" name="Submit" value="Submit" /></td>
          </tr>
        </table>
        </form>
      </div></td>
  </tr>
</table>
<div id="bar">Seavest Africa</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
