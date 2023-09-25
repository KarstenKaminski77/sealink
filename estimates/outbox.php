<?php
session_start();

require_once('../Connections/seavest.php'); 
require_once('../functions/functions.php');

if(isset($_POST['master_area'])){
	
	$_SESSION['areaid'] = $_POST['master_area'];
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['areaid'];
}

$userlevel = $_SESSION['kt_login_level'];

$colname_area = $_SESSION['areaid'];

$area = mysqli_query($con, "SELECT * FROM tbl_areas WHERE Id = '$colname_area'") or die(mysql_error());
$row_area = mysqli_fetch_assoc($area);
$totalRows_area = mysqli_num_rows($area);

if($_SESSION['kt_login_level'] >= 1){
	
	$areaid = $_SESSION['areaid'];
	
} else {
	
	$areaid = $_SESSION['kt_AreaId'];
}

if($_SESSION['kt_login_level'] == 0){

$areaid = $_SESSION['kt_AreaId'];
}

if($userid == '29'){

$where = "AND tbl_qs.AreaId = '". $areaid ."' AND tbl_qs.CompanyId = '3'";

} else {
	
	$where = "AND tbl_qs.AreaId = ". $areaid ."";
}

system_select();

$sql_where = system_parameters('tbl_qs'); 

$query_Recordset3 = "SELECT tbl_qs.QuoteNo AS QuoteNo_1, tbl_qs.JobDescription, tbl_sent_quotes.QuoteNo, tbl_sent_quotes.CompanyId, tbl_qs.CompanyId AS CompanyId_1, tbl_sent_quotes.SiteId, tbl_sent_quotes.PDF, tbl_sent_quotes.DateSent, tbl_sent_quotes.Sent FROM (tbl_sent_quotes LEFT JOIN tbl_qs ON tbl_qs.QuoteNo=tbl_sent_quotes.QuoteNo) WHERE tbl_qs.Status = '1' $sql_where GROUP BY QuoteNo ORDER BY tbl_qs.Id ASC";
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

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
      
      
	  <script type="text/javascript" src="../fancyBox-2/lib/jquery-1.10.1.min.js"></script>
      <script type="text/javascript" src="../fancyBox-2/lib/jquery.mousewheel-3.0.6.pack.js"></script>
      <script type="text/javascript" src="../fancyBox-2/source/jquery.fancybox.js?v=2.1.5"></script>
      <link rel="stylesheet" type="text/css" href="../fancyBox-2/source/jquery.fancybox.css?v=2.1.5" media="screen" />
  
      <script type="text/javascript">
          $(document).ready(function() {
  
              $('.view-pdf').fancybox({
			  
				  autoSize    : true, 
				  closeClick  : false, 
				  fitToView   : true, 
				  openEffect  : 'none', 
				  closeEffect : 'none', 
				  scrolling   : 'no',
				  type : 'iframe',

			  });
  
              $('.view-photos').fancybox({
			  
				  autoSize    : true, 
				  closeClick  : false, 
				  fitToView   : true, 
				  openEffect  : 'none', 
				  closeEffect : 'none', 
				  scrolling   : 'no',
				  width		  : '900',
				  type : 'iframe',

			  });
			  
  
  
          });
      </script>
      
      <style type="text/css">
	  
	  a.add-row-inv{
		  cursor:pointer;
	  }
	  
	  a.add-row-inv:hover{
		  
		  background-position:top;
	  }
	  </style>
      
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
            <li><a href="#">Quotations</a></li>
            <li><a href="#">Outbox</a></li>
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
      <form action="../fpdf16/pdf/quote_mail.php" method="post" enctype="multipart/form-data" name="form2">
        <div id="list-border">
          <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" id="myTable">
            <tr>
              <td colspan="8" align="left" nowrap="nowrap" class="td-right">
                <input name="email[]" type="text" class="tarea-100" value="To" id="email[]" onfocus="if (this.value=='To') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='To';" />
                <script>
              function myFunction() {
                  var table = document.getElementById("myTable");
                  var row = table.insertRow(1);
                  var cell1 = row.insertCell(0);
				  cell1.className = 'td-right';
                  cell1.innerHTML = "<input name=\"email[]\" type=\"text\" class=\"tarea-100\" value=\"To\" id=\"email[]\" onFocus=\"if (this.value=='To') this.value='';\" onBlur=\"if (this.value.replace(/\s/g,'')=='') this.value='To';\" style=\"width:100%\">";
                document.getElementById('myTable').rows[1].cells[0].colSpan = 10
              }
              </script>
              </td>
              <td width="30" align="left" nowrap="nowrap" class="td-right"><a class="add-row-inv" onclick="myFunction()"></a></td>
            </tr>
            <tr>
              <td colspan="9" align="left" nowrap="nowrap" class="td-right"><input name="attach" type="file" class="tarea-100" id="attach" style="width:100%" /></td>
            </tr>
            <tr>
              <td colspan="9" align="left" nowrap="nowrap" class="td-right"><textarea name="message" rows="5" class="tarea-100" id="message" onfocus="if (this.value=='Message') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Message';" style="width:100%">Message</textarea></td>
            </tr>
            </table>
        </div>
          
         <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
          <tr>
            <td colspan="9" align="right" nowrap="nowrap"><input name="Submit2" type="submit" class="btn-new" value="Send" /></td>
          </tr>
          <tr>
            <td colspan="9" align="left" nowrap="nowrap">&nbsp;</td>
          </tr>
          </table>
         <div id="list-border">
           <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
             <tr>
               <td width="50" class="td-header"><strong>Quote </strong></td>
               <td width="150" class="td-header"><strong>Company</strong></td>
               <td width="250" class="td-header"><strong>Site Address </strong></td>
               <td width="250" class="td-header"><strong>Date</strong></td>
               <td width="80" class="td-header">Age</td>
               <td width="20" align="center" class="td-header"></td>
               <td width="20" align="center" class="td-header"></td>
               <td width="20" align="center" class="td-header"></td>
               <td width="20" align="center" class="td-header"></td>
             </tr>
             <?php do { 
		   $jobid = $row_Recordset3['JobId'];

?>
             <tr class="<?php echo ($ac_sw1++%2==0)?"odd":"even"; ?>" onmouseover="this.oldClassName = this.className; this.className='over';" onmouseout="this.className = this.oldClassName;">
               <td width="50" align="center"><div><a href="<?php echo $row_Recordset3['JobDescription']; ?>" class="menu" title="<?php echo $row_Recordset3['JobDescription']; ?>"><?php echo $row_Recordset3['QuoteNo']; ?></a>
                 <input name="qn[]" type="hidden" id="qn[]" value="<?php echo $row_Recordset3['QuoteNo']; ?>" />
               </div></td>
               <td width="150" nowrap="nowrap"><div><?php echo $row_Recordset3['CompanyId']; ?></div></td>
               <td width="250" nowrap="nowrap"><div><?php echo $row_Recordset3['SiteId']; ?></div></td>
               <td width="250"><div><?php echo $row_Recordset3['DateSent']; ?></div></td>
               <td width="80"><?php 
					$quoteno = $row_Recordset3['QuoteNo'];
					time_schedule_quotes($quoteno); ?></td>
               <td align="center"><a href="../fpdf16/pdf_quotation.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>&amp;preview" target="_blank" class="view view-pdf"></a></td>
               <td align="center"><a href="revive.php?Id=<?php echo $row_Recordset3['QuoteNo']; ?>" class="edit" onclick="return confirmSubmit()"></a></td>
               <td align="center"><a href="photos.php?Id=<?php echo $row_Recordset3['QuoteNo_1']; ?>" class="image-icon view-photos"></a></td>
               <td align="center"><input name="file[]" type="radio" id="file[]" value="<?php echo $row_Recordset3['PDF']; ?>" /></td>
             </tr>
             <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
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