<?php 

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$edit = $_GET['Edit'];

if(isset($_POST['reset'])){
	
	header('Location: create-new.php');
}

if(isset($_POST)){
	
   $form_data = array(
	  
	  'MenuCat' => 'HES',
	  'CatId' => addslashes($_POST['cat']),
	  'Name' => addslashes($_POST['name']),
	  'OperationalProcedure' => addslashes($_POST['op'])
	);
	
}

if(isset($_POST['insert']) && !empty($_POST['cat'])){
	
	dbInsert('tbl_op', $form_data,$con);
	
}

if(isset($_POST['update'])){
		
	dbUpdate('tbl_op', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);
	
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_op', $where_clause='Id='. $_GET['Delete'],$con);
}

$query_cat = mysqli_query($con, "SELECT * FROM tbl_op_categories WHERE MenuCat = 'HES'")or die(mysqli_error($con));

$query_form = mysqli_query($con, "SELECT * FROM tbl_op WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php include('../menu.php'); ?>
      </td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="823" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
            <tr>
              <td colspan="4" bordercolor="#FFFFFF" class="combo">
              <form action="create-new.php" method="post" enctype="multipart/form-data" name="form1" id="form2" style="margin-left:30px">
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td colspan="5">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="middle" class="td-header">&nbsp; Category</td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="middle" class="td-right">
                    <select name="cat" class="tarea-new-100" id="cat">
                    <option value="">Select a Catdgory...</option>
                    <?php while($row_cat = mysqli_fetch_array($query_cat)){ ?>
                      <option value="<?php echo $row_cat['Id']; ?>" <?php if($row_cat['Id'] == $row_form['CatId']){ echo 'selected="selected"'; } ?>><?php echo $row_cat['Category']; ?></option>
                    <?php } ?>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="middle" class="td-header">&nbsp; Name</td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="middle" class="td-right"><input name="name" type="text" class="tarea-new-100" id="name" value="<?php echo stripslashes($row_form['Name']); ?>" /></td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="middle" class="td-header">&nbsp; Procedure</td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="middle" class="td-right" style="padding:0"><textarea name="op" cols="45" rows="20" class="tarea-new-100 mceEditor" id="op"><?php echo stripslashes($row_form['OperationalProcedure']); ?></textarea></td>
                  </tr>
                  <tr>
                    <td align="right"><?php if(isset($_GET['Edit'])){ ?>
                      <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
                      <input name="update" type="submit" class="btn-new" id="update" value="Update" />
                      <?php } else { ?>
                      <input name="insert" type="submit" class="btn-new" id="insert" value="Insert" />
                      <?php } ?></td>
                  </tr>
                  </table>
              </form></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
