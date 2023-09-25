<?php 
session_start();
require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

$userid = $_SESSION['userid'];
$edit = $_GET['Edit'];

if(!isset($_SESSION['userid'])){
	
	header('Location: ../index.php');
	
	exit();
}

$query_scope = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '$userid'")or die(mysqli_error($con));
$row_scope = mysqli_fetch_array($query_scope);

$query_list = mysqli_query($con, "SELECT * FROM tbl_profile_work_duties WHERE TechId = '$userid'")or die(mysqli_error($con));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Sealink</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Include scripts -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../../i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
    <script type="text/javascript" src="../../jquery-ui-sliderAccess.js"></script>
    
    <script type="text/javascript">
        
        $(function(){
    
            $('.date-container > script').each(function(i){
                eval($(this).text());
            });
        });
        
    </script>
    
    <script type="text/javascript" src="../menu/js/responsivemultimenu.js"></script>
    
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
    
    <!-- Include styles -->
    <link rel="stylesheet" href="../menu/css/responsivemultimenu.css" type="text/css" />
    
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css" href="../../jquery-ui-timepicker-addon.css" />

    <!-- Include media queries -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href="../../css/mobile.css" rel="stylesheet" type="text/css" />

</head>
<body id="site">

    <div id="wrapper">

        <div id="content">
        
          <?php include('../menu/menu.php'); ?>
          
          <form action="" method="post" id="form-1">
          
          <div style="width:100%">
              
              <!-- Logged Medical Examinations -->
                  <div id="list-border" style="margin-top:10px">
                    <table width="100%" border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td colspan="2" class="td-header"><?php echo $row_scope['Position']; ?></td>
                      </tr>
                      <tr>
                        <td width="145" class="td-left">Date of Employment</td>
                        <td class="td-right"><?php echo $row_scope['EmploymentDate']; ?></td>
                      </tr>
                      <tr>
                        <td class="td-left">Period of Contract</td>
                        <td class="td-right"><?php echo $row_scope['ContractLength']; ?></td>
                      </tr>
                      <tr class="row<?php echo $i; ?>">
                        <td colspan="2" class="td-sub-header2">Work Scope</td>
                        
                        <?php 
						
						while($row_list = mysqli_fetch_array($query_list)){
							
							$i++;
						
						?>
                        
                      </tr>
                        <tr class="row<?php echo $i; ?>">
                        <td colspan="2" class="td-right"><?php echo $i; ?>. <?php echo $row_list['Duty']; ?></td>
                      </tr>
                      
                      <?php } ?>
                      
                    </table>
                  </div>
              <!-- End Logged Medical Examinations -->
                          
          </div>
          </form>
          
          </div>
        
    </div>
    
</body>
</html>








