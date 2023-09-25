<?php

   //header('Location: index.html');
   
   require_once('../../functions/functions.php');
   
   login_engineer_bp_sched($con);
   
?>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Seavest Pt Ltd - Secure Login</title>
      <link href="../../Reports/Engineers/BP/css/login.css" rel="stylesheet" type="text/css" />
   </head>
<body>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
      <?php if(isset($_GET['Error'])){ ?>
      <tr>
        <td><div id="banner-error">Either your username or password is incorrect, plwase try again</div></td>
      </tr>
      <?php } ?>
      <tr>
        <td align="center">
        <div class="title-login" id="login-panel"><img src="../../Reports/Engineers/images/logo-bp.png" width="390" height="50">
          <form action="" method="post" name="form1" class="form-bdr" id="form1">
            <input name="username" type="text" class="tfield-login" id="username" onFocus="if(this.value=='Username'){this.value=''}" onBlur="if(this.value==''){this.value='Username'}" value="Username" />
            <input name="password" type="password" class="tfield-login" id="password" onFocus="if(this.value=='Password'){this.value=''}" onBlur="if(this.value==''){this.value='Password'}" value="Password" />
            <input name="login" type="submit" class="btn-login" id="login" value="LOGIN" />
        </form>
        </div>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>