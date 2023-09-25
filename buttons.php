<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	background-color: #DCEAFA;
}
-->
</style></head>

<body>
<table border="0" cellpadding="2" cellspacing="3" class="combo_bold">
  <tr>
    <td><form method="post" name="form5" id="form5" onclick="MM_openBrWindow('reject.php?Id=<?php echo $_GET['Id']; ?>','','scrollbars=yes,width=600,height=200')">
      <input name="Submit5" type="submit" class="camcel" value="Reject" />
    </form></td>
    <td width="50"><form action="invoice_calc.php?delete&amp;Id=<?php echo $_GET['Id']; ?>" method="post" name="form4" id="form4">
      <input name="Submit4" type="submit" class="camcel" value="Cancel" />
    </form></td>
    <td width="50" align="right" valign="middle"><form action="inv_preview.php?Id=<?php echo $_GET['Id']; ?>" method="post" name="form3" target="_blank" id="form3">
      <input name="Submit" type="submit" class="tarea2" id="Submit" value="Preview" />
    </form></td>
  </tr>
</table>
</body>
</html>
