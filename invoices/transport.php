<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
<?php

$jobid = $_GET['Id'];

$query2 = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$row2 = mysql_fetch_array($query2);

$companyid = $row2['CompanyId'];

$query = mysql_query("SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysql_error());


?>
<div>
  <table width="100%" border="0" cellspacing="5" cellpadding="0">
  <?php
while($row = mysql_fetch_array($query)){

	$siteid = $row['SiteId'];

	$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
	$result2 = mysql_query($query2) or die(mysql_error());
	$row2 = mysql_fetch_array($result2);
	  ?>
        <tr>
          <td width="475"><input name="t_comment[]" type="text" class="tarea-100per" id="t_comment[]" onFocus="if(this.value=='Comments'){this.value=''}" onBlur="if(this.value==''){this.value='Comments'}" value="<?php field_val($row['TransportComment'],'Comments'); ?>"></td>
          <td width="120"><input name="transport[]" type="text" class="tarea-100per" id="transport" onFocus="if(this.value=='Trips to site'){this.value=''}" onBlur="if(this.value==''){this.value='Trips to site'}" value="<?php field_val($row['Description'],'Trips to site'); ?>" size="6"></td>
          <td width="120">
          <input name="qty_t[]" type="text" class="tarea-100per" id="qty_t" onfocus="if(this.value=='Return Distance'){this.value=''}" onblur="if(this.value==''){this.value='Return Distance'}" value="<?php field_val($row['Qty'],'Return Distance'); ?>" /></td>
          <td width="120"><input name="price_t[]" type="text" class="tarea-100per" id="price_t[]" onfocus="if(this.value=='Fuel Rate'){this.value=''}" onblur="if(this.value==''){this.value='Fuel Rate'}" value="<?php field_val($row['Price'],'Fuel Rate'); ?>" size="16" /></td>
          <td width="20"><input name="delete_t[]" type="checkbox" id="delete_t[]" value="<?php echo $row['Id']; ?>" /></td>
        </tr>
      <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
      <input name="unit_t[]" type="hidden" class="tfield-jc-km" id="unit_t" value="km" size="7">
        <?php } ?>
      </table>
	</div>