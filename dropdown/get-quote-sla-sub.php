<?php
session_start();
require_once("../functions/functions.php");
require_once("dbcontroller.php");
$db_handle = new DBController();

$_SESSION['company-id'] = $_POST["company_id"];

	$query ="SELECT * FROM tbl_sla_subcat WHERE CompanyId = '". $_POST["company_id"] ."' AND CatId = '7' ORDER BY SubCat ASC";
	$results = $db_handle->runQuery($query);
?>
	<option value="">Sub Category...</option>
    
<?php
	foreach($results as $sla){
		
		
?>
	<option value="<?php echo $sla["Id"]; ?>"><?php echo $sla["SubCat"]; ?></option>
<?php
	}
?>