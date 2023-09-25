<?php
require_once('functions.php');

select_db();

if(isset($_POST['bank'])){
$bank = $_POST['bank'];
$cash = $_POST['cash'];

mysql_query("UPDATE tbl_budget SET BankTotal = '$bank', CashTotal = '$cash'")or die(mysql_error());
}
$query = mysql_query("SELECT * FROM tbl_budget")or die(mysql_error());
$row = mysql_fetch_array($query);
$bank = $row['BankTotal'];
$cash = $row['CashTotal'];

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
<form id="form1" name="form1" method="post" action="balances.php?update">
  <div align="center">
    <p>
      <?php if(isset($_GET['update'])){ ?>
   </p>
    <p>&nbsp;</p>
    <p>     <span class="combo_bold">Balances Successfully Updated ...</span>
      <?php } else { ?>
    </p>
    <table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td class="combo_bold">Bank</td>
        <td><input name="bank" type="text" class="tarea" id="bank" size="30" value="<?php echo $row['BankTotal']; ?>" /></td>
      </tr>
      <tr>
        <td class="combo_bold">Cash</td>
        <td><input name="cash" type="text" class="tarea" id="cash" size="30" value="<?php echo $row['CashTotal']; ?>" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><input name="Submit" type="submit" class="combo_bold_btn" value="Save" /></td>
      </tr>
      </table>
	  <?php } ?>
  </div>
</form>
</body>
</html>
