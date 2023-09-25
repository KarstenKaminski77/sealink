<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

if(isset($_POST['submit'])){
	
	$_SESSION['total'] = '';
	
	for($i=0;$i<count($_POST['file']);$i++){
		
		$file = $_POST['file'][$i];
								
		$query2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE InvoiceNo = '$file' AND Total2 >= '1' LIMIT 1")or die(mysqli_error($con));
		$row2 = mysqli_fetch_array($query2);
			
		$total = $row2['Total2'];
		
		echo $total .'<br>';
			
		$_SESSION['total'] = $_SESSION['total'] + $total;
			
	}
		
	$total = number_format($_SESSION['total'],2);
	$remittance = number_format($_POST['amount'] + $_POST['discount'],2);
	
	echo $total .' '. $remittance;
		
	if($total == $remittance){
				
		$amount = $_POST['amount'];
		$discount = $_POST['discount'];
		$to = $_POST['email'];
		$message = $_POST['message'];
		
		$companyid = $_POST['company'];
		$date = $_POST['date'];
		$userid = $_SESSION['kt_login_id'];
		$datesubmitted = date('Y-m-d');
		
		mysqli_query($con, "INSERT INTO tbl_remittance (UserId,CompanyId,Date,DateSubmitted,Amount,Discount,Email,Message) VALUES ('$userid','$companyid','$date','$datesubmitted','$amount','$discount','$to','$message')")or die(mysqli_error($con));
		$query = mysqli_query($con, "SELECT * FROM tbl_remittance ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		
		$remittance_id = $row['Id'];
		
		for($i=0;$i<count($_POST['file']);$i++){
			
			$file = $_POST['file'][$i];
			
			$query_id = mysqli_query($con, "SELECT * FROM tbl_jc WHERE InvoiceNo = '$file'")or die(mysqli_error($con));
			$row_id = mysqli_fetch_array($query_id);
			
			$jobid = $row_id['JobId'];
			
			mysqli_query($con, "UPDATE tbl_jc SET Status = '10' WHERE JobId = '$jobid'")or die(mysqli_error($con));
			
			$query2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
			$row2 = mysqli_fetch_array($query2);
			
			$total = $row2['Total2'];
		    $siteid = $row2['SiteId'];
		    $jobno = $row2['JobNo'];
		    $inv_date = date('Y-m-d', strtotime($row2['InvoiceDate']));
			$invoiceno = $file;
			
			mysqli_query($con, "INSERT INTO tbl_remittance_details (RemittanceId,JobNo,InvoiceNo,InvoiceDate,JobId,SiteId,Amount) 
		    VALUES ('$remittance_id','$jobno','$invoiceno','$inv_date','$jobid','$siteid','$total')")or die(mysqli_error($con));
			
		}
		
		header('Location: ../pdf/pdf-remittance.php?Id='. $remittance_id);
	}
}

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$companyid = $_POST['company'];

$query_Recordset3 = "
  SELECT
	  tbl_sites.`Name` AS Name_1,
	  STR_TO_DATE(
		  tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort,
	  tbl_jc.CompanyId,
	  tbl_sent_invoices.PDF,
	  tbl_sent_invoices.DateSent,
	  tbl_sent_invoices.Sent,
	  tbl_jc.JobDescription,
	  tbl_jc.`Status`,
	  tbl_jc.JobId,
	  tbl_jc.Total2,
	  tbl_companies.`Name`,
	  tbl_sent_invoices.InvoiceNo AS InvoiceNo_1
  FROM
	  (
		  (
			  (
				  tbl_sent_invoices
				  LEFT JOIN tbl_jc ON tbl_jc.JobId = tbl_sent_invoices.JobId
			  )
			  LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
		  )
		  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
	  )
  WHERE
	  tbl_jc.`Status` = '12'
  AND tbl_companies.Id = '$companyid'
  GROUP BY
	  tbl_jc.JobId
  ORDER BY
	  tbl_sent_invoices.InvoiceNo ASC";
	
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_rs_totals = $_SESSION['kt_login_id'];

$rs_totals = mysqli_query($con, "SELECT * FROM tbl_menu_relation WHERE UserId = '$colname_rs_totals' AND MenuId = '47'") or die(mysqli_error($con));
$row_rs_totals = mysqli_fetch_assoc($rs_totals);
$totalRows_rs_totals = mysqli_num_rows($rs_totals);

$query_Recordset4 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$inv_array = array('10009','10058','10084','10094','10100','10182','10189','10201','10311','10316','10335','10576','10580','10593','10599','10610','10638','10661','10663','10673','10695','10704','10705','10850','10868','10897','10898','10966','10968','10990','11010','9994');

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
      
          <form action="" method="post" enctype="multipart/form-data" name="form2">
      
              <?php if(isset($_GET['Error'])){ ?>
              <div id="banner-error">Remittance report unsuccessfull</div>
              <?php } ?>
      
              <div id="list-border">
                  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                      <tr>
                          <td colspan="6" align="right" nowrap class="td-right">
                              <select name="company" class="tarea-100" id="company">
                                  <option value="">Select one...</option>
                                  <?php do { ?>
                                  <option value="<?php echo $row_Recordset4['Id']; ?>" <?php if($_POST['company'] == $row_Recordset4['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_Recordset4['Name']; ?></option>
                                  <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                              </select>
                          </td>
                      </tr>
                      <tr>
                          <td colspan="6" align="right" nowrap class="td-right">
                              <?php
                              if(isset($_POST['remittance']) || isset($_POST['submit'])){
      
								  $var = $_POST['date'];
      
                              } else {
      
								  $var = 'Date Received';
                              }
                              ?>
                              <input name="date" class="tarea-100" id="date" onfocus="if(this.value=='Date Received') this.value='';" 
                              onblur="if(this.value.replace(/\s/g,'')=='') this.value='Date Received';" value="<?php echo $var; ?>">
      
                              <script type="text/javascript">
                                  $('#date').datepicker({
                                      dateFormat: "yy-mm-dd"
                                  });
                              </script>
      
                          </td>
                      </tr>
                      <tr>
                          <td colspan="6" align="right" nowrap class="td-right">
                              <?php
                              if(isset($_POST['remittance']) || isset($_POST['submit'])){
      
								  $var = $_POST['amount'];
      
                              } else {
      
								  $var = 'Remittance Amount';
                              }
                              ?>
                              <input name="amount" type="text" class="tarea-100" id="amount" value="<?php echo $var; ?>" onfocus="if(this.value=='Remittance Amount') this.value='';" 
                              onblur="if (this.value.replace(/\s/g,'')=='') this.value='Remittance Amount';" style="width:100%">
                          </td>
                      </tr>
                      <tr>
                          <td colspan="6" align="right" nowrap class="td-right">
                              <?php
                              if(isset($_POST['remittance']) || isset($_POST['submit'])){
								  
								  $var = $_POST['discount'];
								  
								  if($_POST['discount'] == 'Remittance Discount' || empty($_POST['discount'])){
									  
									  $var = '0.00';
								  }
      
							  } else {
								  
								  $var = 'Remittance Discount';
                              }
                              ?>
                              <input name="discount" type="text" class="tarea-100" id="discount" value="<?php echo $var; ?>" onfocus="if(this.value=='Remittance Discount') this.value='';" 
                              onblur="if(this.value.replace(/\s/g,'')=='') this.value='Remittance Discount';" style="width:100%">
                          </td>
                      </tr>
                  </table>
      
              </div>
      
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                      <td align="right">
                          <span class="combo">
                              <input name="remittance" type="submit" class="btn-new" id="remittance" value="Select Invoices" style="margin-bottom:30px" />
                          </span>
                      </td>
                  </tr>
              </table>
      
      
              <?php if(isset($_POST['remittance']) || isset($_POST['submit'])){ ?>
              <div id="list-border">
                  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                      <tr>
                          <td width="50" align="center" nowrap class="td-header"><strong>In No. </strong></td>
                          <td width="180" align="left" class="td-header"><strong>Company</strong></td>
                          <td align="left" class="td-header"><strong>Site Address </strong></td>
                          <td width="100" align="left" class="td-header"><strong>Date</strong></td>
                          <td width="100" align="right" class="td-header">Total</td>
                          <td width="25" align="center" class="td-header">&nbsp;</td>
                      </tr>
                      <?php
                      $i = -1;
                      $total_rem = 0;
      
                      do {
      
                      $i++;
      
                      $jobid = $row_Recordset3['JobId']; ?>
                      <tr class="<?php echo ($ac_sw1++%2==0)?" odd":"even"; ?>
                          " onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                          <td width="50" align="center" nowrap class="combo"><a href="#" class="menu"<?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo_1']; ?></a></td>
                          <td width="180" nowrap class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name']; ?></td>
                          <td class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['Name_1']; ?> </td>
                          <td class="combo"<?php debtors_overdue($jobid); ?>><?php echo $row_Recordset3['date_for_sort']; ?></td>
                          <td align="right" nowrap class="combo"<?php debtors_overdue($jobid); ?>>R<?php echo $row_Recordset3['Total2']; ?></td>
                          <td align="center">
                              <?php
                              $checked = '';
      
                              for($i=0;$i<count($_post['file']);$i++){
								  
								  if($_post['file'][$i] == $row_Recordset3['InvoiceNo_1']){
									  
									  $checked='checked="checked"';
								  }
							  }
							  
							  if(in_array($row_recordset3['invoiceno_1'], $inv_array)){
								  
								  $checked='checked="checked"';
								  $total_rem = $total_rem + $row_recordset3['total2'];
							  }
							  
							  ?>
                              
                              <input name="file[]" type="checkbox" id="file[]" value="<?php echo $row_Recordset3['InvoiceNo_1']; ?>"><?php echo $checked; ?>>
                          </td>
                      </tr>
                      <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                  </table>
              </div>
              <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                  <tr>
                      <td colspan="6" align="right"><input name="submit" type="submit" class="btn-new" id="submit" value="Submit"></td>
                  </tr>
                  <?php } ?>
              </table>
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