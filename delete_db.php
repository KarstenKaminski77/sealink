<?php
require_once('functions/functions.php');

select_db();

mysql_query("DELETE FROM tbl_sites WHERE Company = '3' AND Id >= '1135'")or die(mysql_error());

?>