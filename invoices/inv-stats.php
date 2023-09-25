<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

$where = "AND tbl_jc.Id = '0'";

if(isset($_POST['filter'])){
	
	$where = '';
	$row = '';
	$class = 'odd';
	
	if(!empty($_POST['area-s'])){
		
		$id = $_POST['area-s'];
		
		$query_area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$id'")or die(mysqli_error($con));
		$row_area = mysqli_fetch_array($query_area);
				
		$where .= " AND tbl_jc.AreaId = '". $_POST['area-s'] ."'";
		$row .= '<tr><td nowrap class="td-left"><strong>Region</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. $row_area['Area'] .'</td></tr>';
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['company'])){
		
		$id = $_POST['company'];
		
		$query_company = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$id'")or die(mysqli_error($con));
		$row_company = mysqli_fetch_array($query_company);
		
		$where .= " AND tbl_jc.CompanyId = '". $_POST['company'] ."'";
		$row .= '<tr><td nowrap class="td-left"><strong>Oil Company</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. $row_company['Name'] .'</td></tr>';
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['from'])){
		
		$where .= " AND tbl_jc.NewInvoiceDate >= '". $_POST['from'] ."'";
		$row .= '<tr><td nowrap class="td-left"><strong>Date From</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. $_POST['from'] .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['to'])){
		
		$where .= " AND tbl_jc.NewInvoiceDate <= '". $_POST['to'] ."'";
		$row .= '<tr><td nowrap class="td-left"><strong>Date To</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. $_POST['to'] .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['min'])){
		
		$where .= " AND tbl_jc.Total2 >= '". $_POST['min'] ."'";
		$row .= '<tr><td nowrap class="td-left"><strong>Min Price</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. number_format($_POST['min'],2) .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['max'])){
		
		$where .= " AND tbl_jc.Total2 <= '". $_POST['max'] ."'";
		$row .= '<tr><td nowrap class="td-left"><strong>Max Price</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. number_format($_POST['max'],2) .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}	
}

$query_Recordset3 = "
	SELECT
		tbl_sites.`Name` AS Name_1,
		STR_TO_DATE(
			tbl_jc.InvoiceDate,
			'%d %M %Y'
		) AS date_for_sort,
		tbl_jc.CompanyId,
		tbl_jc.JobDescription,
		tbl_jc.`Status`,
		tbl_jc.JobId,
		tbl_jc.Total2,
		tbl_companies.`Name`,
		tbl_status.`Status` AS Status_1,
		tbl_jc.InvoiceNo
	FROM
		(
			(
				(
					tbl_jc
					LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
				)
				LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
			)
			INNER JOIN tbl_status ON tbl_jc.`Status` = tbl_status.Id
		)
	WHERE
		(tbl_jc.Status >= '9' AND tbl_jc.Status <= '12') $where
	GROUP BY
		tbl_jc.JobId
	ORDER BY
		date_for_sort ASC";
		
$Recordset3 = mysqli_query($con, $query_Recordset3)or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_companies = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC")or die(mysqli_error($con));
$query_search_areas = mysqli_query($con, "SELECT * FROM tbl_areas ORDER BY Area ASC")or die(mysqli_error($con));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
              
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="../menu/script.js"></script>
      
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
      
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
          <?php include('../menu/menu.php'); ?>
    <!-- End Navigation -->
    
    <!-- Breadcrumbs -->
    <div class="td-bread">
        <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Invoices</a></li>
            <li><a href="#">Stats</a></li>
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
    
        <form action="" method="post" enctype="multipart/form-data" name="form2">
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
                <tr>
                    <td colspan="7" nowrap>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="7" nowrap>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="7" nowrap>
                        <div id="list-border">
                            <table width="100%" border="0" cellpadding="4" cellspacing="1">
                                <tr>
                                    <td class="td-left">Company</td>
                                    <td class="td-right">
                                        <select name="company" class="tarea-100" id="company">
                                            <option value="">Oil Company</option>
                                            <?php while($row_companies = mysqli_fetch_array($query_companies)){ ?>
                                            <option value="<?php echo $row_companies['Id']; ?>"><?php echo $row_companies['Name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="td-left">Region</td>
                                    <td class="td-right">
    
                                        <select name="area-s" class="tarea-100" id="area-s">
                                            <option value="">Region</option>
                                            <?php while($row_search_areas = mysqli_fetch_array($query_search_areas)){ ?>
                                            <option value="<?php echo $row_search_areas['Id']; ?>"><?php echo $row_search_areas['Area']; ?></option>
                                            <?php } ?>
                                        </select>
    
                                    </td>
                                </tr>
                                <tr>
                                    <td width="75" class="td-left"><strong>Date From</strong></td>
                                    <td class="td-right">
                                        <input name="from" type="text" class="tarea-100" id="from" />
                                        <script type="text/javascript">
											$('#from').datepicker({
											dateFormat: "yy-mm-dd"
											});
                                        </script>
                                    </td>
                                    <td width="75" class="td-left"><strong>  Date To</strong></td>
                                    <td class="td-right">
                                        <input name="to" type="text" class="tarea-100" id="to" />
                                        <script type="text/javascript">
											$('#to').datepicker({
											dateFormat: "yy-mm-dd"
											});
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-left"><strong>Min Price</strong></td>
                                    <td class="td-right"><input name="min" type="text" class="tarea-100" id="min"></td>
                                    <td class="td-left"><strong>Max Price</strong></td>
                                    <td class="td-right"><input name="max" type="text" class="tarea-100" id="max"></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
    
            <table width="100%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                    <td colspan="7" align="right" nowrap><input name="filter" type="submit" class="btn-new" id="filter" value="Filter"></td>
                </tr>
            </table>
    
            <?php if($totalRows_Recordset3 >= '1'){ ?>
            <div id="list-border" style="margin-top:20px; margin-bottom:20px">
                <table width="100%" border="0" cellpadding="4" cellspacing="1">
                    <tr>
                        <td width="100" nowrap class="td-left"><strong>No of Results</strong></td>
                        <td colspan="5" align="left" nowrap class="even"><?php echo $totalRows_Recordset3; ?></td>
                    </tr>
                    <?php echo $row; ?>
                </table>
            </div>
    
            <div id="list-border">
                <table width="100%" border="0" cellpadding="3" cellspacing="1">
                    <tr class="td-header">
                        <td width="50" align="center" nowrap><strong>In No. </strong></td>
                        <td width="180" align="left"><strong>Company</strong></td>
                        <td align="left"><strong>Site Address </strong></td>
                        <td width="150" align="left">Date</td>
                        <td width="150" align="left">Status</td>
                        <td width="80" align="right">Total</td>
                        <td align="center" width="20">&nbsp;</td>
                    </tr>
                    <?php do {
    
                    $jobid = $row_Recordset3['JobId'];
    
                    ?>
                    <tr class="<?php echo ($ac_sw1++%2==0)?" odd":"even"; ?>
                        " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                        <td width="50" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
                        <td width="180" class="combo"><?php echo $row_Recordset3['Name']; ?></td>
                        <td class="combo"><?php echo $row_Recordset3['Name_1']; ?></td>
                        <td class="combo"><?php echo $row_Recordset3['date_for_sort']; ?></td>
                        <td class="combo"><?php echo $row_Recordset3['Status_1']; ?></td>
                        <td align="right" class="combo">R<?php echo $row_Recordset3['Total2']; ?></td>
                        <td align="center">
                            <a href="../fpdf16/inv-preview.php?Id=<?php echo $jobid; ?>" target="_blank" class="view"></a>
                        </td>
                    </tr>
                    <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                    <tr>
                        <td colspan="6" align="right" class="td-header"><?php sum_stats($con, $where); ?></td>
                        <td align="right" class="td-header">&nbsp;</td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </form>
    
    </div>
    <!-- End Main Form -->
    
    <!-- Footer -->
    <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
    <!-- End Footer -->
      
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