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
      
	  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
      <script>!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');</script>
      <script type="text/javascript" src="../../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
      <script type="text/javascript" src="../../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
      <link rel="stylesheet" type="text/css" href="../../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
      
	  <script type="text/javascript">
      
        jQuery(document).ready(function() {	
            
					$(".various3").fancybox({
					  'hideOnContentClick' : true,
					  'width' : 700,
					  'type' : 'iframe',
					  'padding' : 0,
                      'onClosed' : function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
					  parent.location.reload(true);
                      },				  
			          'onComplete' : function() {
						$('#fancybox-frame').load(function() { // wait for frame to load and then gets it's height
						  $('#fancybox-content').height($(this).contents().find('body').height()+18);
						});
					  }
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
      <?php include('../../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Job Cards</a></li>
            <li><a href="#">In Progress</a></li>
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
	  <?php
      if(isset($_GET['Date'])){
		  
		  $date = explode('-', $_GET['Date']);
		  $month = $date[0];
		  $year = $date[1];
		  
		  $date2 = date('d') .'-'. $_GET['Date'];
		  
		  echo '<div class="cal-header">';
		  echo '<a href="index.php?Date='. date("m-Y", strtotime("$date2 - 1 months")) .'" class="arrow-left"></a>';
          echo '<span class="cal-month">'. date("F Y", mktime(0, 0, 0, $month, 1, $year)) .'</span>';
		  echo '<a href="index.php?Date='. date("m-Y", strtotime("$date2 + 1 months")) .'" class="arrow-right"></a>';
		  echo '</div>';
          
      } else {
          
		  $date = date('d-m-Y');
		  
		  echo '<div class="cal-header">';
		  echo '<a href="index.php?Date='. date("m-Y", strtotime("$date - 1 months")) .'" class="arrow-left"></a>';
          echo '<span class="cal-month">'. date('F Y', strtotime(date('M Y'))) .'</span>';
		  echo '<a href="index.php?Date='. date("m-Y", strtotime("$date + 1 months")) .'" class="arrow-right"></a>';
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
		
		echo draw_calendar($con,$month,$year); 
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