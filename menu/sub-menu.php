<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

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

?>
<div id="sticky-anchor"></div>
<div id="sticky">
<div id='cssmenu'>
<ul class="">
<?php
$x = 0;
$query_menu_cat = "
  SELECT
	  tbl_menu_cat.Category,
	  tbl_menu_cat.URL,
	  tbl_menu_sub_cat.CatId,
	  tbl_menu_sub_cat.Id AS SubCatId,
	  tbl_menu_sub_cat.SubCat,
	  tbl_menu_links.Menu,
	  tbl_menu_links.Url,
	  tbl_menu_links.Counter,
	  tbl_menu_links.Backend,
	  tbl_menu_links.`Status`,
	  tbl_menu_user_relation.UserId
  FROM
	  tbl_menu_cat
  INNER JOIN tbl_menu_sub_cat ON tbl_menu_sub_cat.CatId = tbl_menu_cat.Id
  INNER JOIN tbl_menu_links ON tbl_menu_links.CategoryId = tbl_menu_sub_cat.Id
  INNER JOIN tbl_menu_user_relation ON tbl_menu_user_relation.MenuId = tbl_menu_links.Id
  WHERE
	  tbl_menu_user_relation.UserId = '$user_id' AND tbl_menu_cat.Id = '10'
  GROUP BY
	  tbl_menu_sub_cat.CatId
  ORDER BY
	  tbl_menu_cat.OrderBy ASC";
  
$query_menu_cat = mysqli_query($con, $query_menu_cat) or die(mysqli_error($con));
while($row_menu_cat = mysqli_fetch_assoc($query_menu_cat)){
	
	$x++;
	
	$first = '';
	
	if($x == 1){
		
		$first = 'first';
	}
?>
    <li class='active has-sub active open <?php echo $row_menu_cat['URL']; ?> <?php echo $first; ?>'><a href='#'><span><?php echo $row_menu_cat['Category']; ?></span></a>
      <ul>
      <?php
	  $catid = $row_menu_cat['CatId'];
	  
	  $query_menu_sub_cat = "
		SELECT
			tbl_menu_cat.Category,
	        tbl_menu_cat.URL,
			tbl_menu_sub_cat.CatId,
			tbl_menu_sub_cat.Id,
			tbl_menu_sub_cat.SubCat,
			tbl_menu_user_relation.UserId
		FROM
			tbl_menu_cat
		INNER JOIN tbl_menu_sub_cat ON tbl_menu_sub_cat.CatId = tbl_menu_cat.Id
		INNER JOIN tbl_menu_links ON tbl_menu_links.CategoryId = tbl_menu_sub_cat.Id
		INNER JOIN tbl_menu_user_relation ON tbl_menu_user_relation.MenuId = tbl_menu_links.Id
		WHERE
			tbl_menu_sub_cat.CatId = '$catid' AND tbl_menu_sub_cat.Id = '29'
		AND tbl_menu_user_relation.UserId = '$user_id'
		GROUP BY
			tbl_menu_sub_cat.SubCat
		ORDER BY
			tbl_menu_sub_cat.OrderBy ASC,
			tbl_menu_links.OrderBy ASC";
		
		$query_menu_sub_cat = mysqli_query($con, $query_menu_sub_cat)or die(mysqli_error($con));
		while($row_sub_cat = mysqli_fetch_array($query_menu_sub_cat)){ 
		
	  ?>
      
         <li class='has-sub open <?php echo $row_sub_cat['URL']; ?>'><a href='#'><span><?php echo $row_sub_cat['SubCat']; ?></span></a>
         
         <?php
		  if($row_sub_cat['Id'] == 27){
			  
			  echo '<ul>';
			  
			  $query_tech = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));
			  while($row_tech = mysqli_fetch_array($query_tech)){
				  
				  ?>
				  
                   <li>
                     <a class="menu_item" href='http://www.seavest.co.za/inv/technicians/profiles/index.php?ProfileId=<?php echo $row_tech['Id']; ?>'>
                     <span><?php echo $row_tech['Name']; ?></span></a>
                   </li>
				  
                  <?php
				  
			  }
			  
			  echo '</ul>';
			  
			  
		  } else {
		 ?>
            <ul>
            <?php
			
			$subcatid = $row_sub_cat['Id'];
			
			$query_items = "
			  SELECT
				  tbl_menu_cat.Category,
				  tbl_menu_sub_cat.CatId,
				  tbl_menu_sub_cat.Id,
				  tbl_menu_sub_cat.SubCat,
				  tbl_menu_links.Menu,
				  tbl_menu_links.Url,
				  tbl_menu_links.Counter,
				  tbl_menu_links.Backend,
				  tbl_menu_links.`Status`,
				  tbl_menu_links.Counter,
				  tbl_menu_user_relation.UserId
			  FROM
				  tbl_menu_cat
			  INNER JOIN tbl_menu_sub_cat ON tbl_menu_sub_cat.CatId = tbl_menu_cat.Id
			  INNER JOIN tbl_menu_links ON tbl_menu_links.CategoryId = tbl_menu_sub_cat.Id
			  INNER JOIN tbl_menu_user_relation ON tbl_menu_user_relation.MenuId = tbl_menu_links.Id
			  WHERE
				  tbl_menu_sub_cat.Id = '$subcatid'
			  AND tbl_menu_user_relation.UserId = '$user_id'
			  ORDER BY
				  tbl_menu_sub_cat.OrderBy ASC,
				  tbl_menu_links.OrderBy ASC";
				  
			  $query_items = mysqli_query($con, $query_items)or die(mysqli_error($con));
			  while($row_items = mysqli_fetch_array($query_items)){
				  
				  if($row_items['Menu'] == $_GET['Menu']){
					  
					  $class = 'class="active"';
					  
				  } else {
					  
					  $class = '';
				  }
			
			?>
               <li>
                 <a class="menu_item" href='<?php echo $_SERVER['HOST'] .'/'. $row_items['Url']; ?>'>
                 <span><?php echo $row_items['Menu']; ?></span>
                 <?php if($row_items['Counter'] != 'NULL'){
					 
					 $row_items['Counter']($con);
				 }
				 ?></a>
               </li>
            <?php } ?>
            
            </ul>
            
         <?php } // End if 27 ?>
         </li>
         <?php } ?>
         
      </ul>
    </li>
<?php } ?>

  <li><a href='http://www.seavest.co.za/inv/welcome.php'><span>Main Menu</span></a></li>
  <li class='last'><a href='<?php echo $_SERVER['HOST'] .'?Logout'; ?>'><span>Logout</span></a></li>
</ul>
</div>
</div>