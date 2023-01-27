<?php

include('config/db.php');

if (isset($_POST['submit'])) {

	// Escape user inputs for security
	$un = $_SESSION['id'];
	$m  = mysqli_real_escape_string($connection, $_REQUEST['message']);
	//date_default_timezone_set('Asia/Kolkata');
	$ts = date('d-m-y H:m');

	// Attempt insert query execution
	$sql = "INSERT INTO chat (player_id, message, time) VALUES ($un, '$m', '$ts')";
	
	if(!mysqli_query($connection, $sql)) {
		echo "ERROR: Message not sent!!!";
	}
}
?>