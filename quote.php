<?php require_once('Connections/seavest.php'); ?>
<?php
session_start();

require_once('functions/functions.php');

//MX Widgets3 include
require_once('includes/wdg/WDG.php');

require_once('includes/tng/tNG.inc.php');

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
}}

if($_SESSION['kt_login_level'] == 0){

$areaid = $_SESSION['kt_AreaId'];
}

mysql_select_db($database_seavest, $seavest);
$query_Recordset1 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset1 = mysql_query($query_Recordset1, $seavest) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_seavest, $seavest);
$query_Recordset2 = "SELECT * FROM tbl_sites WHERE AreaId = '$areaid' ORDER BY Name ASC";
$Recordset2 = mysql_query($query_Recordset2, $seavest) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$query_sla_sub_cat = mysqli_query($con, "SELECT * FROM tbl_sla_subcat WHERE CompanyId = '". $_GET['Company'] ."' AND CatId = '7' ORDER BY SubCat ASC")or die(mysqli_error($con));
$sla_rows = mysqli_num_rows($query_sla_sub_cat);

///////// Getting the data from Mysql table for first list box//////////
$quer2 = mysqli_query($con, "SELECT DISTINCT Name, Id FROM tbl_companies ORDER BY Name"); 
///////////// End of query for first list box////////////

$areaid = $_SESSION['areaid'];
/////// for second drop down list we will check if category is selected else we will display all the subcategory///// 
$cat=$_GET['Company']; // This line is added to take care if your global variable is off
$quer = mysqli_query($con, "SELECT DISTINCT Name, Id FROM tbl_sites WHERE Company = '$cat' AND AreaId = '$areaid' AND Name <> '' ORDER BY Name"); 
////////// end of query for second subcategory drop down list box ///////////////////////////

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

	  <script language=JavaScript>
	  
		function reload1(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			self.location='quote.php?Company=' + val ;
		}
		
		function reload2(form){
			var val=form.company.options[form.company.options.selectedIndex].value; 
			var val2=form.site.options[form.site.options.selectedIndex].value; 
			self.location='quote.php?Company=' + val + '&Site=' + val2;
		}
		
		function reload3(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = form.site.options[form.site.options.selectedIndex].value; 
			var val3 = document.getElementById('description').value;
			self.location='quote.php?Company=' + val + '&Site=' + val2 + '&Description=' + val3;
		}
		
		function reload4(form){
			var val = form.company.options[form.company.options.selectedIndex].value; 
			var val2 = form.site.options[form.site.options.selectedIndex].value; 
			var val3 = document.getElementById('description').value;
			var val4 = form.sub_cat.options[form.sub_cat.options.selectedIndex].value; 
			self.location='quote.php?Company=' + val + '&Site=' + val2 + '&Description=' + val3 + '&SubCat=' + val4;
		}
		
      </script>

</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="200" valign="top">
            <?php include('menu.php'); ?>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </td>
        <td valign="top">
            <table width="750" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="top">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                            <tr>
                                <td colspan="3" align="center"><img src="images/banner.jpg" width="823" height="151"></td>
                            </tr>

                            <tr>
                                <td colspan="3" bordercolor="#FFFFFF" class="combo">
                                    <div id="add_row" style="background-color: #FFF; border:none; width:auto">
                                        <form name="form1" method="post" action="quote_new_process.php">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <div id="list-brdr" style="width:100%">
                                                            <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
                                                                <tr>
                                                                    <td width="140" nowrap class="td-left">Company </td>
                                                                    <td nowrap class="td-right">
                                                                        <?php
                                                                        echo "<select name='company' class='tarea-100' id='company' onchange=\"reload1(this.form)\">";
                                                                            echo "<option value=''>Oil Company...</option>";

                                                                            while($noticia2 = mysqli_fetch_array($quer2)){
																				
																				if($noticia2['Id'] == $_GET['Company'] && !empty($_GET['Company'])){
																					
																					echo '<option selected="selected" value='. $noticia2['Id'] .'>'. $noticia2['Name'] .'</option>';

																				} else {
																					
																					echo '<option value='. $noticia2['Id'] .'>'. $noticia2['Name'] .'</option>';
																				}
																			}
																			
																			echo "</select>";
                                                                        ?>
                                                                    </td>
                                                                    <td width="140" nowrap class="td-left">Site</td>
                                                                    <td nowrap class="td-right">
                                                                        <?php
                                                                        echo "<select name='site' id='site' class='tarea-100' onchange=\"reload2(this.form)\">";
                                                                            
																			echo "<option value=''>Site...</option>";

                                                                            while($noticia = mysqli_fetch_array($quer)){
																				
																				if($noticia['Id']==$_GET['Site'] && !empty($_GET['Site'])){
																					
																					echo "<option selected value='$noticia[Id]'>$noticia[Name]</option>";

																				} else {
																					
																					echo  "<option value='$noticia[Id]'>$noticia[Name]</option>";
																				}
																			}
																			
                                                                            echo "</select>";
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" class="td-left">Description</td>
                                                                    <td colspan="3" class="td-right">
                                                                        <textarea name="description" cols="40" rows="3" class="tarea-100" id="description" onchange="reload3(this.form)"><?php echo $_GET['Description']; ?></textarea>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="td-left">SLA</td>
                                                                    <td colspan="3" class="td-right">
                                                                        <select name="sub_cat" class="tarea-100" id="sub_cat" onchange="reload4(this.form)">
                                                                            <option value="">Sub Category...</option>
                                                                            <?php while($row_sla_sub_cat = mysqli_fetch_array($query_sla_sub_cat)){ ?>
                                                                            <option value="<?php echo $row_sla_sub_cat['Id']; ?>" <?php if($_GET['SubCat'] == $row_sla_sub_cat['Id']){ echo 'selected="selected"'; } ?>><?php echo $row_sla_sub_cat['SubCat']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <?php if($_GET['SubCat'] >= 1){ ?>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="right"><input name="Submit2" type="submit" class="btn-new" value="Next"></td>
                                                </tr>
                                            </table>
                                            <?php } ?>

                                        </form>
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
