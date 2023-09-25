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

$KTColParam1_Recordset1 = "0";
if (isset($_GET["date"])) {
  $KTColParam1_Recordset1 = (get_magic_quotes_gpc()) ? $_GET["date"] : addslashes($_GET["date"]);
}
$KTColParam2_Recordset1 = "0";
if (isset($_GET["Id"])) {
  $KTColParam2_Recordset1 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT tbl_appraisal_answers.Date, tbl_technicians.Name, tbl_technicians.Id, tbl_appraisal_answers.Comments, tbl_appraisal_answers.Answer, tbl_appraisal_answers.Question FROM (tbl_appraisal_answers LEFT JOIN tbl_technicians ON tbl_technicians.Id=tbl_appraisal_answers.EmployeeId) WHERE tbl_appraisal_answers.Date='%s'  AND tbl_technicians.Id=%s ", $KTColParam1_Recordset1,$KTColParam2_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$id = $_GET['Id'];
$date = $_GET['date'];

select_db();

$query_comments = mysql_query("SELECT * FROM tbl_appraisal_answers WHERE EmployeeId = '$id' AND Date = '$date'")or die(mysql_error());
$row_comments = mysql_fetch_array($query_comments);

$comments = $row_comments['Comments'];
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
          
            <div style="padding-left:25px; padding-right:25px">
              <table border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td class="combo_bold">Appraisal:</td>
                  <td class="combo"><?php echo $row_Recordset1['Date']; ?>
                      <input name="date" type="hidden" id="date" value="<?php echo $row_Recordset3['Date']; ?>"></td>
                </tr>
                <tr>
                  <td class="combo_bold">Technician:</td>
                  <td class="combo"><?php echo $row_Recordset1['Name']; ?>
                      <input name="employee" type="hidden" id="employee" value="<?php echo $row_Recordset4['Id']; ?>"></td>
                </tr>
                <tr>
                  <td class="combo_bold">&nbsp;</td>
                  <td class="combo">&nbsp;</td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td class="combo_bold">Question:</td>
                    <td class="combo"><?php echo $row_Recordset1['Question']; ?>
                    <input name="question[]" type="hidden" id="question[]" value="<?php echo $row_Recordset3['Question']; ?>"></td>
                  </tr>
                  <tr>
                    <td valign="top" class="combo_bold">Answer:</td>
                    <td class="combo"><?php echo $row_Recordset1['Answer']; ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
                <tr>
                  <td valign="top" class="combo_bold">Comments:</td>
                  <td class="combo"><?php echo $comments; ?></td>
                </tr>
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
