<?php require_once('../Connections/seavest.php'); ?>
<?php
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

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("Cell", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

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

// Filter
$tfi_listtbl_sms_group1 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_sms_group1");
$tfi_listtbl_sms_group1->addColumn("tbl_areas.Id", "STRING_TYPE", "AreaId", "%");
$tfi_listtbl_sms_group1->addColumn("tbl_sms_group.Name", "STRING_TYPE", "Name", "%");
$tfi_listtbl_sms_group1->addColumn("tbl_sms_group.Cell", "STRING_TYPE", "Cell", "%");
$tfi_listtbl_sms_group1->Execute();

// Sorter
$tso_listtbl_sms_group1 = new TSO_TableSorter("rstbl_sms_group1", "tso_listtbl_sms_group1");
$tso_listtbl_sms_group1->addColumn("tbl_areas.Area");
$tso_listtbl_sms_group1->addColumn("tbl_sms_group.Name");
$tso_listtbl_sms_group1->addColumn("tbl_sms_group.Cell");
$tso_listtbl_sms_group1->setDefault("tbl_sms_group.AreaId");
$tso_listtbl_sms_group1->Execute();

// Navigation
$nav_listtbl_sms_group1 = new NAV_Regular("nav_listtbl_sms_group1", "rstbl_sms_group1", "../", $_SERVER['PHP_SELF'], 50);

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

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT * FROM tbl_areas";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT Area, Id FROM tbl_areas ORDER BY Area";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_sms_group1 = $_SESSION['max_rows_nav_listtbl_sms_group1'];
$pageNum_rstbl_sms_group1 = 0;
if (isset($_GET['pageNum_rstbl_sms_group1'])) {
  $pageNum_rstbl_sms_group1 = $_GET['pageNum_rstbl_sms_group1'];
}
$startRow_rstbl_sms_group1 = $pageNum_rstbl_sms_group1 * $maxRows_rstbl_sms_group1;

// Defining List Recordset variable
$NXTFilter_rstbl_sms_group1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_sms_group1'])) {
  $NXTFilter_rstbl_sms_group1 = $_SESSION['filter_tfi_listtbl_sms_group1'];
}
// Defining List Recordset variable
$NXTSort_rstbl_sms_group1 = "tbl_sms_group.AreaId";
if (isset($_SESSION['sorter_tso_listtbl_sms_group1'])) {
  $NXTSort_rstbl_sms_group1 = $_SESSION['sorter_tso_listtbl_sms_group1'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_sms_group1 = "SELECT tbl_areas.Area AS AreaId, tbl_sms_group.Name, tbl_sms_group.Cell, tbl_sms_group.Id FROM tbl_sms_group LEFT JOIN tbl_areas ON tbl_sms_group.AreaId = tbl_areas.Id WHERE {$NXTFilter_rstbl_sms_group1} ORDER BY {$NXTSort_rstbl_sms_group1}";
$query_limit_rstbl_sms_group1 = sprintf("%s LIMIT %d, %d", $query_rstbl_sms_group1, $startRow_rstbl_sms_group1, $maxRows_rstbl_sms_group1);
$rstbl_sms_group1 = mysql_query($query_limit_rstbl_sms_group1, $seavest) or die(mysql_error());
$row_rstbl_sms_group1 = mysql_fetch_assoc($rstbl_sms_group1);

if (isset($_GET['totalRows_rstbl_sms_group1'])) {
  $totalRows_rstbl_sms_group1 = $_GET['totalRows_rstbl_sms_group1'];
} else {
  $all_rstbl_sms_group1 = mysql_query($query_rstbl_sms_group1);
  $totalRows_rstbl_sms_group1 = mysql_num_rows($all_rstbl_sms_group1);
}
$totalPages_rstbl_sms_group1 = ceil($totalRows_rstbl_sms_group1/$maxRows_rstbl_sms_group1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_sms_group = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_sms_group);
// Register triggers
$ins_tbl_sms_group->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_sms_group->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_sms_group->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_sms_group->setTable("tbl_sms_group");
$ins_tbl_sms_group->addColumn("Name", "STRING_TYPE", "POST", "Name");
$ins_tbl_sms_group->addColumn("Cell", "STRING_TYPE", "POST", "Cell");
$ins_tbl_sms_group->addColumn("AreaId", "NUMERIC_TYPE", "POST", "AreaId");
$ins_tbl_sms_group->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_sms_group = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_sms_group);
// Register triggers
$upd_tbl_sms_group->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_sms_group->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_sms_group->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_sms_group->setTable("tbl_sms_group");
$upd_tbl_sms_group->addColumn("Name", "STRING_TYPE", "POST", "Name");
$upd_tbl_sms_group->addColumn("Cell", "STRING_TYPE", "POST", "Cell");
$upd_tbl_sms_group->addColumn("AreaId", "NUMERIC_TYPE", "POST", "AreaId");
$upd_tbl_sms_group->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_sms_group = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_sms_group);
// Register triggers
$del_tbl_sms_group->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_sms_group->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_sms_group->setTable("tbl_sms_group");
$del_tbl_sms_group->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_sms_group = $tNGs->getRecordset("tbl_sms_group");
$row_rstbl_sms_group = mysql_fetch_assoc($rstbl_sms_group);
$totalRows_rstbl_sms_group = mysql_num_rows($rstbl_sms_group);

$nav_listtbl_sms_group1->checkBoundries();
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
  /* Dynamic List row settings */
  .KT_col_AreaId {width:140px; overflow:hidden;}
  .KT_col_Name {width:140px; overflow:hidden;}
  .KT_col_Cell {width:140px; overflow:hidden;}
</style>
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
          <br>
          <?php
	echo $tNGs->getErrorMsg();
?>
          <div class="KT_tng" style="padding-left:30px">
            <div class="KT_tngform">
              <form method="post" id="form2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_sms_group > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="Name_<?php echo $cnt1; ?>">Name:</label></td>
                      <td><input type="text" name="Name_<?php echo $cnt1; ?>" id="Name_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sms_group['Name']); ?>" size="40" />
                        <?php echo $tNGs->displayFieldHint("Name");?> <?php echo $tNGs->displayFieldError("tbl_sms_group", "Name", $cnt1); ?></td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Cell_<?php echo $cnt1; ?>">Cell:</label></td>
                      <td><input type="text" name="Cell_<?php echo $cnt1; ?>" id="Cell_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sms_group['Cell']); ?>" size="40" maxlength="20" />
                        <?php echo $tNGs->displayFieldHint("Cell");?> <?php echo $tNGs->displayFieldError("tbl_sms_group", "Cell", $cnt1); ?></td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="AreaId_<?php echo $cnt1; ?>">Area:</label></td>
                      <td><select name="AreaId_<?php echo $cnt1; ?>" id="AreaId_<?php echo $cnt1; ?>">
                        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        <?php 
do {  
?>
                        <option value="<?php echo $row_Recordset3['Id']?>"<?php if (!(strcmp($row_Recordset3['Id'], $row_rstbl_sms_group['AreaId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['Area']?></option>
                        <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                      </select>
                        <?php echo $tNGs->displayFieldError("tbl_sms_group", "AreaId", $cnt1); ?></td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_tbl_sms_group_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_sms_group['kt_pk_tbl_sms_group']); ?>" />
                  <?php } while ($row_rstbl_sms_group = mysql_fetch_assoc($rstbl_sms_group)); ?>
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
                        <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onClick="nxt_form_insertasnew(this, 'Id')" />
                      </div>
                      <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onClick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onClick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
                  </div>
                </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          <p>&nbsp;
          <div class="KT_tng" id="listtbl_sms_group1" style="padding-left:30px">
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form3">
                <div class="KT_options"> <a href="<?php echo $nav_listtbl_sms_group1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_sms_group1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listtbl_sms_group1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_sms_group1'] == 1) {
?>
                    <a href="<?php echo $tfi_listtbl_sms_group1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                    <?php 
  // else Conditional region2
  } else { ?>
                    <a href="<?php echo $tfi_listtbl_sms_group1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                    <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="AreaId" class="KT_sorter KT_col_AreaId <?php echo $tso_listtbl_sms_group1->getSortIcon('tbl_areas.Area'); ?>"> <a href="<?php echo $tso_listtbl_sms_group1->getSortLink('tbl_areas.Area'); ?>">Area</a> </th>
                      <th id="Name" class="KT_sorter KT_col_Name <?php echo $tso_listtbl_sms_group1->getSortIcon('tbl_sms_group.Name'); ?>"> <a href="<?php echo $tso_listtbl_sms_group1->getSortLink('tbl_sms_group.Name'); ?>">Name</a> </th>
                      <th id="Cell" class="KT_sorter KT_col_Cell <?php echo $tso_listtbl_sms_group1->getSortIcon('tbl_sms_group.Cell'); ?>"> <a href="<?php echo $tso_listtbl_sms_group1->getSortLink('tbl_sms_group.Cell'); ?>">Cell</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_sms_group1'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><select name="tfi_listtbl_sms_group1_AreaId" id="tfi_listtbl_sms_group1_AreaId">
                          <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listtbl_sms_group1_AreaId']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset4['Id']?>"<?php if (!(strcmp($row_Recordset4['Id'], @$_SESSION['tfi_listtbl_sms_group1_AreaId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['Area']?></option>
                          <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
                        </select></td>
                        <td><input type="text" name="tfi_listtbl_sms_group1_Name" id="tfi_listtbl_sms_group1_Name" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_sms_group1_Name']); ?>" size="20" maxlength="100" /></td>
                        <td><input type="text" name="tfi_listtbl_sms_group1_Cell" id="tfi_listtbl_sms_group1_Cell" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_sms_group1_Cell']); ?>" size="20" maxlength="20" /></td>
                        <td><input type="submit" name="tfi_listtbl_sms_group1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rstbl_sms_group1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rstbl_sms_group1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_tbl_sms_group" class="id_checkbox" value="<?php echo $row_rstbl_sms_group1['Id']; ?>" />
                            <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_sms_group1['Id']; ?>" /></td>
                          <td><div class="KT_col_AreaId"><?php echo KT_FormatForList($row_rstbl_sms_group1['AreaId'], 20); ?></div></td>
                          <td><div class="KT_col_Name"><?php echo KT_FormatForList($row_rstbl_sms_group1['Name'], 20); ?></div></td>
                          <td><div class="KT_col_Cell"><?php echo KT_FormatForList($row_rstbl_sms_group1['Cell'], 20); ?></div></td>
                          <td><a class="KT_edit_link" href="contacts.php?Id=<?php echo $row_rstbl_sms_group1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
                        </tr>
                        <?php } while ($row_rstbl_sms_group1 = mysql_fetch_assoc($rstbl_sms_group1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listtbl_sms_group1->Prepare();
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
                  <a class="KT_additem_op_link" href="contacts.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          <p>&nbsp;</p>
          </p>
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

mysql_free_result($Recordset4);

mysql_free_result($rstbl_sms_group1);
?>
