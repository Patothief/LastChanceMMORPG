<?php

include 'config/db.php';

if (isset($_GET['message'])) {
	$m  = mysqli_real_escape_string($connection, $_GET['message']);

	$sql = "INSERT INTO chat (player_id, rocket_id, message) VALUES (" . $_SESSION['playerId'] . ", " .  $_GET['rocketId'] . ", '$m')";

	if(!mysqli_query($connection, $sql)) {
		echo "ERROR: Message not sent!!!";
	}
}

$sql = "SELECT * FROM (SELECT chat.player_id, chat.message, chat.time_created, player.playername FROM chat JOIN player ON chat.player_id = player.id " .
		"WHERE chat.rocket_id = " . $_GET['rocketId'] . " " .
		"ORDER BY chat.time_created DESC LIMIT 15) AS sub ORDER BY time_created ASC";
$run = $connection->query($sql);

while ($row = $run->fetch_array()) :
	if ($row['player_id']!=$_SESSION['playerId']) {
		$css_class = "item_left";
	} else {
		$css_class = "item_right";
	}

	$time = strtotime($row['time_created']);
	$formattedTime = date("F jS, g:i a", $time);

	echo "<div class='message_info " . $css_class . "'>";
	echo $formattedTime;
	echo "</div>";
	echo "<div class='message " . $css_class . "'>";
	echo $row['playername'] . ": " . $row['message'];
	echo "</div>";
endwhile;

?>