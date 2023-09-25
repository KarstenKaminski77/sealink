<?php 
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

require_once('../functions/functions.php');

$companyid = $_GET['Company'];

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset3 = "SELECT * FROM tbl_engineers ORDER BY Name ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_Recordset4 = "SELECT * FROM tbl_engineers WHERE CompanyId = '$companyid' ORDER BY Name ASC";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$query_status_company = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$status_company = mysqli_query($con, $query_status_company) or die(mysqli_error($con));
$row_status_company = mysqli_fetch_assoc($status_company);
$totalRows_status_company = mysqli_num_rows($status_company);

if(!empty($_POST['date1']) || !empty($_POST['date2']) || !empty($_POST['company']) || !empty($_POST['engineer'])){
	
	if(!empty($_POST['company'])){
		
		$company = 'tbl_jc.CompanyId = "'. $_POST['company'] .'" AND ';
		
	}
	
	if(!empty($_POST['engineer'])){
		
		$engineer = 'tbl_engineers.Id = "'. $_POST['engineer'] .'" AND ';
		
   }
   
   $where = $company . $engineer . "tbl_jc.Status <= '5'";
   
} elseif(isset($_GET['Company'])){
	
	$where = "tbl_jc.CompanyId = '". $_GET['Company'] ."' AND tbl_jc.SiteId = '". $_GET['Site'] ."' AND tbl_jc.Status <= '5'";
      
} else {
	
	$where = "tbl_engineers.Id = '-1'";
	
}

$query_Recordset5 = "SELECT tbl_companies.Name AS Name_2, tbl_engineers.Name AS Name_1, tbl_sites.Name, tbl_engineers.Id, tbl_jc.CustomerFeedBack, tbl_engineers.CompanyId, tbl_engineers.Email, tbl_jc.JobNo, tbl_history_photos.Photo, tbl_history_relation.JobId, tbl_jc.JcDate, tbl_jc.Status, tbl_jc.Progress, tbl_jc.JobId AS JobId_1 FROM (((((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_history_relation ON tbl_history_relation.JobId=tbl_jc.JobId) LEFT JOIN tbl_history_photos ON tbl_history_photos.Id=tbl_history_relation.PhotoId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) LEFT JOIN tbl_engineers ON tbl_engineers.Id=tbl_sites.EngineerId) WHERE $where GROUP BY tbl_jc.JobId";
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
      
      
	  <script language=JavaScript>
	  
		function reload1(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			self.location='index-new.php?Company=' + val ;
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
            <li><a href="#">Scheduled Maintenance</a></li>
            <li><a href="#">Status Reports</a></li>
            <li><a href="#">In Progress</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="../job-cards/search.php">
          <input name="search" type="text" class="search-top" onfocus="if(this.value=='Search...'){this.value=''}" onblur="if(this.value==''){this.value='Search...'}" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
        <form action="" method="post" name="form2" id="form2">
          <table border="0" cellspacing="3" cellpadding="2">
            <tr>
              <td width="200">
              <select name="company" class="select-2-dd" id="company" onchange="reload1(this.form)">
                <option value="">Select one...</option>
                <?php
do {  
?>
                <option value="<?php echo $row_status_company['Id']?>"<?php if ($row_status_company['Id'] == $_GET['Company']) {echo "selected=\"selected\"";} ?>><?php echo $row_status_company['Name']?></option>
                <?php
} while ($row_status_company = mysqli_fetch_assoc($status_company));
$rows = mysqli_num_rows($status_company);
if($rows > 0) {
    mysqli_data_seek($status_company, 0);
    $row_status_company = mysqli_fetch_assoc($status_company);
}
?>
              </select></td>
              <td width="200"><select name="engineer" class="select-2-dd" id="engineer">
                <option value="">Select 0ne...</option>
                <?php do{ ?>
                <option value="<?php echo $row_Recordset4['Id']; ?>"><?php echo $row_Recordset4['Name']; ?></option>
                <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
              </select></td>
              <td><input name="Submit" type="submit" class="btn-new-2" id="Submit" value="Search" /></td>
            </tr>
          </table>
          <br />
          <br />
        </form>
        <?php if(isset($_GET['Success'])){ ?>
        <span class="btn-red-generic">Status report successfully sent.</span>
        <?php } ?>
        <form action="mail.php" method="post" name="form-status" id="form-status">
          <?php if($totalRows_Recordset5 >= 1 && isset($_POST['Submit'])){ ?>
          <div id="list-border">
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr>
                
                <?php
				if(filter_var($row_Recordset5['Email'], FILTER_VALIDATE_EMAIL) === false){
					
					$to = 'To';
					
				} else {
					
					$to = $row_Recordset5['Email'];
				}
				?>
                
                <td nowrap="nowrap" class="td-right"><input name="email" type="text" class="tarea-100" id="email" value="<?php echo $to; ?>" onfocus="if (this.value=='To') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='To';" /></td>
              </tr>
              <tr>
                <td nowrap="nowrap" class="td-right"><input name="cc" type="text" class="tarea-100" id="cc" value="Cc" onfocus="if (this.value=='Cc') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Cc';" /></td>
              </tr>
              <tr>
                <td nowrap="nowrap" class="td-right"><textarea name="message" rows="5" class="tarea-100" id="message" onfocus="if (this.value=='Message') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Message';">Mr <?php echo $row_Recordset5['Name_1']; ?>
&#010;
Herewith is a status report for work in progress </textarea></td>
              </tr>
              </table>
          </div>
            
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
            <tr>
              <td align="right" nowrap="nowrap"><input name="date1" type="hidden" id="date1" value="<?php echo $_POST['date1']; ?>" />
                <input name="date2" type="hidden" id="date2" value="<?php echo $_POST['date2']; ?>" />
                <input name="engineer" type="hidden" id="engineer" value="<?php echo $_POST['engineer']; ?>" />
                <input name="Submit2" type="submit" class="btn-new" value="Send" /></td>
            </tr>
          </table>
          <?php } ?>
          <?php if($totalRows_Recordset5 >= 1 && isset($_POST['Submit'])){ ?>
          <br />
          <br />
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><div id="list-border">
                <table width="100%" border="0" cellpadding="3" cellspacing="1" class="combo">
                  <tr class="odd">
                    <td width="100" bgcolor="#E1E1E1" class="td-header"><strong>JobNo</strong></td>
                    <td bgcolor="#E1E1E1" class="td-header"><strong>Site</strong></td>
                    <td width="300" bgcolor="#E1E1E1" class="td-header"><strong>Progress</strong></td>
                    <td colspan="3" align="right" bgcolor="#E1E1E1" class="td-header" width="60">&nbsp;</td>
                  </tr>
                  <?php 
					$i = 0;
					
					$count = $totalRows_Recordset5;
					
					do { 
					
					$i++;
				  ?>
                  <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                    <td width="121"><?php echo $row_Recordset5['JobNo']; ?></td>
                    <td><?php echo $row_Recordset5['Name']; ?></td>
                    <td align="right">
                    <div id="status-graph-brdr">
                      <div id="status-graph" style="width:<?php echo $row_Recordset5['Progress']; ?>%"></div>
                    </div></td>
                    <td width="30" align="center" class="KT_field_error"><strong><?php echo $row_Recordset5['Progress']; ?>%</strong></td>
                    <td width="30" align="center" class="KT_field_error"><?php if($row_Recordset5['JobId'] != NULL){ ?>
                      <a href="../photo_view_history.php?Id=<?php echo $row_Recordset5['JobId']; ?>&amp;photos"> <img src="../images/camera-icon.png" width="25" height="20" border="0" /> </a>
                      <?php } ?></td>
                    <td width="30" align="center" class="KT_field_error"><input name="status-id[]" type="checkbox" id="status-id[]" value="<?php echo $row_Recordset5['JobId_1']; ?>" /></td>
                  </tr>
                  <?php if(!empty($row_Recordset5['CustomerFeedBack'])){ ?>
                  <tr bgcolor="#E1E1E1" class="comb-sms">
                    <td colspan="6"><table border="0" cellpadding="10" cellspacing="0" class="comb-sms">
                      <tr>
                        <td><?php echo nl2br($row_Recordset5['CustomerFeedBack']); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <?php } ?>
                  <?php } while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
                </table>
              </div></td>
            </tr>
          </table>
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