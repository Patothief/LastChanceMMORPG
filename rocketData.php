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
	}
	
	if (isset($_GET['createRocket'])) {

		$sql = "INSERT INTO rocket (name, rocket_type_id, player_creator_id) VALUES ('test', 1, " . $_SESSION['playerId'] . ")";

		if(!mysqli_query($connection, $sql)) {
			echo "ERROR: Message not sent!!!";
		}
	
		$insertId = mysqli_insert_id($connection);
		// Attempt insert query execution
		$sql = "UPDATE player SET rocket_id=" . $insertId . " WHERE id = '" . $_SESSION['playerId'] . "'";

		if(!mysqli_query($connection, $sql)) {
			echo "SQL ERROR";
		}
		
		$_SESSION['rocketId'] = $insertId;
	}
	
	$htmlContent = "";
	
	if ($_SESSION['rocketId'] == 0) {
		$htmlContent = "<label>" .
		"You have not joined any rocket project. Join existing project or create a new one." .
		"</label>" .
		"<br/><br/>" .
		"<label>" .
		"Rocket projects:" .
		"</label>" .
		"<br/>" . 
		"<table>" .
		"<tr>" .
		"<th>Name</th><th>Type</th><th>Owner</th><th>Action</th>" .
		"</tr>";

		$sql = "SELECT rocket.id AS rocketId, rocket.name AS rocketName, rocket_type.name AS rocketTypeName, player.playername AS playerName " .
			"FROM rocket JOIN rocket_type ON rocket.rocket_type_id = rocket_type.id JOIN player on rocket.player_creator_id = player.id";
		$run = $connection->query($sql);

		while ($row = $run->fetch_array()) :
			$rocketId = $row['rocketId'];
			
			$htmlContent .= "<tr>" .
			"<td>" . $row['rocketName'] . "</td>" .
			"<td>" . $row['rocketTypeName'] . "</td>" .
			"<td>" . $row['playerName'] . "</td>" .
			"<td>" .
			"<button onClick = 'joinRocket(" . $rocketId . ")' class='btn btn-outline-primary btn-lg btn-block'>Join</button>" .
			"</td>" .
			"</tr>";
		endwhile;				

		$htmlContent .= "</table>" .
		"<br/><br/>" .
		"<button onClick = 'createRocket()' class='btn btn-outline-primary btn-lg btn-block'>Create rocket (50 metals)</button>" .
		"<br/>" .
		"<label>" .
		"You have " . $_SESSION['metal'] . " metals." .
		"</label>";		
	} else {
		$htmlContent = "Rocket joined";
	}
	

	$return_arr[] = array("htmlContent" => $htmlContent,
					"rocketId" => $_SESSION['rocketId']);
				
	echo json_encode($return_arr);
?>