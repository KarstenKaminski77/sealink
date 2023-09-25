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

$userid = $_SESSION['kt_login_id'];

if($userid == '29'){

$where = "AND tbl_qs.AreaId = '". $areaid ."' AND tbl_qs.CompanyId = '3'";

} else {
	
	$where = "AND tbl_qs.AreaId = ". $areaid ."";
}

$maxRows_Recordset3 = $_SESSION['max_rows_nav_Recordset3'];
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_qs.QuoteNo AS QuoteNo_1, tbl_qs.AreaId, tbl_qs.JobDescription, tbl_qs.Type, tbl_sent_quotes.QuoteNo, tbl_sent_quotes.CompanyId, tbl_sent_quotes.SiteId, tbl_sent_quotes.PDF, tbl_sent_quotes.DateSent, tbl_sent_quotes.Sent FROM (tbl_sent_quotes LEFT JOIN tbl_qs ON tbl_qs.QuoteNo=tbl_sent_quotes.QuoteNo) WHERE Status = '3' $where GROUP BY QuoteNo ORDER BY tbl_qs.Id DESC";
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
        <td colspan="4"><form action="fpdf16/pdf/quote_resend_mail.php" method="post" enctype="multipart/form-data" name="form2" style="margin-left:30px">
          <table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
            <tr>
              <td class="combo_bold"><input name="email" type="text" class="td-mail" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:100%"></td>
            </tr>
            <tr>
              <td class="combo_bold"><input name="attach" type="file" class="td-mail" id="attach" style="width:100%"></td>
            </tr>
            <tr>
              <td valign="top" class="combo_bold"><textarea name="message" rows="5" class="td-mail" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:100%">Message</textarea></td>
            </tr>
            <tr>
              <td align="right"><input name="Submit2" type="submit" class="btn-blue-generic" value="Send"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
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
          <table border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td width="60" align="left" nowrap class="td-header"><strong>Quote </strong></td>
              <td width="160" align="left" class="td-header"><strong>Company</strong></td>
              <td width="260" align="left" class="td-header"><strong>Site Address </strong></td>
              <td align="left" class="td-header"><strong>Date</strong></td>
              <td align="left" class="td-header">&nbsp;</td>
              <td colspan="3" align="left" class="td-header">&nbsp;</td>
              <td align="left" class="td-header">&nbsp;</td>
              </tr>
			  
                        <?php do { ?>
                          <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                          <td width="60"><div style="padding-left:5px"><a href="<?php echo $row_Recordset3['JobDescription']; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['QuoteNo']; ?></a></div></td>
                            <td width="160" class="combo"><?php echo $row_Recordset3['CompanyId']; ?></td>
                            <td width="260" class="combo"><?php echo $row_Recordset3['SiteId']; ?></td>
                            <td width="150" class="combo"><?php echo $row_Recordset3['DateSent']; ?></td>
							<?php
							$type = $row_Recordset3['Type'];
							if($type == "Accepted"){
							$colour = "#006600";
							} else {
							$colour = "#FF0000";
							}
							?>
                            <td width="100" class="combo"><div style="color:<?php echo $colour; ?>"><strong><?php echo $row_Recordset3['Type']; ?></strong></div></td>
                            <td width="25"><a href="fpdf16/pdf/<?php echo $row_Recordset3['PDF']; ?>" target="_blank"><img src="images/icons/btn-view.png" width="25" height="25" border="0"></a></td>
                            <td width="25" align="right">
                              <a onClick="return confirmSubmit()" href="revive_q.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>">
                            <img src="images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
                            <td width="25" align="right"><a href="quote_history.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>"><img src="images/icons/btn-information.png" width="25" height="25" border="0"></a></td>
                            <td width="25" align="right" colspan="2"><input name="file[]" type="radio" id="file[]" value="<?php echo $row_Recordset3['PDF']; ?>"></td>
                          </tr>
                          <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
			</table>
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
