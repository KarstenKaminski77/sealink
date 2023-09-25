<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the Navigation classes
require_once('includes/nav/NAV.php'); 

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

if(isset($_GET['Id'])){
	
	select_db();
	
	$quoteno = $_GET['Id'];
	
	mysql_query("DELETE FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysql_error());
	
}

if(isset($_GET['Restore'])){
	
	select_db();
	
	$quoteno = $_GET['Restore'];
	
	mysql_query("UPDATE tbl_qs SET Status = '0' WHERE QuoteNo = '$quoteno'")or die(mysql_error());
	
	header('Location: quote_calc.php?Id='. $quoteno);
}

$nav_Recordset3 = new NAV_Regular("nav_Recordset3", "Recordset3", "", KT_getPHP_SELF(), 50);


mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$maxRows_Recordset3 = $_SESSION['max_rows_nav_Recordset3'];
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

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

if($_SESSION['kt_login_level'] >= 1){
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['kt_AreaId'];
}

$where = "AND tbl_qs.AreaId = ". $areaid ."";

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_qs.QuoteNo, tbl_companies.Name, tbl_qs.Date, tbl_qs.JobDescription, tbl_qs.Id FROM ((tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_qs.SiteId) WHERE Status = '15' $where GROUP BY QuoteNo ORDER BY tbl_qs.Id DESC";
$query_limit_Recordset3 = sprintf("%s LIMIT %d, %d", $query_Recordset3, $startRow_Recordset3, $maxRows_Recordset3);
$Recordset3 = mysql_query($query_limit_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);

if (isset($_GET['totalRows_Recordset3'])) {
  $totalRows_Recordset3 = $_GET['totalRows_Recordset3'];
} else {
  $all_Recordset3 = mysql_query($query_Recordset3);
  $totalRows_Recordset3 = mysql_num_rows($all_Recordset3);
}
$totalPages_Recordset3 = ceil($totalRows_Recordset3/$maxRows_Recordset3)-1;

$nav_Recordset3->checkBoundries();

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript">
  $NAV_SETTINGS = {
     'show_as_buttons': false
  }
</script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
      <?php
include('menu.php'); ?>
        <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="200" colspan="4" align="center" bordercolor="#9E9E9E"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
            <tr>
              <td colspan="4" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4"><div style="padding-left:25px">
                <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="650" align="left" nowrap class="combo"><table width="800" border="0" align="center" cellpadding="3" cellspacing="1" class="combo">
                      <tr class="td-header">
                        <td width="50" align="center" bordercolor="#68A4E6"><strong>Quote </strong></td>
                        <td width="150" bordercolor="#68A4E6"><strong>Company</strong></td>
                        <td width="300" bordercolor="#68A4E6"><strong>Site Address </strong></td>
                        <td width="75" bordercolor="#68A4E6"><strong>Date</strong></td>
                        <td width="80" bordercolor="#68A4E6">Age</td>
                        <td width="40" align="center" bordercolor="#68A4E6">&nbsp;</td>
                        <td width="40" align="center" bordercolor="#68A4E6">Delete</td>
                        </tr>
					<?php do { ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                          <td width="65" align="center">
						  <a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>">
						  <?php echo $row_Recordset3['QuoteNo']; ?></a>
						  </td>
                          <td width="150">
						  <a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu">
						  <?php echo $row_Recordset3['Name']; ?></a>
						  </td>
                          <td width="300">
						  <a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu">
						  <?php echo $row_Recordset3['Name_1']; ?></a>
						  </td>
                          <td width="75">
                            <a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu">
                              <?php echo $row_Recordset3['Date']; ?></a>
                          </td>
                          <td width="80" nowrap><?php 
						  $quoteno = $row_Recordset3['QuoteNo'];
						  time_schedule_quotes($quoteno); ?></td>
                          <td width="40" align="center"><a href="recycle-qs.php?Restore=<?php echo $row_Recordset3['QuoteNo']; ?>"><img src="images/icons/btn-debtors.png" title="Awaiting Approval" width="25" height="25" border="0"></a></td>
                          <td width="40" align="center"><a href="recycle-qs.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>"><img src="images/icons/btn-bin.png" title="Permanently Delete" width="25" height="25" border="0"></a></td>
                          </tr>
                          <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                    </table></td>
                    </tr>
                </table>
              </div>
                <br>
                <br>
                <BR />
                <div class="KT_bottomnav" align="center">
                  <div class="combo"> <BR />
                    <div class="KT_bottomnav">
                      <div>
                        <?php
      //Display Navigation		
      $nav_Recordset3->Prepare();
      require("includes/nav/NAV_Text_Navigation.inc.php");
    ?>
                      </div>
                    </div>
                  </div>
                </div></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
