<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

logout($con);

$edit = $_GET['Edit'];

if(isset($_POST['reset'])){
	
	header('Location: vat.php');
}

if(isset($_POST)){
	
   $form_data = array(
	  
	  'VatRate' => $_POST['vat'],
	  'StartDate' => $_POST['start'],
	  'EndDte' => $_POST['end']
	);
}

// INSERT
if(isset($_POST['insert']) && !empty($_POST['vat'])){
		
	dbInsert('tbl_vat', $form_data,$con);
}

// UPDATE
if(isset($_POST['update'])){
		
	dbUpdate('tbl_vat', $form_data, $where_clause="Id = '". $_GET['Edit'] ."'",$con);
}

if(isset($_GET['Delete'])){
	
	dbDelete('tbl_vat', $where_clause='Id='. $_GET['Delete'],$con);
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_vat WHERE Id = '$edit'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_vat ORDER BY StartDate DESC")or die(mysqli_error($con));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../../menu/styles.css">
      <script src="../../menu/script.js"></script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
      <link rel="stylesheet" media="all" type="text/css" href="jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="jquery-ui-sliderAccess.js"></script>
      
      <link rel="stylesheet" href="../../form-validation/css/normalize.css">
      <link rel="stylesheet" href="../../form-validation/css/style.css">
      
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
            <li><a href="#">Settings</a></li>
            <li><a href="#">System</a></li>
            <li><a href="#">Root Cause</a></li>
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
          <form action="" method="post" enctype="multipart/form-data" name="form1" id="form2"  class="uk-form bt-flabels js-flabels" data-parsley-validate data-parsley-errors-messages-disabled>
            <div id="list-brdr-supprt">
              <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td width="93" class="td-left">VAT Rate</td>
                  <td width="814" class="td-right">
                    <div class="bt-flabels__wrapper">
                      <input name="vat" type="text" class="tarea-100" value="<?php echo $row_form['VatRate']; ?>" autocomplete="off" data-parsley-required>
                      <span class="bt-flabels__error-desc-dd">Required</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="td-left">Start Date</td>
                  <td class="td-right">
                    <div class="bt-flabels__wrapper">
                      <input name="start" type="text" class="tarea-100" id="start" value="<?php echo $row_form['StartDate']; ?>" autocomplete="off" data-parsley-required>
                      <span class="bt-flabels__error-desc-dd">Required</span>
					   <script type="text/javascript">
                            $('#start').datepicker({
                            dateFormat: "yy-mm-dd"
                            });
                       </script>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="td-left">End Date</td>
                  <td class="td-right">
                    <div class="bt-flabels__wrapper">
                      <input name="end" type="text" class="tarea-100" id="end" value="<?php echo $row_form['EndDate']; ?>" autocomplete="off" data-parsley-required>
                      <span class="bt-flabels__error-desc-dd">Required</span>
					   <script type="text/javascript">
                            $('#end').datepicker({
                            dateFormat: "yy-mm-dd"
                            });
                       </script>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td align="right">
                  <?php if(isset($_GET[ 'Edit'])){ ?>
                  <input name="reset" type="submit" class="btn-new" id="reset" value="Reset" />
                  <input name="update" type="submit" class="btn-new" id="update" value="Update" />
                  <?php } else { ?>
                  <input name="insert" type="submit" class="btn-new" id="insert" value="Insert" />
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
              </tr>
              <tr>
                <td align="right">
                  <div id="list-border">
                    <table width="100%" border="0" cellpadding="4" cellspacing="1">
                      <tr>
                        <td class="td-header">VAT Rate</td>
                        <td width="200" class="td-header">Start Date</td>
                        <td width="200" class="td-header">End Date</td>
                        <td width="32" align="center" class="td-header-right">&nbsp;</td>
                        <td width="32" align="center" class="td-header-right">&nbsp;</td>
                      </tr>
                      <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                      <tr class="<?php echo ($ac_sw1++%2==0)?" even ":"odd "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                        <td>
                          <?php echo $row_list[ 'VatRate']; ?>%
                        </td>
                        <td>
                        	<?php echo $row_list[ 'StartDate']; ?>
                        </td>
                        <td>
                        	<?php echo $row_list[ 'EndDate']; ?>
                        </td>
                        <td align="center">
                          <a href="vat.php?Delete=<?php echo $row_list['Id']; ?>" class="delete"></a>
                        </td>
                        <td align="center">
                          <a href="vat.php?Edit=<?php echo $row_list['Id']; ?>" class="edit"></a>
                        </td>
                      </tr>
                      <?php } ?>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </form>
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
      <script src='https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.1.2/parsley.min.js'></script>
      <script src="../../form-validation/js/index.js"></script>
      
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