<?php

$jobid = $_GET['Id'];

$query2 = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$row2 = mysql_fetch_array($query2);

$companyid = $row2['CompanyId'];

$query = mysql_query("SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysql_error());
	
	if($companyid == 1 || $companyid == 12){ // Engen
	
	while($row = mysql_fetch_array($query)){
		
		$siteid = $row['SiteId'];
		$jobid = $row['JobId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />

        <div>
        <span class="combo_bold">&nbsp;
        <input name="t_comment[]" type="text" class="tfield-generic-jc" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>"> 
        &nbsp; Trips to site</span>&nbsp;
        <input name="transport[]" type="text" class="tfield-jc-km" id="transport" value="<?php echo $row['Description']; ?>">
        <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km" size="7"> 
        <span class="combo_bold">Return Distance</span>
<?php

if($row2['Distance'] != NULL){
	
	$distance = $row2['Distance'];
	
} else {
	
	$distance = $row['Qty'];
	
}
							
?>
         <input name="qty_t[]" type="text" class="tfield-jc-km" id="qty_t" value="<?php echo $distance; ?>">
         <span class="combo_bold">km</span> 
         &nbsp;&nbsp;&nbsp; 
         <input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
         <input name="price_t[]" type="hidden" class="tfield-jc-km" id="price_t[]" value="<?php fuel_rate($jobid); ?>" size="16">
         <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
        </div>
<?php 
} // close loop 

} // close if
	
	if($companyid == 2){ // Pragma
	
	while($row = mysql_fetch_array($query)){
		
		$siteid = $row['SiteId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
        <div>
         <table border="0" cellspacing="3" cellpadding="2">
           <tr>
             <td><span class="combo_bold"> Qty / Km</span>;
        </td>
             <td><input name="qty_t[]" type="text" class="tfield-jc-km" id="transport2" value="<?php echo $row['Qty']; ?>" /></td>
             <td width="110" align="right">
               <input type="hidden" name="jobno[]" id="jobno[]" value="<?php echo $row_Recordset5['JobNo']; ?>" /><span class="combo_bold">Unit</span>
          </td>
             <td><select name="unit_t[]" class="tarea" id="unit_t[]">
            <option>Select unit...</option>
            <option value="km" <?php if($row['Unit'] == 'km'){ ?> selected="selected" <?php } ?>>km</option>
            <option value="hrs" <?php if($row['Unit'] == 'hrs'){ ?> selected="selected" <?php } ?>>hrs</option>
          </select></td>
             <td width="200" align="right"><span class="combo_bold">Travel Time Rate</span>
        </td>
             <td><select name="travel-time-rate[]" id="travel-time-rate[]" class="tarea">
          <?php
do {  
?>
          <option value="<?php echo $row_rs_transport_rates['Rate']; ?>" <?php if($row['TravelTimeRate'] == $row_rs_transport_rates['Rate']){ ?> selected="selected" <?php } ?>><?php echo $row_rs_transport_rates['Name']?></option>
          <?php
} while ($row_rs_transport_rates = mysql_fetch_assoc($rs_transport_rates));
  $rows = mysql_num_rows($rs_transport_rates);
  if($rows > 0) {
      mysql_data_seek($rs_transport_rates, 0);
	  $row_rs_transport_rates = mysql_fetch_assoc($rs_transport_rates);
  }
?>
        </select></td>
             <td><input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
         <input name="price_t[]" type="hidden" class="tfield-jc-km" id="price_t[]" value="<?php echo $rate; ?>" size="16">
         <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>"></td>
           </tr>
         </table>
        </div>
<?php 
} // close loop 

} // close if
	
	if($companyid == 3){ // Prowalco
	
	while($row = mysql_fetch_array($query)){
		
		$siteid = $row['SiteId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
        <div>
        <span class="combo_bold">&nbsp;
        <input name="t_comment[]" type="text" class="tfield-generic-jc" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>"> 
        &nbsp; Trips to site</span>&nbsp;
        <input name="transport[]" type="text" class="tfield-jc-km" id="transport" value="<?php echo $row['Description']; ?>">
        <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km" size="7"> 
        <span class="combo_bold">Return Distance</span>
<?php

if($row2['Distance'] != NULL){
	
	$distance = $row2['Distance'];
	
} else {
	
	$distance = $row['Qty'];
	
}
							
?>
         <input name="qty_t[]" type="text" class="tfield-jc-km" id="qty_t" value="<?php echo $distance; ?>">
         <span class="combo_bold">km</span> 
         &nbsp;&nbsp;&nbsp; 
         <input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
         <input name="price_t[]" type="hidden" class="tfield-jc-km" id="price_t[]" value="<?php fuel_rate($jobid); ?>" size="16">
         <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
</div>
<?php 
} // close loop 

} // close if
	
	if($companyid == 4){ // Southern Star
	
	while($row = mysql_fetch_array($query)){
		
		$siteid = $row['SiteId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
        <div>
        <span class="combo_bold">&nbsp;
        <input name="t_comment[]" type="text" class="tfield-generic-jc" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>"> 
        &nbsp; Trips to site</span>&nbsp;
        <input name="transport[]" type="text" class="tfield-jc-km" id="transport" value="<?php echo $row['Description']; ?>">
        <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km" size="7"> 
        <span class="combo_bold">Return Distance</span>
<?php

if($row2['Distance'] != NULL){
	
	$distance = $row2['Distance'];
	
} else {
	
	$distance = $row['Qty'];
	
}
							
?>
         <input name="qty_t[]" type="text" class="tfield-jc-km" id="qty_t" value="<?php echo $distance; ?>">
         <span class="combo_bold">km</span> 
         &nbsp;&nbsp;&nbsp; 
         <input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
         <input name="price_t[]" type="hidden" class="tfield-jc-km" id="price_t[]" value="<?php fuel_rate($jobid); ?>" size="16">
         <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
</div>
<?php 
} // close loop 

} // close if
	
	if($companyid == 5){ // Shell
	
	while($row = mysql_fetch_array($query)){
		
		$siteid = $row['SiteId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
        <div>
        <span class="combo_bold">&nbsp;
        <input name="t_comment[]" type="text" class="tfield-generic-jc" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>"> 
        &nbsp; Trips to site</span>&nbsp;
        <input name="transport[]" type="text" class="tfield-jc-km" id="transport" value="<?php echo $row['Description']; ?>">
        <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km" size="7"> 
        <span class="combo_bold">Return Distance</span>
<?php

if($row2['Distance'] != NULL){
	
	$distance = $row2['Distance'];
	
} else {
	
	$distance = $row['Qty'];
	
}
							
?>
         <input name="qty_t[]" type="text" class="tfield-jc-km" id="qty_t" value="<?php echo $distance; ?>">
         <span class="combo_bold">km</span> 
         &nbsp;&nbsp;&nbsp; 
         <input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
         <input name="price_t[]" type="hidden" class="tfield-jc-km" id="price_t[]" value="<?php fuel_rate($jobid); ?>" size="16">
         <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
</div>
<?php 
} // close loop 

} // close if
	
	if($companyid == 6){ // Total
	
	while($row = mysql_fetch_array($query)){
		
		$siteid = $row['SiteId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
        <div>
        <span class="combo_bold">&nbsp;
        <input name="t_comment[]" type="text" class="tfield-generic-jc" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>"> 
        &nbsp; Trips to site</span>&nbsp;
        <input name="transport[]" type="text" class="tfield-jc-km" id="transport" value="<?php echo $row['Description']; ?>">
        <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km" size="7"> 
        <span class="combo_bold">Return Distance</span>
<?php

if($row2['Distance'] != NULL){
	
	$distance = $row2['Distance'];
	
} else {
	
	$distance = $row['Qty'];
	
}
							
?>
         <input name="qty_t[]" type="text" class="tfield-jc-km" id="qty_t" value="<?php echo $distance; ?>">
         <span class="combo_bold">km</span> 
         &nbsp;&nbsp;&nbsp; 
         <input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
         <input name="price_t[]" type="hidden" class="tfield-jc-km" id="price_t[]" value="<?php fuel_rate($jobid); ?>" size="16">
         <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
</div>
<?php 
} // close loop 

} // close if
	
	if($companyid == 10){ // ERM
	
	while($row = mysql_fetch_array($query)){
		
		$siteid = $row['SiteId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
        <div>
        <span class="combo_bold">&nbsp;
        <input name="t_comment[]" type="text" class="tfield-generic-jc" id="t_comment[]" value="<?php echo $row['TransportComment']; ?>"> 
        &nbsp; Trips to site</span>&nbsp;
        <input name="transport[]" type="text" class="tfield-jc-km" id="transport" value="<?php echo $row['Description']; ?>">
        <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km" size="7"> 
        <span class="combo_bold">Return Distance</span>
<?php

if($row2['Distance'] != NULL){
	
	$distance = $row2['Distance'];
	
} else {
	
	$distance = $row['Qty'];
	
}
							
?>
         <input name="qty_t[]" type="text" class="tfield-jc-km" id="qty_t" value="<?php echo $distance; ?>">
         <span class="combo_bold">km</span> 
         &nbsp;&nbsp;&nbsp; 
         <input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>">
         <input name="price_t[]" type="hidden" class="tfield-jc-km" id="price_t[]" value="<?php fuel_rate($jobid); ?>" size="16">
         <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
</div>
<?php 

} // close loop 

} // close if


?>
		  