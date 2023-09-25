<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

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

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_jc.Id, tbl_jc.InvoiceNo, tbl_jc.AreaId, tbl_companies.Name, tbl_sites.Name AS Name_1, tbl_jc.InvoiceDate, tbl_jc.JobId, tbl_jc.JobDescription, Total2
FROM ((tbl_jc
LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId)
LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId)
$where GROUP BY tbl_jc.JobId ORDER BY tbl_jc.Id DESC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
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
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
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
        <td colspan="4"><form action="fpdf16/pdf/test.php" method="post" enctype="multipart/form-data" name="form2">
          <table width="675" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="6" align="left" nowrap class="combo"><table width="650" border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td width="60" class="combo_bold">To</td>
                  <td width="570"><input name="email" type="text" class="tarea5" id="email"></td>
                </tr>
                <tr>
                  <td width="60" class="combo_bold">Attach</td>
                  <td width="570"><input name="attach" type="file" class="tarea5" id="attach"></td>
                </tr>
                <tr>
                  <td width="60" valign="top" class="combo_bold">Message</td>
                  <td width="570"><textarea name="message" rows="5" class="tarea5" id="message"></textarea></td>
                </tr>
                <tr>
                  <td width="60">&nbsp;</td>
                  <td width="570" align="right"><input name="Submit2" type="submit" class="tarea3" value="Send"></td>
                </tr>
                <tr>
                  <td width="60">&nbsp;</td>
                  <td width="570">&nbsp;</td>
                </tr>
              </table></td>
              </tr>
            
            <tr>
              <td width="50" align="left" nowrap class="combo">&nbsp;</td>
              <td align="left" class="combo">&nbsp;</td>
              <td align="left" class="combo">&nbsp;</td>
              <td align="left" class="combo">&nbsp;</td>
              <td align="left" class="combo">&nbsp;</td>
              <td align="left" class="combo">&nbsp;</td>
            </tr>
			</table>
          <table width="705" border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td width="50" align="left" nowrap class="tb_border"><strong>In No. </strong></td>
              <td width="180" align="left" class="tb_border"><strong>Company</strong></td>
              <td width="250" align="left" class="tb_border"><strong>Site Address </strong></td>
              <td width="325" colspan="3" align="left" class="tb_border"><strong>Date</strong></td>
              </tr>
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
$jobid = $row_Recordset3['JobId'];

// Show If Field Has Changed (region1)
if (tNG_fieldHasChanged("region1", $row_Recordset3['InvoiceNo'])) {
?><tr>
                          <td width="50"><a href="#" class="menu" ONMOUSEOVER="popup('<?php echo $row_Recordset3['JobDescription']; ?>','#CEE1F7')"; ONMOUSEOUT="kill()"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                          <td width="180" class="combo"><?php echo $row_Recordset3['CompanyId']; ?></td>
                          <td width="250" class="combo"><?php echo $row_Recordset3['SiteId']; ?></td>
                          <td width="325" class="combo"><?php echo $row_Recordset3['InvoiceDate']; ?></td>
                          <td><a href="fpdf16/pdf/<?php echo $row_Recordset3['PDF']; ?>" target="_blank"><img src="images/pdf.jpg" width="19" height="20" border="0"></a></td>
                          <td width="25" align="right"><input name="file[]" type="checkbox" id="file[]" value="<?php echo $row_Recordset3['PDF']; ?>"></td>
                        </tr><?php } 
// EndIf Field Has Changed (region1)
?>
              <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>			</table>
                <p>&nbsp;</p>
        </form>
        <br>
          <br>
          <BR />
          <div class="KT_bottomnav" align="center">
            <div class="combo"></div>
          </div></td></tr>
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
