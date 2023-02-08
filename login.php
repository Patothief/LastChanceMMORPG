<?php
   
    // Database connection
    include 'config/db.php';
	include_once 'language.php';
	
    global $playernameErr, $passwordErr;
	
	$playernameErr = $passwordErr = "";
	
	$playerNameRegexp = "/^[a-zA-Z0-9_.-]{5,15}$/";
	$passwordRegexp = "/^[a-zA-Z0-9_.*#$%&!-]{6,20}$/";
	
    if(isset($_POST['login'])) {
        $playername_signin   = $_POST['playername_signin'];
        $password_signin     = $_POST['password_signin'];

		if (!preg_match($playerNameRegexp, $playername_signin)) {
			$playernameErr = '<div class="alert alert-danger">' . $lang['PLAYER_NAME_REGEXP_ERROR'] . '</div>';
		} else if (!preg_match($passwordRegexp, $password_signin)) {
			$passwordErr = '<div class="alert alert-danger">' . $lang['PASSWORD_REGEXP_ERROR'] . '</div>';
		} else {
			$sql = "SELECT * FROM player WHERE playername = '{$playername_signin}' ";
			$query = mysqli_query($connection, $sql);

			if (!$query){
			   die("SQL query failed: " . mysqli_error($connection));
			}
			
            if (mysqli_num_rows($query) <= 0) {
				
				// create new player
                $password_hash = password_hash($password_signin, PASSWORD_BCRYPT);
				
				// Query
				$sql = "INSERT INTO player (playername, password, max_actions, max_health, actions, health) 
					VALUES ('{$playername_signin}', '{$password_hash}', 30, 100, 30, 100)";
				
				// Create mysql query
				$sqlQuery = mysqli_query($connection, $sql);
				
				if(!$sqlQuery){
					die("MySQL query failed!" . mysqli_error($connection));
				} 
				
				$sql = "SELECT * FROM player WHERE playername = '{$playername_signin}' ";
				$query = mysqli_query($connection, $sql);
				$row = mysqli_fetch_array($query);
				
				assignSessionVariables($row);

				header("Location: shelter.php");
            } else {
				$row = mysqli_fetch_array($query);
				
				if (password_verify($password_signin, $row['password'])) {
					assignSessionVariables($row);
					
					header("Location: shelter.php");
				} else {
					$passwordErr = '<div class="alert alert-danger">' . $lang['PASSWORD_IS_INCORRECT'] . '</div>';			
				}
			}
        }
    }
	
	function assignSessionVariables($row) {
		$_SESSION['playerId'] = $row['id'];
		$_SESSION['playername'] = $row['playername'];
		$_SESSION['rocketId'] = $row['rocket_id'];
		$_SESSION['actions'] = $row['actions'];
		$_SESSION['health'] = $row['health'];
		$_SESSION['fuel'] = $row['fuel'];
		$_SESSION['food'] = $row['food'];
		$_SESSION['metal'] = $row['metal'];
		$_SESSION['weapons'] = $row['weapons'];
	}

?>    