<?php require_once('../../Connections/inv.php'); ?>
<?php 
session_start();

if(!isset($_SESSION['userid'])){
	header('Location: index.php');
}

require_once('../../Connections/inv.php');

require_once('../../functions/functions.php');

// Find the number of times to loop the file field
$numrows = 1;

if(isset($_POST['add'])){
	
	$numrows = count($_POST['id']) + 1;
	
	if($numrows == 0){
		
		$numrows = 1;
	}
}

if(isset($_POST['Submit'])){
	
	select_db();
	
	$technicianid = $_SESSION['userid'];
	$date = date('Y-m-d H:i:s');
	$comments = addslashes("<span style='color:#FF0000;'>".$_POST['history']."</span>");
    $quoteno = $_GET['Id'];
	$date2 = date('Y-m-d');
	
	if(!empty($_POST['history'])){
		
		mysqli_query($con, "INSERT INTO tbl_actual_history (QuoteNo,TechnicianId,Date,Comments,Mobile) VALUES ('$quoteno','$technicianid','$date','$comments','1')")or die(mysqli_error($con));
		
		mysqli_query($con, "UPDATE tbl_history_alerts SET Date = '$date2', OnHold = '0' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	}
}

if(isset($_POST['close'])){
	
	select_db();
	
	$quoteno = $_GET['Id'];
	
	$comments = addslashes("<span style='color:#FF0000;'>".$_POST['history'] . " Assesment is complete</span>");
	
	$technicianid = $_SESSION['userid'];
	$date = date('Y-m-d H:i:s');
	$comments = addslashes("<span style='color:#FF0000;'>".$_POST['history']."</span>");
    $quoteno = $_GET['Id'];
	$date2 = date('Y-m-d');
	
	mysqli_query($con, "INSERT INTO tbl_actual_history (QuoteNo,TechnicianId,Date,Comments,Mobile) VALUES ('$quoteno','$technicianid','$date','$comments','1')")or die(mysqli_error($con));
	
	mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	
	header('Location: ../menu.php');
	
}

if(isset($_FILES['photo']['name'])){
	
	unset($_SESSION['error']);
	
	for($i=0;$i<count($_FILES['photo']['name']);$i++){
		
		$quoteno = $_GET['Id'];
		$target_path = "../../photos/";
		
		$target_path = $target_path . basename( $_FILES['photo']['name'][$i]); 
		
		if(move_uploaded_file($_FILES['photo']['tmp_name'][$i], $target_path)) {
			
			$file_attachment = $_FILES['photo']['name'][$i];
			$ext = explode(".", $file_attachment);
			
			$extension = $ext[1];
			
			$image = rename('../../photos/'.$file_attachment, '../../photos/'. $_GET['Id'] .'-'. $i .'-'. date('H-i-s') .'.'. $extension);
			$image_name = $_GET['Id'] .'-'. $i .'-'. date('H-i-s') .'.'. $extension;
			
			mysqli_query($con, "INSERT INTO tbl_photos (QuoteNo,Image) VALUES ('$quoteno','$image_name')")or die(mysqli_error($con));
			
			createThumbs('../../photos/','../../photos/thumbnails/',100,$image_name);
			$_SESSION['error'] .= "Successfully uploaded ". $_FILES['photo']['name'][$i] .'<br>';
			
		} else {
			
			$_SESSION['error'] .= "Failed to upload ". $_FILES['photo']['name'][$i] .'<br>';
			
		}
	}
	
	header('Location: history.php?Id='. $_GET['Id']);
}


if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

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
$query_Recordset1 = "SELECT * FROM tbl_history_alerts WHERE TechnicianId = '$colname_Recordset1' AND QuoteNo >= '1'";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = $_GET['Id'];
}
$query_Recordset2 = "SELECT * FROM tbl_jc WHERE QuoteNo = '$colname_Recordset2' AND Comment = '1'";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error());
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

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

<style type="text/css">
input[type=text], input[type=url], input[type=email], input[type=password], input[type=tel], select {
  -webkit-appearance: none; -moz-appearance: none;
  display: block;
  margin: 0;
  width: 100%; 
  height: 40px;
  line-height: 40px; 
  font-size: 17px;
  border: 1px solid #bbb;
}
textarea {
  -webkit-appearance: none; -moz-appearance: none;
  display: block;
  margin: 0;
  width: 100%; 
  line-height: 20px; 
  font-size: 17px;
  border: 1px solid #bbb;
  font-family: Arial, Helvetica, sans-serif;
}
input[type=submit], input[type=file], .CollapsiblePanelTab {
 -webkit-appearance: none; 
 -moz-appearance: none;
 display: block;
 margin: 1.5em 0;
 margin-bottom:0px;
 font-size: 1em !important; 
 line-height: 2.5em !important;
 color: #333 !important;
 font-weight: bold;
 height: 2.5em; 
 width: 100%;
 background: #fdfdfd; 
 background: -moz-linear-gradient(top, #fdfdfd 0%, #bebebe 100%); 
 background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fdfdfd), color-stop(100%,#bebebe)); 
 background: -webkit-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); 
 background: -o-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); 
 background: -ms-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); 
 background: linear-gradient(to bottom, #fdfdfd 0%,#bebebe 100%);
 border: 1px solid #bbb !important;
 -webkit-border-radius: 5px; 
 -moz-border-radius: 5px; 
 border-radius: 5px;
}
#banner-overdue {
	background-color: #F7CECA;
	background-image: url(../images/icons/report-late.png);
	padding-top: 30px;
	padding-right: 20px;
	padding-bottom: 30px;
	padding-left: 60px;
	border: 2px solid #D93322;
	-moz-border-radius: 5px;
	-webkit-border-radius:5 px;
	border-radius: 5px; /* future proofing */
	-khtml-border-radius: 5px;
	background-repeat: no-repeat;
	background-position: 15px 24px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333;
	line-height: 20px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 10px;
	margin-left: 0px;
}

</style>

</head>
<body id="site">

<div id="wrapper">

        <div id="logo"><a href="../menu.php"><img src="images/logo.jpg" alt="Exit" title="Exit" width="171" height="67" border="0"></a></div><!--logo-->
        
  <div id="content">
  <?php if(!empty($_SESSION['error'])){ ?>
  <div id="banner-overdue">
   <?php echo $_SESSION['error'];  ?>
  </div>
  <?php } ?>
  
    <form action="history.php<?php if(isset($_GET['Id'])){ ?>?Id=<?php echo $_GET['Id']; } ?>" method="post" enctype="multipart/form-data" >
      <p>
      <select name="jumpMenu" id="jumpMenu" onChange="MM_jumpMenu('parent',this,0)">
        <option value="history.php" <?php if($_GET['Id'] == $row_Recordset1['QuoteNo']) {echo "selected=\"selected\"";} ?>>Quote Number</option>
                                <?php
do {  
?>
                                <option value="history.php?Id=<?php echo $row_Recordset1['QuoteNo']?>"<?php if($_GET['Id'] == $row_Recordset1['QuoteNo']) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['QuoteNo'] .' '. stripslashes($row_Recordset1['Site']); ?></option>
                                <?php
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  $rows = mysqli_num_rows($Recordset1);
  if($rows > 0) {
      mysqli_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  }
?>
                              </select>
                              </p>
      <?php if(isset($_GET['Id'])){ ?>
      <p><textarea name="history" id="history" cols="45" class="tarea-height"></textarea></p>
      <?php for($i=0;$i<$numrows;$i++){ ?>
      <table width="100%" border="0" align="left" cellpadding="2" cellspacing="3">
       <tr>
       <td><input name="photo[]" type="file" id="photo[]" style="width:100%"></td>
        <td width="13"><input name="id[]" type="hidden" id="id[]" value="<?php echo $i; ?>" /></td>
        <td width="15">
		<?php if($i == 0){ ?>
          <input name="add" type="submit" class="new-image" id="add" value="">
         <?php } ?>
         </td>
        </tr>
      </table>
       <?php } ?>
         <p><input class="fm-req" id="fm-submit" name="Submit" value="Update Assesment" type="submit" /></p>
         <p><input class="fm-req" id="fm-submit" name="close" value="Close Assesment" type="submit" /></p>
      <?php } ?>
    </form>
  </div><!--content-->
</div><!--end wrapper-->

</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);
?>
