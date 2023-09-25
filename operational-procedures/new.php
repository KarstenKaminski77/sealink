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

$edit = $_GET['Edit'];

if(isset($_POST['reset'])){

	header('Location: new.php');
}

if(isset($_POST['insert']) && !empty($_POST['cat'])){

   $form_data = array(

	  'CatId' => addslashes($_POST['cat']),
		'SystemId' => $_POST['system'],
	  'Name' => addslashes($_POST['name']),
	  'OperationalProcedure' => addslashes($_POST['op'])
	);

	dbInsert('tbl_op', $form_data,$con);

}

if(isset($_POST['update'])){

   $form_data = array(

	  'CatId' => addslashes($_POST['cat']),
		'SystemId' => $_POST['system'],
	  'Name' => addslashes($_POST['name']),
	  'OperationalProcedure' => addslashes($_POST['op'])
	);

	dbUpdate('tbl_op', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);

}

if(isset($_GET['Delete'])){

	dbDelete('tbl_op', $where_clause='Id='. $_GET['Delete'],$con);
}

$query_cat = mysqli_query($con, "SELECT * FROM tbl_op_categories")or die(mysqli_error($con));

$query_form = mysqli_query($con, "SELECT * FROM tbl_op WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
SELECT
	tbl_op_categories.Category,
	tbl_op.Id,
	tbl_op.CatId,
	tbl_op.Name,
	tbl_op.OperationalProcedure
FROM
	tbl_op
INNER JOIN
    tbl_op_categories ON tbl_op_categories.Id = tbl_op.CatId";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

$sql = "
	SELECT
		Id,
		Name
	FROM
		tbl_systems
	WHERE
		Id != 3
	ORDER BY
		Id ASC
	";

$query_system = mysqli_query($con, $sql)or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>

      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />

	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

      <link rel="stylesheet" href="../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/script.js"></script>

      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>

			<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
			<script>

				tinyMCE.PluginManager.add('stylebuttons', function(editor, url) {
				  ['pre', 'p', 'code', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'].forEach(function(name){
				   editor.addButton("style-" + name, {
					   tooltip: "Toggle " + name,
						 text: name.toUpperCase(),
						 onClick: function() { editor.execCommand('mceToggleFormat', false, name); },
						 onPostRender: function() {
							 var self = this, setup = function() {
								 editor.formatter.formatChanged(name, function(state) {
									 self.active(state);
								 });
							 };
							 editor.formatter ? setup() : editor.on('init', setup);
						 }
					 })
				  });
				});

			    tinymce.init({
				mode : "specific_textareas",
			    editor_selector : "mceEditor",
			    theme: "modern",
				content_css : "http://www.seavest.co.za/eurovets/backend/styles.css",
				plugins: [
			        ["stylebuttons autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
			        ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
			        ["save table contextmenu directionality emoticons template paste importcss  responsivefilemanager"]
			    ],
			    add_unload_trigger: true,
			    schema: "html5",
			    inline: false,
			    toolbar: "undo redo | style-h1 style-h2 style-h3 style-h4 style-h5 style-h6 | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image responsivefilemanager",
			    statusbar: false,
				relative_urls:false,
			    external_filemanager_path:"/inv/tinymce/filemanager/",
			    filemanager_title:"Responsive Filemanager" ,
			    external_plugins: { "filemanager" : "/inv/tinymce/filemanager/plugin.min.js"},
			});

			</script>

   </head>
   <body>

      <!-- Banner -->
      <div id="logo">
         <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
      </div>
      <!-- End Banner -->

      <!-- Navigatiopn -->
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->

      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Miscellaneous</a></li>
            <li><a href="#">S.O.P</a></li>
            <li><a href="#">Create New</a></li>
            <li></li>
         </ul>
      </div>
      <!-- End Breadcrumbs -->

      <!-- Main Form -->
      <div id="main-wrapper">

				<form action="index.php" method="post" enctype="multipart/form-data" name="form1" id="form2" style="margin-left:30px">
					<div id="list-border">
				    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
				        <tr>
				            <td colspan="4" valign="middle" class="td-header">&nbsp; Category</td>
				        </tr>
				        <tr>
				            <td colspan="4" valign="middle" class="td-right">
				                <select name="cat" class="tarea-100" id="cat">
				                    <option value="">Select a Catdgory...</option>
				                    <?php while($row_cat = mysqli_fetch_array($query_cat)){ ?>
				                        <option value="<?php echo $row_cat['Id']; ?>" <?php if($row_cat[ 'Id'] == $row_form[ 'CatId']){ echo 'selected="selected"'; } ?>>
				                            <?php echo $row_cat['Category']; ?>
				                        </option>
				                        <?php } ?>
				                </select>
				            </td>
				        </tr>
								<tr>
				            <td colspan="4" valign="middle" class="td-header">&nbsp; System</td>
				        </tr>
				        <tr>
				            <td colspan="4" valign="middle" class="td-right">
											<select name="system" class="tarea-100" data-parsley-required>
													<option value="">Select one...</option>
													<?php while($row_system = mysqli_fetch_array($query_system)){ ?>
															<option value="<?php echo $row_system['Id']; ?>">
																	<?php echo $row_system['Name']; ?>
															</option>
													<?php } ?>
												</select>
				            </td>
				        </tr>
				        <tr>
				            <td colspan="4" valign="middle" class="td-header">&nbsp; Name</td>
				        </tr>
				        <tr>
				            <td colspan="4" valign="middle" class="td-right">
				                <input name="name" type="text" class="tarea-100" id="name" value="<?php echo stripslashes($row_form['Name']); ?>" />
				            </td>
				        </tr>
				        <tr>
				            <td colspan="4" valign="middle" class="td-header">&nbsp; Procedure</td>
				        </tr>
				        <tr>
				            <td colspan="4" valign="middle" class="td-right" style="padding:0">
				                <textarea name="op" cols="45" rows="20" class="tarea-100 mceEditor" id="op">
				                    <?php echo stripslashes($row_form['OperationalProcedure']); ?>
				                </textarea>
				            </td>
				        </tr>
				    </table>
					</div>
					<table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
						<tr>
							<td align="right">

								<?php if(isset($_GET['Edit'])){ ?>
										<input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
										<input name="update" type="submit" class="btn-new" id="update" value="Update" />
								<?php } else { ?>
										<input name="insert" type="submit" class="btn-new" id="insert" value="Insert" />
								<?php } ?>

							</td>
						</tr>
					</table>
				</form>

      </div>
      <!-- End Main Form -->

      <!-- Footer -->
   <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->

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
