<?php 
header("Location: https://sealink.reimaginedstudios.co.za/inv/mobile/index.php");
session_start();

require_once('../../Connections/inv.php');

if(isset($_POST['username'])){
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$query = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Username = '$username' AND Password = '$password'")or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$numrows = mysqli_num_rows($query);
	
	if($numrows >= 1){
		
		$_SESSION['userid'] = $row['Id'];
		setcookie('username',$row['Username'], 60 * 60 * 24 * 365 + time());
		setcookie('password',$row['Password'], 60 * 60 * 24 * 365 + time());
		
		header('Location: menu.php');
		
	} 
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
                
                <form id="fm_form" method="post" action="index.php" >
                  <fieldset>
                </fieldset>
                
                    <fieldset>
                            <p>
                                  
               <input name="username" type="text" class="fm-req" id="username"
               value="<?php 
			   if(isset($_COOKIE['username'])){ 
			   echo $_COOKIE['username']; 
			   } else { 
			   echo 'Username'; 
			   } 
			   ?>" onFocus="if (this.value=='Username') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Username';" />
                      </p>
                            <p>
                                    
              <input name="password" type="text" class="fm-req" id="password" 
              value="<?php 
			   if(isset($_COOKIE['password'])){ 
			   echo $_COOKIE['password']; 
			   } else { 
			   echo 'Password'; 
			   } 
			   ?>" onFocus="if (this.value=='Password') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Password';" />
                            </p>
                    </fieldset>
                    
                    <div id="sub">
                      <input class="fm-req" id="fm-submit" name="Submit" value="Login" type="submit" />
                    </div>
                    
    </form>
  </div><!--content-->
</div><!--end wrapper-->

</body>
</html>









