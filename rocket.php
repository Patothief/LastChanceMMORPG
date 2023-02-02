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
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

	<label class="pageLabel">Rocket</label>
	
	   <div class="vertical-center">
		<p class="card-text"><?php echo $lang['PLAYER']; ?>
			<label><?php echo $_SESSION['playername']; ?></label>
		</p>
		
		<p class="card-text" id="rocketList"/>
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

var rocketId;

function readRocketData() {
  	var request = $.ajax({
   		url: 'rocketData.php',
   		type: 'get',
   		dataType: 'json'
	});
 	
 	request.done( function ( data ) {
		if (data.length > 0) {
			var htmlContent = data[0].htmlContent;
			rocketId = data[0].rocketId;


			$("#rocketList").html(htmlContent);
			
			//console.log("rocket id: " + rocketId);
			if (rocketId > 0) {
				$("#chatbox").show();
			} else {
				$("#chatbox").hide();
			}
		}
 	});
}

readRocketData();

function joinRocket(joinRocketId) {
  	var request = $.ajax({
   		url: 'rocketData.php',
   		type: 'get',
   		dataType: 'json',
		data: { 
			joinRocketId: joinRocketId
		}
	});
 	
 	request.done( function ( data ) {
		if (data.length > 0) {
			var htmlContent = data[0].htmlContent;
			rocketId = data[0].rocketId;
			
			$("#rocketList").html(htmlContent);
			$("#chatbox").show();
		}
 	});
}

function createRocket() {
  	var request = $.ajax({
   		url: 'rocketData.php',
   		type: 'get',
   		dataType: 'json',
		data: { 
			createRocket: true
		}
	});
 	
 	request.done( function ( data ) {
		if (data.length > 0) {
			var htmlContent = data[0].htmlContent;
			rocketId = data[0].rocketId;
			
			$("#rocketList").html(htmlContent);
			$("#chatbox").show();
		}
 	});
}

function addChatLine(message) {
	$("#message").val("");
	
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
			rocketId: rocketId,
			message: message
		}
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
		
		var element = document.getElementById("chatlist");
		element.scrollTop = element.scrollHeight;
 	});
}

setInterval(function refreshChat() {
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
			rocketId: rocketId
		}		
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
 	});
}, 5000);

</script>