<?php
include('phpgraphlib.php');
$graph = new PHPGraphLib(1000,650);
$data = array('Test 1' => 12124, 
			  'Test 2' => 5535, 
			  'Test 3' => 43373, 
			  'Test 4' => 22223, 
			  'Test 5' => 90432, 
			  'Test 6' => 23332, 
			  'Test 7' => 15544, 
			  'Test 8' => 24523, 
			  'Test 9' => 32778, 
			  'Test 10' => 38878, 
			  'Test 11' => 28787, 
			  'Test 12' => 33243, 
			  'Test 13' => 34832, 
			  'Test 14' => 32302);
$graph->addData($data);
$graph->setTitle('Widgets Produced');
$graph->setGradient('red', 'maroon');
$graph->setXValuesHorizontal(true);
$graph->setRange(0,100000);
$graph->setBackgroundColor("#535353");
$graph->setGridColor("#6a6a6a");
$graph->setGoalLine(35000, "red", "solid");
$graph->createGraph();
?>