<?php 
session_start();

if(isset($_GET['location'])){
$_SESSION['location'] = $_GET['location'];
}

require_once('../Connections/seavest.php'); ?>
<?php
require_once('../includes/tng/tNG.inc.php');

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
	background-image: url(../images/menu_line2.jpg);
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
	background-image: url(../images/menu_header2.jpg);
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
	background-image: url(../images/menu_header.jpg);
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
<?php if(isset($_SESSION['kt_login_id'])){ ?>
<?php if($_SESSION['kt_AreaId'] == 0){ ?>

<div id="top"><span class="style1">
<?php 
if(isset($_SESSION['areaid'])){
echo $row_area['Area']; 
} else {
echo "Please select a region";
}
?>
</span>
<form id="form1" name="form1" method="post" action="">
  <p>
    <select name="master_area" class="tarea" id="master_area">
        <option value="">Select one...</option>
      <?php
do {  
?>
      <option value="<?php echo $row_menu['Id']?>"><?php echo $row_menu['Area']?></option>
      <?php
} while ($row_menu = mysql_fetch_assoc($menu));
  $rows = mysql_num_rows($menu);
  if($rows > 0) {
      mysql_data_seek($menu, 0);
	  $row_menu = mysql_fetch_assoc($menu);
  }
?>
    </select>
    <input type="submit" name="Submit" value="Go" />
  </p>
  </form>
  </div>
<?php } ?>
  <div id="menu_header">
    Quotations
  </div>
  <div style="padding-left:20px">
  <a href="../quote.php?location=quote_new" class="menu2" <?php if($_SESSION['location'] == "quote_new"){ ?> style="color:#FF0000"<?php } ?>>Create New</a><br />
  <a href="../select_q_pending.php?location=quote_pending" class="menu2" <?php if($_SESSION['location'] == "quote_pending"){ ?> style="color:#FF0000"<?php } ?>>Pending&nbsp; <?php quotes_pending(); ?></a><br />
  <?php if($userlevel == 1){ ?>
  <a href="../select_q.php?location=quote_submitted" class="menu2" <?php if($_SESSION['location'] == "quote_submitted"){ ?> style="color:#FF0000"<?php } ?>>Submitted  &nbsp; <?php quotes_submitted(); ?></a>
  <?php } ?>
  </div>
   <?php } if(isset($_SESSION['kt_login_id'])){ ?>
  <div id="menu_header2">
  Jobcards  </div>
  <div style="padding-left:20px">
  <?php if($userlevel == 1){ ?>
  <a href="../search.php?location=jc_search" class="menu2" <?php if($_SESSION['location'] == "jc_search"){ ?> style="color:#FF0000"<?php } ?>>Search</a><br /> 
  <?php } ?>   
  <a href="../job_card.php?location=jc_new" class="menu2" <?php if($_SESSION['location'] == "jc_new"){ ?> style="color:#FF0000"<?php } ?>>Create New</a><br />  
  <a href="../jc_select_q.php?location=jc_qued" class="menu2" <?php if($_SESSION['location'] == "jc_qued"){ ?> style="color:#FF0000"<?php } ?>>Qued  &nbsp; <?php jc_que(); ?></a><br />
  <a href="../jc_select.php?location=jc_progress" class="menu2" <?php if($_SESSION['location'] == "jc_progress"){ ?> style="color:#FF0000"<?php } ?>>In Progress  &nbsp; <?php jc_current(); ?></a><br />
  <a href="../jc_pending.php?location=jc_pending" class="menu2" <?php if($_SESSION['location'] == "jc_pending"){ ?> style="color:#FF0000"<?php } ?>>Pending &nbsp; <?php jc_onhold(); ?></a><br />
  <a href="../jc_rejected.php?location=jc_rejected" class="menu2" <?php if($_SESSION['location'] == "jc_rejected"){ ?> style="color:#FF0000"<?php } ?>>Rejected &nbsp; <?php jc_rejected(); ?></a><br />
  <?php } if($userlevel == 1){ ?>
  <a href="../jc_archives.php?location=jc_archives" class="menu2" <?php if($_SESSION['location'] == "jc_archives"){ ?> style="color:#FF0000"<?php } ?>>Archive &nbsp; <?php jc_archives(); ?></a><br />
  <?php } ?>
  </div>
  <div id="menu_header2">Bank</div>
  <div style="padding-left:20px">
  <?php if($userlevel != 1){ ?>
  <a href="index.php?location=bank_cb" class="menu2" <?php if($_SESSION['location'] == "bank_cb"){ ?> style="color:#FF0000"<?php } ?>>Cash Book</a><br />
  <?php } if($userlevel == 1){ ?>
  <a href="deposit.php?location=bank_d" class="menu2" <?php if($_SESSION['location'] == "bank_d"){ ?> style="color:#FF0000"<?php } ?>>Deposit Funds</a><br />
  <?php } ?>
  <a href="statements.php?location=bank_s" class="menu2" <?php if($_SESSION['location'] == "bank_s"){ ?> style="color:#FF0000"<?php } ?>>Statements</a>
  </div>
  <div id="menu_header2">Register</div>
  <div style="padding-left:20px">
  <?php if($userlevel != 1){ ?>
  <a href="../register.php?location=register" class="menu2" <?php if($_SESSION['location'] == "register"){ ?> style="color:#FF0000"<?php } ?>>Weekly Register</a><br />
  <?php } if($userlevel == 1){ ?>
  <a href="../register_select.php?location=register_view" class="menu2" <?php if($_SESSION['location'] == "register_view"){ ?> style="color:#FF0000"<?php } ?>>View Register</a><br />
  <?php } ?>
  </div>
  <div id="menu_header2">Appraisals</div>
  <div style="padding-left:20px">
  <?php if($userlevel != 1){ ?>
  <a href="../appraisal_select.php?location=app" class="menu2" <?php if($_SESSION['location'] == "app"){ ?> style="color:#FF0000"<?php } ?>>Appraisals</a><br />
  <?php  } if($userlevel == 1){ ?>
  <a href="../appraisal_review_select.php?location=app_rev" class="menu2" <?php if($_SESSION['location'] == "app_rev"){ ?> style="color:#FF0000"<?php } ?>>Review Appraisals</a><br />
  <?php } ?>
  </div>
  <div id="menu_header2">Equipment</div>
  <div style="padding-left:20px">
  <?php if($userlevel == 1){ ?>
  <a href="../tools_select.php?location=equipment_r" class="menu2" <?php if($_SESSION['location'] == "equipment_r"){ ?> style="color:#FF0000"<?php } ?>>Review Equipment</a><br />
    <?php } if($userlevel != 1){ ?>
  <a href="../tools_update.php?location=equipment_u" class="menu2" <?php if($_SESSION['location'] == "equipment_u"){ ?> style="color:#FF0000"<?php } ?>>Update Equipment</a><br />
  <?php } ?>
  </div>
  <?php if($userlevel == 1){ ?>
  <div id="menu_header2">Invoices</div>
  <div style="padding-left:20px">
  <a href="../invoice_pending.php?location=invoice_pending" class="menu2" <?php if($_SESSION['location'] == "invoice_pending"){ ?> style="color:#FF0000"<?php } ?>>Pending &nbsp; <?php pending_inv(); ?></a><br />
  <a href="../invoice_select.php?location=invoice_approved" class="menu2" <?php if($_SESSION['location'] == "invoice_approved"){ ?> style="color:#FF0000"<?php } ?>>Approved &nbsp; <?php approved_inv(); ?></a><br />
  <a href="../invoice_outstanding.php?location=invoice_outstanding" class="menu2" <?php if($_SESSION['location'] == "invoice_outstanding"){ ?> style="color:#FF0000"<?php } ?>>Debtors &nbsp; <?php archived_inv(); ?></a><br />
  <a href="../invoice_archives.php?location=invoice_archives" class="menu2" <?php if($_SESSION['location'] == "invoice_archives"){ ?> style="color:#FF0000"<?php } ?>>Archive &nbsp; <?php paid_inv(); ?></a><br />
  <a href="../outbox.php?location=outbox" class="menu2" <?php if($_SESSION['location'] == "outbox"){ ?> style="color:#FF0000"<?php } ?>>Outbox &nbsp; <?php outbox(); ?></a><br />  
  <a href="../invoice_sent.php?location=sent" class="menu2" <?php if($_SESSION['location'] == "sent"){ ?> style="color:#FF0000"<?php } ?>>Sent Items &nbsp; <?php sent(); ?></a>
  </div>
  <?php } ?>
<div id="menu_header2"><a href="../logout.php" class="logout" style="text-decoration:none; color:#FF0000">Logout</a></div>
</div>
<?php
mysql_free_result($area);
?>
