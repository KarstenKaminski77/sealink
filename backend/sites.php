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
$tfi_listtbl_sites1 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_sites1");
$tfi_listtbl_sites1->addColumn("tbl_companies.Name", "STRING_TYPE", "Name1", "%");
$tfi_listtbl_sites1->addColumn("tbl_sites.Site", "STRING_TYPE", "Site", "%");
$tfi_listtbl_sites1->addColumn("tbl_sites.Name", "STRING_TYPE", "Name", "%");
$tfi_listtbl_sites1->addColumn("tbl_sites.Address", "STRING_TYPE", "Address", "%");
$tfi_listtbl_sites1->addColumn("tbl_sites.Suburb", "STRING_TYPE", "Suburb", "%");
$tfi_listtbl_sites1->Execute();

// Sorter
$tso_listtbl_sites1 = new TSO_TableSorter("rstbl_sites1", "tso_listtbl_sites1");
$tso_listtbl_sites1->addColumn("tbl_companies.Name");
$tso_listtbl_sites1->addColumn("tbl_sites.Site");
$tso_listtbl_sites1->addColumn("tbl_sites.Name");
$tso_listtbl_sites1->addColumn("tbl_sites.Address");
$tso_listtbl_sites1->addColumn("tbl_sites.Suburb");
$tso_listtbl_sites1->setDefault("tbl_sites.Company");
$tso_listtbl_sites1->Execute();

// Navigation
$nav_listtbl_sites1 = new NAV_Regular("nav_listtbl_sites1", "rstbl_sites1", "../", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT Name, Id FROM tbl_companies ORDER BY Name";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT * FROM tbl_areas";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_sites1 = $_SESSION['max_rows_nav_listtbl_sites1'];
$pageNum_rstbl_sites1 = 0;
if (isset($_GET['pageNum_rstbl_sites1'])) {
  $pageNum_rstbl_sites1 = $_GET['pageNum_rstbl_sites1'];
}
$startRow_rstbl_sites1 = $pageNum_rstbl_sites1 * $maxRows_rstbl_sites1;

$NXTFilter_rstbl_sites1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_sites1'])) {
  $NXTFilter_rstbl_sites1 = $_SESSION['filter_tfi_listtbl_sites1'];
}
$NXTSort_rstbl_sites1 = "tbl_sites.Company";
if (isset($_SESSION['sorter_tso_listtbl_sites1'])) {
  $NXTSort_rstbl_sites1 = $_SESSION['sorter_tso_listtbl_sites1'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_sites1 = sprintf("SELECT tbl_companies.Name AS Name1, tbl_sites.Site, tbl_sites.Name, tbl_sites.Address, tbl_sites.Suburb, tbl_sites.Id FROM tbl_sites LEFT JOIN tbl_companies ON tbl_sites.Company = tbl_companies.Id WHERE %s ORDER BY %s", $NXTFilter_rstbl_sites1, $NXTSort_rstbl_sites1);
$query_limit_rstbl_sites1 = sprintf("%s LIMIT %d, %d", $query_rstbl_sites1, $startRow_rstbl_sites1, $maxRows_rstbl_sites1);
$rstbl_sites1 = mysql_query($query_limit_rstbl_sites1, $seavest) or die(mysql_error());
$row_rstbl_sites1 = mysql_fetch_assoc($rstbl_sites1);

if (isset($_GET['totalRows_rstbl_sites1'])) {
  $totalRows_rstbl_sites1 = $_GET['totalRows_rstbl_sites1'];
} else {
  $all_rstbl_sites1 = mysql_query($query_rstbl_sites1);
  $totalRows_rstbl_sites1 = mysql_num_rows($all_rstbl_sites1);
}
$totalPages_rstbl_sites1 = ceil($totalRows_rstbl_sites1/$maxRows_rstbl_sites1)-1;
//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_sites = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_sites);
// Register triggers
$ins_tbl_sites->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_sites->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_sites->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_sites->setTable("tbl_sites");
$ins_tbl_sites->addColumn("Company", "STRING_TYPE", "POST", "Company");
$ins_tbl_sites->addColumn("AreaId", "NUMERIC_TYPE", "POST", "AreaId");
$ins_tbl_sites->addColumn("Site", "STRING_TYPE", "POST", "Site");
$ins_tbl_sites->addColumn("Name", "STRING_TYPE", "POST", "Name");
$ins_tbl_sites->addColumn("Cat06", "STRING_TYPE", "POST", "Cat06");
$ins_tbl_sites->addColumn("NewNwM", "STRING_TYPE", "POST", "NewNwM");
$ins_tbl_sites->addColumn("FirstName", "STRING_TYPE", "POST", "FirstName");
$ins_tbl_sites->addColumn("LastName", "STRING_TYPE", "POST", "LastName");
$ins_tbl_sites->addColumn("Address", "STRING_TYPE", "POST", "Address");
$ins_tbl_sites->addColumn("Suburb", "STRING_TYPE", "POST", "Suburb");
$ins_tbl_sites->addColumn("Telephone", "STRING_TYPE", "POST", "Telephone");
$ins_tbl_sites->addColumn("Fax", "STRING_TYPE", "POST", "Fax");
$ins_tbl_sites->addColumn("Cell", "STRING_TYPE", "POST", "Cell");
$ins_tbl_sites->addColumn("Other", "STRING_TYPE", "POST", "Other");
$ins_tbl_sites->addColumn("Email", "STRING_TYPE", "POST", "Email");
$ins_tbl_sites->addColumn("ShopType", "STRING_TYPE", "POST", "ShopType");
$ins_tbl_sites->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_sites = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_sites);
// Register triggers
$upd_tbl_sites->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_sites->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_sites->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_sites->setTable("tbl_sites");
$upd_tbl_sites->addColumn("Company", "STRING_TYPE", "POST", "Company");
$upd_tbl_sites->addColumn("AreaId", "NUMERIC_TYPE", "POST", "AreaId");
$upd_tbl_sites->addColumn("Site", "STRING_TYPE", "POST", "Site");
$upd_tbl_sites->addColumn("Name", "STRING_TYPE", "POST", "Name");
$upd_tbl_sites->addColumn("Cat06", "STRING_TYPE", "POST", "Cat06");
$upd_tbl_sites->addColumn("NewNwM", "STRING_TYPE", "POST", "NewNwM");
$upd_tbl_sites->addColumn("FirstName", "STRING_TYPE", "POST", "FirstName");
$upd_tbl_sites->addColumn("LastName", "STRING_TYPE", "POST", "LastName");
$upd_tbl_sites->addColumn("Address", "STRING_TYPE", "POST", "Address");
$upd_tbl_sites->addColumn("Suburb", "STRING_TYPE", "POST", "Suburb");
$upd_tbl_sites->addColumn("Telephone", "STRING_TYPE", "POST", "Telephone");
$upd_tbl_sites->addColumn("Fax", "STRING_TYPE", "POST", "Fax");
$upd_tbl_sites->addColumn("Cell", "STRING_TYPE", "POST", "Cell");
$upd_tbl_sites->addColumn("Other", "STRING_TYPE", "POST", "Other");
$upd_tbl_sites->addColumn("Email", "STRING_TYPE", "POST", "Email");
$upd_tbl_sites->addColumn("ShopType", "STRING_TYPE", "POST", "ShopType");
$upd_tbl_sites->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_sites = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_sites);
// Register triggers
$del_tbl_sites->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_sites->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_sites->setTable("tbl_sites");
$del_tbl_sites->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_sites = $tNGs->getRecordset("tbl_sites");
$row_rstbl_sites = mysql_fetch_assoc($rstbl_sites);
$totalRows_rstbl_sites = mysql_num_rows($rstbl_sites);

$nav_listtbl_sites1->checkBoundries();
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
  .KT_col_Name1 {width:140px; overflow:hidden;}
  .KT_col_Site {width:140px; overflow:hidden;}
  .KT_col_Name {width:140px; overflow:hidden;}
  .KT_col_Address {width:140px; overflow:hidden;}
  .KT_col_Suburb {width:140px; overflow:hidden;}
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
              Site </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_sites > 1) {
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
                          <option value="<?php echo $row_Recordset1['Id']?>"<?php if (!(strcmp($row_Recordset1['Id'], $row_rstbl_sites['Company']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['Name']?></option>
                          <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("tbl_sites", "Company", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="AreaId_<?php echo $cnt1; ?>">Region:</label></td>
                      <td><select name="AreaId_<?php echo $cnt1; ?>" id="AreaId_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['Id']?>"<?php if (!(strcmp($row_Recordset3['Id'], $row_rstbl_sites['AreaId']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['Area']?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("tbl_sites", "AreaId", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Site_<?php echo $cnt1; ?>">Site:</label></td>
                      <td><input type="text" name="Site_<?php echo $cnt1; ?>" id="Site_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Site']); ?>" size="32" maxlength="50" />
                          <?php echo $tNGs->displayFieldHint("Site");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Site", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Name_<?php echo $cnt1; ?>">Name:</label></td>
                      <td><input type="text" name="Name_<?php echo $cnt1; ?>" id="Name_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Name']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("Name");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Name", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Cat06_<?php echo $cnt1; ?>">Cat06:</label></td>
                      <td><input type="text" name="Cat06_<?php echo $cnt1; ?>" id="Cat06_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Cat06']); ?>" size="20" maxlength="20" />
                          <?php echo $tNGs->displayFieldHint("Cat06");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Cat06", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="NewNwM_<?php echo $cnt1; ?>">NewNwM:</label></td>
                      <td><input type="text" name="NewNwM_<?php echo $cnt1; ?>" id="NewNwM_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['NewNwM']); ?>" size="20" maxlength="20" />
                          <?php echo $tNGs->displayFieldHint("NewNwM");?> <?php echo $tNGs->displayFieldError("tbl_sites", "NewNwM", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="FirstName_<?php echo $cnt1; ?>">First Name:</label></td>
                      <td><input type="text" name="FirstName_<?php echo $cnt1; ?>" id="FirstName_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['FirstName']); ?>" size="32" maxlength="50" />
                          <?php echo $tNGs->displayFieldHint("FirstName");?> <?php echo $tNGs->displayFieldError("tbl_sites", "FirstName", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="LastName_<?php echo $cnt1; ?>">Last Name:</label></td>
                      <td><input type="text" name="LastName_<?php echo $cnt1; ?>" id="LastName_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['LastName']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("LastName");?> <?php echo $tNGs->displayFieldError("tbl_sites", "LastName", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Address_<?php echo $cnt1; ?>">Address:</label></td>
                      <td><input type="text" name="Address_<?php echo $cnt1; ?>" id="Address_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Address']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("Address");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Address", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Suburb_<?php echo $cnt1; ?>">Suburb:</label></td>
                      <td><input type="text" name="Suburb_<?php echo $cnt1; ?>" id="Suburb_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Suburb']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("Suburb");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Suburb", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Telephone_<?php echo $cnt1; ?>">Telephone:</label></td>
                      <td><input type="text" name="Telephone_<?php echo $cnt1; ?>" id="Telephone_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Telephone']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("Telephone");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Telephone", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Fax_<?php echo $cnt1; ?>">Fax:</label></td>
                      <td><input type="text" name="Fax_<?php echo $cnt1; ?>" id="Fax_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Fax']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("Fax");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Fax", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Cell_<?php echo $cnt1; ?>">Cell:</label></td>
                      <td><input type="text" name="Cell_<?php echo $cnt1; ?>" id="Cell_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Cell']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("Cell");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Cell", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Other_<?php echo $cnt1; ?>">Other:</label></td>
                      <td><input type="text" name="Other_<?php echo $cnt1; ?>" id="Other_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Other']); ?>" size="32" maxlength="255" />
                          <?php echo $tNGs->displayFieldHint("Other");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Other", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="Email_<?php echo $cnt1; ?>">Email:</label></td>
                      <td><input type="text" name="Email_<?php echo $cnt1; ?>" id="Email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['Email']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("Email");?> <?php echo $tNGs->displayFieldError("tbl_sites", "Email", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="ShopType_<?php echo $cnt1; ?>">ShopType:</label></td>
                      <td><input type="text" name="ShopType_<?php echo $cnt1; ?>" id="ShopType_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_sites['ShopType']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("ShopType");?> <?php echo $tNGs->displayFieldError("tbl_sites", "ShopType", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_tbl_sites_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_sites['kt_pk_tbl_sites']); ?>" />
                  <?php } while ($row_rstbl_sites = mysql_fetch_assoc($rstbl_sites)); ?>
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
          </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;
      <div class="KT_tng" id="listtbl_sites1">
        <h1>&nbsp; </h1>
        <h1>Sites
          <?php
  $nav_listtbl_sites1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
        </h1>
        <div class="KT_tnglist">
          <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
            <div class="KT_options"> <a href="<?php echo $nav_listtbl_sites1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_sites1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listtbl_sites1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_sites1'] == 1) {
?>
                              <a href="<?php echo $tfi_listtbl_sites1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                              <?php 
  // else Conditional region2
  } else { ?>
                              <a href="<?php echo $tfi_listtbl_sites1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                              <?php } 
  // endif Conditional region2
?>
            </div>
            <table cellpadding="2" cellspacing="0" class="KT_tngtable">
              <thead>
                <tr class="KT_row_order">
                  <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                  </th>
                  <th id="Name1" class="KT_sorter KT_col_Name1 <?php echo $tso_listtbl_sites1->getSortIcon('tbl_companies.Name'); ?>"> <a href="<?php echo $tso_listtbl_sites1->getSortLink('tbl_companies.Name'); ?>">Company</a> </th>
                  <th id="Site" class="KT_sorter KT_col_Site <?php echo $tso_listtbl_sites1->getSortIcon('tbl_sites.Site'); ?>"> <a href="<?php echo $tso_listtbl_sites1->getSortLink('tbl_sites.Site'); ?>">Site</a> </th>
                  <th id="Name" class="KT_sorter KT_col_Name <?php echo $tso_listtbl_sites1->getSortIcon('tbl_sites.Name'); ?>"> <a href="<?php echo $tso_listtbl_sites1->getSortLink('tbl_sites.Name'); ?>">Name</a> </th>
                  <th id="Address" class="KT_sorter KT_col_Address <?php echo $tso_listtbl_sites1->getSortIcon('tbl_sites.Address'); ?>"> <a href="<?php echo $tso_listtbl_sites1->getSortLink('tbl_sites.Address'); ?>">Address</a> </th>
                  <th id="Suburb" class="KT_sorter KT_col_Suburb <?php echo $tso_listtbl_sites1->getSortIcon('tbl_sites.Suburb'); ?>"> <a href="<?php echo $tso_listtbl_sites1->getSortLink('tbl_sites.Suburb'); ?>">Suburb</a> </th>
                  <th>&nbsp;</th>
                </tr>
                <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_sites1'] == 1) {
?>
                  <tr class="KT_row_filter">
                    <td>&nbsp;</td>
                    <td><input type="text" name="tfi_listtbl_sites1_Name1" id="tfi_listtbl_sites1_Name1" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_sites1_Name1']); ?>" size="20" maxlength="100" /></td>
                    <td><input type="text" name="tfi_listtbl_sites1_Site" id="tfi_listtbl_sites1_Site" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_sites1_Site']); ?>" size="20" maxlength="50" /></td>
                    <td><input type="text" name="tfi_listtbl_sites1_Name" id="tfi_listtbl_sites1_Name" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_sites1_Name']); ?>" size="20" maxlength="100" /></td>
                    <td><input type="text" name="tfi_listtbl_sites1_Address" id="tfi_listtbl_sites1_Address" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_sites1_Address']); ?>" size="20" maxlength="100" /></td>
                    <td><input type="text" name="tfi_listtbl_sites1_Suburb" id="tfi_listtbl_sites1_Suburb" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_sites1_Suburb']); ?>" size="20" maxlength="100" /></td>
                    <td><input type="submit" name="tfi_listtbl_sites1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                  </tr>
                  <?php } 
  // endif Conditional region3
?>
              </thead>
              <tbody>
                <?php if ($totalRows_rstbl_sites1 == 0) { // Show if recordset empty ?>
                  <tr>
                    <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                  </tr>
                  <?php } // Show if recordset empty ?>
                <?php if ($totalRows_rstbl_sites1 > 0) { // Show if recordset not empty ?>
                  <?php do { ?>
                    <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                      <td><input type="checkbox" name="kt_pk_tbl_sites" class="id_checkbox" value="<?php echo $row_rstbl_sites1['Id']; ?>" />
                          <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_sites1['Id']; ?>" />
                      </td>
                      <td><div class="KT_col_Name1"><?php echo KT_FormatForList($row_rstbl_sites1['Name1'], 20); ?></div></td>
                      <td><div class="KT_col_Site"><?php echo KT_FormatForList($row_rstbl_sites1['Site'], 20); ?></div></td>
                      <td><div class="KT_col_Name"><?php echo KT_FormatForList($row_rstbl_sites1['Name'], 20); ?></div></td>
                      <td><div class="KT_col_Address"><?php echo KT_FormatForList($row_rstbl_sites1['Address'], 20); ?></div></td>
                      <td><div class="KT_col_Suburb"><?php echo KT_FormatForList($row_rstbl_sites1['Suburb'], 20); ?></div></td>
                      <td><a class="KT_edit_link" href="sites.php?Id=<?php echo $row_rstbl_sites1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                    </tr>
                    <?php } while ($row_rstbl_sites1 = mysql_fetch_assoc($rstbl_sites1)); ?>
                  <?php } // Show if recordset not empty ?>
              </tbody>
            </table>
            <div class="KT_bottomnav">
              <div>
                <?php
            $nav_listtbl_sites1->Prepare();
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
              <a class="KT_additem_op_link" href="sites.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($rstbl_sites1);
?>
