<?php 
session_start();

mysql_connect("sql25.jnb1.host-h.net","kwdaco_333","SBbB38c8Qh8") or die(mysql_error());
mysql_select_db("kwdaco_333") or die(mysql_error());

// Redirect
if(isset($_POST['history'])){
	
	header('Location: history.php');
	
}

if(isset($_POST['images'])){
	
	header('Location: images.php');
	
}

if(isset($_POST['logout'])){
	
	session_destroy();
	
	header('Location: index.php');
	
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Sealink</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="css/zero.css" rel="stylesheet" type="text/css" />
		<link href="css/default.css" rel="stylesheet" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	
<body id="site">

<div id="wrapper">

        <div id="logo"><img src="images/logo.jpg" alt="" width="171" height="67"></div><!--logo-->
        
  <div id="content">
    <p>&nbsp;</p>
                
                <form id="fm_form" method="post" action="menu.php" >
                  <fieldset>
                </fieldset>
                
                    <fieldset>
                            <p>
                              <input class="fm-req" id="fm-submit" name="history" value="Update History" type="submit" onclick="javascript:document.location='history.php';" />
                            </p>
                            <p>
                              <input class="fm-req" id="fm-submit" name="images" value="Seavest Gallery" type="submit" onclick="javascript:document.location='gallery.php';" />
                            </p>
                    </fieldset>
                    <p>
                      <input class="fm-req" id="fm-submit" name="logout" value="Logout" type="submit" style="background-color:#900; color:#FFF" />
                    </p>
    </form>
  </div><!--content-->
</div><!--end wrapper-->

</body>
</html>









