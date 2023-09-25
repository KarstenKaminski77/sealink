<?php require_once('Connections/seavest.php'); ?>
<?php
session_start();

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

$areaid = $_SESSION['areaid'];

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
$query_Recordset3 = "SELECT tbl_qs.QuoteNo AS QuoteNo_1, tbl_qs.AreaId, tbl_qs.JobDescription, tbl_sent_quotes.QuoteNo, tbl_sent_quotes.CompanyId, tbl_sent_quotes.SiteId, tbl_sent_quotes.PDF, tbl_sent_quotes.DateSent, tbl_sent_quotes.Sent FROM (tbl_sent_quotes LEFT JOIN tbl_qs ON tbl_qs.QuoteNo=tbl_sent_quotes.QuoteNo) WHERE Status = '2' $where GROUP BY QuoteNo ORDER BY tbl_qs.Days ASC";
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
        <td colspan="4"><form action="fpdf16/pdf/quote_resend_mail.php" method="post" enctype="multipart/form-data" name="form2">
          <table width="750" border="0" align="center" cellpadding="3" cellspacing="1" id="myTable">
            <tr>
              <td colspan="9" align="left" nowrap><input name="email[]" type="text" class="td-mail" value="To" id="email[]" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:100%">
                
                <script>
              function myFunction() {
                  var table = document.getElementById("myTable");
                  var row = table.insertRow(1);
                  var cell1 = row.insertCell(0);
                  cell1.innerHTML = "<input name=\"email[]\" type=\"text\" class=\"td-mail\" value=\"To\" id=\"email[]\" onFocus=\"if (this.value=='To') this.value='';\" onBlur=\"if (this.value.replace(/\s/g,'')=='') this.value='To';\" style=\"width:100%\">";
                document.getElementById('myTable').rows[1].cells[0].colSpan = 10
              }
              </script>
                
              </td>
              <td align="left" nowrap><a class="btn-new-2" onclick="myFunction()">+</a></td>
            </tr>
            <tr>
              <td colspan="10" align="left" nowrap><input name="attach" type="file" class="td-mail" id="attach" style="width:100%"></td>
            </tr>
            <tr>
              <td colspan="10" align="left" nowrap><textarea name="message" rows="5" class="td-mail" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:100%">Message</textarea></td>
            </tr>
            <tr>
              <td colspan="10" align="right" nowrap><input name="Submit3" type="submit" class="btn-blue-generic" value="Send"></td>
            </tr>
            <tr>
              <td colspan="10" align="left" nowrap>&nbsp;</td>
            </tr>
            <tr class="td-header">
              <td width="50" align="left" nowrap><strong>Quote </strong></td>
              <td width="150" align="left"><strong>Company</strong></td>
              <td width="250" align="left"><strong>Site Address </strong></td>
              <td width="74" align="left">Age</td>
              <td width="95" align="left" nowrap>Follow Up</td>
              <td width="40" align="center"></td>
              <td width="40" align="center"></td>
              <td width="40" align="center"></td>
              <td width="40" align="center"></td>
              <td width="40" align="center"></td>
            </tr>
           <?php do { ?>
                    <?php 
// Show If Field Has Changed (region1)
if (tNG_fieldHasChanged("region1", $row_Recordset3['InvoiceNo'])) {
?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                      <td width="50"><div><a href="<?php echo $row_Recordset3['JobDescription']; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['QuoteNo']; ?></a></div></td>
                      <td width="150" nowrap><div><?php echo $row_Recordset3['CompanyId']; ?></div></td>
                      <td width="250" nowrap><div><?php echo $row_Recordset3['SiteId']; ?></div></td>
                      <td width="74" nowrap><div>
					  <?php 
					  
					  $quoteno = $row_Recordset3['QuoteNo_1'];
					  time_schedule_quotes($quoteno); 
					  
					  ?>
                      </div></td>
                      <td width="95" nowrap><?php follow_up($quoteno); ?></td>
                      <td width="40" align="center"><a href="fpdf16/pdf_quotation.php?Id=<?php echo $row_Recordset3['QuoteNo_1']; ?>&preview" target="_blank"><img title="View PDF" src="images/icons/btn-view.png" width="25" height="25" border="0"></a></td>
                      <td width="40" align="center">
                        <a onClick="return confirmSubmit()" href="revive_q.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>">
                      <img title="Edit" src="images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
                      <td width="40" align="center"><a href="quote_history.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>"><img title="Mail History" src="images/icons/btn-information.png" width="25" height="25" border="0"></a></td>
                      <td width="40" align="center"><img title="Send to Archives" style="cursor:pointer" src="images/icons/btn-archives.png" width="25" height="25" onClick="MM_openBrWindow('quote-archive-update.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>','','width=200,height=130')"></td>
                      <td width="40" align="center"><input name="file[]" type="radio" id="file[]" value="<?php echo $row_Recordset3['PDF']; ?>"></td>
                      </tr>
                      <?php } 
// EndIf Field Has Changed (region1)
?>
                    <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
			</table>
</form>
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
