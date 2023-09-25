<?php
/*
KOIVI HTML Form to XFDF Parser for PHP (C) 2004 Justin Koivisto
Version 1.0 - customized
Last Modified: 2006-08-25

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

    require_once 'createXFDF.php';
    $mailto='you@example.com';
    $subject='Submitted Form Data';
    $from='webform@example.com';
    $pdf_file='http://example.com/from.pdf';
    
    // set $redirect to '' to just output the fdf data to the browser, or give a URL for a
    // thank-you type of page
    $redirect='';
    
    // set $file_directory to '' to skip saving the file to server
    $file_directory=dirname(__FILE__).'/results';
    
    $message=<<<EndMessage
The XFDF creation script on your website has received data. Save the
attachment in this message to your hard disk. Open the PDF file and
use the import form data option under the File menu. Some setups allow
you to simply double-click the .xfdf file.

-- 
createXFDF PHP script by Justin Koivisto
http://koivi.com/fill-pdf-form-fields/
EndMessage;

    /**
     * fields accepted by this script
     */
    $text_fields=array(
    	'field1',
    	'field2',
    	'field3',
    	'field4',
    	'field5',
    	'field6',
    	'field7',
    );

    /**
     * ony text fields are checked in this script as no other type was provided in the html form
     * -justin 8/25
     */
    
    if(isset($_POST['submit'])){
		unset($_POST['submit']); // don't need to process the submit button
    	
		foreach($_POST as $k=>$v){
			if(in_array($k,$text_fields)){
				// this field was an "accepted" field
				// verify contents (these are single lines, so no \r or \n)
				if(preg_match('`[\r\n]+`',$v)){
					$clean[$k]=''; // bad data - send empty
				}else{
					$clean[$k]=$v;
				}
			}else{
				// bad field option, we do nothing with this
				unset($_POST[$k]);
			}
		}
		
        // get the XFDF file contents
        $xfdf=createXFDF($pdf_file,$clean);

        // this seems to be the most popular request, so we will mail the xfdf to an account
        
        // this is the file attachment's name - you may want to customize this to fit your needs.
	    $fileattname = "{$clean['Text3']}-{$clean['Text1']}.xfdf";
	    $fileatttype = "application/vnd.adobe.xfdf"; 
	    $data=chunk_split(base64_encode($xfdf));
	    $mime_boundary = '==Multipart_Boundary_x'.md5(time()).'x'; 
	    $headers = "From: $from\n".
	    	"MIME-Version: 1.0\n".
	        "Content-Type: multipart/mixed;\n".
	        " boundary=\"{$mime_boundary}\"";
	    $message = "This is a multi-part message in MIME format.\n\n".
	        "--{$mime_boundary}\n".
	        "Content-Type: text/plain; charset=\"iso-8859-1\"\n".
	        "Content-Transfer-Encoding: 7bit\n\n".
	        $message."\n\n".
	        "--{$mime_boundary}\n".
	        "Content-Type: {$fileatttype};\n".
	        " name=\"{$fileattname}\"\n".
	        "Content-Disposition: attachment;\n".
	        " filename=\"{$fileattname}\"\n".
	        "Content-Transfer-Encoding: base64\n\n".
	        $data."\n\n".
	        "--{$mime_boundary}--\n";
        if(!mail($mailto,$subject,$message,$headers)){
        	// mail failed!
        	mail(
        		$mailto,
        		'ERROR in '.__FILE__,
        		'Unable to send xfdf file via attachment. Data follows:'."\n----- Begin -----\n$xfdf\n----- End -----\n"
    		);
        }
        
		$file_name=time().'-'.$fileattname;
    	if(strlen($file_directory) && file_exists($file_directory) & is_writeable($file_directory)){
    		// if we have defined a writable directory on the server, write the results there as well
    		$target=$file_directory.'/'.$file_name;
    		if($fp=fopen($target,'w')){
    			fwrite($fp,$xfdf,strlen($xfdf));
    			fclose($fp);
    			
    			// mail notification of file creation
    			mail($mailto,'XFDF file saved to server',$target);
    		}else{
    			// can't open the file for writing...
    			mail($mailto,'XFDF file cannot be saved',"File cannot be written to server: $target\n");
    		}
    	}else if(strlen($file_directory)){
    		// can't use this directory - exists on server? writeable by web server process?
    		mail($mailto,'XFDF file directory cannot be used',"File cannot be written to server directory: $file_directory\n");
    	}
    	
    	if(strlen($redirect)){
    		// success - redirect if a redirect url is provided
    		header('Location: '.$redirect);
    		exit;
    	}else{
			// if no redirect, we want to just send the data to the browser
			
            // send the XFDF headers for the browser
            header('Content-type: application/vnd.adobe.xfdf');
            header('Content-Disposition: attachment; filename="'.$file_name.'"');
            echo $xfdf;
    	}
    }else{
    	die('This script is meant to be used in conjunction with the web forms on our website only.');
    }
?>
