<?php require_once('Connections/seavest.php'); ?>
<?php
require_once('includes/tng/tNG.inc.php');

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');


$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysql_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);


$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysqli_query($con, $query_Recordset2) or die(mysql_error());
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$areaid = $_SESSION['kt_AreaId'];

$query = mysqli_query($con, "SELECT * FROM tbl_tool_relation WHERE AreaId = '$areaid'")or die(mysql_error());
$row = mysqli_fetch_array($query);

$date1 = $row['Date'];
$date = explode("-", $date1);

$year = $date[0];
$month = $date[1];
$day = $date[2];

$current_year = date('Y');
$current_month = date('m');
$current_day = date('d');

$past_date = mktime(0,0,0,$month,$day,$year);

$current_date = mktime(0,0,0,$current_month,$current_day,$current_year);

$difference =  floor(($past_date - $current_date)/86400);

if($difference == 0){
$warning =  "Your tool report is due today";
} elseif(($difference <= 3) && ($difference >= 1)){
$warning =  $difference ." days to go untill your tool report is due...";
} elseif($difference <= -1){
$overdue = $difference * -1;
$warning =  "<span style=\"color: #FF0000\">Your tool report is ". $overdue ." days overdue...</span>";
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
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
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="styles/layout.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#6699CC">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
          <tr>
            <td colspan="4" align="center" bordercolor="#9E9E9E" bgcolor="#E0E0E0"><span class="HEADER">SEAVEST AFRICA TRADING CC</span></td>
            </tr>
            <tr>
              <td width="263" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;P.O.Box 201153 Durban North. 4016</td>
              <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Tel: 031 563 7735</td>
              <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Fax: 0865 191 153</td>
              <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;nicky@seavest.co.za</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center">
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <div style="padding-left:2px; border:solid 1px #cccccc; margin:2px; width:400px"><table width="400" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE" class="combo_bold">
        <tr>
          <td align="center" nowrap>
		  <br>
		  <?php echo $warning; ?><br>
		  <br></td>
          </tr>
		  <tr>
		    <td align="center"><form name="form1" method="post" action="tools_update.php">
<br>
<input name="Submit" type="submit" class="form_validation_field_error_error_message" value="Submit Tool Report">
<br>
<br>
                                                            </form>
		    </td>
		  </tr>
      </table>
    </div>		  
	
	</td>
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
