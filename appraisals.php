<?php require_once('Connections/seavest.php'); ?>
<?php
require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
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

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT * FROM tbl_appraisal_questions WHERE `Current` = 1";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT * FROM tbl_technicians WHERE Id = %s", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

if(isset($_POST['question'])){

$date = $_POST['date'];
$employee = $_POST['employee'];
$question = $_POST['question'];
$answer = $_POST['answer'];
$comment = $_POST['comment'];

select_db();

$query = mysql_query("SELECT * FROM tbl_appraisal_questions WHERE Current = '1'")or die(mysql_error());
$numrows = mysql_num_rows($query);

for($i=0;$i<$numrows;$i++){

$date = $_POST['date'];
$employee = $_POST['employee'];
$ques = $question[$i];
$ans = $answer[$i];
$comment = $_POST['comment'];

mysql_query("INSERT INTO tbl_appraisal_answers (EmployeeId,Question,Answer,Comments,Date) VALUES ('$employee','$ques','$ans','$comment','$date')")or die(mysql_error());
}
header('Location: appraisal_select.php?submited');
}

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
        <td align="center"><p>&nbsp;</p>
          <form name="form1" method="post" action="">
            <table border="0" cellspacing="3" cellpadding="2">
              <tr>
                <td class="combo_bold">Appraisal:</td>
                <td class="combo"><?php echo $row_Recordset3['Date']; ?>
                  <input name="date" type="hidden" id="date" value="<?php echo $row_Recordset3['Date']; ?>"></td>
              </tr>
              <tr>
                <td class="combo_bold">Technician:</td>
                <td class="combo"><?php echo $row_Recordset4['Name']; ?>
                  <input name="employee" type="hidden" id="employee" value="<?php echo $row_Recordset4['Id']; ?>"></td>
              </tr>
              <tr>
                <td class="combo_bold">&nbsp;</td>
                <td class="combo">&nbsp;</td>
              </tr>
			  <?php do { ?>
              <tr>
                <td class="combo_bold">Question:</td>
                <td class="combo"><?php echo $row_Recordset3['Question']; ?>
                  <input name="question[]" type="hidden" id="question[]" value="<?php echo $row_Recordset3['Question']; ?>"></td>
              </tr>
              <tr>
                <td valign="top" class="combo_bold">Answer:</td>
                <td><textarea name="answer[]" cols="60" rows="7" id="answer[]"></textarea></td>
              </tr>

              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
				<?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
				<tr>
                  <td valign="top" class="combo_bold">Comments:</td>
				  <td><textarea name="comment" cols="60" rows="4" id="comment"></textarea></td>
			    </tr>
				<tr>
				  <td valign="top" class="combo_bold">&nbsp;</td>
				  <td>&nbsp;</td>
			    </tr>
				<tr>
				  <td valign="top" class="combo_bold">&nbsp;</td>
				  <td align="right"><input type="submit" name="Submit2" value="Save"></td>
			    </tr>
            </table>
            </form>
          <p>&nbsp;</p></td>
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

mysql_free_result($Recordset4);
?>
