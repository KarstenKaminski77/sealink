<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="mail.php?Id=<?php echo $_GET['Id']; ?>">
<br />
<table border="0" align="center" cellpadding="2" cellspacing="3">
    <tr>
      <td><input name="email" type="text" class="tarea2" id="email" style="width:300px; cursor: text;" /></td>
    </tr>
    <tr>
      <td align="right"><input name="Submit" type="submit" class="btn-send" value="" /></td>
    </tr>
  </table>
</form>
</body>
</html>