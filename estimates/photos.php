<?php 

require_once('../functions/functions.php');

if(isset($_GET['delete'])){
	
	$photo = $_GET['delete'];
	
	mysqli_query($con, "DELETE FROM tbl_photos WHERE Id = '$photo'")or die(mysqli_error($con));

}

$colname_Recordset3 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset3 = $_GET['Id'];
}
$query_img = mysqli_query($con, "SELECT * FROM tbl_photos WHERE QuoteNo = '$colname_Recordset3'")or die(mysqli_error($con));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
</head>

<body>
<form action="fpdf16/pdf/quote_mail.php" method="post" enctype="multipart/form-data" name="form2" id="form2">  
  <table border="0" cellpadding="3" cellspacing="1">
    <tr>
      <td align="right">
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
                  <td align="center"><a href="../photos/<?php echo $row_img['Image']; ?>" class="look_inside" onclick="return hs.expand(this, {captionId: 'caption1'})"> <img src="../photos/thumbnails/<?php echo $row_img['Image']; ?>" border="0" class="img_border" /></a></td>
                </tr>
                <tr>
                  <td align="center" class="blue-generic">
                    <a href="q_photos.php?delete=<?php echo $row_Recordset3['Id']; ?>&amp;Id=<?php echo $_GET['Id']; ?>">
                      <img src="../images/no.jpg" width="15" height="15" border="0" />
                    </a>
                  </td>
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
        </table>      </td>
    </tr>
    <?php if(mysqli_num_rows($query_img) >= 1){ ?>
    <tr>
      <td align="right">&nbsp;</td>
    </tr>
    <?php } ?>
  </table>
</form>
</body>
</html>