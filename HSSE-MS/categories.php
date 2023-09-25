<?php require_once('../Connections/seavest.php'); ?>
<?php

require_once('../functions/functions.php');

$edit = $_GET['Edit'];

if(isset($_POST['reset'])){
	
	header('Location: categories.php');
}

if(isset($_POST)){
	
   $form_data = array(
	  
	  'MenuCat' => 'HES',
	  'Category' => addslashes($_POST['cat'])
	);
	
}

if(isset($_POST['insert']) && !empty($_POST['cat'])){
	
	dbInsert('tbl_op_categories', $form_data,$con);
	
}

if(isset($_POST['update'])){
	
	dbUpdate('tbl_op_categories', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);
	
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_op_categories', $where_clause='Id='. $_GET['Delete'],$con);
}

if($_SESSION['kt_login_level'] >= 1){
	
	if(isset($_SESSION['areaid'])){
		
		$areaid = $_SESSION['areaid'];
	} else {
		
		$areaid = 1;
	}
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_op_categories WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_op_categories WHERE MenuCat = 'HES'")or die(mysqli_error($con));
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">

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
              <form action="" method="post" enctype="multipart/form-data" name="form1" id="form2" style="margin-left:30px">
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="middle" class="td-header" style="padding:4px">Category</td>
                  </tr>
                  <tr>
                    <td valign="middle" class="td-right"><input name="cat" type="text" class="tarea-new-100" id="cat" value="<?php echo $row_form['Category']; ?>" /></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="right">
                    <?php if(isset($_GET['Edit'])){ ?>
                      <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
                      <input name="update" type="submit" class="btn-new" id="update" value="Update" />
                    <?php } else { ?>
                      <input name="insert" type="submit" class="btn-new" id="insert" value="Insert" />
                    <?php } ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" align="right">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2" align="right"><div id="list-border">
                      <table width="100%" border="0" cellpadding="4" cellspacing="1">
                        <tr>
                          <td class="td-header">Category</td>
                          <td width="20" align="center" class="td-header">&nbsp;</td>
                          <td width="21" align="center" class="td-header">&nbsp;</td>
                        </tr>
                        <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                        <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                          <td><?php echo $row_list['Category']; ?></td>
                          <td align="center"><a href="categories.php?Delete=<?php echo $row_list['Id']; ?>" class="delete"></a></td>
                          <td align="center"><a href="categories.php?Edit=<?php echo $row_list['Id']; ?>" class="edit"></a></td>
                        </tr>
                        <?php } ?>
                      </table>
                    </div></td>
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
  mysqli_close($con);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
  mysql_free_result($Recordset1);
  mysql_free_result($Recordset2);
?>