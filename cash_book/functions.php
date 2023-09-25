<?php
function colour_bank(){
$query = mysql_query("SELECT SUM(Bank) FROM tbl_budget") or die(mysql_error());
$row = mysql_fetch_array($query);
$bank = $row['SUM(Bank)'];
if($bank >= 0){
echo "#009933";
} else {
echo "#FF0000";
}}
function colour_cash(){
$query = mysql_query("SELECT SUM(Cash) FROM tbl_budget") or die(mysql_error());
$row = mysql_fetch_array($query);
$bank = $row['SUM(Cash)'];
if($bank >= 0){
echo "#009933";
} else {
echo "#FF0000";
}}
?>