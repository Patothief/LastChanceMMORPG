<?php 
include 'config/db.php';
include_once 'language.php';

if (isset($_GET['rocketDetailsDiv'])) {
	$content = 'This is flight controll to major Tom';

	$return_arr[] = array("content" => $content);
				
	echo json_encode($return_arr);
}