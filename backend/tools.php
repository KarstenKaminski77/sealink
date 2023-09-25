<?php require_once('../Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

//Start log out user
  $logout = new tNG_Logout();
  $logout->setLogoutType("link");
  $logout->setPageRedirect("../index.php");
  $logout->Execute();
//End log out user

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("ManagerId", true, "text", "", "", "", "Required Field");
$formValidation->addField("ToolId", true, "text", "", "", "", "Required Field");
$formValidation->addField("Qty", true, "numeric", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

// Filter
$tfi_listtbl_tool_relation3 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_tool_relation3");
$tfi_listtbl_tool_relation3->addColumn("tbl_technicians.Manager", "STRING_TYPE", "Manager", "%");
$tfi_listtbl_tool_relation3->addColumn("tbl_tools.Tool", "STRING_TYPE", "Tool", "%");
$tfi_listtbl_tool_relation3->addColumn("tbl_tool_relation.Qty", "NUMERIC_TYPE", "Qty", "=");
$tfi_listtbl_tool_relation3->Execute();

// Sorter
$tso_listtbl_tool_relation3 = new TSO_TableSorter("rstbl_tool_relation1", "tso_listtbl_tool_relation3");
$tso_listtbl_tool_relation3->addColumn("tbl_technicians.Manager");
$tso_listtbl_tool_relation3->addColumn("tbl_tools.Tool");
$tso_listtbl_tool_relation3->addColumn("tbl_tool_relation.Qty");
$tso_listtbl_tool_relation3->setDefault("tbl_tool_relation.ManagerId");
$tso_listtbl_tool_relation3->Execute();

// Navigation
$nav_listtbl_tool_relation3 = new NAV_Regular("nav_listtbl_tool_relation3", "rstbl_tool_relation1", "../", $_SERVER['PHP_SELF'], 50);

// Filter
$tfi_listtbl_tool_relation4 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_tool_relation4");
$tfi_listtbl_tool_relation4->addColumn("tbl_areas.Area", "STRING_TYPE", "Area", "%");
$tfi_listtbl_tool_relation4->addColumn("tbl_technicians.Name", "STRING_TYPE", "Name", "%");
$tfi_listtbl_tool_relation4->addColumn("tbl_tools.Tool", "STRING_TYPE", "Tool", "%");
$tfi_listtbl_tool_relation4->addColumn("tbl_tool_relation.Qty", "NUMERIC_TYPE", "Qty", "=");
$tfi_listtbl_tool_relation4->addColumn("tbl_tool_relation.CurrentQty", "NUMERIC_TYPE", "CurrentQty", "=");
$tfi_listtbl_tool_relation4->addColumn("tbl_tool_relation.Date", "STRING_TYPE", "Date", "%");
$tfi_listtbl_tool_relation4->Execute();

// Sorter
$tso_listtbl_tool_relation4 = new TSO_TableSorter("rstbl_tool_relation2", "tso_listtbl_tool_relation4");
$tso_listtbl_tool_relation4->addColumn("tbl_areas.Area");
$tso_listtbl_tool_relation4->addColumn("tbl_technicians.Name");
$tso_listtbl_tool_relation4->addColumn("tbl_tools.Tool");
$tso_listtbl_tool_relation4->addColumn("tbl_tool_relation.Qty");
$tso_listtbl_tool_relation4->addColumn("tbl_tool_relation.CurrentQty");
$tso_listtbl_tool_relation4->addColumn("tbl_tool_relation.Date");
$tso_listtbl_tool_relation4->setDefault("tbl_tool_relation.AreaId");
$tso_listtbl_tool_relation4->Execute();

// Navigation
$nav_listtbl_tool_relation4 = new NAV_Regular("nav_listtbl_tool_relation4", "rstbl_tool_relation2", "../", $_SERVER['PHP_SELF'], 50);

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_technicians WHERE Manager != 1";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_tools";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT Area, Id FROM tbl_areas ORDER BY Area";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT Name, Id FROM tbl_technicians ORDER BY Name";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT Tool, Id FROM tbl_tools ORDER BY Tool";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

mysql_select_db($database_seavest, $seavest);
$query_Recordset6 = "SELECT * FROM tbl_areas";
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_tool_relation2 = $_SESSION['max_rows_nav_listtbl_tool_relation4'];
$pageNum_rstbl_tool_relation2 = 0;
if (isset($_GET['pageNum_rstbl_tool_relation2'])) {
  $pageNum_rstbl_tool_relation2 = $_GET['pageNum_rstbl_tool_relation2'];
}
$startRow_rstbl_tool_relation2 = $pageNum_rstbl_tool_relation2 * $maxRows_rstbl_tool_relation2;

$NXTFilter_rstbl_tool_relation2 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_tool_relation4'])) {
  $NXTFilter_rstbl_tool_relation2 = $_SESSION['filter_tfi_listtbl_tool_relation4'];
}
$NXTSort_rstbl_tool_relation2 = "tbl_tool_relation.AreaId";
if (isset($_SESSION['sorter_tso_listtbl_tool_relation4'])) {
  $NXTSort_rstbl_tool_relation2 = $_SESSION['sorter_tso_listtbl_tool_relation4'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_tool_relation2 = sprintf("SELECT tbl_areas.Area, tbl_technicians.Name, tbl_tools.Tool, tbl_tool_relation.Qty, tbl_tool_relation.CurrentQty, tbl_tool_relation.Date, tbl_tool_relation.Id FROM ((tbl_tool_relation LEFT JOIN tbl_areas ON tbl_tool_relation.AreaId = tbl_areas.Id) LEFT JOIN tbl_technicians ON tbl_tool_relation.ManagerId = tbl_technicians.Id) LEFT JOIN tbl_tools ON tbl_tool_relation.ToolId = tbl_tools.Id WHERE %s ORDER BY %s", $NXTFilter_rstbl_tool_relation2, $NXTSort_rstbl_tool_relation2);
$query_limit_rstbl_tool_relation2 = sprintf("%s LIMIT %d, %d", $query_rstbl_tool_relation2, $startRow_rstbl_tool_relation2, $maxRows_rstbl_tool_relation2);
$rstbl_tool_relation2 = mysql_query($query_limit_rstbl_tool_relation2, $seavest) or die(mysql_error());
$row_rstbl_tool_relation2 = mysql_fetch_assoc($rstbl_tool_relation2);

if (isset($_GET['totalRows_rstbl_tool_relation2'])) {
  $totalRows_rstbl_tool_relation2 = $_GET['totalRows_rstbl_tool_relation2'];
} else {
  $all_rstbl_tool_relation2 = mysql_query($query_rstbl_tool_relation2);
  $totalRows_rstbl_tool_relation2 = mysql_num_rows($all_rstbl_tool_relation2);
}
$totalPages_rstbl_tool_relation2 = ceil($totalRows_rstbl_tool_relation2/$maxRows_rstbl_tool_relation2)-1;
//End NeXTenesio3 Special List Recordset

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_tool_relation1 = $_SESSION['max_rows_nav_listtbl_tool_relation3'];
$pageNum_rstbl_tool_relation1 = 0;
if (isset($_GET['pageNum_rstbl_tool_relation1'])) {
  $pageNum_rstbl_tool_relation1 = $_GET['pageNum_rstbl_tool_relation1'];
}
$startRow_rstbl_tool_relation1 = $pageNum_rstbl_tool_relation1 * $maxRows_rstbl_tool_relation1;

$NXTFilter_rstbl_tool_relation1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_tool_relation3'])) {
  $NXTFilter_rstbl_tool_relation1 = $_SESSION['filter_tfi_listtbl_tool_relation3'];
}
$NXTSort_rstbl_tool_relation1 = "tbl_tool_relation.ManagerId";
if (isset($_SESSION['sorter_tso_listtbl_tool_relation3'])) {
  $NXTSort_rstbl_tool_relation1 = $_SESSION['sorter_tso_listtbl_tool_relation3'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_tool_relation1 = sprintf("SELECT tbl_technicians.Manager, tbl_tools.Tool, tbl_tool_relation.Qty, tbl_tool_relation.Id FROM (tbl_tool_relation LEFT JOIN tbl_technicians ON tbl_tool_relation.ManagerId = tbl_technicians.Id) LEFT JOIN tbl_tools ON tbl_tool_relation.ToolId = tbl_tools.Id WHERE %s ORDER BY %s", $NXTFilter_rstbl_tool_relation1, $NXTSort_rstbl_tool_relation1);
$query_limit_rstbl_tool_relation1 = sprintf("%s LIMIT %d, %d", $query_rstbl_tool_relation1, $startRow_rstbl_tool_relation1, $maxRows_rstbl_tool_relation1);
$rstbl_tool_relation1 = mysql_query($query_limit_rstbl_tool_relation1, $seavest) or die(mysql_error());
$row_rstbl_tool_relation1 = mysql_fetch_assoc($rstbl_tool_relation1);

if (isset($_GET['totalRows_rstbl_tool_relation1'])) {
  $totalRows_rstbl_tool_relation1 = $_GET['totalRows_rstbl_tool_relation1'];
} else {
  $all_rstbl_tool_relation1 = mysql_query($query_rstbl_tool_relation1);
  $totalRows_rstbl_tool_relation1 = mysql_num_rows($all_rstbl_tool_relation1);
}
$totalPages_rstbl_tool_relation1 = ceil($totalRows_rstbl_tool_relation1/$maxRows_rstbl_tool_relation1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_tool_relation = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_tool_relation);
// Register triggers
$ins_tbl_tool_relation->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_tool_relation->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_tool_relation->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_tool_relation->setTable("tbl_tool_relation");
$ins_tbl_tool_relation->addColumn("AreaId", "STRING_TYPE", "POST", "AreaId");
$ins_tbl_tool_relation->addColumn("ManagerId", "STRING_TYPE", "POST", "ManagerId");
$ins_tbl_tool_relation->addColumn("ToolId", "STRING_TYPE", "POST", "ToolId");
$ins_tbl_tool_relation->addColumn("Qty", "NUMERIC_TYPE", "POST", "Qty");
$ins_tbl_tool_relation->addColumn("CurrentQty", "NUMERIC_TYPE", "POST", "CurrentQty");
$ins_tbl_tool_relation->addColumn("Date", "STRING_TYPE", "POST", "Date");
$ins_tbl_tool_relation->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_tool_relation = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_tool_relation);
// Register triggers
$upd_tbl_tool_relation->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_tool_relation->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_tool_relation->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_tool_relation->setTable("tbl_tool_relation");
$upd_tbl_tool_relation->addColumn("AreaId", "STRING_TYPE", "POST", "AreaId");
$upd_tbl_tool_relation->addColumn("ManagerId", "STRING_TYPE", "POST", "ManagerId");
$upd_tbl_tool_relation->addColumn("ToolId", "STRING_TYPE", "POST", "ToolId");
$upd_tbl_tool_relation->addColumn("Qty", "NUMERIC_TYPE", "POST", "Qty");
$upd_tbl_tool_relation->addColumn("CurrentQty", "NUMERIC_TYPE", "POST", "CurrentQty");
$upd_tbl_tool_relation->addColumn("Date", "STRING_TYPE", "POST", "Date");
$upd_tbl_tool_relation->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_tool_relation = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_tool_relation);
// Register triggers
$del_tbl_tool_relation->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_tool_relation->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_tool_relation->setTable("tbl_tool_relation");
$del_tbl_tool_relation->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_tool_relation = $tNGs->getRecordset("tbl_tool_relation");
$row_rstbl_tool_relation = mysql_fetch_assoc($rstbl_tool_relation);
$totalRows_rstbl_tool_relation = mysql_num_rows($rstbl_tool_relation);

$nav_listtbl_tool_relation3->checkBoundries();

$nav_listtbl_tool_relation4->checkBoundries();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seavest Administration</title>
<style type="text/css">
<!--
a.top {
	font-family: Arial;
	text-decoration: none;
	font-size: 11px;
	color: #FF0000;
	font-weight: bold;
	line-height:20px;
}
a:link.top {
	text-decoration: none;
	line-height:20px;
}
a:visited.top {
	text-decoration: none;
	line-height:20px;
	color: #FF0000;
}
a:hover.top {
	text-decoration: none;
	line-height:20px;
	color: #B00000;
}
a:active.top {
	text-decoration: none;
	line-height:20px;
	color: #FF0000;
}
-->
</style>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: true,
  merge_down_value: true
}
</script>
<script src="../includes/nxt/scripts/list.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: false,
  duplicate_navigation: false,
  row_effects: true,
  show_as_buttons: true,
  record_counter: false
}
</script>
<style type="text/css">
  /* NeXTensio3 List row settings */
  .KT_col_Manager {width:140px; overflow:hidden;}
  .KT_col_Tool {width:140px; overflow:hidden;}
  .KT_col_Qty {width:140px; overflow:hidden;}
</style>
<style type="text/css">
  /* NeXTensio3 List row settings */
  .KT_col_Area {width:140px; overflow:hidden;}
  .KT_col_Name {width:140px; overflow:hidden;}
  .KT_col_Tool {width:140px; overflow:hidden;}
  .KT_col_Qty {width:140px; overflow:hidden;}
  .KT_col_CurrentQty {width:140px; overflow:hidden;}
  .KT_col_Date {width:140px; overflow:hidden;}
</style>
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../includes/resources/calendar.js"></script>
</head>

<body>
<p><a href="index.php" class="top">Main Menu</a><br />
    <a href="logout.php" class="top">Logout</a></p>
<p>&nbsp;
  <?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['Id'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Tool</h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_tool_relation > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="AreaId_<?php echo $cnt1; ?>">Area:</label></td>
            <td><select name="AreaId_<?php echo $cnt1; ?>" id="AreaId_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_Recordset6['Id']?>"<?php if (!(strcmp($row_Recordset6['Id'], $row_rstbl_tool_relation['AreaId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset6['Area']?></option>
                <?php
} while ($row_Recordset6 = mysql_fetch_assoc($Recordset6));
  $rows = mysql_num_rows($Recordset6);
  if($rows > 0) {
      mysql_data_seek($Recordset6, 0);
	  $row_Recordset6 = mysql_fetch_assoc($Recordset6);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("tbl_tool_relation", "AreaId", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="ManagerId_<?php echo $cnt1; ?>">Manager:</label></td>
            <td><select name="ManagerId_<?php echo $cnt1; ?>" id="ManagerId_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_Recordset1['Id']?>"<?php if (!(strcmp($row_Recordset1['Id'], $row_rstbl_tool_relation['ManagerId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['Name']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("tbl_tool_relation", "ManagerId", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="ToolId_<?php echo $cnt1; ?>">Tool:</label></td>
            <td><select name="ToolId_<?php echo $cnt1; ?>" id="ToolId_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_Recordset2['Id']?>"<?php if (!(strcmp($row_Recordset2['Id'], $row_rstbl_tool_relation['ToolId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['Tool']?></option>
                <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("tbl_tool_relation", "ToolId", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Qty_<?php echo $cnt1; ?>">Qty:</label></td>
            <td><input type="text" name="Qty_<?php echo $cnt1; ?>" id="Qty_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_tool_relation['Qty']); ?>" size="7" />
                <?php echo $tNGs->displayFieldHint("Qty");?> <?php echo $tNGs->displayFieldError("tbl_tool_relation", "Qty", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="CurrentQty_<?php echo $cnt1; ?>">Current Qty:</label></td>
            <td><input type="text" name="CurrentQty_<?php echo $cnt1; ?>" id="CurrentQty_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_tool_relation['CurrentQty']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("CurrentQty");?> <?php echo $tNGs->displayFieldError("tbl_tool_relation", "CurrentQty", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Date_<?php echo $cnt1; ?>">Date:</label></td>
            <td><input name="Date_<?php echo $cnt1; ?>" id="Date_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_tool_relation['Date']); ?>" size="32" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:mondayfirst="true" wdg:singleclick="true" wdg:restricttomask="yes" />
                <?php echo $tNGs->displayFieldHint("Date");?> <?php echo $tNGs->displayFieldError("tbl_tool_relation", "Date", $cnt1); ?> </td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_tbl_tool_relation_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_tool_relation['kt_pk_tbl_tool_relation']); ?>" />
        <?php } while ($row_rstbl_tool_relation = mysql_fetch_assoc($rstbl_tool_relation)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['Id'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <div class="KT_operations">
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'Id')" />
            </div>
            <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
            <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
            <?php }
      // endif Conditional region1
      ?>
          <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
        </div>
      </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>
<div class="KT_tng" id="listtbl_tool_relation4">
  <h1> Tools
    <?php
  $nav_listtbl_tool_relation4->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
      <div class="KT_options"> <a href="<?php echo $nav_listtbl_tool_relation4->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_tool_relation4'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listtbl_tool_relation4']; ?>
          <?php 
  // else Conditional region2
  } else { ?>
          <?php echo NXT_getResource("all"); ?>
          <?php } 
  // endif Conditional region2
?>
            <?php echo NXT_getResource("records"); ?></a> &nbsp;
        &nbsp;
                            <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_listtbl_tool_relation4'] == 1) {
?>
                              <a href="<?php echo $tfi_listtbl_tool_relation4->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                              <?php 
  // else Conditional region2
  } else { ?>
                              <a href="<?php echo $tfi_listtbl_tool_relation4->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                              <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="Area" class="KT_sorter KT_col_Area <?php echo $tso_listtbl_tool_relation4->getSortIcon('tbl_areas.Area'); ?>"> <a href="<?php echo $tso_listtbl_tool_relation4->getSortLink('tbl_areas.Area'); ?>">AreaId</a> </th>
            <th id="Name" class="KT_sorter KT_col_Name <?php echo $tso_listtbl_tool_relation4->getSortIcon('tbl_technicians.Name'); ?>"> <a href="<?php echo $tso_listtbl_tool_relation4->getSortLink('tbl_technicians.Name'); ?>">Technician</a> </th>
            <th id="Tool" class="KT_sorter KT_col_Tool <?php echo $tso_listtbl_tool_relation4->getSortIcon('tbl_tools.Tool'); ?>"> <a href="<?php echo $tso_listtbl_tool_relation4->getSortLink('tbl_tools.Tool'); ?>">Tool</a> </th>
            <th id="Qty" class="KT_sorter KT_col_Qty <?php echo $tso_listtbl_tool_relation4->getSortIcon('tbl_tool_relation.Qty'); ?>"> <a href="<?php echo $tso_listtbl_tool_relation4->getSortLink('tbl_tool_relation.Qty'); ?>">Qty</a> </th>
            <th id="CurrentQty" class="KT_sorter KT_col_CurrentQty <?php echo $tso_listtbl_tool_relation4->getSortIcon('tbl_tool_relation.CurrentQty'); ?>"> <a href="<?php echo $tso_listtbl_tool_relation4->getSortLink('tbl_tool_relation.CurrentQty'); ?>">Current Qty</a> </th>
            <th id="Date" class="KT_sorter KT_col_Date <?php echo $tso_listtbl_tool_relation4->getSortIcon('tbl_tool_relation.Date'); ?>"> <a href="<?php echo $tso_listtbl_tool_relation4->getSortLink('tbl_tool_relation.Date'); ?>">Date</a> </th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_tool_relation4'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listtbl_tool_relation4_Area" id="tfi_listtbl_tool_relation4_Area" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_tool_relation4_Area']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listtbl_tool_relation4_Name" id="tfi_listtbl_tool_relation4_Name" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_tool_relation4_Name']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listtbl_tool_relation4_Tool" id="tfi_listtbl_tool_relation4_Tool" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_tool_relation4_Tool']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listtbl_tool_relation4_Qty" id="tfi_listtbl_tool_relation4_Qty" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_tool_relation4_Qty']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listtbl_tool_relation4_CurrentQty" id="tfi_listtbl_tool_relation4_CurrentQty" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_tool_relation4_CurrentQty']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listtbl_tool_relation4_Date" id="tfi_listtbl_tool_relation4_Date" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_tool_relation4_Date']); ?>" size="20" maxlength="255" /></td>
              <td><input type="submit" name="tfi_listtbl_tool_relation4" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rstbl_tool_relation2 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="8"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rstbl_tool_relation2 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_tbl_tool_relation" class="id_checkbox" value="<?php echo $row_rstbl_tool_relation2['Id']; ?>" />
                    <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_tool_relation2['Id']; ?>" />
                </td>
                <td><div class="KT_col_Area"><?php echo KT_FormatForList($row_rstbl_tool_relation2['Area'], 20); ?></div></td>
                <td><div class="KT_col_Name"><?php echo KT_FormatForList($row_rstbl_tool_relation2['Name'], 20); ?></div></td>
                <td><div class="KT_col_Tool"><?php echo KT_FormatForList($row_rstbl_tool_relation2['Tool'], 20); ?></div></td>
                <td><div class="KT_col_Qty"><?php echo KT_FormatForList($row_rstbl_tool_relation2['Qty'], 20); ?></div></td>
                <td><div class="KT_col_CurrentQty"><?php echo KT_FormatForList($row_rstbl_tool_relation2['CurrentQty'], 20); ?></div></td>
                <td><div class="KT_col_Date"><?php echo KT_FormatForList($row_rstbl_tool_relation2['Date'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="tools.php?Id=<?php echo $row_rstbl_tool_relation2['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
              </tr>
              <?php } while ($row_rstbl_tool_relation2 = mysql_fetch_assoc($rstbl_tool_relation2)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listtbl_tool_relation4->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
<span>&nbsp;</span>
        <select name="no_new" id="no_new">
          <option value="1">1</option>
          <option value="3">3</option>
          <option value="6">6</option>
        </select>
        <a class="KT_additem_op_link" href="tools.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</p>
</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset6);

mysql_free_result($rstbl_tool_relation2);

mysql_free_result($rstbl_tool_relation1);
?>
