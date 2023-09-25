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

mysql_select_db($database_seavest, $seavest);
$query_Recordset3 = "SELECT tbl_technicians.Id AS Id_1, tbl_tool_relation.Id AS Id_2, tbl_technicians.Manager, tbl_technicians.Name, tbl_tool_relation.Qty, tbl_tool_relation.Comments, tbl_tool_relation.CurrentQty, tbl_tool_relation.AreaId, tbl_tools.Tool, tbl_tools.Id FROM ((tbl_technicians LEFT JOIN tbl_tool_relation ON tbl_tool_relation.ManagerId=tbl_technicians.Id) LEFT JOIN tbl_tools ON tbl_tools.Id=tbl_tool_relation.ToolId) WHERE tbl_technicians.Manager=0 AND tbl_tool_relation.AreaId = '$areaid'";
$Recordset3 = mysql_query($query_Recordset3, $seavest) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

function tools($id,$qty,$current,$tool){
if($qty == $current){
echo '<img src="images/check.jpg" width="15" height="15">';
} else {
echo '<a href="tools.php?Id='.$id.'&tool='.$tool.'"><img src="images/no.jpg" border="0" width="15" height="15"></a>';
}}

$colname_Recordset4 = "-1";
if (isset($_GET['Id'])) {
  $colname_Recordset4 = (get_magic_quotes_gpc()) ? $_GET['Id'] : addslashes($_GET['Id']);
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
include('menu.php'); ?>
      </td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
		<div style="padding-left:25px">
          <table border="1" cellpadding="2" cellspacing="3" bordercolor="#FFFFFF">

            <tr>
              <td colspan="4" bordercolor="#FFFFFF" class="combo_bold">&nbsp;</td>
              </tr>
            <tr>
              <td width="100" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Tool</td>
              <td width="60" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Initial Qty </td>
              <td width="60" bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">Actual Qty </td>
              <td bordercolor="#68A4E6" bgcolor="#A6CAF0" class="combo_bold">&nbsp;</td>
            </tr>
            <?php do { ?>
                <?php 
// Show If Field Has Changed (region1)
if (tNG_fieldHasChanged("region1", $row_Recordset3['Name'])) {
?>
                  <tr>
                      <td colspan="4" bordercolor="#000066" bgcolor="#000066" class="combo"><span class="combo_bold style1"><?php echo $row_Recordset3['Name']; ?></span></td>
                  </tr>
                  <?php } 
// EndIf Field Has Changed (region1)
?>
                <tr>
                  <td width="100" class="combo"><?php echo $row_Recordset3['Tool']; ?></td>
                <td width="60" class="combo"><?php echo $row_Recordset3['Qty']; ?></td>
                <td width="60" class="combo"><?php echo $row_Recordset3['CurrentQty']; ?></td>
                <td class="combo">
				<?php
				$id = $row_Recordset3['Id_2'];
				$tool = $row_Recordset3['Id'];
				$qty = $row_Recordset3['Qty'];
                $current = $row_Recordset3['CurrentQty'];
				tools($id,$qty,$current,$tool); ?></td>
                </tr>

              <?php } while ($row_Recordset3 = mysql_fetch_assoc($Recordset3)); ?>
            <tr>
              <td height="29" colspan="4" bordercolor="#FFFFFF" class="combo_bold">&nbsp;</td>
              </tr>
			  </table>
			  <table>
            <tr>
              <td colspan="4" bordercolor="#FFFFFF" class="combo"><?php echo $row_Recordset4['Comments']; ?></td>
            </tr>
            <tr>
          </table> 
		  </div>         
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
