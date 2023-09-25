<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<link href="../css/calendar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
$date = '21-01-2016';

echo date("d", strtotime("$date - 1 months")).'<br>';
echo date("m", strtotime("$date - 1 months")).'<br>';
echo date("Y", strtotime("$date - 1 months")).'<br>';

echo date('01-11-Y', strtotime('+1 year'));
?>
</body>
</html>