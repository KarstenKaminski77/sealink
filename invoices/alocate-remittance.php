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
			
		$_SESSION['total'] = $_SESSION['total'] + $total;
			
	}
	
	$total = number_format($_SESSION['total'],2);
	$remittance = number_format($_POST['amount'] + $_POST['discount'],2);
	
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
		
		header('Location: pdf/pdf-remittance.php?Id='. $remittance_id);
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
	  STR_TO_DATE(tbl_jc.InvoiceDate, '%d %M %Y') AS date_for_sort,
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
	  date_for_sort ASC";
	  
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
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SEAVEST AFRICA TRADING CC</title>
  <link href="../styles/layout.css" rel="stylesheet" type="text/css" />
  <link href="../styles/fonts.css" rel="stylesheet" type="text/css">
  <link href="../font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
  
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
  
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
  
</head>

<body>
<table height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
      <?php include( '../menu.php'); ?>
    </td>
    <td valign="top">
      <table width="823" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center"><img src="../images/banner.jpg" width="823" height="151">
          </td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">
            <form action="" method="post" enctype="multipart/form-data" name="form2">
              <?php if(isset($_GET[ 'Error'])){ ?>
              <table width="823" border="0" cellpadding="3" cellspacing="1">
                <tr>
                  <td colspan="6" align="right" nowrap class="combo">
                    <table border="0" align="center">
                      <tr>
                        <td>
                          <div class="big2" id="remittance-failiure">Remittance report unsuccessfull</div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <?php } ?>
              </table>
              <div id="list-brdr-supprt" style="margin-left:30px">
                <table width="100%" border="0" cellpadding="3" cellspacing="1">
                  <tr>
                    <td colspan="6" align="right" class="td-right">
                      <select name="company" class="tarea-new-100" id="company">
                        <option value="">Select one...</option>
                        <?php do { ?>
                        <option value="<?php echo $row_Recordset4['Id']; ?>" <?php if($_POST[ 'company'] == $row_Recordset4[ 'Id']){ echo 'selected="selected"'; } ?>>
                          <?php echo $row_Recordset4[ 'Name']; ?>
                        </option>
                        <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" align="right" class="td-right">
                      <?php 
					  if(isset($_POST[ 'remittance']) || isset($_POST[ 'submit'])){
						  
						  $var = $_POST[ 'date']; 
						  
					  } else {
						  
						  $var='Date Received' ;
					  }
					  ?>
                      <input name="date" class="tarea-new-100" id="date" style="width:100%" onFocus="if (this.value=='Date Received') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Date Received';" value="<?php echo $var; ?>">
                      
					  <script type="text/javascript">
                      $('#date').datepicker({
                      dateFormat: "yy-mm-dd"
                      });
                      </script>
                      
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" align="right" class="td-right">
                      <?php if(isset($_POST[ 'remittance']) || isset($_POST[ 'submit'])){ $var = $_POST[ 'amount']; } else { $var='Remittance Amount' ; } ?>
                      <input name="amount" type="text" class="tarea-new-100" id="amount" value="<?php echo $var; ?>" onFocus="if (this.value=='Remittance Amount') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Remittance Amount';" style="width:100%">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" align="right" class="td-right">
                      <?php 
					  if(isset($_POST[ 'remittance']) || isset($_POST[ 'submit'])){
						  
						  $var = $_POST[ 'discount']; 
						  
						  if($_POST[ 'discount'] == 'Remittance Discount' || empty($_POST[ 'discount'])){
							  
							  $var='0.00' ; 
						  }
						  
					  } else {
						  
						  $var='Remittance Discount' ;
					  }
					  ?>
                      <input name="discount" type="text" class="tarea-new-100" id="discount" value="<?php echo $var; ?>" onFocus="if (this.value=='Remittance Discount') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Remittance Discount';" style="width:100%">
                    </td>
                  </tr>
                </table>
              </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="right"><span class="combo">
                    <input name="remittance" type="submit" class="btn-new" id="remittance" value="Select Invoices">
                  </span></td>
                </tr>
              </table>
                
				<?php if(isset($_POST[ 'remittance']) || isset($_POST[ 'submit'])){ ?>
                <div id="list-brdr-supprt" style="margin-left:30px; margin-top:50px">
                <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
                  <tr class="td-header">
                    <td width="50" align="center"><strong>In No. </strong>
                    </td>
                    <td width="200" align="left"><strong>Company</strong>
                    </td>
                    <td align="left"><strong>Site Address </strong>
                    </td>
                    <td width="173" align="left"><strong>Date</strong>
                    </td>
                    <td width="75" align="left">Total</td>
                    <td width="25" align="center">&nbsp;</td>
                  </tr>
                  <?php $i=- 1; do { $i++; $jobid = $row_Recordset3[ 'JobId']; ?>
                  <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                    <td align="center" nowrap class="combo"><a href="#" class="menu" <?php debtors_overdue($jobid); ?> title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['InvoiceNo_1']; ?></a>
                    </td>
                    <td nowrap class="combo" <?php debtors_overdue($jobid); ?>>
                      <?php echo $row_Recordset3[ 'Name']; ?>
                    </td>
                    <td class="combo" <?php debtors_overdue($jobid); ?>>
                      <?php echo $row_Recordset3[ 'Name_1']; ?> </td>
                    <td class="combo" <?php debtors_overdue($jobid); ?>>
                      <?php echo $row_Recordset3[ 'date_for_sort']; ?>
                    </td>
                    <td nowrap class="combo" <?php debtors_overdue($jobid); ?>>R
                      <?php echo $row_Recordset3[ 'Total2']; ?>
                    </td>
                    <td align="center">
                      <?php $checked='' ; for($i=0;$i<count($_POST[ 'file']);$i++){ if($_POST[ 'file'][$i] == $row_Recordset3[ 'InvoiceNo_1']){ $checked='checked="checked"' ; } } ?>
                      <input name="file[]" type="checkbox" id="file[]" value="<?php echo $row_Recordset3['InvoiceNo_1']; ?>" <?php echo $checked; ?>>
                    </td>
                  </tr>
                  <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                </table>
              </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="right">
                    <input name="submit" type="submit" class="btn-new" id="submit" value="Submit">
                  </td>
                </tr>
              </table>
              <?php } ?>

            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);

mysqli_free_result($Recordset4);
?>
