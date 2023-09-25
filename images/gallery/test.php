<?php

function createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality)
{
  // Open the original image.
  $original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original");
  list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");
  
  $ratio=$width/$height;
  $newHeight=(int)$ratio*120;
 
  // Resample the image.
  $tempImg = imagecreatetruecolor($newWidth, $newHeight) or die("Cant create temp image");
  imagecopyresized($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die("Cant resize copy");
 
  // Create the new file name.
  $newNameE = explode(".", $img);
  $newName = ''. $newNameE[0] .''. $suffix .'.'. $newNameE[1] .'';
 
  // Save the image.
  imagejpeg($tempImg, "$imgPath/$newName", $quality) or die("Cant save image");
 
  // Clean up.
  imagedestroy($original);
  imagedestroy($tempImg);
  return true;
}

$thumb = createThumbnail('0-19-05-55.jpg', 'images', "-thumb", 120, 100, 100);


$image = 'images/0-19-05-55-thumb.jpg';
$degrees = 90;
header('Content-type: image/jpeg');
$source = imagecreatefromjpeg($image);
$rotate = imagerotate($source, $degrees, 0);
imagejpeg($rotate);

?>
