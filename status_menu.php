<?php 
session_start();

if(!isset($_SESSION['kt_login_id'])){
header('Location: index.php');
}

if(isset($_GET['location'])){
$_SESSION['location'] = $_GET['location'];
}

require_once('Connections/seavest.php'); ?>
<?php
require_once('includes/tng/tNG.inc.php');

mysql_select_db($database_seavest, $seavest);
$query_menu = "SELECT * FROM tbl_areas";
$menu = mysql_query($query_menu, $seavest) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);

if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = "-1";
if (isset($_SESSION['areaid'])) {
  $colname_area = (get_magic_quotes_gpc()) ? $_SESSION['areaid'] : addslashes($_SESSION['areaid']);
}
mysql_select_db($database_seavest, $seavest);
$query_area = sprintf("SELECT * FROM tbl_areas WHERE Id = %s", $colname_area);
$area = mysql_query($query_area, $seavest) or die(mysql_error());
$row_area = mysql_fetch_assoc($area);
$totalRows_area = mysql_num_rows($area);
?>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-size: 16px;
}
#bg {
	background-image: url(images/menu_line2.jpg);
	background-repeat: repeat-y;
	color:#18519B;
	font-size:12px;
	font-family:arial;
	font-weight:bold;
	margin: 0px;
	padding: 0px;
	width: 200px;
}
#top{
	height:127px;
	background-color: #18519b;
	padding-left:20px;
	padding-top:20px;
	margin: 0px;
	width: 180px;
	padding-right: 0px;
	padding-bottom: 0px;
}
#menu_header {
	line-height: 31px;
	background-image: url(images/menu_header2.jpg);
	height: 31px;
	width: 180px;
	color: #FF0000;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 20px;
	font-family: arial;
	font-size: 14px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 10px;
	margin-left: 0px;
}
#menu_header2 {
	line-height: 31px;
	background-image: url(images/menu_header.jpg);
	height: 31px;
	width: 180px;
	color: #FF0000;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 20px;
	font-family: arial;
	font-size: 14px;
	margin-top: 10px;
	margin-right: 0px;
	margin-bottom: 10px;
	margin-left: 0px;
}
-->
</style>
<div id="bg">
<div id="top"><span class="style1">
</span>
  </div>
<div id="menu_header">
    Jobcards
  </div>
  <div style="padding-left:20px"><a href="jc_status.php?location=jc_status" class="menu2" <?php if($_SESSION['location'] == "jc_status"){ ?> style="color:#FF0000"<?php } ?>>Overdue</a><br />
  </div>
   <div id="menu_header2"><a href="logout.php" class="logout" style="text-decoration:none">Logout</a></div>
</div>