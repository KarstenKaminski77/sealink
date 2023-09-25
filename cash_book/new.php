<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#6699CC">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
        <?php
include($_SERVER['DOCUMENT_ROOT'].'/inv/menu.php'); ?>
        <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top">
<div style="padding-left:2px; border:solid 1px #cccccc; margin:2px">
      <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE" class="combo_bold">
        <tr>
          <td align="center" nowrap><p>&nbsp;</p>
            <form name="form1" method="post" action="new_process.php">
              Name 
              <input name="name" type="text" class="tarea" id="name" size="35">
                       &nbsp; 
                       <input name="Submit" type="submit" class="combo_bold_btn" value="Create">
            </form>
            <p>&nbsp;</p>            <p>&nbsp;</p></td>
          </tr>
      </table>
    </div>    </td>
  </tr>
</table>
</body>
</html>