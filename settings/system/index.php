<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

$edit = $_GET['Edit'];

if(isset($_POST['reset'])){
	
	header('Location: user-accounts.php');
}

if(isset($_POST)){
	
   $form_data = array(
	  
	  'Name' => addslashes($_POST['name']),
	  'Username' => addslashes($_POST['username']),
	  'Password' => addslashes($_POST['password']),
	  'Email' => $_POST['email'],
	  'EditHistory' => $_POST['history'],
	  'UserLevel' => '1'
	);
}

// INSERT
if(isset($_POST['insert']) && !empty($_POST['cat'])){
		
	dbInsert('tbl_users', $form_data,$con);
	
	$query = mysqli_query($con, "SELECT * FROM tbl_users ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$userid = $row['Id'];
	
	// Areas
	for($i=0;$i<count($_POST['area']);$i++){
				
		$areaid = $_POST['area'][$i];
		
		mysqli_query($con, "INSERT INTO tbl_area_relation (AreaId,UserId) VALUES ('$areaid','$userid')")or die(mysqli_error($con));
	}
	
	// Menu Items
	for($i=0;$i<count($_POST['menu']);$i++){
				
		$menuid = $_POST['menu'][$i];
		
		mysqli_query($con, "INSERT INTO tbl_menu_relation (MenuId,UserId) VALUES ('$menuid','$userid')")or die(mysqli_error($con));
	}
}

// UPDATE
if(isset($_POST['update'])){
		
	dbUpdate('tbl_users', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);
	
	$userid = $_GET['Edit'];
	
	mysqli_query($con, "DELETE FROM tbl_area_relation WHERE UserId = '$userid'")or die(mysqli_error($con));
	mysqli_query($con, "DELETE FROM tbl_menu_relation WHERE UserId = '$userid'")or die(mysqli_error($con));
	
	
	// Areas
	for($i=0;$i<count($_POST['area']);$i++){
				
		$areaid = $_POST['area'][$i];
		
		mysqli_query($con, "INSERT INTO tbl_area_relation (AreaId,UserId) VALUES ('$areaid','$userid')")or die(mysqli_error($con));
	}
	
	// Menu Items
	for($i=0;$i<count($_POST['menu']);$i++){
				
		$menuid = $_POST['menu'][$i];
		
		mysqli_query($con, "INSERT INTO tbl_menu_relation (MenuId,UserId) VALUES ('$menuid','$userid')")or die(mysqli_error($con));
	}
	
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_users', $where_clause='Id='. $_GET['Delete'],$con);
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_users ORDER BY Name  ASC")or die(mysqli_error($con));

$query_areas = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC")or die(mysqli_error($con));

$query_user_menu = "
  SELECT
	  tbl_menu_items.Id,
	  tbl_menu_items.Menu,
	  tbl_menu_items.Backend,
	  tbl_menu_items.CategoryId,
	  tbl_menu_relation.UserId,
	  tbl_menu_categories.Category
  FROM
	  tbl_menu_items
  LEFT JOIN tbl_menu_relation ON (
	  tbl_menu_relation.MenuId = tbl_menu_items.Id
	  AND tbl_menu_relation.UserId = 0123456789
  )
  INNER JOIN tbl_menu_categories ON tbl_menu_categories.Id = tbl_menu_items.CategoryId
  ORDER BY
	  tbl_menu_categories.Category ASC";
	
$query_user_menu = mysqli_query($con, $query_user_menu)or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../../menu/script.js"></script>
      
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
      </div>
      <!-- End Banner -->
      
      <!-- Navigatiopn -->
      <?php include('../../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
   <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Welcome</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="">
          <input name="textfield" type="text" class="search-top" id="textfield" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
          <form action="" method="post" enctype="multipart/form-data" name="form1" id="form2" style="margin-left:30px">
            <div id="list-brdr-supprt">
              <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td width="120" class="td-left">Name</td>
                  <td class="td-right">
                    <input name="name" type="text" class="tarea-new-100" id="name" value="<?php echo $row_form['Name']; ?>">
                  </td>
                  <td width="120" class="td-left">Email</td>
                  <td class="td-right">
                    <input name="email" type="text" class="tarea-new-100" id="email" value="<?php echo $row_form['Email']; ?>">
                  </td>
                </tr>
                <tr>
                  <td class="td-left">Username</td>
                  <td class="td-right">
                    <input name="username" type="text" class="tarea-new-100" id="username" value="<?php echo $row_form['Username']; ?>">
                  </td>
                  <td class="td-left">Password</td>
                  <td class="td-right">
                    <input name="password" type="text" class="tarea-new-100" id="password" value="<?php echo $row_form['Password']; ?>">
                  </td>
                </tr>
                <tr>
                  <td class="td-left">Edit History</td>
                  <td colspan="3" class="td-right">
                    <table border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td>
                          <input type="radio" name="history" id="radio" value="1" <?php if($row_form[ 'EditHistory']==1 ){ echo 'checked="checked"'; } ?>></td>
                        <td class="combo-right">
                          <label for="radio">Yes</label>
                        </td>
                        <td width="10">&nbsp;</td>
                        <td>
                          <input type="radio" name="history" id="radio2" value="0" <?php if($row_form[ 'EditHistory']==0 ){ echo 'checked="checked"'; } ?>></td>
                        <td class="combo-right">
                          <label for="radio2">No</label>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="td-left">Areas</td>
                  <td colspan="3" class="td-right">
          
                    <?php 
					
					$x = 0 ; 
					
					while($row_areas = mysqli_fetch_array($query_areas)){
						
						$areaid = $row_areas[ 'Id']; 
						$checked='' ; 
						
						$query = mysqli_query($con, "SELECT * FROM tbl_area_relation WHERE UserId = '$edit' AND AreaId = '$areaid'")or die(mysqli_error($con)); 
						
						if(mysqli_num_rows($query)>= 1){
							
							$checked = 'checked="checked"';
						}
						
						$x++;
					?>
                    <table width="100%" border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td width="20" align="center">
                          <input name="area[]" type="checkbox" id="area-<?php echo $x; ?>" value="<?php echo $row_areas['Id']; ?>" <?php echo $checked; ?>></td>
                        <td class="combo-right">
                          <label for="area-<?php echo $x; ?>">
                            <?php echo $row_areas[ 'Area']; ?>
                          </label>
                        </td>
                      </tr>
                    </table>
                    <?php } ?>
          
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="td-left">Menu Items</td>
                  <td colspan="3" class="td-right">
          
                    <?php 
					$x = 0;
					
					$_SESSION[ 'cat']='' ; 
					
					while($row_user_menu = mysqli_fetch_array($query_user_menu)){
						
						$x++; $checked='' ; 
						$menuid = $row_user_menu[ 'Id']; 
						
						$query= mysqli_query($con, "SELECT * FROM tbl_menu_relation WHERE UserId = '$edit' AND MenuId = '$menuid'")or die(mysqli_error($con)); 
						
						if(mysqli_num_rows($query)>= 1){
							
							$checked = 'checked="checked"';
						}
						
						if($x == 1){
							
							$class = 'style="margin-bottom:10px"';
							
						} else {
							
							$class = 'style="margin-bottom:10px; margin-top:10px; overflow: hidden; float: left; width: 100%"';
						}
					?>
          
                    <?php if($row_user_menu[ 'Category'] != $_SESSION[ 'cat']){ ?>
                    <div <?php echo $class; ?>>
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td class="td-left">
                            <?php echo $row_user_menu[ 'Category']; ?> </td>
                        </tr>
                      </table>
                    </div>
                    <?php } ?>
                    <div style="width:33%; float:left">
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="20" align="center">
                            <input name="menu[]" type="checkbox" id="menu-<?php echo $x; ?>" value="<?php echo $row_user_menu['Id']; ?>" <?php echo $checked; ?>></td>
                          <td class="combo-right">
                            <label for="menu-<?php echo $x; ?>">
                              <?php echo $row_user_menu[ 'Menu']; ?>
                            </label>
                          </td>
                        </tr>
                      </table>
                    </div>
                    <?php $_SESSION[ 'cat'] = $row_user_menu[ 'Category']; } ?>
          
                  </td>
                </tr>
              </table>
            </div>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td align="right">
                  <?php if(isset($_GET[ 'Edit'])){ ?>
                  <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
                  <input name="update" type="submit" class="btn-new" id="update" value="Update" />
                  <?php } else { ?>
                  <input name="insert" type="submit" class="btn-new" id="insert" value="Insert" />
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
              </tr>
              <tr>
                <td align="right">
                  <div id="list-border">
                    <table width="100%" border="0" cellpadding="4" cellspacing="1">
                      <tr>
                        <td width="207" class="td-header">Category</td>
                        <td width="249" class="td-header">Name</td>
                        <td width="250" class="td-header">Password</td>
                        <td width="20" align="center" class="td-header">&nbsp;</td>
                        <td width="21" align="center" class="td-header">&nbsp;</td>
                      </tr>
                      <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                      <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                        <td>
                          <?php echo $row_list[ 'Name']; ?>
                        </td>
                        <td>
                          <?php echo $row_list[ 'Username']; ?>
                        </td>
                        <td>
                          <?php echo $row_list[ 'Password']; ?>
                        </td>
                        <td align="center">
                          <a href="user-accounts.php?Delete=<?php echo $row_list['Id']; ?>" class="delete"></a>
                        </td>
                        <td align="center">
                          <a href="user-accounts.php?Edit=<?php echo $row_list['Id']; ?>" class="edit"></a>
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
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
</body>
</html>
<?php mysqli_close($con); ?>