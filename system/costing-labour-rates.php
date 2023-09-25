<?php
error_reporting(ERROR);
ini_set('display_errors', '1');

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

////////////////////
/// INSERT AREA ///
//////////////////

if(isset($_POST['insert'])){
	
	$name = $_POST['name'];
	$rate = $_POST['rate'];
	
	mysqli_query($con, "INSERT INTO tbl_costing_labour_rates (Name,Rate) VALUES ('$name','$rate')")or die(mysqli_error($con));
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
	
}

////////////////////
/// UPDATE AREA ///
//////////////////

if(isset($_POST['update'])){
	
	$name = $_POST['name'];
	$rate = $_POST['rate'];
	$id = $_GET['Id'];
	
	mysqli_query($con, "UPDATE tbl_costing_labour_rates SET Name = '$name', Rate = '$rate' WHERE Id = '$id'")or die(mysqli_error($con));
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
	
}

////////////////////
/// DELETE AREA ///
//////////////////

if(isset($_GET['Delete'])){
	
	$id = $_GET['Delete'];
	
	mysqli_query($con, "DELETE FROM tbl_costing_labour_rates WHERE Id = '$id'")or die(mysqli_error($con));
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
	
}

//////////////////////////////
/// UPDATE POPULATE QUERY ///
////////////////////////////

$id = $_GET['Id'];

$query = mysqli_query($con, "SELECT * FROM tbl_costing_labour_rates WHERE Id = '$id'")or die(mysql_error($con));
$row_costing = mysqli_fetch_array($query);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FFFFFF;
}
a:hover {
	text-decoration: none;
	color: #CCCCCC;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
-->
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../menu.php'); ?></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <form name="form2" method="post" action="" style="margin-left:30px">
            <table border="0" align="left" cellpadding="2" cellspacing="3">
              <tr class="td-header">
                <td width="400">Name</td>
                <td width="120">Rate</td>
              </tr>
              <tr>
                <td><input name="name" type="text" class="tarea-100per" id="name" value="<?php echo $row_costing['Name']; ?>"></td>
                <td><input name="rate" type="text" class="tarea-100per" id="rate" value="<?php echo $row_costing['Rate']; ?>"></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><?php if(isset($_GET['Id'])){ ?>
                  <input name="update" type="submit" class="btn-blue-generic" id="update" value="Update" />
                  <?php } else { ?>
                  <input name="insert" type="submit" class="btn-blue-generic" id="insert" value="Insert" />
                  <?php } ?></td>
                </tr>
            </table>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div id="list-brdr">
                  <table cellpadding="2" cellspacing="3">
                    <?php 
					////////////////////////////
					/// DATABASE LIST QUERY ///
					//////////////////////////
					$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');
					$query2 = mysqli_query($con, "SELECT * FROM tbl_costing_labour_rates ORDER BY Name ASC")or die(mysql_error($con));

					while($row2 = mysqli_fetch_array($query2)){ ?>
                    <?php if(mysqli_num_rows($query2) >= 1){ ?>
                    <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                      <td width="455" align="left"><?php echo $row2['Name']; ?>
                        <input name="id" type="hidden" id="id" value="<?php echo $row2['Id']; ?>" /></td>
                      <td width="25" align="right"><a href="costing-labour-rates.php?Delete=<?php echo $row2['Id']; ?>"><img src="../images/icons/btn-delete.png" title="Delete" width="25" height="25" border="0" /></a></td>
                      <td width="25" align="right"><a href="costing-labour-rates.php?Id=<?php echo $row2['Id']; ?>"><img src="../images/icons/btn-edit.png" title="Edit" width="25" height="25" border="0" /></a></td>
                    </tr>
                    <?php }} ?>
                  </table>
                </div></td>
              </tr>
            </table>
          </form>
</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>