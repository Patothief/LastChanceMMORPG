<?php
	include 'config/db.php';
		
	if ($_SESSION['rocketId'] == 0) {
		header("Location: launchPadNotJoined.php");
	} else {
		header("Location: launchPadJoined.php");
	}

?>