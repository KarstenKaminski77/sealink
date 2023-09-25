<?php

require_once('functions/functions.php');

select_db();

$quoteno = $_GET['Id'];
$type = $_POST['approval'];

mysql_query("UPDATE tbl_qs SET Status = '3', Type = '$type' WHERE QuoteNo = '$quoteno'")or die(mysql_error());

?>
<html>
<body onLoad="window.close();">
</body>
</html>
