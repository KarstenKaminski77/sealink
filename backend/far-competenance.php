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
$formValidation->addField("CompanyId", true, "numeric", "", "", "", "Required Field");
$formValidation->addField("Competenance", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

// Filter
$tfi_listtbl_far_competence1 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_far_competence1");
$tfi_listtbl_far_competence1->addColumn("tbl_companies.Id", "STRING_TYPE", "CompanyId", "%");
$tfi_listtbl_far_competence1->addColumn("tbl_far_competence.Competenance", "STRING_TYPE", "Competenance", "%");
$tfi_listtbl_far_competence1->Execute();

// Sorter
$tso_listtbl_far_competence1 = new TSO_TableSorter("rstbl_far_competence1", "tso_listtbl_far_competence1");
$tso_listtbl_far_competence1->addColumn("tbl_companies.Name");
$tso_listtbl_far_competence1->addColumn("tbl_far_competence.Competenance");
$tso_listtbl_far_competence1->setDefault("tbl_far_competence.CompanyId");
$tso_listtbl_far_competence1->Execute();

// Navigation
$nav_listtbl_far_competence1 = new NAV_Regular("nav_listtbl_far_competence1", "rstbl_far_competence1", "../", $_SERVER['PHP_SELF'], 50);

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_jc WHERE JobNo = 'JobNo'";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT Name, Id FROM tbl_companies ORDER BY Name";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT * FROM tbl_far_risc_classification";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_far_competence1 = $_SESSION['max_rows_nav_listtbl_far_competence1'];
$pageNum_rstbl_far_competence1 = 0;
if (isset($_GET['pageNum_rstbl_far_competence1'])) {
  $pageNum_rstbl_far_competence1 = $_GET['pageNum_rstbl_far_competence1'];
}
$startRow_rstbl_far_competence1 = $pageNum_rstbl_far_competence1 * $maxRows_rstbl_far_competence1;

$NXTFilter_rstbl_far_competence1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_far_competence1'])) {
  $NXTFilter_rstbl_far_competence1 = $_SESSION['filter_tfi_listtbl_far_competence1'];
}
$NXTSort_rstbl_far_competence1 = "tbl_far_competence.CompanyId";
if (isset($_SESSION['sorter_tso_listtbl_far_competence1'])) {
  $NXTSort_rstbl_far_competence1 = $_SESSION['sorter_tso_listtbl_far_competence1'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_far_competence1 = sprintf("SELECT tbl_companies.Name AS CompanyId, tbl_far_competence.Competenance, tbl_far_competence.Id FROM tbl_far_competence LEFT JOIN tbl_companies ON tbl_far_competence.CompanyId = tbl_companies.Id WHERE %s ORDER BY %s", $NXTFilter_rstbl_far_competence1, $NXTSort_rstbl_far_competence1);
$query_limit_rstbl_far_competence1 = sprintf("%s LIMIT %d, %d", $query_rstbl_far_competence1, $startRow_rstbl_far_competence1, $maxRows_rstbl_far_competence1);
$rstbl_far_competence1 = mysql_query($query_limit_rstbl_far_competence1, $seavest) or die(mysql_error());
$row_rstbl_far_competence1 = mysql_fetch_assoc($rstbl_far_competence1);

if (isset($_GET['totalRows_rstbl_far_competence1'])) {
  $totalRows_rstbl_far_competence1 = $_GET['totalRows_rstbl_far_competence1'];
} else {
  $all_rstbl_far_competence1 = mysql_query($query_rstbl_far_competence1);
  $totalRows_rstbl_far_competence1 = mysql_num_rows($all_rstbl_far_competence1);
}
$totalPages_rstbl_far_competence1 = ceil($totalRows_rstbl_far_competence1/$maxRows_rstbl_far_competence1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_far_competence = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_far_competence);
// Register triggers
$ins_tbl_far_competence->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_far_competence->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_far_competence->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_far_competence->setTable("tbl_far_competence");
$ins_tbl_far_competence->addColumn("CompanyId", "NUMERIC_TYPE", "POST", "CompanyId");
$ins_tbl_far_competence->addColumn("RiskId", "NUMERIC_TYPE", "POST", "RiskId");
$ins_tbl_far_competence->addColumn("Competenance", "STRING_TYPE", "POST", "Competenance");
$ins_tbl_far_competence->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_far_competence = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_far_competence);
// Register triggers
$upd_tbl_far_competence->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_far_competence->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_far_competence->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_far_competence->setTable("tbl_far_competence");
$upd_tbl_far_competence->addColumn("CompanyId", "NUMERIC_TYPE", "POST", "CompanyId");
$upd_tbl_far_competence->addColumn("RiskId", "NUMERIC_TYPE", "POST", "RiskId");
$upd_tbl_far_competence->addColumn("Competenance", "STRING_TYPE", "POST", "Competenance");
$upd_tbl_far_competence->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_far_competence = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_far_competence);
// Register triggers
$del_tbl_far_competence->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_far_competence->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_far_competence->setTable("tbl_far_competence");
$del_tbl_far_competence->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_far_competence = $tNGs->getRecordset("tbl_far_competence");
$row_rstbl_far_competence = mysql_fetch_assoc($rstbl_far_competence);
$totalRows_rstbl_far_competence = mysql_num_rows($rstbl_far_competence);

$nav_listtbl_far_competence1->checkBoundries();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
  .KT_col_CompanyId {width:140px; overflow:hidden;}
  .KT_col_Competenance {width:140px; overflow:hidden;}
</style>
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
    FAR Competence </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_far_competence > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="CompanyId_<?php echo $cnt1; ?>">Company:</label></td>
            <td><select name="CompanyId_<?php echo $cnt1; ?>" id="CompanyId_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_Recordset2['Id']?>"<?php if (!(strcmp($row_Recordset2['Id'], $row_rstbl_far_competence['CompanyId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['Name']?></option>
                <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("tbl_far_competence", "CompanyId", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="RiskId_<?php echo $cnt1; ?>">Risk:</label></td>
            <td><select name="RiskId_<?php echo $cnt1; ?>" id="RiskId_<?php echo $cnt1; ?>">
                <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <?php 
do {  
?>
                <option value="<?php echo $row_Recordset4['Id']?>"<?php if (!(strcmp($row_Recordset4['Id'], $row_rstbl_far_competence['RiskId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['Risk']?></option>
                <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
              </select>
                <?php echo $tNGs->displayFieldError("tbl_far_competence", "RiskId", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Competenance_<?php echo $cnt1; ?>">Competenance:</label></td>
            <td><input type="text" name="Competenance_<?php echo $cnt1; ?>" id="Competenance_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_far_competence['Competenance']); ?>" size="32" />
                <?php echo $tNGs->displayFieldHint("Competenance");?> <?php echo $tNGs->displayFieldError("tbl_far_competence", "Competenance", $cnt1); ?> </td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_tbl_far_competence_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_far_competence['kt_pk_tbl_far_competence']); ?>" />
        <?php } while ($row_rstbl_far_competence = mysql_fetch_assoc($rstbl_far_competence)); ?>
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
<div class="KT_tng" id="listtbl_far_competence1">
  <h1> FAR Competence
    <?php
  $nav_listtbl_far_competence1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
      <div class="KT_options"> <a href="<?php echo $nav_listtbl_far_competence1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
            <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_far_competence1'] == 1) {
?>
              <?php echo $_SESSION['default_max_rows_nav_listtbl_far_competence1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_far_competence1'] == 1) {
?>
                              <a href="<?php echo $tfi_listtbl_far_competence1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                              <?php 
  // else Conditional region2
  } else { ?>
                              <a href="<?php echo $tfi_listtbl_far_competence1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                              <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="CompanyId" class="KT_sorter KT_col_CompanyId <?php echo $tso_listtbl_far_competence1->getSortIcon('tbl_companies.Name'); ?>"> <a href="<?php echo $tso_listtbl_far_competence1->getSortLink('tbl_companies.Name'); ?>">Company</a> </th>
            <th id="Competenance" class="KT_sorter KT_col_Competenance <?php echo $tso_listtbl_far_competence1->getSortIcon('tbl_far_competence.Competenance'); ?>"> <a href="<?php echo $tso_listtbl_far_competence1->getSortLink('tbl_far_competence.Competenance'); ?>">Competenance</a> </th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_far_competence1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listtbl_far_competence1_CompanyId" id="tfi_listtbl_far_competence1_CompanyId">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listtbl_far_competence1_CompanyId']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset3['Id']?>"<?php if (!(strcmp($row_Recordset3['Id'], @$_SESSION['tfi_listtbl_far_competence1_CompanyId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['Name']?></option>
                <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
              </select>
              </td>
              <td><input type="text" name="tfi_listtbl_far_competence1_Competenance" id="tfi_listtbl_far_competence1_Competenance" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_far_competence1_Competenance']); ?>" size="20" maxlength="100" /></td>
              <td><input type="submit" name="tfi_listtbl_far_competence1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rstbl_far_competence1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rstbl_far_competence1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_tbl_far_competence" class="id_checkbox" value="<?php echo $row_rstbl_far_competence1['Id']; ?>" />
                    <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_far_competence1['Id']; ?>" />
                </td>
                <td><div class="KT_col_CompanyId"><?php echo KT_FormatForList($row_rstbl_far_competence1['CompanyId'], 20); ?></div></td>
                <td><div class="KT_col_Competenance"><?php echo KT_FormatForList($row_rstbl_far_competence1['Competenance'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="far-competenance.php?Id=<?php echo $row_rstbl_far_competence1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
              </tr>
              <?php } while ($row_rstbl_far_competence1 = mysql_fetch_assoc($rstbl_far_competence1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listtbl_far_competence1->Prepare();
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
        <a class="KT_additem_op_link" href="far-competenance.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($rstbl_far_competence1);
?>
