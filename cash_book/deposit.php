<?php require_once('../Connections/seavest.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("deposit", true, "text", "", "", "", "Required Field");
$formValidation->addField("area", true, "text", "", "", "", "Required Field");
$tNGs->prepareValidation($formValidation);
// End trigger
?>
<?php
require_once('../includes/tng/tNG.inc.php');

require_once('../functions/functions.php');

select_db();

if(isset($_POST['deposit'])){

$area = $_POST['area'];

$query = mysql_query("SELECT * FROM tbl_system_balances WHERE AreaId = '$area' ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$old_accumulated = $row['Accumulated'];

$deposit = $_POST['deposit'];
$accumulated = $old_accumulated + $deposit;

$old_system_balance = $row['SystemBalance'];
$system_balance = $old_system_balance + $deposit;

$search_date = date('Y m d');
$date = date('d M Y');

mysql_query("INSERT INTO tbl_system_balances (AreaId,Date,SearchDate,Deposited,Accumulated,SystemBalance) VALUES ('$area','$date','$search_date','$deposit','$accumulated','$system_balance')")or die(mysql_error());

$comment = "Seaveast Africa Deposit";

$reference = randomPrefix(5);

$query2 = mysql_query("SELECT * FROM tbl_budget WHERE AreaId = '$area' ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row2 = mysql_fetch_array($query2);

$old_system_balance = $row2['SystemBalance'];
$system_balance = $old_system_balance + $deposit;

mysql_query("INSERT INTO tbl_budget (Date,AreaId,reference,Deposit,AccumulatedDeposits,SystemBalance,TransactionAmount,Description,SearchDate,TransactionType) VALUES ('$date','$area','$reference','$deposit','$accumulated','$system_balance','$deposit','$comment','$search_date','1')")or die(mysql_error());
}

if($_SESSION['kt_AreaId'] == 0){
if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
}
if(isset($_POST['date1'])){
$date1 = $_POST['date1'];
$date_1 = date('Y m d', strtotime($date1));
$date2 = $_POST['date2'];
$date_2 = date('Y m d', strtotime($date2));
$areaid = "AreaId = '". $areaid ."'";
$where = "WHERE SearchDate >= '". $date_1 ."' AND SearchDate <= '". $date_2 ."' AND ". $areaid ." AND ";
} else {
$where = "WHERE AreaId = '". $areaid ."' AND";
}} elseif($_SESSION['kt_AreaId'] != 0){
$areaid = $_SESSION['kt_AreaId'];
if(isset($_POST['date1'])){
$date1 = $_POST['date1'];
$date_1 = date('Y m d', strtotime($date1));
$date2 = $_POST['date2'];
$date_2 = date('Y m d', strtotime($date2));
$areaid = "AreaId = '". $areaid ."'";

$where = "WHERE SearchDate >= '". $date_1 ."' AND SearchDate <= '". $date_2 ."' AND ". $areaid ." AND ";
} else {
$where = "WHERE AreaId = '". $areaid ."'";
}}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_budget $where TransactionType = 1 ORDER BY Id DESC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_areas";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>
<?php
// Make a custom transaction instance
$customTransaction = new tNG_custom($conn_seavest);
$tNGs->addTransaction($customTransaction);
// Register triggers
$customTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "Submit");
$customTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
// Add columns
$customTransaction->addColumn("deposit", "STRING_TYPE", "POST", "deposit");
$customTransaction->addColumn("area", "STRING_TYPE", "POST", "area");
// End of custom transaction instance
?>
<?php
// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
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
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
      </td>
    <td valign="top"><table width="760" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center"><p>&nbsp;</p>
          <p>&nbsp;</p>
          <form name="form1" method="post" action="deposit.php">
<div style="padding-left:2px; border:solid 1px #cccccc; margin:2px; width:350px">
      <table width="350" border="0" cellpadding="5" cellspacing="5" bgcolor="#EEEEEE" class="combo_bold">
        <tr>
          <td nowrap>Amount</td>
          <td nowrap><input name="deposit" type="text" class="tarea" id="deposit" size="45">
          &nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td nowrap>Area</td>
          <td nowrap><select name="area" class="tarea" id="area">
		  <option value="">Select one...</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Recordset2['Id']?>"><?php echo $row_Recordset2['Area']?></option>
            <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td nowrap>&nbsp;</td>
          <td align="right" nowrap><input name="Submit" type="submit" class="combo_bold_btn" value="Deposit">
            &nbsp;&nbsp;&nbsp; </td>
        </tr>
      </table>
    </div>          </form>          
          <p>&nbsp;</p>
		  <table border="0" cellpadding="2" cellspacing="3">
		  <tr>
		    <td width="100" class="combo_bold">Date</td>
		    <td width="130" class="combo_bold">Amount Deposited </td>
		    <td class="combo_bold">Accumulated Deposits </td>
		  </tr>
		  <?php do { ?>
		  <tr>
                  <td width="100" class="combo"><?php echo $row_Recordset1['Date']; ?></td>
                  <td width="130" class="combo">R<?php echo $row_Recordset1['Deposit']; ?></td>
                  <td class="combo">R<?php echo $row_Recordset1['AccumulatedDeposits']; ?></td>
                </tr>
              <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
		  </table>
		  <p>&nbsp;</p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
