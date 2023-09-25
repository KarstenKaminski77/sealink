<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Asset Management</title>
      
      <link href="../../css/layout.css" rel="stylesheet" type="text/css" />
      <link href="../../css/breadcrumbs.css" rel="stylesheet" type="text/css" />
      
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        
      <link rel="stylesheet" href="../../menu/styles.css">
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
      <script src="../../menu/script.js"></script>
      
	  <script type="text/javascript" src="../../tinymce/js/tinymce/tinymce.min.js"></script>
	  <script type="text/javascript">
      tinymce.init({
          mode : "specific_textareas",
          editor_selector : "mceEditor",
          theme: "modern",
		  statusbar: false,
          plugins: [
              "advlist autolink lists link image charmap print preview anchor",
              "searchreplace visualblocks code fullscreen",
              "insertdatetime media table contextmenu paste"
          ],
          toolbar: "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
      });
      

        function MM_jumpMenu(targ,selObj,restore){ //v3.0
          eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
          if (restore) selObj.selectedIndex=0;
        }
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
      <?php include('../../menu/menu.php'); ?>
      <!-- End Navigation -->
      
      <!-- Breadcrumbs -->
   <div class="td-bread">
         <ul class="breadcrumb">
            <li><a href="#">Seavest Asset Management</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Signatures</a></li>
            <li></li>
         </ul>
      </div>      
      <!-- End Breadcrumbs -->      
      
      <!-- Search -->
      <div class="search-container">
        <form id="form1" name="form1" method="post" action="">
          <input name="textfield" type="text" class="search-top" id="textfield" value="Search..." />
          <input name="button" type="submit" class="search-top-btn" id="button" value="" />
        </form>
      </div>
      <!-- End Search -->
      
      <!-- Main Form -->
      <div id="main-wrapper">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="sub_header">Email Signature Installation</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="sub_header">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      </table>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="90%"><p><strong>Windows 7</strong></p>
              <ol>
                <li>Click <strong>Start</strong>. </li>
                <li>Next to the <strong>Shut down</strong> button, in the <strong>Search programs and files</strong> box, type <strong>%APPDATA%\Microsoft\Signatures</strong> and then press Enter.</li>
                <li>Paste the zip file in the folder that opens, right click the zip folder and click &quot;<strong>Extract Here</strong>&quot;</li>
            </ol></td>
            <td width="10%" valign="top"><a href="files/Signatures.rar"><img src="../../images/icons/zip.jpg" width="50" height="46" /></a></td>
          </tr>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><p><strong>Windows Vista</strong>    </p>
              <ol>
                <li>Click <strong>Start</strong>. </li>
                <li>Next to the <strong>Shut Down</strong> button, in the <strong>Search</strong> box, type <strong>%APPDATA%\Microsoft\Signatures </strong>and then press Enter.</li>
                <li>Paste the zip file in the folder that opens, right click the zip folder and click &quot;<strong>Extract Here</strong>&quot;.</li>
            </ol></td>
            <td width="10%" valign="top"><a href="files/Signatures.rar"><img src="../../images/icons/zip.jpg" width="50" height="46" /></a></td>
          </tr>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td><p><strong>Windows XP</strong>    </p>
              <ol>
                <li>Click <strong>Start</strong>, click <strong>Run</strong>, type <strong>%APPDATA%\Microsoft\Signatures</strong> and then press Enter. </li>
                <li>Paste the zip file in the folder that opens, right click the zip folder and click &quot;<strong>Extract Here</strong>&quot;.</li>
            </ol></td>
            <td width="10%" valign="top"><a href="files/Signatures.rar"><img src="../../images/icons/zip.jpg" width="50" height="46" /></a></td>
          </tr>
        </table>
      </div>
      <!-- End Main Form -->
      
      <!-- Footer -->
   <div id="footer"><a href="../../support/index.php"><img src="../../images/KWD-SS.png" width="200" height="24" /></a></div>
      <!-- End Footer -->
      
</body>
</html>
<?php 
  mysqli_close($con); 
?>