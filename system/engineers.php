<?php require_once('../Connections/seavest.php'); ?>
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
$tfi_listtbl_engineers1 = new TFI_TableFilter($conn_inv, "tfi_listtbl_engineers1");
$tfi_listtbl_engineers1->addColumn("tbl_companies.Id", "STRING_TYPE", "CompanyId", "%");
$tfi_listtbl_engineers1->addColumn("tbl_engineers.Name", "STRING_TYPE", "Name", "%");
$tfi_listtbl_engineers1->addColumn("tbl_engineers.Email", "STRING_TYPE", "Email", "%");
$tfi_listtbl_engineers1->Execute();

// Sorter
$tso_listtbl_engineers1 = new TSO_TableSorter("rstbl_engineers1", "tso_listtbl_engineers1");
$tso_listtbl_engineers1->addColumn("tbl_companies.Name");
$tso_listtbl_engineers1->addColumn("tbl_engineers.Name");
$tso_listtbl_engineers1->addColumn("tbl_engineers.Email");
$tso_listtbl_engineers1->setDefault("tbl_engineers.CompanyId");
$tso_listtbl_engineers1->Execute();

// Navigation
$nav_listtbl_engineers1 = new NAV_Regular("nav_listtbl_engineers1", "rstbl_engineers1", "../", $_SERVER['PHP_SELF'], 20);

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

mysql_select_db($database_inv, $inv);
$query_Recordset3 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset3 = mysql_query($query_Recordset3, $inv) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_inv, $inv);
$query_Recordset4 = "SELECT Name, Id FROM tbl_companies ORDER BY Name";
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_engineers1 = $_SESSION['max_rows_nav_listtbl_engineers1'];
$pageNum_rstbl_engineers1 = 0;
if (isset($_GET['pageNum_rstbl_engineers1'])) {
  $pageNum_rstbl_engineers1 = $_GET['pageNum_rstbl_engineers1'];
}
$startRow_rstbl_engineers1 = $pageNum_rstbl_engineers1 * $maxRows_rstbl_engineers1;

// Defining List Recordset variable
$NXTFilter_rstbl_engineers1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_engineers1'])) {
  $NXTFilter_rstbl_engineers1 = $_SESSION['filter_tfi_listtbl_engineers1'];
}
// Defining List Recordset variable
$NXTSort_rstbl_engineers1 = "tbl_engineers.CompanyId";
if (isset($_SESSION['sorter_tso_listtbl_engineers1'])) {
  $NXTSort_rstbl_engineers1 = $_SESSION['sorter_tso_listtbl_engineers1'];
}
mysql_select_db($database_inv, $inv);

$query_rstbl_engineers1 = "SELECT tbl_companies.Name AS CompanyId, tbl_engineers.Name, tbl_engineers.Email, tbl_engineers.Id FROM tbl_engineers LEFT JOIN tbl_companies ON tbl_engineers.CompanyId = tbl_companies.Id WHERE {$NXTFilter_rstbl_engineers1} ORDER BY {$NXTSort_rstbl_engineers1}";
$query_limit_rstbl_engineers1 = sprintf("%s LIMIT %d, %d", $query_rstbl_engineers1, $startRow_rstbl_engineers1, $maxRows_rstbl_engineers1);
$rstbl_engineers1 = mysql_query($query_limit_rstbl_engineers1, $inv) or die(mysql_error());
$row_rstbl_engineers1 = mysql_fetch_assoc($rstbl_engineers1);

if (isset($_GET['totalRows_rstbl_engineers1'])) {
  $totalRows_rstbl_engineers1 = $_GET['totalRows_rstbl_engineers1'];
} else {
  $all_rstbl_engineers1 = mysql_query($query_rstbl_engineers1);
  $totalRows_rstbl_engineers1 = mysql_num_rows($all_rstbl_engineers1);
}
$totalPages_rstbl_engineers1 = ceil($totalRows_rstbl_engineers1/$maxRows_rstbl_engineers1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_engineers = new tNG_multipleInsert($conn_inv);
$tNGs->addTransaction($ins_tbl_engineers);
// Register triggers
$ins_tbl_engineers->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_engineers->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_engineers->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_engineers->setTable("tbl_engineers");
$ins_tbl_engineers->addColumn("CompanyId", "NUMERIC_TYPE", "POST", "CompanyId");
$ins_tbl_engineers->addColumn("Name", "STRING_TYPE", "POST", "Name");
$ins_tbl_engineers->addColumn("Email", "STRING_TYPE", "POST", "Email");
$ins_tbl_engineers->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_engineers = new tNG_multipleUpdate($conn_inv);
$tNGs->addTransaction($upd_tbl_engineers);
// Register triggers
$upd_tbl_engineers->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_engineers->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_engineers->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_engineers->setTable("tbl_engineers");
$upd_tbl_engineers->addColumn("CompanyId", "NUMERIC_TYPE", "POST", "CompanyId");
$upd_tbl_engineers->addColumn("Name", "STRING_TYPE", "POST", "Name");
$upd_tbl_engineers->addColumn("Email", "STRING_TYPE", "POST", "Email");
$upd_tbl_engineers->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_engineers = new tNG_multipleDelete($conn_inv);
$tNGs->addTransaction($del_tbl_engineers);
// Register triggers
$del_tbl_engineers->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_engineers->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_engineers->setTable("tbl_engineers");
$del_tbl_engineers->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_engineers = $tNGs->getRecordset("tbl_engineers");
$row_rstbl_engineers = mysql_fetch_assoc($rstbl_engineers);
$totalRows_rstbl_engineers = mysql_num_rows($rstbl_engineers);

$nav_listtbl_engineers1->checkBoundries();
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
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
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
  .KT_col_CompanyId {width:140px; overflow:hidden;}
  .KT_col_Name {width:140px; overflow:hidden;}
  .KT_col_Email {width:140px; overflow:hidden;}
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
        <td align="center"><br>
          <br>
          <br>
          <div style="margin-left: 30px">
            <div class="KT_tng">
              <h1>&nbsp;</h1>
              <div class="KT_tngform">
                <form method="post" id="form2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                  <?php $cnt1 = 0; ?>
                  <?php do { ?>
                    <?php $cnt1++; ?>
                    <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_engineers > 1) {
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
                          <option value="<?php echo $row_Recordset3['Id']?>"<?php if (!(strcmp($row_Recordset3['Id'], $row_rstbl_engineers['CompanyId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['Name']?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                          </select>
                          <?php echo $tNGs->displayFieldError("tbl_engineers", "CompanyId", $cnt1); ?></td>
                        </tr>
                      <tr>
                        <td class="KT_th"><label for="Name_<?php echo $cnt1; ?>">Name:</label></td>
                        <td><input type="text" name="Name_<?php echo $cnt1; ?>" id="Name_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_engineers['Name']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("Name");?> <?php echo $tNGs->displayFieldError("tbl_engineers", "Name", $cnt1); ?></td>
                        </tr>
                      <tr>
                        <td class="KT_th"><label for="Email_<?php echo $cnt1; ?>">Email:</label></td>
                        <td><input type="text" name="Email_<?php echo $cnt1; ?>" id="Email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_engineers['Email']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("Email");?> <?php echo $tNGs->displayFieldError("tbl_engineers", "Email", $cnt1); ?></td>
                        </tr>
                      </table>
                    <input type="hidden" name="kt_pk_tbl_engineers_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_engineers['kt_pk_tbl_engineers']); ?>" />
                    <?php } while ($row_rstbl_engineers = mysql_fetch_assoc($rstbl_engineers)); ?>
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
            
            <div class="KT_tng" id="listtbl_engineers1">
              <h1>&nbsp;</h1>
              <div class="KT_tnglist">
                <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form3">
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <thead>
                      <tr class="KT_row_order">
                        <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                          </th>
                        <th id="CompanyId" class="KT_sorter KT_col_CompanyId <?php echo $tso_listtbl_engineers1->getSortIcon('tbl_companies.Name'); ?>"> <a href="<?php echo $tso_listtbl_engineers1->getSortLink('tbl_companies.Name'); ?>">Company</a> </th>
                        <th id="Name" class="KT_sorter KT_col_Name <?php echo $tso_listtbl_engineers1->getSortIcon('tbl_engineers.Name'); ?>"> <a href="<?php echo $tso_listtbl_engineers1->getSortLink('tbl_engineers.Name'); ?>">Name</a> </th>
                        <th id="Email" class="KT_sorter KT_col_Email <?php echo $tso_listtbl_engineers1->getSortIcon('tbl_engineers.Email'); ?>"> <a href="<?php echo $tso_listtbl_engineers1->getSortLink('tbl_engineers.Email'); ?>">Email</a> </th>
                        <th>&nbsp;</th>
                        </tr>
                      <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_engineers1'] == 1) {
?>
                        <tr class="KT_row_filter">
                          <td>&nbsp;</td>
                          <td><select name="tfi_listtbl_engineers1_CompanyId" id="tfi_listtbl_engineers1_CompanyId">
                            <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listtbl_engineers1_CompanyId']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset4['Id']?>"<?php if (!(strcmp($row_Recordset4['Id'], @$_SESSION['tfi_listtbl_engineers1_CompanyId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['Name']?></option>
                            <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
                          </select></td>
                          <td><input type="text" name="tfi_listtbl_engineers1_Name" id="tfi_listtbl_engineers1_Name" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_engineers1_Name']); ?>" size="20" maxlength="100" /></td>
                          <td><input type="text" name="tfi_listtbl_engineers1_Email" id="tfi_listtbl_engineers1_Email" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_engineers1_Email']); ?>" size="20" maxlength="100" /></td>
                          <td><input type="submit" name="tfi_listtbl_engineers1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                        </tr>
                        <?php } 
  // endif Conditional region3
?>
                      </thead>
                    <tbody>
                      <?php if ($totalRows_rstbl_engineers1 == 0) { // Show if recordset empty ?>
                        <tr>
                          <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                          </tr>
                        <?php } // Show if recordset empty ?>
                      <?php if ($totalRows_rstbl_engineers1 > 0) { // Show if recordset not empty ?>
                        <?php do { ?>
                          <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                            <td><input type="checkbox" name="kt_pk_tbl_engineers" class="id_checkbox" value="<?php echo $row_rstbl_engineers1['Id']; ?>" />
                              <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_engineers1['Id']; ?>" /></td>
                            <td><div class="KT_col_CompanyId"><?php echo KT_FormatForList($row_rstbl_engineers1['CompanyId'], 20); ?></div></td>
                            <td><div class="KT_col_Name"><?php echo KT_FormatForList($row_rstbl_engineers1['Name'], 20); ?></div></td>
                            <td><div class="KT_col_Email"><?php echo KT_FormatForList($row_rstbl_engineers1['Email'], 20); ?></div></td>
                            <td><a class="KT_edit_link" href="engineers.php?Id=<?php echo $row_rstbl_engineers1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a></td>
                            </tr>
                          <?php } while ($row_rstbl_engineers1 = mysql_fetch_assoc($rstbl_engineers1)); ?>
                        <?php } // Show if recordset not empty ?>
                    </tbody>
                    </table>
                  <div class="KT_bottomnav">
                    <div>
                      <?php
            $nav_listtbl_engineers1->Prepare();
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
                    <a class="KT_additem_op_link" href="engineers.php?KT_back=1" onClick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
                  </form>
                </div>
              <br class="clearfixplain" />
            </div>
            <p>&nbsp;</p>
            </p>
          </div></td>
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

mysql_free_result($rstbl_engineers1);
?>
