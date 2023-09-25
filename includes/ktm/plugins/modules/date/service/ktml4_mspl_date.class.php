<?php

class ktml4_mspl_date {
	var $errorObj;
	
	function ktml4_mspl_date() {
		$this->errorObj = NULL;
	}
	
	function getCurrentDate() {
		$date = date("l, F n, Y");
		return $date;
	}
	
	function setError($errorObj) {
		$this->errorObj = $errorObj;
	}

	function getError() {
		return $this->errorObj;
	}
}
?>