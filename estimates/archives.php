<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

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
$areaid = $_SESSION['areaid'];
} else {
$areaid = $_SESSION['kt_AreaId'];
}

$userid = $_SESSION['kt_login_id'];

if($userid == '29'){

$where = "AND tbl_qs.AreaId = '". $areaid ."' AND tbl_qs.CompanyId = '3'";

} else {
	
	$where = "AND tbl_qs.AreaId = ". $areaid ."";
}

$maxRows_Recordset3 = $_SESSION['max_rows_nav_Recordset3'];
$pageNum_Recordset3 = 0;
if (isset($_GET['pageNum_Recordset3'])) {
  $pageNum_Recordset3 = $_GET['pageNum_Recordset3'];
}
$startRow_Recordset3 = $pageNum_Recordset3 * $maxRows_Recordset3;

system_select();

$sql_where = system_parameters('tbl_qs'); 

$query_Recordset3 = mysqli_query($con, "SELECT tbl_qs.QuoteNo AS QuoteNo_1, tbl_qs.AreaId, tbl_qs.JobDescription, tbl_qs.Type, tbl_sent_quotes.QuoteNo, tbl_sent_quotes.CompanyId, tbl_sent_quotes.SiteId, tbl_sent_quotes.PDF, tbl_sent_quotes.DateSent, tbl_sent_quotes.Sent FROM (tbl_sent_quotes LEFT JOIN tbl_qs ON tbl_qs.QuoteNo=tbl_sent_quotes.QuoteNo) WHERE Status = '3' $where $sql_where GROUP BY QuoteNo ORDER BY tbl_qs.Id DESC") or die(mysqli_error($con));
$row_Recordset3 = mysql_fetch_assoc($Recordset3);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../css/fonts.css" rel="stylesheet" type="text/css" />
      <link href="../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../menu/script.js"></script>
      
      <script type="text/javascript">
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
      </script>
      
      <!-- Fancybox -->
      <script type="text/javascript" src="../fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <script type="text/javascript" src="../fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
      
      <link rel="stylesheet" type="text/css" href="../fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />
      
      <script type="text/javascript">
          $(document).ready(function() {
  
              $('.history').fancybox({
              
                  autoSize    : false, 
                  closeClick  : false, 
                  fitToView   : true, 
                  openEffect  : 'none', 
                  closeEffect : 'none',
				  width		  : '800',
				  height	  : '190',		 
                  type : 'iframe',
              });
			  
          });
      </script>
      <!-- End Fancybox -->
      
   </head>
   <body>
   
      <!-- Banner -->
      <div id="logo">
         <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
        <?php system_dd($con); ?>
      </div>
      <!-- End Banner -->
      
      <!-- Navigatiopn -->
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Estimates</a></li>
            <li><a href="#">Quotes</a></li>
            <li><a href="#">Archives</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="search.php">
          <input name="search-field" type="text" class="search-top" id="search-field" placeholder="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
        <form action="fpdf16/pdf/quote_resend_mail.php" method="post" enctype="multipart/form-data" name="form2" id="form2">
          <div id="list-border">
            <table width="100%" border="0" cellpadding="0" cellspacing="1">
              <tr>
                <td class="td-right"><input name="email" type="text" class="tarea-100" id="email" value="To" onfocus="if (this.value=='To') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='To';" style="width:100%" /></td>
              </tr>
              <tr>
                <td class="td-right"><input name="attach" type="file" class="tarea-100" id="attach" style="width:100%" /></td>
              </tr>
              <tr>
                <td valign="top" class="td-right"><textarea name="message" rows="5" class="tarea-100" id="message" onfocus="if (this.value=='Message') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:100%">Message</textarea></td>
              </tr>
            </table>
          </div>
          
          <table width="100%" border="0" cellpadding="0" cellspacing="1">
            <tr>
              <td align="right"><input name="Submit2" type="submit" class="btn-new" value="Send" /></td>
            </tr>
          </table>

          <div id="list-border" style="margin-top:20px">
            <table border="0" align="center" cellpadding="3" cellspacing="1">
              <tr>
                <td width="60" align="left" nowrap="nowrap" class="td-header"><strong>Quote </strong></td>
                <td width="160" align="left" class="td-header"><strong>Company</strong></td>
                <td width="260" align="left" class="td-header"><strong>Site Address </strong></td>
                <td align="left" class="td-header"><strong>Date</strong></td>
                <td align="left" class="td-header">&nbsp;</td>
                <td align="left" class="td-header-right">&nbsp;</td>
                <td align="left" class="td-header-right">&nbsp;</td>
                <td align="left" class="td-header-right">&nbsp;</td>
                <td align="left" class="td-header-right">&nbsp;</td>
              </tr>
              <?php do { ?>
              <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
                <td width="60"><div style="padding-left:5px"><a href="<?php echo $row_Recordset3['JobDescription']; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['QuoteNo']; ?></a></div></td>
                <td width="160" class="combo"><?php echo $row_Recordset3['CompanyId']; ?></td>
                <td width="260" class="combo"><?php echo $row_Recordset3['SiteId']; ?></td>
                <td width="150" class="combo"><?php echo date('Y-m-d', strtotime($row_Recordset3['DateSent'])); ?></td>
                <?php
							$type = $row_Recordset3['Type'];
							if($type == "Accepted"){
							$colour = "#006600";
							} else {
							$colour = "#FF0000";
							}
							?>
                <td width="100" class="combo"><div style="color:<?php echo $colour; ?>"><strong><?php echo $row_Recordset3['Type']; ?></strong></div></td>
                <td width="25"><a href="fpdf16/pdf/<?php echo $row_Recordset3['PDF']; ?>" target="_blank" class="view"></a></td>
                <td width="25" align="right"><a onclick="return confirmSubmit()" href="revive.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>" class="edit"></a></td>
                <td width="25" align="right"><a href="history.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>" class="icon-info history"></a></td>
                <td width="25" align="right" colspan="2"><input name="file[]" type="radio" id="file[]" value="<?php echo $row_Recordset3['PDF']; ?>" /></td>
              </tr>
              <?php } while ($row_Recordset3 = mysqli_fetch_assoc($query_Recordset3)); ?>
            </table>
          </div>
        </form>
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../support/index.php"><img src="../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
</body>
</html>
<?php 
  mysqli_close($con); 
  mysqli_free_result($query);
  mysqli_free_result($query_areas);
  mysqli_free_result($query_list);
  mysqli_free_result($query_form);
  mysqli_free_result($query_user_menu);
?>