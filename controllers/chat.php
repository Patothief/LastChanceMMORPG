<?php

include('../config/db.php');

if (isset($_GET['message'])) {
	
	// Escape user inputs for security
	$un = $_SESSION['id'];
	$m  = mysqli_real_escape_string($connection, $_GET['message']);
	//date_default_timezone_set('Asia/Kolkata');
	$ts = date('d-m-y H:m');

	// Attempt insert query execution
	$sql = "INSERT INTO chat (player_id, message) VALUES ($un, '$m')";

	if(!mysqli_query($connection, $sql)) {
		echo "ERROR: Message not sent!!!";
	}
}

$sql = "SELECT * FROM (SELECT chat.player_id, chat.message, chat.time_created, users.playername FROM chat JOIN users ON chat.player_id = users.id ORDER BY chat.time_created DESC LIMIT 15) AS sub ORDER BY time_created ASC";
$run = $connection->query($sql);

while ($row = $run->fetch_array()) :
	if ($row['player_id']!=$_SESSION['id']) {
		$css_class = "item_left";
	} else {
		$css_class = "item_right";
	}

	echo "<div class='message_info " . $css_class . "'>";
	echo $row['time_created'];
	echo "</div>";
	echo "<div class='message " . $css_class . "'>";
	echo $row['playername'] . ": " . $row['message'];
	echo "</div>";
endwhile;

?>