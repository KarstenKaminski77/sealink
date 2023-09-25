<?php require_once('Connections/seavest.php'); ?>
<?php 
session_start();

require_once('Connections/seavest.php');

require_once('Connections/inv.php');

require_once('functions/functions.php');

select_db();

if(isset($_GET['delete'])){
	
	$id = $_GET['delete'];
	
	$query = mysql_query("SELECT * FROM tbl_statements WHERE Id = '$id'")or die(mysql_error());
	$row = mysql_fetch_array($query);
	
	$file = $row['FileName'];
	
	if(file_exists('fpdf16/pdf/'.$file)){
		
		unlink('fpdf16/pdf/'.$file);
		
		
	}
	
	mysql_query("DELETE FROM tbl_statements WHERE Id = '$id'")or die(mysql_error());
	
	//header('Location: inv-statements.php');
}

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

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = $_GET['Id'];
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_statements WHERE Id = %s", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_statements.Id,  tbl_statements.NoInvoices, tbl_statements.DateGenerated, tbl_statements.FileName, tbl_companies.Name FROM (tbl_statements LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_statements.CompanyId) ORDER BY DateGenerated DESC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT Id, Name FROM tbl_companies";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

mysql_select_db($database_inv, $inv);
$query_Recordset5 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset5 = mysql_query($query_Recordset5, $inv) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

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
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<style>
#dek {
POSITION:absolute;
VISIBILITY:hidden;
Z-INDEX:200;}
</style>
<script type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>
<body <?php if(isset($_GET['jcn'])){ ?>onload="document.form2.jobno.focus();"<?php } ?>
      <?php if(isset($_GET['in'])){ ?>onload="document.form2.invoiceno.focus();"<?php } ?>
      <?php if(isset($_GET['qn'])){ ?>onload="document.form2.quoteno.focus();"<?php } ?>
>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php
include('menu.php'); ?>
    </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
          </tr>
          <tr>
            <td colspan="3" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"><p>&nbsp;</p>
              <div style="margin-left:30px">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td class="combo_bold"><form name="form2" method="post" action="fpdf16/pdf/statements-pdf.php">
                          <select name="companyid" class="blue-generic" id="companyid">
                            <option value="">Oil Company</option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset5['Id']; ?>" <?php if($_POST['companyid'] == $row_Recordset5['Id']){ ?> selected="selected"<?php } ?>><?php echo $row_Recordset5['Name']; ?> </option>
                            <?php
} while ($row_Recordset5 = mysql_fetch_assoc($Recordset5));
  $rows = mysql_num_rows($Recordset5);
  if($rows > 0) {
      mysql_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysql_fetch_assoc($Recordset5);
  }
?>
                            </select>
                          </select>
                          <input name="button2" type="submit" class="btn-blue-generic" id="button2" value="Submit">
                          </form></td>
                        </tr>
                      <tr>
                        <td class="combo_bold">&nbsp;</td>
                        </tr>
                      <tr>
                        <td align="center" class="combo_bold">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                  <tr>
                    <td>
                    <form name="form3" method="post" action="fpdf16/pdf/statement-mail.php">
                      <table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
            <tr>
  <td colspan="9" nowrap><input name="email" type="text" class="tfield-generic-statements" id="email" value="To" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:705px"></td>
              </tr>
            <tr>
              <td colspan="9" nowrap><input name="attach" type="file" class="tfield-generic-statements" id="attach" style="width:74px"></td>
            </tr>
            <tr>
              <td colspan="9" nowrap><textarea name="message" rows="5" class="tfield-generic-statements" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:705px">Message</textarea></td>
            </tr>
                        <tr>
                          <td align="right"><input name="button3" type="submit" class="btn-blue-generic" id="button3" value="Send"></td>
                          </tr>
                        <?php if(isset($_GET['Id'])){ ?>
                        <tr>
                          <td class="KT_field_error"><strong><em><?php echo $row_Recordset2['FileName']; ?></em></strong> successfully sent.</td>
                        </tr>
                        <?php } ?>
                        <tr>
                          <td>&nbsp;</td>
                          </tr>
                      </table>
<div id="list-brdr">
  <table width="705" align="center" cellpadding="0" cellspacing="1">
    <tr class="td-header">
      <td width="110" align="center" nowrap><strong>Statement No.&nbsp; </strong></td>
      <td width="110" align="center" nowrap>Invoices</td>
      <td align="left"><strong>&nbsp;Company</strong></td>
      <td width="194" align="left"><strong>&nbsp;Date</strong></td>
      <td colspan="4" align="center">&nbsp;</td>
      </tr>
    <?php do { ?>
      <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
        <td width="110" align="center" class="combo">&nbsp;<?php echo $row_Recordset3['Id']; ?></td>
        <td width="110" align="center" class="combo"><?php echo $row_Recordset3['NoInvoices']; ?></td>
        <td width="198" class="combo">&nbsp;<?php echo $row_Recordset3['Name']; ?></td>
        <td width="194" class="combo">&nbsp;<?php echo $row_Recordset3['DateGenerated']; ?></td>
        <td width="44" align="center">
        <input name="view" type="button" class="btn-blue-generic" id="view" value="View" onClick="location.href='fpdf16/pdf/<?php echo $row_Recordset3['FileName']; ?>';">
        </td>
        <td width="72" align="center">
        <input name="download" type="button" class="btn-blue-generic" id="download" onClick="location.href='fpdf16/pdf/statement-download.php?Id=<?php echo $row_Recordset3['FileName']; ?>';" value="Download">
        </td>
        <td width="52" align="center">
        <a href="inv-statements.php?delete=<?php echo $row_Recordset3['Id']; ?>">
        <img src="images/icons/btn-delete.png" width="25" height="25" border="0">
        </a>
        </td>
        <td width="25" align="center"><input type="radio" name="file" id="radio" value="<?php echo $row_Recordset3['Id']; ?>"></td>
        </tr>
      <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
    </table>
</div>
                    </form></td>
                    </tr>
                </table>
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

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset3);

?>
