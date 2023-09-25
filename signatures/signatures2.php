<?php
session_start();

function createImage(){
// creates the images, writes the file
$im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/inv/signatures/template.png");
$colour = imagecolorallocate($im, 2, 114,158);
$colour_grey = imagecolorallocate($im, 147, 147,148);

$font_bold = $_SERVER['DOCUMENT_ROOT'].'/inv/signatures/arialbd.ttf';
$font = $_SERVER['DOCUMENT_ROOT'].'/inv/signatures/arial.ttf';

$salutation = "Kind Regards";
$name = "Karsten Kaminski";
$tel = "031 701 7852";
$cell = "082 382 3960";
$web = "www.seavest.co.za";
$mail = "karsten@kwd.co.za";

$x = imagesx($im) - 170 ;
$y = imagesy($im) - 105;
// Add the text
imagettftext($im, 11, $angle, 4, 15, $colour_grey, $font_bold, $salutation);
imagettftext($im, 11, $angle, 4, 35, $colour_grey, $font_bold, $name);
imagettftext($im, 10, $angle, $x, $y + 20, $colour, $font, $tel);
imagettftext($im, 10, $angle, $x, ($y + 40), $colour, $font, $cell);
imagettftext($im, 10, $angle, $x, ($y + 60), $colour, $font, $web);
imagettftext($im, 10, $angle, $x, ($y + 80), $colour, $font, $mail);
$outfile= "KarstenKaminski.png";
imagepng($im,$outfile);
return $outfile;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php echo "<img src=".createImage()." />"; ?>
</body>
</html>