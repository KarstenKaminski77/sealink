<?php
$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$qry = 'SELECT * FROM tbl_engineers WHERE CompanyId='.$_POST['id'];
print_r($qry);
$sql = mysqli_query($con, $qry);
while($row = mysqli_fetch_array($sql)){
?>
	<option value="<?php echo $row['Name']; ?>" <?php if($row['Name'] == $_POST['reference']){ echo 'selected="selected"'; } ?>><?php echo $row['Name']; ?></option>
<?php } ?>