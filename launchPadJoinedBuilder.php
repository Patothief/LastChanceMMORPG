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

	echo "<tr><th>" . $lang['NAME'] . "</th><th>" . $lang['ROLE'] . "</th></tr>";

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
	$sql = "SELECT rocket.state AS state, rocket.build_progress AS build_progress, rocket.player_creator_id AS player_creator_id, rocket_type.metals_required AS metals_required, " .
			"rocket.fuel AS fuel, rocket.food AS food, rocket.weapons AS weapons, " .
			"rocket_type.max_cargo AS max_cargo, rocket_type.launch_fuel AS launch_fuel " .
			"FROM rocket JOIN rocket_type ON rocket.rocket_type_id = rocket_type.id WHERE rocket.id = " . $_SESSION['rocketId'];

	$run = $connection->query($sql);
	$row = $run->fetch_array();

	$state = $row['state'];
	$buildProgress = $row['build_progress'];
	$metalsRequired = $row['metals_required'];
	$playerCreatorId = $row['player_creator_id'];
	
	$fuel = $row['fuel'];
	$food = $row['food'];
	$weapons = $row['weapons'];

	$max_cargo = $row['max_cargo'];
	$launch_fuel = $row['launch_fuel'];

	
	$insetPercentage = 100 - $buildProgress * 100 / $metalsRequired;

	$content = '';

	if ($state == 0) { // building
		$buildPrice = min(10, $metalsRequired - $buildProgress, $_SESSION['metal']);
		
		$content = "Build progress: " . $buildProgress . "/" . $metalsRequired .
			"<br/><br/>" .
			"<div id='image1'>" .
			"<img id='image2' src='images/rocket.png'/>" .
			"</div>" .
			"<br/>" .
			"<button onClick = 'buildRocket(" . $buildPrice . ")' class='btn btn-outline-primary btn-lg btn-block'>Build (" . $buildPrice . " metals)</button>" .
			"<label>" . $lang['YOU_HAVE'] . $_SESSION['metal'] . $lang['METALS.'] . "</label>";
		
		
	} else if ($state == 1) { // loading
		$cargo = $food + $fuel + $weapons;
		
		$loadFoodAmount = min(10, $max_cargo - $cargo, $_SESSION['food']);
		$unloadFoodAmount = min(10, $food);

		$loadFuelAmount = min(10, $max_cargo - $cargo, $_SESSION['fuel']);
		$unloadFuelAmount = min(10, $fuel);

		$loadWeaponsAmount = min(10, $max_cargo - $cargo, $_SESSION['weapons']);
		$unloadWeaponsAmount = min(10, $weapons);
			
		$content = "Loading rocket" .
			"<br/>" .
			"<div id='image1'>" .
			"<img id='image2' src='images/rocket.png'/>" .
			"</div>" .		
			"<br/>" .
			"<table>" .
			"<tr>" .
			"<td>Food:</td>" .
			"<td>" . $food . "</td>" .
			"<td><button onClick = 'loadFood(" . $loadFoodAmount . ")' class='btn btn-outline-primary btn-block'>Load (" . $loadFoodAmount . ")</button></td>" .
			"<td><button onClick = 'unloadFood(" . $unloadFoodAmount . ")' class='btn btn-outline-primary btn-block'>Unload (" . $unloadFoodAmount . ")</button></td>" .
			"</tr>" .
			"<tr>" .
			"<td>Fuel:</td>" .
			"<td>" . $fuel . "</td>" .
			"<td><button onClick = 'loadFuel(" . $loadFuelAmount . ")' class='btn btn-outline-primary btn-block'>Load (" . $loadFuelAmount . ")</button></td>" .
			"<td><button onClick = 'unloadFuel(" . $unloadFuelAmount . ")' class='btn btn-outline-primary btn-block'>Unload (" . $unloadFuelAmount . ")</button></td>" .
			"</tr>" .
			"<tr>" .
			"<td>Weapons:</td>" .
			"<td>" . $weapons . "</td>" .
			"<td><button onClick = 'loadWeapons(" . $loadWeaponsAmount . ")' class='btn btn-outline-primary btn-block'>Load (" . $loadWeaponsAmount . ")</button></td>" .
			"<td><button onClick = 'unloadWeapons(" . $unloadWeaponsAmount . ")' class='btn btn-outline-primary btn-block'>Unload (" . $unloadWeaponsAmount . ")</button></td>" .
			"</tr>" .
			"</table>" .
			"<br/>";
			
		$content .=	"Cargo: " . $cargo . "<br/>" .
			"Max Cargo: " . $max_cargo . "<br/>" .
			"Min launch fuel: " . $launch_fuel . "<br/><br/>";
			
			
		$content .= "<button onClick = 'abandonRocket()' class='btn btn-outline-primary btn-lg btn-block btn-warning'>LAUNCH</button>";
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

if (isset($_GET['loadFood'])) {
	$amount = $_GET['amount'];
	
	if ($_SESSION['food'] < $amount) {
		echo "ERROR: Not enough resource but javascript check passed!!!";
		exit();
	}
	
	$sql = "UPDATE player SET food = food - " . $amount . " WHERE id = " . $_SESSION['playerId'];
	$connection->query($sql);

	$sql = "UPDATE rocket SET food = food + " . $amount . " WHERE id = " . $_SESSION['rocketId'];
	$connection->query($sql);
	
	$_SESSION['food'] = $_SESSION['food'] - $amount;
}

if (isset($_GET['unloadFood'])) {
	$amount = $_GET['amount'];
	
	$sql = "UPDATE player SET food = food + " . $amount . " WHERE id = " . $_SESSION['playerId'];
	$connection->query($sql);
	
	$sql = "UPDATE rocket SET food = food - " . $amount . " WHERE id = " . $_SESSION['rocketId'];
	$connection->query($sql);
	
	$_SESSION['food'] = $_SESSION['food'] + $amount;
}

if (isset($_GET['loadFuel'])) {
	$amount = $_GET['amount'];
	
	if ($_SESSION['fuel'] < $amount) {
		echo "ERROR: Not enough resource but javascript check passed!!!";
		exit();
	}
	
	$sql = "UPDATE player SET fuel = fuel - " . $amount . " WHERE id = " . $_SESSION['playerId'];
	$connection->query($sql);

	$sql = "UPDATE rocket SET fuel = fuel + " . $amount . " WHERE id = " . $_SESSION['rocketId'];
	$connection->query($sql);
	
	$_SESSION['fuel'] = $_SESSION['fuel'] - $amount;
}

if (isset($_GET['unloadFuel'])) {
	$amount = $_GET['amount'];
	
	$sql = "UPDATE player SET fuel = fuel + " . $amount . " WHERE id = " . $_SESSION['playerId'];
	$connection->query($sql);
	
	$sql = "UPDATE rocket SET fuel = fuel - " . $amount . " WHERE id = " . $_SESSION['rocketId'];
	$connection->query($sql);
	
	$_SESSION['fuel'] = $_SESSION['fuel'] + $amount;
}

if (isset($_GET['loadWeapons'])) {
	$amount = $_GET['amount'];
	
	if ($_SESSION['weapons'] < $amount) {
		echo "ERROR: Not enough resource but javascript check passed!!!";
		exit();
	}
	
	$sql = "UPDATE player SET weapons = weapons - " . $amount . " WHERE id = " . $_SESSION['playerId'];
	$connection->query($sql);

	$sql = "UPDATE rocket SET weapons = weapons + " . $amount . " WHERE id = " . $_SESSION['rocketId'];
	$connection->query($sql);
	
	$_SESSION['weapons'] = $_SESSION['weapons'] - $amount;
}

if (isset($_GET['unloadWeapons'])) {
	$amount = $_GET['amount'];
	
	$sql = "UPDATE player SET weapons = weapons + " . $amount . " WHERE id = " . $_SESSION['playerId'];
	$connection->query($sql);
	
	$sql = "UPDATE rocket SET weapons = weapons - " . $amount . " WHERE id = " . $_SESSION['rocketId'];
	$connection->query($sql);
	
	$_SESSION['weapons'] = $_SESSION['weapons'] + $amount;
}