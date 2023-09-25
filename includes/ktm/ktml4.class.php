<?php

/**
 * This class contains the KTML4 core.
 * @access public
 */
class ktml4 {
	/**
	 * The form field to which KTML4 is binded.
	 * @var string
	 * @access private
	 */
	var $fieldName;
	
	/**
	 * Associative array storing the properties of the current KTML4 instance.
	 * @var array
	 * @access private
	 */
	var $properties = array();
	
	/**
	 * Associative array storing the properties of the current KTML4 instance 
	 * that will also be outputed in the page.
	 * @var array
	 * @access private
	 */
	var $exportToJs = array();
	
	
	/**
	 * Constructor. Sets the bind form field, relative and absolute path of the page.
	 * @param string $fieldName the form field to which KTML4 is binded
	 * @access public
	 */
	function ktml4($fieldName) {
		$this->fieldName = $fieldName;
	}
	
	/**
	 * Set KTML4 modules properties for the current instance.
	 * @param string $module the module
	 * @param string $propName the property name
	 * @param string $propValue the property value
	 * @param boolean $exportToJs true if the property is also available to JS
	 * @access public
	 */
	function setModuleProperty($module, $propName, $propValue, $exportToJs) {
		$propValue = KT_DynamicData($propValue, null, '', false, array(), false);
		$this->properties[$module][$propName] = $propValue;
		if ($exportToJs) {
			$this->exportToJs[$module][$propName] = $propValue;
		}
	}
	
	/**
	 * Sets an unique ID form the current KTML4 instance, saves the properties in SESSION 
	 * and exports the specified properties in the page
	 * @access public
	 */
	function Execute() {
		if (!isset($_SESSION['ktml4'])) {
			$_SESSION['ktml4'] = array();
		}
		
		if (isset($this->properties['file'])) {
			$this->properties['file']['AllowedModule'] = 'true';
		}
		if (isset($this->properties['media'])) {
			$this->properties['media']['AllowedModule'] = 'true';
		}
		
		$arr = array();
		$arr['name'] = $this->fieldName;
		$arr['rights'] = array();
		if (isset($this->properties['filebrowser']['AllowedModule']) && $this->properties['filebrowser']['AllowedModule'] == 'true') {
			if (isset($this->properties['file']['AllowedModule']) && $this->properties['file']['AllowedModule'] == 'true') {
				$arr['rights'][] = 'file';
			}
			if (isset($this->properties['media']['AllowedModule']) && $this->properties['media']['AllowedModule'] == 'true') {
				$arr['rights'][] = 'image';
			}
		}
		if (isset($GLOBALS['KTML4_custom_modules']) && is_array($GLOBALS['KTML4_custom_modules'])) {
			foreach($GLOBALS['KTML4_custom_modules'] as $module => $methods) {
				if (isset($this->properties[$module]['AllowedModule']) && $this->properties[$module]['AllowedModule'] == 'true') {
					$arr['rights'][] = $module;
				}
			}
		}
		
		$path = KT_getUri();
		$serverName = KT_getServerName();
		$var_name = $this->fieldName . "_config";
		
		foreach ($this->exportToJs as $module => $moduleDetails) {
			echo <<<EOD
		if (typeof {$var_name}['module_props']['{$module}'] == 'undefined') {
			{$var_name}['module_props']['{$module}'] = {};
		}

EOD;
			foreach ($moduleDetails as $propName=>$propValue) {
				if (($module == 'file' || $module == 'media')  && $propName == 'UploadFolderUrl') {
					$propValue = KT_Rel2AbsUrl($path, '', KT_makeIncludedURL($propValue));
					$propValue = substr($propValue, strlen($serverName));
					if ($propValue == '') {
						$propValue = '/';
					}
					if (substr($propValue, -1) != '/') {
						$propValue .= '/';
					}
				}
				if ( ($module == 'css' && $propName == 'PathToStyle') || ($module == 'hyperlink_browser' && $propName == 'ServiceProvider') ) {
					$propValue = KT_Rel2AbsUrl($path, '', KT_makeIncludedURL($propValue));
					$propValue = substr($propValue, strlen($serverName));
				}
				echo <<<EOD
		{$var_name}['module_props']['{$module}']['{$propName}'] = '{$propValue}';

EOD;
			}
		}
		
		$this->properties['filebrowser']['RejectedFolders'] = '';
		foreach ($this->properties as $module => $moduleDetails) {
			foreach ($moduleDetails as $propName => $propValue) {
				if ($module == 'filebrowser' && $propName == 'RejectedFolders') {
					if (trim($propValue) != '') {
						$propValue = explode(',', $propValue);
					} else {
						$propValue = array();
					}
					$propValue[] = '.tidy';
					$propValue[] = '.aspell';
					$propValue[] = str_replace('/', '', KTML4_THUMBNAIL_FOLDER);
				}
				if (($module == 'file' || $module == 'media') && $propName == 'AllowedFileTypes') {
					if (trim($propValue) != '') {
						$propValue = explode(',', $propValue);
					} else {
						$propValue = array();
					}
					$propValue = array_map('trim', $propValue);
				}
				if ($propName == 'UploadFolder') {
					$propValue = KT_RealPath($propValue,true);
				}
				$this->properties[$module][$propName] = $propValue;
			}
		}
		$arr['properties'] = $this->properties;
		
		$id = md5(serialize($arr));
		$_SESSION['ktml4'][$id] = $arr;
		echo "\t\tvar ".$this->fieldName."_id = '$id';\n";
		
		KTML4_prepareGlobals();
		echo "\t\tvar \$KTML4_GLOBALS = {};\n";
		if (isset($GLOBALS['KTML4_GLOBALS'])) {
			foreach ($GLOBALS['KTML4_GLOBALS'] as $key=>$value) {
				echo "\t\t\$KTML4_GLOBALS['" . $key . "'] = '" . KT_escapeJs($value). "';\n";
			}
		}
	}
	
}

?>