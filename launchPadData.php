<?php

	include 'config/db.php';

	if (isset($_GET['joinRocketId'])) {
		
		$joinRocketId = $_GET['joinRocketId'];

		// Attempt insert query execution
		$sql = "UPDATE player SET rocket_id=" . $joinRocketId . " WHERE id = '" . $_SESSION['playerId'] . "'";

		if(!mysqli_query($connection, $sql)) {
			echo "SQL ERROR";
		}
		
		$_SESSION['rocketId'] = $joinRocketId;
		
		$sql = "SELECT name FROM rocket WHERE id = " . $joinRocketId;
		$run = $connection->query($sql);
		$row = $run->fetch_array();
		$rocketName = $row['name'];
		
		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, 0, '" . $_SESSION['playername'] . " joined launch pad for rocket " . $rocketName . ".')";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}

		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, " . $joinRocketId . ", '" . $_SESSION['playername'] . " joined.')";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}
	}
	
	if (isset($_GET['createRocket'])) {
		$rocketPrice = 100;
		
		if ($_SESSION['metal'] < $rocketPrice) {
			echo "ERROR: Not enough metals but javascript check passed!!!";
			exit();
		}
		
		$rocketName = $_GET['rocketName'];
		
		$sql = "INSERT INTO rocket (name, rocket_type_id, player_creator_id) VALUES ('" . $rocketName . "', " . $_GET['rocketTypeId'] . ", " . $_SESSION['playerId'] . ")";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}
	
		$insertId = mysqli_insert_id($connection);
		// Attempt insert query execution
		$sql = "UPDATE player SET rocket_id=" . $insertId . ", metal = metal - " . $rocketPrice . " WHERE id = '" . $_SESSION['playerId'] . "'";

		if(!mysqli_query($connection, $sql)) {
			echo "SQL ERROR";
		}
		
		$_SESSION['rocketId'] = $insertId;
		$_SESSION['metal'] = $_SESSION['metal'] - $rocketPrice;
		
		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, 0, '" . $_SESSION['playername'] . " created new launch pad for " . $rocketName . ".')";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}

		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, " . $insertId . ", '" . $_SESSION['playername'] . " created launch pad.')";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}
	}
	
	if (isset($_GET['abandonRocket'])) {
		$abanonedRocketId = $_SESSION['rocketId'];
		
		// Attempt insert query execution
		$sql = "UPDATE player SET rocket_id=0 WHERE id = '" . $_SESSION['playerId'] . "'";

		if(!mysqli_query($connection, $sql)) {
			echo "SQL ERROR";
		}
		
		$sql = "SELECT name FROM rocket WHERE id = " . $abanonedRocketId;
		$run = $connection->query($sql);
		$row = $run->fetch_array();
		$rocketName = $row['name'];

		$_SESSION['rocketId'] = 0;

		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, 0, '" . $_SESSION['playername'] . " abanoned launch pad for rocket " . $rocketName . ".')";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}

		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, " . $abanonedRocketId . ", '" . $_SESSION['playername'] . " abanoned launch pad.')";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}
	}
	
	if (isset($_GET['buildRocket'])) {
		$buildPrice = $_GET['buildPrice'];
		
		if ($_SESSION['metal'] < $buildPrice) {
			echo "ERROR: Not enough metals but javascript check passed!!!";
			exit();
		}
		
		$sql = "UPDATE player SET metal = metal - " . $buildPrice . " WHERE id = " . $_SESSION['playerId'];
		$connection->query($sql);

		$sql = "UPDATE rocket SET build_progress = build_progress + " . $buildPrice . " WHERE id = " . $_SESSION['rocketId'];
		$connection->query($sql);
		
		$_SESSION['metal'] = $_SESSION['metal'] - $buildPrice;
		
		$sql = "SELECT rocket.state AS state, rocket.build_progress as build_progress, rocket_type.metals_required as metals_required " .
				"FROM rocket JOIN rocket_type ON rocket.rocket_type_id = rocket_type.id WHERE rocket.id = " . $_SESSION['rocketId'];
			
		$run = $connection->query($sql);
		$row = $run->fetch_array();
		$state = $row['state'];
		$buildProgress = $row['build_progress'];
		$metalsRequired = $row['metals_required'];
			
		if ($buildProgress >= $metalsRequired) { // build finished
			$sql = "UPDATE rocket SET state = 1 WHERE id = " . $_SESSION['rocketId'];
			$connection->query($sql);
		}
		
		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, " . $_SESSION['rocketId'] . ", 'Rocket built and ready for loading.')";
		$connection->query($sql);
	}
?>