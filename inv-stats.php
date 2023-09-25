<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

$where = "AND tbl_jc.Id = '0'";

if(isset($_POST['filter'])){
	
	$class = 'odd';
	
	if(!empty($_POST['company'])){
		
		$id = $_POST['company'];
		
		$query_company = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$id'")or die(mysqli_error($con));
		$row_company = mysqli_fetch_array($query_company);
		
		$company = " AND tbl_jc.CompanyId = '". $_POST['company'] ."'";
		$row_1 = '<tr><td nowrap class="td-header-search"><strong>Oil Company</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. $row_company['Name'] .'</td></tr>';
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['from'])){
		
		$from = " AND tbl_jc.NewInvoiceDate >= '". $_POST['from'] ."'";
		$row_2 = '<tr><td nowrap class="td-header-search"><strong>Date From</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. $_POST['from'] .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['to'])){
		
		$to = " AND tbl_jc.NewInvoiceDate <= '". $_POST['to'] ."'";
		$row_3 = '<tr><td nowrap class="td-header-search"><strong>Date To</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. $_POST['to'] .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['min'])){
		
		$min = " AND tbl_jc.Total2 >= '". $_POST['min'] ."'";
		$row_4 = '<tr><td nowrap class="td-header-search"><strong>Min Price</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. number_format($_POST['min'],2) .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	if(!empty($_POST['max'])){
		
		$max = " AND tbl_jc.Total2 <= '". $_POST['max'] ."'";
		$row_5 = '<tr><td nowrap class="td-header-search"><strong>Max Price</strong></td><td colspan="5" align="left" nowrap class="'. $class .'">'. number_format($_POST['max'],2) .'</td></tr>';
		
		
		if($class == 'odd'){
			
			$class = 'even';
			
		} else {
			
			$class = 'odd';
		}
	}
	
	$where = $company . $from . $to . $min . $max;
	
}

mysql_select_db($database_seavest, $seavest);
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
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$query_companies = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC")or die(mysqli_error($con));

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	font-family: Arial;
}
a {
	font-family: Arial;
	font-size: 11px;
	color: #FFFFFF;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FFFFFF;
}
a:hover {
	text-decoration: none;
	color: #CCCCCC;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
-->
</style>

		<style type="text/css"> 
			ul,ol{ margin: 10px 0 10px 40px; }
			li{ margin: 4px 0; }
			dl.defs{ margin: 10px 0 10px 40px; }
			dl.defs dt{ font-weight: bold; line-height: 20px; margin: 10px 0 0 0; }
			dl.defs dd{ margin: -20px 0 10px 160px; padding-bottom: 10px; border-bottom: solid 1px #eee;}
			pre{ font-size: 12px; line-height: 16px; padding: 5px 5px 5px 10px; margin: 10px 0; background-color: #e4f4d4; border-left: solid 5px #9EC45F; overflow: auto; tab-size: 4; -moz-tab-size: 4; -o-tab-size: 4; -webkit-tab-size: 4; }

			.wrapper{ background-color: #ffffff; width: 800px; border: solid 1px #eeeeee; padding: 20px; margin: 0 auto; }
			#tabs{ margin: 20px -20px; border: none; }
			#tabs, #ui-datepicker-div, .ui-datepicker{ font-size: 85%; }
			.clear{ clear: both; }
			
			.example-container{ background-color: #f4f4f4; border-bottom: solid 2px #777777; margin: 0 0 20px 40px; padding: 20px; }
			.example-container input{ border: solid 1px #aaa; padding: 4px; width: 175px; }
			.ebook{}
			.ebook img.ebookimg{ float: left; margin: 0 15px 15px 0; width: 100px; }
			.ebook .buyp a iframe{ margin-bottom: -5px; }
			
</style> 
		
		<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" media="all" type="text/css" href="jquery-ui-timepicker-addon.css" />

		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
		<script type="text/javascript" src="jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="jquery-ui-sliderAccess.js"></script>

<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">

</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><form action="" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
          <table width="823" border="0" cellpadding="3" cellspacing="1">
            <tr>
            <td colspan="7" nowrap>&nbsp;</td>
              </tr>
            <tr>
              <td colspan="7" nowrap>
              <select name="company" class="tarea-new" id="company">
              <option value="">Oil Company</option>
              <?php while($row_companies = mysqli_fetch_array($query_companies)){ ?>
              <option value="<?php echo $row_companies['Id']; ?>"><?php echo $row_companies['Name']; ?></option>
              <?php } ?>
              </select></td>
            </tr>
            <tr>
              <td colspan="7" nowrap>
                <table width="100%" border="0" cellpadding="2" cellspacing="3" class="combo">
                  <tr>
                    <td width="75"><strong>Date From</strong></td>
                    <td>
                    <input name="from" type="text" class="tarea-new" id="from" /> 
					<script type="text/javascript">
					  $('#from').datepicker({
					  dateFormat: "yy-mm-dd"
					  });
                    </script>
                    </td>
                    <td width="100">&nbsp;</td>
                    <td width="75"><strong>  Date To</strong></td>
                    <td>
                    <input name="to" type="text" class="tarea-new" id="to" />
					<script type="text/javascript">
					  $('#to').datepicker({
					  dateFormat: "yy-mm-dd"
					  });
                    </script>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Min Price</strong></td>
                    <td><input name="min" type="text" class="tarea-new" id="min"></td>
                    <td>&nbsp;</td>
                    <td><strong>Max Price</strong></td>
                    <td><input name="max" type="text" class="tarea-new" id="max"></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td colspan="7" align="right" nowrap><input name="filter" type="submit" class="btn-blue-generic" id="filter" value="Filter"></td>
            </tr>
            <tr>
              <td colspan="7" align="right" nowrap>&nbsp;</td>
            </tr>
            <?php if($totalRows_Recordset3 >= '1'){ ?>
            <tr>
              <td nowrap class="td-header-search"><strong>No of Results</strong></td>
              <td colspan="5" align="left" nowrap class="even"><?php echo $totalRows_Recordset3; ?></td>
            </tr>
            <?php echo $row_1; ?>
            <?php echo $row_2; ?>
            <?php echo $row_3; ?>
            <?php echo $row_4; ?>
            <?php echo $row_5; ?>
            <tr>
              <td align="center" nowrap>&nbsp;</td>
              <td colspan="5" align="left" nowrap>&nbsp;</td>
              </tr>
            <tr class="td-header">
              <td width="50" align="center" nowrap><strong>In No. </strong></td>
              <td width="180" align="left"><strong>Company</strong></td>
              <td align="left"><strong>Site Address </strong></td>
              <td width="100" align="left">Status</td>
              <td width="80" align="right">Total</td>
              <td align="center" width="25">&nbsp;</td>
              </tr>
  <?php do { 

$jobid = $row_Recordset3['JobId'];

?><tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
              <td width="50" align="center"><a href="#" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo']; ?></a></td>
              <td width="180" class="combo"><?php echo $row_Recordset3['Name']; ?></td>
              <td class="combo"><?php echo $row_Recordset3['Name_1']; ?></td>
              <td class="combo"><?php echo $row_Recordset3['Status_1']; ?></td>
              <td align="right" class="combo">R<?php echo $row_Recordset3['Total2']; ?></td>
              <td align="center">
              <a href="fpdf16/inv-preview.php?Id=<?php echo $jobid; ?>" target="_blank"><img title="View PDF" src="images/icons/btn-view.png" width="25" height="25" border="0"></a>
              </td>
              </tr>
            <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>			
            <tr>
            <td colspan="5" align="right" class="td-header"><?php sum_stats($con, $where); ?></td>
            <td align="right" class="td-header">&nbsp;</td>
            </tr>
            <?php } ?>
            </table>
        </form>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>