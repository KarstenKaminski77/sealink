<?php require_once('Connections/seavest.php'); ?>
<?php
session_start();

$_SESSION['location'] = "equipment_r";

require_once('functions/functions.php');

$areaid = $_SESSION['areaid'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT * FROM tbl_technicians WHERE AreaId = '$areaid'";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$id = $row_Recordset3['AreaId'];

header('Location: tools.php?Id='.$id.'');
?>
<?php
mysql_free_result($Recordset3);
?>
