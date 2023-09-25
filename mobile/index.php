<?php
header("Location: https://sealink.reimaginedstudios.co.za/inv/mobile/index.php");
session_start();

if (isset($_POST['username'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];

	$con = mysqli_connect('sql15.jnb1.host-h.net', 'kwdaco_333', 'SBbB38c8Qh8', 'seavest_db333');

	$query = mysqli_query($con, "SELECT * FROM tbl_technicians WHERE Username = '$username' AND Password = '$password'") or die(mysqli_error($con));
	$row = mysqli_fetch_array($query);
	$numrows = mysqli_num_rows($query);

	if ($numrows >= 1) {

		$_SESSION['userid'] = $row['Id'];
		setcookie('username', $row['Username'], 60 * 60 * 24 * 365 + time());
		setcookie('password', $row['Password'], 60 * 60 * 24 * 365 + time());

		header('Location: menu.php');
		exit();
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>Sealink</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<style type="text/css">
		.btn-new {
			background: #18519b;
			/* Old browsers */
			background: -moz-linear-gradient(top, #18519b 0%, #3c87c4 100%);
			/* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #18519b), color-stop(100%, #3c87c4));
			/* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top, #18519b 0%, #3c87c4 100%);
			/* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top, #18519b 0%, #3c87c4 100%);
			/* Opera 11.10+ */
			background: -ms-linear-gradient(top, #18519b 0%, #3c87c4 100%);
			/* IE10+ */
			background: linear-gradient(to bottom, #18519b 0%, #3c87c4 100%);
			/* W3C */

			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#18519b', endColorstr='#3c87c4', GradientType=0);
			/* IE6-9 */
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			color: #FFF;
			margin-top: 10px;
			border: none;
			padding-top: 5px;
			padding-right: 10px;
			padding-bottom: 5px;
			padding-left: 10px;
			cursor: pointer;
			display: inline-block;
			text-decoration: none;
			width: 100%;
			display: block;
		}
	</style>
	<link href="../css/mobile.css" rel="stylesheet" type="text/css">
</head>

<body id="site">

	<div id="wrapper">

		<div id="logo"><img src="../Reports/Engineers/images/logo.png" alt="" width="290" height="50"></div>
		<!--logo-->

		<div id="content">

			<form id="fm_form" method="post" action="">

				<input name="username" type="text" class="tfield" id="username" style="margin-bottom:10px" value="<?php
																													if (isset($_COOKIE['username'])) {
																														echo $_COOKIE['username'];
																													} else {
																														echo 'Username';
																													}
																													?>" onFocus="if (this.value=='Username') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Username';" />

				<input name="password" type="text" class="tfield" id="password" style="margin-bottom:10px" value="<?php
																													if (isset($_COOKIE['password'])) {
																														echo $_COOKIE['password'];
																													} else {
																														echo 'Password';
																													}
																													?>" onFocus="if (this.value=='Password') this.value='';" onBlur="if (this.value.replace(/\s/g,'')=='') this.value='Password';" />

				<div id="sub">
					<input class="btn-new" id="fm-submit" name="Submit" value="Login" type="submit" />
				</div>

			</form>
		</div>
		<!--content-->
	</div>
	<!--end wrapper-->

</body>

</html>