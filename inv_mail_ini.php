<?php
session_start();

require_once('functions/functions.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="fpdf16/test.php?Id=<?php echo $_GET['Id']; ?>">
  <div align="center">
    <table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td align="center"><input name="email" type="text" class="tarea2" id="email" style="cursor:text" size="70"/></td>
      </tr>
      <tr>
        <td align="center"><textarea name="message" cols="70" rows="10" class="tarea2" id="message" style="cursor:text"></textarea></td>
      </tr>
      <tr>
        <td align="center"><input name="Submit" type="submit" class="camcel" value="Send Email" />
        <input name="jobid" type="hidden" id="jobid" value="<?php echo $_GET['Id']; ?>" /></td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
