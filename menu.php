<?php
session_start();

require_once('Connections/inv.php');
require_once('functions/functions.php');

$con = mysqli_connect('sql15.jnb1.host-h.net','kwdaco_333','SBbB38c8Qh8','seavest_db333');

$user_id = $_SESSION['kt_login_id'];

$query_menu = "
  SELECT
	  tbl_users.Id,
	  tbl_areas.Area,
	  tbl_area_relation.AreaId
  FROM
	  (
		  (
			  tbl_users
			  LEFT JOIN tbl_area_relation ON tbl_area_relation.UserId = tbl_users.Id
		  )
		  LEFT JOIN tbl_areas ON tbl_areas.Id = tbl_area_relation.AreaId
	  )
  WHERE
	  tbl_users.Id = '$user_id'";

$menu = mysqli_query($con, $query_menu) or die(mysqli_error($con));
$row_menu = mysqli_fetch_assoc($menu);
$totalRows_menu = mysqli_num_rows($menu);

if(isset($_POST['master_area'])){

	$_SESSION['areaid'] = $_POST['master_area'];

	$areaid = $_SESSION['areaid'];
} else {

	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = "-1";
if (isset($_SESSION['areaid'])) {
  $colname_area = $_SESSION['areaid'];
}

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

?>
<link rel="stylesheet" type="text/css" href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/sdmenu/sdmenu.css" />
<script type="text/javascript" src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/sdmenu/sdmenu.js"></script>
<script type="text/javascript">

  var myMenu;
  window.onload = function() {
	  myMenu = new SDMenu("my_menu");
	  myMenu.init();
  };

  jQuery(document).ready(function() {

			  $(".various3").fancybox({
				  'width'				: 500,
				  'height'			: 230,
				  'autoScale'			: true,
				  'transitionIn'		: 'none',
				  'transitionOut'		: 'none',
				  'type'				: 'iframe',
				  'padding'           : 0,
				  'overlayOpacity'    : '0.8',
				  'overlayColor'      : 'black',

			  });



  });


</script>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-size: 16px;
}
#bg {
	background-repeat: repeat-y;
	color:#18519B;
	font-size:12px;
	font-family:arial;
	font-weight:bold;
	margin: 0px;
	padding: 0px;
	width: 200px;
}
#top{
	height:148px;
	background-color: #18519b;
	padding:0px;
	margin: 0px;
	width: 200px;
}
#menu_header {
	line-height: 31px;
	background-image: url(images/menu_header2.jpg);
	height: 31px;
	width: 180px;
	color: #FF0000;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 20px;
	font-family: arial;
	font-size: 14px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
}
#menu_header2 {
	line-height: 31px;
	background-image: url(images/menu_header.jpg);
	height: 31px;
	width: 175px;
	color: #FF0000;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 25px;
	font-family: arial;
	font-size: 14px;
	margin-top: 10px;
	margin-right: 0px;
	margin-bottom: 10px;
	margin-left: 0px;
	clear: both;
}
#sealink {
	background-image: url(<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/images/sealink.jpg);
	background-repeat: no-repeat;
	background-position: center 10px;
	width: 200px;
	margin: 0px;
	padding-top: 60px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
	text-align: center;
}
#search-bar {
	background-color: #5693E4;
	text-align: center;
	margin: 0px;
	height: 35px;
	width: 200px;
	line-height: 35px;
	padding-top: 7px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 0px;
}
form {
	padding:0px;
	margin:0px;
}
.search-field {
	font-family: Arial;
	font-size: 12px;
	color: #18519B;
	border: 1px solid #18519B;
	width: 140px;
}
.btn-go-search {
	background: url(<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/images/btn-go.jpg);
	margin: 0px;
	padding: 0px;
	height: 18px;
	width: 36px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
.btn-go-area {
	background: url(<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/images/btn-go2.jpg);
	margin: 0px;
	padding: 0px;
	height: 18px;
	width: 36px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
-->
</style>
<table height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" background="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/images/menu_header2.jpg">
<div id="bg">
  <div id="top"><span class="style1"> </span>
    <div id="sealink">
      <div>
        <form id="form1" name="form1" method="post" action="">
        <br />
        <table border="0" align="center" cellpadding="2" cellspacing="3">
            <tr>
              <td><select name="master_area" class="search-field" id="master_area">
                <option value="">Select one...</option>
                <?php
do {
?>
                <option value="<?php echo $row_menu['AreaId']?>"><?php echo $row_menu['Area']?></option>
                <?php
} while ($row_menu = mysqli_fetch_assoc($menu));
  $rows = mysqli_num_rows($menu);
  if($rows > 0) {
      mysqli_data_seek($menu, 0);
	  $row_menu = mysqli_fetch_assoc($menu);
  }
?>
              </select></td>
              <td><input name="Submit" type="submit" class="btn-go-area" value="" /></td>
            </tr>
          </table>
        </form>
        <div style=" padding-top:10px"><span class="left_menu_header"><?php echo $row_area['Area']; ?></span></div>
      </div>
    </div>
  </div>
  <div id="search-bar">
    <form id="form10" name="form10" method="post" action="search-results.php">
      <table width="176" border="0" align="center" cellpadding="2" cellspacing="3">
        <tr>
          <td><input name="search-field" type="text" class="search-field" id="search-field" /></td>
          <td><input name="button" type="submit" class="btn-go-search" id="button" value="" /></td>
        </tr>
      </table>
    </form>
  </div>
  <div style="float: left" id="my_menu" class="sdmenu">
  <?php
select_db();

	$query = mysqli_query($con, "SELECT * FROM tbl_menu_categories ORDER BY OrderBy ASC")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){

		$category_id = $row['Id'];

		$query_rs_user_menu_cat = "
		  SELECT
			  tbl_users.Username,
			  tbl_users. PASSWORD,
			  tbl_menu_relation.UserId,
			  tbl_menu_relation.MenuId,
			  tbl_menu_items.Menu,
			  tbl_menu_categories.Category,
			  tbl_menu_items.CategoryId
		  FROM
			  (
				  (
					  (
						  tbl_users
						  LEFT JOIN tbl_menu_relation ON tbl_menu_relation.UserId = tbl_users.Id
					  )
					  LEFT JOIN tbl_menu_items ON tbl_menu_items.Id = tbl_menu_relation.MenuId
				  )
				  LEFT JOIN tbl_menu_categories ON tbl_menu_categories.Id = tbl_menu_items.CategoryId
			  )
		  WHERE
			  tbl_menu_items.CategoryId = '$category_id'
		  AND tbl_menu_relation.UserId = '$user_id'";

		$rs_user_menu_cat = mysqli_query($con, $query_rs_user_menu_cat) or die(mysqli_error($con));
		$row_rs_user_menu_cat = mysqli_fetch_assoc($rs_user_menu_cat);
		$totalRows_rs_user_menu_cat = mysqli_num_rows($rs_user_menu_cat);

		if($totalRows_rs_user_menu_cat >= 1){
		?>
			<div>
			 <span><?php echo $row['Category']; ?></span>
			<?php
			$category_id = $row['Id'];

			$query2 = mysqli_query($con, "SELECT * FROM tbl_menu_items WHERE CategoryId = '$category_id' ORDER BY OrderBy ASC")or die(mysqli_error());
			while($row2 = mysqli_fetch_array($query2)){

				$menu_id = $row2['Id'];

				$query_rs_user_menu_items = "
				  SELECT
					  tbl_users.Username,
					  tbl_users. PASSWORD,
					  tbl_menu_relation.UserId,
					  tbl_menu_relation.MenuId,
					  tbl_menu_items.Menu,
					  tbl_menu_items.OrderBy
				  FROM
					  (
						  (
							  tbl_users
							  LEFT JOIN tbl_menu_relation ON tbl_menu_relation.UserId = tbl_users.Id
						  )
						  LEFT JOIN tbl_menu_items ON tbl_menu_items.Id = tbl_menu_relation.MenuId
					  )
				  WHERE
					  tbl_menu_relation.UserId = '$user_id'
				  AND tbl_menu_relation.MenuId = '$menu_id'
				  ORDER BY
					  tbl_menu_items.Id DESC";

				$rs_user_menu_items = mysqli_query($con, $query_rs_user_menu_items) or die(mysqli_error());
				$row_rs_user_menu_items = mysqli_fetch_assoc($rs_user_menu_items);
				$totalRows_rs_user_menu_items = mysqli_num_rows($rs_user_menu_items);

				if($totalRows_rs_user_menu_items >= 1){
				?>
					<a href="<?php echo $_SERVER['HOST'].'/'.$row2['Url']; ?>?menu=<?php echo $row2['Menu']; ?>" <?php if($_GET['menu'] == $row2['Menu']){ ?>style="color:#FF0000"<?php } ?>>
					<?php echo $row2['Menu']; ?>&nbsp;<?php if($row2['Counter'] != 'NULL'){$counter = $row2['Counter']; $counter($con);}?>
					</a>
				<?php
				}
			}
			?>
		</div>
		<?php
		}
	}
	?>
  </div>
</div>

<div id="menu_header2"><a href="<?php echo $_SERVER['HOST']; ?>/inv/logout.php" class="logout" style="text-decoration:none">Logout</a></div>
</div>
</td>
  </tr>
</table>
<?php
  mysqli_free_result($query);
  mysqli_free_result($query2);
  mysqli_free_result($area);
  mysqli_free_result($menu);
  mysqli_free_result($rs_user_menu_cat);
  mysqli_free_result($rs_user_menu_items);
?>
