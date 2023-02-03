<?php
	include 'config/db.php';
		
	if ($_SESSION['rocketId'] == 0) {
		header("Location: rocketNotJoined.php");
	} else {
		header("Location: rocketJoined.php");
	}

?>