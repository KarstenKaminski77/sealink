<?php

require_once('functions/functions.php');

select_db();

     //mysql_query("DELETE FROM tbl_sites WHERE Company = '14'")or die(mysql_error());

     $filename = 'bp-gp.csv';
     $handle = fopen("$filename", "r");
	 $x = 0;
     while (($data = fgetcsv($handle, 305, ",")) !== FALSE)
     {
		 
		 $x++;
		 
		 echo $x.'<br>';
    
	       $company = '14';
		   $area = '3';
	       $name = addslashes($data[0]);
		   
		   $address = addslashes($data[1]);
		   $address .= "<br>" . addslashes($data[2]);
		   $address .= "<br>" . addslashes($data[3]);
		   $address .= "<br>" . addslashes($data[4]);
	       
	       $import="INSERT into tbl_sites(Company,AreaId,Name,Address) VALUES('$company','$area','$name','$address')";
           mysql_query($import) or die(mysql_error());
     }
     fclose($handle);
?>