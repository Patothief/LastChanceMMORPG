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
	<div class="topbar">
		<label><?php echo $_SESSION['playername']; ?></label>
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

	<label class="pageLabel"><?php echo $lang['LAUNCH_PAD']?></label>
	
	<div class="vertical-center">
		<label><?php echo $lang['LAUNCH_PAD_NOT_JOINED']; ?></label>
		<br/><br/>
		<label><?php echo $lang['LAUNCH_PAD_PROJECTS']; ?></label>
		<br/>
		<table>
			<tr>
				<th><?php echo $lang['NAME']; ?></th><th><?php echo $lang['TYPE']; ?></th><th><?php echo $lang['OWNER']; ?></th><th><?php echo $lang['ACTION']; ?></th>
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
				echo "<button onClick = 'joinRocket(" . $rocketId . ")' class='btn btn-outline-primary btn-block'>" . $lang['JOIN'] . "</button>";
				echo "</td>";
				echo "</tr>";
			endwhile;				
			?>
		</table>
		<br/><br/>
		<a class='btn btn-outline-primary btn-lg btn-block' href='createLaunchPad.php'><?php echo $lang['CREATE_LAUNCH_PAD']; ?></a>
	</div>

	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="shelter.php"><?php echo $lang['SHELTER']; ?></a>
	</div>
	
	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="wilderness.php"><?php echo $lang['WILDERNESS']; ?></a>
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
   		url: 'launchPadData.php',
   		type: 'get',
		data: { 
			joinRocketId: joinRocketId
		}
	});
 	
 	request.done( function ( data ) {
		window.location.href = "launchPadController.php";
 	});
}

</script>