<?php require_once('../Connections/seavest.php'); ?>
<?php require_once('../Connections/inv.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

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
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_inv = new KT_connection($inv, $database_inv);

// Make unified connection variable
$conn_inv = new KT_connection($inv, $database_inv);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("Username", true, "text", "", "", "", "Required Field");
$formValidation->addField("Password", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_Default_ManyToMany trigger
//remove this line if you want to edit the code by hand 
function Trigger_Default_ManyToMany(&$tNG) {
  $mtm = new tNG_ManyToMany($tNG);
  $mtm->setTable("tbl_menu_relation");
  $mtm->setPkName("UserId");
  $mtm->setFkName("MenuId");
  $mtm->setFkReference("mtm");
  return $mtm->Execute();
}
//end Trigger_Default_ManyToMany trigger

//start Trigger_Default_ManyToMany1 trigger
//remove this line if you want to edit the code by hand 
function Trigger_Default_ManyToMany1(&$tNG) {
  $mtm = new tNG_ManyToMany($tNG);
  $mtm->setTable("tbl_area_relation");
  $mtm->setPkName("UserId");
  $mtm->setFkName("AreaId");
  $mtm->setFkReference("mtm1");
  return $mtm->Execute();
}
//end Trigger_Default_ManyToMany1 trigger

// Filter
$tfi_listtbl_users1 = new TFI_TableFilter($conn_inv, "tfi_listtbl_users1");
$tfi_listtbl_users1->addColumn("tbl_users.Username", "STRING_TYPE", "Username", "%");
$tfi_listtbl_users1->addColumn("tbl_users.Password", "STRING_TYPE", "Password", "%");
$tfi_listtbl_users1->Execute();

// Sorter
$tso_listtbl_users1 = new TSO_TableSorter("rstbl_users1", "tso_listtbl_users1");
$tso_listtbl_users1->addColumn("tbl_users.Username");
$tso_listtbl_users1->addColumn("tbl_users.Password");
$tso_listtbl_users1->setDefault("tbl_users.Username");
$tso_listtbl_users1->Execute();

// Navigation
$nav_listtbl_users1 = new NAV_Regular("nav_listtbl_users1", "rstbl_users1", "", $_SERVER['PHP_SELF'], 20);

//start Trigger_DeleteDetail1 trigger
//remove this line if you want to edit the code by hand
function Trigger_DeleteDetail1(&$tNG) {
  $tblDelObj = new tNG_DeleteDetailRec($tNG);
  $tblDelObj->setTable("tbl_area_relation");
  $tblDelObj->setFieldName("UserId");
  return $tblDelObj->Execute();
}
//end Trigger_DeleteDetail1 trigger

//start Trigger_DeleteDetail trigger
//remove this line if you want to edit the code by hand
function Trigger_DeleteDetail(&$tNG) {
  $tblDelObj = new tNG_DeleteDetailRec($tNG);
  $tblDelObj->setTable("tbl_menu_relation");
  $tblDelObj->setFieldName("UserId");
  return $tblDelObj->Execute();
}
//end Trigger_DeleteDetail trigger

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

mysql_select_db($database_inv, $inv);
$query_rstbl_menu_items = "SELECT tbl_menu_items.Id, tbl_menu_items.Menu, tbl_menu_items.Backend, tbl_menu_items.CategoryId, tbl_menu_relation.UserId FROM tbl_menu_items LEFT JOIN tbl_menu_relation ON (tbl_menu_relation.MenuId=tbl_menu_items.Id AND tbl_menu_relation.UserId=0123456789) ORDER BY tbl_menu_items.CategoryId ASC";
$rstbl_menu_items = mysql_query($query_rstbl_menu_items, $inv) or die(mysql_error());
$row_rstbl_menu_items = mysql_fetch_assoc($rstbl_menu_items);
$totalRows_rstbl_menu_items = mysql_num_rows($rstbl_menu_items);

mysql_select_db($database_inv, $inv);
$query_rstbl_areas = "SELECT tbl_areas.Id, tbl_areas.Area, tbl_area_relation.UserId FROM tbl_areas LEFT JOIN tbl_area_relation ON (tbl_area_relation.AreaId=tbl_areas.Id AND tbl_area_relation.UserId=0123456789)";
$rstbl_areas = mysql_query($query_rstbl_areas, $inv) or die(mysql_error());
$row_rstbl_areas = mysql_fetch_assoc($rstbl_areas);
$totalRows_rstbl_areas = mysql_num_rows($rstbl_areas);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_users1 = $_SESSION['max_rows_nav_listtbl_users1'];
$pageNum_rstbl_users1 = 0;
if (isset($_GET['pageNum_rstbl_users1'])) {
  $pageNum_rstbl_users1 = $_GET['pageNum_rstbl_users1'];
}
$startRow_rstbl_users1 = $pageNum_rstbl_users1 * $maxRows_rstbl_users1;

$NXTFilter_rstbl_users1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_users1'])) {
  $NXTFilter_rstbl_users1 = $_SESSION['filter_tfi_listtbl_users1'];
}
$NXTSort_rstbl_users1 = "tbl_users.Username";
if (isset($_SESSION['sorter_tso_listtbl_users1'])) {
  $NXTSort_rstbl_users1 = $_SESSION['sorter_tso_listtbl_users1'];
}
mysql_select_db($database_inv, $inv);

$query_rstbl_users1 = sprintf("SELECT tbl_users.Username, tbl_users.Password, tbl_users.Id FROM tbl_users WHERE %s ORDER BY %s", $NXTFilter_rstbl_users1, $NXTSort_rstbl_users1);
$query_limit_rstbl_users1 = sprintf("%s LIMIT %d, %d", $query_rstbl_users1, $startRow_rstbl_users1, $maxRows_rstbl_users1);
$rstbl_users1 = mysql_query($query_limit_rstbl_users1, $inv) or die(mysql_error());
$row_rstbl_users1 = mysql_fetch_assoc($rstbl_users1);

if (isset($_GET['totalRows_rstbl_users1'])) {
  $totalRows_rstbl_users1 = $_GET['totalRows_rstbl_users1'];
} else {
  $all_rstbl_users1 = mysql_query($query_rstbl_users1);
  $totalRows_rstbl_users1 = mysql_num_rows($all_rstbl_users1);
}
$totalPages_rstbl_users1 = ceil($totalRows_rstbl_users1/$maxRows_rstbl_users1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_users = new tNG_multipleInsert($conn_inv);
$tNGs->addTransaction($ins_tbl_users);
// Register triggers
$ins_tbl_users->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_users->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_users->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$ins_tbl_users->registerTrigger("AFTER", "Trigger_Default_ManyToMany", 50);
$ins_tbl_users->registerTrigger("AFTER", "Trigger_Default_ManyToMany1", 50);
// Add columns
$ins_tbl_users->setTable("tbl_users");
$ins_tbl_users->addColumn("Name", "STRING_TYPE", "POST", "Name");
$ins_tbl_users->addColumn("Username", "STRING_TYPE", "POST", "Username");
$ins_tbl_users->addColumn("Password", "STRING_TYPE", "POST", "Password");
$ins_tbl_users->addColumn("UserLevel", "STRING_TYPE", "POST", "UserLevel");
$ins_tbl_users->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_users = new tNG_multipleUpdate($conn_inv);
$tNGs->addTransaction($upd_tbl_users);
// Register triggers
$upd_tbl_users->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_users->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_users->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$upd_tbl_users->registerTrigger("AFTER", "Trigger_Default_ManyToMany", 50);
$upd_tbl_users->registerTrigger("AFTER", "Trigger_Default_ManyToMany1", 50);
// Add columns
$upd_tbl_users->setTable("tbl_users");
$upd_tbl_users->addColumn("Name", "STRING_TYPE", "POST", "Name");
$upd_tbl_users->addColumn("Username", "STRING_TYPE", "POST", "Username");
$upd_tbl_users->addColumn("Password", "STRING_TYPE", "POST", "Password");
$upd_tbl_users->addColumn("UserLevel", "STRING_TYPE", "POST", "UserLevel");
$upd_tbl_users->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_users = new tNG_multipleDelete($conn_inv);
$tNGs->addTransaction($del_tbl_users);
// Register triggers
$del_tbl_users->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_users->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$del_tbl_users->registerTrigger("BEFORE", "Trigger_DeleteDetail", 99);
$del_tbl_users->registerTrigger("BEFORE", "Trigger_DeleteDetail1", 99);
// Add columns
$del_tbl_users->setTable("tbl_users");
$del_tbl_users->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_users = $tNGs->getRecordset("tbl_users");
$row_rstbl_users = mysql_fetch_assoc($rstbl_users);
$totalRows_rstbl_users = mysql_num_rows($rstbl_users);

$nav_listtbl_users1->checkBoundries();
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
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
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
  .KT_col_Username {width:140px; overflow:hidden;}
  .KT_col_Password {width:140px; overflow:hidden;}
  .KT_th {
    background-color:#689CCE;
}

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
            <?php
	echo $tNGs->getErrorMsg();
?>
          <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<div>
<div class="KT_tngform">
              <form method="post" id="form2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_users > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td><label for="Name_<?php echo $cnt1; ?>">Name:</label></td>
                      <td><input type="text" name="Name_<?php echo $cnt1; ?>" id="Name_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_users['Name']); ?>" size="30" />
                        <?php echo $tNGs->displayFieldHint("Name");?> <?php echo $tNGs->displayFieldError("tbl_users", "Name", $cnt1); ?></td>
                    </tr>
                    <tr>
                      <td class=class="KT_th" style="background-color:#689CCE;"><label for="Username_<?php echo $cnt1; ?>">Username:</label></td>
                      <td><input type="text" name="Username_<?php echo $cnt1; ?>" id="Username_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_users['Username']); ?>" size="30" maxlength="30" />
                        <?php echo $tNGs->displayFieldHint("Username");?> <?php echo $tNGs->displayFieldError("tbl_users", "Username", $cnt1); ?></td>
                      <td rowspan="3" valign="top" class=class="KT_th" style="background-color:#689CCE;"><label>Areas:</label></td>
                      <td rowspan="3" valign="top"><table border="0" class="KT_mtm">
                        <tr>
                          <?php
  $cnt3 = 0;
?>
                          <?php
  if ($totalRows_rstbl_users>0) {
    $nested_query_rstbl_areas = str_replace("123456789", $row_rstbl_users['Id'], $query_rstbl_areas);
    mysql_select_db($database_inv);
    $rstbl_areas = mysql_query($nested_query_rstbl_areas, $inv) or die(mysql_error());
    $row_rstbl_areas = mysql_fetch_assoc($rstbl_areas);
    $totalRows_rstbl_areas = mysql_num_rows($rstbl_areas);
    $nested_sw = false;
    if (isset($row_rstbl_areas) && is_array($row_rstbl_areas)) {
      do { //Nested repeat
?>
                          <td><input id="mtm1_<?php echo $row_rstbl_areas['Id']; ?>_<?php echo $cnt1; ?>" name="mtm1_<?php echo $row_rstbl_areas['Id']; ?>_<?php echo $cnt1; ?>" type="checkbox" value="1" <?php if ($row_rstbl_areas['UserId'] != "") {?> checked<?php }?>></td>
                          <td><label for="mtm1_<?php echo $row_rstbl_areas['Id']; ?>_<?php echo $cnt1; ?>"><?php echo $row_rstbl_areas['Area']; ?></label></td>
                          <?php
	$cnt3++;
	if ($cnt3%1 == 0) {
		echo "</tr><tr>";
	}
?>
                          <?php
      } while ($row_rstbl_areas = mysql_fetch_assoc($rstbl_areas)); //Nested move next
    }
  }
?>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td class=class="KT_th" style="background-color:#689CCE;"><label for="Password_<?php echo $cnt1; ?>">Password:</label></td>
                      <td><input type="text" name="Password_<?php echo $cnt1; ?>" id="Password_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_users['Password']); ?>" size="30" maxlength="30" />
                        <?php echo $tNGs->displayFieldHint("Password");?> <?php echo $tNGs->displayFieldError("tbl_users", "Password", $cnt1); ?></td>
                    </tr>
                    <tr>
                      <td class=class="KT_th" style="background-color:#689CCE;"><label for="UserLevel_<?php echo $cnt1; ?>">SLA:</label></td>
                      <td><select name="UserLevel_<?php echo $cnt1; ?>" id="UserLevel_<?php echo $cnt1; ?>">
                        <option>Select one...</option>
                        <option value="3" <?php if (!(strcmp(3, KT_escapeAttribute($row_rstbl_users['UserLevel'])))) {echo "SELECTED";} ?>>On</option>
                        <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rstbl_users['UserLevel'])))) {echo "SELECTED";} ?>>Off</option>
                      </select>
                        <?php echo $tNGs->displayFieldError("tbl_users", "UserLevel", $cnt1); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" nowrap class=class="KT_th" style="background-color:#689CCE;"><label>Menu Items:</label></td>
                      <td><table border="0" class="KT_mtm">
                        <tr>
                          <?php
  $cnt2 = 0;
?>
                          <?php
  if ($totalRows_rstbl_users>0) {
    $nested_query_rstbl_menu_items = str_replace("123456789", $row_rstbl_users['Id'], $query_rstbl_menu_items);
    mysql_select_db($database_inv);
    $rstbl_menu_items = mysql_query($nested_query_rstbl_menu_items, $inv) or die(mysql_error());
    $row_rstbl_menu_items = mysql_fetch_assoc($rstbl_menu_items);
    $totalRows_rstbl_menu_items = mysql_num_rows($rstbl_menu_items);
    $nested_sw = false;
    if (isset($row_rstbl_menu_items) && is_array($row_rstbl_menu_items)) {
      do { //Nested repeat
?>
                          <td><input id="mtm_<?php echo $row_rstbl_menu_items['Id']; ?>_<?php echo $cnt1; ?>" name="mtm_<?php echo $row_rstbl_menu_items['Id']; ?>_<?php echo $cnt1; ?>" type="checkbox" value="1" <?php if ($row_rstbl_menu_items['UserId'] != "") {?> checked<?php }?>></td>
                          <td><label for="mtm_<?php echo $row_rstbl_menu_items['Id']; ?>_<?php echo $cnt1; ?>">
                            <?php 
							  if(($row_rstbl_menu_items['CategoryId'] == 1) || ($row_rstbl_menu_items['CategoryId'] == 2) || ($row_rstbl_menu_items['CategoryId'] == 7) || ($row_rstbl_menu_items['CategoryId'] == 8) || ($row_rstbl_menu_items['CategoryId'] == 11) || ($row_rstbl_menu_items['CategoryId'] == 12)){
							  echo $row_rstbl_menu_items['Backend']; 
							  } else {
							  echo $row_rstbl_menu_items['Menu'];
							  }
							  ?>
                          </label></td>
                          <?php
	$cnt2++;
	if ($cnt2%1 == 0) {
		echo "</tr><tr>";
	}
?>
                          <?php
      } while ($row_rstbl_menu_items = mysql_fetch_assoc($rstbl_menu_items)); //Nested move next
    }
  }
?>
                        </tr>
                      </table></td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_tbl_users_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_users['kt_pk_tbl_users']); ?>" />
                  <?php } while ($row_rstbl_users = mysql_fetch_assoc($rstbl_users)); ?>
                <div class="KT_bottombuttons" style="background-color:#689CCE;">
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
          </div>	</td>
  </tr>
</table>

          <p> 
          <table border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td><div class="KT_tng" id="listtbl_users1">
                <div class="KT_tnglist">
                  <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form3">
                    <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                      <thead>
                        <tr class="KT_row_order">
                          <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                          </th>
                          <th id="Username" class="KT_sorter KT_col_Username <?php echo $tso_listtbl_users1->getSortIcon('tbl_users.Username'); ?>"> <a href="<?php echo $tso_listtbl_users1->getSortLink('tbl_users.Username'); ?>">Username</a> </th>
                          <th id="Password" class="KT_sorter KT_col_Password <?php echo $tso_listtbl_users1->getSortIcon('tbl_users.Password'); ?>"> <a href="<?php echo $tso_listtbl_users1->getSortLink('tbl_users.Password'); ?>">Password</a> </th>
                          <th>&nbsp;</th>
                        </tr>
                        <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_users1'] == 1) {
?>
                        <tr class="KT_row_filter">
                          <td>&nbsp;</td>
                          <td><input type="text" name="tfi_listtbl_users1_Username" id="tfi_listtbl_users1_Username" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_users1_Username']); ?>" size="20" maxlength="30" /></td>
                          <td><input type="text" name="tfi_listtbl_users1_Password" id="tfi_listtbl_users1_Password" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_users1_Password']); ?>" size="20" maxlength="30" /></td>
                          <td><input type="submit" name="tfi_listtbl_users1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                        </tr>
                        <?php } 
  // endif Conditional region3
?>
                      </thead>
                      <tbody>
                        <?php if ($totalRows_rstbl_users1 == 0) { // Show if recordset empty ?>
                        <tr>
                          <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                        </tr>
                        <?php } // Show if recordset empty ?>
                        <?php if ($totalRows_rstbl_users1 > 0) { // Show if recordset not empty ?>
                        <?php do { ?>
                        <tr class="<?php echo @$cnt4++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_tbl_users" class="id_checkbox" value="<?php echo $row_rstbl_users1['Id']; ?>" />
                              <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_users1['Id']; ?>" />
                          </td>
                          <td><div class="KT_col_Username"><?php echo KT_FormatForList($row_rstbl_users1['Username'], 20); ?></div></td>
                          <td><div class="KT_col_Password"><?php echo KT_FormatForList($row_rstbl_users1['Password'], 20); ?></div></td>
                          <td><a class="KT_edit_link" href="user-accounts.php?Id=<?php echo $row_rstbl_users1['Id']; ?>&KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                        </tr>
                        <?php } while ($row_rstbl_users1 = mysql_fetch_assoc($rstbl_users1)); ?>
                        <?php } // Show if recordset not empty ?>
                      </tbody>
                    </table>
                    <div class="KT_bottomnav">
                      <div>
                        <?php
            $nav_listtbl_users1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
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
                    <a class="KT_additem_op_link" href="user-accounts.php?KT_back=1" onClick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
                  </form>
                </div>
                <br class="clearfixplain" />
              </div></td>
            </tr>
          </table>
          <p>&nbsp;</p>
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
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($rstbl_menu_items);

mysql_free_result($rstbl_areas);

mysql_free_result($rstbl_users1);
?>
