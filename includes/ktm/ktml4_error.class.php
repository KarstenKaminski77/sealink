<?php

/**
 * Error class used in KTML4
 * @access protected
 */
class ktml4_error {
	/**
	 * Error code.
	 * @var string
	 * @access private
	 */
  var $errorCode;
	
	/**
	 * Error message.
	 * @var string
	 * @access private
	 */
  var $errorMessage;
  
	/**
	 * Constructor.
	 * @param string $errorCode error code
	 * @param array $arrArgs arguments for the error
	 * @access public
	 */
  function ktml4_error($errorCode, $arrArgs = array()) {
    $this->errorCode = $errorCode;
    $this->errorMessage = KT_getResource($errorCode, 'KTML4', $arrArgs);
    
  }
}
?>