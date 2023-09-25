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

    require_once 'createFDF.php';
    $pdf_file='http://koivi.com/fill-pdf-form-fields/test.pdf';

    // allow for up to 25 different files to be created, based on the minute
    $min=date('i') % 25;
    
    $fdf_file=dirname(__FILE__).'/results/posted-'.$min.'.fdf';

    if(isset($_POST['__NAME__'])){
        $_POST['__CSZ__']=$_POST['__CITY__'].', '.
                          $_POST['__STATE__'].' '.
                          $_POST['__ZIP__'];

        // get the FDF file contents
        $fdf=createFDF($pdf_file,$_POST);

        // Create a file for later use
        if($fp=fopen($fdf_file,'w')){
            fwrite($fp,$fdf,strlen($fdf));
            $CREATED=TRUE;
        }else{
            echo 'Unable to create file: '.$fdf_file.'<br><br>';
            $CREATED=FALSE;
        }
        fclose($fp);
    }
?>
