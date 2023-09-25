<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

$edit = $_GET['Edit'];

if(isset($_POST['reset'])){
	
	header('Location: sla-categories.php');
}

   $edit = $_GET['Edit'];
   
	if(isset($_POST)){	   
				
	   $form_data = array(
		   'Category' => $_POST['cat'],
		);
	}
	
	// Insert	
   if(isset($_POST['add']) && !isset($_GET['Edit'])){
	   
	   mysqli_query($con, "INSERT INTO tbl_sla_subcat (CatId) VALUES ('-1')")or die(mysqli_error($con));
   }

   if(isset($_POST['add2']) && !isset($_GET['Edit'])){
	   
	   mysqli_query($con, "INSERT INTO tbl_sla_email (CatId) VALUES ('-1')")or die(mysqli_error($con));
   }

	if(isset($_POST['insert'])){
		
		dbInsert('tbl_sla_cat', $form_data,$con);
		
		$catid = last_id($con, 'tbl_sla_cat');
		
		for($i=0;$i<count($_POST['cat-id']);$i++){
			
			$company = $_POST['company'][$i];
			$subcat = $_POST['sub-cat'][$i];
			$duration = $_POST['duration'][$i];
			
			mysqli_query($con, "INSERT INTO tbl_sla_subcat (CompanyId,CatId,SubCat,Duration) 
			VALUES ('$company','$catid','$subcat','$duration')")or die(mysqli_error($con));
		}
		
		for($i=0;$i<count($_POST['email-id']);$i++){
			
			$name = $_POST['name'][$i];
			$email = $_POST['email'][$i];
			$content = $_POST['content'];
			
			mysqli_query($con, "INSERT INTO tbl_sla_email (CatId,Name,Email,Content) VALUES ('$catid','$name','$email','$content')")or die(mysqli_error($con));
		
		}
		
		mysqli_query($con, "DELETE FROM tbl_sla_email WHERE CatId = '-1'")or die(mysqli_error($con));
		
		header('Location: sla-categories.php');
		
	}
		
	// Update
	$catid = $_GET['Edit'];
	
    if(isset($_POST['add']) && isset($_GET['Edit'])){
	   
	    mysqli_query($con, "INSERT INTO tbl_sla_subcat (CatId) VALUES ('$catid')")or die(mysqli_error($con));
    }
	
    if(isset($_POST['add2']) && isset($_GET['Edit'])){
	   
	    mysqli_query($con, "INSERT INTO tbl_sla_email (CatId) VALUES ('$catid')")or die(mysqli_error($con));
    }

	if(isset($_POST['update'])){
		
		dbUpdate('tbl_sla_cat', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);
				
		for($i=0;$i<count($_POST['sub-cat']);$i++){
			
			$company = $_POST['company'][$i];
			$subcat = $_POST['sub-cat'][$i];
			$duration = $_POST['duration'][$i];
			$subcatid = $_POST['cat-id'][$i];
			
			mysqli_query($con, "UPDATE tbl_sla_subcat SET CompanyId = '$company', CatId = '$catid', SubCat = '$subcat', 
			Duration = '$duration' WHERE Id = '$subcatid'")or die(mysqli_error($con));
		}
				
		for($i=0;$i<count($_POST['email-id']);$i++){
			
			$catid = $_GET['Edit'];
			$emailid = $_POST['email-id'][$i];
			$name = $_POST['name'][$i];
			$email = $_POST['email'][$i];
			$content = $_POST['content'];
			
			mysqli_query($con, "UPDATE tbl_sla_email SET CatId = '$catid', Name = '$name', Email = '$email', Content = '$content' WHERE Id = '$emailid'")or die(mysqli_error($con));
			
			$mailid = $_POST['email-id'][$i];
			
			mysqli_query($con, "DELETE FROM tbl_sla_areas WHERE MailId = '$mailid'")or die(mysqli_error($con));
			
			for($x=0;$x<count($_POST[$mailid]);$x++){
				
				$areaid = $_POST[$mailid][$x];
				
				mysqli_query($con, "INSERT INTO tbl_sla_areas (CatId,AreaId, MailId) VALUES ('$catid','$areaid','$mailid')")or die(mysqli_error($con));
			}
				
		}
						
	}
	
	// Delete
	if(isset($_GET['Delete'])){
		
		dbDelete('tbl_sla_cat', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
		
		header('Location: sla-categories.php');
		
	}
	
	if(isset($_GET['Delete-SubCat'])){
		
		dbDelete('tbl_sla_subcat', $where_clause="Id = '". $_GET['Delete-SubCat'] ."'",$con);
		
		$param = '';
		
		if(isset($_GET['Edit'])){
			
			$param = '?Edit='. $_GET['Edit'];
		}
		
		header('Location: sla-categories.php'.$param);
	}
	
	if(isset($_GET['Delete-Email'])){
		
		dbDelete('tbl_sla_email', $where_clause="Id = '". $_GET['Delete-Email'] ."'",$con);
		
		$param = '';
		
		if(isset($_GET['Edit'])){
			
			$param = '?Edit='. $_GET['Edit'];
		}
		
		header('Location: sla-categories.php'.$param);
	}		
	
	$query_list = mysqli_query($con, "SELECT * FROM tbl_sla_cat ORDER BY Category ASC")or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query_list);
		
	$query_form = mysqli_query($con, "SELECT * FROM tbl_sla_cat WHERE Id = '$edit'")or die(mysqli_error($con));
	$row_form = mysqli_fetch_array($query_form);   
	
	if(isset($_GET['Edit'])){
		
		$catid = $_GET['Edit'];
		
	} else {
		
		$catid = '-1';
	}
	
	$query_subcat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CatId = '$catid' ORDER BY CompanyId ASC")or die(mysqli_error($con));
	
	$query_content = mysqli_query($con, "SELECT * FROM tbl_sla_email WHERE CatId = '$catid'")or die(mysqli_error($con));
	$row_content = mysqli_fetch_array($query_content);
	
	$query_mail = mysqli_query($con, "SELECT * FROM tbl_sla_email WHERE CatId = '$catid'")or die(mysqli_error($con));
	
	$query_areas = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC")or die(mysqli_error($con));

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
            <li><a href="#">SLA</a></li>
            <li><a href="#">SLA Categories</a></li>
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
                  <td width="130" class="td-left">SLA Category</td>
                  <td class="td-right"><input name="cat" type="text" class="tarea-100" id="cat" value="<?php echo $row_form['Category']; ?>" /></td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="form-border" style="margin-bottom: 5px">
              <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td colspan="7" class="td-header" style="padding-right:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>Sub Category</td>
                      <td width="15" align="right"><input type="submit" name="add" id="add" value="" class="add-row" /></td>
                    </tr>
                  </table></td>
                </tr>
                <?php 
				while($row_subcat = mysqli_fetch_array($query_subcat)){
					
					$i++;
				
				?>
                    <tr>
                      <td width="110" class="td-left">Oil Company</td>
                      <td class="td-right">
                      <select name="company[]" class="tarea-100" id="company[]">
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
                      </select>
                      </td>
                      <td width="110" class="td-left">Sub Category</td>
                      <td class="td-right"><input name="sub-cat[]" type="text" class="tarea-100" id="sub-cat[]" value="<?php echo $row_subcat['SubCat']; ?>" /></td>
                      <td width="110" class="td-left">Duration (Hrs)</td>
                      <td class="td-right">
                        <input name="duration[]" type="text" class="tarea-100" id="duration[]" value="<?php echo $row_subcat['Duration']; ?>" />
                        <input name="cat-id[]" type="hidden" id="cat-id[]" value="<?php echo $row_subcat['Id']; ?>" />
                      </td>
                      <td class="td-right" style="padding:0;">
                        <?php 
						$param = '';
						
						if(isset($_GET['Edit'])){
							
							$param = '&Edit='. $_GET['Edit'];
						}
						?>
                        <a href="sla-categories.php?Delete-SubCat=<?php echo $row_subcat['Id'] . $param; ?>" class="remove-row"></a>
                      </td>
                    </tr>
                <?php } ?>
              </table>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><div id="form-border2" style="margin-bottom: 5px">
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="5" class="td-header" style="padding-right:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>Email Content & Recipients</td>
                    <td width="15" align="right"><input type="submit" name="add2" id="add2" value="" class="add-row" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="5" class="td-right" style="padding-right:0;"><textarea name="content" cols="45" rows="5" class="tarea-100 mceEditor" id="content"><?php echo $row_content['Content']; ?></textarea></td>
              </tr>
              <?php 
				while($row_mail = mysqli_fetch_array($query_mail)){
					
					$i++;
				
				?>
              <tr>
                <td width="130" class="td-left">Name</td>
                <td width="341" class="td-right"><input name="name[]" type="text" class="tarea-100" id="name[]" value="<?php echo $row_mail['Name']; ?>" /></td>
                <td width="130" class="td-left">Email</td>
                <td width="320" class="td-right"><input name="email[]" type="text" class="tarea-100" id="email[]" value="<?php echo $row_mail['Email']; ?>" />
                  <input name="email-id[]" type="hidden" id="email-id[]" value="<?php echo $row_mail['Id']; ?>" /></td>
                <td class="td-right" style="padding:0;"><?php 
						$param = '';
						
						if(isset($_GET['Edit'])){
							
							$param = '&Edit='. $_GET['Edit'];
						}
						?>
                  <a href="sla-categories.php?Delete-Email=<?php echo $row_mail['Id'] . $param; ?>" class="remove-row"></a></td>
              </tr>
              <tr>
                <td class="td-left">&nbsp;</td>
                <td colspan="3" class="td-right">
               
               <!-- Areas --> 
               <?php 
			   $query_areas = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC")or die(mysqli_error($con));
			   while($row_areas = mysqli_fetch_array($query_areas)){
				   
				   $areaid = $row_areas['Id'];
				   $mailid = $row_mail['Id'];
				   
				   $query_sla_area = mysqli_query($con, "SELECT * FROM tbl_sla_areas WHERE AreaId = '$areaid' AND MailId = '$mailid'")or die(mysqli_error($con));
			   ?>
                <div style="width:25%">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td width="4%"><input name="<?php echo $row_mail['Id']; ?>[]" type="checkbox" id="area[]" value="<?php echo $row_areas['Id']; ?>" 
					  <?php if(mysqli_num_rows($query_sla_area) >= 1){ echo 'checked="checked"'; } ?> /></td>
                      <td width="96%"><?php echo $row_areas['Area']; ?></td>
                    </tr>
                  </table>
                </div>
                <?php } ?>
                <!-- End Areas -->
                
                </td>
                <td class="td-right" style="padding:0;">&nbsp;</td>
              </tr>
              <?php } ?>
            </table>
          </div></td>
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

        <!-- Search Patameters -->
        <?php if(isset($_POST[ 'search'])){ ?>
        <tr>
          <td colspan="2">
            <?php search_results($_POST[ 'filter'], $numrows); ?>
          </td>
        </tr>
        <?php } ?>
        <!-- Close Parameters -->

        <?php if($numrows>= 1){ ?>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="list-border">
              <table width="100%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                  <td class="td-header">Categories</td>
                  <td width="20" class="td-header-right">&nbsp;</td>
                  <td width="20" class="td-header-right">&nbsp;</td>
                </tr>
                <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                  <td><?php echo $row_list['Category']; ?></td>
                  <td align="center">
                    <a href="sla-categories.php?Delete=<?php echo $row_list['Id']; ?>" title="Delete" class="delete" onclick = "if (!confirm('Delete <?php echo $row_list['Category']; ?>?')){ return false; }"></a>
                  </td>
                  <td align="center">
                    <a href="sla-categories.php?Edit=<?php echo $row_list['Id']; ?>" title="Edit" class="edit"></a>
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