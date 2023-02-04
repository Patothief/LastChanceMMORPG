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

	<label class="pageLabel">Launch Pad</label>
	
	<div class="vertical-center">
		<label>Crew:</label>
		<table>
			<tr>
				<th>Name</th>
				<th>Role</th>
			</tr>		
		<?php
			$sql = "SELECT rocket.state AS state, rocket.build_progress as build_progress, rocket.player_creator_id as player_creator_id, rocket_type.metals_required as metals_required " .
					"FROM rocket JOIN rocket_type ON rocket.rocket_type_id = rocket_type.id WHERE rocket.id = " . $_SESSION['rocketId'];
			
			$run = $connection->query($sql);
			$row = $run->fetch_array();
			
			$state = $row['state'];
			$buildProgress = $row['build_progress'];
			$metalsRequired = $row['metals_required'];
			$playerCreatorId = $row['player_creator_id'];
			
			$sql = "SELECT id, playername, rocket_role FROM player WHERE rocket_id = " . $_SESSION['rocketId'];
			$run = $connection->query($sql);

			while ($row = $run->fetch_array()) :
				echo "<tr>";
				if ($row['id'] == $playerCreatorId) {
					echo "<td class='creator'>" . $row['playername'] . "</td>";
				} else {
					echo "<td>" . $row['playername'] . "</td>";
				}
				
				if ($row['rocket_role'] == 4) {
					echo "<td>Commander</td>";
				} else if ($row['rocket_role'] == 3) {
					echo "<td>Pilot</td>";
				} else if ($row['rocket_role'] == 2) {
					echo "<td>Engineer</td>";
				} else if ($row['rocket_role'] == 1) {
					echo "<td>Participant</td>";
				} else {	// error state
					echo "<td>Unasigned</td>";
				}
				
				echo "</tr>";
			endwhile;	
			echo "</table>";
			echo "<br/><br/>";
			

			
			if ($state == 0) { // building
				$buildPrice = min(10, $metalsRequired - $buildProgress);
				
				echo "Build progress: " . $buildProgress . "/" . $metalsRequired;
				echo "<br/><br/>";
				echo "<div id='image1'>";
				echo "<img id='image2' src='images/rocket.png'/>";
				echo "</div>";
				echo "<button onClick = 'buildRocket(" . $buildPrice . ")' class='btn btn-outline-primary btn-lg btn-block'>Build (" . $buildPrice . " metals)</button>";
				echo "<label>You have " . $_SESSION['metal'] . " metals.</label>";
				
				
			} else if ($state == 1) { // loading
				echo "Loading rocket";
			}
		?>
		
		<br/>
		<label id="labelMessage"></label>
		
		<br/><br/>
		<button onClick = "abandonRocket()" class='btn btn-outline-primary btn-lg btn-block'>Abandon launch pad</button>
		<br/>

	</div>
	
    <div id="chatbox" class="vertical-center">
		<div class="inner_div" id="chatlist">
		</div>
		<div class="input_area">
			<input id="message" placeholder="<?php echo $lang['TYPE_MESSAGE']; ?>"/>
			<button onClick = 'addChatLine($("#message").val())' class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['SEND']; ?></button> 
		</div>
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
//240 -> 570


var request = $.ajax({
	url: 'launchPadData.php',
	type: 'get',
	data: { 
		buildProgressPercentage: true
	}
});

request.done( function ( data ) {
	insetPercentage = 100 - data*100;
	console.log(insetPercentage);
	$("#image2").css('clip-path', 'inset(' + insetPercentage + '% 0px 0px 0px)');
});
	
function abandonRocket() {
  	var request = $.ajax({
   		url: 'launchPadData.php',
   		type: 'get',
		data: { 
			abandonRocket: true
		}
	});
 	
 	request.done( function ( data ) {
		window.location.href = "launchPadController.php";
 	});
}

function buildRocket(buildPrice) {
	
	if (buildPrice > <?php echo $_SESSION['metal'] ?>) {
		$("#labelMessage").text("You do not have enough metals!");
		return;
	}
	
	  var request = $.ajax({
   		url: 'launchPadData.php',
   		type: 'get',
		data: { 
			buildRocket: true,
			buildPrice: buildPrice
		}
	});
 	
 	request.done( function ( data ) {
		window.location.href = "launchPadJoined.php";
 	});
}

function addChatLine(message) {
	$("#message").val("");
	
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
			rocketId: <?php echo $_SESSION['rocketId'] ?>,
			message: message
		}
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
		
		var element = document.getElementById("chatlist");
		element.scrollTop = element.scrollHeight;
 	});
}

function refreshChat() {
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
			rocketId: <?php echo $_SESSION['rocketId'] ?>
		}		
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
 	});
}

setInterval(refreshChat, 5000);
refreshChat();

</script>