<?php 
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

logout($con);

// Mobile Numbers
if(isset($_GET['Site'])){
	
	$siteid = $_GET['Site'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_jc_mobile WHERE SiteId = '$siteid'")or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	
	if($numrows == 0){
		
		mysqli_query($con, "INSERT INTO tbl_jc_mobile (SiteId) VALUES ('$siteid')")or die(mysqli_error($con));
	}
	
	if(isset($_POST['add'])){
		
		mysqli_query($con, "INSERT INTO tbl_jc_mobile (SiteId) VALUES ('$siteid')")or die(mysqli_error($con));
	}
	
	if(isset($_POST['cell']) || isset($_POST['add'])){
		
		for($i=0;$i<count($_POST['cell']);$i++){
			
			$cell = $_POST['cell'][$i];
			$id = $_POST['id'][$i];
			
			mysqli_query($con, "UPDATE tbl_jc_mobile SET SiteId = '$siteid', Mobile = '$cell' WHERE Id = '$id'")or die(mysqli_error($con));
		}
	}
}

$query_sla_cat = mysqli_query($con, "SELECT * FROM tbl_sla_cat WHERE Module = 'JC' ORDER BY Category ASC")or die(mysqli_error($con));

$catid = $_GET['SLA'];
$companyid = $_GET['Company'];

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '$companyid' AND CatId = '$catid' ORDER BY SubCat ASC")or die(mysqli_error($con));
$sla_rows = mysqli_num_rows($query_sla_sub_cat);

// Error Messages
if(isset($_POST['Submit'])){
	
	$errors = '';
	
	$query_company = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '". $_POST['company'] ."'")or die(mysqli_error($con));
	$row_company = mysqli_fetch_array($query_company);
	
	$query_duplicate = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobNo = '". $row_company['Prefix'] . $_POST['jobnumber'] ."' LIMIT 1") or die(mysqli_error($con));
	
	if(mysqli_num_rows($query_duplicate) >= 1){

		$errors .= "Duplicate Jobcard Entered!" . "\r\n";
	}
	
	if(empty($_POST['company'])){
		
		$errors .= "Please select an oil company!" . "\r\n";
	}
	
	if(empty($_POST['site'])){
		
		$errors .= "Please select site!" . "\r\n";
	}
	
	if(empty($_POST['jobnumber'])){
		
		$errors .= "Please enter a job number!" . "\r\n";
	}
	
	if(empty($_POST['reference'])){
		
		$errors .= "Please select an engineer!" . "\r\n";
	}
	
	if(empty($_POST['description'])){
		
		$errors .= "Please enter the service requested!" . "\r\n";
		
	} else {
		
		$words = count(explode(' ', $_POST['description']));
		
		if($words < 5){
			
			$errors .= "Please enter 5 or more words into the service requested" . "\r\n";
		}
	}
	
	if($_FILES['pdf']['error'] != 0){
		
		$errors .= "Please upload a job card pdf!" . "\r\n";
	}
	
	if(empty($_POST['sla'])){
		
		$errors .= "Please select an SLA category!" . "\r\n";
	}
	
	if(empty($_POST['sub_cat']) && ($_POST['sla'] == 5 || $_POST['sla'] == 6)){
		
		$errors .= "Please select an SLA sub category!" . "\r\n";
	}
	
	if(empty($_POST['risk'])){
		
		$errors .= "Please select a FAR risk classification!" . "\r\n";
	}
	
	if(empty($_POST['type']) && $_POST['risk'] == 3){
		
		$errors .= "Please select a FAR high risk classification!";
	}
}
// End Error Mesages

if(isset($_POST['Submit']) && empty($errors)){
	
	if($sla_rows >= 1 || (!empty($_POST['start']) && !empty($_POST['end']))){
	
		$company = $_POST['company'];
		$site = $_POST['site'];
		$jobnumber = $_POST['jobnumber'];
		$job_desc = $_POST['description'];
		$date = date('d M Y');
		$today = date('Y-m-j');
		$ref = $_POST['reference'];
		$areaid = $_SESSION['areaid'];
		
		$sla_cat = $_POST['sla'];
		$sla_sub_cat = $_POST['sub_cat'];
		$contractor = $_POST['contractor'];
		
		if(!empty($_POST['start'])){
			
			$start = $_POST['start'];
			$end = $_POST['end'];
			
		} else  {
			
			$query_end = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE Id = '$sla_sub_cat'")or die(mysqli_error($con));
			$row_end = mysqli_fetch_array($query_end);
			
			$duration = $row_end['Duration'];
			
			$start = date('Y-m-d H:i:s');
			
			$future = addRollover(date('Y-m-d H:i:s'), $duration, '8:00', '16:30', true);
			
			$end = $future->format('Y-m-d H:i:s').'</br>';
		}
			
		mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));
		
		$query = mysqli_query($con, "SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$jobid = $row['Id'] + 1;
		
		$_SESSION['jobid'] = $jobid;
		
		$query = mysqli_query($con, "SELECT Id, Prefix FROM tbl_companies WHERE Id = '$company'")or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		
		$prefix = $row['Prefix'];
		$jobno = $prefix . $jobnumber;
		
		$query2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobNo = '$jobno' LIMIT 1") or die(mysqli_error($con));
		$numrows = mysqli_num_rows($query2);
		
		if($numrows >= 1){
	
			header('Location: jc-new.php?Company='. $_GET['Company'] .'&Site='. $_GET['Site'] .'&Duplicate');
			
		} else {
		
			$companyid = $row['Id'];
			
			$query = mysqli_query($con, "SELECT * FROM tbl_rates WHERE CompanyId = '$companyid' AND Fuel = '1'")or die(mysqli_error($con));
			$row = mysqli_fetch_array($query);
			$rate = $row['Rate'];
			 
			$jcdate = date('Y-m-d');
			 
			mysqli_query($con, "INSERT INTO tbl_jc 
			(ContractorId,SlaCatId,SlaSubCatId,AreaId,CompanyId,SiteId,JobNo,JobId,Labour,Date,Reference,Unit,Status,Days,JcDate,Date1,Date2,JobDescription) 
			VALUES
			('$contractor','$sla_cat','$sla_sub_cat','$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','hours','1','$today','$jcdate','$start','$end','$job_desc')") or die(mysqli_error($con));
			
			mysqli_query($con, "INSERT INTO tbl_jc 
			(ContractorId,SlaCatId,SlaSubCatId,AreaId,CompanyId,SiteId,JobNo,JobId,Material,Date,Reference,Status,Days,JcDate,Date1,Date2,JobDescription) 
			VALUES 
			('$contractor','$sla_cat','$sla_sub_cat','$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today','$jcdate','$start','$end','$job_desc')") or die(mysqli_error($con));
			
			mysqli_query($con, "INSERT INTO tbl_travel (JobId) VALUES ('$jobid')") or die(mysqli_error($con));
			
			mysqli_query($con, "INSERT INTO tbl_jc 
			(ContractorId,SlaCatId,SlaSubCatId,AreaId,CompanyId,SiteId,JobNo,JobId,Comment,Date,Reference,Status,Days,JcDate,Date1,Date2,JobDescription) 
			VALUES 
			('$contractor','$sla_cat','$sla_sub_cat','$areaid','$company','$site','$jobno','$jobid','1','$date','$ref','1','$today','$jcdate','$start','$end','$job_desc')") or die(mysqli_error($con));
			
			// SLA
			mysqli_query($con, "INSERT INTO tbl_sla_history (JobId,JobNo,SlaStart,SlaEnd) VALUES ('$jobid','$jobno','$start','$end')")or die(mysqli_error($con));
			
			$date2 = date('d M Y');
			
			mysqli_query($con, "INSERT INTO tbl_feedback (Reference,Date,Status) VALUES ('$jobno','$date2','1')")or die(mysqli_error($con));
			
			$risk = $_POST['risk'];
			$type = $_POST['type'];
			
			mysqli_query($con, "INSERT INTO tbl_far (JobNo,RiskType,RiskClassification) VALUES ('$jobno','$risk','$type')")or die(mysqli_error($con));
			
			$target_path = "../jc-pdf/";
			
			$target_path = $target_path . basename($_FILES['pdf']['name']); 
			
			if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)) {
				
				$pdf = $_FILES['pdf']['name'];
				$id = $_GET['Id'];
				
				mysqli_query($con, "UPDATE tbl_jc SET JobcardPDF = '$pdf' WHERE JobId = '$jobid'")or die(mysqli_error($con));
			}
		
			header('Location: qued-details.php?Id='. $jobid);
			
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

$query_Recordset4 = "SELECT * FROM tbl_far_risc_classification WHERE Id != '2'";
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
			self.location='jc-new.php?Company=' + val ;
		}
		
		function reload2(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			var val2=form.site.options[form.site.options.selectedIndex].value; 
			self.location='jc-new.php?Company=' + val + '&Site=' + val2;
		}
		
		function reload3(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = form.site.options[form.site.options.selectedIndex].value; 
			var val3 = document.getElementById('jobnumber').value;
			var val4 = form.reference.options[form.reference.options.selectedIndex].value; 
			self.location='jc-new.php?Company=' + val + '&Site=' + val2 + '&JobNo=' + val3 + '&Reference=' + val4;
		}
		
		function reload4(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = form.site.options[form.site.options.selectedIndex].value; 
			var val3 = document.getElementById('jobnumber').value;
			var val4 = form.reference.options[form.reference.options.selectedIndex].value; 
			var val5 = form.contractor.options[form.contractor.options.selectedIndex].value; 
			self.location='jc-new.php?Company=' + val + '&Site=' + val2 + '&JobNo=' + val3 + '&Reference=' + val4 + '&Contractor=' + val5;
		}
		
		function reload_desc(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = form.site.options[form.site.options.selectedIndex].value; 
			var val3 = document.getElementById('jobnumber').value;
			var val4 = form.reference.options[form.reference.options.selectedIndex].value; 
			var val5 = form.contractor.options[form.contractor.options.selectedIndex].value; 
			var val6 = document.getElementById('description').value;
			self.location='jc-new.php?Company=' + val + '&Site=' + val2 + '&JobNo=' + val3 + '&Reference=' + val4 + '&Contractor=' + val5 + '&Description=' + val6;
		}
		
		function reload5(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = form.site.options[form.site.options.selectedIndex].value; 
			var val3 = document.getElementById('jobnumber').value;
			var val4 = form.reference.options[form.reference.options.selectedIndex].value; 
			var val5 = form.contractor.options[form.contractor.options.selectedIndex].value; 
			var val6 = document.getElementById('description').value;
			var val7 = form.sla.options[form.sla.options.selectedIndex].value; 
			self.location='jc-new.php?Company=' + val + '&Site=' + val2 + '&JobNo=' + val3 + '&Reference=' + val4 + '&Contractor=' + val5 + '&Description=' + val6 + '&SLA=' + val7;
		}
		
		function reload6(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = form.site.options[form.site.options.selectedIndex].value; 
			var val3 = document.getElementById('jobnumber').value;
			var val4 = form.reference.options[form.reference.options.selectedIndex].value; 
			var val5 = form.contractor.options[form.contractor.options.selectedIndex].value;
			var val6 = document.getElementById('description').value;
			var val7 = form.sla.options[form.sla.options.selectedIndex].value;
			var val8 = form.sub_cat.options[form.sub_cat.options.selectedIndex].value;
			self.location='jc-new.php?Company=' + val + '&Site=' + val2 + '&JobNo=' + val3 + '&Reference=' + val4 + '&Contractor=' + val5 + '&Description=' + val6 + '&SLA=' + val7 + '&SubCat=' + val8;
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
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Job Cards</a></li>
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
                <td width="256" nowrap class="td-right">
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
                <td width="140" nowrap class="td-left">Site</td>
                <td width="256" nowrap class="td-right">
                  <?php
                    echo "<select name='site' id='site' class='tarea-100' onchange=\"reload2(this.form)\">";
                    echo "<option value=''>Site...</option>";
                    
                    while($noticia = mysqli_fetch_array($quer)){
                        
                        if($noticia['Id']==$_GET['Site'] && !empty($_GET['Site'])){
                            
                            echo "<option selected value='$noticia[Id]'>$noticia[Name]</option>";
                            
                        } else {
                            
                            echo  "<option value='$noticia[Id]'>$noticia[Name]</option>";
                        }
                    }
                    echo "</select>";
                    ?>
                </td>
              </tr>
              <tr>
                <td width="140" class="td-left">Job Number </td>
                <td class="td-right"><input name="jobnumber" type="text" class="tarea-100" id="jobnumber" value="<?php echo $_GET['JobNo']; ?>"></td>
                <td width="140" class="td-left">Reference</td>
                <td width="256" class="td-right">
                <select name="reference" class="tarea-100" id="reference" onchange="reload3(this.form)">
                  <option value="">Select an Engineer</option>
                  <?php while($row_engineers = mysqli_fetch_array($query_engineers)){ ?>
                  <option value="<?php echo trim($row_engineers['Name']); ?>" <?php if($row_engineers['Name'] == $_GET['Reference']){ echo 'selected="selected"'; } ?>><?php echo $row_engineers['Name']; ?></option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td width="140" class="td-left">Contractor</td>
                <td class="td-right">
                <select name="contractor" class="tarea-100" id="contractor" onchange="reload4(this.form)">
                  <option value="">Contractor</option>
                  <?php while($row_contractor = mysqli_fetch_array($query_contractor)){ ?>
                    <option value="<?php echo $row_contractor['Id']; ?>" <?php if($_GET['Contractor'] == $row_contractor['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_contractor['Name']; ?></option>
                  <?php } ?>
                </select>
                </td>
                <td class="td-left">PDF</td>
                <td class="td-right"><input name="pdf" type="file" class="tarea-100" id="pdf" size="27" /></td>
              </tr>
              <tr>
                <td valign="top" class="td-left">Service Requested</td>
                <td colspan="3" class="td-right"><textarea name="description" cols="45" rows="5" class="tarea-100" id="description" onchange="reload_desc(this.form)"><?php echo $_GET['Description']; ?></textarea></td>
              </tr>
              </table>
              </div>
              
          <div id="list-border" style="margin-bottom:20px">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
              <tr>
                <td colspan="4" class="td-header">SLA</td>
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
                <td class="td-right" width="256" colspan="<?php echo $colspan; ?>">
                <select name="sla" class="tarea-100" id="sla" onchange="reload5(this.form)">
                  <option value="">Category...</option>
                  <?php while($row_sla_cat = mysqli_fetch_array($query_sla_cat)){ ?>
                    <option value="<?php echo $row_sla_cat['Id']; ?>" <?php if($_GET['SLA'] == $row_sla_cat['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_sla_cat['Category']; ?></option>
                  <?php } ?>
                </select>
                </td>
                <?php if($sla_rows >= 1){ ?>
                <td width="140" class="td-left">SLA Sub Category</td>
                <td width="256" class="td-right">
                <select name="sub_cat" class="tarea-100" id="sub_cat" onchange="reload6(this.form)">
                  <option value="">Sub Category...</option>
                  <?php while($row_sla_sub_cat = mysqli_fetch_array($query_sla_sub_cat)){ ?>
                  <option value="<?php echo $row_sla_sub_cat['Id']; ?>" <?php if($_GET['SubCat'] == $row_sla_sub_cat['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_sla_sub_cat['SubCat']; ?></option>
                  <?php } ?>
                </select>
                </td>
                <?php } ?>
              </tr>
              <?php if(isset($_GET['SLA']) && $sla_rows == 0){ ?>
              <tr>
                <td class="td-left">Strat Date</td>
                <td class="td-right">
                <input name="start" type="text" class="tarea-100" id="start" value="<?php echo $_GET['Start']; ?>" />
                
				<script type="text/javascript">
                $('#start').datepicker({
                dateFormat: "yy-mm-dd"
                });
                </script>
                
                </td>
                <td class="td-left">End Date</td>
                <td class="td-right">
                <input name="end" type="text" class="tarea-100" id="end" value="<?php echo $_GET['End']; ?>" />
                
				<script type="text/javascript">
                $('#end').datepicker({
                dateFormat: "yy-mm-dd"
                });
                </script>
                
                </td>
              </tr>
              <?php } ?>
            </table>
              </div>

          <div id="list-border" style="margin-bottom:20px">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
              <tr>
                <td colspan="4" class="td-header">FAR</td>
              </tr>
              <tr>
                <td width="140" class="td-left">Risk Classification</td>
                <td width="256" class="td-right">
                <select name="risk" class="tarea-100" id="risk" style="width:100%">
                  <option value="">Select one...</option>
                  <?php
                    do {  
                    ?>
                    <option value="<?php echo $row_Recordset4['Id']; ?>" <?php if($row_Recordset4['Id'] == $_POST['risk']){ echo 'selected="selected"'; } ?>><?php echo $row_Recordset4['Risk']?></option>
                                            <?php
                    } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4));
                      $rows = mysqli_num_rows($Recordset4);
                      if($rows > 0) {
                          mysqli_data_seek($Recordset4, 0);
                          $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
                      }
                    ?>
                </select></td>
                <td width="140" class="td-left">High Risk Classif</td>
                <td width="256" class="td-right"><select name="type" class="tarea-100" id="type" style="width:100%">
                  <option value="">Select one...</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_Recordset3['Id']; ?>" <?php if($row_Recordset3['Id'] == $_POST['type']){ echo 'selected="selected"'; } ?> ><?php echo $row_Recordset3['Risk']?></option>
                  <?php
                  } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3));
                    $rows = mysqli_num_rows($Recordset3);
                    if($rows > 0) {
                        mysqli_data_seek($Recordset3, 0);
                        $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
                    }
                  ?>
                </select></td>
              </tr>
              </table>
              </div>
              <?php if(isset($_GET['Company']) && isset($_GET['Site'])){ ?>
          <div id="list-border">
            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
              <tr>
                <td colspan="4" class="td-header">SMS Notifications</td>
              </tr>
              <?php
              $i = 0;
              $query = mysqli_query($con, "SELECT * FROM tbl_jc_mobile WHERE SiteId = '$siteid' ORDER BY Id ASC")or die(mysqli_error($con));
              while($row = mysqli_fetch_array($query)){
                  
                  $i++;
              
                  $id = $row['Id'];
                  $mobile = $row['Mobile'];
                  ?>
                  <tr>
                    <td width="140" class="td-left"><?php if($i == 1){ ?>Cell Number<?php } ?></td>
                    <td colspan="3" class="td-right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><input name="cell[]" type="text" class="tarea-100" id="cell[]" size="40" value="<?php echo $row['Mobile']; ?>">
                        <input type="hidden" name="id[]" id="id[]" value="<?php echo $id; ?>" /></td>
                        <td width="15"><?php if($i == 1){ ?><input name="add" type="submit" class="btn-add-new" id="add" value=""><?php } ?></td>
                      </tr>
                    </table></td>
              </tr>
                <?php } ?>
            </table>
            </div>
            <?php } ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                  <td align="right"><input name="Submit" type="submit" class="btn-new" value="Create Job Card"></td>
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