<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

// Upload Image
$target_path = "../photos/";
$target_path = $target_path.basename($_FILES['image']['name']);
$form_data = array();

if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {

  $form_data['Image'] = $_FILES['image']['name'];
  $form_data['QuoteNo'] = $_GET['Id'];
  
  dbInsert('tbl_photos', $form_data, $con);
}

if(isset($_POST['image'])){
	
	for($i=0;$i<count($_POST['image']);$i++){
		
		$image = $_POST['image'][$i];
		$quoteno = $_COOKIE['quoteno'];
		
		mysqli_query($con, "INSERT INTO tbl_photos (QuoteNo,Image) VALUES ('$quoteno','$image')")or die(mysqli_error($con));
		
		copy('../gallery/images/'. $image, '../photos/'. $image);
	}
}

if($_POST['limit'] >= 1){
	
	$limit = $_POST['limit'];
	
} else {
	
	$limit = 0;
}

$query_img = mysqli_query($con, "SELECT * FROM tbl_photos ORDER BY Id DESC LIMIT $limit")or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
            
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      
      <link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />
      
      <script type="text/javascript" src="../highslide/highslide.js"></script>
      <script type="text/javascript">
      <!--
      hs.graphicsDir = '../highslide/graphics/';
          hs.outlineType = 'rounded-white';
      
      function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
      }
      
      function MM_popupMsg(msg) { //v1.0
        alert(msg);
      }
      //-->
      </script>
      
	  <style type="text/css">
      
        .td-left {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 20px;
            font-weight: bold;
            text-transform: capitalize;
            color: #818284;
            background-color: #f7f7f7;
            border: 1px solid #DFDFDF;
            padding-left: 5px;
            font-style: italic;
        }
        
        .td-right {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 20px;
            font-weight: normal;
            /* [disabled]text-transform: capitalize; */
            color: #818284;
            border: 1px solid #DFDFDF;
            padding: 5px;
            background-color: #FFF;
        }
        
        #list-border{
            border: 1px solid #DDDDDD;
            padding: 1px;
        }
        
        .tarea-100 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #808185;
            width: 100%;
            border-top-style: none;
            border-right-style: none;
            border-bottom-style: none;
            border-left-style: none;
            outline:none;
            resize: none;
            background:none;
        }
        
        .btn-new {
            background: #18519b; /* Old browsers */
            background: -moz-linear-gradient(top,  #18519b 0%, #3c87c4 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#18519b), color-stop(100%,#3c87c4)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* IE10+ */
            background: linear-gradient(to bottom,  #18519b 0%,#3c87c4 100%); /* W3C */
        
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#18519b', endColorstr='#3c87c4',GradientType=0 ); /* IE6-9 */
            
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
            color: #FFF;
            margin-top: 10px;
            border: none;
            padding-top: 5px;
            padding-right: 10px;
            padding-bottom: 5px;
            padding-left: 10px;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
        }
      
      .btn-new1 {            background: #18519b; /* Old browsers */
            background: -moz-linear-gradient(top,  #18519b 0%, #3c87c4 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#18519b), color-stop(100%,#3c87c4)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* IE10+ */
            background: linear-gradient(to bottom,  #18519b 0%,#3c87c4 100%); /* W3C */
        
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#18519b', endColorstr='#3c87c4',GradientType=0 ); /* IE6-9 */
            
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
            color: #FFF;
            margin-top: 10px;
            border: none;
            padding-top: 5px;
            padding-right: 10px;
            padding-bottom: 5px;
            padding-left: 10px;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
}
      </style>
      
   </head>
<body>
 
<form action="" method="post" enctype="multipart/form-data" name="form3">
  <table width="879" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
      <div id="list-border">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="92" class="td-left">Upload Photo</td>
            <td width="787" class="td-right"><input name="image" type="file" class="tarea-100" id="image" /></td>
          </tr>
        </table>
      </div>
      </td>
    </tr>
  </table>
  
  <div style="width:879px; text-align:right; margin-bottom:20px">
    <input name="upload" type="submit" class="btn-new" id="upload" value="Submit" />
  </div>
  
  <table width="879" border="0" cellpadding="3" cellspacing="1">
    <tr>
      <td class="td-right">
        <input name="limit" type="text" class="tarea-100" id="limit" value="<?php echo $_POST['limit']; ?>" placeholder="Number of photos to display">
      </td>
      <td width="40"><input name="button2" type="submit" class="btn-new1" id="button2" value="Search" style="margin-top:0" /></td>
    </tr>
  </table>
  <table width="879" border="0" cellpadding="3" cellspacing="1">
    <tr>
      <td align="right">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="3">
          <?php if(mysqli_num_rows($query_img) >= 1){ ?>
          <tr>
            <?php
			
			$c = 0;
			
            while($row_img = mysqli_fetch_array($query_img)){
				
				createThumbs('../photos/','../photos/thumbnails/',200,$row_img['Image']);
				
				$c++;
				
            ?>
            <td align="center" valign="bottom">
              <table border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td colspan="2" align="center"><a href="../photos/<?php echo $row_img['Image']; ?>" class="look_inside" onclick="return hs.expand(this, {captionId: 'caption1'})"> <img src="../photos/thumbnails/<?php echo $row_img['Image']; ?>" border="0" class="img_border" /></a></td>
                </tr>
                <tr>
                  <td align="center" class="blue-generic"><?php echo $row_img['Name']; ?></td>
                  <td><input name="image[]" type="checkbox" id="image[]" value="<?php echo $row_img['Image']; ?>"></td>
                </tr>
              </table>
            </td>
            <?php
            if ($c % 4 == 0) {
				
				echo "</tr><tr>";
            }
			}
            ?>
          </tr>
          <?php } ?>
        </table>
      </td>
    </tr>
    <?php if(mysqli_num_rows($query_img) >= 1){ ?>
    <tr>
      <td align="right"><input name="attach-image" type="submit" class="btn-new" id="attach-image" value="Attach Images"></td>
    </tr>
    <?php } ?>
  </table>
</form>
      
</body>
</html>
<?php 
  mysqli_close($con); 
  mysqli_free_result($query);
  mysqli_free_result($query_areas);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
  mysqli_free_result($query_user_menu);
?>