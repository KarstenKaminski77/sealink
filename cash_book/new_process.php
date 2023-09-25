<?php
session_start();

require_once('../includes/tng/tNG.inc.php');

require_once('../functions/functions.php');

$balance = $_POST['balance'];

$expire = 60 * 60 * 24 + time();

setcookie("bank_balance",$balance,$expire);

select_db();

if($_SESSION['kt_login_level'] >= 1){
if(isset($_SESSION['areaid'])){
$areaid = $_SESSION['areaid'];
} else {
$_SESSION['areaid'] = '1';
}}

$query = mysql_query("SELECT * FROM tbl_budget WHERE AreaId = '$areaid' ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$old_balance = $row['SystemBalance'];

$_SESSION['bank_balance'] = $old_balance;

setcookie("system_balance",$old_balance,$expire);

mysql_query("INSERT INTO tbl_budget_numbers (BudgetNo) VALUES ('1')")or die(mysql_error());

$query = mysql_query("SELECT * FROM tbl_budget_numbers ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

$transaction_id = $row['Id'] + 1;

$date = date('d M Y');

mysql_query("INSERT INTO tbl_budget (AreaId,Date,TransactionId,BankBalance,SystemBalance,Ini) VALUES ('$areaid','$date','$transaction_id','$balance','$old_balance','1')")or die(mysql_error());

header('Location: select.php?Id='. $transaction_id .'');
?>

