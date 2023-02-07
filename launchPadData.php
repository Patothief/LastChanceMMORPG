<?php

	include 'config/db.php';
	include_once 'language.php';

	if (isset($_GET['joinRocketId'])) {
		
		$joinRocketId = $_GET['joinRocketId'];

		// Attempt insert query execution
		$sql = "UPDATE player SET rocket_id=" . $joinRocketId . ", rocket_role = 1 WHERE id = '" . $_SESSION['playerId'] . "'";

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
		$sql = "UPDATE player SET rocket_id=" . $insertId . ", rocket_role = 4, metal = metal - " . $rocketPrice . " WHERE id = '" . $_SESSION['playerId'] . "'";

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
		$sql = "UPDATE player SET rocket_id = 0, rocket_role = 0 WHERE id = '" . $_SESSION['playerId'] . "'";

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

		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, " . $abanonedRocketId . ", '" . $_SESSION['playername'] . "  abanoned launch pad." . "')";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}
	}
?>