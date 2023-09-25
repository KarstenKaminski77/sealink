<?php 
session_start();

require_once('functions/functions.php');

require_once('includes/tng/tNG.inc.php');

if(!isset($_SESSION['kt_login_id'])){
header('Location: index.php');
}

if(isset($_GET['location'])){
$_SESSION['location'] = $_GET['location'];
}

require_once('Connections/seavest.php');

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
	<link rel="stylesheet" type="text/css" href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/sdmenu/sdmenu.css" />
	<script type="text/javascript" src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/sdmenu/sdmenu.js">
		/***********************************************
		* Slashdot Menu script- By DimX
		* Submitted to Dynamic Drive DHTML code library: http://www.dynamicdrive.com
		* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
		***********************************************/
	</script>
	<script type="text/javascript">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.init();
	};
	// ]]>
	</script>
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
	width: 175px;
	color: #FF0000;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 25px;
	font-family: arial;
	font-size: 14px;
	margin-top: 10px;
	margin-right: 0px;
	margin-bottom: 10px;
	margin-left: 0px;
	clear: both;
}
-->
</style>
<table height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" background="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/images/menu_header2.jpg">
<div id="bg">
<?php if(isset($_SESSION['kt_login_id'])){ ?>
<?php if($_SESSION['kt_login_level'] >= 1){ ?>

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
<div style="float: left" id="my_menu" class="sdmenu">
  <?php if(($userlevel == 1) || ($userlevel == 2)){ ?>
  <div class="collapsed">
  <span>Quotations</span>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/search_q.php?location=qs_search" class="menu2" <?php if($_SESSION['location'] == "qs_search"){ ?> style="color:#FF0000"<?php } ?>>Search</a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/quote.php?location=quote_new" class="menu2" <?php if($_SESSION['location'] == "quote_new"){ ?> style="color:#FF0000"<?php } ?>>Create New</a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/select_q_pending.php?location=quote_pending" class="menu2" <?php if($_SESSION['location'] == "quote_pending"){ ?> style="color:#FF0000"<?php } ?>>Awaiting Approval &nbsp; <?php quotes_pending(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/quotation_outbox.php?location=q_outbox" class="menu2" <?php if($_SESSION['location'] == "q_outbox"){ ?> style="color:#FF0000"<?php } ?>>Outbox &nbsp; <?php quote_outbox(); ?></a>  
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/select_q.php?location=quote_submitted" class="menu2" <?php if($_SESSION['location'] == "quote_submitted"){ ?> style="color:#FF0000"<?php } ?>>Submitted  &nbsp; <?php quotes_submitted(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/select_q_archives.php?location=quote_archives" class="menu2" <?php if($_SESSION['location'] == "quote_archives"){ ?> style="color:#FF0000"<?php } ?>>Archives  &nbsp; <?php quotes_archives(); ?></a>
  </div>
  <?php } ?>
  <?php } ?>
  
	  
      <div>
        <span>Jobcards</span>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/search.php?location=jc_search" class="menu2" <?php if($_SESSION['location'] == "jc_search"){ ?> style="color:#FF0000"<?php } ?>>Search</a> 
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/job_card.php?location=jc_new" class="menu2" <?php if($_SESSION['location'] == "jc_new"){ ?> style="color:#FF0000"<?php } ?>>Create New</a>  
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/jc_select_q.php?location=jc_qued" class="menu2" <?php if($_SESSION['location'] == "jc_qued"){ ?> style="color:#FF0000"<?php } ?>>Qued  &nbsp; <?php jc_que(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/jc_select.php?location=jc_progress" class="menu2" <?php if($_SESSION['location'] == "jc_progress"){ ?> style="color:#FF0000"<?php } ?>>In Progress  &nbsp; <?php jc_current(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/jc_costing.php?location=jc_costing" class="menu2" <?php if($_SESSION['location'] == "jc_costing"){ ?> style="color:#FF0000"<?php } ?>>Costing  &nbsp; <?php jc_costing(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/jc_pending.php?location=jc_pending" class="menu2" <?php if($_SESSION['location'] == "jc_pending"){ ?> style="color:#FF0000"<?php } ?>>Pending &nbsp; <?php jc_onhold(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/jc_rejected.php?location=jc_rejected" class="menu2" <?php if($_SESSION['location'] == "jc_rejected"){ ?> style="color:#FF0000"<?php } ?>>Rejected &nbsp; <?php jc_rejected(); ?></a>
  <?php if($userlevel == 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/jc_archives.php?location=jc_archives" class="menu2" <?php if($_SESSION['location'] == "jc_archives"){ ?> style="color:#FF0000"<?php } ?>>Archive &nbsp; <?php jc_archives(); ?></a>
  <?php } ?>
      </div>
      <div>
        <span>Bank</span>
  <?php if($userlevel != 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/cash_book/index.php?location=bank_cb" class="menu2" <?php if($_SESSION['location'] == "bank_cb"){ ?> style="color:#FF0000"<?php } ?>>Cash Book</a>
  <?php } if($userlevel == 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/cash_book/deposit.php?location=bank_d" class="menu2" <?php if($_SESSION['location'] == "bank_d"){ ?> style="color:#FF0000"<?php } ?>>Deposit Funds</a>
  <?php } ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/cash_book/statements.php?location=bank_s" class="menu2" <?php if($_SESSION['location'] == "bank_s"){ ?> style="color:#FF0000"<?php } ?>>Statements</a>
      </div>
      <div>
        <span>Register</span>
  <?php if($userlevel != 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/register.php?location=register" class="menu2" <?php if($_SESSION['location'] == "register"){ ?> style="color:#FF0000"<?php } ?>>Weekly Register</a>
  <?php } if($userlevel == 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/register_select.php?location=register_view" class="menu2" <?php if($_SESSION['location'] == "register_view"){ ?> style="color:#FF0000"<?php } ?>>View Register</a>
  <?php } ?>
      </div>
      <div>
        <span>Appraisals</span>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/appraisal_select.php?location=app" class="menu2" <?php if($_SESSION['location'] == "app"){ ?> style="color:#FF0000"<?php } ?>>Appraisals</a>
  <?php if($userlevel == 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/appraisal_review_select.php?location=app_rev" class="menu2" <?php if($_SESSION['location'] == "app_rev"){ ?> style="color:#FF0000"<?php } ?>>Review Appraisals</a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/technician-scores.php?location=tech_score" class="menu2" <?php if($_SESSION['location'] == "tech_score"){ ?> style="color:#FF0000"<?php } ?>>Technician Scores </a>
  <?php } ?>
      </div>
      <div>
        <span>Equipment</span>
  <?php if($userlevel == 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/tools_select.php?location=equipment_r" class="menu2" <?php if($_SESSION['location'] == "equipment_r"){ ?> style="color:#FF0000"<?php } ?>>Review Equipment</a>
    <?php } if($userlevel != 1){ ?>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/tools_update.php?location=equipment_u" class="menu2" <?php if($_SESSION['location'] == "equipment_u"){ ?> style="color:#FF0000"<?php } ?>>Update Equipment</a>
  <?php } ?>
      </div>
	  <?php if($userlevel == 1){ ?>
      <div>
        <span>Invoices</span>
  
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/search_inv.php?location=inv_search" class="menu2" <?php if($_SESSION['location'] == "inv_search"){ ?> style="color:#FF0000"<?php } ?>>Search</a> 
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/invoice_pending.php?location=invoice_pending" class="menu2" <?php if($_SESSION['location'] == "invoice_pending"){ ?> style="color:#FF0000"<?php } ?>>Pending &nbsp; <?php pending_inv(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/invoice_select.php?location=invoice_approved" class="menu2" <?php if($_SESSION['location'] == "invoice_approved"){ ?> style="color:#FF0000"<?php } ?>>Approved &nbsp; <?php approved_inv(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/invoice_outstanding.php?location=invoice_outstanding" class="menu2" <?php if($_SESSION['location'] == "invoice_outstanding"){ ?> style="color:#FF0000"<?php } ?>>Debtors &nbsp; <?php archived_inv(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/inv-statements.php?location=invoice_statements" class="menu2" <?php if($_SESSION['location'] == "invoice_statements"){ ?> style="color:#FF0000"<?php } ?>>Statements &nbsp; <?php archived_inv(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/invoice_archives.php?location=invoice_archives" class="menu2" <?php if($_SESSION['location'] == "invoice_archives"){ ?> style="color:#FF0000"<?php } ?>>Archive &nbsp; <?php paid_inv(); ?></a>
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/outbox.php?location=outbox" class="menu2" <?php if($_SESSION['location'] == "outbox"){ ?> style="color:#FF0000"<?php } ?>>Outbox &nbsp; <?php outbox(); ?></a>  
  <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/invoice_sent.php?location=sent" class="menu2" <?php if($_SESSION['location'] == "sent"){ ?> style="color:#FF0000"<?php } ?>>Sent Items &nbsp; <?php sent(); ?></a></div><?php } ?>
    </div>
<div id="menu_header2"><a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/logout.php" class="logout" style="text-decoration:none">Logout</a></div>
</div>	</td>
  </tr>
</table>

<?php
echo $_SESSION['kt_login_level'] .'x';
mysql_free_result($area);
?>