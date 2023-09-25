<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
$query ="SELECT * FROM tbl_sla_cat WHERE Module = 'JC' ORDER BY Category ASC";
$results = $db_handle->runQuery($query);
?>
<html>
<head>
<TITLE>jQuery Dependent DropDown List - Countries and States</TITLE>
<head>
<style>
body{width:610px;}
.frmDronpDown {border: 1px solid #F0F0F0;background-color:#C8EEFD;margin: 2px 0px;padding:40px;}
.demoInputBox {padding: 10px;border: #F0F0F0 1px solid;border-radius: 4px;background-color: #FFF;width: 50%;}
.row{padding-bottom:15px;}
</style>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get-sla-sub.php",
	data:'sla_id='+val,
	success: function(data){
		$("#sla-list").html(data);
	}
	});
}

function selectCountry(val) {
$("#search-box").val(val);
$("#suggesstion-box").hide();
}
</script>
</head>
<body>
<div class="frmDronpDown">
<div class="row">
<label>Country:</label><br/>
<select name="company" id="country-list" class="demoInputBox" onChange="getState(this.value);">
<option value="">Oil Company</option>
<?php
foreach($results as $company) {
?>
<option value="<?php echo $company["Id"]; ?>"><?php echo $company["Category"]; ?></option>
<?php
}
?>
</select>
</div>
<div class="row">
<label>State:</label><br/>
<select name="state" id="sla-list" class="demoInputBox">
<option value="">Select State</option>
</select>
</div>
</div>
</body>
</html>