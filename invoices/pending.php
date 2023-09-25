<?php 
session_start();

require_once('../Connections/seavest.php');
require_once('../functions/functions.php'); 

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset4 = "SELECT Id, Name FROM tbl_companies";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

if (isset($_POST['invoiceno'])) {
    $invoiceno = $_POST['invoiceno'];
    $where = "WHERE Status = '7' AND InvoiceNo = ".$invoiceno." AND tbl_jc.CompanyId != '0'";
}

elseif(isset($_POST['jobno'])) {
    $jobno = $_POST['jobno'];
    $where = "WHERE Status = '7' AND JobNo = '".$jobno."' AND tbl_jc.CompanyId != '0'";
}

elseif(isset($_POST['quoteno'])) {
    $quoteno = $_POST['quoteno'];
    $where = "WHERE Status = '7' AND QuoteNo = ".$quoteno." AND tbl_jc.CompanyId != '0'";
}

elseif(isset($_POST['oil'])) {
    $oil = $_POST['oil'];
    $where = "WHERE Status = '7' AND CompanyId = ".$oil." AND tbl_jc.CompanyId != '0'";
}

elseif(isset($_POST['date1'])) {
    $date1 = $_POST['date1'];
    $date_1 = date('Y m d', strtotime($date1));
    $date2 = $_POST['date2'];
    $date_2 = date('Y m d', strtotime($date2));
    $where = "WHERE Status = '7' AND SearchDate >= '".$date_1.
    "' AND SearchDate <= '".$date_2.
    "' AND tbl_jc.CompanyId != '0'";
}

elseif(isset($_GET['all'])) {
    $oil = $_POST['oil'];
    $where = "WHERE Status = '7' AND tbl_jc.CompanyId != '0'";
}

elseif(isset($_POST['area'])) {
    $area = $_POST['area'];
    $where = "WHERE Status = '7' AND tbl_jc.AreaId = ".$area.
    " AND tbl_jc.CompanyId != '0'";
	
} else {
	
    $where = "WHERE Status = '7' AND tbl_jc.CompanyId != '0'";
}
$query_Recordset3 = "
  SELECT
	  tbl_sites.Name AS Name_1,
	  tbl_jc.Id,
	  tbl_jc.JobDescription,
	  tbl_jc.JobNo,
	  tbl_jc.Date,
	  tbl_companies.Name,
	  tbl_sites.Address,
	  tbl_jc.InvoiceSent,
	  tbl_jc.JobId
  FROM
	  (
		  (
			  tbl_jc
			  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		  )
		  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
	  ) $where
  GROUP BY
	  JobId
  ORDER BY
	  tbl_jc.Id ASC";
	  
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_Recordset5 = "SELECT * FROM tbl_areas";
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
      
      <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
      
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
      
	  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
      <script>!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');</script>
      <script type="text/javascript" src="../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
      <script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
      <link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
      
	  <script type="text/javascript">
      
        jQuery(document).ready(function() {	
            
                    $(".various3").fancybox({
                        'width'				: 500,
                        'height'			: 230,
                        'autoScale'			: true,
                        'transitionIn'		: 'none',
                        'transitionOut'		: 'none',
                        'type'				: 'iframe',
                        'padding'           : 0,
                        'overlayOpacity'    : '0.8',
                        'overlayColor'      : 'black',
        
                    });
                    
					$('.fancybox').fancybox({
					
						autoSize    : true, 
						closeClick  : false, 
						fitToView   : true, 
						openEffect  : 'none', 
						closeEffect : 'none', 
						scrolling   : 'no',
						type : 'iframe',
	  
					});
            
        });
      </script>
      
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
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Invoices</a></li>
            <li><a href="#">Pending</a></li>
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
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="bottom" class="combo_bold">
                    <form name="form1" method="post" action="">
                        <select name="menu1" class="select-5" onchange="MM_jumpMenu('parent',this,0)">
                            <option>Search...</option>
                            <option value="pending.php?all">All</option>
                            <option value="pending.php?area">Area</option>
                            <option value="pending.php?jcn">Job Card Number</option>
                            <option value="pending.php?date">Date</option>
                            <option value="pending.php?oil">Oil Company</option>
                        </select>
                    </form>
                </td>
                <td valign="bottom" nowrap>
                    <?php
                    if(isset($_GET['jcn'])){
						
						$_SESSION['search'] = "jcn";
                    ?>
                        <form name="form2" method="post" action="pending.php?jcn">
                            <input name="jobno" type="text" class="select" id="jobno" style="cursor:text">
                            <input name="Submit" type="submit" class="btn-new" id="Submit" value="Search">
                        </form>
                        
                    <?php } ?>
                    
                    <?php
                    if(isset($_GET['in'])){

						$_SESSION['search'] = "in";
						?>
						<form name="form2" method="post" action="pending.php?in">
							<input name="invoiceno" type="text" class="select" id="invoiceno" style="cursor:text">
							<input name="Submit2" type="submit" class="btn-new" id="Submit2" value="Search">
						</form>
                        
                    <?php } ?>
                    
                    <?php
                    if(isset($_GET['qn'])){

						$_SESSION['search'] = "qn";
                    ?>
                        <form name="form2" method="post" action="pending.php?qn">
                            <input name="quoteno" type="text" class="select" id="quoteno" style="cursor:text">
                            <input name="Submit2" type="submit" class="btn-new" id="Submit2" value="Search">
                        </form>
                        
                    <?php } ?>
                    
                    <?php
                    if(isset($_GET['date'])){

						$_SESSION['search'] = "date";
                    ?>
                        <form name="form2" method="post" action="pending.php?date">
                            <input name="date1" class="select" id="date1" value="">
                            <script type="text/javascript">
                            $('#date1').datepicker({
                            dateFormat: "yy-mm-dd"
                            });
                            </script>
    
                            <input name="date2" class="select" id="date2" value="">
                            <script type="text/javascript">
                            $('#date2').datepicker({
                            dateFormat: "yy-mm-dd"
                            });
                            </script>
    
                            <input name="Submit3" type="submit" class="btn-new" id="Submit3" value="Search">
                        </form>
                    <?php } ?>
                    
                    <?php
                    if(isset($_GET['oil'])){

						$_SESSION['search'] = "oil";
                    ?>
                    
                        <form name="form2" method="post" action="pending.php?oil">
                            <select name="oil" class="select" id="oil">
                                <?php
                                do {
                                ?>
                                <option value="<?php echo $row_Recordset4['Id']?>"><?php echo $row_Recordset4['Name']?></option>
                                <?php
                                } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4));
                                $rows = mysqli_num_rows($Recordset4);
                                if($rows > 0) {
                                mysqli_data_seek($Recordset4, 0);
                                $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
                                }
                                ?>
                            </select>
                            <input name="Submit4" type="submit" class="btn-new" value="Search">
                        </form>
                        
                    <?php } ?>
                    
                    <?php if(isset($_GET['area'])){ ?>
                        <form name="form2" method="post" action="pending.php?rea">
                            <select name="area" class="select" id="area">
                                <?php
                                do {
                                ?>
                                <option value="<?php echo $row_Recordset5['Id']?>"><?php echo $row_Recordset5['Area']?></option>
                                <?php
                                } while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5));
                                $rows = mysqli_num_rows($Recordset5);
                                if($rows > 0) {
                                mysqli_data_seek($Recordset5, 0);
                                $row_Recordset5 = mysqli_fetch_assoc($Recordset5);
                                }
                                ?>
                            </select>
                            <input name="Submit4" type="submit" class="btn-new" value="Search">
                        </form>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div id="list-border">
                        <?php $jobid = $row_Recordset3['JobId']; ?>
                        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="combo">
                            <tr>
                                <td width="100" align="left" nowrap class="td-header"><strong>&nbsp;Invoice </strong></td>
                                <td width="200" align="left" class="td-header"><strong>&nbsp;Company</strong></td>
                                <td align="left" class="td-header"><strong>&nbsp;Site Address </strong></td>
                                <td width="75" align="center" class="td-header"><strong>&nbsp;Age</strong></td>
                                <td width="20" align="center" class="td-header-right">&nbsp;</td>
                            </tr>
                            <?php
                            do {
								
								$query_proforma = mysqli_query($con, "SELECT * FROM tbl_sent_invoices WHERE JobId = '". $row_Recordset3['JobId'] ."'")or die(mysqli_error($con));
								$row_proforma = mysqli_fetch_array($query_proforma);
								
								if(empty($row_proforma['Proforma'])){
									
									$url = 'inv-calc.php?menu='. $_GET['menu'] .'&Id='.$row_Recordset3['JobId'] .'&job';
									$site = $row_Recordset3['Name_1'];
									
								} else {
									
									$url = 'scheduled-maintenance-calc.php?menu='. $_GET['menu'] .'&Id='.$row_Recordset3['JobId'] .'&job';
									$site = 'Scheduled Maintenance';
								}

								$jobid = $row_Recordset3['JobId'];
                            ?>
                            <tr class="<?php echo ($ac_sw1++%2==0)?" even":"odd"; ?>
                                " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                                <td><a href="<?php echo $url; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"> &nbsp;<?php echo $row_Recordset3['JobNo']; ?></a></td>
                                <td><a href="<?php echo $url; ?>" class="menu"> &nbsp;<?php echo $row_Recordset3['Name']; ?></a></td>
                                <td><a href="<?php echo $url; ?>" class="menu"> &nbsp;<?php echo $site; ?></a></td>
                                <td align="center"><a href="<?php echo $url; ?>" class="menu"> &nbsp;<?php time_schedule($jobid); ?></a><a name="<?php echo $row_Recordset3['JobId']; ?>"></a></td>
                                <td align="center"><a href="order-no.php?Id=<?php echo $row_Recordset3['JobId']; ?>&Pending" title="Order No" class="po various3"></a></td>
                            </tr>
                            <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <!-- End Main Form -->
    <!-- Footer -->
    <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
    <!-- End Footer --></html>
<?php
  mysqli_close($con);
  mysqli_free_result($Recordset1);
  mysqli_free_result($Recordset2);
  mysqli_free_result($Recordset3);
  mysqli_free_result($Recordset5);
  mysqli_free_result($Recordset4);
?>
