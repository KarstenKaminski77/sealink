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
$tNGs->prepareValidation($formValidation);
// End trigger

// Filter
$tfi_listtbl_companies1 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_companies1");
$tfi_listtbl_companies1->addColumn("tbl_companies.Name", "STRING_TYPE", "Name", "%");
$tfi_listtbl_companies1->addColumn("tbl_companies.ContactName", "STRING_TYPE", "ContactName", "%");
$tfi_listtbl_companies1->addColumn("tbl_companies.ContactNumber", "STRING_TYPE", "ContactNumber", "%");
$tfi_listtbl_companies1->addColumn("tbl_companies.ContactEmail", "STRING_TYPE", "ContactEmail", "%");
$tfi_listtbl_companies1->Execute();

// Sorter
$tso_listtbl_companies1 = new TSO_TableSorter("rstbl_companies1", "tso_listtbl_companies1");
$tso_listtbl_companies1->addColumn("tbl_companies.Name");
$tso_listtbl_companies1->addColumn("tbl_companies.ContactName");
$tso_listtbl_companies1->addColumn("tbl_companies.ContactNumber");
$tso_listtbl_companies1->addColumn("tbl_companies.ContactEmail");
$tso_listtbl_companies1->setDefault("tbl_companies.Name");
$tso_listtbl_companies1->Execute();

// Navigation
$nav_listtbl_companies1 = new NAV_Regular("nav_listtbl_companies1", "rstbl_companies1", "../", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_companies1 = $_SESSION['max_rows_nav_listtbl_companies1'];
$pageNum_rstbl_companies1 = 0;
if (isset($_GET['pageNum_rstbl_companies1'])) {
  $pageNum_rstbl_companies1 = $_GET['pageNum_rstbl_companies1'];
}
$startRow_rstbl_companies1 = $pageNum_rstbl_companies1 * $maxRows_rstbl_companies1;

$NXTFilter_rstbl_companies1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_companies1'])) {
  $NXTFilter_rstbl_companies1 = $_SESSION['filter_tfi_listtbl_companies1'];
}
$NXTSort_rstbl_companies1 = "tbl_companies.Name";
if (isset($_SESSION['sorter_tso_listtbl_companies1'])) {
  $NXTSort_rstbl_companies1 = $_SESSION['sorter_tso_listtbl_companies1'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_companies1 = sprintf("SELECT tbl_companies.Name, tbl_companies.ContactName, tbl_companies.ContactNumber, tbl_companies.ContactEmail, tbl_companies.Id FROM tbl_companies WHERE %s ORDER BY %s", $NXTFilter_rstbl_companies1, $NXTSort_rstbl_companies1);
$query_limit_rstbl_companies1 = sprintf("%s LIMIT %d, %d", $query_rstbl_companies1, $startRow_rstbl_companies1, $maxRows_rstbl_companies1);
$rstbl_companies1 = mysql_query($query_limit_rstbl_companies1, $seavest) or die(mysql_error());
$row_rstbl_companies1 = mysql_fetch_assoc($rstbl_companies1);

if (isset($_GET['totalRows_rstbl_companies1'])) {
  $totalRows_rstbl_companies1 = $_GET['totalRows_rstbl_companies1'];
} else {
  $all_rstbl_companies1 = mysql_query($query_rstbl_companies1);
  $totalRows_rstbl_companies1 = mysql_num_rows($all_rstbl_companies1);
}
$totalPages_rstbl_companies1 = ceil($totalRows_rstbl_companies1/$maxRows_rstbl_companies1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_companies = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_companies);
// Register triggers
$ins_tbl_companies->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_companies->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_companies->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_companies->setTable("tbl_companies");
$ins_tbl_companies->addColumn("Name", "STRING_TYPE", "POST", "Name");
$ins_tbl_companies->addColumn("Address", "STRING_TYPE", "POST", "Address");
$ins_tbl_companies->addColumn("ContactName", "STRING_TYPE", "POST", "ContactName");
$ins_tbl_companies->addColumn("ContactNumber", "STRING_TYPE", "POST", "ContactNumber");
$ins_tbl_companies->addColumn("ContactEmail", "STRING_TYPE", "POST", "ContactEmail");
$ins_tbl_companies->addColumn("VATNO", "STRING_TYPE", "POST", "VATNO");
$ins_tbl_companies->addColumn("Prefix", "STRING_TYPE", "POST", "Prefix");
$ins_tbl_companies->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_companies = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_companies);
// Register triggers
$upd_tbl_companies->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_companies->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_companies->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_companies->setTable("tbl_companies");
$upd_tbl_companies->addColumn("Name", "STRING_TYPE", "POST", "Name");
$upd_tbl_companies->addColumn("Address", "STRING_TYPE", "POST", "Address");
$upd_tbl_companies->addColumn("ContactName", "STRING_TYPE", "POST", "ContactName");
$upd_tbl_companies->addColumn("ContactNumber", "STRING_TYPE", "POST", "ContactNumber");
$upd_tbl_companies->addColumn("ContactEmail", "STRING_TYPE", "POST", "ContactEmail");
$upd_tbl_companies->addColumn("VATNO", "STRING_TYPE", "POST", "VATNO");
$upd_tbl_companies->addColumn("Prefix", "STRING_TYPE", "POST", "Prefix");
$upd_tbl_companies->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_companies = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_companies);
// Register triggers
$del_tbl_companies->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_companies->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_companies->setTable("tbl_companies");
$del_tbl_companies->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_companies = $tNGs->getRecordset("tbl_companies");
$row_rstbl_companies = mysql_fetch_assoc($rstbl_companies);
$totalRows_rstbl_companies = mysql_num_rows($rstbl_companies);

$nav_listtbl_companies1->checkBoundries();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
  .KT_col_Name {width:140px; overflow:hidden;}
  .KT_col_ContactName {width:140px; overflow:hidden;}
  .KT_col_ContactNumber {width:140px; overflow:hidden;}
  .KT_col_ContactEmail {width:140px; overflow:hidden;}
</style>
</head>

<body>
<p><a href="index.php" class="top">Main Menu</a><br />
    <a href="logout.php" class="top">Logout</a></p>
<table width="100" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;
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
              Company</h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_companies > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="Name_<?php echo $cnt1; ?>">Name:</label></td>
                      <td><input type="text" name="Name_<?php echo $cnt1; ?>" id="Name_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_companies['Name']); ?>" size="32" maxlength="255" />
                          <?php echo $tNGs->displayFieldHint("Name");?> <?php echo $tNGs->displayFieldError("tbl_companies", "Name", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Address_<?php echo $cnt1; ?>">Address:</label></td>
                      <td><textarea name="Address_<?php echo $cnt1; ?>" id="Address_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rstbl_companies['Address']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("Address");?> <?php echo $tNGs->displayFieldError("tbl_companies", "Address", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="ContactName_<?php echo $cnt1; ?>">Contact Name:</label></td>
                      <td><input type="text" name="ContactName_<?php echo $cnt1; ?>" id="ContactName_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_companies['ContactName']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("ContactName");?> <?php echo $tNGs->displayFieldError("tbl_companies", "ContactName", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="ContactNumber_<?php echo $cnt1; ?>">Contact Number:</label></td>
                      <td><input type="text" name="ContactNumber_<?php echo $cnt1; ?>" id="ContactNumber_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_companies['ContactNumber']); ?>" size="32" maxlength="50" />
                          <?php echo $tNGs->displayFieldHint("ContactNumber");?> <?php echo $tNGs->displayFieldError("tbl_companies", "ContactNumber", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="ContactEmail_<?php echo $cnt1; ?>">Contact E-mail:</label></td>
                      <td><input type="text" name="ContactEmail_<?php echo $cnt1; ?>" id="ContactEmail_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_companies['ContactEmail']); ?>" size="32" maxlength="50" />
                          <?php echo $tNGs->displayFieldHint("ContactEmail");?> <?php echo $tNGs->displayFieldError("tbl_companies", "ContactEmail", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="VATNO_<?php echo $cnt1; ?>">VAT No:</label></td>
                      <td><input type="text" name="VATNO_<?php echo $cnt1; ?>" id="VATNO_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_companies['VATNO']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("VATNO");?> <?php echo $tNGs->displayFieldError("tbl_companies", "VATNO", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Prefix_<?php echo $cnt1; ?>">Prefix:</label></td>
                      <td><input type="text" name="Prefix_<?php echo $cnt1; ?>" id="Prefix_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_companies['Prefix']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("Prefix");?> <?php echo $tNGs->displayFieldError("tbl_companies", "Prefix", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_tbl_companies_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_companies['kt_pk_tbl_companies']); ?>" />
                  <?php } while ($row_rstbl_companies = mysql_fetch_assoc($rstbl_companies)); ?>
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
          <p>&nbsp;</p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;
      <div class="KT_tng" id="listtbl_companies1">
        <h1> Companies
          <?php
  $nav_listtbl_companies1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
        </h1>
        <div class="KT_tnglist">
          <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
            <div class="KT_options"> <a href="<?php echo $nav_listtbl_companies1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_companies1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listtbl_companies1']; ?>
                    <?php 
  // else Conditional region2
  } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php } 
  // endif Conditional region2
?>
                  <?php echo NXT_getResource("records"); ?></a> &nbsp;
              &nbsp; </div>
            <table cellpadding="2" cellspacing="0" class="KT_tngtable">
              <thead>
                <tr class="KT_row_order">
                  <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                  </th>
                  <th id="Name" class="KT_sorter KT_col_Name <?php echo $tso_listtbl_companies1->getSortIcon('tbl_companies.Name'); ?>"> <a href="<?php echo $tso_listtbl_companies1->getSortLink('tbl_companies.Name'); ?>">Name</a> </th>
                  <th id="ContactName" class="KT_sorter KT_col_ContactName <?php echo $tso_listtbl_companies1->getSortIcon('tbl_companies.ContactName'); ?>"> <a href="<?php echo $tso_listtbl_companies1->getSortLink('tbl_companies.ContactName'); ?>">Contact Name</a> </th>
                  <th id="ContactNumber" class="KT_sorter KT_col_ContactNumber <?php echo $tso_listtbl_companies1->getSortIcon('tbl_companies.ContactNumber'); ?>"> <a href="<?php echo $tso_listtbl_companies1->getSortLink('tbl_companies.ContactNumber'); ?>">Contact Number</a> </th>
                  <th id="ContactEmail" class="KT_sorter KT_col_ContactEmail <?php echo $tso_listtbl_companies1->getSortIcon('tbl_companies.ContactEmail'); ?>"> <a href="<?php echo $tso_listtbl_companies1->getSortLink('tbl_companies.ContactEmail'); ?>">Contact E-mail</a> </th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($totalRows_rstbl_companies1 == 0) { // Show if recordset empty ?>
                  <tr>
                    <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                  </tr>
                  <?php } // Show if recordset empty ?>
                <?php if ($totalRows_rstbl_companies1 > 0) { // Show if recordset not empty ?>
                  <?php do { ?>
                    <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                      <td><input type="checkbox" name="kt_pk_tbl_companies" class="id_checkbox" value="<?php echo $row_rstbl_companies1['Id']; ?>" />
                          <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_companies1['Id']; ?>" />
                      </td>
                      <td><div class="KT_col_Name"><?php echo KT_FormatForList($row_rstbl_companies1['Name'], 20); ?></div></td>
                      <td><div class="KT_col_ContactName"><?php echo KT_FormatForList($row_rstbl_companies1['ContactName'], 20); ?></div></td>
                      <td><div class="KT_col_ContactNumber"><?php echo KT_FormatForList($row_rstbl_companies1['ContactNumber'], 20); ?></div></td>
                      <td><div class="KT_col_ContactEmail"><?php echo KT_FormatForList($row_rstbl_companies1['ContactEmail'], 20); ?></div></td>
                      <td><a class="KT_edit_link" href="comapanies.php?Id=<?php echo $row_rstbl_companies1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                    </tr>
                    <?php } while ($row_rstbl_companies1 = mysql_fetch_assoc($rstbl_companies1)); ?>
                  <?php } // Show if recordset not empty ?>
              </tbody>
            </table>
            <div class="KT_bottomnav">
              <div>
                <?php
            $nav_listtbl_companies1->Prepare();
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
              <a class="KT_additem_op_link" href="comapanies.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
          </form>
        </div>
        <br class="clearfixplain" />
      </div>
    <p>&nbsp;</p></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstbl_companies1);
?>
