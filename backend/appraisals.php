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
$formValidation->addField("Appraisal", true, "text", "", "", "", "Required Field");
$formValidation->addField("Date", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

// Filter
$tfi_listtbl_appraisals1 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_appraisals1");
$tfi_listtbl_appraisals1->addColumn("tbl_appraisals.Appraisal", "STRING_TYPE", "Appraisal", "%");
$tfi_listtbl_appraisals1->addColumn("tbl_appraisals.Date", "STRING_TYPE", "Date", "%");
$tfi_listtbl_appraisals1->Execute();

// Sorter
$tso_listtbl_appraisals1 = new TSO_TableSorter("rstbl_appraisals1", "tso_listtbl_appraisals1");
$tso_listtbl_appraisals1->addColumn("tbl_appraisals.Appraisal");
$tso_listtbl_appraisals1->addColumn("tbl_appraisals.Date");
$tso_listtbl_appraisals1->setDefault("tbl_appraisals.Appraisal");
$tso_listtbl_appraisals1->Execute();

// Navigation
$nav_listtbl_appraisals1 = new NAV_Regular("nav_listtbl_appraisals1", "rstbl_appraisals1", "../", $_SERVER['PHP_SELF'], 50);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_appraisals1 = $_SESSION['max_rows_nav_listtbl_appraisals1'];
$pageNum_rstbl_appraisals1 = 0;
if (isset($_GET['pageNum_rstbl_appraisals1'])) {
  $pageNum_rstbl_appraisals1 = $_GET['pageNum_rstbl_appraisals1'];
}
$startRow_rstbl_appraisals1 = $pageNum_rstbl_appraisals1 * $maxRows_rstbl_appraisals1;

$NXTFilter_rstbl_appraisals1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_appraisals1'])) {
  $NXTFilter_rstbl_appraisals1 = $_SESSION['filter_tfi_listtbl_appraisals1'];
}
$NXTSort_rstbl_appraisals1 = "tbl_appraisals.Appraisal";
if (isset($_SESSION['sorter_tso_listtbl_appraisals1'])) {
  $NXTSort_rstbl_appraisals1 = $_SESSION['sorter_tso_listtbl_appraisals1'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_appraisals1 = sprintf("SELECT tbl_appraisals.Appraisal, tbl_appraisals.Date, tbl_appraisals.Id FROM tbl_appraisals WHERE %s ORDER BY %s", $NXTFilter_rstbl_appraisals1, $NXTSort_rstbl_appraisals1);
$query_limit_rstbl_appraisals1 = sprintf("%s LIMIT %d, %d", $query_rstbl_appraisals1, $startRow_rstbl_appraisals1, $maxRows_rstbl_appraisals1);
$rstbl_appraisals1 = mysql_query($query_limit_rstbl_appraisals1, $seavest) or die(mysql_error());
$row_rstbl_appraisals1 = mysql_fetch_assoc($rstbl_appraisals1);

if (isset($_GET['totalRows_rstbl_appraisals1'])) {
  $totalRows_rstbl_appraisals1 = $_GET['totalRows_rstbl_appraisals1'];
} else {
  $all_rstbl_appraisals1 = mysql_query($query_rstbl_appraisals1);
  $totalRows_rstbl_appraisals1 = mysql_num_rows($all_rstbl_appraisals1);
}
$totalPages_rstbl_appraisals1 = ceil($totalRows_rstbl_appraisals1/$maxRows_rstbl_appraisals1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_appraisals = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_appraisals);
// Register triggers
$ins_tbl_appraisals->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_appraisals->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_appraisals->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_appraisals->setTable("tbl_appraisals");
$ins_tbl_appraisals->addColumn("Appraisal", "STRING_TYPE", "POST", "Appraisal");
$ins_tbl_appraisals->addColumn("Date", "STRING_TYPE", "POST", "Date");
$ins_tbl_appraisals->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_appraisals = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_appraisals);
// Register triggers
$upd_tbl_appraisals->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_appraisals->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_appraisals->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_appraisals->setTable("tbl_appraisals");
$upd_tbl_appraisals->addColumn("Appraisal", "STRING_TYPE", "POST", "Appraisal");
$upd_tbl_appraisals->addColumn("Date", "STRING_TYPE", "POST", "Date");
$upd_tbl_appraisals->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_appraisals = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_appraisals);
// Register triggers
$del_tbl_appraisals->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_appraisals->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_appraisals->setTable("tbl_appraisals");
$del_tbl_appraisals->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_appraisals = $tNGs->getRecordset("tbl_appraisals");
$row_rstbl_appraisals = mysql_fetch_assoc($rstbl_appraisals);
$totalRows_rstbl_appraisals = mysql_num_rows($rstbl_appraisals);

$nav_listtbl_appraisals1->checkBoundries();
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
  .KT_col_Appraisal {width:140px; overflow:hidden;}
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
    Appraisal</h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_appraisals > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="Appraisal_<?php echo $cnt1; ?>">Appraisal:</label></td>
            <td><input type="text" name="Appraisal_<?php echo $cnt1; ?>" id="Appraisal_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_appraisals['Appraisal']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("Appraisal");?> <?php echo $tNGs->displayFieldError("tbl_appraisals", "Appraisal", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Date_<?php echo $cnt1; ?>">Date:</label></td>
            <td><input name="Date_<?php echo $cnt1; ?>" id="Date_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_appraisals['Date']); ?>" size="32" maxlength="255" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:mondayfirst="true" wdg:singleclick="true" wdg:restricttomask="yes" />
                <?php echo $tNGs->displayFieldHint("Date");?> <?php echo $tNGs->displayFieldError("tbl_appraisals", "Date", $cnt1); ?> </td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_tbl_appraisals_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_appraisals['kt_pk_tbl_appraisals']); ?>" />
        <?php } while ($row_rstbl_appraisals = mysql_fetch_assoc($rstbl_appraisals)); ?>
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
<p>&nbsp;</p>
</p>
<div class="KT_tng" id="listtbl_appraisals1">
  <h1> Appraisals
    <?php
  $nav_listtbl_appraisals1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
      <div class="KT_options"> <a href="<?php echo $nav_listtbl_appraisals1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_appraisals1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listtbl_appraisals1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_appraisals1'] == 1) {
?>
                              <a href="<?php echo $tfi_listtbl_appraisals1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                              <?php 
  // else Conditional region2
  } else { ?>
                              <a href="<?php echo $tfi_listtbl_appraisals1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                              <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="Appraisal" class="KT_sorter KT_col_Appraisal <?php echo $tso_listtbl_appraisals1->getSortIcon('tbl_appraisals.Appraisal'); ?>"> <a href="<?php echo $tso_listtbl_appraisals1->getSortLink('tbl_appraisals.Appraisal'); ?>">Appraisal</a> </th>
            <th id="Date" class="KT_sorter KT_col_Date <?php echo $tso_listtbl_appraisals1->getSortIcon('tbl_appraisals.Date'); ?>"> <a href="<?php echo $tso_listtbl_appraisals1->getSortLink('tbl_appraisals.Date'); ?>">Date</a> </th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_appraisals1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listtbl_appraisals1_Appraisal" id="tfi_listtbl_appraisals1_Appraisal" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_appraisals1_Appraisal']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listtbl_appraisals1_Date" id="tfi_listtbl_appraisals1_Date" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_appraisals1_Date']); ?>" size="20" maxlength="255" /></td>
              <td><input type="submit" name="tfi_listtbl_appraisals1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rstbl_appraisals1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rstbl_appraisals1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_tbl_appraisals" class="id_checkbox" value="<?php echo $row_rstbl_appraisals1['Id']; ?>" />
                    <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_appraisals1['Id']; ?>" />
                </td>
                <td><div class="KT_col_Appraisal"><?php echo KT_FormatForList($row_rstbl_appraisals1['Appraisal'], 20); ?></div></td>
                <td><div class="KT_col_Date"><?php echo KT_FormatForList($row_rstbl_appraisals1['Date'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="appraisals.php?Id=<?php echo $row_rstbl_appraisals1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
              </tr>
              <?php } while ($row_rstbl_appraisals1 = mysql_fetch_assoc($rstbl_appraisals1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listtbl_appraisals1->Prepare();
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
        <a class="KT_additem_op_link" href="appraisals.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstbl_appraisals1);
?>
