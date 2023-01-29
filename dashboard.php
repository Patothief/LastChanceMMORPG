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

<body onload="addChatLine()">
	<div class="languagebar">
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

    <div class="vertical-center">
		<p class="card-text">
			<label><?php echo $lang['STORY']; ?></label>
		</p>

		<label><?php echo $lang['WARNING']; ?></label>
	</div>

	<br/>

    <div class="vertical-center">
		<p class="card-text"><?php echo $lang['PLAYER']; ?>
			<label><?php echo $_SESSION['playername']; ?></label>
		</p>
		
		<p class="card-text"><?php echo $lang['ACTIONS']; ?>
			<label id="labelActions"><?php echo $_SESSION['actions'] ?></label>
		</p>
		
		<br/>
		
		<p class="card-text"><?php echo $lang['FUEL']; ?>
			<label id="labelFuel"><?php echo $_SESSION['fuel'] ?></label>
		</p>

		<br/>
		
		<p class="card-text"><?php echo $lang['FOOD']; ?>
			<label id="labelFood"><?php echo $_SESSION['food'] ?></label>
		</p>
		
		<br/><br/>

		<button onClick = 'search()' class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['SEARCH']; ?></button> 

		<br/>

		<label id="labelMessage"><?php if (isset($_SESSION['message'])) echo $_SESSION['message']; ?></label>
	</div>

	<br/>

    <div id="chatbox" class="vertical-center">
		<div class="inner_div" id="chatlist">
		</div>
		<div class="input_area">
			<input id="message" placeholder="<?php echo $lang['TYPE_MESSAGE']; ?>"/>
			<button onClick = 'addChatLine($("#message").val())' class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['SEND']; ?></button> 
		</div>
	</div>
	
	<br/><br/><br/>
	<br/><br/><br/>
	<br/><br/><br/>
	<br/><br/><br/>

    <div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="logout.php">Log out</a>
	</div>

	<br/>

</body>

</html>

<script>
function search() {

  	var request = $.ajax({
   		url: 'search.php',
   		type: 'get',
   		dataType: 'json'
 	});
 	
 	request.done( function ( data ) {
		console.log(data);
		
		if (data.length > 0) {
			var actions = data[0].actions;
			var fuel = data[0].fuel;
			var food = data[0].food;
			var message = data[0].message;
			
			$('#labelActions').text(actions);
			$('#labelFuel').text(fuel);
			$('#labelFood').text(food);
			$('#labelMessage').text(message);
		}
 	});
}

function addChatLine(message) {
	$("#message").val("");
	
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
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
   		type: 'get'
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
 	});

}

setInterval(refreshChat, 5000);

</script>