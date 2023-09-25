<?php 

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$view = $_GET['View'];

$query_cat = mysqli_query($con, "SELECT * FROM tbl_op_categories")or die(mysqli_error($con));

$query_form = mysqli_query($con, "SELECT * FROM tbl_op WHERE Id = '$view' AND MenuCat = 'HES'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);
$num_rows = mysqli_num_rows($query_form);

$query_list = "
SELECT
	tbl_op_categories.Category,
	tbl_op.Id,
	tbl_op.CatId,
	tbl_op.Name,
	tbl_op.OperationalProcedure,
	tbl_op.Updated
FROM
	tbl_op
INNER JOIN 
    tbl_op_categories ON tbl_op_categories.Id = tbl_op.CatId WHERE tbl_op.MenuCat = 'HES'";

$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));

require_once 'simple_html_dom.php';

if(isset($_GET['View'])){
	
	$html = str_get_html(stripslashes($row_form['OperationalProcedure']));
	
	$toc = '';
	$last_level = 0;
	
	foreach($html->find('h1,h2,h3,h4,h5,h6') as $h){
		$innerTEXT = trim($h->innertext);
		$id =  str_replace(' ','_',$innerTEXT);
		$h->id= $id; // add id attribute so we can jump to this element
		$level = intval($h->tag[1]);
	
		if($level > $last_level)
			$toc .= "<ol>";
		else{
			$toc .= str_repeat('</li></ol>', $last_level - $level);
			$toc .= '</li>';
		}
	
		$toc .= "<li><a href='#{$id}'>{$innerTEXT}</a>";
	
		$last_level = $level;
	}
	
	$toc .= str_repeat('</li></ol>', $last_level);
	$html_with_toc = $toc . '<hr class="style-one">' . $html->save();
}
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SEAVEST AFRICA TRADING CC</title>
    
    <link href="../styles/layout.css" rel="stylesheet" type="text/css" />
    <link href="../styles/fonts.css" rel="stylesheet" type="text/css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    
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
        <td valign="top">
          <table width="750" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center">
                <table width="823" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
                  </tr>
                  <tr>
                    <td colspan="4" bordercolor="#FFFFFF" class="combo">
                      <form action="" method="post" enctype="multipart/form-data" name="form1" id="form2" style="margin-left:30px">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                          <tr>
                            <td colspan="5">
                              <?php if($num_rows > 0){ ?>
                                <div id="document">
                                <h1><?php echo $row_form['Name']; ?></h1>
								<?php echo $html_with_toc; ?>
                                </div>
                              <?php } ?> 
                            </td>
                          </tr>
                          <tr>
                            <td align="right">
                              <div id="list-border">
                                <table width="100%" border="0" cellpadding="4" cellspacing="1">
                                  <tr>
                                    <td width="207" class="td-header">Category</td>
                                    <td width="502" class="td-header">Name</td>
                                    <td width="20" align="center" class="td-header">&nbsp;</td>
                                    <td width="20" align="center" class="td-header">&nbsp;</td>
                                  </tr>
                                  <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                                  <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                                    <td><?php echo $row_list['Category']; ?></td>
                                    <td><?php echo $row_list['Name']; ?></td>
                                    <td align="center"><a href="word.php?Doc=<?php echo $row_list['Id']; ?>" class="ms-word"></a></td>
                                    <td align="center"><a href="view.php?View=<?php echo $row_list['Id']; ?>" class="view"></a></td>
                                  </tr>
                                  <?php } ?>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </form>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
?>