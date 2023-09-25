<?php 
require_once('../../Connections/seavest.php'); 

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

//MX Widgets3 include
require_once('../../includes/wdg/WDG.php');

require_once('../../functions/functions.php');

select_db();

$userid = $_SESSION['kt_login_id'];

$query = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$userid'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$name = $row['Name'];
$date = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];

mysqli_query($con, "INSERT INTO tbl_login (Name, Date, Location) VALUES ('$name','$date','$ip')")or die(mysqli_error($con));

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysql_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysql_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$userid = $_SESSION['kt_login_id'];

$query_company = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC")or die(mysqli_error($con));

if(isset($_POST['save'])){
	
	for($i=0;$i<count($_POST['comments']);$i++){
		
		$jobid = $_POST['jobid'][$i];
		$comments = $_POST['comments'][$i];
		
		mysqli_query($con, "UPDATE tbl_jc SET SLAComments = '$comments' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	}
}

for($i=0;$i<count($_POST['jobid']);$i++){
	
	$jobid = $_POST['jobid'][$i];
	
	mysqli_query($con, "UPDATE tbl_jc SET SLAClose = '0' WHERE JobId = '$jobid'")or die(mysqli_error($con));
}

if(isset($_POST['close'])){
	
	for($i=0;$i<count($_POST['close']);$i++){
		
		$jobid = $_POST['close'][$i];
		
		mysqli_query($con, "UPDATE tbl_jc SET SLAClose = '1' WHERE JobId = '$jobid'")or die(mysqli_error($con));
	}
}

if(empty($_POST)){
	
	unset($_SESSION['c_id']);
	unset($_SESSION['r_id']);
}

if(isset($_POST['search'])){
		
	$_SESSION['c_id'] = $_POST['country'];
	$_SESSION['r_id'] = $_POST['reference'];	
}

$companyid = $_SESSION['c_id'];
$ref = $_SESSION['r_id'];

$query_sla = "
  SELECT
	  tbl_companies.`Name` AS CompanyName,
	  tbl_sites.`Name` AS SiteName,
	  tbl_engineers.`Name` AS EngineerName,
	  tbl_engineers.Email
  FROM
	  tbl_companies
  INNER JOIN tbl_sites ON tbl_companies.Id = tbl_sites.Company
  INNER JOIN tbl_engineers ON tbl_sites.EngineerId = tbl_engineers.Id
  WHERE
	  tbl_companies.Id = '$companyid'
  AND tbl_engineers. NAME = '$ref'";
$query_sla = mysqli_query($con, $query_sla)or die(mysqli_error($con));
$row_sla = mysqli_fetch_array($query_sla);

$query_sla_report = "
  SELECT
	  tbl_companies.Name AS CompanyName,
	  tbl_sites.Name AS SiteName,
	  tbl_engineers.Name AS EngineerName,
	  tbl_engineers.Email,
	  tbl_jc.JobNo,
	  tbl_jc.Date1,
	  tbl_jc.Date2,
	  tbl_jc.SLAComments,
	  tbl_jc.SLAClose,
	  tbl_jc.JobDescription,
	  tbl_jc.JobId
  FROM
	  tbl_engineers
  INNER JOIN tbl_jc ON tbl_engineers.`Name` = tbl_jc.Reference
  INNER JOIN tbl_companies ON tbl_jc.CompanyId = tbl_companies.Id
  INNER JOIN tbl_sites ON tbl_jc.SiteId = tbl_sites.Id
  WHERE
	  tbl_jc.CompanyId = '$companyid'
	  AND tbl_jc.Reference = '$ref'
	  AND (tbl_jc.`Status` = '1'
	  OR tbl_jc.`Status` = '2'
	  OR tbl_jc.`Status` = '3'
	  OR tbl_jc.`Status` = '17'
	  OR tbl_jc.`Status` = '19')

  GROUP BY
	  tbl_jc.JobId";
$query_sla_report = mysqli_query($con, $query_sla_report)or die(mysqli_error($con));
$rows_sla = mysqli_num_rows($query_sla_report);

if(isset($_POST['send'])){
	
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['cc'] = $_POST['cc'];
	$_SESSION['message'] = $_POST['message'];
	
	header('Location: ../../fpdf16/pdf_sla_report.php');
	
	exit();
}
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
	font-weight: bold;
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
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../../styles/layout.css" rel="stylesheet" type="text/css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('#country').change(function(){
		var country_id = $('#country').val();
		if(country_id != 0)
		{
			$.ajax({
				type:'post',
				url:'../../scripts/getvalue.php',
				data:{id:country_id},
				cache:false,
				success: function(returndata){
					$('#city').html(returndata);
				}
			});
		}
	})
})
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php include('../../menu.php'); ?>
    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
      </tr>
      <tr>
        <td>
        <br><br>
        <form name="form2" method="post" action="" style="margin-left:30px">
        <?php if(isset($_GET['Success'])){ ?>
          <div id="banner-success-mail">SLA Report Successfully Sent.</div>
        <?php } ?>
        <?php if(isset($_GET['Error'])){ ?>
          <div id="banner-overdue">SLA Report Failed, Please Try Again.</div>
        <?php } ?>
          <table border="0" cellspacing="3" cellpadding="2">
              <tr>
                <td><span class="inputbox">
                  <select name="country" class="tarea" id="country">
                    <option value="0">Oil Company...</option>
                    <?php $sql = mysqli_query($con, 'SELECT * FROM tbl_companies'); ?>
                    <?php while($row = mysqli_fetch_array($sql)){ ?>
                      <option value="<?php echo $row['Id']; ?>" <?php if($row['Id'] == $_POST['country']){ echo 'selected="selected"'; } ?>><?php echo $row['Name']; ?></option>
                    <?php } ?>
                  </select>
                  </span></td>
                <td><span class="inputbox">
                  <select name="reference" class="tarea" id="city">
                    <option value="0">Reference...</option>
                    <option></option>
                  </select>
                </span></td>
                <td><input name="search" type="submit" class="btn_search" id="search" value=""></td>
              </tr>
          </table>
          <p>&nbsp;</p>
          <?php if($rows_sla >= 1){ ?>
          <table width="100%" border="0" cellpadding="3" cellspacing="1">
            <tr>
              <td nowrap>
                <input name="email" type="text" class="tarea-100per" id="email" value="<?php echo $row_sla['Email']; ?>" onFocus="if (this.value=='To') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='To';"></td>
            </tr>
            <tr>
              <td nowrap>
                <input name="cc" type="text" class="tarea-100per" id="cc" value="Cc" onFocus="if (this.value=='Cc') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Cc';"></td>
            </tr>
            <tr>
              <td nowrap><textarea name="message" rows="5" class="tarea-100per" id="message" onFocus="if (this.value=='Message') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Message';">Mr <?php echo $row_sla['EngineerName']; ?>
&#010;
Herewith is a SLA report for work in progress.</textarea></td>
            </tr>
            <tr>
              <td align="right" nowrap><input name="date1" type="hidden" id="date1" value="<?php echo $_POST['date1']; ?>">
                <input name="date2" type="hidden" id="date2" value="<?php echo $_POST['date2']; ?>">
                <input name="engineer" type="hidden" id="engineer" value="<?php echo $_POST['engineer']; ?>">
                <input name="send" type="submit" class="combo_bold" id="send" value="Send"></td>
            </tr>
            <tr>
              <td align="right" nowrap>&nbsp;</td>
            </tr>
            <tr>
              <td align="right" nowrap>
              <div style="padding:1px; border:solid 1px #CCC">
                    <table width="100%" border="0" cellpadding="3" cellspacing="1" class="combo">
                      <tr class="td-header">
                        <td width="108"><strong>JobNo</strong></td>
                        <td width="220"><strong>Site</strong></td>
                        <td><strong>Received Date</strong></td>
                        <td colspan="2"><strong>Requested Completion</strong></td>
                        </tr>
                      <?php 
$i = 0;

$count = $totalRows_Recordset5;

while($row_sla_report = mysqli_fetch_array($query_sla_report)){ 

$i++;
?>
                      <?php
					  if($row_sla_report['SLAClose'] == 1){
						  
						  $bg1 = '#a2f3aa';
						  $bg2 = '#2a9133';
						  $colour = 'style="color:#FFF"';
						  $style = 'style="color:#333; background:none; border:none"';
						  
					  } else {
						  
						  $bg1 = '#F4F4F4';
						  $bg2 = '#E1E1E1';
						  $colour = '';
						  $style = '';
					  }
					  ?>
                      <tr bgcolor="<?php echo $bg2; ?>" <?php echo $colour; ?> class="comb-sms">
                        <td width="108"><?php echo $row_sla_report['JobNo']; ?></td>
                        <td><?php echo $row_sla_report['SiteName']; ?></td>
                        <td width="162"><?php echo $row_sla_report['Date1']; ?></td>
                        <td width="235"><?php echo $row_sla_report['Date2']; ?></td>
                        <td width="20" align="center">
                        <input name="close[]" type="checkbox" id="close[]" title="Complete" onChange="this.form.submit()" value="<?php echo $row_sla_report['JobId']; ?>" <?php if($row_sla_report['SLAClose'] == 1){ echo 'checked="checked"'; } ?>></td>
                        </tr>
                      <tr bgcolor="<?php echo $bg1; ?>" class="comb-sms">
                        <td colspan="5"><?php echo stripslashes($row_sla_report['JobDescription']); ?></td>
                      </tr>
                      <tr bgcolor="<?php echo $bg1; ?>" <?php echo $colour; ?> class="comb-sms">
                        <td colspan="5">
                        <?php
						if(!empty($row_sla_report['SLAComments'])){
							
							$value = $row_sla_report['SLAComments'];
							
						} else {
							
							$value =  "Comments";
						} 
						?>
                        <textarea name="comments[]" cols="45" rows="5" class="tarea-100per" <?php echo $style; ?> id="comments[]" onFocus="if (this.value=='Comments') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Comments';"><?php echo $value; ?></textarea>
                        <input type="hidden" name="jobid[]" id="jobid[]" value="<?php echo $row_sla_report['JobId']; ?>"></td>
                      </tr>
                      <?php } ?>
                    </table>
                  </div></td>
            </tr>
            <tr>
              <td align="right" nowrap><input name="save" type="submit" class="combo_bold" id="save" value="Save"></td>
            </tr>
          </table>
          <?php } ?>
          </form></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
