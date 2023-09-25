<?php
require_once('functions/functions.php');

select_db();

$quoteno = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_reports WHERE QuoteNo = '$quoteno'")or die(mysql_error());
$numrows = mysql_num_rows($query);

if($numrows == 0){

mysql_query("INSERT INTO tbl_reports (QuoteNo) VALUES ('$quoteno')")or die(mysql_error());

header('Location: report.php?Id='. $quoteno .'');

} else {

header('Location: report.php?Id='. $quoteno .'');

}
?>