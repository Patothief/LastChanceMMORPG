<?php 

include 'config/db.php';
include_once 'language.php';

$query = "SELECT actions, health, fuel, food, metal FROM player WHERE id = '" . $_SESSION['playerId'] . "'";
$result = mysqli_query($connection, $query);

$actions = 0;
$fuel = 0;
$food = 0;
$metal = 0;
$message = '';

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    
	$actions = $data['actions'];
	$health = $data['health'];	
	$fuel = $data['fuel'];
	$food = $data['food'];
	$metal = $data['metal'];
} else {
    $message = "No data found";
}

if ($actions <= 0) {
	$message = $lang['NO_MORE_ACTIONS'];
} else {
	$randomValue = rand(0, 20);
	$searchAmount = 0;
	
	if ($randomValue == 0) { // found nothing
		$sql = "UPDATE player SET actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['NOTHING_FOUND'];
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 11) { // found food
		$searchAmount = $randomValue;
		
		$sql = "UPDATE player SET food = food + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['FOOD_FOUND'];

			$food = $food + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 14) { // found fuel
		$searchAmount = $randomValue;
		
		$sql = "UPDATE player SET fuel = fuel + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['FUEL_FOUND'];

			$fuel = $fuel + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 18) { // found metal
		$searchAmount = $randomValue;
		
		$sql = "UPDATE player SET metal = metal + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['METAL_FOUND'];

			$metal = $metal + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 20) { // found a loot of food
		$searchAmount = 100;
		
		$sql = "UPDATE player SET food = food + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['FOOD_FOUND'];

			$fuel = $fuel + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} else { // found a loot of fuel
		$searchAmount = 100;
		
		$sql = "UPDATE player SET fuel = fuel + " . $searchAmount . ", actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['FUEL_FOUND'];

			$fuel = $fuel + $searchAmount;
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
$_SESSION['message'] = $message;
			
$return_arr[] = array("actions" => $actions,
				"health" => $health,
				"fuel" => $fuel,
				"food" => $food,
				"metal" => $metal,
				"message" => $message);
				
echo json_encode($return_arr);
?>