<?php 
session_start();

if($_SESSION['kt_login_level'] >= 1){
if(isset($_SESSION['areaid'])){
$areaid = $_SESSION['areaid'];
} else {
$_SESSION['areaid'] = '1';
}}

require_once('../Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_seavest = new KT_connection($seavest, $database_seavest);

//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

require_once('../functions/functions.php');

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_budget";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_beneficiaries";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

select_db();

$transactionid = $_GET['Id'];

if(isset($_POST['delete'])){
$id = $_POST['id'];

$delete = $_POST['delete'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_budget WHERE Id = '$c'") or die(mysql_error());
}}

$rows = '1';

if(isset($_POST['rows'])){
$rows = $_POST['rows'];
} 
if(isset($_POST['numrows'])){
$rows = $_POST['numrows'];
} 

if(isset($_POST['Submit'])){
$id_b = $_POST['id'];
$day_b = $_POST['day'];
$month_b = $_POST['month'];
$year_b = $_POST['year'];
$beneficiary_b = $_POST['beneficiary'];
$reference_b = $_POST['reference'];
$description_b = $_POST['description'];
$amount_b = $_POST['amount'];
$currency_b = $_POST['currency'];
$transactionid_b = $_GET['Id'];

for($i=0;$i<$rows;$i++){

$id = $id_b[$i];
$day = date('d');
$month = date('M');
$year = date('Y');
$date = $day ." ". $month ." ". $year;
$beneficiary = $beneficiary_b[$i];
$reference = $reference_b[$i];
$description = $description_b[$i];
$amount = $amount_b[$i];
$searchdate = date('Y m d',strtotime($date));
$transactionid = $transactionid_b;
$bank_balance = $_COOKIE['bank_balance'];

mysql_query("INSERT INTO tbl_budget (Date,AreaId,Beneficiary,Reference,BankBalance,Description,TransactionAmount,SearchDate,TransactionId) VALUES ('$date','$areaid','$beneficiary','$reference','$bank_balance','$description','$amount','$searchdate','$transactionid')") or die(mysql_error());

$transactionid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_budget WHERE TransactionId = '$transactionid' AND Ini = '0' ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$id = $row['Id'];
$amount = $row['TransactionAmount'];
$transactionid = $_GET['Id'];

if(isset($_SESSION['new_system_balance'])){
$system_balance = $_SESSION['new_system_balance'] - $amount;
$_SESSION['new_system_balance'] = $system_balance;
echo $system_balance .' - 1';
} else {
$system_balance = $_COOKIE['system_balance'] - $amount;

$_SESSION['new_system_balance'] = $system_balance;

echo $system_balance .' - 2';
}

mysql_query("UPDATE tbl_budget SET SystemBalance = '$system_balance' WHERE Id = '$id'") or die(mysql_error());


}}

if(isset($_POST['Complete'])){

unset($_SESSION['new_system_balance']);
$expire = time() - 3600;
setcookie("bank_balance","0",$expire);
setcookie("system_balance","0",$expire);
header('Location: statements.php');
}

$query = mysql_query("SELECT * FROM tbl_budget ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);
$bank_total = $row['BankBalance'];
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
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
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
<script type="text/javascript" src="../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../includes/resources/calendar.js"></script>
</head>

<body>
<table width="823" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
        <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td width="1231" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td>
		  <div id="add_row">
		    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="combo_bold">
		      <tr>
		        <td width="17%" nowrap><form name="form2" method="post" action="select.php?Id=<?php echo $transactionid; ?>">
		          <select name="rows" class="tarea2" id="rows">
		            <option value="0">0</option>
		            <option value="1">1</option>
		            <option value="2">2</option>
		            <option value="3">3</option>
		            <option value="4">4</option>
		            <option value="5">5</option>
		            </select>
		          <input name="Submit2" type="submit" class="tarea2" value="Add Rows">
		          </form></td>
                <td nowrap>
                  <span style="color:<?php echo colour_bank($areaid); ?>">System Balance R <?php echo $bank_total; ?>&nbsp; 
				  <?php 
				  balance_out($areaid); 
				  ?>
                  </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td width="26%" valign="top" nowrap>&nbsp;</td>
		      </tr>
	          </table>
		    </div>
		  </div>
	<p>&nbsp;</p>
    <div style="margin-left:28px">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td>
          <p>&nbsp;</p>
          <form name="form1" method="post" action="select.php?Id=<?php echo $transactionid; ?>">
            <table border="0" cellpadding="5" cellspacing="1" bordercolor="#FFFFFF">
              <tr>
                <td width="150" bordercolor="#68A4E6" class="tb_border">Beneficiary</td>
                <td width="120" bordercolor="#68A4E6" class="tb_border">Reference</td>
                <td width="330" bordercolor="#68A4E6" class="tb_border">Comments</td>
                <td width="100" bordercolor="#68A4E6" class="tb_border">Amount</td>
                <td bordercolor="#68A4E6">&nbsp;</td>
                </tr>
            <?php 
            if(isset($_POST['rows'])){
            $rows = $_POST['rows'];
            } else {
            $rows = '1';
            }
			for($i=0;$i<$rows;$i++){
			?>
                <tr><td style="padding:0px">
                <input name="beneficiary[]" type="text" class="tarea" id="beneficiary[]" value="<?php echo $row['Beneficiary']; ?>" style="width:154px" maxlength="25">
                </td><td style="padding:0px">
                <input name="reference[]" type="text" class="tarea" id="reference[]" value="<?php echo $row['Reference']; ?>" style="width:124px" maxlength="20">
                </td><td style="padding:0px">
                <input name="description[]" type="text" class="tarea" id="description[]" value="<?php echo $row['Description']; ?>" style="width:334px" maxlength="60" />
                </td><td align="right" style="padding:0px">
                <input name="amount[]" type="text" class="tarea" id="amount[]" style="text-align:right; width:104px" value="<?php echo $row['TransactionAmount']; ?>" maxlength="15">
                </td><td align="right">
                <input name="id[]" type="hidden" id="id[]" value="<?php echo $row['Id']; ?>" />
                <input type="hidden" name="system-balance[]" id="system-balance[]" value="<?php echo $row['SystemBalance']; ?>">
                </td>
                </tr>
                <?php } ?>
                <tr>
              <?php
$transactionid = $_GET['Id'];
if(isset($_POST['where']) && ($_POST['where'] == 1)){
if(isset($_POST['date1'])){
$date1 = $_POST['date1'];
$date_1 = date('Y m d', strtotime($date1));
$date2 = $_POST['date2'];
$date_2 = date('Y m d', strtotime($date2));
$where = "WHERE SearchDate >= '". $date_1 ."' AND SearchDate <= '". $date_2 ."' AND  TransactionId = '$transactionid'";
} if(isset($_POST['where']) && ($_POST['where'] == 2)){
$cat = $_POST['cat'];
$where = "WHERE Beneficiary = '". $cat ."' AND  TransactionId = '$transactionid'";
} else { 
$where = "WHERE 1 = 1 AND  TransactionId = '$transactionid'";
}
$query = "SELECT * FROM tbl_budget $where" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());

} else {
$query = "SELECT * FROM tbl_budget WHERE  TransactionId = '$transactionid' AND Ini = '0' ORDER BY Id ASC" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());
}

if(!isset($where)){
$where = "WHERE TransactionId = '$transactionid'";
}
$query4 = "SELECT SUM(TransactionAmount) FROM tbl_budget $where " or die(mysql_error());
$result4 = mysql_query($query4) or die(mysql_error());
$row4 = mysql_fetch_array($result4);
$total = $row4['SUM(TransactionAmount)'];

while($row = mysql_fetch_array($result)){

$date = $row['Date'];

$split = explode(" ", $date);
$year = $split[2];
$month = $split[1];
$day = $split[0];
?>
              <tr>
                <td class="combo2" style="padding:0px"><?php echo $row['Beneficiary']; ?></td>
                <td class="combo2" style="padding:0px"><?php echo $row['Reference']; ?></td>
                <td class="combo2" style="padding:0px"><?php echo $row['Description']; ?></td>
                <td align="right" class="combo2" style="padding:0px"><?php echo $row['TransactionAmount']; ?></td>
                <td align="right" style="padding:0px">
                <input type="hidden" name="system-balance[]" id="system-balance[]" value="<?php echo $row['SystemBalance']; ?>">
                <input name="delete[]" type="checkbox" id="delete[]" value="<?php echo $row['Id']; ?>" />
                </td>
                </tr>
              <?php } // close loop ?>
              <tr>
                <td colspan="4" align="right" bordercolor="#68A4E6" class="combo_bold" style="padding:0px;">
                R<?php echo $row4['SUM(TransactionAmount)']; ?>
                </td>
                <td bordercolor="#68A4E6" class="combo_bold">&nbsp;</td>
                </tr>
              <tr>
                <td align="right" bordercolor="#68A4E6" class="combo_bold">&nbsp;</td>
                <td align="right" bordercolor="#68A4E6" class="combo_bold">&nbsp;</td>
                <td align="right" bordercolor="#68A4E6" class="combo_bold">&nbsp;</td>
                <td align="right" bordercolor="#68A4E6"><input name="numrows" type="hidden" id="numrows" value="<?php if(isset($_POST['rows'])){ echo $_POST['rows']; } else { echo '1'; } ?>" />
                  <?php balanced($areaid); ?></td>
                <td align="right" bordercolor="#68A4E6" class="combo_bold">&nbsp;</td>
              </tr>
              </table>
          </form>
          </td></tr>
      </table>
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
mysql_close();
?>
