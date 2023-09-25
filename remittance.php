<?php require_once('Connections/seavest.php'); ?>
<?php require_once('Connections/inv.php'); ?>
<?php

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

select_db();

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

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

if(isset($_GET['Company'])){
	
	$where = 'WHERE tbl_remittance.CompanyId = '. $_GET['Company'];
	
}

if(isset($_POST['submit'])){
	
	$var = $_POST['search'];
	$date = $_POST['date'];
	
	if($_POST['search'] != 'Search'){
		
		$where = "WHERE tbl_remittance.Amount = '$var' OR tbl_remittance_details.InvoiceNo = '$var' OR tbl_remittance_details.JobNo = '$var' OR tbl_remittance_details.InvoiceNo = '$var' OR tbl_remittance_details.Amount = '$var'";
		
	}
	
	if(!empty($_POST['date'])){
		
		$where = "WHERE tbl_remittance.Date = '$date'";
		
	}
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_users.Name AS Name_2, tbl_sites.Name AS Name_1, tbl_remittance_details.Amount AS Amount_1, tbl_remittance_details.JobNo, tbl_remittance_details.InvoiceNo, tbl_remittance.Amount, tbl_remittance_details.InvoiceDate, tbl_companies.Name, tbl_remittance.Date, tbl_remittance.Id, tbl_remittance.UserId
FROM ((((tbl_remittance
LEFT JOIN tbl_remittance_details ON tbl_remittance_details.RemittanceId=tbl_remittance.Id)
LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_remittance.CompanyId)
LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_remittance_details.SiteId)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_remittance.UserId) $where GROUP BY tbl_remittance_details.RemittanceId ORDER BY tbl_remittance.Date ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_inv, $inv);
$query_Recordset4 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset4 = mysql_query($query_Recordset4, $inv) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$id = $_GET['Id'];

$query_remittance = mysqli_query($con, "SELECT * FROM tbl_remittance WHERE Id = '$id'")or die(mysqli_error($con));
$row_remittance = mysqli_fetch_array($query_remittance);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
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
<style>
body {
	margin:0px;
	padding:0px;
}
iframe {
	margin:0px;
	padding:0px;
	border:none;
	min-height:900px;
	height:auto !important;
	height:900px;
	overflow: hidden;
	width:800px;
}
</style>

<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="highslide/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="highslide/highslide.css" />


<!--
    2) Optionally override the settings defined at the top
    of the highslide.js file. The parameter hs.graphicsDir is important!
-->

<script type="text/javascript">
    hs.graphicsDir = 'highslide/graphics/';
    hs.outlineType = 'rounded-white';
</script>
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

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
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
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
    <td width="823" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><form action="" method="post" enctype="multipart/form-data" name="form2">
          <table width="725" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr>
              <td colspan="7" align="left" nowrap class="combo">
              <?php
			  if(isset($_GET['Id'])){
				  
				  $to = $row_remittance['Email'];
				  
			  } else {
				  
				  $to = 'To';
			  }
			  ?>
              <input name="email" type="text" class="tarea-100per" id="email" value="<?php echo $to; ?>" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';"></td>
            </tr>
            
            <tr>
              <td colspan="7" align="left" nowrap class="combo">
              <?php
			  if(isset($_GET['Id'])){
				  
				  $var = $row_remittance['Message'];
				  
			  } else {
				  
				  $var = 'Message';
			  }
			  ?>
              <textarea name="message" rows="5" class="tarea-100per" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';"><?php echo $var; ?></textarea></td>
            </tr>
            <tr>
              <td colspan="7" align="right" nowrap class="combo">
              <?php
			  if(isset($_GET['Id'])){
				  
				  $var = $row_remittance['Amount'];
				  
			  } else {
				  
				  $var = 'Amount';
			  }
			  ?>
              <input name="amount" type="text" class="tarea-100per" id="amount" value="<?php echo $var; ?>" onFocus="if (this.value=='Amount') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Amount';"></td>
            </tr>
            <tr>
              <td colspan="7" align="right" nowrap class="combo"><table border="0">
                <tr>
                  <td><input name="Submit2" type="submit" class="btn-blue-generic" value="Send"></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="7" align="left" nowrap class="combo"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="21%"><select name="jumpMenu" class="tarea" id="jumpMenu" onChange="MM_jumpMenu('parent',this,0)">
                    <?php do { ?>
                    <option value="remittance.php?Company=<?php echo $row_Recordset4['Id']; ?>" <?php if($_GET['Company'] == $row_Recordset4['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_Recordset4['Name']; ?></option>
                    <?php } while ($row_Recordset4 = mysql_fetch_assoc($Recordset4)); ?>
                    </select></td>
                  <td align="right"><table border="0">
                    <tr>
                      <td><input name="date" class="tarea" id="date" value="" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="no"></td>
                      <td><input name="search" type="text" class="tarea" id="search" value="Search" onFocus="if (this.value=='Search') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Search';"></td>
                      <td><input name="submit" type="submit" class="btn-blue-generic" id="submit" value="Search"></td>
                      </tr>
                  </table></td>
  <?php if(isset($_GET['Company'])){ ?>
                  <?php } ?>
                  </tr>
                </table></td>
            </tr>
            <tr class="td-header">
              <td width="50" align="center" nowrap><strong> No. </strong></td>
              <td align="left"><strong>Company</strong></td>
              <td width="173" align="left"><strong>Date</strong></td>
              <td width="75" align="left">Total</td>
              <td width="40" align="center">&nbsp;</td>
              <td width="40" align="center">&nbsp;</td>
              <td width="40" align="center">&nbsp;</td>
            </tr>
			  <?php 
			  $i = 0;
			  do {
				  
				  $i++;
				  
				  $jobid = $row_Recordset3['JobId'];
				                  ?>
<tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                          <td align="center" nowrap class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu"><?php echo $row_Recordset3['Id']; ?></a></td>
                  <td nowrap class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu"><?php echo $row_Recordset3['Name']; ?></a></td>
  <td width="173" class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu"><?php echo $row_Recordset3['Date']; ?></a></td>
                          <td width="75" nowrap class="combo"><a href="remittance.php?Id=<?php echo $row_Recordset3['Id']; ?>" class="menu">R<?php echo $row_Recordset3['Amount']; ?></a></td>
                          <td align="center">
                          <a href="pdf/pdf/Seavest Remittance <?php echo $row_Recordset3['Id']; ?>.pdf" onClick="return hs.htmlExpand(this, { contentId: 'highslide-html<?php echo $i; ?>' } )" class="highslide">
                          <img title="View PDF" src="images/icons/btn-view.png" width="25" height="25" border="0"></a>
<?php
$id = $row_Recordset3['Id'];

$query = mysqli_query($con, "SELECT tbl_users.Name AS Name_2, tbl_sites.Name AS Name_1, tbl_remittance_details.Amount AS Amount_1, tbl_remittance_details.JobNo, tbl_remittance_details.InvoiceNo, tbl_remittance.Amount, tbl_remittance_details.InvoiceDate, tbl_companies.Name, tbl_remittance.Date, tbl_remittance.Id, tbl_remittance.UserId
FROM ((((tbl_remittance
LEFT JOIN tbl_remittance_details ON tbl_remittance_details.RemittanceId=tbl_remittance.Id)
LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_remittance.CompanyId)
LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_remittance_details.SiteId)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_remittance.UserId)
WHERE tbl_remittance.Id = '$id'")or die(mysqli_error($con));

$query2 = mysqli_query($con, "SELECT tbl_users.Name AS Name_2, tbl_sites.Name AS Name_1, tbl_remittance_details.Amount AS Amount_1, tbl_remittance_details.JobNo, tbl_remittance.DateSubmitted, tbl_remittance_details.InvoiceNo, tbl_remittance.Amount, tbl_remittance.Discount, tbl_remittance_details.InvoiceDate, tbl_companies.Name, tbl_remittance.Date, tbl_remittance.Id, tbl_remittance.UserId
FROM ((((tbl_remittance
LEFT JOIN tbl_remittance_details ON tbl_remittance_details.RemittanceId=tbl_remittance.Id)
LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_remittance.CompanyId)
LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_remittance_details.SiteId)
LEFT JOIN tbl_users ON tbl_users.Id=tbl_remittance.UserId)
WHERE tbl_remittance.Id = '$id'")or die(mysqli_error($con));
$row2 = mysqli_fetch_array($query2);

?>
<div class="highslide-html-content" id="highslide-html<?php echo $i; ?>">
	<div class="highslide-header">
		<ul>
			<li class="highslide-close">
				<a href="#" onClick="return hs.close(this)"><img src="images/icons/close.png" width="25" height="25" border="0" /></a>
			</li>
		</ul>
	</div>
	<div class="highslide-body">
<table width="600" border="0" cellpadding="4" cellspacing="1">
  <tr>
    <td colspan="5"><table border="0" class="comb-sms">
      <tr>
        <td width="120" class="td-header"><strong>Client</strong></td>
        <td class="even" width="200"><?php echo $row2['Name']; ?></td>
      </tr>
      <tr>
        <td width="120" class="td-header"><strong>Payment Received</strong></td>
        <td class="odd"><?php echo $row2['Date']; ?></td>
      </tr>
      <tr>
        <td width="120" class="td-header"><strong>Date Submitted</strong></td>
        <td class="even"><?php echo $row2['DateSubmitted']; ?></td>
      </tr>
      <tr>
        <td width="120" class="td-header"><strong>Batch Amount</strong></td>
        <td class="odd">R<?php echo $row2['Amount']; ?></td>
      </tr>
      <tr>
        <td width="120" class="td-header"><strong>Remitted By</strong></td>
        <td class="even"><?php echo $row2['Name_2']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr class="td-header">
    <td width="50">Inv No</td>
    <td width="50">Job No</td>
    <td>Site</td>
    <td width="100">Date</td>
    <td width="75" align="right">Total</td>
  </tr>
  <?php while($row = mysqli_fetch_array($query)){ ?>
  <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
    <td width="50"><?php echo $row['InvoiceNo']; ?></td>
    <td width="50"><?php echo $row['JobNo']; ?></td>
    <td><?php echo $row['Name_1']; ?></td>
    <td width="100"><?php echo $row['InvoiceDate']; ?></td>
    <td width="75" align="right">R<?php echo $row['Amount_1']; ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="3" rowspan="3">&nbsp;</td>
    <td align="right" class="td-header">Total</td>
    <td align="right" class="even"><b><?php echo 'R'. number_format($row2['Amount'] + $row2['Discount'],2); ?></b></td>
  </tr>
  <tr>
    <td align="right" class="td-header">Discount</td>
    <td align="right" class="odd"><b><?php echo 'R'. number_format($row2['Discount'],2); ?></b></td>
  </tr>
  <tr>
    <td align="right" class="td-header">Total Paid</td>
    <td align="right" class="even"><b><?php echo 'R'. number_format($row2['Amount'],2); ?></b></td>
  </tr>
</table>
<div>
</div>	
</div>
    <div class="highslide-footer">
        <div>
            <span class="highslide-resize" title="Resize">
                <span></span>
            </span>
        </div>
    </div>
</div>                          
                          
                          </td>
                          <td align="center"><a href="pdf/pdf/Seavest Remittance <?php echo $row_Recordset3['Id']; ?>.pdf" target="_blank"></a><a href="pdf/pdf/Seavest Remittance <?php echo $row_Recordset3['Id']; ?>.pdf" target="_blank"><img src="images/icons/download.png" width="25" height="25"></a></td>
                          <td width="40" align="center">
                            <?php
			  
			  // Check if Pragma and send XL format
			  
			  $companyid = $row_Recordset3['CompanyId'];
			  
			  if($companyid == 2){
				  
				  $value = $row_Recordset3['JobId'];
				  
			  } else {
				  
				  $value = $row_Recordset3['PDF'];
			  }
			  			  
			  ?>
                  <input name="remittanceid" type="radio" id="file[]" value="<?php echo $row_Recordset3['Id']; ?>" <?php if($_GET['Id'] == $row_Recordset3['Id']){ echo 'checked="checked"'; } ?>>
                  
                  
                  </td>
                </tr>
						<?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
                    <tr>
                      <td colspan="4" align="right" class="td-header">
					  <?php 
					  if($totalRows_rs_totals >= 1){
						  
						  if(isset($_GET['Company'])){
							  
							  $where = "WHERE Status = '12' AND CompanyId = '". $_GET['Company'] ."'";
							  
						  } else {
							  
							  $where = "WHERE Status = '12'";
							  
						  }
						  
						  sum_outstanding($where); 
					  }
					  ?>
                      </td>
                      <td align="right" class="td-header">&nbsp;</td>
                      <td align="right" class="td-header">&nbsp;</td>
                      <td align="right" class="td-header">&nbsp;</td>
                      </tr>
			  </table>
        </form>
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

mysql_free_result($Recordset4);
?>
