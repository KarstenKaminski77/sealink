<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');
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
            <li><a href="#">Personnel</a></li>
            <li><a href="#">Profiles</a></li>
            <li><a href="#"><?php profile_name($con, $_GET['ProfileId']); ?></a></li>
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
        
        <div class="profile-name"><?php profile_name($con, $_GET['ProfileId']); ?>s Profile</div>
        
        <a href="personal-info.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-1"></a>
        <a href="medicals.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-2"></a>
        <a href="idps.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-3"></a>
        <a href="non-conformance.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-4"></a>
        <a href="general-incidents.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-5"></a>
        <a href="vehicle-incidents.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-6"></a>
        <a href="vehicle-allocation.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-7"></a>
        <a href="tool-allocation.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-8"></a>
        <a href="equipment-allocation.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-9"></a>
        <a href="achievements.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-10"></a>
        <a href="work-scope.php?ProfileId=<?php echo $_GET['ProfileId']; ?>" class="profile-icon-11"></a>
        <a href="kpis.php?ProfileId=<?php echo $_GET['ProfileId']; ?>&Month=<?php echo date('m'); ?>&Week=<?php echo date('W'); ?>" class="profile-icon-12"></a>
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