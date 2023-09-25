<?php
$print = $_POST['print'];
$rows = count($_POST['print']);

for($i=0;$i<$rows;$i++){
	
	$orderid = $print[$i];
	
?>
	
	   <script>
       window.open("http://www.seavest.co.za/inv/order-forms/print.php?Id=<?php echo $orderid; ?>");
      </script>

<?php	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Batch Print</title>
</head>
<body onLoad="window.close();">
</body>
</html>
