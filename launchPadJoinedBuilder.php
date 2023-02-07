<?php 
include 'config/db.php';
include_once 'language.php';

if (isset($_GET['loadLaunchPadParticipantsTable'])) {
	$sql = "SELECT rocket.state AS state, rocket.build_progress as build_progress, rocket.player_creator_id as player_creator_id, rocket_type.metals_required as metals_required " .
			"FROM rocket JOIN rocket_type ON rocket.rocket_type_id = rocket_type.id WHERE rocket.id = " . $_SESSION['rocketId'];

	$run = $connection->query($sql);
	$row = $run->fetch_array();

	$state = $row['state'];
	$buildProgress = $row['build_progress'];
	$metalsRequired = $row['metals_required'];
	$playerCreatorId = $row['player_creator_id'];

	$sql = "SELECT id, playername, rocket_role FROM player WHERE rocket_id = " . $_SESSION['rocketId'];
	$run = $connection->query($sql);

	while ($row = $run->fetch_array()) :
		echo "<tr>";
		if ($row['id'] == $playerCreatorId) {
			echo "<td class='creator'>" . $row['playername'] . "</td>";
		} else {
			echo "<td>" . $row['playername'] . "</td>";
		}
		
		if ($row['rocket_role'] == 4) {
			echo "<td>Commander</td>";
		} else if ($row['rocket_role'] == 3) {
			echo "<td>Pilot</td>";
		} else if ($row['rocket_role'] == 2) {
			echo "<td>Engineer</td>";
		} else if ($row['rocket_role'] == 1) {
			echo "<td>Participant</td>";
		} else {	// error state
			echo "<td>Unasigned</td>";
		}
		
		echo "</tr>";
	endwhile;	
}

if (isset($_GET['launchPadDetailsDiv'])) {
	$sql = "SELECT rocket.state AS state, rocket.build_progress as build_progress, rocket.player_creator_id as player_creator_id, rocket_type.metals_required as metals_required " .
			"FROM rocket JOIN rocket_type ON rocket.rocket_type_id = rocket_type.id WHERE rocket.id = " . $_SESSION['rocketId'];

	$run = $connection->query($sql);
	$row = $run->fetch_array();

	$state = $row['state'];
	$buildProgress = $row['build_progress'];
	$metalsRequired = $row['metals_required'];
	$playerCreatorId = $row['player_creator_id'];

	
	$insetPercentage = 100 - $buildProgress * 100 / $metalsRequired;

	$content = '';

	if ($state == 0) { // building
		$buildPrice = min(10, $metalsRequired - $buildProgress);
		
		$content = "Build progress: " . $buildProgress . "/" . $metalsRequired .
			"<br/><br/>" .
			"<div id='image1'>" .
			"<img id='image2' src='images/rocket.png'/>" .
			"</div>" .
			"<button onClick = 'buildRocket(" . $buildPrice . ")' class='btn btn-outline-primary btn-lg btn-block'>Build (" . $buildPrice . " metals)</button>" .
			"<label>" . $lang['YOU_HAVE'] . $_SESSION['metal'] . $lang['METALS.'] . "</label>";
		
		
	} else if ($state == 1) { // loading
		$content = "Loading rocket";
	}
	
	$return_arr[] = array("content" => $content,
				"insetPercentage" => $insetPercentage);
				
	echo json_encode($return_arr);
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
		
		$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (0, " . $_SESSION['rocketId'] . ", 'Rocket built and ready for loading.')";
		$connection->query($sql);
	}
}