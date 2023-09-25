<?php

require_once('functions/functions.php');

select_db();

     $filename = 'GautengPragma.csv';
     $handle = fopen("$filename", "r");
     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
     {
    
	       $company = '1';
		   $area = '3';
	       $name = addslashes($data[0]);
	       $firstname = addslashes($data[1]);
		   $telephone = addslashes($data[2]);
	       $fax = addslashes($data[3]);
	       $cell = addslashes($data[4]);
	       $address = addslashes($data[5]);
	       
	       $custodian = addslashes($data[7]);
	       $import="INSERT into tbl_sites(Company,AreaId,Name,FirstName,Address,Telephone,Fax,Cell,Custodian) values('$company','$area','$name','$firstname','$address','$telephone','$fax','$cell','$custodian')";
           mysql_query($import) or die(mysql_error());
     }
     fclose($handle);
?>