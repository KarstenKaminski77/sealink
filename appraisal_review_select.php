<?php require_once('Connections/seavest.php'); ?>
<?php

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT tbl_appraisal_answers.Question, tbl_appraisal_answers.Answer, tbl_appraisal_answers.Comments, tbl_appraisal_answers.Date, tbl_technicians.AreaId, tbl_technicians.Name, tbl_technicians.Id FROM (tbl_appraisal_answers LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_appraisal_answers.EmployeeId) WHERE tbl_technicians.AreaId = '$areaid' GROUP BY Date  ORDER BY Date DESC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$KTColParam1_Recordset2 = "0";
if (isset($_GET["date"])) {
  $KTColParam1_Recordset2 = (get_magic_quotes_gpc()) ? $_GET["date"] : addslashes($_GET["date"]);
}
$KTColParam2_Recordset2 = "0";
if (isset($_GET["area"])) {
  $KTColParam2_Recordset2 = (get_magic_quotes_gpc()) ? $_GET["area"] : addslashes($_GET["area"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT tbl_appraisal_answers.Question, tbl_appraisal_answers.Answer, tbl_appraisal_answers.Comments, tbl_appraisal_answers.Date, tbl_technicians.AreaId, tbl_technicians.Name, tbl_technicians.Id FROM (tbl_appraisal_answers LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_appraisal_answers.EmployeeId) WHERE tbl_appraisal_answers.Date='%s'  AND tbl_technicians.AreaId=%s GROUP BY tbl_technicians.Name", $KTColParam1_Recordset2,$KTColParam2_Recordset2);
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
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
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
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#6699CC">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
        <?php
include('menu.php'); ?>
        <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
          <tr>
            <td align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          
            <div style="padding-left:25px">
              <table border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td valign="top" class="combo">
                  <?php 
				  if(isset($_GET['date'])){
				  $appraisal_date = $_GET['date'];
				  $selected_appraisal_date = date('d M Y', strtotime($appraisal_date));
				  echo $selected_appraisal_date; 
				  }
				  ?>                </td>
                  <td valign="top" class="combo">&nbsp;</td>
                  <td valign="top" class="combo"><?php 
$date = $_GET['date'];

$query = mysql_query("SELECT tbl_appraisal_answers.Date, tbl_technicians.Name, tbl_technicians.Id, tbl_technicians.AreaId FROM (tbl_appraisal_answers LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_appraisal_answers.EmployeeId) WHERE tbl_appraisal_answers.Date='$date' AND tbl_technicians.AreaId = '$areaid' GROUP BY tbl_technicians.Name ORDER BY Id DESC")or die(mysql_error());
while($row = mysql_fetch_array($query)){ 
?>
                      <table border="0" cellpadding="0" cellspacing="0" class="combo">
                        <tr>
                          <td valign="top">
						  <div style="margin-bottom:5px">
						  <a href="appraisal_view.php?Id=<?php echo $row['Id']; ?>&date=<?php echo $_GET['date']; ?>" class="menu" style="font-weight:normal; font-size:12px; line-height: normal;"><? echo $row['Name']; ?></a>
						  </div>
						  </td>
                        </tr>
                      </table>
                    <?php } // close loop ?></td>
                </tr>
                <tr>
                  <td colspan="3" valign="top" class="combo">&nbsp;</td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td colspan="3" valign="top" class="combo"><?php 
				  if(!empty($row_Recordset1['Date'])){
				  $date = $row_Recordset1['Date']; 
				  $new_date = date('d M Y',strtotime($date));
				  ?>
<a href="appraisal_review_select.php?date=<?php echo $row_Recordset1['Date']; ?>&area=<?php echo $areaid; ?>" class="menu"><?php echo $new_date; ?></a><?php } ?></td>
                  </tr>
                  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                          </table>
            </div>
            <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
