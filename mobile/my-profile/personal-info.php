<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');
$userid = $_SESSION['userid'];

if(!isset($_SESSION['userid'])){
	
	header('Location: ../index.php');
	
	exit();
}

$form_data = array(
	
	'Name' => $_POST['name'],
	'Title' => $_POST['title'],
	'Gender' => $_POST['gender'],
	'Nationality' => $_POST['nationality'],
	'Race' => $_POST['race'],
	'Language' => $_POST['language'],
	'HighestEducation' => $_POST['highest-education'],
	'Institution' => $_POST['institution'],
	'PostMatricQual' => $_POST['post-matric'],
	'CriminalRecord' => $_POST['record'],
	'CriminalRecordComments' => $_POST['record-comments'],
	'Dependants' => $_POST['dependants'],
	'MaritalStatus' => $_POST['marital-status'],
	'IdNo' => $_POST['idno'],
	'PassportNo' => $_POST['passportno'],
	
	'Occupation' => $_POST['occupation'],
	'EmploymentStatus' => $_POST['employment-status'],
	'CompanyNo' => $_POST['company-no'],
	'LicenseNumber' => $_POST['license-code'],
	
	'Cell_1' => $_POST['cell1'],
	'Cell_2' => $_POST['cell2'],
	'Telephone' => $_POST['telephone'],
	'Email' => $_POST['email'],
	'PostalAddress' => $_POST['address'],
	'ResidentialAddress' => $_POST['address-res'],
	
	'AlcoholConsumption' => $_POST['alcohol'],
	'Smoker' => $_POST['smoker'],
	'Skills' => $_POST['skills'],
	
	'SpouseName' => $_POST['spouse'],
	'SpouseEmploymentStatus' => $_POST['spouse-employment-status'],
	'EmploymentDetails' => $_POST['employment-details'],
	'NextOfKin_1' => $_POST['kin1'],
	'NextOfKin_2' => $_POST['kin2'],
	'KinTelephone_1' => $_POST['kin-telephone-1'],
	'KinTelephone_2' => $_POST['kin-telephone-2'],
	
	);

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_personal_info', $form_data, $where_clause="TechnicianId = '". $_SESSION['userid'] ."'",$con);
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_personal_info WHERE TechnicianId = '$userid'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Sealink</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Include scripts -->
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript" src="../menu/js/responsivemultimenu.js"></script>
    
    <script type="text/javascript">
	  $(document).ready(function() {
	  
		  var $submit = $("#update").hide(),
			  $cbs = $('input[name="declaration"]').click(function() {
				  $submit.toggle( $cbs.is(":checked") );
			  });
	  
	  });
	</script>
    
    <!-- Include styles -->
    <link rel="stylesheet" href="../menu/css/responsivemultimenu.css" type="text/css" />

    <!-- Include media queries -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href="../../SpryAssets/SpryCollapsiblePanel4.css" rel="stylesheet" type="text/css" />
    <link href="../../css/mobile.css" rel="stylesheet" type="text/css" />

</head>
<body id="site">

    <div id="wrapper">

        <div id="content">
        
        <?php include('../menu/menu.php'); ?>

            <form action="" method="post" id="form-1">
            
            <div style="width:100%">

                <!-- Personal info section 1 -->
                <div class="field-container">
                  <select name="title" class="tfield-dd" id="title">
                    <option value="">Title...</option>
                    <option value="Mr" <?php if($row_form['Title'] == 'Mr'){ echo 'selected="selected"'; } ?>>Mr</option>
                    <option value="Mrs" <?php if($row_form['Title'] == 'Mrs'){ echo 'selected="selected"'; } ?>>Mrs</option>
                    <option value="Miss" <?php if($row_form['Title'] == 'Miss'){ echo 'selected="selected"'; } ?>>Miss</option>
                    <option value="Dr" <?php if($row_form['Title'] == 'Dr'){ echo 'selected="selected"'; } ?>>Dr</option>
                  </select>
                </div>

                <div class="field-container">
                  <?php 
				  if(empty($row_form['Name'])){
					  
					  $def = 'Full Name';
					  
				  } else {
					  
					  $def = $row_form['Name'];
				  }
				  ?>
                  <input name="name" type="text" class="tfield" id="name" onfocus="if(this.value=='Full Name'){this.value=''}" onblur="if(this.value==''){this.value='Full Name'}" value="<?php echo $def; ?>">
                </div>

                <div class="field-container">
                  <select name="gender" class="tfield-dd" id="gender">
                    <option value="">Gender...</option>
                    <option value="Male" <?php if($row_form['Gender'] == 'Male'){ echo 'selected="selected"'; } ?>>Male</option>
                    <option value="Female" <?php if($row_form['Gender'] == 'Female'){ echo 'selected="selected"'; } ?>>Female</option>
                  </select>
                </div>

                <div class="field-container">
                  <?php 
				  if(empty($row_form['Nationality'])){
					  
					  $def = 'Nationality';
					  
				  } else {
					  
					  $def = $row_form['Nationality'];
				  }
				  ?>
                  <input name="nationality" type="text" class="tfield" id="nationality" onfocus="if(this.value=='Nationality'){this.value=''}" onblur="if(this.value==''){this.value='Nationality'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Race'])){
					  
					  $def = 'Race';
					  
				  } else {
					  
					  $def = $row_form['Race'];
				  }
				  ?>
                  <input name="race" type="text" class="tfield" id="race" onfocus="if(this.value=='Race'){this.value=''}" onblur="if(this.value==''){this.value='Race'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Language'])){
					  
					  $def = 'Home Language';
					  
				  } else {
					  
					  $def = $row_form['Language'];
				  }
				  ?>
                  <input name="language" type="text" class="tfield" id="language" onfocus="if(this.value=='Highest Level of Education'){this.value=''}" onblur="if(this.value==''){this.value='Highest Level of Education'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['HighestEducation'])){
					  
					  $def = 'Highest Level of Education';
					  
				  } else {
					  
					  $def = $row_form['HighestEducation'];
				  }
				  ?>
                  <input name="highest-education" type="text" class="tfield" id="highest-education" onfocus="if(this.value=='Highest Level of Education'){this.value=''}" onblur="if(this.value==''){this.value='Highest Level of Education'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Institution'])){
					  
					  $def = 'Institution';
					  
				  } else {
					  
					  $def = $row_form['Institution'];
				  }
				  ?>
                  <input name="institution" type="text" class="tfield" id="institution" onfocus="if(this.value=='Institution'){this.value=''}" onblur="if(this.value==''){this.value='Institution'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['PostMatricQual'])){
					  
					  $def = 'Post Matric Qualifications';
					  
				  } else {
					  
					  $def = $row_form['PostMatricQual'];
				  }
				  ?>
                  <input name="post-matric" type="text" class="tfield" id="post-matric" onfocus="if(this.value=='Post Matric Qualifications'){this.value=''}" onblur="if(this.value==''){this.value='Post Matric Qualifications'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['CriminalRecord'])){
					  
					  $def = 'Criminal Record';
					  
				  } else {
					  
					  $def = $row_form['CriminalRecord'];
				  }
				  ?>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="radio-field">
                    <tr>
                      <td width="120">Criminal Record</td>
                      <td align="right" style="text-align:right">
                      <table border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                          <td><input type="radio" name="record" id="radio" value="Yes" <?php if($row_form['CriminalRecord'] == 'Yes'){ echo 'checked'; } ?>></td>
                          <td>Yes</td>
                          <td><input type="radio" name="record" id="radio" value="No" <?php if($row_form['CriminalRecord'] == 'No'){ echo 'checked'; } ?>></td>
                          <td>No</td>
                        </tr>
                      </table>
                      </td>
                    </tr>
                  </table>
                </div>
                
                <div class="field-container-comments">
                  <?php 
				  if(empty($row_form['CriminalRecordComments'])){
					  
					  $def = 'If yes, provide information regarding record';
					  
				  } else {
					  
					  $def = $row_form['CriminalRecordComments'];
				  }
				  ?>
                  <textarea name="record-comments" class="tfield" id="record-comments" onFocus="if(this.value=='If yes, provide information regarding record'){this.value=''}" onBlur="if(this.value==''){this.value='If yes, provide information regarding record'}"><?php echo $def; ?></textarea>
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Dependants'])){
					  
					  $def = 'No. of Dependants';
					  
				  } else {
					  
					  $def = $row_form['Dependants'];
				  }
				  ?>
                  <input name="dependants" type="text" class="tfield" id="dependants" onfocus="if(this.value=='No. of Dependants'){this.value=''}" onblur="if(this.value==''){this.value='No. of Dependants'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['MaritalStatus'])){
					  
					  $def = 'Marital Status';
					  
				  } else {
					  
					  $def = $row_form['MaritalStatus'];
				  }
				  ?>
                  <select name="marital-status" class="tfield">
                    <option value="Marital Status" <?php if($row_form['MaritalStatus'] == ''){ echo 'selected'; } ?>>Marital Status</option>
                    <option value="Single" <?php if($row_form['MaritalStatus'] == 'Single'){ echo 'selected'; } ?>>Single</option>
                    <option value="Married" <?php if($row_form['MaritalStatus'] == 'Married'){ echo 'selected'; } ?>>Married</option>
                    <option value="Divorced" <?php if($row_form['MaritalStatus'] == 'Divorced'){ echo 'selected'; } ?>>Divorced</option>
                    <option value="Widowed" <?php if($row_form['MaritalStatus'] == 'Widowed'){ echo 'selected'; } ?>>Widowed</option>
                  </select>
                </div>
                
                <div class="field-container-last-1">
                  <?php 
				  if(empty($row_form['IdNo'])){
					  
					  $def = 'Id Number';
					  
				  } else {
					  
					  $def = $row_form['IdNo'];
				  }
				  ?>
                  <input name="idno" type="text" class="tfield" id="idno" onfocus="if(this.value=='Id Number'){this.value=''}" onblur="if(this.value==''){this.value='Id Number'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container-last-2">
                  <?php 
				  if(empty($row_form['PassportNo'])){
					  
					  $def = 'Passport Number';
					  
				  } else {
					  
					  $def = $row_form['PassportNo'];
				  }
				  ?>
                  <input name="passportno" type="text" class="tfield" id="passportno" onfocus="if(this.value=='Passport Number'){this.value=''}" onblur="if(this.value==''){this.value='Passport Number'}" value="<?php echo $def; ?>">
                </div>
                <!-- End Personal info section 1 -->
                
                <!-- Personal info section 2 -->
                <div class="tab-flat">Employment Info</div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Occupation'])){
					  
					  $def = 'Occupation';
					  
				  } else {
					  
					  $def = $row_form['Occupation'];
				  }
				  ?>
                  <input name="occupation" type="text" class="tfield" id="occupation" onfocus="if(this.value=='Occupation'){this.value=''}" onblur="if(this.value==''){this.value='Occupation'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['EmploymentStatus'])){
					  
					  $def = 'Employment Status';
					  
				  } else {
					  
					  $def = $row_form['EmploymentStatus'];
				  }
				  ?>
                  <input name="employment-status" type="text" class="tfield" id="employment-status" onfocus="if(this.value=='Employment Status'){this.value=''}" onblur="if(this.value==''){this.value='Employment Status'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container-last-1">
                  <?php 
				  if(empty($row_form['CompanyNo'])){
					  
					  $def = 'Employee Number';
					  
				  } else {
					  
					  $def = $row_form['CompanyNo'];
				  }
				  ?>
                  <input name="company-no" type="text" class="tfield" id="company-no" onfocus="if(this.value=='Employee Number'){this.value=''}" onblur="if(this.value==''){this.value='Employee Number'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container-last-2">
                  <?php 
				  if(empty($row_form['LicenseNumber'])){
					  
					  $def = 'License Number';
					  
				  } else {
					  
					  $def = $row_form['LicenseNumber'];
				  }
				  ?>
                  <input name="license-code" type="text" class="tfield" id="license-code" onfocus="if(this.value=='License Number'){this.value=''}" onblur="if(this.value==''){this.value='License Number'}" value="<?php echo $def; ?>">
                </div>
                <!-- End Personal info section 2 -->
                
                <!-- Personal info section 3 -->
                <div class="tab-flat">Contact Info</div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Cell_1'])){
					  
					  $def = 'Cell Number 1';
					  
				  } else {
					  
					  $def = $row_form['Cell_1'];
				  }
				  ?>
                  <input name="cell1" type="text" class="tfield" id="cell1" onfocus="if(this.value=='Cell Number 1'){this.value=''}" onblur="if(this.value==''){this.value='Cell Number 1'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Telephone'])){
					  
					  $def = 'Landline Number';
					  
				  } else {
					  
					  $def = $row_form['Telephone'];
				  }
				  ?>
                  <input name="telephone" type="text" class="tfield" id="telephone" onfocus="if(this.value=='Landline Number'){this.value=''}" onblur="if(this.value==''){this.value='Landline Number'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Cell_2'])){
					  
					  $def = 'Cell Number 2';
					  
				  } else {
					  
					  $def = $row_form['Cell_2'];
				  }
				  ?>
                  <input name="cell2" type="text" class="tfield" id="cell2" onfocus="if(this.value=='Cell Number 2'){this.value=''}" onblur="if(this.value==''){this.value='Cell Number 2'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['Email'])){
					  
					  $def = 'Email Address';
					  
				  } else {
					  
					  $def = $row_form['Email'];
				  }
				  ?>
                  <input name="email" type="text" class="tfield" id="email" onfocus="if(this.value=='Email Address'){this.value=''}" onblur="if(this.value==''){this.value='Email Address'}" value="<?php echo $def; ?>">
                </div>

                <div class="field-container-last-1">
                  <?php 
				  if(empty($row_form['PostalAddress'])){
					  
					  $def = 'Postal Address';
					  
				  } else {
					  
					  $def = $row_form['PostalAddress'];
				  }
				  ?>
                  <textarea name="address" rows="3" class="tfield" id="address" onFocus="if(this.value=='Postal Address'){this.value=''}" onBlur="if(this.value==''){this.value='Postal Address'}"><?php echo $def; ?></textarea>
                </div>

                <div class="field-container-last-2">
                  <?php 
				  if(empty($row_form['ResidentialAddress'])){
					  
					  $def = 'Residential Address';
					  
				  } else {
					  
					  $def = $row_form['ResidentialAddress'];
				  }
				  ?>
                  <textarea name="address-res" rows="3" class="tfield" id="address-res" onFocus="if(this.value=='Residential Address'){this.value=''}" onBlur="if(this.value==''){this.value='Residential Address'}"><?php echo $def; ?></textarea>
              </div>
                <!-- End Personal info section 3 -->
                
                <!-- Misc info section 4 -->
              <div class="tab-flat">Contact Info</div>
                
                <div class="field-container">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="radio-field">
                    <tr>
                      <td width="135">Alcohol Consumption</td>
                      <td align="right" style="text-align:right">
                      <table border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                          <td><input type="radio" name="alcohol" id="alcohol-1" value="Yes" <?php if($row_form['AlcoholConsumption'] == 'Yes'){ echo 'checked'; } ?>></td>
                          <td><label for="alcohol-1">Yes</label></td>
                          <td><input type="radio" name="alcohol" id="alcohol-2" value="No" <?php if($row_form['AlcoholConsumption'] == 'No'){ echo 'checked'; } ?>></td>
                          <td><label for="alcohol-2">No</label></td>
                        </tr>
                      </table>
                      </td>
                    </tr>
                  </table>
                </div>
                
                <div class="field-container">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="radio-field">
                    <tr>
                      <td width="135">Smoker</td>
                      <td align="right" style="text-align:right">
                      <table border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                          <td><input type="radio" name="smoker" id="smoker-1" value="Yes" <?php if($row_form['Smoker'] == 'Yes'){ echo 'checked'; } ?>></td>
                          <td><label for="smoker-1">Yes</label></td>
                          <td><input type="radio" name="smoker" id="smoker-2" value="No" <?php if($row_form['Smoker'] == 'No'){ echo 'checked'; } ?>></td>
                          <td><label for="smoker-2">No</label></td>
                        </tr>
                      </table>
                      </td>
                    </tr>
                  </table>
                </div>

                <div class="field-container-address">
                  <?php 
				  if(empty($row_form['Skills'])){
					  
					  $def = 'Skills';
					  
				  } else {
					  
					  $def = $row_form['Skills'];
				  }
				  ?>
                  <textarea name="skills" class="tfield" id="skills" onFocus="if(this.value=='Skills'){this.value=''}" onBlur="if(this.value==''){this.value='Skills'}"><?php echo $def; ?></textarea>
                </div>
                <!-- End Misc info section 4 -->
                
                <!-- Personal info section 5 -->
                <div class="tab-flat">Next of Kin (Complete If Applicable)</div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['SpouseName'])){
					  
					  $def = 'Spouse Name';
					  
				  } else {
					  
					  $def = $row_form['SpouseName'];
				  }
				  ?>
                  <input name="spouse" type="text" class="tfield" id="spouse" onfocus="if(this.value=='Spouse Name'){this.value=''}" onblur="if(this.value==''){this.value='Spouse Name'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="radio-field">
                    <tr>
                      <td width="135">EmploymentStatus</td>
                      <td align="right" style="text-align:right">
                      <table border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                          <td><input type="radio" name="spouse-employment-status" id="spouse-employment-status-1" value="Employed" <?php if($row_form['SpouseEmploymentStatus'] == 'Employed'){ echo 'checked'; } ?>></td>
                          <td><label for="spouse-employment-status-1">Employed</label></td>
                          <td><input type="radio" name="spouse-employment-status" id="spouse-employment-status-2" value="Unemployed" <?php if($row_form['SpouseEmploymentStatus'] == 'Unemployed'){ echo 'checked'; } ?>></td>
                          <td><label for="spouse-employment-status-2">Unemployed</label></td>
                        </tr>
                      </table>
                      </td>
                    </tr>
                  </table>
                </div>
                
                <div class="field-container-address">
                  <?php 
				  if(empty($row_form['EmploymentDetails'])){
					  
					  $def = 'Employment Details';
					  
				  } else {
					  
					  $def = $row_form['EmploymentDetails'];
				  }
				  ?>
                  <textarea name="employment-details" class="tfield" id="employment-details" onFocus="if(this.value=='Employment Details'){this.value=''}" onBlur="if(this.value==''){this.value='Employment Details'}"><?php echo $def; ?></textarea>
                </div>
                <!-- End Misc info section 4 -->
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['NextOfKin_1'])){
					  
					  $def = 'Next Of Kin 1';
					  
				  } else {
					  
					  $def = $row_form['NextOfKin_1'];
				  }
				  ?>
                  <input name="kin1" type="text" class="tfield" id="kin1" onfocus="if(this.value=='Next Of Kin 1'){this.value=''}" onblur="if(this.value==''){this.value='Next Of Kin 1'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container">
                  <?php 
				  if(empty($row_form['NextOfKin_2'])){
					  
					  $def = 'Next Of Kin 2';
					  
				  } else {
					  
					  $def = $row_form['NextOfKin_2'];
				  }
				  ?>
                  <input name="kin2" type="text" class="tfield" id="kin2" onfocus="if(this.value=='Next Of Kin 2'){this.value=''}" onblur="if(this.value==''){this.value='Next Of Kin 2'}" value="<?php echo $def; ?>">
                </div>
                
                <div class="field-container-last-1">
                  <?php 
				  if(empty($row_form['KinTelephone_1'])){
					  
					  $def = 'Next Of Kin Telephone 1';
					  
				  } else {
					  
					  $def = $row_form['KinTelephone_1'];
				  }
				  ?>
                  <input name="kin-telephone-1" type="text" class="tfield" id="kin-telephone-1" onfocus="if(this.value=='Next Of Kin Telephone 1'){this.value=''}" onblur="if(this.value==''){this.value='Next Of Kin Telephone 1'}" value="<?php echo $def; ?>">
                </div>
                
              <div class="field-container-last-2">
                  <?php 
				  if(empty($row_form['KinTelephone_2'])){
					  
					  $def = 'Next Of Kin Telephone 2';
					  
				  } else {
					  
					  $def = $row_form['KinTelephone_2'];
				  }
				  ?>
                  <input name="kin-telephone-2" type="text" class="tfield" id="kin-telephone-2" onfocus="if(this.value=='Next Of Kin Telephone 2'){this.value=''}" onblur="if(this.value==''){this.value='Next Of Kin Telephone 2'}" value="<?php echo $def; ?>">
                </div>
                <!-- End Personal info section 5 -->
                
                <div class="field-container-comments">
                <table width="100%" border="0" cellspacing="3" cellpadding="2">
                  <tr>
                    <td valign="top"><input name="declaration" type="checkbox" id="declaration" value="1"></td>
                    <td>
                    <label for="declaration">
                    <strong>Declaration:</strong> I hereby declare that the details furnished above are true and correct to the best of my knowledge
                    and belief and I undertake to inform you of any changes therein, immediately. In case any of the above 
                    information is found to be false or untrue or misleading or misrepresenting, I am aware that I may be held liable.
                    </label>
                </td>
                  </tr>
                </table>
                </div>
                
              <input name="update" type="submit" class="btn-flat" value="Update" id="update">
                            
            </div>
            </form>
        </div>
    </div>
</body>
</html>








