<?php

include 'config/db.php';

$sql = "SELECT * FROM game_state";
$run = $connection->query($sql);
$row = $run->fetch_array();


$return_arr[] = array("state" => $row['state'],
				"next_date" => $row['next_date']);
				
echo json_encode($return_arr);

?>