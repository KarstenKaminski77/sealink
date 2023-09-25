<?php
session_start();
require_once("../functions/functions.php");
require_once("dbcontroller.php");
$db_handle = new DBController();

$catid = $_POST['sla_id'];
$companyid = $_POST["company_id"];

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '$companyid' AND CatId = '$catid' ORDER BY SubCat ASC")or die(mysqli_error($con));
$_SESSION['sla_rows'] = mysqli_num_rows($query_sla_sub_cat);



	$query ="SELECT * FROM tbl_sla_subcat WHERE CompanyId = '". $_SESSION['company-id'] ."' AND CatId = '". $_POST['sla_id'] ."' ORDER BY SubCat ASC";
	$results = $db_handle->runQuery($query);
?>
	<option value="">Type</option>
    <option value="0">None</option>

<?php
	foreach($results as $sla){

		$selected = '';

		if($sla['Id'] == $_POST['site']){

			$selected = 'selected="selected"';
		}
?>
	<option value="<?php echo $sla["Id"]; ?>" <?php echo $selected; ?>><?php echo $sla["SubCat"]; ?></option>
<?php
	}
?>
