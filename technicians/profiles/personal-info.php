<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$edit = $_GET['ProfileId'];

if(!isset($_COOKIE['userid'])){
	
	header('Location: ../../index.php');
	
	exit();
}

if(isset($_POST['reset'])){
	
	header('Location: personal-info.php');
	exit();
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_personal_info WHERE TechnicianId = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));

$query_techs = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));
$query_techs_dd = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC")or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../../css/fonts.css" rel="stylesheet" type="text/css" />
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
      
	  <script type="text/javascript">
  
          $(document).ready(function () {
              $(".toggler").click(function (e) {
                  e.preventDefault();
                  $('.row' + $(this).attr('data-prod-cat')).toggle();
              });
          });
  
      </script>
      
      
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
      
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
          <li><a href="#">Personnel</a></li>
          <li><a href="#">Profiles</a></li>
          <li><a href="index.php?ProfileId=<?php echo $_GET['ProfileId']; ?>"><?php profile_name($con, $_GET['ProfileId']); ?></a></li>
          <li><a href="#">Personal Info</a></li>
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
      <?php
	  
	  if(isset($_GET['ProfileId'])){
		  
		  $display = 'table-row';
		  $display2 = 'table';
		  $display3 = 'block';
		  
	  } else {
		  
		  $display = 'none'; 
		  $display2 = 'none'; 
		  $display3 = 'none'; 
	  }
	  
	  ?>
      
   <div id="main-wrapper">
   
        <form id="form2" name="form2" method="post" action="">
        
          <div id="list-border" style="display: <?php echo $display3; ?>; margin-bottom:20px">
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td colspan="4" class="td-header"><?php echo $row_form['Name']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td width="20%" class="td-left">Name</td>
                <td width="30%" class="td-right"><?php echo $row_form['Name']; ?></td>
                <td width="20%" class="td-left">Title</td>
                <td width="30%" class="td-right"><?php echo $row_form['Title']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Gender</td>
                <td class="td-right"><?php echo $row_form['Gender']; ?></td>
                <td class="td-left">Nationality</td>
                <td class="td-right"><?php echo $row_form['Nationality']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Race</td>
                <td class="td-right"><?php echo $row_form['Race']; ?></td>
                <td class="td-left">Home Language</td>
                <td class="td-right"><?php echo $row_form['Language']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Highest Level of Education</td>
                <td class="td-right"><?php echo $row_form['HighestEducation']; ?></td>
                <td class="td-left">Post Matric Qualifications</td>
                <td class="td-right"><?php echo $row_form['PostMatricQual']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Institution</td>
                <td class="td-right"><?php echo $row_form['Institution']; ?></td>
                <td class="td-left">Criminal Record</td>
                <td class="td-right"><?php echo $row_form['CriminalRecord']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td colspan="4" valign="top" class="td-left">If yes, provide information regarding record</td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td colspan="4" class="td-right"><?php echo $row_form['CriminalRecordComments']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Marital Status</td>
                <td class="td-right"><?php echo $row_form['MaritalStatus']; ?></td>
                <td class="td-left">No. of Dependants</td>
                <td class="td-right"><?php echo $row_form['Dependants']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Id Number</td>
                <td class="td-right"><?php echo $row_form['IdNo']; ?></td>
                <td class="td-left">Passport Number</td>
                <td class="td-right"><?php echo $row_form['PassportNo']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td colspan="4" valign="top" class="td-sub-header2">Employment Info</td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Occupation</td>
                <td class="td-right"><?php echo $row_form['Occupation']; ?></td>
                <td class="td-left">Employment Status</td>
                <td class="td-right"><?php echo $row_form['EmploymentStatus']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Employee Number</td>
                <td class="td-right"><?php echo $row_form['CompanyNo']; ?></td>
                <td class="td-left">License Number</td>
                <td class="td-right"><?php echo $row_form['LicenseNumber']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td colspan="4" class="td-sub-header2">Contact Info</td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Cell Number 1</td>
                <td class="td-right"><?php echo $row_form['Cell_1']; ?></td>
                <td class="td-left">Cell Number 2</td>
                <td class="td-right"><?php echo $row_form['Cell_2']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Landline Number</td>
                <td class="td-right"><?php echo $row_form['Telephone']; ?></td>
                <td class="td-left">Email Address</td>
                <td class="td-right"><?php echo $row_form['Email']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Postal Address</td>
                <td class="td-right"><?php echo $row_form['PostalAddress']; ?></td>
                <td class="td-left">Residential Address</td>
                <td class="td-right"><?php echo $row_form['ResidentialAddress']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td colspan="4" class="td-sub-header2">General Info</td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Alcohol Consumption</td>
                <td class="td-right"><?php echo $row_form['AlcoholConsumption']; ?></td>
                <td class="td-left">Smoker</td>
                <td class="td-right"><?php echo $row_form['Smoker']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Skills</td>
                <td colspan="3" class="td-right"><?php echo $row_form['Skills']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td colspan="4" class="td-sub-header2">Next of Kin</td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Spouse Name</td>
                <td class="td-right"><?php echo $row_form['SpouseName']; ?></td>
                <td class="td-left">Employment Status</td>
                <td class="td-right"><?php echo $row_form['SpouseEmploymentStatus']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Employment Details</td>
                <td colspan="3" class="td-right"><?php echo $row_form['EmploymentDetails']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Next of Kin 1</td>
                <td class="td-right"><?php echo $row_form['NextOfKin_1']; ?></td>
                <td class="td-left">Next of Kin 2</td>
                <td class="td-right"><?php echo $row_form['NextOfKin_2']; ?></td>
              </tr>
              <tr class="row1" style="display: <?php echo $display; ?>">
                <td class="td-left">Next of Kin 1 Telephone</td>
                <td class="td-right"><?php echo $row_form['KinTelephone_1']; ?></td>
                <td class="td-left">Next of Kin 2 Telephone</td>
                <td class="td-right"><?php echo $row_form['KinTelephone_2']; ?></td>
              </tr>
            </table>
          </div>
      </form>
        <table width="100%" border="0" cellspacing="3" cellpadding="2">
          <tr>
            <td valign="top"><label for="declaration"> <strong>Declaration:</strong> I hereby declare that the details furnished above are true and correct to the best of my knowledge
              and belief and I undertake to inform you of any changes therein, immediately. In case any of the above 
            information is found to be false or untrue or misleading or misrepresenting, I am aware that I may be held liable. </label></td>
          </tr>
        </table>
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