<?php
require_once('functions/functions.php');

select_db();

$jobid = $_GET['Id'];

if(isset($_GET['reject'])){
	
	$reason = $_POST['reason'];
	
	mysql_query("UPDATE tbl_jc SET Status = '5', Reason = '$reason', RequestPreWorkPo = '0' WHERE JobId = '$jobid'")or die(mysql_error());
	
	header('Location: invoices/pending.php');
}
?>
<link href="styles/layout.css" rel="stylesheet" type="text/css">
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<form name="form1" method="post" action="reject.php?Id=<?php echo $_GET['Id']; ?>&reject">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><textarea name="reason" cols="70" rows="7" id="reason"></textarea></td>
    </tr>
    <tr>
      <td align="right"><input name="Submit" type="submit" class="tarea2" value="Send"></td>
    </tr>
  </table>
</form>
