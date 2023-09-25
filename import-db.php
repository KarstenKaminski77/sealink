<?php
mysql_connect("sql25.jnb1.host-h.net","kwdaco_43","SBbB38c8Qh8") or die(mysql_error());
mysql_select_db("seavest_db333") or die(mysql_error());

$query = mysql_query("SELECT * FROM tbl_jc WHERE Transport = '1'")or die(mysql_error());
while($row = mysql_fetch_assoc($query)){
							   
							   $jobid = $row['JobId'];	
							   $km = $row['DistanceKm'];	
							   $km_rate = $row['KmRate'];	
							   $total_km = $row['TotalKm'];	
							   $travel_time = $row['TravelTime'];	
							   $travel_time_rate = $row['TravelTimeRate'];	
							   $round_trips = $row['RoundTrips'];	
							   $distance = $row['Distance'];	
							   $total = $row['Total'];	
							   $unit = $row['Unit'];	
							   $qty = $row['Qty'];	
							   $price = $row['Price'];	
							   $total1 = $row['Total1'];	
							   $transport_comment = $row['TransportComment'];
							   $total_pragma = $row['TotalPragma'];

mysql_query("INSERT INTO tbl_travel (JobId,DistanceKm,KmRate,TotalKm,TravelTime,TravelTimeRate,RoundTrips,Distance,Total,Unit,Qty,Price,Total1,TransportComment,TotalPragma) VALUES ('$jobid','$km','$km_rate','$total_km','$travel_time','$travel_time_rate','$round_trips','$distance','$total','$unit','$qty','$price','$total1','$tranport_comment','$total_pragma')")or die(mysql_error());

							   }
?>
