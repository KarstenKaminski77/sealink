<?php 
session_start();

// Redirects
if(!isset($_SESSION['userid'])){
	
	header('Location: ../index.php');
	
	exit();
}

//Job Cards
if(isset($_POST['history-jc'])){
	
	header('Location: jc/history.php');
	
}

if(isset($_POST['images-jc'])){
	
	header('Location: jc/images.php');
	
}

//Quotes
if(isset($_POST['history-qs'])){
	
	header('Location: qs/history.php');
	
}

if(isset($_POST['images-qs'])){
	
	header('Location: qs/images.php');
	
}

if(isset($_POST['logout'])){
	
	session_destroy();
	
	header('Location: index.php');
	
}

if(isset($_POST['my-profile'])){
	
	session_destroy();
	
	header('Location: my-profile/index.php');
	
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Sealink</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Include scripts -->
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript" src="../menu/js/responsivemultimenu.js"></script>

    <!-- Include styles -->
    <link rel="stylesheet" href="../menu/css/responsivemultimenu.css" type="text/css" />

    <!-- Include media queries -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <link href="../../css/mobile.css" rel="stylesheet" type="text/css" />

</head>
<body id="site">

    <div id="wrapper">

        <div id="content">
        
        <?php include('../menu/menu.php'); ?>

            <form id="fm_form" method="post" action="">
            
            <div style="width:100%">

                <div class="icon-container"> 
                  <a href="personal-info.php">
                    <img src="../../images/icons/Mobile/tab-1.png">
                  </a>
                </div>
                
                <div class="icon-container"> 
                  <a href="medicals.php">
                    <img src="../../images/icons/Mobile/tab-2.png">
                  </a>
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-3.png">
                </div>
                
                <div class="icon-container"> 
                  <a href="non-conformance.php">
                    <img src="../../images/icons/Mobile/tab-4.png">
                  </a>
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-5.png">
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-6.png">
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-7.png">
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-8.png">
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-9.png">
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-10.png">
                </div>
                
                <div class="icon-container">
                  <img src="../../images/icons/Mobile/tab-11.png">
                </div>
            
            </div>
            </form>
        </div><!--content-->
    </div><!--end wrapper-->
</body>
</html>








