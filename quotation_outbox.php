<?php
session_start();

require_once('functions/functions.php');

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

if(empty($_SESSION['areaid'])){
	
	$_SESSION['areaid'] = 1;
}

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userid = $_SESSION['kt_login_id'];

if($userid == '29'){

$where = "AND tbl_qs.AreaId = '". $areaid ."' AND tbl_qs.CompanyId = '3'";

} else {
	
	$where = "AND tbl_qs.AreaId = ". $areaid ."";
}


$query_Recordset3 = "SELECT tbl_qs.QuoteNo AS QuoteNo_1, tbl_qs.JobDescription, tbl_sent_quotes.QuoteNo, tbl_sent_quotes.CompanyId, tbl_qs.CompanyId AS CompanyId_1, tbl_sent_quotes.SiteId, tbl_sent_quotes.PDF, tbl_sent_quotes.DateSent, tbl_sent_quotes.Sent FROM (tbl_sent_quotes LEFT JOIN tbl_qs ON tbl_qs.QuoteNo=tbl_sent_quotes.QuoteNo) WHERE tbl_qs.Status = '1' $where GROUP BY QuoteNo ORDER BY tbl_qs.Id ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);
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
        <?php include('menu.php'); ?>
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
        <td colspan="4">
        <form action="fpdf16/pdf/quote_mail.php" method="post" enctype="multipart/form-data" name="form2" style="margin-left:30px">
          <table border="0" align="center" cellpadding="3" cellspacing="1" id="myTable">
            <tr>
              <td colspan="8" align="left" nowrap><input name="email[]" type="text" class="td-mail" value="To" id="email[]" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:100%">
                
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
              <td colspan="9" align="left" nowrap><input name="attach" type="file" class="td-mail" id="attach" style="width:100%"></td>
            </tr>
            <tr>
              <td colspan="9" align="left" nowrap><textarea name="message" rows="5" class="td-mail" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:100%">Message</textarea></td>
            </tr>
            <tr>
              <td colspan="9" align="right" nowrap><input name="Submit2" type="submit" class="btn-blue-generic" value="Send"></td>
            </tr>
            <tr>
              <td colspan="9" align="left" nowrap>&nbsp;</td>
            </tr>
            <tr class="td-header">
              <td width="50" align="left" nowrap><strong>Quote </strong></td>
              <td width="150" align="left"><strong>Company</strong></td>
              <td width="250" align="left"><strong>Site Address </strong></td>
              <td width="250" align="left"><strong>Date</strong></td>
              <td width="80" align="left">Age</td>
              <td width="40" align="center"></td>
              <td width="40" align="center"></td>
              <td width="40" align="center"></td>
              <td align="center"></td>
              </tr>
           <?php do { 
		   $jobid = $row_Recordset3['JobId'];

?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                    <td width="50" align="center"><div><a href="<?php echo $row_Recordset3['JobDescription']; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['QuoteNo']; ?></a>
                        <input name="qn[]" type="hidden" id="qn[]" value="<?php echo $row_Recordset3['QuoteNo']; ?>">
                    </div></td>
                    <td width="150" nowrap><div><?php echo $row_Recordset3['CompanyId']; ?></div></td>
                    <td width="250" nowrap><div><?php echo $row_Recordset3['SiteId']; ?></div></td>
                    <td width="250"><div><?php echo $row_Recordset3['DateSent']; ?></div></td>
                    <td width="80"><?php 
					$quoteno = $row_Recordset3['QuoteNo'];
					time_schedule_quotes($quoteno); ?></td>
                    <td width="40" align="center"><a href="fpdf16/pdf_quotation.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&preview" target="_blank"><img src="images/icons/btn-view.png" width="25" height="25" border="0"></a></td>
                    <td width="40" align="center">
                      <a onClick="return confirmSubmit()" href="revive_q.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>">
                    <img src="images/icons/btn-edit.png" width="25" height="25" border="0"></a></td>
                    <td width="40" align="center"><a href="q_photos.php?Id=<?php echo $row_Recordset3['QuoteNo_1']; ?>"><img src="images/icons/btn-photo.png" width="25" height="25" border="0"></a></td>
                    <td align="center"><input name="file[]" type="radio" id="file[]" value="<?php echo $row_Recordset3['PDF']; ?>"></td>
                  </tr>
                <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
			</table>
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
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);
?>
