<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysql_error());
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

if($_SESSION['kt_login_level'] == 0){

$areaid = $_SESSION['kt_AreaId'];
}

$quoteno = $_GET['Id'];

$query = mysqli_query($con, "SELECT * FROM tbl_reports WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));

if(mysqli_num_rows($query) == 0){
	
	mysqli_query($con, "INSERT INTO tbl_reports (QuoteNo) VALUES ('$quoteno')")or die(mysqli_error($con));

}

if(isset($_POST['report'])){
	
	$report = $_POST['report'];
	$quoteno = $_GET['Id'];
	
	mysqli_query($con, "UPDATE tbl_reports SET Report = '$report' WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
}

$colname_Recordset3 = "-1";

if(isset($_GET['Id'])){
	
  $colname_Recordset3 = $_GET['Id'];
}
$query_Recordset3 = mysqli_query($con, "SELECT * FROM tbl_reports WHERE QuoteNo = '$colname_Recordset3'") or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($query_Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($query_Recordset3);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
            
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      
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
	  
		.td-header {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			line-height: 30px;
			font-weight: bold;
			text-transform: capitalize;
		
			background: #18519b; /* Old browsers */
			background: -moz-linear-gradient(top,  #18519b 0%, #3c87c4 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#18519b), color-stop(100%,#3c87c4)); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* IE10+ */
			background: linear-gradient(to bottom,  #18519b 0%,#3c87c4 100%); /* W3C */
		
			padding-left: 10px;
			color: #FFF;
			padding-top: 0px;
			padding-right: 10px;
			padding-bottom: 0px;
		}
	  
      </style>
      
   </head>
   <body>
         
      <!-- Main Form -->
      <div id="main-wrapper">
      
		<form name="form2" method="post" action="" style="margin-left:15px">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		      <td><div id="list-border">
		        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		          <tr>
		            <td class="td-header">Report</td>
	              </tr>
		          <tr>
		            <td class="td-right"><textarea name="report" cols="145" rows="7" class="tarea-100" id="report"><?php echo $row_Recordset3['Report']; ?></textarea>		              <br /></td>
	              </tr>
	            </table>
		      </div>
              
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="right"><input name="Submit2" type="submit" class="btn-new" value="Save Report" /></td>
                  </tr>
              </table></td>
	        </tr>
	      </table>
		</form>
      
   </div>
      <!-- End Main Form -->
      
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