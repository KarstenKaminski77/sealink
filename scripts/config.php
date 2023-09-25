<?php
	$connectdb = mysql_connect('localhost','root','') or die('Cannot connect to database');
	$selectdb = mysql_select_db('demos') or die('Cannot Select database');
?>