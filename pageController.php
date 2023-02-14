<?php
	include 'config/db.php';
	
	$sql = "SELECT rocket.state AS state FROM rocket WHERE rocket.id = " . $_SESSION['rocketId'];
		
	$run = $connection->query($sql);
	$row = $run->fetch_array();
	
	$state = $row['state'];
	
	if ($state == 0 || $state == 1) { // building or loading
		header("Location: shelter.php");
	} else if ($state == 2) { // launched
		header("Location: rocketBridge.php");
	} else if ($state == 3) { // landed
		header("Location: ???.php");
	} else { // unknown state
		echo "Unknown state";
	}

?>