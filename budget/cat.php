<?php require_once('Connections/budget.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('includes/nxt/KT_back.php');

// Load the required classes
require_once('includes/tfi/TFI.php');
require_once('includes/tso/TSO.php');
require_once('includes/nav/NAV.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_budget = new KT_connection($budget, $database_budget);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("Category", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

// Filter
$tfi_listtbl_categories1 = new TFI_TableFilter($conn_budget, "tfi_listtbl_categories1");
$tfi_listtbl_categories1->addColumn("tbl_categories.Category", "STRING_TYPE", "Category", "%");
$tfi_listtbl_categories1->Execute();

// Sorter
$tso_listtbl_categories1 = new TSO_TableSorter("rstbl_categories1", "tso_listtbl_categories1");
$tso_listtbl_categories1->addColumn("tbl_categories.Category");
$tso_listtbl_categories1->setDefault("tbl_categories.Category");
$tso_listtbl_categories1->Execute();

// Navigation
$nav_listtbl_categories1 = new NAV_Regular("nav_listtbl_categories1", "rstbl_categories1", "", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_categories1 = $_SESSION['max_rows_nav_listtbl_categories1'];
$pageNum_rstbl_categories1 = 0;
if (isset($_GET['pageNum_rstbl_categories1'])) {
  $pageNum_rstbl_categories1 = $_GET['pageNum_rstbl_categories1'];
}
$startRow_rstbl_categories1 = $pageNum_rstbl_categories1 * $maxRows_rstbl_categories1;

$NXTFilter_rstbl_categories1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_categories1'])) {
  $NXTFilter_rstbl_categories1 = $_SESSION['filter_tfi_listtbl_categories1'];
}
$NXTSort_rstbl_categories1 = "tbl_categories.Category";
if (isset($_SESSION['sorter_tso_listtbl_categories1'])) {
  $NXTSort_rstbl_categories1 = $_SESSION['sorter_tso_listtbl_categories1'];
}
mysql_select_db($database_budget, $budget);

$query_rstbl_categories1 = sprintf("SELECT tbl_categories.Category, tbl_categories.Id FROM tbl_categories WHERE %s ORDER BY %s", $NXTFilter_rstbl_categories1, $NXTSort_rstbl_categories1);
$query_limit_rstbl_categories1 = sprintf("%s LIMIT %d, %d", $query_rstbl_categories1, $startRow_rstbl_categories1, $maxRows_rstbl_categories1);
$rstbl_categories1 = mysql_query($query_limit_rstbl_categories1, $budget) or die(mysql_error());
$row_rstbl_categories1 = mysql_fetch_assoc($rstbl_categories1);

if (isset($_GET['totalRows_rstbl_categories1'])) {
  $totalRows_rstbl_categories1 = $_GET['totalRows_rstbl_categories1'];
} else {
  $all_rstbl_categories1 = mysql_query($query_rstbl_categories1);
  $totalRows_rstbl_categories1 = mysql_num_rows($all_rstbl_categories1);
}
$totalPages_rstbl_categories1 = ceil($totalRows_rstbl_categories1/$maxRows_rstbl_categories1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_categories = new tNG_multipleInsert($conn_budget);
$tNGs->addTransaction($ins_tbl_categories);
// Register triggers
$ins_tbl_categories->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_categories->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_categories->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$ins_tbl_categories->setTable("tbl_categories");
$ins_tbl_categories->addColumn("Category", "STRING_TYPE", "POST", "Category");
$ins_tbl_categories->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_categories = new tNG_multipleUpdate($conn_budget);
$tNGs->addTransaction($upd_tbl_categories);
// Register triggers
$upd_tbl_categories->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_categories->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_categories->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$upd_tbl_categories->setTable("tbl_categories");
$upd_tbl_categories->addColumn("Category", "STRING_TYPE", "POST", "Category");
$upd_tbl_categories->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_categories = new tNG_multipleDelete($conn_budget);
$tNGs->addTransaction($del_tbl_categories);
// Register triggers
$del_tbl_categories->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_categories->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$del_tbl_categories->setTable("tbl_categories");
$del_tbl_categories->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_categories = $tNGs->getRecordset("tbl_categories");
$row_rstbl_categories = mysql_fetch_assoc($rstbl_categories);
$totalRows_rstbl_categories = mysql_num_rows($rstbl_categories);

$nav_listtbl_categories1->checkBoundries();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: true,
  merge_down_value: true
}
</script>
<script src="includes/nxt/scripts/list.js" type="text/javascript"></script>
<script src="includes/nxt/scripts/list.js.php" type="text/javascript"></script>
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
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#006699"><p>&nbsp;</p>
        <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>
          <?php include('menu.php'); ?>
      </p></td>
    <td valign="top"><div style="padding-left:5px">
      <table width="100" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td nowrap="nowrap">&nbsp;
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
                          Category</h1>
                        <div class="KT_tngform">
                          <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                            <?php $cnt1 = 0; ?>
                            <?php do { ?>
                            <?php $cnt1++; ?>
                            <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_categories > 1) {
?>
                            <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                            <?php } 
// endif Conditional region1
?>
                            <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                              <tr>
                                <td class="KT_th"><label for="Category_<?php echo $cnt1; ?>">Category:</label></td>
                                <td><input type="text" name="Category_<?php echo $cnt1; ?>" id="Category_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_categories['Category']); ?>" size="32" maxlength="255" />
                                <?php echo $tNGs->displayFieldHint("Category");?> <?php echo $tNGs->displayFieldError("tbl_categories", "Category", $cnt1); ?> </td>
                              </tr>
                              </table>
                            <input type="hidden" name="kt_pk_tbl_categories_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_categories['kt_pk_tbl_categories']); ?>" />
                            <?php } while ($row_rstbl_categories = mysql_fetch_assoc($rstbl_categories)); ?>
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
                                <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onClick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />
                                </div>
                            </div>
                          </form>
                        </div>
                        <br class="clearfixplain" />
                        </div>
                  <p>&nbsp;</p></td>
              </tr>
            </table></td>
          </tr>
        <tr>
          <td nowrap="nowrap">&nbsp;
                <div class="KT_tng" id="listtbl_categories1">
                  <h1> Categories
                    <?php
  $nav_listtbl_categories1->Prepare();
  require("includes/nav/NAV_Text_Statistics.inc.php");
?>
                    </h1>
                  <div class="KT_tnglist">
                    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
                      <div class="KT_options"> <a href="<?php echo $nav_listtbl_categories1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                          <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_categories1'] == 1) {
?>
                          <?php echo $_SESSION['default_max_rows_nav_listtbl_categories1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_categories1'] == 1) {
?>
                        <a href="<?php echo $tfi_listtbl_categories1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                                          <?php 
  // else Conditional region2
  } else { ?>
                        <a href="<?php echo $tfi_listtbl_categories1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                                          <?php } 
  // endif Conditional region2
?>
                        </div>
                      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                        <thead>
                          <tr class="KT_row_order">
                            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>                          </th>
                            <th id="Category" class="KT_sorter KT_col_Category <?php echo $tso_listtbl_categories1->getSortIcon('tbl_categories.Category'); ?>"> <a href="<?php echo $tso_listtbl_categories1->getSortLink('tbl_categories.Category'); ?>">Category</a> </th>
                            <th>&nbsp;</th>
                          </tr>
                          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_categories1'] == 1) {
?>
                          <tr class="KT_row_filter">
                            <td>&nbsp;</td>
                            <td><input type="text" name="tfi_listtbl_categories1_Category" id="tfi_listtbl_categories1_Category" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_categories1_Category']); ?>" size="20" maxlength="255" /></td>
                            <td><input type="submit" name="tfi_listtbl_categories1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                          </tr>
                          <?php } 
  // endif Conditional region3
?>
                          </thead>
                        <tbody>
                          <?php if ($totalRows_rstbl_categories1 == 0) { // Show if recordset empty ?>
                          <tr>
                            <td colspan="3"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                          </tr>
                          <?php } // Show if recordset empty ?>
                          <?php if ($totalRows_rstbl_categories1 > 0) { // Show if recordset not empty ?>
                          <?php do { ?>
                          <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                            <td><input type="checkbox" name="kt_pk_tbl_categories" class="id_checkbox" value="<?php echo $row_rstbl_categories1['Id']; ?>" />
                            <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_categories1['Id']; ?>" />                          </td>
                            <td><div class="KT_col_Category"><?php echo KT_FormatForList($row_rstbl_categories1['Category'], 20); ?></div></td>
                            <td><a class="KT_edit_link" href="cat.php?Id=<?php echo $row_rstbl_categories1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                          </tr>
                          <?php } while ($row_rstbl_categories1 = mysql_fetch_assoc($rstbl_categories1)); ?>
                          <?php } // Show if recordset not empty ?>
                          </tbody>
                        </table>
                      <div class="KT_bottomnav">
                        <div>
                          <?php
            $nav_listtbl_categories1->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                          </div>
                      </div>
                      <div class="KT_bottombuttons">
                        <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onClick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onClick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
    <span>&nbsp;</span>
                        <select name="no_new" id="no_new">
                          <option value="1">1</option>
                          <option value="3">3</option>
                          <option value="6">6</option>
                          </select>
                        <a class="KT_additem_op_link" href="cat.php?KT_back=1" onClick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
                    </form>
                  </div>
                  <br class="clearfixplain" />
                  </div>
            <p>&nbsp;</p></td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rstbl_categories1);
?>
