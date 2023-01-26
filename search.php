<?php 

include('config/db.php');
include_once 'controllers/language.php';

$query = "SELECT actions, fuel, food FROM users WHERE email = '" . $_SESSION['email'] . "'";
$result = mysqli_query($connection, $query);

$actions = 0;
$fuel = 0;
$food = 0;
$message = '';

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    
	$actions = $data['actions'];
	$fuel = $data['fuel'];
	$food = $data['food'];
} else {
    $message = "No data found";
}

if ($actions <= 0) {
	$message = $lang['NO_MORE_ACTIONS'];
} else {
	$randomValue = rand(0, 15);
	$searchAmount = 0;
	
	if ($randomValue == 0) { // found nothing
		$message = $lang['NOTHING_FOUND'];
	} elseif ($randomValue < 11) { // found food
		$searchAmount = $randomValue;
		
		$sql = "UPDATE users SET food = food + " . $searchAmount . ", actions = actions - 1 WHERE email = '" . $_SESSION['email'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['FOOD_FOUND'];

			$food = $food + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 14) { // found fuel
		$searchAmount = $randomValue;
		
		$sql = "UPDATE users SET fuel = fuel + " . $searchAmount . ", actions = actions - 1 WHERE email = '" . $_SESSION['email'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['FUEL_FOUND'];

			$fuel = $fuel + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} elseif ($randomValue < 15) { // found a loot of food
		$searchAmount = 100;
		
		$sql = "UPDATE users SET food = food + " . $searchAmount . ", actions = actions - 1 WHERE email = '" . $_SESSION['email'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['FOUND'] . $searchAmount . $lang['FOOD_FOUND'];

			$fuel = $fuel + $searchAmount;
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} else { // found a loot of fuel
		$searchAmount = 100;
		
		$sql = "UPDATE users SET fuel = fuel + " . $searchAmount . ", actions = actions - 1 WHERE email = '" . $_SESSION['email'] . "'";

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
$_SESSION['fuel'] = $fuel;
$_SESSION['food'] = $food;
$_SESSION['message'] = $message;
			
$return_arr[] = array("actions" => $actions,
				"fuel" => $fuel,
				"food" => $food,
				"message" => $message);
				
echo json_encode($return_arr);
?>