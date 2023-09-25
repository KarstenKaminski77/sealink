<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="jquery autocomplete">
	<meta name="keywords" content="jquery autocomplete">
	<meta name="author" content="muni">
    <title>jquery autocomplete using ajax php mysql </title>
	<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
 	</head>
 	<body>

				<button type="button" data-toggle="collapse"
					data-target=".navbar-collapse" class="navbar-toggle collapsed">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>

    <!-- Static navbar -->
				<form id='students' method='post' name='students' action='index.php'>
					  	<table class="table table-bordered">
							<tr>
							    <th><input class='check_all' type='checkbox' onclick="select_all()"/></th>
							    <th>S. No</th>
							    <th>Country Name</th>
							    <th>Country Number</th>
							    <th>Country Phone code</th>
							    <th>Country code</th>
							</tr>
							<tr>
						    	<td><input type='checkbox' class='case'/></td>
						    	<td><span id='snum'>1.</span></td>
						   	 	<td><input class="form-control" type='text' id='details_1' name='details[]'/></td>
						    	<td><input class="form-control" type='text' id='price_1' name='price[]'/></td>
						    	<td><input class="form-control" type='text' id='phone_code_1' name='phone_code[]'/></td>
						    	<td><input class="form-control" type='text' id='country_code_1' name='country_code[]'/> </td>
						  	</tr>
					  	</table>
				</form>
    <script src="js/auto.js"></script>
  </body>
</html>
