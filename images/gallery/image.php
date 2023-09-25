<?php
header('Content-type: image/jpeg');
$source = imagecreatefromjpeg($image);
$rotate = imagerotate($source, $degrees, 0);
imagejpeg($rotate);
?>