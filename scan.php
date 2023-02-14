<?php 

include 'config/db.php';
include_once 'language.php';

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
$foundPlanet = false;
$habitability = 0;


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
	// 0-10 - nothing
	// 11-20 - weapons
	// 21-30 - planet

	$actions = $actions - 1;
	
	$randomValue = rand(0, 30);
	$searchAmount = 0;
	
	
	if ($randomValue < 11) { // found nothing
		$sql = "UPDATE player SET actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		if ($connection->query($sql) === TRUE) {
			$message = $lang['NOTHING_FOUND'];
			$actions = $actions - 1;
		} else {
			$message = "Error updating record: " . $connection->error;
		}
	} else if ($randomValue < 21) { // found weapons
		$type = rand(1, 5);	
		$searchAmount = round($weaponArray[$type] * (0.8 - rand(0, 4)/10));
		
		$sql = "UPDATE player SET actions = actions - 1 WHERE id = '" . $_SESSION['playerId'] . "'";

		$connection->query($sql);
		$message = $lang['FOUND'] . $lang['WEAPONS_FOUND_' . $type] . " (" . $searchAmount . " " . $lang['WEAPONS_SMALL'] . ")";

		$weapons = $weapons + $searchAmount;
		
		$sql = "UPDATE rocket SET weapons = weapons + " . $searchAmount . " WHERE id = '" . $_SESSION['rocketId'] . "'";
		$connection->query($sql);
	} else if ($randomValue < 31) { // found planet

		$type = rand(1, 1000);	
		$habitability = number_format((float)70 + log10($type)*10, 2, '.', '');

		$message = $lang['FOUND'] . "planet (Habitability: " . $habitability . ")";
		
		$foundPlanet = true;
	}
}

$_SESSION['actions'] = $actions;
$_SESSION['message'] = $message;
			
$return_arr[] = array(
	"message" => $message, 
	"foundPlanet" => $foundPlanet,
	"habitability" => $habitability
);
				
echo json_encode($return_arr);
?>