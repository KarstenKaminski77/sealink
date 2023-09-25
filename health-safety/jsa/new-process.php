<?php 
require_once('../../functions/functions.php'); 

select_db();

$date = date('Y-m-d');
$company = $_POST['company'];
$site = $_POST['site'];
$ref = $_POST['ref'];
$jsa = $_POST['jsa'];
$work = $_POST['work'];

// Create New JSA Form

mysql_query("INSERT INTO tbl_jsa (Date,CompanyId,SiteId,Reference,JSAType,WorkActivity) VALUES ('$date','$company','$site','$ref','$jsa','$work')")or die(mysql_error());

// Get The Newly Created JSA Id

$query = mysql_query("SELECT * FROM tbl_jsa ORDER BY Id DESC LIMIT 1")or die(mysql_error());
$row = mysql_fetch_array($query);

// Loop Through And Insert The JSA Id And PPE Id's Into The Relational Table

$query2 = mysql_query("SELECT * FROM tbl_ppe_list")or die(mysql_error());
while($row2 = mysql_fetch_array($query2)){
	
	$ppe = $row2['Id'];
    $id = $row['Id'];
	
	mysql_query("INSERT INTO tbl_ppe_relation (JSAId,PPEId) VALUES ('$id','$ppe')")or die(mysql_error());
	
}

	mysql_query("INSERT INTO tbl_job_steps (JSAId) VALUES ('$id')")or die(mysql_error());

header('Location: jsa.php?Id='. $id);

?>