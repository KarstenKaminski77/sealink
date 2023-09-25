<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the Navigation classes
require_once('includes/nav/NAV.php'); 

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

$nav_Recordset3 = new NAV_Regular("nav_Recordset3", "Recordset3", "", KT_getPHP_SELF(), 50);


mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
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

if(isset($_POST['jobno'])){
	
	$jobno = $_POST['jobno'];
	$where = "WHERE QuoteNo = '". $jobno ."'";
	
} elseif((isset($_POST['company'])) && (!isset($_POST['site']))){
	
	$companyid = $_POST['company'];
	$where = "WHERE tbl_qs.CompanyId = '". $companyid ."' AND tbl_qs.CompanyId != '0'";

} elseif(isset($_POST['site'])){
	
	$site = $_POST['site'];
	$where = "WHERE tbl_qs.SiteId = '". $site ."' AND tbl_qs.SiteId != '0'";

} elseif(isset($_GET['search'])){
	
	$jobdescription = $_GET['search'];
	
	//$count = count(explode("+", $jobdescription));
	//$string = explode("+", $jobdescription);
	
	$where = "WHERE tbl_qs.Description LIKE \"%$jobdescription%\" OR tbl_qs.JobDescription LIKE \"%$jobdescription%\" OR tbl_qs.Notes LIKE \"%$jobdescription%\" OR tbl_qs.InternalNotes LIKE \"%$jobdescription%\"";

} else {
	
	$where = "WHERE tbl_qs.QuoteNo=1";

}

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_sites.Name AS Name_1, tbl_sites.AreaId, tbl_qs.QuoteNo, tbl_qs.CompanyId, tbl_qs.SiteId, tbl_qs.Status, tbl_companies.Name, tbl_qs.Date, tbl_qs.JobDescription, tbl_qs.Id FROM ((tbl_qs LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_qs.CompanyId) LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_qs.SiteId) $where AND tbl_qs.AreaId = '$areaid' GROUP BY QuoteNo ORDER BY tbl_qs.Id DESC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$nav_Recordset3->checkBoundries();

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

mysql_select_db($database_seavest, $seavest);
$query_Recordset6 = "SELECT * FROM tbl_sites WHERE AreaId = '$areaid' ORDER BY Name ASC";
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

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
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/skins/style.js" type="text/javascript"></script>
<script type="text/javascript">
  $NAV_SETTINGS = {
     'show_as_buttons': false
  }
</script>
<script type="text/javascript">
<!--
$NAV_SETTINGS = {
     'show_as_buttons': false
  }

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset6 = new WDG_JsRecordset("Recordset6");
echo $jsObject_Recordset6->getOutput();
//end JSRecordset
?>
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
              <td colspan="4" bordercolor="#FFFFFF" class="combo">
			  <div id="add_row" style="background-color:#FFFFFF; border:none">
<table width="100%" border="0" cellpadding="2" cellspacing="3">
                  <tr>
                    <td width="23%"><table border="0" cellpadding="0" cellspacing="0" class="combo">
                  <tr>
                    <td class="btn-blue-generic">Search By:&nbsp; </td>
                    <td><form name="form3">
                      <select name="menu1" class="tarea-white" onChange="MM_jumpMenu('parent',this,0)">
                        <option>Select one...</option>
                        <option value="search_q.php?oil">Oil Company</option>
                        <option value="search_q.php?site">Site</option>
                        <option value="search_q.php?job">Job Number</option>
                      </select>
                    </form>                    </td>
                  </tr>
                </table></td>
                    <td width="77%"><?php if(isset($_GET['job'])){ ?>
                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="blue-generic"><strong>
                    Quote No.:                 </strong></td>
                  <td nowrap><form name="form2" method="post" action="search_q.php?job">
                     &nbsp;
                     <input name="jobno" type="text" class="tarea-white" id="jobno" style="cursor:text">
                    <input name="Submit" type="submit" class="btn-go-search-2" id="Submit" value="">
                  </form>				  </td>
                </tr>
              </table>
			   <?php } ?>
				<?php if(isset($_GET['oil'])){ ?>
                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="btn-blue-generic">
                    Oil Company:                 </td>
                  <td nowrap><form action="search_q.php?oil" method="post" name="form4" id="form4">
                     &nbsp;
                     <select name="company" class="tarea-white" id="company">
                       <option value="">Select one...</option>
                       <?php
do {  
?>
                       <option value="<?php echo $row_Recordset5['Id']?>"><?php echo $row_Recordset5['Name']?></option>
                       <?php
} while ($row_Recordset5 = mysql_fetch_assoc($Recordset5));
  $rows = mysql_num_rows($Recordset5);
  if($rows > 0) {
      mysql_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysql_fetch_assoc($Recordset5);
  }
?>
                     </select>
                     <input name="Submit" type="submit" class="btn-go-search-2" id="Submit" value="">
                  </form>				  </td>
                </tr>
              </table>
			   <?php } ?>
				<?php if(isset($_GET['site'])){ ?>
                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="btn-blue-generic">
                    Site:                 </td>
                  <td nowrap><form action="search_q.php?site" method="post" name="form5" id="form5">
                     &nbsp;
                     <select name="company" class="tarea-white" id="company">
                       <?php
do {  
?>
                       <option value="<?php echo $row_Recordset5['Id']?>"><?php echo $row_Recordset5['Name']?></option>
                       <?php
} while ($row_Recordset5 = mysql_fetch_assoc($Recordset5));
  $rows = mysql_num_rows($Recordset5);
  if($rows > 0) {
      mysql_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysql_fetch_assoc($Recordset5);
  }
?>
                     </select>
                     <select name="site" class="tarea-white" id="site" wdg:subtype="DependentDropdown" wdg:type="widget" wdg:recordset="Recordset6" wdg:displayfield="Name" wdg:valuefield="Id" wdg:fkey="Company" wdg:triggerobject="company">
                     </select>
<input name="Submit" type="submit" class="btn-go-search-2" id="Submit" value="">
                  </form>				  </td>
                </tr>
              </table>
			   <?php } ?></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"><form name="form6" method="get" action="search_q.php">
                      <table width="100%" border="0" cellpadding="2" cellspacing="3">
                        <tr>
                          <td><input name="search" type="text" class="combo" id="search" style="width:100%"></td>
                          <td width="32"><input name="Submit" type="submit" class="btn_search" id="Submit" value=""></td>
                        </tr>
                      </table>
                    </form></td>
                    </tr>
                </table> 
				<?php if(isset($_POST['Submit'])){ ?>               
			  <p>&nbsp;</p>
			  <table border="0" align="center" cellpadding="2" cellspacing="3" class="combo_bold">
                <tr>
                  <td><?php echo $totalRows_Recordset3; ?> Results</td>
                </tr>
              </table>
			  <?php } ?>
				<?php if(isset($_GET['search'])){ ?>               
			  <p>&nbsp;</p>
			  <table border="0" align="center" cellpadding="2" cellspacing="3" class="combo_bold">
                <tr>
                  <td><?php echo $totalRows_Recordset3; ?> Results</td>
                </tr>
              </table>
			  <?php } ?>
			  </div>
                <p>&nbsp;</p>
                <p>&nbsp;</p></td>
            </tr>
            <tr>
              <td colspan="4" align="left">
			  <?php if(isset($_GET['search'])){ ?>
<div style="padding-left:25px">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="left" nowrap class="combo"><div id="list-brdr" style="display:block">
                      <table width="100%" border="0" cellpadding="4" cellspacing="1" class="combo">
                        <tr>
                          <td width="50" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Quote </strong></td>
                          <td width="150" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Company</strong></td>
                          <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Site Address </strong></td>
                          <td width="75" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Date</strong></td>
                          <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">Photos</td>
                          <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">Location</td>
                          <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">New</td>
                          </tr>
                        <?php do { ?>
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
                          <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                            <td><div><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu" ONMOUSEOVER="popup('<?php echo $row_Recordset3['JobDescription']; ?>','#CEE1F7')"; ONMOUSEOUT="kill()"> <?php echo $row_Recordset3['QuoteNo']; ?></a></div></td>
                            <td><div><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"><?php echo $row_Recordset3['Name']; ?></a></div></td>
                            <td><div><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"> <?php echo $row_Recordset3['Name_1']; ?></a></div></td>
                            <td><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"> <?php echo $row_Recordset3['Date']; ?></a> </td>
                            <td align="center"><?php
						  $quoteno = $row_Recordset3['QuoteNo'];
						  $query = mysql_query("SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysql_error());
						  $numrows = mysql_num_rows($query);
						  
						  if($numrows >= 1){
						  echo "<a href=\"photo_view.php?Id=". $quoteno ."&photos\" class=\"menu2\"><img src=\"images/check.jpg\" width=\"15\" height=\"15\" border=\"0\"> (".$numrows.")</a>";
						  } else {
						  echo "<img src=\"images/no.jpg\" width=\"15\" height=\"15\">";
						  }
						  ?>                                                      </td>
                            <td align="center"><?php
						  if($row_Recordset3['Status'] == "0")
						  { echo "Pending"; } 
						  elseif($row_Recordset3['Status'] == "1")
						  { echo "Outbox"; } 
						  elseif($row_Recordset3['Status'] == "2")
						  { echo "Submitted"; }
						  elseif($row_Recordset3['Status'] == "3")
						  { echo "Archives"; }
						  ?>                                                      </td>
                            <td align="center"><a href="quote_save_new.php?quote=<?php echo $row_Recordset3['QuoteNo']; ?>"><img src="images/new.jpg" width="44" height="18" border="0"></a></td>
                            </tr><?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                      </table>
                    </div></td>
                    </tr>
                </table>
              </div>
			  <?php } else { ?>
			  			  <div style="padding-left:25px">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="left" nowrap class="combo"><div id="list-brdr" style="display:block">
                      <table width="100%" border="0" align="left" cellpadding="4" cellspacing="1" class="combo">
                        <tr>
                          <td width="50" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Quote </strong></td>
                          <td width="150" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Company</strong></td>
                          <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Site Address </strong></td>
                          <td width="75" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header"><strong>Date</strong></td>
                          <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">Photos</td>
                          <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">Location</td>
                          <td width="75" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="td-header">New</td>
                          </tr>
                        <?php do { ?>
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
                          <tr class="<?php echo ($ac_sw2++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                            <td><div><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu" ONMOUSEOVER="popup('<?php echo $row_Recordset3['JobDescription']; ?>','#CEE1F7')"; ONMOUSEOUT="kill()"> <?php echo $row_Recordset3['QuoteNo']; ?></a></div></td>
                            <td><div><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"><?php echo $row_Recordset3['Name']; ?></a></div></td>
                            <td><div><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"> <?php echo $row_Recordset3['Name_1']; ?></a></div></td>
                            <td><a href="quote_calc.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&job" class="menu"> <?php echo $row_Recordset3['Date']; ?></a> </td>
                            <td align="center"><?php
						  $quoteno = $row_Recordset3['QuoteNo'];
						  $query = mysql_query("SELECT * FROM tbl_photos WHERE QuoteNo = '$quoteno'")or die(mysql_error());
						  $numrows = mysql_num_rows($query);
						  
						  if($numrows >= 1){
						  echo "<a href=\"photo_view.php?Id=". $quoteno ."&photos\" class=\"menu2\"><img src=\"images/check.jpg\" width=\"15\" height=\"15\" border=\"0\"> (".$numrows.")</a>";
						  } else {
						  echo "<img src=\"images/no.jpg\" width=\"15\" height=\"15\">";
						  }
						  ?>                                                      </td>
                            <td align="center"><?php
						  if($row_Recordset3['Status'] == "0")
						  { echo "Pending"; } 
						  elseif($row_Recordset3['Status'] == "1")
						  { echo "Outbox"; } 
						  elseif($row_Recordset3['Status'] == "2")
						  { echo "Submitted"; }
						  elseif($row_Recordset3['Status'] == "3")
						  { echo "Archives"; }
						  ?>                                                      </td>
                            <td align="center"><a href="quote_save_new.php?quote=<?php echo $row_Recordset3['QuoteNo']; ?>"><img src="images/new.jpg" width="44" height="18" border="0"></a></td>
                            </tr><?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                      </table>
                    </div></td>
                    </tr>
                </table>
              </div>
			  <?php } ?>
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

mysql_free_result($Recordset5);

mysql_free_result($Recordset6);
?>
