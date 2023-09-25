<?php require_once('../Connections/inv.php'); ?>
<?php 
session_start();

if(!isset($_SESSION['userid'])){
	header('Location: index.php');
}

require_once('../Connections/inv.php');

require_once('../functions/functions.php');

if(isset($_POST['Submit'])){
	
	select_db();
	
	$technicianid = $_SESSION['userid'];
	$date = date('Y-m-d H:i:s');
	$comments = addslashes("<span style='color:#FF0000;'>".$_POST['history']."</span>");
    $jobid = $_GET['Id'];
	$date2 = date('Y-m-d');
	
	if(!empty($_POST['history'])){
		
		mysql_query("INSERT INTO tbl_actual_history (JobId,TechnicianId,Date,Comments,Mobile) VALUES ('$jobid','$technicianid','$date','$comments','1')")or die(mysql_error());
		
		mysql_query("UPDATE tbl_history_alerts SET Date = '$date2', OnHold = '0' WHERE JobId = '$jobid'")or die(mysql_error());
	}
}

if(isset($_POST['costing'])){
	
	select_db();
	
	$jobid = $_GET['Id'];
	
	$complete = addslashes("<span style='color:#FF0000;'>".$_POST['history'] . " Job is complete</span>");
	
	mysql_query("UPDATE tbl_jc SET CommentText = '$complete' WHERE JobId = '$jobid'")or die(mysql_error());
	
	mysql_query("DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'")or die(mysql_error());
	
	header('Location: history.php?costing');
	
}

if(isset($_POST['onhold'])){
	
	select_db();
	
	$jobid = $_GET['Id'];
		
	mysql_query("UPDATE tbl_history_alerts SET OnHold = '1' WHERE JobId = '$jobid'")or die(mysql_error());
	
	header('Location: history.php?on-hold');
	
}

if(isset($_FILES['photo']['name'])){
	
	$target_path = "../images/history/";
	
	$target_path = $target_path . basename( $_FILES['photo']['name']); 
	
	if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
		
		$file_attachment = $_FILES['photo']['name'];
		$ext = explode(".", $file_attachment);
		$extension = $ext[1];
		
		$image = rename('../images/history/'.$file_attachment, '../images/history/'. $_GET['Id'] .'-'. date('H-i-s') .'.'. $extension);
		$image_name = $_GET['Id'] .'-'. date('H-i-s') .'.'. $extension;
		
		mysql_query("INSERT INTO tbl_history_photos (Photo) VALUES ('$image_name')")or die(mysql_error());
		
		$query = mysql_query("SELECT * FROM tbl_history_photos ORDER BY Id DESC")or die(mysql_error());
		$row = mysql_fetch_array($query);
		
		$photoid = $row['Id'];
		$jobid = $_GET['Id'];
		
		mysql_query("INSERT INTO tbl_history_relation (JobId,PhotoId) VALUES ('$jobid','$photoid')")or die(mysql_error());
	}
}


if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_Recordset1 = "-1";
if (isset($_SESSION['userid'])) {
  $colname_Recordset1 = $_SESSION['userid'];
}
mysql_select_db($database_inv, $inv);
$query_Recordset1 = sprintf("SELECT * FROM tbl_history_alerts WHERE TechnicianId = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $inv) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = $_GET['Id'];
}
mysql_select_db($database_inv, $inv);
$query_Recordset2 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s AND Comment = '1'", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $inv) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Sealink</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="css/zero.css" rel="stylesheet" type="text/css" />
		<link href="css/default.css" rel="stylesheet" type="text/css" />
	
    <script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
        </script>
<body id="site">

<div id="wrapper">

        <div id="logo"><a href="history.php"><img src="images/logo.jpg" alt="Exit" title="Exit" width="171" height="67" border="0"></a></div><!--logo-->
        
  <div id="content">
    <form action="history.php<?php if(isset($_GET['Id'])){ ?>?Id=<?php echo $_GET['Id']; } ?>" method="post" enctype="multipart/form-data" id="fm-form" >
      <p>
      <select name="jumpMenu" id="jumpMenu" onChange="MM_jumpMenu('parent',this,0)">
        <option value="history.php" <?php if($_GET['Id'] == $row_Recordset1['JobId']) {echo "selected=\"selected\"";} ?>>Job Number</option>
                                <?php
do {  
?>
                                <option value="history.php?Id=<?php echo $row_Recordset1['JobId']?>"<?php if($_GET['Id'] == $row_Recordset1['JobId']) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['JobNo'] .' '. stripslashes($row_Recordset1['Site']); ?></option>
                                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                              </select>
                              </p>
                              <p>
                              <?php if(isset($_GET['Id'])){ ?>
                              <textarea name="history" id="history" cols="45" rows="5"></textarea></p>
                              <p>
                                <input type="file" name="photo" id="photo">
                              </p>
                              <p>
                              <input class="fm-req" id="fm-submit" name="Submit" value="Update History" type="submit" />
                              </p>
      <p>
                              <input class="fm-req" id="fm-submit2" name="costing" value="Job Complete" type="submit" /></p>
                              <p>
                                <input class="fm-req" id="fm-submit2" name="onhold" value="Put Job on Hold" type="submit" />
                              </p>
<?php } elseif(isset($_GET['costing'])){ ?>
                              <p>
                              <div align="center">Job card sent to costing.</div>
                              </p>
<?php } elseif(isset($_GET['on-hold'])){ ?>
                              <p>
                              <div align="center">Job is on hold.</div>
                              </p>
                              <?php } else { ?>
                              <p>
                              <div align="center">Please select a job from the list above.</div>
                              </p>
                              <?php } ?>
                              
                    
    </form>
  </div><!--content-->
</div><!--end wrapper-->

</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
