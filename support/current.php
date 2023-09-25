<?php 
session_start();

require_once('../Connections/inv.php'); 
require_once('../functions/functions.php');

if(isset($_GET['Status'])){
	
	$status = $_GET['Status'];
	$jobid = $_GET['Id'];
	
	$query = "
		SELECT
			tbl_support.Date,
			tbl_support.JobNo,
			tbl_support.JobStatus,
			tbl_support.Error,
			tbl_support.ResolvedDate,
			tbl_support.Status,
			tbl_users.Id AS userid,
			tbl_users.Name,
			tbl_users.Email
		FROM
			tbl_support
		INNER JOIN tbl_users ON tbl_support.UserId = tbl_users.Id
		WHERE tbl_support.Id = '$jobid'";
	
	$query = mysqli_query($con, $query)or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
		
	$date = date('Y-m-d H:i');
	$userid = $row['userid'];
	$name = $row['Name'];
	
	mysqli_query($con, "UPDATE tbl_support SET Status = '$status', ResolvedDate = '$date' WHERE Id = '$jobid'")or die(mysqli_error());
	
	$to  = $row['Email']; 
	$subject = 'Sealink Support Update';
		
	$message = '
	<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
	<div style="padding-left:55px; padding-top:50px">
	<span class="header"><img src="http://www.seavest.co.za/inv/images/logo.jpg" /></span>
	</div>
	<div style="padding-left:55px; padding-top:50px">
	<table border="0" cellpadding="2" width="700" cellspacing="3" style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
	  <tr>
		<td>Hi '. $name .'<br><br>The status your support ticket with the reference number '. $_GET['Id'] .' has been updated to <b><i>'. $status .'</i></b></td>
	  </tr>
	</table>
	</div>
	</body>
	';
	
	$mail = 'karsten@kwd.co.za';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'FROM: '. $mail . "\r\n";
	
	mail($to, $subject, $message, $headers);
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
}

$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

$query_support = "
  SELECT
	  tbl_support.Id,
	  tbl_support.UserId,
	  tbl_support.Date,
	  tbl_support.JobNo,
	  tbl_support.JobStatus,
	  tbl_support.Error,
	  tbl_support.ResolvedDate,
	  tbl_support.Status,
	  tbl_support.Error,
	  tbl_users.Name,
	  tbl_users.Email
  FROM
	  (
		  tbl_support
		  LEFT JOIN tbl_users ON tbl_users.Id = tbl_support.UserId
	  )
  WHERE
	  tbl_support.Status = 'In Progress'";
	
$query_support = mysqli_query($con, $query_support)or die(mysqli_error($con));
$numrows = mysqli_num_rows($query_support);

$query_balance = mysqli_query($con, "SELECT * FROM tbl_time_log")or die(mysqli_error($con));
$row_balance = mysqli_fetch_array($query_balance);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
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

.hidden{
	display:none!important;
	visibility:hidden!important;
}
-->
</style>
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>

<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
	mode : "specific_textareas",
    editor_selector : "mceEditor",
    theme: "modern",
	content_css : "http://www.webdesigndurban.co.za/eurovets/backend/styles.css",
    plugins: [
        ["autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
        ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
        ["save table contextmenu directionality emoticons template paste importcss  responsivefilemanager"]
    ],
    add_unload_trigger: true,
    schema: "html5",
    inline: false,
    toolbar: "undo redo | style-h1 style-h2 style-h3 style-h4 style-h5 style-h6 | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image responsivefilemanager",
    statusbar: false,
	relative_urls:false,
    external_filemanager_path:"/inv/tinymce/filemanager/",
    filemanager_title:"Responsive Filemanager" ,
    external_plugins: { "filemanager" : "/inv/tinymce/filemanager/plugin.min.js"},
});

</script> 

</head>

<body>
   <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
         <td width="200" valign="top"><?php include('../menu.php'); ?></td>
         <td valign="top">
            <table width="750" border="0" cellpadding="0" cellspacing="0">
               <tr>
                  <td align="center">
                     <table width="761" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                           <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td>
                     <?php
					 $seconds = 60 * 60 * 18;
					 $remaining = ($seconds / strtotime($row_balance['Balance'])) * 100;
					 ?>
                     <p>&nbsp;</p>
                     <div style="margin-left:30px">
                     <table width="100%" border="0" cellpadding="0" cellspacing="0">
                       <tr>
                         <td>
                         <div id="list-border">
                           <table width="100%" border="0" cellspacing="1" cellpadding="2">
                              <tr>
                                <td colspan="4" align="center" class="td-header-ref-bg"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td align="center" class="td-header-ref"><?php echo date('F Y'); ?></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="12%" rowspan="2" valign="top" class="td-left">Balance</td>
                                <td width="38%" class="td-right"><?php echo $row_balance['Balance']; ?> hrs</td>
                                <td width="12%" rowspan="2" valign="top" class="td-left">Used</td>
                                <td width="38%" class="td-right"><?php echo gmdate('H:i:s',(strtotime('18:00:00') - strtotime($row_balance['Balance']))); ?> Hrs</td>
                              </tr>
                              <tr>
                                <td class="td-right">
								<?php echo round(((strtotime($row_balance['Balance']) - strtotime('TODAY')) / $seconds) * 100); // 3600; ?>%</td>
                                <td class="td-right"><?php echo 100 - round(((strtotime($row_balance['Balance']) - strtotime('TODAY')) / $seconds) * 100); // 3600; ?>%</td>
                              </tr>
                            </table>

                         </div>
                         </td>
                       </tr>
                     </table>
                     </div>
                     <p>&nbsp;
                     
					  <?php 
                         if($numrows >= 1){ 
                             
                             $num_rows = $numrows;
                             $i = 0;
                             
                             while($row = mysqli_fetch_array($query_support)){
                                 
                                 $i++;
                                 
                                 if($_GET['Id'] == $row['Id']){
                                     
                                     $class = 'td-header-ref-bg-active';
                                     
                                 } else {
                                     
                                     $class = 'td-header-ref-bg';
                                 }
                         ?>
                    <form name="form2" method="post" action="time-cookies.php?Id=<?php echo $row['Id']; ?>" style="margin-left:30px">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                           <tr>
                              <td>
                                 <div id="list-brdr-supprt">
                                    <table width="100%" border="0" cellpadding="2" cellspacing="1" class="comb-sms">
                                       <tr>
                                          <td colspan="2" class="<?php echo $class; ?>">
                                             <table width="100%" border="0" cellpadding="5" cellspacing="0" class="td-header-ref">
                                                <tr>
                                                   <td align="center">REF NO:<?php echo $row['Id']; ?></td>
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td class="td-left"><strong><em>Status</em></strong></td>
                                          <td width="86%" class="td-right">
                                             <select name="change-status" class="tarea-new-100" id="change-status" onChange="MM_jumpMenu('parent',this,0)">
                                                <option value="">Select one...</option>
                                                <option value="current.php?Status=Pending&Id=<?php echo $row['Id']; ?>" <?php if($row['Status'] == 'Pending'){ echo 'selected="selected"';} ?>>Pending</option>
                                                <option value="current.php?Status=Acknowledged&Id=<?php echo $row['Id']; ?>" <?php if($row['Status'] == 'Acknowledged'){ echo 'selected="selected"';} ?>>Acknowledged</option>
                                                <option value="current.php?Status=In Progress&Id=<?php echo $row['Id']; ?>" <?php if($row['Status'] == 'In Progress') { echo 'selected="selected"';} ?>>In Progress</option>
                                                <option value="current.php?Status=Resolved&Id=<?php echo $row['Id']; ?>" <?php if ($row['Status'] == 'Resolved') { echo 'selected="selected"';} ?>>Resolved</option>
                                             </select>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td width="14%" class="td-left"><em><strong>Date</strong></em></td>
                                          <td class="td-right"><?php echo $row['Date']; ?></td>
                                       </tr>
                                       <tr>
                                          <td class="td-left"><strong><em>Requestor</em></strong></td>
                                          <td class="td-right"><?php echo $row['Name']; ?>
                                          <input name="userid" type="hidden" id="userid" value="<?php echo $row['UserId']; ?>">
                                          <input name="username" type="hidden" id="username" value="<?php echo $row['Name']; ?>"></td>
                                       </tr>
                                       <tr>
                                          <td class="td-left"><em><strong>Job / Invoice No</strong></em></td>
                                          <td class="td-right"><?php echo $row['JobNo']; ?></td>
                                       </tr>
                                       <tr>
                                          <td class="td-left"><em><strong>Location</strong></em></td>
                                          <td class="td-right"><?php echo $row['JobStatus']; ?></td>
                                       </tr>
                                       <tr>
                                          <td class="td-left"><strong><em>Status</em></strong></td>
                                          <td class="td-right"><?php echo $row['Status']; ?></td>
                                       </tr>
                                       <?php if($_GET['Id'] == $row['Id'] && isset($_GET['Start'])){ ?>
                                       <tr>
                                          <td class="td-left">Current Session</td>
                                          <td class="td-right"><h2><time>00:00:00</time></h2></td>
                                       </tr>
                                       <?php } ?>
									   <?php
                                      $jobid = $row['Id'];
                                      $query_sum = mysqli_query($con, "SELECT SUM(TIME_TO_SEC(tbl_time_log.Difference)) AS Diff FROM tbl_time_log WHERE JobId = '$jobid'")or die(mysqli_error($con));
                                      $row_sum = mysqli_fetch_array($query_sum);
                                      
                                      $diff = $row_sum['Diff'];
                      
                                      $hours = floor($diff/(60*60));
									  
                                      if($hours <= 9){
										  
										  $hours = '0'.floor($diff/(60*60));
										  
                                      } else {
										  
										  $hours = floor($diff/(60*60));
                                      }
									  
                                      $mins = floor(($diff-($hours*60*60))/(60));
									  
                                      if($mins <= 9){
										  
										  $mins = '0'.floor(($diff-($hours*60*60))/(60));
									  
                                      } else {
										  
										  $mins = floor(($diff-($hours*60*60))/(60));
                                      }
									  
                                      $seconds = floor(($diff-(($hours*60*60)+($mins*60))));
									  
                                      if($seconds <= 9){
										  
										  $secs = '0'.floor(($diff-(($hours*60*60)+($mins*60))));
									  
                                      } else {
										  
										  $secs = floor(($diff-(($hours*60*60)+($mins*60))));
                                      }
                                      $result = $hours.":".$mins.":".$secs;
									   ?>
                                       <tr>
                                          <td valign="top" class="td-left"><em><strong>Time Logged</strong></em></td>
                                          <td class="td-right"><?php echo $result; ?></td>
                                       </tr>
                                       <tr>
                                          <td valign="top" class="td-left"><em><strong>Error / Request</strong></em></td>
                                          <td class="td-right"><?php echo $row['Error']; ?></td>
                                       </tr>
                                       <tr>
                                         <td valign="top" class="td-left">Comments</td>
                                         <td class="td-right"><textarea name="comments" rows="5" class="tarea-new-100per mceEditor" id="comments-<?php echo $i; ?>"><?php echo $row['Error']; ?></textarea></td>
                                       </tr>
                                       <tr>
                                          <td colspan="2" align="center" class="td-left">
                                             <?php if($_GET['Power'] == $row['Id']){ ?>
                                                <button type="submit" id="start" name="start" class="btn"><i class="fa fa-play icon-large"></i></button>
                                              <?php } ?>
                                              <?php if($_GET['Start'] == $row['Id']){ ?>
                                                <button type="submit" id="stop" name="stop" class="btn"><i class="fa fa-stop icon-large"></i></button>
                                              <?php } ?>
                                             
                                             <?php if($_GET['Id'] != $row['Id']){ ?>
                                                <button type="submit" name="power" class='btn btn-power power-timer-btn'><i class="fa fa-power-off icon-large" style="padding-bottom:2px"></i></button>
                                             <?php } ?>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
                              </td>
                           </tr>
                        </table>
                     </form>
                     <div id="support-hr-2"></div>
                     						
						<?php 
							 }
						 }
                     ?>
                     
					<script src='timer/libs/jquery/jquery-1.11.0.min.js' type='text/javascript'></script>
                    <script src='timer/src/timer.jquery.js'></script>
                    <script src='timer/libs/jquery/jquery-1.11.0.min.js' type='text/javascript'></script>
                    <script src='timer/src/timer.jquery.js'></script>
					<script type="text/javascript">
                    var h1 = document.getElementsByTagName('h2')[0],
                        start = document.getElementById('start'),
                        stop = document.getElementById('stop'),
                        clear = document.getElementById('clear'),
                        seconds = 0, minutes = 0, hours = 0,
                        t;
                    
                    function add() {
                        seconds++;
                        if (seconds >= 60) {
                            seconds = 0;
                            minutes++;
                            if (minutes >= 60) {
                                minutes = 0;
                                hours++;
                            }
                        }
                        
                        h1.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);
                    
                        timer();
                    }
                    function timer() {
                        t = setTimeout(add, 1000);
                    }
                    timer();
                    
                    
                    /* Start button */
                    start.onclick = timer;
                    
                    /* Stop button */
                    stop.onclick = function() {
                        clearTimeout(t);
                    }
                    
                    /* Clear button */
                    clear.onclick = function() {
                        h1.textContent = "00:00:00";
                        seconds = 0; minutes = 0; hours = 0;
                    }
                    </script>
                     
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
</body>
</html>
<?php
  mysqli_free_result($query_support);
?>
