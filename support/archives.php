<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysql_error());
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

if($_SESSION['kt_login_level'] == 0){

$areaid = $_SESSION['kt_AreaId'];
}

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
      
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

<script type="text/javascript">
	function MM_jumpMenu(targ, selObj, restore) { //v3.0
		eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
		if (restore) selObj.selectedIndex = 0;
	}

	$(document).ready(function () {
		$(".toggler").click(function (e) {
			e.preventDefault();
			$('.cat' + $(this).attr('data-prod-cat')).toggle();
		});
	});

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
            <li><a href="#">Settings</a></li>
            <li><a href="#">Technical Support</a></li>
            <li><a href="#">Service Stats</a></li>
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
        <div id="list-border">
		  <?php
            $_SESSION['month'] = '';
			
			$query_sum = "
				SELECT
					`tbl_support`.`Date`
					, `tbl_support`.`Id`
					, `tbl_time_log`.`Id` AS  Record
					, `tbl_time_log`.`Login`
					, `tbl_time_log`.`Logout`
					, SEC_TO_TIME( SUM( TIME_TO_SEC(tbl_time_log.Difference))) AS Hrs
					, `tbl_time_log`.`Balance`
					, `tbl_time_log`.`JobId`
					, `tbl_users`.`Name`
				FROM
					`tbl_support`
					INNER JOIN `tbl_time_log` 
						ON (`tbl_support`.`Id` = `tbl_time_log`.`JobId`)
					INNER JOIN `tbl_users` 
						ON (`tbl_support`.`UserId` = `tbl_users`.`Id`)
					WHERE MONTH(tbl_support.Date) = '$month[1]'
					GROUP BY MONTH(tbl_support.Date)
					ORDER BY tbl_support.Date ASC";
			 
			$query_sum = mysqli_query($con, $query_sum)or die(mysqli_error($con));
			$row_sum = mysqli_fetch_array($query_sum);
            
            $query_log = "
            
                SELECT
                    `tbl_support`.`Date`
                    , `tbl_support`.`Id`
                    , `tbl_support`.`Error`
                    , `tbl_time_log`.`Id` AS  Record
                    , TIMEDIFF(Logout, Login) AS Diff
                    , SEC_TO_TIME( SUM( TIME_TO_SEC(tbl_time_log.Difference))) AS IndvHrs
                    , `tbl_time_log`.`Login`
                    , `tbl_time_log`.`Logout`
                    , `tbl_time_log`.`Difference`
                    , `tbl_time_log`.`Balance`
                    , `tbl_time_log`.`NewBalance`
                    , `tbl_time_log`.`JobId`
                    , `tbl_users`.`Name`
                FROM
                    `tbl_support`
                    INNER JOIN `tbl_time_log` 
                        ON (`tbl_support`.`Id` = `tbl_time_log`.`JobId`)
                    INNER JOIN `tbl_users` 
                        ON (`tbl_support`.`UserId` = `tbl_users`.`Id`)
                    WHERE tbl_time_log.`Difference` != '00:00:00'
                    GROUP BY tbl_time_log.JobId
                    ORDER BY tbl_support.Date ASC";
             
            $query_log = mysqli_query($con, $query_log)or die(mysqli_error($con));
            
            echo '<table cellpadding="3px" cellspacing="1">';
			
			echo '<tr>';
			echo '<td colspan="2" class="td-sub-header2">Month</td>';
			echo '<td class="td-sub-header2">Total Hours</td>';
			echo '<td colspan="3" class="td-sub-header2">Excess Hours</td>';
			echo '<td class="td-sub-header2"></td>';
			echo '</tr>';
            
            $x = 0;
            $c = 0;
            
            while($row_log = mysqli_fetch_array($query_log)){
                
                $x++;
                
                $jobid = $row_log['JobId'];
                $record = $row_log['Record'];
                            
                $balance = $_SESSION['balance'];
                $difference = $row_log['Diff'];
                
                if($difference > $balance){
                    
                    $new_balance = gmdate('H:i:s',(strtotime('+18 hours', strtotime($balance)) - strtotime($row_log['IndvHrs'])));
                    $style = 'style="background-color:#F00"';
                    
                } else {
                    
                    $new_balance = gmdate('H:i:s',(strtotime($balance) - strtotime($row_log['IndvHrs'])));
                    $style = '';
                    
                }
                
                $month = explode('-', $row_log['Date']);
                
                if($_SESSION['month'] != $month[1]){
                    
                    $c++;
                    
                    $query_total = "
                        SELECT
                            `tbl_support`.`Date`
                            , `tbl_support`.`Id`
                            , `tbl_time_log`.`Id` AS  Record
                            , `tbl_time_log`.`Login`
                            , `tbl_time_log`.`Logout`
                            , SEC_TO_TIME( SUM( TIME_TO_SEC(tbl_time_log.Difference))) AS Hrs
                            , `tbl_time_log`.`Balance`
                            , `tbl_time_log`.`JobId`
                            , `tbl_users`.`Name`
                        FROM
                            `tbl_support`
                            INNER JOIN `tbl_time_log` 
                                ON (`tbl_support`.`Id` = `tbl_time_log`.`JobId`)
                            INNER JOIN `tbl_users` 
                                ON (`tbl_support`.`UserId` = `tbl_users`.`Id`)
                            WHERE MONTH(tbl_support.Date) = '$month[1]'
                            GROUP BY MONTH(tbl_support.Date)
                            ORDER BY tbl_support.Date ASC";
                     
                    $query_total = mysqli_query($con, $query_total)or die(mysqli_error($con));
                    $row_total = mysqli_fetch_array($query_total);
                    
                    echo '<tr>';
                    echo '<td colspan="2" width="200" class="td-sub-header">' . date('F', strtotime($row_log['Date'])) .'</td>';
                    echo '<td class="td-sub-header" width="150"><b>' . $row_total['Hrs'] .'</td>';
                    echo '<td colspan="3" class="td-sub-header" width="450"><b>' . date('M', strtotime($row_log['Date'])) .' Total: '. $row_total['Hrs'] .'</b></td>';
                    echo '<td class="td-sub-header" width="20px"><a href="#" class="toggler expand" data-prod-cat="2-'. $c .'"></a></td>';
                    echo '</tr><tr class="cat2-'. $c .'" style="display:none">';
                    echo '<td class="td-sub-sub-header">Ref.</td>';
                    echo '<td class="td-sub-sub-header">Opening Bal</td>';
                    echo '<td class="td-sub-sub-header">Duration</td>';
                    echo '<td class="td-sub-sub-header">Bal Remainbing</td>';
                    echo '<td class="td-sub-sub-header">Date</td>';
                    echo '<td class="td-sub-sub-header">Requestor</td>';
                    echo '<td class="td-sub-sub-header">&nbsp;</td>';
                    echo '</tr>';
                }
                
                echo '<tr class="cat2-'. $c .'" style="display:none" '. $style .'>';
                echo '<td width="50" class="td-right"><b>' . $row_log['Id'] . '</b></td>';
                echo '<td width="150" class="td-right">'. $balance .'</td>';
                echo '<td width="150" class="td-right">' . $row_log['IndvHrs'] . '</td>';
                echo '<td width="150" class="td-right">'. $new_balance .'</td>';
                echo '<td width="150" class="td-right">'. $row_log['Date'] .'</td>';
                echo '<td width="150" class="td-right">'. $row_log['Name'] .'</td>';
                echo '<td width="20" class="td-right"><a href="#" class="toggler expand" data-prod-cat="'. $x .'"></a></td>';
                echo '</tr>';
                echo '<tr class="cat'. $x .'" style="display:none">';
                echo '<td colspan="7" class="td-right"><div style="width:820px">'. $row_log['Error'] .'</div></td></tr>';
                
                mysqli_query($con, "UPDATE tbl_time_log SET NewBalance = '$new_balance' WHERE Id = '$record'")or die(mysqli_error($con));
                
                $_SESSION['balance'] = $new_balance;
                $_SESSION['month'] = $month[1];
                
                //header('Location: current.php?Id='. $jobid .'&Power='. $jobid);
                
            }
            
            echo '</table>';
          ?>
        </div>
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
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