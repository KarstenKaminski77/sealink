<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

$edit = $_GET['Edit'];

if(isset($_POST['reset'])){
	
	header('Location: assets.php');
}

   $edit = $_GET['Edit'];
   
	if(isset($_POST)){	   
				
	   $form_data = array(
		   'CatId' => $_POST['cat'],
		   'Company' => $_POST['company'],
		   'Asset' => $_POST['asset'],
		);
	}
	
	// Insert	
	if(isset($_POST['insert'])){
		
		dbInsert('tbl_sm_assets', $form_data,$con);
						
		header('Location: assets.php');
		
	}
		
	// Update
	$catid = $_GET['Edit'];
	
	if(isset($_POST['update'])){
		
		dbUpdate('tbl_sm_assets', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);
				
	}
	
	// Delete
	if(isset($_GET['Delete'])){
		
		dbDelete('tbl_sm_assets', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
		
		header('Location: assets.php');
		
	}
	
	if(isset($_GET['Delete-SubCat'])){
		
		dbDelete('tbl_sla_subcat', $where_clause="Id = '". $_GET['Delete-SubCat'] ."'",$con);
		
		$param = '';
		
		if(isset($_GET['Edit'])){
			
			$param = '?Edit='. $_GET['Edit'];
		}
		
		header('Location: assets.php'.$param);
	}
	
	if(isset($_GET['Delete-Email'])){
		
		dbDelete('tbl_sla_email', $where_clause="Id = '". $_GET['Delete-Email'] ."'",$con);
		
		$param = '';
		
		if(isset($_GET['Edit'])){
			
			$param = '?Edit='. $_GET['Edit'];
		}
		
		header('Location: assets.php'.$param);
	}	
	
	$query_list = "
	  SELECT
		  tbl_sm_assets.Id,
		  tbl_sm_assets.CatId,
		  tbl_sm_assets.Company,
		  tbl_sm_assets.Asset,
		  tbl_sm_cat.Cat,
		  tbl_companies.`Name`
	  FROM
		  tbl_sm_assets
	  INNER JOIN tbl_sm_cat ON tbl_sm_assets.CatId = tbl_sm_cat.Id
	  INNER JOIN tbl_companies ON tbl_sm_assets.Company = tbl_companies.Id
	  ORDER BY tbl_companies.`Name` ASC, tbl_sm_cat.Cat ASC";	
	
	$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query_list);
		
	$query_form = mysqli_query($con, "SELECT * FROM tbl_sm_assets WHERE Id = '$edit'")or die(mysqli_error($con));
	$row_form = mysqli_fetch_array($query_form);   
	
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
      
	  <script type="text/javascript" src="../../tinymce/js/tinymce/tinymce.min.js"></script>
	  <script type="text/javascript">
      tinymce.init({
          mode : "specific_textareas",
          editor_selector : "mceEditor",
          theme: "modern",
		  statusbar: false,
          plugins: [
              "advlist autolink lists link image charmap print preview anchor",
              "searchreplace visualblocks code fullscreen",
              "insertdatetime media table contextmenu paste"
          ],
          toolbar: "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
      });
      

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
            <li><a href="#">Settings</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Assets</a></li>
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
      <form action="" method="post" enctype="multipart/form-data" name="form1" id="form2">
       <table width="100%" border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td colspan="2">
            <div id="form-border" style="margin-bottom: 5px">
              <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td class="td-left">Category</td>
                  <td class="td-right">
                  <select name="cat" class="tarea-100" id="cat">
                    <option value="">Select one...</option>
                    <?php 
						  $query_cat = mysqli_query($con, "SELECT * FROM tbl_sm_cat ORDER BY Cat ASC")or die(mysqli_error($con));
						  while($row_cat = mysqli_fetch_array($query_cat)){
							  
							  $selected = '';
							  
							  if($row_cat['Id'] == $row_subcat['CatId']){
								  
								  $selected = 'selected="selected"';
							  }
						?>
                    <option value="<?php echo $row_cat['Id']; ?>" <?php echo $selected; ?>><?php echo $row_cat['Cat']; ?></option>
                    <?php } ?>
                  </select></td>
                </tr>
                <tr>
                  <td class="td-left">Oil Company</td>
                  <td class="td-right"><select name="company" class="tarea-100" id="company">
                    <option value="">Select one...</option>
                    <?php 
						  $query_companies = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC")or die(mysqli_error($con));
						  while($row_companies = mysqli_fetch_array($query_companies)){
							  
							  $selected = '';
							  
							  if($row_companies['Id'] == $row_subcat['CompanyId']){
								  
								  $selected = 'selected="selected"';
							  }
						?>
                    <option value="<?php echo $row_companies['Id']; ?>" <?php echo $selected; ?>><?php echo $row_companies['Name']; ?></option>
                    <?php } ?>
                  </select></td>
                </tr>
                <tr>
                  <td width="130" class="td-left">Asset</td>
                  <td class="td-right"><input name="asset" type="text" class="tarea-100" id="asset" value="<?php echo $row_form['Asset']; ?>" /></td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="right">
            <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
            <?php if(isset($_GET[ 'Edit'])){ ?>
            <input name="update" type="submit" class="btn-new" id="update" value="Update" />
            <?php } else { ?>
            <input name="insert" type="submit" class="btn-new" id="insert" value="Insert" />
            <?php } ?>
          </td>
        </tr>

        <?php if($numrows>= 1){ ?>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="list-border">
              <table width="100%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                  <td width="196" class="td-header">Oil Company</td>
                  <td width="196" class="td-header">Category</td>
                  <td width="426" class="td-header">Asset</td>
                  <td width="20" class="td-header-right">&nbsp;</td>
                  <td width="20" class="td-header-right">&nbsp;</td>
                </tr>
                <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                  <td><?php echo $row_list['Name']; ?></td>
                  <td><?php echo $row_list['Cat']; ?></td>
                  <td><?php echo $row_list['Asset']; ?></td>
                  <td align="center">
                    <a href="assets.php?Delete=<?php echo $row_list['Id']; ?>" title="Delete" class="delete" onclick = "if (!confirm('Delete <?php echo $row_list['Category']; ?>?')){ return false; }"></a>
                  </td>
                  <td align="center">
                    <a href="assets.php?Edit=<?php echo $row_list['Id']; ?>" title="Edit" class="edit"></a>
                  </td>
                </tr>
                <?php } ?>
              </table>
            </div>
          </td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
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