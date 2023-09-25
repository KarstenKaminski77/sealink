<?php

/**
 * KTML4 file module.
 * @access protected
 */
class ktml4_mspl_file {
	/**
	 * The error object.
	 * @var KTML4 error object
	 * @access private
	 */
	var $errorObj;
	
	/**
	 * Absolute path of the root folder for file operations.
	 * @var string
	 * @access private
	 */
	var $folderName;
	
	/**
	 * Submode in which the module runs.
	 * @var string
	 * @access private
	 */
	var $submode;
	
	/**
	 * Constructor.
	 * @access public
	 */
	function ktml4_mspl_file() {
		$this->errorObj = NULL;
		if (isset($_POST['submode'])) {
			$this->submode = strtolower($_POST['submode']);
		} else {
			$this->submode = strtolower($_GET['submode']);
		}
		$this->folderName = KT_RealPath($GLOBALS['ktml4_props']['properties'][$this->submode]['UploadFolder'], true);
		KTML4_checkFolder($this->folderName);
	}
	
	/**
	 * Upload a file.
	 * @return KTML4 error or the path of the saved file
	 * @access public
	 */
	function upload() {
		if (!isset($_FILES['Filedata'])) {
			$ret = new ktml4_error('KTML_ARGUMENT_NOT_SET', array('FILE','Filedata'));
			$this->setError($ret);
			return $ret;
		}
		
		if (!isset($_POST['folder'])) {
			if (isset($_GET['folder'])) {
				$folder = urldecode($_GET['folder']);
			} else {
				$ret = new ktml4_error('KTML_ARGUMENT_NOT_SET', array('FILE','folder'));
				$this->setError($ret);
				return $ret;
			}
		} else {
			$folder = $_POST['folder'];
		}
		$postFolder = str_replace('//', '/', $folder);
		$uploadfolder = KT_RealPath($this->folderName . $postFolder, true);
		if (substr($postFolder, 0, 1) == '/') {
			$uploadfolder = KT_RealPath($this->folderName . substr($postFolder, 1), true);
		}

		$fileName = $_FILES['Filedata']['name'];
		$extensions = $GLOBALS['ktml4_props']['properties'][$this->submode]['AllowedFileTypes'];
		if (isset($GLOBALS['ktml4_props']['properties']['media'])) {
			if ($GLOBALS['ktml4_props']['properties']['file']['UploadFolder'] == $GLOBALS['ktml4_props']['properties']['media']['UploadFolder']) {
				$extensions = array_merge($GLOBALS['ktml4_props']['properties']['file']['AllowedFileTypes'], $GLOBALS['ktml4_props']['properties']['media']['AllowedFileTypes']);
			}
		} 
		
		$fileUpload = new KT_fileUpload();
		$fileUpload->setFileInfo('Filedata');
		$fileUpload->setFolder($uploadfolder);
		$fileUpload->setRequired(true);
		$fileUpload->setAllowedExtensions($extensions);
		$fileUpload->setAutoRename(true);
		$fileUpload->setMinSize(0);
		$fileUpload->setMaxSize($GLOBALS['ktml4_props']['properties']['filebrowser']['MaxFileSize']);
		$file_name = $fileUpload->uploadFile($fileName, null);
		if ($fileUpload->hasError()) {
			$arr = $fileUpload->getError();
			$ret = new ktml4_error('KTML_FILEUPLOAD_ERROR', array($fileName, $arr[1]));
			$this->setError($ret);
			return $ret;
		}
		return $folder . $file_name;
	}
	
	/**
	 * Delete a file.
	 * @return KTML4 error or the path of the deleted file
	 * @access public
	 */
	function delete() {
		if (!isset($_POST['filename'])) {
			$ret = new ktml4_error('KTML_ARGUMENT_NOT_SET', array('FILE','filename'));
			$this->setError($ret);
			return $ret;
		}
		$fileName = KT_RealPath($this->folderName.$_POST['filename'],false);
		
		$file = new KT_file();
		$file->deleteFile($fileName); 
		if ($file->hasError()) {
			$arr = $file->getError();
			$ret = new ktml4_error('KTML_FILE_ERROR', array($arr[1]));
			$this->setError($ret);
			return $ret;
		}
		$dir_fileName = dirname($fileName).'/';
		$uploadFolder = substr($dir_fileName,strlen($this->folderName));
		$name_fileName = basename($fileName);
		KTML4_deleteThumbnails($dir_fileName, $name_fileName);
		return $uploadFolder . $name_fileName;
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