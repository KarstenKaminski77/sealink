<?php 
session_start();

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once('../../Connections/inv.php');
require_once('../../includes/wdg/WDG.php');
require_once('../../functions/functions.php');

select_db();

////////////////////////////////////////////////
///////////////SAVE AS NEW///////////////////
////////////////////////////////////////////////

if(isset($_POST['proceed'])){
	
	header('Location: hes.php?Id='. $_GET['Id']);
	
}

if(isset($_POST['new'])){
	
	$date = date('Y-m-d');
	$company = $_POST['company'];
	$site = $_POST['site'];
	$jobno = $_POST['ref'];
	
	// Find Company Prefix
	
	$query = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '$site'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$companyid = $row['Company'];
	
	$query2 = mysqli_query($con, $con, "SELECT * FROM tbl_companies WHERE Id = '$companyid'")or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);
	
	$prefix = $row2['Prefix'];
	
	$ref = $prefix.$jobno;
	
	$scope = $_POST['scope'];
	$equipment = $_POST['equipment'];
	$risk = $_POST['risk'];
	$contractor = $_POST['contractor'];
	$chesm = $_POST['chesm'];
	$issuer = $_POST['issuer'];
	$so = $_POST['so'];
	
	// Create New HES Instance
	
	mysqli_query($con, "INSERT INTO tbl_hes (CompanyId,SiteId,JobNo,Date,ScopeOfWork,Equipment,RiskRanking,Contractor,ContractorRating,PermitIssuer,SafetyOfficer,FallProtecionPlan,New) VALUES ('$company','$site','$ref','$date','$scope','$equipment','$risk','$contractor','$chesm','$issuer','$so','$fpp','1')")or die(mysqli_error($con));
	
	$query3 = mysqli_query($con, "SELECT * FROM tbl_hes ORDER BY Id DESC LIMIT 1")or die(mysqli_error($con));
	$row3 = mysqli_fetch_array($query3);
	
	$old_id = $_GET['Id'];
	$hesid = $row3['Id'];
	
	// Attached Safety Documents
	// Loop Through Old Records And Duplicate Them With A New HES Id
	
	$query = mysqli_query($con, "SELECT * FROM tbl_hes_documents_relation WHERE HESId = '$old_id'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$doc_id = $row['DocumentId'];
		$pdf = $row['PDF'];
		$active = $row['Active'];
		
		if($row['DocumentId'] == 4 || $row['DocumentId'] == 5){
			
			$new = '0';
			
		} else {
			
			$new = '1';
			
		}
		
		mysqli_query($con, "INSERT INTO tbl_hes_documents_relation (HESId,DocumentId,PDF,Active,New) VALUES ('$hesid','$doc_id','$pdf','$active','$new')")or die(mysqli_error($con));
	
	}
	
	mysqli_query($con, "UPDATE tbl_hes_documents_relation SET PDF = 'Seavest Fall Protection.pdf' WHERE DocumentId = '4'")or die(mysqli_error($con)); 
	
	mysqli_query($con, "UPDATE tbl_hes_documents_relation SET Active = '1' WHERE DocumentId = '4' AND DocumentId = '5'")or die(mysqli_error($con)); 
	
	// JSA DOCUMENT
	
	// Personal Protective Equipment
	
	$query = mysqli_query($con, "SELECT * FROM tbl_ppe_relation WHERE HESId = '$old_id'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$ppeid = $row['PPEId'];
		$selected = $row['Selected'];
		$comments = $row['Comments'];
		
		mysqli_query($con, "INSERT INTO tbl_ppe_relation (HESId,PPEId,Selected,Comments) VALUES ('$hesid','$ppeid','$selected','$comments')")or die(mysqli_error($con));
	}
	
	// Job Steps
	
	$query = mysqli_query($con, "SELECT * FROM tbl_job_steps WHERE HESId = '$old_id' AND JMS = '0'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$js = $row['JobSteps'];
		$hazard = $row['PotentialHazard'];
		$actions = $row['CriticalActions'];
		
		mysqli_query($con, "INSERT INTO tbl_job_steps (HESId,JobSteps,PotentialHazard,CriticalActions) VALUES ('$hesid','$js','$hazard','$actions')")or die(mysqli_error($con));
	}
	
	// JOB METHOD STATEMENT
	
	$query = mysqli_query($con, "SELECT * FROM tbl_job_steps WHERE HESId = '$old_id' AND JMS = '1'")or die(mysqli_error($con));
	while($row = mysqli_fetch_array($query)){
		
		$js = $row['JobSteps'];
		$hazard = $row['PotentialHazard'];
		$actions = $row['CriticalActions'];
		
		mysqli_query($con, "INSERT INTO tbl_job_steps (HESId,JobSteps,PotentialHazard,CriticalActions,JMS) VALUES ('$hesid','$js','$hazard','$actions','1')")or die(mysqli_error($con));
	}
	
	header('Location: ../../fpdf16/pdf-hes.php?Id='.$hesid.'&New');
}

////////////////////////////////////////////////
///////////////UPDATE CURRENT///////////////////
////////////////////////////////////////////////

$id = $_GET['Id'];

$query = mysqli_query($con, "SELECT * FROM tbl_hes WHERE Id = '$id'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);
	
$_SESSION['jobno'] = $row['JobNo'];

// Save Changes

if(isset($_POST['save']) || isset($_POST['close']) || isset($_POST['preview'])){
	
	$date = date('Y-m-d');
	$company = $_POST['company'];
	$site = $_POST['site'];
	$jobno = $_POST['ref'];
	
	// Find Company Prefix
	
	$query = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '$site'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$companyid = $row['Company'];
	
	$query2 = mysqli_query($con, "SELECT * FROM tbl_companies WHERE Id = '$companyid'")or die(mysqli_error($con));
	$row2 = mysqli_fetch_array($query2);
	
	$prefix = $row2['Prefix'];
	
	$ref = $prefix.$jobno;
	
	$scope = $_POST['scope'];
	$equipment = $_POST['equipment'];
	$risk = $_POST['risk'];
	$contractor = $_POST['contractor'];
	$chesm = $_POST['chesm'];
	$issuer = $_POST['issuer'];
	$so = $_POST['so'];
	$fpp = $_POST['fpp'];
	$id = $_GET['Id'];
	
	mysqli_query($con, "UPDATE tbl_hes SET CompanyId = '$company', SiteId = '$site', JobNo = '$ref', Date = '$date', ScopeOfWork = '$scope', Equipment = '$equipment', RiskRanking = '$risk', Contractor = '$contractor', ContractorRating = '$chesm', PermitIssuer = '$issuer', SafetyOfficer = '$so', FallProtecionPlan = '$fpp' WHERE Id = '$id'")or die(mysqli_error($con));
	
	// Update The Job Risk
	
	$count = count($_POST['jsa-risk']);
	
	$jsa_risk = $_POST['jsa-risk'];
	$id = $_GET['Id'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_hes WHERE Id = '$id'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$jobno = $row['JobNo'];
	
	mysqli_query($con, "DELETE FROM tbl_hes_jsa_relation WHERE JobNo = '$jobno'")or die(mysqli_error($con));
	
	for($i=0;$i<$count;$i++){
		
		$risk = $jsa_risk[$i];
		
		mysqli_query($con, "INSERT INTO tbl_hes_jsa_relation (JobNo,JSAId) VALUES ('$jobno','$risk')")or die(mysqli_error($con));
		
	}
	
	// Attached Safety Documents
	
	$hesid = $_GET['Id'];
	
	// Check Which Documents Will Be Attached And Update Or Create Instances In Tables
	
	$query = mysqli_query($con, "SELECT * FROM tbl_hes_documents_relation WHERE HESId = '$hesid'")or die(mysqli_error($con));
	$numrows = mysqli_num_rows($query);
	
	if($numrows == 0){
		
		$query2 = mysqli_query($con, "SELECT * FROM tbl_hes_documents")or die(mysqli_error($con));
		while($row2 = mysqli_getch_array($query2)){
			
			$documentid = $row2['Id'];
			
			mysqli_query($con, "INSERT INTO tbl_hes_documents_relation (HESId,DocumentId) VALUES ('$hesid','$documentid')")or die(mysqli_error($con));
			
		}
		
		mysqli_query($con, "UPDATE tbl_hes_documents_relation SET PDF = 'Seavest Fall Protection.pdf' WHERE DocumentId = '4'")or die(mysqli_error($con)); 
		
		mysqli_query($con, "UPDATE tbl_hes_documents_relation SET Active = '1', New = '0' WHERE DocumentId = '4' AND DocumentId = '5'")or die(mysqli_error($con)); 
		
	}
	
	// Reset Attachment List To None
	
	mysqli_query($con, "UPDATE tbl_hes_documents_relation SET Active = '0' WHERE HESId = '$hesid'")or die(mysqli_error($con));
		
	// Insert New Attachments List
		
	$documents = $_POST['documents'];
	$count = count($_POST['documents']);
		
	for($i=0;$i<$count;$i++){
		
		$docs = $documents[$i];
		
		mysqli_query($con, "UPDATE tbl_hes_documents_relation SET PDF = 'Seavest Fall Protection.pdf' WHERE DocumentId = '4'")or die(mysqli_error($con));
			
		mysqli_query($con, "UPDATE tbl_hes_documents_relation SET Active = '1' WHERE HESId = '$hesid' AND DocumentId = '$docs'")or die(mysqli_error($con));
	}
	
	if(isset($_POST['close'])){
		
		// Save and Close
		
		header('Location: ../../fpdf16/pdf-hes.php?Id='. $_GET['Id'] .'&Close');
		
	}
	
}

    $query_Recordset1 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
    $Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($Recordset1);

    $query_Recordset2 = "SELECT * FROM tbl_sites ORDER BY Name ASC";
    $Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysqli_error($con));
    while($row_Recordset2 = mysqli_fetch_assoc($Recordset2)){
        
        if(!empty($row_Recordset2['Name'])){
        
            $sites[] = $row_Recordset2;
        }
    }
    $totalRows_Recordset2 = mysqli_num_rows($Recordset2);

    $query_Recordset3 = "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC";
    $Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
    $row_Recordset3 = mysqli_fetch_assoc($Recordset3);
    $totalRows_Recordset3 = mysqli_num_rows($Recordset3);

    $colname_Recordset4 = "-1";
    if (isset($_GET['Id'])) {
      $colname_Recordset4 = $_GET['Id'];
    }

    $query_Recordset4 = "SELECT * FROM tbl_hes WHERE Id = $colname_Recordset4";
    $Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
    $row_Recordset4 = mysqli_fetch_assoc($Recordset4);
    $totalRows_Recordset4 = mysqli_num_rows($Recordset4);

    //echo '<pre>', var_dump($row_Recordset4), '</pre>';
    $query_Recordset5 = "SELECT * FROM tbl_hes_documents";
    $Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
    $row_Recordset5 = mysqli_fetch_assoc($Recordset5);
    $totalRows_Recordset5 = mysqli_num_rows($Recordset5);

    $jobno = $_SESSION['jobno'];

    if(isset($_POST['save'])){

            header('Location: ../../fpdf16/pdf-hes.php?Id='. $_GET['Id']);
    }

    if(isset($_POST['preview'])){
	
	?>
    
    <script type="text/javascript" language="Javascript">window.open('../../fpdf16/pdf-hes.php?Id=<?php echo $_GET['Id']; ?>&Preview');</script>
    
    <?php } ?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
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
<script type="text/javascript" src="../../includes/common/js/sigslot_core.js"></script>
<script src="../../includes/common/js/base.js" type="text/javascript"></script>
<script src="../../includes/common/js/utility.js" type="text/javascript"></script>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../../menu.php'); ?></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;
          </p>          
          <form name="form2" method="post" action="" style="margin-left:30px">  
          <?php if(isset($_GET['Proceed'])){ ?>           
          <table border="0" align="center" cellpadding="2" cellspacing="3">
                <tr>
                  <td nowrap class="alert">Please review the document below and <strong><em>Save</em></strong> it. Please click on proceed to continue</td>
                  <td><input name="proceed" type="submit" class="btn-red-generic" id="proceed" value="Proceed"></td>
                </tr>
                <tr>
                  <td nowrap class="alert">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
          <?php } ?>
            <div id="list-brdr">
              <table border="0" cellpadding="3" cellspacing="1">
                <tr class="td-header">
                  <td width="150" align="left"><strong>&nbsp; Company</strong></td>
                  <td width="150" align="left"><strong>&nbsp;Site</strong></td>
                  <td width="150" align="left"><strong>&nbsp;Reference</strong></td>
                  <td align="left">&nbsp;&nbsp;Date&nbsp;</td>
                  </tr>
                <tr class="even">
                  <td width="150" align="left"><select name="company" class="tarea-hes" id="company">
                    <option value="" <?php if (!(strcmp("", $row_Recordset4['CompanyId']))) {echo "selected=\"selected\"";} ?>>Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset1['Id']?>"<?php if (!(strcmp($row_Recordset1['Id'], $row_Recordset4['CompanyId']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['Name']?></option>
                    <?php
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  $rows = mysqli_num_rows($Recordset1);
  if($rows > 0) {
      mysqli_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  }
?>
                  </select></td>
                  <td width="150" align="left" id="site">
                      <select name="site" class="tarea-hes">
                        <option value="">Select one...</option>
                        <?php foreach($sites as $site){ ?>
                            <option value="<?php echo $site['Id']; ?>" <?php if($site['Id'] == $row_Recordset4['SiteId']){echo 'selected';} ?>>
                                <?php echo $site['Name']; ?>
                            </option>
                        <?php } ?>
                      </select>
                  </td>
                  <td width="150" align="left" nowrap>
                  <?php 
				  
				  	$id = $_GET['Id'];
					
					$query = mysqli_query($con, "SELECT tbl_hes.JobNo, tbl_hes.Id, tbl_companies.Prefix FROM (tbl_hes LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_hes.CompanyId) WHERE tbl_hes.Id = '$id' ")or die(mysqli_error($con));
					$row = mysqli_fetch_array($query);
					
					$prefix = $row['Prefix'];
					
					$jobno = explode($prefix, $row['JobNo']);
					
					echo $jobno[0];
				  
				  ?>
                  <input name="ref" type="text" class="tarea-hes" id="ref" value="<?php echo $jobno[1]; ?>"></td>
                  <td align="left" nowrap><input name="date" class="tarea-hes" id="date" value="<?php echo $row_Recordset4['Date']; ?>" wdg:mondayfirst="false" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="false" wdg:restricttomask="no" wdg:readonly="true"></td>
                  </tr>
                <tr class="odd">
                  <td colspan="4" class="td-header">HES Documents</td>
                </tr>
                <tr class="odd">
                  <td colspan="4">
                      <table border="0" cellpadding="2" cellspacing="3" class="comb-sms">
                        <tr>
						<?php 
						do { 
						
						$hesid = $_GET['Id'];
						$docid = $row_Recordset5['Id'];
						?>
                          <td><input name="documents[]" type="checkbox" id="documents[]" value="<?php echo $row_Recordset5['Id']; ?>"  <?php hes_risk_types($hesid,$docid); ?>></td>
                          <td><div style="margin-right:15px"><?php echo $row_Recordset5['Document']; ?></div></td>
                          <?php } while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5)); ?>
                        </tr>
                      </table>
                  </td>
                </tr>
                <tr class="odd">
                  <td colspan="4" class="td-header">&nbsp;JSA Type</td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <?php
do { // horizontal looper version 3

$jobno = $row_Recordset4['JobNo'];
$jsaid = $row_Recordset3['Id'];

$query = mysqli_query($con, "SELECT * FROM tbl_hes_jsa_relation WHERE JobNo = '$jobno' AND JSAId = '$jsaid'")or die(mysqli_error($con));
$numrows = mysqli_num_rows($query);
?>
                        <td><table border="0" cellpadding="2" cellspacing="3" class="comb-sms">
                          <tr>
                            <td><input name="jsa-risk[]" type="checkbox" id="jsa-risk[]" value="<?php echo $row_Recordset3['Id']; ?>" <?php if($numrows == 1){ echo 'checked="checked"'; } ?>></td>
                            <td><?php echo $row_Recordset3['Risk']; ?></td>
                            <td>&nbsp;</td>
                            </tr>
                        </table></td>
<?php
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
    if (!isset($nested_Recordset3)) {
      $nested_Recordset3= 1;
    }
    if (isset($row_Recordset3) && is_array($row_Recordset3) && $nested_Recordset3++ % 6==0) {
      echo "</tr><tr>";
    }
  } while ($row_Recordset3); //end horizontal looper version 3
?>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="td-header">The risk matrix is as follows</td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Scope of work</td>
                      <td><input name="scope" type="text" class="tarea-jla-matrix" id="scope" value="<?php echo $row_Recordset4['ScopeOfWork']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Equipment to be used</td>
                      <td><input name="equipment" type="text" class="tarea-jla-matrix" id="equipment" value="<?php echo $row_Recordset4['Equipment']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Risk Ranking of job</td>
                      <td><select name="risk" class="tarea-jla-matrix" id="risk">
                        <option value="" <?php if (!(strcmp("", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>Select Risk...</option>
                        <option value="H/H" <?php if (!(strcmp("H/H", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>H/H</option>
                        <option value="M/H" <?php if (!(strcmp("M/H", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>M/H</option>
<option value="L/H" <?php if (!(strcmp("L/H", $row_Recordset4['RiskRanking']))) {echo "selected=\"selected\"";} ?>>L/H</option>
                        </select></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Contractor doing job</td>
                      <td><input name="contractor" type="text" class="tarea-jla-matrix" id="contractor" value="<?php echo $row_Recordset4['Contractor']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Contractor CHESM Rating</td>
                      <td><textarea name="chesm" cols="45" rows="1" class="tarea-jla-matrix" id="chesm"><?php echo $row_Recordset4['ContractorRating']; ?></textarea></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Permit Issuer</td>
                      <td><input name="issuer" type="text" class="tarea-jla-matrix" id="issuer" value="<?php echo $row_Recordset4['PermitIssuer']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" class="odd"><table border="0" cellpadding="0" cellspacing="0" class="comb-sms">
                    <tr>
                      <td width="150">Safety Officer</td>
                      <td><input name="so" type="text" class="tarea-jla-matrix" id="so" value="<?php echo $row_Recordset4['SafetyOfficer']; ?>"></td>
                    </tr>
                  </table></td>
                </tr>
                <tr class="even">
                  <td colspan="4" align="right" class="td-header">
                  <?php if(isset($_GET['New'])){ ?>
                  <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><input name="new" type="submit" class="btn-green-generic" id="new" value="Save As New" ></td>
                      </tr>
                  </table>
                  <?php } ?>
                  <?php if(!isset($_GET['New']) && !isset($_GET['Proceed'])){ ?>
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><input name="preview" type="submit" class="btn-blue-generic" id="preview" value="Preview"></td>
                        <td><input name="save" type="submit" class="btn-blue-generic" id="save" value="Save"></td>
                        <td><input name="close" type="submit" class="btn-blue-generic" id="close" value="Save &amp; Close"></td>
                      </tr>
                    </table>
                    <?php } ?>
                    </td>
                </tr>
              </table>
            </div>
          </form>
          <p>
          </p>
          </p>
</td>
      </tr>
    </table></td>
  </tr>
</table>
    <script>
        
        $(document).ready(function(){
            
            $("#company").on("change", function(e) {

                var company_id = $('#company').val();        
                $.ajax({
                    url: "get-sites.php",
                    type: "post",
                    data: {company_id:company_id},
                    success: function (response) {
                        $('#site').empty();
                        $('#site').append(response);
                    },
                    error: function() {
                        alert("Error!");
                    }
                });
            });
        });
        
    </script>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($Recordset2);

mysqli_free_result($Recordset3);

mysqli_free_result($Recordset4);
?>
