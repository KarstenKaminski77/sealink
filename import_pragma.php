<?php

require_once('functions/functions.php');

select_db();

     $filename = 'GautengPragma.csv';
     $handle = fopen("$filename", "r");
     while (($data = fgetcsv($handle, 305, ",")) !== FALSE)
     {
    
	       $company = '2';
		   $area = '3';
	       $name = addslashes($data[0]);
	       $firstname = addslashes($data[1]);
		   $telephone = addslashes($data[2]);
	       $cell = addslashes($data[3]);
	       $address = addslashes($data[4]);
	       
	       $custodian = addslashes($data[7]);
	       $import="INSERT into tbl_sites(Company,AreaId,Name,FirstName,Telephone,Cell,Address) VALUES('$company','$area','$name','$firstname','$telephone','$cell','$address')";
           mysql_query($import) or die(mysql_error());
     }
     fclose($handle);
?>