<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="quote-archive-process.php?Id=<?php echo $_GET['Id']; ?>">
  <table border="0" cellpadding="5" cellspacing="5" class="tarea">
    <tr>
      <td width="249"><table border="0" align="center" cellpadding="2" cellspacing="3" class="combo">
        <tr>
          <td width="63">Accepted</td>
          <td width="20"><input name="approval" type="radio" value="Accepted" /></td>
        </tr>
        <tr>
          <td>Rejected</td>
          <td><input name="approval" type="radio" value="Rejected" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="right"><input name="Submit" type="submit" class="tarea2" value="Submit" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
