<?php 
session_start();

mysql_connect("sql15.jnb1.host-h.net","kwdaco_333","SBbB38c8Qh8") or die(mysql_error());
mysql_select_db("seavest_db333") or die(mysql_error());

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

<style type="text/css">
input[type=text], input[type=url], input[type=email], input[type=password], input[type=tel], select {
  -webkit-appearance: none; -moz-appearance: none;
  display: block;
  margin: 0;
  width: 100%; 
  height: 40px;
  line-height: 40px; 
  font-size: 17px;
  border: 1px solid #bbb;
}
textarea {
  -webkit-appearance: none; -moz-appearance: none;
  display: block;
  margin: 0;
  width: 100%; 
  line-height: 20px; 
  font-size: 17px;
  border: 1px solid #bbb;
  font-family: Arial, Helvetica, sans-serif;
}
input[type=submit], input[type=file], .CollapsiblePanelTab {
 -webkit-appearance: none; 
 -moz-appearance: none;
 display: block;
 margin: 1.5em 0;
 margin-bottom:0px;
 font-size: 1em !important; 
 line-height: 2.5em !important;
 color: #333 !important;
 font-weight: bold;
 height: 2.5em !important; 
 width: 100%;
 background: #fdfdfd; 
 background: -moz-linear-gradient(top, #fdfdfd 0%, #bebebe 100%); 
 background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fdfdfd), color-stop(100%,#bebebe)); 
 background: -webkit-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); 
 background: -o-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); 
 background: -ms-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); 
 background: linear-gradient(to bottom, #fdfdfd 0%,#bebebe 100%);
 border: 1px solid #bbb !important;
 -webkit-border-radius: 5px; 
 -moz-border-radius: 5px; 
 border-radius: 5px;
}
</style>

</head>	
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









