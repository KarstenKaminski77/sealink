<?php require_once('../Connections/inv.php'); 

session_start();

require_once('../functions/functions.php');

date_default_timezone_set('UTC');

if(isset($_GET['New'])){
	
	$new_date = $_GET['New'] .'-01';
	$ref = $_GET['Ref'];
	
	mysqli_query($con, "UPDATE tbl_support SET AllocatedDate = '$new_date' WHERE Id = '$ref'")or die(mysqli_error($con));
}

$date = explode('-', $_GET['Date']);

$year = $date[0];
$month = $date[1];

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
	  tbl_support.Comments,
	  tbl_support.AllocatedDate,
	  tbl_users.Name,
	  tbl_users.Email
  FROM
	  (
		  tbl_support
		  LEFT JOIN tbl_users ON tbl_users.Id = tbl_support.UserId
	  )
  WHERE
      YEAR(tbl_support.Date) = '$year'
  AND
      MONTH(tbl_support.Date) = '$month'";
	
$query_support = mysqli_query($con, $query_support)or die(mysqli_error($con));
$numrows = mysqli_num_rows($query_support);

$query_total = "
  SELECT
  SEC_TO_TIME(
		  SUM(TIME_TO_SEC(`Difference`))
	  ) AS TotalTime,
  tbl_support.Date
  FROM
  tbl_time_log
  INNER JOIN tbl_support ON tbl_support.Id = tbl_time_log.JobId
  WHERE
	  YEAR (tbl_support.Date) = '$year'
  AND MONTH (tbl_support.Date) = '$month'";

$query_total = mysqli_query($con, $query_total)or die(mysqli_error($con));
$row_total = mysqli_fetch_array($query_total);

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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	  <script type="text/javascript">
$(document).ready(function(){
          $(".toggler").click(function(e){
              e.preventDefault();
              $('.order'+$(this).attr('view')).toggle();
          });
      });
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
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
                                    <td align="center" class="td-header-ref"><?php echo date('F Y', strtotime($_GET['Date'])); ?></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="12%" rowspan="2" valign="top" class="td-left">Balance</td>
                                <td width="38%" class="td-right"><?php echo gmdate('H:i:s',(strtotime('18:00:00') - strtotime($row_total['TotalTime']))); ?> hrs</td>
                                <td width="12%" rowspan="2" valign="top" class="td-left">Used</td>
                                <td width="38%" class="td-right"><?php echo $row_total['TotalTime']; ?> Hrs</td>
                              </tr>
                              <tr>
                                <td class="td-right"><?php echo round(((strtotime($row_balance['Balance']) - strtotime('TODAY')) / $seconds) * 100); // 3600; ?>%</td>
                                <td class="td-right"><?php echo 100 - round(((strtotime($row_balance['Balance']) - strtotime('TODAY')) / $seconds) * 100); // 3600; ?>%</td>
                              </tr>
                           </table>

                         </div>
                         </td>
                       </tr>
                     </table>
                     </div>
                     <p>&nbsp;</p>
<form name="form2" method="post" action="time-cookies.php?Id=<?php echo $row['Id']; ?>" style="margin-left:30px">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td><div id="list-brdr-supprt">
                            <table width="100%" border="0" cellpadding="4" cellspacing="1" class="comb-sms">
                              <tr>
                                <td width="40" class="td-header">Ref No</td>
                                <td width="120" class="td-header">Date</td>
                                <td width="120" class="td-header">Allocated Month</td>
                                <td class="td-header">Requestor</td>
                                <td class="td-header">Job No</td>
                                <td width="45" class="td-header">Time</td>
                                <td width="30" class="td-header">&nbsp;</td>
                              </tr>
							  <?php 
                                   if($numrows >= 1){ 
                                       
                                       $num_rows = $numrows;
                                       $i = 0;
									   $x = 0;
                                       
                                       while($row = mysqli_fetch_array($query_support)){
                                           
                                           $i++;
										   $x++;
										   
											$jobid = $row['Id'];
											$query_sum = mysqli_query($con, "SELECT SEC_TO_TIME( SUM( TIME_TO_SEC(tbl_time_log.Difference))) AS Diff FROM tbl_time_log WHERE JobId = '$jobid'")or die(mysqli_error($con));
											$row_sum = mysqli_fetch_array($query_sum);
											
									 ?>
                              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                                <td class="td-combo"><?php echo $row['Id']; ?></td>
                                <td class="td-combo"><?php echo $row['Date']; ?></td>
                                <td class="td-combo">
                                    <select name="jumpMenu" class="tarea-new-100" id="jumpMenu" onChange="MM_jumpMenu('parent',this,0)">
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -9 month")); ?>&Ref=<?php echo $row['Id']; ?>" 
                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -9 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -9 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -8 month")); ?>&Ref=<?php echo $row['Id']; ?>" 
                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -8 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -8 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -7 month")); ?>&Ref=<?php echo $row['Id']; ?>" 
                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -7 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -7 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -6 month")); ?>&Ref=<?php echo $row['Id']; ?>" 
                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -6 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -6 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -5 month")); ?>&Ref=<?php echo $row['Id']; ?>"                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -5 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -5 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -4 month")); ?>&Ref=<?php echo $row['Id']; ?>"                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -4 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -4 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -3 month")); ?>&Ref=<?php echo $row['Id']; ?>"                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -3 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -3 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -2 month")); ?>&Ref=<?php echo $row['Id']; ?>"                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -2 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -2 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -1 month")); ?>&Ref=<?php echo $row['Id']; ?>"                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -1 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -1 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." -0 month")); ?>&Ref=<?php echo $row['Id']; ?>"                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." -0 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." -0 month")); ?>
                                      </option>
                                      <option value="archive-details-update.php?Date=<?php echo $_GET['Date']; ?>&New=<?php echo date('Y-m', strtotime(date('Y-m')." +1 month")); ?>&Ref=<?php echo $row['Id']; ?>"                                      <?php if(date('Y-m-01', strtotime(date('Y-m')." +1 month")) == $row['AllocatedDate']){ echo 'selected'; } ?>>
									    <?php echo date('M Y', strtotime(date('Y-m')." +1 month")); ?>
                                      </option>
                                    </select>
                                </td>
                                <td class="td-combo"><?php echo $row['Name']; ?></td>
                                <td class="td-combo"><?php echo $row['JobNo']; ?></td>
                                <td class="td-combo"><?php echo $row_sum['Diff']; ?></td>
                                <td align="center" class="combo">
                                  <a href="" class="icon-circle toggler" view="<?php  echo $x; ?>" title="View Details">
                                    <i class="fa fa-search line-height"></i>
                                  </a>
                                </td>
                              </tr>
                              <td colspan="7" valign="top" class="td-right order<?php  echo $x; ?>" style="display:none"><?php echo $row['Error']; ?></td>
                              </tr>
                              <?php 
                                       }
                                   }
                                  ?>
                            </table>
                          </div>
                          </td>
                        </tr>
              </table>
</form></td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
</body>
</html>
<?php
  mysqli_close($con);
  mysqli_free_result($query_balance);
  mysqli_free_result($query_sum);
  mysqli_free_result($query_total);
  mysqli_free_result($query_support);
?>
