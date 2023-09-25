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

require_once('../functions/functions.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("Category", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

// Filter
$tfi_listtbl_beneficiaries1 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_beneficiaries1");
$tfi_listtbl_beneficiaries1->addColumn("tbl_beneficiaries.Category", "STRING_TYPE", "Category", "%");
$tfi_listtbl_beneficiaries1->Execute();

// Sorter
$tso_listtbl_beneficiaries1 = new TSO_TableSorter("rstbl_beneficiaries1", "tso_listtbl_beneficiaries1");
$tso_listtbl_beneficiaries1->addColumn("tbl_beneficiaries.Category");
$tso_listtbl_beneficiaries1->setDefault("tbl_beneficiaries.Category");
$tso_listtbl_beneficiaries1->Execute();

// Navigation
$nav_listtbl_beneficiaries1 = new NAV_Regular("nav_listtbl_beneficiaries1", "rstbl_beneficiaries1", "../", $_SERVER['PHP_SELF'], 50);

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../includes/treemenus/MX_TreeMenu.inc.php');

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

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_beneficiaries1 = $_SESSION['max_rows_nav_listtbl_beneficiaries1'];
$pageNum_rstbl_beneficiaries1 = 0;
if (isset($_GET['pageNum_rstbl_beneficiaries1'])) {
  $pageNum_rstbl_beneficiaries1 = $_GET['pageNum_rstbl_beneficiaries1'];
}
$startRow_rstbl_beneficiaries1 = $pageNum_rstbl_beneficiaries1 * $maxRows_rstbl_beneficiaries1;

$NXTFilter_rstbl_beneficiaries1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_beneficiaries1'])) {
  $NXTFilter_rstbl_beneficiaries1 = $_SESSION['filter_tfi_listtbl_beneficiaries1'];
}
$NXTSort_rstbl_beneficiaries1 = "tbl_beneficiaries.Category";
if (isset($_SESSION['sorter_tso_listtbl_beneficiaries1'])) {
  $NXTSort_rstbl_beneficiaries1 = $_SESSION['sorter_tso_listtbl_beneficiaries1'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_beneficiaries1 = sprintf("SELECT tbl_beneficiaries.Category, tbl_beneficiaries.Id FROM tbl_beneficiaries WHERE %s ORDER BY %s", $NXTFilter_rstbl_beneficiaries1, $NXTSort_rstbl_beneficiaries1);
$query_limit_rstbl_beneficiaries1 = sprintf("%s LIMIT %d, %d", $query_rstbl_beneficiaries1, $startRow_rstbl_beneficiaries1, $maxRows_rstbl_beneficiaries1);
$rstbl_beneficiaries1 = mysql_query($query_limit_rstbl_beneficiaries1, $seavest) or die(mysql_error());
$row_rstbl_beneficiaries1 = mysql_fetch_assoc($rstbl_beneficiaries1);

if (isset($_GET['totalRows_rstbl_beneficiaries1'])) {
  $totalRows_rstbl_beneficiaries1 = $_GET['totalRows_rstbl_beneficiaries1'];
} else {
  $all_rstbl_beneficiaries1 = mysql_query($query_rstbl_beneficiaries1);
  $totalRows_rstbl_beneficiaries1 = mysql_num_rows($all_rstbl_beneficiaries1);
}
$totalPages_rstbl_beneficiaries1 = ceil($totalRows_rstbl_beneficiaries1/$maxRows_rstbl_beneficiaries1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_beneficiaries = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_beneficiaries);
// Register triggers
$ins_tbl_beneficiaries->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_beneficiaries->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_beneficiaries->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_beneficiaries->setTable("tbl_beneficiaries");
$ins_tbl_beneficiaries->addColumn("Category", "STRING_TYPE", "POST", "Category");
$ins_tbl_beneficiaries->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_beneficiaries = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_beneficiaries);
// Register triggers
$upd_tbl_beneficiaries->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_beneficiaries->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_beneficiaries->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_beneficiaries->setTable("tbl_beneficiaries");
$upd_tbl_beneficiaries->addColumn("Category", "STRING_TYPE", "POST", "Category");
$upd_tbl_beneficiaries->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_beneficiaries = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_beneficiaries);
// Register triggers
$del_tbl_beneficiaries->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_beneficiaries->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_beneficiaries->setTable("tbl_beneficiaries");
$del_tbl_beneficiaries->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_beneficiaries = $tNGs->getRecordset("tbl_beneficiaries");
$row_rstbl_beneficiaries = mysql_fetch_assoc($rstbl_beneficiaries);
$totalRows_rstbl_beneficiaries = mysql_num_rows($rstbl_beneficiaries);

$nav_listtbl_beneficiaries1->checkBoundries();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset2 = new WDG_JsRecordset("Recordset2");
echo $jsObject_Recordset2->getOutput();
//end JSRecordset
?>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #000066;
	font-weight: bold;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000066;
}
a:hover {
	text-decoration: none;
	color: #000066;
}
a:active {
	text-decoration: none;
	color: #000066;
}
-->
</style>
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
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
  .KT_col_Category {width:140px; overflow:hidden;}
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
      </td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;
            <?php
	echo $tNGs->getErrorMsg();
?>
          <table>
		  <tr><td>
		  <div class="KT_tng" style="padding-left:25px">
            <div style="padding: 10px; border:solid 1px #cccccc; margin:2px; background-color:#EEEEEE">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_beneficiaries > 1) {
?>
                    
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0">
                    <tr>
                      <td class="KT_th"><label for="Category_<?php echo $cnt1; ?>" class="combo_bold">beneficiary:</label></td>
                      <td><input type="text" name="Category_<?php echo $cnt1; ?>" id="Category_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_beneficiaries['Category']); ?>" size="32" maxlength="255" />
                          <?php echo $tNGs->displayFieldHint("Category");?> <?php echo $tNGs->displayFieldError("tbl_beneficiaries", "Category", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_tbl_beneficiaries_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_beneficiaries['kt_pk_tbl_beneficiaries']); ?>" />
                  <?php } while ($row_rstbl_beneficiaries = mysql_fetch_assoc($rstbl_beneficiaries)); ?>
                <div>
                  <div style="padding:5px; margin:0px; text-align:right">
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['Id'] == "") {
      ?>
                      <input name="KT_Insert1" type="submit" class="tarea2" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <input name="KT_Update1" type="submit" class="tarea2" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <input name="KT_Delete1" type="submit" class="tarea2" onClick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" value="<?php echo NXT_getResource("Delete_FB"); ?>" />
                      <?php }
      // endif Conditional region1
      ?>
                  </div>
                </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          </td></tr>
		  </table>
		  <p>
          <div class="KT_tng" id="listtbl_beneficiaries1" style="padding-left:25px">
            <div class="KT_tnglist" style="padding: 10px; border:solid 1px #cccccc; margin:2px; background-color:#EEEEEE">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
                <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="border:none">
                  <thead>

                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_beneficiaries1'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_listtbl_beneficiaries1_Category" id="tfi_listtbl_beneficiaries1_Category" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_beneficiaries1_Category']); ?>" size="20" maxlength="255" /></td>
                        <td><input type="submit" name="tfi_listtbl_beneficiaries1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rstbl_beneficiaries1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="3"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rstbl_beneficiaries1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>" style="border:none">
                          <td style="border:none"><input type="checkbox" name="kt_pk_tbl_beneficiaries" class="id_checkbox" value="<?php echo $row_rstbl_beneficiaries1['Id']; ?>" />
                              <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_beneficiaries1['Id']; ?>" />                          </td>
                          <td style="border:none"><div class="KT_col_Category"><?php echo KT_FormatForList($row_rstbl_beneficiaries1['Category'], 20); ?></div></td>
                          <td style="border:none"><a class="tarea2" style="padding-left:5px; padding-right:5px" href="beneficiaries.php?Id=<?php echo $row_rstbl_beneficiaries1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="tarea2" style="padding-left:5px; padding-right:5px" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                        </tr>
                        <?php } while ($row_rstbl_beneficiaries1 = mysql_fetch_assoc($rstbl_beneficiaries1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listtbl_beneficiaries1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons" style="border:none; background-color:#EEEEEE">
                  <div class="KT_operations"><a class="tarea2" href="#" onClick="nxt_list_edit_link_form(this); return false;" style="padding-left:5px; padding-right:5px"><?php echo NXT_getResource("edit_all"); ?></a><a class="tarea2" style="padding-left:5px; padding-right:5px; margin-left:3px" href="#" onClick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
                  <span>&nbsp;</span>
                  <select name="no_new" class="tarea2" id="no_new">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="6">6</option>
                  </select>
                  <a class="tarea2" style="padding-left:5px; padding-right:5px" href="beneficiaries.php?KT_back=1" onClick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($rstbl_beneficiaries1);
?>
