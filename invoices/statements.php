<?php
session_start();

require_once('../functions/functions.php');

if(isset($_GET['delete'])){
	
	$id = $_GET['delete'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_statements WHERE Id = '$id'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	
	$file = $row['FileName'];
	
	if(file_exists('fpdf16/pdf/'.$file)){
		
		unlink('fpdf16/pdf/'.$file);
		
		
	}
	
	mysqli_query($con, "DELETE FROM tbl_statements WHERE Id = '$id'")or die(mysqli_error($con));
	
	//header('Location: inv-statements.php');
}

$query_Recordset1 = "SELECT * FROM tbl_companies";
$Recordset1 = mysqli_query($con, $query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$colname_Recordset2 = $_GET['Id'];

$Recordset2 = mysqli_query($con, "SELECT * FROM tbl_statements WHERE Id = '$colname_Recordset2'") or die(mysqli_error($con));
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);

$query_Recordset3 = "
  SELECT
	  tbl_statements.Id,
	  tbl_statements.DateGenerated,
	  tbl_statements.FileName,
	  tbl_statements.NoInvoices,
	  tbl_companies.Name
  FROM
	  (
		  tbl_statements
		  LEFT JOIN tbl_companies ON tbl_companies.Id = tbl_statements.CompanyId
	  )
  ORDER BY
	  tbl_statements.Id DESC";
	  
$Recordset3 = mysqli_query($con, $query_Recordset3) or die(mysqli_error($con));
$row_Recordset3 = mysqli_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysqli_num_rows($Recordset3);

$query_Recordset4 = "SELECT Id, Name FROM tbl_companies";
$Recordset4 = mysqli_query($con, $query_Recordset4) or die(mysqli_error($con));
$row_Recordset4 = mysqli_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysqli_num_rows($Recordset4);

mysqli_select_db($database_inv, $inv);
$query_Recordset5 = "SELECT * FROM tbl_companies ORDER BY Name ASC";
$Recordset5 = mysqli_query($con, $query_Recordset5, $inv) or die(mysqli_error());
$row_Recordset5 = mysqli_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysqli_num_rows($Recordset5);

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
  
              $('.fancybox').fancybox({
			  
				  autoSize    : true, 
				  closeClick  : false, 
				  fitToView   : false, 
				  openEffect  : 'none', 
				  closeEffect : 'none', 
				  scrolling   : 'no',
				  type : 'iframe',
				  iframe : {
					  preload: false
				  }

			  });
          });
      </script>
      
   </head>
   <body>
   
      <!-- Banner -->
      <div id="logo">
         <?php logout_link(); ?>
        <div id="tab-user"><?php echo $_COOKIE['name']; ?></div>
        <?php area_select($con); ?>
      </div>
      <!-- End Banner -->
      
      <!-- Navigatiopn -->
      <?php include('../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
      <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Accounts</a></li>
            <li><a href="#">Invoices</a></li>
            <li><a href="#">Statements</a></li>
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
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="right"><form action="../fpdf16/pdf/statements-pdf.php" method="post" name="form2" id="form2">
                  </select>
                  <div id="list-border" style="margin-bottom:10px;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td class="td-header">Generate Statement</td>
                        </tr>
                      <tr>
                        <td class="td-right"><select name="companyid" class="tarea-100" id="companyid">
                          <option value="">Oil Company</option>
                          <?php
                                        do {
                                        ?>
                          <option value="<?php echo $row_Recordset5['Id']; ?>" <?php if($_POST['companyid'] == $row_Recordset5['Id']){ ?> selected="selected"<?php } ?>><?php echo $row_Recordset5['Name']; ?></option>
                          <?php
                                        } while ($row_Recordset5 = mysqli_fetch_assoc($Recordset5));
                                        $rows = mysqli_num_rows($Recordset5);
                                        if($rows > 0) {
                                        mysqli_data_seek($Recordset5, 0);
                                        $row_Recordset5 = mysqli_fetch_assoc($Recordset5);
                                        }
                                        ?>
                          </select></td>
                        </tr>
                    </table>
                  </div>
                  <input name="button2" type="submit" class="btn-new-2" id="button2" value="Generate" style="margin-bottom:15px" />
                </form>
                </td>
          </tr>
            <tr>
                <td>
                    <form name="form3" method="post" action="../fpdf16/pdf/statement-mail.php">
                        <div id="list-border">
                            <table width="100%" border="0" cellpadding="0" cellspacing="1" class="combo">
                                <tr>
                                  <td colspan="9" nowrap class="td-header">Email</td>
                              </tr>
                                <tr>
                                  <td colspan="9" nowrap class="td-right"><input name="email" type="text" class="tarea-100" id="email" value="To" onfocus="if (this.value=='To') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='To';"></td>
                                </tr>
                                <tr>
                                  <td colspan="9" nowrap class="td-right"><input name="attach" type="file" class="tarea-100" id="attach"></td>
                                </tr>
                                <tr>
                                  <td colspan="9" nowrap class="td-right"><textarea name="message" rows="5" class="tarea-100" id="message" onfocus="if (this.value=='Message') this.value='';" onblur="if (this.value.replace(/\s/g,'')=='') this.value='Message';">Message</textarea></td>
                                </tr>
                          </table>
                      </div>
                            
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="right"><input name="button3" type="submit" class="btn-new" id="button3" value="Send"></td>
                            </tr>
                            <?php if(isset($_GET['Id'])){ ?>
                            <tr>
                                <td class="KT_field_error"><strong><em><?php echo $row_Recordset2['FileName']; ?></em></strong> successfully sent.</td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                      </table>
                        <div id="list-border">
                            <table width="100%" align="center" cellpadding="3" cellspacing="1">
                                <tr class="td-header">
                                    <td width="110" align="center" nowrap><strong>Statement No.&nbsp; </strong></td>
                                    <td width="90" align="center" nowrap>Invoices</td>
                                    <td align="left"><strong>&nbsp;Company</strong></td>
                                    <td width="110" align="left"><strong>&nbsp;Date</strong></td>
                                    <td width="20" align="center" class="td-header-right">&nbsp;</td>
                                    <td width="20" align="center" class="td-header-right">&nbsp;</td>
                                  <td width="20" align="center" class="td-header-right">&nbsp;</td>
                                    <td width="20" align="center" class="td-header-right">&nbsp;</td>
                                </tr>
                                <?php do { ?>
                                <tr class="<?php echo ($ac_sw1++%2==0)?" odd ":"even "; ?>" onMouseOver="this.oldClassName = this.className; this.className='over';" onMouseOut="this.className = this.oldClassName;">
                                    <td align="center" class="combo">&nbsp;<?php echo $row_Recordset3['Id']; ?></td>
                                    <td align="center" class="combo"><?php echo $row_Recordset3['NoInvoices']; ?></td>
                                    <td class="combo">&nbsp;<?php echo $row_Recordset3['Name']; ?></td>
                                  <td class="combo">&nbsp;<?php echo $row_Recordset3['DateGenerated']; ?></td>
                                    <td align="center">
                                        <a href="../fpdf16/pdf/<?php echo $row_Recordset3['FileName']; ?>" class="icon-pdf fancybox" title="View"></a>
                                  </td>
                                    <td align="center">
                                        <a href="../fpdf16/pdf/statement-download.php?Id=<?php echo $row_Recordset3['FileName']; ?>" class="download" title="Download"></a>
                                    </td>
                                    <td align="center">
                                        <a href="statements.php?delete=<?php echo $row_Recordset3['Id']; ?>" class="delete" title="Delete"></a>
                                    </td>
                                  <td align="center"><input type="radio" name="file" id="radio" value="<?php echo $row_Recordset3['Id']; ?>"></td>
                                </tr>
                                <?php } while ($row_Recordset3 = mysqli_fetch_assoc($Recordset3)); ?>
                            </table>
                      </div>
                  </form>
                </td>
            </tr>
        </table>
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