<?php

session_start();

require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

logout($con);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link rel="icon" href="../../favicon.ico" type="image/x-icon" />
      
      <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      <link href="../../css/calendar.css" rel="stylesheet" type="text/css" />
      
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
      
	  <script type="text/javascript" src="../../fancyBox-2/lib/jquery-1.10.1.min.js"></script>
      <script type="text/javascript" src="../../fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <script type="text/javascript" src="../../fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
      <link rel="stylesheet" type="text/css" href="../../fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />
  
      <script type="text/javascript">
          $(document).ready(function() {
  
              $('.fancybox').fancybox({
			  
				  autoSize    : true, 
				  closeClick  : false, 
				  fitToView   : false, 
				  openEffect  : 'none', 
				  closeEffect : 'none', 
				  type : 'iframe' 
			  });
  
  
          });
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
      <?php include('../../menu/menu-guest.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">BP</a></li>
            <li><a href="#">View Calendar</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search --><!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
	  <?php
      if(isset($_GET['Date'])){
		  
		  $date = explode('-', $_GET['Date']);
		  $month = $date[0];
		  $year = $date[1];
		  
		  $date2 = date('d') .'-'. $_GET['Date'];
		  
		  echo '<div class="cal-header">';
		  echo '<a href="bp-calendar.php?Date='. date("m-Y", strtotime("$date2 - 1 months")) .'" class="arrow-left"></a>';
          echo '<span class="cal-month">'. date("F Y", mktime(0, 0, 0, $month, 1, $year)) .'</span>';
		  echo '<a href="bp-calendar.php?Date='. date("m-Y", strtotime("$date2 + 1 months")) .'" class="arrow-right"></a>';
		  echo '</div>';
          
      } else {
          
		  $date = date('d-m-Y');
		  
		  echo '<div class="cal-header">';
		  echo '<a href="bp-calendar.php?Date='. date("m-Y", strtotime("$date - 1 months")) .'" class="arrow-left"></a>';
          echo '<span class="cal-month">'. date('F Y', strtotime(date('M Y'))) .'</span>';
		  echo '<a href="bp-calendar.php?Date='. date("m-Y", strtotime("$date + 1 months")) .'" class="arrow-right"></a>';
		  echo '</div>';
      }
      ?>
        <?php 
		if(isset($_GET['Date'])){
			
			$date = explode('-', $_GET['Date']);
			$month = $date[0];
			$year = $date[1];
			
		} else {
			
			$month = date('m');
			$year = date('Y');
		}
		
		echo draw_calendar_company($con,$month,$year); 
		?>
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../Reports/Engineers/images/seavest.png" width="146" height="25" /></a></div>
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