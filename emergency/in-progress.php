<?php
$con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333');

require_once('../functions/functions.php');

$form_data = array(
	'Closed' => '1',
);


////////////////////
/// UPDATE AREA ///
//////////////////

if(isset($_GET['Close'])){
	
	dbUpdate('tbl_emergency', $form_data, $where_clause="Id = '". $_GET['Close'] ."'", $con);
	
	header('Location: '. $_SERVER['HTTP_REFERER']);
	
}


//////////////////////////////
/// UPDATE POPULATE QUERY ///
////////////////////////////

$id = $_GET['Id'];

$query_form = mysqli_query($con, "SELECT * FROM tbl_emergency_cells WHERE Id = '$id'")or die(mysqli_error($con));
$row_form = mysqli_fetch_array($query_form);

$query_list = "
	SELECT
		tbl_sites.Id,
		tbl_sites.AreaId,
		tbl_sites.Company,
		tbl_sites.`Name`,
		tbl_areas.Area,
		tbl_companies.`Name` AS CompanyName,
		tbl_emergency.Id AS EmergencyId,
		tbl_emergency.Description,
		tbl_emergency.Requestor,
		tbl_emergency.Cell,
		tbl_emergency.Email,
		tbl_emergency.DateSubmitted
	FROM
		tbl_sites
	INNER JOIN tbl_areas ON tbl_sites.AreaId = tbl_areas.Id
	INNER JOIN tbl_companies ON tbl_sites.Company = tbl_companies.Id
	INNER JOIN tbl_emergency ON tbl_emergency.SiteId = tbl_sites.Id
	WHERE tbl_emergency.Closed = '0'
	ORDER BY tbl_emergency.Id DESC";
	
$query_list = mysqli_query($con, $query_list)or die(mysqli_error($con));
$row_list = mysqli_fetch_array($query_list);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://www.interaktonline.com/MXWidgets">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SEAVEST AFRICA TRADING CC</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/fonts.css" rel="stylesheet" type="text/css">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".toggler").click(function(e){
		e.preventDefault();
		$('.order'+$(this).attr('view')).toggle();
	});
});
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" valign="top"><?php include('../menu.php'); ?></td>
    <td valign="top"><table width="823" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center"><table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="200" colspan="4" align="center"><img src="../images/banner.jpg" width="823" height="151"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <form name="form2" method="post" action="" style="margin-left:30px">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><div id="list-brdr" style="display:block">
                  <table width="100%" cellpadding="1" cellspacing="1">
                    <?php if(mysqli_num_rows($query_list) >= 1){ ?>
                    <tr>
                      <td width="250" align="left" class="td-header-cell">Oil Company</td>
                      <td width="178" align="left" class="td-header-cell">Site</td>
                      <td width="75" align="left" class="td-header-cell">Area</td>
                      <td width="75" align="left" class="td-header-cell">Date</td>
                      <td width="25" align="right" class="td-header">&nbsp;</td>
                      <td width="25" align="right" class="td-header">&nbsp;</td>
                    </tr>
                    <?php $x = 0; ?>
                    <?php while($row_list = mysqli_fetch_array($query_list)){ ?>
                    <?php $x++; ?>
                    <tr class="<?php echo ($ac_sw1++%2==0)?"even":"odd"; ?>" onMouseOver="this.oldClassName = this.className; this.className='list-over';" onMouseOut="this.className = this.oldClassName;">
                      <td align="left" class="td-data-cell"><?php echo $row_list['CompanyName']; ?></td>
                      <td align="left" class="td-data-cell"><?php echo $row_list['Name']; ?></td>
                      <td align="left" class="td-data-cell"><?php echo $row_list['Area']; ?></td>
                      <td align="left" class="td-data-cell"><?php echo date('Y-m-d', strtotime($row_list['DateSubmitted'])); ?></td>
                      <td align="center"><a href="in-progress.php?Close=<?php echo $row_list['EmergencyId']; ?>"><img src="../images/icons/btn-tick.png" width="25" height="25"></a></td>
                      <td align="center"><a href=""class="toggler" view="<?php  echo $x; ?>"><img src="../images/icons/btn-information.png" title="Edit" width="25" height="25" border="0" /></a></td>
                    </tr>
                    
						 <?php
                         if(isset($_POST['save-'.$x])){
                             
                             $display = 'table-row';
                             
                         } else {
                             
                             $display = 'none';
                         }
                         
                         $id = $row_list['EmergencyId'];
						 
						 $query_sub = "
							SELECT
								tbl_sites.Id,
								tbl_sites.AreaId,
								tbl_sites.Company,
								tbl_sites.`Name`,
								tbl_areas.Area,
								tbl_companies.`Name` AS CompanyName,
								tbl_emergency.Id AS EmergencyId,
								tbl_emergency.Description,
								tbl_emergency.Requestor,
								tbl_emergency.Cell,
								tbl_emergency.Email
							FROM
								tbl_sites
							INNER JOIN tbl_areas ON tbl_sites.AreaId = tbl_areas.Id
							INNER JOIN tbl_companies ON tbl_sites.Company = tbl_companies.Id
							INNER JOIN tbl_emergency ON tbl_emergency.SiteId = tbl_sites.Id
							WHERE
								tbl_emergency.Id = '$id'";
	                         
                         $query_sub = mysqli_query($con, $query_sub)or die(mysqli_error($con));
                         $row_sub = mysqli_fetch_array($query_sub);
                         ?>
                           
                    <tr class="order<?php  echo $x; ?>" style="display:<?php echo $display; ?>">
                      <td align="left" class="tarea-em"><?php echo $row_sub['Requestor']; ?></td>
                      <td align="left" class="tarea-em"><?php echo $row_sub['Email']; ?></td>
                      <td colspan="2" align="left" class="tarea-em"><?php echo $row_sub['Cell']; ?></td>
                      <td colspan="2" align="right" class="tarea-em">&nbsp;</td>
                      </tr>
                    <tr class="order<?php  echo $x; ?>" style="display:<?php echo $display; ?>">
                      <td colspan="4" align="left" class="td-data-cell td-right"><?php echo $row_sub['Description']; ?></td>
                      <td colspan="2" align="right" class="td-right">&nbsp;</td>
                      </tr>
                    <?php }} ?>
                  </table>
                </div></td>
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