<link href="styles/layout.css" rel="stylesheet" type="text/css" />
  <table border="0" align="center" cellpadding="0" cellspacing="0">
  <?php if(isset($_SESSION['user_id'])){ ?>
  <tr><td valign="top">
  <div style="float: left; margin-bottom:10px" id="my_menu" class="sdmenu">
      <div>
        <span>Work Status</span>
        <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/public/qued.php">Allocated to Seavest </a>
        <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/public/progress.php">Service In Progress</a>
        <a href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/inv/public/costing.php">Service Complete</a>      </div>
      <div class="collapsed">
        <span>
		<a style="background:none; color:#FFFFFF; line-height:5px; border:none; padding-left:0px;" href="logout.php">Logout</a>
		</span>
      </div>
  </div>
  </td></tr>
  <?php } else { ?>
  <tr>
    <td valign="top"><form id="form1" name="form1" method="post" action="login.php">
      <table border="0" cellspacing="3" cellpadding="2">
        <tr>
          <td><input name="username" type="text" class="tarea2" id="username" onblur="clickrecall(this,'Username')" onclick="clickclear(this, 'Username')" value="Username" /></td>
        </tr>
        <tr>
          <td><input name="password" type="password" class="tarea2" id="password" onblur="clickrecall(this,'Password')" onclick="clickclear(this, 'Password')" value="Password" /></td>
        </tr>
        <tr>
          <td align="right"><input name="Submit" type="image" src="sdmenu/blue/btn_go.jpg" value="Submit" /></td>
        </tr>
		</table>
		</form>
    </td>
  </tr>
  <?php } ?>
  </table>
  		<form id="form2" name="form2" method="post" action="index.php">
      <table border="0" align="center" cellpadding="2" cellspacing="3">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="ref" type="text" class="tarea2" id="ref" onblur="clickrecall(this,'Reference No.')" onclick="clickclear(this, 'Reference No.')" value="Reference No." /></td>
        </tr>
        <tr>
          <td align="right"><input name="Submit2" type="image" src="sdmenu/blue/btn_go.jpg" value="Submit2" /></td>
        </tr>
      </table>
        </form>
