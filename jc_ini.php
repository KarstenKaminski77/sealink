<?php require_once('Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('functions/functions.php');

require_once('includes/common/KT_common.php');

require_once('includes/tng/tNG.inc.php');

select_db();

$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'") or die(mysql_error());
$row = mysql_fetch_array($query);

$jobno = $row['JobNo'];

mysql_query("UPDATE tbl_jc SET JobNo = '$jobno' WHERE JobId = '$jobid'") or die(mysql_error());

$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_l = $row['SUM(Total1)'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_m = $row['SUM(Total1)'];

$query = mysql_query("SELECT SUM(Total1) FROM tbl_jc WHERE JobId = '$jobid' AND Transport = '1'") or die(mysql_error());
$row = mysql_fetch_array($query);

$subtotal_t = $row['SUM(Total1)'];

$subtotal = $subtotal_l + $subtotal_m + $subtotal_t;

mysql_query("UPDATE tbl_jc SET SubTotal = '$subtotal' WHERE JobId = '$jobid'") or die(mysql_error());

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$colname_Recordset2 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset2);
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$company = $_SESSION['company'];
$site = $_SESSION['site'];

$jobid = $_GET['Id'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = "SELECT tbl_sites.Name AS Name_1, tbl_companies.Name, tbl_sites.Company, tbl_sites.Site, tbl_sites.Address, tbl_sites.FirstName, tbl_sites.LastName, tbl_sites.Telephone, tbl_sites.Email, tbl_jc.Id, tbl_jc.JobId, tbl_jc.JobNo, tbl_jc.Date, tbl_jc.JobDescription FROM ((tbl_jc LEFT JOIN tbl_sites ON tbl_sites.Id=tbl_jc.SiteId) LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId = '$jobid' ORDER BY Id ASC LIMIT 1";
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

$KTColParam1_Recordset3 = "0";
if (isset($_GET["Id"])) {
  $KTColParam1_Recordset3 = (get_magic_quotes_gpc()) ? $_GET["Id"] : addslashes($_GET["Id"]);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = sprintf("SELECT tbl_jc.CompanyId, tbl_jc.JobId, tbl_companies.* FROM (tbl_jc LEFT JOIN tbl_companies ON tbl_companies.Id=tbl_jc.CompanyId) WHERE tbl_jc.JobId=%s ORDER BY Id ASC LIMIT 1", $KTColParam1_Recordset3);
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$colname_Recordset6 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset6 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset6 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset6);
$Recordset6 = mysql_query($query_Recordset6, $seavest) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

mysql_select_db($database_seavest, $seavest);
$query_Recordset7 = "SELECT * FROM tbl_rates";
$Recordset7 = mysql_query($query_Recordset7, $seavest) or die(mysql_error());
$row_Recordset7 = mysql_fetch_assoc($Recordset7);
$totalRows_Recordset7 = mysql_num_rows($Recordset7);

mysql_select_db($database_seavest, $seavest);
$query_Recordset8 = "SELECT * FROM tbl_fuel";
$Recordset8 = mysql_query($query_Recordset8, $seavest) or die(mysql_error());
$row_Recordset8 = mysql_fetch_assoc($Recordset8);
$totalRows_Recordset8 = mysql_num_rows($Recordset8);

mysql_select_db($database_seavest, $seavest);
$query_Recordset100 = "SELECT * FROM tbl_technicians";
$Recordset100 = mysql_query($query_Recordset100, $seavest) or die(mysql_error());
$row_Recordset100 = mysql_fetch_assoc($Recordset100);
$totalRows_Recordset100 = mysql_num_rows($Recordset100);

if(isset($_POST['complete'])){
if($_POST['complete'] == 1){
$jobid = $_GET['Id'];
$query = mysql_query("UPDATE tbl_jc SET Invoice = '1' WHERE JobId = '$jobid'") or die(mysql_error());
header('Location: jc_complete.php');
}}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
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
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#6699CC">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
        <?php
include('menu.php'); ?>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="761" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
          <tr>
            <td colspan="4" align="center" bordercolor="#9E9E9E" bgcolor="#E0E0E0"><span class="HEADER">SEAVEST AFRICA TRADING CC</span></td>
        </tr>
          <tr>
            <td width="263" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;P.O.Box 201153 Durban North. 4016</td>
          <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Tel: 031 563 7735</td>
          <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;Fax: 0865 191 153</td>
          <td width="200" bordercolor="#9E9E9E" bgcolor="#E0E0E0" class="combo">&nbsp;nicky@seavest.co.za</td>
        </tr>
		</table>
		  </form>
		  <table width="100%">
          <tr>
            <td colspan="3" class="combo"><div id="add_row" style="margin-top:4px;">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="combo_bold">
                <tr>
                  <td><form name="form2" method="post" action="jc_calc.php?Id=<?php echo $_GET['Id']; ?>">
                    &nbsp;Labour
                    <select name="labour_row" class="tarea2" id="labour_row">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    &nbsp; Materials
                    <select name="material_row" class="tarea2" id="material_row">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    &nbsp; Transport
                    <select name="transport_row" class="tarea2" id="transport_row">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    &nbsp; History 
                    <select name="comment_row" class="tarea2" id="comment_row">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                    <input name="Submit2" type="submit" class="tarea2" value="Add Rows">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                  </form></td>
                  <td width="101" align="right" valign="middle">&nbsp;</td>
                  <td width="40" align="right" valign="middle">&nbsp;</td>
                </tr>
              </table>
            </div></td>
            </tr>
			</table>
			<form name="form1" method="post" action="jc_calc.php?Id=<?php echo $jobid; ?>&update">
			  <table>
          <tr>
            <td bordercolor="#FFFFFF" class="combo"><div style="border:solid 1px #A6CAF0; margin-bottom:6px; width:759px;">
              <table width="100%" border="0" cellpadding="3" cellspacing="0" bordercolor="#0000FF">
                  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                    <td bordercolor="#0000FF" bgcolor="#FFFFFF">&nbsp;</td>
                    <td bordercolor="#0000FF" bgcolor="#FFFFFF">&nbsp;</td>
                  </tr>
                  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                    <td colspan="2" valign="top" bordercolor="#0000FF" bgcolor="#FFFFFF"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                        <td width="50%" valign="top"><span class="combo_bold" style="padding-right:10px"><?php echo $row_Recordset5['Name']; ?></span><span style="padding-right:10px"><br>
                              <span class="combo"><?php echo $row_Recordset5['Name_1']; ?><br>
                              <?php echo $row_Recordset5['Address']; ?><br>
                              <?php echo $row_Recordset5['FirstName']; ?>&nbsp; <?php echo $row_Recordset5['LastName']; ?><br>
                              <?php echo $row_Recordset5['Telephone']; ?><br>
                              <?php echo $row_Recordset5['Email']; ?> </span><br>
                          </span><br>
                        </td>
                        <td width="50%" align="right" valign="top" class="combo"><div style="padding-right:10px"><span class="combo_bold">&nbsp; Jobcard No:</span><?php echo $row_Recordset5['JobNo']; ?></div></td>
                      </tr>
                      <tr>
                        <td width="50%" class="combo_bold">&nbsp;</td>
                        <td width="50%" class="combo_bold">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2"><span class="combo_bold">&nbsp; Received Date</span>
                            <input name="date1" class="tarea" id="date1" value="<?php echo $row_Recordset6['Date1']; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                          &nbsp;&nbsp;&nbsp;&nbsp;<span class="combo_bold">Requested Completion:</span>&nbsp;
                          <input name="date2" class="tarea" id="date2" value="<?php echo $row_Recordset6['Date2']; ?>" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes">
                          &nbsp;
                          <?php
							 schedule($jobid); ?></td>
                      </tr>
                    </table></td>
                    </tr>
                  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                    <td valign="top" bordercolor="#0000FF" bgcolor="#FFFFFF">&nbsp;</td>
                    <td valign="top" bordercolor="#0000FF" bgcolor="#FFFFFF">&nbsp;</td>
                  </tr>
                    </table>
              </div>              </td>
            </tr>
          <tr>
            <td bordercolor="#FFFFFF" class="combo"><table width="760" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
              <tr>
                <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Service Requested</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td bordercolor="#FFFFFF" class="combo">
			<textarea name="service" cols="147" rows="5" class="tarea" id="service"><?php echo $row_Recordset6['JobDescription']; ?></textarea>			</td>
          </tr>
          <tr>
            <td bordercolor="#FFFFFF" class="combo"><table width="760" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
              <tr>
                <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">History</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td bordercolor="#FFFFFF" class="combo">
                      <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
if($numrows >= 2){
while($row = mysql_fetch_array($result)){

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
                      <div style=" border:solid 1px #A6CAF0; padding:5px;">
          <br>
          <div style="padding-bottom:5px;">
            <select name="day[]" class="tarea" id="day[]">
              <option value="1" selected <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\"";} ?>>1</option>
              <option value="2" <?php if (!(strcmp(2, $day))) {echo "selected=\"selected\"";} ?>>2</option>
              <option value="3" <?php if (!(strcmp(3, $day))) {echo "selected=\"selected\"";} ?>>3</option>
              <option value="4" <?php if (!(strcmp(4, $day))) {echo "selected=\"selected\"";} ?>>4</option>
              <option value="5" <?php if (!(strcmp(5, $day))) {echo "selected=\"selected\"";} ?>>5</option>
              <option value="6" <?php if (!(strcmp(6, $day))) {echo "selected=\"selected\"";} ?>>6</option>
              <option value="7" <?php if (!(strcmp(7, $day))) {echo "selected=\"selected\"";} ?>>7</option>
              <option value="8" <?php if (!(strcmp(8, $day))) {echo "selected=\"selected\"";} ?>>8</option>
              <option value="9" <?php if (!(strcmp(9, $day))) {echo "selected=\"selected\"";} ?>>9</option>
              <option value="10" <?php if (!(strcmp(10, $day))) {echo "selected=\"selected\"";} ?>>10</option>
              <option value="11" <?php if (!(strcmp(11, $day))) {echo "selected=\"selected\"";} ?>>11</option>
              <option value="12" <?php if (!(strcmp(12, $day))) {echo "selected=\"selected\"";} ?>>12</option>
              <option value="13" <?php if (!(strcmp(13, $day))) {echo "selected=\"selected\"";} ?>>13</option>
              <option value="14" <?php if (!(strcmp(14, $day))) {echo "selected=\"selected\"";} ?>>14</option>
              <option value="15" <?php if (!(strcmp(15, $day))) {echo "selected=\"selected\"";} ?>>15</option>
              <option value="16" <?php if (!(strcmp(16, $day))) {echo "selected=\"selected\"";} ?>>16</option>
              <option value="17" <?php if (!(strcmp(17, $day))) {echo "selected=\"selected\"";} ?>>17</option>
              <option value="18" <?php if (!(strcmp(18, $day))) {echo "selected=\"selected\"";} ?>>18</option>
              <option value="19" <?php if (!(strcmp(19, $day))) {echo "selected=\"selected\"";} ?>>19</option>
              <option value="20" <?php if (!(strcmp(20, $day))) {echo "selected=\"selected\"";} ?>>20</option>
              <option value="21" <?php if (!(strcmp(21, $day))) {echo "selected=\"selected\"";} ?>>21</option>
              <option value="22" <?php if (!(strcmp(22, $day))) {echo "selected=\"selected\"";} ?>>22</option>
              <option value="23" <?php if (!(strcmp(23, $day))) {echo "selected=\"selected\"";} ?>>23</option>
              <option value="24" <?php if (!(strcmp(24, $day))) {echo "selected=\"selected\"";} ?>>24</option>
              <option value="25" <?php if (!(strcmp(25, $day))) {echo "selected=\"selected\"";} ?>>25</option>
              <option value="26" <?php if (!(strcmp(26, $day))) {echo "selected=\"selected\"";} ?>>26</option>
              <option value="27" <?php if (!(strcmp(27, $day))) {echo "selected=\"selected\"";} ?>>27</option>
              <option value="28" <?php if (!(strcmp(28, $day))) {echo "selected=\"selected\"";} ?>>28</option>
              <option value="29" <?php if (!(strcmp(29, $day))) {echo "selected=\"selected\"";} ?>>29</option>
              <option value="30" <?php if (!(strcmp(30, $day))) {echo "selected=\"selected\"";} ?>>30</option><option value="31" <?php if (!(strcmp(31, $day))) {echo "selected=\"selected\"";} ?>>31</option>
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
            <input name="comment_name[]" type="text" class="tarea" id="comment_name[]" value="<?php echo $row['CommentName']; ?>" size="47">
            <select name="tech" class="tarea" id="tech" style="width:195px; height:15px">
              <option value="" <?php if (!(strcmp("", $row['FeedBackTech']))) {echo "selected=\"selected\"";} ?>>Select one...</option>
              <?php
do {  
?>
              <option value="<?php echo $row_Recordset100['Id']?>"<?php if (!(strcmp($row_Recordset100['Id'], $row['FeedBackTech']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset100['Name']?></option>
              <?php
} while ($row_Recordset100 = mysql_fetch_assoc($Recordset100));
  $rows = mysql_num_rows($Recordset100);
  if($rows > 0) {
      mysql_data_seek($Recordset100, 0);
	  $row_Recordset100 = mysql_fetch_assoc($Recordset100);
  }
?>
            </select>
<input name="delete_c[]" type="checkbox" id="delete_c[]" value="<?php echo $row['Id']; ?>">
          </div>
          <br>
          <input name="comment1" type="hidden" id="comment1" value="<?php echo $row['Id']; ?>">
                        <input name="id_c[]" type="hidden" id="id_c[]" value="<?php echo $row['Id']; ?>">
                        <textarea name="comment[]" cols="141" rows="8" class="tarea" id="textarea" type="text" value="<?php echo $row['CommentText']; ?>"><?php echo $row['CommentText']; ?></textarea>
                        <br>
                        <br>
					</div>
          <?php }} else { 

$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Comment = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
$row = mysql_fetch_array($result);

$date = $row['CommentDate'];
$name = $row['CommentName'];
$comment = $row['CommentText'];
$split = explode(" ", $date);


$day = $split[0];
$month = $split[1];
$year = $split[2];
$hour = $split[3];
$minute = $split[4];

?>
          <div style=" border:solid 1px #A6CAF0; padding:5px;">
              <div style="padding-bottom:5px;">
                <select name="day" class="tarea" id="day">
                  <option value="1" selected <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\"";} ?>>1</option>
                  <option value="2" <?php if (!(strcmp(2, $day))) {echo "selected=\"selected\"";} ?>>2</option>
                  <option value="3" <?php if (!(strcmp(3, $day))) {echo "selected=\"selected\"";} ?>>3</option>
                  <option value="4" <?php if (!(strcmp(4, $day))) {echo "selected=\"selected\"";} ?>>4</option>
                  <option value="5" <?php if (!(strcmp(5, $day))) {echo "selected=\"selected\"";} ?>>5</option>
                  <option value="6" <?php if (!(strcmp(6, $day))) {echo "selected=\"selected\"";} ?>>6</option>
                  <option value="7" <?php if (!(strcmp(7, $day))) {echo "selected=\"selected\"";} ?>>7</option>
                  <option value="8" <?php if (!(strcmp(8, $day))) {echo "selected=\"selected\"";} ?>>8</option>
                  <option value="9" <?php if (!(strcmp(9, $day))) {echo "selected=\"selected\"";} ?>>9</option>
                  <option value="10" <?php if (!(strcmp(10, $day))) {echo "selected=\"selected\"";} ?>>10</option>
                  <option value="11" <?php if (!(strcmp(11, $day))) {echo "selected=\"selected\"";} ?>>11</option>
                  <option value="12" <?php if (!(strcmp(12, $day))) {echo "selected=\"selected\"";} ?>>12</option>
                  <option value="13" <?php if (!(strcmp(13, $day))) {echo "selected=\"selected\"";} ?>>13</option>
                  <option value="14" <?php if (!(strcmp(14, $day))) {echo "selected=\"selected\"";} ?>>14</option>
                  <option value="15" <?php if (!(strcmp(15, $day))) {echo "selected=\"selected\"";} ?>>15</option>
                  <option value="16" <?php if (!(strcmp(16, $day))) {echo "selected=\"selected\"";} ?>>16</option>
                  <option value="17" <?php if (!(strcmp(17, $day))) {echo "selected=\"selected\"";} ?>>17</option>
                  <option value="18" <?php if (!(strcmp(18, $day))) {echo "selected=\"selected\"";} ?>>18</option>
                  <option value="19" <?php if (!(strcmp(19, $day))) {echo "selected=\"selected\"";} ?>>19</option>
                  <option value="20" <?php if (!(strcmp(20, $day))) {echo "selected=\"selected\"";} ?>>20</option>
                  <option value="21" <?php if (!(strcmp(21, $day))) {echo "selected=\"selected\"";} ?>>21</option>
                  <option value="22" <?php if (!(strcmp(22, $day))) {echo "selected=\"selected\"";} ?>>22</option>
                  <option value="23" <?php if (!(strcmp(23, $day))) {echo "selected=\"selected\"";} ?>>23</option>
                  <option value="24" <?php if (!(strcmp(24, $day))) {echo "selected=\"selected\"";} ?>>24</option>
                  <option value="25" <?php if (!(strcmp(25, $day))) {echo "selected=\"selected\"";} ?>>25</option>
                  <option value="26" <?php if (!(strcmp(26, $day))) {echo "selected=\"selected\"";} ?>>26</option>
                  <option value="27" <?php if (!(strcmp(27, $day))) {echo "selected=\"selected\"";} ?>>27</option>
                  <option value="28" <?php if (!(strcmp(28, $day))) {echo "selected=\"selected\"";} ?>>28</option>
                  <option value="29" <?php if (!(strcmp(29, $day))) {echo "selected=\"selected\"";} ?>>29</option>
                  <option value="30" <?php if (!(strcmp(30, $day))) {echo "selected=\"selected\"";} ?>>30</option>
                  <option value="31" <?php if (!(strcmp(31, $day))) {echo "selected=\"selected\"";} ?>>31</option>
                </select>
                <select name="month" class="tarea" id="month">
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
                <select name="year" class="tarea" id="year">
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
                <select name="hour" class="tarea" id="hour">
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
                <select name="minute" class="tarea" id="minute">
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
                <input name="comment_name" type="text" class="tarea" id="comment_name" value="<?php echo $row['CommentName']; ?>" size="47">
                <select name="tech" class="tarea" id="tech" style="width:195px; height:15px">
                  <option value="" <?php if (!(strcmp("", $row['FeedBackTech']))) {echo "selected=\"selected\"";} ?>>Select one...</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_Recordset100['Id']?>"<?php if (!(strcmp($row_Recordset100['Id'], $row['FeedBackTech']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset100['Name']?></option>
                  <?php
} while ($row_Recordset100 = mysql_fetch_assoc($Recordset100));
  $rows = mysql_num_rows($Recordset100);
  if($rows > 0) {
      mysql_data_seek($Recordset100, 0);
	  $row_Recordset100 = mysql_fetch_assoc($Recordset100);
  }
?>
                </select>
              </div>
              <textarea name="comment" cols="141" rows="8" class="tarea" id="comment" type="text"><?php echo $row['CommentText']; ?></textarea>
                      </div>
<div style="padding-bottom:5px; padding-top:5px">
              <table width="760" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
        <tr>
          <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">&nbsp;Feed Back </td>
        </tr>
                </table>
            </div>					  <?php } // close loop ?>
<div style=" border:solid 1px #A6CAF0; padding:5px;">
              <div style="padding-bottom:5px;">
                <select name="fb_day" class="tarea" id="fb_day">
                    <option value="1" selected <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\"";} ?>>1</option>
                    <option value="2" <?php if (!(strcmp(2, $day))) {echo "selected=\"selected\"";} ?>>2</option>
                    <option value="3" <?php if (!(strcmp(3, $day))) {echo "selected=\"selected\"";} ?>>3</option>
                    <option value="4" <?php if (!(strcmp(4, $day))) {echo "selected=\"selected\"";} ?>>4</option>
                    <option value="5" <?php if (!(strcmp(5, $day))) {echo "selected=\"selected\"";} ?>>5</option>
                    <option value="6" <?php if (!(strcmp(6, $day))) {echo "selected=\"selected\"";} ?>>6</option>
                    <option value="7" <?php if (!(strcmp(7, $day))) {echo "selected=\"selected\"";} ?>>7</option>
                    <option value="8" <?php if (!(strcmp(8, $day))) {echo "selected=\"selected\"";} ?>>8</option>
                    <option value="9" <?php if (!(strcmp(9, $day))) {echo "selected=\"selected\"";} ?>>9</option>
                    <option value="10" <?php if (!(strcmp(10, $day))) {echo "selected=\"selected\"";} ?>>10</option>
                    <option value="11" <?php if (!(strcmp(11, $day))) {echo "selected=\"selected\"";} ?>>11</option>
                    <option value="12" <?php if (!(strcmp(12, $day))) {echo "selected=\"selected\"";} ?>>12</option>
                    <option value="13" <?php if (!(strcmp(13, $day))) {echo "selected=\"selected\"";} ?>>13</option>
                    <option value="14" <?php if (!(strcmp(14, $day))) {echo "selected=\"selected\"";} ?>>14</option>
                    <option value="15" <?php if (!(strcmp(15, $day))) {echo "selected=\"selected\"";} ?>>15</option>
                    <option value="16" <?php if (!(strcmp(16, $day))) {echo "selected=\"selected\"";} ?>>16</option>
                    <option value="17" <?php if (!(strcmp(17, $day))) {echo "selected=\"selected\"";} ?>>17</option>
                    <option value="18" <?php if (!(strcmp(18, $day))) {echo "selected=\"selected\"";} ?>>18</option>
                    <option value="19" <?php if (!(strcmp(19, $day))) {echo "selected=\"selected\"";} ?>>19</option>
                    <option value="20" <?php if (!(strcmp(20, $day))) {echo "selected=\"selected\"";} ?>>20</option>
                    <option value="21" <?php if (!(strcmp(21, $day))) {echo "selected=\"selected\"";} ?>>21</option>
                    <option value="22" <?php if (!(strcmp(22, $day))) {echo "selected=\"selected\"";} ?>>22</option>
                    <option value="23" <?php if (!(strcmp(23, $day))) {echo "selected=\"selected\"";} ?>>23</option>
                    <option value="24" <?php if (!(strcmp(24, $day))) {echo "selected=\"selected\"";} ?>>24</option>
                    <option value="25" <?php if (!(strcmp(25, $day))) {echo "selected=\"selected\"";} ?>>25</option>
                    <option value="26" <?php if (!(strcmp(26, $day))) {echo "selected=\"selected\"";} ?>>26</option>
                    <option value="27" <?php if (!(strcmp(27, $day))) {echo "selected=\"selected\"";} ?>>27</option>
                    <option value="28" <?php if (!(strcmp(28, $day))) {echo "selected=\"selected\"";} ?>>28</option>
                    <option value="29" <?php if (!(strcmp(29, $day))) {echo "selected=\"selected\"";} ?>>29</option>
                    <option value="30" <?php if (!(strcmp(30, $day))) {echo "selected=\"selected\"";} ?>>30</option>
                  <option value="31" <?php if (!(strcmp(31, $day))) {echo "selected=\"selected\"";} ?>>31</option>
                          </select>
                <select name="fb_month" class="tarea" id="select2">
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
                <select name="fb_year" class="tarea" id="select3">
                    <option value="2008" selected <?php if (!(strcmp(2009, $year))) {echo "selected=\"selected\"";} ?>>2009</option>
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
                <select name="fb_hour" class="tarea" id="select4">
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
                <select name="fb_minute" class="tarea" id="select5">
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
  &nbsp;</div>
              <textarea name="feedback" cols="141" rows="8" class="tarea" id="feedback"><?php echo $row['FeedBack']; ?></textarea>
    <input name="id_c" type="hidden" id="id_c" value="<?php echo $row['Id']; ?>">
  </div>					</td>
          </tr>
          <tr>
            <td bordercolor="#FFFFFF" class="combo"><table width="760" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
              <tr>
                <td width="485" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Actual Work Carried Out </td>
                <td width="50" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Unit</td>
                <td width="50" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Qty.</td>
                <td width="100" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Unit Price </td>
                <td width="15" align="center" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Delete</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="combo_bold"><div style=" border:solid 1px #A6CAF0">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                <tr>
                  <td align="left" bordercolor="#A6CAF0">
                    <br> 
                    &nbsp;<span class="combo_bold">Labour</span><br />
                    
                    
                      
                      <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Labour = '1'";
$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){

$id = $row['Id'];

$query1 = "SELECT * FROM tbl_jc WHERE Id = '$id'";
$result1 = mysql_query($query1) or die(mysql_error());
$row1 = mysql_fetch_array($result1);

$rate = $row1['Price'];
$rate = explode(".", $rate);

?>
                      <div>
                         &nbsp;
                         <input name="labour[]" type="text" class="tarea" id="labour" size="91" value="<?php echo $row['Description']; ?>"> 
                        <input name="unit_l[]" type="text" class="tarea" id="unit_l" value="Hours" size="8"> 
                        <input name="qty_l[]" type="text" class="tarea" id="qty_l" value="<?php echo $row['Qty']; ?>" size="7">
                        <select name="price_l[]" class="tarea" id="price_l[]" style="width:100px;">
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset7['Rate']?>"<?php if (!(strcmp($row_Recordset7['Rate'], $rate[0]))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset7['Name']?></option>
                          <?php
} while ($row_Recordset7 = mysql_fetch_assoc($Recordset7));
  $rows = mysql_num_rows($Recordset7);
  if($rows > 0) {
      mysql_data_seek($Recordset7, 0);
	  $row_Recordset7 = mysql_fetch_assoc($Recordset7);
  }
?>
                        </select>
                        <input name="delete[]" type="checkbox" id="delete" value="<?php echo $row['Id']; ?>">
                        &nbsp;&nbsp;
                        <input name="id_l[]" type="hidden" id="id_l[]" value="<?php echo $row['Id']; ?>">
                        </div>
          <?php } // close loop ?><br>
                      <br>
                      &nbsp;<span class="combo_bold">Material</span> 
                      <?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Material = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                      <div>
                         &nbsp;
                         <input name="material[]" type="text" class="tarea" id="material" size="91" value="<?php echo $row['Description']; ?>"> 
                        <input name="unit_m[]" type="text" class="tarea" id="unit_m" value="Lot" size="8"> 
                        <input name="qty_m[]" type="text" class="tarea" id="qty_m" value="<?php echo $row['Qty']; ?>" size="7"> 
                        <input name="price_m[]" type="text" class="tarea" id="price_m" value="<?php echo $row['Price']; ?>" size="16">
                        <input name="delete_m[]" type="checkbox" id="delete[]" value="<?php echo $row['Id']; ?>">
                        &nbsp;&nbsp;
                       <input name="id_m[]" type="hidden" id="id_m[]" value="<?php echo $row['Id']; ?>">
                        </div>
          <?php } // close loop ?><br>
                      <br>
                      &nbsp;<?php
$query = "SELECT * FROM tbl_jc WHERE JobId = '$jobid' AND Transport = '1'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
?>
                      <div>
                         <span class="combo_bold">Trips to site</span>&nbsp;
                         <input name="transport[]" type="text" class="tarea" id="transport" size="10" value="<?php echo $row['Description']; ?>"> 
                        <input name="unit_t[]" type="hidden" class="tarea" id="unit_t" value="Km" size="7"> 
                        <span class="combo_bold">Return Distance</span>
                        <input name="qty_t[]" type="text" class="tarea" id="qty_t" value="<?php echo $row['Qty']; ?>" size="10">
                        <span class="combo_bold">                        km</span> 
                        <input name="price_t[]" type="hidden" class="tarea" id="price_t" value="<?php echo $row_Recordset8['Rate']; ?>" size="16">
                        <input name="id_t[]" type="hidden" id="id_t[]" value="<?php echo $row['Id']; ?>">
                        </div>
          <?php } // close loop ?><br>
                      <div class="combo_bold"><br>
                        <span style="padding-bottom:5px">
                          &nbsp;&nbsp;</span>
                        <table width="400" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="100" valign="middle"><span style="padding-bottom:5px">
                              <input name="Submit3" type="submit" class="tarea2" value="Save Jobcard" style="padding:5px">
                            </span>&nbsp;</td>
                            <td width="123" align="center" valign="middle" class="combo">Job Card Is Complete </td>
                            <td width="177" valign="middle"><input name="complete" type="checkbox" id="complete" value="1"></td>
                          </tr>
                        </table>
                        <br>
                        <br>
                      </div>                      </td>
                  </tr>
              </table>
            </div>			  </td>
          </tr>
        </table>  
		    </form>        
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset3);

mysql_free_result($Recordset6);

mysql_free_result($Recordset7);

mysql_free_result($Recordset8);

mysql_free_result($Recordset100);
?>
