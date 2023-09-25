<?php $con = mysqli_connect('sql25.jnb1.host-h.net','kwdaco_43','SBbB38c8Qh8','seavest_db333'); ?>
<html>
<head>
<title>Dynamic Dependent Dropdown with jQuery</title>
<style type="text/css">
	.wrapper{
		width: 500px;
		margin: 0px auto;
	}
	.inputbox{
		padding: 10px 0px;
	}
	.selectbox{
		padding: 5px;
		width: 250px;
	}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('#country').change(function(){
		var country_id = $('#country').val();
		if(country_id != 0)
		{
			$.ajax({
				type:'post',
				url:'getvalue.php',
				data:{id:country_id},
				cache:false,
				success: function(returndata){
					$('#city').html(returndata);
				}
			});
		}
	})
})
</script>
</head>
<body>
<div class="wrapper">
	<h1>Dynamic Dependent Dropdown with jQuery</h1>
	<p><a href="http://coffeecupweb.com/">http://coffeecupweb.com/</a></p>
	<form>
	<div class="inputbox">
		<select id="country" class="selectbox">
	    	<option value="0">Please Select a country</option>
	    	<?php $sql = mysqli_query($con, 'SELECT * FROM tbl_companies'); ?>
	        <?php while($row = mysqli_fetch_array($sql)){ ?>
	    	<option value="<?php echo $row['Id']; ?>"><?php echo $row['Name']; ?></option>
	        <?php } ?>
	    </select>
	</div>
	<div class="inputbox">
	    <select id="city" class="selectbox">
	    	<option value="0">Please select a city</option>
	        <option></option>
	    </select>
	</div>
	</form>
</div>
</body>
</html>