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
        
        $base->setTechnicianId($_GET['id']);
        $base->getTechnician();
    }
    
    if(isset($_POST['technician_id'])){
        
        $base->setFilterTechnicianId($_POST['technician_id']);
    }
    
    $base->filter();
    $base->getTechnicianList();
    $base->getAreasDd();
    $base->logout_link();
    $base->areaSelect();
    
    if(!empty($_POST)){

        $base->setAreaId($_POST['area-id']);
        $base->setName($_POST['technician-name']);
        $base->setUsername($_POST['username']);
        $base->setPassword($_POST['password']);
        $base->setEmail($_POST['email']);
        $base->setCell($_POST['cell']);
    }
    
    if(!empty($_POST) || !empty($_GET['delete_id'])){
        
        $base->saveTecnician();
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
                                <li><a href="#">Settings</a></li>
                                <li><a href="#">Technicians</a></li>
                                <li><a href="#"></a></li>
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

                                <!-- Technicain Form -->
                                <div id="list-border">
                                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                                        <tr>
                                            <td colspan="4" class="td-header">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>Technician</td>
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
                                            <td width="90" class="td-left">Region.  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <?php echo $base->areas_dd; ?>
                                            </td>
                                            <td width="90" class="td-left">Technician Name  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="technician-name" value="<?php $base->getFieldValue('tbl_technicians', 'Name', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                        <tr>
                                            <td width="90" class="td-left">Username <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="username" value="<?php $base->getFieldValue('tbl_technicians', 'Username', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                            <td width="90" class="td-left">Password  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="password" value="<?php $base->getFieldValue('tbl_technicians', 'Password', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="90" class="td-left" valign="top">Email</td>
                                            <td width="200" class="td-right">
                                                <input name="email" type="email" value="<?php $base->getFieldValue('tbl_technicians', 'Email', $_GET['id']); ?>" class="tarea-100">
                                            </td>
                                            <td width="90" class="td-left">Cell  <span class="red">*</span></td>
                                            <td width="200" class="td-right">
                                                <input type="text" name="cell" value="<?php $base->getFieldValue('tbl_technicians', 'Cell', $_GET['id']); ?>" class="tarea-100 " required />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- End Technician Form -->`
                                
                                <!-- Technicians List -->
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
                                
                            <form action="technicians.php" method="post" enctype="multipart/form-data">
                                
                                <div>
                                    <table border="0" cellpadding="3" cellspacing="1" class="combo" style="width: 100%">
                                        <tbody>
                                            <tr>
                                                <td width="220" align="center">
                                                    <?php echo $base->filter_technicians; ?>
                                                </td>
                                                <td>
                                                    &nbsp;
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
                                                <td width="200" class="td-header"><strong>Region</strong></td>
                                                <td class="td-header"><strong>Name</strong></td>
                                                <td width="120" class="td-header"><strong>Email </strong></td>
                                                <td width="120" class="td-header">Cell</td>
                                                <td width="95" class="td-header"></td>
                                            </tr>
                                            <?php foreach($base->list as $list){ ?>
                                                <tr class="odd" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                                                    <td>
                                                        <?php echo $list['Area']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $list['Name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $list['Email']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $list['Cell']; ?>
                                                    </td>
                                                    <td nowrap>
                                                        <a href="technicians.php?delete_id=<?php echo $list['Id']; ?>" class="btn_red" style="font-weight:bold;text-decoration:none;padding:8px;border-radius:5px;font-family:arial">Delete</a>
                                                        <a href="<?php echo $base->editUrl($list['Id']); ?>" class="btn-new" style="text-decoration:none;margin:0;border-radius:5px;font-family:arial">Edit</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- End Technicians List -->
                                
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
