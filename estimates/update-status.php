<?php
require_once('../functions/functions.php');

if(isset($_POST['Submit'])){
	
	$quoteno = $_GET['Id'];
	$type = $_POST['approval'];
	
	mysqli_query($con, "UPDATE tbl_qs SET Status = '3', Type = '$type' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table border="0" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="3" class="combo">
        <tr>
          <td><label for="radio-1">Estimate Was Accepted</label></td>
          <td width="20"><input name="approval" type="radio" id="radio-1" value="Accepted" /></td>
        </tr>
        <tr>
          <td><label for="radio-2">Estimate Was Rejected</label></td>
          <td><input name="approval" type="radio" id="radio-2" value="Rejected" /></td>
        </tr>
        <tr>
          <td colspan="2" align="right"><input name="Submit" type="submit" class="btn-new" value="Submit" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
