<?php require_once('Connections/budget.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

mysql_select_db($database_budget, $budget);
$query_Recordset1 = "SELECT * FROM tbl_categories";
$Recordset1 = mysql_query($query_Recordset1, $budget) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_budget, $budget);
$query_Recordset2 = "SELECT * FROM tbl_budget";
$Recordset2 = mysql_query($query_Recordset2, $budget) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_budget, $budget);
$query_Recordset3 = "SELECT SUM(Bank) FROM tbl_budget";
$Recordset3 = mysql_query($query_Recordset3, $budget) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_budget, $budget);
$query_Recordset4 = "SELECT * FROM tbl_categories";
$Recordset4 = mysql_query($query_Recordset4, $budget) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

session_start();

require_once('functions.php');

select_db();

$budgetid = $_GET['Id'];

if(isset($_POST['delete'])){
$id = $_POST['id'];
$delete = $_POST['delete'];

foreach($delete as $c){

mysql_query("DELETE FROM tbl_budget WHERE Id = '$c'") or die(mysql_error());
}}


if($_POST['rows'] >= 1){

$rows = $_POST['rows'];

for($i=0;$i<=$rows;$i++){
$date = date('d M Y');
$budgetid = $_GET['Id'];

mysql_query("INSERT INTO tbl_budget (Date,BudgetId) VALUES ('$date','$budgetid')") or die(mysql_error());
}}

if(isset($_POST['id'])){
$id_b = $_POST['id'];
$day_b = $_POST['day'];
$month_b = $_POST['month'];
$year_b = $_POST['year'];
$type_b = $_POST['type'];
$description_b = $_POST['description'];
$amount_b = $_POST['amount'];
$currency_b = $_POST['currency'];
$budgetid_b = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_budget WHERE BudgetId = '$budgetid'")or die(mysql_error());
$row = mysql_fetch_array($query);
$numrows = mysql_num_rows($query);

for($i=0;$i<$numrows;$i++){
$id = $id_b[$i];
$day = $day_b[$i];
$monthb = $month_b[$i];
$year = $year_b[$i];
$date = $day_b[$i] ." ". $month_b[$i] ." ". $year_b[$i];
$type = $type_b[$i];
$description = $description_b[$i];
$amount = $amount_b[$i];
$currency = $currency_b[$i];
if($currency == "1"){
$currency_type = "Bank = ". $amount .", Transaction = 1";
} elseif($currency == "2"){
$currency_type = "Cash = ". $amount .", Transaction = 2";
}
$searchdate = date('Y m d',strtotime($date));
$budgetid = $budgetid_b;

mysql_query("UPDATE tbl_budget SET Date = '$date', Description = '$description', Type = '$type', Total = '$amount', $currency_type, SearchDate = '$searchdate', BudgetId = '$budgetid' WHERE Id = '$id'") or die(mysql_error());
}}

$query = mysql_query("SELECT * FROM tbl_budget WHERE  BudgetId = '$budgetid'")or die(mysql_error());
$row = mysql_fetch_array($query);
$cash_total = $row['CashTotal'];
$bank_total = $row['BankTotal'];
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
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
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top" bgcolor="#006699">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>
	  <?php include('menu.php'); ?>
	</p>
	</td>
    <td valign="top"><div style="padding-left:2px; border:solid 1px #cccccc; margin:2px">
      <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EEEEEE" class="combo_bold">
        <tr>
          <td width="17%" nowrap><form name="form2" method="post" action="select.php?Id=<?php echo $budgetid; ?>">
            <select name="rows" class="tarea" id="rows">
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              </select>
            <input name="Submit2" type="submit" class="combo_bold_btn" value="Add Rows">
          </form></td>
            <td width="25%" nowrap><form name="form3" method="post" action="select.php?Id=<?php echo $budgetid; ?>">
Group By
<select name="where" class="tarea" id="where">
                <option>Select one...</option>
                <option value="1">Date</option>
                <option value="2">Type</option>
              </select>
                        <input name="Submit3" type="submit" class="combo_bold_btn" value="Go">
            </form>            </td>
            <td width="32%" valign="top" nowrap class="combo_bold">
			<span style="color:<?php echo colour_bank(); ?>">Bank Balance R <?php echo $row['BankTotal']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:<?php echo colour_cash(); ?>">Cash Balance R<?php echo $row['CashTotal']; ?> </span></td>
            <td width="26%" valign="top" nowrap class="combo_bold"><form action="" method="post" name="form4" onSubmit="MM_openBrWindow('balances.php','','width=250,height=100')">
              <input name="Submit4" type="submit" class="combo_bold_btn" value="Edit Balances">
                                    </form>
            </td>
        </tr>
      </table>
    </div>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<?php
	if(isset($_POST['where']) && ($_POST['where'] == 1)){ ?>
	<form name="form1" method="post" action="select.php?filter&Id=<?php echo $budgetid; ?>">
	  <table border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td><input name="date1" class="tarea" id="date1" value="" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:mondayfirst="true" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes"></td>
          <td><input name="date2" class="tarea" id="date2" value="" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:mondayfirst="true" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes"></td>
          <td><input name="Submit5" type="submit" class="combo_bold_btn" value="Go">
            <input name="where" type="hidden" id="where" value="1"></td>
        </tr>
      </table>
      </form>
	<p>&nbsp;</p>
	<?php } ?>
	<?php
	if(isset($_POST['where']) && ($_POST['where'] == 2)){ ?>
	<form name="form1" method="post" action="select.php?filter&Id=<?php echo $budgetid; ?>">
	  <table border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td><select name="cat" class="tarea" id="cat">
            <?php
do {  
?>
            <option value="<?php echo $row_Recordset4['Id']?>"><?php echo $row_Recordset4['Category']?></option>
            <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
          </select>
</td>
          <td><input name="Submit5" type="submit" class="combo_bold_btn" value="Go">
            <input name="where" type="hidden" id="where" value="2"></td>
        </tr>
      </table>
      </form>
	<p>&nbsp;</p>
	<?php } ?>
      <form name="form1" method="post" action="select.php?Id=<?php echo $budgetid; ?>">
        <p>
          <?php
echo $_COOKIE['budgetid'];
if(isset($_POST['where']) && ($_POST['where'] == 1)){
if(isset($_POST['date1'])){
$date1 = $_POST['date1'];
$date_1 = date('Y m d', strtotime($date1));
$date2 = $_POST['date2'];
$date_2 = date('Y m d', strtotime($date2));
$where = "WHERE SearchDate >= '". $date_1 ."' AND SearchDate <= '". $date_2 ."' AND  BudgetId = '$budgetid'";
} if(isset($_POST['where']) && ($_POST['where'] == 2)){
$cat = $_POST['cat'];
$where = "WHERE Type = '". $cat ."' AND  BudgetId = '$budgetid'";
} else { 
$where = "WHERE 1 = 1 AND  BudgetId = '$budgetid'";
}
$query = "SELECT * FROM tbl_budget $where" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());

} else {
$query = "SELECT * FROM tbl_budget WHERE  BudgetId = '$budgetid'" or die(mysql_error());
$result = mysql_query($query) or die(mysql_error());
}

if(!isset($where)){
$where = "WHERE '1 = 1'";
}
$query4 = "SELECT SUM(Total) FROM tbl_budget $where" or die(mysql_error());
$result4 = mysql_query($query4) or die(mysql_error());
$row4 = mysql_fetch_array($result4);
$total = $row4['SUM(Total)'];

while($row = mysql_fetch_array($result)){

$date = $row['Date'];

$split = explode(" ", $date);
$year = $split[2];
$month = $split[1];
$day = $split[0];
?>
        </p>
        <div>
          <table border="0" cellspacing="0" cellpadding="10">
            <tr>
              <td nowrap><select name="day[]" class="tarea" id="day[]">
                              <option value="1" selected="selected" <?php if (!(strcmp(1, $day))) {echo "selected=\"selected\"";} ?>>1</option>
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
                              <option value="2009" selected="selected" <?php if (!(strcmp(2009, $year))) {echo "selected=\"selected\"";} ?>>2009</option>
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
              </select></td>
              <td><select name="type[]" class="tarea" id="type[]">
                <?php
do {  
?>
                <option value="<?php echo $row_Recordset1['Id']?>"<?php if (!(strcmp($row_Recordset1['Id'], $row['Type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['Category']?></option>
                <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
              </select></td>
              <td><input name="description[]" type="text" class="tarea" id="material" size="60" value="<?php echo $row['Description']; ?>" /></td>
              <td><input name="amount[]" type="text" class="tarea" id="amount[]" value="<?php echo $row['Total']; ?>" size="15"></td>
              <td><select name="currency[]" class="tarea" id="currency[]">
                <option value="1" <?php if (!(strcmp(1, $row['Transaction']))) {echo "selected=\"selected\"";} ?>>Bank</option>
                <option value="2" <?php if (!(strcmp(2, $row['Transaction']))) {echo "selected=\"selected\"";} ?>>Cash</option>
              </select>
              </td>
              <td><input name="delete[]" type="checkbox" id="delete[]" value="<?php echo $row['Id']; ?>" />
                  <input name="id[]" type="hidden" id="id[]" value="<?php echo $row['Id']; ?>" /></td>
            </tr>
          </table>
        </div>
        <?php } // close loop ?>
        <div id="line">
          <table border="0" cellspacing="0" cellpadding="10">
            <tr>
              <td nowrap>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td nowrap class="combo_bold">R<?php echo $row4['SUM(Total)']; ?></td>
            </tr>
          </table>
        </div>
        <table width="100" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td><input name="Submit" type="submit" class="combo_bold" value="Save"></td>
          </tr>
        </table>
      </form>      <p>&nbsp;</p>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset4);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
