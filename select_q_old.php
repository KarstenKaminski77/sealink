<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the Navigation classes
require_once('includes/nav/NAV.php'); 

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

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

$maxRows_Recordset3 = $_SESSION['max_rows_nav_Recordset3'];
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_qs.AreaId, tbl_qs.JobDescription, tbl_qs.QuoteNo, tbl_companies.Name, tbl_qs.Date FROM ((tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_qs.SiteId) GROUP BY QuoteNo ORDER BY tbl_qs.QuoteNo DESC";
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
<script LANGUAGE="JavaScript"> 
<!--
// Nannette Thacker http://www.shiningstar.net
function confirmSubmit() 
{
var agree=confirm("Are you sure you wish to continue?");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><form action="fpdf16/pdf/quote_mail.php" method="post" enctype="multipart/form-data" name="form2">
          <table width="675" border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td width="50" align="left" nowrap class="tb_border"><strong>Quote </strong></td>
              <td width="150" align="left" class="tb_border"><strong>Company</strong></td>
              <td width="250" align="left" class="tb_border"><strong>Site Address </strong></td>
              <td width="225" align="left" class="tb_border"><strong>Date</strong></td>
              </tr>
			</table>
           <?php do { 
				                  $jobid = $row_Recordset3['JobId'];
				                  ?>
<DIV ID="dek"></DIV>

<SCRIPT TYPE="text/javascript">
<!--

//Pop up information box II (Mike McGrath (mike_mcgrath@lineone.net,  http://website.lineone.net/~mike_mcgrath))
//Permission granted to Dynamicdrive.com to include script in archive
//For this and 100's more DHTML scripts, visit http://dynamicdrive.com

Xoffset=-60;    // modify these values to ...
Yoffset= 20;    // change the popup position.

var old,skn,iex=(document.all),yyy=-1000;

var ns4=document.layers
var ns6=document.getElementById&&!document.all
var ie4=document.all

if (ns4)
skn=document.dek
else if (ns6)
skn=document.getElementById("dek").style
else if (ie4)
skn=document.all.dek.style
if(ns4)document.captureEvents(Event.MOUSEMOVE);
else{
skn.visibility="visible"
skn.display="none"
}
document.onmousemove=get_mouse;

function popup(msg,bak){
var content="<TABLE  WIDTH=150 BORDER=1 BORDERCOLOR=black CELLPADDING=2 CELLSPACING=0 "+
"BGCOLOR="+bak+"><TD ALIGN=center><FONT COLOR=#000066 face=arial SIZE=2>"+msg+"</FONT></TD></TABLE>";
yyy=Yoffset;
 if(ns4){skn.document.write(content);skn.document.close();skn.visibility="visible"}
 if(ns6){document.getElementById("dek").innerHTML=content;skn.display=''}
 if(ie4){document.all("dek").innerHTML=content;skn.display=''}
}

function get_mouse(e){
var x=(ns4||ns6)?e.pageX:event.x+document.body.scrollLeft;
skn.left=x+Xoffset;
var y=(ns4||ns6)?e.pageY:event.y+document.body.scrollTop;
skn.top=y+yyy;
}

function kill(){
yyy=-1000;
if(ns4){skn.visibility="hidden";}
else if (ns6||ie4)
skn.display="none"
}

//-->
</SCRIPT>
                    <?php 
// Show If Field Has Changed (region1)
if (tNG_fieldHasChanged("region1", $row_Recordset3['InvoiceNo'])) {
?>
                      <table width="675" border="0" align="center" cellpadding="0" cellspacing="1" class="combo">
                        <tr>
                          <td width="50"><div style="padding-left:5px"><a href="<?php echo $row_Recordset3['JobDescription']; ?>" class="menu" ONMOUSEOVER="popup('<?php echo $row_Recordset3['JobDescription']; ?>','#CEE1F7')"; ONMOUSEOUT="kill()"><?php echo $row_Recordset3['QuoteNo']; ?></a></div></td>
                          <td width="150"><div style="padding-left:10px"><a href="quote_archives.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>" class="menu"><?php echo $row_Recordset3['Name']; ?></a></div></td>
                          <td width="250"><div style="padding-left:10px"><a href="quote_archives.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>" class="menu"><?php echo $row_Recordset3['Name_1']; ?></a></div></td>
                          <td width="150"><div style="padding-left:10px"><a href="quote_archives.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>" class="menu"><?php 
						  if(!empty($row_Recordset3['Date'])){
						  $date = explode(" ",$row_Recordset3['Date']);
						  $day = $date[0];
						  $month = $date[1];
						  $year = $date[2];
						  $date = date('d M Y', mktime(0,0,0,$month,$day,$year));
						  echo $date;
						  }
						  ?>
						  </a></div></td>
                          <td width="25"><a href="fpdf16/pdf/<?php echo $row_Recordset3['PDF']; ?>" target="_blank"></a></td>
                          <td width="25" align="right">
                          <a onClick="return confirmSubmit()" href="revive_q.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>"></a></td>
                          <td width="25" align="right"><a href="print.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>">print</a></td>
                        </tr>
                    </table>
                      <?php } 
// EndIf Field Has Changed (region1)
?>
                    <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
        </form>
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
                </div>
		  </td></tr>
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
