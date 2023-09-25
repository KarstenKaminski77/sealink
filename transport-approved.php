<?php

$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$row = mysql_fetch_assoc($query);

$companyid = $row['CompanyId'];
$siteid = $row['SiteId'];

$query = mysql_query("SELECT * FROM tbl_travel WHERE JobId = '$jobid'")or die(mysql_error());
	
	if($companyid == 1){ // Engen
	
	while($row = mysql_fetch_array($query)){
		
		
		$jobid = $row['JobId'];
		
		$query2 = "SELECT * FROM tbl_sites WHERE Id = '$siteid'";
		$result2 = mysql_query($query2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
	
?>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<div>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
    <tr>
      <td width="350">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?></td>
      <td width="100" align="right"><div style="padding-right:5px; text-align:right; width:95px"><span class="combo2"><?php echo $row['Description']; ?></span> round trips </div></td>
      <td width="50" align="center" valign="bottom"><span class="combo2">km</span></td>
      <td width="50" align="center" valign="bottom"><span class="combo2"><?php echo $row['Qty']; ?></span></td>
      <td width="100" align="center" valign="bottom"><span class="combo2">R<?php echo number_format($row_Recordset8['Rate'],2); ?></span></td>
      <td width="50" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </table>
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
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="200"><span class="combo2"> &nbsp;<?php echo $row['TransportComment']; ?></span></td>
             <td width="80"><span class="combo2">Km&nbsp; <?php echo $row['DistanceKm']; ?></span></td>
             <td width="100"><span class="combo2">Km Rate
             <?php

if($row2['Distance'] != NULL){
	
	$distance = $row2['Distance'];
	
} else {
	
	$distance = $row['Qty'];
	
}

$query3 = mysql_query("SELECT * FROM tbl_jc WHERE tbl_jc.JobId = '$jobid'  AND Transport = '1' AND Price > '0'")or die(mysql_error());
$numrows3 = mysql_num_rows($query3);
$row3 = mysql_fetch_array($query3);

if($numrows3 >= 1){
	
	$rate = number_format($row3['Price'],2);
	
} else {
	
	$query4 = mysql_query("SELECT * FROM tbl_jc WHERE tbl_jc.JobId = '$jobid'  AND Transport = '1'")or die(mysql_error());
	$row4 = mysql_fetch_array($query4);
	
	$companyid = $row4['CompanyId'];
	
	$query5 = mysql_query("SELECT * FROM tbl_fuel WHERE Company = '$companyid'")or die(mysql_error());
	$row5 = mysql_fetch_array($query5);
	
	$rate = number_format($row5['Rate'],2);
	
	}

							
?>
             <?php echo $row['KmRate']; ?></span></td>
             <td width="115"><span class="combo2">Total Km <?php echo $row['TotalKm']; ?></span></td>
             <td width="125"><span class="combo2">Travel Time <?php echo $row['TravelTime']; ?></span></td>
             <td><span class="combo2">Travel Time Rate <?php echo $row['TravelTimeRate']; ?></span></td>
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
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
    <tr>
      <td width="350">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?></td>
      <td width="100" align="right"><div style="padding-right:5px; text-align:right; width:95px"><span class="combo2"><?php echo $row['Description']; ?></span> round trips </div></td>
      <td width="50" align="center" valign="bottom"><span class="combo2">km</span></td>
      <td width="50" align="center" valign="bottom"><span class="combo2"><?php echo $row['Qty']; ?></span></td>
      <td width="100" align="center" valign="bottom"><span class="combo2">R<?php echo number_format($row_Recordset8['Rate'],2); ?></span></td>
      <td width="50" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </table>
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
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
    <tr>
      <td width="350">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?></td>
      <td width="100" align="right"><div style="padding-right:5px; text-align:right; width:95px"><span class="combo2"><?php echo $row['Description']; ?></span> round trips </div></td>
      <td width="50" align="center" valign="bottom"><span class="combo2">km</span></td>
      <td width="50" align="center" valign="bottom"><span class="combo2"><?php echo $row['Qty']; ?></span></td>
      <td width="100" align="center" valign="bottom"><span class="combo2">R<?php echo number_format($row_Recordset8['Rate'],2); ?></span></td>
      <td width="50" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </table>
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
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
    <tr>
      <td width="350">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?></td>
      <td width="100" align="right"><div style="padding-right:5px; text-align:right; width:95px"><span class="combo2"><?php echo $row['Description']; ?></span> round trips </div></td>
      <td width="50" align="center" valign="bottom"><span class="combo2">km</span></td>
      <td width="50" align="center" valign="bottom"><span class="combo2"><?php echo $row['Qty']; ?></span></td>
      <td width="100" align="center" valign="bottom"><span class="combo2">R<?php echo number_format($row_Recordset8['Rate'],2); ?></span></td>
      <td width="50" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </table>
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
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
    <tr>
      <td width="350">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?></td>
      <td width="100" align="right"><div style="padding-right:5px; text-align:right; width:95px"><span class="combo2"><?php echo $row['Description']; ?></span> round trips </div></td>
      <td width="50" align="center" valign="bottom"><span class="combo2">km</span></td>
      <td width="50" align="center" valign="bottom"><span class="combo2"><?php echo $row['Qty']; ?></span></td>
      <td width="100" align="center" valign="bottom"><span class="combo2">R<?php echo number_format($row_Recordset8['Rate'],2); ?></span></td>
      <td width="50" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </table>
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
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo">
    <tr>
      <td width="350">&nbsp;&nbsp;<?php echo $row['TransportComment']; ?></td>
      <td width="100" align="right"><div style="padding-right:5px; text-align:right; width:95px"><span class="combo2"><?php echo $row['Description']; ?></span> round trips </div></td>
      <td width="50" align="center" valign="bottom"><span class="combo2">km</span></td>
      <td width="50" align="center" valign="bottom"><span class="combo2"><?php echo $row['Qty']; ?></span></td>
      <td width="100" align="center" valign="bottom"><span class="combo2">R<?php echo number_format($row_Recordset8['Rate'],2); ?></span></td>
      <td width="50" align="right" valign="bottom">R<?php echo $row['Total1']; ?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
  </table>
</div>
<?php 

} // close loop 

} // close if


?>
		  