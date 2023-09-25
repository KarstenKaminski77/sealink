<?php require_once('../Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

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
$formValidation->addField("Question", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger

// Filter
$tfi_listtbl_appraisal_questions3 = new TFI_TableFilter($conn_seavest, "tfi_listtbl_appraisal_questions3");
$tfi_listtbl_appraisal_questions3->addColumn("tbl_appraisal_questions.Date", "STRING_TYPE", "Date", "%");
$tfi_listtbl_appraisal_questions3->addColumn("tbl_appraisal_questions.Question", "STRING_TYPE", "Question", "%");
$tfi_listtbl_appraisal_questions3->addColumn("Current", "STRING_TYPE", "Current", "%");
$tfi_listtbl_appraisal_questions3->Execute();

// Sorter
$tso_listtbl_appraisal_questions3 = new TSO_TableSorter("rstbl_appraisal_questions1", "tso_listtbl_appraisal_questions3");
$tso_listtbl_appraisal_questions3->addColumn("tbl_appraisal_questions.Date");
$tso_listtbl_appraisal_questions3->addColumn("tbl_appraisal_questions.Question");
$tso_listtbl_appraisal_questions3->addColumn("Current");
$tso_listtbl_appraisal_questions3->setDefault("tbl_appraisal_questions.Date");
$tso_listtbl_appraisal_questions3->Execute();

// Navigation
$nav_listtbl_appraisal_questions3 = new NAV_Regular("nav_listtbl_appraisal_questions3", "rstbl_appraisal_questions1", "../", $_SERVER['PHP_SELF'], 50);

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_appraisal_questions1 = $_SESSION['max_rows_nav_listtbl_appraisal_questions3'];
$pageNum_rstbl_appraisal_questions1 = 0;
if (isset($_GET['pageNum_rstbl_appraisal_questions1'])) {
  $pageNum_rstbl_appraisal_questions1 = $_GET['pageNum_rstbl_appraisal_questions1'];
}
$startRow_rstbl_appraisal_questions1 = $pageNum_rstbl_appraisal_questions1 * $maxRows_rstbl_appraisal_questions1;

$NXTFilter_rstbl_appraisal_questions1 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_appraisal_questions3'])) {
  $NXTFilter_rstbl_appraisal_questions1 = $_SESSION['filter_tfi_listtbl_appraisal_questions3'];
}
$NXTSort_rstbl_appraisal_questions1 = "tbl_appraisal_questions.Date";
if (isset($_SESSION['sorter_tso_listtbl_appraisal_questions3'])) {
  $NXTSort_rstbl_appraisal_questions1 = $_SESSION['sorter_tso_listtbl_appraisal_questions3'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_appraisal_questions1 = sprintf("SELECT tbl_appraisal_questions.Date, tbl_appraisal_questions.Question, tbl_appraisal_questions.Current, tbl_appraisal_questions.Id FROM tbl_appraisal_questions WHERE %s ORDER BY %s", $NXTFilter_rstbl_appraisal_questions1, $NXTSort_rstbl_appraisal_questions1);
$query_limit_rstbl_appraisal_questions1 = sprintf("%s LIMIT %d, %d", $query_rstbl_appraisal_questions1, $startRow_rstbl_appraisal_questions1, $maxRows_rstbl_appraisal_questions1);
$rstbl_appraisal_questions1 = mysql_query($query_limit_rstbl_appraisal_questions1, $seavest) or die(mysql_error());
$row_rstbl_appraisal_questions1 = mysql_fetch_assoc($rstbl_appraisal_questions1);

if (isset($_GET['totalRows_rstbl_appraisal_questions1'])) {
  $totalRows_rstbl_appraisal_questions1 = $_GET['totalRows_rstbl_appraisal_questions1'];
} else {
  $all_rstbl_appraisal_questions1 = mysql_query($query_rstbl_appraisal_questions1);
  $totalRows_rstbl_appraisal_questions1 = mysql_num_rows($all_rstbl_appraisal_questions1);
}
$totalPages_rstbl_appraisal_questions1 = ceil($totalRows_rstbl_appraisal_questions1/$maxRows_rstbl_appraisal_questions1)-1;
//End NeXTenesio3 Special List Recordset

//NeXTenesio3 Special List Recordset
$maxRows_rstbl_appraisal_questions2 = $_SESSION['max_rows_nav_listtbl_appraisal_questions2'];
$pageNum_rstbl_appraisal_questions2 = 0;
if (isset($_GET['pageNum_rstbl_appraisal_questions2'])) {
  $pageNum_rstbl_appraisal_questions2 = $_GET['pageNum_rstbl_appraisal_questions2'];
}
$startRow_rstbl_appraisal_questions2 = $pageNum_rstbl_appraisal_questions2 * $maxRows_rstbl_appraisal_questions2;

$NXTFilter_rstbl_appraisal_questions2 = "1=1";
if (isset($_SESSION['filter_tfi_listtbl_appraisal_questions2'])) {
  $NXTFilter_rstbl_appraisal_questions2 = $_SESSION['filter_tfi_listtbl_appraisal_questions2'];
}
$NXTSort_rstbl_appraisal_questions2 = "tbl_appraisal_questions.Date";
if (isset($_SESSION['sorter_tso_listtbl_appraisal_questions2'])) {
  $NXTSort_rstbl_appraisal_questions2 = $_SESSION['sorter_tso_listtbl_appraisal_questions2'];
}
mysql_select_db($database_seavest, $seavest);

$query_rstbl_appraisal_questions2 = sprintf("SELECT tbl_appraisal_questions.Date, tbl_appraisal_questions.Question, tbl_appraisal_questions.Id FROM tbl_appraisal_questions WHERE %s ORDER BY %s", $NXTFilter_rstbl_appraisal_questions2, $NXTSort_rstbl_appraisal_questions2);
$query_limit_rstbl_appraisal_questions2 = sprintf("%s LIMIT %d, %d", $query_rstbl_appraisal_questions2, $startRow_rstbl_appraisal_questions2, $maxRows_rstbl_appraisal_questions2);
$rstbl_appraisal_questions2 = mysql_query($query_limit_rstbl_appraisal_questions2, $seavest) or die(mysql_error());
$row_rstbl_appraisal_questions2 = mysql_fetch_assoc($rstbl_appraisal_questions2);

if (isset($_GET['totalRows_rstbl_appraisal_questions2'])) {
  $totalRows_rstbl_appraisal_questions2 = $_GET['totalRows_rstbl_appraisal_questions2'];
} else {
  $all_rstbl_appraisal_questions2 = mysql_query($query_rstbl_appraisal_questions2);
  $totalRows_rstbl_appraisal_questions2 = mysql_num_rows($all_rstbl_appraisal_questions2);
}
$totalPages_rstbl_appraisal_questions2 = ceil($totalRows_rstbl_appraisal_questions2/$maxRows_rstbl_appraisal_questions2)-1;
//End NeXTenesio3 Special List Recordset

//End NeXTenesio3 Special List Recordset

// Make an insert transaction instance
$ins_tbl_appraisal_questions = new tNG_multipleInsert($conn_seavest);
$tNGs->addTransaction($ins_tbl_appraisal_questions);
// Register triggers
$ins_tbl_appraisal_questions->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_tbl_appraisal_questions->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_tbl_appraisal_questions->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_tbl_appraisal_questions->setTable("tbl_appraisal_questions");
$ins_tbl_appraisal_questions->addColumn("Date", "STRING_TYPE", "POST", "Date");
$ins_tbl_appraisal_questions->addColumn("Question", "STRING_TYPE", "POST", "Question");
$ins_tbl_appraisal_questions->addColumn("Current", "STRING_TYPE", "POST", "Current");
$ins_tbl_appraisal_questions->setPrimaryKey("Id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_tbl_appraisal_questions = new tNG_multipleUpdate($conn_seavest);
$tNGs->addTransaction($upd_tbl_appraisal_questions);
// Register triggers
$upd_tbl_appraisal_questions->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_tbl_appraisal_questions->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_tbl_appraisal_questions->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_tbl_appraisal_questions->setTable("tbl_appraisal_questions");
$upd_tbl_appraisal_questions->addColumn("Date", "STRING_TYPE", "POST", "Date");
$upd_tbl_appraisal_questions->addColumn("Question", "STRING_TYPE", "POST", "Question");
$upd_tbl_appraisal_questions->addColumn("Current", "STRING_TYPE", "POST", "Current");
$upd_tbl_appraisal_questions->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Make an instance of the transaction object
$del_tbl_appraisal_questions = new tNG_multipleDelete($conn_seavest);
$tNGs->addTransaction($del_tbl_appraisal_questions);
// Register triggers
$del_tbl_appraisal_questions->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_tbl_appraisal_questions->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_tbl_appraisal_questions->setTable("tbl_appraisal_questions");
$del_tbl_appraisal_questions->setPrimaryKey("Id", "NUMERIC_TYPE", "GET", "Id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rstbl_appraisal_questions = $tNGs->getRecordset("tbl_appraisal_questions");
$row_rstbl_appraisal_questions = mysql_fetch_assoc($rstbl_appraisal_questions);
$totalRows_rstbl_appraisal_questions = mysql_num_rows($rstbl_appraisal_questions);

$nav_listtbl_appraisal_questions3->checkBoundries();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
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
  .KT_col_Appraisal {width:140px; overflow:hidden;}
  .KT_col_Question {width:140px; overflow:hidden;}
</style>
<style type="text/css">
  /* NeXTensio3 List row settings */
  .KT_col_Date {width:140px; overflow:hidden;}
  .KT_col_Question {width:140px; overflow:hidden;}
  .KT_col_Current {width:140px; overflow:hidden;}
</style>
<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../includes/resources/calendar.js"></script>
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
    Appraisal Question</h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rstbl_appraisal_questions > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="Date_<?php echo $cnt1; ?>">Date:</label></td>
            <td><input name="Date_<?php echo $cnt1; ?>" id="Date_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rstbl_appraisal_questions['Date']); ?>" size="32" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:mondayfirst="true" wdg:singleclick="true" wdg:restricttomask="yes" />
                <?php echo $tNGs->displayFieldHint("Date");?> <?php echo $tNGs->displayFieldError("tbl_appraisal_questions", "Date", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Question_<?php echo $cnt1; ?>">Question:</label></td>
            <td><textarea name="Question_<?php echo $cnt1; ?>" id="Question_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rstbl_appraisal_questions['Question']); ?></textarea>
                <?php echo $tNGs->displayFieldHint("Question");?> <?php echo $tNGs->displayFieldError("tbl_appraisal_questions", "Question", $cnt1); ?> </td>
          </tr>
          <tr>
            <td class="KT_th"><label for="Current_<?php echo $cnt1; ?>_1">Current:</label></td>
            <td>
                <input <?php if (!(strcmp(KT_escapeAttribute($row_rstbl_appraisal_questions['Current']),"1"))) {echo "CHECKED";} ?> type="radio" name="Current_<?php echo $cnt1; ?>" id="Current_<?php echo $cnt1; ?>_1" value="1" />
                <label for="Current_<?php echo $cnt1; ?>_1">Yes</label>
                  <input <?php if (!(strcmp(KT_escapeAttribute($row_rstbl_appraisal_questions['Current']),"0"))) {echo "CHECKED";} ?> type="radio" name="Current_<?php echo $cnt1; ?>" id="Current_<?php echo $cnt1; ?>_2" value="0" />
                  <label for="Current_<?php echo $cnt1; ?>_2">No</label>
                <?php echo $tNGs->displayFieldError("tbl_appraisal_questions", "Current", $cnt1); ?> </td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_tbl_appraisal_questions_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rstbl_appraisal_questions['kt_pk_tbl_appraisal_questions']); ?>" />
        <?php } while ($row_rstbl_appraisal_questions = mysql_fetch_assoc($rstbl_appraisal_questions)); ?>
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
              <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, '')" />
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
<p>
<div class="KT_tng" id="listtbl_appraisal_questions3">
  <h1> Appraisal Questions
    <?php
  $nav_listtbl_appraisal_questions3->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form2">
      <div class="KT_options"> <a href="<?php echo $nav_listtbl_appraisal_questions3->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
            <?php 
  // Show IF Conditional region2
  if (@$_GET['show_all_nav_listtbl_appraisal_questions3'] == 1) {
?>
              <?php echo $_SESSION['default_max_rows_nav_listtbl_appraisal_questions3']; ?>
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
  if (@$_SESSION['has_filter_tfi_listtbl_appraisal_questions3'] == 1) {
?>
                              <a href="<?php echo $tfi_listtbl_appraisal_questions3->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                              <?php 
  // else Conditional region2
  } else { ?>
                              <a href="<?php echo $tfi_listtbl_appraisal_questions3->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                              <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>            </th>
            <th id="Date" class="KT_sorter KT_col_Date <?php echo $tso_listtbl_appraisal_questions3->getSortIcon('tbl_appraisal_questions.Date'); ?>"> <a href="<?php echo $tso_listtbl_appraisal_questions3->getSortLink('tbl_appraisal_questions.Date'); ?>">Date</a> </th>
            <th id="Question" class="KT_sorter KT_col_Question <?php echo $tso_listtbl_appraisal_questions3->getSortIcon('tbl_appraisal_questions.Question'); ?>"> <a href="<?php echo $tso_listtbl_appraisal_questions3->getSortLink('tbl_appraisal_questions.Question'); ?>">Question</a> </th>
            <th id="Current" class="KT_sorter KT_col_Current <?php echo $tso_listtbl_appraisal_questions3->getSortIcon('Current'); ?>"> <a href="<?php echo $tso_listtbl_appraisal_questions3->getSortLink('Current'); ?>">Current</a></th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listtbl_appraisal_questions3'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listtbl_appraisal_questions3_Date" id="tfi_listtbl_appraisal_questions3_Date" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_appraisal_questions3_Date']); ?>" size="20" maxlength="255" /></td>
              <td><input type="text" name="tfi_listtbl_appraisal_questions3_Question" id="tfi_listtbl_appraisal_questions3_Question" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_appraisal_questions3_Question']); ?>" size="20" maxlength="100" /></td>
              <td><input type="text" name="tfi_listtbl_appraisal_questions3_Current" id="tfi_listtbl_appraisal_questions3_Current" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listtbl_appraisal_questions3_Current']); ?>" size="10" maxlength="100" /></td>
              <td><input type="submit" name="tfi_listtbl_appraisal_questions3" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rstbl_appraisal_questions1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rstbl_appraisal_questions1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt2++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_tbl_appraisal_questions" class="id_checkbox" value="<?php echo $row_rstbl_appraisal_questions1['Id']; ?>" />
                    <input type="hidden" name="Id" class="id_field" value="<?php echo $row_rstbl_appraisal_questions1['Id']; ?>" />                </td>
                <td><div class="KT_col_Date"><?php echo KT_FormatForList($row_rstbl_appraisal_questions1['Date'], 20); ?></div></td>
                <td><div class="KT_col_Question"><?php echo KT_FormatForList($row_rstbl_appraisal_questions1['Question'], 20); ?></div></td>
                <td><div class="KT_col_Current">
				<?php 
				if(KT_FormatForList($row_rstbl_appraisal_questions1['Current'], 20) == 0){
				echo "No";
				} else {
				echo "Yes";
				}
				?></div></td>
                <td><a class="KT_edit_link" href="appraisal_ques.php?Id=<?php echo $row_rstbl_appraisal_questions1['Id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
              </tr>
              <?php } while ($row_rstbl_appraisal_questions1 = mysql_fetch_assoc($rstbl_appraisal_questions1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listtbl_appraisal_questions3->Prepare();
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
        <a class="KT_additem_op_link" href="appraisal_ques.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</p>
</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstbl_appraisal_questions1);

mysql_free_result($rstbl_appraisal_questions2);
?>
