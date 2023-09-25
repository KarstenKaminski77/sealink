<?php 
session_start();

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$quoteno = $_GET['Quote'];

require_once('../functions/functions.php');
require_once("../dropdown/dbcontroller.php");
$db_handle = new DBController();

$Recordset2 = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '". $_GET['Quote'] ."'") or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_sla_cat = mysqli_query($con, "SELECT * FROM tbl_sla_cat WHERE Module = 'JC' ORDER BY Category ASC")or die(mysqli_error($con));

$query_qs = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '". $_GET['Quote'] ."'")or die(mysqli_error($con));
$row_qs = mysqli_fetch_array($query_qs);

$_SESSION['company-id'] = $row_qs['CompanyId'];

// Create Job Card
if(isset($_POST['jobcard']) && !empty($_POST['jobno'])){
	
	$jobno = $_POST['jobno'];
	
	mysqli_query($con, "INSERT INTO tbl_jobnumbers (JobNo,Prefix) VALUES ('1','1')") or die(mysqli_error($con));
	
	$query = mysqli_query($con, "SELECT * FROM tbl_jobnumbers ORDER BY Id DESC LIMIT 1") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$jobid = $row['Id'] + 1;
	
	$query = mysqli_query($con, "SELECT * FROM tbl_qs WHERE QuoteNo = '$quoteno'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$quote = $row['QuoteNo']; 
		$companyid = $row['CompanyId'];
		$siteid = $row['SiteId'];
		$areaid = $row['AreaId'];
		$desc = $row['Description']; 	
		$unit = $row['Unit']; 	
		$qty = $row['Qty']; 	
		$price = $row['Price']; 	
		$total = $row['Total1']; 	
		$date = date('j M Y');	
		$labour = $row['Labour'];
		$material = $row['Material']; 	
		$transport = $row['Transport'];
		$t_comment = $row['TransportComment'];
		
		if(isset($_POST['jobno'])){
			
			$jobno = $_POST['jobno'];
			$_SESSION['jobno'] = $_POST['jobno'];
			
		} else {
			
			$jobno = $row['FMC'];
			$_SESSION['jobno'] = $row['FMC'];
		}
	
		mysqli_query($con, "INSERT INTO tbl_jc (AreaId,QuoteNo,JobNo,CompanyId,SiteId,Description,Unit,Qty,
		Price,Total1,Date,Labour,Material,Transport,JobId,TransportComment,JC,Status) 
		VALUES
		('$areaid','$quote','$jobno','$companyid','$siteid','$desc','$unit','$qty','$price','$total','$date','$labour','$material','$transport','$jobid','$t_comment','1','1')") or die(mysqli_error($con));
	}
	
	$sla_cat = $_POST['sla'];
	$sla_sub_cat = $_POST['sub_cat'];
	
	if(!empty($_POST['start'])){
		
		$start = $_POST['start'];
		$end = $_POST['end'];
		
	} else  {
		
		$query_end = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE Id = '$sla_sub_cat'")or die(mysqli_error($con));
		$row_end = mysqli_fetch_array($query_end);
		
		$duration = $row_end['Duration'];
		
		$start = date('Y-m-d H:i:s');
		
		$future = addRollover(date('Y-m-d H:i:s'), $duration, '8:00', '16:30', true);
		
		$end = $future->format('Y-m-d H:i:s').'</br>';
	}
	
	mysql_query ("INSERT INTO tbl_jc (AreaId, Status, QuoteNo, JobNo, CompanyId, SiteId, Comment, JobId) 
	VALUES ('". $row_qs['AreaId'] ."', '1','$quoteno','". $_SESSION['jobno'] ."','". $row_qs['CompanyId'] ."','". $row_qs['SiteId'] ."','1','$jobid')") or die(mysql_error());
	
	mysqli_query($con, "INSERT INTO tbl_sla_history (JobId,JobNo,SlaStart,SlaEnd) VALUES ('$jobid','". $_SESSION['jobno'] ."','$start','$end')")or die(mysqli_error($con));
	
	$date2 = date('d M Y');
	
	mysqli_query($con, "INSERT INTO tbl_feedback (Reference,Date,Status) VALUES ('". $_SESSION['jobno'] ."','$date2','1')")or die(mysqli_error($con));

	
	mysqli_query($con, "UPDATE tbl_jc SET SlaCatId = '$sla_cat', SlaSubCatId = '$sla_sub_cat', Date1 = '$start', Date2 = '$end' WHERE QuoteNo = '". $_GET['Quote'] ."'")or die(mysqli_error($con));
	
}
// End Create Job Card

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

  <link href="../styles/fonts.css" rel="stylesheet" type="text/css" />
  <link href="../styles/layout.css" rel="stylesheet" type="text/css" />
  
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
      <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
            
      <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
      <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
      <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
        
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="../menu/script.js"></script>
            
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
      <?php //$_SESSION['company-id'] = ''; ?>
      
	  <script>
	  
		$(function() {
			
			var date_row = $('#sla-date').detach();
			var col_3 = $('#col-3').detach();
			var col_4 = $('#col-4').detach();
			
			$('#sla').change(function(){
				
				if($('#sla').val() == '5') {
					
					$('#sla-date').detach();
					$('#sla-tbl #tbl-row-1').append(col_3);
					$('#sla-tbl #tbl-row-1').append(col_4);
					
					$("#col-2").attr('colspan',1);				
					
				} else {
					
					$('#sla-tbl').append(date_row);
					$("#col-2").attr('colspan',3);
					
					$('#col-3').detach();
					$('#col-4').detach();
					
				} 
			});
		});	
		  
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
			url: "../dropdown/get-sla-sub.php",
			data:'sla_id='+val,
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

            <form action="" method="post" enctype="multipart/form-data" name="f1" class="uk-form bt-flabels js-flabels" data-parsley-validate data-parsley-errors-messages-disabled>
            <table width="700" border="0" align="center" cellpadding="2" cellspacing="1" id="sla-tbl">
              <tr>
                <td colspan="4" class="td-header">SLA</td>
              </tr>
              <tr>
                <td class="td-left">Quote No.</td>
                <td class="td-right" colspan="1" id="col-"><?php echo $_GET['Quote']; ?></td>
                <td class="td-left" id="col-5" style="display:table-cell">Job No.</td>
                <td class="td-right" id="col-6" style="display:table-cell"><input name="jobno" type="text" class="tarea-100" id="jobno" value="<?php echo $row_Recordset2['FMC']; ?>"></td>
              </tr>
              <tr id="tbl-row-1">
                <td width="140" class="td-left">SLA Category</td>
                <td class="td-right" colspan="1" id="col-2">
                
                  <div class="bt-flabels__wrapper">
                    <select name="sla" class="tarea-100" id="sla" onChange="getSlaSub(this.value);" autocomplete="off" data-parsley-required>
                      <option value="">Category...</option>
                      <?php while($row_sla_cat = mysqli_fetch_array($query_sla_cat)){ ?>
                        <option value="<?php echo $row_sla_cat['Id']; ?>" <?php if($_GET['SLA'] == $row_sla_cat['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_sla_cat['Category']; ?></option>
                      <?php } ?>
                    </select>
                    <span class="bt-flabels__error-desc-dd">Required</span>
                  </div>
                
                </td>
                <td width="140" class="td-left" id="col-3" style="display:table-cell">SLA Sub Category</td>
                <td class="td-right" id="col-4" style="display:table-cell">
                
                  <div class="bt-flabels__wrapper">
                    <select name="sub_cat" class="tarea-100" id="sla-list" autocomplete="off" data-parsley-required>
                      <option value="">Sub Category...</option>
                    </select>
                    <span class="bt-flabels__error-desc-dd">Required</span>
                  </div>
                
                </td>
              </tr>
              <tr id="sla-date">
                <td width="140" class="td-left">Strat Date</td>
                <td class="td-right">
                
                  <div class="bt-flabels__wrapper">
                    <input name="start" type="text" class="tarea-100" id="start" value="<?php echo $_GET['Start']; ?>" autocomplete="off" data-parsley-required />
                    <span class="bt-flabels__error-desc">Required</span>
                  </div>
                
				<script type="text/javascript">
                $('#start').datepicker({
                dateFormat: "yy-mm-dd"
                });
                </script>
                
                </td>
                <td width="140" class="td-left">End Date</td>
                <td class="td-right">
                
                  <div class="bt-flabels__wrapper">
                    <input name="end" type="text" class="tarea-100" id="end" value="<?php echo $_GET['End']; ?>" autocomplete="off" data-parsley-required />
                    <span class="bt-flabels__error-desc">Required</span>
                  </div>
                
				<script type="text/javascript">
                $('#end').datepicker({
                dateFormat: "yy-mm-dd"
                });
                </script>
                
                </td>
              </tr>
            </table>
            
          <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                  <td align="right"><input name="jobcard" type="submit" class="btn-new" id="jobcard" value="Create Job Card"></td>
            </tr>
          </table>
</form>
            
<script src='https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.1.2/parsley.min.js'></script>
<script src="../form-validation/js/index.js"></script>
            
</body>
</html>
<?php 
  mysqli_close($con);
  mysqli_free_result($query);
 ?>
