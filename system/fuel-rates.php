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
$tfi_listtbl_fuel1 = new TFI_TableFilter($conn_inv, "tfi_listtbl_fuel1");
$tfi_listtbl_fuel1->addColumn("tbl_companies.Id", "STRING_TYPE", "Company", "%");
$tfi_listtbl_fuel1->addColumn("tbl_fuel.Rate", "DOUBLE_TYPE", "Rate", "=");
$tfi_listtbl_fuel1->Execute();

// Sorter
$tso_listtbl_fuel1 = new TSO_TableSorter("rstbl_fuel1", "tso_listtbl_fuel1");
$tso_listtbl_fuel1->addColumn("tbl_companies.Name");
$tso_listtbl_fuel1->addColumn("tbl_fuel.Rate");
$tso_listtbl_fuel1->setDefault("tbl_fuel.Company");
$tso_listtbl_fuel1->Execute();

// Navigation
$nav_listtbl_fuel1 = new NAV_Regular("nav_listtbl_fuel1", "rstbl_fuel1", "../", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_inv, $inv);
$query_Recordset1 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_inv, $inv);
$query_Recordset2 = "SELECT Name, Id FROM tbl_companies ORDER BY Name";
$Recordset2 = mysql_query($query_Recordset2, $inv) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_fuel1 = $_SESSION['max_rows_nav_listtbl_fuel1'];
$pageNum_rstbl_fuel1 = 0;
if (isset($_GET['pageNum_rstbl_fuel1'])) {
  $pageNum_rstbl_fuel1 = $_GET['pageNum_rstbl_fuel1'];
}
$startRow_rstbl_fuel1 = $pageNum_rstbl_fuel1 * $maxRows_rstbl_fuel1;

// Defining List Recordset variable
$NXTFilter_rstbl_fuel1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_fuel1'])) {
  $NXTFilter_rstbl_fuel1 = $_SESSION['filter_tfi_listtbl_fuel1'];
}
// Defining List Recordset variable
$NXTSort_rstbl_fuel1 = "tbl_fuel.Company";
if (isset($_SESSION['sorter_tso_listtbl_fuel1'])) {
  $NXTSort_rstbl_fuel1 = $_SESSION['sorter_tso_listtbl_fuel1'];
}
mysql_select_db($database_inv, $inv);

$query_rstbl_fuel1 = "SELECT tbl_companies.Name AS Company, tbl_fuel.Rate, tbl_fuel.Id FROM tbl_fuel LEFT JOIN tbl_companies ON tbl_fuel.Company = tbl_companies.Id WHERE {$NXTFilter_rstbl_fuel1} ORDER BY {$NXTSort_rstbl_fuel1}";
$query_limit_rstbl_fuel1 = sprintf("%s LIMIT %d, %d", $query_rstbl_fuel1, $startRow_rstbl_fuel1, $maxRows_rstbl_fuel1);
$rstbl_fuel1 = mysql_query($query_limit_rstbl_fuel1, $inv) or die(mysql_error());
$row_rstbl_fuel1 = mysql_fetch_assoc($rstbl_fuel1);

if (isset($_GET['totalRows_rstbl_fuel1'])) {
  $totalRows_rstbl_fuel1 = $_GET['totalRows_rstbl_fuel1'];
} else {
  $all_rstbl_fuel1 = mysql_query($query_rstbl_fuel1);
  $totalRows_rstbl_fuel1 = mysql_num_rows($all_rstbl_fuel1);
}
$totalPages_rstbl_fuel1 = ceil($totalRows_rstbl_fuel1/$maxRows_rstbl_fuel1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_fuel = new tNG_multipleInsert($conn_inv);
$tNGs->addTransaction($ins_tbl_fuel);
// Register triggers
$ins_tbl_fuel->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_fuel->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_fuel->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_fuel->setTable("tbl_fuel");
$ins_tbl_fuel->addColumn("Company", "NUMERIC_TYPE", "POST", "Company");
$ins_tbl_fuel->addColumn("Rate", "DOUBLE_TYPE", "POST", "Rate");
$ins_tbl_fuel->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_fuel = new tNG_multipleUpdate($conn_inv);
$tNGs->addTransaction($upd_tbl_fuel);
// Register triggers
$upd_tbl_fuel->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_fuel->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_fuel->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_fuel->setTable("tbl_fuel");
$upd_tbl_fuel->addColumn("Company", "NUMERIC_TYPE", "POST", "Company");
$upd_tbl_fuel->addColumn("Rate", "DOUBLE_TYPE", "POST", "Rate");
$upd_tbl_fuel->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_fuel = new tNG_multipleDelete($conn_inv);
$tNGs->addTransaction($del_tbl_fuel);
// Register triggers
$del_tbl_fuel->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_fuel->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_fuel->setTable("tbl_fuel");
$del_tbl_fuel->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_fuel = $tNGs->getRecordset("tbl_fuel");
$row_rstbl_fuel = mysql_fetch_assoc($rstbl_fuel);
$totalRows_rstbl_fuel = mysql_num_rows($rstbl_fuel);

$nav_listtbl_fuel1->checkBoundries();
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
  .KT_col_Company {width:140px; overflow:hidden;}
  .KT_col_Rate {width:140px; overflow:hidden;}
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
        <td align="center">
        <div style="margin-left:30px; text-align:left">
        <div class="KT_tng">
            <h1>&nbsp;</h1>
            <br>
            <br>
            <br>
            <div class="KT_tngform">
              <form method="post" id="form2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_fuel > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="Company_<?php echo $cnt1; ?>">Company:</label></td>
                      <td><select name="Company_<?php echo $cnt1; ?>" id="Company_<?php echo $cnt1; ?>">
                        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        <?php 
do {  
?>
                        <option value="<?php echo $row_Recordset1['Id']?>"<?php if (!(strcmp($row_Recordset1['Id'], $row_rstbl_fuel['Company']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['Name']?></option>
                        <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                      </select>
                        <?php echo $tNGs->displayFieldError("tbl_fuel", "Company", $cnt1); ?></td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Rate_<?php echo $cnt1; ?>">Rate:</label></td>
                      <td><input type="text" name="Rate_<?php echo $cnt1; ?>" id="Rate_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_fuel['Rate']); ?>" size="32" />
                        <?php echo $tNGs->displayFieldHint("Rate");?> <?php echo $tNGs->displayFieldError("tbl_fuel", "Rate", $cnt1); ?></td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_tbl_fuel_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_fuel['kt_pk_tbl_fuel']); ?>" />
                  <?php } while ($row_rstbl_fuel = mysql_fetch_assoc($rstbl_fuel)); ?>
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
            <br>
            <br>
            <br>
            <br>
        </div>
        <div class="KT_tng" id="listtbl_fuel1">
            <h1>&nbsp;</h1>
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form3">
                <div class="KT_options"> <a href="<?php echo $nav_listtbl_fuel1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_fuel1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listtbl_fuel1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_fuel1'] == 1) {
?>
                    <a href="<?php echo $tfi_listtbl_fuel1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                    <?php 
  // else Conditional region2
  } else { ?>
                    <a href="<?php echo $tfi_listtbl_fuel1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                    <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="Company" class="KT_sorter KT_col_Company <?php echo $tso_listtbl_fuel1->getSortIcon('tbl_companies.Name'); ?>"> <a href="<?php echo $tso_listtbl_fuel1->getSortLink('tbl_companies.Name'); ?>">Company</a> </th>
                      <th id="Rate" class="KT_sorter KT_col_Rate <?php echo $tso_listtbl_fuel1->getSortIcon('tbl_fuel.Rate'); ?>"> <a href="<?php echo $tso_listtbl_fuel1->getSortLink('tbl_fuel.Rate'); ?>">Rate</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_fuel1'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><select name="tfi_listtbl_fuel1_Company" id="tfi_listtbl_fuel1_Company">
                          <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listtbl_fuel1_Company']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset2['Id']?>"<?php if (!(strcmp($row_Recordset2['Id'], @$_SESSION['tfi_listtbl_fuel1_Company']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['Name']?></option>
                          <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                        </select></td>
                        <td><input type="text" name="tfi_listtbl_fuel1_Rate" id="tfi_listtbl_fuel1_Rate" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_fuel1_Rate']); ?>" size="20" maxlength="100" /></td>
                        <td><input type="submit" name="tfi_listtbl_fuel1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rstbl_fuel1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rstbl_fuel1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_tbl_fuel" class="id_checkbox" value="<?php echo $row_rstbl_fuel1['Id']; ?>" />
                            <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_fuel1['Id']; ?>" /></td>
                          <td><div class="KT_col_Company"><?php echo KT_FormatForList($row_rstbl_fuel1['Company'], 20); ?></div></td>
                          <td><div class="KT_col_Rate"><?php echo KT_FormatForList($row_rstbl_fuel1['Rate'], 20); ?></div></td>
                          <td><a class="KT_edit_link" href="fuel-rates.php?Id=<?php echo $row_rstbl_fuel1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
                        </tr>
                        <?php } while ($row_rstbl_fuel1 = mysql_fetch_assoc($rstbl_fuel1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listtbl_fuel1->Prepare();
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
                  <a class="KT_additem_op_link" href="fuel-rates.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          </div>
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($rstbl_fuel1);
?>
