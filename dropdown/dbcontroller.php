<?php
class DBController {
	private $host = "sql15.jnb1.host-h.net";
	private $user = "kwdaco_333";
	private $password = "SBbB38c8Qh8";
	private $database = "seavest_db333";
	
	function __construct() {
		$conn = $this->connectDB();
		if(!$conn) {
			echo "Connection Failed";
			
		} else {
			
			return $conn;
		}
	}
	
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
//	function selectDB($conn) {
//		mysql_select_db($this->database,$conn);
//	}
	
	function runQuery($query) {
		$result = mysqli_query($this->connectDB(),$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	function numRows($query) {
		$result  = mysqli_query($this->connectDB(),$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
}
?>
