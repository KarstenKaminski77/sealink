<?php require_once('../Connections/inv.php'); ?>
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
$conn_inv = new KT_connection($inv, $database_inv);

// Start trigger
$formValidation = new tNG_FormValidation();
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
$tfi_listtbl_menu_categories2 = new TFI_TableFilter($conn_inv, "tfi_listtbl_menu_categories2");
$tfi_listtbl_menu_categories2->addColumn("tbl_menu_categories.Category", "STRING_TYPE", "Category", "%");
$tfi_listtbl_menu_categories2->addColumn("tbl_menu_categories.OrderBy", "NUMERIC_TYPE", "OrderBy", "=");
$tfi_listtbl_menu_categories2->Execute();

// Sorter
$tso_listtbl_menu_categories2 = new TSO_TableSorter("rstbl_menu_categories1", "tso_listtbl_menu_categories2");
$tso_listtbl_menu_categories2->addColumn("tbl_menu_categories.Category");
$tso_listtbl_menu_categories2->addColumn("tbl_menu_categories.OrderBy");
$tso_listtbl_menu_categories2->setDefault("tbl_menu_categories.Category");
$tso_listtbl_menu_categories2->Execute();

// Navigation
$nav_listtbl_menu_categories2 = new NAV_Regular("nav_listtbl_menu_categories2", "rstbl_menu_categories1", "../", $_SERVER['PHP_SELF'], 40);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_menu_categories1 = $_SESSION['max_rows_nav_listtbl_menu_categories2'];
$pageNum_rstbl_menu_categories1 = 0;
if (isset($_GET['pageNum_rstbl_menu_categories1'])) {
  $pageNum_rstbl_menu_categories1 = $_GET['pageNum_rstbl_menu_categories1'];
}
$startRow_rstbl_menu_categories1 = $pageNum_rstbl_menu_categories1 * $maxRows_rstbl_menu_categories1;

// Defining List Recordset variable
$NXTFilter_rstbl_menu_categories1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_menu_categories2'])) {
  $NXTFilter_rstbl_menu_categories1 = $_SESSION['filter_tfi_listtbl_menu_categories2'];
}
// Defining List Recordset variable
$NXTSort_rstbl_menu_categories1 = "tbl_menu_categories.Category";
if (isset($_SESSION['sorter_tso_listtbl_menu_categories2'])) {
  $NXTSort_rstbl_menu_categories1 = $_SESSION['sorter_tso_listtbl_menu_categories2'];
}
mysql_select_db($database_inv, $inv);

$query_rstbl_menu_categories1 = "SELECT tbl_menu_categories.Category, tbl_menu_categories.OrderBy, tbl_menu_categories.Id FROM tbl_menu_categories WHERE {$NXTFilter_rstbl_menu_categories1} ORDER BY {$NXTSort_rstbl_menu_categories1}";
$query_limit_rstbl_menu_categories1 = sprintf("%s LIMIT %d, %d", $query_rstbl_menu_categories1, $startRow_rstbl_menu_categories1, $maxRows_rstbl_menu_categories1);
$rstbl_menu_categories1 = mysql_query($query_limit_rstbl_menu_categories1, $inv) or die(mysql_error());
$row_rstbl_menu_categories1 = mysql_fetch_assoc($rstbl_menu_categories1);

if (isset($_GET['totalRows_rstbl_menu_categories1'])) {
  $totalRows_rstbl_menu_categories1 = $_GET['totalRows_rstbl_menu_categories1'];
} else {
  $all_rstbl_menu_categories1 = mysql_query($query_rstbl_menu_categories1);
  $totalRows_rstbl_menu_categories1 = mysql_num_rows($all_rstbl_menu_categories1);
}
$totalPages_rstbl_menu_categories1 = ceil($totalRows_rstbl_menu_categories1/$maxRows_rstbl_menu_categories1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_menu_categories = new tNG_multipleInsert($conn_inv);
$tNGs->addTransaction($ins_tbl_menu_categories);
// Register triggers
$ins_tbl_menu_categories->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_menu_categories->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_menu_categories->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_menu_categories->setTable("tbl_menu_categories");
$ins_tbl_menu_categories->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_menu_categories = new tNG_multipleUpdate($conn_inv);
$tNGs->addTransaction($upd_tbl_menu_categories);
// Register triggers
$upd_tbl_menu_categories->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_menu_categories->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_menu_categories->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_menu_categories->setTable("tbl_menu_categories");
$upd_tbl_menu_categories->addColumn("Category", "STRING_TYPE", "CURRVAL", "");
$upd_tbl_menu_categories->addColumn("OrderBy", "NUMERIC_TYPE", "POST", "OrderBy");
$upd_tbl_menu_categories->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_menu_categories = new tNG_multipleDelete($conn_inv);
$tNGs->addTransaction($del_tbl_menu_categories);
// Register triggers
$del_tbl_menu_categories->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_menu_categories->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_menu_categories->setTable("tbl_menu_categories");
$del_tbl_menu_categories->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_menu_categories = $tNGs->getRecordset("tbl_menu_categories");
$row_rstbl_menu_categories = mysql_fetch_assoc($rstbl_menu_categories);
$totalRows_rstbl_menu_categories = mysql_num_rows($rstbl_menu_categories);

$nav_listtbl_menu_categories2->checkBoundries();
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
  .KT_col_Category {width:420px; overflow:hidden;}
  .KT_col_OrderBy {width:140px; overflow:hidden;}
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../menu.php'); ?></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center"><p>&nbsp;</p>
          <p>&nbsp;
          <div style="margin-left:30px; clear:both; overflow:hidden">
            <div class="KT_tng">
              <h1>&nbsp;</h1>
              <div class="KT_tngform">
                <form method="post" id="form2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                  <?php $cnt1 = 0; ?>
                  <?php do { ?>
                    <?php $cnt1++; ?>
                    <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_menu_categories > 1) {
?>
                      <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                      <?php } 
// endif Conditional region1
?>
                    <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                      <?php 
// Show IF Conditional show_Category_on_update_only 
if (@$_GET['Id'] != "") {
?>
                        <tr>
                          <td class="KT_th">Category:</td>
                          <td><?php echo KT_escapeAttribute($row_rstbl_menu_categories['Category']); ?></td>
                          </tr>
                        <?php } 
// endif Conditional show_Category_on_update_only
?>
                      <?php 
// Show IF Conditional show_OrderBy_on_update_only 
if (@$_GET['Id'] != "") {
?>
                        <tr>
                          <td class="KT_th"><label for="OrderBy_<?php echo $cnt1; ?>">OrderBy:</label></td>
                          <td><input type="text" name="OrderBy_<?php echo $cnt1; ?>" id="OrderBy_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_menu_categories['OrderBy']); ?>" size="30" />
                            <?php echo $tNGs->displayFieldHint("OrderBy");?> <?php echo $tNGs->displayFieldError("tbl_menu_categories", "OrderBy", $cnt1); ?></td>
                          </tr>
                        <?php } 
// endif Conditional show_OrderBy_on_update_only
?>
                      </table>
                    <input type="hidden" name="kt_pk_tbl_menu_categories_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_menu_categories['kt_pk_tbl_menu_categories']); ?>" />
                    <?php } while ($row_rstbl_menu_categories = mysql_fetch_assoc($rstbl_menu_categories)); ?>
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
                      <br class="clearfixplain" />
            </div>
                  </div>
                </form>
                </div>
            </div>
            <p><br>
            </p>
          </div>
          <p><br>
            <br>
          <div class="KT_tng" id="listtbl_menu_categories2" style="margin-left:30px">
            <h1>&nbsp;</h1>
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form3">
                <div class="KT_options"> <a href="<?php echo $nav_listtbl_menu_categories2->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_menu_categories2'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listtbl_menu_categories2']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_menu_categories2'] == 1) {
?>
                    <a href="<?php echo $tfi_listtbl_menu_categories2->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                    <?php 
  // else Conditional region2
  } else { ?>
                    <a href="<?php echo $tfi_listtbl_menu_categories2->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                    <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                        </th>
                      <th id="Category" class="KT_sorter KT_col_Category <?php echo $tso_listtbl_menu_categories2->getSortIcon('tbl_menu_categories.Category'); ?>"> <a href="<?php echo $tso_listtbl_menu_categories2->getSortLink('tbl_menu_categories.Category'); ?>">Category</a> </th>
                      <th id="OrderBy" class="KT_sorter KT_col_OrderBy <?php echo $tso_listtbl_menu_categories2->getSortIcon('tbl_menu_categories.OrderBy'); ?>"> <a href="<?php echo $tso_listtbl_menu_categories2->getSortLink('tbl_menu_categories.OrderBy'); ?>">OrderBy</a> </th>
                      <th>&nbsp;</th>
                      </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_menu_categories2'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_listtbl_menu_categories2_Category" id="tfi_listtbl_menu_categories2_Category" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_menu_categories2_Category']); ?>" size="60" maxlength="100" /></td>
                        <td><input type="text" name="tfi_listtbl_menu_categories2_OrderBy" id="tfi_listtbl_menu_categories2_OrderBy" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_menu_categories2_OrderBy']); ?>" size="20" maxlength="100" /></td>
                        <td><input type="submit" name="tfi_listtbl_menu_categories2" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                        </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rstbl_menu_categories1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                        </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rstbl_menu_categories1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_tbl_menu_categories" class="id_checkbox" value="<?php echo $row_rstbl_menu_categories1['Id']; ?>" />
                            <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_menu_categories1['Id']; ?>" /></td>
                          <td><div class="KT_col_Category"><?php echo KT_FormatForList($row_rstbl_menu_categories1['Category'], 60); ?></div></td>
                          <td><div class="KT_col_OrderBy"><?php echo KT_FormatForList($row_rstbl_menu_categories1['OrderBy'], 20); ?></div></td>
                          <td><a class="KT_edit_link" href="menu-order.php?Id=<?php echo $row_rstbl_menu_categories1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
                          </tr>
                        <?php } while ($row_rstbl_menu_categories1 = mysql_fetch_assoc($rstbl_menu_categories1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                  </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listtbl_menu_categories2->Prepare();
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
                  <a class="KT_additem_op_link" href="menu-order.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
                </form>
              </div>
            <br class="clearfixplain" />
          </div>
          <p>&nbsp;</p>
          </p>
</p>
          </p>
</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rstbl_menu_categories1);
?>
