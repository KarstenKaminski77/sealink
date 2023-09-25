<?php
session_start();

require_once('../../functions/functions.php');

logout_engineer($con);

$catid = $_GET['Cat'];
$statusid = $_GET['Status'];
$userid = $_COOKIE['userid'];

if(isset($_SESSION['areaid'])){
	
	$areaid = $_SESSION['areaid'];
}

// Jobs Logged Pie Chart
$image = imagecreatetruecolor(588, 140);

$white = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $white);

// allocate some colors
$blue = imagecolorallocate($image, 55, 127, 199);
$yellow = imagecolorallocate($image, 255, 255, 0);
$orange = imagecolorallocate($image, 255, 165, 0);
$red = imagecolorallocate($image, 255, 0, 0);
$green = imagecolorallocate($image, 66, 190, 68);
$darkblue = imagecolorallocate($image, 0, 93, 155);
$darkyellow = imagecolorallocate($image, 196, 196, 0);
$darkorange = imagecolorallocate($image, 208, 135, 1);
$darkred = imagecolorallocate($image, 209, 2, 2);
$darkgreen = imagecolorallocate($image, 2, 135, 5);

$black = imagecolorallocate($image, 102, 102, 102);


//Set Path to Font File
$font_path = 'fonts/arial.ttf';

if(isset($_POST['search'])){
	
	$where = " AND tbl_jc.JobNo = '". $_POST['search'] ."'";
}

$query_pieces = "
	SELECT
		COUNT(tbl_jc.Id) AS Faults,
		tbl_sla_subcat.SubCat
	FROM
		tbl_jc
	INNER JOIN tbl_sla_subcat ON tbl_sla_subcat.Id = tbl_jc.SlaSubCatId
	WHERE
		(
			tbl_jc. STATUS = '1'
			OR tbl_jc. STATUS = '2'
			OR tbl_jc. STATUS = '4'
			OR tbl_jc. STATUS = '17'
			OR tbl_jc. STATUS = '19'
			OR tbl_jc. STATUS = '20'
		)
	AND tbl_jc.CompanyId = '6'
	AND tbl_jc.SlaCatId > '1'
	$where
	GROUP BY
		tbl_sla_subcat.SubCat";
		
if(isset($_GET['Cat'])){
	
	$query_pieces = "
		SELECT
			Count(tbl_jc.Id) AS Faults,
			tbl_sla_subcat.SubCat,
			tbl_status.`Status`
		FROM
			tbl_jc
		INNER JOIN tbl_sla_subcat ON tbl_sla_subcat.Id = tbl_jc.SlaSubCatId
		INNER JOIN tbl_status ON tbl_jc.`Status` = tbl_status.Id
		WHERE
			(
				tbl_jc. STATUS = '1'
				OR tbl_jc. STATUS = '2'
				OR tbl_jc. STATUS = '4'
				OR tbl_jc. STATUS = '17'
				OR tbl_jc. STATUS = '19'
				OR tbl_jc. STATUS = '20'
			)
		AND tbl_jc.CompanyId = '6'
		AND tbl_jc.SlaSubCatId = '$catid'
		$where
		GROUP BY
			tbl_jc.`Status`";
}

if(isset($_GET['Status'])){
	
	$query_pieces = "
		SELECT
			Count(tbl_jc.Id) AS Faults,
			tbl_sla_subcat.SubCat,
			tbl_status.`Status`
		FROM
			tbl_jc
		INNER JOIN tbl_sla_subcat ON tbl_sla_subcat.Id = tbl_jc.SlaSubCatId
		INNER JOIN tbl_status ON tbl_jc.`Status` = tbl_status.Id
		WHERE
			(
				tbl_jc. STATUS = '1'
				OR tbl_jc. STATUS = '2'
				OR tbl_jc. STATUS = '4'
				OR tbl_jc. STATUS = '17'
				OR tbl_jc. STATUS = '19'
				OR tbl_jc. STATUS = '20'
			)
		AND tbl_jc.CompanyId = '6'
		AND tbl_jc.SlaSubCatId = '$catid'
		$where
		GROUP BY
			tbl_jc.`Status`";
}
	
$query_pieces = mysqli_query($con, $query_pieces)or die(mysqli_query($con));
$numrow_piece = mysqli_num_rows($query_pieces);

$total = 0;
$faults = array();
$cat = array();

while($row_pieces = mysqli_fetch_array($query_pieces)){
	
	$total += $row_pieces['Faults'];
	$_SESSION['total'] = $total;
	
	if(isset($_GET['Cat']) || isset($_GET['SubCat'])){
		
		$string = $row_pieces['Status'];
		
	} else {
		
		$string = $row_pieces['SubCat'];
	}
	
	array_push($faults, $row_pieces['Faults']);
	array_push($cat, $string);
	
	$darkcolour = array($darkblue, $darkyellow, $darkorange, $darkred, $darkgreen);
	$colour = array($blue, $yellow, $orange, $red, $green);
}
	
// make the 3D effect
for ($i = 75; $i > 60; $i--) {
	
	$_SESSION['start'] = 0;
	$percentages = array(); 
	
	for($c=0;$c<count($faults);$c++){
		
		$start = $_SESSION['start'];
		$percent = ($faults[$c] / $_SESSION['total']) * 100;
		$degrees = $percent / 100 * 360;
		
		array_push($percentages, $percent);
		
		if(isset($_SESSION['start'])){
			
			$start = $_SESSION['start'];
			$end = $start + $degrees;
			
		} else {
			
			$start = 0;
			$end = $percent;
		} 

		imagefilledarc($image, 100, $i, 150, 75, $start, $end, $darkcolour[$c], IMG_ARC_PIE);
		
		$_SESSION['start'] = $end;
		
	}
}

$i = 0;
for($c=0;$c<count($faults);$c++){
	
	$i++;
	
	$start = $_SESSION['start'];
	$percent = ($faults[$c] / $_SESSION['total']) * 100;
	$degrees = $percent / 100 * 360;
	
	if(isset($_SESSION['start'])){
		
		$start = $_SESSION['start'];
		$end = $start + $degrees;
		
	} else {
		
		$start = 0;
		$end = $percent;
	} 
	
	// Print Text On Image
	imagettftext($image, 10, 0, 235, $i * 20 + 15, $black, $font_path, round($percentages[$c]) .'% '. $cat[$c]);
	
	// Draw Rectangle
	imagefilledrectangle($image, 215, $i * 20 + 5, 225, $i * 20 + 15, $colour[$c]);
	
	// Draw Pie Slice
	imagefilledarc($image, 100, 60, 150, 75, $start, $end, $colour[$c], IMG_ARC_PIE);
	
	$_SESSION['start'] = $end;
	
}

// flush image
header('Content-type: image/jpg');
imagepng($image,'images/chart.png');
imagedestroy($image);
	  
header('Content-Type: text/html');

$query_subcat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '6' ORDER BY SubCat ASC")or die(mysqli_error($con));
$numrowws_subcat = mysqli_num_rows($query_subcat);

if(isset($_POST['search'])){
	
	$where = " tbl_jc.JobNo = '". $_POST['search'] ."'";
	
} else {
	
	$where = " tbl_jc.`Status` = '$statusid' AND tbl_jc.SlaSubCatId = '$catid'";
}

$query_jobs = "
	SELECT
		tbl_jc.JobNo,
		tbl_jc.QuoteNo,
		tbl_sites.`Name`,
		tbl_jc.JobId,
		tbl_jc.Date2,
		tbl_jc.QuoteNo
	FROM
		tbl_jc
	INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
	WHERE
	$where
	GROUP BY
		tbl_jc.JobId";

$query_jobs = mysqli_query($con, $query_jobs)or die(mysqli_error($con));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Seavest Reports</title>
  
  <link href="css/layout.css" rel="stylesheet" type="text/css">
  
  <script type="text/javascript" src="../javascripts/jquery.js"></script>
  
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
  
  <script>
  jQuery(document).resize(function () {
      var screen = $(window)    
      if (screen.width < 1200) {
		  
		  var div = $(".css-table");
		  var tmp = div.children().clone();
		  var parent = div.parent();
		  div.remove();
		  tmp.appendTo(parent);
      }
  });
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
    <div id="title"></div>
    <table border="0" align="right" cellpadding="0" cellspacing="0">
     <tr>
     <td><?php engineer_area_select($con); ?></td>
     <td><div id="tab-user"><b>User:</b> <?php echo $_COOKIE['name']; ?></div></td>
     <td><?php engineer_logout_link($con); ?></td>
     </tr>
     <tr>
     <td colspan="3">
        <form id="form1" name="form1" method="post" action="">
          <input name="search" type="text" class="search-top" id="search" value="Search Reference No..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
     </td>
     </tr>
    </table>
  </div>
  <!-- End Banner -->
  
  <!-- Current Faults -->
  <?php echo '<div class="css-table">';
    $z = 1;
    
    while($row_subcat = mysqli_fetch_array($query_subcat)){
		
		$z++;
		
		if($z == 4 || $z == 6){
			
			echo '</div><div class="css-table">';
		}
  ?>
    
  <div class="tab">
  <?php
  if($row_subcat['Id'] == $_GET['Cat']){
	  
	  echo "<a href=\"index.php\" class=\"tab-title-selected\">". $row_subcat['SubCat'] ."</a>";
	  
  } else {
	  
	  echo "<a href=\"index.php?Cat=". $row_subcat['Id'] ."#View\" class=\"tab-title\">". $row_subcat['SubCat'] ."</a>";
  }
  ?>
	      
    <?php
      $category = $row_subcat['Id'];
      
      $i = 0;
	  $query_icons = "
		SELECT * FROM
			tbl_menu_links
		WHERE
		   Status = '1'
		OR Status = '2'
		OR Status = '4'
		OR Status = '17'
		OR Status = '19'
		OR Status = '20'
		ORDER BY
			OrderBy ASC";
	    
      $query_icons = mysqli_query($con, $query_icons)or die(mysqli_error($con));
      while($row_icons = mysqli_fetch_array($query_icons)){
		  
		  $i++;
          $c = 34 + ($i *5);
          $subcategory = $row_icons['Id'];
		  $status = $row_icons['Status'];

		  $query_count = "
			SELECT
				tbl_jc.Id,
				tbl_jc.SlaCatId,
				tbl_jc.Status
			FROM
				tbl_jc
			WHERE
				tbl_jc.SlaSubCatId = '$category'
			AND 
			    tbl_jc.CompanyId = '6'
			AND 
			    tbl_jc.`Status` = '$status'
		    GROUP BY 
			    tbl_jc.JobId
			";

          $query_count = mysqli_query($con, $query_count)or die(mysqli_error($con));
          $row_count = mysqli_fetch_array($query_count);
		  $numrows_count = mysqli_num_rows($query_count);
		  
		  if($row_count['Fault'] >= 1){
			  
			  $faults = $row_count['Fault'];
			  
		  } else {
			  
			  $faults = '0';
		  }
          
          if($row_count['Id'] >= 1){
              
			  if($_GET['Cat'] == $row_subcat['Id'] && $_GET['Status'] == $row_count['Status'] && isset($_GET['Status'])){
				  
				  $class = 'icon-selected';
				  $url = 'index.php';
				  $colour = '';
				  
			  } else {
				  
				  $class = 'icon';
				  $url = 'index.php?Cat='. $row_subcat['Id'] .'&Status='. $row_count['Status'] .'#View';
				  $colour = 'style="background:hsla(358, 86%, '. $c .'%, 1)"';
			  }
			  
			  echo '<a href="'. $url .'" class="'. $class .'" '. $colour .'><b class="counter">'. $numrows_count .'</b><br />'. $row_icons['Condensed'] .'</a>';
              
          } else {
			  
			  if($_GET['Cat'] == $row_subcat['Id'] && $_GET['Status'] == $row_count['Status'] && isset($_GET['Status'])){
				  
				  $class = 'icon-selected';
				  $url = 'index.php';
				  $colour = '';
				  
			  } else {
				  
				  $class = 'icon';
				  $url = 'index.php?Cat='. $row_subcat['Id'] .'&Status='. $row_count['Status'] .'#View';
				  $colour = 'style="background:hsla(204, 100%, '. $c .'%, 1)"';
			  }
      
			  echo '<a href="'. $url .'" class="'. $class .'" '. $colour .'><b class="counter">'. $numrows_count .'</b><br />'. $row_icons['Condensed'] .'</a>';              
          }
		  			  
      ?>
    <?php } ?>
  </div>
  <?php } ?>
  </div>
  <!-- End Current Faults -->

  <!-- End Fault Stats -->
  <div class="css-table">
  
    <?php if(isset($_GET['Status']) || isset($_POST['search'])){ ?>
    <!-- Fault Ages -->
    <div class="tab">
      <div id="tab-title"><?php fault_types($con, $_GET['Cat'], $_GET['Status']); ?><a name="View" id="View"></a></div>
      
      <?php 
	  while($row_jobs = mysqli_fetch_array($query_jobs)){
		  
		  $class = '';
		  
		  if($row_jobs['Date2'] < date('Y-m-d')){
			  
			  $class = 'class="over-sla"';
		  }
	  ?>
      <div class="age_row">
        <div class="bullet"></div>
		<div style="display:inline-block; width:90px" <?php echo $class; ?>><?php echo $row_jobs['JobNo']; ?></div>
		<div style="display:inline-block; width:50px" <?php echo $class; ?>><?php echo $row_jobs['QuoteNo']; ?></div>
		<div style="display:inline-block; width:90px" <?php echo $class; ?>><?php echo $row_jobs['Date2']; ?></div>
		<div style="display:inline-block; width:250px" <?php echo $class; ?>><?php echo $row_jobs['Name']; ?></div>
      </div>
      <?php } ?>
      
    </div>
    <!-- End Fault Ages -->
  
    <?php } else { ?>
    <!-- Fault Ages -->
    <div class="tab">
      <div id="tab-title"><?php fault_types($con, $_GET['Cat'], $_GET['Status']); ?> <a name="View" id="View"></a></div>
      
      <div class="age_row" style="padding-top:15px">
        <div class="bullet"></div>Faults Logged Today
		<div class="age_count"><?php logged_today($con, $_GET['Cat'], $_GET['SubCat']); ?></div>
      </div>
      
      <div class="age_row">
        <div class="bullet"></div>
        Total Faults
		<div class="age_count"><?php total_faults($con, $_GET['Cat'], $_GET['SubCat']); ?></div>
      </div>
      
      <div class="age_row">
        <div class="bullet"></div>
        Faults Due Today
		<div class="age_count"><?php due_today($con, $_GET['Cat'], $_GET['SubCat']); ?></div>
      </div>
      
      <div class="age_row">
        <div class="bullet"></div>
        Overdue Faults
		<div class="age_count"><?php overdue($con, $_GET['Cat'], $_GET['SubCat']); ?></div>
      </div>
      
    </div>
    <!-- End Fault Ages -->
    <?php } ?>
    
    <div class="tab">
      <div id="tab-title"><?php fault_types($con, $_GET['Cat'], $_GET['SubCat']); ?></div>
      <div class="chart_row">
      <?php if($numrow_piece >= 1){ ?>
		<img src="images/chart.png" />
      <?php } else { ?>
        <span class="no-fault">No Faults Logged</span>
      <?php } ?>
      </div>      
    </div>
    
    </div>
    <!-- End Fault Stats -->
  
  
  <!-- Footer -->
  <div id="footer">
  </div>
  <!-- End Footer -->
</body>
</html>
<?php  
  mysqli_close($con);
  mysqli_free_result($query_faults); 
  mysqli_free_result($query_count);
  mysqli_free_result($query_frequent);
  mysqli_free_result($query_pieces);
  mysqli_free_result($query_icons);
  mysqli_free_result($query_pieces);
  mysqli_free_result($query_priorities_count);
?>
