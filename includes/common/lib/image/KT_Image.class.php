<?php
/*
	Copyright (c) InterAKT Online 2000-2005
*/

/** 
* manipulate images;
* @access public
*/
class KT_image
{
	/**
	 * keep the commands for ImageMagik
	 * @var array
	 * @access private
	 */
	var $arrCommands;
	
	/**
	 * imgtype for GD manipulation (gif, jpg, png);
	 * @var string
	 * @access private
	 */
	var $imgType;
	
	/**
	 * error message to be displayed as User Error
	 * @var array 
	 * @access private
	 */
	var $errorType = array();
	
	/**
	 * error message to be displayed as Developer Error
	 * @var array
	 * @access private
	 */
	var $develErrorMessage = array();
	
	/**
	 * contains the name of the order of prefered libraries
	 * @var array
	 * @access private
	 */
	var $orderLib = array();
	
	/**
	 * version of gd library;
	 * @var string
	 * @access private
	 */
	var $gdInfo;
	
	/**
	 * string for which the GD has no support;
	 * @var string
	 * @access private
	 */
	var $gdNoSupport;
	
	/**
	 * path of the succesfully executed Image Magick command (if any)
	 * @var string
	 * @access private
	 */
	var $imageMagickPath;
	
	/**
	 * Constructor. Set default values for some variables
	 * @access public
	 */
	function KT_image() {
		$this->arrCommands = array(
								"C:\\PROGRA~1\\IMAGEM~1\\",
								'/usr/bin/',
								'/usr/bin/X11/',
								'/usr/X11R6/bin/'
							 );
		$this->orderLib = array('imagemagick','gd');
		$this->gdNoSupport = '';
		$this->imageMagickPath = '';
		$this->getVersionGd();
	}
	
	/**
	 * setter. change the order of the execution for libs;	
	 * @param string $lib lib name: gd or imagemagick
	 * @return nothing;
	 * @access public
	 */
	function setPreferedLib($lib)
	{
		$lib = strtolower($lib);
		if (in_array($lib, $this->orderLib)) {
			$i = array_search($lib, $this->orderLib);
			array_splice($this->orderLib, $i, 1);
		}
		array_unshift($this->orderLib, $lib);
	}
	
	/**
	 * prepend in front of the commands array a new command;
	 * @param string $command
	 * @return nothing;
	 * @access public
	 */
	function addCommand($command)
	{
		$command = trim($command);
		if ($command =='') {
			return;
		}
		if ( substr($command, -1, 1) != '/' && substr($command, -1, 1) != '\\' && substr($command, -7) != 'convert' ) {
			$command .= DIRECTORY_SEPARATOR;
		}
		array_unshift($this->arrCommands, $command);
	}
	
	/**
	 *take a file name as the only argument and return an array conatining the image dimensions;
	 * @param string $sourceFileName  path to the source file;
	 * @return array (x, y) on succes, [array] (-1, -1) on error;
	 * @access public
	 */
	function imageSize($sourceFileName)
	{
		$res = array(-1,-1);
		if (!is_file($sourceFileName)) {
			$this->setError('PHP_IMAGE_NO_IMG', array(), array($sourceFileName));
			return $res;
		}
		if (!$this->is_readable($sourceFileName)) {
			$this->setError('PHP_IMAGE_READ_ERR', array(), array($sourceFileName));
			return $res;
		}
		
		$arr = @getimagesize($sourceFileName);
		if (is_array($arr)) {
			switch ($arr[2]) {
				case 1:
				case 2:
				case 3:
					$res = array($arr[0], $arr[1]);	 
					break;
			}	
		}
		return $res;
		
	}
	
	/**
	 * resize an image;
	 * @param string $sourceFileName  path to the source file;
	 * @param string $folder path to the destination file (without filename);
	 * @param string $destinationFileName  nama of the destination file;
	 * @param integer $newWidth  new width of the file
	 * @param integer $newHeight	new hight of the file;
	 * @param boolean $keepProportion if the proportion must be kept or not;
	 * @return nothing;
	 * @access public
	 */
	function resize($sourceFileName, $folder, $destinationFileName, $width, $height, $keepProportion)
	{	
		$this->checkFolder($folder, 'write', 'image resize');
		$this->validateFile($sourceFileName, 'image resize');
		if ($this->hasError()) {
			return;
		}	
		if ($keepProportion === "false") {
			$keepProportion = false;
		}
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'resize_' . $lib;
			if ($rez = $this->$lib($sourceFileName, $folder, $destinationFileName, $width, $height, $keepProportion)) {
				KT_setFilePermissions($folder.$destinationFileName);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_RESIZE_NO_LIB', array(), array($this->getGdNoSupport()));
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * resize an image using GD library;
	 * @param string $sourceFileName  path to the source file;
	 * @param string $folder path to the destination file (without filename);
	 * @param string $destinationFileName  nama of the destination file;
	 * @param integer $newWidth  new width of the file
	 * @param integer $newHeight	new hight of the file;
	 * @param boolean $keepProportion if the proportion must be kept or not;
	 * @return nothing;
	 * @access private
	 */
	function resize_gd($sourceFileName, $folder, $destinationFileName, $newWidth, $newHeight, $keepProportion)
	{
		$newWidth = (int)$newWidth;
		$newHeight = (int)$newHeight;
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($sourceFileName)) {
			return false;
		}
		$img = &$this->getImg($sourceFileName);
		$srcWidth = ImageSX($img);
		$srcHeight = ImageSY($img);

		if ( $keepProportion && ($newWidth != 0 && $srcWidth<$newWidth) && ($newHeight!=0 && $srcHeight<$newHeight) ) {
			if ($sourceFileName != $folder . $destinationFileName) {
				@copy($sourceFileName, $folder . $destinationFileName);
			}
			return true;
		}
		
		if ($keepProportion == true) {
			if ($newWidth != 0 && $newHeight != 0) {
				$ratioWidth = $srcWidth/$newWidth; 
				$ratioHeight = $srcHeight/$newHeight; 
				if( $ratioWidth < $ratioHeight ){ 
					$destWidth = $srcWidth/$ratioHeight;
					$destHeight = $newHeight; 
				} else { 
					$destWidth = $newWidth; 
					$destHeight = $srcHeight/$ratioWidth; 
				}
			} else {
				if ($newWidth != 0) {
					$ratioWidth = $srcWidth/$newWidth; 
					$destWidth = $newWidth; 
					$destHeight = $srcHeight/$ratioWidth; 
				} else if ($newHeight != 0) {
					$ratioHeight = $srcHeight/$newHeight; 
					$destHeight = $newHeight; 
					$destWidth = $srcWidth/$ratioHeight; 
				} else {
					$destWidht = $srcWidth;
					$destHeight = $srcHeight;
				}
			}
		} else {
			$destWidth = $newWidth; 
			$destHeight = $newHeight; 
		}
		$destWidth = round($destWidth);
		$destHeight = round($destHeight);
		
		$destImage = &$this->getImageCreate($destWidth, $destHeight); 
		
		$this->getImageCopyResampled($destImage, $img, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);
		ImageDestroy($img);
		$img = &$destImage;
		$this->createNewImg($img, $folder . $destinationFileName, 80);
		return true;
	}
	
	/**
	 * resize an image using ImageMagick library;
	 * @param string $sourceFileName  path to the source file;
	 * @param string $folder path to the destination file (without filename);
	 * @param string $destinationFileName  nama of the destination file;
	 * @param integer $newWidth  new width of the file
	 * @param integer $newHeight	new hight of the file;
	 * @param boolean $keepProportion if the proportion must be kept or not;
	 * @return nothing;
	 * @access private
	 */
	function resize_imagemagick($sourceFileName, $folder, $destinationFileName, $width, $height, $keepProportion)
	{
		if (!$this->checkImageMagik()) {
			return false;
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		$arrArguments = array(
							'-resize',
							($width==0?"":$width) . 'x' . ($height==0?"":$height) . ($keepProportion==true?'>':'!'),
							$sourceFileName,
							$folder . $destinationFileName
							);
		$shell->execute($arrCommands, $arrArguments);
		
		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_RESIZE_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	/**
	 * makes a thumbnail from an image;
	 * @param string $sourceFileName  path to the source file;
	 * @param string $folder path to the destination file (without filename);
	 * @param string $destinationFileName  nama of the destination file;
	 * @param integer $newWidth  new width of the file
	 * @param integer $newHeight	new hight of the file;
	 * @param boolean $keepProportion if the proportion must be kept or not;
	 * @return nothing;
	 * @access public
	 */
	function thumbnail($sourceFileName, $folder, $destinationFileName, $width, $height, $keepProportion)
	{
		$this->checkFolder($folder, 'write', 'create thumbnail');
		$this->validateFile($sourceFileName, 'create thumbnail');
		if ($this->hasError()) {
			return;
		}
		if ($keepProportion === "false") {
			$keepProportion = false;
		}
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'thumbnail_' . $lib;
			if ($rez = $this->$lib($sourceFileName, $folder, $destinationFileName, $width, $height, $keepProportion)) {
				KT_setFilePermissions($folder.$destinationFileName);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_THUMBNAIL_NO_LIB', array(), array($this->getGdNoSupport()));
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * makes an thumbnailout of an image using GD library;
	 * @param string $sourceFileName  path to the source file;
	 * @param string $folder path to the destination file (without filename);
	 * @param string $destinationFileName  name of the destination file;
	 * @param integer $newWidth  new width of the file
	 * @param integer $newHeight  new hight of the file;
	 * @param boolean $keepProportion if the proportion must be kept or not;
	 * @return nothing;
	 * @access private
	 */
	function thumbnail_gd($sourceFileName, $folder, $destinationFileName, $newWidth, $newHeight, $keepProportion)
	{
		$newWidth = (int)$newWidth;
		$newHeight = (int)$newHeight;
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($sourceFileName)) {
			return false;
		}
		$img = &$this->getImg($sourceFileName);
		$srcWidth = ImageSX($img);
		$srcHeight = ImageSY($img);

		if ( $keepProportion && ($newWidth != 0 && $srcWidth<$newWidth) && ($newHeight!=0 && $srcHeight<$newHeight) ) {
			if ($sourceFileName != $folder . $destinationFileName) {
				@copy($sourceFileName, $folder . $destinationFileName);
			}
			return true;
		}
		
		if ($keepProportion == true) {
			if ($newWidth != 0 && $newHeight != 0) {
				$ratioWidth = $srcWidth/$newWidth; 
				$ratioHeight = $srcHeight/$newHeight; 
				if( $ratioWidth < $ratioHeight ){ 
					$destWidth = $srcWidth/$ratioHeight;
					$destHeight = $newHeight; 
				} else { 
					$destWidth = $newWidth; 
					$destHeight = $srcHeight/$ratioWidth; 
				}
			} else {
				if ($newWidth != 0) {
					$ratioWidth = $srcWidth/$newWidth; 
					$destWidth = $newWidth; 
					$destHeight = $srcHeight/$ratioWidth; 
				} else if ($newHeight != 0) {
					$ratioHeight = $srcHeight/$newHeight; 
					$destHeight = $newHeight; 
					$destWidth = $srcWidth/$ratioHeight; 
				} else {
					$destWidht = $srcWidth;
					$destHeight = $srcHeight;
				}
			}
		} else {
			$destWidth = $newWidth; 
			$destHeight = $newHeight; 
		}
		$destWidth = round($destWidth);
		$destHeight = round($destHeight);
		
		$destImage = &$this->getImageCreate($destWidth, $destHeight); 
		
		$this->getImageCopyResampled($destImage, $img, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);
		ImageDestroy($img);
		$img = &$destImage;
		$this->createNewImg($img, $folder . $destinationFileName, 75);
		return true;
	}
	
	/**
	 * makes an thumbnail out of an image using ImageMagick library;
	 * @param string $sourceFileName  path to the source file;
	 * @param string $folder path to the destination file (without filename);
	 * @param string $destinationFileName  nama of the destination file;
	 * @param integer $newWidth  new width of the file
	 * @param integer $newHeight	new hight of the file;
	 * @param boolean $keepProportion if the proportion must be kept or not;
	 * @return nothing;
	 * @access private
	 */
	function thumbnail_imagemagick($sourceFileName, $folder, $destinationFileName, $width, $height, $keepProportion)
	{
		if (!$this->checkImageMagik()) {
			return false;
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		$arrArguments = array(
							'-thumbnail',
							($width==0?"":$width) . 'x' . ($height==0?"":$height) . ($keepProportion==true?'>':'!'),
							$sourceFileName,
							$folder . $destinationFileName
							);
		$shell->execute($arrCommands, $arrArguments);
		
		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_THUMBNAIL_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	/**
	 * adjust the Quality of an image;
	 * @param string $filename path to the source file;
	 * @param integer $qualityLevel  the quality; 
	 * @return nothing;
	 * @access public
	 */
	function adjustQuality($filename, $qualityLevel)
	{	
		$this->checkFolder($filename, 'write', 'adjust quality');
		$this->validateFile($filename, 'adjust quality');
		if ($this->hasError()) {
			return;
		}
		
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'adjustQuality_' . $lib;
			if ($rez = $this->$lib($filename, $qualityLevel)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_ADJUST_QUAL_NO_LIB', array(), array($this->getGdNoSupport()));
		} else {
			$this->setError('', array(), array());
		}
		
	}
	
	/**
	 * adjust quality image (for jpg) using GD library;	
	 * @param string $sourceFileName path to the source file;
	 * @param integer $qualityLevel  the quality; 
	 * @return nothing;
	 * @access private
	 */
	function adjustQuality_gd($sourceFileName, $qualityLevel)
	{
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($sourceFileName)) {
			return false;
		}
		$img = &$this->getImg($sourceFileName);
		$this->createNewImg($img, $sourceFileName, $qualityLevel);
		return true;
	}
	
	/**
	 * adjust quality image (for jpg) using image magick;	
	 * @param string $filename path to the source file;
	 * @param integer $qualityLevel  the quality; 
	 * @return nothing;
	 * @access private
	 */
	function adjustQuality_imagemagick($filename, $qualityLevel)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		$arrArguments = array(
							'-quality',
							$qualityLevel,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);

		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_ADJUST_QUAL_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}

	/**
	 * crop an image;
	 * @param string $filenamepath to the source file
	 * @param integer $x top left of the picture;
	 * @param integer $y top left of the picture;
	 * @param integer $destWidth width of the new picture;
	 * @param integer $destHeight height of the new picture;
	 * @return nothing;
	 * @access public
	 */
	function crop($filename, $x, $y, $width, $height)
	{
		$this->checkFolder($filename, 'write', 'crop');
		$this->validateFile($filename, 'crop');
		if ($this->hasError()) {
			return;
		}
		
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'crop_' . $lib;
			if ($rez = $this->$lib($filename, $x, $y, $width, $height)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_CROP_NO_LIB', array(), array($this->getGdNoSupport()));
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * crop an image using GD library;
	 * @param string $sourceFileName to the source file
	 * @param integer $x top left of the picture;
	 * @param integer $y top left of the picture;
	 * @param integer $destWidth width of the new picture;
	 * @param integer $destHeight height of the new picture;
	 * @return nothing;
	 * @access private
	 */
	function crop_gd($sourceFileName, $x, $y, $destWidth, $destHeight)
	{	
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($sourceFileName)) {
			return false;
		}
		$img = &$this->getImg($sourceFileName);
		$srcWidth = ImageSX($img); 
		$srcHeight = ImageSY($img); 
		$destImage = &$this->getImageCreate($destWidth, $destHeight);
		
		$this->getImageCopyResampled($destImage, $img, 0, 0, $x, $y, $destWidth, $destHeight, $destWidth, $destHeight);
		ImageDestroy($img);
		$this->createNewImg($destImage, $sourceFileName, 80);
		return true;
	}
	
	/**
	 * crop an image using image magick;
	 * @param string $fileName to the source file
	 * @param integer $x top left of the picture;
	 * @param integer $y top left of the picture;
	 * @param integer $destWidth width of the new picture;
	 * @param integer $destHeight height of the new picture;
	 * @return nothing;
	 * @access private
	 */
	function crop_imagemagick($filename, $x, $y, $width, $height)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		$arrArguments = array(
							'-crop',
							$width .'x'. $height .'+'. $x .'+'. $y,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);

		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_CROP_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	/**
	 * rotate an image;
	 * @param string $filename to the source file
	 * @param integer $degrees to rotate the image clockwise;
	 * @return nothing;
	 * @access public
	 */
	function rotate($filename, $degree)
	{
		$this->checkFolder($filename, 'write', 'rotate');
		$this->validateFile($filename, 'rotate');
    	$this->validateDegree($degree);
		if ($this->hasError()) {
			return;
		}
		
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'rotate_' . $lib;
			if ($rez = $this->$lib($filename, $degree)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_ROTATE_NO_LIB', array(), array($this->getGdNoSupport()));
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * rotate an image using GD library;
	 * @param string $filename to the source file
	 * @param integer $degrees to rotate the image clockwise;
	 * @return nothing;
	 * @access private
	 */
	function rotate_gd($sourceFileName, $degree)
	{	
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($sourceFileName)) {
			return false;
		}
		$img = &$this->getImg($sourceFileName);
		$srcWidth = ImageSX($img); 
		$srcHeight = ImageSY($img);
		if ($srcWidth>$srcHeight) {
			$newd = $srcWidth;
			$corection = $srcWidth - $srcHeight;
			$landscape = 1;
		} else {
			$newd = $srcHeight;
			$corection = $srcHeight - $srcWidth;
			$landscape = 0;
		}			
		$degree = 360 - $degree;
				
		switch ($degree) {
			case 360:
			case 0:
				$w = $srcWidth;
				$h = $srcHeight;
				$x1 = 0;
				$y1 = 0;
				break;
			case 180:
				$w = $srcWidth;
				$h = $srcHeight;
				if ($landscape) {
					$x1 = 0;
					$y1 = $corection;
				} else {	
					$x1 = $corection;
					$y1 = 0;
				}
				break;
			case 90:
				$w = $srcHeight;
				$h = $srcWidth;
				if ($landscape) {
					$x1 = 0;
					$y1 = 0;
				} else {
					$x1 = 0;
					$y1 = $corection;
				}
				break;
			case 270:
				$w = $srcHeight;
				$h = $srcWidth;
				if ($landscape) {
					$x1 = $corection;
					$y1 = 0;
				} else {
					$x1 = 0;
					$y1 = 0;
				}
				break;
		}

		$destImage = &$this->getImageCreate($newd, $newd);
		$finalImage = &$this->getImageCreate($w,$h);
		
		$this->getImageCopyResampled($destImage, $img, 0, 0, 0, 0, $srcWidth, $srcHeight, $srcWidth, $srcHeight);
		$rotatedImage = ImageRotate($destImage, $degree, 0);
		$this->getImageCopyResampled($finalImage, $rotatedImage, 0, 0, 0 + $x1, 0 + $y1, $w, $h, $w, $h);
		
		ImageDestroy($img);
		$this->createNewImg($finalImage, $sourceFileName, 80);
		return true;
	}
	
	/**
	 * rotate an image useing Image magick;
	 * @param string $filename to the source file
	 * @param integer $degrees to rotate the image clockwise;
	 * @return nothing;
	 * @access private
	 */
	function rotate_imagemagick($filename, $degree)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		$arrArguments = array(
							'-rotate',
							$degree,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);
	
		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_ROTATE_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	/**
	 * validate the degree. must be 0, 180, 90, 270, 360;
	 * @param integer $degree to be validated;
	 * @return nothing;
	 * @access private
	 */
	function validateDegree($degree)
	{
		switch ($degree) {
			case 0:
			case 180:
			case 90:
			case 270:
			case 360:
				break;
			default:
				$this->setError('PHP_IMAGE_ROTATE_DEG_ERR', array(), array());
				break;
		}
    return;
  }


	/**
	 * flip an image horisontally or vertically;
	 * @param string $filename to the source file
	 * @param string $direction how to flip horisontal or vertical;
	 * @return nothing;
	 * @access public
	 */
	function flip($filename, $direction)
	{
		$this->checkFolder($filename, 'write', 'flip');
		$this->validateFile($filename, 'flip');
		if ($this->hasError()) {
			return;
		}
		
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'flip_' . $lib;
			if ($rez = $this->$lib($filename, $direction)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_FLIP_NO_LIB', array(), array());
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * flip an image horisontally or vertically using GD library;
	 * @param string $filename to the source file
	 * @param string $direction how to flip horisontal or vertical;
	 * @return nothing;
	 * @access private
	 */
	function flip_gd($filename, $direction)
	{
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($filename)) {
			return false;
		}
		$srcImage = &$this->getImg($filename);
		$srcWidth = ImageSX($srcImage);
		$srcHeight = ImageSY($srcImage);
		$destImage = &$this->getImageCreate($srcWidth, $srcHeight);
		
		if (strtolower($direction)=='vertical') {
			for ($x = 0; $x < $srcWidth; $x++) {
				for ($y = 0; $y < $srcHeight; $y++) {
					imagecopy($destImage, $srcImage, $x, $srcHeight - $y - 1, $x, $y, 1, 1);
				}
			}
		} else {
			for ($x = 0; $x < $srcWidth; $x++) {
				for ($y = 0; $y < $srcHeight; $y++) {
					imagecopy($destImage, $srcImage, $srcWidth - $x - 1, $y, $x, $y, 1, 1);
				}
			}
		}
		ImageDestroy($srcImage);
		$this->createNewImg($destImage, $filename, 80);
		return true;
	}
	
	/**
	 * flip an image horisontally or vertically useing Image magick;
	 * @param string $filename to the source file
	 * @param string $direction how to flip horisontal or vertical;
	 * @return nothing;
	 * @access private
	 */
	function flip_imagemagick($filename, $direction)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		if (strtolower($direction)=='vertical') {
			$arg = '-flip';
		} else {
			$arg = '-flop';
		}
		$arrArguments = array(
							$arg,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);

		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_FLIP_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
		
	}
	
	/**
	 * apply an unsharp mask on the image;
	 * @param string $filename to the source file
	 * @param integer $intensity ;
	 * @return nothing;
	 * @access public
	 */
	function sharpen($filename, $intensity = 5)
	{
		$intensity = 1;
		$this->checkFolder($filename, 'write', 'sharpen');
		$this->validateFile($filename, 'sharpen');
		if ($this->hasError()) {
			return;
		}
		
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'sharpen_' . $lib;
			if ($rez = $this->$lib($filename, $intensity)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_SHARPEN_NO_LIB', array(), array());
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * current GD library doesn't support sharpen;
	 * @param string $filename to the source file
	 * @param integer $intensity ;
	 * @return nothing;
	 * @access private
	 */
	function sharpen_gd($filename, $intensity = 5)
	{
		return false;
	}
	
	/**
	 * apply an unsharp mask on the image useing image magick;
	 * @param string $filename to the source file
	 * @param integer $intensity ;
	 * @return nothing;
	 * @access private
	 */
	function sharpen_imagemagick($filename, $intensity = 5)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		$arrArguments = array(
							'-sharpen',
							'3x' . $intensity,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);

		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_SHARPEN_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	
	/**
	 * apply an gaussian blur mask on the image;
	 * @param string $filename to the source file
	 * @param integer $intensity intensity of the filter;
	 * @return nothing;
	 * @access public
	 */
	function blur($filename, $intensity = 1)
	{
		$intensity = 1;
		$this->checkFolder($filename, 'write', 'blur');
		$this->validateFile($filename, 'blur');
		if ($this->hasError()) {
			return;
		}
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'blur_' . $lib;
			if ($rez = $this->$lib($filename, $intensity)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_BLUR_NO_LIB', array(), array());
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * apply an gaussian blur mask on the image using GD;
	 * @param string $filename to the source file
	 * @param integer $intensity intensity of the filter;
	 * @return nothing;
	 * @access private
	 */
	function blur_gd($filename, $intensity = 1)
	{
		if (substr(PHP_VERSION, 0, 1) < 5) {
			return false;
		}
		if (!function_exists('imagefilter')) {
			return false;
		}
		
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($filename)) {
			return false;
		}
		
		$srcImage = &$this->getImg($filename);
		ImageFilter($srcImage, IMG_FILTER_GAUSSIAN_BLUR);
		$this->createNewImg($srcImage, $filename, 80);
		return true;
	}
	
	/**
	 * apply an gaussian blur mask on the image useing image magick;
	 * @param string $filename to the source file
	 * @param integer $intensity intensity of the filter;
	 * @return nothing;
	 * @access private
	 */
	function blur_imagemagick($filename, $intensity = 1)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		$arrArguments = array(
							'-blur',
							$intensity,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);

		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_BLUR_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	
	/**
	 * increase or decrease the contrast of an image;
	 * @param string $filename to the source file
	 * @param string $direction  increase or decrease;
	 * @return nothing;
	 * @access public
	 */
	function contrast($filename, $direction)
	{
		$this->checkFolder($filename, 'write', 'contrast');
		$this->validateFile($filename, 'contrast');
		if ($this->hasError()) {
			return;
		}
		
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'contrast_' . $lib;
			if ($rez = $this->$lib($filename, $direction)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_CONTRAST_NO_LIB', array(), array());
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * increase or decrease the contrast of an image usign GD library;
	 * @param string $filename to the source file
	 * @param string $direction  increase or decrease;
	 * @return nothing;
	 * @access private
	 */
	function contrast_gd($filename, $direction)
	{
		if (substr(PHP_VERSION, 0, 1) < 5) {
			return false;
		}
		if (!function_exists('imagefilter')) {
			return false;
		}
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($filename)) {
			return false;
		}
		
		$srcImage = &$this->getImg($filename);
		if (strtolower($direction)=='decrease') {
			$arg = 5;
		} else {
			$arg = -5;
		}
		ImageFilter($srcImage, IMG_FILTER_CONTRAST, $arg);
		$this->createNewImg($srcImage, $filename, 80);
		return true;
	}
	
	/**
	 * increase or decrease the contrast of an image useing image magick;
	 * @param string $filename to the source file
	 * @param string $direction  increase or decrease;
	 * @return nothing;
	 * @access private
	 */
	function contrast_imagemagick($filename, $direction)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		if (strtolower($direction)=='decrease') {
			$arg = '+contrast';
		} else {
			$arg = '-contrast';
		}
		$arrArguments = array(
							$arg,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);
		
		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_CONTRAST_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	
	/**
	 * increase or decrease the brightness of an image;
	 * @param string $filename to the source file
	 * @param string $direction  increase or decrease;
	 * @return nothing;
	 * @access public
	 */
	function brightness($filename, $direction)
	{
		$this->checkFolder($filename, 'write', 'brightness');
		$this->validateFile($filename, 'brightness');
		if ($this->hasError()) {
			return;
		}
		
		foreach ($this->orderLib as $key => $lib) {
			$lib = 'brightness_' . $lib;
			if ($rez = $this->$lib($filename, $direction)) {
				KT_setFilePermissions($filename);
				break;
			}
		}
		
		if ($rez!=true) {
			$this->setError('PHP_IMAGE_BRIGHTNESS_NO_LIB', array(), array());
		} else {
			$this->setError('', array(), array());
		}
	}
	
	/**
	 * increase or decrease the brightness of an image useing GD library;
	 * @param string $filename to the source file
	 * @param string $direction  increase or decrease;
	 * @return nothing;
	 * @access private
	 */
	function brightness_gd($filename, $direction)
	{
		if (substr(PHP_VERSION, 0, 1) < 5) {
			return false;
		}
		if (!function_exists('imagefilter')) {
			return false;
		}
		if (!$this->gdInfo >= 1 || !$this->checkGdFileType($filename)) {
			return false;
		}
		
		$srcImage = &$this->getImg($filename);
		if (strtolower($direction)=='decrease') {
			$arg = -6;
		} else {
			$arg = 6;
		}
		ImageFilter($srcImage, IMG_FILTER_BRIGHTNESS, $arg);
		$this->createNewImg($srcImage, $filename, 80);
		return true;
	}
	
	/**
	 * increase or decrease the brightness of an image useing image magick;
	 * @param string $filename to the source file
	 * @param string $direction  increase or decrease;
	 * @return nothing;
	 * @access private
	 */
	function brightness_imagemagick($filename, $direction)
	{
		if (!$this->checkImageMagik()) {
			return false;	
		}
		$shell = new KT_shell();
		$arrCommands = $this->arrCommands;
		if (strtolower($direction)=='decrease') {
			$arg = '80';
		} else {
			$arg = '120';
		}
		$arrArguments = array(
							'-modulate',
							$arg,
							$filename,
							$filename
							);
		$shell->execute($arrCommands, $arrArguments);
		
		if ($shell->hasError()) {
			$arr = $shell->getError();
			$this->setError('PHP_IMAGE_BRIGHTNESS_ERR', array($arr[0]), array($arr[1]));
		} else {
			$this->imageMagickPath = $shell->getExecutedCommand();
			return true;
		}
	}
	
	
	/**
	 * return an image handle or set an error if not succeded;
	 * @param string $sourceFileName path to the source file;
	 * @return integer image handle to the image if succeded or set error;
	 * @access private
	 */
	function &getImg($sourceFileName)
	{
		$arr = getimagesize($sourceFileName);
		if (is_array($arr)) {
			switch ($arr[2]) {
				case 1:
					$img = imagecreatefromgif($sourceFileName);
					$this->imgType = 'gif';
					return $img;
					break;
				case 2:
					$img = imagecreatefromjpeg($sourceFileName);
					$this->imgType = 'jpg';
					return $img;
					break;
				case 3:
					$img = imagecreatefrompng($sourceFileName);
					$this->imgType = 'png';
					return $img;
					break;
			}
		}
	}
	
	/**
	 * check if file exists;
	 * validate the file; suported format jpg, gif, png;	
	 * @param string $filename path to the source file
	 * @param string $from the right to be checked: read/write:
	 * @return boolean  true if supported filetype/false otherwise;
	 * @access private
	 */
	function validateFile($filename, $from)
	{
		if ($filename == '' || !file_exists($filename)) {
			$this->setError('PHP_IMAGE_NO_IMG_ERR', array($from), array($from, $filename));
			return false;
		}
		
		$arr = @getimagesize($filename);
		$res = false;
		if (is_array($arr)) {
			switch ($arr[2]) {
				case 1:
				case 2:
				case 3:
					$res = true;
					break;
			}
		}
		if (!$res) {
			$this->setError('PHP_IMAGE_INV_IMG', array($from), array($from, $filename));
		}
		return $res;
	}
	
	/**
	 * check if GD support the type of picture;
	 * @param string $sourceFileName  path to the source file;
	 * @return boolean true if GD support the type of picture false if not;
	 * @access private
	 */
	function checkGdFileType($filename)
	{
		$this->gdNoSupport = '';
		$arr = @getimagesize($filename);
		$res = false;
		if (is_array($arr)) {
			switch ($arr[2]) {
				case 1:
					$this->gdNoSupport = 'GIF';
					if (function_exists('imagecreatefromgif') && function_exists('imagegif')) {
						$res = true;
					}
					break;
				case 2:
					$this->gdNoSupport = 'JPG';
					if (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
						$res = true;
					}
					break;
				case 3:
					$this->gdNoSupport = 'PNG';
					if (function_exists('imagecreatefrompng') && function_exists('imagepng')) {
						$res = true;
					}
					break;
			}
		}
		return $res;
	}
	
	/**
	 * return the a string if it GD has support for the current picture type;
	 * @return string ;
	 * @access private;
	 */	
	function getGdNoSupport()
	{
		if ($this->gdNoSupport != '') {
			return KT_getResource('PHP_IMAGE_GD_SUPPORT', 'Image', array($this->gdNoSupport));
			$this->gdNoSupport = '';
		} else {
			return '';
		}
	}
		
	/**
	 * wrapper for imagecreatetruecolor/imagecreate;
	 * @param integer $destWidth widh of the file
	 * @param integer $destHeight  height of the  file;
	 * @return integer image handle;
	 * @access private
	 */
	function &getImageCreate($destWidth, $destHeight)
	{
		if (function_exists('imagecreatetruecolor') && $this->gdInfo>=2) {
			$image = @imagecreatetruecolor($destWidth, $destHeight); 
		} else {
			$image = @imagecreate($destWidth, $destHeight); 
		}
		return $image; 
	}
	
	/**
	 * 	wrapper for ImageCopyResampled/ImageCopyResized;
	 * @param integer $destImage  image handle for destination image;
	 * @param integer $img  image handle of source image;
	 * @param integer $x1 x from top left for destinantion image;
	 * @param integer $y1 y from top left for destinantion image;
	 * @param integer $x2 x from top left for source image;
	 * @param integer $y2 y from top left for source image;
	 * @param integer $destWidth  widh for destination file;
	 * @param integer $destHeight  height for destination file;
	 * @param integer $srcWidth widh for source file;
	 * @param integer $srcHeight height for source file;
	 * @return nothing;
	 * @access private
	 */
	function getImageCopyResampled(&$destImage, &$img, $x1, $y1, $x2, $y2, $destWidth, $destHeight, $srcWidth, $srcHeight)
	{
		if (function_exists('imagecopyresampled') && $this->gdInfo>=2) {
			@ImageCopyResampled($destImage, $img, $x1, $y1, $x2, $y2, $destWidth, $destHeight, $srcWidth, $srcHeight);
		} else {
			@ImageCopyResized($destImage, $img, $x1, $y1, $x2, $y2, $destWidth, $destHeight, $srcWidth, $srcHeight);
		}	
	}
	
	/**
	 * verify the rights on the giving folder;
	 * @param string $img image handle;
	 * @param string $file filename (including path), to save the;
	 * @param integer $qualityLevel quality level (used with jpg pictures);
	 * @return nothing;
	 * @access private
	 */
	function createNewImg(&$img, $file, $qualityLevel='')
	{
		switch ($this->imgType) {
			case 'gif':
				imagegif($img, $file);
				break;
			case 'jpg':
				if ($qualityLevel>0) {
					imagejpeg($img, $file, $qualityLevel);
				} else {
					imagejpeg($img, $file);
				}
				break;
			case 'png':
				imagepng($img, $file);
				break;
		}	
		imagedestroy($img);
	}
	
	/**
	 * return the version of the GD;
	 * @return string return the version of the GD;
	 * @access public
	 */
	function getVersionGd()
	{
		ob_start();
		phpinfo(8);
		$phpinfo = ob_get_contents();
		ob_end_clean();
		$phpinfo = strip_tags($phpinfo);
		$phpinfo = stristr($phpinfo, "gd version");
		$phpinfo = stristr($phpinfo, "version");
		if ($phpinfo === false && function_exists('gd_info')) {
			$phpinfo = gd_info();
			$phpinfo = $phpinfo['GD Version'];
		}
		$end = strpos($phpinfo, ".");
		$phpinfo = substr($phpinfo, 0, $end);
		$length = strlen($phpinfo) - 1;
		$phpinfo = substr($phpinfo, $length);
		$this->gdInfo = $phpinfo;
		return $phpinfo;
	}
	
	/**
	 * check if imagemagik is installed;
	 * @return boolean true if is installed or false if not;
	 * @access private
	 */
	function checkImageMagik() {
		for ($i=0; $i<count($this->arrCommands) ;$i++) {
			if (substr($this->arrCommands[$i], -7) != 'convert') {
				$this->arrCommands[$i] .= 'convert';
			}
		}
		if (!isset($GLOBALS["tNG"]["imagemagick"])) {
			$shell = new KT_shell();
			$arrCommands = $this->arrCommands;
			$arrArguments = array("");
			$output = $shell->execute($arrCommands, $arrArguments);
			
			if ($shell->hasError()) {
				$GLOBALS["tNG"]["imagemagick"] = false;
			} else {
				if ($output!='') {
					$this->imageMagickPath = $shell->getExecutedCommand();
					$GLOBALS["tNG"]["imagemagick"] = true;
				} else {
					$GLOBALS["tNG"]["imagemagick"] = false;	
				}
			}
		}
		return $GLOBALS["tNG"]["imagemagick"];
	}
	
	/**
	 * gets the path of the succesfully executed Image Magick command (if any);
	 * @return string the command path
	 * @access public
	 */
	function getImageMagickPath() {
		return $this->imageMagickPath;
	}
	
	/**
	 * Check if the folder exists and has write permissions.
	 * If the folder does not exists, try to create it.
	 * If the folder does not have write permissions or if could not create it, set error.	
	 * @param string $path the path
	 * @param string $right the right to check
	 * @param string $from  from what function is called
	 * @return boolean true if is installed or false if not;
	 * @access private
	 */
	function checkFolder($path, $right, $from) {
		if (strtolower(substr(PHP_OS, 0, 1))=='w') {
			$path = str_replace('/', '\\', $path);
		}
		if (preg_match("/\./ims",$path)) {
			$arr = split("[/\]", $path);
			array_pop($arr);
			$path = implode(DIRECTORY_SEPARATOR, $arr);
		}
		
		$folder = new KT_folder();
		$folder->createFolder($path);
		if ($right!='') {
			$res = $folder->checkRights($path, $right);
			if ($res !== true) {
				$this->setError('PHP_IMAGE_CHECK_FOLDER_ERROR', array($from), array($from, $path, $right));
			}
		}
		if ($folder->hasError()) {
			$arr = $folder->getError();
			$this->setError('PHP_IMAGE_FOLDER_ERROR', array($from, $arr[0]), array($from, $arr[1]));
		}
	}
	
	function is_readable($file)	{
		$f = @fopen($file, 'rb');
		if (is_resource($f)) {
			fclose($f);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Setter. set error for developper and user.
	 * @var string $errorCode error message code;
	 * @var array $arrArgsUsr  array with optional parameters for sprintf functions;
	 * @var array $arrArgsDev array with optional parameters for sprintf functions.
	 * @return nothing;
	 * @access private
	 */
	function setError($errorCode, $arrArgsUsr, $arrArgsDev)
	{
		$errorCodeDev = $errorCode;
		if ( !in_array($errorCodeDev, array('', '%s')) ) {
			$errorCodeDev .= '_D';
		}
		if ($errorCode!='') {
			$this->errorType[] = KT_getResource($errorCode, 'Image', $arrArgsUsr);
		} else {
			$this->errorType = array();
		}
		if ($errorCodeDev!='') {
			$this->develErrorMessage[] = KT_getResource($errorCodeDev, 'Image', $arrArgsDev);
		} else {
			$this->develErrorMessage = array();
		}
	}
	
	/**
	 * check if an error was setted.
	 * @return boolean true if error is set or false if not;
	 * @access public
	 */
	function hasError()
	{	
		if (count($this->errorType)>0 || count($this->develErrorMessage)>0) {
			return 1;	
		}	
		return 0;
	}
		
	/**
	 * Getter. 	return the errors setted.
	 * @return array  array - 0=>error for user, 1=>error for developer;
	 * @access public
	 */
	function getError()
	{
		return array(implode('<br />', $this->errorType), implode('<br />', $this->develErrorMessage));	
	}

}
?>