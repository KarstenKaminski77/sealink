<?php

require_once('functions/functions.php');

select_db();

     $filename = 'CALTEXRETAIL5.csv';
     $handle = fopen("$filename", "r");
     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
     {
    
	       $company = '4';
		   $site = addslashes($data[0]);
	       $name = addslashes($data[1]);
	       $cat = addslashes($data[2]);
	       $newnwm = addslashes($data[3]);
	       $firstname = addslashes($data[4]);
	       $lastname = addslashes($data[5]);
	       $address = addslashes($data[6]);
	       $suburb = addslashes($data[7]);
	       $telephone = addslashes($data[8]);
	       $fax = addslashes($data[9]);
	       $cell = addslashes($data[10]);
		   $other = addslashes($data[11]);
	       $email = addslashes($data[12]);
	       $shoptype = addslashes($data[13]);
	       $import="INSERT into tbl_sites(Company,Site,Name,Cat06,NewNwM,FirstName,LastName,Address,Suburb,Telephone,Fax,Cell,Other,Email,ShopType) values('$company','$site','$name','$cat','$newnwm','$firstname','$lastname','$address','$suburb','$telephone','$fax','$cell','$other','$email','$shoptype')";
       mysql_query($import) or die(mysql_error());
     }
     fclose($handle);
?>