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

$form_data = array(
	
	'MyExplanation' => $_POST['my-exp'],
	
	);

if(isset($_POST['update'])){
	
	dbUpdate('tbl_profile_ncr', $form_data, $where_clause="Id = '". $edit ."'",$con);
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_profile_ncr WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_profile_ncr WHERE TechId = '$userid'")or die(mysqli_error($con));

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

              <!-- Update My Explanation -->
              <?php if(isset($_GET['Edit'])){ ?>
              
                  <div class="tab-flat-sub">Update My Explanation</div>
                  
                    <div class="field-container-comments">
                      <table width="100%" cellpadding="0" cellspacing="1">
                        <tr>
                          <td class="td-right" style="padding:0">
                            <textarea name="my-exp" rows="5" class="tfield mceEditor" id="my-exp"><?php echo $row_form['MyExplanation']; ?></textarea>
                          </td>
                        </tr>
                      </table>
              </div>
                    
                    <!-- Buttons -->
                    <input name="update" type="submit" class="btn-flat" value="Update">
                    <!-- End Buttons -->
                
                <?php } ?>
                <!-- End Update My Explanation -->
                
                <!-- Logged Medical Examinations -->
                <?php if(mysqli_num_rows($query_list) >= 1){ ?>
                    <div id="list-border" style="margin-top:30px">
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                              <tr>
                                <td colspan="5" class="td-header">
                                  Logged Non Conformance Reports</td>
                              </tr>
                              <tr>
                                <td colspan="3" class="td-sub-header2">Non Conformance</td>
                                <td width="25" class="td-sub-header2 td-header-no-padding">&nbsp;</td>
                                <td width="25" class="td-sub-header2 td-header-no-padding">&nbsp;</td>
                              </tr>
                              <?php
							  
							  $i = 0;
							  
							  while($row_list = mysqli_fetch_array($query_list)){
								  
								  $i++;
							  ?>
                                  <tr>
                                    <td colspan="3" class="td-sub-sub-header"><?php echo $row_list['NonConformance']; ?></td>
                                    <td class="td-sub-sub-header-padding"><a href="non-conformance.php?Edit=<?php echo $row_list['Id']; ?>" class="edit"></a></td>
                                    <td class="td-sub-sub-header-padding"><a href="#" class="toggler expand" data-prod-cat="<?php echo $i; ?>"></a></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td width="130" class="td-left">Date</td>
                                    <td colspan="4" class="td-right"><?php echo $row_list['Date']; ?></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td class="td-left">Details of Non Conformance</td>
                                    <td colspan="4" class="td-right"><?php echo $row_list['Details']; ?></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td class="td-left">My Explanation</td>
                                    <td colspan="4" class="td-right"><?php echo $row_list['MyExplanation']; ?></td>
                                  </tr>
                                  <tr class="row<?php echo $i; ?>" style="display: none">
                                    <td class="td-left">Final Comment</td>
                                    <td colspan="4" class="td-right"><?php echo $row_list['FinalComment']; ?></td>
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








