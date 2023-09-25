<?php
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php');

require_once("../dropdown/dbcontroller.php");
$db_handle = new DBController();

$query ="SELECT * FROM tbl_companies ORDER BY Name ASC";
$results = $db_handle->runQuery($query);

if(isset($_POST['master_area'])){

	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];

} else {

	$areaid = $_SESSION['areaid'];
}

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '". $_GET['Company'] ."' AND CatId = '7' ORDER BY SubCat ASC")or die(mysqli_error($con));
$sla_rows = mysqli_num_rows($query_sla_sub_cat);

$sql = "
	SELECT
		Id,
		Name
	FROM
		tbl_systems
	WHERE
		Id != 3
	ORDER BY
		Id ASC
	";

$query_system = mysqli_query($con, $sql)or die(mysqli_error($con));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>

      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />

	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

      <link rel="stylesheet" href="../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/script.js"></script>

      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>

	  <script>

		function getSites(val) {
			$.ajax({
			type: "POST",
			url: "../dropdown/get-sites.php",
			data:'company_id='+val,
			success: function(data){
				$("#site-list").html(data);
			}
			});
		}

		function getSlaSub(val) {
			$.ajax({
			type: "POST",
			url: "../dropdown/get-quote-sla-sub.php",
			data:'company_id='+val,
			success: function(data){
				$("#sla-list").html(data);

			}
			});
		}

		function selectCountry(val) {
		$("#search-box").val(val);
		$("#suggesstion-box").hide();
		}
      </script>

      <link rel="stylesheet" href="../form-validation/css/normalize.css">
      <link rel="stylesheet" href="../form-validation/css/style.css">

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
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->

      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Estimates</a></li>
            <li><a href="#">Quotations</a></li>
            <li><a href="#">Create New</a></li>
            <li></li>
         </ul>
      </div>
      <!-- End Breadcrumbs -->

      <!-- Search -->
      <div class="search-container">
        <form name="form1" id="form1" method="post" action="search.php">
          <input name="search-field" type="text" class="search-top" id="search-field" placeholder="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->

      <!-- Main Form -->
      <div id="main-wrapper">
      <form method="post" action="new-process.php" name="f1" class="uk-form bt-flabels js-flabels" data-parsley-validate data-parsley-errors-messages-disabled>
        <div id="list-border">
          <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
            <tr>
              <td width="15%" nowrap="nowrap" class="td-left">Company </td>
              <td width="35%" nowrap="nowrap" class="td-right">

                <div class="bt-flabels__wrapper">
                  <select name="company" id="country-list" class="tarea-100" onChange="getSites(this.value); getSlaSub(this.value);" autocomplete="off" data-parsley-required>
                    <option value="">Oil Company</option>
                    <?php foreach($results as $company){ ?>
                    <option value="<?php echo $company["Id"]; ?>" <?php if($company["Id"] == $_POST['company']){ echo 'selected="selected"'; } ?>><?php echo $company["Name"]; ?></option>
                    <?php } ?>
                  </select>
                  <span class="bt-flabels__error-desc-dd">Required</span>
                </div>

              </td>
              <td width="15%" nowrap="nowrap" class="td-left">Site</td>
              <td width="35%" nowrap="nowrap" class="td-right">

                <div class="bt-flabels__wrapper">
                  <select name="site" id="site-list" class="tarea-100" autocomplete="off" data-parsley-required>
                    <option value="">Site...</option>
                  </select>
                  <span class="bt-flabels__error-desc-dd">Required</span>
                </div>

              </td>
            </tr>
            <tr>
              <td valign="top" class="td-left">Description</td>
              <td colspan="3" class="td-right">
                <div class="bt-flabels__wrapper">
                  <textarea name="description" cols="40" rows="3" class="tarea-100" id="description" autocomplete="off" data-parsley-required><?php echo $_GET['Description']; ?></textarea>
                  <span class="bt-flabels__error-desc-dd">Required</span>
                </div>
              </td>
            </tr>
            <tr>
              <td valign="top" class="td-left">System</td>
              <td colspan="3" class="td-right">
                <div class="bt-flabels__wrapper">
                  <select name="system" class="tarea-100" data-parsley-required>
                  	<option value="">Select one...</option>
                    <?php while($row_system = mysqli_fetch_array($query_system)){ ?>
                    	<option value="<?php echo $row_system['Id']; ?>"><?php echo $row_system['Name']; ?></option>
                    <?php } ?>
                  </select>
                  <span class="bt-flabels__error-desc-dd">Required</span>
                </div>
              </td>
            </tr>
          </table>
        </div>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right"><input name="Submit2" type="submit" class="btn-new" value="Next" /></td>
          </tr>
        </table>
      </form>
   </div>
      <!-- End Main Form -->

      <!-- Footer -->
   <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->

<script src='https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.1.2/parsley.min.js'></script>
<script src="../form-validation/js/index.js"></script>

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
