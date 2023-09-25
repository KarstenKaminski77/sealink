<?php
/*
	Copyright (c) InterAKT Online 2000-2005
*/

/** 
* Provides functionalities for deleting files.
* @access public
*/
class tNG_FileDelete {
	/**
	 * The tNG object
	 * @var object tNG
	 * @access public
	 */
	var $tNG;
	/**
	 * name of the field from database wich helds the file name
	 * @var string 
	 * @access public
	 */
	var $dbFieldName = '';
	/**
	 * folder name
	 * @var string
	 * @access public
	 */
	var $folder = '';
	/**
	 * if it is used rename
	 * @var boolean
	 * @access public
	 */
	var $rename = false;
	/**
	 * the rename rule
	 * @var string
	 * @access public
	 */
	var $renameRule = '';
	
	/**
	 * Constructor. Sets the reference to transaction.
	 * @param object tNG 
	 * @access public
	 */
	function tNG_FileDelete(&$tNG) {
		$this->tNG = &$tNG;
	}
	
	/**
	 * setter. set the db field name
	 * @var string
	 * @access public
	 */
	function setDbFieldName($dbFieldName) {
		$this->dbFieldName = $dbFieldName;
	}
	/**
	 * setter. set the folder name
	 * @var object tNG
	 * @access public
	 */
	function setFolder($folder) {
		$this->folder = $folder;
	}
	/**
	 * setter. set the rename to true and the renamarule;
	 * @var object tNG
	 * @access public
	 */
	function setRenameRule($renameRule) {
		$this->rename = true;
		$this->renameRule = $renameRule;
	}
	/**
	 * delete the tumbnails if exists
	 * @var string folder name
	 * @var string name of the file
	 * @return nothing
	 * @access public
	 */
	function deleteThumbnails($folder, $oldName) {
		if ($oldName != '') {
			$path_info = KT_pathinfo($oldName);
			$regexp = '/'.preg_quote($path_info['filename'],'/').'_\d+x\d+';
			if ($path_info['extension'] != "") {
				$regexp	.= '\.'.preg_quote($path_info['extension'],'/');
			}
			$regexp	.= '/';
			
			$folderObj = new KT_folder();
			$entry = $folderObj->readFolder($folder, false); 
			
			if (!$folderObj->hasError()) {
				foreach($entry['files'] as $key => $fDetail) {
					if (preg_match($regexp,$fDetail['name'])) {
						@unlink($folder.$fDetail['name']);
					}
				}  
			}
		}
	}
	/**
	 * the main method, execute the code of the class
	 * return mix null or error object
	 * @access public
	 */
	function Execute() {
		$ret = NULL;
		$folder = KT_realpath(KT_DynamicData($this->folder,$this->tNG,'',true));
		if ($this->rename == false && $this->dbFieldName != '') {
			$fileName = $this->tNG->getSavedValue($this->dbFieldName);
		} else {
			$fileName = KT_DynamicData($this->renameRule , $this->tNG,'', true);
		}
		if ($fileName != "") {
			$fullFileName = $folder . $fileName;
			if (file_exists($fullFileName)) {
				$delRet = @unlink($fullFileName);
				if ($delRet !== true) {
					$ret = new tNG_error('FILE_DEL_ERROR', array(), array($fullFileName));
					$ret->setFieldError($this->fieldName, 'FILE_DEL_ERROR_D', array($fullFileName));
				} else {
					$this->deleteThumbnails($folder.'thumbnails'.DIRECTORY_SEPARATOR, $fileName);
				}
			}
		}
		return $ret;
	}
}
?>