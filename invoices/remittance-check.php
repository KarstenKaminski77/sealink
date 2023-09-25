<?php
session_start();

require_once('../functions/functions.php'); 

$final = 0;

$query = array(
	array('12130','1008.90'), 
	array('12146','2276.58'), 
	array('12040','4860.96'), 
	array('12132','4982.94'),
	array('12133','2622.00'), 
	array('12064','4936.20'), 
	array('12101','951.90'), 
	array('12086','1863.90'), 
	array('12081','7047.48'), 
	array('12100','855.00'), 
	array('12102','1008.90'), 
	array('12153','2303.37'), 
	array('12074','900.57'), 
	array('12092','3503.79'), 
	array('11842','1288.20'), 
	array('11742','6659.88'), 
	array('12019','3014.45'), 
	array('12026','1615.38'), 
	array('12094','4344.54'), 
	array('12152','1594.86'), 
	array('12147','1526.46'), 
	array('12165','2179.68'), 
	array('12120','2562.72'), 
	array('12137','1018.02'), 
	array('12125','5476.51'), 
	array('12164','1959.66'), 
	array('12111','1057.35'), 
	array('12090','3483.84'), 
	array('11557','29908.17'), 
	array('12072','3382.38'), 
	array('12099','2533.08'), 
	array('12126','2937.78'), 
	array('12129','1920.90'), 
	array('12134','3838.38'), 
	array('12113','3080.28'), 
	array('12135','4436.88'), 
	array('12112','1133.16'), 
	array('12060','2718.90'), 
	array('12163','1436.40'), 
	array('12162','2052.00'), 
	array('12080','583.68'), 
	array('12150','30091.76'), 
	array('12091','1971.06'), 
	array('12093','1028.28'), 
	array('11763','15045.72'), 
	array('12119','3472.44'), 
	array('12076','1586.88'),
	array('12063','4208.88'), 
	array('12103','4607.88'), 
	array('12104','5214.36'), 
	array('11758','5300.43'), 
	array('11995','5543.82'), 
	array('11626','23140.72'), 
	array('12078','1053.36')
);

for($i=0;$i<count($query);$i++){
	
	$invno = $query[$i][0];
	$total = $query[$i][1];
	
	$final += $total;
	
	$sql = mysqli_query($con, "SELECT * FROM tbl_jc WHERE InvoiceNo = '$invno'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($sql);
	
	if($row['Total2'] == $total){
		
		echo $i .' '. $invno .' '. $total .' Correct<br>';
		
	} else {
		
		echo $i .' '. $invno .' '. $total .' <b>WRONG</b><br>';
	}
}

echo '<br><br><b>'. $final .'</b>';
	
	
