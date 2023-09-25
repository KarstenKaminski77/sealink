<?php
/*
KOIVI HTML Form to FDF Parser for PHP (C) 2004 Justin Koivisto
Version 2.0
Last Modified: 2/1/2005 8:18AM

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
  <title>Tutorial: Fill in PDF Fields With Submitted Form Data</title>
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
    <h1>Tutorial: Fill in PDF Fields With Submitted Form Data</h1>
    <p>
     Due to recent popularity of <a href="./index.php">my original article</a> (and a couple donations!), I was able to find
     some extra time to write out a tutorial on how to implement my original createFDF function into your website using both
     PDF as well as HTML forms to populate your PDF documents with submitted data. In order to follow this tutorial in whole,
     you will need to have the <a href="http://www.adobe.com/products/acrobatstd/main.html">Adobe Acrobat</a> software (not
     the reader, but the full application) and a web server that supports <a href="http://www.php.net/">PHP</a>. So without
     further delay, let's get down and dirty.
    </p>
   </div>  

   <div>
    <p><a href="./index.php">Back to article</a></p>
    <h1>Submitting Data to a PDF from via HTML and/or PDF forms</h1>
    <p>
     This is a short tutorial about how to implement my original createFDF function into your website OR web application
     so you can import user-submitted data into your PDF files. The most popular reason for using a method like this is
     so the data can be represented in a standard format that is easy to comprehend. This method can be useful for things
     like employment applications where you not only accept them online, but also through the mail. A single PDF document
     allows your web users to provide you with information in a variety of ways, but all are formatted to the way yout
     Human Resources department will expect to see them.
    </p>
    <ol>
     <li>
      <h3>Create your PDF document that you want the submitted data imported to.</h3>
      <p>
       To do this, I had used Quark to create the initial document, but you can use pretty much any application to produce
       it. Since I am using Microsoft Windows, I just printed my document to the "Adobe PDF" entry in my printer control
       panel that was created when installing Acrobat. <a href="Project1.pdf">Initial PDF document</a> (5.7 KB).
      </p>
      <p>Once you have your initial PDF document ready, it is time to add fields to import the data into.</p>
      <ol>
       <li>
        <p>Open your PDF document, and select the Text Field Tool.</p>
        <p><img src="screen1.png" width="480" height="577" alt="Text Field Tool Button Location in Acrobat" /></p>
        <p>You can also access the Text Field Tool via the Tools &raquo; Advanced Editing &raquo; Forms menu.</p>
       </li>
       <li>
        <p>
         Draw your first text field. When you do, a window will pop up prompting for field information. There are
         quite a few options for each of the fields, but the only one we are concerned with for this tutorial is
         the "Name" property under the Genera; tab.
        </p>
        <p>
         Enter a name for this field name. Keep in mind that when submitted, PHP will automatically convert spaces to
         underscores when data is posted. Therefore, it's best not to use spaces in the first place (to save time later).
        </p>
        <p><img src="screen2.png" width="480" height="577" alt="Text Field option screen in Acrobat" /></p>
        <p>
         Repeat this step for the rest of your text fields. Notice that the Comments field would be a great place to use
         an HTML textarea field element. Therefore, in Acrobat's field properties, under the options tab, I have selected
         the "Multi-line" option.
        </p>
        <p><img src="screen3.png" width="480" height="577" alt="Multi-line text field option in Acrobat" /></p>
       </li>
       <li>
        <p>
         Save your PDF form. Notice that when you open it up again, it looks the same as the initial document. The only
         difference is that you can now place the cursor over the fields and actually type in information.
         <a href="Project2.pdf">PDF document with form</a> (8.2 KB).
        </p>
       </li>
      </ol>
     </li>
     <li>
      <h3>Create the form that the users will fill in to submit the data.</h3>
      <ol>
       <li>
        <h4>Using an HTML form to allow users to fill in the data.</h4>
        <p>
         Create your HTML form for your web visitors to fill out. You do not have to include all the fields that
         your PDF uses. For instance, in this tutorial, I will automatically fill in the date field when the form
         is processed. Below is an example HTML form to use with this tutorial.
        </p>
        <div class="example"><?php show_source('example_form.html'); ?></div>
        <p>Here is how that would look in your borwser:</p>
        <div><?php include 'example_form.html'; ?></div>
       </li>
       <li>
        <h4>Using a PDF form to allow users to fill in the data.</h4>
        <p>
         When using multi-page or long forms (a job application form for example), you may want to allow your visitors
         to simply fill in the PDF file to preserve formatting. You have 3 options:
        </p>
        <ol>
         <li><p>Have the users print the PDF and fill it in by hand and mail it,</p></li>
         <li><p>Have the users type in the information using the PDF, print it and mail it or</p></li>
         <li><p>Have the users type in the information using the PDF and submit it online.</p></li>
        </ol>
        <p>
         Because of the nature of PDF documents, you can be fairly certain that any of these options will produce
         similar results.  We can cover all three options without additional effort (assuming you want to be able
         to accept the submission online in the first place).
        </p>
        <p>
         <b>Create the PDF that your visitors will fill out.</b> In most cases, this will be the exact PDF that you
         created earlier with an added submit button, so let's add that.
        </p>
        <ol>
         <li><p>Open your PDF document that has the form and select the button tool.</p></li>
         <li><p>Draw the button in the document. This is what the users will click to submit the form.</p></li>
         <li><p>Select the Actions tab of the field properties.</p></li>
         <li><p>Select the Submit a Form option under the Select Action field and click the Add button.</p>
          <ul>
           <li><p>Select the HTML Export Format.</p></li>
           <li><p>Enter the URL of the processing script.</p></li>
           <li><p>Select All fields option for the Field Selection</p></li>
           <li><p>Select All fields option for the Field Selection</p></li>
           <li><p>Click OK</p></li>
          </ul>
          <p><img src="screen4.png" width="480" height="577" alt="Multi-line text field option in Acrobat" /></p>
         </li>
         <li><p>Save your visitor PDF file. <a href="Project3.pdf">PDF document with user form</a> (9.0 KB).</p></li>
        </ol>
       </li>
      </ol>
     </li>
     <li>
      <h3>Add your data-processing PHP script to handle the posted data.</h3>
      <p>
       The very first thing you will want to do is check that you are getting the data you expect when a form is submitted.
       The best way to do this is to create your processing script with the <b>only</b> following code:
      </p>
      <code class="example">&lt;?php echo '&lt;pre&gt;'; print_r($_POST); echo '&lt;/pre&gt;'; ?&gt;</code>
      <p>
       This will show you how your submitted data is coming across, field names and all. Check the field names against
       the names used in your PDF file that the data will be imported to. An example output may look like this:
      </p>
      <pre>Array
    (
        [Text2] => Justin Kovisto
        [Text3] => Purple
        [Text4] => 28
        [Text5] => This tutorial is taking more time than I thought! ;)
        [Button6] => Submit Online
    )</pre>
      <p>
       Once you have verified that the field names are the same as in your PDF file, you can start writing your processing
       script. For this example, we will simply take the submitted information and write an fdf file to the server's
       filesystem to use later. (I also have created versions of this processing script to store the FDF data in a database
       for later retrieval as well as emailing the file as an attachment.)
      </p>
      <p>
       Firstly, you only want to attempt to process if a form was posted and the information you wanted was there. Add your
       custom verification routines and errors as you see fit. I will keep this very simple (and probably not that secure)
       for illustration purposes. Here is an example processing script:
      </p>
      <pre><?php show_source('submit_form.php'); ?></pre>
      <p>You will also need the <code>createFDF.php</code> file in the same directory. This file has the following contents:</p>
      <pre><?php show_source('createFDF.php'); ?></pre>
     </li>
    </ol>
   </div>

   <div>
    <h1>Code Download</h1>
    <p>This tutorial is now included in the source code that is <a href="/fill-pdf-form-fields.zip">available for download</a> as part of the first article.</p>
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
