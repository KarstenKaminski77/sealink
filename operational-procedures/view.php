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

$view = $_GET['View'];

$query_cat = mysqli_query($con, "SELECT * FROM tbl_op_categories")or die(mysqli_error($con));

$query_form = mysqli_query($con, "SELECT * FROM tbl_op WHERE Id = '$view'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);
$num_rows = mysqli_num_rows($query_form);

system_select();

$sql_where = system_parameters('tbl_op');

$query_list = "
	SELECT
		tbl_op_categories.Category,
		tbl_op.Id,
		tbl_op.CatId,
		tbl_op.Name,
		tbl_op.OperationalProcedure,
		tbl_op.Updated,
		tbl_systems.Name AS SystemName
	FROM
		tbl_op
	JOIN
	    tbl_op_categories ON tbl_op_categories.Id = tbl_op.CatId
	JOIN
			tbl_systems ON tbl_op.SystemId = tbl_systems.Id
	WHERE
			tbl_op.MenuCat = 'SOP'
			$sql_where
";

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

   </head>
   <body>

      <!-- Banner -->
      <div id="logo">
         <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
				<?php system_dd($con); ?>
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
            <li><a href="#">View</a></li>
            <li></li>
         </ul>
      </div>
      <!-- End Breadcrumbs -->

      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="search.php">
          <input name="textfield" type="text" class="search-top" id="textfield" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->

      <!-- Main Form -->
      <div id="main-wrapper">
				<div id="list-bdr">
					<form action="" method="post" enctype="multipart/form-data" name="form1" id="form2" style="margin-left:30px">
				    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
				        <tr>
				            <td colspan="5">
				                <?php if($num_rows > 0){ ?>
				                    <div id="document">
				                        <h1><?php echo $row_form['Name']; ?></h1>
				                        <span class="op-date"><?php echo date('l d M Y', strtotime($row_form['Updated'])); ?></span>
				                        <hr class="style-one">
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
				                            <td width="207" class="td-header">System</td>
																		<td width="207" class="td-header">Category</td>
				                            <td width="502" class="td-header">Name</td>
				                            <td width="20" align="center" class="td-header">&nbsp;</td>
				                        </tr>
				                        <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
				                            <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
				                                <td>
				                                    <?php echo $row_list['SystemName']; ?>
				                                </td>
																				<td>
				                                    <?php echo $row_list['Category']; ?>
				                                </td>
				                                <td>
				                                    <?php echo $row_list['Name']; ?>
				                                </td>
				                                <td align="center">
				                                    <a href="view.php?View=<?php echo $row_list['Id']; ?>" class="view"></a>
				                                </td>
				                            </tr>
				                            <?php } ?>
				                    </table>
				                </div>
				            </td>
				        </tr>
				    </table>
				</form>
				</div>
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
