<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

$sites = array();

$query_sites = mysqli_query($con, "SELECT * FROM tbl_sites WHERE Name <> '' ORDER BY Name ASC")or die(mysqli_error($con));
while($row_sites = mysqli_fetch_array($query_sites)){
	
	array_push($sites, addslashes($row_sites['Name']));
	//echo $row_sites['Name'] .'<br>';
}
?>
<!doctype html>
<html>
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="assets/js/jquery-1.9.0.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery-ui-1.10.0.custom.min.js"></script>
        <link rel="stylesheet" href="assets/css/jquery-ui-1.10.0.custom.min.css" type="text/css"/>

        <script type="text/javascript" src="jquery.cascading-autocompletes.js"></script>
        <link rel="stylesheet" href="style.css" type="text/css"/>
    </head>
    <body>
        <label for="country">Country: </label> 
        <input id="country" />
        (Type "a", "r", "g", "e", "n", "t", "i", "n", "v", "z", "u", "l", "s", "c" or "h")
        <br/>
        <label for="city">City: </label>
        <input id="city" />
        (Type "c")
        <br/>
        <label for="street">Street: </label>
        <input id="street" />
        (Type "s")
        <script type="text/javascript">
            $(function() {
                $("#country").autocomplete({
                    source: [<?php
						
						$comma = ',';
				
						for($i=1;$i<=count($sites)-1;$i++){
							
							if($i == count($sites)-1){
								
								$comma = '';
							}
								
							echo '"'. trim($sites[$i]) .'"' . $comma;
						}
						
						?>]
                });
                $("#city").autocomplete({
                    source: function(request, response) {
                        var country = $("#country").val(),
                            cities = [
                                "City 1, " + country,
                                "City 2, " + country,
                                "City 3, " + country,
                                "City 4, " + country
                            ];
                        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term ), "i" );
                        response($.grep(cities, function(value) {
                            return matcher.test(value);
                        }));
                    }
                });
                $("#street").autocomplete({
                    source: function(request, response) {
                        var city = $("#city").val(),
                            streets = [
                                "Street 1, " + city,
                                "Street 1, " + city,
                                "Street 1, " + city,
                                "Street 1, " + city
                            ];
                        var matcher = new RegExp($.ui.autocomplete.escapeRegex( request.term ), "i" );
                        response($.grep(streets, function(value) {
                            return matcher.test(value);
                        }));
                    }
                });

                $.cascadingAutocompletes(["#country", "#city", "#street"]);
            });
        </script>
    </body>
</html>