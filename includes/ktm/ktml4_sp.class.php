<?php

/**
 * KTML4 service provider class.
 * @access protected
 */
class ktml4_sp {
	/**
	 * The error object.
	 * @var KTML4 error object
	 * @access private
	 */
	var $errorObj;
	
	/**
	 * Service provider's response encoding.
	 * @var string
	 * @access private
	 */
	var $outEncoding;
	
	/**
	 * Constructor.
	 * @access public
	 */
	function ktml4_sp() {
		$this->errorObj = NULL;
		$this->outEncoding = '';
		KTML4_prepareGlobals();
	}
	
	/**
	 * Checks and executes a service.
	 * @return string service's response
	 * @access public
	 */
	function execute() {
		$security_ok = true;
		//flash issue with fake request
		if (isset($_GET['module']) && isset($_GET['method'])) {
			if ($_GET['module'] == 'file' && $_GET['method'] == 'upload') {
				if (count($_POST) == 0 && count($_FILES) == 0) {
					return;
				}
			}
		}
		
		$this->checkSecurity();
		$ret = $this->getError();
		if ($ret === null) {
			$response = $this->executeModule();
		} else {
			$security_ok = false;
		}
		$ret = $this->getError();
		$has_error = false;
		if ($ret !== null) {
			$response = array(
				'error' => array(
					'code' => $ret->errorCode, 
					'message' => KTML4_prepareErrorMsg($ret->errorMessage)
				)
			);
			$has_error = true;
		}
		
		if ($security_ok) {
			if (isset($_GET['savetosession']) && $_GET['savetosession'] == 'true') {
				if (!isset($_SESSION['ktml4'][$GLOBALS['ktml4_props']['id']]['status'])) {
					$_SESSION['ktml4'][$GLOBALS['ktml4_props']['id']]['status'] == array();
				}
				$_SESSION['ktml4'][$GLOBALS['ktml4_props']['id']]['status'][] = $response;
			}
		}
		
		if (!isset($_POST['RawRequest'])) {
			$response = $this->serializeJs($response);
		} else {
			$errorMarker = '';
			if ($has_error) {
				$response = $this->serializeJs($response);
				$errorMarker = ' id="error"';
			}
			$response = <<<EOD
<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$this->outEncoding}">
</head>
<body>
	<textarea$errorMarker>{$response}</textarea>
</body>
</html>
EOD;
		}
		if ($this->outEncoding != '') {
			header('Content-Type:text/html; charset=' . $this->outEncoding);
		}
		return $response;
	}
	
	/**
	 * Performs a security check on the service (if the method is allowed, if the entry data is valid, etc.).
	 * @return KTML4 error object or null
	 * @access private
	 */
	function checkSecurity() {
		$security = new ktml4_security();
		// Set $_GLOBALS['KTML'] = $_SESSION['KTML'][$id], if it exists
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		} elseif(isset($_GET['id'])) {
			$id = $_GET['id'];
		} else {
			$id = null;
		}
		
		$ret = $security->setGlobalKtmlForId($id);
		if ($ret != null) {
			$this->setError($ret);
			return $ret;	
		}
		
		// Check if $_GLOBALS['KTML'] may execute module(method)
		if (isset($_POST['module'])) {
			$module = $_POST['module'];
		} elseif(isset($_GET['module'])) {
			$module = $_GET['module'];
		} else {
			$module = null;
		}
		if (isset($_POST['method'])) {
			$method = $_POST['method'];
		} elseif (isset($_GET['method'])) {
			$method = $_GET['method'];
		} else {
			$method = null;
		}
		
		$ret = $security->checkPlugin($module, $method);
		if ($ret != null) {
			$this->setError($ret);
			return $ret;
		}
		// Check the entry data for the current $_GLOBALS['KTML']
		$ret = $security->checkEntryData(); 
		if ($ret != null) {
			$this->setError($ret);
			return $ret;
		}
		
		return null;
	}
	
	/**
	 * Loads and executes the service's method.
	 * @return string service's response
	 * @access private
	 */
	function executeModule() {
		$module = '';
		if (isset($_POST['module'])) {
			$module = $_POST['module'];
		} else {
			$module = $_GET['module'];
		}
		$file = dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'ktml4_'.$module.'.php';
		$class = 'ktml4_mspl_'. $module;
		
		if (!isset($_POST['module'])) {
			if (!isset($_GET['module'])) {
				$ret = new ktml4_error('KTML_SP_NO_MODULE');
				$this->setError($ret);
				return $ret;
			}
		}
		if (!file_exists($file)) {
			$ret = new ktml4_error('KTML_SP_NO_MODULE_FILE', array($file));
			$this->setError($ret);
			return $ret;
		}
		require_once($file);
		if (!class_exists($class)) {
			$ret = new ktml4_error('KTML_SP_NO_MODULE_CLASS', array($class, $module));
			$this->setError($ret);
			return $ret;
		}
		if (!isset($_POST['method'])) {
			if (!isset($_GET['method'])) {
				$ret = new ktml4_error('KTML_SP_NO_METHOD');
				$this->setError($ret);
				return $ret;
			} else {
				$method = $_GET['method'];
			}
		} else {
			$method = $_POST['method'];
		}
		$mspl = new $class();
		if (!method_exists($mspl, $method)) {
			$ret = new ktml4_error('KTML_SP_NO_METHOD_IN_MODULE', array($method, $class, $module));
			$this->setError($ret);
			return $ret;
		}
		$response = $mspl->$method();
		// get the encoding, if the response must specify it
		if (
			($module == 'xhtml' && $method == 'xhtml') ||
			($module == 'spellcheck' && $method == 'spellcheck') ||
			($module == 'templates' && $method == 'read') ||
			($module == 'folder' && $method == 'read')
		) {
			if (!method_exists($mspl, 'getOutputEncoding')) {
				$ret = new ktml4_error('KTML_SP_NO_METHOD_IN_MODULE', array('getOutputEncoding', $class, $module));
				$this->setError($ret);
				return $ret;
			}
			$this->outEncoding = trim($mspl->getOutputEncoding());
		}
		$ret = $mspl->getError();
		if ($ret != null) {
			$this->setError($ret);
			return $ret;
		}
		return $response;
	}
	
	/**
	 * Serialize a simple or complex value to JavaScript
	 * @param array $arr value to be serialized
	 * @return string the serialized value
	 * @access private
	 */
	function serializeJs($arr) {
		$ret = '';
		if (is_array($arr)) {
			if (isset($arr[0])) {
				// is array
				$ret = '[';
				$i = 0;
				foreach ($arr as $key => $value) {
					if ($i != 0) {
						$ret .= ', ';
					}
					$ret .= $this->serializeJs($value);
					$i++;
				}
				$ret .= ']';
			} else {
				// is object
				$ret = '{';
				$i = 0;
				foreach ($arr as $key => $value) {
					if ($i != 0) {
						$ret .= ', ';
					}
					$ret .= "'" . KT_escapeJS($key) . "': " . $this->serializeJs($value);
					$i++;
				}
				$ret .= '}';
			}
		} else {
			// is value
			$ret = "'" . KT_escapeJS($arr) . "'";
		}
		return $ret;
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