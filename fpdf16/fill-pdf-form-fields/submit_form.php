<?php
    // check that a form was submitted
    if(isset($_POST) && is_array($_POST) && count($_POST)){
        // we will use this array to pass to the createFDF function
        $data=array();
        
        // This displays all the data that was submitted. You can
        // remove this without effecting how the FDF data is generated.
        echo'<pre>POST '; print_r($_POST);echo '</pre>';

        if(isset($_POST['Text2'])){
            // the name field was submitted
            $pat='`[^a-z0-9\s]+$`i';
            if(empty($_POST['Text2']) || preg_match($pat,$_POST['Text2'])){
                // no value was submitted or something other than a
                // number, letter or space was included
                die('Invalid input for Text2 field.');
            }else{
                // if this passed our tests, this is safe
                $data['Text2']=$_POST['Text2'];
            }
            
            if(!isset($_POST['Text3'])){
                // Why this? What if someone is spoofing form submissions
                // to see how your script works? Only allow the script to
                // continue with expected data, don't be lazy and insecure ;)
                die('You did not submit the correct form.');
            }
            
            // Check your data for ALL FIELDS that you expect, ignore ones you
            // don't care about. This is just an example to illustrate, so I
            // won't check anymore, but I will add them blindly (you don't want
            // to do this in a production environment).
            $data['Text3']=$_POST['Text3'];
            $data['Text4']=$_POST['Text4'];
            $data['Text5']=$_POST['Text5'];
            
            // I wanted to add the date to the submissions
            $data['Text1']=date('Y-m-d H:i:s');
            
            // if we got here, the data should be valid,
            // time to create our FDF file contents
            
            // need the function definition
            require_once 'createFDF.php';
            
            // some variables to use
            
            // file name will be <the current timestamp>.fdf
            $fdf_file=time().'.fdf';
            
            // the directory to write the result in
            $fdf_dir=dirname(__FILE__).'/results';
            
            // need to know what file the data will go into
            $pdf_doc='http://localhost/inv/fpdf16/fill-pdf-form-fields/Project2.pdf';
            
            // generate the file content
            $fdf_data=createFDF($pdf_doc,$data);

            // this is where you'd do any custom handling of the data
            // if you wanted to put it in a database, email the
            // FDF data, push ti back to the user with a header() call, etc.

            // write the file out
            if($fp=fopen($fdf_dir.'/'.$fdf_file,'w')){
                fwrite($fp,$fdf_data,strlen($fdf_data));
                echo $fdf_file,' written successfully.';
            }else{
                die('Unable to create file: '.$fdf_dir.'/'.$fdf_file);
            }
            fclose($fp);
        }
    }else{
        echo 'You did not submit a form.';
    }
?>
