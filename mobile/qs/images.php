<?php require_once('../../Connections/inv.php'); ?>
<?php
//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');


session_start();

if(!isset($_SESSION['userid'])){
	header('Location: index.php');
}

require_once('../../Connections/inv.php');

require_once('../../functions/functions.php');

// Redirects
if(isset($_POST['menu'])){
	
	header('Location: menu.php');
	
}

// Find the number of times to loop the file field
$numrows = 1;

if(isset($_POST['add'])){
	
	$numrows = count($_POST['id']) + 1;
	
	if($numrows == 0){
		
		$numrows = 1;
	}
}

if(isset($_FILES['photo']['name'])){
	
	select_db();
		
	$area = $_POST['area'];
	$company = $_POST['company'];
	$site = $_POST['site'];
	
	for($i=0;$i<=$numrows;$i++){
		
		$target_path = "../../gallery/images/";
		$target_path = $target_path . basename( $_FILES['photo']['name'][$i]); 
		
		if(move_uploaded_file($_FILES['photo']['tmp_name'][$i], $target_path)){
			
			$file_attachment = $_FILES['photo']['name'][$i];
			$ext = explode(".", $file_attachment);
			$extension = $ext[1];
			
			$name = $_POST['name'][$i];
			
			$image = rename('../../gallery/images/'.$file_attachment, '../../gallery/images/'. $i .'-'. date('H-i-s') .'.'. $extension);
			$image_name = $i .'-'. date('H-i-s') .'.'. $extension;
			
			mysqli_query($con, "INSERT INTO tbl_gallery (Image) VALUES ('$image_name')")or die(mysqli_error($con));
			
			$success = 'Image upload successfull';
			
		} else {
			
			$success = 'Upload failed';
		}
	}
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

$query_Recordset1 = "SELECT * FROM tbl_areas ORDER BY Area ASC";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset2 = $_GET['Id'];
}
$query_Recordset2 = "SELECT * FROM tbl_jc WHERE JobId = '$colname_Recordset2' AND Comment = '1'";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset3 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<title>Sealink</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/zero.css" rel="stylesheet" type="text/css" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="../../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../../../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../../includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="../../includes/wdg/classes/DependentDropdown.js"></script>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>

<div id="wrapper">

        <div id="logo"><a href="history.php"><img src="images/logo.jpg" alt="Exit" title="Exit" width="171" height="67" border="0" /></a></div><!--logo-->
        
  <div id="content">
  <?php if(isset($success)){ echo $success; } ?> 
    <form action="images.php" method="post" enctype="multipart/form-data" id="fm_form" name="fm_form" >
      <p>
        <?php for($i=0;$i<$numrows;$i++){ ?>
      </p>
<table width="100%" border="0" align="left" cellpadding="2" cellspacing="3">
  <tr>
                                  <td><input name="photo[]" type="file" id="photo[]" size="20" style="width:100%"></td>
                                  <td width="13"><input name="id[]" type="hidden" id="id[]" value="<?php echo $i; ?>" /></td>
                                  <td width="15">
								  <?php if($i == 0){ ?>
                                  <input name="add" type="submit" class="new-image" id="add" value="">
                                  <?php } ?>
                                  </td>
                                </tr>
                              </table>
                              <?php } ?>
<p>&nbsp;</p>
                              <p>
                              <input class="fm-req" id="fm-submit" name="upload" value="Upload Images" type="submit" />
                              </p>
                              <p>
<input class="fm-req" id="fm-submit2" name="menu" value="Main Menu" type="submit" /></p>
                              <p>&nbsp;</p>
                              
                    
    </form>
  </div><!--content-->
</div><!--end wrapper-->

</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);

mysqli_free_result($Recordset4);
?>
