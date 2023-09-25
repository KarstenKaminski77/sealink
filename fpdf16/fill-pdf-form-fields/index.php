<?php
/*
KOIVI HTML Form to FDF Parser for PHP (C) 2004 Justin Koivisto
Version 2.1
Last Modified: 2005-04-21

    This library is free software; you can redistribute it and/or modify it
    under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or (at
    your option) any later version.

    This library is distributed in the hope that it will be useful, but
    WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
    or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public
    License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with this library; if not, write to the Free Software Foundation,
    Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA 

    Full license agreement notice can be found in the LICENSE file contained
    within this distribution package.

    Justin Koivisto
    justin.koivisto@gmail.com
    http://koivi.com
*/

    include dirname(__FILE__).'/pdf_process.php';
    echo '<?xml version="1.0" encoding="iso-8859-1"?>',"\n";    // because short_open_tags = On causes a parse error.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Using HTML forms to fill in PDF fields with PHP and FDF</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="description" content="A PHP solution to filling a PDF file's form fields with data from a submitted HTML form." />
  <style type="text/css">
   @import url(http://koivi.com/styles.css);
  </style>
  <!--[if lt IE 7]><script src="/ie7/ie7-standard.js" type="text/javascript"></script><![endif]-->
 
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-207722-1";
urchinTracker();
</script>
 </head>


 <body>
  <div id="container">
   <div id="intro">
    <h1>Using HTML forms to fill in PDF fields with PHP and FDF</h1>
    <p>Using a simple HTML form and a small PHP function, it is possible to fill in the fields of a PDF form file for viewing without having to install any PHP libraries or modules. This is accomplished by using a pre-made PDF file with all the form fields necessary and FDF files.</p>
    <p>By taking values provided via a GET or POST web request from an HTML form or hyperlink, we can generate an FDF file that, when opened, will provide the values to fill the PDF form fields in the original document.</p>
    <h1>Tutorial</h1>
    <p>I have now posted a <a href="./tutorial.php">tutorial</a> on how to use the programming found in this article for those of you who need just a little extra help implementing this.</p>
   </div>  

<?php
    if(isset($CREATED)){
?>
   <div id="pageResults">
    <h1>Your Result</h1>
    <p>You can now <a href="<?php echo str_replace(dirname(__FILE__).'/','',$fdf_file) ?>">download</a> the file you just created. When downloaded, open the FDF file with your Adobe Acrobat or Acrobat Reader program to view the original PDF document with the fields filled in with the information you posted through the form below.</p>
   </div>
<?php
    }
?>
  
   <div>
    <h1>Creating the PDF file</h1>
    <p>First, you need to have created your <a href="test.pdf">PDF form file</a>. I do this using the form tools in Adobe Acrobat. Remember to name each field in your document, you will need those names later when you create the HTML form.</p>
   </div>

   <div>
    <h1>Creating the HTML form</h1>
    <p>Next, you need to create the form fields to fill out in an HTML form. Below is an example that fits with the above PDF file. Note that the field names in your HTML form must match the field names in your PDF file.</p>
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
     <table cellpadding="3" cellspacing="0" border="0">
      <tr><td>Name</td><td><input type="text" name="__NAME__" value="<?php if(isset($_POST['__NAME__'])) echo $_POST['__NAME__']; ?>" /></td></tr>
      <tr><td>Title</td><td><input type="text" name="__TITLE__" value="<?php if(isset($_POST['__TITLE__'])) echo $_POST['__TITLE__']; ?>" /></td></tr>
      <tr><td>Email</td><td><input type="text" name="__EMAIL__" value="<?php if(isset($_POST['__EMAIL__'])) echo $_POST['__EMAIL__']; ?>" /></td></tr>
      <tr><td>Address</td><td><input type="text" name="__ADDRESS__" value="<?php if(isset($_POST['__ADDRESS__'])) echo $_POST['__ADDRESS__']; ?>" /></td></tr>
      <tr><td>City</td><td><input type="text" name="__CITY__" value="<?php if(isset($_POST['__CITY__'])) echo $_POST['__CITY__']; ?>" /></td></tr>
      <tr><td>State</td><td><input type="text" name="__STATE__" value="<?php if(isset($_POST['__STATE__'])) echo $_POST['__STATE__']; ?>" /></td></tr>
      <tr><td>Zip</td><td><input type="text" name="__ZIP__" value="<?php if(isset($_POST['__ZIP__'])) echo $_POST['__ZIP__']; ?>" /></td></tr>
      <tr><td>Phone</td><td><input type="text" name="__PHONE__" value="<?php if(isset($_POST['__PHONE__'])) echo $_POST['__PHONE__']; ?>" /></td></tr>
      <tr><td>Fax</td><td><input type="text" name="__FAX__" value="<?php if(isset($_POST['__FAX__'])) echo $_POST['__FAX__']; ?>" /></td></tr>
     </table>
     <input type="submit" />
    </form>
   </div>

   <div>
    <h1>Processing the POST request</h1>
    <p>When the form is submitted, the $_POST array is passed to a function along with the location of the PDF file. The function returns the contents of an FDF file. This can them be used to write an FDF file to download an view.</p>
   </div>    
    
   <div>
    <h1>The code behind this</h1>
    <div class="example"><?php highlight_file(dirname(__FILE__).'/createFDF.php'); ?></div>
    <h2>Code for using XFDF formatted data</h2>
    <div class="example"><?php highlight_file(dirname(__FILE__).'/createXFDF.php'); ?></div>
   </div>

   <div>
    <h1>Code Download</h1>
    <p>Because of the number of requests I have received for help getting this to work or copies of the code and PDF files, I have created a ZIP archive of this entire directory and am making it <a href="/fill-pdf-form-fields.zip">available for download</a> (~180K - updated 2006-09-08).</p>
    <p>Inside this zip file you will also find a file named <code>process-xfdf.php</code> which contains sample code for sending results as an email attachment as well as sending the output directly to the browser.</p>
   </div>

   <div>
    <h1>Updates</h1>
    <ul>
     <li>
      <p><b>2009-07-15</b> It has been a few years since I posted this and nearly as long since I have used it. However, I still get emails about this all the time. Apparently the programming is still useful for people to store the form data. I did receive an email about a month or so ago about a rogue line of code in the createXFDF function that was left over from the createFDF version of the function. Thank you AndreasDo&ouml;ler for bringing this to my attention. I have now updated the function and download appropriately.</p>
     </li>
     <li>
      <p><b>2006-09-08</b> For those of you having problems with using PDF files created from Acrobat 7 or Designer, you'll be please to hear that I have an update for you.</p>
      <p>The problems were stemming from the way that the latest Acrobat, Acrobat Reader and Designer handle form data. Instead of using the previous FDF format, these programs are now defaulting to the newer XFDF file format. This is basically an XML-like file structure to define the values that would be filled into the fields.</p>
     </li>
     <li>
      <p><b>2005-09-12</b> In the move from my last web server, I found that some of my files were corrupted, and this particular demonstrations was not working correctly. That should be all fixed up now.</p>
      <p>Also, I did update the createFDF function a little as well. It turned out that Acrobat 7 was having problems importing data into the PDF form if there were spaces in the field definitions inside the FDF file. I have removed those spacer, and tested on a couple simple files. I will test on a better file with more options when I have a chance.</p>
     </li>
     <li>
      <p>
       <b>2005-04-21</b> - Thanks to Adrian James for submitting information about the FDF format of a multiple value selection field
       (<code>&lt;select multiple="multiple"&gt;</code> HTML equivalent). The createFDF function has now been updated to support these fields as well.
      </p>
     </li>
    </ul>
   </div>

<script type="text/javascript"><!--
google_ad_client = "pub-6879264339756189";
google_alternate_ad_url = "http://koivi.com/google_adsense_script.html";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text_image";
google_ad_channel ="7653137181";
google_color_border = "6E6057";
google_color_bg = "DFE0D0";
google_color_link = "313040";
google_color_url = "0000CC";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
  </div>

<?php include_once 'site_menu.php'; ?>

 </body>
</html>