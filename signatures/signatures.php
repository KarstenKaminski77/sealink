<?php
session_start();

function createImage(){
// creates the images, writes the file
$im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/inv/signatures/template.png");
$colour = imagecolorallocate($im, 2, 114,158);
$colour_grey = imagecolorallocate($im, 147, 147,148);

$font_round = $_SERVER['DOCUMENT_ROOT'].'/inv/signatures/ARLRDBD.TTF';
$font_bold = $_SERVER['DOCUMENT_ROOT'].'/inv/signatures/arialbd.ttf';
$font = $_SERVER['DOCUMENT_ROOT'].'/inv/signatures/arial.ttf';

$salutation = "Kind Regards";
$name = $_POST['name'];
$tel = $_POST['telephone'];
$cell = $_POST['cell'];
$web = $_POST['web'];
$mail = $_POST['email'];
$fax = $_POST['fax'];

$file = explode(" ", $name);
$filename = $file[0].$file[1];

$x = imagesx($im) - 170 ;
$y = imagesy($im) - 125;
// Add the text
imagettftext($im, 11, $angle, 4, 15, $colour_grey, $font_round, $salutation);
imagettftext($im, 11, $angle, 4, 35, $colour_grey, $font_bold, $name);
imagettftext($im, 10, $angle, $x, $y + 20, $colour, $font, $tel);
imagettftext($im, 10, $angle, $x, ($y + 40), $colour, $font, $cell);
imagettftext($im, 10, $angle, $x, ($y + 60), $colour, $font, $fax);
imagettftext($im, 10, $angle, $x, ($y + 80), $colour, $font, $mail);
imagettftext($im, 10, $angle, $x, ($y + 100), $colour, $font, $web);
$outfile = $filename.".png";
imagepng($im,$outfile);
echo "<img src=".$outfile." />";
echo '<br><br><a style="color:#000066" href="download.php?image='. $outfile .'"><img src="../images/btn-download.jpg" width="86" height="29"></a>';
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FFFFFF;
}
a:hover {
	text-decoration: none;
	color: #CCCCCC;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
-->
</style>
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('../menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0" class="combo">
      <tr>
        <td align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <p style="padding-left:30px">
<?php 
createImage(); 
?>
          </p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>