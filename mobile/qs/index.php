<?php 
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









