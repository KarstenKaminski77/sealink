<?php require_once('Connections/seavest.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('includes/tng/tNG.inc.php');

require_once('functions/functions.php');

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if(isset($_POST['master_area'])){
$_SESSION['areaid'] = $_POST['master_area'];
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = "-1";
if (isset($_SESSION['areaid'])) {
  $colname_area = (get_magic_quotes_gpc()) ? $_SESSION['areaid'] : addslashes($_SESSION['areaid']);
}
mysql_select_db($database_seavest, $seavest);
$query_area = sprintf("SELECT * FROM tbl_areas WHERE Id = %s", $colname_area);
$area = mysql_query($query_area, $seavest) or die(mysql_error());
$row_area = mysql_fetch_assoc($area);
$totalRows_area = mysql_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
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
$query_Recordset2 = "SELECT * FROM tbl_sites WHERE $where ORDER BY Name ASC";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$query_engineers = mysqli_query($con, "SELECT * FROM tbl_engineers ORDER BY Name ASC")or die(mysqli_error($con));

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
            <td colspan="3"><div id="add_row" style="margin-top:30px">
			<?php if($_SESSION['areaid'] != 0){ ?>
              <form action="new_process.php" method="post" enctype="multipart/form-data" name="form1">
                <table border="0" align="center" cellpadding="2" cellspacing="3">
                  <tr>
                    <td colspan="5" align="center" class="combo_bold">
					<?php if(isset($_GET['duplicate'])){ ?>
					<br>
					<span class="form_validation_field_error_error_message">					Duplicate Jobcard Entered!</span><br>					<?php } ?>					</td>
                        </tr>
                  <tr>
                    <td width="80" nowrap class="combo_bold">Company                      </td>
                        <td width="200" nowrap class="combo_bold"><select name="company" class="tarea-100per" id="company">
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset1['Id']?>"><?php echo $row_Recordset1['Name']?></option>
                          <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                        </select></td>
                        <td nowrap class="combo_bold">&nbsp;</td>
                        <td width="80" nowrap class="combo_bold">Site</td>
                        <td width="200" nowrap class="combo_bold"><select name="site" class="tarea-100per" id="site" wdg:subtype="DependentDropdown" wdg:type="widget" wdg:recordset="Recordset2" wdg:displayfield="Name" wdg:valuefield="Id" wdg:fkey="Company" wdg:triggerobject="company">
                        </select></td>
                  </tr>
                  <tr>
                    <td width="80" class="combo_bold">Job Number </td>
                    <td width="200"><input name="jobnumber" type="text" class="tarea-100per" id="jobnumber" size="10" style="cursor: text"></td>
                    <td>&nbsp;</td>
                    <td width="80"><span class="combo_bold">Reference</span></td>
                    <td width="200"><select name="reference" class="tarea-100per" id="reference">
                        <option value="">Select an Engineer</option>
                        <?php while($row_engineers = mysqli_fetch_array($query_engineers)){ ?>
                        <option value="<?php echo trim($row_engineers['Name']); ?>" <?php if($row_engineers['Name'] == $row_Recordset5['Reference']){ echo 'selected="selected"'; } ?>><?php echo $row_engineers['Name']; ?>
                          <?php } ?>
                        </select>
                    </td>
                  </tr>
                  <tr>
                    <td width="80" class="combo_bold">PDF</td>
                    <td width="200"><input name="pdf" type="file" class="tarea-100per" id="pdf" size="27"></td>
                    <td>&nbsp;</td>
                    <td width="80">&nbsp;</td>
                    <td width="200">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="combo_bold">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><input name="Submit" type="submit" class="btn-blue-generic" value="Next"></td>
                  </tr>
                  </table>
                    </form>
              <div align="center">
                  <?php } else {  // if isset areaid?>
                <span align="center" class="form_validation_field_error_error_message">Please select a region!</span>
                  <?php } ?> 
                
                </div>
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
?>
