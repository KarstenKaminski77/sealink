<?php require_once('Connections/seavest.php'); ?>
<?php
$colname_Recordset1 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$site = $row_Recordset1['SiteId'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites WHERE Id = '$site'";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="public/styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="costin-mail2.php?Id=<?php echo $_GET['Id']; ?>">
<br />
<table border="0" align="center" cellpadding="2" cellspacing="3">
    <tr>
      <td><input name="email" type="text" class="tarea2" id="email" style="width:300px; cursor: text;" value="<?php echo $row_Recordset2['Email']; ?>" /></td>
    </tr>
    <tr>
      <td align="right"><input name="Submit" type="submit" class="tarea3" value="Send" /></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
