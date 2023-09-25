<?php
session_start();

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

mysql_connect("sql25.jnb1.host-h.net","kwdaco_43","SBbB38c8Qh8") or die(mysql_error());
mysql_select_db("seavest_db333") or die(mysql_error());

$query = mysql_query("SELECT * FROM tbl_users WHERE Username = '$username' AND Password = '$password'")or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

if($numrows == 1){
$_SESSION['userid'] = $row['Id'];
header('Location: jc_select.php');
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
header('Location: index2.php?'. $string1.$string2);
}}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
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
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
    </td>
    <td valign="top">
    <table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
              </tr>
          
          <tr>
            <td colspan="3" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
              </tr>
          <tr>
            <td colspan="3"><p>&nbsp;</p>
              <p>                            
              <form action="index2.php" method="post" name="form1" class="KT_tngformerror" id="form1">
                <table align="center" cellpadding="2" cellspacing="3" class="combo_bold">
                  <tr>
                    <td height="39" valign="middle" class="text"><span class="style4">Username</span></td>
                    <td valign="middle" class="text"><input name="username" type="text" class="btns" id="username" size="25" />
                      &nbsp;
                      <?php validate_username(); ?></td>
                  </tr>
                  <tr>
                    <td class="text"><span class="style4">Password</span></td>
                    <td class="text"><input name="password" type="text" class="btns" id="password" size="25" />
                      &nbsp;
                      <?php validate_password(); ?></td>
                  </tr>
                  
                  <tr>
                    <td colspan="2" align="right"><input name="kt_login1" type="submit" class="tarea2" id="kt_login1" value="Login" />                    </td>
                  </tr>
                </table>
              </form>
              <p>&nbsp;</p>
              </p></td>
              </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>