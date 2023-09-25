<?php

$start = new DateTime('2016-10-12 14:07:09');
$end = new DateTime('2016-10-14 13:07:09');

$interval = date_diff($start,$end);

echo $interval->format('%h:%i:%s');
?>