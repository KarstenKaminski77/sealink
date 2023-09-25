<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
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

<style type="text/css">
.td-header {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 30px;
	font-weight: bold;
	text-transform: capitalize;

	background: #18519b; /* Old browsers */
	background: -moz-linear-gradient(top,  #18519b 0%, #3c87c4 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#18519b), color-stop(100%,#3c87c4)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #18519b 0%,#3c87c4 100%); /* W3C */

	padding-left: 10px;
	color: #FFF;
	padding-top: 0px;
	padding-right: 10px;
	padding-bottom: 0px;
}
.td-header-right {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 30px;
	font-weight: bold;
	text-transform: capitalize;
	background: #18519b; /* Old browsers */
	background: -moz-linear-gradient(top,  #18519b 0%, #3c87c4 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#18519b), color-stop(100%,#3c87c4)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #18519b 0%,#3c87c4 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #18519b 0%,#3c87c4 100%); /* W3C */
	padding-left: 0px;
	color: #FFF;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
}

.td-sub-header {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 30px;
	font-weight: bold;
	text-transform: capitalize;
	background: #77a0c7; /* Old browsers */
	padding-left: 10px;
	color: #FFF;
	padding-top: 0px;
	padding-right: 10px;
	padding-bottom: 0px;
	border: 1px solid #6391BE;
}

.td-sub-header2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 30px;
	font-weight: bold;
	text-transform: capitalize; /* Old browsers */
	padding-left: 10px;
	color: #FFF;
	padding-top: 0px;
	padding-right: 10px;
	padding-bottom: 0px;
	border: 1px solid #6391BE;
	background-color: #4272a0;
}

.td-sub-sub-header {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 30px;
	font-weight: bold;
	text-transform: capitalize; /* Old browsers */
	padding-left: 10px;
	color: #477AAD;
	padding-top: 0px;
	padding-right: 10px;
	padding-bottom: 0px;
	background-color: #bdd1e5;
	border: 1px solid #ABC5DE;
}

.td-sub-sub-sub-header {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 30px;
	font-weight: bold;
	text-transform: capitalize; /* Old browsers */
	padding-left: 10px;
	color: #477AAD;
	padding-top: 0px;
	padding-right: 10px;
	padding-bottom: 0px;
	background-color: #dbe8f4;
	border: 1px solid #bdd1e5;
}

.td-right {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: normal;
	/* [disabled]text-transform: capitalize; */
	color: #818284;
	border: 1px solid #DFDFDF;
	padding: 5px;
	background-color: #FFF;
}

a.expand {
	background-image:url(../images/icons/expand.png);
	width:20px;
	height:20px;
	display:block;
	border:none;
	overflow:hidden;
	margin-left:auto;
	margin-right:auto;
}

</style>
<?php 
session_start();

require_once('../Connections/inv.php'); 

$_SESSION['balance'] = '18:00:00';
$_SESSION['month'] = '';

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
		echo '<td colspan="6" class="td-sub-header" width="855px"><b>' . date('M', strtotime($row_log['Date'])) .' Total: '. $row_total['Hrs'] .'</b></td>';
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