<?php
session_start();

if(isset($_GET['Area'])){
	
	$_SESSION['areaid'] = $_GET['Area'];
	
	if($_GET['Area'] == 'All'){
		
		unset($_SESSION['areaid']);
	}
}

if(isset($_GET['Qrt'])){
	
	$_SESSION['qrt'] = $_GET['Qrt'];
	
	if($_GET['Qrt'] == 'All'){
		
		unset($_SESSION['qrt']);
	}
}

header('Location: '. $_SERVER['HTTP_REFERER']);

?>