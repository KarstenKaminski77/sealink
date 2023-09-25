<?php require_once('Connections/seavest.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');
?>
<?php
require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}

function lastday($month = '', $year = '') {
   if (empty($month)) {
      $month = date('m');
   }
   if (empty($year)) {
      $year = date('Y');
   }
   $result = strtotime("{$year}-{$month}-01");
   $result = strtotime('-1 second', strtotime('+1 month', $result));
   echo date('Y m d', $result);
}

$areaid = $_SESSION['kt_AreaId'];

if(isset($_POST['actual_qty'])){

$id = $_SESSION['kt_AreaId'];

$query = mysql_query("SELECT * FROM tbl_tool_relation WHERE AreaId = '$id'")or die(mysql_error());
$numrows = mysql_num_rows($query);

$actual_qty = $_POST['actual_qty'];
$comments = $_POST['comments'];
$record_id = $_POST['tool_id'];

for($i=0;$i<$numrows;$i++){

$qty = $actual_qty[$i];
$reason = $comments[$i];

$toolid = $record_id[$i];
$areaiid = $_SESSION['kt_AreaId'];

select_db();

$query = mysql_query("SELECT * FROM tbl_tool_relation WHERE AreaId = '$areaid'")or die(mysql_error());
$row = mysql_fetch_array($query);

$old_date = $row['Date'];
$date = explode(" ", $old_date);

$year = $date[0];
$month = $date[1];

lastday($month = '', $year = '');

mysql_query("UPDATE tbl_tool_relation SET CurrentQty = '$qty', Comments = '$reason', Date = '$date' WHERE Id = '$toolid'")or die(mysql_error());
}}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$areaid = $_SESSION['kt_AreaId'];

$query = mysql_query("SELECT * FROM tbl_technicians WHERE AreaId = '$areaid' AND Manager = '0'")or die(mysql_error());
$row = mysql_fetch_array($query);

$id = $_SESSION['kt_AreaId'];

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_technicians.Id AS Id_1, tbl_tool_relation.Id AS Id_2, tbl_technicians.Manager, tbl_technicians.Name, tbl_tool_relation.Qty, tbl_tool_relation.Comments, tbl_tool_relation.CurrentQty, tbl_tool_relation.AreaId, tbl_tools.Tool, tbl_tools.Id FROM ((tbl_technicians LEFT JOIN tbl_tool_relation ON tbl_tool_relation.ManagerId=tbl_technicians.Id) LEFT JOIN tbl_tools ON tbl_tools.Id=tbl_tool_relation.ToolId) WHERE tbl_technicians.Manager=0 AND tbl_tool_relation.AreaId = '$id' ORDER BY tbl_technicians.Name ASC";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

function tools($id,$qty,$current,$tool){
if($qty == $current){
echo '<img src="images/check.jpg" width="15" height="15">';
} else {
echo '<img src="images/no.jpg" border="0" width="15" height="15">';
}}

$colname_Recordset4 = "-1";
if (isset($_GET['tool'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['tool'] : addslashes($_GET['tool']);
}
mysql_select_db($database_seavest, $seavest);
$query_Recordset4 = sprintf("SELECT * FROM tbl_tool_relation WHERE Id = %s", $colname_Recordset4);
$Recordset4 = mysql_query($query_Recordset4, $seavest) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);
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
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top">
        <?php
include('menu.php'); ?>    </td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#FFFFFF">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
		
          <form name="form2" method="post" action="tools_update.php">
		<div style="padding-left:25px">
          <table border="1" cellpadding="2" cellspacing="3" bordercolor="#FFFFFF">

            <tr>
              <td colspan="5" bordercolor="#FFFFFF" class="combo_bold">&nbsp;</td>
              </tr>
            <tr>
              <td width="100" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Tool</td>
              <td width="60" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Initial Qty </td>
              <td width="60" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Actual Qty </td>
              <td width="60" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Comments</td>
              <td class="combo_bold">&nbsp;</td>
            </tr>
            <?php do { ?>
                <?php 
// Show If Field Has Changed (region1)
if (tNG_fieldHasChanged("region1", $row_Recordset3['Name'])) {
?>
                  <tr>
                    <td colspan="4" bordercolor="#000066" bgcolor="#000066" class="combo"><span class="combo_bold style1"><?php echo $row_Recordset3['Name']; ?></span></td>
                    <td class="combo">&nbsp;</td>
                  </tr>
                  <?php } 
// EndIf Field Has Changed (region1)
?>
                <tr>
                  <td width="100" class="combo"><?php echo $row_Recordset3['Tool']; ?></td>
                <td width="60" class="combo"><?php echo $row_Recordset3['Qty']; ?></td>
                <td width="60" class="combo"><input name="actual_qty[]" type="text" class="tarea" id="actual_qty[]" value="<?php echo $row_Recordset3['CurrentQty']; ?>" size="5"></td>
                <td width="60" class="combo"><input name="comments[]" type="text" class="tarea" id="comments[]" value="<?php echo $row_Recordset3['Comments']; ?>" size="70">
                  <input name="tool_id[]" type="hidden" id="tool_id[]" value="<?php echo $row_Recordset3['Id_2']; ?>"></td>
                <td class="combo">
				<?php 
				$id = $_GET['Id'];
				$tool = $row_Recordset3['Id'];
				$qty = $row_Recordset3['Qty'];
                $current = $row_Recordset3['CurrentQty'];
				tools($id,$qty,$current,$tool); ?></td>
                </tr>

              <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
            <tr>
              <td colspan="4" align="right" bordercolor="#000066" bgcolor="#000066" class="combo_bold"><span class="combo">
                <input name="Submit2" type="submit" class="tarea2" value="Update">
              </span></td>
              <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="combo_bold">&nbsp;</td>
            </tr>
            <tr>
              <td align="right" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
              <td align="right" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
              <td align="right" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
              <td align="right" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
              <td align="right" bordercolor="#FFFFFF" class="combo">&nbsp;</td>
            </tr>
            </table> 
		  </div>         
          </form>
          <p>&nbsp;</p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>
