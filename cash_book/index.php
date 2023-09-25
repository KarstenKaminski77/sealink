<?php require_once('../Connections/seavest.php'); ?>
<?php
mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_system_balances ORDER BY Id DESC LIMIT 1";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

require_once('../includes/tng/tNG.inc.php');

require_once('../functions/functions.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
        </td>
    <td valign="top">
<table width="760" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center"></div>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <div style="padding-left:2px; border:solid 1px #cccccc; margin:2px; width:400px"><table width="400" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE" class="combo_bold">
        <tr>
          <td align="center" nowrap>
		  <form name="form1" method="post" action="new_process.php">
		    <br>
          Bank Balance
          <input name="balance" type="text" class="tarea" id="balance" value="<?php echo $row_Recordset1['BankBalance']; ?>" size="35">
          <input name="Submit" type="submit" class="combo_bold_btn" value="Update">
		  <br><br>
            </form>
            </td>
          </tr>
      </table>
    </div></td>
      </tr>
    </table>    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
