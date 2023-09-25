<?php

/**
 * KTML4 image module.
 * @access protected
 */
class ktml4_mspl_image {
	/**
	 * The error object.
	 * @var KTML4 error object
	 * @access private
	 */
	var $errorObj;
	
	/**
	 * Absolute path of the root folder for image operations.
	 * @var KTML4 error object
	 * @access private
	 */
	var $folderName;
	
	/**
	 * Constructor.
	 * @access public
	 */
	function ktml4_mspl_image() {
		$this->errorObj = NULL;
		$this->folderName = KT_RealPath($GLOBALS['ktml4_props']['properties']['media']['UploadFolder'], true);
		KTML4_checkFolder($this->folderName);
	}
	
	/**
	 * Retrieve image informations.
	 * @return array image informations (width, height, size)
	 * @access public
	 */
	function getfileinfo() {
		if (!isset($_POST['rel_filename'])) {
			$ret = new ktml4_error('KTML_ARGUMENT_NOT_SET', array('IMAGE','rel_filename'));
			$this->setError($ret);
			return $ret;
		}
		$rel_filename = $_POST['rel_filename'];			
		$rel_filename = KTML4_makeValidFilename($rel_filename);
		
		if (!isset($_POST['folder'])) {
			$ret = new ktml4_error('KTML_ARGUMENT_NOT_SET', array('IMAGE','folder'));
			$this->setError($ret);
			return $ret;
		}
		$folder = KT_RealPath($this->folderName . $_POST['folder'], true);
		
		$arr = @getimagesize($folder . $rel_filename);
		if ($arr === false) {
			$ret = new ktml4_error('KTML_IMAGE_ERROR', array('Error reading image properties.'));
			$this->setError($ret);
			return $ret;
		}
		$arr = array("size"=>filesize($folder . $rel_filename), "width"=>$arr[0], "height"=>$arr[1]);
		return $arr;
	}

	/**
	 * Set the error object.
	 * @param string $errorObj the error object
	 * @access private
	 */
	function setError($errorObj) {
		$this->errorObj = $errorObj;
	}
	
	/**
	 * Get the error object.
	 * @return error object
	 * @access public
	 */
	function getError() {
		return $this->errorObj;
	}
}

?>