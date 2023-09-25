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
$tfi_listtbl_emergency1 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_emergency1");
$tfi_listtbl_emergency1->addColumn("tbl_areas.Id", "STRING_TYPE", "AreaId", "%");
$tfi_listtbl_emergency1->addColumn("tbl_companies.Id", "STRING_TYPE", "CompanyId", "%");
$tfi_listtbl_emergency1->addColumn("tbl_sites.Id", "STRING_TYPE", "SiteId", "%");
$tfi_listtbl_emergency1->addColumn("DateSubmitted", "STRING_TYPE", "DateSubmitted", "%");
$tfi_listtbl_emergency1->Execute();

// Sorter
$tso_listtbl_emergency1 = new TSO_TableSorter("rstbl_emergency1", "tso_listtbl_emergency1");
$tso_listtbl_emergency1->addColumn("tbl_areas.Area");
$tso_listtbl_emergency1->addColumn("tbl_companies.Name");
$tso_listtbl_emergency1->addColumn("tbl_sites.Name");
$tso_listtbl_emergency1->addColumn("DateSubmitted");
$tso_listtbl_emergency1->setDefault("tbl_emergency.AreaId");
$tso_listtbl_emergency1->Execute();

// Navigation
$nav_listtbl_emergency1 = new NAV_Regular("nav_listtbl_emergency1", "rstbl_emergency1", "../", $_SERVER['PHP_SELF'], 50);

$KTColParam1_Recordset1 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset1 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT tbl_sites.Name AS Name_1, tbl_companies.Name, tbl_emergency.Id, tbl_emergency.Description, tbl_emergency.Cell, tbl_emergency.Email, tbl_emergency.DateSubmitted, tbl_areas.Area, tbl_emergency.Requestor FROM (((tbl_emergency LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_emergency.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_emergency.SiteId) LEFT JOIN tbl_areas ON tbl_areas.Id=tbl_emergency.AreaId) WHERE tbl_emergency.Id=%s ", $KTColParam1_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT Area, Id FROM tbl_areas ORDER BY Area";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT Name, Id FROM tbl_companies ORDER BY Name";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT Name, Id FROM tbl_sites ORDER BY Name";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_emergency1 = $_SESSION['max_rows_nav_listtbl_emergency1'];
$pageNum_rstbl_emergency1 = 0;
if (isset($_GET['pageNum_rstbl_emergency1'])) {
  $pageNum_rstbl_emergency1 = $_GET['pageNum_rstbl_emergency1'];
}
$startRow_rstbl_emergency1 = $pageNum_rstbl_emergency1 * $maxRows_rstbl_emergency1;

$NXTFilter_rstbl_emergency1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_emergency1'])) {
  $NXTFilter_rstbl_emergency1 = $_SESSION['filter_tfi_listtbl_emergency1'];
}
$NXTSort_rstbl_emergency1 = "tbl_emergency.AreaId";
if (isset($_SESSION['sorter_tso_listtbl_emergency1'])) {
  $NXTSort_rstbl_emergency1 = $_SESSION['sorter_tso_listtbl_emergency1'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_emergency1 = sprintf("SELECT tbl_areas.Area AS AreaId, tbl_companies.Name AS CompanyId, tbl_sites.Name AS SiteId, tbl_emergency.Requestor,tbl_emergency.DateSubmitted, tbl_emergency.Id FROM ((tbl_emergency LEFT JOIN tbl_areas ON tbl_emergency.AreaId = tbl_areas.Id) LEFT JOIN tbl_companies ON tbl_emergency.CompanyId = tbl_companies.Id) LEFT JOIN tbl_sites ON tbl_emergency.SiteId = tbl_sites.Id WHERE %s ORDER BY %s", $NXTFilter_rstbl_emergency1, $NXTSort_rstbl_emergency1);
$query_limit_rstbl_emergency1 = sprintf("%s LIMIT %d, %d", $query_rstbl_emergency1, $startRow_rstbl_emergency1, $maxRows_rstbl_emergency1);
$rstbl_emergency1 = mysql_query($query_limit_rstbl_emergency1, $seavest) or die(mysql_error());
$row_rstbl_emergency1 = mysql_fetch_assoc($rstbl_emergency1);

if (isset($_GET['totalRows_rstbl_emergency1'])) {
  $totalRows_rstbl_emergency1 = $_GET['totalRows_rstbl_emergency1'];
} else {
  $all_rstbl_emergency1 = mysql_query($query_rstbl_emergency1);
  $totalRows_rstbl_emergency1 = mysql_num_rows($all_rstbl_emergency1);
}
$totalPages_rstbl_emergency1 = ceil($totalRows_rstbl_emergency1/$maxRows_rstbl_emergency1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_emergency = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_emergency);
// Register triggers
$ins_tbl_emergency->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_emergency->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_emergency->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_emergency->setTable("tbl_emergency");
$ins_tbl_emergency->addColumn("AreaId", "NUMERIC_TYPE", "VALUE", "");
$ins_tbl_emergency->addColumn("CompanyId", "NUMERIC_TYPE", "VALUE", "");
$ins_tbl_emergency->addColumn("SiteId", "NUMERIC_TYPE", "POST", "SiteId");
$ins_tbl_emergency->addColumn("Description", "STRING_TYPE", "POST", "Description");
$ins_tbl_emergency->addColumn("Requestor", "STRING_TYPE", "POST", "Requestor");
$ins_tbl_emergency->addColumn("Cell", "STRING_TYPE", "POST", "Cell");
$ins_tbl_emergency->addColumn("Email", "STRING_TYPE", "POST", "Email");
$ins_tbl_emergency->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_emergency = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_emergency);
// Register triggers
$upd_tbl_emergency->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_emergency->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_emergency->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_emergency->setTable("tbl_emergency");
$upd_tbl_emergency->addColumn("AreaId", "NUMERIC_TYPE", "CURRVAL", "");
$upd_tbl_emergency->addColumn("CompanyId", "NUMERIC_TYPE", "CURRVAL", "");
$upd_tbl_emergency->addColumn("SiteId", "NUMERIC_TYPE", "POST", "SiteId");
$upd_tbl_emergency->addColumn("Description", "STRING_TYPE", "POST", "Description");
$upd_tbl_emergency->addColumn("Requestor", "STRING_TYPE", "POST", "Requestor");
$upd_tbl_emergency->addColumn("Cell", "STRING_TYPE", "POST", "Cell");
$upd_tbl_emergency->addColumn("Email", "STRING_TYPE", "POST", "Email");
$upd_tbl_emergency->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_emergency = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_emergency);
// Register triggers
$del_tbl_emergency->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_emergency->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_emergency->setTable("tbl_emergency");
$del_tbl_emergency->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_emergency = $tNGs->getRecordset("tbl_emergency");
$row_rstbl_emergency = mysql_fetch_assoc($rstbl_emergency);
$totalRows_rstbl_emergency = mysql_num_rows($rstbl_emergency);

$nav_listtbl_emergency1->checkBoundries();
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
  .KT_col_AreaId {width:140px; overflow:hidden;}
  .KT_col_CompanyId {width:140px; overflow:hidden;}
  .KT_col_SiteId {width:140px; overflow:hidden;}
  .KT_col_DateSubmitted {width:140px; overflow:hidden;}
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
    Seavest Emergency Reactive Service </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_emergency > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table width="95%" cellpadding="5" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="23%" class="KT_th">Area:</td>
            <td width="77%"><div style="padding-left:2px"><?php echo $row_Recordset1['Area']; ?></div></td>
          </tr>
          <tr>
            <td class="KT_th">Company:</td>
            <td><div style="padding-left:5px"><?php echo $row_Recordset1['Name']; ?></div></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="SiteId_<?php echo $cnt1; ?>">SiteId:</label></td>
            <td><div style="padding-left:5px"><?php echo $row_Recordset1['Name_1']; ?></div></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Description_<?php echo $cnt1; ?>">Description:</label></td>
            <td><div style="padding-left:5px"><?php echo $row_Recordset1['Description']; ?></div></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Requestor_<?php echo $cnt1; ?>">Requestor:</label></td>
            <td><div style="padding-left:5px"><?php echo $row_Recordset1['Requestor']; ?></div></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Cell_<?php echo $cnt1; ?>">Cell:</label></td>
            <td><div style="padding-left:5px"><?php echo $row_Recordset1['Cell']; ?></div></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Email_<?php echo $cnt1; ?>">Email:</label></td>
            <td><div style="padding-left:5px"><?php echo $row_Recordset1['Email']; ?></div></td>
          </tr>
          <tr>
            <td class="KT_th">Date:</td>
            <td><div style="padding-left:5px"><?php echo $row_Recordset1['DateSubmitted']; ?></div></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_tbl_emergency_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_emergency['kt_pk_tbl_emergency']); ?>" />
        <?php } while ($row_rstbl_emergency = mysql_fetch_assoc($rstbl_emergency)); ?>
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
<p>&nbsp;</p>
</p>
<div class="KT_tng" id="listtbl_emergency1">
  <h1> Tbl_emergency
    <?php
  $nav_listtbl_emergency1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
      <div class="KT_options"> <a href="<?php echo $nav_listtbl_emergency1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
            <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_emergency1'] == 1) {
?>
              <?php echo $_SESSION['default_max_rows_nav_listtbl_emergency1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_emergency1'] == 1) {
?>
                              <a href="<?php echo $tfi_listtbl_emergency1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                              <?php 
  // else Conditional region2
  } else { ?>
                              <a href="<?php echo $tfi_listtbl_emergency1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                              <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>            </th>
            <th id="AreaId" class="KT_sorter KT_col_AreaId <?php echo $tso_listtbl_emergency1->getSortIcon('tbl_areas.Area'); ?>"> <a href="<?php echo $tso_listtbl_emergency1->getSortLink('tbl_areas.Area'); ?>">Area</a> </th>
            <th id="CompanyId" class="KT_sorter KT_col_CompanyId <?php echo $tso_listtbl_emergency1->getSortIcon('tbl_companies.Name'); ?>"> <a href="<?php echo $tso_listtbl_emergency1->getSortLink('tbl_companies.Name'); ?>">Company</a> </th>
            <th id="SiteId" class="KT_sorter KT_col_SiteId <?php echo $tso_listtbl_emergency1->getSortIcon('tbl_sites.Name'); ?>"> <a href="<?php echo $tso_listtbl_emergency1->getSortLink('tbl_sites.Name'); ?>">Site</a> </th>
            <th id="DateSubmitted" class="KT_sorter KT_col_DateSubmitted <?php echo $tso_listtbl_emergency1->getSortIcon('DateSubmitted'); ?>"> <a href="<?php echo $tso_listtbl_emergency1->getSortLink('DateSubmitted'); ?>">Date</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_emergency1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><select name="tfi_listtbl_emergency1_AreaId" id="tfi_listtbl_emergency1_AreaId">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listtbl_emergency1_AreaId']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset2['Id']?>"<?php if (!(strcmp($row_Recordset2['Id'], @$_SESSION['tfi_listtbl_emergency1_AreaId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['Area']?></option>
                <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
              </select>              </td>
              <td><select name="tfi_listtbl_emergency1_CompanyId" id="tfi_listtbl_emergency1_CompanyId">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listtbl_emergency1_CompanyId']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset3['Id']?>"<?php if (!(strcmp($row_Recordset3['Id'], @$_SESSION['tfi_listtbl_emergency1_CompanyId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['Name']?></option>
                <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
              </select>              </td>
              <td><select name="tfi_listtbl_emergency1_SiteId" id="tfi_listtbl_emergency1_SiteId">
                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listtbl_emergency1_SiteId']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset4['Id']?>"<?php if (!(strcmp($row_Recordset4['Id'], @$_SESSION['tfi_listtbl_emergency1_SiteId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['Name']?></option>
                <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
              </select>              </td>
              <td><input type="text" name="tfi_listtbl_emergency1_DateSubmitted" id="tfi_listtbl_emergency1_DateSubmitted" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_emergency1_DateSubmitted']); ?>" size="10" maxlength="100" /></td>
              <td><input type="submit" name="tfi_listtbl_emergency1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rstbl_emergency1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rstbl_emergency1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_tbl_emergency" class="id_checkbox" value="<?php echo $row_rstbl_emergency1['Id']; ?>" />
                    <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_emergency1['Id']; ?>" />                </td>
                <td><div class="KT_col_AreaId"><?php echo KT_FormatForList($row_rstbl_emergency1['AreaId'], 20); ?></div></td>
                <td><div class="KT_col_CompanyId"><?php echo KT_FormatForList($row_rstbl_emergency1['CompanyId'], 20); ?></div></td>
                <td><div class="KT_col_SiteId"><?php echo KT_FormatForList($row_rstbl_emergency1['SiteId'], 20); ?></div></td>
                <td><div class="KT_col_DateSubmitted"><?php echo KT_FormatForList($row_rstbl_emergency1['DateSubmitted'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="emergency-history.php?Id=<?php echo $row_rstbl_emergency1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
              </tr>
              <?php } while ($row_rstbl_emergency1 = mysql_fetch_assoc($rstbl_emergency1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listtbl_emergency1->Prepare();
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
        <a class="KT_additem_op_link" href="emergency-history.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($rstbl_emergency1);
?>
