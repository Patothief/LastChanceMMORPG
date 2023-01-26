<?php include('config/db.php');

$query = "SELECT actions FROM users WHERE email = '" . $_SESSION['email'] . "'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
	
	$return_arr[] = array("actions" => $data['actions']);
				
	echo json_encode($return_arr);
}

?>