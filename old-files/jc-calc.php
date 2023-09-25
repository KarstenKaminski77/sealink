<?php 
session_start();
require_once('../Connections/seavest.php'); ?>
<?php
require_once('../Connections/inv.php');
require_once('../functions/functions.php');

logout($con);

$today = date('Y-m-j');
$jobid = mysqli_real_escape_string($con, $_GET['Id']);

if(isset($_POST['Submit3'])){
    
    // Insert / update Pragma table
        
    $query = mysqli_query($con, "SELECT CompanyId, JobNo FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);
    
    if($row['CompanyId'] == 2){
        
        $jobno = $row['JobNo'];
                $component = $_POST['component'];
        $failure_type = $_POST['failure-type'];
        $failure = $_POST['failure'];
        $root_cause = $_POST['root-cause'];
        $repair = $_POST['repair'];
        $followup = $_POST['followup'];
        
        if(empty($_POST['ptw-number'])){
            
            $ptw = 'No';
            $ptw_number = 'N/A';
            
        } else {
            
            $ptw = 'Yes';
            $ptw_number = $_POST['ptw-number'];
            
        }
        
        mysqli_query($con, "DELETE FROM tbl_pragma WHERE JobNo = '$jobno'") or die(mysqli_error($con));
        
        mysqli_query($con, "INSERT INTO tbl_pragma (JobNo,JobId,Component,FailureTypeCode,Failure,RootCause,Repair,PTW,PTWNumber,FollowUpWork) VALUES ('$jobno','$jobid','$component','$failure_type','$failure','$root_cause','$repair','$ptw','$ptw_number','$followup')") or die(mysqli_error($con));
        
    }
    // End insert / update Pragma table
    
    // Allocate technicians to actual history
    if(isset($_POST['tech'])){
        
        mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'") or die(mysqli_error($con));
        
        $numrows = count($_POST['tech']);
        $techid = $_POST['tech'];
                $date = date('Y-m-d');
        
        $technician_id = array();
        
        for($i = 0; $i < $numrows; $i++){
            
            $techid_1 = $techid[$i];
                        
            $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
            $row = mysqli_fetch_array($query);
            
            $siteid = $row['SiteId'];
            
            $query = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '$siteid'") or die(mysqli_error($con));
            $row = mysqli_fetch_array($query);
            
            $query2 = mysqli_query($con, "SELECT * FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId = '$techid_1'") or die(mysqli_error($con));
            $row2 = mysqli_fetch_array($query2);
            $numrows2 = mysqli_num_rows($query2);
            
            $query3 = mysqli_query($con, "SELECT JobNo FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
            $row3 = mysqli_fetch_array($query3);
            $numrows3 = mysqli_num_rows($query3);
            
            $jobno = $row3['JobNo'];
            
            array_push($technician_id, $techid_1);
            
            $site = addslashes($row['Name']);
            
            mysqli_query($con, "INSERT INTO tbl_history_alerts (Site,JobNo,Date,JobId,TechnicianId) VALUES ('$site','$jobno','$date','$jobid','$techid_1')") or die(mysqli_error($con));
            
        }
        
        mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobNo = '' AND Site = '' AND JobId = '$jobid'") or die(mysqli_error($con));
        
        mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId NOT IN ('" . join("','", $technician_id) . "')") or die(mysqli_error($con));
    }
    
    
    
    if($_POST['complete'] == 1){
        
        $siteid = $row['SiteId'];
        
        $query3 = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Id = '$siteid'") or die(mysqli_error($con));
        $row3 = mysqli_fetch_array($query3);
        
        $site = $row3['Name'];
        $jobno = $row['JobNo'];
        $date = date('Y-m-d');
        $technician = $_POST['tech'];
        
        mysqli_query($con, "INSERT INTO tbl_history_alerts (JobNo,Date,JobId,TechnicianId,Site) VALUES ('$jobno','$date','$jobid','$technician','$site')") or die(mysqli_error($con));
        
        //        $techid = $_POST['tech'];
        //		
        //		$query4 = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Id = '$techid'")or die(mysqli_error($con));
        //		$row4 = mysqli_fetch_array($query4);
        //		
        //		$cell = $row4['Cell'];
        //		$to  = 'sms@messaging.clickatell.com'; 
        //        $subject = 'Seavest';
        //        $from = "control@seavest.co.za";
        //
        //$message = '
        //user:seavest
        //password:abc123
        //api_id:3232946
        //to:'. $cell .'
        //reply: control@seavest.co.za
        //concat: 3
        //text: Job '. $jobno .' '. $site .' is now in progress';
        //
        //$headers  = 'MIME-Version: 1.0' . "\r\n";
        //$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
        //
        //$headers .= 'FROM: '. $from . "\r\n";
        //
        //mail($to, $subject, $message, $headers);
        
    }
}

// Actual History
$actual_history = $_POST['comment'];

$query = mysqli_query($con, "SELECT * FROM tbl_actual_history WHERE JobId = '$jobid' AND Comments = '$actual_history'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);
$numrows = mysqli_num_rows($query);

if(isset($_POST['comment']) && !empty($_POST['comment']) && $numrows == 0){
    
    $technicianid = $_SESSION['kt_login_id']; echo '<<- '. $_SESSION['kt_login_id'] .' ->>';
    $date = date('Y-m-d H:i:s');
    $comments = $_POST['comment'];
    
    mysqli_query($con, "INSERT INTO tbl_actual_history (JobId,TechnicianId,Date,Comments) VALUES ('$jobid','$technicianid','$date','$comments')") or die(mysqli_error($con));
    
}

// Upload PDF Job Card
$target_path = "../jc-pdf/";

$target_path = $target_path . basename($_FILES['pdf']['name']);

if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)){
    
    $pdf = $_FILES['pdf']['name'];
    $id = $_GET['Id'];
    
    mysqli_query($con, "UPDATE tbl_jc SET JobcardPDF = '$pdf' WHERE JobId = '$id'") or die(mysqli_error($con));
}

if(isset($_POST['contractor'])){
	
	$contractorid = $_POST['contractor'];
	
	mysqli_query($con, "UPDATE tbl_jc SET ContractorId = '$contractorid' WHERE JobId = '$jobid'")or die(mysqli_error($con));
}

$target_path = "../images/history/";

$target_path = $target_path . basename($_FILES['photo']['name']);

if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)){
    
    $file_attachment = $_FILES['photo']['name'];
    $ext = explode(".", $file_attachment);
    $extension = $ext[1];
    $image = rename('../images/history/' . $file_attachment, '../images/history/' . $_GET['Id'] . '-' . date('H-i-s') . '.' . $extension);
    $image_name = $_GET['Id'] . '-' . date('H-i-s') . '.' . $extension;
    
    mysqli_query($con, "INSERT INTO tbl_history_photos (Photo) VALUES ('$image_name')") or die(mysqli_error($con));
    
    $query = mysqli_query($con, "SELECT * FROM tbl_history_photos ORDER BY Id DESC") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);
    
    $photoid = $row['Id'];
        
    mysqli_query($con, "INSERT INTO tbl_history_relation (JobId,PhotoId) VALUES ('$jobid','$photoid')") or die(mysqli_error($con));
}

if(!empty($_POST['score'])){
    
    $techid = $_POST['technician'];
    $jobno = $_POST['job'];
    $score = $_POST['score'];
    $scoredate = $_POST['score_date'];
    
    mysqli_query($con, "INSERT INTO tbl_score_relational (JobId,TechnicianId,Score,Date) VALUES ('$jobid','$techid','$score','$scoredate')") or die(mysqli_error($con));
    
}

if(isset($_GET['recordid'])){
    
    $recordid = $_GET['recordid'];
    
    mysqli_query($con, "DELETE FROM tbl_score_relational WHERE Id = '$recordid'") or die(mysqli_error($con));
    
}

if(isset($_POST['status'])){
    $status = $_POST['status'];
        mysqli_query($con, "UPDATE tbl_jc SET Status = '$status' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

if(isset($_POST['delete'])){
    $delete = $_POST['delete'];
    
    foreach($delete as $c){
        
        mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysqli_error($con));
    }
}
if(isset($_POST['delete_m'])){
    $delete = $_POST['delete_m'];
    
    foreach($delete as $c){
        
        mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysqli_error($con));
    }
}
if(isset($_POST['delete_c'])){
    $delete = $_POST['delete_c'];
    
    foreach($delete as $c){
        
        mysqli_query($con, "DELETE FROM tbl_jc WHERE Id = '$c'") or die(mysqli_error($con));
    }
}

// Delete transport rows
if(isset($_POST['delete_t'])){
    
    $delete = $_POST['delete_t'];
    
    foreach($delete as $c){
        
        mysqli_query($con, "DELETE FROM tbl_travel WHERE Id = '$c'") or die(mysqli_error($con));
    }
}

if(isset($_POST['reference'])){
    $ref = $_POST['reference'];
        mysqli_query($con, "UPDATE tbl_jc SET Reference = '$ref' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}


// Service Requested
$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

if(!empty($_POST['service']) && ($_POST['service'] != $row['JobDescription'])){
    
    $service = addslashes($_POST['service']);
    $loginid = $_SESSION['kt_login_id'];
    
    $query2 = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$loginid'") or die(mysqli_error($con));
    $row2 = mysqli_fetch_array($query2);
    
    $user = $row2['Name'];
    $date = date('Y-m-d');
    
    mysqli_query($con, "UPDATE tbl_jc SET JobDescription = '$service', JobDescriptionOperator = '$user', JobDescriptionDate = '$date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
    
}

// Date Received, Date Requested
if(isset($_POST['date1'])){
    
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
        $service = addslashes($_POST['service']);
    
    mysqli_query($con, "UPDATE tbl_jc SET Date1 = '$date1', Date2 = '$date2' WHERE JobId = '$jobid'") or die(mysqli_error($con));
    
}

// Add Labour Row

if($_POST['labour_row'] >= 1){
    
        $rows = $_POST['labour_row'];
    
    $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);
    
    $jobno = $row['JobNo'];
    $companyid = $row['CompanyId'];
    $siteid = $row['SiteId'];
    
    for($i = 0; $i < $rows; $i++){
        
        mysqli_query($con, "INSERT INTO tbl_jc (JobId,CompanyId,SiteId,JobNo,Labour,Unit,Status) VALUES ('$jobid','$companyid','$siteid','$jobno','1','hours','1')") or die(mysqli_error($con));
        
    }
}

// Add Material Row

if($_POST['material_row'] >= 1){
    
        $rows = $_POST['material_row'];
    
    $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);
    
    $jobno = $row['JobNo'];
    $companyid = $row['CompanyId'];
    $siteid = $row['SiteId'];
    
    for($i = 0; $i < $rows; $i++){
        
        mysqli_query($con, "INSERT INTO tbl_jc (JobId,CompanyId,SiteId,JobNo,Material,Status) VALUES ('$jobid','$companyid','$siteid','$jobno','1','1')") or die(mysqli_error($con));
        
    }
}

// Add Transport Row

if($_POST['transport_row'] >= 1){
    
        $rows = $_POST['transport_row'];
    
    $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);
    
    $jobno = $row['JobNo'];
    
    for($i = 0; $i < $rows; $i++){
        
        mysqli_query($con, "INSERT INTO tbl_travel (JobId,JobNo,Description,DistanceKm,KmRate,TotalKm,TravelTime,TravelTimeRate) VALUES ('$jobid','$jobno','','60','4.2','252','1','365')") or die(mysqli_error($con));
        
    }
}

// Add Comment Row

if($_POST['comment_row'] >= 1){
    
        $rows = $_POST['comment_row'];
    
    for($i = 0; $i < $rows; $i++){
        
        mysqli_query($con, "INSERT INTO tbl_jc (JobId,Comment) VALUES ('$jobid','1')") or die(mysqli_error($con));
        
    }
}

if(isset($_GET['update'])){
    
    // Update Labour
    
    $query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $numrows = mysqli_num_rows($result);
    
    $idl = $_POST['id_l'];
    $labour_l = $_POST['labour'];
    $unit_l = $_POST['unit_l'];
    $qty_l = $_POST['qty_l'];
    $qty_t = $_POST['qty_t'];
    $price_l = $_POST['price_l'];
    
    for($i = 0; $i < $numrows; $i++){
        
        $id = $idl[$i];
        $labour = $labour_l[$i];
        $unit = $unit_l[$i];
        $qty = $qty_l[$i];
        $qty_fuel = $qty_t[$i];
        $price = $price_l[$i];
        $total = $qty * $price;
        $total_fuel = $qty_fuel * $price;
        
        if($unit == 'hours'){
            
            mysqli_query($con, "UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysqli_error($con));
            
        } elseif($unit == 'km'){
            
            mysqli_query($con, "UPDATE tbl_jc SET  Description = '$labour', Unit = '$unit', Qty = '$qty_fuel', Price = '$price', Total1 = '$total_fuel' WHERE Id = '$id'") or die(mysqli_error($con));
        }
    }
    
    // Update Material
    
    $idm = $_POST['id_m'];
    $material_m = $_POST['material'];
    $unit_m = $_POST['unit_m'];
    $qty_m = $_POST['qty_m'];
    $price_m = $_POST['price_m'];
    
    for($i = 0; $i < $numrows; $i++){
        
        $id = $idm[$i];
        $material = $material_m[$i];
        $unit = $unit_m[$i];
        $qty = $qty_m[$i];
        $price = $price_m[$i];
        $total = $qty * $price;
        
        mysqli_query($con, "UPDATE tbl_jc SET  Description = '$material', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total' WHERE Id = '$id'") or die(mysqli_error($con));
    }
    
    // Update transport
    
    $query = mysqli_query($con, "SELECT * FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
    $numrows = mysqli_num_rows($query);
    
    $idt = $_POST['id_t'];
    $transport_t = $_POST['transport'];
    $unit_t = $_POST['unit_t'];
    $qty_t = $_POST['qty_t'];
    $price_t = $_POST['price_t'];
    $comment_t = $_POST['t_comment'];
    $km_t = $_POST['km'];
    $km_rate_t = $_POST['km-rate'];
    $total_km_t = $_POST['total-km'];
    $travel_time_t = $_POST['travel-time'];
    $travel_time_rate_t = $_POST['travel-time-rate'];
    
    for($i = 0; $i < $numrows; $i++){
        
        $id = $idt[$i];
        $transport = $transport_t[$i];
        $unit = $unit_t[$i];
        $qty = $qty_t[$i];
        $price = $price_t[$i];
        $comment = $comment_t[$i];
        $total = $qty * $transport * $price;
        
        $km = $km_t[$i];
        $km_rate = $km_rate_t[$i];
        $total_km = $total_km_t[$i];
        $travel_time = $travel_time_t[$i];
        $travel_time_rate = $travel_time_rate_t[$i];
        
        $total_pragma = $qty * $travel_time_rate;
        $total = $qty * $transport * $price;
        
        mysqli_query($con, "UPDATE tbl_travel SET  Description = '$transport', Unit = '$unit', Qty = '$qty', Price = '$price', Total1 = '$total', TransportComment = '$comment', DistanceKm = '$km', KmRate = '$km_rate',TotalKm = '$total_km', TravelTime = '$travel_time', TravelTimeRate = '$travel_time_rate', TotalPragma = '$total_pragma' WHERE Id = '$id'") or die(mysqli_error($con));
        
    }
    
    if(isset($_POST['id_c'])){
		
        $idc = $_POST['id_c'];
        $date_c = $_POST['history-date'];
        $name_c = $_POST['comment_name'];
        $comment_c = $_POST['comment'];
        $feedback_c = $_POST['feedback'];
        $fbtech_c = $_POST['tech'];
        $fb_date_c = $_POST['fb_date'];
        
        
        if(isset($_POST['comment1'])){
			
            for($i = 0; $i < $numrows; $i++){
				
                $id = $idc[$i];
                $date = $date_c[$i];
                $name = $name_c[$i];
                $comment = $comment_c[$i];
                $feedback = $feedback_c[$i];
                $feedback_c;
                $fbtech = $fbtech_c[$i];
                $fb_date = $fb_date_c[$i];
                
                mysqli_query($con, "UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date', CommentName = '$name', CommentText = '$comment', HistoryPlatform = '1' WHERE Id = '$id' AND Comment = '1'") or die(mysqli_error($con));
                
            }
        } else {
            mysqli_query($con, "UPDATE tbl_jc SET  Comment = '1', CommentDate = '$date_c', CommentName = '$name_c', CommentText = '$comment_c' ,HistoryPlatform = '1' WHERE JobId = '$jobid' AND Comment = '1' ORDER BY Id ASC LIMIT 1") or die(mysqli_error($con));
            
        }
    }
}

// Dealers Feedback
$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

if(!empty($_POST['feedback']) && ($_POST['feedback'] != $row['FeedBack'])){
    
    $feedback = $_POST['feedback'];
    $fb_date = $_POST['fb_date'];
    
    $loginid = $_SESSION['kt_login_id'];
    
    $query2 = mysqli_query($con, "SELECT * FROM tbl_users WHERE Id = '$loginid'") or die(mysqli_error($con));
    $row2 = mysqli_fetch_array($query2);
    
    $fbtech = $row2['Name'];
    
    mysqli_query($con, "UPDATE tbl_jc SET FeedBack = '$feedback', FeedBackTech = '$fbtech', FeedBackDate = '$fb_date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
    
}


$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$subtotal_l = $row['SUM(Total1)'];

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$subtotal_m = $row['SUM(Total1)'];

$query = mysqli_query($con, "SELECT CompanyId FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$companyid = $row['CompanyId'];

$query = mysqli_query($con, "SELECT SUM(Total1) FROM tbl_travel WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$subtotal_t = $row['SUM(Total1)'];
    
$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

mysqli_query($con, "UPDATE tbl_jc SET SubTotal = '$subtotal' WHERE JobId = '$jobid'") or die(mysqli_error($con));

$query_Recordset4 = "
	SELECT
		tbl_technicians.`Name` AS Name_1,
		tbl_actual_history.JobId,
		tbl_users.`Name`,
		tbl_actual_history.Date,
		tbl_actual_history.Comments,
		tbl_actual_history.Mobile
	FROM
		(
			(
				tbl_actual_history
				LEFT JOIN tbl_users ON tbl_users.Id = tbl_actual_history.TechnicianId
			)
			LEFT JOIN tbl_technicians ON tbl_technicians.Id = tbl_actual_history.TechnicianId
		)
	WHERE
		tbl_actual_history.JobId = '$jobid'
	ORDER BY
		tbl_actual_history.Id ASC";
		
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

$Recordset2 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];


$query_Recordset5 = "
	SELECT
		tbl_sites.Name AS Name_1,
		tbl_sites.Id AS SiteId,
		tbl_companies.Id AS Id_1,
		tbl_jc.RequestPreWorkPo,
		tbl_companies.Name,
		tbl_sites.Company,
		tbl_sites.Site,
		tbl_sites.Address,
		tbl_sites.FirstName,
		tbl_sites.LastName,
		tbl_sites.Telephone,
		tbl_sites.Email,
		tbl_jc.Id,
		tbl_jc.AreaId,
		tbl_jc.JobId,
		tbl_jc.ContractorId,
		tbl_jc.FeedBackTech,
		tbl_jc.FeedBack,
		tbl_jc.FeedBackDate,
		tbl_jc.JobNo,
		tbl_jc.Date,
		tbl_jc.Status,
		tbl_jc.JobDescription,
		tbl_jc.Progress,
		tbl_jc.Reference,
		tbl_jc.InvoiceNo,
		tbl_jc.QuoteNo
	FROM
		(
			(
				tbl_jc
				LEFT JOIN tbl_sites ON tbl_sites.Id = tbl_jc.SiteId
			)
			LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		)
	WHERE
		tbl_jc.JobId = '$jobid'
	ORDER BY
		Id ASC
	LIMIT 1";
$Recordset5 = mysqli_query($con, $query_Recordset5) or die(mysqli_error($con));
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);


if(isset($_POST['import'])){
    
        $quoteno = $_POST['quote'];
    
    quote_import($jobid, $quoteno);
    
}

if(isset($_POST['risk'])){
    
    $jobno = $row_Recordset5['JobNo'];
    
    $query = mysqli_query($con, "SELECT * FROM tbl_far WHERE JobNo = '$jobno'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);
    $numrows = mysqli_num_rows($query);
    
    mysqli_query($con, "UPDATE tbl_far SET RiskType = '$risk', RiskClassification = '$type' WHERE JobNo = '$jobno'") or die(mysqli_error($con));
    
}



$sql = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$hint = mysqli_fetch_array($sql);

$cfb = $_POST['cfb'];

if(!empty($_POST['costing-hint'])){
    
    $costinghint = $_POST['costing-hint'];
    
} else {
    
    $costinghint = $hint['CostingHint'];
}

// UPDATE CUSTOMER STATUS REPORTS

if(isset($_POST['Submit3'])){
    
    $progress = $_POST['progress'];
    
    mysqli_query($con, "UPDATE tbl_jc SET CustomerFeedBack = '$cfb', CostingHint = '$costinghint', Progress = '$progress' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}

$query_Recordset3 = "
	SELECT
		tbl_jc.CompanyId,
		tbl_jc.JobId,
		tbl_companies.*
	FROM
		(
			tbl_jc
			LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_jc.CompanyId
		)
	WHERE
		tbl_jc.JobId = '$jobid'
	ORDER BY
		Id ASC
	LIMIT 1";
	
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$Recordset6 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' ORDER BY Id ASC") or die(mysqli_error($con));
$row_Recordset6 = mysqli_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysqli_num_rows($Recordset6);

$query_Recordset7 = "SELECT * FROM tbl_rates";
$Recordset7 = mysqli_query($con, $query_Recordset7) or die(mysqli_error($con));
$row_Recordset7 = mysqli_fetch_assoc($Recordset7);
$totalRows_Recordset7 = mysqli_num_rows($Recordset7);

$query_Recordset8 = "
	SELECT
		tbl_jc.CompanyId,
		tbl_jc.JobId,
		tbl_jc.Price,
		tbl_jc.Transport
	FROM
		tbl_jc
	WHERE
		tbl_jc.JobId = '$jobid'
	AND tbl_jc.Transport = 1";
	
$Recordset8 = mysqli_query($con, $query_Recordset8) or die(mysqli_error($con));
$row_Recordset8 = mysqli_fetch_assoc($Recordset8);
$totalRows_Recordset8 = mysqli_num_rows($Recordset8);

$areaid = $row_Recordset5['AreaId'];

$query_tech = mysqli_query($con, "SELECT * FROM tbl_technicians") or die(mysqli_error($con));
$totalRows_query_tech = mysqli_num_rows($query_tech);

$Recordset100 = mysqli_query($con, "SELECT * FROM tbl_technicians") or die(mysqli_error($con));
$totalRows_Recordset100 = mysqli_num_rows($Recordset100);

$companyid = $row_Recordset5['Id_1'];

$query_Recordset101 = "
	SELECT
		tbl_rates. NAME AS Name_1,
		tbl_companies. NAME,
		tbl_rates.Rate,
		tbl_rates.CompanyId
	FROM
		(
			tbl_companies
			LEFT JOIN tbl_rates ON tbl_rates.CompanyId = tbl_companies.Id
		)
	WHERE
		tbl_rates.CompanyId = '$companyid'";
	
$Recordset101 = mysqli_query($con, $query_Recordset101) or die(mysqli_error($con));
$row_Recordset101 = mysqli_fetch_assoc($Recordset101);
$totalRows_Recordset101 = mysqli_num_rows($Recordset101);

$Recordset9 = mysqli_query($con, "SELECT * FROM tbl_status WHERE Id <= 5") or die(mysqli_error($con));
$row_Recordset9 = mysqli_fetch_assoc($Recordset9);
$totalRows_Recordset9 = mysqli_num_rows($Recordset9);

$query = mysqli_query($con, "SELECT JobNo FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$jobno = $row['JobNo'];

$query_41 = "
	SELECT
		tbl_far_risc_classification.Risk,
		tbl_far_high_risk_classification.Risk AS Risk2,
		tbl_far.JobNo,
		tbl_far.Id
	FROM
		tbl_far_risc_classification
	INNER JOIN tbl_far ON tbl_far.RiskType = tbl_far_risc_classification.Id
	INNER JOIN tbl_far_high_risk_classification ON tbl_far.RiskClassification = tbl_far_high_risk_classification.Id
	WHERE
		tbl_far.JobNo = '$jobno' ";

$Recordset41 = mysqli_query($con, $query_41) or die(mysqli_error($con));
$row_Recordset41 = mysqli_fetch_assoc($Recordset41);
$totalRows_Recordset41 = mysqli_num_rows($Recordset41);

$Recordset42 = mysqli_query($con, "SELECT * FROM tbl_far_high_risk_classification ORDER BY Risk ASC") or die(mysqli_error($con));
$row_Recordset42 = mysqli_fetch_assoc($Recordset42);
$totalRows_Recordset42 = mysqli_num_rows($Recordset42);

$jobno = $row_Recordset5['JobNo'];

$Recordset43 = mysqli_query($con, "SELECT * FROM tbl_far WHERE JobNo = '$jobno'") or die(mysqli_error($con));
$row_Recordset43 = mysqli_fetch_assoc($Recordset43);
$totalRows_Recordset43 = mysqli_num_rows($Recordset43);

$query_Recordset44 = "
	SELECT
		tbl_score_relational.Id,
		tbl_score_relational.JobId,
		tbl_technicians. NAME,
		tbl_score_relational.Score,
		tbl_jc.JobNo
	FROM
		(
			(
				tbl_score_relational
				LEFT JOIN tbl_technicians ON tbl_technicians.Id = tbl_score_relational.TechnicianId
			)
			LEFT JOIN tbl_jc ON tbl_jc.JobId = tbl_score_relational.JobId
		)
	WHERE
		tbl_score_relational.JobId = '$jobid'
	GROUP BY
		tbl_technicians.Id";
		
$Recordset44 = mysqli_query($con, $query_Recordset44) or die(mysqli_error($con));
$row_Recordset44 = mysqli_fetch_assoc($Recordset44);
$totalRows_Recordset44 = mysqli_num_rows($Recordset44);

$Recordset45 = mysqli_query($con, "SELECT * FROM tbl_technicians ORDER BY Name ASC") or die(mysqli_error($con));
$row_Recordset45 = mysqli_fetch_assoc($Recordset45);
$totalRows_Recordset45 = mysqli_num_rows($Recordset45);

$rs_companies = mysqli_query($con, "SELECT * FROM tbl_companies ORDER BY Name ASC") or die(mysqli_error($con));
$row_rs_companies = mysqli_fetch_assoc($rs_companies);
$totalRows_rs_companies = mysqli_num_rows($rs_companies);

$rs_sites = mysqli_query($con, "SELECT * FROM tbl_sites ORDER BY Name ASC") or die(mysqli_error($con));
$row_rs_sites = mysqli_fetch_assoc($rs_sites);
$totalRows_rs_sites = mysqli_num_rows($rs_sites);

$query_job_history = "
	SELECT
		tbl_history_relation.PhotoId,
		tbl_history_photos.Photo,
		tbl_history_relation.JobId
	FROM
		(
			tbl_history_relation
			LEFT JOIN tbl_history_photos ON tbl_history_photos.Id = tbl_history_relation.PhotoId
		)
	WHERE
		tbl_history_relation.JobId = '$jobid'";
		
$job_history = mysqli_query($con, $query_job_history) or die(mysqli_error($con));
$row_job_history = mysqli_fetch_assoc($job_history);
$totalRows_job_history = mysqli_num_rows($job_history);

$progress = mysqli_query($con, "SELECT Progress FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row_progress = mysqli_fetch_assoc($progress);
$totalRows_progress = mysqli_num_rows($progress);

$companyid = $row_Recordset5['Id_1'];

$rs_transport_rates = mysqli_query($con, "SELECT * FROM tbl_rates WHERE CompanyId = '$companyid' AND Description LIKE '%Travel%' ORDER BY Name ASC") or die(mysqli_error($con));
$row_rs_transport_rates = mysqli_fetch_assoc($rs_transport_rates);
$totalRows_rs_transport_rates = mysqli_num_rows($rs_transport_rates);

$rs_components = mysqli_query($con, "SELECT * FROM tbl_pragma_components ORDER BY `Description` ASC") or die(mysqli_error($con));
$row_rs_components = mysqli_fetch_assoc($rs_components);
$totalRows_rs_components = mysqli_num_rows($rs_components);

$rs_failure = mysqli_query($con, "SELECT CompDescription, Code2, Description2 FROM tbl_pragma_failures ORDER BY Description2 ASC") or die(mysqli_error($con));
$row_rs_failure = mysqli_fetch_assoc($rs_failure);
$totalRows_rs_failure = mysqli_num_rows($rs_failure);

$rs_root_cause = mysqli_query($con, "SELECT * FROM tbl_pragma_root_cause ORDER BY `Description` ASC") or die(mysqli_error($con));
$row_rs_root_cause = mysqli_fetch_assoc($rs_root_cause);
$totalRows_rs_root_cause = mysqli_num_rows($rs_root_cause);

$rs_repair = mysqli_query($con, "SELECT * FROM tbl_pragma_repair ORDER BY `Description` ASC") or die(mysqli_error($con));
$row_rs_repair = mysqli_fetch_assoc($rs_repair);
$totalRows_rs_repair = mysqli_num_rows($rs_repair);

$rs_pragma = mysqli_query($con, "SELECT * FROM tbl_pragma WHERE JobId = '$jobid'") or die(mysqli_error($con));
$row_rs_pragma = mysqli_fetch_assoc($rs_pragma);
$totalRows_rs_pragma = mysqli_num_rows($rs_pragma);

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers ORDER BY Name ASC") or die(mysqli_error($con));
$query_contractor = mysqli_query($con, "SELECT * FROM tbl_users WHERE Contractor = '1'")or die(mysqli_error($con));

if(isset($_POST['con-complete']) || $_POST['complete'] == 'awaiting-pwk'){
	
	mysqli_query($con, "UPDATE tbl_jc SET Status = '20', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
	
	header('Location: in-progress.php');
	
	exit();
}

if($_POST['complete'] == 'awaiting-post-po'){
	
	mysqli_query($con, "UPDATE tbl_jc SET Status = '17', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
	
	header('Location: in-progress.php');
	
	exit();
}
	
if(isset($_POST['complete'])){
	
    if($_POST['complete'] == 1){
                
        mysqli_query($con, "UPDATE tbl_jc SET Status = '2', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
        
                
        $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'") or die(mysqli_error($con));
        $row = mysqli_fetch_array($query);
        
        $jobno = $row['JobNo'];
        $date = date('Y-m-d');
        $technician = $row['FeedBackTech'];
        
        $jobno = $row_Recordset5['JobNo'];
        $site = $row_Recordset5['SiteId'];
        $required = $row_Recordset5['JobDescription'];
        
        mysqli_query($con, "INSERT INTO tbl_feedback (Reference,Status) VALUES ('$jobno','2')") or die(mysqli_error($con));
        
        $query40 = mysqli_query($con, "SELECT * FROM tbl_jc_mobile WHERE SiteId = '$site'") or die(mysqli_error($con));
        $numrows40 = mysqli_num_rows($query40);
        while($row40 = mysqli_fetch_array($query40)){
            
            if($numrows40 >= 1){
                
                if(empty($_POST['service'])){
                    
                                        header('Location: jc-calc.php?Id=' . $jobid . '&service');
                    
                } else {
                    
                    //$cell = $row40['Mobile'];
                    //
                    //$to  = 'sms@messaging.clickatell.com'; 
                    //$subject = 'Seavest';
                    //$from = "control@seavest.co.za";
                    //
                    //$message = '
                    //user:seavest
                    //password:abc123
                    //api_id:3232946
                    //to:'. $cell .'
                    //reply: control@seavest.co.za
                    //concat: 3
                    //text:Your request: "'. $required .'" has been received by Seavest. Use ref. No. '. $jobno .' and log onto www.seavest.co.za/progress for updates.';
                    //
                    //$headers  = 'MIME-Version: 1.0' . "\r\n";
                    //$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
                    //
                    //$headers .= 'FROM: '. $from . "\r\n";
                    //
                    //mail($to, $subject, $message, $headers);
                }
            }
            
            header('Location: ../jc_complete.php?Id=' . $jobno);
            
        }
    }
    
    // Send to costing
    
    elseif($_POST['complete'] == "2" || $_POST['complete'] == "request-pre-po"){
                
        unset($_SESSION['services']);
        unset($_SESSION['feedback']);
        unset($_SESSION['reference']);
        
        if(empty($_POST['service']) || empty($_POST['feedback']) || empty($_POST['reference'])){
            
            if(empty($_POST['service'])){
                
                $_SESSION['services'] = "&service";
                
            }
            
            if(empty($_POST['feedback'])){
                
                $_SESSION['feedback'] = "&feedback";
            }
            
            if(empty($_POST['reference'])){
                
                $_SESSION['reference'] = "&reference";
            }
            
            header('Location: jc-calc.php?Id=' . $jobid . $_SESSION['services'] . $_SESSION['feedback'] . $_SESSION['reference']);
            
        } else {
            
            if($row_Recordset5['Id_1'] == 6 && $row_Recordset5['RequestPreWorkPo'] == 0){
                
                if($_POST['complete'] == "request-pre-po"){
                    
                    mysqli_query($con, "UPDATE tbl_jc SET RequestPreWorkPo = '1', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
                }
                
                // Send to Awaiting Order No.
                $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '18', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
                
            } else {
                
                // Send ro Costing
                $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '3', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
                
            }
            
            $jobno = $row_Recordset5['JobNo'];
            
            mysqli_query($con, "INSERT INTO tbl_feedback (Reference,Status) VALUES ('$jobno','3')") or die(mysqli_error($con));
            
            $query3 = mysqli_query($con, "SELECT * FROM tbl_sms WHERE Id = '2'") or die(mysqli_error($con));
            $row3 = mysqli_fetch_array($query3);
            
            $qued = $row3['SMS'];
            
            $query4 = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysqli_error($con));
            $row4 = mysqli_fetch_array($query4);
            
            $site = $row4['SiteId'];
            $jobnumber = $row4['JobNo'];
            $required = $row4['JobDescription'];
            
            $query5 = mysqli_query($con, "SELECT * FROM tbl_jc_mobile WHERE SiteId = '$site'") or die(mysqli_error($con));
            $numrows5 = mysqli_num_rows($query5);
            while($row5 = mysqli_fetch_array($query5)){
                
                if($numrows5 >= 1){
                    
                    //				$cell = $row5['Mobile'];
                    //				$to  = 'sms@messaging.clickatell.com'; 
                    //				$subject = 'Seavest';
                    //				$from = "control@seavest.co.za";
                    //				
                    //				$message = '
                    //				user:seavest
                    //				password:abc123
                    //				api_id:3232946
                    //				to:'. $cell .'
                    //				reply: control@seavest.co.za
                    //				concat: 3
                    //				text:Ref. No. '. $jobnumber .': '. $required .' has successfully been completed. Thank you for your support.';
                    //				
                    //				$headers  = 'MIME-Version: 1.0' . "\r\n";
                    //				$headers .= 'Content-type: text/pain; charset=iso-8859-1' . "\r\n";
                    //				$headers .= 'FROM: '. $from . "\r\n";
                    //				
                    //				mail($to, $subject, $message, $headers);
                }
            }
            
                        
            mysqli_query($con, "DELETE FROM tbl_history_alerts WHERE JobId = '$jobid'") or die(mysqli_error($con));
            
            header('Location: in-progress.php?Id=' . $_GET['Id']);
        }
        
    } elseif($_POST['complete'] == "3"){
        
        $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '7', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
        
        header('Location: in-progress.php');
        
    } elseif($_POST['complete'] == "5"){
        
        $query = mysqli_query($con, "UPDATE tbl_jc SET Status = '7', Days = '$today' WHERE JobId = '$jobid'") or die(mysqli_error($con));
        
        header('Location: in-progress.php');
    }
        
}

$invoiceno = $row_Recordset5['InvoiceNo'];

if(($invoiceno == 0) && ($row_Recordset5['Status'] != 1)){
    
    $invoiceno = invno($con);
    $inv_date = date('Y-m-d');
    mysqli_query($con, "UPDATE tbl_jc SET InvoiceNo = '$invoiceno', NewInvoiceDate = '$inv_date' WHERE JobId = '$jobid'") or die(mysqli_error($con));
}


if($_POST['complete'] == 6){ ?>

    <script>
      window.open("far-print.php?Id=<?php echo $row_Recordset5['JobNo']; ?>");
    </script>
    
<?php } ?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SEAVEST AFRICA TRADING CC</title>
  <link href="../styles/layout.css" rel="stylesheet" type="text/css" />
  <link href="../styles/fonts.css" rel="stylesheet" type="text/css">
  
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script>!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');</script>
  <script type="text/javascript" src="../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
  <script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
  <link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../jquery-ui-timepicker-addon.css" />
  
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script type="text/javascript" src="../jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="../jquery-ui-sliderAccess.js"></script>
  
  <script type="text/javascript" src="../highslide/highslide-with-html.js"></script>
  <script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />
  
  <script type="text/javascript" src="../highslide/highslide.js"></script>
  <script type="text/javascript">
  <!--
  hs.graphicsDir = '../highslide/graphics/';
      hs.outlineType = 'rounded-white';
  
  function MM_openBrWindow(theURL,winName,features) { //v2.0
    window.open(theURL,winName,features);
  }
  
  function MM_popupMsg(msg) { //v1.0
    alert(msg);
  }
  //-->
  </script>
    
  <script type="text/javascript">
  function validateForm()
  {
  var x=document.forms["form1"]["tech"].value;
  if (x==null || x=="")
    {
    alert("Please select a technician under Actual History");
    return false;
    }
  }
  </script>
  <link href="../SpryAssets/SpryCollapsiblePanel-jc.css" rel="stylesheet" type="text/css">
  
  <style type="text/css">
  <!--
  .big1 {    font-size:14px;
  }
  
  .menuHeader{
    font-weight:bold;
    font-size: 8pt;
  }
  
  #fancybox-content{
	  width:700px !important;
	  height:600px !important;
	  background-color:#FFF;
  }
  
  #fancybox-wrap{
	  width:700px !important;
  }
  -->
  </style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
if(!isset($_COOKIE['status'])){
include('../menu.php'); 
} else {
include('../status_menu.php'); 
}
?>
    </td>
    <td valign="top"><table width="761" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" alt="" width="823" height="151"></td>
      </tr>
    </table>
      </form>
      <div style="margin-left:30px">
        <table width="759" border="0" cellpadding="0" cellspacing="0" class="td-header">
          <tr>
            <td><form name="form2" method="post" action="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
              &nbsp;Labour
              <select name="labour_row" class="tarea-white" id="labour_row">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                </select>
              &nbsp; Materials
              <select name="material_row" class="tarea-white" id="material_row">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                </select>
              &nbsp; Transport
              <select name="transport_row" class="tarea-white" id="transport_row">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                </select>
              &nbsp; Comments
              <select name="comment_row" class="tarea-white" id="comment_row">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                </select>
              <input name="Submit2" type="submit" class="btn-go-search-2" value="">
            </form></td>
            <td width="25"><form name="form4" method="post" action="../jc-recycle.php?Id=<?php echo $_GET['Id']; ?>">
              <input name="Submit4" type="submit" class="camcel" value="">
            </form></td>
            <td align="right" valign="middle"><form name="form3" method="post" action="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $_GET['Id']; ?>">
              Status
              <select name="status" class="tarea-white" id="status">
                <option value="">Select one...</option>
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset9['Id']?>"><?php echo $row_Recordset9['Status']?></option>
                <?php
} while ($row_Recordset9 = mysqli_fetch_assoc($Recordset9));
  $rows = mysqli_num_rows($Recordset9);
  if($rows > 0) {
      mysqli_data_seek($Recordset9, 0);
	  $row_Recordset9 = mysqli_fetch_assoc($Recordset9);
  }
?>
                </select>
              <input name="Submit" type="submit" class="btn-go-search-2" value="">
              &nbsp;
            </form></td>
          </tr>
        </table>
      </div>
      <form action="jc-calc.php?menu=<?php echo $_GET['menu']; ?>&Id=<?php echo $jobid; ?>&update" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="return validateForm();" style="margin-left:27px">
        <table width="759">
          <tr>
            <td colspan="3" valign="top" class="combo"><div style="border:solid 1px #A6CAF0; width:759px; background-color:#EEE"">
              <table width="100%" border="0" cellpadding="3" cellspacing="0">
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td valign="top"><span class="combo"><span style="padding-right:10px"><br>
                        <table border="0" cellpadding="2" cellspacing="3" class="combo">
                          <tr>
                            <td><span class="combo_bold" style="padding-right:10px"><?php echo $row_Recordset5['Name']; ?></span></td>
                            <td width="65">&nbsp;</td>
                            <td><?php echo $row_Recordset5['FirstName']; ?>&nbsp; <?php echo $row_Recordset5['LastName']; ?></td>
                          </tr>
                          <tr>
                            <td><span style="padding-right:10px"><?php echo $row_Recordset5['Name_1']; ?></span></td>
                            <td width="65">&nbsp;</td>
                            <td><?php echo $row_Recordset5['Telephone']; ?></td>
                          </tr>
                          <tr>
                            <td><span style="padding-right:10px"><?php echo $row_Recordset5['Address']; ?></span></td>
                            <td width="65">&nbsp;</td>
                            <td><a href="mailto:<?php echo $row_Recordset5['Email']; ?>"><?php echo $row_Recordset5['Email']; ?></a></td>
                          </tr>
                        </table>
                        </span></span> <span class="combo"><span style="padding-right:10px"><br>
                          </span><br>
                        </span></td>
                      <td align="right" valign="top" class="combo"><table border="0" align="right" cellpadding="2" cellspacing="3" class="combo">
                        <tr>
                          <td class="combo_bold"> Jobcard No:&nbsp;</td>
                          <td><input name="jobno" type="text" class="tarea-new-100" id="jobno" value="<?php echo $row_Recordset5['JobNo']; ?>" size="30"></td>
                        </tr>
                        <?php if(($row_Recordset5['Status'] == 3) || ($row_Recordset5['Status'] == 4)){ ?>
                        <tr>
                          <td><strong>Quote No</strong>: </td>
                          <?php
						  if($row_Recordset5['QuoteNo'] > 0){
							  
							  $value = $row_Recordset5['QuoteNo'];
							  
						  } else {
							  
							  $value = $_POST['quote'];
						  }
						  ?>
                          <td><input name="quote" type="text" class="tarea" id="quote" value="<?php echo $value; ?>" size="18">
                            <input name="import" type="submit" class="tarea2" id="import" value="Import"></td>
                        </tr>
                        <?php } ?>
                        <?php if($row_Recordset5['Status'] == 3){ ?>
                        <tr>
                          <td><span class="combo_bold">Invoice No:</span></td>
                          <td><input name="textfield" type="text" class="tarea-new-100" value="<?php echo $row_Recordset5['InvoiceNo']; ?>" size="30"></td>
                        </tr>
                        <?php } ?>
                        <tr>
                          <td><div style="padding-right:10px"> <span class="combo_bold">Reference:</span></div></td>
                          <td><span style="padding-right:10px">
                            <select name="reference" class="tarea-new-100" id="reference">
                              <option value="">Select an Engineer</option>
                              <?php while($row_engineers = mysqli_fetch_array($query_engineers)){ ?>
								  <option value="<?php echo trim($row_engineers['Name']); ?>" <?php if($row_engineers['Name'] == $row_Recordset5['Reference']){ echo 'selected="selected"'; } ?>><?php echo $row_engineers['Name']; ?>
                              <?php } ?>
                          </select>
                          <?php if(isset($_GET['reference'])){ ?>
                            <span class="form_validation_field_error_error_message">Required Field</span>
					      <?php } ?>
                          </span></td>
                        </tr>
                        <tr>
                          <td nowrap><strong>Job Card PDF</strong></td>
                          <td><input name="pdf" type="file" class="tarea-new-100" id="pdf" size="12"></td>
                        </tr>
                        <tr>
                          <td nowrap><strong>Contractor</strong></td>
                          <td>
                            <select name="contractor" class="tarea-new-100" id="contractor">
                              <option value="0">None selected...</option>
                              <?php while($row_contractor = mysqli_fetch_array($query_contractor)){ ?>
                              <option value="<?php echo $row_contractor['Id']; ?>" <?php if($row_Recordset5['ContractorId'] == $row_contractor['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_contractor['Name']; ?></option>
                              <?php } ?>
                            </select>
                          </td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td width="60%" class="combo_bold">&nbsp;</td>
                      <td width="40%" align="right" class="combo_bold"></td>
                    </tr>
                    <tr>
                    <?php
					if($_SESSION['sla'] == 1){
						
						$sla = '';
						
					} else {
						
						$sla = 'disabled';
					}
					?>
                      <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                      <td rowspan="2">
                      
                        <div id="clockdiv">
                          <div style="display:none">
                            <span class="days">0</span>
                            <div class="smalltext">Days</div>
                          </div>
                          <div>
                            <span class="hours">0</span>
                            <div class="smalltext">Hours</div>
                          </div>
                          <div>
                            <span class="minutes">0</span>
                            <div class="smalltext">Min</div>
                          </div>
                          <div>
                            <span class="seconds">0</span>
                            <div class="smalltext">Sec</div>
                          </div>
                        </div>
                        
                        <?php if($row_Recordset6['Date2'] > date('Y-m-d H:i:s')){ ?>
							
							<?php
							
							date_default_timezone_set("Africa/Johannesburg");
							$dt2 = strtotime($row_Recordset6['Date2']);
							$dt1 = strtotime($row_Recordset6['Date1']);
							
							$hrs = work_hours_diff(strtotime(date('Y-m-d H:i:s')) , $dt2 );
							$end = convertTime($hrs);
							
							$pieces = explode(':', $end);
							
							$date = date('Y-m-d H:i:s', strtotime('+'. $pieces[0] .' hours '. $pieces[1] .' minutes '. $pieces[2] .' seconds'));
							
							?>
							<script src="//assets.codepen.io/assets/common/stopExecutionOnTimeout-f961f59a28ef4fd551736b43f94620b5.js"></script>
                            
                                
                            <script>
                                  function getTimeRemaining(endtime){
                              var t = Date.parse(endtime) - Date.parse(new Date());
                              var seconds = Math.floor( (t/1000) % 60 );
                              var minutes = Math.floor( (t/1000/60) % 60 );
                              var hours = Math.floor( (t/(1000*60*60)) % 24 );
                              var days = Math.floor( t/(1000*60*60*24) );
                              return {
                                'total': t,
                                'days': days,
                                'hours': hours,
                                'minutes': minutes,
                                'seconds': seconds
                              };
                            }
                            
                            function initializeClock(id, endtime){
                              var clock = document.getElementById(id);
                              var daysSpan = clock.querySelector('.days');
                              var hoursSpan = clock.querySelector('.hours');
                              var minutesSpan = clock.querySelector('.minutes');
                              var secondsSpan = clock.querySelector('.seconds');
                            
                              function updateClock(){
                                var t = getTimeRemaining(endtime);
                            
                                daysSpan.innerHTML = t.days;
                                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
                            
                                if(t.total<=0){
                                  clearInterval(timeinterval);
                                }
                              }
                            
                              updateClock();
                              var timeinterval = setInterval(updateClock,1000);
                            }
                            
                            var deadline = '<?php echo $date; ?>';
                            initializeClock('clockdiv', deadline);
                                  //@ sourceURL=pen.js
                                </script>
                            
                                
                            <script>
                              if (document.location.search.match(/type=embed/gi)) {
                                window.parent.postMessage("resize", "*");
                              }
                            </script>
                        <?php } ?>
                        
                      </td>
                      <td align="right">
                        <span class="combo_bold">&nbsp; Received Date</span>
                        <input name="date1" <?php echo $sla; ?> class="tarea" id="date1" value="<?php echo $row_Recordset6['Date1']; ?>">
                        
						<script type="text/javascript">
                        $('#date1').datepicker({
                        dateFormat: "yy-mm-dd"
                        });
                        </script>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <span class="combo_bold">Requested Completion:</span>&nbsp;
                        <input name="date2" <?php echo $sla; ?> class="tarea" id="date2" value="<?php echo $row_Recordset6['Date2']; ?>">
                        
						<script type="text/javascript">
                        $('#date2').datepicker({
                        dateFormat: "yy-mm-dd"
                        });
                        </script>
                      </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                </tr>
              </table>
            </div></td>
          </tr>
          <?php if($row_Recordset5['Id_1'] == 2){ ?>
          <tr>
            <td class="combo">
            <table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header">&nbsp; Invoice Details</td>
              </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td class="combo"><div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
              <table border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td>
                    <select name="component" class="combo" id="component" style="width:500px">
                      <option value="" class="menuHeader" <?php if (!(strcmp("", $row_rs_pragma['Component']))) {echo "selected=\"selected\"";} ?>>Component</option>
                      <?php
do {  
?>
<option value="<?php echo $row_rs_components['Code']?>"<?php if (!(strcmp($row_rs_components['Code'], $row_rs_pragma['Component']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_components['Description']?></option>
                      <?php
} while ($row_rs_components = mysqli_fetch_assoc($rs_components));
  $rows = mysqli_num_rows($rs_components);
  if($rows > 0) {
      mysqli_data_seek($rs_components, 0);
	  $row_rs_components = mysqli_fetch_assoc($rs_components);
  }
?>
                      </select>
                    <select name="failure-type" class="combo" id="failure-type" style="width:227px">
                      <option value="" selected style="font-weight:bold" <?php if (!(strcmp("", $row_rs_pragma['FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Failure Type</option>
                      <option value="GEN" <?php if (!(strcmp("GEN", $row_rs_pragma['FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Generic Type</option>
                      <option value="VER" <?php if (!(strcmp("VER", $row_rs_pragma['FailureTypeCode']))) {echo "selected=\"selected\"";} ?>>Verification</option>
                    </select></td>
                  </tr>
                <tr>
                  <td><select name="failure" class="combo" id="failure" style="width:730px">
                    <option value="">Failure</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_failure['Code2']; ?>" <?php if (!(strcmp($row_rs_failure['Code2'], $row_rs_pragma['Failure']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_failure['Description2'] .' - '. $row_rs_failure['CompDescription']; ?></option>
                    <?php
} while ($row_rs_failure = mysqli_fetch_assoc($rs_failure));
  $rows = mysqli_num_rows($rs_failure);
  if($rows > 0) {
      mysqli_data_seek($rs_failure, 0);
	  $row_rs_failure = mysqli_fetch_assoc($rs_failure);
  }
?>
                  </select></td>
                  </tr>
                <tr>
                  <td>
                    <select name="root-cause" class="combo" id="root-cause" style="width:248px">
                      <option value="" <?php if (!(strcmp("", $row_rs_pragma['RootCause']))) {echo "selected=\"selected\"";} ?>>Root Cause</option>
                      <?php
do {  
?>
<option value="<?php echo $row_rs_root_cause['Code']?>"<?php if (!(strcmp($row_rs_root_cause['Code'], $row_rs_pragma['RootCause']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_root_cause['Description']?></option>
                      <?php
} while ($row_rs_root_cause = mysqli_fetch_assoc($rs_root_cause));
  $rows = mysqli_num_rows($rs_root_cause);
  if($rows > 0) {
      mysqli_data_seek($rs_root_cause, 0);
	  $row_rs_root_cause = mysqli_fetch_assoc($rs_root_cause);
  }
?>
                      </select>
                    <select name="repair" id="repair" class="combo" style="width:248px">
                      <option value="" <?php if (!(strcmp("", $row_rs_pragma['Repair']))) {echo "selected=\"selected\"";} ?>>Repair</option>
                      <?php
do {  
?>
<option value="<?php echo $row_rs_repair['Code']?>"<?php if (!(strcmp($row_rs_repair['Code'], $row_rs_pragma['Repair']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_repair['Description']?></option>
                      <?php
} while ($row_rs_repair = mysqli_fetch_assoc($rs_repair));
  $rows = mysqli_num_rows($rs_repair);
  if($rows > 0) {
      mysqli_data_seek($rs_repair, 0);
	  $row_rs_repair = mysqli_fetch_assoc($rs_repair);
  }
?>
                    </select>                    
                    <input name="ptw-number" type="text" class="combo" id="ptw-number" style="width:227px" onFocus="if(this.value=='PTW Number'){this.value=''}" onBlur="if(this.value==''){this.value='PTW Number'}" value="<?php if(!empty($row_rs_pragma['PTWNumber'])){ echo $row_rs_pragma['PTWNumber']; } else { ?>PTW Number<?php } ?>"></td>
                  </tr>
                <tr>
                  <td><table border="0" cellpadding="0" cellspacing="0" class="combo">
                    <tr>
                      <td width="152">Follow up work required</td>
                      <td width="163"><table border="0" cellpadding="2" cellspacing="3" class="combo">
                        <tr>
                          <td width="20"><input type="radio" <?php if($row_rs_pragma['FollowUpWork'] == "Yes") {echo "checked=\"checked\"";} ?> name="followup" id="followup" value="Yes"></td>
                          <td width="21">Yes</td>
                          <td width="20"><input name="followup" <?php if ($row_rs_pragma['FollowUpWork'] == "No") {echo "checked=\"checked\"";} ?> type="radio" id="followup" value="No"></td>
                          <td width="37">No</td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
            </div></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="3" class="combo">
            <table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header">&nbsp; Service Requested</td>
              </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><div style="border:solid 1px #A6CAF0; padding:5px; background-color:#EEE">
            <div id="history-log">
        <span style="color:#306294; background-image:url(../images/icons/history-bg.jpg); height:19px; border:solid 1px #85afd7; font-size:8px"><b>
        
		<?php 
		
		echo $row_Recordset6['JobDescriptionOperator'];
		
		?></b>&nbsp;<span style="font-size:8px; color:#4383C2"><?php echo $row_Recordset6['JobDescriptionDate']; ?></span>
        </span>
        &nbsp;<?php echo nl2br($row_Recordset6['JobDescription']); ?>
                  </div>
            <textarea name="service" rows="4" class="tarea-tech" id="service"><?php echo stripslashes($row_Recordset6['JobDescription']); ?></textarea>
            </div>
              <?php if(isset($_GET['service'])){ ?>
              <span class="form_validation_field_error_error_message">Required Field</span>
              <?php } ?></td>
          </tr>
          <?php if($_COOKIE['contractor'] == 0 && isset($_COOKIE['contractor'])){ ?>
          <tr>
            <td colspan="3" class="td-header"><table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><table width="195" border="0" cellpadding="0" cellspacing="0" class="td-header">
                  <tr>
                    <td width="84"><a title="<?php echo $row_Recordset6['CostingHint']; ?>">&nbsp;Costing Hint</a></td>
                    <td width="20">
                    <a href="jc-calc.php?Id=<?php echo $_GET['Id']; ?>&costing">
                      <img src="../images/icons/btn-edit-small.png" alt="" width="15" height="15" border="0">
                    </a>
                    </td>
                    <td width="111">
                    <?php if(!empty($row_Recordset5['QuoteNo'])){ ?> 
                      <a href="costing-hint.php?Quote=<?php echo $row_Recordset5['QuoteNo']; ?>" class="icon-info various3"></a>
                    <?php } ?>
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
              <textarea name="costing-hint" rows="4" class="tarea-tech" id="costing-hint" ><?php 
			  if(isset($_GET['costing'])){
			  echo $row_Recordset6['CostingHint']; 
			  }
			  ?>
      </textarea>
            </div></td>
          </tr>
          <tr>
            <td bgcolor="#A6CAF0" class="td-header" style="background-color:<?php echo $row_Recordset41['Colour']; ?>">&nbsp;Proactive  Risk  Assessement</td>
          </tr>
          <tr>
            <td class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
              <table border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td width="108">&nbsp;Risk Classification</td>
                  <td width="150"><?php echo $row_Recordset41['Risk']; ?>
                  </td>
                  <td width="183">&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; High risk classification</td>
                  <td width="152"><?php echo $row_Recordset41['Risk2']; ?></td>
                </tr>
              </table>
            </div></td>
          </tr>
          <tr>
            <td bgcolor="#A6CAF0" class="td-header">&nbsp;Technician Score </td>
          </tr>
          <tr>
            <td class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE""> <?php echo $norecord; ?>
              <table border="0" cellspacing="3" cellpadding="2">
                <tr>
                  <td class="combo"><input name="job" type="hidden" id="job" value="<?php echo $_GET['Id']; ?>">
                    Technician</td>
                  <td><select name="technician" class="tarea" id="technician">
                    <option value=" ">Select one...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Recordset45['Id']?>"><?php echo $row_Recordset45['Name']?></option>
                    <?php
} while ($row_Recordset45 = mysqli_fetch_assoc($Recordset45));
  $rows = mysqli_num_rows($Recordset45);
  if($rows > 0) {
      mysqli_data_seek($Recordset45, 0);
	  $row_Recordset45 = mysqli_fetch_assoc($Recordset45);
  }
?>
                  </select></td>
                  <td class="combo">Score</td>
                  <td><input name="score_date" type="hidden" value="<?php echo date('Y m d'); ?>"></td>
                  <td><input name="Score" type="submit" class="btn-go-small" id="Score" value=""></td>
                </tr>
              </table>
              <?php 
			if($totalRows_Recordset44 >= 1){
			do { ?>
<table width="200" border="0" cellpadding="2" cellspacing="3" class="combo">
                <tr>
                  <td><?php echo $row_Recordset44['Name']; ?></td>
                  <td width="20"><?php echo $row_Recordset44['Score']; ?></td>
                  <td width="20"><a href="<?php echo $_SERVER["../REQUEST_URI"] .'&recordid='. $row_Recordset44['Id']; ?>"> <img src="../images/no.jpg" alt="" width="15" height="15" border="0" /></a></td>
                </tr>
              </table>
<input name="score" type="text" class="tarea" id="score" size="20">
              <?php } while ($row_Recordset44 = mysqli_fetch_assoc($Recordset44)); } ?>
            </div></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header">&nbsp;Customer Status Report</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
              <div style="padding-bottom:5px;">
                <table border="0" cellpadding="2" cellspacing="3" class="combo2">
                  <tr>
                    <td><select name="progress" class="combo" id="progress">
                      <option>Select one...</option>
                      <?php
				$i = 0;
				
				for($i=1;$i<=100;$i++){
				?>
                      <option value="<?php echo $i; ?>" <?php if($i == $row_progress['Progress']){ ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
                      <?php } ?>
                    </select></td>
                    <td>%</td>
                  </tr>
                </table>
              </div>
              <?php
$jobid = $_GET['Id'];
$queryfb = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
$rowfb = mysqli_fetch_array($queryfb);
?>
              <textarea name="cfb" rows="4" class="tarea-tech" id="cfb"><?php echo $rowfb['CustomerFeedBack']; ?></textarea>
            </div></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="3" class="combo"><table width="760" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#A6CAF0" class="td-header"><table border="0" cellpadding="0" cellspacing="0" class="td-header">
                    <tr>
                      <td><strong>&nbsp;Actual History</strong> <span style="font-weight:normal"><?php echo $row_Recordset4['HistoryLog']; ?></span></td>
                      <td width="40" align="right">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo"><?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$numrows = mysqli_num_rows($result);
if($numrows >= 2){
while($row = mysqli_fetch_array($result)){

$date = $row['CommentDate'];
$name = $row['CommentName'];
$comment = $row['CommentText'];

$split = explode(" ", $date);
$year = $split[2];
$month = $split[1];
$day = $split[0];
$hour = $split[3];
$minute = $split[4];

?>
              <div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE""> <br>
                <div style="padding-bottom:5px;">
                  <select name="day[]" class="tarea" id="day[]">
                    <?php for($i=1;$i<=31;$i++){ ?>
                      <option value="<?php echo $i; ?>" <?php if($day == $i){ echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
                    <?php } ?>
                  </select>
                  
                  <select name="month[]" class="tarea" id="month[]">
                    <option value="January" <?php if (!(strcmp("January", $month))) {echo "selected=\"selected\"";} ?>>January</option>
                    <option value="February" <?php if (!(strcmp("February", $month))) {echo "selected=\"selected\"";} ?>>February</option>
                    <option value="March" <?php if (!(strcmp("March", $month))) {echo "selected=\"selected\"";} ?>>March</option>
                    <option value="April" <?php if (!(strcmp("April", $month))) {echo "selected=\"selected\"";} ?>>April</option>
                    <option value="May" <?php if (!(strcmp("May", $month))) {echo "selected=\"selected\"";} ?>>May</option>
                    <option value="June" <?php if (!(strcmp("June", $month))) {echo "selected=\"selected\"";} ?>>June</option>
                    <option value="July" <?php if (!(strcmp("July", $month))) {echo "selected=\"selected\"";} ?>>July</option>
                    <option value="August" <?php if (!(strcmp("August", $month))) {echo "selected=\"selected\"";} ?>>August</option>
                    <option value="September" <?php if (!(strcmp("September", $month))) {echo "selected=\"selected\"";} ?>>September</option>
                    <option value="October" <?php if (!(strcmp("October", $month))) {echo "selected=\"selected\"";} ?>>October</option>
                    <option value="November" <?php if (!(strcmp("November", $month))) {echo "selected=\"selected\"";} ?>>November</option>
                    <option value="December" <?php if (!(strcmp("December", $month))) {echo "selected=\"selected\"";} ?>>December</option>
                  </select>
                  <select name="year[]" class="tarea" id="year[]">
                    <option value="2009" selected <?php if (!(strcmp(2009, $year))) {echo "selected=\"selected\"";} ?>>2009</option>
                    <option value="2010" <?php if (!(strcmp(2010, $year))) {echo "selected=\"selected\"";} ?>>2010</option>
                    <option value="2011" <?php if (!(strcmp(2011, $year))) {echo "selected=\"selected\"";} ?>>2011</option>
                    <option value="2012" <?php if (!(strcmp(2012, $year))) {echo "selected=\"selected\"";} ?>>2012</option>
                    <option value="2013" <?php if (!(strcmp(2013, $year))) {echo "selected=\"selected\"";} ?>>2013</option>
                    <option value="2014" <?php if (!(strcmp(2014, $year))) {echo "selected=\"selected\"";} ?>>2014</option>
                    <option value="2015" <?php if (!(strcmp(2015, $year))) {echo "selected=\"selected\"";} ?>>2015</option>
                    <option value="2017" <?php if (!(strcmp(2017, $year))) {echo "selected=\"selected\"";} ?>>2016</option>
                    <option value="2018" <?php if (!(strcmp(2018, $year))) {echo "selected=\"selected\"";} ?>>2018</option>
                    <option value="2019" <?php if (!(strcmp(2019, $year))) {echo "selected=\"selected\"";} ?>>2019</option>
                    <option value="2020" <?php if (!(strcmp(2020, $year))) {echo "selected=\"selected\"";} ?>>2020</option>
                  </select>
                  <select name="hour[]" class="tarea" id="hour[]">
                    <option value="1" selected <?php if (!(strcmp(1, $hour))) {echo "selected=\"selected\"";} ?>>1</option>
                    <option value="2" <?php if (!(strcmp(2, $hour))) {echo "selected=\"selected\"";} ?>>2</option>
                    <option value="3" <?php if (!(strcmp(3, $hour))) {echo "selected=\"selected\"";} ?>>3</option>
                    <option value="4" <?php if (!(strcmp(4, $hour))) {echo "selected=\"selected\"";} ?>>4</option>
                    <option value="5" <?php if (!(strcmp(5, $hour))) {echo "selected=\"selected\"";} ?>>5</option>
                    <option value="6" <?php if (!(strcmp(6, $hour))) {echo "selected=\"selected\"";} ?>>6</option>
                    <option value="7" <?php if (!(strcmp(7, $hour))) {echo "selected=\"selected\"";} ?>>7</option>
                    <option value="8" <?php if (!(strcmp(8, $hour))) {echo "selected=\"selected\"";} ?>>8</option>
                    <option value="9" <?php if (!(strcmp(9, $hour))) {echo "selected=\"selected\"";} ?>>9</option>
                    <option value="10" <?php if (!(strcmp(10, $hour))) {echo "selected=\"selected\"";} ?>>10</option>
                    <option value="11" <?php if (!(strcmp(11, $hour))) {echo "selected=\"selected\"";} ?>>11</option>
                    <option value="12" <?php if (!(strcmp(12, $hour))) {echo "selected=\"selected\"";} ?>>12</option>
                    <option value="13" <?php if (!(strcmp(13, $hour))) {echo "selected=\"selected\"";} ?>>13</option>
                    <option value="14" <?php if (!(strcmp(14, $hour))) {echo "selected=\"selected\"";} ?>>14</option>
                    <option value="15" <?php if (!(strcmp(15, $hour))) {echo "selected=\"selected\"";} ?>>15</option>
                    <option value="16" <?php if (!(strcmp(16, $hour))) {echo "selected=\"selected\"";} ?>>16</option>
                    <option value="17" <?php if (!(strcmp(17, $hour))) {echo "selected=\"selected\"";} ?>>17</option>
                    <option value="18" <?php if (!(strcmp(18, $hour))) {echo "selected=\"selected\"";} ?>>18</option>
                    <option value="19" <?php if (!(strcmp(19, $hour))) {echo "selected=\"selected\"";} ?>>19</option>
                    <option value="20" <?php if (!(strcmp(20, $hour))) {echo "selected=\"selected\"";} ?>>20</option>
                    <option value="21" <?php if (!(strcmp(21, $hour))) {echo "selected=\"selected\"";} ?>>21</option>
                    <option value="22" <?php if (!(strcmp(22, $hour))) {echo "selected=\"selected\"";} ?>>22</option>
                    <option value="23" <?php if (!(strcmp(23, $hour))) {echo "selected=\"selected\"";} ?>>23</option>
                    <option value="24" <?php if (!(strcmp(24, $hour))) {echo "selected=\"selected\"";} ?>>24</option>
                  </select>
                  <select name="minute[]" class="tarea" id="minute[]">
                    <option value="00" selected <?php if (!(strcmp(00, $minute))) {echo "selected=\"selected\"";} ?>>00</option>
                    <option value="05" <?php if (!(strcmp(05, $minute))) {echo "selected=\"selected\"";} ?>>05</option>
                    <option value="10" <?php if (!(strcmp(10, $minute))) {echo "selected=\"selected\"";} ?>>10</option>
                    <option value="15" <?php if (!(strcmp(15, $minute))) {echo "selected=\"selected\"";} ?>>15</option>
                    <option value="20" <?php if (!(strcmp(20, $minute))) {echo "selected=\"selected\"";} ?>>20</option>
                    <option value="25" <?php if (!(strcmp(25, $minute))) {echo "selected=\"selected\"";} ?>>25</option>
                    <option value="30" <?php if (!(strcmp(30, $minute))) {echo "selected=\"selected\"";} ?>>30</option>
                    <option value="35" <?php if (!(strcmp(35, $minute))) {echo "selected=\"selected\"";} ?>>35</option>
                    <option value="40" <?php if (!(strcmp(40, $minute))) {echo "selected=\"selected\"";} ?>>40</option>
                    <option value="45" <?php if (!(strcmp(45, $minute))) {echo "selected=\"selected\"";} ?>>45</option>
                    <option value="50" <?php if (!(strcmp(50, $minute))) {echo "selected=\"selected\"";} ?>>50</option>
                    <option value="55" <?php if (!(strcmp(55, $minute))) {echo "selected=\"selected\"";} ?>>55</option>
                  </select>
                  &nbsp;
                  <input name="history-date" type="text" class="tarea" id="textfield">
                  <input name="comment_name[]" type="text" class="tarea" id="comment_name[]" value="<?php echo $row['CommentName']; ?>" size="87">
                  <input name="delete_c[]" type="checkbox" id="delete_c[]" value="<?php echo $row['Id']; ?>">
                </div>
                <br>
                <input name="comment1" type="hidden" id="comment1" value="<?php echo $row['Id']; ?>">
                <input name="id_c[]" type="hidden" id="id_c[]" value="<?php echo $row['Id']; ?>">
                <textarea name="comment[]" rows="4" class="tarea-tech" id="textarea" type="text" value="<?php echo $row['CommentText']; ?>"><?php echo $row['CommentText']; ?></textarea>
                <br>
                <br>
              </div>
              <?php }} else { ?>
              <div>
              
              <div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
                <div style="padding-bottom:5px;">
                
                  <!-- Allocate Technicians -->
                  <div id="CollapsiblePanel1" class="CollapsiblePanel">
                    <div class="CollapsiblePanelTab" tabindex="0">&nbsp;Allocate Technicians</div>
                    <div class="CollapsiblePanelContent">
                      <?php
                        while($row_Recordset100 = mysqli_fetch_array($Recordset100)){
						
							$id = $row_Recordset100['Id'];
							
							$query = mysqli_query($con, "SELECT * FROM tbl_history_alerts WHERE JobId = '$jobid' AND TechnicianId = '$id'")or die(mysqli_error($con));
							$row = mysqli_fetch_array($query);
                        
                       ?>
                      <div class="allocate-tech">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td>
                            <table border="0" cellspacing="3" cellpadding="2">
                              <tr>
                                <td><input name="tech[]" type="checkbox" id="tech[]" value="<?php echo $row_Recordset100['Id']; ?>"<?php if($row_Recordset100['Id'] == $row['TechnicianId']) { ?> checked="checked"<?php } ?>></td>
                                <td class="combo"><?php echo $row_Recordset100['Name']; ?></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- End Allocate Technicians -->
                  
                </div>
                <div id="history-log">
                  <?php do { ?>
                  <span style="color:#306294; background-image:url(../images/icons/history-bg.jpg); height:19px; border:solid 1px #85afd7; font-size:8px"><b>
                  <?php 
                    $jobid = $row['JobId'];
                    $string = explode(" ",$row_Recordset4['Date']);
                    
                    $date = $string[0];
                    $time = $string[1];
                    $split_time = explode(":", $time);
                    
                    $new_time = $split_time[0] .':'. $split_time[1];
                    
                    $date_split = explode("-",$date);
                    
                    $day = $date_split[2];
                    $month = $date_split[1];
                    $year = $date_split[2];
                    
                    if($row_Recordset4['Mobile'] == 1){
						
						echo $row_Recordset4['Name_1'];
                    
                    } else {
						
						echo $row_Recordset4['Name'];
                    
                    } 
                    ?></b>
                    
                  &nbsp;<span style="font-size:8px; color:#4383C2"><?php echo $day .' / '. $month .' '. $new_time; ?></span>
                  </span>
                  &nbsp;<?php echo nl2br($row_Recordset4['Comments']); ?>
                  <?php } while ($row_Recordset4 = mysqli_fetch_assoc($Recordset4)); ?>
                </div>
                <div class="comments">Comments</div>
                <?php
				
				$disabled = '';
				
				if($_SESSION['history'] == 0){
					
					if($row_Recordset5['Status'] == 18 || $row_Recordset5['Status'] == 19){
						
						$disabled = 'disabled="disabled"';
					}
				}
				?>
                <textarea name="comment" rows="4" class="tarea-tech" id="comment" type="text" <?php echo $disabled; ?>></textarea>
                <input name="jobno" type="hidden" id="jobno" value="<?php echo $row_Recordset5['JobNo']; ?>">
                <div style="padding-top:5px;">
                  <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin-bottom:5px; padding:3px; border:solid 1px #A6CAF0">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="50%"><span class="combo_bold" style="color:#333;">Upload Image</span></td>
                        <td width="50%" align="right"><span class="combo_bold" style="color:#333;"><a href="../jc-photos.php?Id=<?php echo $_GET['Id']; ?>">Search Gallery</a></span></td>
                      </tr>
                    </table>
                  </div>
                  <table border="0" cellpadding="2" cellspacing="3" class="combo">
                    <tr>
                      <td><input type="file" name="photo" id="photo"></td>
                      <td><?php if($totalRows_job_history >= 1){ echo $totalRows_job_history .' Photos'; ?></td>
                      <td>
                        <table border="0" cellpadding="2" cellspacing="3">
                          <tr>
                            <?php
                              do {
                              ?>
                            <td><a href="../images/history/<?php echo $row_job_history['Photo']; ?>" class="look_inside" onClick="return hs.expand(this, {captionId: 'caption1'})"> <img src="../images/icons/btn-image.png" alt="" width="25" height="25" border="0"> </a></td>
                            <?php
                              $row_job_history = mysqli_fetch_assoc($job_history);
                              if (!isset($nested_job_history)) {
                                $nested_job_history= 1;
                              }
                              if (isset($row_job_history) && is_array($row_job_history) && $nested_job_history++ % 12==0) {
                                echo "</tr><tr>";
                              }
                              } while ($row_job_history); //end horizontal looper version 3
                              ?>
                          </tr>
                        </table>
                        <?php } // close if ?>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
              
                <div style="padding-bottom:5px; padding-top:5px">
                  <table width="760" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td bgcolor="#A6CAF0" class="td-header">&nbsp;Dealers Feed Back </td>
                    </tr>
                  </table>
                </div>
                <?php
				$jobid = $_GET['Id'];
				
				$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
				$result = mysqli_query($con, $query) or die(mysqli_error($con));
				$numrows = mysqli_num_rows($result);
				$row = mysqli_fetch_array($result);
				
				if(!empty($row['FeedBackDate'])){
					
					$fb_date = $row['FeedBackDate'];
					
				} else {
					
					$fb_date = date('Y-m-d');
				}
			?>
                <div style=" border:solid 1px #A6CAF0; padding:5px; background-color:#EEE"">
                  <div style="padding-bottom:5px;">
                    <input name="fb_date" class="tarea" id="fb_date" value="<?php echo $fb_date; ?>">
                    
					<script type="text/javascript">
                    $('#fb_date').datepicker({
                    dateFormat: "yy-mm-dd"
                    });
                    </script>
                    
                  </div>
<div id="history-log">
        <span style="color:#306294; background-image:url(../images/icons/history-bg.jpg); height:19px; border:solid 1px #85afd7; font-size:8px"><b>
        
		<?php 
		
		echo $row_Recordset6['FeedBackTech'];
		
		?></b>&nbsp;<span style="font-size:8px; color:#4383C2"><?php echo $row_Recordset6['FeedBackDate']; ?></span>
        </span>
        &nbsp;<?php echo nl2br($row_Recordset6['FeedBack']); ?>
                  </div>                  <textarea name="feedback" rows="4" class="tarea-tech" id="feedback"><?php echo $row_Recordset6['FeedBack']; ?></textarea>
                  <input name="id_c" type="hidden" id="id_c" value="<?php echo $row['Id']; ?>">
                </div>
              </div>
              <?php } // close loop ?>
              </div>
              <?php if(isset($_GET['feedback'])){ ?>
              <span class="form_validation_field_error_error_message">Required Field</span>
              <?php } ?></td>
          </tr>
          <?php if($_COOKIE['contractor'] == 0 && isset($_COOKIE['contractor'])){ ?>
          <tr>
            <td colspan="3" class="combo"><table width="760" border="0" cellpadding="2" cellspacing="2">
              <tr>
                <td width="485" class="td-header">&nbsp;Actual Work Carried Out </td>
                <td width="50" align="center" class="td-header">Unit</td>
                <td width="50" align="center" class="td-header">Qty.</td>
                <td width="100" align="center" class="td-header">Unit Price </td>
                <td width="15" align="center" class="td-header">Delete</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3" class="combo_bold"><div style=" border:solid 1px #A6CAF0; background-color:#EEE">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left"><div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Labour</div>
                    <?php
					  $jobid = $_GET['Id'];
					  
					  $query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1' ORDER BY Id ASC") or die(mysqli_error($con));
					  $numrows = mysqli_num_rows($query);
					  while($row = mysqli_fetch_array($query)){
					  
					  $id = $row['Id'];
					  
					  $query1 = "SELECT * FROM tbl_jc WHERE Id = '$id'";
					  $result1 = mysqli_query($con, $query1) or die(mysqli_error($con));
					  $row1 = mysqli_fetch_array($result1);
					  
					  $rate = $row1['Price'];
					  $rate = explode(".", $rate);
					  $unit = $row['Unit'];
					  
					  $query3 = mysqli_query($con, "SELECT * FROM tbl_rates WHERE Fuel = '1'")or die(mysqli_error($con));
					  $row3 = mysqli_fetch_array($query3);
					  
					  $unit_display = $row['Name'];
					
					?>
                    <div> &nbsp;
                      <textarea name="labour[]" rows="5" wrap="hard" class="tfield-jc" id="labour"><?php if($row['Description'] == NULL){ echo 'Conducted risk assessment & completed necessary safety documents'; } else { echo $row['Description']; } ?>
          </textarea>
                      <input name="unit_l[]" type="text" class="tarea" id="unit_l" value="<?php echo $unit; ?>" size="6">
                      <input name="qty_l[]" type="text" class="tarea" id="qty_l" value="<?php echo $row['Qty']; ?>" size="6">
                      <?php include('../transport.php'); ?>
                      <?php if($unit == 'hours'){ ?>
                      <select name="price_l[]" class="tarea" id="price_l[]" style="width:100px;">
                        <?php
do {  
?>
                        <option value="<?php echo $row_Recordset101['Rate']?>"<?php if (!(strcmp($row_Recordset101['Rate'], $rate[0]))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset101['Name_1']?></option>
                        <?php
} while ($row_Recordset101 = mysqli_fetch_assoc($Recordset101));
  $rows = mysqli_num_rows($Recordset101);
  if($rows > 0) {
      mysqli_data_seek($Recordset101, 0);
	  $row_Recordset101 = mysqli_fetch_assoc($Recordset101);
  }
?>
                      </select>
                      <?php } elseif($unit == 'km'){ ?>
                      <input name="price_display_l[]" type="text" class="tarea" id="price_display_l[]" value="<?php echo $row3['Name']; ?>" style="width:100px">
                      <input name="material[]" type="text" class="tfield-jc" id="material" value="<?php echo $row['Description']; ?>">
<input name="price_l[]" type="hidden" class="tarea" id="price_l[]" value="<?php echo $row['Price']; ?>">
                      <?php } ?>
                      &nbsp;&nbsp;
                      <input name="delete[]" type="checkbox" id="delete" value="<?php echo $row['Id']; ?>">
                      <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>">
                    </div>
                    <?php } // close loop ?>
                    <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Material</div>
                    <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
while($row = mysqli_fetch_array($result)){
?>
                    <div> &nbsp;
                      <input name="material[]" type="text" class="tfield-jc" id="material" value="<?php echo $row['Description']; ?>">
                      <input name="unit_m[]" type="text" class="tarea" id="unit_m" value="<?php echo $row['Unit']; ?>" size="6">
                      <input name="qty_m[]" type="text" class="tarea" id="qty_m" value="<?php echo $row['Qty']; ?>" size="6">
                      <input name="price_m[]" type="text" class="tarea" id="price_m" value="<?php echo $row['Price']; ?>" size="16">
                      &nbsp;&nbsp;
                      <input name="delete_m[]" type="checkbox" id="delete_m[]" value="<?php echo $row['Id']; ?>">
                      <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>">
                    </div>
                    <?php } // close loop ?>
                    <div class="combo_bold" style="background-color:#E1E1E1; color:#333; margin:5px; padding:3px; border:solid 1px #A6CAF0"> Transport</div>
                    <?php include('transport.php'); ?>
                    </td>
                </tr>
              </table>
            </div></td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="3" class="combo_bold">
            <div class="combo_bold" style="margin-top:2px; margin-bottom:15px; clear:both;">
              <?php if($_COOKIE['contractor'] == 0 && isset($_COOKIE['contractor'])){ ?>
              <table border="0" cellpadding="0" cellspacing="3">
                <tr>
                  <td valign="middle"><span style="padding-bottom:5px">
                    <input name="Submit3" type="submit" class="btn-new" value="Save" style="padding:5px">
                  </span></td>
                  <?php
							$jobid = $_GET['Id'];
							$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
							$row = mysqli_fetch_array($query);
							$status = $row['Status'];
							$print = $row['Print'];
							if($status == 1){ 
							?>
                  <td align="center" valign="middle" class="combo_bold">Send To In Progress </td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="1"></td>
                  <?php } elseif($status == 2 || $status == 5 || $status == 20){ ?>
                  <td align="center" valign="middle" class="combo_bold">Send To Awaiting PWK</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="awaiting-pwk"></td>
                  <td valign="middle" nowrap="nowrap" class="combo_bold">Awaiting Post Work PO</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="awaiting-post-po"></td>
                  <td align="center" valign="middle" class="combo_bold">Send To Costing</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="2"></td>
                  <?php if($row_Recordset5['Id_1'] == 6 && $row_Recordset5['RequestPreWorkPo'] == 0){ ?>
                  <td align="center" valign="middle" class="combo_bold">Request Pre Work PO</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="request-pre-po"></td>
                  <?php } ?>
                  <?php } elseif($status == 3){ ?>
                  <td align="center" valign="middle" class="combo_bold">Send To Invoices</td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="3"></td>
                  <?php } elseif($status == 5){ ?>
                  <td align="center" valign="middle" class="combo_bold">Send To In Progress </td>
                  <td valign="middle"><input name="complete" type="checkbox" id="complete" value="5"></td>
                  <?php } ?>
                </tr>
              </table>
              <?php } else { ?>
              <table border="0" align="right" cellpadding="0" cellspacing="3">
                <tr>
                  <td><input name="con-complete" type="submit" class="btn-new" id="con-complete" value="Complete"></td>
                  <td><span style="padding-bottom:5px">
                    <input name="con-save" type="submit" class="btn-new" id="con-save" style="padding:5px" value="Save">
                  </span></td>
                </tr>
              </table>
              <?php } ?>
            </div></td>
          </tr>
        </table>
    </form></td>
  </tr>
</table>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
//-->
</script>
</body>
</html>
<?php

unset($_SESSION['feedback']);
unset($_SESSION['services']);

mysqli_close($con);
mysqli_free_result($query);
mysqli_free_result($query2);
mysqli_free_result($query3);
mysqli_free_result($query4);
mysqli_free_result($query5);
mysqli_free_result($query40);
mysqli_free_result($Recordset4);
mysqli_free_result($Recordset5);
mysqli_free_result($Recordset3);
mysqli_free_result($Recordset6);
mysqli_free_result($Recordset7);
mysqli_free_result($Recordset8);
mysqli_free_result($Recordset100);
mysqli_free_result($Recordset101);
mysqli_free_result($Recordset9);
mysqli_free_result($Recordset41);
mysqli_free_result($Recordset42);
mysqli_free_result($Recordset43);
mysqli_free_result($Recordset44);
mysqli_free_result($Recordset45);
mysqli_free_result($rs_companies);
mysqli_free_result($rs_sites);
mysqli_free_result($job_history);
mysqli_free_result($progress);
mysqli_free_result($rs_transport_rates);
mysqli_free_result($rs_components);
mysqli_free_result($rs_failure);
mysqli_free_result($rs_root_cause);
mysqli_free_result($rs_repair);
mysqli_free_result($rs_pragma);
mysqli_free_result($query_engineers);
?>
