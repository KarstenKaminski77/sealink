<?php

//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);

    require_once('class/base.php');
    require_once('functions/functions.php');
    
    $base = new Base();
    
    $page = 1;
    
    if(!empty($_GET['page_no'])){
    
        $page = $_GET['page_no'];
        
    }
        
    $base->setPageNo($page);
    
    $base->setTotalRecordsPerPage(10);
    
    if(!empty($_GET['delete_id'])){
        
        $base->setDeleteId($_GET['delete_id']);
    }
    
    if(isset($_GET['id'])){
        
        $base->setSiteId($_GET['id']);
        $base->getSite();
    }
    
    if(isset($_POST['company_id']) || isset($_POST['site_id'])){
        
        $base->setFilterCompanyId($_POST['company_id']);
        $base->setFilterSiteId($_POST['site_id']);
    }
    
    $base->filter();
    $base->getSitesList();
    
    $base->getAreasDd();
    $base->getCompaniesDd();
    $base->getEngineersDd();
    $base->logout_link();
    $base->areaSelect();
    
    if(!empty($_POST)){

        $base->setAreaId($_POST['area-id']);
        $base->setCompany($_POST['company']);
        $base->setName($_POST['site']);
        $base->setFirstName($_POST['first-name']);
        $base->setAddress($_POST['address']);
        $base->setTelephone($_POST['telephone']);
        $base->setCell($_POST['cell']);
        $base->setEmail($_POST['email']);
        $base->setEngineerId($_POST['engineer-id']);
        $base->setVat($_POST['vat']);
        $base->setDistance($_POST['distance']);
    }
    
    if(!empty($_POST) || !empty($_GET['delete_id'])){
        
        $base->saveSite();
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Seavest Asset Management</title>

        <link href="css/layout.css" rel="stylesheet" type="text/css" />
        <link href="css/fonts.css" rel="stylesheet" type="text/css" />
        <link href="css/breadcrumbs.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="countdown/jquery.countdown.css">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

        <link rel="stylesheet" href="menu/styles.css">
        <script src="menu/script.js"></script>

        <script type="text/javascript">
          function MM_jumpMenu(targ,selObj,restore){ //v3.0
            eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
            if(restore) selObj.selectedIndex=0;
          }

          $(document).ready(function () {
              $(".toggler").click(function (e) {
                                  e.preventDefault();
                  $('.row' + $(this).attr('data-row')).toggle();
              });
          });

        </script>

        <script type="text/javascript" src="fancyBox-2/lib/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>

        <link rel="stylesheet" media="all" type="text/css" href="jquery-ui.css" />
        <link rel="stylesheet" media="all" type="text/css" href="jquery-ui-timepicker-addon.css" />

        <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
        <script type="text/javascript" src="jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="jquery-ui-sliderAccess.js"></script>

        <script type="text/javascript" src="highslide/highslide-with-html.js"></script>
        <script src="SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>

        <script type="text/javascript" src="highslide/highslide.js"></script>

        <script type="text/javascript" src="js/sticky.js"></script>

        <script src="countdown/jquery.plugin.js"></script>
        
    </head>
        <body>

            <!-- Banner -->
            <div id="logo">
                <?php echo $base->logout_link; ?>
                <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
                <?php echo $base->generic_areas_dd; ?>
            </div>
            <!-- End Banner -->

            <table>
                <tr>
                    <td width="250" valign="top">

                        <!-- Navigatiopn -->
                        <?php include('menu/menu.php'); ?>
                        <!-- End Navigation -->

                    </td>
                    <td valign="top">

                        <!-- Breadcrumbs -->
                        <div class="td-bread">
                            <ul class="breadcrumb" style="margin-left: 40px">
                                <li><a href="#">Seavest Asset Management</a></li>
                                <li><a href="#">Scheduled Maintenance</a></li>
                                <li><a href="#">Job Cards</a></li>
                                <li><a href="#"><?php echo $row_Recordset5['JobStatus']; ?></a></li>
                            </ul>
                        </div>
                        <!-- End Breadcrumbs -->

                        <!-- Search -->
                        <div class="search-container">
                            <form id="form1" name="form1" method="post" action="search.php">
                                <input name="search" type="text" class="search-top" onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" value="Search..." />
                                <input name="button" type="submit" class="search-top-btn" id="button" value="" />
                            </form>
                        </div>
                        <!-- End Search -->

                        <!-- Main Form -->
                        <div id="main-wrapper" style="margin-bottom:105px; width: calc(100% - 40px)">
                            
                            <?php
                            
                                if(!empty($_SESSION['alert'])){
                                    
                                    echo $_SESSION['alert'];
                                    unset($_SESSION['alert']);
                                }
                            
                            ?>

                            <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">

                                <!-- Site -->
                                <div id="list-border">
                                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                                        <tr>
                                            <td colspan="4" class="td-header">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>Job Card</td>
                                                        <td width="20">
                                                        </td>
                                                        <td width="20" align="center"></td>
                                                        <td width="20" align="right">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="90" class="td-left">Region  <span class="red">*</span></td>
                                            <td colspan="3" class="td-right">
                                                <?php echo $base->areas_dd; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="90" class="td-left">Oil Company <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <?php echo $base->companies_dd; ?>
                                            </td>
                                            <td width="90" class="td-left">Site  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="site" value="<?php $base->getFieldValue('tbl_sites', 'Name', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="90" class="td-left">Contact Person  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="first-name" value="<?php $base->getFieldValue('tbl_sites', 'FirstName', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                            <td width="90" class="td-left">Address  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="address" value="<?php $base->getFieldValue('tbl_sites', 'Address', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="90" class="td-left">Landline</td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="telephone" value="<?php $base->getFieldValue('tbl_sites', 'Telephone', $_GET['id']); ?>" class="tarea-100" />
                                            </td>
                                            <td width="90" class="td-left">Mobile</td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="cell" value="<?php $base->getFieldValue('tbl_sites', 'Cell', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="90" class="td-left">Email  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="email" value="<?php $base->getFieldValue('tbl_sites', 'Email', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                            <td width="90" class="td-left">Engineer  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <?php echo $base->engineers_dd; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="90" class="td-left">VAT  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="radio" name="vat" value="1" id="yes" <?php if($base->row['VAT'] == 1){ echo 'checked'; } ?> required />
                                                <label for="yes">Yes</label>
                                                <input type="radio" name="vat" value="0" id="no" <?php if($base->row['VAT'] == 0){ echo 'checked'; } ?> required />
                                                <label for="no">No</label>
                                            </td>
                                            <td width="90" class="td-left">Return Distance</td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="distance" value="<?php $base->getFieldValue('tbl_sites', 'Distance', $_GET['id']); ?>" class="tarea-100" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div style="margin-bottom:30px">
                                    <table border="0" cellpadding="3" cellspacing="1" class="combo" style="width: 100%">
                                        <tr>
                                            <td>
                                                <input type="submit" name="submit" value="SAVE" class="btn-new" style="float: right;padding: 8px">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                                
                            <form action="temp.php" method="post" enctype="multipart/form-data">
                                
                                <div>
                                    <table border="0" cellpadding="3" cellspacing="1" class="combo" style="width: 100%">
                                        <tbody>
                                            <tr>
                                                <td width="170" align="center">
                                                    <?php echo $base->filter_companies; ?>
                                                </td>
                                                <td>
                                                    <?php echo $base->filter_sites; ?>
                                                </td>
                                                <td width="320"></td>
                                                <td width="115">
                                                    <input type="submit" name="submit" value="FILTER" class="btn-new" style="float: right;padding: 8px">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div id="list-border">
                                    <table border="0" cellpadding="3" cellspacing="1" class="combo" style="width: 100%">
                                        <tbody>
                                            <tr>
                                                <td width="150" align="center" class="td-header"><strong>Company</strong></td>
                                                <td class="td-header"><strong>Site</strong></td>
                                                <td width="300" class="td-header"><strong>Site Address </strong></td>
                                                <td width="95" class="td-header"></td>
                                            </tr>
                                            <?php foreach($base->list as $list){ ?>
                                                <tr class="odd" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                                                    <td>
                                                        <?php echo $list['company_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $list['site_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $list['Address']; ?>
                                                    </td>
                                                    <td nowrap>
                                                        <a href="temp.php?delete_id=<?php echo $list['Id']; ?>" class="btn_red" style="font-weight:bold;text-decoration:none;padding:8px;border-radius:5px;font-family:arial">Delete</a>
                                                        <a href="<?php echo $base->editUrl($list['Id']); ?>" class="btn-new" style="text-decoration:none;margin:0;border-radius:5px;font-family:arial">Edit</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- End Site -->
                                
                                <table align="center">
                                    <tr>
                                        <td>
                                            <?php echo $base->paginator; ?>
                                        </td>
                                    </tr>
                                </table>

                            </form>
                        </div>
                    </td>
                </tr>
            </table>
        
        </body>
</html>
