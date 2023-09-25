<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();

$_SESSION['company-id'] = $_POST["company_id"];
	
	$query ="SELECT * FROM tbl_sites WHERE Company = '" . $_POST["company_id"] . "' AND AreaId = '". $_SESSION['areaid'] ."' ORDER BY Name ASC";
	$results = $db_handle->runQuery($query);
?>
	<option value="">Site...</option>
<?php
	foreach($results as $site){
		
		$selected = '';
		
		if($site['Id'] == $_POST['site']){
			
			$selected = 'selected="selected"';
		}
?>
	<option value="<?php echo $site["Id"]; ?>" <?php echo $selected; ?>><?php echo $site["Name"]; ?></option>
<?php
	}
?>