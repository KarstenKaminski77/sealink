<?php
$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

require_once('../functions/functions.php');

$form_data = array(
	'Name' => $_POST['name'],
	'Cell' => $_POST['mobile'],
	'Active' => $_POST['active']
);

////////////////////
/// INSERT AREA ///
//////////////////

if(isset($_POST['insert'])){
		
	dbInsert('tbl_emergency_cells', $form_data,$con);
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
	
}

////////////////////
/// UPDATE AREA ///
//////////////////

if(isset($_POST['update'])){
	
	dbUpdate('tbl_emergency_cells', $form_data, $where_clause="Id = '". $_GET['Id'] ."'", $con);
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
	
}

////////////////////
/// DELETE AREA ///
//////////////////

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_emergency_cells', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
	
}

//////////////////////////////
/// UPDATE POPULATE QUERY ///
////////////////////////////

$id = $_GET['Id'];

$query_form = mysqli_query($con, "SELECT * FROM tbl_emergency_cells WHERE Id = '$id'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_emergency_cells ORDER BY Name ASC")or die(mysqli_error($con));
$row_list = mysqli_fetch_array($query_list);

if(isset($_POST['reset'])){
	
	header('Location: recipients.php');
}
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
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <form name="form2" method="post" action="" style="margin-left:30px">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                <div id="list-brdr" style="display:block">
                <table width="100%" border="0" cellpadding="1" cellspacing="1">
                  <tr>
                    <td class="td-header-cell">Name</td>
                    <td width="120" class="td-header-cell">Mobile No.</td>
                    <td width="120" class="td-header-cell">Active</td>
                  </tr>
                  <tr>
                    <td class="td-right"><input name="name" type="text" class="tarea-new-100" id="name" value="<?php echo $row_form['Name']; ?>"></td>
                    <td class="td-right"><input name="mobile" type="text" class="tarea-new-100" id="mobile" value="<?php echo $row_form['Cell']; ?>"></td>
                    <td valign="middle" class="td-right">
                    <input type="radio" name="active" value="1" id="active_0" <?php if($row_form['Active'] == 1){ echo 'checked="checked"'; } ?>> Yes</label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="radio" name="active" value="0" id="active_1" <?php if($row_form['Active'] == 0){ echo 'checked="checked"'; } ?>> No</label>
                      <br>
                    </p></td>
                  </tr>
                </table>
                </div>
                </td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right">
				<?php if(isset($_GET['Id'])){ ?>
                  <input name="reset" type="submit" class="btn-grad" id="reset" value="Reset" />
                  <input name="update" type="submit" class="btn-grad" id="update" value="Update" />
                <?php } else { ?>
                  <input name="insert" type="submit" class="btn-grad" id="insert" value="Insert" />
                <?php } ?></td>
              </tr>
          </table>
            <br>
            <br>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><div id="list-brdr" style="display:block">
                  <table width="100%" cellpadding="1" cellspacing="1">
                    <?php if(mysqli_num_rows($query_list) >= 1){ ?>
                    <tr>
                      <td align="left" class="td-header-cell">Name</td>
                      <td width="120" align="left" class="td-header-cell">Mobile No.</td>
                      <td width="60" align="left" class="td-header-cell">Active</td>
                      <td width="25" align="right" class="td-header">&nbsp;</td>
                      <td width="25" align="right" class="td-header">&nbsp;</td>
                    </tr>
                    <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                    <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                      <td align="left" class="td-data-cell"><?php echo $row_list['Name']; ?></td>
                      <td align="left" class="td-data-cell"><?php echo $row_list['Cell']; ?></td>
                      <td align="left" class="td-data-cell"><?php if($row_list['Active'] == 1){ echo 'Yes'; } else { echo 'No'; } ?></td>
                      <td align="right"><a href="recipients.php?Delete=<?php echo $row_list['Id']; ?>"><img src="../images/icons/btn-delete.png" title="Delete" width="25" height="25" border="0" /></a></td>
                      <td align="right"><a href="recipients.php?Id=<?php echo $row_list['Id']; ?>"><img src="../images/icons/btn-edit.png" title="Edit" width="25" height="25" border="0" /></a></td>
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