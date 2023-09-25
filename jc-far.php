<?php require_once('Connections/seavest.php'); ?>
<?php
session_start();

require_once('functions/functions.php');

select_db();

$jobid = $_GET['Id'];

$query = mysql_query("SELECT * FROM tbl_jc WHERE JobId = '$jobid'")or die(mysql_error());
$row = mysql_fetch_array($query);

$siteid = $row['SiteId'];

$query = mysql_query("SELECT * FROM tbl_jc_mobile WHERE SiteId = '$siteid'")or die(mysql_error());
$numrows = mysql_num_rows($query);

if($numrows == 0){
mysql_query("INSERT INTO tbl_jc_mobile (SiteId) VALUES ('$siteid')")or die(mysql_error());
}

if(isset($_POST['Submit2'])){

mysql_query("INSERT INTO tbl_jc_mobile (SiteId) VALUES ('$siteid')")or die(mysql_error());
}

if(isset($_POST['cell'])){

$cell = $_POST['cell'];
$count = count($_POST['cell']);
$id = $_POST['id'];

for($i=0;$i<$count;$i++){

$cell_1 = $cell[$i];
$id_1 = $id[$i];

mysql_query("UPDATE tbl_jc_mobile SET SiteId = '$siteid', Mobile = '$cell_1' WHERE Id = '$id_1'")or die(mysql_error());
}}
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('includes/tng/tNG.inc.php');

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if($_SESSION['kt_login_level'] == 1){
if(isset($_SESSION['areaid'])){
$areaid = $_SESSION['areaid'];
} else {
$areaid = 1;
}} else {
$areaid = $_SESSION['kt_AreaId'];
$_SESSION['areaid'] = $areaid;
}
$where = "AreaId = ". $areaid ."";

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites WHERE $where";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT * FROM tbl_far_high_risk_classification";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = "SELECT * FROM tbl_far_risc_classification";
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

$colname_Recordset5 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset5 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset5 = sprintf("SELECT * FROM tbl_jc WHERE JobId = %s", $colname_Recordset5);
$Recordset5 = mysql_query($query_Recordset5, $seavest) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

select_db();

if(!empty($_POST['risk'])){

$jobno = $row_Recordset5['JobNo'];
$risk = $_POST['risk'];
$type = $_POST['type'];

mysql_query("INSERT INTO tbl_far (JobNo,RiskType,RiskClassification) VALUES ('$jobno','$risk','$type')")or die(mysql_error());

header('Location: jc_calc.php?Id='. $jobid .'');
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/fonts.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/JSRecordset.js"></script>
<script type="text/javascript" src="includes/wdg/classes/DependentDropdown.js"></script>
<?php
//begin JSRecordset
$jsObject_Recordset2 = new WDG_JsRecordset("Recordset2");
echo $jsObject_Recordset2->getOutput();
//end JSRecordset
?>
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
<style type="text/css">
<!--
.td {   border: 1px solid #000066;
}
-->
</style>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>
      <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td colspan="3" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
              </tr>
          
          <tr>
            <td colspan="3"><div id="add_row">
              <form name="form1" method="post" action="jc-far.php?Id=<?php echo $_GET['Id']; ?>">
                <table border="0" align="center" cellpadding="2" cellspacing="3">
				<?php
				$query = mysql_query("SELECT * FROM tbl_jc_mobile WHERE SiteId = '$siteid' ORDER BY Id ASC")or die(mysql_error());
				while($row = mysql_fetch_array($query)){
				
				$id = $row['Id'];
				$mobile = $row['Mobile'];
				?>
                  <tr>
                    <td class="combo_bold">Risk Classification</td>
                    <td width="150">
                      <select name="risk" class="tarea" id="risk" style="width:100%">
                        <option value=" ">Select one...</option>
                        <?php
do {  
?>
                        <option value="<?php echo $row_Recordset4['Id']?>"><?php echo $row_Recordset4['Risk']?></option>
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
                  </tr>
				  <?php } ?>
                  <tr>
                    <td class="combo_bold">High risk classification</td>
                    <td width="150" align="right">
                      <select name="type" class="tarea" id="type" style="width:100%">
                        <option value=" ">Select one...</option>
                        <?php
do {  
?>
                        <option value="<?php echo $row_Recordset3['Id']?>"><?php echo $row_Recordset3['Risk']?></option>
                        <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="right">&nbsp;   
					<input name="Next" type="submit" class="tarea2" id="Next" value="Next" />
                      <br></td>
                  </tr>
                  </table>
                    </form>
              </div></td>
              </tr>
          </table></td>
        </tr>
    </table></td></tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);
?>
