<?php session_start(); ?>
<!DOCTYPE html>
<html>
<body>


<div id="result"></div>

<script>

function form_action(param){
// Check browser support
	if (typeof(Storage) !== "undefined") {
		// Store
		localStorage.setItem("param", param);
		// Retrieve
		document.form1.action = localStorage.getItem("param");
		
	}
}
</script>
</body>

<form name="form1" id="form1" method="post" action="">
<input type="submit" name="button" id="button" value="Submit">
</form>

<a onClick="form_action('test.php?Smith')">Smith</a><br>
<a onClick="form_action('test.php?Kaminski')">Kaminski</a><br>

</html>