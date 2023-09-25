<?php 

session_start();

require_once('../../Connections/seavest.php');
require_once('../../Connections/inv.php');
require_once('../../functions/functions.php');


$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);


$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);


$query_Recordset3 = "SELECT tbl_sites.Id AS SiteId,tbl_sites.Name AS Name_1, tbl_hes.Id, tbl_hes.JobNo, tbl_hes.Date, tbl_companies.Name FROM ((tbl_hes LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_hes.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_hes.CompanyId) ";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$colname_Recordset5 = "-1";
if (isset($_GET['Email'])) {
  $colname_Recordset5 = $_GET['Email'];
}
mysqli_select_db($database_inv);
$query_Recordset5 = "SELECT * FROM tbl_hes_documents_relation WHERE HESId = '$colname_Recordset5' AND PDF != '' AND Active = '1' AND New = '0'";
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

$colname_Recordset6 = "-1";
if (isset($_GET['Email'])) {
  $colname_Recordset6 = $_GET['Email'];
}
mysqli_select_db($database_inv);
$query_Recordset6 = "SELECT * FROM tbl_hes_documents_relation WHERE HESId = '$colname_Recordset6' AND Active = '1'";
$Recordset6 = mysqli_query($con, $query_Recordset6) or die(mysqli_error($con));
$row_Recordset6 = mysqli_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysqli_num_rows($Recordset6);

$colname_Recordset7 = "-1";
if (isset($_GET['Email'])) {
  $colname_Recordset7 = $_GET['Email'];
}
mysqli_select_db($database_inv);
$query_Recordset7 = "SELECT * FROM tbl_hes WHERE Id = '$colname_Recordset7'";
$Recordset7 = mysqli_query($con, $query_Recordset7) or die(mysqli_error($con));
$row_Recordset7 = mysqli_fetch_assoc($Recordset7);
$totalRows_Recordset7 = mysqli_num_rows($Recordset7);

if(isset($_GET['Email'])){
	
	if($row_Recordset7['New'] == 1){
		
		//header('Location: hes.php?Id='.  $row_Recordset7['Id'] .'&New');
		
	}
	
	if($totalRows_Recordset5 != $totalRows_Recordset6){
		
		$difference = $totalRows_Recordset6 - $totalRows_Recordset5;
		
		$no_documents = $totalRows_Recordset6 - $difference;
		
		if($no_documents >= 2){
			
			$documents = 'documents';
			
		} else {
			
			$documents = 'document';
			
		}
		
		$alert = "You have only created ". $no_documents . " ". $documents .", please create the remaining documents before sending email.";
		
	}
}

///////////////////////////////////////////
//////////////DELETE///////////////////////
///////////////////////////////////////////

if(isset($_GET['Delete'])){
	
	$id = $_GET['Delete'];
	
	mysqli_query($con, "DELETE FROM tbl_hes WHERE Id = '$id'")or die(mysqli_error($con));
	mysqli_query($con, "DELETE FROM tbl_hes_documents_relation WHERE HESId = '$id'")or die(mysqli_error($con));
	mysqli_query($con, "DELETE FROM tbl_job_steps WHERE HESId = '$id'")or die(mysqli_error($con));
	mysqli_query($con, "DELETE FROM tbl_ppe_relation WHERE HESId = '$id'")or die(mysqli_error($con));
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
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
<link href="../../styles/fonts.css" rel="stylesheet" type="text/css">
<link href="../../styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="1023" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php include('../../menu.php'); ?>
    </td>
    <td width="823" valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><form action="../../fpdf16/pdf/hes-mail.php?Id=<?php echo $_GET['Email']; ?>" method="post" enctype="multipart/form-data" name="form2" style="padding-left:30px">
          <table width="100%" border="0" cellpadding="3" cellspacing="1">
            <?php if(isset($_GET['Email'])){ ?>
            <tr>
              <td colspan="10" nowrap><input name="email" type="text" class="td-mail" id="email" value="To" onfocus="if (this.value=='To') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:100%" /></td>
            </tr>
            <tr>
              <td colspan="10" nowrap><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <?php
do { // horizontal looper version 3
?>
                      <td width="40"><div style="margin-left:10px">
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><img src="../../images/icons/pdf.png" width="15" height="15" /></td>
                            <td class="pdf-attachment"><?php echo $row_Recordset5['PDF']; ?></td>
                          </tr>
                        </table>
                      </div></td>
                      <?php
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
    if (!isset($nested_Recordset5)) {
      $nested_Recordset5= 1;
    }
    if (isset($row_Recordset5) && is_array($row_Recordset5) && $nested_Recordset5++ % 6==0) {
      echo "</tr><tr>";
    }
  } while ($row_Recordset5); //end horizontal looper version 3
?>
                    </tr>
                  </table></td>
                  </tr>
                <tr>
                  <td><div style="width:90px; overflow:hidden">
                    <input name="attach" type="file" id="attach" size="0" />
                  </div></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="10" nowrap><textarea name="message" rows="5" class="td-mail" id="message" onfocus="if (this.value=='Message') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:100%">Message</textarea></td>
            </tr>
            <tr>
              <td colspan="10" align="right" nowrap class="alert"><?php if(isset($alert)){
				  
				  echo '<div style="padding-top:10px">'. $alert .'</div>';
				  
			  } else {
			  ?>
                <input name="Submit2" type="submit" class="btn-blue-generic" value="Send" /></td>
              <?php } ?>
            </tr>
            <?php } 
		  
		  if(isset($_GET['Success'])){
			  
		  ?>
            <tr>
              <td colspan="10" align="center" nowrap class="alert"> Health & Safety documents for Job No: <?php echo $_GET['Success']; ?> successfully sent. </td>
            </tr>
            <?php } ?>
            <tr>
              <td colspan="10" align="center" nowrap>&nbsp;</td>
            </tr>
            </table>
          <div id="list-brdr">
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr class="td-header">
                <td width="120" align="center" nowrap><strong>Job No. </strong></td>
                <td width="205" align="left"><strong>Company</strong></td>
                <td align="left"><strong>Site Address </strong></td>
                <td width="26" align="center">&nbsp;</td>
                <td width="26" align="center">&nbsp;</td>
                <td width="20" align="center">&nbsp;</td>
                <td width="25" align="center">&nbsp;</td>
                <td colspan="3" align="center">&nbsp;</td>
                </tr>
              <?php 
  
  $i = 0;
  
  do { 
  
  $i++;
  
$id = $row_Recordset3['Id'];

mysqli_select_db($database_inv);
$query_Recordset4 = "SELECT tbl_hes_documents.URL, tbl_hes_documents_relation.PDF, tbl_hes_documents.BtnReady, tbl_hes_documents.BtnWaiting, tbl_hes_documents_relation.HESId, tbl_hes_documents_relation.DocumentId, tbl_hes_documents_relation.New, tbl_hes_documents.Document FROM (tbl_hes_documents LEFT JOIN tbl_hes_documents_relation ON tbl_hes_documents_relation.DocumentId=tbl_hes_documents.Id) WHERE tbl_hes_documents_relation.HESId = '$id' AND tbl_hes_documents_relation.DocumentId != '4' AND tbl_hes_documents_relation.DocumentId != '5' AND tbl_hes_documents_relation.Active = '1' ";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);


$jobid = $row_Recordset3['JobId'];

?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onmouseover="this.oldClassName = this.className; this.className='list-over';" onmouseout="this.className = this.oldClassName;">
                <td width="120" align="center" class="combo"><?php echo $row_Recordset3['JobNo']; ?></td>
                <td width="205" class="combo"><?php echo $row_Recordset3['Name']; ?></td>
                <td class="combo"><?php echo $row_Recordset3['Name_1']; ?></td>
                <td width="26" align="center"><a href="../../fpdf16/pdf-hes.php?Id=<?php echo $row_Recordset3['Id']; ?>&Preview" target="_blank"><img title="View PDF" src="../../images/icons/btn-view.png" width="25" height="25" border="0" /></a></td>
                <td width="26" align="center"><a onclick="return confirmSubmit()" href="hes.php?Id=<?php echo $row_Recordset3['Id']; ?>"> <img title="Edit" src="../../images/icons/btn-edit.png" width="25" height="25" border="0" /></a></td>
                <td align="center"><a href="pending.php?Email=<?php echo $row_Recordset3['Id']; ?>"><img src="../../images/icons/btn-mail.png" width="25" height="25" border="0" /></a></td>
                <?php do {
				  
				  if($row_Recordset4['DocumentId'] == '1'){
					  
					  $id = $row_Recordset3['SiteId'] .'&HESId='. $row_Recordset4['HESId'];
					  
				  } else {
					  
					  $id = $row_Recordset4['HESId'];
				  }
				  
				  $pdf = $row_Recordset4['PDF'];
				  
				  if(!empty($pdf) && $row_Recordset4['New'] == '0'){
					  
					  $image = $row_Recordset4['BtnReady'];
					  
				  } else {
					  
					  $image = $row_Recordset4['BtnWaiting'];
	
				  }
?>
                <td width="25" align="center"><a href="<?php echo $row_Recordset4['URL'].'?Id='. $id; ?>"> <img src="../../images/icons/<?php echo $image; ?>" width="25" height="25" /> </a></td>
                <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                <?php if($totalRows_Recordset4 == 3){ echo '<td></td>'; } ?>
                <td width="25"><a href="pending.php?Delete=<?php echo $row_Recordset3['Id']; ?>"><img src="../../images/icons/btn-delete.png" width="25" height="25" border="0" /></a></td>
                </tr>
              <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
            </table>
          </div>
        </form>
        <br><br>
          <div class="KT_bottomnav" align="center">
            <div class="combo"></div>
          </div></td></tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);

mysqli_free_result($Recordset4);

mysqli_free_result($Recordset5);
?>
