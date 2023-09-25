<?php
/**
 * KTML4 security class.
 * @access protected
 */
class ktml4_security {
	/**
	 * The error object.
	 * @var KTML4 error object
	 * @access private
	 */
	var $errorObj;
	
	/**
	 * Constructor.
	 * @access public
	 */
	function ktml4_security() {
		$this->errorObj = NULL;
	}	
	
	/**
	 * Validates the current properties.
	 * @param string $id the current KTML4 id
	 * @return KTML4 error object or null
	 * @access public
	 */
	function setGlobalKtmlForId($id) {
		$ret = NULL;
		if (isset($_SESSION['ktml4'][$id])) {
			$tmpArr = $_SESSION['ktml4'][$id]; 
			$tmpArr['id'] = $id;
			$arrModulesMethods = array();
			$arrModulesMethods['file'] = array(
												'folder' => array('delete', 'create', 'read'),
												'file' => array('upload', 'delete')
												);
			$arrModulesMethods['image'] = array(
												'folder' => array('delete', 'create', 'read'),
												'image' => array('getfileinfo'),
												'file' => array('upload', 'delete')
												);
			$tmpArr['modulerights'] = array();
			foreach ($tmpArr['rights'] as $key => $val) {
				if (isset($arrModulesMethods[$val])) {
					$tmpArr['modulerights'] = array_merge_recursive($tmpArr['modulerights'], $arrModulesMethods[$val]);
				}
				if (isset($GLOBALS['KTML4_custom_modules']) && isset($GLOBALS['KTML4_custom_modules'][$val])) {
					$tmpArr['modulerights'] = array_merge_recursive($tmpArr['modulerights'], $GLOBALS['KTML4_custom_modules'][$val]);
				}
			}
			$GLOBALS['ktml4_props'] = $tmpArr;
      
		} else {
			$GLOBALS['ktml4_props'] = null;
			$ret = new ktml4_error('KTML_INVALID_SESSION',array());
		}
		return $ret;
	}
	
	/**
	 * Checks if the called method is allowed.
	 * @param string $module the called module name
	 * @param string $methodName the called method name
	 * @return KTML4 error object or null
	 * @access public
	 */
	function checkPlugin($module, $methodName) {
		if (!isset($GLOBALS['ktml4_props']['modulerights'][$module])) {
			$ret = new ktml4_error('KTML_SECURITY_MODULE_NOTREG',array($module, $GLOBALS['ktml4_props']['id']));
			$this->setError($ret);
			return $ret;
		}
		if ($methodName != '' && !in_array($methodName, $GLOBALS['ktml4_props']['modulerights'][$module])) {
			$ret = new ktml4_error('KTML_SECURITY_METHOD_NOTSUP',array($methodName, $module, $GLOBALS['ktml4_props']['id']));
			$this->setError($ret);
			return $ret;
		}
		return null;
	}
	
	/**
	 * Checks the validity of the entry data (for example, that the file browser is not outside its specified root folder).
	 * @param string $module the called module name
	 * @param string $methodName the called method name
	 * @return KTML4 error object or null
	 * @access public
	 */
	function checkEntryData() {
		
		/*** SUBMODE CHECKS ***/
		$submode = '';
		$submodeMissing = false;
		$submodeCheck = false;
		if (isset($_POST['module']) && ($_POST['module'] == 'file' || $_POST['module'] == 'folder')) {
			if (isset($_POST['submode'])) {
				$submode = strtolower($_POST['submode']);
				$submodeCheck = true;
			} else {
				$submodeMissing = true;
			}
		}
		if (isset($_GET['module']) && $_GET['module'] == 'file' && isset($_GET['method']) &&  $_GET['method'] == 'upload') {
			if (isset($_GET['submode'])) {
				$submode = strtolower($_GET['submode']);
				$submodeCheck = true;
			} else {
				$submodeMissing = true;
			}
		}
		if ($submodeMissing) {
			$ret = new ktml4_error('KTML_SECURITY_FAILED', array('submode'));
			$this->setError($ret);
			return $ret;
		}
		if ($submodeCheck && !in_array($submode, array('file','media'))) {
			$ret = new ktml4_error('KTML_SECURITY_FAILED', array('submode'));
			$this->setError($ret);
			return $ret;
		}

		if (isset($_POST['module']) && $_POST['module'] == 'image') {
			$submode = 'media';
		}
		
		if ($submode != '' && (!isset($GLOBALS['ktml4_props']['properties'][$submode]) || $GLOBALS['ktml4_props']['properties'][$submode]['AllowedModule'] != 'true') ) {
			$ret = new ktml4_error('KTML_SECURITY_FAILED', array('submode'));
			$this->setError($ret);
			return $ret;
		}
		/*** /SUBMODE CHECKS ***/
		
		$folder = null;
		if (isset($_POST['folder'])) {
			$folder = trim($_POST['folder']);
		} elseif (isset($_GET['folder'])) {
			$folder = trim($_GET['folder']);
		}
		if (isset($folder)) {
			$folderName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'] . $folder, true);
			if (!$this->checkFolderName($folderName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('folder'));
				$this->setError($ret);
				return $ret;
			}
			if (!$this->checkFolderDepth($folderName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('folder'));
				$this->setError($ret);
				return $ret;
			}
		}
		
		if (isset($_POST['new_foldername'])) {
			if (isset($folder)) {
				$folderName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'] . $folder, true);
			} else {
				$folderName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'], true);
			}
			$folderName .= KTML4_makeValidFilename(trim($_POST['new_foldername']));
			if (!$this->checkFolderName($folderName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('new_foldername'));
				$this->setError($ret);
				return $ret;
			}
		}
		
		if (isset($_POST['old_foldername'])) {
			if (isset($folder)) {
				$folderName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'] . $folder, true);
			} else {
				$folderName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'], true);
			}
			$folderName .= KTML4_makeValidFilename(trim($_POST['old_foldername']));
			if (!$this->checkFolderName($folderName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('old_foldername'));
				$this->setError($ret);
				return $ret;
			}
		}
		
		if (isset($_POST['new_filefolder'])) {
			$folderName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'] . trim($_POST['new_filefolder']), true);
			if (!$this->checkFolderName($folderName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('new_filefolder'));
				$this->setError($ret);
				return $ret;
			}
		}

		if (isset($_POST['filename'])) {
			$fileName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'] . trim($_POST['filename']), false);
			if (!$this->checkFileName($fileName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('filename'));
				$this->setError($ret);
				return $ret;
			}
			if (!$this->checkFolderDepth($fileName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('filename'));
				$this->setError($ret);
				return $ret;
			}
		}

		
		if (isset($_POST['rel_filename'])) {
			$rel_filename = trim($_POST['rel_filename']);
			$rel_filename = KTML4_makeValidFilename($rel_filename);
			$folderName = '';
			if (isset($_POST['folder'])) {
				$folderName = trim($_POST['folder']);
			}
			$folderName = KT_realpath($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'] . $folderName, true);
			$fileName = $folderName . $rel_filename;
			if (!$this->checkFileName($fileName, $submode)) {
				$ret = new ktml4_error('KTML_SECURITY_FAILED', array('rel_filename'));
				$this->setError($ret);
				return $ret;
			}
		}
		
		return null;
	}

	function checkFolderDepth($folderName, $submode = 'file') {
		$module = trim(strtolower($_POST['module']));
		$method = trim(strtolower($_POST['method']));
		 
		$levelFolder = substr($folderName,strlen($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder']));
		$level = substr_count($levelFolder,'/');
		if ($level > 1) {
			return false;
		} 
		return true;		
	}
	function checkFolderName($folderName, $submode = 'file') {
		if (strpos($folderName,$GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder']) !== 0) {
			return false;
		}
		
		if (count($GLOBALS['ktml4_props']['properties']['filebrowser']['RejectedFolders'])>0) {
			$arr = split("\\/", $folderName);
			foreach ($arr as $val) {
				if (in_array($val, $GLOBALS['ktml4_props']['properties']['filebrowser']['RejectedFolders'])) {
					return false;
				}
			}
		}
		return true;
	}
	
	
	function checkFileName($fileName, $submode = 'file') {
		if (!isset($GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder'])) {
			
			return false;
		}
		if (strpos($fileName, $GLOBALS['ktml4_props']['properties'][$submode]['UploadFolder']) !== 0){
			return false;
		}
		$info = KT_pathinfo($fileName);
		$info['extension'] = strtolower($info['extension']);
		$arr_ext = array();
		
		if (isset($GLOBALS['ktml4_props']['properties'][$submode]['AllowedFileTypes'])) {
			$arr_ext = $GLOBALS['ktml4_props']['properties'][$submode]['AllowedFileTypes'];
		}
		if(isset($GLOBALS['ktml4_props']['properties']['media']['AllowedFileTypes'])) {
			if ($GLOBALS['ktml4_props']['properties']['file']['UploadFolder'] == $GLOBALS['ktml4_props']['properties']['media']['UploadFolder']) {
					$arr_ext = array_merge($GLOBALS['ktml4_props']['properties']['file']['AllowedFileTypes'], $GLOBALS['ktml4_props']['properties']['media']['AllowedFileTypes']);
			} 
		}
		if (!in_array($info['extension'],$arr_ext)) {
			return false;
		}
		if (!$this->checkFolderName($info['dirname'].'/', $submode)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Sets the error object.
	 * @param string $errorObj the error object
	 * @access private
	 */
	function setError($errorObj) {
		$this->errorObj = $errorObj;
	}

	/**
	 * Gets the error object.
	 * @return error object
	 * @access public
	 */
	function getError() {
		return $this->errorObj;
	}
}


?>