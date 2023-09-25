<?php

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$quote = array('5923');

for($i=0;$i<count($quote);$i++){
	
	mysqli_query($con, "UPDATE tbl_qs SET Status = '2' WHERE QuoteNo = '". $quote[$i] ."'")or die(mysqli_error($con));
}
?>