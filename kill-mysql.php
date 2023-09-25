<?php

mysql_connect('sql25.jnb1.host-h.net', 'kwdaco_43', 'SBbB38c8Qh8');
mysql_select_db("seavest_db333");
$res = mysql_query("SHOW FULL PROCESSLIST");
while ($row=mysql_fetch_array($res)) {
  $pid=$row["Id"];
  if ($row['Command']=='Sleep') {
      if ($row["Time"] > 3 ) { //any sleeping process more than 3 secs
         $sql="KILL $pid";
         echo "\n$sql"; //added for log file
         mysql_query($sql);
      }
  }
}

$result = mysql_query("SHOW FULL PROCESSLIST");
while ($row=mysql_fetch_array($result)) {
  $process_id=$row["Id"];
  if ($row["Time"] > 200 ) {
    $sql="KILL $process_id";
    mysql_query($sql);
  }
}
?>