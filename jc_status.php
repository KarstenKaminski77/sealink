<?php require_once('Connections/seavest.php'); ?>
<?php

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

select_db();

$query2 = mysql_query("SELECT * FROM tbl_jc WHERE Status = '1' OR Status = '2' OR Status = '4'")or die(mysql_error());
while($row2 = mysql_fetch_array($query2)){

$id = $row2['Id'];

$date_required = $row2['Date2'];
$date = explode("-",$date_required);

$year = $date[0];
$month = $date[1];
$day = $date[2];

$completion = $year.$month.$day;

mysql_query("UPDATE tbl_jc SET CompletionDate = '$completion' WHERE Id = '$id'")or die(mysql_error());
}

$current_date = date('Ymd');

$query = mysql_query("SELECT * FROM tbl_jc WHERE (Status <= '4' AND Status != '3') AND (CompletionDate < '$current_date' AND CompletionDate > '1') GROUP BY JobId")or die(mysql_error());
$numrows = mysql_num_rows($query);
if($numrows >= 1){
$expire = 60 * 60 * 24 * 360 + time();
setcookie('status','1',$expire);
} else {
setcookie('status','1',time() - 3600);
//header('Location: jc_select.php');
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
-->
</style>
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
if($numrows == 0){
include('menu.php'); 
}
?>
      </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%"cellpadding="0" cellspacing="1">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">
<table width="730" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td width="100" align="left" nowrap class="tb_border"><strong>Jobcard </strong></td>
    <td width="150" align="left" class="tb_border"><strong>Company</strong></td>
    <td width="250" align="left" class="tb_border"><strong>Site Address </strong></td>
    <td width="150" align="left" class="tb_border"><strong>Requested Completion</strong></td>
    <td width="40" align="center" class="tb_border">&nbsp;</td>
    <td width="40" align="center" class="tb_border">&nbsp;</td>
  </tr>
<?php

while($row = mysql_fetch_array($query)){

$received_date = explode("-",$row['Date1']);
$requested_completion = explode("-",$row['Date2']);
$completion = $requested_completion[0].$requested_completion[1].$requested_completion[2];
$current_date = date('Ymd');
$siteid = $row['SiteId'];
$companyid = $row['CompanyId'];

$requested_completion_date = date('d M Y', mktime(0,0,0,$requested_completion[1],$requested_completion[2],$requested_completion[0]));

$query2 = mysql_query("SELECT * FROM tbl_companies WHERE Id = '$companyid'")or die(mysql_error());
$row2 = mysql_fetch_array($query2);

$query3 = mysql_query("SELECT * FROM tbl_sites WHERE Id = '$siteid'")or die(mysql_error());
$row3 = mysql_fetch_array($query3);
?>
                        
                          <tr>
                            <td width="100"><a href="jc_calc.php?Id=<?php echo $row['JobId']; ?>&job" class="menu" ONMOUSEOVER="popup('<?php echo $row['JobDescription']; ?>','#CEE1F7')" ONMOUSEOUT="kill()";><?php echo $row['JobNo']; ?></a></td>
                            <td width="150"><a href="jc_calc.php?Id=<?php echo $row['JobId']; ?>&job" class="menu"><?php echo $row2['Name']; ?></a></td>
                            <td width="250"><a href="jc_calc.php?Id=<?php echo $row['JobId']; ?>&job" class="menu"><?php echo $row3['Name']; ?></a></td>
                            <td width="150"><a href="jc_calc.php?Id=<?php echo $row['JobId']; ?>&job" class="menu"><?php echo $requested_completion_date; ?></a></td>
                            <td width="40" align="center">
							<a href="jc_calc.php?Id=<?php echo $row['JobId']; ?>&job">
							<img src="images/edit.jpg" width="20" height="20" border="0"></a></td>
                            <td width="40" align="center">
							<img style="cursor:pointer" src="images/mail2.jpg" width="20" height="18" onClick="MM_openBrWindow('jc_status_mail_ini.php?Id=<?php echo $row['JobId']; ?>','','width=400,height=250')"></td>
                          </tr>
<?php } ?>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
