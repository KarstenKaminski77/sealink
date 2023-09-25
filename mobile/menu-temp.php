<?php 
session_start();

mysql_connect("sql25.jnb1.host-h.net","kwdaco_43","SBbB38c8Qh8") or die(mysql_error());
mysql_select_db("seavest_db333") or die(mysql_error());

// Redirects

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
	
    <script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
    <link href="../SpryAssets/SpryCollapsiblePanel3.css" rel="stylesheet" type="text/css">
<body id="site">

<div id="wrapper">

        <div id="logo"><img src="images/logo.jpg" alt="" width="171" height="67"></div><!--logo-->
        
  <div id="content">
    <p>&nbsp;</p>
                
                <form id="fm_form" method="post" action="" >
                
                    <div id="CollapsiblePanel1" class="CollapsiblePanel">
                      <div class="CollapsiblePanelTab" tabindex="0">Job Cards</div>
                      <div class="CollapsiblePanelContent">
                    <fieldset>
                            <p>
                              <input class="fm-req" id="fm-submit" name="history-jc" value="Update History" type="submit" onclick="javascript:document.location='history.php';" />
                      </p>
                            <p>
                              <input class="fm-req" id="fm-submit" name="images-jc" value="Seavest Gallery" type="submit" onclick="javascript:document.location='gallery.php';" />
                            </p>
                    </fieldset>
                      </div>
                    </div>
                    
                    <div id="CollapsiblePanel2" class="CollapsiblePanel">
                      <div class="CollapsiblePanelTab" tabindex="0">Quotes</div>
                      <div class="CollapsiblePanelContent">
                    <fieldset>
                            <p>
                              <input class="fm-req" id="fm-submit" name="history-qs" value="Update History" type="submit" onclick="javascript:document.location='history.php';" />
                      </p>
                            <p>
                              <input class="fm-req" id="fm-submit" name="images-qs" value="Seavest Gallery" type="submit" onclick="javascript:document.location='gallery.php';" />
                            </p>
                    </fieldset>
                      </div>
                    </div>
<p>
<input class="fm-req" id="fm-submit" name="logout" value="Logout" type="submit" style="background-color:#900; color:#FFF" />
                    </p>
    </form>
  </div><!--content-->
</div><!--end wrapper-->

<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2");
//-->
</script>
</body>
</html>









