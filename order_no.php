<?php
$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');
?><?php
require_once('functions/functions.php');

select_db();

if(isset($_POST['orderno'])){
	
	$orderno = $_POST['orderno'];
	$jobid = $_GET['Id'];

	mysql_query("UPDATE tbl_jc SET RefNo = '$orderno' WHERE JobId = '$jobid'")or die(mysql_error());

	if(!empty($_POST['inv_date'])){
		
		$date = $_POST['inv_date'];
		$date = date('d M Y',strtotime($date));
		$jobid = $_GET['Id'];
		
		mysql_query("UPDATE tbl_jc SET InvoiceDate = '$date' WHERE JobId = '$jobid'") or die(mysql_error());
	}
	
	header('Location: fpdf16/test.php?order&Id='. $jobid);
}
$jobid = $_GET['Id'];

$query = mysqli_query($con, "SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysqli_error($con));
$row = mysqli_fetch_array($query);

$inv_date = date('Y-m-d', strtotime($row['InvoiceDate']));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<form id="form1" name="form1" method="post" action="order_no.php?Id=<?php echo $_GET['Id']; ?>">
  <br />
  <table border="0" align="center" cellpadding="2" cellspacing="3">
    <tr>
      <td align="left" class="btn-blue-generic">Order&nbsp;No</td>
      <td><input name="orderno" type="text" class="tarea2" id="orderno" size="40" value="<?php echo $row['RefNo']; ?>" /></td>
    </tr>
    <tr>
      <td align="left" class="btn-blue-generic">Inv Date</td>
      <td align="right"><input name="inv_date" class="tarea2" id="inv_date" size="40" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:mondayfirst="true" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" value="<?php echo $inv_date; ?>" /></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="right"><input name="Submit" type="submit" class="tarea2" value="Submit" /></td>
    </tr>
  </table>
</form>
</body>
</html>
