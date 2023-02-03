<?php 
include 'config/db.php';
include_once 'language.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Last Chance MMORPG</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="languagebar">
		<label><?php echo $_SESSION['playername']; ?></label>
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

	<label class="pageLabel">Rocket</label>
	
	<div class="vertical-center">
		<label>You have not joined any rocket project. Join existing project or create a new one.</label>
		<br/><br/>
		<label>Rocket projects:</label>
		<br/>
		<table>
			<tr>
				<th>Name</th><th>Type</th><th>Owner</th><th>Action</th>
			</tr>
			<?php
			$sql = "SELECT rocket.id AS rocketId, rocket.name AS rocketName, rocket_type.name AS rocketTypeName, player.playername AS playerName " .
				"FROM rocket JOIN rocket_type ON rocket.rocket_type_id = rocket_type.id JOIN player on rocket.player_creator_id = player.id";
			$run = $connection->query($sql);

			while ($row = $run->fetch_array()) :
				$rocketId = $row['rocketId'];
				
				echo "<tr>";
				echo "<td>" . $row['rocketName'] . "</td>";
				echo "<td>" . $row['rocketTypeName'] . "</td>";
				echo "<td>" . $row['playerName'] . "</td>";
				echo "<td>";
				echo "<button onClick = 'joinRocket(" . $rocketId . ")' class='btn btn-outline-primary btn-lg btn-block'>Join</button>";
				echo "</td>";
				echo "</tr>";
			endwhile;				
			?>
		</table>
		<br/><br/>
		<a class='btn btn-outline-primary btn-lg btn-block' href='createRocket.php'>Create rocket</a>
	</div>

	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="shelter.php">Shelter</a>
	</div>
	
	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="wilderness.php">Wilderness</a>
	</div>

	<br/><br/><br/>
	<br/><br/><br/>
	<br/><br/><br/>

    <div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="logout.php">Log out</a>
	</div>

</body>

</html>

<script>

function joinRocket(joinRocketId) {
  	var request = $.ajax({
   		url: 'rocketData.php',
   		type: 'get',
		data: { 
			joinRocketId: joinRocketId
		}
	});
 	
 	request.done( function ( data ) {
		window.location.href = "rocketController.php";
 	});
}

</script>