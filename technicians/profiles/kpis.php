<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$edit = $_GET['Edit'];

if(!isset($_COOKIE['userid'])){
	
	header('Location: ../../index.php');
	
	exit();
}

if(isset($_POST['reset'])){
	
	header('Location: idps.php');
	exit();
}

	$query_week = "
	SELECT
		tbl_sla_history.Id,
		tbl_sla_history.JobId,
		tbl_sla_history.JobNo,
		tbl_sla_history.SlaStart,
		tbl_sla_history.SlaEnd,
		tbl_sla_history.DateCompleted,
		tbl_sla_history.EmailFrom,
		tbl_sla_history.EmailTo,
		tbl_sla_history.EmailBody,
		tbl_sla_history.Sender,
		tbl_history_alerts.TechnicianId
	FROM
		tbl_sla_history
	INNER JOIN tbl_history_alerts ON tbl_sla_history.JobId = tbl_history_alerts.JobId
	WHERE
		tbl_history_alerts.TechnicianId = '". $_GET['ProfileId'] ."'
	AND
		WEEKOFYEAR(tbl_sla_history.DateCompleted) = '". $_GET['Week'] ."'
	AND
	    tbl_sla_history.DateCompleted != ''";
	
$query_week = mysqli_query($con, $query_week)or die(mysqli_error($con));
$row_week = mysqli_fetch_array($query_week);

	$query_month = "
	SELECT
		tbl_sla_history.Id,
		tbl_sla_history.JobId,
		tbl_sla_history.JobNo,
		tbl_sla_history.SlaStart,
		tbl_sla_history.SlaEnd,
		tbl_sla_history.DateCompleted,
		tbl_sla_history.EmailFrom,
		tbl_sla_history.EmailTo,
		tbl_sla_history.EmailBody,
		tbl_sla_history.Sender,
		tbl_history_alerts.TechnicianId
	FROM
		tbl_sla_history
	INNER JOIN tbl_history_alerts ON tbl_sla_history.JobId = tbl_history_alerts.JobId
	WHERE
		tbl_history_alerts.TechnicianId = '". $_GET['ProfileId'] ."'
	AND
		WEEKOFYEAR(tbl_sla_history.DateCompleted) = '". $_GET['Week'] ."'
	AND
	    tbl_sla_history.DateCompleted != ''";
	
$query_month = mysqli_query($con, $query_month)or die(mysqli_error($con));
$row_month = mysqli_fetch_array($query_month);

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
      
	  <script type="text/javascript" src="../../tinymce/js/tinymce/tinymce.min.js"></script>
      <script>
          tinymce.init({
		  menubar:false,
		  mode : "specific_textareas",
		  editor_selector : "mceEditor",
          theme: "modern",
          browser_spellcheck : true,
          plugins: [
              ["autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
              ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
              ["save table contextmenu directionality emoticons template paste"]
          ],
          add_unload_trigger: true,
          schema: "html5",
          inline: false,
          toolbar: "undo redo bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
          statusbar: false
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
          <li><a href="#">Seavest Asset Management</a></li>
          <li><a href="#">Personnel</a></li>
          <li><a href="i#">Profiles</a></li>
          <li><a href="index.php?ProfileId=<?php echo $_GET['ProfileId']; ?>"><?php profile_name($con, $_GET['ProfileId']); ?></a></li>
          <li><a href="#">IDP's</a></li>
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
       
            <form id="form2" name="form2" method="post" action="">
            
              <div id="list-border"></div>
    
            <div  style="margin-bottom:20px">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="row1" style="display: <?php echo $display2; ?>">
              <tr>
                <td width="50%" align="right"><table width="75%" border="0" align="center" cellpadding="3" cellspacing="1">
                  <tr>
                    <td colspan="2" class="td-header">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td align="center">Week <?php echo $_GET['Week']; ?></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td width="31%" class="td-left">Jobs Complete</td>
                    <td width="69%" class="td-right"><?php echo mysqli_num_rows($query_week); ?></td>
                  </tr>
                  <tr>
                    <td class="td-left">In SLA</td>
                    <td class="td-right">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="td-left">Out of SLA</td>
                    <td class="td-right">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="td-left">&nbsp;</td>
                    <td class="td-right">&nbsp;</td>
                  </tr>
                </table></td>
                <td width="50%" align="right">&nbsp;</td>
              </tr>
            </table>
          </div>
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
  mysqli_free_result($query_week);
  mysqli_free_result($query_month);
  mysqli_free_result($query_user_menu);
?>