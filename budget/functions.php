<?php
function select_db(){
mysql_connect("sql1.jnb1.host-h.net","budget","budget001") or die(mysql_error());
mysql_select_db("budget") or die(mysql_error());
}
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