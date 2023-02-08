<?php 

include 'config/db.php';
include_once 'language.php';

$foodArray = array(
    1 => 8,
    2 => 5,
    3 => 5,
    4 => 7,
    5 => 15,
    6 => 12,
    7 => 20,
    8 => 30,
    9 => 8,
    10 => 6
);

$fuelArray = array(
    1 => 12,
    2 => 7,
    3 => 3,
    4 => 16,
    5 => 6
);

$metalsArray = array(
    1 => 12,
    2 => 10,
    3 => 20,
    4 => 1,
    5 => 3,
    6 => 15,
    7 => 2,
    8 => 2,
    9 => 8,
    10 => 6
);

$weaponArray = array(
    1 => 2,
    2 => 4,
    3 => 15,
    4 => 10,
    5 => 20
);

$query = "SELECT actions, health, fuel, food, metal, weapons FROM player WHERE id = '" . $_SESSION['playerId'] . "'";
$result = mysqli_query($connection, $query);

$actions = 0;
$fuel = 0;
$food = 0;
$metal = 0;
$weapons = 0;
$message = '';

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    
	$actions = $data['actions'];
	$health = $data['health'];	
	$fuel = $data['fuel'];
	$food = $data['food'];
	$metal = $data['metal'];
	$weapons = $data['weapons'];
} else {
    $message = "No data found";
}

if ($actions <= 0) {
	$message = $lang['NO_MORE_ACTIONS'];
} else {
	// 0 - nothing
	// 1-5 food
	// 6-10 fuel
	// 11-15 metals
	// 16-17 weapons
	
	$randomValue = rand(0, 17);
	$searchAmount = 0;
	
	
	if ($randomValue == 0) { // found nothing
		$sql = "UPDATE player SET actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['NOTHING_FOUND'];
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 6) { // found food
		$type = rand(1, 10);
		$searchAmount = round($foodArray[$type] * (0.8 - rand(0, 4)/10));
		
		$sql = "UPDATE player SET food = food + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $lang['FOOD_FOUND_' . $type] . " (" . $searchAmount . " " . $lang['FOOD_SMALL'] . ")";

			$food = $food + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 11) { // found fuel
		$type = rand(1, 5);	
		$searchAmount = round($fuelArray[$type] * (0.8 - rand(0, 4)/10));
		
		$sql = "UPDATE player SET fuel = fuel + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $lang['FUEL_FOUND_' . $type] . " (" . $searchAmount . " " . $lang['FUEL_SMALL'] . ")";

			$fuel = $fuel + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 16) { // found metal
		$type = rand(1, 10);	
		$searchAmount = round($metalsArray[$type] * (0.8 - rand(0, 4)/10));
		
		$sql = "UPDATE player SET metal = metal + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $lang['METALS_FOUND_' . $type] . " (" . $searchAmount . " " . $lang['METALS_SMALL'] . ")";

			$metal = $metal + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} else { // found weapons
		$type = rand(1, 5);	
		$searchAmount = round($weaponArray[$type] * (0.8 - rand(0, 4)/10));
		
		$sql = "UPDATE player SET weapons = weapons + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $lang['WEAPONS_FOUND_' . $type] . " (" . $searchAmount . " " . $lang['WEAPONS_SMALL'] . ")";

			$weapons = $weapons + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}		
	}
	
}

$_SESSION['actions'] = $actions;
$_SESSION['health'] = $health;
$_SESSION['fuel'] = $fuel;
$_SESSION['food'] = $food;
$_SESSION['metal'] = $metal;
$_SESSION['weapons'] = $weapons;
$_SESSION['message'] = $message;
			
$return_arr[] = array("actions" => $actions,
				"health" => $health,
				"fuel" => $fuel,
				"food" => $food,
				"metal" => $metal,
				"weapons" => $weapons,
				"message" => $message);
				
echo json_encode($return_arr);
?>