<?php require_once('Connections/seavest.php'); ?>
<?php
session_start();

if($_SESSION['kt_login_level'] >= 1){
if(isset($_SESSION['areaid'])){
$areaid = $_SESSION['areaid'];
} else {
$_SESSION['areaid'] = '1';
}}

// Load the Navigation classes
require_once('includes/nav/NAV.php'); 

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

$nav_Recordset3 = new NAV_Regular("nav_Recordset3", "Recordset3", "", KT_getPHP_SELF(), 50);

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

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


if($_SESSION['kt_login_level'] == 0){
$areaid = $_SESSION['kt_AreaId'];
}

if($_SESSION['kt_login_level'] != 1){

$query = mysql_query("SELECT * FROM tbl_tool_relation WHERE AreaId = '$areaid'")or die(mysql_error());
$row = mysql_fetch_array($query);
$tool_numrows = mysql_num_rows($query);

if($tool_numrows >= 1){
if($row['Date'] != "Array"){;

$date1 = $row['Date'];
echo $date1;
$date = explode("-", $date1);

$year = $date[0]; 
$month = $date[1];
$day = $date[2];

$current_year = date('Y');
$current_month = date('m');
$current_day = date('d');

$past_date = mktime(0,0,0,$month,$day,$year);

$current_date = mktime(0,0,0,$current_month,$current_day,$current_year);

$difference =  floor(($past_date - $current_date)/86400);

if($difference == 0){
$warning =  "Your tool report is due today";
} elseif(($difference <= 3) && ($difference >= 1)){
$warning =  $difference ." days to go untill your tool report is due...";
} elseif($difference <= -1){
$overdue = $difference * -1;
$warning =  "<span style=\"color: #FF0000\">Your tool report is ". $overdue ." days overdue...</span>";
}

if($difference <= -3){
header('Location: alert.php');
}}}}

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

$where = "AND tbl_jc.AreaId = ". $areaid ."";

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_jc.QuoteNo, tbl_jc.CompanyId, tbl_jc.JobDescription, tbl_jc.SiteId, tbl_jc.CommentText, tbl_jc.Date, tbl_jc.JobcardPDF, tbl_jc.JobNo, tbl_jc.JobId, tbl_companies.Name FROM ((tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) WHERE Status = '2' AND Comment = '1' $where GROUP BY tbl_jc.JobId ORDER BY tbl_jc.Id ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

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
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset2 = new WDG_JsRecordset("Recordset2");
echo $jsObject_Recordset2->getOutput();
//end JSRecordset
?>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/skins/style.js" type="text/javascript"></script>
<script type="text/javascript">
  $NAV_SETTINGS = {
     'show_as_buttons': false
  }
</script>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>

<?php if(isset($_GET['Id'])){ ?>
<body onLoad="MM_openBrWindow('costing_mail.php?Id=<?php echo $_GET['Id']; ?>','','width=400,height=100')">
<?php } else { ?>
<body>
<?php } ?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
      </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="832" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
            <tr>
              <td colspan="3" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4">
<?php
if(($difference >= -3) && ($difference <= 3)){ ?>
<table align="center">
<tr><td>
<p>&nbsp;</p>
<p align="center">
<div style="padding-left:2px; border:solid 1px #cccccc; margin:2px; margin-left:35px; width:570px"><table width="570" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE" class="combo_bold">
        <tr>
          <td align="center" nowrap>
		  <br>
		  <?php echo $warning; ?><br>
		  <br></td>
          </tr>
		  
      </table>
    </div>			 
</p>
<p>&nbsp;</p>
</td></tr>
</table>
<?php } ?>
 <div style="margin-left:30px">
   <table width="820" border="0" align="center" cellpadding="3" cellspacing="1">
     
     <tr class="td-header">
       <td width="100" align="left" nowrap><strong>Jobcard </strong></td>
       <td width="150" align="left"><strong>Company</strong></td>
       <td width="250" align="left"><strong>Site Address </strong></td>
       <td width="150" align="left"><strong>Age</strong></td>
       <td width="40" align="center">PDF</td>
       <td width="40" align="center">&nbsp;</td>
       <td width="40" align="center">&nbsp;</td>
       </tr>
     <?php if($totalRows_Recordset3 >= 1){ ?>
     <?php do { ?>
       <?php 
// Show If Field Has Changed (region1)
if (tNG_fieldHasChanged("region1", $row_Recordset3['JobId'])) {

$jobid = $row_Recordset3['JobId'];

?>
         <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
           <td width="100"><a href="jc_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['JobNo']; ?></a></td>
           <td width="150"><a href="jc_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"><?php echo $row_Recordset3['Name']; ?></a></td>
           <td width="250"><a href="jc_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu" title="<?php echo $row_Recordset3['CommentText']; ?>"><?php echo $row_Recordset3['Name_1']; ?></a></td>
           <td width="150"><a href="jc_calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $row_Recordset3['JobId']; ?>&job" class="menu"><?php time_schedule($jobid); ?></a></td>
           <td width="40" align="center">
             <?php if($row_Recordset3['JobcardPDF'] != NULL){ ?>
             <a href="jc-pdf/<?php echo $row_Recordset3['JobcardPDF']; ?>" target="_blank"><img src="images/icons/btn-view.png" width="25" height="25" border="0"></a>
             <?php } ?>
             </td>
           <td width="40" align="center">
             <?php
							$jobno = $row_Recordset3['JobNo'];
							
							select_db();
							$query = mysql_query("SELECT * FROM tbl_far WHERE JobNo = '$jobno'")or die(mysql_error());
							$numrows = mysql_num_rows($query);
							
							if($numrows >= 1){
							
							?>
             
             <a href="far-print.php?Id=<?php echo $row_Recordset3['JobNo']; ?>"><img src="images/icons/btn-print.png" width="25" height="25" border="0"></a>
             <?php } ?>
             </td>
           <td width="40">
             <?php
						  $jobid = $row_Recordset3['JobId'];
						  $query = mysql_query("SELECT tbl_history_relation.PhotoId, tbl_history_photos.Photo, tbl_history_relation.JobId FROM (tbl_history_relation LEFT JOIN tbl_history_photos ON tbl_history_photos.Id=tbl_history_relation.PhotoId) WHERE tbl_history_relation.JobId = '$jobid'")or die(mysql_error());
						  $numrows = mysql_num_rows($query);
						  
						  if($numrows >= 1){
						  echo "<table cellpadding=\"0\" cellspacing=\"0\"><tr><td><a href=\"photo_view_history.php?Id=". $jobid ."&photos\" class=\"menu2\"><img src=\"images/icons/btn-tick.png\" width=\"25\" height=\"25\" border=\"0\"></a></td>
						  <td><a href=\"photo_view_history.php?Id=". $jobid ."&photos\" class=\"menu2\">(".$numrows.")</a></td>
						  </tr></table>";
						  } else {
						  echo "<img src=\"images/icons/btn-delete.png\" width=\"25\" height=\"25\" border=\"0\">";
						  }
						  ?>						  
             </td>
           </tr>
         <?php } 
// EndIf Field Has Changed (region1)
?>
       <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
     <?php } ?>
   </table>
 </div>
				<br>
                <div class="KT_bottomnav" align="center">
                  <div class="combo">
                    <?php
      //Display Navigation		
      $nav_Recordset3->Prepare();
      require("includes/nav/NAV_Text_Navigation.inc.php");
    ?>
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
