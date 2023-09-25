<?php
session_start();

require_once('../../Connections/seavest.php');
require_once('../../functions/functions.php');

logout($con);

$edit = $_GET['Edit'];

if (isset($_POST['reset'])) {

  header('Location: user-accounts.php');
}

if (isset($_POST)) {

  $form_data = array(

    'Name' => addslashes($_POST['name']),
    'Username' => addslashes($_POST['username']),
    'Password' => addslashes($_POST['password']),
    'Telephone' => addslashes($_POST['telephone']),
    'Email' => $_POST['email'],
    'EditHistory' => $_POST['history'],
    'QuoteInv' => $_POST['qs-inv'],
    'OverrideSLA' => $_POST['sla'],
    'UserLevel' => '1',
    'Contractor' => $_POST['contractor'],
    'ChangeSystems' => $_POST['toggle-system']
  );
}

// INSERT
if (isset($_POST['insert']) && !empty($_POST['menu'])) {

  dbInsert('tbl_users', $form_data, $con);

  $query = mysqli_query($con, "SELECT * FROM tbl_users ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
  $row = mysqli_fetch_array($query);

  $userid = $row['Id'];

  // Systems
  for ($i = 0; $i < count($_POST['system']); $i++) {

    $systemid = $_POST['system'][$i];
    $def = $_POST['systemDef'][$systemid];

    if ($def == 1) {
      $form_data['SystemId'] = $systemid;
      dbUpdate('tbl_users', $form_data, $where_clause = "Id = '" . $userid . "'", $con);
    }

    mysqli_query($con, "INSERT INTO tbl_system_relation (SystemId,UserId,Def) VALUES ('$systemid','$userid','$def')") or die(mysqli_error($con));
  }

  // Areas
  for ($i = 0; $i < count($_POST['area']); $i++) {

    $areaid = $_POST['area'][$i];
    $def = $_POST['def'][$areaid];

    mysqli_query($con, "INSERT INTO tbl_area_relation (AreaId,UserId,Def) VALUES ('$areaid','$userid','$def')") or die(mysqli_error($con));
  }

  // Category
  for ($i = 0; $i < count($_POST['worktype']); $i++) {

    $CatId = $_POST['worktype'][$i];
    $catDef = $_POST['catDef'][$CatId];

    mysqli_query($con, "INSERT INTO tbl_category_relation (CatId,UserId,Def) VALUES ('$CatId','$userid','$catDef')") or die(mysqli_error($con));
  }

  // Menu Items
  for ($i = 0; $i < count($_POST['menu']); $i++) {

    $menuid = $_POST['menu'][$i];

    mysqli_query($con, "INSERT INTO tbl_menu_relation (MenuId,UserId) VALUES ('$menuid','$userid')") or die(mysqli_error($con));
    mysqli_query($con, "INSERT INTO tbl_menu_user_relation (MenuId,UserId) VALUES ('$menuid','$userid')") or die(mysqli_error($con));
  }
}

// UPDATE
if (isset($_POST['update'])) {

  $userid = $_GET['Edit'];

   // Systems
  mysqli_query($con, "DELETE FROM tbl_system_relation WHERE UserId = '$userid'") or die(mysqli_error($con));

  for ($i = 0; $i < count($_POST['system']); $i++) {

    $systemid = $_POST['system'][$i];
    $def = $_POST['systemDef'][$systemid];

    if ($def == 1) {
      $form_data['SystemId'] = $systemid;
    }

    mysqli_query($con, "INSERT INTO tbl_system_relation (SystemId,UserId,Def) VALUES ('$systemid','$userid','$def')") or die(mysqli_error($con));
  }

  dbUpdate('tbl_users', $form_data, $where_clause = "Id = '" . $_GET['Edit'] . "'", $con);

  mysqli_query($con, "DELETE FROM tbl_area_relation WHERE UserId = '$userid'") or die(mysqli_error($con));
  mysqli_query($con, "DELETE FROM tbl_menu_user_relation WHERE UserId = '$userid'") or die(mysqli_error($con));
  mysqli_query($con, "DELETE FROM tbl_category_relation WHERE UserId = '$userid'") or die(mysqli_error($con));
  mysqli_query($con, "DELETE FROM tbl_menu_relation WHERE UserId = '$userid'") or die(mysqli_error($con));

  // Areas
  for ($i = 0; $i < count($_POST['area']); $i++) {

    $areaid = $_POST['area'][$i];
    $def = $_POST['def'][$areaid];

    mysqli_query($con, "INSERT INTO tbl_area_relation (AreaId,UserId,Def) VALUES ('$areaid','$userid','$def')") or die(mysqli_error($con));
  }

  // Category
  for ($i = 0; $i < count($_POST['worktype']); $i++) {

    $CatId = $_POST['worktype'][$i];
    $catDef = $_POST['catDef'][$CatId];

    mysqli_query($con, "INSERT INTO tbl_category_relation (CatId,UserId,Def) VALUES ('$CatId','$userid','$catDef')") or die(mysqli_error($con));
  }

  // Menu Items
  for ($i = 0; $i < count($_POST['menu']); $i++) {

    $menuid = $_POST['menu'][$i];

    mysqli_query($con, "INSERT INTO tbl_menu_relation (MenuId,UserId) VALUES ('$menuid','$userid')") or die(mysqli_error($con));
    mysqli_query($con, "INSERT INTO tbl_menu_user_relation (MenuId,UserId) VALUES ('$menuid','$userid')") or die(mysqli_error($con));
  }
}

if (isset($_GET['Delete'])) {

  dbDelete('tbl_users', $where_clause = 'Id=' . $_GET['Delete'], $con);
}

$query_form = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$edit'") or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = mysqli_query($con, "SELECT * FROM tbl_users ORDER BY Name  ASC") or die(mysqli_error($con));

$query_areas = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC") or die(mysqli_error($con));

$query_user_menu = "
SELECT
	tbl_menu_links.Id,
	tbl_menu_links.Menu,
	tbl_menu_links.Backend,
	tbl_menu_links.CategoryId,
	tbl_menu_categories.Category
FROM
	tbl_menu_links
INNER JOIN tbl_menu_categories ON tbl_menu_categories.Id = tbl_menu_links.CategoryId
  ORDER BY
	  tbl_menu_categories.Category ASC";

$query_user_menu = mysqli_query($con, $query_user_menu) or die(mysqli_error($con));

$query_categories = mysqli_query($con, "SELECT * FROM tbl_menu_sub_cat ORDER BY SubCat ASC") or die(mysqli_error($con));

$sql = "
	SELECT
		Id,
		Name
	FROM
		tbl_systems
	-- WHERE
	-- 	Id != 3
	ORDER BY
		Name ASC
";

$sql_system = mysqli_query($con, $sql) or die(mysqli_error($con));

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
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <script src="../../menu/script.js"></script>

  <script type="text/javascript">
    function MM_jumpMenu(targ, selObj, restore) { //v3.0
      eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
      if (restore) selObj.selectedIndex = 0;
    }
  </script>

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
      <li><a href="#">User Accounts</a></li>
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
    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form2" class="uk-form bt-flabels js-flabels" data-parsley-validate data-parsley-errors-messages-disabled>
      <div id="list-brdr-supprt">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td width="120" class="td-left">Name</td>
            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="name" type="text" class="tarea-100" id="name" value="<?php echo $row_form['Name']; ?>" autocomplete="off" data-parsley-required>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>
            <td width="120" class="td-left">Email</td>
            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="email" type="text" class="tarea-100" id="email" value="<?php echo $row_form['Email']; ?>" autocomplete="off" data-parsley-required>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>
          </tr>
          <tr>
            <td class="td-left">Username</td>
            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="username" type="text" class="tarea-100" id="username" value="<?php echo $row_form['Username']; ?>" autocomplete="off" data-parsley-required>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>
            <td class="td-left">Password</td>
            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="password" type="text" class="tarea-100" id="password" value="<?php echo $row_form['Password']; ?>" autocomplete="off" data-parsley-required>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>
          </tr>
          <tr>            
            <td class="td-left">Telephone</td>
            <td class="td-right">
              <div class="bt-flabels__wrapper">
                <input name="telephone" type="text" class="tarea-100" id="telephone" value="<?php echo $row_form['Telephone']; ?>" autocomplete="off" data-parsley-required>
                <span class="bt-flabels__error-desc-dd">Required</span>
              </div>
            </td>
            <td class="td-left">Toggle Systems</td>
            <td class="td-right">

              <div class="bt-flabels__wrapper">
                <table border="0" cellspacing="1" cellpadding="0">
                  <tr>
                    <td>
                      <input type="radio" name="toggle-system" id="toggle-system1" value="1" <?php if ($row_form['ChangeSystems'] == 1) {
                                                                                                echo 'checked="checked"';
                                                                                              } ?> />
                    </td>
                    <td class="combo-right"><label for="toggle-system1">Yes</label></td>
                    <td width="10">&nbsp;</td>
                    <td>
                      <input type="radio" name="toggle-system" id="toggle-system2" value="0" <?php if ($row_form['ChangeSystems'] == 0) {
                                                                                                echo 'checked="checked"';
                                                                                              } ?> />
                    </td>
                    <td class="combo-right"><label for="toggle-system2">No</label></td>
                  </tr>
                </table>
              </div>

            </td>
          </tr>
          <tr>
            <td class="td-left">Work Category</td>
            <td  colspan="3" class="td-right">
              <?php

              $x = 0;

              while ($row_system = mysqli_fetch_array($sql_system)) {

                $systemid = $row_system['Id'];
                $checked = '';

                $query = mysqli_query($con, "SELECT * FROM tbl_system_relation WHERE UserId = '$edit' AND SystemId = '$systemid'") or die(mysqli_error($con));
                $row = mysqli_fetch_array($query);

                if (mysqli_num_rows($query) >= 1) {

                  $checked = 'checked="checked"';
                }

                $x++;

              ?>
                <div class="bt-flabels__wrapper">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td width="20" align="center">

                        <input name="system[]" type="checkbox" id="system-<?php echo $x; ?>" value="<?php echo $row_system['Id']; ?>" <?php echo $checked; ?> data-parsley-required>
                        <span class="bt-flabels__error-desc-dd">Required</span>
                      </td>
                      <td width="80" align="center">
                        <select name="systemDef[<?=$row_system['Id'];?>]" class="tarea-100" id="systemDef[]" style="border:solid 1px #DFDFDF;">
                          <option value="0" <?php if ($row['Def'] != 1) {
                                              echo 'selected="selected"';
                                            } ?>>No</option>
                          <option value="1" <?php if ($row['Def'] == 1) {
                                              echo 'selected="selected"';
                                            } ?>>Default</option>
                        </select>
                      </td>
                      <td class="combo-right">
                        <label for="system-<?php echo $x; ?>">
                          <?php echo $row_system['Name']; ?>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td class="td-left">Override SLA</td>
            <td colspan="3" class="td-right">

              <div class="bt-flabels__wrapper">
                <table border="0" cellspacing="1" cellpadding="0">
                  <tr>
                    <td><input type="radio" name="sla" id="radio1" value="1" <?php if ($row_form['OverrideSLA'] == 1) {
                                                                                echo 'checked="checked"';
                                                                              } ?> /></td>
                    <td class="combo-right"><label for="radio1">Yes</label></td>
                    <td width="10">&nbsp;</td>
                    <td><input type="radio" name="sla" id="radio2" value="0" <?php if ($row_form['OverrideSLA'] == 0) {
                                                                                echo 'checked="checked"';
                                                                              } ?> /></td>
                    <td class="combo-right"><label for="radio2">No</label></td>
                  </tr>
                </table>
              </div>

            </td>
          </tr>
          <tr>
            <td class="td-left">Invoice From Quote</td>
            <td colspan="3" class="td-right">

              <div class="bt-flabels__wrapper">
                <table border="0" cellspacing="1" cellpadding="0">
                  <tr>
                    <td><input type="radio" name="qs-inv" id="radio3" value="1" <?php if ($row_form['QuoteInv'] == 1) {
                                                                                  echo 'checked="checked"';
                                                                                } ?> /></td>
                    <td class="combo-right"><label for="radio3">Yes</label></td>
                    <td width="10">&nbsp;</td>
                    <td><input type="radio" name="qs-inv" id="radio4" value="0" <?php if ($row_form['QuoteInv'] == 0) {
                                                                                  echo 'checked="checked"';
                                                                                } ?> /></td>
                    <td class="combo-right"><label for="radio4">No</label></td>
                  </tr>
                </table>
              </div>

            </td>
          </tr>
          <tr>
            <td class="td-left">Edit History</td>
            <td colspan="3" class="td-right">

              <div class="bt-flabels__wrapper">
                <table border="0" cellspacing="1" cellpadding="0">
                  <tr>
                    <td>
                      <input type="radio" name="history" id="radio5" value="1" <?php if ($row_form['EditHistory'] == 1) {
                                                                                  echo 'checked="checked"';
                                                                                } ?>>
                    </td>
                    <td class="combo-right">
                      <label for="radio5">Yes</label>
                    </td>
                    <td width="10">&nbsp;</td>
                    <td>
                      <input type="radio" name="history" id="radio6" value="0" <?php if ($row_form['EditHistory'] == 0) {
                                                                                  echo 'checked="checked"';
                                                                                } ?>>
                    </td>
                    <td class="combo-right">
                      <label for="radio6">No</label>
                    </td>
                  </tr>
                </table>
              </div>

            </td>
          </tr>
          <tr>
            <td class="td-left">Contractor</td>
            <td colspan="3" class="td-right">

              <div class="bt-flabels__wrapper">
                <table border="0" cellspacing="1" cellpadding="0">
                  <tr>
                    <td><input type="radio" name="contractor" id="radio7" value="1" <?php if ($row_form['Contractor'] == 1) {
                                                                                      echo 'checked="checked"';
                                                                                    } ?> /></td>
                    <td class="combo-right"><label for="radio7">Yes</label></td>
                    <td width="10">&nbsp;</td>
                    <td><input type="radio" name="contractor" id="radio8" value="0" <?php if ($row_form['Contractor'] == 0) {
                                                                                      echo 'checked="checked"';
                                                                                    } ?> /></td>
                    <td class="combo-right"><label for="radio8">No</label></td>
                  </tr>
                </table>
              </div>

            </td>
          </tr>
          <tr>
            <td valign="top" class="td-left">Areas (Default)</td>
            <td  class="td-right">

              <?php

              $x = 0;

              while ($row_areas = mysqli_fetch_array($query_areas)) {

                $areaid = $row_areas['Id'];
                $checked = '';

                $query = mysqli_query($con, "SELECT * FROM tbl_area_relation WHERE UserId = '$edit' AND AreaId = '$areaid'") or die(mysqli_error($con));
                $row = mysqli_fetch_array($query);

                if (mysqli_num_rows($query) >= 1) {

                  $checked = 'checked="checked"';
                }

                $x++;

              ?>
                <div class="bt-flabels__wrapper">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td width="20" align="center">

                        <input name="area[]" type="checkbox" id="area-<?php echo $x; ?>" value="<?php echo $row_areas['Id']; ?>" <?php echo $checked; ?> data-parsley-required>
                        <span class="bt-flabels__error-desc-dd">Required</span>
                      </td>
                      <td width="80" align="center">
                        <select name="def[<?=$row_areas['Id'];?>]" class="tarea-100" id="def[]" style="border:solid 1px #DFDFDF;">
                          <option value="0" <?php if ($row['Def'] != 1) {
                                              echo 'selected="selected"';
                                            } ?>>No</option>
                          <option value="1" <?php if ($row['Def'] == 1) {
                                              echo 'selected="selected"';
                                            } ?>>Default</option>
                        </select>
                      </td>
                      <td class="combo-right">
                        <label for="area-<?php echo $x; ?>">
                          <?php echo $row_areas['Area']; ?>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
              <?php } ?>

            </td>
            <td valign="top" class="td-left">Work Type (Default)</td>
            <td  class="td-right">

              <?php

              $x = 0;
              $sql = "SELECT Id, Category FROM tbl_sla_cat WHERE MODULE <> 'Est' ORDER BY Category ASC ";
              $sql_type = mysqli_query($con, $sql) or die(mysqli_error($con));

              while ($row_sla_cat = mysqli_fetch_array($sql_type)) {

                $catId = $row_sla_cat['Id'];
                $checked = '';

                $query = mysqli_query($con, "SELECT * FROM tbl_category_relation WHERE UserId = '$edit' AND CatId = '$catId'") or die(mysqli_error($con));
                $row = mysqli_fetch_array($query);

                if (mysqli_num_rows($query) >= 1) {

                  $checked = 'checked="checked"';
                }

                $x++;

              ?>
                <div class="bt-flabels__wrapper">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td width="20" align="center">

                        <input name="worktype[]" type="checkbox" id="worktype-<?php echo $x; ?>" value="<?php echo $row_sla_cat['Id']; ?>" <?php echo $checked; ?> data-parsley-required>
                        <span class="bt-flabels__error-desc-dd">Required</span>
                      </td>
                      <td width="80" align="center">
                        <select name="catDef[<?=$row_sla_cat['Id'];?>]" class="tarea-100" id="catDef[]" style="border:solid 1px #DFDFDF;">
                          <option value="0" <?php if ($row['Def'] != 1) {
                                              echo 'selected="selected"';
                                            } ?>>No</option>
                          <option value="1" <?php if ($row['Def'] == 1) {
                                              echo 'selected="selected"';
                                            } ?>>Default</option>
                        </select>
                      </td>
                      <td class="combo-right">
                        <label for="worktype-<?php echo $x; ?>">
                          <?php echo $row_sla_cat['Category']; ?>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
              <?php } ?>

            </td>
          </tr>
          <tr>
            <td valign="top" class="td-left">Menu Items</td>
            <td colspan="3" class="td-right">

              <?php
              $x = 0;

              $_SESSION['cat'] = '';

              while ($row_categories = mysqli_fetch_array($query_categories)) {

                $catid = $row_categories['Id'];

                $query_sub_categories = mysqli_query($con, "SELECT * FROM tbl_menu_links WHERE CategoryId = '$catid' ORDER BY Menu ASC") or die(mysqli_error($con));

              ?>

                <div <?php echo $class; ?>>
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td class="td-left">
                        <?php echo $row_categories['SubCat']; ?> </td>
                    </tr>
                  </table>
                </div>
                <div class="bt-flabels__wrapper">
                  <?php
                  while ($row_sub_categories = mysqli_fetch_array($query_sub_categories)) {

                    $x++;
                    $checked = '';
                    $menuid = $row_sub_categories['Id'];

                    $query = mysqli_query($con, "SELECT * FROM tbl_menu_user_relation WHERE UserId = '$edit' AND MenuId = '$menuid'") or die(mysqli_error($con));

                    if (mysqli_num_rows($query) >= 1) {

                      $checked = 'checked="checked"';
                    }

                    if ($x == 1) {

                      $class = 'style="margin-bottom:10px"';
                    } else {

                      $class = 'style="margin-bottom:10px; margin-top:10px; overflow: hidden; float: left; width: 100%"';
                    }

                  ?>
                    <div style="width:33%; float:left">
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td width="20" align="center">
                            <input name="menu[]" type="checkbox" id="menu-<?php echo $x; ?>" style="margin-bottom:0px" value="<?php echo $row_sub_categories['Id']; ?>" <?php echo $checked; ?> data-parsley-required>
                          </td>
                          <td class="combo-right">
                            <label for="menu-<?php echo $x; ?>">
                              <?php echo $row_sub_categories['Menu']; ?>
                            </label>
                          </td>
                        </tr>
                      </table>
                    </div>
                  <?php } ?>
                  <span class="bt-flabels__error-desc-dd">Required</span>
                </div>
              <?php $_SESSION['cat'] = $row_user_menu['Category'];
              } ?>

            </td>
          </tr>
        </table>
      </div>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td align="right">
            <?php if (isset($_GET['Edit'])) { ?>
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
                  <td width="207" class="td-header">Category</td>
                  <td width="249" class="td-header">Name</td>
                  <td width="250" class="td-header">Password</td>
                  <td width="20" align="center" class="td-header-right">&nbsp;</td>
                  <td width="20" align="center" class="td-header-right">&nbsp;</td>
                </tr>
                <?php while ($row_list = mysqli_fetch_array($query_list)) { ?>
                  <tr class="<?php echo ($ac_sw1++ % 2 == 0) ? " even " : "odd "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                    <td>
                      <?php echo $row_list['Name']; ?>
                    </td>
                    <td>
                      <?php echo $row_list['Username']; ?>
                    </td>
                    <td>
                      <?php echo $row_list['Password']; ?>
                    </td>
                    <td align="center">
                      <a href="user-accounts.php?Delete=<?php echo $row_list['Id']; ?>" class="delete"></a>
                    </td>
                    <td align="center">
                      <a href="user-accounts.php?Edit=<?php echo $row_list['Id']; ?>" class="edit"></a>
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
  <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a>
  </div>
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