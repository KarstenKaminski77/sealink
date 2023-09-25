<?php

require_once('functions/functions.php');

select_db();

     $filename = 'caltex-jhb.csv';
     $handle = fopen("$filename", "r");
     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
     {
    
	       $company = '3';
		   $area = '3';
	       $name = addslashes($data[0]);
	       $address = addslashes($data[1]);
	       $suburb = addslashes($data[2]);
	       $import="INSERT into tbl_sites(AreaId,Company,Name,Address,Suburb) VALUES('$area','$company','$name','$address','$suburb')";
       mysql_query($import) or die(mysql_error());
     }
     fclose($handle);
?>