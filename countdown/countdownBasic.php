<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>jQuery Countdown</title>
<link rel="stylesheet" href="jquery.countdown.css">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="jquery.plugin.js"></script>
<script src="jquery.countdown.js"></script>

<?php

$now = strtotime(date('Y-m-d H:i:s'));
$to = strtotime('2016-02-10 13:20:00');

$secs_before = $to - $now;
$secs_after = $now - $to;

echo $secs_before.'<br>';
echo $secs_after.'<br><br>';
?>

<script>
$(function () {
	<?php
	if(date('Y-m-d H:i:s') < '2016-02-10 13:20:00'){
		
		$class = 'bg-blue';
	?>
	$('#defaultCountdown').countdown({until: +<?php echo $secs_before; ?>});
	<?php 
	} else {
		
		$class = 'bg-red';
		
	?>
	$('#defaultCountdown').countdown({since: -<?php echo $secs_after; ?>});
	<?php } ?>
});
</script>
</head>
<body>
<div id="defaultCountdown" class="<?php echo $class; ?>"></div>
</body>
</html>
