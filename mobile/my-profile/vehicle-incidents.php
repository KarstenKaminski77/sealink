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

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_vir WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_profile_vir WHERE TechId = '$userid'")or die(mysqli_error($con));

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
              <?php if(mysqli_num_rows($query_list) >= 1){ ?>
                  <div id="list-border" style="margin-top:10px">
                    <table width="100%" border="0" cellspacing="1" cellpadding="0">
                            <tr>
                              <td colspan="3" class="td-header">
                                Logged Vehicle Incident Reports
                              </td>
                            </tr>
                            <tr>
                              <td width="80" class="td-sub-header2">Date</td>
                              <td class="td-sub-header2">Time</td>
                              <td width="15" class="td-sub-header2">&nbsp;</td>
                            </tr>
                            <?php
                            
                            $i = 0;
                            
                            while($row_list = mysqli_fetch_array($query_list)){
                                
                                $i++;
                            ?>
                                <tr>
                                  <td class="td-sub-sub-header"><?php echo $row_list['Date']; ?></td>
                                  <td class="td-sub-sub-header"><?php echo $row_list['Time']; ?></td>
                                  <td class="td-sub-sub-header-padding"><a href="#" class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                                </tr>
                                <tr class="row<?php echo $i; ?>" style="display: none">
                                  <td class="td-left">Operator</td>
                                  <td colspan="2" class="td-right"><?php echo $row_list['Operator']; ?></td>
                                </tr>
                                <tr class="row<?php echo $i; ?>" style="display: none">
                                  <td class="td-left">Driver</td>
                                  <td colspan="2" class="td-right"><?php echo $row_list['Driver']; ?></td>
                                </tr>
                                <tr class="row<?php echo $i; ?>" style="display: none">
                                  <td class="td-left">Nature of Incdt</td>
                                  <td colspan="2" class="td-right"><?php echo $row_list['Incident']; ?></td>
                                </tr>
                                <tr class="row<?php echo $i; ?>" style="display: none">
                                  <td class="td-left">Brief Desc.</td>
                                  <td colspan="2" class="td-right"><?php echo $row_list['Description']; ?></td>
                                </tr>
                                <tr class="row<?php echo $i; ?>" style="display: none">
                                  <td class="td-left">Action Taken</td>
                                  <td colspan="2" class="td-right"><?php echo $row_list['ActionTaken']; ?></td>
                                </tr>
                                <tr class="row<?php echo $i; ?>" style="display: none">
                                  <td class="td-left">Vehicle Reg</td>
                                  <td colspan="2" class="td-right"><?php echo $row_list['Registration']; ?></td>
                                </tr>
                                <tr class="row<?php echo $i; ?>" style="display: none">
                                  <td class="td-left">Vehicle Model</td>
                                  <td colspan="2" class="td-right"><?php echo $row_list['Model']; ?></td>
                                </tr>
                            <?php } ?>
                      </table>
                  </div>
              <?php } ?>
              <!-- End Logged Medical Examinations -->
                          
          </div>
          </form>
          
          </div>
        
    </div>
    
</body>
</html>








