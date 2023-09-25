<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

if(isset($_GET['Edit'])){
	
	$edit = $_GET['Edit'];
}

if(isset($_GET['Company'])){
	
	$edit = $_GET['Company'];
}

if(isset($_POST['reset'])){
	
	header('Location: check-list-items.php');
}

   
	if(isset($_POST)){	   
				
	   $form_data = array(
		   'Company' => $_POST['company'],
		   'Asset' => $_POST['asset'],
		   'ListItem' => $_POST['item'],
		);
	}
	
	// Insert	
	if(isset($_POST['insert'])){
		
		dbInsert('tbl_sm_checklist_items', $form_data,$con);
						
		header('Location: check-list-items.php');
		
	}
		
	// Update
	$catid = $_GET['Edit'];
	
	if(isset($_POST['update'])){
		
		dbUpdate('tbl_sm_checklist_items', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);
				
	}
	
	// Delete
	if(isset($_GET['Delete'])){
		
		dbDelete('tbl_sm_checklist_items', $where_clause="Id = '". $_GET['Delete'] ."'",$con);
		
		header('Location: check-list-items.php');
		
	}
	
	if(isset($_GET['Delete-SubCat'])){
		
		dbDelete('tbl_sla_subcat', $where_clause="Id = '". $_GET['Delete-SubCat'] ."'",$con);
		
		$param = '';
		
		if(isset($_GET['Edit'])){
			
			$param = '?Edit='. $_GET['Edit'];
		}
		
		header('Location: check-list-items.php'.$param);
	}
	
	if(isset($_GET['Delete-Email'])){
		
		dbDelete('tbl_sla_email', $where_clause="Id = '". $_GET['Delete-Email'] ."'",$con);
		
		$param = '';
		
		if(isset($_GET['Edit'])){
			
			$param = '?Edit='. $_GET['Edit'];
		}
		
		header('Location: check-list-items.php'.$param);
	}
	
	// Filter Results
	if(isset($_POST['filter'])){
		
		$where = 'WHERE ';
		
		if(!empty($_POST['oilcompany'])){
			
			$where .= 'tbl_companies.Id = '. $_POST['oilcompany'] .' AND ';
		}
		
		if(!empty($_POST['cat'])){
			
			$where .= 'tbl_sm_assets.CatId = '. $_POST['cat'] .' AND ';
		}
		
		if(!empty($_POST['checklist'])){
			
			$where .= 'tbl_sm_assets.Id = '. $_POST['checklist'] .' AND ';
		}
		
		$where .= '1=1';
		
	}
	// End Filter
	
	$query_list = "
		SELECT
			tbl_companies.`Name`,
			tbl_sm_checklist_items.ListItem,
			tbl_sm_assets.Asset,
			tbl_sm_assets.Id AS AssetId,
			tbl_sm_checklist_items.Id,
			tbl_companies.Id AS CompanyId,
			tbl_sm_cat.Cat
		FROM
			tbl_sm_assets
		INNER JOIN tbl_sm_checklist_items ON tbl_sm_assets.Id = tbl_sm_checklist_items.Asset
		INNER JOIN tbl_companies ON tbl_sm_assets.Company = tbl_companies.Id
		INNER JOIN tbl_sm_cat ON tbl_sm_cat.Id = tbl_sm_assets.CatId
		$where
		ORDER BY tbl_companies.`Name` ASC, tbl_sm_cat.Cat ASC, tbl_sm_assets.Asset ASC, tbl_sm_checklist_items.ListItem ASC";
	
	$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query_list);
	
	$query_form = "
	  SELECT
		  tbl_companies.`Name`,
		  tbl_sm_checklist_items.ListItem,
		  tbl_sm_assets.Asset,
		  tbl_sm_assets.Id AS AssetId,
		  tbl_sm_checklist_items.Id,
		  tbl_companies.Id AS CompanyId
	  FROM
		  tbl_sm_assets
	  INNER JOIN tbl_sm_checklist_items ON tbl_sm_assets.Id = tbl_sm_checklist_items.Asset
	  INNER JOIN tbl_companies ON tbl_sm_assets.Company = tbl_companies.Id
	  WHERE 
	      tbl_sm_checklist_items.Id = '$edit'";
	
	$query_form = mysqli_query($con, $query_form)or die(mysqli_error($con));
	$row_form = mysqli_fetch_array($query_form);  
	
	$query_companies2 = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC")or die(mysqli_error($con));
	
	$company = $_GET['OilCompany'];
	
	$query_checklist_cat = mysqli_query($con, "SELECT * FROM tbl_sm_cat WHERE CompanyId = '$company' ORDER BY Cat ASC")or die(mysqli_error($con));
	
	$catid = $_GET['Cat'];
	
	$query_checklists = mysqli_query($con, "SELECT * FROM tbl_sm_assets WHERE CatId = '$catid' ORDER BY Asset ASC")or die(mysqli_error($con));
	 
	
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
      
	  <script language=JavaScript>
	  
		function reload1(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			self.location='check-list-items.php?Company=' + val ;
		}
	  
		function reload2(form){
			var val_1=form.oilcompany.options[form.oilcompany.options.selectedIndex].value; 
			self.location='check-list-items.php?OilCompany=' + val_1 ;
		}
	  
		function reload3(form){
			var val_1=form.oilcompany.options[form.oilcompany.options.selectedIndex].value; 
			var val_2=form.cat.options[form.cat.options.selectedIndex].value; 
			self.location='check-list-items.php?OilCompany=' + val_1 + '&Cat=' + val_2;
		}
		
      </script>
      
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
            <li><a href="#">Settings</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Check List Items</a></li>
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
                  <td class="td-left">Oil Company</td>
                  <td class="td-right">
                  
                  <select name="company" class="tarea-100" id="company" onchange="reload1(this.form)">
                    <option value="">Select one...</option>
                    <?php 
						  $query_companies = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC")or die(mysqli_error($con));
						  while($row_companies = mysqli_fetch_array($query_companies)){
							  
							  $selected = '';
							  
							  if($row_companies['Id'] == $row_form['CompanyId']){
								  
								  $selected = 'selected="selected"';
							  }
							  
							  if($row_companies['Id'] == $_GET['Company']){
								  
								  $selected = 'selected="selected"';
							  }
						?>
                    <option value="<?php echo $row_companies['Id']; ?>" <?php echo $selected; ?>><?php echo $row_companies['Name']; ?></option>
                    <?php } ?>
                  </select>
                  
                  </td>
                </tr>
                <tr>
                  <td width="130" class="td-left">Check List</td>
                  <td class="td-right">
                  <select name="asset" class="tarea-100" id="asset">
                    <option value="">Select one...</option>
                    <?php 
						  if(isset($_GET['Company'])){
							  
							  $companyid = $_GET['Company'];
							  
						  } else {
							  
							  $companyid = $row_form['CompanyId'];
						  }
						  
						  $query_assets = mysqli_query($con, "SELECT * FROM tbl_sm_assets WHERE Company = '$companyid' ORDER BY Asset ASC")or die(mysqli_error($con));
						  while($row_assets = mysqli_fetch_array($query_assets)){
							  
							  $selected = '';
							  
							  if($row_assets['Id'] == $row_form['AssetId']){
								  
								  $selected = 'selected="selected"';
							  }
						?>
                    <option value="<?php echo $row_assets['Id']; ?>" <?php echo $selected; ?>><?php echo $row_assets['Asset']; ?></option>
                    <?php } ?>
                  </select></td>
                </tr>
                <tr>
                  <td class="td-left">Question</td>
                  <td class="td-right"><input name="item" type="text" class="tarea-100" id="item" value="<?php if(isset($_GET['Edit'])){ echo $row_form['ListItem']; } ?>" /></td>
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
          <td colspan="2" align="right"><table width="100%" border="0" cellpadding="0" cellspacing="1">
            <tr>
              <td width="170">
                <select name="oilcompany" class="select-filter" id="oilcompany" onchange="reload2(this.form)">
                  <option value="">Oil Company</option>
                  <?php while($row_companies2 = mysqli_fetch_array($query_companies2)){ ?>
                    <option value="<?php echo $row_companies2['Id']; ?>" <?php if($row_companies2['Id'] == $_GET['OilCompany']){ echo 'selected="selected"'; } ?>><?php echo $row_companies2['Name']; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td width="170">
                <select name="cat" class="select-filter" id="cat" onchange="reload3(this.form)">
                  <option value="">Category</option>
                  <?php while($row_checklist_cat = mysqli_fetch_array($query_checklist_cat)){ ?>
                    <option value="<?php echo $row_checklist_cat['Id']; ?>" <?php if($row_checklist_cat['Id'] == $_GET['Cat']){ echo 'selected="selected"'; } ?>><?php echo $row_checklist_cat['Cat']; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td width="170">
                <select name="checklist" class="select-filter" id="checklist">
                  <option value="">Checklist</option>
                  <?php while($row_checklists = mysqli_fetch_array($query_checklists)){ ?>
                    <option value="<?php echo $row_checklists['Id']; ?>"><?php echo $row_checklists['Asset']; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td><input name="filter" type="submit" class="search-filter-btn" id="filter" value="" /></td>
              <td width="20">&nbsp;</td>
              <td width="20">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="list-border">
              <table width="100%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                  <td width="150" class="td-header">Company</td>
                  <td width="150" class="td-header">Category</td>
                  <td width="150" class="td-header">Checklist</td>
                  <td class="td-header">Check List Item</td>
                  <td width="20" class="td-header-right">&nbsp;</td>
                  <td width="20" class="td-header-right">&nbsp;</td>
                </tr>
                <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                  <td><?php echo $row_list['Name']; ?></td>
                  <td><?php echo $row_list['Cat']; ?></td>
                  <td><?php echo $row_list['Asset']; ?></td>
                  <td><?php echo $row_list['ListItem']; ?></td>
                  <td align="center">
                    <a href="check-list-items.php?Delete=<?php echo $row_list['Id']; ?>" title="Delete" class="delete" onclick = "if (!confirm('Delete <?php echo $row_list['ListItem']; ?>?')){ return false; }"></a>
                  </td>
                  <td align="center">
                    <a href="check-list-items.php?Edit=<?php echo $row_list['Id']; ?>" title="Edit" class="edit"></a>
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
  mysqli_free_result($query_checklist_cat);
  mysqli_free_result($query_checklists);
  mysqli_free_result($query_companies2);
  mysqli_free_result($query_user_menu);
?>