<?php 
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

logout($con);

$query_sla_cat = mysqli_query($con, "SELECT * FROM tbl_sla_cat ORDER BY Category ASC")or die(mysqli_error($con));

$catid = $_GET['SLA'];
$companyid = $_GET['Company'];

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '$companyid' AND CatId = '$catid' ORDER BY SubCat ASC")or die(mysqli_error($con));
$sla_rows = mysqli_num_rows($query_sla_sub_cat);

// Error Messages
if(isset($_POST['Submit'])){
	
	$errors = '';
	
	$query_company = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '". $_POST['company'] ."'")or die(mysqli_error($con));
	$row_company = mysqli_fetch_array($query_company);
	
	$query_duplicate = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobNo = '". $_POST['jobnumber'] ."' LIMIT 1") or die(mysqli_error($con));
	
	if(mysqli_num_rows($query_duplicate) >= 1){

		$errors .= "Duplicate Jobcard Entered!" . "\r\n";
	}
	
	if(empty($_POST['company'])){
		
		$errors .= "Please select an oil company!" . "\r\n";
	}
		
	if(empty($_POST['jobnumber'])){
		
		$errors .= "Please enter a job number!" . "\r\n";
	}
	
	if(empty($_POST['sla'])){
		
		$errors .= "Please select an SLA category!" . "\r\n";
	}
	
}
// End Error Mesages

if(isset($_POST['Submit']) && empty($errors)){
	
	if($sla_rows >= 1 || (!empty($_POST['start']) && !empty($_POST['end']))){		
					
		mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));
		
		$query = mysqli_query($con, "SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$jobid = $row['Id'] + 1;
		
		$_SESSION['jobid'] = $jobid;		
		
		$query2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobNo = '". $_POST['jobnumber'] ."' LIMIT 1") or die(mysqli_error($con));
		$numrows = mysqli_num_rows($query2);
		
		if($numrows >= 1){
	
			header('Location: create-new.php?Company='. $_GET['Company'] .'&Site='. $_GET['Site'] .'&Duplicate');
			
		} else {
			
			$description = date('F Y', strtotime('+1 month', strtotime(date('Y-m-d')))) . ' Scheduled Maintenance invoice for HVAC and General buildings.';
			
			$jc_data = array(
			
				'CompanyId' => $_POST['company'],
				'JobNo' => $_POST['jobnumber'],
				'InvoiceDate' => date('Y-m-d'),
				'Date' => date('d M Y'),
				'Days' => date('Y-m-j'),
				'JcDate' => date('Y-m-d'),
				'Reference' => $_POST['reference'],
				'SlaCatId' => $_POST['sla'],
				'Description' => $description,
				'Labour' => '1',
				'Status' => '22',
				'JobId' => $jobid
			);
		
			dbInsert('tbl_jc', $jc_data,$con);
			
			$hvac_data = array(
			
				'CompanyId' => $_POST['company'],
				'JobNo' => $_POST['jobnumber'],
				'InvoiceDate' => date('Y-m-d'),
				'Date' => date('d M Y'),
				'Days' => date('Y-m-j'),
				'JcDate' => date('Y-m-d'),
				'Reference' => $_POST['reference'],
				'RefNo' => $_POST['orderno'],
				'SlaCatId' => $_POST['sla'],
				'Description' => 'HVAC & Refrigeration',
				'Material' => '1',
				'Status' => '22',
				'JobId' => $jobid
			);
		
			dbInsert('tbl_jc', $hvac_data,$con);
			
			$building_data = array(
			
				'CompanyId' => $_POST['company'],
				'JobNo' => $_POST['jobnumber'],
				'InvoiceDate' => date('Y-m-d'),
				'Date' => date('d M Y'),
				'Days' => date('Y-m-j'),
				'JcDate' => date('Y-m-d'),
				'Reference' => $_POST['reference'],
				'RefNo' => $_POST['orderno'],
				'SlaCatId' => $_POST['sla'],
				'Description' => 'General building',
				'Material' => '1',
				'Status' => '22',
				'JobId' => $jobid
			);
		
			dbInsert('tbl_jc', $building_data,$con);
		
			$invoiceno = invno($con);
			$inv_date = date('Y-m-d');
			mysqli_query($con, "UPDATE tbl_jc SET InvoiceNo = '$invoiceno', NewInvoiceDate = '$inv_date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
					 			 					
			header('Location: in-progress.php?Id='. $jobid);
			
			exit();
		}
	}
}

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysqli_error($con));
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	if(isset($_SESSION['areaid'])){
		
		$areaid = $_SESSION['areaid'];
		
	} else {
		
		$areaid = 1;
	}
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

$where = "AreaId = ". $areaid ."";

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers ORDER BY Name ASC")or die(mysqli_error($con));

///////// Getting the data from Mysql table for first list box//////////
$quer2 = mysqli_query($con, "SELECT DISTINCT Name, Id FROM tbl_companies ORDER BY Name"); 
///////////// End of query for first list box////////////

$areaid = $_SESSION['areaid'];
/////// for second drop down list we will check if category is selected else we will display all the subcategory///// 
$cat=$_GET['Company']; // This line is added to take care if your global variable is off
$quer = mysqli_query($con, "SELECT DISTINCT Name, Id FROM tbl_sites WHERE Company = '$cat' AND AreaId = '$areaid' AND Name <> '' ORDER BY Name"); 
////////// end of query for second subcategory drop down list box ///////////////////////////

$query_contractor = mysqli_query($con, "SELECT * FROM tbl_users WHERE Contractor = '1'")or die(mysqli_error($con));

$query_Recordset3 = "SELECT * FROM tbl_far_high_risk_classification";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_Recordset4 = "SELECT * FROM tbl_far_risc_classification";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
        
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="../menu/script.js"></script>
      
	  <script language=JavaScript>
	  
		function reload1(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			self.location='create-new.php?Company=' + val ;
		}
				
		function reload2(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = document.getElementById('jobnumber').value;
			var val3 = document.getElementById('reference').value;
			var val4 = form.sla.options[form.sla.options.selectedIndex].value;
				
			self.location='create-new.php?Company=' + val + '&JobNo=' + val2 + '&Reference=' + val3 + '&SLA=' + val4;
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
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
   <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Pro Forma Invoices</a></li>
            <li><a href="#">Create New</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="search.php">
          <input name="search" type="text" class="search-top" onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
	  <?php if(!empty($errors)){ ?>
      <div id="banner-info">
        <?php echo nl2br($errors); ?>
      </div>
      <?php } ?>
                  
      <?php if($_SESSION['areaid'] != 0){ ?>
        <form action="" method="post" enctype="multipart/form-data" name="f1">
        
          <div id="list-border" style="margin-bottom:20px">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
              <tr>
                <td colspan="4" nowrap class="td-header">Job Card</td>
              </tr>
              <tr>
                <td width="140" nowrap class="td-left">Company                      </td>
                <td colspan="3" nowrap class="td-right">
                  <?php
                    echo "<select name='company' class='tarea-100' id='company' onchange=\"reload1(this.form)\">";
                    echo "<option value=''>Oil Company...</option>";
                    
                    while($noticia2 = mysqli_fetch_array($quer2)){
                        
                        if($noticia2['Id'] == $_GET['Company'] && !empty($_GET['Company'])){
                            
                            echo '<option selected="selected" value='. $noticia2['Id'] .'>'. $noticia2['Name'] .'</option>';
                            
                        } else {
                            
                            echo '<option value='. $noticia2['Id'] .'>'. $noticia2['Name'] .'</option>';
                        }
                    }
                    
                    echo "</select>";
                    ?>
                </td>
              </tr>
              <tr>
                <td width="140" class="td-left">Job Number</td>
                <td width="256" class="td-right"><input name="jobnumber" type="text" class="tarea-100" id="jobnumber" value="<?php echo $_GET['JobNo']; ?>" /></td>
                <td width="140" class="td-left">Reference</td>
                <td width="256" class="td-right"><input name="reference" type="text" class="tarea-100" id="reference" value="<?php echo $_GET['Reference']; ?>" /></td>
              </tr>
              </table>
              </div>
              
          <div id="list-border">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
              <tr>
                <td colspan="2" class="td-header">SLA</td>
              </tr>
              <?php 
			  if($sla_rows >= 1){
				  
				  $colspan = 1;
				  
			  } else {
				  
				  $colspan = 3;
			  }
			  
			  ?>
              <tr>
                <td width="140" class="td-left">SLA Category</td>
                <td class="td-right">
                <select name="sla" class="tarea-100" id="sla" onchange="reload2(this.form)">
                  <option value="">Category...</option>
                  <?php while($row_sla_cat = mysqli_fetch_array($query_sla_cat)){ ?>
                    <option value="<?php echo $row_sla_cat['Id']; ?>" <?php if($_GET['SLA'] == $row_sla_cat['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_sla_cat['Category']; ?></option>
                  <?php } ?>
                </select>
                </td>
                <?php if($sla_rows >= 1){ ?>
                <?php } ?>
              </tr>
              <?php if(isset($_GET['SLA']) && $sla_rows == 0){ ?>
              <?php } ?>
            </table>
              </div>
              
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                  <td align="right"><input name="Submit" type="submit" class="btn-new" value="Create Invoice"></td>
            </tr>
          </table>
              
        </form>
        <div>
		<?php } else {  ?>
            
         <div id="banner-info">Please select a region!</div>
         
       <?php } ?> 
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