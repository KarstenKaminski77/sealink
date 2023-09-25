<?php

require_once('../functions/functions.php');

select_db();

     $filename = 'root-cause.csv';
     $handle = fopen("$filename", "r");
     while (($data = fgetcsv($handle, 305, ",")) !== FALSE)
     {
    
	       $code = addslashes($data[0]);
	       $description = addslashes($data[1]);
		   $compcode = addslashes($data[2]);
	       $compdescription = addslashes($data[3]);
	       $code2 = addslashes($data[4]);
	       $description2 = addslashes($data[5]);
	       
	       $custodian = addslashes($data[7]);
	       $import="INSERT into tbl_pragma_root_cause(Code,Description) VALUES('$code','$description')";
           mysql_query($import) or die(mysql_error());
     }
     fclose($handle);
?>