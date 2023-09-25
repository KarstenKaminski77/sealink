<?php
session_start();

require_once('../functions/functions.php');

select_db();

function validate_username(){
if(isset($_GET['username'])){
echo "<span class=\"error\">Invalid Username</span>";
}}
function validate_password(){
if(isset($_GET['password'])){
echo "<span class=\"error\">Invalid Password</span>";
}}

if(isset($_POST['username'])){
$username = $_POST['username'];
$password = $_POST['password'];

$query = mysql_query("SELECT * FROM tbl_module_users WHERE Username = '$username' AND Password = '$password'")or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

if($numrows == 1){
$_SESSION['user_id'] = $row['Id'];
$_SESSION['area'] = $row['AreaId'];
$_SESSION['company_id'] = $row['CompanyId'];
header('Location: index.php');
} else {
$query1 = mysql_query("SELECT * FROM tbl_module_users WHERE Username = '$username'")or die(mysql_error());
$numrows1 = mysql_num_rows($query1);
if($numrows1 == 0){
$string1 = 'username';
}
$query = mysql_query("SELECT * FROM tbl_module_users WHERE Password = '$password'")or die(mysql_error());
$numrows = mysql_num_rows($query);
if($numrows == 0){
$string2 = '&password';
}
header('Location: index.php?'. $string1.$string2);
}}
?>
