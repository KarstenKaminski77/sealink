<?php
session_start();

require_once('../functions/functions.php');

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

select_db();

$query = mysql_query("SELECT * FROM tbl_users WHERE Username = '$username' AND Password = '$password' AND UserLevel = '10'")or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

if($numrows == 1){
$_SESSION['userid'] = $row['Id'];
header('Location: index.php');
} else {
$query1 = mysql_query("SELECT * FROM tbl_users WHERE Username = '$username'")or die(mysql_error());
$numrows1 = mysql_num_rows($query1);
if($numrows1 == 0){
$string1 = 'username';
}
$query = mysql_query("SELECT * FROM tbl_users WHERE Password = '$password'")or die(mysql_error());
$numrows = mysql_num_rows($query);
if($numrows == 0){
$string2 = '&password';
}
header('Location: login.php?'. $string1.$string2);
}}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seavest Africa Login</title>
<style type="text/css">
<!--
.btns {
	font-family: arial;
	font-size: 12px;
	font-weight: bold;
	color: #0062A9;
	background-color: #FFFFFF;
	border: 1px solid #0080AC;
}
.style1 {	font-family: arial;
	font-size: 16px;
	font-weight: bold;
	color: #FFFFFF;
}
.style2 {font-family: arial}
.style3 {font-size: 12px}
.style4 {	font-family: arial;
	font-size: 12px;
	color: #1378A6;
	font-weight: bold;
}
.error {
	font-family: Tahoma;
	font-size: 11px;
	color: #FF0000;
}
#splash {
    background-image:url(../images/Splash.jpg); 
	background-repeat:no-repeat; 
	background-position:370px bottom;
}
-->
</style>
</head>

<body>
<p>&nbsp;</p>
<table width="569" border="0" align="center" cellpadding="2" cellspacing="3">
  <tr>
    <td><img src="../fpdf16/mail_logo.jpg" width="193" height="160" /></td>
  </tr>
  <tr>
    <td><p>&nbsp;</p>
      <div id="splash">
        <form id="form1" name="form1" method="post" action="login.php">
          <table border="0" align="center" cellpadding="3" cellspacing="3">
            <tr>
              <td height="39" valign="middle" class="text"><span class="style4">Username</span></td>
              <td valign="middle" class="text"><input name="username" type="text" class="btns" id="username" size="25" />
                &nbsp;
                <?php validate_username(); ?></td>
            </tr>
            <tr>
              <td class="text"><span class="style4">Password</span></td>
              <td class="text"><input name="password" type="password" class="btns" id="password" size="25" />
                &nbsp;
                <?php validate_password(); ?></td>
            </tr>
            <tr>
              <td colspan="2" align="center" valign="top" class="text">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" colspan="2" align="right" valign="middle" class="title"><input name="Submit" type="submit" class="btns" value="Login" /></td>
            </tr>
          </table>
        </form>
    </div></td>
  </tr>
</table>
</body>
</html>
