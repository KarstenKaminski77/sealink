<?php
session_start();

require_once('../../Connections/seavest.php'); 
require_once('../../functions/functions.php');

$sites = array();

$query_sites = "
	SELECT
		tbl_sites.`Name`,
		tbl_sites.Id,
		tbl_jc.`Status`,
		tbl_jc.JobNo
	FROM
		tbl_sites
	INNER JOIN tbl_jc ON tbl_sites.Id = tbl_jc.SiteId
	WHERE
		tbl_jc.`Status` = 2
	GROUP BY
	tbl_jc.JobId
	ORDER BY
		tbl_sites.`Name` ASC";
	
$query_sites = mysqli_query($con, $query_sites)or die(mysqli_error($con));
while($row_sites = mysqli_fetch_array($query_sites)){
	
	array_push($sites, $row_sites['JobNo'] .' | '. addslashes($row_sites['Name']));
	//echo $row_sites['Name'] .'<br>';
}
?>
<html>
    <head>
        <script src="js/jquery1.7.1.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(function() {
                availableSports = [<?php
						
						$comma = ',';
				
						for($i=1;$i<=count($sites)-1;$i++){
							
							if($i == count($sites)-1){
								
								$comma = '';
							}
								
							echo '"'. trim($sites[$i]) .'"' . $comma;
						}
						
						?>];
                   $("#sport").autocomplete({
                	source: availableSports
                    });
            });
            function loadSportsTeams(){
                //Gets the name of the sport entered.
                var sportSelected= $("#sport").val();
                var teamList = "";
                $.ajax({
                    url: 'sportsTeams.php',
                    type: "POST",
                    async: false,
                    data: { sport: sportSelected}
                 }).done(function(teams){
                    teamList = teams.split(',');
                 });
                //Returns the javascript array of sports teams for the selected sport.
                return teamList;
            }
            function autocompleteSportsTeams(){
                var teams = loadSportsTeams();
                $("#sportsTeam").autocomplete({
                     source: teams
                 });
             }
        </script>
    </head>
    <body>
        Sport: <input type="text" id="sport" name="sport" onBlur="autocompleteSportsTeams()"></input>
        Sports Team: <input type="text" id="sportsTeam" name="sportsTeam"></input>
    </body>
</html>